<?php
include 'config.php';

$update = file_get_contents('php://input');
$update = json_decode($update, TRUE);

//messaggi mandati dall'utente
$update_id = $update['update_id'];

$message_id = $update['message']['message_id'];
//dati persona che manda
$chat_id_from = $update['message']['from']['id'];
$is_bot_from = $update['message']['from']['is_bot']; //da vedere come esce il risultato//
$first_name_from = $update['message']['from']['first_name'];
$username_from = $update['message']['from']['username'];
$language_code_from = $update['message']['from']['language_code'];
//dati chat che manda
$chat_id = $update['message']['chat']['id'];
$chat_first_name = $update['message']['chat']['first_name'];
$chat_username = $update['message']['chat']['username'];
$chat_username = $update['message']['chat']['type']; //private //group
$chat_title = $update['message']['chat']["title"]; //titolo chat
$chat_all_members_are_administrators = $update['message']['chat']["all_members_are_administrators"]; //all_members_are_administrators true or flase

$date = $update['message']['date']; //data in formato internet
//message text
$text = $update['message']['text'];//testo del messaggio
//audio
$voice_duration = $update['message']['voice']['duration'];//audio mandato dall'utente durata sec
$voice_mime_type = $update['message']['voice']['mime_type']; //audio normale: "mime_type": "audio\/ogg"  per mp3 "mime_type": "audio\/mp3"
$voice_title = $update['message']['voice']['title']; //titolo brano
$voice_performer = $update['message']['voice']['performer']; //autori
$voice_file_id = $update['message']['voice']['file_id'];
$voice_file_size = $update['message']['voice']['file_size'];
//photo
$photo_0_file_id = $update['message']['photo'][0]['file_id']; //id foto qualità peggiore
$photo_0_file_size = $update['message']['photo'][0]['file_size'];
$photo_0_width = $update['message']['photo'][0]['width'];
$photo_0_height= $update['message']['photo'][0]['height'];
$photo_1_file_id = $update['message']['photo'][1]['file_id']; //id foto qualità mah
$photo_1_file_size = $update['message']['photo'][1]['file_size'];
$photo_1_width = $update['message']['photo'][1]['width'];
$photo_1_height= $update['message']['photo'][1]['height'];
$photo_2_file_id = $update['message']['photo'][2]['file_id']; //id foto qualità meh
$photo_2_file_size = $update['message']['photo'][2]['file_size'];
$photo_2_width = $update['message']['photo'][2]['width'];
$photo_2_height= $update['message']['photo'][2]['height'];
$photo_3_file_id = $update['message']['photo'][3]['file_id']; //id foto qualità migliore
$photo_3_file_size = $update['message']['photo'][3]['file_size'];
$photo_3_width = $update['message']['photo'][3]['width'];
$photo_3_height= $update['message']['photo'][3]['height'];
//video
$video_duration = $update['message']['video']['duration'];//sec
$video_width = $update['message']['video']['width'];
$video_height = $update['message']['video']['height'];
$video_mime_type = $update['message']['video']['mime_type']; //"mime_type": "video\/mp4",
$video_thumb_file_id = $update['message']['video']['thumb']['file_id'];//id video
$video_thumb_file_size = $update['message']['video']['thumb']['file_size'];//dimensione originale video
$video_thumb_width = $update['message']['video']['thumb']['width'];
$video_thumb_height = $update['message']['video']['thumb']['height'];

//sticker
$sticker_width = $update['message']['sticker']['width'];
$sticker_height = $update['message']['sticker']['height'];
$sticker_emoji = $update['message']['sticker']['emoji']; //codice emoji
$sticker_set_name = $update['message']['sticker']['set_name']; //nome emoji
$sticker_thumb_file_id = $update['message']['sticker']['thumb']['file_id']; //id del pacchetto degli sticker
$sticker_thumb_file_size = $update['message']['sticker']['thumb']['file_size'];
$sticker_thumb_width = $update['message']['sticker']['thumb']['width'];
$sticker_thumb_height = $update['message']['sticker']['thumb']['height'];

$file_id = $update['message']['file_id'];//id del file mandato
$file_size = $update['message']['file_size'];

$entities_offset = $update['message']['entities'][0]['offset'];
$entities_length = $update['message']['entities'][0]['length']; //lunghezza testo
$entities_type = $update['message']['entities'][0]['type']; //verifica che tipo di messaggio è es: bot_command

$location_latitude = $update['message']["location"]["latitude"];
$location_longitude = $update['message']["location"]["longitude"];

//inline_query (quando digiti @nomebot_bot e ti si apre la ricerca)
$inline_query_id = $update['inline_query']['id'];
$inline_query_from_id = $update['inline_query']['from']["id"]; //sender
$inline_query_location = $update['inline_query']['location'];
$inline_query_query = $update['inline_query']['query']; //text
$inline_query_offset = $update['inline_query']['offset']; //Offset of the results to be returned, can be controlled by the bot
  
//calback_query per tastiere inline sotto i messaggi del bot e relativi messaggi inviati dal bot stesso
$callback_ID_from = $update["callback_query"]["from"]["id"];
$callback_data = $update["callback_query"]["data"];

//mi manda la chat come la vede il $botToken
$agg = json_encode($update,JSON_PRETTY_PRINT);

//database peronale di ogni utente
    $file = "data/" . $chat_id_from . ".json";
    $file = file_get_contents($file, 'r');
    $json = json_decode($file, TRUE);

    $chatId_json = $json['ChatId'];
    $Is_bot_json = $json['Is_bot'];
        //Status
        $grade_json = $json["Status"]["Grade"];
        $level_json = $json["Status"]["Level"];
    $Fisrst_name_json = $json['Fisrst_name'];
    $Username_json = $json['Username'];
    $Language_code_json = $json['Language_code'];
    $Data_registation_json = $json['Data_registation'];
    $Operation_status_json = $json['Operation_status'];
        //Last_command
        $command_json = $json['Last_command']["Command"];
    $RSS_feed_json = $json['RSS_feed'];
        //weather
        $Weather_units_json = $json['Weather']['Units'];
        $Weather_language_json = $json['Weather']['Language'];
        //Coordinate
        $Latitude_json = $json['Coordinate']["Latitude"];
        $Longitude_json = $json['Coordinate']["Longitude"];


///////////////////////////
//    ____   ____ _______ 
//   |  _ \ / __ \__   __|
//   | |_) | |  | | | |   
//   |  _ <| |  | | | |   
//   | |_) | |__| | | |   
//   |____/ \____/  |_|   
//                        
//        

//controllo se uso un comando
if ($entities_type == 'bot_command'){
  sendMessage($chat_id,"$first_name_from, hai mandato un comando.");
  //sendMessage($chat_id, $agg);
  if($text == '/start'){
    start();
    register_user();
    register_users_json($chat_id, $is_bot_from, $first_name_from, $username_from, $language_code_from, $text);
    register_user_json($chat_id, $is_bot_from, $first_name_from, $username_from, $language_code_from, $text);
  }//if
  elseif ($text == '/home'){
    home($chat_id);
  }//elseif
  elseif($text =='/profile'){
    profile_user_show($chat_id);
  }//elseif
  elseif($text =='/weather'){
   weather($chat_id);
  }//elseif
}//if


///////////////////
//CALLBACK QUERRY//
///////////////////
if (isset($update["callback_query"])){ //se esiste
  callback($callback_ID_from, $callback_data);
}//if callback

/////////////////
//INLINE QUERRY//
/////////////////
if (isset($update["inline_query"])){ //se esiste
  inline_query($inline_query_id, $inline_query_from_id, $inline_query_query);
}//if inlinequery

////////////////
//LAST COMMAND//
////////////////
// Questo test sarà TRUE pertanto sarà visualizzato il testo.
if (isset($entities_type)) {
}//if
else {
  if($command_json == 'Current weather'){
    current_weather($chat_id, $text, $location_latitude, $location_longitude, $owmapi, $Weather_units_json, $weather_language_json);
  }//if
  elseif($command_json == '5 day weather forecast'){
    fiveday_3hour_forecast($chat_id, $text, $location_latitude, $location_longitude, $owmapi, $Weather_units_json, $weather_language_json);
  }//elseif
}//else

///////////////
//DEVELOPMENT//
///////////////
// Questo test sarà TRUE pertanto sarà visualizzato il testo.
if (isset($entities_type)) {
}//if
else {
  if($Operation_status_json == 'development'){
    //getinfo
    sendMessage($chat_id, $agg);
  }//if
  //getMe
  elseif ($text == '/getMe') {
  getMe($chat_id);
  }//elseif
  //controllo se c'è uno sticker
  elseif ($sticker_emoji != '') {
    sendMessage($chat_id, "$sticker_set_name $sticker_thumb_file_id");
  }//elseif
  //controllo se c'è una foto okay
  elseif ($photo_3_file_id != '') {
    sendPhoto($chat_id, "$photo_3_file_id");
  }//elseif
  //controllo se c'è una audio okay
  elseif ($voice_duration != '') {
    sendMessage($chat_id, "$voice_file_id");
    sendAudio($chat_id, "$voice_file_id");
    sendVoice($chat_id, "$voice_file_id");
  }//elseif
  //controllo se c'è un video no okay
  elseif ($video_duration != '') {
    sendMessage($chat_id, "$video_thumb_file_id");
    sendVideo($chat_id, "$video_thumb_file_id");
    sendDocument($chat_id, "$video_thumb_file_id");
  }//elseif
}//else

//https://github.com/alexandrussmc/telegramtutorial/blob/master/index.php
?>
