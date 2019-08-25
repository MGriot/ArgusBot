<?php
function data_link(){
  $arr = array('science' => 'https://science.sciencemag.org/rss/current.xml',
                'nationalgeographic' => 'http://www.nationalgeographic.it/rss/news/rss2.0.xml');
  $data = json_encode($arr);
  sendMessage($chat_id_from,$data);
}
function xml(){
  // nome di host
  $host = "localhost";
  // username dell'utente in connessione
  $user = "ArgusBot";
  // password dell'utente
  $password = "usuG663ecrMT";
  //assegno un nome al database che viene creato
  $database = 'my_argusbot';
  // nome table
  $table = "user_data"; // non la uso ma almeno mi ricordo

  $n = 10;

  // stringa di connessione al DBMS
  //comando di accesso
  mysql_connect("$host", "$user", "$password");
  mysql_select_db("$database");

  $result = mysql_query("SELECT * FROM $table WHERE `RSS_feed` LIKE 'run'");

  while($row = mysql_fetch_assoc($result)){

    $chatId = $row['ChatId'];
    $Username = $row['Username'];

    // nome di host
    $host = "localhost";
    // username dell'utente in connessione
    $user = "ArgusBot";
    // password dell'utente
    $password = "usuG663ecrMT";
    //assegno un nome al database che viene creato
    $database = 'my_argusbot';
    // nome table
    $table2 = "$Username"; // non la uso ma almeno mi ricordo

    // stringa di connessione al DBMS
    //comando di accesso
    mysql_connect("$host", "$user", "$password");
    mysql_select_db("$database");

    $result2 = mysql_query("SELECT * FROM $table2");

    while($row2 = mysql_fetch_assoc($result2)){
      $dati = $row2['Link'];
      $description2 = $row2['Description'];

      sendMessage($chatId, $description2);

      $xml = simplexml_load_file($dati);

    $title = $xml->channel->item[0]->title; //nationalgeographic a
    $title2 = $xml->channel->item[1]->title; //inran c
    $title1 = $xml->item[0]->title; //le scienze/science b
    // prova  $titlea = $xml->channel->item[1]->title;
    // prova  sendMessage($chatId, $titlea);
      //controllo quale deve attivarsi
      //sendMessage($chat_id_from,'a: '.$title);
      //sendMessage($chat_id_from,'b: '.$title1);

      //a
      if($title != ''){
        for ($i=0; $i < $n ; $i++) {//for nationalgeographic
          $title = $xml->channel->item[$i]->title;
          $link = $xml->channel->item[$i]->link;
          $author = $xml->channel->item[$i]->author;
          $category = $xml->channel->item[$i]->category;
          $pubDate = $xml->channel->item[$i]->pubDate;
          sendMessage($chatId,"<b>$category</b>\n<b>$title</b>\n$author\n$pubDate\n$link");
        }//for
      }//if
      //c
      if($title2 != ''){
        for ($i=1; $i < $n ; $i++) {//for nationalgeographic
          $title = $xml->channel->item[$i]->title;
          $link = $xml->channel->item[$i]->link;
          $author = $xml->channel->item[$i]->author;
          $category = $xml->channel->item[$i]->category;
          $pubDate = $xml->channel->item[$i]->pubDate;
          sendMessage($chatId,"<b>$category</b>\n<b>$title</b>\n$author\n$pubDate\n$link");
        }//for
      }//if
      //b
      elseif ($title1 != '') {//for science
        for ($i=0; $i < $n ; $i++) {
          $title = $xml->item[$i]->title;
          $link = $xml->item[$i]->link;
          //$date = $xml->item[$i]->dc:date;
          //$publisher = $xml->item[$i]->dc:publisher;
          //$subject = $xml->item[$i]->dc:subject;
          //$titledc = $xml->item[$i]->dc:title;
          sendMessage($chatId,"<b>$title</b>\n$link");
          //sendMessage($chat_id_from,$date);
          //sendMessage($chat_id_from,$publisher);
          //sendMessage($chat_id_from,$subject);
          //sendMessage($chat_id_from,$titledc);
        }//for
      }//elseif
    }//while 2
  }//while
}//function
?>
