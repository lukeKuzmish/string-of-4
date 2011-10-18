<?php
class String_Of_4 {

    private $player1_color;
    private $player2_color;
    private $gameboard = array();
    private $next_move;
    private $status = "new";
    private $game_id;

    var $side_length;


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


    public function output_json() {
      // TODO
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
      for ($i = 0; $i < $side_length; $i++) {
        for ($j = 0; $j < $side_length; $j++) {
          $row[$j] = 0;
        }
        $this->gameboard[$i] = $row;
      }


      // decide which player goes first
      $this->next_move = mt_rand(1,2);


      // set status
      $this->status = "awaiting";

      
      // create string id
      $this->game_id = generate_random_string();
      
    } // __construct


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

?>
