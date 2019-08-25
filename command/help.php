<?php
function help_command(){
  if($command_for_help == '/current_weather'){
    sendMessage($chat_id_from,"send yuor location like this format:\n/current_weather \"your location\", optionally after the city you can put the state of the city in this way /current_weather \"your location,XX\"");
  }
  else {
    sendMessage($chat_id_from,'help command typed wrong');
  }
}
 ?>
