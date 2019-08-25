<?php
function register_user_json($chat_id_from, $is_bot_from, $first_name_from, $username_from, $language_code_from, $text){
    //file unico di utenti
    $file = "data/users.json";
     
     //$is_bot_from = (string)$is_bot_from;
     $date = date('m/d/Y h:i:s a', time());
     $Operation_status = 'normal_use';
     $Last_command = "";
     $RSS_feed = "arrest";
     $Weather_units = "standard";
     $latitude_register = '/';
     $longitude_register = '/';
     
         $array = array(
                  "Id" => "1",
                        "user" => array(
                        "ChatId" => "$chat_id_from",
                        "Is_bot" => "$is_bot_from",
                        "Fisrst_name" => "$first_name_from",
                        "Username" => "$username_from",
                        "Language_code" => "language_code_from",
                        "Data_registation" => "$date"
                        )
                     ); //array

    file_put_contents($file, json_encode($array));

    switch (json_last_error()) {
            case JSON_ERROR_NONE:
                sendMessage($chat_id_from,'<b>- No errors -</b>');
            break;
            case JSON_ERROR_DEPTH:
                sendMessage($chat_id_from,'<b>- Maximum stack depth exceeded -</b>');
            break;
            case JSON_ERROR_STATE_MISMATCH:
                sendMessage($chat_id_from,'<b>- Underflow or the modes mismatch -</b>');
            break;
            case JSON_ERROR_CTRL_CHAR:
                sendMessage($chat_id_from,'<b>- Unexpected control character found -</b>');
            break;
            case JSON_ERROR_SYNTAX:
                sendMessage($chat_id_from,'<b>- Syntax error, malformed JSON -</b>');
            break;
            case JSON_ERROR_UTF8:
                sendMessage($chat_id_from,'<b>- Malformed UTF -</b> 8 characters, possibly incorrectly encoded');
            break;
            default:
                sendMessage($chat_id_from,'<b>- Unknown error -</b>');
            break;
        }//switch
}

function register_users_json($chat_id_from, $is_bot_from, $first_name_from, $username_from, $language_code_from, $text){
    global $admin_list ;

    //file unico per ogni singolo utente
    $file = "data/" . $chat_id_from . ".json";
     
     //$is_bot_from = (string)$is_bot_from;
     $date = date('m/d/Y h:i:s a', time());
     $Operation_status = 'normal_use';
     //verifico se è un admin nel codice
    if(in_array($chat_id_from, $admin_list)){
     $grade = "admin";
     $level = "1";
    }
    else{
     $grade = "normal user";
     $level = "3";
    }
     $Last_command = "";
     $RSS_feed = "arrest";
     $Weather_units = "standard";
     $Language = "en";
     $latitude_register = '/';
     $longitude_register = '/';
     
         $array = array(
                        "ChatId" => "$chat_id_from",
                        "Is_bot" => "$is_bot_from",
                        "Status" => array(
                            "Grade" => "$grade",
                            "Level" => "$level"
                        ),
                        "Fisrst_name" => "$first_name_from",
                        "Username" => "$username_from",
                        "Language_code" => "language_code_from",
                        "Data_registation" => "$date",
                        "Operation_status" => "$Operation_status",
                        "Last_command" =>array(
                            "Command" => "$Last_command"
                        ),
                        "RSS_feed" => "$RSS_feed",
                        "Weather" => array(
                            "Units" => "$Weather_units",
                            "Language" => "$Language"
                        ),
                        "Coordinate" => array(
                            "Latitude" => "$latitude_register",
                            "Longitude" => "$longitude_register"
                        )
                 );//array

    file_put_contents($file, json_encode($array));

    switch (json_last_error()) {
            case JSON_ERROR_NONE:
                sendMessage($chat_id_from,'<b>- No errors -</b>');
            break;
            case JSON_ERROR_DEPTH:
                sendMessage($chat_id_from,'<b>- Maximum stack depth exceeded -</b>');
            break;
            case JSON_ERROR_STATE_MISMATCH:
                sendMessage($chat_id_from,'<b>- Underflow or the modes mismatch -</b>');
            break;
            case JSON_ERROR_CTRL_CHAR:
                sendMessage($chat_id_from,'<b>- Unexpected control character found -</b>');
            break;
            case JSON_ERROR_SYNTAX:
                sendMessage($chat_id_from,'<b>- Syntax error, malformed JSON -</b>');
            break;
            case JSON_ERROR_UTF8:
                sendMessage($chat_id_from,'<b>- Malformed UTF -</b> 8 characters, possibly incorrectly encoded');
            break;
            default:
                sendMessage($chat_id_from,'<b>- Unknown error -</b>');
            break;
        }//switch
}
?>