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
    private $player1_color;
    private $player2_color;
    private $gameboard = array();
    private $next_move;
    private $status = "new";
    private $game_id;


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

        $col = array();
        // setup gameboard multidimensional array
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


    
   private function generate_random_string($size = 12) {

      $available_chars = "0123456789abcdefghijklmnopqrstuvwxyz";
      $to_return = "";
      
      for ($i = 0; $i < $size; $i++) {
        $random_char_index = mt_rand(0, strlen($available_chars) - 1);
        $to_return = $to_return . $available_chars[$random_char_index];
      }

      // TODO check for existence of this string already in DB/flat file
      return $to_return;
    }




    public function return_json() {
    
      header('Content-type: text/json');
      header('Content-type: application/json');
      $to_return = array(
        "player1_color" => $this->player1_color,
        "player2_color" => $this->player2_color, 
        "gameboard"     => $this->gameboard,
        "next_move"     => $this->next_move,
        "status"        => $this->status,
        "game_id"       => $this->game_id        
      );
      print json_encode($to_return);
      die;
    }



    public function load_from_file($filename) {

      $file_loc = GAMESTATE_DIR . $filename . ".json";
      $fp = fopen($file_loc,"r") or die("file doesn't exist");
      $contents = fread($fp, filesize($file_loc));
      fclose($fp);

      $arr_vars = json_decode($contents,true);    // true makes array associative
      $this->player1_color = $arr_vars["player1_color"];
      $this->player2_color = $arr_vars["player2_color"];
      $this->gameboard = $arr_vars["gameboard"];
      $this->next_move = $arr_vars["next_move"];
      $this->status = $arr_vars["status"];
      $this->game_id = $arr_vars["game_id"];        

    }


    public function make_move($col) {

      // check for bad input
      if (($col >= SIDE_LENGTH) || ($col < 0)) {
        return false;
      }

      
      if ($this->next_move == 1) {
        $drop_color = $this->player1_color;
      }
      else {
        $drop_color = $this->player2_color;
      }

      foreach ($this->gameboard[$col] as &$slot) {
        if ($slot == 0) {
          $slot = $drop_color;
          return true;
        }
      // TODO
      // tried adding to a column that is full
      // RETURN_AN_ERROR_WITH_JSON
      // disable this in the UI too
      }


    }
    
}



$my_game = new String_Of_4("r","trial");
if ($my_game->make_move(1) == true) {
  // TODO successfully dropped, now switch players
}
$my_game->return_json();
?>
