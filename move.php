<?php

  include_once("string_of_4.oop.php");


  if (isset($_GET["game_id"]) && isset($_GET["column"])) {
    $game_id = htmlspecialchars($_GET["game_id"]);
    $column = intval($_GET["column"]);
  }
  
  $my_game = new String_Of_4('',$game_id);
  $my_game->make_move($column);

?>
