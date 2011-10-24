<?php

  // AJAX request --
  //            color_choice = player1's color choice  (either 'r' or 'b')
  //            on result, make sense of the URL

  
  include_once("string_of_4.oop.php");
  if (isset($_GET["color_choice"])) {
    $color_choice = htmlspecialchars($_GET["color_choice"]);
  }
  
  $my_game = new String_Of_4($color_choice);
?>
