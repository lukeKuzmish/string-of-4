<?php

require_once("config.php");

class String_Of_4 {

/*

  Board setup
      0   1   2   3   4   5   6   7       gameboard[column][row]
 7   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [B]      A  =  gameboard[0][0]
 6   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [ ]      B  =  gameboard[7][7]
 5   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [ ]      C  =  gameboard[3][1]
 4   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [ ]
 3   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [ ]
 2   [ ] [ ] [ ] [ ] [ ] [ ] [ ] [ ]
 1   [ ] [ ] [ ] [C] [ ] [ ] [ ] [ ]
 0   [A] [ ] [ ] [ ] [ ] [ ] [ ] [ ]
*/



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  private fields
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    private $player1_color;
    private $player2_color;
    private $gameboard = array();
    private $next_move;
    private $status = "new";
    private $game_id;
    private $error;
    private $suppress_output = false;

/*
statuses

  'awaiting'              --  waiting for other player to join
  'wating for player #'   --  player # has next move

*/




/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  public methods
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */ 
    function __construct($player1_color_choice, $filename="") {

      if ($filename != "") {
        $this->load_from_file($filename);
      }


      else {
        // set colors
        $this->player1_color = $player1_color_choice;
        
        switch ($player1_color_choice) {
          case "r":
            $this->player2_color = "b";
            break;
          case "b":
            $this->player2_color = "r";
            break;
          default:
            // error
        }

        // setup gameboard multidimensional array
        $col = array();
        
        for ($i = 0; $i < SIDE_LENGTH; $i++) {
          for ($j = 0; $j < SIDE_LENGTH; $j++) {
            $col[$j] = 0;
          }
          $this->gameboard[$i] = $col;
        }


        // decide which player goes first
        $this->next_move = mt_rand(1,2);

        // set status
        $this->status = "awaiting";
        
        // create string id
        $this->game_id = $this->generate_random_string(12);

      }
      
    } // __construct



    public function __destruct() {
      $this->write_to_file();
      if ($this->suppress_output == false) {
        $this->show_json();
      }
    }


    public function make_move($col) {

      // check for bad input
      if (($col >= SIDE_LENGTH) || ($col < 0)) {
        $this->add_error("incorrect column");
        return false;
      }

      
      if ($this->next_move == 1) {
        $drop_color = $this->player1_color;
      }
      else {
        $drop_color = $this->player2_color;
      }


      foreach ($this->gameboard[$col] as &$slot) {
        if ($slot === 0) {
          $slot = $drop_color;
          $this->update_player();
          return true;
        }
      }

      $this->add_error("slot full");
      return false;

    }



    public function player2_join() {
      if ($this->status == "awaiting") {
        $this->status = "waiting for player " . $this->next_move;
      }
      else {
        $this->add_error("can't join game");
      }

      //$this->suppress_output = true;
    }



    public function show_json() {
    
      // set headers
      header('Content-type: text/json');
      header('Content-type: application/json');
      print $this->get_json();
      die;
    }




/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
  private methods
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */    
   private function generate_random_string($size = 12) {

      $available_chars = "0123456789abcdefghijklmnopqrstuvwxyz";
      $to_return = "";

      // grab $size characters from $available_chars to make a unique game id
      for ($i = 0; $i < $size; $i++) {
        $random_char_index = mt_rand(0, strlen($available_chars) - 1);
        $to_return = $to_return . $available_chars[$random_char_index];
      }

      // if the string is already a saved game state file, restart the process
      if (file_exists(GAMESTATE_DIR . $to_return . ".json")) {
        $to_return = $this->generate_random_string($size);
      }
      
      return $to_return;
    }



    private function add_error($error) {
      $this->error = $error;
    }


// returns string of JSON encoded game data
    private function get_json() {
      // set variables to return
      $to_return = array(
        "player1_color" => $this->player1_color,
        "player2_color" => $this->player2_color, 
        "gameboard"     => $this->gameboard,
        "next_move"     => $this->next_move,
        "status"        => $this->status,
        "game_id"       => $this->game_id        
      );
      
      // if there is an error string, set that too
      if (isset($this->error)) { $to_return["error"] = $this->error; }

      return json_encode($to_return);
    }

    
    
    private function load_from_file($filename) {

      $file_loc = ROOT_DIR . GAMESTATE_DIR . $filename . ".json";
      $contents = file_get_contents($file_loc) or $this->add_error("no such game id");

      $arr_vars = json_decode($contents,true);    // true makes array associative
      $this->player1_color = $arr_vars["player1_color"];
      $this->player2_color = $arr_vars["player2_color"];
      $this->gameboard = $arr_vars["gameboard"];
      $this->next_move = $arr_vars["next_move"];
      $this->status = $arr_vars["status"];
      $this->game_id = $arr_vars["game_id"];
    }



    private function write_to_file() {
      $file_loc = ROOT_DIR . GAMESTATE_DIR . $this->game_id . ".json";
      $fp = fopen($file_loc,"w");
      fwrite($fp, $this->get_json());
      fclose($fp);
    }

    

    private function update_player() {

      switch ($this->next_move) {
        case (1):
          $this->next_move = 2;
          break;
        case (2):
          $this->next_move = 1;
      }

      $this->status = "waiting for player " . $this->next_move;
    }


} // class String_Of_4

?>
