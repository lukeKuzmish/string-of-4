<?php

// game properties
$colors = array("red", "black");
$side_length = 8;
$game_state = array(
    "player1_color"   =>  "",
    "player2_color"   =>  "",
    "gameboard"       =>  array(),
    "next_move"       =>  "",
    "status"          =>  "new",
    "game_id"         =>  ""
);



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    generate_random_string(size)

    parameter:  $size

    returns:    string of random lowercase letters and numbers


    Create a string of acceptable characters, then select randomly til size is
    met.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function generate_random_string($size) {
  $available_chars = "0123456789abcdefghijklmnopqrstuvwxyz";
  $to_return = "";
  
  for ($i = 0; $i < $size; $i++) {
    $random_char_index = mt_rand(0, strlen($available_chars) - 1);
    $to_return = $to_return . $available_chars[$random_char_index];
  }

  // TODO check for existence of this string already in DB/flat file
  return $to_return;
}




/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    return_current_game()
    
    parameter:  none

    returns:    none


    Return JSON file with game state then die.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function return_current_game() {
  global $game_state, $side_length;
  
  header('Content-type: text/json');
  header('Content-type: application/json');
  print json_encode($game_state);
  die;
}



/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
    initialize_game($player1_color_choice)

    parameter:  $player1_color_choice
                "red" or "black"

    returns:   none


    Initialize game_state attributes.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function initialize_game($player1_color_choice) {

  global $game_state, $side_length;


  // set colors
  switch ($player1_color_choice) {
    case "red":
      $player2_color_choice = "black";
      break;
    case "black":
      $player2_color_choice = "red";
      break;
    default:
      // error
  }
  $game_state["player1_color"] = $player1_color_choice;
  $game_state["player2_color"] = $player2_color_choice;


  // setup gameboard multidimensional array
  for ($i = 0; $i < $side_length; $i++) {
    for ($j = 0; $j < $side_length; $j++) {
      $row[$j] = 0;
    }
    $game_state["gameboard"][$i] = $row;
  }


  // decide who takes first move
  // NOTE: use 1 and 2, not traditional 0, 1
  $game_state["next_move"] = mt_rand(1,2);

  
  // bump status to "awaiting (player2)"
  $game_state["status"] = "awaiting";


  $game_state["game_id"] = generate_random_string(12);

}

initialize_game("red");
return_current_game();

/*

TODO:
  initialize needs to both create a text file for game state (or save to DB) as well as verify that it does not already exist.

  -- join
      parameters: game_id
      returns:    game state information
      function:   set game_state status to "playing"

  -- save_state
      function:  write to flat file/DB    
*/
?>
