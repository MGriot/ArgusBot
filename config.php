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
$name_bot = "ArgusBot";
$username_bot = "@ArgusBot_bot";

//admin
  $admin_list = array(
  "407638940"//mio Id chat
  //,"altro id)
  );
  //aggiungi altri eventuali admin

//access al database
  //Parametri di connessione al database
  $host_database = "localhost"; //per altervista va bene, per phpmyadmin (altervista) Host: localhost (può essere lasciato vuoto)
  $user_database = "ArgusBot"; //nome con cui registri il database, per phpmyadmin (altervista) Username: nomeutente (può essere lasciato vuoto)
  $password_database = "usuG663ecrMT"; //password di acesso al database, per phpmyadmin (altervista) Password: password (può essere lasciato vuoto)
  $database = 'my_argusbot'; //nome del database, per phpmyadmin (altervista) Database: my_nomeutente

//tabelle Database
  $tabella = 'User_log'; //tabella in cui conservo i dati di log

//accesso alla mail del bot
/*
account GMAIL:
nome: Argus
cognome: Bot
e-mail: ArgusBot.Gri@gmail.com
password: Argus97Bot42

numero di telefono: matteo.griot@gmail.com
indirizzo mail di recupero: matteo.griot@gmail.com
data nascita: 04-02-1997
sesso: Bot (altro)
*/

$botToken = "738796639:AAGhiMIbMghT681pk_q2BtZWzbNiW-MbwBU";
$website = "https://api.telegram.org/bot".$botToken;

///////////////////
//OPENWEATHER API//
///////////////////
/*
OpenWeather API
Dear Customer!


Thank you for subscribing to Free OpenWeather API!

API key:
- Your API key is 726c8c734a25a6b7d44f9f5392badfc1
- Within the next couple of hours, it will be activated and ready to use
- You can later create more API keys on your account page
- Please, always use your API key in each API call

Endpoint:
- Please, use the endpoint api.openweathermap.org for your API calls
- Example of API call:
api.openweathermap.org/data/2.5/weather?q=London,uk&APPID=726c8c734a25a6b7d44f9f5392badfc1

Useful links:
- API documentation https://openweathermap.org/api
- Details of your plan https://openweathermap.org/price
- Please, note that 16-days daily forecast and History API are not available for Free subscribers


Blog
Support center & FAQ
Contact us info@openweathermap.org.



Best wishes,
OpenWeather team
*/
$owmapi ="726c8c734a25a6b7d44f9f5392badfc1";
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
$custom_search_api ="AIzaSyCqLSRxDOElJ5FeM-D6-Z_3GUMOQhaDLsk";
 ?>
