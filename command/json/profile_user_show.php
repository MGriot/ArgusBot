<?php
function profile_user_show($chat_id){
    global $chat_id_from;
    global $chatId_json; //
    global $Is_bot_json; //
        //Status
        global $grade_json; //
        global $level_json; //
    global $Fisrst_name_json; //
    global $Username_json; //
    global $Language_code_json; //
    global $Data_registation_json; //
    global $Operation_status_json; //
        //Last_command
        global $command_json; //
    global $RSS_feed_json; //
        //Weather
        global $Weather_units_json; //
        global $Weather_language_json;//
        //Coordinate
        global $Latitude_json; //
        global $Longitude_json; //

      $keyboard = '[{"text":"Operation status","callback_data":"development"},{"text":"Normal Use","callback_data":"normal_use"}]';
        
sendMessage($chat_id,
"📎 Profile 📎
👤First name: $Fisrst_name_json
👤Username: $Username_json
🆔Chat ID: $chatId_json
🧟‍♂️Robot: $Is_bot_json
🏳️ Language: $Language_code_json
📅 Data registration: $Data_registation_json

📝Status for bot📝
    Grade: $grade_json
    Level: $level_json
    Last command: $command_json

📨RSS: $RSS_feed_json

📈Weather 
    Units: $Weather_units_json
    Language: $Weather_language_json

📝Operation status: $Operation_status_json

📍Coordinate📍
    Latitude: $Latitude_json
    Longitude: $Longitude_json
", $keyboard, "inline");
}

?>