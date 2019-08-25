<?php
//manda un file
function getMe($chatId){
  $url = $GLOBALS[website]."/getMe";
  file_get_contents($url);
}

//manda un messaggio
function sendMessage($chatId,$text,$keyboard,$tipe){
  if(isset($keyboard)){
    if($tipe == "fisica"){
      $keyboard_ = '&reply_markup={"keyboard":['.urlencode($keyboard).'],"resize_keyboard":true}';
    }
    else {
      $keyboard_ = '&reply_markup={"inline_keyboard":['.urlencode($keyboard).'],"resize_keyboard":true}';
    }
  }
  $url = $GLOBALS[website]."/sendMessage?chat_id=$chatId&parse_mode=HTML&text=".urlencode($text).$keyboard_;
  file_get_contents($url);
}

//manda una foto
function sendPhoto($chatId,$photo){
  $url = $GLOBALS[website]."/sendPhoto?chat_id=$chatId&photo=".urlencode($photo);
  file_get_contents($url);
}

//manda un voice audio
function sendVoice($chatId,$voice){
  $url = $GLOBALS[website]."/sendVoice?chat_id=$chatId&voice=".urlencode($voice);
  file_get_contents($url);
}

//manda un voice audio
function sendAudio($chatId,$audio){
  $url = $GLOBALS[website]."/sendAudio?chat_id=$chatId&audio=".urlencode($audio);
  file_get_contents($url);
}

//manda un video
function sendVideo($chatId,$video){
  $url = $GLOBALS[website]."/sendVideo?chat_id=$chatId&video=".urlencode($video);
  file_get_contents($url);
}

//manda un file
function sendDocument($chatId,$document){
  $url = $GLOBALS[website]."/sendDocument?chat_id=$chatId&document=".urlencode($document);
  file_get_contents($url);
}

//inline_query
function answerInlineQuery($inline_query_id, $result){
  $result = json_encode($result);
  $url = $GLOBALS[website]."/answerInlineQuery?inline_query_id=".$inline_query_id."&results=".urlencode($result);
  file_get_contents($url);
}
//RESULT PER QUERY//
function result (){
  $result = array();
}
//article
function InlineQueryResultArticle (){
$array = array( "type" => "article",
          "id" => "0",
          "title" => "$title0",
          "input_message_content" => array("message_text" => "$title0 \n$description0 \n$link0", "parse_mode" => "HTML"),
          "description" => "$description0",
          "thumb_url" => "$link0");
}
 ?>
