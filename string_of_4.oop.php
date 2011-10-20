<?php

require_once("config.php");

class String_Of_4 {

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
        $row = array();

        
        // set colors
        $this->player1_color = $player1_color_choice;
        
        switch ($player1_color_choice) {
          case "red":
            $this->player2_color = "black";
            break;
          case "black":
            $this->player2_color = "red";
            break;
          default:
            // error
        }


        // setup gameboard multidimensional array
        for ($i = 0; $i < SIDE_LENGTH; $i++) {
          for ($j = 0; $j < SIDE_LENGTH; $j++) {
            $row[$j] = 0;
          }
          $this->gameboard[$i] = $row;
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

      $arr_vars = json_decode($contents,true);
      $this->player1_color = $arr_vars["player1_color"];
      $this->player2_color = $arr_vars["player2_color"];
      $this->gameboard = $arr_vars["gameboard"];
      $this->next_move = $arr_vars["next_move"];
      $this->status = $arr_vars["status"];
      $this->game_id = $arr_vars["game_id"];        

    }
    /*
$filename = "Somefile.txt"; //creating our file name
$filehandle = fopen($filename, "w"); //create a file and a file handle (description)
fwrite($filehandle, "Dummy data"); //writing into the created file. one more parameter available
fclose($filehandle); //finish the first file handle, no need to close it. just for demonstration

$fileread = fopen($filename, "r"); //create a second handle (which is the same like the first)
$filetext = fread($filename, filesize($filename)); //read *all* data from the file
fclose($flieread); //end handle
echo $filetext; //echo the file data

      */

      
  
}

$my_game = new String_Of_4("red","trial");
$my_game->return_json();
?>
