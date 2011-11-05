<?php

  include_once("string_of_4.oop.php");


  if (isset($_GET["game_id"])) {
    $game_id = htmlspecialchars($_GET["game_id"]);
  }
  
  $my_game = new String_Of_4('',$game_id);

?>
