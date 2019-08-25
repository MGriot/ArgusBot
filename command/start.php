<?php
function start(){
  global $chat_id_from;
  global $username;
  global $first_name;
  global $language_code;

  //creo il file generale dove tiene tutti gli utenti e mi indica un po' di info generali
  $file = "data/users.json";
  $date = date('m/d/Y h:i:s a', time());
  $array = array(
    "Id" => "1",
    "user" => array(
    "ChatId" => "$chat_id_from",
    "Username" => "$username",
    "Fisrst_name" => "$first_name",
    "language_code" => "$language_code",
    "Data" => "$date"
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
    }

//creo il singolo file dove tiene le impostazioni di un singolo utente
  $file = "data/". $chat_id_from .".json";
  $date = date('m/d/Y h:i:s a', time());
  $array = array(
    "ChatId" => "$chat_id_from",
    "Username" => "$username",
    "Fisrst_name" => "$first_name",
    "Language_code" => "$language_code",
    "Data" => "$date",
    "Status" => "status",
    "Command" =>array(
        "last_command" => "",
        "status_one" => "") 
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
    }

//devo fargli aggiungere le cose e non eliminare, adesso sostituisce completamente il file
//controllare se l'utente è già registrato -importante-
//mandare messaffi switch solo a admin (io)

//se il messagio inviato al bot è start allora manda il messaggio di benevenuto
sendMessage($chat_id_from,"Salve $nome!\nSono un bot appena creato, probabilmente all'inzio non avrò nessun comando ma se aspetterai un giorno farò cose strepitose!!\nPer visionare tutti i comandi usa /home");
}//function
//menu da aggiungere
?>
