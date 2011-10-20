<?php
class String_Of_4 {

    private $player1_color;
    private $player2_color;
    private $gameboard = array();
    private $next_move;
    private $status = "new";
    private $game_id;

    var $side_length;


   public function generate_random_string($size = 12) {
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
      // TODO
      header('Content-type: text/json');
      header('Content-type: application/json');
/*
$side_length = 8;
$game_state = array(
    "player1_color"   =>  "",
    "player2_color"   =>  "",
    "gameboard"       =>  array(),
    "next_move"       =>  "",
    "status"          =>  "new",
    "game_id"         =>  ""
);
*/

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





    function __construct($player1_color_choice, $_side_length = 8) {

      $row = array();
      $this->side_length = $_side_length;

      
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
      for ($i = 0; $i < $this->side_length; $i++) {
        for ($j = 0; $j < $this->side_length; $j++) {
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
      
    } // __construct

}

$my_game = new String_Of_4("red");
$my_game->return_json();
?>
