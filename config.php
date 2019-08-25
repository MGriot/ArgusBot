<?php
//funzioni telegram
  include 'command/telegram_function.php';
//command
  include 'command/start.php';
  include 'command/mysql/user_database.php'; //register_user get_user_data
  include 'command/json/user_database_json.php'; //register_user_json and register_users_json (registra singolo e foglio unico)
  include 'command/json/profile_user_show.php'; //mostra i file utente
  include 'command/home.php'; //home
  include 'command/weather.php'; //weather
    include 'command/weather/current_weather.php'; //current weather
    include 'command/weather/5day_3hour_forecast.php'; //5day_3hour_forecast
    include 'command/weather/weather_set_units.php'; //weather_set_unit
  include 'command/callback.php'; //callback
  include 'command/inline_query.php'; //inline_query
    include 'command/wikipedia.php';
//informazioni bot
$name_bot = "";
$username_bot = "";

//admin
  $admin_list = array(
  "chat_id del proprietario del bot"//mio Id chat
  //,"altro id)
  );
  //aggiungi altri eventuali admin

//access al database
  //Parametri di connessione al database
  $host_database = ""; //per altervista va bene, per phpmyadmin (altervista) Host: localhost (può essere lasciato vuoto)
  $user_database = ""; //nome con cui registri il database, per phpmyadmin (altervista) Username: nomeutente (può essere lasciato vuoto)
  $password_database = ""; //password di acesso al database, per phpmyadmin (altervista) Password: password (può essere lasciato vuoto)
  $database = ''; //nome del database, per phpmyadmin (altervista) Database: my_nomeutente

//tabelle Database

$botToken = "";
$website = "https://api.telegram.org/bot".$botToken;

///////////////////
//OPENWEATHER API//
///////////////////
/*
OpenWeather API
$owmapi ="";
//https://openweathermap.org/api
//https://agromonitoring.com/api

/////////////////
//WIKIPEDIA API//
/////////////////
$wiki_api = "https://en.wikipedia.org/w/api.php?";
// https://www.mediawiki.org/wiki/API:Main_page

//////////////
//GOOGLE API//
//////////////
//https://console.cloud.google.com/apis/library?pli=1
//https://developers.google.com/
//https://developers.google.com/custom-search/
$custom_search_api ="";
 ?>
