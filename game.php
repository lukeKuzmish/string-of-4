<?php

  include_once("string_of_4.oop.php");

  if isset($_GET["status"]) {
    $status = htmlspecialchars($_GET["status"]);
  }
  
  $my_game = new String_Of_4("r");


  
?>
