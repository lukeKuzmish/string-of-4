<?php

  include_once("string_of_4.oop.php");
  echo `whoami`;
  print $_SERVER['DOCUMENT_ROOT'];
  $my_game = new String_Of_4("r");
  if ($my_game->make_move(1) == true);
  if (isset($my_game->error)) { print "error is " . isset($my_game->error); }

?>
