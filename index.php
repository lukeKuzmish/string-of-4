<?php
function return_current_game($game_config) {
  header('Content-type: text/json');
  header('Content-type: application/json');
  print json_encode($game_config);
  die;
}

$some_json = array(
  "player1" => "red",
  "player2" => "black",
  "board"   => array(array(0,0,0,0), array(0,0,0,0), array(0,0,0,0), array(0,0,0,0)),
  "next_move" => "player1"
  );

return_current_game($some_json);


?>