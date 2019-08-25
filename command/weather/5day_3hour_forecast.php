<?php
function message_fiveday_3hour_forecast($callback_ID_from){
  //messaggio
  sendMessage($callback_ID_from, 'mandami il nome della città che ti interessa o \"nome città, stato\", oppure mandami la tua posizione, mandami le coordinate digitando \"lat ; lon\", o ancora il zip code (codice postale)');
}

function fiveday_3hour_forecast($chat_id, $text, $location_latitude, $location_longitude, $owmapi, $Weather_units_json, $weather_language_json){

if ($Weather_units_json == 'standard'){
$temperature_unit = 'K';
$pressure_unit = 'hPa';
$speed_unit = 'm/s';
$wind_direction_unit = '°';
$cloud_or_humidity_unit = '%';
$precipitation_unit = 'mm';
}//if
elseif($Weather_units_json == 'metric'){
$temperature_unit = '°C';
$pressure_unit = 'hPa';
$speed_unit = 'm/s';
$wind_direction_unit = '°';
$cloud_or_humidity_unit = '%';
$precipitation_unit = 'mm';
}//elseif
else{
$temperature_unit = 'F';
$pressure_unit = 'hPa';
$speed_unit = 'mph';
$wind_direction_unit = '°';
$cloud_or_humidity_unit = '%';
$precipitation_unit = 'mm';
}//else

  if($text != ''){//city id or name and city by geografial coordinates inseriti manualmente
    $text_exploded = explode(" ", $text);
    if($text_exploded[1] != ";"){
      if($text_exploded[1] == ""){
       $uri = "http://api.openweathermap.org/data/2.5/forecast?q=".$text."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
      }//if
      elseif($text_exploded[1] != ""){
        if(is_numeric($text_exploded[0])){
           $uri = "http://api.openweathermap.org/data/2.5/forecast?zip=".$text_exploded[0].$text_exploded[1]."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
        }
        else{
          $uri = "http://api.openweathermap.org/data/2.5/forecast?q=".$text_exploded[0].$text_exploded[1]."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
        }//else
      }//elseif
    }//if
    elseif($text_exploded[1] == ";" and $text_exploded[2] != ""){
      $lat = $text_exploded[0];
      $lon = $text_exploded[2];
      $uri = "http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$lon."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
    }//elseif
   
  }//if
  elseif($location_latitude != '' and $location_longitude != ''){//city by geografial coordinates  weather?lat={lat}&lon={lon} inseriti via gps
     $uri = "http://api.openweathermap.org/data/2.5/forecast?lat=".$location_latitude."&lon=".$location_longitude."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
  }//elseif

      //prendo i dati in uscita dall'uri
        $uri_meteo = file_get_contents($uri);
      //decodifico il risultato mamdato da $uri_meteo
        $clima = json_decode($uri_meteo, TRUE);

      //city
        $city_id = $clima['city']['id'];
        $city_name = $clima['city']['name'];
        $longitudine = $clima['city']['coord']['lon'];
        $latitudine = $clima['city']['coord']['lat'];
        $country = $clima['city']['country'];

      if ($city_id !=''){
          //bollettino meteo, raccoglie tutte le informazioni
          $bollettino_meteo_0 = ("City name: <b>$city_name</b>\nCountry: <b>$country</b>\nLongitudine: $longitudine\nLatitudine: $latitudine\nCity Id: $city_id\n$uri");

            sendMessage($chat_id,$bollettino_meteo_0);

            //0
              $temperatura0 = $clima['list'][0]['main']['temp'];
              $temp_min0 = $clima['list'][0]['main']['temp_min'];
              $temp_max0 = $clima['list'][0]['main']['temp_max'];
              $pressure0 = $clima['list'][0]['main']['pressure'];
              $sea_level0 = $clima['list'][0]['main']['sea_level'];
              $ground_level0 = $clima['list'][0]['main']['grnd_level'];
              $humidity0 = $clima['list'][0]['main']['humidity'];
              $internal_parameter0 = $clima['list'][0]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm0 = round($pressure0*0.00098692, 3);
            //weather list
              $weather_condition_id0 = $clima['list'][0]['weather'][0]['id'];
              $weather_condition_main0 = $clima['list'][0]['weather'][0]['main'];
              $weather_description0 = $clima['list'][0]['weather'][0]['description'];
              $weather_icon0x = $clima['list'][0]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon0x == "01d") {
                $weather_icon0 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon0x == "01n") {
                $weather_icon0 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon0x == "02d") {
                $weather_icon0 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon0x == "02n") {
                $weather_icon0 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon0x == "03d") {
                $weather_icon0 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon0x == "03n") {
                $weather_icon0 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon0x == "04d") {
                $weather_icon0 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon0x == "04n") {
                $weather_icon0 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon0x == "9d"){ //
                $weather_icon0 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon0x == "9n"){ //
                $weather_icon0 = "🌧";
              /*

              */
              }
              elseif ($weather_icon0x == "10d"){
                $weather_icon0 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon0x == "10n"){
                $weather_icon0 = "🌦";
              /*

              */
              }
              elseif($weather_icon0x == "11d"){
                $weather_icon0 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon0x == "11n"){
                $weather_icon0 = "⛈";
                /*

                */
              }
              elseif ($weather_icon0x == "13d") {
                $weather_icon0 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon0x == "13n") {
              $weather_icon0 = "🌨";
              /*

              */
              }
              elseif ($weather_icon0x == "50d") {
                $weather_icon0 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon0x == "50n") {
                $weather_icon0 = "🌫";
                /*

                */
              }
            //clouds
              $clouds0 = $clima['list'][0]['clouds']['all'];
            //wind
              $wind_speed0 = $clima['list'][0]['wind']['speed'];
              $wind_deg0 = $clima['list'][0]['wind']['deg'];
              $degre0 = (int)$wind_deg0;
            //direction
              if($degre0 == 0){
                $direction_wind0 = 'North';
              }
              elseif($degre0 > 0 && $degre0 <45){
                $direction_wind0 = 'North-NorthEast';
              }
              elseif ($degre0 == 45) {
                $direction_wind0 = 'North-East';
              }
              elseif ($degre0 > 45 && $degre0 <90){
                $direction_wind0 = 'East-NorthEast';
              }
              elseif ($degre0 == 90){
                $direction_wind0 = 'East';
              }
              elseif($degre0 > 90 && $degre0 <135){
                $direction_wind0 = 'East-SouthEast';
              }
              elseif($degre0 == 135){
                $direction_wind0 = 'South-East';
              }
              elseif($degre0 > 135 && $degre0 <180){
                $direction_wind0 = 'South-SouthEast';
              }
              elseif($degre0 == 180){
                $direction_wind0 = 'South';
              }
              elseif($degre0 > 180 && $degre0 <225){
                $direction_wind0 = 'West-SouthWest';
              }
              elseif($degre0 == 225){
                $direction_wind0 = 'South-West';
              }
              elseif($degre0 > 225 && $degre0 <270){
                $direction_wind0 = 'West-SouthWest';
              }
              elseif($degre0 == 270){
                $direction_wind0 = 'West';
              }
              elseif($degre0 > 270 && $degre0 <315){
                $direction_wind0 = 'West-NortWest';
              }
              elseif($degre0 == 315){
                $direction_wind0 = 'North-West';
              }
              elseif($degre0 > 315 && $degre0 <360){
                $direction_wind0 = 'North-NorthWest';
              }
              else{
                $direction_wind0 = 'North';
              }
            //sea
              if($wind_speed0 <= 0.3){
                $wind_force0 = 'Calm';
                $sea_force0 = 'Zero force';
              }
              elseif($wind_speed0 > 0.3 && $wind_speed0 < 1.5){
                $wind_force0 = 'Burr of wind';
                $sea_force0 = 'Force one';
              }
              elseif($wind_speed0 > 1.5 && $wind_speed0 < 3.3){
                $wind_force0 = 'Light breeze';
                $sea_force0 = 'Force two';
              }
              elseif($wind_speed0 > 3.3 && $wind_speed0 < 5.4){
                $wind_force0 = 'Breeze';
                $sea_force0 = 'Force two';
              }
              elseif($wind_speed0 > 5.4 && $wind_speed0 < 7.9){
                $wind_force0 = 'Livelly breeze';
                $sea_force0 = 'Force three';
              }
              elseif($wind_speed0 > 7.9 && $wind_speed0 < 10.7){
                $wind_force0 = 'Tense breeze';
                $sea_force0 = 'Force four';
              }
              elseif($wind_speed0 > 10.7 && $wind_speed0 < 13.8){
                $wind_force0 = 'Fresh wind';
                $sea_force0 = 'Force five';
              }
              elseif($wind_speed0 > 13.8 && $wind_speed0 < 17.1){
                $wind_force0 = 'Strong wind';
                $sea_force0 = 'Force six';
              }
              elseif($wind_speed0 > 17.1 && $wind_speed0 < 20.7){
                $wind_force0 = 'Moderate storm';
                $sea_force0 = 'Force seven';
              }
              elseif($wind_speed0 > 20.7 && $wind_speed0 < 24.4){
                $wind_force0 = 'Strong storm';
                $sea_force0 = 'Force eight';
              }
              elseif($wind_speed0 > 24.4 && $wind_speed0 < 28.4){
                $wind_force0 = 'Storm';
                $sea_force0 = 'Force nine';
              }
              elseif($wind_speed0 > 28.4 && $wind_speed0 < 32.6){
                $wind_force0 = 'Fortunale';
                $sea_force0 = 'Force ten';
              }
              elseif($wind_speed0 > 32.6){
                $wind_force0 = 'Hurricane';
                $sea_force0 = 'Force ten';
              }
              else{
                $wind_force0 = 'zero';
                $sea_force0 = 'zero';
              }
            //rain
              $rain0 = $clima['list'][0]['rain']['3h'];
            //snow
              $snow0 = $clima['list'][0]['snow']['3h'];
            //data/time of calculation
              $data_time_pre0 = $clima['list'][0]['dt_txt'];
              $data_time_confr0 = substr($data_time_pre0,0,11);
              $data_time0 = substr($data_time_pre0,11);

            //1
              $temperatura1 = $clima['list'][1]['main']['temp'];
              $temp_min1 = $clima['list'][1]['main']['temp_min'];
              $temp_max1 = $clima['list'][1]['main']['temp_max'];
              $pressure1 = $clima['list'][1]['main']['pressure'];
              $sea_level1 = $clima['list'][1]['main']['sea_level'];
              $ground_level1 = $clima['list'][1]['main']['grnd_level'];
              $humidity1 = $clima['list'][1]['main']['humidity'];
              $internal_parameter1 = $clima['list'][1]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm1 = round($pressure1*0.00098692, 3);
            //weather list
              $weather_condition_id1 = $clima['list'][1]['weather'][0]['id'];
              $weather_condition_main1 = $clima['list'][1]['weather'][0]['main'];
              $weather_description1 = $clima['list'][1]['weather'][0]['description'];
              $weather_icon1x = $clima['list'][1]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon1x == "01d") {
                $weather_icon1 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon1x == "01n") {
                $weather_icon1 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon1x == "02d") {
                $weather_icon1 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon1x == "02n") {
                $weather_icon1 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon1x == "03d") {
                $weather_icon1 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon1x == "03n") {
                $weather_icon1 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon1x == "04d") {
                $weather_icon1 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon1x == "04n") {
                $weather_icon1 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon1x == "9d"){ //
                $weather_icon1 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon1x == "9n"){ //
                $weather_icon1 = "🌧";
              /*

              */
              }
              elseif ($weather_icon1x == "10d"){
                $weather_icon1 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon1x == "10n"){
                $weather_icon1 = "🌦";
              /*

              */
              }
              elseif($weather_icon1x == "11d"){
                $weather_icon1 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon1x == "11n"){
                $weather_icon1 = "⛈";
                /*

                */
              }
              elseif ($weather_icon1x == "13d") {
                $weather_icon1 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon1x == "13n") {
              $weather_icon1 = "🌨";
              /*

              */
              }
              elseif ($weather_icon1x == "50d") {
                $weather_icon1 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon1x == "50n") {
                $weather_icon1 = "🌫";
                /*

                */
              }
            //clouds
              $clouds1 = $clima['list'][1]['clouds']['all'];
            //wind
              $wind_speed1 = $clima['list'][1]['wind']['speed'];
              $wind_deg1 = $clima['list'][1]['wind']['deg'];
              $degre1 = (int)$wind_deg1;
            //direction
              if($degre1 == 0){
                $direction_wind1 = 'North';
              }
              elseif($degre1 > 0 && $degre1 <45){
                $direction_wind1 = 'North-NorthEast';
              }
              elseif ($degre1 == 45) {
                $direction_wind1 = 'North-East';
              }
              elseif ($degre1 > 45 && $degre1 <90){
                $direction_wind1 = 'East-NorthEast';
              }
              elseif ($degre1 == 90){
                $direction_wind1 = 'East';
              }
              elseif($degre1 > 90 && $degre1 <135){
                $direction_wind1 = 'East-SouthEast';
              }
              elseif($degre1 == 135){
                $direction_wind1 = 'South-East';
              }
              elseif($degre1 > 135 && $degre1 <180){
                $direction_wind1 = 'South-SouthEast';
              }
              elseif($degre1 == 180){
                $direction_wind1 = 'South';
              }
              elseif($degre1 > 180 && $degre1 <225){
                $direction_wind1 = 'West-SouthWest';
              }
              elseif($degre1 == 225){
                $direction_wind1 = 'South-West';
              }
              elseif($degre1 > 225 && $degre1 <270){
                $direction_wind1 = 'West-SouthWest';
              }
              elseif($degre1 == 270){
                $direction_wind1 = 'West';
              }
              elseif($degre1 > 270 && $degre1 <315){
                $direction_wind1 = 'West-NortWest';
              }
              elseif($degre1 == 315){
                $direction_wind1 = 'North-West';
              }
              elseif($degre1 > 315 && $degre1 <360){
                $direction_wind1 = 'North-NorthWest';
              }
              else{
                $direction_wind1 = 'North';
              }
            //sea
              if($wind_speed1 <= 0.3){
                $wind_force1 = 'Calm';
                $sea_force1 = 'Zero force';
              }
              elseif($wind_speed1 > 0.3 && $wind_speed1 < 1.5){
                $wind_force1 = 'Burr of wind';
                $sea_force1 = 'Force one';
              }
              elseif($wind_speed1 > 1.5 && $wind_speed1 < 3.3){
                $wind_force1 = 'Light breeze';
                $sea_force1 = 'Force two';
              }
              elseif($wind_speed1 > 3.3 && $wind_speed1 < 5.4){
                $wind_force1 = 'Breeze';
                $sea_force1 = 'Force two';
              }
              elseif($wind_speed1 > 5.4 && $wind_speed1 < 7.9){
                $wind_force1 = 'Livelly breeze';
                $sea_force1 = 'Force three';
              }
              elseif($wind_speed1 > 7.9 && $wind_speed1 < 10.7){
                $wind_force1 = 'Tense breeze';
                $sea_force1 = 'Force four';
              }
              elseif($wind_speed1 > 10.7 && $wind_speed1 < 13.8){
                $wind_force1 = 'Fresh wind';
                $sea_force1 = 'Force five';
              }
              elseif($wind_speed1 > 13.8 && $wind_speed1 < 17.1){
                $wind_force1 = 'Strong wind';
                $sea_force1 = 'Force six';
              }
              elseif($wind_speed1 > 17.1 && $wind_speed1 < 20.7){
                $wind_force1 = 'Moderate storm';
                $sea_force1 = 'Force seven';
              }
              elseif($wind_speed1 > 20.7 && $wind_speed1 < 24.4){
                $wind_force1 = 'Strong storm';
                $sea_force1 = 'Force eight';
              }
              elseif($wind_speed1 > 24.4 && $wind_speed1 < 28.4){
                $wind_force1 = 'Storm';
                $sea_force1 = 'Force nine';
              }
              elseif($wind_speed1 > 28.4 && $wind_speed1 < 32.6){
                $wind_force1 = 'Fortunale';
                $sea_force1 = 'Force ten';
              }
              elseif($wind_speed1 > 32.6){
                $wind_force1 = 'Hurricane';
                $sea_force1 = 'Force ten';
              }
              else{
                $wind_force1 = 'zero';
                $sea_force1 = 'zero';
              }
            //rain
              $rain1 = $clima['list'][1]['rain']['3h'];
            //snow
              $snow1 = $clima['list'][1]['snow']['3h'];
            //data/time of calculation
              $data_time_pre1 = $clima['list'][1]['dt_txt'];
              $data_time_confr1 = substr($data_time_pre1,0,11);
              $data_time1 = substr($data_time_pre1,11);

            //2
              $temperatura2 = $clima['list'][2]['main']['temp'];
              $temp_min2 = $clima['list'][2]['main']['temp_min'];
              $temp_max2 = $clima['list'][2]['main']['temp_max'];
              $pressure2 = $clima['list'][2]['main']['pressure'];
              $sea_level2 = $clima['list'][2]['main']['sea_level'];
              $ground_level2 = $clima['list'][2]['main']['grnd_level'];
              $humidity2 = $clima['list'][2]['main']['humidity'];
              $internal_parameter2 = $clima['list'][2]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm2 = round($pressure2*0.00098692, 3);
            //weather list
              $weather_condition_id2 = $clima['list'][2]['weather'][0]['id'];
              $weather_condition_main2 = $clima['list'][2]['weather'][0]['main'];
              $weather_description2 = $clima['list'][2]['weather'][0]['description'];
              $weather_icon2x = $clima['list'][2]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon2x == "01d") {
                $weather_icon2 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon2x == "01n") {
                $weather_icon2 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon2x == "02d") {
                $weather_icon2 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon2x == "02n") {
                $weather_icon2 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon2x == "03d") {
                $weather_icon2 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon2x == "03n") {
                $weather_icon2 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon2x == "04d") {
                $weather_icon2 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon2x == "04n") {
                $weather_icon2 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon2x == "9d"){ //
                $weather_icon2 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon2x == "9n"){ //
                $weather_icon2 = "🌧";
              /*

              */
              }
              elseif ($weather_icon2x == "10d"){
                $weather_icon2 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon2x == "10n"){
                $weather_icon2 = "🌦";
              /*

              */
              }
              elseif($weather_icon2x == "11d"){
                $weather_icon2 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon2x == "11n"){
                $weather_icon2 = "⛈";
                /*

                */
              }
              elseif ($weather_icon2x == "13d") {
                $weather_icon2 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon2x == "13n") {
              $weather_icon2 = "🌨";
              /*

              */
              }
              elseif ($weather_icon2x == "50d") {
                $weather_icon2 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon2x == "50n") {
                $weather_icon2 = "🌫";
                /*

                */
              }
            //clouds
              $clouds2 = $clima['list'][2]['clouds']['all'];
            //wind
              $wind_speed2 = $clima['list'][2]['wind']['speed'];
              $wind_deg2 = $clima['list'][2]['wind']['deg'];
              $degre2 = (int)$wind_deg2;
            //direction
              if($degre2 == 0){
                $direction_wind2 = 'North';
              }
              elseif($degre2 > 0 && $degre2 <45){
                $direction_wind2 = 'North-NorthEast';
              }
              elseif ($degre2 == 45) {
                $direction_wind2 = 'North-East';
              }
              elseif ($degre2 > 45 && $degre2 <90){
                $direction_wind2 = 'East-NorthEast';
              }
              elseif ($degre2 == 90){
                $direction_wind2 = 'East';
              }
              elseif($degre2 > 90 && $degre2 <135){
                $direction_wind2 = 'East-SouthEast';
              }
              elseif($degre2 == 135){
                $direction_wind2 = 'South-East';
              }
              elseif($degre2 > 135 && $degre2 <180){
                $direction_wind2 = 'South-SouthEast';
              }
              elseif($degre2 == 180){
                $direction_wind2 = 'South';
              }
              elseif($degre2 > 180 && $degre2 <225){
                $direction_wind2 = 'West-SouthWest';
              }
              elseif($degre2 == 225){
                $direction_wind2 = 'South-West';
              }
              elseif($degre2 > 225 && $degre2 <270){
                $direction_wind2 = 'West-SouthWest';
              }
              elseif($degre2 == 270){
                $direction_wind2 = 'West';
              }
              elseif($degre2 > 270 && $degre2 <315){
                $direction_wind2 = 'West-NortWest';
              }
              elseif($degre2 == 315){
                $direction_wind2 = 'North-West';
              }
              elseif($degre2 > 315 && $degre2 <360){
                $direction_wind2 = 'North-NorthWest';
              }
              else{
                $direction_wind2 = 'North';
              }
            //sea
              if($wind_speed2 <= 0.3){
                $wind_force2 = 'Calm';
                $sea_force2 = 'Zero force';
              }
              elseif($wind_speed2 > 0.3 && $wind_speed2 < 1.5){
                $wind_force2 = 'Burr of wind';
                $sea_force2 = 'Force one';
              }
              elseif($wind_speed2 > 1.5 && $wind_speed2 < 3.3){
                $wind_force2 = 'Light breeze';
                $sea_force2 = 'Force two';
              }
              elseif($wind_speed2 > 3.3 && $wind_speed2 < 5.4){
                $wind_force2 = 'Breeze';
                $sea_force2 = 'Force two';
              }
              elseif($wind_speed2 > 5.4 && $wind_speed2 < 7.9){
                $wind_force2 = 'Livelly breeze';
                $sea_force2 = 'Force three';
              }
              elseif($wind_speed2 > 7.9 && $wind_speed2 < 10.7){
                $wind_force2 = 'Tense breeze';
                $sea_force2 = 'Force four';
              }
              elseif($wind_speed2 > 10.7 && $wind_speed2 < 13.8){
                $wind_force2 = 'Fresh wind';
                $sea_force2 = 'Force five';
              }
              elseif($wind_speed2 > 13.8 && $wind_speed2 < 17.1){
                $wind_force2 = 'Strong wind';
                $sea_force2 = 'Force six';
              }
              elseif($wind_speed2 > 17.1 && $wind_speed2 < 20.7){
                $wind_force2 = 'Moderate storm';
                $sea_force2 = 'Force seven';
              }
              elseif($wind_speed2 > 20.7 && $wind_speed2 < 24.4){
                $wind_force2 = 'Strong storm';
                $sea_force2 = 'Force eight';
              }
              elseif($wind_speed2 > 24.4 && $wind_speed2 < 28.4){
                $wind_force2 = 'Storm';
                $sea_force2 = 'Force nine';
              }
              elseif($wind_speed2 > 28.4 && $wind_speed2 < 32.6){
                $wind_force2 = 'Fortunale';
                $sea_force2 = 'Force ten';
              }
              elseif($wind_speed2 > 32.6){
                $wind_force2 = 'Hurricane';
                $sea_force2 = 'Force ten';
              }
              else{
                $wind_force2 = 'zero';
                $sea_force2 = 'zero';
              }
            //rain
              $rain2 = $clima['list'][2]['rain']['3h'];
            //snow
              $snow2 = $clima['list'][2]['snow']['3h'];
            //data/time of calculation
              $data_time_pre2 = $clima['list'][2]['dt_txt'];
              $data_time_confr2 = substr($data_time_pre2,0,11);
              $data_time2 = substr($data_time_pre2,11);

            //3
              $temperatura3 = $clima['list'][3]['main']['temp'];
              $temp_min3 = $clima['list'][3]['main']['temp_min'];
              $temp_max3 = $clima['list'][3]['main']['temp_max'];
              $pressure3 = $clima['list'][3]['main']['pressure'];
              $sea_level3 = $clima['list'][3]['main']['sea_level'];
              $ground_level3 = $clima['list'][3]['main']['grnd_level'];
              $humidity3 = $clima['list'][3]['main']['humidity'];
              $internal_parameter3 = $clima['list'][3]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm3 = round($pressure3*0.00098692, 3);
            //weather list
              $weather_condition_id3 = $clima['list'][3]['weather'][0]['id'];
              $weather_condition_main3 = $clima['list'][3]['weather'][0]['main'];
              $weather_description3 = $clima['list'][3]['weather'][0]['description'];
              $weather_icon3x = $clima['list'][3]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon3x == "01d") {
                $weather_icon3 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon3x == "01n") {
                $weather_icon3 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon3x == "02d") {
                $weather_icon3 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon3x == "02n") {
                $weather_icon3 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon3x == "03d") {
                $weather_icon3 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon3x == "03n") {
                $weather_icon3 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon3x == "04d") {
                $weather_icon3 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon3x == "04n") {
                $weather_icon3 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon3x == "9d"){ //
                $weather_icon3 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon3x == "9n"){ //
                $weather_icon3 = "🌧";
              /*

              */
              }
              elseif ($weather_icon3x == "10d"){
                $weather_icon3 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon3x == "10n"){
                $weather_icon3 = "🌦";
              /*

              */
              }
              elseif($weather_icon3x == "11d"){
                $weather_icon3 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon3x == "11n"){
                $weather_icon3 = "⛈";
                /*

                */
              }
              elseif ($weather_icon3x == "13d") {
                $weather_icon3 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon3x == "13n") {
              $weather_icon3 = "🌨";
              /*

              */
              }
              elseif ($weather_icon3x == "50d") {
                $weather_icon3 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon3x == "50n") {
                $weather_icon3 = "🌫";
                /*

                */
              }
            //clouds
              $clouds3 = $clima['list'][3]['clouds']['all'];
            //wind
              $wind_speed3 = $clima['list'][3]['wind']['speed'];
              $wind_deg3 = $clima['list'][3]['wind']['deg'];
              $degre3 = (int)$wind_deg3;
            //direction
              if($degre3 == 0){
                $direction_wind3 = 'North';
              }
              elseif($degre3 > 0 && $degre3 <45){
                $direction_wind3 = 'North-NorthEast';
              }
              elseif ($degre3 == 45) {
                $direction_wind3 = 'North-East';
              }
              elseif ($degre3 > 45 && $degre3 <90){
                $direction_wind3 = 'East-NorthEast';
              }
              elseif ($degre3 == 90){
                $direction_wind3 = 'East';
              }
              elseif($degre3 > 90 && $degre3 <135){
                $direction_wind3 = 'East-SouthEast';
              }
              elseif($degre3 == 135){
                $direction_wind3 = 'South-East';
              }
              elseif($degre3 > 135 && $degre3 <180){
                $direction_wind3 = 'South-SouthEast';
              }
              elseif($degre3 == 180){
                $direction_wind3 = 'South';
              }
              elseif($degre3 > 180 && $degre3 <225){
                $direction_wind3 = 'West-SouthWest';
              }
              elseif($degre3 == 225){
                $direction_wind3 = 'South-West';
              }
              elseif($degre3 > 225 && $degre3 <270){
                $direction_wind3 = 'West-SouthWest';
              }
              elseif($degre3 == 270){
                $direction_wind3 = 'West';
              }
              elseif($degre3 > 270 && $degre3 <315){
                $direction_wind3 = 'West-NortWest';
              }
              elseif($degre3 == 315){
                $direction_wind3 = 'North-West';
              }
              elseif($degre3 > 315 && $degre3 <360){
                $direction_wind3 = 'North-NorthWest';
              }
              else{
                $direction_wind3 = 'North';
              }
            //sea
              if($wind_speed3 <= 0.3){
                $wind_force3 = 'Calm';
                $sea_force3 = 'Zero force';
              }
              elseif($wind_speed3 > 0.3 && $wind_speed3 < 1.5){
                $wind_force3 = 'Burr of wind';
                $sea_force3 = 'Force one';
              }
              elseif($wind_speed3 > 1.5 && $wind_speed3 < 3.3){
                $wind_force3 = 'Light breeze';
                $sea_force3 = 'Force two';
              }
              elseif($wind_speed3 > 3.3 && $wind_speed3 < 5.4){
                $wind_force3 = 'Breeze';
                $sea_force3 = 'Force two';
              }
              elseif($wind_speed3 > 5.4 && $wind_speed3 < 7.9){
                $wind_force3 = 'Livelly breeze';
                $sea_force3 = 'Force three';
              }
              elseif($wind_speed3 > 7.9 && $wind_speed3 < 10.7){
                $wind_force3 = 'Tense breeze';
                $sea_force3 = 'Force four';
              }
              elseif($wind_speed3 > 10.7 && $wind_speed3 < 13.8){
                $wind_force3 = 'Fresh wind';
                $sea_force3 = 'Force five';
              }
              elseif($wind_speed3 > 13.8 && $wind_speed3 < 17.1){
                $wind_force3 = 'Strong wind';
                $sea_force3 = 'Force six';
              }
              elseif($wind_speed3 > 17.1 && $wind_speed3 < 20.7){
                $wind_force3 = 'Moderate storm';
                $sea_force3 = 'Force seven';
              }
              elseif($wind_speed3 > 20.7 && $wind_speed3 < 24.4){
                $wind_force3 = 'Strong storm';
                $sea_force3 = 'Force eight';
              }
              elseif($wind_speed3 > 24.4 && $wind_speed3 < 28.4){
                $wind_force3 = 'Storm';
                $sea_force3 = 'Force nine';
              }
              elseif($wind_speed3 > 28.4 && $wind_speed3 < 32.6){
                $wind_force3 = 'Fortunale';
                $sea_force3 = 'Force ten';
              }
              elseif($wind_speed3 > 32.6){
                $wind_force3 = 'Hurricane';
                $sea_force3 = 'Force ten';
              }
              else{
                $wind_force3 = 'zero';
                $sea_force3 = 'zero';
              }
            //rain
              $rain3 = $clima['list'][3]['rain']['3h'];
            //snow
              $snow3 = $clima['list'][3]['snow']['3h'];
            //data/time of calculation
              $data_time_pre3 = $clima['list'][3]['dt_txt'];
              $data_time_confr3 = substr($data_time_pre3,0,11);
              $data_time3 = substr($data_time_pre3,11);

            //4
              $temperatura4 = $clima['list'][4]['main']['temp'];
              $temp_min4 = $clima['list'][4]['main']['temp_min'];
              $temp_max4 = $clima['list'][4]['main']['temp_max'];
              $pressure4 = $clima['list'][4]['main']['pressure'];
              $sea_level4 = $clima['list'][4]['main']['sea_level'];
              $ground_level4 = $clima['list'][4]['main']['grnd_level'];
              $humidity4 = $clima['list'][4]['main']['humidity'];
              $internal_parameter4 = $clima['list'][4]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm4 = round($pressure4*0.00098692, 3);
            //weather list
              $weather_condition_id4 = $clima['list'][4]['weather'][0]['id'];
              $weather_condition_main4 = $clima['list'][4]['weather'][0]['main'];
              $weather_description4 = $clima['list'][4]['weather'][0]['description'];
              $weather_icon4x = $clima['list'][4]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon4x == "01d") {
                $weather_icon4 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon4x == "01n") {
                $weather_icon4 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon4x == "02d") {
                $weather_icon4 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon4x == "02n") {
                $weather_icon4 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon4x == "03d") {
                $weather_icon4 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon4x == "03n") {
                $weather_icon4 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon4x == "04d") {
                $weather_icon4 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon4x == "04n") {
                $weather_icon4 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon4x == "9d"){ //
                $weather_icon4 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon4x == "9n"){ //
                $weather_icon4 = "🌧";
              /*

              */
              }
              elseif ($weather_icon4x == "10d"){
                $weather_icon4 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon4x == "10n"){
                $weather_icon4 = "🌦";
              /*

              */
              }
              elseif($weather_icon4x == "11d"){
                $weather_icon4 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon4x == "11n"){
                $weather_icon4 = "⛈";
                /*

                */
              }
              elseif ($weather_icon4x == "13d") {
                $weather_icon4 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon4x == "13n") {
              $weather_icon4 = "🌨";
              /*

              */
              }
              elseif ($weather_icon4x == "50d") {
                $weather_icon4 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon4x == "50n") {
                $weather_icon4 = "🌫";
                /*

                */
              }
            //clouds
              $clouds4 = $clima['list'][4]['clouds']['all'];
            //wind
              $wind_speed4 = $clima['list'][4]['wind']['speed'];
              $wind_deg4 = $clima['list'][4]['wind']['deg'];
              $degre4 = (int)$wind_deg4;
            //direction
              if($degre4 == 0){
                $direction_wind4 = 'North';
              }
              elseif($degre4 > 0 && $degre4 <45){
                $direction_wind4 = 'North-NorthEast';
              }
              elseif ($degre4 == 45) {
                $direction_wind4 = 'North-East';
              }
              elseif ($degre4 > 45 && $degre4 <90){
                $direction_wind4 = 'East-NorthEast';
              }
              elseif ($degre4 == 90){
                $direction_wind4 = 'East';
              }
              elseif($degre4 > 90 && $degre4 <135){
                $direction_wind4 = 'East-SouthEast';
              }
              elseif($degre4 == 135){
                $direction_wind4 = 'South-East';
              }
              elseif($degre4 > 135 && $degre4 <180){
                $direction_wind4 = 'South-SouthEast';
              }
              elseif($degre4 == 180){
                $direction_wind4 = 'South';
              }
              elseif($degre4 > 180 && $degre4 <225){
                $direction_wind4 = 'West-SouthWest';
              }
              elseif($degre4 == 225){
                $direction_wind4 = 'South-West';
              }
              elseif($degre4 > 225 && $degre4 <270){
                $direction_wind4 = 'West-SouthWest';
              }
              elseif($degre4 == 270){
                $direction_wind4 = 'West';
              }
              elseif($degre4 > 270 && $degre4 <315){
                $direction_wind4 = 'West-NortWest';
              }
              elseif($degre4 == 315){
                $direction_wind4 = 'North-West';
              }
              elseif($degre4 > 315 && $degre4 <360){
                $direction_wind4 = 'North-NorthWest';
              }
              else{
                $direction_wind4 = 'North';
              }
            //sea
              if($wind_speed4 <= 0.3){
                $wind_force4 = 'Calm';
                $sea_force4 = 'Zero force';
              }
              elseif($wind_speed4 > 0.3 && $wind_speed4 < 1.5){
                $wind_force4 = 'Burr of wind';
                $sea_force4 = 'Force one';
              }
              elseif($wind_speed4 > 1.5 && $wind_speed4 < 3.3){
                $wind_force4 = 'Light breeze';
                $sea_force4 = 'Force two';
              }
              elseif($wind_speed4 > 3.3 && $wind_speed4 < 5.4){
                $wind_force4 = 'Breeze';
                $sea_force4 = 'Force two';
              }
              elseif($wind_speed4 > 5.4 && $wind_speed4 < 7.9){
                $wind_force4 = 'Livelly breeze';
                $sea_force4 = 'Force three';
              }
              elseif($wind_speed4 > 7.9 && $wind_speed4 < 10.7){
                $wind_force4 = 'Tense breeze';
                $sea_force4 = 'Force four';
              }
              elseif($wind_speed4 > 10.7 && $wind_speed4 < 13.8){
                $wind_force4 = 'Fresh wind';
                $sea_force4 = 'Force five';
              }
              elseif($wind_speed4 > 13.8 && $wind_speed4 < 17.1){
                $wind_force4 = 'Strong wind';
                $sea_force4 = 'Force six';
              }
              elseif($wind_speed4 > 17.1 && $wind_speed4 < 20.7){
                $wind_force4 = 'Moderate storm';
                $sea_force4 = 'Force seven';
              }
              elseif($wind_speed4 > 20.7 && $wind_speed4 < 24.4){
                $wind_force4 = 'Strong storm';
                $sea_force4 = 'Force eight';
              }
              elseif($wind_speed4 > 24.4 && $wind_speed4 < 28.4){
                $wind_force4 = 'Storm';
                $sea_force4 = 'Force nine';
              }
              elseif($wind_speed4 > 28.4 && $wind_speed4 < 32.6){
                $wind_force4 = 'Fortunale';
                $sea_force4 = 'Force ten';
              }
              elseif($wind_speed4 > 32.6){
                $wind_force4 = 'Hurricane';
                $sea_force4 = 'Force ten';
              }
              else{
                $wind_force4 = 'zero';
                $sea_force4 = 'zero';
              }
            //rain
              $rain4 = $clima['list'][4]['rain']['3h'];
            //snow
              $snow4 = $clima['list'][4]['snow']['3h'];
            //data/time of calculation
              $data_time_pre4 = $clima['list'][4]['dt_txt'];
              $data_time_confr4 = substr($data_time_pre4,0,11);
              $data_time4 = substr($data_time_pre4,11);

            //5
              $temperatura5 = $clima['list'][5]['main']['temp'];
              $temp_min5 = $clima['list'][5]['main']['temp_min'];
              $temp_max5 = $clima['list'][5]['main']['temp_max'];
              $pressure5 = $clima['list'][5]['main']['pressure'];
              $sea_level5 = $clima['list'][5]['main']['sea_level'];
              $ground_level5 = $clima['list'][5]['main']['grnd_level'];
              $humidity5 = $clima['list'][5]['main']['humidity'];
              $internal_parameter5 = $clima['list'][5]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm5 = round($pressure5*0.00098692, 3);
            //weather list
              $weather_condition_id5 = $clima['list'][5]['weather'][0]['id'];
              $weather_condition_main5 = $clima['list'][5]['weather'][0]['main'];
              $weather_description5 = $clima['list'][5]['weather'][0]['description'];
              $weather_icon5x = $clima['list'][5]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon5x == "01d") {
                $weather_icon5 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon5x == "01n") {
                $weather_icon5 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon5x == "02d") {
                $weather_icon5 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon5x == "02n") {
                $weather_icon5 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon5x == "03d") {
                $weather_icon5 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon5x == "03n") {
                $weather_icon5 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon5x == "04d") {
                $weather_icon5 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon5x == "04n") {
                $weather_icon5 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon5x == "9d"){ //
                $weather_icon5 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon5x == "9n"){ //
                $weather_icon5 = "🌧";
              /*

              */
              }
              elseif ($weather_icon5x == "10d"){
                $weather_icon5 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon5x == "10n"){
                $weather_icon5 = "🌦";
              /*

              */
              }
              elseif($weather_icon5x == "11d"){
                $weather_icon5 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon5x == "11n"){
                $weather_icon5 = "⛈";
                /*

                */
              }
              elseif ($weather_icon5x == "13d") {
                $weather_icon5 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon5x == "13n") {
              $weather_icon5 = "🌨";
              /*

              */
              }
              elseif ($weather_icon5x == "50d") {
                $weather_icon5 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon5x == "50n") {
                $weather_icon5 = "🌫";
                /*

                */
              }
            //clouds
              $clouds5 = $clima['list'][5]['clouds']['all'];
            //wind
              $wind_speed5 = $clima['list'][5]['wind']['speed'];
              $wind_deg5 = $clima['list'][5]['wind']['deg'];
              $degre5 = (int)$wind_deg5;
            //direction
              if($degre5 == 0){
                $direction_wind5 = 'North';
              }
              elseif($degre5 > 0 && $degre5 <45){
                $direction_wind5 = 'North-NorthEast';
              }
              elseif ($degre5 == 45) {
                $direction_wind5 = 'North-East';
              }
              elseif ($degre5 > 45 && $degre5 <90){
                $direction_wind5 = 'East-NorthEast';
              }
              elseif ($degre5 == 90){
                $direction_wind5 = 'East';
              }
              elseif($degre5 > 90 && $degre5 <135){
                $direction_wind5 = 'East-SouthEast';
              }
              elseif($degre5 == 135){
                $direction_wind5 = 'South-East';
              }
              elseif($degre5 > 135 && $degre5 <180){
                $direction_wind5 = 'South-SouthEast';
              }
              elseif($degre5 == 180){
                $direction_wind5 = 'South';
              }
              elseif($degre5 > 180 && $degre5 <225){
                $direction_wind5 = 'West-SouthWest';
              }
              elseif($degre5 == 225){
                $direction_wind5 = 'South-West';
              }
              elseif($degre5 > 225 && $degre5 <270){
                $direction_wind5 = 'West-SouthWest';
              }
              elseif($degre5 == 270){
                $direction_wind5 = 'West';
              }
              elseif($degre5 > 270 && $degre5 <315){
                $direction_wind5 = 'West-NortWest';
              }
              elseif($degre5 == 315){
                $direction_wind5 = 'North-West';
              }
              elseif($degre5 > 315 && $degre5 <360){
                $direction_wind5 = 'North-NorthWest';
              }
              else{
                $direction_wind5 = 'North';
              }
            //sea
              if($wind_speed5 <= 0.3){
                $wind_force5 = 'Calm';
                $sea_force5 = 'Zero force';
              }
              elseif($wind_speed5 > 0.3 && $wind_speed5 < 1.5){
                $wind_force5 = 'Burr of wind';
                $sea_force5 = 'Force one';
              }
              elseif($wind_speed5 > 1.5 && $wind_speed5 < 3.3){
                $wind_force5 = 'Light breeze';
                $sea_force5 = 'Force two';
              }
              elseif($wind_speed5 > 3.3 && $wind_speed5 < 5.4){
                $wind_force5 = 'Breeze';
                $sea_force5 = 'Force two';
              }
              elseif($wind_speed5 > 5.4 && $wind_speed5 < 7.9){
                $wind_force5 = 'Livelly breeze';
                $sea_force5 = 'Force three';
              }
              elseif($wind_speed5 > 7.9 && $wind_speed5 < 10.7){
                $wind_force5 = 'Tense breeze';
                $sea_force5 = 'Force four';
              }
              elseif($wind_speed5 > 10.7 && $wind_speed5 < 13.8){
                $wind_force5 = 'Fresh wind';
                $sea_force5 = 'Force five';
              }
              elseif($wind_speed5 > 13.8 && $wind_speed5 < 17.1){
                $wind_force5 = 'Strong wind';
                $sea_force5 = 'Force six';
              }
              elseif($wind_speed5 > 17.1 && $wind_speed5 < 20.7){
                $wind_force5 = 'Moderate storm';
                $sea_force5 = 'Force seven';
              }
              elseif($wind_speed5 > 20.7 && $wind_speed5 < 24.4){
                $wind_force5 = 'Strong storm';
                $sea_force5 = 'Force eight';
              }
              elseif($wind_speed5 > 24.4 && $wind_speed5 < 28.4){
                $wind_force5 = 'Storm';
                $sea_force5 = 'Force nine';
              }
              elseif($wind_speed5 > 28.4 && $wind_speed5 < 32.6){
                $wind_force5 = 'Fortunale';
                $sea_force5 = 'Force ten';
              }
              elseif($wind_speed5 > 32.6){
                $wind_force5 = 'Hurricane';
                $sea_force5 = 'Force ten';
              }
              else{
                $wind_force5 = 'zero';
                $sea_force5 = 'zero';
              }
            //rain
              $rain5 = $clima['list'][5]['rain']['3h'];
            //snow
              $snow5 = $clima['list'][5]['snow']['3h'];
            //data/time of calculation
              $data_time_pre5 = $clima['list'][5]['dt_txt'];
              $data_time_confr5 = substr($data_time_pre5,0,11);
              $data_time5 = substr($data_time_pre5,11);

            //6
              $temperatura6 = $clima['list'][6]['main']['temp'];
              $temp_min6 = $clima['list'][6]['main']['temp_min'];
              $temp_max6 = $clima['list'][6]['main']['temp_max'];
              $pressure6 = $clima['list'][6]['main']['pressure'];
              $sea_level6 = $clima['list'][6]['main']['sea_level'];
              $ground_level6 = $clima['list'][6]['main']['grnd_level'];
              $humidity6 = $clima['list'][6]['main']['humidity'];
              $internal_parameter6 = $clima['list'][6]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm6 = round($pressure6*0.00098692, 3);
            //weather list
              $weather_condition_id6 = $clima['list'][6]['weather'][0]['id'];
              $weather_condition_main6 = $clima['list'][6]['weather'][0]['main'];
              $weather_description6 = $clima['list'][6]['weather'][0]['description'];
              $weather_icon6x = $clima['list'][6]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon6x == "01d") {
                $weather_icon6 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon6x == "01n") {
                $weather_icon6 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon6x == "02d") {
                $weather_icon6 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon6x == "02n") {
                $weather_icon6 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon6x == "03d") {
                $weather_icon6 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon6x == "03n") {
                $weather_icon6 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon6x == "04d") {
                $weather_icon6 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon6x == "04n") {
                $weather_icon6 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon6x == "9d"){ //
                $weather_icon6 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon6x == "9n"){ //
                $weather_icon6 = "🌧";
              /*

              */
              }
              elseif ($weather_icon6x == "10d"){
                $weather_icon6 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon6x == "10n"){
                $weather_icon6 = "🌦";
              /*

              */
              }
              elseif($weather_icon6x == "11d"){
                $weather_icon6 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon6x == "11n"){
                $weather_icon6 = "⛈";
                /*

                */
              }
              elseif ($weather_icon6x == "13d") {
                $weather_icon6 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon6x == "13n") {
              $weather_icon6 = "🌨";
              /*

              */
              }
              elseif ($weather_icon6x == "50d") {
                $weather_icon6 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon6x == "50n") {
                $weather_icon6 = "🌫";
                /*

                */
              }
            //clouds
              $clouds6 = $clima['list'][6]['clouds']['all'];
            //wind
              $wind_speed6 = $clima['list'][6]['wind']['speed'];
              $wind_deg6 = $clima['list'][6]['wind']['deg'];
              $degre6 = (int)$wind_deg6;
            //direction
              if($degre6 == 0){
                $direction_wind6 = 'North';
              }
              elseif($degre6 > 0 && $degre6 <45){
                $direction_wind6 = 'North-NorthEast';
              }
              elseif ($degre6 == 45) {
                $direction_wind6 = 'North-East';
              }
              elseif ($degre6 > 45 && $degre6 <90){
                $direction_wind6 = 'East-NorthEast';
              }
              elseif ($degre6 == 90){
                $direction_wind6 = 'East';
              }
              elseif($degre6 > 90 && $degre6 <135){
                $direction_wind6 = 'East-SouthEast';
              }
              elseif($degre6 == 135){
                $direction_wind6 = 'South-East';
              }
              elseif($degre6 > 135 && $degre6 <180){
                $direction_wind6 = 'South-SouthEast';
              }
              elseif($degre6 == 180){
                $direction_wind6 = 'South';
              }
              elseif($degre6 > 180 && $degre6 <225){
                $direction_wind6 = 'West-SouthWest';
              }
              elseif($degre6 == 225){
                $direction_wind6 = 'South-West';
              }
              elseif($degre6 > 225 && $degre6 <270){
                $direction_wind6 = 'West-SouthWest';
              }
              elseif($degre6 == 270){
                $direction_wind6 = 'West';
              }
              elseif($degre6 > 270 && $degre6 <315){
                $direction_wind6 = 'West-NortWest';
              }
              elseif($degre6 == 315){
                $direction_wind6 = 'North-West';
              }
              elseif($degre6 > 315 && $degre6 <360){
                $direction_wind6 = 'North-NorthWest';
              }
              else{
                $direction_wind6 = 'North';
              }
            //sea
              if($wind_speed6 <= 0.3){
                $wind_force6 = 'Calm';
                $sea_force6 = 'Zero force';
              }
              elseif($wind_speed6 > 0.3 && $wind_speed6 < 1.5){
                $wind_force6 = 'Burr of wind';
                $sea_force6 = 'Force one';
              }
              elseif($wind_speed6 > 1.5 && $wind_speed6 < 3.3){
                $wind_force6 = 'Light breeze';
                $sea_force6 = 'Force two';
              }
              elseif($wind_speed6 > 3.3 && $wind_speed6 < 5.4){
                $wind_force6 = 'Breeze';
                $sea_force6 = 'Force two';
              }
              elseif($wind_speed6 > 5.4 && $wind_speed6 < 7.9){
                $wind_force6 = 'Livelly breeze';
                $sea_force6 = 'Force three';
              }
              elseif($wind_speed6 > 7.9 && $wind_speed6 < 10.7){
                $wind_force6 = 'Tense breeze';
                $sea_force6 = 'Force four';
              }
              elseif($wind_speed6 > 10.7 && $wind_speed6 < 13.8){
                $wind_force6 = 'Fresh wind';
                $sea_force6 = 'Force five';
              }
              elseif($wind_speed6 > 13.8 && $wind_speed6 < 17.1){
                $wind_force6 = 'Strong wind';
                $sea_force6 = 'Force six';
              }
              elseif($wind_speed6 > 17.1 && $wind_speed6 < 20.7){
                $wind_force6 = 'Moderate storm';
                $sea_force6 = 'Force seven';
              }
              elseif($wind_speed6 > 20.7 && $wind_speed6 < 24.4){
                $wind_force6 = 'Strong storm';
                $sea_force6 = 'Force eight';
              }
              elseif($wind_speed6 > 24.4 && $wind_speed6 < 28.4){
                $wind_force6 = 'Storm';
                $sea_force6 = 'Force nine';
              }
              elseif($wind_speed6 > 28.4 && $wind_speed6 < 32.6){
                $wind_force6 = 'Fortunale';
                $sea_force6 = 'Force ten';
              }
              elseif($wind_speed6 > 32.6){
                $wind_force6 = 'Hurricane';
                $sea_force6 = 'Force ten';
              }
              else{
                $wind_force6 = 'zero';
                $sea_force6 = 'zero';
              }
            //rain
              $rain6 = $clima['list'][6]['rain']['3h'];
            //snow
              $snow6 = $clima['list'][6]['snow']['3h'];
            //data/time of calculation
              $data_time_pre6 = $clima['list'][6]['dt_txt'];
              $data_time_confr6 = substr($data_time_pre6,0,11);
              $data_time6 = substr($data_time_pre6,11);

            //7
              $temperatura7 = $clima['list'][7]['main']['temp'];
              $temp_min7 = $clima['list'][7]['main']['temp_min'];
              $temp_max7 = $clima['list'][7]['main']['temp_max'];
              $pressure7 = $clima['list'][7]['main']['pressure'];
              $sea_level7 = $clima['list'][7]['main']['sea_level'];
              $ground_level7 = $clima['list'][7]['main']['grnd_level'];
              $humidity7 = $clima['list'][7]['main']['humidity'];
              $internal_parameter7 = $clima['list'][7]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm7 = round($pressure7*0.00098692, 3);
            //weather list
              $weather_condition_id7 = $clima['list'][7]['weather'][0]['id'];
              $weather_condition_main7 = $clima['list'][7]['weather'][0]['main'];
              $weather_description7 = $clima['list'][7]['weather'][0]['description'];
              $weather_icon7x = $clima['list'][7]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon7x == "01d") {
                $weather_icon7 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon7x == "01n") {
                $weather_icon7 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon7x == "02d") {
                $weather_icon7 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon7x == "02n") {
                $weather_icon7 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon7x == "03d") {
                $weather_icon7 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon7x == "03n") {
                $weather_icon7 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon7x == "04d") {
                $weather_icon7 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon7x == "04n") {
                $weather_icon7 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon7x == "9d"){ //
                $weather_icon7 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon7x == "9n"){ //
                $weather_icon7 = "🌧";
              /*

              */
              }
              elseif ($weather_icon7x == "10d"){
                $weather_icon7 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon7x == "10n"){
                $weather_icon7 = "🌦";
              /*

              */
              }
              elseif($weather_icon7x == "11d"){
                $weather_icon7 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon7x == "11n"){
                $weather_icon7 = "⛈";
                /*

                */
              }
              elseif ($weather_icon7x == "13d") {
                $weather_icon7 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon7x == "13n") {
              $weather_icon7 = "🌨";
              /*

              */
              }
              elseif ($weather_icon7x == "50d") {
                $weather_icon7 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon7x == "50n") {
                $weather_icon7 = "🌫";
                /*

                */
              }
            //clouds
              $clouds7 = $clima['list'][7]['clouds']['all'];
            //wind
              $wind_speed7 = $clima['list'][7]['wind']['speed'];
              $wind_deg7 = $clima['list'][7]['wind']['deg'];
              $degre7 = (int)$wind_deg7;
            //direction
              if($degre7 == 0){
                $direction_wind7 = 'North';
              }
              elseif($degre7 > 0 && $degre7 <45){
                $direction_wind7 = 'North-NorthEast';
              }
              elseif ($degre7 == 45) {
                $direction_wind7 = 'North-East';
              }
              elseif ($degre7 > 45 && $degre7 <90){
                $direction_wind7 = 'East-NorthEast';
              }
              elseif ($degre7 == 90){
                $direction_wind7 = 'East';
              }
              elseif($degre7 > 90 && $degre7 <135){
                $direction_wind7 = 'East-SouthEast';
              }
              elseif($degre7 == 135){
                $direction_wind7 = 'South-East';
              }
              elseif($degre7 > 135 && $degre7 <180){
                $direction_wind7 = 'South-SouthEast';
              }
              elseif($degre7 == 180){
                $direction_wind7 = 'South';
              }
              elseif($degre7 > 180 && $degre7 <225){
                $direction_wind7 = 'West-SouthWest';
              }
              elseif($degre7 == 225){
                $direction_wind7 = 'South-West';
              }
              elseif($degre7 > 225 && $degre7 <270){
                $direction_wind7 = 'West-SouthWest';
              }
              elseif($degre7 == 270){
                $direction_wind7 = 'West';
              }
              elseif($degre7 > 270 && $degre7 <315){
                $direction_wind7 = 'West-NortWest';
              }
              elseif($degre7 == 315){
                $direction_wind7 = 'North-West';
              }
              elseif($degre7 > 315 && $degre7 <360){
                $direction_wind7 = 'North-NorthWest';
              }
              else{
                $direction_wind7 = 'North';
              }
            //sea
              if($wind_speed7 <= 0.3){
                $wind_force7 = 'Calm';
                $sea_force7 = 'Zero force';
              }
              elseif($wind_speed7 > 0.3 && $wind_speed7 < 1.5){
                $wind_force7 = 'Burr of wind';
                $sea_force7 = 'Force one';
              }
              elseif($wind_speed7 > 1.5 && $wind_speed7 < 3.3){
                $wind_force7 = 'Light breeze';
                $sea_force7 = 'Force two';
              }
              elseif($wind_speed7 > 3.3 && $wind_speed7 < 5.4){
                $wind_force7 = 'Breeze';
                $sea_force7 = 'Force two';
              }
              elseif($wind_speed7 > 5.4 && $wind_speed7 < 7.9){
                $wind_force7 = 'Livelly breeze';
                $sea_force7 = 'Force three';
              }
              elseif($wind_speed7 > 7.9 && $wind_speed7 < 10.7){
                $wind_force7 = 'Tense breeze';
                $sea_force7 = 'Force four';
              }
              elseif($wind_speed7 > 10.7 && $wind_speed7 < 13.8){
                $wind_force7 = 'Fresh wind';
                $sea_force7 = 'Force five';
              }
              elseif($wind_speed7 > 13.8 && $wind_speed7 < 17.1){
                $wind_force7 = 'Strong wind';
                $sea_force7 = 'Force six';
              }
              elseif($wind_speed7 > 17.1 && $wind_speed7 < 20.7){
                $wind_force7 = 'Moderate storm';
                $sea_force7 = 'Force seven';
              }
              elseif($wind_speed7 > 20.7 && $wind_speed7 < 24.4){
                $wind_force7 = 'Strong storm';
                $sea_force7 = 'Force eight';
              }
              elseif($wind_speed7 > 24.4 && $wind_speed7 < 28.4){
                $wind_force7 = 'Storm';
                $sea_force7 = 'Force nine';
              }
              elseif($wind_speed7 > 28.4 && $wind_speed7 < 32.6){
                $wind_force7 = 'Fortunale';
                $sea_force7 = 'Force ten';
              }
              elseif($wind_speed7 > 32.6){
                $wind_force7 = 'Hurricane';
                $sea_force7 = 'Force ten';
              }
              else{
                $wind_force7 = 'zero';
                $sea_force7 = 'zero';
              }
            //rain
              $rain7 = $clima['list'][7]['rain']['3h'];
            //snow
              $snow7 = $clima['list'][7]['snow']['3h'];
            //data/time of calculation
              $data_time_pre7 = $clima['list'][7]['dt_txt'];
              $data_time_confr7 = substr($data_time_pre7,0,11);
              $data_time7 = substr($data_time_pre7,11);

            //8
              $temperatura8 = $clima['list'][8]['main']['temp'];
              $temp_min8 = $clima['list'][8]['main']['temp_min'];
              $temp_max8 = $clima['list'][8]['main']['temp_max'];
              $pressure8 = $clima['list'][8]['main']['pressure'];
              $sea_level8 = $clima['list'][8]['main']['sea_level'];
              $ground_level8 = $clima['list'][8]['main']['grnd_level'];
              $humidity8 = $clima['list'][8]['main']['humidity'];
              $internal_parameter8 = $clima['list'][8]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm8 = round($pressure8*0.00098692, 3);
            //weather list
              $weather_condition_id8 = $clima['list'][8]['weather'][0]['id'];
              $weather_condition_main8 = $clima['list'][8]['weather'][0]['main'];
              $weather_description8 = $clima['list'][8]['weather'][0]['description'];
              $weather_icon8x = $clima['list'][8]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon8x == "01d") {
                $weather_icon8 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon8x == "01n") {
                $weather_icon8 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon8x == "02d") {
                $weather_icon8 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon8x == "02n") {
                $weather_icon8 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon8x == "03d") {
                $weather_icon8 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon8x == "03n") {
                $weather_icon8 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon8x == "04d") {
                $weather_icon8 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon8x == "04n") {
                $weather_icon8 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon8x == "9d"){ //
                $weather_icon8 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon8x == "9n"){ //
                $weather_icon8 = "🌧";
              /*

              */
              }
              elseif ($weather_icon8x == "10d"){
                $weather_icon8 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon8x == "10n"){
                $weather_icon8 = "🌦";
              /*

              */
              }
              elseif($weather_icon8x == "11d"){
                $weather_icon8 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon8x == "11n"){
                $weather_icon8 = "⛈";
                /*

                */
              }
              elseif ($weather_icon8x == "13d") {
                $weather_icon8 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon8x == "13n") {
              $weather_icon8 = "🌨";
              /*

              */
              }
              elseif ($weather_icon8x == "50d") {
                $weather_icon8 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon8x == "50n") {
                $weather_icon8 = "🌫";
                /*

                */
              }
            //clouds
              $clouds8 = $clima['list'][8]['clouds']['all'];
            //wind
              $wind_speed8 = $clima['list'][8]['wind']['speed'];
              $wind_deg8 = $clima['list'][8]['wind']['deg'];
              $degre8 = (int)$wind_deg8;
            //direction
              if($degre8 == 0){
                $direction_wind8 = 'North';
              }
              elseif($degre8 > 0 && $degre8 <45){
                $direction_wind8 = 'North-NorthEast';
              }
              elseif ($degre8 == 45) {
                $direction_wind8 = 'North-East';
              }
              elseif ($degre8 > 45 && $degre8 <90){
                $direction_wind8 = 'East-NorthEast';
              }
              elseif ($degre8 == 90){
                $direction_wind8 = 'East';
              }
              elseif($degre8 > 90 && $degre8 <135){
                $direction_wind8 = 'East-SouthEast';
              }
              elseif($degre8 == 135){
                $direction_wind8 = 'South-East';
              }
              elseif($degre8 > 135 && $degre8 <180){
                $direction_wind8 = 'South-SouthEast';
              }
              elseif($degre8 == 180){
                $direction_wind8 = 'South';
              }
              elseif($degre8 > 180 && $degre8 <225){
                $direction_wind8 = 'West-SouthWest';
              }
              elseif($degre8 == 225){
                $direction_wind8 = 'South-West';
              }
              elseif($degre8 > 225 && $degre8 <270){
                $direction_wind8 = 'West-SouthWest';
              }
              elseif($degre8 == 270){
                $direction_wind8 = 'West';
              }
              elseif($degre8 > 270 && $degre8 <315){
                $direction_wind8 = 'West-NortWest';
              }
              elseif($degre8 == 315){
                $direction_wind8 = 'North-West';
              }
              elseif($degre8 > 315 && $degre8 <360){
                $direction_wind8 = 'North-NorthWest';
              }
              else{
                $direction_wind8 = 'North';
              }
            //sea
              if($wind_speed8 <= 0.3){
                $wind_force8 = 'Calm';
                $sea_force8 = 'Zero force';
              }
              elseif($wind_speed8 > 0.3 && $wind_speed8 < 1.5){
                $wind_force8 = 'Burr of wind';
                $sea_force8 = 'Force one';
              }
              elseif($wind_speed8 > 1.5 && $wind_speed8 < 3.3){
                $wind_force8 = 'Light breeze';
                $sea_force8 = 'Force two';
              }
              elseif($wind_speed8 > 3.3 && $wind_speed8 < 5.4){
                $wind_force8 = 'Breeze';
                $sea_force8 = 'Force two';
              }
              elseif($wind_speed8 > 5.4 && $wind_speed8 < 7.9){
                $wind_force8 = 'Livelly breeze';
                $sea_force8 = 'Force three';
              }
              elseif($wind_speed8 > 7.9 && $wind_speed8 < 10.7){
                $wind_force8 = 'Tense breeze';
                $sea_force8 = 'Force four';
              }
              elseif($wind_speed8 > 10.7 && $wind_speed8 < 13.8){
                $wind_force8 = 'Fresh wind';
                $sea_force8 = 'Force five';
              }
              elseif($wind_speed8 > 13.8 && $wind_speed8 < 17.1){
                $wind_force8 = 'Strong wind';
                $sea_force8 = 'Force six';
              }
              elseif($wind_speed8 > 17.1 && $wind_speed8 < 20.7){
                $wind_force8 = 'Moderate storm';
                $sea_force8 = 'Force seven';
              }
              elseif($wind_speed8 > 20.7 && $wind_speed8 < 24.4){
                $wind_force8 = 'Strong storm';
                $sea_force8 = 'Force eight';
              }
              elseif($wind_speed8 > 24.4 && $wind_speed8 < 28.4){
                $wind_force8 = 'Storm';
                $sea_force8 = 'Force nine';
              }
              elseif($wind_speed8 > 28.4 && $wind_speed8 < 32.6){
                $wind_force8 = 'Fortunale';
                $sea_force8 = 'Force ten';
              }
              elseif($wind_speed8 > 32.6){
                $wind_force8 = 'Hurricane';
                $sea_force8 = 'Force ten';
              }
              else{
                $wind_force8 = 'zero';
                $sea_force8 = 'zero';
              }
            //rain
              $rain8 = $clima['list'][8]['rain']['3h'];
            //snow
              $snow8 = $clima['list'][8]['snow']['3h'];
            //data/time of calculation
              $data_time_pre8 = $clima['list'][8]['dt_txt'];
              $data_time_confr8 = substr($data_time_pre8,0,11);
              $data_time8 = substr($data_time_pre8,11);

            //9
              $temperatura9 = $clima['list'][9]['main']['temp'];
              $temp_min9 = $clima['list'][9]['main']['temp_min'];
              $temp_max9 = $clima['list'][9]['main']['temp_max'];
              $pressure9 = $clima['list'][9]['main']['pressure'];
              $sea_level9 = $clima['list'][9]['main']['sea_level'];
              $ground_level9 = $clima['list'][9]['main']['grnd_level'];
              $humidity9 = $clima['list'][9]['main']['humidity'];
              $internal_parameter9 = $clima['list'][9]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm9 = round($pressure9*0.00098692, 3);
            //weather list
              $weather_condition_id9 = $clima['list'][9]['weather'][0]['id'];
              $weather_condition_main9 = $clima['list'][9]['weather'][0]['main'];
              $weather_description9 = $clima['list'][9]['weather'][0]['description'];
              $weather_icon9x = $clima['list'][9]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon9x == "01d") {
                $weather_icon9 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon9x == "01n") {
                $weather_icon9 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon9x == "02d") {
                $weather_icon9 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon9x == "02n") {
                $weather_icon9 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon9x == "03d") {
                $weather_icon9 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon9x == "03n") {
                $weather_icon9 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon9x == "04d") {
                $weather_icon9 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon9x == "04n") {
                $weather_icon9 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon9x == "9d"){ //
                $weather_icon9 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon9x == "9n"){ //
                $weather_icon9 = "🌧";
              /*

              */
              }
              elseif ($weather_icon9x == "10d"){
                $weather_icon9 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon9x == "10n"){
                $weather_icon9 = "🌦";
              /*

              */
              }
              elseif($weather_icon9x == "11d"){
                $weather_icon9 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon9x == "11n"){
                $weather_icon9 = "⛈";
                /*

                */
              }
              elseif ($weather_icon9x == "13d") {
                $weather_icon9 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon9x == "13n") {
              $weather_icon9 = "🌨";
              /*

              */
              }
              elseif ($weather_icon9x == "50d") {
                $weather_icon9 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon9x == "50n") {
                $weather_icon9 = "🌫";
                /*

                */
              }
            //clouds
              $clouds9 = $clima['list'][9]['clouds']['all'];
            //wind
              $wind_speed9 = $clima['list'][9]['wind']['speed'];
              $wind_deg9 = $clima['list'][9]['wind']['deg'];
              $degre9 = (int)$wind_deg9;
            //direction
              if($degre9 == 0){
                $direction_wind9 = 'North';
              }
              elseif($degre9 > 0 && $degre9 <45){
                $direction_wind9 = 'North-NorthEast';
              }
              elseif ($degre9 == 45) {
                $direction_wind9 = 'North-East';
              }
              elseif ($degre9 > 45 && $degre9 <90){
                $direction_wind9 = 'East-NorthEast';
              }
              elseif ($degre9 == 90){
                $direction_wind9 = 'East';
              }
              elseif($degre9 > 90 && $degre9 <135){
                $direction_wind9 = 'East-SouthEast';
              }
              elseif($degre9 == 135){
                $direction_wind9 = 'South-East';
              }
              elseif($degre9 > 135 && $degre9 <180){
                $direction_wind9 = 'South-SouthEast';
              }
              elseif($degre9 == 180){
                $direction_wind9 = 'South';
              }
              elseif($degre9 > 180 && $degre9 <225){
                $direction_wind9 = 'West-SouthWest';
              }
              elseif($degre9 == 225){
                $direction_wind9 = 'South-West';
              }
              elseif($degre9 > 225 && $degre9 <270){
                $direction_wind9 = 'West-SouthWest';
              }
              elseif($degre9 == 270){
                $direction_wind9 = 'West';
              }
              elseif($degre9 > 270 && $degre9 <315){
                $direction_wind9 = 'West-NortWest';
              }
              elseif($degre9 == 315){
                $direction_wind9 = 'North-West';
              }
              elseif($degre9 > 315 && $degre9 <360){
                $direction_wind9 = 'North-NorthWest';
              }
              else{
                $direction_wind9 = 'North';
              }
            //sea
              if($wind_speed9 <= 0.3){
                $wind_force9 = 'Calm';
                $sea_force9 = 'Zero force';
              }
              elseif($wind_speed9 > 0.3 && $wind_speed9 < 1.5){
                $wind_force9 = 'Burr of wind';
                $sea_force9 = 'Force one';
              }
              elseif($wind_speed9 > 1.5 && $wind_speed9 < 3.3){
                $wind_force9 = 'Light breeze';
                $sea_force9 = 'Force two';
              }
              elseif($wind_speed9 > 3.3 && $wind_speed9 < 5.4){
                $wind_force9 = 'Breeze';
                $sea_force9 = 'Force two';
              }
              elseif($wind_speed9 > 5.4 && $wind_speed9 < 7.9){
                $wind_force9 = 'Livelly breeze';
                $sea_force9 = 'Force three';
              }
              elseif($wind_speed9 > 7.9 && $wind_speed9 < 10.7){
                $wind_force9 = 'Tense breeze';
                $sea_force9 = 'Force four';
              }
              elseif($wind_speed9 > 10.7 && $wind_speed9 < 13.8){
                $wind_force9 = 'Fresh wind';
                $sea_force9 = 'Force five';
              }
              elseif($wind_speed9 > 13.8 && $wind_speed9 < 17.1){
                $wind_force9 = 'Strong wind';
                $sea_force9 = 'Force six';
              }
              elseif($wind_speed9 > 17.1 && $wind_speed9 < 20.7){
                $wind_force9 = 'Moderate storm';
                $sea_force9 = 'Force seven';
              }
              elseif($wind_speed9 > 20.7 && $wind_speed9 < 24.4){
                $wind_force9 = 'Strong storm';
                $sea_force9 = 'Force eight';
              }
              elseif($wind_speed9 > 24.4 && $wind_speed9 < 28.4){
                $wind_force9 = 'Storm';
                $sea_force9 = 'Force nine';
              }
              elseif($wind_speed9 > 28.4 && $wind_speed9 < 32.6){
                $wind_force9 = 'Fortunale';
                $sea_force9 = 'Force ten';
              }
              elseif($wind_speed9 > 32.6){
                $wind_force9 = 'Hurricane';
                $sea_force9 = 'Force ten';
              }
              else{
                $wind_force9 = 'zero';
                $sea_force9 = 'zero';
              }
            //rain
              $rain9 = $clima['list'][9]['rain']['3h'];
            //snow
              $snow9 = $clima['list'][9]['snow']['3h'];
            //data/time of calculation
              $data_time_pre9 = $clima['list'][9]['dt_txt'];
              $data_time_confr9 = substr($data_time_pre9,0,11);
              $data_time9 = substr($data_time_pre9,11);

            //10
              $temperatura10 = $clima['list'][10]['main']['temp'];
              $temp_min10 = $clima['list'][10]['main']['temp_min'];
              $temp_max10 = $clima['list'][10]['main']['temp_max'];
              $pressure10 = $clima['list'][10]['main']['pressure'];
              $sea_level10 = $clima['list'][10]['main']['sea_level'];
              $ground_level10 = $clima['list'][10]['main']['grnd_level'];
              $humidity10 = $clima['list'][10]['main']['humidity'];
              $internal_parameter10 = $clima['list'][10]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm10 = round($pressure10*0.00098692, 3);
            //weather list
              $weather_condition_id10 = $clima['list'][10]['weather'][0]['id'];
              $weather_condition_main10 = $clima['list'][10]['weather'][0]['main'];
              $weather_description10 = $clima['list'][10]['weather'][0]['description'];
              $weather_icon10x = $clima['list'][10]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon10x == "01d") {
                $weather_icon10 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon10x == "01n") {
                $weather_icon10 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon10x == "02d") {
                $weather_icon10 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon10x == "02n") {
                $weather_icon10 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon10x == "03d") {
                $weather_icon10 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon10x == "03n") {
                $weather_icon10 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon10x == "04d") {
                $weather_icon10 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon10x == "04n") {
                $weather_icon10 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon10x == "9d"){ //
                $weather_icon10 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon10x == "9n"){ //
                $weather_icon10 = "🌧";
              /*

              */
              }
              elseif ($weather_icon10x == "10d"){
                $weather_icon10 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon10x == "10n"){
                $weather_icon10 = "🌦";
              /*

              */
              }
              elseif($weather_icon10x == "11d"){
                $weather_icon10 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon10x == "11n"){
                $weather_icon10 = "⛈";
                /*

                */
              }
              elseif ($weather_icon10x == "13d") {
                $weather_icon10 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon10x == "13n") {
              $weather_icon10 = "🌨";
              /*

              */
              }
              elseif ($weather_icon10x == "50d") {
                $weather_icon10 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon10x == "50n") {
                $weather_icon10 = "🌫";
                /*

                */
              }
            //clouds
              $clouds10 = $clima['list'][10]['clouds']['all'];
            //wind
              $wind_speed10 = $clima['list'][10]['wind']['speed'];
              $wind_deg10 = $clima['list'][10]['wind']['deg'];
              $degre10 = (int)$wind_deg10;
            //direction
              if($degre10 == 0){
                $direction_wind10 = 'North';
              }
              elseif($degre10 > 0 && $degre10 <45){
                $direction_wind10 = 'North-NorthEast';
              }
              elseif ($degre10 == 45) {
                $direction_wind10 = 'North-East';
              }
              elseif ($degre10 > 45 && $degre10 <90){
                $direction_wind10 = 'East-NorthEast';
              }
              elseif ($degre10 == 90){
                $direction_wind10 = 'East';
              }
              elseif($degre10 > 90 && $degre10 <135){
                $direction_wind10 = 'East-SouthEast';
              }
              elseif($degre10 == 135){
                $direction_wind10 = 'South-East';
              }
              elseif($degre10 > 135 && $degre10 <180){
                $direction_wind10 = 'South-SouthEast';
              }
              elseif($degre10 == 180){
                $direction_wind10 = 'South';
              }
              elseif($degre10 > 180 && $degre10 <225){
                $direction_wind10 = 'West-SouthWest';
              }
              elseif($degre10 == 225){
                $direction_wind10 = 'South-West';
              }
              elseif($degre10 > 225 && $degre10 <270){
                $direction_wind10 = 'West-SouthWest';
              }
              elseif($degre10 == 270){
                $direction_wind10 = 'West';
              }
              elseif($degre10 > 270 && $degre10 <315){
                $direction_wind10 = 'West-NortWest';
              }
              elseif($degre10 == 315){
                $direction_wind10 = 'North-West';
              }
              elseif($degre10 > 315 && $degre10 <360){
                $direction_wind10 = 'North-NorthWest';
              }
              else{
                $direction_wind10 = 'North';
              }
            //sea
              if($wind_speed10 <= 0.3){
                $wind_force10 = 'Calm';
                $sea_force10 = 'Zero force';
              }
              elseif($wind_speed10 > 0.3 && $wind_speed10 < 1.5){
                $wind_force10 = 'Burr of wind';
                $sea_force10 = 'Force one';
              }
              elseif($wind_speed10 > 1.5 && $wind_speed10 < 3.3){
                $wind_force10 = 'Light breeze';
                $sea_force10 = 'Force two';
              }
              elseif($wind_speed10 > 3.3 && $wind_speed10 < 5.4){
                $wind_force10 = 'Breeze';
                $sea_force10 = 'Force two';
              }
              elseif($wind_speed10 > 5.4 && $wind_speed10 < 7.9){
                $wind_force10 = 'Livelly breeze';
                $sea_force10 = 'Force three';
              }
              elseif($wind_speed10 > 7.9 && $wind_speed10 < 10.7){
                $wind_force10 = 'Tense breeze';
                $sea_force10 = 'Force four';
              }
              elseif($wind_speed10 > 10.7 && $wind_speed10 < 13.8){
                $wind_force10 = 'Fresh wind';
                $sea_force10 = 'Force five';
              }
              elseif($wind_speed10 > 13.8 && $wind_speed10 < 17.1){
                $wind_force10 = 'Strong wind';
                $sea_force10 = 'Force six';
              }
              elseif($wind_speed10 > 17.1 && $wind_speed10 < 20.7){
                $wind_force10 = 'Moderate storm';
                $sea_force10 = 'Force seven';
              }
              elseif($wind_speed10 > 20.7 && $wind_speed10 < 24.4){
                $wind_force10 = 'Strong storm';
                $sea_force10 = 'Force eight';
              }
              elseif($wind_speed10 > 24.4 && $wind_speed10 < 28.4){
                $wind_force10 = 'Storm';
                $sea_force10 = 'Force nine';
              }
              elseif($wind_speed10 > 28.4 && $wind_speed10 < 32.6){
                $wind_force10 = 'Fortunale';
                $sea_force10 = 'Force ten';
              }
              elseif($wind_speed10 > 32.6){
                $wind_force10 = 'Hurricane';
                $sea_force10 = 'Force ten';
              }
              else{
                $wind_force10 = 'zero';
                $sea_force10 = 'zero';
              }
            //rain
              $rain10 = $clima['list'][10]['rain']['3h'];
            //snow
              $snow10 = $clima['list'][10]['snow']['3h'];
            //data/time of calculation
              $data_time_pre10 = $clima['list'][10]['dt_txt'];
              $data_time_confr10 = substr($data_time_pre10,0,11);
              $data_time10 = substr($data_time_pre10,11);

            //11
              $temperatura11 = $clima['list'][11]['main']['temp'];
              $temp_min11 = $clima['list'][11]['main']['temp_min'];
              $temp_max11 = $clima['list'][11]['main']['temp_max'];
              $pressure11 = $clima['list'][11]['main']['pressure'];
              $sea_level11 = $clima['list'][11]['main']['sea_level'];
              $ground_level11 = $clima['list'][11]['main']['grnd_level'];
              $humidity11 = $clima['list'][11]['main']['humidity'];
              $internal_parameter11 = $clima['list'][11]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm11 = round($pressure11*0.00098692, 3);
            //weather list
              $weather_condition_id11 = $clima['list'][11]['weather'][0]['id'];
              $weather_condition_main11 = $clima['list'][11]['weather'][0]['main'];
              $weather_description11 = $clima['list'][11]['weather'][0]['description'];
              $weather_icon11x = $clima['list'][11]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon11x == "01d") {
                $weather_icon11 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon11x == "01n") {
                $weather_icon11 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon11x == "02d") {
                $weather_icon11 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon11x == "02n") {
                $weather_icon11 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon11x == "03d") {
                $weather_icon11 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon11x == "03n") {
                $weather_icon11 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon11x == "04d") {
                $weather_icon11 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon11x == "04n") {
                $weather_icon11 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon11x == "9d"){ //
                $weather_icon11 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon11x == "9n"){ //
                $weather_icon11 = "🌧";
              /*

              */
              }
              elseif ($weather_icon11x == "10d"){
                $weather_icon11 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon11x == "10n"){
                $weather_icon11 = "🌦";
              /*

              */
              }
              elseif($weather_icon11x == "11d"){
                $weather_icon11 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon11x == "11n"){
                $weather_icon11 = "⛈";
                /*

                */
              }
              elseif ($weather_icon11x == "13d") {
                $weather_icon11 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon11x == "13n") {
              $weather_icon11 = "🌨";
              /*

              */
              }
              elseif ($weather_icon11x == "50d") {
                $weather_icon11 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon11x == "50n") {
                $weather_icon11 = "🌫";
                /*

                */
              }
            //clouds
              $clouds11 = $clima['list'][11]['clouds']['all'];
            //wind
              $wind_speed11 = $clima['list'][11]['wind']['speed'];
              $wind_deg11 = $clima['list'][11]['wind']['deg'];
              $degre11 = (int)$wind_deg11;
            //direction
              if($degre11 == 0){
                $direction_wind11 = 'North';
              }
              elseif($degre11 > 0 && $degre11 <45){
                $direction_wind11 = 'North-NorthEast';
              }
              elseif ($degre11 == 45) {
                $direction_wind11 = 'North-East';
              }
              elseif ($degre11 > 45 && $degre11 <90){
                $direction_wind11 = 'East-NorthEast';
              }
              elseif ($degre11 == 90){
                $direction_wind11 = 'East';
              }
              elseif($degre11 > 90 && $degre11 <135){
                $direction_wind11 = 'East-SouthEast';
              }
              elseif($degre11 == 135){
                $direction_wind11 = 'South-East';
              }
              elseif($degre11 > 135 && $degre11 <180){
                $direction_wind11 = 'South-SouthEast';
              }
              elseif($degre11 == 180){
                $direction_wind11 = 'South';
              }
              elseif($degre11 > 180 && $degre11 <225){
                $direction_wind11 = 'West-SouthWest';
              }
              elseif($degre11 == 225){
                $direction_wind11 = 'South-West';
              }
              elseif($degre11 > 225 && $degre11 <270){
                $direction_wind11 = 'West-SouthWest';
              }
              elseif($degre11 == 270){
                $direction_wind11 = 'West';
              }
              elseif($degre11 > 270 && $degre11 <315){
                $direction_wind11 = 'West-NortWest';
              }
              elseif($degre11 == 315){
                $direction_wind11 = 'North-West';
              }
              elseif($degre11 > 315 && $degre11 <360){
                $direction_wind11 = 'North-NorthWest';
              }
              else{
                $direction_wind11 = 'North';
              }
            //sea
              if($wind_speed11 <= 0.3){
                $wind_force11 = 'Calm';
                $sea_force11 = 'Zero force';
              }
              elseif($wind_speed11 > 0.3 && $wind_speed11 < 1.5){
                $wind_force11 = 'Burr of wind';
                $sea_force11 = 'Force one';
              }
              elseif($wind_speed11 > 1.5 && $wind_speed11 < 3.3){
                $wind_force11 = 'Light breeze';
                $sea_force11 = 'Force two';
              }
              elseif($wind_speed11 > 3.3 && $wind_speed11 < 5.4){
                $wind_force11 = 'Breeze';
                $sea_force11 = 'Force two';
              }
              elseif($wind_speed11 > 5.4 && $wind_speed11 < 7.9){
                $wind_force11 = 'Livelly breeze';
                $sea_force11 = 'Force three';
              }
              elseif($wind_speed11 > 7.9 && $wind_speed11 < 10.7){
                $wind_force11 = 'Tense breeze';
                $sea_force11 = 'Force four';
              }
              elseif($wind_speed11 > 10.7 && $wind_speed11 < 13.8){
                $wind_force11 = 'Fresh wind';
                $sea_force11 = 'Force five';
              }
              elseif($wind_speed11 > 13.8 && $wind_speed11 < 17.1){
                $wind_force11 = 'Strong wind';
                $sea_force11 = 'Force six';
              }
              elseif($wind_speed11 > 17.1 && $wind_speed11 < 20.7){
                $wind_force11 = 'Moderate storm';
                $sea_force11 = 'Force seven';
              }
              elseif($wind_speed11 > 20.7 && $wind_speed11 < 24.4){
                $wind_force11 = 'Strong storm';
                $sea_force11 = 'Force eight';
              }
              elseif($wind_speed11 > 24.4 && $wind_speed11 < 28.4){
                $wind_force11 = 'Storm';
                $sea_force11 = 'Force nine';
              }
              elseif($wind_speed11 > 28.4 && $wind_speed11 < 32.6){
                $wind_force11 = 'Fortunale';
                $sea_force11 = 'Force ten';
              }
              elseif($wind_speed11 > 32.6){
                $wind_force11 = 'Hurricane';
                $sea_force11 = 'Force ten';
              }
              else{
                $wind_force11 = 'zero';
                $sea_force11 = 'zero';
              }
            //rain
              $rain11 = $clima['list'][11]['rain']['3h'];
            //snow
              $snow11 = $clima['list'][11]['snow']['3h'];
            //data/time of calculation
              $data_time_pre11 = $clima['list'][11]['dt_txt'];
              $data_time_confr11 = substr($data_time_pre11,0,11);
              $data_time11 = substr($data_time_pre11,11);

            //12
              $temperatura12 = $clima['list'][12]['main']['temp'];
              $temp_min12 = $clima['list'][12]['main']['temp_min'];
              $temp_max12 = $clima['list'][12]['main']['temp_max'];
              $pressure12 = $clima['list'][12]['main']['pressure'];
              $sea_level12 = $clima['list'][12]['main']['sea_level'];
              $ground_level12 = $clima['list'][12]['main']['grnd_level'];
              $humidity12 = $clima['list'][12]['main']['humidity'];
              $internal_parameter12 = $clima['list'][12]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm12 = round($pressure12*0.00098692, 3);
            //weather list
              $weather_condition_id12 = $clima['list'][12]['weather'][0]['id'];
              $weather_condition_main12 = $clima['list'][12]['weather'][0]['main'];
              $weather_description12 = $clima['list'][12]['weather'][0]['description'];
              $weather_icon12x = $clima['list'][12]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon12x == "01d") {
                $weather_icon12 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon12x == "01n") {
                $weather_icon12 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon12x == "02d") {
                $weather_icon12 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon12x == "02n") {
                $weather_icon12 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon12x == "03d") {
                $weather_icon12 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon12x == "03n") {
                $weather_icon12 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon12x == "04d") {
                $weather_icon12 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon12x == "04n") {
                $weather_icon12 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon12x == "9d"){ //
                $weather_icon12 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon12x == "9n"){ //
                $weather_icon12 = "🌧";
              /*

              */
              }
              elseif ($weather_icon12x == "10d"){
                $weather_icon12 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon12x == "10n"){
                $weather_icon12 = "🌦";
              /*

              */
              }
              elseif($weather_icon12x == "11d"){
                $weather_icon12 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon12x == "11n"){
                $weather_icon12 = "⛈";
                /*

                */
              }
              elseif ($weather_icon12x == "13d") {
                $weather_icon12 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon12x == "13n") {
              $weather_icon12 = "🌨";
              /*

              */
              }
              elseif ($weather_icon12x == "50d") {
                $weather_icon12 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon12x == "50n") {
                $weather_icon12 = "🌫";
                /*

                */
              }
            //clouds
              $clouds12 = $clima['list'][12]['clouds']['all'];
            //wind
              $wind_speed12 = $clima['list'][12]['wind']['speed'];
              $wind_deg12 = $clima['list'][12]['wind']['deg'];
              $degre12 = (int)$wind_deg12;
            //direction
              if($degre12 == 0){
                $direction_wind12 = 'North';
              }
              elseif($degre12 > 0 && $degre12 <45){
                $direction_wind12 = 'North-NorthEast';
              }
              elseif ($degre12 == 45) {
                $direction_wind12 = 'North-East';
              }
              elseif ($degre12 > 45 && $degre12 <90){
                $direction_wind12 = 'East-NorthEast';
              }
              elseif ($degre12 == 90){
                $direction_wind12 = 'East';
              }
              elseif($degre12 > 90 && $degre12 <135){
                $direction_wind12 = 'East-SouthEast';
              }
              elseif($degre12 == 135){
                $direction_wind12 = 'South-East';
              }
              elseif($degre12 > 135 && $degre12 <180){
                $direction_wind12 = 'South-SouthEast';
              }
              elseif($degre12 == 180){
                $direction_wind12 = 'South';
              }
              elseif($degre12 > 180 && $degre12 <225){
                $direction_wind12 = 'West-SouthWest';
              }
              elseif($degre12 == 225){
                $direction_wind12 = 'South-West';
              }
              elseif($degre12 > 225 && $degre12 <270){
                $direction_wind12 = 'West-SouthWest';
              }
              elseif($degre12 == 270){
                $direction_wind12 = 'West';
              }
              elseif($degre12 > 270 && $degre12 <315){
                $direction_wind12 = 'West-NortWest';
              }
              elseif($degre12 == 315){
                $direction_wind12 = 'North-West';
              }
              elseif($degre12 > 315 && $degre12 <360){
                $direction_wind12 = 'North-NorthWest';
              }
              else{
                $direction_wind12 = 'North';
              }
            //sea
              if($wind_speed12 <= 0.3){
                $wind_force12 = 'Calm';
                $sea_force12 = 'Zero force';
              }
              elseif($wind_speed12 > 0.3 && $wind_speed12 < 1.5){
                $wind_force12 = 'Burr of wind';
                $sea_force12 = 'Force one';
              }
              elseif($wind_speed12 > 1.5 && $wind_speed12 < 3.3){
                $wind_force12 = 'Light breeze';
                $sea_force12 = 'Force two';
              }
              elseif($wind_speed12 > 3.3 && $wind_speed12 < 5.4){
                $wind_force12 = 'Breeze';
                $sea_force12 = 'Force two';
              }
              elseif($wind_speed12 > 5.4 && $wind_speed12 < 7.9){
                $wind_force12 = 'Livelly breeze';
                $sea_force12 = 'Force three';
              }
              elseif($wind_speed12 > 7.9 && $wind_speed12 < 10.7){
                $wind_force12 = 'Tense breeze';
                $sea_force12 = 'Force four';
              }
              elseif($wind_speed12 > 10.7 && $wind_speed12 < 13.8){
                $wind_force12 = 'Fresh wind';
                $sea_force12 = 'Force five';
              }
              elseif($wind_speed12 > 13.8 && $wind_speed12 < 17.1){
                $wind_force12 = 'Strong wind';
                $sea_force12 = 'Force six';
              }
              elseif($wind_speed12 > 17.1 && $wind_speed12 < 20.7){
                $wind_force12 = 'Moderate storm';
                $sea_force12 = 'Force seven';
              }
              elseif($wind_speed12 > 20.7 && $wind_speed12 < 24.4){
                $wind_force12 = 'Strong storm';
                $sea_force12 = 'Force eight';
              }
              elseif($wind_speed12 > 24.4 && $wind_speed12 < 28.4){
                $wind_force12 = 'Storm';
                $sea_force12 = 'Force nine';
              }
              elseif($wind_speed12 > 28.4 && $wind_speed12 < 32.6){
                $wind_force12 = 'Fortunale';
                $sea_force12 = 'Force ten';
              }
              elseif($wind_speed12 > 32.6){
                $wind_force12 = 'Hurricane';
                $sea_force12 = 'Force ten';
              }
              else{
                $wind_force12 = 'zero';
                $sea_force12 = 'zero';
              }
            //rain
              $rain12 = $clima['list'][12]['rain']['3h'];
            //snow
              $snow12 = $clima['list'][12]['snow']['3h'];
            //data/time of calculation
              $data_time_pre12 = $clima['list'][12]['dt_txt'];
              $data_time_confr12 = substr($data_time_pre12,0,11);
              $data_time12 = substr($data_time_pre12,11);

            //13
              $temperatura13 = $clima['list'][13]['main']['temp'];
              $temp_min13 = $clima['list'][13]['main']['temp_min'];
              $temp_max13 = $clima['list'][13]['main']['temp_max'];
              $pressure13 = $clima['list'][13]['main']['pressure'];
              $sea_level13 = $clima['list'][13]['main']['sea_level'];
              $ground_level13 = $clima['list'][13]['main']['grnd_level'];
              $humidity13 = $clima['list'][13]['main']['humidity'];
              $internal_parameter13 = $clima['list'][13]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm13 = round($pressure13*0.00098692, 3);
            //weather list
              $weather_condition_id13 = $clima['list'][13]['weather'][0]['id'];
              $weather_condition_main13 = $clima['list'][13]['weather'][0]['main'];
              $weather_description13 = $clima['list'][13]['weather'][0]['description'];
              $weather_icon13x = $clima['list'][13]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon13x == "01d") {
                $weather_icon13 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon13x == "01n") {
                $weather_icon13 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon13x == "02d") {
                $weather_icon13 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon13x == "02n") {
                $weather_icon13 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon13x == "03d") {
                $weather_icon13 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon13x == "03n") {
                $weather_icon13 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon13x == "04d") {
                $weather_icon13 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon13x == "04n") {
                $weather_icon13 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon13x == "9d"){ //
                $weather_icon13 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon13x == "9n"){ //
                $weather_icon13 = "🌧";
              /*

              */
              }
              elseif ($weather_icon13x == "10d"){
                $weather_icon13 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon13x == "10n"){
                $weather_icon13 = "🌦";
              /*

              */
              }
              elseif($weather_icon13x == "11d"){
                $weather_icon13 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon13x == "11n"){
                $weather_icon13 = "⛈";
                /*

                */
              }
              elseif ($weather_icon13x == "13d") {
                $weather_icon13 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon13x == "13n") {
              $weather_icon13 = "🌨";
              /*

              */
              }
              elseif ($weather_icon13x == "50d") {
                $weather_icon13 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon13x == "50n") {
                $weather_icon13 = "🌫";
                /*

                */
              }
            //clouds
              $clouds13 = $clima['list'][13]['clouds']['all'];
            //wind
              $wind_speed13 = $clima['list'][13]['wind']['speed'];
              $wind_deg13 = $clima['list'][13]['wind']['deg'];
              $degre13 = (int)$wind_deg13;
            //direction
              if($degre13 == 0){
                $direction_wind13 = 'North';
              }
              elseif($degre13 > 0 && $degre13 <45){
                $direction_wind13 = 'North-NorthEast';
              }
              elseif ($degre13 == 45) {
                $direction_wind13 = 'North-East';
              }
              elseif ($degre13 > 45 && $degre13 <90){
                $direction_wind13 = 'East-NorthEast';
              }
              elseif ($degre13 == 90){
                $direction_wind13 = 'East';
              }
              elseif($degre13 > 90 && $degre13 <135){
                $direction_wind13 = 'East-SouthEast';
              }
              elseif($degre13 == 135){
                $direction_wind13 = 'South-East';
              }
              elseif($degre13 > 135 && $degre13 <180){
                $direction_wind13 = 'South-SouthEast';
              }
              elseif($degre13 == 180){
                $direction_wind13 = 'South';
              }
              elseif($degre13 > 180 && $degre13 <225){
                $direction_wind13 = 'West-SouthWest';
              }
              elseif($degre13 == 225){
                $direction_wind13 = 'South-West';
              }
              elseif($degre13 > 225 && $degre13 <270){
                $direction_wind13 = 'West-SouthWest';
              }
              elseif($degre13 == 270){
                $direction_wind13 = 'West';
              }
              elseif($degre13 > 270 && $degre13 <315){
                $direction_wind13 = 'West-NortWest';
              }
              elseif($degre13 == 315){
                $direction_wind13 = 'North-West';
              }
              elseif($degre13 > 315 && $degre13 <360){
                $direction_wind13 = 'North-NorthWest';
              }
              else{
                $direction_wind13 = 'North';
              }
            //sea
              if($wind_speed13 <= 0.3){
                $wind_force13 = 'Calm';
                $sea_force13 = 'Zero force';
              }
              elseif($wind_speed13 > 0.3 && $wind_speed13 < 1.5){
                $wind_force13 = 'Burr of wind';
                $sea_force13 = 'Force one';
              }
              elseif($wind_speed13 > 1.5 && $wind_speed13 < 3.3){
                $wind_force13 = 'Light breeze';
                $sea_force13 = 'Force two';
              }
              elseif($wind_speed13 > 3.3 && $wind_speed13 < 5.4){
                $wind_force13 = 'Breeze';
                $sea_force13 = 'Force two';
              }
              elseif($wind_speed13 > 5.4 && $wind_speed13 < 7.9){
                $wind_force13 = 'Livelly breeze';
                $sea_force13 = 'Force three';
              }
              elseif($wind_speed13 > 7.9 && $wind_speed13 < 10.7){
                $wind_force13 = 'Tense breeze';
                $sea_force13 = 'Force four';
              }
              elseif($wind_speed13 > 10.7 && $wind_speed13 < 13.8){
                $wind_force13 = 'Fresh wind';
                $sea_force13 = 'Force five';
              }
              elseif($wind_speed13 > 13.8 && $wind_speed13 < 17.1){
                $wind_force13 = 'Strong wind';
                $sea_force13 = 'Force six';
              }
              elseif($wind_speed13 > 17.1 && $wind_speed13 < 20.7){
                $wind_force13 = 'Moderate storm';
                $sea_force13 = 'Force seven';
              }
              elseif($wind_speed13 > 20.7 && $wind_speed13 < 24.4){
                $wind_force13 = 'Strong storm';
                $sea_force13 = 'Force eight';
              }
              elseif($wind_speed13 > 24.4 && $wind_speed13 < 28.4){
                $wind_force13 = 'Storm';
                $sea_force13 = 'Force nine';
              }
              elseif($wind_speed13 > 28.4 && $wind_speed13 < 32.6){
                $wind_force13 = 'Fortunale';
                $sea_force13 = 'Force ten';
              }
              elseif($wind_speed13 > 32.6){
                $wind_force13 = 'Hurricane';
                $sea_force13 = 'Force ten';
              }
              else{
                $wind_force13 = 'zero';
                $sea_force13 = 'zero';
              }
            //rain
              $rain13 = $clima['list'][13]['rain']['3h'];
            //snow
              $snow13 = $clima['list'][13]['snow']['3h'];
            //data/time of calculation
              $data_time_pre13 = $clima['list'][13]['dt_txt'];
              $data_time_confr13 = substr($data_time_pre13,0,11);
              $data_time13 = substr($data_time_pre13,11);

            //14
              $temperatura14 = $clima['list'][14]['main']['temp'];
              $temp_min14 = $clima['list'][14]['main']['temp_min'];
              $temp_max14 = $clima['list'][14]['main']['temp_max'];
              $pressure14 = $clima['list'][14]['main']['pressure'];
              $sea_level14 = $clima['list'][14]['main']['sea_level'];
              $ground_level14 = $clima['list'][14]['main']['grnd_level'];
              $humidity14 = $clima['list'][14]['main']['humidity'];
              $internal_parameter14 = $clima['list'][14]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm14 = round($pressure14*0.00098692, 3);
            //weather list
              $weather_condition_id14 = $clima['list'][14]['weather'][0]['id'];
              $weather_condition_main14 = $clima['list'][14]['weather'][0]['main'];
              $weather_description14 = $clima['list'][14]['weather'][0]['description'];
              $weather_icon14x = $clima['list'][14]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon14x == "01d") {
                $weather_icon14 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon14x == "01n") {
                $weather_icon14 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon14x == "02d") {
                $weather_icon14 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon14x == "02n") {
                $weather_icon14 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon14x == "03d") {
                $weather_icon14 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon14x == "03n") {
                $weather_icon14 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon14x == "04d") {
                $weather_icon14 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon14x == "04n") {
                $weather_icon14 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon14x == "9d"){ //
                $weather_icon14 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon14x == "9n"){ //
                $weather_icon14 = "🌧";
              /*

              */
              }
              elseif ($weather_icon14x == "10d"){
                $weather_icon14 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon14x == "10n"){
                $weather_icon14 = "🌦";
              /*

              */
              }
              elseif($weather_icon14x == "11d"){
                $weather_icon14 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon14x == "11n"){
                $weather_icon14 = "⛈";
                /*

                */
              }
              elseif ($weather_icon14x == "13d") {
                $weather_icon14 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon14x == "13n") {
              $weather_icon14 = "🌨";
              /*

              */
              }
              elseif ($weather_icon14x == "50d") {
                $weather_icon14 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon14x == "50n") {
                $weather_icon14 = "🌫";
                /*

                */
              }
            //clouds
              $clouds14 = $clima['list'][14]['clouds']['all'];
            //wind
              $wind_speed14 = $clima['list'][14]['wind']['speed'];
              $wind_deg14 = $clima['list'][14]['wind']['deg'];
              $degre14 = (int)$wind_deg14;
            //direction
              if($degre14 == 0){
                $direction_wind14 = 'North';
              }
              elseif($degre14 > 0 && $degre14 <45){
                $direction_wind14 = 'North-NorthEast';
              }
              elseif ($degre14 == 45) {
                $direction_wind14 = 'North-East';
              }
              elseif ($degre14 > 45 && $degre14 <90){
                $direction_wind14 = 'East-NorthEast';
              }
              elseif ($degre14 == 90){
                $direction_wind14 = 'East';
              }
              elseif($degre14 > 90 && $degre14 <135){
                $direction_wind14 = 'East-SouthEast';
              }
              elseif($degre14 == 135){
                $direction_wind14 = 'South-East';
              }
              elseif($degre14 > 135 && $degre14 <180){
                $direction_wind14 = 'South-SouthEast';
              }
              elseif($degre14 == 180){
                $direction_wind14 = 'South';
              }
              elseif($degre14 > 180 && $degre14 <225){
                $direction_wind14 = 'West-SouthWest';
              }
              elseif($degre14 == 225){
                $direction_wind14 = 'South-West';
              }
              elseif($degre14 > 225 && $degre14 <270){
                $direction_wind14 = 'West-SouthWest';
              }
              elseif($degre14 == 270){
                $direction_wind14 = 'West';
              }
              elseif($degre14 > 270 && $degre14 <315){
                $direction_wind14 = 'West-NortWest';
              }
              elseif($degre14 == 315){
                $direction_wind14 = 'North-West';
              }
              elseif($degre14 > 315 && $degre14 <360){
                $direction_wind14 = 'North-NorthWest';
              }
              else{
                $direction_wind14 = 'North';
              }
            //sea
              if($wind_speed14 <= 0.3){
                $wind_force14 = 'Calm';
                $sea_force14 = 'Zero force';
              }
              elseif($wind_speed14 > 0.3 && $wind_speed14 < 1.5){
                $wind_force14 = 'Burr of wind';
                $sea_force14 = 'Force one';
              }
              elseif($wind_speed14 > 1.5 && $wind_speed14 < 3.3){
                $wind_force14 = 'Light breeze';
                $sea_force14 = 'Force two';
              }
              elseif($wind_speed14 > 3.3 && $wind_speed14 < 5.4){
                $wind_force14 = 'Breeze';
                $sea_force14 = 'Force two';
              }
              elseif($wind_speed14 > 5.4 && $wind_speed14 < 7.9){
                $wind_force14 = 'Livelly breeze';
                $sea_force14 = 'Force three';
              }
              elseif($wind_speed14 > 7.9 && $wind_speed14 < 10.7){
                $wind_force14 = 'Tense breeze';
                $sea_force14 = 'Force four';
              }
              elseif($wind_speed14 > 10.7 && $wind_speed14 < 13.8){
                $wind_force14 = 'Fresh wind';
                $sea_force14 = 'Force five';
              }
              elseif($wind_speed14 > 13.8 && $wind_speed14 < 17.1){
                $wind_force14 = 'Strong wind';
                $sea_force14 = 'Force six';
              }
              elseif($wind_speed14 > 17.1 && $wind_speed14 < 20.7){
                $wind_force14 = 'Moderate storm';
                $sea_force14 = 'Force seven';
              }
              elseif($wind_speed14 > 20.7 && $wind_speed14 < 24.4){
                $wind_force14 = 'Strong storm';
                $sea_force14 = 'Force eight';
              }
              elseif($wind_speed14 > 24.4 && $wind_speed14 < 28.4){
                $wind_force14 = 'Storm';
                $sea_force14 = 'Force nine';
              }
              elseif($wind_speed14 > 28.4 && $wind_speed14 < 32.6){
                $wind_force14 = 'Fortunale';
                $sea_force14 = 'Force ten';
              }
              elseif($wind_speed14 > 32.6){
                $wind_force14 = 'Hurricane';
                $sea_force14 = 'Force ten';
              }
              else{
                $wind_force14 = 'zero';
                $sea_force14 = 'zero';
              }
            //rain
              $rain14 = $clima['list'][14]['rain']['3h'];
            //snow
              $snow14 = $clima['list'][14]['snow']['3h'];
            //data/time of calculation
              $data_time_pre14 = $clima['list'][14]['dt_txt'];
              $data_time_confr14 = substr($data_time_pre14,0,11);
              $data_time14 = substr($data_time_pre14,11);

            //15
              $temperatura15 = $clima['list'][15]['main']['temp'];
              $temp_min15 = $clima['list'][15]['main']['temp_min'];
              $temp_max15 = $clima['list'][15]['main']['temp_max'];
              $pressure15 = $clima['list'][15]['main']['pressure'];
              $sea_level15 = $clima['list'][15]['main']['sea_level'];
              $ground_level15 = $clima['list'][15]['main']['grnd_level'];
              $humidity15 = $clima['list'][15]['main']['humidity'];
              $internal_parameter15 = $clima['list'][15]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm15 = round($pressure15*0.00098692, 3);
            //weather list
              $weather_condition_id15 = $clima['list'][15]['weather'][0]['id'];
              $weather_condition_main15 = $clima['list'][15]['weather'][0]['main'];
              $weather_description15 = $clima['list'][15]['weather'][0]['description'];
              $weather_icon15x = $clima['list'][15]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon15x == "01d") {
                $weather_icon15 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon15x == "01n") {
                $weather_icon15 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon15x == "02d") {
                $weather_icon15 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon15x == "02n") {
                $weather_icon15 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon15x == "03d") {
                $weather_icon15 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon15x == "03n") {
                $weather_icon15 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon15x == "04d") {
                $weather_icon15 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon15x == "04n") {
                $weather_icon15 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon15x == "9d"){ //
                $weather_icon15 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon15x == "9n"){ //
                $weather_icon15 = "🌧";
              /*

              */
              }
              elseif ($weather_icon15x == "10d"){
                $weather_icon15 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon15x == "10n"){
                $weather_icon15 = "🌦";
              /*

              */
              }
              elseif($weather_icon15x == "11d"){
                $weather_icon15 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon15x == "11n"){
                $weather_icon15 = "⛈";
                /*

                */
              }
              elseif ($weather_icon15x == "13d") {
                $weather_icon15 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon15x == "13n") {
              $weather_icon15 = "🌨";
              /*

              */
              }
              elseif ($weather_icon15x == "50d") {
                $weather_icon15 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon15x == "50n") {
                $weather_icon15 = "🌫";
                /*

                */
              }
            //clouds
              $clouds15 = $clima['list'][15]['clouds']['all'];
            //wind
              $wind_speed15 = $clima['list'][15]['wind']['speed'];
              $wind_deg15 = $clima['list'][15]['wind']['deg'];
              $degre15 = (int)$wind_deg15;
            //direction
              if($degre15 == 0){
                $direction_wind15 = 'North';
              }
              elseif($degre15 > 0 && $degre15 <45){
                $direction_wind15 = 'North-NorthEast';
              }
              elseif ($degre15 == 45) {
                $direction_wind15 = 'North-East';
              }
              elseif ($degre15 > 45 && $degre15 <90){
                $direction_wind15 = 'East-NorthEast';
              }
              elseif ($degre15 == 90){
                $direction_wind15 = 'East';
              }
              elseif($degre15 > 90 && $degre15 <135){
                $direction_wind15 = 'East-SouthEast';
              }
              elseif($degre15 == 135){
                $direction_wind15 = 'South-East';
              }
              elseif($degre15 > 135 && $degre15 <180){
                $direction_wind15 = 'South-SouthEast';
              }
              elseif($degre15 == 180){
                $direction_wind15 = 'South';
              }
              elseif($degre15 > 180 && $degre15 <225){
                $direction_wind15 = 'West-SouthWest';
              }
              elseif($degre15 == 225){
                $direction_wind15 = 'South-West';
              }
              elseif($degre15 > 225 && $degre15 <270){
                $direction_wind15 = 'West-SouthWest';
              }
              elseif($degre15 == 270){
                $direction_wind15 = 'West';
              }
              elseif($degre15 > 270 && $degre15 <315){
                $direction_wind15 = 'West-NortWest';
              }
              elseif($degre15 == 315){
                $direction_wind15 = 'North-West';
              }
              elseif($degre15 > 315 && $degre15 <360){
                $direction_wind15 = 'North-NorthWest';
              }
              else{
                $direction_wind15 = 'North';
              }
            //sea
              if($wind_speed15 <= 0.3){
                $wind_force15 = 'Calm';
                $sea_force15 = 'Zero force';
              }
              elseif($wind_speed15 > 0.3 && $wind_speed15 < 1.5){
                $wind_force15 = 'Burr of wind';
                $sea_force15 = 'Force one';
              }
              elseif($wind_speed15 > 1.5 && $wind_speed15 < 3.3){
                $wind_force15 = 'Light breeze';
                $sea_force15 = 'Force two';
              }
              elseif($wind_speed15 > 3.3 && $wind_speed15 < 5.4){
                $wind_force15 = 'Breeze';
                $sea_force15 = 'Force two';
              }
              elseif($wind_speed15 > 5.4 && $wind_speed15 < 7.9){
                $wind_force15 = 'Livelly breeze';
                $sea_force15 = 'Force three';
              }
              elseif($wind_speed15 > 7.9 && $wind_speed15 < 10.7){
                $wind_force15 = 'Tense breeze';
                $sea_force15 = 'Force four';
              }
              elseif($wind_speed15 > 10.7 && $wind_speed15 < 13.8){
                $wind_force15 = 'Fresh wind';
                $sea_force15 = 'Force five';
              }
              elseif($wind_speed15 > 13.8 && $wind_speed15 < 17.1){
                $wind_force15 = 'Strong wind';
                $sea_force15 = 'Force six';
              }
              elseif($wind_speed15 > 17.1 && $wind_speed15 < 20.7){
                $wind_force15 = 'Moderate storm';
                $sea_force15 = 'Force seven';
              }
              elseif($wind_speed15 > 20.7 && $wind_speed15 < 24.4){
                $wind_force15 = 'Strong storm';
                $sea_force15 = 'Force eight';
              }
              elseif($wind_speed15 > 24.4 && $wind_speed15 < 28.4){
                $wind_force15 = 'Storm';
                $sea_force15 = 'Force nine';
              }
              elseif($wind_speed15 > 28.4 && $wind_speed15 < 32.6){
                $wind_force15 = 'Fortunale';
                $sea_force15 = 'Force ten';
              }
              elseif($wind_speed15 > 32.6){
                $wind_force15 = 'Hurricane';
                $sea_force15 = 'Force ten';
              }
              else{
                $wind_force15 = 'zero';
                $sea_force15 = 'zero';
              }
            //rain
              $rain15 = $clima['list'][15]['rain']['3h'];
            //snow
              $snow15 = $clima['list'][15]['snow']['3h'];
            //data/time of calculation
              $data_time_pre15 = $clima['list'][15]['dt_txt'];
              $data_time_confr15 = substr($data_time_pre15,0,11);
              $data_time15 = substr($data_time_pre15,11);

            //16
              $temperatura16 = $clima['list'][16]['main']['temp'];
              $temp_min16 = $clima['list'][16]['main']['temp_min'];
              $temp_max16 = $clima['list'][16]['main']['temp_max'];
              $pressure16 = $clima['list'][16]['main']['pressure'];
              $sea_level16 = $clima['list'][16]['main']['sea_level'];
              $ground_level16 = $clima['list'][16]['main']['grnd_level'];
              $humidity16 = $clima['list'][16]['main']['humidity'];
              $internal_parameter16 = $clima['list'][16]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm16 = round($pressure16*0.00098692, 3);
            //weather list
              $weather_condition_id16 = $clima['list'][16]['weather'][0]['id'];
              $weather_condition_main16 = $clima['list'][16]['weather'][0]['main'];
              $weather_description16 = $clima['list'][16]['weather'][0]['description'];
              $weather_icon16x = $clima['list'][16]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon16x == "01d") {
                $weather_icon16 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon16x == "01n") {
                $weather_icon16 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon16x == "02d") {
                $weather_icon16 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon16x == "02n") {
                $weather_icon16 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon16x == "03d") {
                $weather_icon16 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon16x == "03n") {
                $weather_icon16 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon16x == "04d") {
                $weather_icon16 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon16x == "04n") {
                $weather_icon16 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon16x == "9d"){ //
                $weather_icon16 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon16x == "9n"){ //
                $weather_icon16 = "🌧";
              /*

              */
              }
              elseif ($weather_icon16x == "10d"){
                $weather_icon16 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon16x == "10n"){
                $weather_icon16 = "🌦";
              /*

              */
              }
              elseif($weather_icon16x == "11d"){
                $weather_icon16 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon16x == "11n"){
                $weather_icon16 = "⛈";
                /*

                */
              }
              elseif ($weather_icon16x == "13d") {
                $weather_icon16 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon16x == "13n") {
              $weather_icon16 = "🌨";
              /*

              */
              }
              elseif ($weather_icon16x == "50d") {
                $weather_icon16 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon16x == "50n") {
                $weather_icon16 = "🌫";
                /*

                */
              }
            //clouds
              $clouds16 = $clima['list'][16]['clouds']['all'];
            //wind
              $wind_speed16 = $clima['list'][16]['wind']['speed'];
              $wind_deg16 = $clima['list'][16]['wind']['deg'];
              $degre16 = (int)$wind_deg16;
            //direction
              if($degre16 == 0){
                $direction_wind16 = 'North';
              }
              elseif($degre16 > 0 && $degre16 <45){
                $direction_wind16 = 'North-NorthEast';
              }
              elseif ($degre16 == 45) {
                $direction_wind16 = 'North-East';
              }
              elseif ($degre16 > 45 && $degre16 <90){
                $direction_wind16 = 'East-NorthEast';
              }
              elseif ($degre16 == 90){
                $direction_wind16 = 'East';
              }
              elseif($degre16 > 90 && $degre16 <135){
                $direction_wind16 = 'East-SouthEast';
              }
              elseif($degre16 == 135){
                $direction_wind16 = 'South-East';
              }
              elseif($degre16 > 135 && $degre16 <180){
                $direction_wind16 = 'South-SouthEast';
              }
              elseif($degre16 == 180){
                $direction_wind16 = 'South';
              }
              elseif($degre16 > 180 && $degre16 <225){
                $direction_wind16 = 'West-SouthWest';
              }
              elseif($degre16 == 225){
                $direction_wind16 = 'South-West';
              }
              elseif($degre16 > 225 && $degre16 <270){
                $direction_wind16 = 'West-SouthWest';
              }
              elseif($degre16 == 270){
                $direction_wind16 = 'West';
              }
              elseif($degre16 > 270 && $degre16 <315){
                $direction_wind16 = 'West-NortWest';
              }
              elseif($degre16 == 315){
                $direction_wind16 = 'North-West';
              }
              elseif($degre16 > 315 && $degre16 <360){
                $direction_wind16 = 'North-NorthWest';
              }
              else{
                $direction_wind16 = 'North';
              }
            //sea
              if($wind_speed16 <= 0.3){
                $wind_force16 = 'Calm';
                $sea_force16 = 'Zero force';
              }
              elseif($wind_speed16 > 0.3 && $wind_speed16 < 1.5){
                $wind_force16 = 'Burr of wind';
                $sea_force16 = 'Force one';
              }
              elseif($wind_speed16 > 1.5 && $wind_speed16 < 3.3){
                $wind_force16 = 'Light breeze';
                $sea_force16 = 'Force two';
              }
              elseif($wind_speed16 > 3.3 && $wind_speed16 < 5.4){
                $wind_force16 = 'Breeze';
                $sea_force16 = 'Force two';
              }
              elseif($wind_speed16 > 5.4 && $wind_speed16 < 7.9){
                $wind_force16 = 'Livelly breeze';
                $sea_force16 = 'Force three';
              }
              elseif($wind_speed16 > 7.9 && $wind_speed16 < 10.7){
                $wind_force16 = 'Tense breeze';
                $sea_force16 = 'Force four';
              }
              elseif($wind_speed16 > 10.7 && $wind_speed16 < 13.8){
                $wind_force16 = 'Fresh wind';
                $sea_force16 = 'Force five';
              }
              elseif($wind_speed16 > 13.8 && $wind_speed16 < 17.1){
                $wind_force16 = 'Strong wind';
                $sea_force16 = 'Force six';
              }
              elseif($wind_speed16 > 17.1 && $wind_speed16 < 20.7){
                $wind_force16 = 'Moderate storm';
                $sea_force16 = 'Force seven';
              }
              elseif($wind_speed16 > 20.7 && $wind_speed16 < 24.4){
                $wind_force16 = 'Strong storm';
                $sea_force16 = 'Force eight';
              }
              elseif($wind_speed16 > 24.4 && $wind_speed16 < 28.4){
                $wind_force16 = 'Storm';
                $sea_force16 = 'Force nine';
              }
              elseif($wind_speed16 > 28.4 && $wind_speed16 < 32.6){
                $wind_force16 = 'Fortunale';
                $sea_force16 = 'Force ten';
              }
              elseif($wind_speed16 > 32.6){
                $wind_force16 = 'Hurricane';
                $sea_force16 = 'Force ten';
              }
              else{
                $wind_force16 = 'zero';
                $sea_force16 = 'zero';
              }
            //rain
              $rain16 = $clima['list'][16]['rain']['3h'];
            //snow
              $snow16 = $clima['list'][16]['snow']['3h'];
            //data/time of calculation
              $data_time_pre16 = $clima['list'][16]['dt_txt'];
              $data_time_confr16 = substr($data_time_pre16,0,11);
              $data_time16 = substr($data_time_pre16,11);

            //17
              $temperatura17 = $clima['list'][17]['main']['temp'];
              $temp_min17 = $clima['list'][17]['main']['temp_min'];
              $temp_max17 = $clima['list'][17]['main']['temp_max'];
              $pressure17 = $clima['list'][17]['main']['pressure'];
              $sea_level17 = $clima['list'][17]['main']['sea_level'];
              $ground_level17 = $clima['list'][17]['main']['grnd_level'];
              $humidity17 = $clima['list'][17]['main']['humidity'];
              $internal_parameter17 = $clima['list'][17]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm17 = round($pressure17*0.00098692, 3);
            //weather list
              $weather_condition_id17 = $clima['list'][17]['weather'][0]['id'];
              $weather_condition_main17 = $clima['list'][17]['weather'][0]['main'];
              $weather_description17 = $clima['list'][17]['weather'][0]['description'];
              $weather_icon17x = $clima['list'][17]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon17x == "01d") {
                $weather_icon17 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon17x == "01n") {
                $weather_icon17 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon17x == "02d") {
                $weather_icon17 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon17x == "02n") {
                $weather_icon17 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon17x == "03d") {
                $weather_icon17 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon17x == "03n") {
                $weather_icon17 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon17x == "04d") {
                $weather_icon17 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon17x == "04n") {
                $weather_icon17 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon17x == "9d"){ //
                $weather_icon17 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon17x == "9n"){ //
                $weather_icon17 = "🌧";
              /*

              */
              }
              elseif ($weather_icon17x == "10d"){
                $weather_icon17 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon17x == "10n"){
                $weather_icon17 = "🌦";
              /*

              */
              }
              elseif($weather_icon17x == "11d"){
                $weather_icon17 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon17x == "11n"){
                $weather_icon17 = "⛈";
                /*

                */
              }
              elseif ($weather_icon17x == "13d") {
                $weather_icon17 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon17x == "13n") {
              $weather_icon17 = "🌨";
              /*

              */
              }
              elseif ($weather_icon17x == "50d") {
                $weather_icon17 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon17x == "50n") {
                $weather_icon17 = "🌫";
                /*

                */
              }
            //clouds
              $clouds17 = $clima['list'][17]['clouds']['all'];
            //wind
              $wind_speed17 = $clima['list'][17]['wind']['speed'];
              $wind_deg17 = $clima['list'][17]['wind']['deg'];
              $degre17 = (int)$wind_deg17;
            //direction
              if($degre17 == 0){
                $direction_wind17 = 'North';
              }
              elseif($degre17 > 0 && $degre17 <45){
                $direction_wind17 = 'North-NorthEast';
              }
              elseif ($degre17 == 45) {
                $direction_wind17 = 'North-East';
              }
              elseif ($degre17 > 45 && $degre17 <90){
                $direction_wind17 = 'East-NorthEast';
              }
              elseif ($degre17 == 90){
                $direction_wind17 = 'East';
              }
              elseif($degre17 > 90 && $degre17 <135){
                $direction_wind17 = 'East-SouthEast';
              }
              elseif($degre17 == 135){
                $direction_wind17 = 'South-East';
              }
              elseif($degre17 > 135 && $degre17 <180){
                $direction_wind17 = 'South-SouthEast';
              }
              elseif($degre17 == 180){
                $direction_wind17 = 'South';
              }
              elseif($degre17 > 180 && $degre17 <225){
                $direction_wind17 = 'West-SouthWest';
              }
              elseif($degre17 == 225){
                $direction_wind17 = 'South-West';
              }
              elseif($degre17 > 225 && $degre17 <270){
                $direction_wind17 = 'West-SouthWest';
              }
              elseif($degre17 == 270){
                $direction_wind17 = 'West';
              }
              elseif($degre17 > 270 && $degre17 <315){
                $direction_wind17 = 'West-NortWest';
              }
              elseif($degre17 == 315){
                $direction_wind17 = 'North-West';
              }
              elseif($degre17 > 315 && $degre17 <360){
                $direction_wind17 = 'North-NorthWest';
              }
              else{
                $direction_wind17 = 'North';
              }
            //sea
              if($wind_speed17 <= 0.3){
                $wind_force17 = 'Calm';
                $sea_force17 = 'Zero force';
              }
              elseif($wind_speed17 > 0.3 && $wind_speed17 < 1.5){
                $wind_force17 = 'Burr of wind';
                $sea_force17 = 'Force one';
              }
              elseif($wind_speed17 > 1.5 && $wind_speed17 < 3.3){
                $wind_force17 = 'Light breeze';
                $sea_force17 = 'Force two';
              }
              elseif($wind_speed17 > 3.3 && $wind_speed17 < 5.4){
                $wind_force17 = 'Breeze';
                $sea_force17 = 'Force two';
              }
              elseif($wind_speed17 > 5.4 && $wind_speed17 < 7.9){
                $wind_force17 = 'Livelly breeze';
                $sea_force17 = 'Force three';
              }
              elseif($wind_speed17 > 7.9 && $wind_speed17 < 10.7){
                $wind_force17 = 'Tense breeze';
                $sea_force17 = 'Force four';
              }
              elseif($wind_speed17 > 10.7 && $wind_speed17 < 13.8){
                $wind_force17 = 'Fresh wind';
                $sea_force17 = 'Force five';
              }
              elseif($wind_speed17 > 13.8 && $wind_speed17 < 17.1){
                $wind_force17 = 'Strong wind';
                $sea_force17 = 'Force six';
              }
              elseif($wind_speed17 > 17.1 && $wind_speed17 < 20.7){
                $wind_force17 = 'Moderate storm';
                $sea_force17 = 'Force seven';
              }
              elseif($wind_speed17 > 20.7 && $wind_speed17 < 24.4){
                $wind_force17 = 'Strong storm';
                $sea_force17 = 'Force eight';
              }
              elseif($wind_speed17 > 24.4 && $wind_speed17 < 28.4){
                $wind_force17 = 'Storm';
                $sea_force17 = 'Force nine';
              }
              elseif($wind_speed17 > 28.4 && $wind_speed17 < 32.6){
                $wind_force17 = 'Fortunale';
                $sea_force17 = 'Force ten';
              }
              elseif($wind_speed17 > 32.6){
                $wind_force17 = 'Hurricane';
                $sea_force17 = 'Force ten';
              }
              else{
                $wind_force17 = 'zero';
                $sea_force17 = 'zero';
              }
            //rain
              $rain17 = $clima['list'][17]['rain']['3h'];
            //snow
              $snow17 = $clima['list'][17]['snow']['3h'];
            //data/time of calculation
              $data_time_pre17 = $clima['list'][17]['dt_txt'];
              $data_time_confr17 = substr($data_time_pre17,0,11);
              $data_time17 = substr($data_time_pre17,11);

            //18
              $temperatura18 = $clima['list'][18]['main']['temp'];
              $temp_min18 = $clima['list'][18]['main']['temp_min'];
              $temp_max18 = $clima['list'][18]['main']['temp_max'];
              $pressure18 = $clima['list'][18]['main']['pressure'];
              $sea_level18 = $clima['list'][18]['main']['sea_level'];
              $ground_level18 = $clima['list'][18]['main']['grnd_level'];
              $humidity18 = $clima['list'][18]['main']['humidity'];
              $internal_parameter18 = $clima['list'][18]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm18 = round($pressure18*0.00098692, 3);
            //weather list
              $weather_condition_id18 = $clima['list'][18]['weather'][0]['id'];
              $weather_condition_main18 = $clima['list'][18]['weather'][0]['main'];
              $weather_description18 = $clima['list'][18]['weather'][0]['description'];
              $weather_icon18x = $clima['list'][18]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon18x == "01d") {
                $weather_icon18 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon18x == "01n") {
                $weather_icon18 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon18x == "02d") {
                $weather_icon18 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon18x == "02n") {
                $weather_icon18 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon18x == "03d") {
                $weather_icon18 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon18x == "03n") {
                $weather_icon18 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon18x == "04d") {
                $weather_icon18 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon18x == "04n") {
                $weather_icon18 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon18x == "9d"){ //
                $weather_icon18 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon18x == "9n"){ //
                $weather_icon18 = "🌧";
              /*

              */
              }
              elseif ($weather_icon18x == "10d"){
                $weather_icon18 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon18x == "10n"){
                $weather_icon18 = "🌦";
              /*

              */
              }
              elseif($weather_icon18x == "11d"){
                $weather_icon18 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon18x == "11n"){
                $weather_icon18 = "⛈";
                /*

                */
              }
              elseif ($weather_icon18x == "13d") {
                $weather_icon18 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon18x == "13n") {
              $weather_icon18 = "🌨";
              /*

              */
              }
              elseif ($weather_icon18x == "50d") {
                $weather_icon18 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon18x == "50n") {
                $weather_icon18 = "🌫";
                /*

                */
              }
            //clouds
              $clouds18 = $clima['list'][18]['clouds']['all'];
            //wind
              $wind_speed18 = $clima['list'][18]['wind']['speed'];
              $wind_deg18 = $clima['list'][18]['wind']['deg'];
              $degre18 = (int)$wind_deg18;
            //direction
              if($degre18 == 0){
                $direction_wind18 = 'North';
              }
              elseif($degre18 > 0 && $degre18 <45){
                $direction_wind18 = 'North-NorthEast';
              }
              elseif ($degre18 == 45) {
                $direction_wind18 = 'North-East';
              }
              elseif ($degre18 > 45 && $degre18 <90){
                $direction_wind18 = 'East-NorthEast';
              }
              elseif ($degre18 == 90){
                $direction_wind18 = 'East';
              }
              elseif($degre18 > 90 && $degre18 <135){
                $direction_wind18 = 'East-SouthEast';
              }
              elseif($degre18 == 135){
                $direction_wind18 = 'South-East';
              }
              elseif($degre18 > 135 && $degre18 <180){
                $direction_wind18 = 'South-SouthEast';
              }
              elseif($degre18 == 180){
                $direction_wind18 = 'South';
              }
              elseif($degre18 > 180 && $degre18 <225){
                $direction_wind18 = 'West-SouthWest';
              }
              elseif($degre18 == 225){
                $direction_wind18 = 'South-West';
              }
              elseif($degre18 > 225 && $degre18 <270){
                $direction_wind18 = 'West-SouthWest';
              }
              elseif($degre18 == 270){
                $direction_wind18 = 'West';
              }
              elseif($degre18 > 270 && $degre18 <315){
                $direction_wind18 = 'West-NortWest';
              }
              elseif($degre18 == 315){
                $direction_wind18 = 'North-West';
              }
              elseif($degre18 > 315 && $degre18 <360){
                $direction_wind18 = 'North-NorthWest';
              }
              else{
                $direction_wind18 = 'North';
              }
            //sea
              if($wind_speed18 <= 0.3){
                $wind_force18 = 'Calm';
                $sea_force18 = 'Zero force';
              }
              elseif($wind_speed18 > 0.3 && $wind_speed18 < 1.5){
                $wind_force18 = 'Burr of wind';
                $sea_force18 = 'Force one';
              }
              elseif($wind_speed18 > 1.5 && $wind_speed18 < 3.3){
                $wind_force18 = 'Light breeze';
                $sea_force18 = 'Force two';
              }
              elseif($wind_speed18 > 3.3 && $wind_speed18 < 5.4){
                $wind_force18 = 'Breeze';
                $sea_force18 = 'Force two';
              }
              elseif($wind_speed18 > 5.4 && $wind_speed18 < 7.9){
                $wind_force18 = 'Livelly breeze';
                $sea_force18 = 'Force three';
              }
              elseif($wind_speed18 > 7.9 && $wind_speed18 < 10.7){
                $wind_force18 = 'Tense breeze';
                $sea_force18 = 'Force four';
              }
              elseif($wind_speed18 > 10.7 && $wind_speed18 < 13.8){
                $wind_force18 = 'Fresh wind';
                $sea_force18 = 'Force five';
              }
              elseif($wind_speed18 > 13.8 && $wind_speed18 < 17.1){
                $wind_force18 = 'Strong wind';
                $sea_force18 = 'Force six';
              }
              elseif($wind_speed18 > 17.1 && $wind_speed18 < 20.7){
                $wind_force18 = 'Moderate storm';
                $sea_force18 = 'Force seven';
              }
              elseif($wind_speed18 > 20.7 && $wind_speed18 < 24.4){
                $wind_force18 = 'Strong storm';
                $sea_force18 = 'Force eight';
              }
              elseif($wind_speed18 > 24.4 && $wind_speed18 < 28.4){
                $wind_force18 = 'Storm';
                $sea_force18 = 'Force nine';
              }
              elseif($wind_speed18 > 28.4 && $wind_speed18 < 32.6){
                $wind_force18 = 'Fortunale';
                $sea_force18 = 'Force ten';
              }
              elseif($wind_speed18 > 32.6){
                $wind_force18 = 'Hurricane';
                $sea_force18 = 'Force ten';
              }
              else{
                $wind_force18 = 'zero';
                $sea_force18 = 'zero';
              }
            //rain
              $rain18 = $clima['list'][18]['rain']['3h'];
            //snow
              $snow18 = $clima['list'][18]['snow']['3h'];
            //data/time of calculation
              $data_time_pre18 = $clima['list'][18]['dt_txt'];
              $data_time_confr18 = substr($data_time_pre18,0,11);
              $data_time18 = substr($data_time_pre18,11);

            //19
              $temperatura19 = $clima['list'][19]['main']['temp'];
              $temp_min19 = $clima['list'][19]['main']['temp_min'];
              $temp_max19 = $clima['list'][19]['main']['temp_max'];
              $pressure19 = $clima['list'][19]['main']['pressure'];
              $sea_level19 = $clima['list'][19]['main']['sea_level'];
              $ground_level19 = $clima['list'][19]['main']['grnd_level'];
              $humidity19 = $clima['list'][19]['main']['humidity'];
              $internal_parameter19 = $clima['list'][19]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm19 = round($pressure19*0.00098692, 3);
            //weather list
              $weather_condition_id19 = $clima['list'][19]['weather'][0]['id'];
              $weather_condition_main19 = $clima['list'][19]['weather'][0]['main'];
              $weather_description19 = $clima['list'][19]['weather'][0]['description'];
              $weather_icon19x = $clima['list'][19]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon19x == "01d") {
                $weather_icon19 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon19x == "01n") {
                $weather_icon19 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon19x == "02d") {
                $weather_icon19 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon19x == "02n") {
                $weather_icon19 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon19x == "03d") {
                $weather_icon19 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon19x == "03n") {
                $weather_icon19 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon19x == "04d") {
                $weather_icon19 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon19x == "04n") {
                $weather_icon19 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon19x == "9d"){ //
                $weather_icon19 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon19x == "9n"){ //
                $weather_icon19 = "🌧";
              /*

              */
              }
              elseif ($weather_icon19x == "10d"){
                $weather_icon19 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon19x == "10n"){
                $weather_icon19 = "🌦";
              /*

              */
              }
              elseif($weather_icon19x == "11d"){
                $weather_icon19 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon19x == "11n"){
                $weather_icon19 = "⛈";
                /*

                */
              }
              elseif ($weather_icon19x == "13d") {
                $weather_icon19 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon19x == "13n") {
              $weather_icon19 = "🌨";
              /*

              */
              }
              elseif ($weather_icon19x == "50d") {
                $weather_icon19 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon19x == "50n") {
                $weather_icon19 = "🌫";
                /*

                */
              }
            //clouds
              $clouds19 = $clima['list'][19]['clouds']['all'];
            //wind
              $wind_speed19 = $clima['list'][19]['wind']['speed'];
              $wind_deg19 = $clima['list'][19]['wind']['deg'];
              $degre19 = (int)$wind_deg19;
            //direction
              if($degre19 == 0){
                $direction_wind19 = 'North';
              }
              elseif($degre19 > 0 && $degre19 <45){
                $direction_wind19 = 'North-NorthEast';
              }
              elseif ($degre19 == 45) {
                $direction_wind19 = 'North-East';
              }
              elseif ($degre19 > 45 && $degre19 <90){
                $direction_wind19 = 'East-NorthEast';
              }
              elseif ($degre19 == 90){
                $direction_wind19 = 'East';
              }
              elseif($degre19 > 90 && $degre19 <135){
                $direction_wind19 = 'East-SouthEast';
              }
              elseif($degre19 == 135){
                $direction_wind19 = 'South-East';
              }
              elseif($degre19 > 135 && $degre19 <180){
                $direction_wind19 = 'South-SouthEast';
              }
              elseif($degre19 == 180){
                $direction_wind19 = 'South';
              }
              elseif($degre19 > 180 && $degre19 <225){
                $direction_wind19 = 'West-SouthWest';
              }
              elseif($degre19 == 225){
                $direction_wind19 = 'South-West';
              }
              elseif($degre19 > 225 && $degre19 <270){
                $direction_wind19 = 'West-SouthWest';
              }
              elseif($degre19 == 270){
                $direction_wind19 = 'West';
              }
              elseif($degre19 > 270 && $degre19 <315){
                $direction_wind19 = 'West-NortWest';
              }
              elseif($degre19 == 315){
                $direction_wind19 = 'North-West';
              }
              elseif($degre19 > 315 && $degre19 <360){
                $direction_wind19 = 'North-NorthWest';
              }
              else{
                $direction_wind19 = 'North';
              }
            //sea
              if($wind_speed19 <= 0.3){
                $wind_force19 = 'Calm';
                $sea_force19 = 'Zero force';
              }
              elseif($wind_speed19 > 0.3 && $wind_speed19 < 1.5){
                $wind_force19 = 'Burr of wind';
                $sea_force19 = 'Force one';
              }
              elseif($wind_speed19 > 1.5 && $wind_speed19 < 3.3){
                $wind_force19 = 'Light breeze';
                $sea_force19 = 'Force two';
              }
              elseif($wind_speed19 > 3.3 && $wind_speed19 < 5.4){
                $wind_force19 = 'Breeze';
                $sea_force19 = 'Force two';
              }
              elseif($wind_speed19 > 5.4 && $wind_speed19 < 7.9){
                $wind_force19 = 'Livelly breeze';
                $sea_force19 = 'Force three';
              }
              elseif($wind_speed19 > 7.9 && $wind_speed19 < 10.7){
                $wind_force19 = 'Tense breeze';
                $sea_force19 = 'Force four';
              }
              elseif($wind_speed19 > 10.7 && $wind_speed19 < 13.8){
                $wind_force19 = 'Fresh wind';
                $sea_force19 = 'Force five';
              }
              elseif($wind_speed19 > 13.8 && $wind_speed19 < 17.1){
                $wind_force19 = 'Strong wind';
                $sea_force19 = 'Force six';
              }
              elseif($wind_speed19 > 17.1 && $wind_speed19 < 20.7){
                $wind_force19 = 'Moderate storm';
                $sea_force19 = 'Force seven';
              }
              elseif($wind_speed19 > 20.7 && $wind_speed19 < 24.4){
                $wind_force19 = 'Strong storm';
                $sea_force19 = 'Force eight';
              }
              elseif($wind_speed19 > 24.4 && $wind_speed19 < 28.4){
                $wind_force19 = 'Storm';
                $sea_force19 = 'Force nine';
              }
              elseif($wind_speed19 > 28.4 && $wind_speed19 < 32.6){
                $wind_force19 = 'Fortunale';
                $sea_force19 = 'Force ten';
              }
              elseif($wind_speed19 > 32.6){
                $wind_force19 = 'Hurricane';
                $sea_force19 = 'Force ten';
              }
              else{
                $wind_force19 = 'zero';
                $sea_force19 = 'zero';
              }
            //rain
              $rain19 = $clima['list'][19]['rain']['3h'];
            //snow
              $snow19 = $clima['list'][19]['snow']['3h'];
            //data/time of calculation
              $data_time_pre19 = $clima['list'][19]['dt_txt'];
              $data_time_confr19 = substr($data_time_pre19,0,11);
              $data_time19 = substr($data_time_pre19,11);

            //20
              $temperatura20 = $clima['list'][20]['main']['temp'];
              $temp_min20 = $clima['list'][20]['main']['temp_min'];
              $temp_max20 = $clima['list'][20]['main']['temp_max'];
              $pressure20 = $clima['list'][20]['main']['pressure'];
              $sea_level20 = $clima['list'][20]['main']['sea_level'];
              $ground_level20 = $clima['list'][20]['main']['grnd_level'];
              $humidity20 = $clima['list'][20]['main']['humidity'];
              $internal_parameter20 = $clima['list'][20]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm20 = round($pressure20*0.00098692, 3);
            //weather list
              $weather_condition_id20 = $clima['list'][20]['weather'][0]['id'];
              $weather_condition_main20 = $clima['list'][20]['weather'][0]['main'];
              $weather_description20 = $clima['list'][20]['weather'][0]['description'];
              $weather_icon20x = $clima['list'][20]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon20x == "01d") {
                $weather_icon20 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon20x == "01n") {
                $weather_icon20 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon20x == "02d") {
                $weather_icon20 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon20x == "02n") {
                $weather_icon20 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon20x == "03d") {
                $weather_icon20 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon20x == "03n") {
                $weather_icon20 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon20x == "04d") {
                $weather_icon20 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon20x == "04n") {
                $weather_icon20 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon20x == "9d"){ //
                $weather_icon20 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon20x == "9n"){ //
                $weather_icon20 = "🌧";
              /*

              */
              }
              elseif ($weather_icon20x == "10d"){
                $weather_icon20 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon20x == "10n"){
                $weather_icon20 = "🌦";
              /*

              */
              }
              elseif($weather_icon20x == "11d"){
                $weather_icon20 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon20x == "11n"){
                $weather_icon20 = "⛈";
                /*

                */
              }
              elseif ($weather_icon20x == "13d") {
                $weather_icon20 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon20x == "13n") {
              $weather_icon20 = "🌨";
              /*

              */
              }
              elseif ($weather_icon20x == "50d") {
                $weather_icon20 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon20x == "50n") {
                $weather_icon20 = "🌫";
                /*

                */
              }
            //clouds
              $clouds20 = $clima['list'][20]['clouds']['all'];
            //wind
              $wind_speed20 = $clima['list'][20]['wind']['speed'];
              $wind_deg20 = $clima['list'][20]['wind']['deg'];
              $degre20 = (int)$wind_deg20;
            //direction
              if($degre20 == 0){
                $direction_wind20 = 'North';
              }
              elseif($degre20 > 0 && $degre20 <45){
                $direction_wind20 = 'North-NorthEast';
              }
              elseif ($degre20 == 45) {
                $direction_wind20 = 'North-East';
              }
              elseif ($degre20 > 45 && $degre20 <90){
                $direction_wind20 = 'East-NorthEast';
              }
              elseif ($degre20 == 90){
                $direction_wind20 = 'East';
              }
              elseif($degre20 > 90 && $degre20 <135){
                $direction_wind20 = 'East-SouthEast';
              }
              elseif($degre20 == 135){
                $direction_wind20 = 'South-East';
              }
              elseif($degre20 > 135 && $degre20 <180){
                $direction_wind20 = 'South-SouthEast';
              }
              elseif($degre20 == 180){
                $direction_wind20 = 'South';
              }
              elseif($degre20 > 180 && $degre20 <225){
                $direction_wind20 = 'West-SouthWest';
              }
              elseif($degre20 == 225){
                $direction_wind20 = 'South-West';
              }
              elseif($degre20 > 225 && $degre20 <270){
                $direction_wind20 = 'West-SouthWest';
              }
              elseif($degre20 == 270){
                $direction_wind20 = 'West';
              }
              elseif($degre20 > 270 && $degre20 <315){
                $direction_wind20 = 'West-NortWest';
              }
              elseif($degre20 == 315){
                $direction_wind20 = 'North-West';
              }
              elseif($degre20 > 315 && $degre20 <360){
                $direction_wind20 = 'North-NorthWest';
              }
              else{
                $direction_wind20 = 'North';
              }
            //sea
              if($wind_speed20 <= 0.3){
                $wind_force20 = 'Calm';
                $sea_force20 = 'Zero force';
              }
              elseif($wind_speed20 > 0.3 && $wind_speed20 < 1.5){
                $wind_force20 = 'Burr of wind';
                $sea_force20 = 'Force one';
              }
              elseif($wind_speed20 > 1.5 && $wind_speed20 < 3.3){
                $wind_force20 = 'Light breeze';
                $sea_force20 = 'Force two';
              }
              elseif($wind_speed20 > 3.3 && $wind_speed20 < 5.4){
                $wind_force20 = 'Breeze';
                $sea_force20 = 'Force two';
              }
              elseif($wind_speed20 > 5.4 && $wind_speed20 < 7.9){
                $wind_force20 = 'Livelly breeze';
                $sea_force20 = 'Force three';
              }
              elseif($wind_speed20 > 7.9 && $wind_speed20 < 10.7){
                $wind_force20 = 'Tense breeze';
                $sea_force20 = 'Force four';
              }
              elseif($wind_speed20 > 10.7 && $wind_speed20 < 13.8){
                $wind_force20 = 'Fresh wind';
                $sea_force20 = 'Force five';
              }
              elseif($wind_speed20 > 13.8 && $wind_speed20 < 17.1){
                $wind_force20 = 'Strong wind';
                $sea_force20 = 'Force six';
              }
              elseif($wind_speed20 > 17.1 && $wind_speed20 < 20.7){
                $wind_force20 = 'Moderate storm';
                $sea_force20 = 'Force seven';
              }
              elseif($wind_speed20 > 20.7 && $wind_speed20 < 24.4){
                $wind_force20 = 'Strong storm';
                $sea_force20 = 'Force eight';
              }
              elseif($wind_speed20 > 24.4 && $wind_speed20 < 28.4){
                $wind_force20 = 'Storm';
                $sea_force20 = 'Force nine';
              }
              elseif($wind_speed20 > 28.4 && $wind_speed20 < 32.6){
                $wind_force20 = 'Fortunale';
                $sea_force20 = 'Force ten';
              }
              elseif($wind_speed20 > 32.6){
                $wind_force20 = 'Hurricane';
                $sea_force20 = 'Force ten';
              }
              else{
                $wind_force20 = 'zero';
                $sea_force20 = 'zero';
              }
            //rain
              $rain20 = $clima['list'][20]['rain']['3h'];
            //snow
              $snow20 = $clima['list'][20]['snow']['3h'];
            //data/time of calculation
              $data_time_pre20 = $clima['list'][20]['dt_txt'];
              $data_time_confr20 = substr($data_time_pre20,0,11);
              $data_time20 = substr($data_time_pre20,11);

            //21
              $temperatura21 = $clima['list'][21]['main']['temp'];
              $temp_min21 = $clima['list'][21]['main']['temp_min'];
              $temp_max21 = $clima['list'][21]['main']['temp_max'];
              $pressure21 = $clima['list'][21]['main']['pressure'];
              $sea_level21 = $clima['list'][21]['main']['sea_level'];
              $ground_level21 = $clima['list'][21]['main']['grnd_level'];
              $humidity21 = $clima['list'][21]['main']['humidity'];
              $internal_parameter21 = $clima['list'][21]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm21 = round($pressure21*0.00098692, 3);
            //weather list
              $weather_condition_id21 = $clima['list'][21]['weather'][0]['id'];
              $weather_condition_main21 = $clima['list'][21]['weather'][0]['main'];
              $weather_description21 = $clima['list'][21]['weather'][0]['description'];
              $weather_icon21x = $clima['list'][21]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon21x == "01d") {
                $weather_icon21 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon21x == "01n") {
                $weather_icon21 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon21x == "02d") {
                $weather_icon21 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon21x == "02n") {
                $weather_icon21 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon21x == "03d") {
                $weather_icon21 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon21x == "03n") {
                $weather_icon21 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon21x == "04d") {
                $weather_icon21 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon21x == "04n") {
                $weather_icon21 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon21x == "9d"){ //
                $weather_icon21 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon21x == "9n"){ //
                $weather_icon21 = "🌧";
              /*

              */
              }
              elseif ($weather_icon21x == "10d"){
                $weather_icon21 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon21x == "10n"){
                $weather_icon21 = "🌦";
              /*

              */
              }
              elseif($weather_icon21x == "11d"){
                $weather_icon21 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon21x == "11n"){
                $weather_icon21 = "⛈";
                /*

                */
              }
              elseif ($weather_icon21x == "13d") {
                $weather_icon21 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon21x == "13n") {
              $weather_icon21 = "🌨";
              /*

              */
              }
              elseif ($weather_icon21x == "50d") {
                $weather_icon21 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon21x == "50n") {
                $weather_icon21 = "🌫";
                /*

                */
              }
            //clouds
              $clouds21 = $clima['list'][21]['clouds']['all'];
            //wind
              $wind_speed21 = $clima['list'][21]['wind']['speed'];
              $wind_deg21 = $clima['list'][21]['wind']['deg'];
              $degre21 = (int)$wind_deg21;
            //direction
              if($degre21 == 0){
                $direction_wind21 = 'North';
              }
              elseif($degre21 > 0 && $degre21 <45){
                $direction_wind21 = 'North-NorthEast';
              }
              elseif ($degre21 == 45) {
                $direction_wind21 = 'North-East';
              }
              elseif ($degre21 > 45 && $degre21 <90){
                $direction_wind21 = 'East-NorthEast';
              }
              elseif ($degre21 == 90){
                $direction_wind21 = 'East';
              }
              elseif($degre21 > 90 && $degre21 <135){
                $direction_wind21 = 'East-SouthEast';
              }
              elseif($degre21 == 135){
                $direction_wind21 = 'South-East';
              }
              elseif($degre21 > 135 && $degre21 <180){
                $direction_wind21 = 'South-SouthEast';
              }
              elseif($degre21 == 180){
                $direction_wind21 = 'South';
              }
              elseif($degre21 > 180 && $degre21 <225){
                $direction_wind21 = 'West-SouthWest';
              }
              elseif($degre21 == 225){
                $direction_wind21 = 'South-West';
              }
              elseif($degre21 > 225 && $degre21 <270){
                $direction_wind21 = 'West-SouthWest';
              }
              elseif($degre21 == 270){
                $direction_wind21 = 'West';
              }
              elseif($degre21 > 270 && $degre21 <315){
                $direction_wind21 = 'West-NortWest';
              }
              elseif($degre21 == 315){
                $direction_wind21 = 'North-West';
              }
              elseif($degre21 > 315 && $degre21 <360){
                $direction_wind21 = 'North-NorthWest';
              }
              else{
                $direction_wind21 = 'North';
              }
            //sea
              if($wind_speed21 <= 0.3){
                $wind_force21 = 'Calm';
                $sea_force21 = 'Zero force';
              }
              elseif($wind_speed21 > 0.3 && $wind_speed21 < 1.5){
                $wind_force21 = 'Burr of wind';
                $sea_force21 = 'Force one';
              }
              elseif($wind_speed21 > 1.5 && $wind_speed21 < 3.3){
                $wind_force21 = 'Light breeze';
                $sea_force21 = 'Force two';
              }
              elseif($wind_speed21 > 3.3 && $wind_speed21 < 5.4){
                $wind_force21 = 'Breeze';
                $sea_force21 = 'Force two';
              }
              elseif($wind_speed21 > 5.4 && $wind_speed21 < 7.9){
                $wind_force21 = 'Livelly breeze';
                $sea_force21 = 'Force three';
              }
              elseif($wind_speed21 > 7.9 && $wind_speed21 < 10.7){
                $wind_force21 = 'Tense breeze';
                $sea_force21 = 'Force four';
              }
              elseif($wind_speed21 > 10.7 && $wind_speed21 < 13.8){
                $wind_force21 = 'Fresh wind';
                $sea_force21 = 'Force five';
              }
              elseif($wind_speed21 > 13.8 && $wind_speed21 < 17.1){
                $wind_force21 = 'Strong wind';
                $sea_force21 = 'Force six';
              }
              elseif($wind_speed21 > 17.1 && $wind_speed21 < 20.7){
                $wind_force21 = 'Moderate storm';
                $sea_force21 = 'Force seven';
              }
              elseif($wind_speed21 > 20.7 && $wind_speed21 < 24.4){
                $wind_force21 = 'Strong storm';
                $sea_force21 = 'Force eight';
              }
              elseif($wind_speed21 > 24.4 && $wind_speed21 < 28.4){
                $wind_force21 = 'Storm';
                $sea_force21 = 'Force nine';
              }
              elseif($wind_speed21 > 28.4 && $wind_speed21 < 32.6){
                $wind_force21 = 'Fortunale';
                $sea_force21 = 'Force ten';
              }
              elseif($wind_speed21 > 32.6){
                $wind_force21 = 'Hurricane';
                $sea_force21 = 'Force ten';
              }
              else{
                $wind_force21 = 'zero';
                $sea_force21 = 'zero';
              }
            //rain
              $rain21 = $clima['list'][21]['rain']['3h'];
            //snow
              $snow21 = $clima['list'][21]['snow']['3h'];
            //data/time of calculation
              $data_time_pre21 = $clima['list'][21]['dt_txt'];
              $data_time_confr21 = substr($data_time_pre21,0,11);
              $data_time21 = substr($data_time_pre21,11);

            //22
              $temperatura22 = $clima['list'][22]['main']['temp'];
              $temp_min22 = $clima['list'][22]['main']['temp_min'];
              $temp_max22 = $clima['list'][22]['main']['temp_max'];
              $pressure22 = $clima['list'][22]['main']['pressure'];
              $sea_level22 = $clima['list'][22]['main']['sea_level'];
              $ground_level22 = $clima['list'][22]['main']['grnd_level'];
              $humidity22 = $clima['list'][22]['main']['humidity'];
              $internal_parameter22 = $clima['list'][22]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm22 = round($pressure22*0.00098692, 3);
            //weather list
              $weather_condition_id22 = $clima['list'][22]['weather'][0]['id'];
              $weather_condition_main22 = $clima['list'][22]['weather'][0]['main'];
              $weather_description22 = $clima['list'][22]['weather'][0]['description'];
              $weather_icon22x = $clima['list'][22]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon22x == "01d") {
                $weather_icon22 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon22x == "01n") {
                $weather_icon22 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon22x == "02d") {
                $weather_icon22 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon22x == "02n") {
                $weather_icon22 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon22x == "03d") {
                $weather_icon22 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon22x == "03n") {
                $weather_icon22 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon22x == "04d") {
                $weather_icon22 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon22x == "04n") {
                $weather_icon22 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon22x == "9d"){ //
                $weather_icon22 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon22x == "9n"){ //
                $weather_icon22 = "🌧";
              /*

              */
              }
              elseif ($weather_icon22x == "10d"){
                $weather_icon22 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon22x == "10n"){
                $weather_icon22 = "🌦";
              /*

              */
              }
              elseif($weather_icon22x == "11d"){
                $weather_icon22 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon22x == "11n"){
                $weather_icon22 = "⛈";
                /*

                */
              }
              elseif ($weather_icon22x == "13d") {
                $weather_icon22 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon22x == "13n") {
              $weather_icon22 = "🌨";
              /*

              */
              }
              elseif ($weather_icon22x == "50d") {
                $weather_icon22 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon22x == "50n") {
                $weather_icon22 = "🌫";
                /*

                */
              }
            //clouds
              $clouds22 = $clima['list'][22]['clouds']['all'];
            //wind
              $wind_speed22 = $clima['list'][22]['wind']['speed'];
              $wind_deg22 = $clima['list'][22]['wind']['deg'];
              $degre22 = (int)$wind_deg22;
            //direction
              if($degre22 == 0){
                $direction_wind22 = 'North';
              }
              elseif($degre22 > 0 && $degre22 <45){
                $direction_wind22 = 'North-NorthEast';
              }
              elseif ($degre22 == 45) {
                $direction_wind22 = 'North-East';
              }
              elseif ($degre22 > 45 && $degre22 <90){
                $direction_wind22 = 'East-NorthEast';
              }
              elseif ($degre22 == 90){
                $direction_wind22 = 'East';
              }
              elseif($degre22 > 90 && $degre22 <135){
                $direction_wind22 = 'East-SouthEast';
              }
              elseif($degre22 == 135){
                $direction_wind22 = 'South-East';
              }
              elseif($degre22 > 135 && $degre22 <180){
                $direction_wind22 = 'South-SouthEast';
              }
              elseif($degre22 == 180){
                $direction_wind22 = 'South';
              }
              elseif($degre22 > 180 && $degre22 <225){
                $direction_wind22 = 'West-SouthWest';
              }
              elseif($degre22 == 225){
                $direction_wind22 = 'South-West';
              }
              elseif($degre22 > 225 && $degre22 <270){
                $direction_wind22 = 'West-SouthWest';
              }
              elseif($degre22 == 270){
                $direction_wind22 = 'West';
              }
              elseif($degre22 > 270 && $degre22 <315){
                $direction_wind22 = 'West-NortWest';
              }
              elseif($degre22 == 315){
                $direction_wind22 = 'North-West';
              }
              elseif($degre22 > 315 && $degre22 <360){
                $direction_wind22 = 'North-NorthWest';
              }
              else{
                $direction_wind22 = 'North';
              }
            //sea
              if($wind_speed22 <= 0.3){
                $wind_force22 = 'Calm';
                $sea_force22 = 'Zero force';
              }
              elseif($wind_speed22 > 0.3 && $wind_speed22 < 1.5){
                $wind_force22 = 'Burr of wind';
                $sea_force22 = 'Force one';
              }
              elseif($wind_speed22 > 1.5 && $wind_speed22 < 3.3){
                $wind_force22 = 'Light breeze';
                $sea_force22 = 'Force two';
              }
              elseif($wind_speed22 > 3.3 && $wind_speed22 < 5.4){
                $wind_force22 = 'Breeze';
                $sea_force22 = 'Force two';
              }
              elseif($wind_speed22 > 5.4 && $wind_speed22 < 7.9){
                $wind_force22 = 'Livelly breeze';
                $sea_force22 = 'Force three';
              }
              elseif($wind_speed22 > 7.9 && $wind_speed22 < 10.7){
                $wind_force22 = 'Tense breeze';
                $sea_force22 = 'Force four';
              }
              elseif($wind_speed22 > 10.7 && $wind_speed22 < 13.8){
                $wind_force22 = 'Fresh wind';
                $sea_force22 = 'Force five';
              }
              elseif($wind_speed22 > 13.8 && $wind_speed22 < 17.1){
                $wind_force22 = 'Strong wind';
                $sea_force22 = 'Force six';
              }
              elseif($wind_speed22 > 17.1 && $wind_speed22 < 20.7){
                $wind_force22 = 'Moderate storm';
                $sea_force22 = 'Force seven';
              }
              elseif($wind_speed22 > 20.7 && $wind_speed22 < 24.4){
                $wind_force22 = 'Strong storm';
                $sea_force22 = 'Force eight';
              }
              elseif($wind_speed22 > 24.4 && $wind_speed22 < 28.4){
                $wind_force22 = 'Storm';
                $sea_force22 = 'Force nine';
              }
              elseif($wind_speed22 > 28.4 && $wind_speed22 < 32.6){
                $wind_force22 = 'Fortunale';
                $sea_force22 = 'Force ten';
              }
              elseif($wind_speed22 > 32.6){
                $wind_force22 = 'Hurricane';
                $sea_force22 = 'Force ten';
              }
              else{
                $wind_force22 = 'zero';
                $sea_force22 = 'zero';
              }
            //rain
              $rain22 = $clima['list'][22]['rain']['3h'];
            //snow
              $snow22 = $clima['list'][22]['snow']['3h'];
            //data/time of calculation
              $data_time_pre22 = $clima['list'][22]['dt_txt'];
              $data_time_confr22 = substr($data_time_pre22,0,11);
              $data_time22 = substr($data_time_pre22,11);

            //23
              $temperatura23 = $clima['list'][23]['main']['temp'];
              $temp_min23 = $clima['list'][23]['main']['temp_min'];
              $temp_max23 = $clima['list'][23]['main']['temp_max'];
              $pressure23 = $clima['list'][23]['main']['pressure'];
              $sea_level23 = $clima['list'][23]['main']['sea_level'];
              $ground_level23 = $clima['list'][23]['main']['grnd_level'];
              $humidity23 = $clima['list'][23]['main']['humidity'];
              $internal_parameter23 = $clima['list'][23]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm23 = round($pressure23*0.00098692, 3);
            //weather list
              $weather_condition_id23 = $clima['list'][23]['weather'][0]['id'];
              $weather_condition_main23 = $clima['list'][23]['weather'][0]['main'];
              $weather_description23 = $clima['list'][23]['weather'][0]['description'];
              $weather_icon23x = $clima['list'][23]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon23x == "01d") {
                $weather_icon23 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon23x == "01n") {
                $weather_icon23 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon23x == "02d") {
                $weather_icon23 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon23x == "02n") {
                $weather_icon23 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon23x == "03d") {
                $weather_icon23 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon23x == "03n") {
                $weather_icon23 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon23x == "04d") {
                $weather_icon23 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon23x == "04n") {
                $weather_icon23 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon23x == "9d"){ //
                $weather_icon23 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon23x == "9n"){ //
                $weather_icon23 = "🌧";
              /*

              */
              }
              elseif ($weather_icon23x == "10d"){
                $weather_icon23 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon23x == "10n"){
                $weather_icon23 = "🌦";
              /*

              */
              }
              elseif($weather_icon23x == "11d"){
                $weather_icon23 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon23x == "11n"){
                $weather_icon23 = "⛈";
                /*

                */
              }
              elseif ($weather_icon23x == "13d") {
                $weather_icon23 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon23x == "13n") {
              $weather_icon23 = "🌨";
              /*

              */
              }
              elseif ($weather_icon23x == "50d") {
                $weather_icon23 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon23x == "50n") {
                $weather_icon23 = "🌫";
                /*

                */
              }
            //clouds
              $clouds23 = $clima['list'][23]['clouds']['all'];
            //wind
              $wind_speed23 = $clima['list'][23]['wind']['speed'];
              $wind_deg23 = $clima['list'][23]['wind']['deg'];
              $degre23 = (int)$wind_deg23;
            //direction
              if($degre23 == 0){
                $direction_wind23 = 'North';
              }
              elseif($degre23 > 0 && $degre23 <45){
                $direction_wind23 = 'North-NorthEast';
              }
              elseif ($degre23 == 45) {
                $direction_wind23 = 'North-East';
              }
              elseif ($degre23 > 45 && $degre23 <90){
                $direction_wind23 = 'East-NorthEast';
              }
              elseif ($degre23 == 90){
                $direction_wind23 = 'East';
              }
              elseif($degre23 > 90 && $degre23 <135){
                $direction_wind23 = 'East-SouthEast';
              }
              elseif($degre23 == 135){
                $direction_wind23 = 'South-East';
              }
              elseif($degre23 > 135 && $degre23 <180){
                $direction_wind23 = 'South-SouthEast';
              }
              elseif($degre23 == 180){
                $direction_wind23 = 'South';
              }
              elseif($degre23 > 180 && $degre23 <225){
                $direction_wind23 = 'West-SouthWest';
              }
              elseif($degre23 == 225){
                $direction_wind23 = 'South-West';
              }
              elseif($degre23 > 225 && $degre23 <270){
                $direction_wind23 = 'West-SouthWest';
              }
              elseif($degre23 == 270){
                $direction_wind23 = 'West';
              }
              elseif($degre23 > 270 && $degre23 <315){
                $direction_wind23 = 'West-NortWest';
              }
              elseif($degre23 == 315){
                $direction_wind23 = 'North-West';
              }
              elseif($degre23 > 315 && $degre23 <360){
                $direction_wind23 = 'North-NorthWest';
              }
              else{
                $direction_wind23 = 'North';
              }
            //sea
              if($wind_speed23 <= 0.3){
                $wind_force23 = 'Calm';
                $sea_force23 = 'Zero force';
              }
              elseif($wind_speed23 > 0.3 && $wind_speed23 < 1.5){
                $wind_force23 = 'Burr of wind';
                $sea_force23 = 'Force one';
              }
              elseif($wind_speed23 > 1.5 && $wind_speed23 < 3.3){
                $wind_force23 = 'Light breeze';
                $sea_force23 = 'Force two';
              }
              elseif($wind_speed23 > 3.3 && $wind_speed23 < 5.4){
                $wind_force23 = 'Breeze';
                $sea_force23 = 'Force two';
              }
              elseif($wind_speed23 > 5.4 && $wind_speed23 < 7.9){
                $wind_force23 = 'Livelly breeze';
                $sea_force23 = 'Force three';
              }
              elseif($wind_speed23 > 7.9 && $wind_speed23 < 10.7){
                $wind_force23 = 'Tense breeze';
                $sea_force23 = 'Force four';
              }
              elseif($wind_speed23 > 10.7 && $wind_speed23 < 13.8){
                $wind_force23 = 'Fresh wind';
                $sea_force23 = 'Force five';
              }
              elseif($wind_speed23 > 13.8 && $wind_speed23 < 17.1){
                $wind_force23 = 'Strong wind';
                $sea_force23 = 'Force six';
              }
              elseif($wind_speed23 > 17.1 && $wind_speed23 < 20.7){
                $wind_force23 = 'Moderate storm';
                $sea_force23 = 'Force seven';
              }
              elseif($wind_speed23 > 20.7 && $wind_speed23 < 24.4){
                $wind_force23 = 'Strong storm';
                $sea_force23 = 'Force eight';
              }
              elseif($wind_speed23 > 24.4 && $wind_speed23 < 28.4){
                $wind_force23 = 'Storm';
                $sea_force23 = 'Force nine';
              }
              elseif($wind_speed23 > 28.4 && $wind_speed23 < 32.6){
                $wind_force23 = 'Fortunale';
                $sea_force23 = 'Force ten';
              }
              elseif($wind_speed23 > 32.6){
                $wind_force23 = 'Hurricane';
                $sea_force23 = 'Force ten';
              }
              else{
                $wind_force23 = 'zero';
                $sea_force23 = 'zero';
              }
            //rain
              $rain23 = $clima['list'][23]['rain']['3h'];
            //snow
              $snow23 = $clima['list'][23]['snow']['3h'];
            //data/time of calculation
              $data_time_pre23 = $clima['list'][23]['dt_txt'];
              $data_time_confr23 = substr($data_time_pre23,0,11);
              $data_time23 = substr($data_time_pre23,11);

            //24
              $temperatura24 = $clima['list'][24]['main']['temp'];
              $temp_min24 = $clima['list'][24]['main']['temp_min'];
              $temp_max24 = $clima['list'][24]['main']['temp_max'];
              $pressure24 = $clima['list'][24]['main']['pressure'];
              $sea_level24 = $clima['list'][24]['main']['sea_level'];
              $ground_level24 = $clima['list'][24]['main']['grnd_level'];
              $humidity24 = $clima['list'][24]['main']['humidity'];
              $internal_parameter24 = $clima['list'][24]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm24 = round($pressure24*0.00098692, 3);
            //weather list
              $weather_condition_id24 = $clima['list'][24]['weather'][0]['id'];
              $weather_condition_main24 = $clima['list'][24]['weather'][0]['main'];
              $weather_description24 = $clima['list'][24]['weather'][0]['description'];
              $weather_icon24x = $clima['list'][24]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon24x == "01d") {
                $weather_icon24 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon24x == "01n") {
                $weather_icon24 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon24x == "02d") {
                $weather_icon24 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon24x == "02n") {
                $weather_icon24 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon24x == "03d") {
                $weather_icon24 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon24x == "03n") {
                $weather_icon24 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon24x == "04d") {
                $weather_icon24 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon24x == "04n") {
                $weather_icon24 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon24x == "9d"){ //
                $weather_icon24 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon24x == "9n"){ //
                $weather_icon24 = "🌧";
              /*

              */
              }
              elseif ($weather_icon24x == "10d"){
                $weather_icon24 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon24x == "10n"){
                $weather_icon24 = "🌦";
              /*

              */
              }
              elseif($weather_icon24x == "11d"){
                $weather_icon24 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon24x == "11n"){
                $weather_icon24 = "⛈";
                /*

                */
              }
              elseif ($weather_icon24x == "13d") {
                $weather_icon24 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon24x == "13n") {
              $weather_icon24 = "🌨";
              /*

              */
              }
              elseif ($weather_icon24x == "50d") {
                $weather_icon24 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon24x == "50n") {
                $weather_icon24 = "🌫";
                /*

                */
              }
            //clouds
              $clouds24 = $clima['list'][24]['clouds']['all'];
            //wind
              $wind_speed24 = $clima['list'][24]['wind']['speed'];
              $wind_deg24 = $clima['list'][24]['wind']['deg'];
              $degre24 = (int)$wind_deg24;
            //direction
              if($degre24 == 0){
                $direction_wind24 = 'North';
              }
              elseif($degre24 > 0 && $degre24 <45){
                $direction_wind24 = 'North-NorthEast';
              }
              elseif ($degre24 == 45) {
                $direction_wind24 = 'North-East';
              }
              elseif ($degre24 > 45 && $degre24 <90){
                $direction_wind24 = 'East-NorthEast';
              }
              elseif ($degre24 == 90){
                $direction_wind24 = 'East';
              }
              elseif($degre24 > 90 && $degre24 <135){
                $direction_wind24 = 'East-SouthEast';
              }
              elseif($degre24 == 135){
                $direction_wind24 = 'South-East';
              }
              elseif($degre24 > 135 && $degre24 <180){
                $direction_wind24 = 'South-SouthEast';
              }
              elseif($degre24 == 180){
                $direction_wind24 = 'South';
              }
              elseif($degre24 > 180 && $degre24 <225){
                $direction_wind24 = 'West-SouthWest';
              }
              elseif($degre24 == 225){
                $direction_wind24 = 'South-West';
              }
              elseif($degre24 > 225 && $degre24 <270){
                $direction_wind24 = 'West-SouthWest';
              }
              elseif($degre24 == 270){
                $direction_wind24 = 'West';
              }
              elseif($degre24 > 270 && $degre24 <315){
                $direction_wind24 = 'West-NortWest';
              }
              elseif($degre24 == 315){
                $direction_wind24 = 'North-West';
              }
              elseif($degre24 > 315 && $degre24 <360){
                $direction_wind24 = 'North-NorthWest';
              }
              else{
                $direction_wind24 = 'North';
              }
            //sea
              if($wind_speed24 <= 0.3){
                $wind_force24 = 'Calm';
                $sea_force24 = 'Zero force';
              }
              elseif($wind_speed24 > 0.3 && $wind_speed24 < 1.5){
                $wind_force24 = 'Burr of wind';
                $sea_force24 = 'Force one';
              }
              elseif($wind_speed24 > 1.5 && $wind_speed24 < 3.3){
                $wind_force24 = 'Light breeze';
                $sea_force24 = 'Force two';
              }
              elseif($wind_speed24 > 3.3 && $wind_speed24 < 5.4){
                $wind_force24 = 'Breeze';
                $sea_force24 = 'Force two';
              }
              elseif($wind_speed24 > 5.4 && $wind_speed24 < 7.9){
                $wind_force24 = 'Livelly breeze';
                $sea_force24 = 'Force three';
              }
              elseif($wind_speed24 > 7.9 && $wind_speed24 < 10.7){
                $wind_force24 = 'Tense breeze';
                $sea_force24 = 'Force four';
              }
              elseif($wind_speed24 > 10.7 && $wind_speed24 < 13.8){
                $wind_force24 = 'Fresh wind';
                $sea_force24 = 'Force five';
              }
              elseif($wind_speed24 > 13.8 && $wind_speed24 < 17.1){
                $wind_force24 = 'Strong wind';
                $sea_force24 = 'Force six';
              }
              elseif($wind_speed24 > 17.1 && $wind_speed24 < 20.7){
                $wind_force24 = 'Moderate storm';
                $sea_force24 = 'Force seven';
              }
              elseif($wind_speed24 > 20.7 && $wind_speed24 < 24.4){
                $wind_force24 = 'Strong storm';
                $sea_force24 = 'Force eight';
              }
              elseif($wind_speed24 > 24.4 && $wind_speed24 < 28.4){
                $wind_force24 = 'Storm';
                $sea_force24 = 'Force nine';
              }
              elseif($wind_speed24 > 28.4 && $wind_speed24 < 32.6){
                $wind_force24 = 'Fortunale';
                $sea_force24 = 'Force ten';
              }
              elseif($wind_speed24 > 32.6){
                $wind_force24 = 'Hurricane';
                $sea_force24 = 'Force ten';
              }
              else{
                $wind_force24 = 'zero';
                $sea_force24 = 'zero';
              }
            //rain
              $rain24 = $clima['list'][24]['rain']['3h'];
            //snow
              $snow24 = $clima['list'][24]['snow']['3h'];
            //data/time of calculation
              $data_time_pre24 = $clima['list'][24]['dt_txt'];
              $data_time_confr24 = substr($data_time_pre24,0,11);
              $data_time24 = substr($data_time_pre24,11);

            //25
              $temperatura25 = $clima['list'][25]['main']['temp'];
              $temp_min25 = $clima['list'][25]['main']['temp_min'];
              $temp_max25 = $clima['list'][25]['main']['temp_max'];
              $pressure25 = $clima['list'][25]['main']['pressure'];
              $sea_level25 = $clima['list'][25]['main']['sea_level'];
              $ground_level25 = $clima['list'][25]['main']['grnd_level'];
              $humidity25 = $clima['list'][25]['main']['humidity'];
              $internal_parameter25 = $clima['list'][25]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm25 = round($pressure25*0.00098692, 3);
            //weather list
              $weather_condition_id25 = $clima['list'][25]['weather'][0]['id'];
              $weather_condition_main25 = $clima['list'][25]['weather'][0]['main'];
              $weather_description25 = $clima['list'][25]['weather'][0]['description'];
              $weather_icon25x = $clima['list'][25]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon25x == "01d") {
                $weather_icon25 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon25x == "01n") {
                $weather_icon25 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon25x == "02d") {
                $weather_icon25 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon25x == "02n") {
                $weather_icon25 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon25x == "03d") {
                $weather_icon25 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon25x == "03n") {
                $weather_icon25 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon25x == "04d") {
                $weather_icon25 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon25x == "04n") {
                $weather_icon25 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon25x == "9d"){ //
                $weather_icon25 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon25x == "9n"){ //
                $weather_icon25 = "🌧";
              /*

              */
              }
              elseif ($weather_icon25x == "10d"){
                $weather_icon25 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon25x == "10n"){
                $weather_icon25 = "🌦";
              /*

              */
              }
              elseif($weather_icon25x == "11d"){
                $weather_icon25 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon25x == "11n"){
                $weather_icon25 = "⛈";
                /*

                */
              }
              elseif ($weather_icon25x == "13d") {
                $weather_icon25 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon25x == "13n") {
              $weather_icon25 = "🌨";
              /*

              */
              }
              elseif ($weather_icon25x == "50d") {
                $weather_icon25 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon25x == "50n") {
                $weather_icon25 = "🌫";
                /*

                */
              }
            //clouds
              $clouds25 = $clima['list'][25]['clouds']['all'];
            //wind
              $wind_speed25 = $clima['list'][25]['wind']['speed'];
              $wind_deg25 = $clima['list'][25]['wind']['deg'];
              $degre25 = (int)$wind_deg25;
            //direction
              if($degre25 == 0){
                $direction_wind25 = 'North';
              }
              elseif($degre25 > 0 && $degre25 <45){
                $direction_wind25 = 'North-NorthEast';
              }
              elseif ($degre25 == 45) {
                $direction_wind25 = 'North-East';
              }
              elseif ($degre25 > 45 && $degre25 <90){
                $direction_wind25 = 'East-NorthEast';
              }
              elseif ($degre25 == 90){
                $direction_wind25 = 'East';
              }
              elseif($degre25 > 90 && $degre25 <135){
                $direction_wind25 = 'East-SouthEast';
              }
              elseif($degre25 == 135){
                $direction_wind25 = 'South-East';
              }
              elseif($degre25 > 135 && $degre25 <180){
                $direction_wind25 = 'South-SouthEast';
              }
              elseif($degre25 == 180){
                $direction_wind25 = 'South';
              }
              elseif($degre25 > 180 && $degre25 <225){
                $direction_wind25 = 'West-SouthWest';
              }
              elseif($degre25 == 225){
                $direction_wind25 = 'South-West';
              }
              elseif($degre25 > 225 && $degre25 <270){
                $direction_wind25 = 'West-SouthWest';
              }
              elseif($degre25 == 270){
                $direction_wind25 = 'West';
              }
              elseif($degre25 > 270 && $degre25 <315){
                $direction_wind25 = 'West-NortWest';
              }
              elseif($degre25 == 315){
                $direction_wind25 = 'North-West';
              }
              elseif($degre25 > 315 && $degre25 <360){
                $direction_wind25 = 'North-NorthWest';
              }
              else{
                $direction_wind25 = 'North';
              }
            //sea
              if($wind_speed25 <= 0.3){
                $wind_force25 = 'Calm';
                $sea_force25 = 'Zero force';
              }
              elseif($wind_speed25 > 0.3 && $wind_speed25 < 1.5){
                $wind_force25 = 'Burr of wind';
                $sea_force25 = 'Force one';
              }
              elseif($wind_speed25 > 1.5 && $wind_speed25 < 3.3){
                $wind_force25 = 'Light breeze';
                $sea_force25 = 'Force two';
              }
              elseif($wind_speed25 > 3.3 && $wind_speed25 < 5.4){
                $wind_force25 = 'Breeze';
                $sea_force25 = 'Force two';
              }
              elseif($wind_speed25 > 5.4 && $wind_speed25 < 7.9){
                $wind_force25 = 'Livelly breeze';
                $sea_force25 = 'Force three';
              }
              elseif($wind_speed25 > 7.9 && $wind_speed25 < 10.7){
                $wind_force25 = 'Tense breeze';
                $sea_force25 = 'Force four';
              }
              elseif($wind_speed25 > 10.7 && $wind_speed25 < 13.8){
                $wind_force25 = 'Fresh wind';
                $sea_force25 = 'Force five';
              }
              elseif($wind_speed25 > 13.8 && $wind_speed25 < 17.1){
                $wind_force25 = 'Strong wind';
                $sea_force25 = 'Force six';
              }
              elseif($wind_speed25 > 17.1 && $wind_speed25 < 20.7){
                $wind_force25 = 'Moderate storm';
                $sea_force25 = 'Force seven';
              }
              elseif($wind_speed25 > 20.7 && $wind_speed25 < 24.4){
                $wind_force25 = 'Strong storm';
                $sea_force25 = 'Force eight';
              }
              elseif($wind_speed25 > 24.4 && $wind_speed25 < 28.4){
                $wind_force25 = 'Storm';
                $sea_force25 = 'Force nine';
              }
              elseif($wind_speed25 > 28.4 && $wind_speed25 < 32.6){
                $wind_force25 = 'Fortunale';
                $sea_force25 = 'Force ten';
              }
              elseif($wind_speed25 > 32.6){
                $wind_force25 = 'Hurricane';
                $sea_force25 = 'Force ten';
              }
              else{
                $wind_force25 = 'zero';
                $sea_force25 = 'zero';
              }
            //rain
              $rain25 = $clima['list'][25]['rain']['3h'];
            //snow
              $snow25 = $clima['list'][25]['snow']['3h'];
            //data/time of calculation
              $data_time_pre25 = $clima['list'][25]['dt_txt'];
              $data_time_confr25 = substr($data_time_pre25,0,11);
              $data_time25 = substr($data_time_pre25,11);

            //26
              $temperatura26 = $clima['list'][26]['main']['temp'];
              $temp_min26 = $clima['list'][26]['main']['temp_min'];
              $temp_max26 = $clima['list'][26]['main']['temp_max'];
              $pressure26 = $clima['list'][26]['main']['pressure'];
              $sea_level26 = $clima['list'][26]['main']['sea_level'];
              $ground_level26 = $clima['list'][26]['main']['grnd_level'];
              $humidity26 = $clima['list'][26]['main']['humidity'];
              $internal_parameter26 = $clima['list'][26]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm26 = round($pressure26*0.00098692, 3);
            //weather list
              $weather_condition_id26 = $clima['list'][26]['weather'][0]['id'];
              $weather_condition_main26 = $clima['list'][26]['weather'][0]['main'];
              $weather_description26 = $clima['list'][26]['weather'][0]['description'];
              $weather_icon26x = $clima['list'][26]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon26x == "01d") {
                $weather_icon26 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon26x == "01n") {
                $weather_icon26 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon26x == "02d") {
                $weather_icon26 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon26x == "02n") {
                $weather_icon26 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon26x == "03d") {
                $weather_icon26 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon26x == "03n") {
                $weather_icon26 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon26x == "04d") {
                $weather_icon26 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon26x == "04n") {
                $weather_icon26 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon26x == "9d"){ //
                $weather_icon26 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon26x == "9n"){ //
                $weather_icon26 = "🌧";
              /*

              */
              }
              elseif ($weather_icon26x == "10d"){
                $weather_icon26 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon26x == "10n"){
                $weather_icon26 = "🌦";
              /*

              */
              }
              elseif($weather_icon26x == "11d"){
                $weather_icon26 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon26x == "11n"){
                $weather_icon26 = "⛈";
                /*

                */
              }
              elseif ($weather_icon26x == "13d") {
                $weather_icon26 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon26x == "13n") {
              $weather_icon26 = "🌨";
              /*

              */
              }
              elseif ($weather_icon26x == "50d") {
                $weather_icon26 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon26x == "50n") {
                $weather_icon26 = "🌫";
                /*

                */
              }
            //clouds
              $clouds26 = $clima['list'][26]['clouds']['all'];
            //wind
              $wind_speed26 = $clima['list'][26]['wind']['speed'];
              $wind_deg26 = $clima['list'][26]['wind']['deg'];
              $degre26 = (int)$wind_deg26;
            //direction
              if($degre26 == 0){
                $direction_wind26 = 'North';
              }
              elseif($degre26 > 0 && $degre26 <45){
                $direction_wind26 = 'North-NorthEast';
              }
              elseif ($degre26 == 45) {
                $direction_wind26 = 'North-East';
              }
              elseif ($degre26 > 45 && $degre26 <90){
                $direction_wind26 = 'East-NorthEast';
              }
              elseif ($degre26 == 90){
                $direction_wind26 = 'East';
              }
              elseif($degre26 > 90 && $degre26 <135){
                $direction_wind26 = 'East-SouthEast';
              }
              elseif($degre26 == 135){
                $direction_wind26 = 'South-East';
              }
              elseif($degre26 > 135 && $degre26 <180){
                $direction_wind26 = 'South-SouthEast';
              }
              elseif($degre26 == 180){
                $direction_wind26 = 'South';
              }
              elseif($degre26 > 180 && $degre26 <225){
                $direction_wind26 = 'West-SouthWest';
              }
              elseif($degre26 == 225){
                $direction_wind26 = 'South-West';
              }
              elseif($degre26 > 225 && $degre26 <270){
                $direction_wind26 = 'West-SouthWest';
              }
              elseif($degre26 == 270){
                $direction_wind26 = 'West';
              }
              elseif($degre26 > 270 && $degre26 <315){
                $direction_wind26 = 'West-NortWest';
              }
              elseif($degre26 == 315){
                $direction_wind26 = 'North-West';
              }
              elseif($degre26 > 315 && $degre26 <360){
                $direction_wind26 = 'North-NorthWest';
              }
              else{
                $direction_wind26 = 'North';
              }
            //sea
              if($wind_speed26 <= 0.3){
                $wind_force26 = 'Calm';
                $sea_force26 = 'Zero force';
              }
              elseif($wind_speed26 > 0.3 && $wind_speed26 < 1.5){
                $wind_force26 = 'Burr of wind';
                $sea_force26 = 'Force one';
              }
              elseif($wind_speed26 > 1.5 && $wind_speed26 < 3.3){
                $wind_force26 = 'Light breeze';
                $sea_force26 = 'Force two';
              }
              elseif($wind_speed26 > 3.3 && $wind_speed26 < 5.4){
                $wind_force26 = 'Breeze';
                $sea_force26 = 'Force two';
              }
              elseif($wind_speed26 > 5.4 && $wind_speed26 < 7.9){
                $wind_force26 = 'Livelly breeze';
                $sea_force26 = 'Force three';
              }
              elseif($wind_speed26 > 7.9 && $wind_speed26 < 10.7){
                $wind_force26 = 'Tense breeze';
                $sea_force26 = 'Force four';
              }
              elseif($wind_speed26 > 10.7 && $wind_speed26 < 13.8){
                $wind_force26 = 'Fresh wind';
                $sea_force26 = 'Force five';
              }
              elseif($wind_speed26 > 13.8 && $wind_speed26 < 17.1){
                $wind_force26 = 'Strong wind';
                $sea_force26 = 'Force six';
              }
              elseif($wind_speed26 > 17.1 && $wind_speed26 < 20.7){
                $wind_force26 = 'Moderate storm';
                $sea_force26 = 'Force seven';
              }
              elseif($wind_speed26 > 20.7 && $wind_speed26 < 24.4){
                $wind_force26 = 'Strong storm';
                $sea_force26 = 'Force eight';
              }
              elseif($wind_speed26 > 24.4 && $wind_speed26 < 28.4){
                $wind_force26 = 'Storm';
                $sea_force26 = 'Force nine';
              }
              elseif($wind_speed26 > 28.4 && $wind_speed26 < 32.6){
                $wind_force26 = 'Fortunale';
                $sea_force26 = 'Force ten';
              }
              elseif($wind_speed26 > 32.6){
                $wind_force26 = 'Hurricane';
                $sea_force26 = 'Force ten';
              }
              else{
                $wind_force26 = 'zero';
                $sea_force26 = 'zero';
              }
            //rain
              $rain26 = $clima['list'][26]['rain']['3h'];
            //snow
              $snow26 = $clima['list'][26]['snow']['3h'];
            //data/time of calculation
              $data_time_pre26 = $clima['list'][26]['dt_txt'];
              $data_time_confr26 = substr($data_time_pre26,0,11);
              $data_time26 = substr($data_time_pre26,11);

            //27
              $temperatura27 = $clima['list'][27]['main']['temp'];
              $temp_min27 = $clima['list'][27]['main']['temp_min'];
              $temp_max27 = $clima['list'][27]['main']['temp_max'];
              $pressure27 = $clima['list'][27]['main']['pressure'];
              $sea_level27 = $clima['list'][27]['main']['sea_level'];
              $ground_level27 = $clima['list'][27]['main']['grnd_level'];
              $humidity27 = $clima['list'][27]['main']['humidity'];
              $internal_parameter27 = $clima['list'][27]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm27 = round($pressure27*0.00098692, 3);
            //weather list
              $weather_condition_id27 = $clima['list'][27]['weather'][0]['id'];
              $weather_condition_main27 = $clima['list'][27]['weather'][0]['main'];
              $weather_description27 = $clima['list'][27]['weather'][0]['description'];
              $weather_icon27x = $clima['list'][27]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon27x == "01d") {
                $weather_icon27 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon27x == "01n") {
                $weather_icon27 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon27x == "02d") {
                $weather_icon27 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon27x == "02n") {
                $weather_icon27 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon27x == "03d") {
                $weather_icon27 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon27x == "03n") {
                $weather_icon27 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon27x == "04d") {
                $weather_icon27 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon27x == "04n") {
                $weather_icon27 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon27x == "9d"){ //
                $weather_icon27 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon27x == "9n"){ //
                $weather_icon27 = "🌧";
              /*

              */
              }
              elseif ($weather_icon27x == "10d"){
                $weather_icon27 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon27x == "10n"){
                $weather_icon27 = "🌦";
              /*

              */
              }
              elseif($weather_icon27x == "11d"){
                $weather_icon27 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon27x == "11n"){
                $weather_icon27 = "⛈";
                /*

                */
              }
              elseif ($weather_icon27x == "13d") {
                $weather_icon27 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon27x == "13n") {
              $weather_icon27 = "🌨";
              /*

              */
              }
              elseif ($weather_icon27x == "50d") {
                $weather_icon27 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon27x == "50n") {
                $weather_icon27 = "🌫";
                /*

                */
              }
            //clouds
              $clouds27 = $clima['list'][27]['clouds']['all'];
            //wind
              $wind_speed27 = $clima['list'][27]['wind']['speed'];
              $wind_deg27 = $clima['list'][27]['wind']['deg'];
              $degre27 = (int)$wind_deg27;
            //direction
              if($degre27 == 0){
                $direction_wind27 = 'North';
              }
              elseif($degre27 > 0 && $degre27 <45){
                $direction_wind27 = 'North-NorthEast';
              }
              elseif ($degre27 == 45) {
                $direction_wind27 = 'North-East';
              }
              elseif ($degre27 > 45 && $degre27 <90){
                $direction_wind27 = 'East-NorthEast';
              }
              elseif ($degre27 == 90){
                $direction_wind27 = 'East';
              }
              elseif($degre27 > 90 && $degre27 <135){
                $direction_wind27 = 'East-SouthEast';
              }
              elseif($degre27 == 135){
                $direction_wind27 = 'South-East';
              }
              elseif($degre27 > 135 && $degre27 <180){
                $direction_wind27 = 'South-SouthEast';
              }
              elseif($degre27 == 180){
                $direction_wind27 = 'South';
              }
              elseif($degre27 > 180 && $degre27 <225){
                $direction_wind27 = 'West-SouthWest';
              }
              elseif($degre27 == 225){
                $direction_wind27 = 'South-West';
              }
              elseif($degre27 > 225 && $degre27 <270){
                $direction_wind27 = 'West-SouthWest';
              }
              elseif($degre27 == 270){
                $direction_wind27 = 'West';
              }
              elseif($degre27 > 270 && $degre27 <315){
                $direction_wind27 = 'West-NortWest';
              }
              elseif($degre27 == 315){
                $direction_wind27 = 'North-West';
              }
              elseif($degre27 > 315 && $degre27 <360){
                $direction_wind27 = 'North-NorthWest';
              }
              else{
                $direction_wind27 = 'North';
              }
            //sea
              if($wind_speed27 <= 0.3){
                $wind_force27 = 'Calm';
                $sea_force27 = 'Zero force';
              }
              elseif($wind_speed27 > 0.3 && $wind_speed27 < 1.5){
                $wind_force27 = 'Burr of wind';
                $sea_force27 = 'Force one';
              }
              elseif($wind_speed27 > 1.5 && $wind_speed27 < 3.3){
                $wind_force27 = 'Light breeze';
                $sea_force27 = 'Force two';
              }
              elseif($wind_speed27 > 3.3 && $wind_speed27 < 5.4){
                $wind_force27 = 'Breeze';
                $sea_force27 = 'Force two';
              }
              elseif($wind_speed27 > 5.4 && $wind_speed27 < 7.9){
                $wind_force27 = 'Livelly breeze';
                $sea_force27 = 'Force three';
              }
              elseif($wind_speed27 > 7.9 && $wind_speed27 < 10.7){
                $wind_force27 = 'Tense breeze';
                $sea_force27 = 'Force four';
              }
              elseif($wind_speed27 > 10.7 && $wind_speed27 < 13.8){
                $wind_force27 = 'Fresh wind';
                $sea_force27 = 'Force five';
              }
              elseif($wind_speed27 > 13.8 && $wind_speed27 < 17.1){
                $wind_force27 = 'Strong wind';
                $sea_force27 = 'Force six';
              }
              elseif($wind_speed27 > 17.1 && $wind_speed27 < 20.7){
                $wind_force27 = 'Moderate storm';
                $sea_force27 = 'Force seven';
              }
              elseif($wind_speed27 > 20.7 && $wind_speed27 < 24.4){
                $wind_force27 = 'Strong storm';
                $sea_force27 = 'Force eight';
              }
              elseif($wind_speed27 > 24.4 && $wind_speed27 < 28.4){
                $wind_force27 = 'Storm';
                $sea_force27 = 'Force nine';
              }
              elseif($wind_speed27 > 28.4 && $wind_speed27 < 32.6){
                $wind_force27 = 'Fortunale';
                $sea_force27 = 'Force ten';
              }
              elseif($wind_speed27 > 32.6){
                $wind_force27 = 'Hurricane';
                $sea_force27 = 'Force ten';
              }
              else{
                $wind_force27 = 'zero';
                $sea_force27 = 'zero';
              }
            //rain
              $rain27 = $clima['list'][27]['rain']['3h'];
            //snow
              $snow27 = $clima['list'][27]['snow']['3h'];
            //data/time of calculation
              $data_time_pre27 = $clima['list'][27]['dt_txt'];
              $data_time_confr27 = substr($data_time_pre27,0,11);
              $data_time27 = substr($data_time_pre27,11);

            //28
              $temperatura28 = $clima['list'][28]['main']['temp'];
              $temp_min28 = $clima['list'][28]['main']['temp_min'];
              $temp_max28 = $clima['list'][28]['main']['temp_max'];
              $pressure28 = $clima['list'][28]['main']['pressure'];
              $sea_level28 = $clima['list'][28]['main']['sea_level'];
              $ground_level28 = $clima['list'][28]['main']['grnd_level'];
              $humidity28 = $clima['list'][28]['main']['humidity'];
              $internal_parameter28 = $clima['list'][28]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm28 = round($pressure28*0.00098692, 3);
            //weather list
              $weather_condition_id28 = $clima['list'][28]['weather'][0]['id'];
              $weather_condition_main28 = $clima['list'][28]['weather'][0]['main'];
              $weather_description28 = $clima['list'][28]['weather'][0]['description'];
              $weather_icon28x = $clima['list'][28]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon28x == "01d") {
                $weather_icon28 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon28x == "01n") {
                $weather_icon28 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon28x == "02d") {
                $weather_icon28 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon28x == "02n") {
                $weather_icon28 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon28x == "03d") {
                $weather_icon28 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon28x == "03n") {
                $weather_icon28 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon28x == "04d") {
                $weather_icon28 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon28x == "04n") {
                $weather_icon28 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon28x == "9d"){ //
                $weather_icon28 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon28x == "9n"){ //
                $weather_icon28 = "🌧";
              /*

              */
              }
              elseif ($weather_icon28x == "10d"){
                $weather_icon28 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon28x == "10n"){
                $weather_icon28 = "🌦";
              /*

              */
              }
              elseif($weather_icon28x == "11d"){
                $weather_icon28 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon28x == "11n"){
                $weather_icon28 = "⛈";
                /*

                */
              }
              elseif ($weather_icon28x == "13d") {
                $weather_icon28 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon28x == "13n") {
              $weather_icon28 = "🌨";
              /*

              */
              }
              elseif ($weather_icon28x == "50d") {
                $weather_icon28 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon28x == "50n") {
                $weather_icon28 = "🌫";
                /*

                */
              }
            //clouds
              $clouds28 = $clima['list'][28]['clouds']['all'];
            //wind
              $wind_speed28 = $clima['list'][28]['wind']['speed'];
              $wind_deg28 = $clima['list'][28]['wind']['deg'];
              $degre28 = (int)$wind_deg28;
            //direction
              if($degre28 == 0){
                $direction_wind28 = 'North';
              }
              elseif($degre28 > 0 && $degre28 <45){
                $direction_wind28 = 'North-NorthEast';
              }
              elseif ($degre28 == 45) {
                $direction_wind28 = 'North-East';
              }
              elseif ($degre28 > 45 && $degre28 <90){
                $direction_wind28 = 'East-NorthEast';
              }
              elseif ($degre28 == 90){
                $direction_wind28 = 'East';
              }
              elseif($degre28 > 90 && $degre28 <135){
                $direction_wind28 = 'East-SouthEast';
              }
              elseif($degre28 == 135){
                $direction_wind28 = 'South-East';
              }
              elseif($degre28 > 135 && $degre28 <180){
                $direction_wind28 = 'South-SouthEast';
              }
              elseif($degre28 == 180){
                $direction_wind28 = 'South';
              }
              elseif($degre28 > 180 && $degre28 <225){
                $direction_wind28 = 'West-SouthWest';
              }
              elseif($degre28 == 225){
                $direction_wind28 = 'South-West';
              }
              elseif($degre28 > 225 && $degre28 <270){
                $direction_wind28 = 'West-SouthWest';
              }
              elseif($degre28 == 270){
                $direction_wind28 = 'West';
              }
              elseif($degre28 > 270 && $degre28 <315){
                $direction_wind28 = 'West-NortWest';
              }
              elseif($degre28 == 315){
                $direction_wind28 = 'North-West';
              }
              elseif($degre28 > 315 && $degre28 <360){
                $direction_wind28 = 'North-NorthWest';
              }
              else{
                $direction_wind28 = 'North';
              }
            //sea
              if($wind_speed28 <= 0.3){
                $wind_force28 = 'Calm';
                $sea_force28 = 'Zero force';
              }
              elseif($wind_speed28 > 0.3 && $wind_speed28 < 1.5){
                $wind_force28 = 'Burr of wind';
                $sea_force28 = 'Force one';
              }
              elseif($wind_speed28 > 1.5 && $wind_speed28 < 3.3){
                $wind_force28 = 'Light breeze';
                $sea_force28 = 'Force two';
              }
              elseif($wind_speed28 > 3.3 && $wind_speed28 < 5.4){
                $wind_force28 = 'Breeze';
                $sea_force28 = 'Force two';
              }
              elseif($wind_speed28 > 5.4 && $wind_speed28 < 7.9){
                $wind_force28 = 'Livelly breeze';
                $sea_force28 = 'Force three';
              }
              elseif($wind_speed28 > 7.9 && $wind_speed28 < 10.7){
                $wind_force28 = 'Tense breeze';
                $sea_force28 = 'Force four';
              }
              elseif($wind_speed28 > 10.7 && $wind_speed28 < 13.8){
                $wind_force28 = 'Fresh wind';
                $sea_force28 = 'Force five';
              }
              elseif($wind_speed28 > 13.8 && $wind_speed28 < 17.1){
                $wind_force28 = 'Strong wind';
                $sea_force28 = 'Force six';
              }
              elseif($wind_speed28 > 17.1 && $wind_speed28 < 20.7){
                $wind_force28 = 'Moderate storm';
                $sea_force28 = 'Force seven';
              }
              elseif($wind_speed28 > 20.7 && $wind_speed28 < 24.4){
                $wind_force28 = 'Strong storm';
                $sea_force28 = 'Force eight';
              }
              elseif($wind_speed28 > 24.4 && $wind_speed28 < 28.4){
                $wind_force28 = 'Storm';
                $sea_force28 = 'Force nine';
              }
              elseif($wind_speed28 > 28.4 && $wind_speed28 < 32.6){
                $wind_force28 = 'Fortunale';
                $sea_force28 = 'Force ten';
              }
              elseif($wind_speed28 > 32.6){
                $wind_force28 = 'Hurricane';
                $sea_force28 = 'Force ten';
              }
              else{
                $wind_force28 = 'zero';
                $sea_force28 = 'zero';
              }
            //rain
              $rain28 = $clima['list'][28]['rain']['3h'];
            //snow
              $snow28 = $clima['list'][28]['snow']['3h'];
            //data/time of calculation
              $data_time_pre28 = $clima['list'][28]['dt_txt'];
              $data_time_confr28 = substr($data_time_pre28,0,11);
              $data_time28 = substr($data_time_pre28,11);

            //29
              $temperatura29 = $clima['list'][29]['main']['temp'];
              $temp_min29 = $clima['list'][29]['main']['temp_min'];
              $temp_max29 = $clima['list'][29]['main']['temp_max'];
              $pressure29 = $clima['list'][29]['main']['pressure'];
              $sea_level29 = $clima['list'][29]['main']['sea_level'];
              $ground_level29 = $clima['list'][29]['main']['grnd_level'];
              $humidity29 = $clima['list'][29]['main']['humidity'];
              $internal_parameter29 = $clima['list'][29]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm29 = round($pressure29*0.00098692, 3);
            //weather list
              $weather_condition_id29 = $clima['list'][29]['weather'][0]['id'];
              $weather_condition_main29 = $clima['list'][29]['weather'][0]['main'];
              $weather_description29 = $clima['list'][29]['weather'][0]['description'];
              $weather_icon29x = $clima['list'][29]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon29x == "01d") {
                $weather_icon29 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon29x == "01n") {
                $weather_icon29 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon29x == "02d") {
                $weather_icon29 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon29x == "02n") {
                $weather_icon29 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon29x == "03d") {
                $weather_icon29 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon29x == "03n") {
                $weather_icon29 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon29x == "04d") {
                $weather_icon29 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon29x == "04n") {
                $weather_icon29 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon29x == "9d"){ //
                $weather_icon29 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon29x == "9n"){ //
                $weather_icon29 = "🌧";
              /*

              */
              }
              elseif ($weather_icon29x == "10d"){
                $weather_icon29 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon29x == "10n"){
                $weather_icon29 = "🌦";
              /*

              */
              }
              elseif($weather_icon29x == "11d"){
                $weather_icon29 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon29x == "11n"){
                $weather_icon29 = "⛈";
                /*

                */
              }
              elseif ($weather_icon29x == "13d") {
                $weather_icon29 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon29x == "13n") {
              $weather_icon29 = "🌨";
              /*

              */
              }
              elseif ($weather_icon29x == "50d") {
                $weather_icon29 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon29x == "50n") {
                $weather_icon29 = "🌫";
                /*

                */
              }
            //clouds
              $clouds29 = $clima['list'][29]['clouds']['all'];
            //wind
              $wind_speed29 = $clima['list'][29]['wind']['speed'];
              $wind_deg29 = $clima['list'][29]['wind']['deg'];
              $degre29 = (int)$wind_deg29;
            //direction
              if($degre29 == 0){
                $direction_wind29 = 'North';
              }
              elseif($degre29 > 0 && $degre29 <45){
                $direction_wind29 = 'North-NorthEast';
              }
              elseif ($degre29 == 45) {
                $direction_wind29 = 'North-East';
              }
              elseif ($degre29 > 45 && $degre29 <90){
                $direction_wind29 = 'East-NorthEast';
              }
              elseif ($degre29 == 90){
                $direction_wind29 = 'East';
              }
              elseif($degre29 > 90 && $degre29 <135){
                $direction_wind29 = 'East-SouthEast';
              }
              elseif($degre29 == 135){
                $direction_wind29 = 'South-East';
              }
              elseif($degre29 > 135 && $degre29 <180){
                $direction_wind29 = 'South-SouthEast';
              }
              elseif($degre29 == 180){
                $direction_wind29 = 'South';
              }
              elseif($degre29 > 180 && $degre29 <225){
                $direction_wind29 = 'West-SouthWest';
              }
              elseif($degre29 == 225){
                $direction_wind29 = 'South-West';
              }
              elseif($degre29 > 225 && $degre29 <270){
                $direction_wind29 = 'West-SouthWest';
              }
              elseif($degre29 == 270){
                $direction_wind29 = 'West';
              }
              elseif($degre29 > 270 && $degre29 <315){
                $direction_wind29 = 'West-NortWest';
              }
              elseif($degre29 == 315){
                $direction_wind29 = 'North-West';
              }
              elseif($degre29 > 315 && $degre29 <360){
                $direction_wind29 = 'North-NorthWest';
              }
              else{
                $direction_wind29 = 'North';
              }
            //sea
              if($wind_speed29 <= 0.3){
                $wind_force29 = 'Calm';
                $sea_force29 = 'Zero force';
              }
              elseif($wind_speed29 > 0.3 && $wind_speed29 < 1.5){
                $wind_force29 = 'Burr of wind';
                $sea_force29 = 'Force one';
              }
              elseif($wind_speed29 > 1.5 && $wind_speed29 < 3.3){
                $wind_force29 = 'Light breeze';
                $sea_force29 = 'Force two';
              }
              elseif($wind_speed29 > 3.3 && $wind_speed29 < 5.4){
                $wind_force29 = 'Breeze';
                $sea_force29 = 'Force two';
              }
              elseif($wind_speed29 > 5.4 && $wind_speed29 < 7.9){
                $wind_force29 = 'Livelly breeze';
                $sea_force29 = 'Force three';
              }
              elseif($wind_speed29 > 7.9 && $wind_speed29 < 10.7){
                $wind_force29 = 'Tense breeze';
                $sea_force29 = 'Force four';
              }
              elseif($wind_speed29 > 10.7 && $wind_speed29 < 13.8){
                $wind_force29 = 'Fresh wind';
                $sea_force29 = 'Force five';
              }
              elseif($wind_speed29 > 13.8 && $wind_speed29 < 17.1){
                $wind_force29 = 'Strong wind';
                $sea_force29 = 'Force six';
              }
              elseif($wind_speed29 > 17.1 && $wind_speed29 < 20.7){
                $wind_force29 = 'Moderate storm';
                $sea_force29 = 'Force seven';
              }
              elseif($wind_speed29 > 20.7 && $wind_speed29 < 24.4){
                $wind_force29 = 'Strong storm';
                $sea_force29 = 'Force eight';
              }
              elseif($wind_speed29 > 24.4 && $wind_speed29 < 28.4){
                $wind_force29 = 'Storm';
                $sea_force29 = 'Force nine';
              }
              elseif($wind_speed29 > 28.4 && $wind_speed29 < 32.6){
                $wind_force29 = 'Fortunale';
                $sea_force29 = 'Force ten';
              }
              elseif($wind_speed29 > 32.6){
                $wind_force29 = 'Hurricane';
                $sea_force29 = 'Force ten';
              }
              else{
                $wind_force29 = 'zero';
                $sea_force29 = 'zero';
              }
            //rain
              $rain29 = $clima['list'][29]['rain']['3h'];
            //snow
              $snow29 = $clima['list'][29]['snow']['3h'];
            //data/time of calculation
              $data_time_pre29 = $clima['list'][29]['dt_txt'];
              $data_time_confr29 = substr($data_time_pre29,0,11);
              $data_time29 = substr($data_time_pre29,11);

            //30
              $temperatura30 = $clima['list'][30]['main']['temp'];
              $temp_min30 = $clima['list'][30]['main']['temp_min'];
              $temp_max30 = $clima['list'][30]['main']['temp_max'];
              $pressure30 = $clima['list'][30]['main']['pressure'];
              $sea_level30 = $clima['list'][30]['main']['sea_level'];
              $ground_level30 = $clima['list'][30]['main']['grnd_level'];
              $humidity30 = $clima['list'][30]['main']['humidity'];
              $internal_parameter30 = $clima['list'][30]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm30 = round($pressure30*0.00098692, 3);
            //weather list
              $weather_condition_id30 = $clima['list'][30]['weather'][0]['id'];
              $weather_condition_main30 = $clima['list'][30]['weather'][0]['main'];
              $weather_description30 = $clima['list'][30]['weather'][0]['description'];
              $weather_icon30x = $clima['list'][30]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon30x == "01d") {
                $weather_icon30 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon30x == "01n") {
                $weather_icon30 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon30x == "02d") {
                $weather_icon30 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon30x == "02n") {
                $weather_icon30 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon30x == "03d") {
                $weather_icon30 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon30x == "03n") {
                $weather_icon30 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon30x == "04d") {
                $weather_icon30 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon30x == "04n") {
                $weather_icon30 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon30x == "9d"){ //
                $weather_icon30 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon30x == "9n"){ //
                $weather_icon30 = "🌧";
              /*

              */
              }
              elseif ($weather_icon30x == "10d"){
                $weather_icon30 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon30x == "10n"){
                $weather_icon30 = "🌦";
              /*

              */
              }
              elseif($weather_icon30x == "11d"){
                $weather_icon30 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon30x == "11n"){
                $weather_icon30 = "⛈";
                /*

                */
              }
              elseif ($weather_icon30x == "13d") {
                $weather_icon30 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon30x == "13n") {
              $weather_icon30 = "🌨";
              /*

              */
              }
              elseif ($weather_icon30x == "50d") {
                $weather_icon30 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon30x == "50n") {
                $weather_icon30 = "🌫";
                /*

                */
              }
            //clouds
              $clouds30 = $clima['list'][30]['clouds']['all'];
            //wind
              $wind_speed30 = $clima['list'][30]['wind']['speed'];
              $wind_deg30 = $clima['list'][30]['wind']['deg'];
              $degre30 = (int)$wind_deg30;
            //direction
              if($degre30 == 0){
                $direction_wind30 = 'North';
              }
              elseif($degre30 > 0 && $degre30 <45){
                $direction_wind30 = 'North-NorthEast';
              }
              elseif ($degre30 == 45) {
                $direction_wind30 = 'North-East';
              }
              elseif ($degre30 > 45 && $degre30 <90){
                $direction_wind30 = 'East-NorthEast';
              }
              elseif ($degre30 == 90){
                $direction_wind30 = 'East';
              }
              elseif($degre30 > 90 && $degre30 <135){
                $direction_wind30 = 'East-SouthEast';
              }
              elseif($degre30 == 135){
                $direction_wind30 = 'South-East';
              }
              elseif($degre30 > 135 && $degre30 <180){
                $direction_wind30 = 'South-SouthEast';
              }
              elseif($degre30 == 180){
                $direction_wind30 = 'South';
              }
              elseif($degre30 > 180 && $degre30 <225){
                $direction_wind30 = 'West-SouthWest';
              }
              elseif($degre30 == 225){
                $direction_wind30 = 'South-West';
              }
              elseif($degre30 > 225 && $degre30 <270){
                $direction_wind30 = 'West-SouthWest';
              }
              elseif($degre30 == 270){
                $direction_wind30 = 'West';
              }
              elseif($degre30 > 270 && $degre30 <315){
                $direction_wind30 = 'West-NortWest';
              }
              elseif($degre30 == 315){
                $direction_wind30 = 'North-West';
              }
              elseif($degre30 > 315 && $degre30 <360){
                $direction_wind30 = 'North-NorthWest';
              }
              else{
                $direction_wind30 = 'North';
              }
            //sea
              if($wind_speed30 <= 0.3){
                $wind_force30 = 'Calm';
                $sea_force30 = 'Zero force';
              }
              elseif($wind_speed30 > 0.3 && $wind_speed30 < 1.5){
                $wind_force30 = 'Burr of wind';
                $sea_force30 = 'Force one';
              }
              elseif($wind_speed30 > 1.5 && $wind_speed30 < 3.3){
                $wind_force30 = 'Light breeze';
                $sea_force30 = 'Force two';
              }
              elseif($wind_speed30 > 3.3 && $wind_speed30 < 5.4){
                $wind_force30 = 'Breeze';
                $sea_force30 = 'Force two';
              }
              elseif($wind_speed30 > 5.4 && $wind_speed30 < 7.9){
                $wind_force30 = 'Livelly breeze';
                $sea_force30 = 'Force three';
              }
              elseif($wind_speed30 > 7.9 && $wind_speed30 < 10.7){
                $wind_force30 = 'Tense breeze';
                $sea_force30 = 'Force four';
              }
              elseif($wind_speed30 > 10.7 && $wind_speed30 < 13.8){
                $wind_force30 = 'Fresh wind';
                $sea_force30 = 'Force five';
              }
              elseif($wind_speed30 > 13.8 && $wind_speed30 < 17.1){
                $wind_force30 = 'Strong wind';
                $sea_force30 = 'Force six';
              }
              elseif($wind_speed30 > 17.1 && $wind_speed30 < 20.7){
                $wind_force30 = 'Moderate storm';
                $sea_force30 = 'Force seven';
              }
              elseif($wind_speed30 > 20.7 && $wind_speed30 < 24.4){
                $wind_force30 = 'Strong storm';
                $sea_force30 = 'Force eight';
              }
              elseif($wind_speed30 > 24.4 && $wind_speed30 < 28.4){
                $wind_force30 = 'Storm';
                $sea_force30 = 'Force nine';
              }
              elseif($wind_speed30 > 28.4 && $wind_speed30 < 32.6){
                $wind_force30 = 'Fortunale';
                $sea_force30 = 'Force ten';
              }
              elseif($wind_speed30 > 32.6){
                $wind_force30 = 'Hurricane';
                $sea_force30 = 'Force ten';
              }
              else{
                $wind_force30 = 'zero';
                $sea_force30 = 'zero';
              }
            //rain
              $rain30 = $clima['list'][30]['rain']['3h'];
            //snow
              $snow30 = $clima['list'][30]['snow']['3h'];
            //data/time of calculation
              $data_time_pre30 = $clima['list'][30]['dt_txt'];
              $data_time_confr30 = substr($data_time_pre30,0,11);
              $data_time30 = substr($data_time_pre30,11);

            //31
              $temperatura31 = $clima['list'][31]['main']['temp'];
              $temp_min31 = $clima['list'][31]['main']['temp_min'];
              $temp_max31 = $clima['list'][31]['main']['temp_max'];
              $pressure31 = $clima['list'][31]['main']['pressure'];
              $sea_level31 = $clima['list'][31]['main']['sea_level'];
              $ground_level31 = $clima['list'][31]['main']['grnd_level'];
              $humidity31 = $clima['list'][31]['main']['humidity'];
              $internal_parameter31 = $clima['list'][31]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm31 = round($pressure31*0.00098692, 3);
            //weather list
              $weather_condition_id31 = $clima['list'][31]['weather'][0]['id'];
              $weather_condition_main31 = $clima['list'][31]['weather'][0]['main'];
              $weather_description31 = $clima['list'][31]['weather'][0]['description'];
              $weather_icon31x = $clima['list'][31]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon31x == "01d") {
                $weather_icon31 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon31x == "01n") {
                $weather_icon31 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon31x == "02d") {
                $weather_icon31 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon31x == "02n") {
                $weather_icon31 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon31x == "03d") {
                $weather_icon31 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon31x == "03n") {
                $weather_icon31 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon31x == "04d") {
                $weather_icon31 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon31x == "04n") {
                $weather_icon31 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon31x == "9d"){ //
                $weather_icon31 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon31x == "9n"){ //
                $weather_icon31 = "🌧";
              /*

              */
              }
              elseif ($weather_icon31x == "10d"){
                $weather_icon31 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon31x == "10n"){
                $weather_icon31 = "🌦";
              /*

              */
              }
              elseif($weather_icon31x == "11d"){
                $weather_icon31 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon31x == "11n"){
                $weather_icon31 = "⛈";
                /*

                */
              }
              elseif ($weather_icon31x == "13d") {
                $weather_icon31 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon31x == "13n") {
              $weather_icon31 = "🌨";
              /*

              */
              }
              elseif ($weather_icon31x == "50d") {
                $weather_icon31 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon31x == "50n") {
                $weather_icon31 = "🌫";
                /*

                */
              }
            //clouds
              $clouds31 = $clima['list'][31]['clouds']['all'];
            //wind
              $wind_speed31 = $clima['list'][31]['wind']['speed'];
              $wind_deg31 = $clima['list'][31]['wind']['deg'];
              $degre31 = (int)$wind_deg31;
            //direction
              if($degre31 == 0){
                $direction_wind31 = 'North';
              }
              elseif($degre31 > 0 && $degre31 <45){
                $direction_wind31 = 'North-NorthEast';
              }
              elseif ($degre31 == 45) {
                $direction_wind31 = 'North-East';
              }
              elseif ($degre31 > 45 && $degre31 <90){
                $direction_wind31 = 'East-NorthEast';
              }
              elseif ($degre31 == 90){
                $direction_wind31 = 'East';
              }
              elseif($degre31 > 90 && $degre31 <135){
                $direction_wind31 = 'East-SouthEast';
              }
              elseif($degre31 == 135){
                $direction_wind31 = 'South-East';
              }
              elseif($degre31 > 135 && $degre31 <180){
                $direction_wind31 = 'South-SouthEast';
              }
              elseif($degre31 == 180){
                $direction_wind31 = 'South';
              }
              elseif($degre31 > 180 && $degre31 <225){
                $direction_wind31 = 'West-SouthWest';
              }
              elseif($degre31 == 225){
                $direction_wind31 = 'South-West';
              }
              elseif($degre31 > 225 && $degre31 <270){
                $direction_wind31 = 'West-SouthWest';
              }
              elseif($degre31 == 270){
                $direction_wind31 = 'West';
              }
              elseif($degre31 > 270 && $degre31 <315){
                $direction_wind31 = 'West-NortWest';
              }
              elseif($degre31 == 315){
                $direction_wind31 = 'North-West';
              }
              elseif($degre31 > 315 && $degre31 <360){
                $direction_wind31 = 'North-NorthWest';
              }
              else{
                $direction_wind31 = 'North';
              }
            //sea
              if($wind_speed31 <= 0.3){
                $wind_force31 = 'Calm';
                $sea_force31 = 'Zero force';
              }
              elseif($wind_speed31 > 0.3 && $wind_speed31 < 1.5){
                $wind_force31 = 'Burr of wind';
                $sea_force31 = 'Force one';
              }
              elseif($wind_speed31 > 1.5 && $wind_speed31 < 3.3){
                $wind_force31 = 'Light breeze';
                $sea_force31 = 'Force two';
              }
              elseif($wind_speed31 > 3.3 && $wind_speed31 < 5.4){
                $wind_force31 = 'Breeze';
                $sea_force31 = 'Force two';
              }
              elseif($wind_speed31 > 5.4 && $wind_speed31 < 7.9){
                $wind_force31 = 'Livelly breeze';
                $sea_force31 = 'Force three';
              }
              elseif($wind_speed31 > 7.9 && $wind_speed31 < 10.7){
                $wind_force31 = 'Tense breeze';
                $sea_force31 = 'Force four';
              }
              elseif($wind_speed31 > 10.7 && $wind_speed31 < 13.8){
                $wind_force31 = 'Fresh wind';
                $sea_force31 = 'Force five';
              }
              elseif($wind_speed31 > 13.8 && $wind_speed31 < 17.1){
                $wind_force31 = 'Strong wind';
                $sea_force31 = 'Force six';
              }
              elseif($wind_speed31 > 17.1 && $wind_speed31 < 20.7){
                $wind_force31 = 'Moderate storm';
                $sea_force31 = 'Force seven';
              }
              elseif($wind_speed31 > 20.7 && $wind_speed31 < 24.4){
                $wind_force31 = 'Strong storm';
                $sea_force31 = 'Force eight';
              }
              elseif($wind_speed31 > 24.4 && $wind_speed31 < 28.4){
                $wind_force31 = 'Storm';
                $sea_force31 = 'Force nine';
              }
              elseif($wind_speed31 > 28.4 && $wind_speed31 < 32.6){
                $wind_force31 = 'Fortunale';
                $sea_force31 = 'Force ten';
              }
              elseif($wind_speed31 > 32.6){
                $wind_force31 = 'Hurricane';
                $sea_force31 = 'Force ten';
              }
              else{
                $wind_force31 = 'zero';
                $sea_force31 = 'zero';
              }
            //rain
              $rain31 = $clima['list'][31]['rain']['3h'];
            //snow
              $snow31 = $clima['list'][31]['snow']['3h'];
            //data/time of calculation
              $data_time_pre31 = $clima['list'][31]['dt_txt'];
              $data_time_confr31 = substr($data_time_pre31,0,11);
              $data_time31 = substr($data_time_pre31,11);

            //32
              $temperatura32 = $clima['list'][32]['main']['temp'];
              $temp_min32 = $clima['list'][32]['main']['temp_min'];
              $temp_max32 = $clima['list'][32]['main']['temp_max'];
              $pressure32 = $clima['list'][32]['main']['pressure'];
              $sea_level32 = $clima['list'][32]['main']['sea_level'];
              $ground_level32 = $clima['list'][32]['main']['grnd_level'];
              $humidity32 = $clima['list'][32]['main']['humidity'];
              $internal_parameter32 = $clima['list'][32]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm32 = round($pressure32*0.00098692, 3);
            //weather list
              $weather_condition_id32 = $clima['list'][32]['weather'][0]['id'];
              $weather_condition_main32 = $clima['list'][32]['weather'][0]['main'];
              $weather_description32 = $clima['list'][32]['weather'][0]['description'];
              $weather_icon32x = $clima['list'][32]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon32x == "01d") {
                $weather_icon32 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon32x == "01n") {
                $weather_icon32 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon32x == "02d") {
                $weather_icon32 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon32x == "02n") {
                $weather_icon32 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon32x == "03d") {
                $weather_icon32 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon32x == "03n") {
                $weather_icon32 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon32x == "04d") {
                $weather_icon32 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon32x == "04n") {
                $weather_icon32 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon32x == "9d"){ //
                $weather_icon32 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon32x == "9n"){ //
                $weather_icon32 = "🌧";
              /*

              */
              }
              elseif ($weather_icon32x == "10d"){
                $weather_icon32 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon32x == "10n"){
                $weather_icon32 = "🌦";
              /*

              */
              }
              elseif($weather_icon32x == "11d"){
                $weather_icon32 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon32x == "11n"){
                $weather_icon32 = "⛈";
                /*

                */
              }
              elseif ($weather_icon32x == "13d") {
                $weather_icon32 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon32x == "13n") {
              $weather_icon32 = "🌨";
              /*

              */
              }
              elseif ($weather_icon32x == "50d") {
                $weather_icon32 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon32x == "50n") {
                $weather_icon32 = "🌫";
                /*

                */
              }
            //clouds
              $clouds32 = $clima['list'][32]['clouds']['all'];
            //wind
              $wind_speed32 = $clima['list'][32]['wind']['speed'];
              $wind_deg32 = $clima['list'][32]['wind']['deg'];
              $degre32 = (int)$wind_deg32;
            //direction
              if($degre32 == 0){
                $direction_wind32 = 'North';
              }
              elseif($degre32 > 0 && $degre32 <45){
                $direction_wind32 = 'North-NorthEast';
              }
              elseif ($degre32 == 45) {
                $direction_wind32 = 'North-East';
              }
              elseif ($degre32 > 45 && $degre32 <90){
                $direction_wind32 = 'East-NorthEast';
              }
              elseif ($degre32 == 90){
                $direction_wind32 = 'East';
              }
              elseif($degre32 > 90 && $degre32 <135){
                $direction_wind32 = 'East-SouthEast';
              }
              elseif($degre32 == 135){
                $direction_wind32 = 'South-East';
              }
              elseif($degre32 > 135 && $degre32 <180){
                $direction_wind32 = 'South-SouthEast';
              }
              elseif($degre32 == 180){
                $direction_wind32 = 'South';
              }
              elseif($degre32 > 180 && $degre32 <225){
                $direction_wind32 = 'West-SouthWest';
              }
              elseif($degre32 == 225){
                $direction_wind32 = 'South-West';
              }
              elseif($degre32 > 225 && $degre32 <270){
                $direction_wind32 = 'West-SouthWest';
              }
              elseif($degre32 == 270){
                $direction_wind32 = 'West';
              }
              elseif($degre32 > 270 && $degre32 <315){
                $direction_wind32 = 'West-NortWest';
              }
              elseif($degre32 == 315){
                $direction_wind32 = 'North-West';
              }
              elseif($degre32 > 315 && $degre32 <360){
                $direction_wind32 = 'North-NorthWest';
              }
              else{
                $direction_wind32 = 'North';
              }
            //sea
              if($wind_speed32 <= 0.3){
                $wind_force32 = 'Calm';
                $sea_force32 = 'Zero force';
              }
              elseif($wind_speed32 > 0.3 && $wind_speed32 < 1.5){
                $wind_force32 = 'Burr of wind';
                $sea_force32 = 'Force one';
              }
              elseif($wind_speed32 > 1.5 && $wind_speed32 < 3.3){
                $wind_force32 = 'Light breeze';
                $sea_force32 = 'Force two';
              }
              elseif($wind_speed32 > 3.3 && $wind_speed32 < 5.4){
                $wind_force32 = 'Breeze';
                $sea_force32 = 'Force two';
              }
              elseif($wind_speed32 > 5.4 && $wind_speed32 < 7.9){
                $wind_force32 = 'Livelly breeze';
                $sea_force32 = 'Force three';
              }
              elseif($wind_speed32 > 7.9 && $wind_speed32 < 10.7){
                $wind_force32 = 'Tense breeze';
                $sea_force32 = 'Force four';
              }
              elseif($wind_speed32 > 10.7 && $wind_speed32 < 13.8){
                $wind_force32 = 'Fresh wind';
                $sea_force32 = 'Force five';
              }
              elseif($wind_speed32 > 13.8 && $wind_speed32 < 17.1){
                $wind_force32 = 'Strong wind';
                $sea_force32 = 'Force six';
              }
              elseif($wind_speed32 > 17.1 && $wind_speed32 < 20.7){
                $wind_force32 = 'Moderate storm';
                $sea_force32 = 'Force seven';
              }
              elseif($wind_speed32 > 20.7 && $wind_speed32 < 24.4){
                $wind_force32 = 'Strong storm';
                $sea_force32 = 'Force eight';
              }
              elseif($wind_speed32 > 24.4 && $wind_speed32 < 28.4){
                $wind_force32 = 'Storm';
                $sea_force32 = 'Force nine';
              }
              elseif($wind_speed32 > 28.4 && $wind_speed32 < 32.6){
                $wind_force32 = 'Fortunale';
                $sea_force32 = 'Force ten';
              }
              elseif($wind_speed32 > 32.6){
                $wind_force32 = 'Hurricane';
                $sea_force32 = 'Force ten';
              }
              else{
                $wind_force32 = 'zero';
                $sea_force32 = 'zero';
              }
            //rain
              $rain32 = $clima['list'][32]['rain']['3h'];
            //snow
              $snow32 = $clima['list'][32]['snow']['3h'];
            //data/time of calculation
              $data_time_pre32 = $clima['list'][32]['dt_txt'];
              $data_time_confr32 = substr($data_time_pre32,0,11);
              $data_time32 = substr($data_time_pre32,11);

            //33
              $temperatura33 = $clima['list'][33]['main']['temp'];
              $temp_min33 = $clima['list'][33]['main']['temp_min'];
              $temp_max33 = $clima['list'][33]['main']['temp_max'];
              $pressure33 = $clima['list'][33]['main']['pressure'];
              $sea_level33 = $clima['list'][33]['main']['sea_level'];
              $ground_level33 = $clima['list'][33]['main']['grnd_level'];
              $humidity33 = $clima['list'][33]['main']['humidity'];
              $internal_parameter33 = $clima['list'][33]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm33 = round($pressure33*0.00098692, 3);
            //weather list
              $weather_condition_id33 = $clima['list'][33]['weather'][0]['id'];
              $weather_condition_main33 = $clima['list'][33]['weather'][0]['main'];
              $weather_description33 = $clima['list'][33]['weather'][0]['description'];
              $weather_icon33x = $clima['list'][33]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon33x == "01d") {
                $weather_icon33 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon33x == "01n") {
                $weather_icon33 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon33x == "02d") {
                $weather_icon33 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon33x == "02n") {
                $weather_icon33 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon33x == "03d") {
                $weather_icon33 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon33x == "03n") {
                $weather_icon33 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon33x == "04d") {
                $weather_icon33 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon33x == "04n") {
                $weather_icon33 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon33x == "9d"){ //
                $weather_icon33 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon33x == "9n"){ //
                $weather_icon33 = "🌧";
              /*

              */
              }
              elseif ($weather_icon33x == "10d"){
                $weather_icon33 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon33x == "10n"){
                $weather_icon33 = "🌦";
              /*

              */
              }
              elseif($weather_icon33x == "11d"){
                $weather_icon33 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon33x == "11n"){
                $weather_icon33 = "⛈";
                /*

                */
              }
              elseif ($weather_icon33x == "13d") {
                $weather_icon33 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon33x == "13n") {
              $weather_icon33 = "🌨";
              /*

              */
              }
              elseif ($weather_icon33x == "50d") {
                $weather_icon33 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon33x == "50n") {
                $weather_icon33 = "🌫";
                /*

                */
              }
            //clouds
              $clouds33 = $clima['list'][33]['clouds']['all'];
            //wind
              $wind_speed33 = $clima['list'][33]['wind']['speed'];
              $wind_deg33 = $clima['list'][33]['wind']['deg'];
              $degre33 = (int)$wind_deg33;
            //direction
              if($degre33 == 0){
                $direction_wind33 = 'North';
              }
              elseif($degre33 > 0 && $degre33 <45){
                $direction_wind33 = 'North-NorthEast';
              }
              elseif ($degre33 == 45) {
                $direction_wind33 = 'North-East';
              }
              elseif ($degre33 > 45 && $degre33 <90){
                $direction_wind33 = 'East-NorthEast';
              }
              elseif ($degre33 == 90){
                $direction_wind33 = 'East';
              }
              elseif($degre33 > 90 && $degre33 <135){
                $direction_wind33 = 'East-SouthEast';
              }
              elseif($degre33 == 135){
                $direction_wind33 = 'South-East';
              }
              elseif($degre33 > 135 && $degre33 <180){
                $direction_wind33 = 'South-SouthEast';
              }
              elseif($degre33 == 180){
                $direction_wind33 = 'South';
              }
              elseif($degre33 > 180 && $degre33 <225){
                $direction_wind33 = 'West-SouthWest';
              }
              elseif($degre33 == 225){
                $direction_wind33 = 'South-West';
              }
              elseif($degre33 > 225 && $degre33 <270){
                $direction_wind33 = 'West-SouthWest';
              }
              elseif($degre33 == 270){
                $direction_wind33 = 'West';
              }
              elseif($degre33 > 270 && $degre33 <315){
                $direction_wind33 = 'West-NortWest';
              }
              elseif($degre33 == 315){
                $direction_wind33 = 'North-West';
              }
              elseif($degre33 > 315 && $degre33 <360){
                $direction_wind33 = 'North-NorthWest';
              }
              else{
                $direction_wind33 = 'North';
              }
            //sea
              if($wind_speed33 <= 0.3){
                $wind_force33 = 'Calm';
                $sea_force33 = 'Zero force';
              }
              elseif($wind_speed33 > 0.3 && $wind_speed33 < 1.5){
                $wind_force33 = 'Burr of wind';
                $sea_force33 = 'Force one';
              }
              elseif($wind_speed33 > 1.5 && $wind_speed33 < 3.3){
                $wind_force33 = 'Light breeze';
                $sea_force33 = 'Force two';
              }
              elseif($wind_speed33 > 3.3 && $wind_speed33 < 5.4){
                $wind_force33 = 'Breeze';
                $sea_force33 = 'Force two';
              }
              elseif($wind_speed33 > 5.4 && $wind_speed33 < 7.9){
                $wind_force33 = 'Livelly breeze';
                $sea_force33 = 'Force three';
              }
              elseif($wind_speed33 > 7.9 && $wind_speed33 < 10.7){
                $wind_force33 = 'Tense breeze';
                $sea_force33 = 'Force four';
              }
              elseif($wind_speed33 > 10.7 && $wind_speed33 < 13.8){
                $wind_force33 = 'Fresh wind';
                $sea_force33 = 'Force five';
              }
              elseif($wind_speed33 > 13.8 && $wind_speed33 < 17.1){
                $wind_force33 = 'Strong wind';
                $sea_force33 = 'Force six';
              }
              elseif($wind_speed33 > 17.1 && $wind_speed33 < 20.7){
                $wind_force33 = 'Moderate storm';
                $sea_force33 = 'Force seven';
              }
              elseif($wind_speed33 > 20.7 && $wind_speed33 < 24.4){
                $wind_force33 = 'Strong storm';
                $sea_force33 = 'Force eight';
              }
              elseif($wind_speed33 > 24.4 && $wind_speed33 < 28.4){
                $wind_force33 = 'Storm';
                $sea_force33 = 'Force nine';
              }
              elseif($wind_speed33 > 28.4 && $wind_speed33 < 32.6){
                $wind_force33 = 'Fortunale';
                $sea_force33 = 'Force ten';
              }
              elseif($wind_speed33 > 32.6){
                $wind_force33 = 'Hurricane';
                $sea_force33 = 'Force ten';
              }
              else{
                $wind_force33 = 'zero';
                $sea_force33 = 'zero';
              }
            //rain
              $rain33 = $clima['list'][33]['rain']['3h'];
            //snow
              $snow33 = $clima['list'][33]['snow']['3h'];
            //data/time of calculation
              $data_time_pre33 = $clima['list'][33]['dt_txt'];
              $data_time_confr33 = substr($data_time_pre33,0,11);
              $data_time33 = substr($data_time_pre33,11);

            //34
              $temperatura34 = $clima['list'][34]['main']['temp'];
              $temp_min34 = $clima['list'][34]['main']['temp_min'];
              $temp_max34 = $clima['list'][34]['main']['temp_max'];
              $pressure34 = $clima['list'][34]['main']['pressure'];
              $sea_level34 = $clima['list'][34]['main']['sea_level'];
              $ground_level34 = $clima['list'][34]['main']['grnd_level'];
              $humidity34 = $clima['list'][34]['main']['humidity'];
              $internal_parameter34 = $clima['list'][34]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm34 = round($pressure34*0.00098692, 3);
            //weather list
              $weather_condition_id34 = $clima['list'][34]['weather'][0]['id'];
              $weather_condition_main34 = $clima['list'][34]['weather'][0]['main'];
              $weather_description34 = $clima['list'][34]['weather'][0]['description'];
              $weather_icon34x = $clima['list'][34]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon34x == "01d") {
                $weather_icon34 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon34x == "01n") {
                $weather_icon34 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon34x == "02d") {
                $weather_icon34 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon34x == "02n") {
                $weather_icon34 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon34x == "03d") {
                $weather_icon34 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon34x == "03n") {
                $weather_icon34 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon34x == "04d") {
                $weather_icon34 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon34x == "04n") {
                $weather_icon34 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon34x == "9d"){ //
                $weather_icon34 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon34x == "9n"){ //
                $weather_icon34 = "🌧";
              /*

              */
              }
              elseif ($weather_icon34x == "10d"){
                $weather_icon34 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon34x == "10n"){
                $weather_icon34 = "🌦";
              /*

              */
              }
              elseif($weather_icon34x == "11d"){
                $weather_icon34 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon34x == "11n"){
                $weather_icon34 = "⛈";
                /*

                */
              }
              elseif ($weather_icon34x == "13d") {
                $weather_icon34 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon34x == "13n") {
              $weather_icon34 = "🌨";
              /*

              */
              }
              elseif ($weather_icon34x == "50d") {
                $weather_icon34 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon34x == "50n") {
                $weather_icon34 = "🌫";
                /*

                */
              }
            //clouds
              $clouds34 = $clima['list'][34]['clouds']['all'];
            //wind
              $wind_speed34 = $clima['list'][34]['wind']['speed'];
              $wind_deg34 = $clima['list'][34]['wind']['deg'];
              $degre34 = (int)$wind_deg34;
            //direction
              if($degre34 == 0){
                $direction_wind34 = 'North';
              }
              elseif($degre34 > 0 && $degre34 <45){
                $direction_wind34 = 'North-NorthEast';
              }
              elseif ($degre34 == 45) {
                $direction_wind34 = 'North-East';
              }
              elseif ($degre34 > 45 && $degre34 <90){
                $direction_wind34 = 'East-NorthEast';
              }
              elseif ($degre34 == 90){
                $direction_wind34 = 'East';
              }
              elseif($degre34 > 90 && $degre34 <135){
                $direction_wind34 = 'East-SouthEast';
              }
              elseif($degre34 == 135){
                $direction_wind34 = 'South-East';
              }
              elseif($degre34 > 135 && $degre34 <180){
                $direction_wind34 = 'South-SouthEast';
              }
              elseif($degre34 == 180){
                $direction_wind34 = 'South';
              }
              elseif($degre34 > 180 && $degre34 <225){
                $direction_wind34 = 'West-SouthWest';
              }
              elseif($degre34 == 225){
                $direction_wind34 = 'South-West';
              }
              elseif($degre34 > 225 && $degre34 <270){
                $direction_wind34 = 'West-SouthWest';
              }
              elseif($degre34 == 270){
                $direction_wind34 = 'West';
              }
              elseif($degre34 > 270 && $degre34 <315){
                $direction_wind34 = 'West-NortWest';
              }
              elseif($degre34 == 315){
                $direction_wind34 = 'North-West';
              }
              elseif($degre34 > 315 && $degre34 <360){
                $direction_wind34 = 'North-NorthWest';
              }
              else{
                $direction_wind34 = 'North';
              }
            //sea
              if($wind_speed34 <= 0.3){
                $wind_force34 = 'Calm';
                $sea_force34 = 'Zero force';
              }
              elseif($wind_speed34 > 0.3 && $wind_speed34 < 1.5){
                $wind_force34 = 'Burr of wind';
                $sea_force34 = 'Force one';
              }
              elseif($wind_speed34 > 1.5 && $wind_speed34 < 3.3){
                $wind_force34 = 'Light breeze';
                $sea_force34 = 'Force two';
              }
              elseif($wind_speed34 > 3.3 && $wind_speed34 < 5.4){
                $wind_force34 = 'Breeze';
                $sea_force34 = 'Force two';
              }
              elseif($wind_speed34 > 5.4 && $wind_speed34 < 7.9){
                $wind_force34 = 'Livelly breeze';
                $sea_force34 = 'Force three';
              }
              elseif($wind_speed34 > 7.9 && $wind_speed34 < 10.7){
                $wind_force34 = 'Tense breeze';
                $sea_force34 = 'Force four';
              }
              elseif($wind_speed34 > 10.7 && $wind_speed34 < 13.8){
                $wind_force34 = 'Fresh wind';
                $sea_force34 = 'Force five';
              }
              elseif($wind_speed34 > 13.8 && $wind_speed34 < 17.1){
                $wind_force34 = 'Strong wind';
                $sea_force34 = 'Force six';
              }
              elseif($wind_speed34 > 17.1 && $wind_speed34 < 20.7){
                $wind_force34 = 'Moderate storm';
                $sea_force34 = 'Force seven';
              }
              elseif($wind_speed34 > 20.7 && $wind_speed34 < 24.4){
                $wind_force34 = 'Strong storm';
                $sea_force34 = 'Force eight';
              }
              elseif($wind_speed34 > 24.4 && $wind_speed34 < 28.4){
                $wind_force34 = 'Storm';
                $sea_force34 = 'Force nine';
              }
              elseif($wind_speed34 > 28.4 && $wind_speed34 < 32.6){
                $wind_force34 = 'Fortunale';
                $sea_force34 = 'Force ten';
              }
              elseif($wind_speed34 > 32.6){
                $wind_force34 = 'Hurricane';
                $sea_force34 = 'Force ten';
              }
              else{
                $wind_force34 = 'zero';
                $sea_force34 = 'zero';
              }
            //rain
              $rain34 = $clima['list'][34]['rain']['3h'];
            //snow
              $snow34 = $clima['list'][34]['snow']['3h'];
            //data/time of calculation
              $data_time_pre34 = $clima['list'][34]['dt_txt'];
              $data_time_confr34 = substr($data_time_pre34,0,11);
              $data_time34 = substr($data_time_pre34,11);

            //35
              $temperatura35 = $clima['list'][35]['main']['temp'];
              $temp_min35 = $clima['list'][35]['main']['temp_min'];
              $temp_max35 = $clima['list'][35]['main']['temp_max'];
              $pressure35 = $clima['list'][35]['main']['pressure'];
              $sea_level35 = $clima['list'][35]['main']['sea_level'];
              $ground_level35 = $clima['list'][35]['main']['grnd_level'];
              $humidity35 = $clima['list'][35]['main']['humidity'];
              $internal_parameter35 = $clima['list'][35]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm35 = round($pressure35*0.00098692, 3);
            //weather list
              $weather_condition_id35 = $clima['list'][35]['weather'][0]['id'];
              $weather_condition_main35 = $clima['list'][35]['weather'][0]['main'];
              $weather_description35 = $clima['list'][35]['weather'][0]['description'];
              $weather_icon35x = $clima['list'][35]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon35x == "01d") {
                $weather_icon35 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon35x == "01n") {
                $weather_icon35 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon35x == "02d") {
                $weather_icon35 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon35x == "02n") {
                $weather_icon35 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon35x == "03d") {
                $weather_icon35 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon35x == "03n") {
                $weather_icon35 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon35x == "04d") {
                $weather_icon35 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon35x == "04n") {
                $weather_icon35 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon35x == "9d"){ //
                $weather_icon35 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon35x == "9n"){ //
                $weather_icon35 = "🌧";
              /*

              */
              }
              elseif ($weather_icon35x == "10d"){
                $weather_icon35 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon35x == "10n"){
                $weather_icon35 = "🌦";
              /*

              */
              }
              elseif($weather_icon35x == "11d"){
                $weather_icon35 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon35x == "11n"){
                $weather_icon35 = "⛈";
                /*

                */
              }
              elseif ($weather_icon35x == "13d") {
                $weather_icon35 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon35x == "13n") {
              $weather_icon35 = "🌨";
              /*

              */
              }
              elseif ($weather_icon35x == "50d") {
                $weather_icon35 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon35x == "50n") {
                $weather_icon35 = "🌫";
                /*

                */
              }
            //clouds
              $clouds35 = $clima['list'][35]['clouds']['all'];
            //wind
              $wind_speed35 = $clima['list'][35]['wind']['speed'];
              $wind_deg35 = $clima['list'][35]['wind']['deg'];
              $degre35 = (int)$wind_deg35;
            //direction
              if($degre35 == 0){
                $direction_wind35 = 'North';
              }
              elseif($degre35 > 0 && $degre35 <45){
                $direction_wind35 = 'North-NorthEast';
              }
              elseif ($degre35 == 45) {
                $direction_wind35 = 'North-East';
              }
              elseif ($degre35 > 45 && $degre35 <90){
                $direction_wind35 = 'East-NorthEast';
              }
              elseif ($degre35 == 90){
                $direction_wind35 = 'East';
              }
              elseif($degre35 > 90 && $degre35 <135){
                $direction_wind35 = 'East-SouthEast';
              }
              elseif($degre35 == 135){
                $direction_wind35 = 'South-East';
              }
              elseif($degre35 > 135 && $degre35 <180){
                $direction_wind35 = 'South-SouthEast';
              }
              elseif($degre35 == 180){
                $direction_wind35 = 'South';
              }
              elseif($degre35 > 180 && $degre35 <225){
                $direction_wind35 = 'West-SouthWest';
              }
              elseif($degre35 == 225){
                $direction_wind35 = 'South-West';
              }
              elseif($degre35 > 225 && $degre35 <270){
                $direction_wind35 = 'West-SouthWest';
              }
              elseif($degre35 == 270){
                $direction_wind35 = 'West';
              }
              elseif($degre35 > 270 && $degre35 <315){
                $direction_wind35 = 'West-NortWest';
              }
              elseif($degre35 == 315){
                $direction_wind35 = 'North-West';
              }
              elseif($degre35 > 315 && $degre35 <360){
                $direction_wind35 = 'North-NorthWest';
              }
              else{
                $direction_wind35 = 'North';
              }
            //sea
              if($wind_speed35 <= 0.3){
                $wind_force35 = 'Calm';
                $sea_force35 = 'Zero force';
              }
              elseif($wind_speed35 > 0.3 && $wind_speed35 < 1.5){
                $wind_force35 = 'Burr of wind';
                $sea_force35 = 'Force one';
              }
              elseif($wind_speed35 > 1.5 && $wind_speed35 < 3.3){
                $wind_force35 = 'Light breeze';
                $sea_force35 = 'Force two';
              }
              elseif($wind_speed35 > 3.3 && $wind_speed35 < 5.4){
                $wind_force35 = 'Breeze';
                $sea_force35 = 'Force two';
              }
              elseif($wind_speed35 > 5.4 && $wind_speed35 < 7.9){
                $wind_force35 = 'Livelly breeze';
                $sea_force35 = 'Force three';
              }
              elseif($wind_speed35 > 7.9 && $wind_speed35 < 10.7){
                $wind_force35 = 'Tense breeze';
                $sea_force35 = 'Force four';
              }
              elseif($wind_speed35 > 10.7 && $wind_speed35 < 13.8){
                $wind_force35 = 'Fresh wind';
                $sea_force35 = 'Force five';
              }
              elseif($wind_speed35 > 13.8 && $wind_speed35 < 17.1){
                $wind_force35 = 'Strong wind';
                $sea_force35 = 'Force six';
              }
              elseif($wind_speed35 > 17.1 && $wind_speed35 < 20.7){
                $wind_force35 = 'Moderate storm';
                $sea_force35 = 'Force seven';
              }
              elseif($wind_speed35 > 20.7 && $wind_speed35 < 24.4){
                $wind_force35 = 'Strong storm';
                $sea_force35 = 'Force eight';
              }
              elseif($wind_speed35 > 24.4 && $wind_speed35 < 28.4){
                $wind_force35 = 'Storm';
                $sea_force35 = 'Force nine';
              }
              elseif($wind_speed35 > 28.4 && $wind_speed35 < 32.6){
                $wind_force35 = 'Fortunale';
                $sea_force35 = 'Force ten';
              }
              elseif($wind_speed35 > 32.6){
                $wind_force35 = 'Hurricane';
                $sea_force35 = 'Force ten';
              }
              else{
                $wind_force35 = 'zero';
                $sea_force35 = 'zero';
              }
            //rain
              $rain35 = $clima['list'][35]['rain']['3h'];
            //snow
              $snow35 = $clima['list'][35]['snow']['3h'];
            //data/time of calculation
              $data_time_pre35 = $clima['list'][35]['dt_txt'];
              $data_time_confr35 = substr($data_time_pre35,0,11);
              $data_time35 = substr($data_time_pre35,11);

            //36
              $temperatura36 = $clima['list'][36]['main']['temp'];
              $temp_min36 = $clima['list'][36]['main']['temp_min'];
              $temp_max36 = $clima['list'][36]['main']['temp_max'];
              $pressure36 = $clima['list'][36]['main']['pressure'];
              $sea_level36 = $clima['list'][36]['main']['sea_level'];
              $ground_level36 = $clima['list'][36]['main']['grnd_level'];
              $humidity36 = $clima['list'][36]['main']['humidity'];
              $internal_parameter36 = $clima['list'][36]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm36 = round($pressure36*0.00098692, 3);
            //weather list
              $weather_condition_id36 = $clima['list'][36]['weather'][0]['id'];
              $weather_condition_main36 = $clima['list'][36]['weather'][0]['main'];
              $weather_description36 = $clima['list'][36]['weather'][0]['description'];
              $weather_icon36x = $clima['list'][36]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon36x == "01d") {
                $weather_icon36 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon36x == "01n") {
                $weather_icon36 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon36x == "02d") {
                $weather_icon36 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon36x == "02n") {
                $weather_icon36 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon36x == "03d") {
                $weather_icon36 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon36x == "03n") {
                $weather_icon36 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon36x == "04d") {
                $weather_icon36 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon36x == "04n") {
                $weather_icon36 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon36x == "9d"){ //
                $weather_icon36 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon36x == "9n"){ //
                $weather_icon36 = "🌧";
              /*

              */
              }
              elseif ($weather_icon36x == "10d"){
                $weather_icon36 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon36x == "10n"){
                $weather_icon36 = "🌦";
              /*

              */
              }
              elseif($weather_icon36x == "11d"){
                $weather_icon36 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon36x == "11n"){
                $weather_icon36 = "⛈";
                /*

                */
              }
              elseif ($weather_icon36x == "13d") {
                $weather_icon36 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon36x == "13n") {
              $weather_icon36 = "🌨";
              /*

              */
              }
              elseif ($weather_icon36x == "50d") {
                $weather_icon36 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon36x == "50n") {
                $weather_icon36 = "🌫";
                /*

                */
              }
            //clouds
              $clouds36 = $clima['list'][36]['clouds']['all'];
            //wind
              $wind_speed36 = $clima['list'][36]['wind']['speed'];
              $wind_deg36 = $clima['list'][36]['wind']['deg'];
              $degre36 = (int)$wind_deg36;
            //direction
              if($degre36 == 0){
                $direction_wind36 = 'North';
              }
              elseif($degre36 > 0 && $degre36 <45){
                $direction_wind36 = 'North-NorthEast';
              }
              elseif ($degre36 == 45) {
                $direction_wind36 = 'North-East';
              }
              elseif ($degre36 > 45 && $degre36 <90){
                $direction_wind36 = 'East-NorthEast';
              }
              elseif ($degre36 == 90){
                $direction_wind36 = 'East';
              }
              elseif($degre36 > 90 && $degre36 <135){
                $direction_wind36 = 'East-SouthEast';
              }
              elseif($degre36 == 135){
                $direction_wind36 = 'South-East';
              }
              elseif($degre36 > 135 && $degre36 <180){
                $direction_wind36 = 'South-SouthEast';
              }
              elseif($degre36 == 180){
                $direction_wind36 = 'South';
              }
              elseif($degre36 > 180 && $degre36 <225){
                $direction_wind36 = 'West-SouthWest';
              }
              elseif($degre36 == 225){
                $direction_wind36 = 'South-West';
              }
              elseif($degre36 > 225 && $degre36 <270){
                $direction_wind36 = 'West-SouthWest';
              }
              elseif($degre36 == 270){
                $direction_wind36 = 'West';
              }
              elseif($degre36 > 270 && $degre36 <315){
                $direction_wind36 = 'West-NortWest';
              }
              elseif($degre36 == 315){
                $direction_wind36 = 'North-West';
              }
              elseif($degre36 > 315 && $degre36 <360){
                $direction_wind36 = 'North-NorthWest';
              }
              else{
                $direction_wind36 = 'North';
              }
            //sea
              if($wind_speed36 <= 0.3){
                $wind_force36 = 'Calm';
                $sea_force36 = 'Zero force';
              }
              elseif($wind_speed36 > 0.3 && $wind_speed36 < 1.5){
                $wind_force36 = 'Burr of wind';
                $sea_force36 = 'Force one';
              }
              elseif($wind_speed36 > 1.5 && $wind_speed36 < 3.3){
                $wind_force36 = 'Light breeze';
                $sea_force36 = 'Force two';
              }
              elseif($wind_speed36 > 3.3 && $wind_speed36 < 5.4){
                $wind_force36 = 'Breeze';
                $sea_force36 = 'Force two';
              }
              elseif($wind_speed36 > 5.4 && $wind_speed36 < 7.9){
                $wind_force36 = 'Livelly breeze';
                $sea_force36 = 'Force three';
              }
              elseif($wind_speed36 > 7.9 && $wind_speed36 < 10.7){
                $wind_force36 = 'Tense breeze';
                $sea_force36 = 'Force four';
              }
              elseif($wind_speed36 > 10.7 && $wind_speed36 < 13.8){
                $wind_force36 = 'Fresh wind';
                $sea_force36 = 'Force five';
              }
              elseif($wind_speed36 > 13.8 && $wind_speed36 < 17.1){
                $wind_force36 = 'Strong wind';
                $sea_force36 = 'Force six';
              }
              elseif($wind_speed36 > 17.1 && $wind_speed36 < 20.7){
                $wind_force36 = 'Moderate storm';
                $sea_force36 = 'Force seven';
              }
              elseif($wind_speed36 > 20.7 && $wind_speed36 < 24.4){
                $wind_force36 = 'Strong storm';
                $sea_force36 = 'Force eight';
              }
              elseif($wind_speed36 > 24.4 && $wind_speed36 < 28.4){
                $wind_force36 = 'Storm';
                $sea_force36 = 'Force nine';
              }
              elseif($wind_speed36 > 28.4 && $wind_speed36 < 32.6){
                $wind_force36 = 'Fortunale';
                $sea_force36 = 'Force ten';
              }
              elseif($wind_speed36 > 32.6){
                $wind_force36 = 'Hurricane';
                $sea_force36 = 'Force ten';
              }
              else{
                $wind_force36 = 'zero';
                $sea_force36 = 'zero';
              }
            //rain
              $rain36 = $clima['list'][36]['rain']['3h'];
            //snow
              $snow36 = $clima['list'][36]['snow']['3h'];
            //data/time of calculation
              $data_time_pre36 = $clima['list'][36]['dt_txt'];
              $data_time_confr36 = substr($data_time_pre36,0,11);
              $data_time36 = substr($data_time_pre36,11);

            //37
              $temperatura37 = $clima['list'][37]['main']['temp'];
              $temp_min37 = $clima['list'][37]['main']['temp_min'];
              $temp_max37 = $clima['list'][37]['main']['temp_max'];
              $pressure37 = $clima['list'][37]['main']['pressure'];
              $sea_level37 = $clima['list'][37]['main']['sea_level'];
              $ground_level37 = $clima['list'][37]['main']['grnd_level'];
              $humidity37 = $clima['list'][37]['main']['humidity'];
              $internal_parameter37 = $clima['list'][37]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm37 = round($pressure37*0.00098692, 3);
            //weather list
              $weather_condition_id37 = $clima['list'][37]['weather'][0]['id'];
              $weather_condition_main37 = $clima['list'][37]['weather'][0]['main'];
              $weather_description37 = $clima['list'][37]['weather'][0]['description'];
              $weather_icon37x = $clima['list'][37]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon37x == "01d") {
                $weather_icon37 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon37x == "01n") {
                $weather_icon37 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon37x == "02d") {
                $weather_icon37 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon37x == "02n") {
                $weather_icon37 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon37x == "03d") {
                $weather_icon37 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon37x == "03n") {
                $weather_icon37 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon37x == "04d") {
                $weather_icon37 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon37x == "04n") {
                $weather_icon37 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon37x == "9d"){ //
                $weather_icon37 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon37x == "9n"){ //
                $weather_icon37 = "🌧";
              /*

              */
              }
              elseif ($weather_icon37x == "10d"){
                $weather_icon37 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon37x == "10n"){
                $weather_icon37 = "🌦";
              /*

              */
              }
              elseif($weather_icon37x == "11d"){
                $weather_icon37 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon37x == "11n"){
                $weather_icon37 = "⛈";
                /*

                */
              }
              elseif ($weather_icon37x == "13d") {
                $weather_icon37 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon37x == "13n") {
              $weather_icon37 = "🌨";
              /*

              */
              }
              elseif ($weather_icon37x == "50d") {
                $weather_icon37 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon37x == "50n") {
                $weather_icon37 = "🌫";
                /*

                */
              }
            //clouds
              $clouds37 = $clima['list'][37]['clouds']['all'];
            //wind
              $wind_speed37 = $clima['list'][37]['wind']['speed'];
              $wind_deg37 = $clima['list'][37]['wind']['deg'];
              $degre37 = (int)$wind_deg37;
            //direction
              if($degre37 == 0){
                $direction_wind37 = 'North';
              }
              elseif($degre37 > 0 && $degre37 <45){
                $direction_wind37 = 'North-NorthEast';
              }
              elseif ($degre37 == 45) {
                $direction_wind37 = 'North-East';
              }
              elseif ($degre37 > 45 && $degre37 <90){
                $direction_wind37 = 'East-NorthEast';
              }
              elseif ($degre37 == 90){
                $direction_wind37 = 'East';
              }
              elseif($degre37 > 90 && $degre37 <135){
                $direction_wind37 = 'East-SouthEast';
              }
              elseif($degre37 == 135){
                $direction_wind37 = 'South-East';
              }
              elseif($degre37 > 135 && $degre37 <180){
                $direction_wind37 = 'South-SouthEast';
              }
              elseif($degre37 == 180){
                $direction_wind37 = 'South';
              }
              elseif($degre37 > 180 && $degre37 <225){
                $direction_wind37 = 'West-SouthWest';
              }
              elseif($degre37 == 225){
                $direction_wind37 = 'South-West';
              }
              elseif($degre37 > 225 && $degre37 <270){
                $direction_wind37 = 'West-SouthWest';
              }
              elseif($degre37 == 270){
                $direction_wind37 = 'West';
              }
              elseif($degre37 > 270 && $degre37 <315){
                $direction_wind37 = 'West-NortWest';
              }
              elseif($degre37 == 315){
                $direction_wind37 = 'North-West';
              }
              elseif($degre37 > 315 && $degre37 <360){
                $direction_wind37 = 'North-NorthWest';
              }
              else{
                $direction_wind37 = 'North';
              }
            //sea
              if($wind_speed37 <= 0.3){
                $wind_force37 = 'Calm';
                $sea_force37 = 'Zero force';
              }
              elseif($wind_speed37 > 0.3 && $wind_speed37 < 1.5){
                $wind_force37 = 'Burr of wind';
                $sea_force37 = 'Force one';
              }
              elseif($wind_speed37 > 1.5 && $wind_speed37 < 3.3){
                $wind_force37 = 'Light breeze';
                $sea_force37 = 'Force two';
              }
              elseif($wind_speed37 > 3.3 && $wind_speed37 < 5.4){
                $wind_force37 = 'Breeze';
                $sea_force37 = 'Force two';
              }
              elseif($wind_speed37 > 5.4 && $wind_speed37 < 7.9){
                $wind_force37 = 'Livelly breeze';
                $sea_force37 = 'Force three';
              }
              elseif($wind_speed37 > 7.9 && $wind_speed37 < 10.7){
                $wind_force37 = 'Tense breeze';
                $sea_force37 = 'Force four';
              }
              elseif($wind_speed37 > 10.7 && $wind_speed37 < 13.8){
                $wind_force37 = 'Fresh wind';
                $sea_force37 = 'Force five';
              }
              elseif($wind_speed37 > 13.8 && $wind_speed37 < 17.1){
                $wind_force37 = 'Strong wind';
                $sea_force37 = 'Force six';
              }
              elseif($wind_speed37 > 17.1 && $wind_speed37 < 20.7){
                $wind_force37 = 'Moderate storm';
                $sea_force37 = 'Force seven';
              }
              elseif($wind_speed37 > 20.7 && $wind_speed37 < 24.4){
                $wind_force37 = 'Strong storm';
                $sea_force37 = 'Force eight';
              }
              elseif($wind_speed37 > 24.4 && $wind_speed37 < 28.4){
                $wind_force37 = 'Storm';
                $sea_force37 = 'Force nine';
              }
              elseif($wind_speed37 > 28.4 && $wind_speed37 < 32.6){
                $wind_force37 = 'Fortunale';
                $sea_force37 = 'Force ten';
              }
              elseif($wind_speed37 > 32.6){
                $wind_force37 = 'Hurricane';
                $sea_force37 = 'Force ten';
              }
              else{
                $wind_force37 = 'zero';
                $sea_force37 = 'zero';
              }
            //rain
              $rain37 = $clima['list'][37]['rain']['3h'];
            //snow
              $snow37 = $clima['list'][37]['snow']['3h'];
            //data/time of calculation
              $data_time_pre37 = $clima['list'][37]['dt_txt'];
              $data_time_confr37 = substr($data_time_pre37,0,11);
              $data_time37 = substr($data_time_pre37,11);

            //38
              $temperatura38 = $clima['list'][38]['main']['temp'];
              $temp_min38 = $clima['list'][38]['main']['temp_min'];
              $temp_max38 = $clima['list'][38]['main']['temp_max'];
              $pressure38 = $clima['list'][38]['main']['pressure'];
              $sea_level38 = $clima['list'][38]['main']['sea_level'];
              $ground_level38 = $clima['list'][38]['main']['grnd_level'];
              $humidity38 = $clima['list'][38]['main']['humidity'];
              $internal_parameter38 = $clima['list'][38]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm38 = round($pressure38*0.00098692, 3);
            //weather list
              $weather_condition_id38 = $clima['list'][38]['weather'][0]['id'];
              $weather_condition_main38 = $clima['list'][38]['weather'][0]['main'];
              $weather_description38 = $clima['list'][38]['weather'][0]['description'];
              $weather_icon38x = $clima['list'][38]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon38x == "01d") {
                $weather_icon38 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon38x == "01n") {
                $weather_icon38 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon38x == "02d") {
                $weather_icon38 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon38x == "02n") {
                $weather_icon38 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon38x == "03d") {
                $weather_icon38 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon38x == "03n") {
                $weather_icon38 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon38x == "04d") {
                $weather_icon38 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon38x == "04n") {
                $weather_icon38 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon38x == "9d"){ //
                $weather_icon38 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon38x == "9n"){ //
                $weather_icon38 = "🌧";
              /*

              */
              }
              elseif ($weather_icon38x == "10d"){
                $weather_icon38 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon38x == "10n"){
                $weather_icon38 = "🌦";
              /*

              */
              }
              elseif($weather_icon38x == "11d"){
                $weather_icon38 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon38x == "11n"){
                $weather_icon38 = "⛈";
                /*

                */
              }
              elseif ($weather_icon38x == "13d") {
                $weather_icon38 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon38x == "13n") {
              $weather_icon38 = "🌨";
              /*

              */
              }
              elseif ($weather_icon38x == "50d") {
                $weather_icon38 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon38x == "50n") {
                $weather_icon38 = "🌫";
                /*

                */
              }
            //clouds
              $clouds38 = $clima['list'][38]['clouds']['all'];
            //wind
              $wind_speed38 = $clima['list'][38]['wind']['speed'];
              $wind_deg38 = $clima['list'][38]['wind']['deg'];
              $degre38 = (int)$wind_deg38;
            //direction
              if($degre38 == 0){
                $direction_wind38 = 'North';
              }
              elseif($degre38 > 0 && $degre38 <45){
                $direction_wind38 = 'North-NorthEast';
              }
              elseif ($degre38 == 45) {
                $direction_wind38 = 'North-East';
              }
              elseif ($degre38 > 45 && $degre38 <90){
                $direction_wind38 = 'East-NorthEast';
              }
              elseif ($degre38 == 90){
                $direction_wind38 = 'East';
              }
              elseif($degre38 > 90 && $degre38 <135){
                $direction_wind38 = 'East-SouthEast';
              }
              elseif($degre38 == 135){
                $direction_wind38 = 'South-East';
              }
              elseif($degre38 > 135 && $degre38 <180){
                $direction_wind38 = 'South-SouthEast';
              }
              elseif($degre38 == 180){
                $direction_wind38 = 'South';
              }
              elseif($degre38 > 180 && $degre38 <225){
                $direction_wind38 = 'West-SouthWest';
              }
              elseif($degre38 == 225){
                $direction_wind38 = 'South-West';
              }
              elseif($degre38 > 225 && $degre38 <270){
                $direction_wind38 = 'West-SouthWest';
              }
              elseif($degre38 == 270){
                $direction_wind38 = 'West';
              }
              elseif($degre38 > 270 && $degre38 <315){
                $direction_wind38 = 'West-NortWest';
              }
              elseif($degre38 == 315){
                $direction_wind38 = 'North-West';
              }
              elseif($degre38 > 315 && $degre38 <360){
                $direction_wind38 = 'North-NorthWest';
              }
              else{
                $direction_wind38 = 'North';
              }
            //sea
              if($wind_speed38 <= 0.3){
                $wind_force38 = 'Calm';
                $sea_force38 = 'Zero force';
              }
              elseif($wind_speed38 > 0.3 && $wind_speed38 < 1.5){
                $wind_force38 = 'Burr of wind';
                $sea_force38 = 'Force one';
              }
              elseif($wind_speed38 > 1.5 && $wind_speed38 < 3.3){
                $wind_force38 = 'Light breeze';
                $sea_force38 = 'Force two';
              }
              elseif($wind_speed38 > 3.3 && $wind_speed38 < 5.4){
                $wind_force38 = 'Breeze';
                $sea_force38 = 'Force two';
              }
              elseif($wind_speed38 > 5.4 && $wind_speed38 < 7.9){
                $wind_force38 = 'Livelly breeze';
                $sea_force38 = 'Force three';
              }
              elseif($wind_speed38 > 7.9 && $wind_speed38 < 10.7){
                $wind_force38 = 'Tense breeze';
                $sea_force38 = 'Force four';
              }
              elseif($wind_speed38 > 10.7 && $wind_speed38 < 13.8){
                $wind_force38 = 'Fresh wind';
                $sea_force38 = 'Force five';
              }
              elseif($wind_speed38 > 13.8 && $wind_speed38 < 17.1){
                $wind_force38 = 'Strong wind';
                $sea_force38 = 'Force six';
              }
              elseif($wind_speed38 > 17.1 && $wind_speed38 < 20.7){
                $wind_force38 = 'Moderate storm';
                $sea_force38 = 'Force seven';
              }
              elseif($wind_speed38 > 20.7 && $wind_speed38 < 24.4){
                $wind_force38 = 'Strong storm';
                $sea_force38 = 'Force eight';
              }
              elseif($wind_speed38 > 24.4 && $wind_speed38 < 28.4){
                $wind_force38 = 'Storm';
                $sea_force38 = 'Force nine';
              }
              elseif($wind_speed38 > 28.4 && $wind_speed38 < 32.6){
                $wind_force38 = 'Fortunale';
                $sea_force38 = 'Force ten';
              }
              elseif($wind_speed38 > 32.6){
                $wind_force38 = 'Hurricane';
                $sea_force38 = 'Force ten';
              }
              else{
                $wind_force38 = 'zero';
                $sea_force38 = 'zero';
              }
            //rain
              $rain38 = $clima['list'][38]['rain']['3h'];
            //snow
              $snow38 = $clima['list'][38]['snow']['3h'];
            //data/time of calculation
              $data_time_pre38 = $clima['list'][38]['dt_txt'];
              $data_time_confr38 = substr($data_time_pre38,0,11);
              $data_time38 = substr($data_time_pre38,11);

            //39
              $temperatura39 = $clima['list'][39]['main']['temp'];
              $temp_min39 = $clima['list'][39]['main']['temp_min'];
              $temp_max39 = $clima['list'][39]['main']['temp_max'];
              $pressure39 = $clima['list'][39]['main']['pressure'];
              $sea_level39 = $clima['list'][39]['main']['sea_level'];
              $ground_level39 = $clima['list'][39]['main']['grnd_level'];
              $humidity39 = $clima['list'][39]['main']['humidity'];
              $internal_parameter39 = $clima['list'][39]['main']['temp_kf'];
              //conversione pressione in atm
                $pressure_atm39 = round($pressure39*0.00098692, 3);
            //weather list
              $weather_condition_id39 = $clima['list'][39]['weather'][0]['id'];
              $weather_condition_main39 = $clima['list'][39]['weather'][0]['main'];
              $weather_description39 = $clima['list'][39]['weather'][0]['description'];
              $weather_icon39x = $clima['list'][39]['weather'][0]['icon'];
            // simbolo del meteo con wheather icon
              if ($weather_icon39x == "01d") {
                $weather_icon39 = "☀️";
                /*800	clear sky	 01d
                */
              }
              elseif ($weather_icon39x == "01n") {
                $weather_icon39 = "🌖";
                /*800	clear sky	 01n
                */
              }
              elseif ($weather_icon39x == "02d") {
                $weather_icon39 = "🌤";
                /*801	few clouds	 02d
                */
              }
              elseif ($weather_icon39x == "02n") {
                $weather_icon39 = "☁️";
                /*801	few clouds	 02n
                */
              }
              elseif ($weather_icon39x == "03d") {
                $weather_icon39 = "⛅️";
                /*802	scattered clouds	 03d
                */
              }
              elseif ($weather_icon39x == "03n") {
                $weather_icon39 = "☁️";
                /*802	scattered clouds	 03n
                */
              }
              elseif ($weather_icon39x == "04d") {
                $weather_icon39 = "🌥";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon39x == "04n") {
                $weather_icon39 = "☁️";
                /*803	broken clouds	 04d
                */
              }
              elseif ($weather_icon39x == "9d"){ //
                $weather_icon39 = "🌧";
              /*300	light intensity drizzle	 09d
                301	drizzle	 09d
                302	heavy intensity drizzle	 09d
                310	light intensity drizzle rain	 09d
                311	drizzle rain	 09d
                312	heavy intensity drizzle rain	 09d
                313	shower rain and drizzle	 09d
                314	heavy shower rain and drizzle	 09d
                321	shower drizzle	 09d
                520	light intensity shower rain	 09d
                521	shower rain	 09d
                522	heavy intensity shower rain	 09d
                531	ragged shower rain	 09d
              */
              }
              elseif ($weather_icon39x == "9n"){ //
                $weather_icon39 = "🌧";
              /*

              */
              }
              elseif ($weather_icon39x == "10d"){
                $weather_icon39 = "🌦";
              /*500	light rain	 10d
                501	moderate rain	 10d
                502	heavy intensity rain	 10d
                503	very heavy rain	 10d
                504	extreme rain	 10d
              */
              }
              elseif ($weather_icon39x == "10n"){
                $weather_icon39 = "🌦";
              /*

              */
              }
              elseif($weather_icon39x == "11d"){
                $weather_icon39 = "⛈";
                /*200	thunderstorm with light rain	 11d
                  201	thunderstorm with rain	 11d
                  202	thunderstorm with heavy rain	 11d
                  210	light thunderstorm	 11d
                  211	thunderstorm	 11d
                  212	heavy thunderstorm	 11d
                  221	ragged thunderstorm	 11d
                  230	thunderstorm with light drizzle	 11d
                  231	thunderstorm with drizzle	 11d
                  232	thunderstorm with heavy drizzle
                */
              }
              elseif($weather_icon39x == "11n"){
                $weather_icon39 = "⛈";
                /*

                */
              }
              elseif ($weather_icon39x == "13d") {
                $weather_icon39 = "🌨";
                /*511	freezing rain	 13d
                  600	light snow	 13d
                  601	snow	 13d
                  602	heavy snow	 13d
                  611	sleet	 13d
                  612	shower sleet	 13d
                  615	light rain and snow	 13d
                  616	rain and snow	 13d
                  620	light shower snow	 13d
                  621	shower snow	 13d
                  622	heavy shower snow	 13d
                */
              }
              elseif ($weather_icon39x == "13n") {
              $weather_icon39 = "🌨";
              /*

              */
              }
              elseif ($weather_icon39x == "50d") {
                $weather_icon39 = "🌫";
                /*701	mist	 50d
                  711	smoke	 50d
                  721	haze	 50d
                  731	sand, dust whirls	 50d
                  741	fog	 50d
                  751	sand	 50d
                  761	dust	 50d
                  762	volcanic ash	 50d
                  771	squalls	 50d
                  781	tornado	 50d
                */
              }
              elseif ($weather_icon39x == "50n") {
                $weather_icon39 = "🌫";
                /*

                */
              }
            //clouds
              $clouds39 = $clima['list'][39]['clouds']['all'];
            //wind
              $wind_speed39 = $clima['list'][39]['wind']['speed'];
              $wind_deg39 = $clima['list'][39]['wind']['deg'];
              $degre39 = (int)$wind_deg39;
            //direction
              if($degre39 == 0){
                $direction_wind39 = 'North';
              }
              elseif($degre39 > 0 && $degre39 <45){
                $direction_wind39 = 'North-NorthEast';
              }
              elseif ($degre39 == 45) {
                $direction_wind39 = 'North-East';
              }
              elseif ($degre39 > 45 && $degre39 <90){
                $direction_wind39 = 'East-NorthEast';
              }
              elseif ($degre39 == 90){
                $direction_wind39 = 'East';
              }
              elseif($degre39 > 90 && $degre39 <135){
                $direction_wind39 = 'East-SouthEast';
              }
              elseif($degre39 == 135){
                $direction_wind39 = 'South-East';
              }
              elseif($degre39 > 135 && $degre39 <180){
                $direction_wind39 = 'South-SouthEast';
              }
              elseif($degre39 == 180){
                $direction_wind39 = 'South';
              }
              elseif($degre39 > 180 && $degre39 <225){
                $direction_wind39 = 'West-SouthWest';
              }
              elseif($degre39 == 225){
                $direction_wind39 = 'South-West';
              }
              elseif($degre39 > 225 && $degre39 <270){
                $direction_wind39 = 'West-SouthWest';
              }
              elseif($degre39 == 270){
                $direction_wind39 = 'West';
              }
              elseif($degre39 > 270 && $degre39 <315){
                $direction_wind39 = 'West-NortWest';
              }
              elseif($degre39 == 315){
                $direction_wind39 = 'North-West';
              }
              elseif($degre39 > 315 && $degre39 <360){
                $direction_wind39 = 'North-NorthWest';
              }
              else{
                $direction_wind39 = 'North';
              }
            //sea
              if($wind_speed39 <= 0.3){
                $wind_force39 = 'Calm';
                $sea_force39 = 'Zero force';
              }
              elseif($wind_speed39 > 0.3 && $wind_speed39 < 1.5){
                $wind_force39 = 'Burr of wind';
                $sea_force39 = 'Force one';
              }
              elseif($wind_speed39 > 1.5 && $wind_speed39 < 3.3){
                $wind_force39 = 'Light breeze';
                $sea_force39 = 'Force two';
              }
              elseif($wind_speed39 > 3.3 && $wind_speed39 < 5.4){
                $wind_force39 = 'Breeze';
                $sea_force39 = 'Force two';
              }
              elseif($wind_speed39 > 5.4 && $wind_speed39 < 7.9){
                $wind_force39 = 'Livelly breeze';
                $sea_force39 = 'Force three';
              }
              elseif($wind_speed39 > 7.9 && $wind_speed39 < 10.7){
                $wind_force39 = 'Tense breeze';
                $sea_force39 = 'Force four';
              }
              elseif($wind_speed39 > 10.7 && $wind_speed39 < 13.8){
                $wind_force39 = 'Fresh wind';
                $sea_force39 = 'Force five';
              }
              elseif($wind_speed39 > 13.8 && $wind_speed39 < 17.1){
                $wind_force39 = 'Strong wind';
                $sea_force39 = 'Force six';
              }
              elseif($wind_speed39 > 17.1 && $wind_speed39 < 20.7){
                $wind_force39 = 'Moderate storm';
                $sea_force39 = 'Force seven';
              }
              elseif($wind_speed39 > 20.7 && $wind_speed39 < 24.4){
                $wind_force39 = 'Strong storm';
                $sea_force39 = 'Force eight';
              }
              elseif($wind_speed39 > 24.4 && $wind_speed39 < 28.4){
                $wind_force39 = 'Storm';
                $sea_force39 = 'Force nine';
              }
              elseif($wind_speed39 > 28.4 && $wind_speed39 < 32.6){
                $wind_force39 = 'Fortunale';
                $sea_force39 = 'Force ten';
              }
              elseif($wind_speed39 > 32.6){
                $wind_force39 = 'Hurricane';
                $sea_force39 = 'Force ten';
              }
              else{
                $wind_force39 = 'zero';
                $sea_force39 = 'zero';
              }
            //rain
              $rain39 = $clima['list'][39]['rain']['3h'];
            //snow
              $snow39 = $clima['list'][39]['snow']['3h'];
            //data/time of calculation
              $data_time_pre39 = $clima['list'][39]['dt_txt'];
              $data_time_confr39 = substr($data_time_pre39,0,11);
              $data_time39 = substr($data_time_pre39,11);

/*
              __ _          _
             / _(_)        | |
            | |_ _ _ __ ___| |_   _ __ ___   ___  ___  __ _  __ _  ___
            |  _| | '__/ __| __| | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
            | | | | |  \__ \ |_  | | | | | |  __/\__ \ (_| | (_| |  __/
            |_| |_|_|  |___/\__| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                             __/ |
                                                            |___/
*/

              //1 °
                if($data_time_confr1 == $data_time_confr0){
                  $a1 = $temperatura1;
                  $b1 = $temp_min1;
                  $c1 = $temp_max1;
                  $d1 = $pressure1;
                  $e1 = $sea_level1;
                  $f1 = $ground_level1;
                  $g1 = $humidity1;

                //weather list
                  $n1 = $weather_condition_id1;
                  $o1 = $weather_condition_main1;
                  $p1 = $weather_description1;
                  $q1 = $weather_icon1;
                //clouds
                  $r1 = $clouds1;
                //wind
                  $s1 = $wind_speed1;
                  $t1 = $wind_deg1;
                  $h1 = $direction_wind1;
                  $z1 = $wind_force1;
                  $l1 = $sea_force1;
                //rain
                  $u1 = $rain1;
                //snow
                  $v1 = $snow1;
                //data/time of calculation
                  $w1 = $data_time_pre1;
                  $y1 = $data_time1;
                }
                else{
                  $a1 = '';
                  $b1 = '';
                  $c1 = '';
                  $d1 = '';
                  $e1 = '';
                  $f1 = '';
                  $g1 = '';

                  //temperatura in gradi c
                    $i1 = '';
                    $l1 = '';
                    $m1 = '';
                //weather list
                  $n1 = '';
                  $o1 = '';
                  $p1 = '';
                  $q1 = '';
                //clouds
                  $r1 = '';
                //wind
                  $s1 = '';
                  $t1 = '';
                  $h1 = '';
                  $z1 = '';
                  $l1 = '';
                //rain
                  $u1 = '';
                //snow
                  $v1 = '';
                //data/time of calculation
                  $w1 = '';
                  $y1 = '';
                }

              //2 °
                if($data_time_confr2 == $data_time_confr0){
                  $a2 = $temperatura2;
                  $b2 = $temp_min2;
                  $c2 = $temp_max2;
                  $d2 = $pressure2;
                  $e2 = $sea_level2;
                  $f2 = $ground_level2;
                  $g2 = $humidity2;

                //weather list
                  $n2 = $weather_condition_id2;
                  $o2 = $weather_condition_main2;
                  $p2 = $weather_description2;
                  $q2 = $weather_icon2;
                //clouds
                  $r2 = $clouds2;
                //wind
                  $s2 = $wind_speed2;
                  $t2 = $wind_deg2;
                  $h2 = $direction_wind2;
                  $z2 = $wind_force2;
                  $l2 = $sea_force2;
                //rain
                  $u2 = $rain2;
                //snow
                  $v2 = $snow2;
                //data/time of calculation
                  $w2 = $data_time_pre2;
                  $y2 = $data_time2;
                }
                else{
                  $a2 = '';
                  $b2 = '';
                  $c2 = '';
                  $d2 = '';
                  $e2 = '';
                  $f2 = '';
                  $g2 = '';

                  //temperatura in gradi c
                    $i2 = '';
                    $l2 = '';
                    $m2 = '';
                //weather list
                  $n2 = '';
                  $o2 = '';
                  $p2 = '';
                  $q2 = '';
                //clouds
                  $r2 = '';
                //wind
                  $s2 = '';
                  $t2 = '';
                  $h2 = '';
                  $z2 = '';
                  $l2 = '';
                //rain
                  $u2 = '';
                //snow
                  $v2 = '';
                //data/time of calculation
                  $w2 = '';
                  $y2 = '';
                }

              //3 °
                if($data_time_confr3 == $data_time_confr0){
                  $a3 = $temperatura3;
                  $b3 = $temp_min3;
                  $c3 = $temp_max3;
                  $d3 = $pressure3;
                  $e3 = $sea_level3;
                  $f3 = $ground_level3;
                  $g3 = $humidity3;

                //weather list
                  $n3 = $weather_condition_id3;
                  $o3 = $weather_condition_main3;
                  $p3 = $weather_description3;
                  $q3 = $weather_icon3;
                //clouds
                  $r3 = $clouds3;
                //wind
                  $s3 = $wind_speed3;
                  $t3 = $wind_deg3;
                  $h3 = $direction_wind3;
                  $z3 = $wind_force3;
                  $l3 = $sea_force3;
                //rain
                  $u3 = $rain3;
                //snow
                  $v3 = $snow3;
                //data/time of calculation
                  $w3 = $data_time_pre3;
                  $y3 = $data_time3;
                }
                else{
                  $a3 = '';
                  $b3 = '';
                  $c3 = '';
                  $d3 = '';
                  $e3 = '';
                  $f3 = '';
                  $g3 = '';

                  //temperatura in gradi c
                    $i3 = '';
                    $l3 = '';
                    $m3 = '';
                //weather list
                  $n3 = '';
                  $o3 = '';
                  $p3 = '';
                  $q3 = '';
                //clouds
                  $r3 = '';
                //wind
                  $s3 = '';
                  $t3 = '';
                  $h3 = '';
                  $z3 = '';
                  $l3 = '';
                //rain
                  $u3 = '';
                //snow
                  $v3 = '';
                //data/time of calculation
                  $w3 = '';
                  $y3 = '';
                }

              //4 °
                if($data_time_confr4 == $data_time_confr0){
                  $a4 = $temperatura4;
                  $b4 = $temp_min4;
                  $c4 = $temp_max4;
                  $d4 = $pressure4;
                  $e4 = $sea_level4;
                  $f4 = $ground_level4;
                  $g4 = $humidity4;

                //weather list
                  $n4 = $weather_condition_id4;
                  $o4 = $weather_condition_main4;
                  $p4 = $weather_description4;
                  $q4 = $weather_icon4;
                //clouds
                  $r4 = $clouds4;
                //wind
                  $s4 = $wind_speed4;
                  $t4 = $wind_deg4;
                  $h4 = $direction_wind4;
                  $z4 = $wind_force4;
                  $l4 = $sea_force4;
                //rain
                  $u4 = $rain4;
                //snow
                  $v4 = $snow4;
                //data/time of calculation
                  $w4 = $data_time_pre4;
                  $y4 = $data_time4;
                }
                else{
                  $a4 = '';
                  $b4 = '';
                  $c4 = '';
                  $d4 = '';
                  $e4 = '';
                  $f4 = '';
                  $g4 = '';

                  //temperatura in gradi c
                    $i4 = '';
                    $l4 = '';
                    $m4 = '';
                //weather list
                  $n4 = '';
                  $o4 = '';
                  $p4 = '';
                  $q4 = '';
                //clouds
                  $r4 = '';
                //wind
                  $s4 = '';
                  $t4 = '';
                  $h4 = '';
                  $z4 = '';
                  $l4 = '';
                //rain
                  $u4 = '';
                //snow
                  $v4 = '';
                //data/time of calculation
                  $w4 = '';
                  $y4 = '';
                }

              //5 °
                if($data_time_confr5 == $data_time_confr0){
                  $a5 = $temperatura5;
                  $b5 = $temp_min5;
                  $c5 = $temp_max5;
                  $d5 = $pressure5;
                  $e5 = $sea_level5;
                  $f5 = $ground_level5;
                  $g5 = $humidity5;

                //weather list
                  $n5 = $weather_condition_id5;
                  $o5 = $weather_condition_main5;
                  $p5 = $weather_description5;
                  $q5 = $weather_icon5;
                //clouds
                  $r5 = $clouds5;
                //wind
                  $s5 = $wind_speed5;
                  $t5 = $wind_deg5;
                  $h5 = $direction_wind5;
                  $z5 = $wind_force5;
                  $l5 = $sea_force5;
                //rain
                  $u5 = $rain5;
                //snow
                  $v5 = $snow5;
                //data/time of calculation
                  $w5 = $data_time_pre5;
                  $y5 = $data_time5;
                }
                else{
                  $a5 = '';
                  $b5 = '';
                  $c5 = '';
                  $d5 = '';
                  $e5 = '';
                  $f5 = '';
                  $g5 = '';

                  //temperatura in gradi c
                    $i5 = '';
                    $l5 = '';
                    $m5 = '';
                //weather list
                  $n5 = '';
                  $o5 = '';
                  $p5 = '';
                  $q5 = '';
                //clouds
                  $r5 = '';
                //wind
                  $s5 = '';
                  $t5 = '';
                  $h5 = '';
                  $z5 = '';
                  $l5 = '';
                //rain
                  $u5 = '';
                //snow
                  $v5 = '';
                //data/time of calculation
                  $w5 = '';
                  $y5 = '';
                }

              //6 °
                if($data_time_confr6 == $data_time_confr0){
                  $a6 = $temperatura6;
                  $b6 = $temp_min6;
                  $c6 = $temp_max6;
                  $d6 = $pressure6;
                  $e6 = $sea_level6;
                  $f6 = $ground_level6;
                  $g6 = $humidity6;

                //weather list
                  $n6 = $weather_condition_id6;
                  $o6 = $weather_condition_main6;
                  $p6 = $weather_description6;
                  $q6 = $weather_icon6;
                //clouds
                  $r6 = $clouds6;
                //wind
                  $s6 = $wind_speed6;
                  $t6 = $wind_deg6;
                  $h6 = $direction_wind6;
                  $z6 = $wind_force6;
                  $l6 = $sea_force6;
                //rain
                  $u6 = $rain6;
                //snow
                  $v6 = $snow6;
                //data/time of calculation
                  $w6 = $data_time_pre6;
                  $y6 = $data_time6;
                }
                else{
                  $a6 = '';
                  $b6 = '';
                  $c6 = '';
                  $d6 = '';
                  $e6 = '';
                  $f6 = '';
                  $g6 = '';

                  //temperatura in gradi c
                    $i6 = '';
                    $l6 = '';
                    $m6 = '';
                //weather list
                  $n6 = '';
                  $o6 = '';
                  $p6 = '';
                  $q6 = '';
                //clouds
                  $r6 = '';
                //wind
                  $s6 = '';
                  $t6 = '';
                  $h6 = '';
                  $z6 = '';
                  $l6 = '';
                //rain
                  $u6 = '';
                //snow
                  $v6 = '';
                //data/time of calculation
                  $w6 = '';
                  $y6 = '';
                }

              //7 °
                if($data_time_confr7 == $data_time_confr0){
                  $a7 = $temperatura7;
                  $b7 = $temp_min7;
                  $c7 = $temp_max7;
                  $d7 = $pressure7;
                  $e7 = $sea_level7;
                  $f7 = $ground_level7;
                  $g7 = $humidity7;

                //weather list
                  $n7 = $weather_condition_id7;
                  $o7 = $weather_condition_main7;
                  $p7 = $weather_description7;
                  $q7 = $weather_icon7;
                //clouds
                  $r7 = $clouds7;
                //wind
                  $s7 = $wind_speed7;
                  $t7 = $wind_deg7;
                  $h7 = $direction_wind7;
                  $z7 = $wind_force7;
                  $l7 = $sea_force7;
                //rain
                  $u7 = $rain7;
                //snow
                  $v7 = $snow7;
                //data/time of calculation
                  $w7 = $data_time_pre7;
                  $y7 = $data_time7;
                }
                else{
                  $a7 = '';
                  $b7 = '';
                  $c7 = '';
                  $d7 = '';
                  $e7 = '';
                  $f7 = '';
                  $g7 = '';

                  //temperatura in gradi c
                    $i7 = '';
                    $l7 = '';
                    $m7 = '';
                //weather list
                  $n7 = '';
                  $o7 = '';
                  $p7 = '';
                  $q7 = '';
                //clouds
                  $r7 = '';
                //wind
                  $s7 = '';
                  $t7 = '';
                  $h7 = '';
                  $z7 = '';
                  $l7 = '';
                //rain
                  $u7 = '';
                //snow
                  $v7 = '';
                //data/time of calculation
                  $w7 = '';
                  $y7 = '';
                }

  //messggio primo giorno
  $bollettino_meteo_1 ="
<b>Day: $data_time_confr0</b>
<b>T</b>: $temperatura0 $a1 $a2 $a3 $a4 $a5 $a6 $a7
<b>Tmin</b>: $temp_min0 $b1 $b2 $b3 $b4 $b5 $b6 $b7
<b>Tmax</b>: $temp_max0 $c1 $c2 $c3 $c4 $c5 $c6 $c7
<b>Pressure</b>: $pressure0 $d1 $d2 $d3 $d4 $d5 $d6 $d7
<b>Sea level</b>: $sea_level0 $e1 $e2 $e3 $e4 $e5 $e6 $e7
<b>Ground level</b>: $ground_level0 $f1 $f2 $f3 $f4 $f5 $f6 $f7
<b>Humidity</b>: $humidity0 $g1 $g2 $g3 $g4 $g5 $g6 $g7

<b>Wweather</b> : $weather_icon0 $q1 $q2 $q3 $q4 $q5 $q6 $q7
<b>Weather Condiction</b>: $weather_condition_main0 $n1 $n2 $n3 $n4 $n5 $n6 $n7
<b>Weather Descriptione</b>: $weather_description0 $o1 $o2 $o3 $o4 $o5 $o6 $o7

<b>Clouds</b>: $clouds0 $r1 $r2 $r3 $r4 $r5 $r6 $r7

<b>Wind Speed</b>: $wind_speed0 $s1 $s2 $s3 $s4 $s5 $s6 $s7
<b>Wind Deg</b>: $wind_deg0 $t1 $t2 $t3 $t4 $t5 $t6 $t7
<b>Wind Direction</b>: $direction_wind0 $h1 $h2 $h3 $h4 $h5 $h6 $h7
<b>Wind Force</b>: $wind_force0 $z1 $z2 $z3 $z4 $z5 $z6 $z7
<b>Sea Force</b>: $sea_force0 $l1 $l2 $l3 $l4 $l5 $l6 $l7

<b>Rain</b>: $rain0 $u1 $u2 $u3 $u4 $u5 $u6 $u7

<b>Snow</b>: $snow0 $v1 $v2 $v3 $v4 $v5 $v6 $v7

<b>Time</b>: $data_time0 $y1 $y2 $y3 $y4 $y5 $y6 $y7
  ";
  sendMessage($chat_id, $bollettino_meteo_1);

/*
                                _
                               | |
   ___  ___  ___ ___  _ __   __| |  _ __ ___   ___  ___  __ _  __ _  ___
  / __|/ _ \/ __/ _ \| '_ \ / _` | | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
  \__ \  __/ (_| (_) | | | | (_| | | | | | | |  __/\__ \ (_| | (_| |  __/
  |___/\___|\___\___/|_| |_|\__,_| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                              __/ |
                                                             |___/
*/

  //1 °
    if($data_time_confr1 == $data_time_confr8){
      $a1 = $temperatura1;
      $b1 = $temp_min1;
      $c1 = $temp_max1;
      $d1 = $pressure1;
      $e1 = $sea_level1;
      $f1 = $ground_level1;
      $g1 = $humidity1;

    //weather list
      $n1 = $weather_condition_id1;
      $o1 = $weather_condition_main1;
      $p1 = $weather_description1;
      $q1 = $weather_icon1;
    //clouds
      $r1 = $clouds1;
    //wind
      $s1 = $wind_speed1;
      $t1 = $wind_deg1;
      $h1 = $direction_wind1;
      $z1 = $wind_force1;
      $l1 = $sea_force1;
    //rain
      $u1 = $rain1;
    //snow
      $v1 = $snow1;
    //data/time of calculation
      $w1 = $data_time_pre1;
      $y1 = $data_time1;
    }
    else{
      $a1 = '';
      $b1 = '';
      $c1 = '';
      $d1 = '';
      $e1 = '';
      $f1 = '';
      $g1 = '';

      //temperatura in gradi c
        $i1 = '';
        $l1 = '';
        $m1 = '';
    //weather list
      $n1 = '';
      $o1 = '';
      $p1 = '';
      $q1 = '';
    //clouds
      $r1 = '';
    //wind
      $s1 = '';
      $t1 = '';
      $h1 = '';
      $z1 = '';
      $l1 = '';
    //rain
      $u1 = '';
    //snow
      $v1 = '';
    //data/time of calculation
      $w1 = '';
      $y1 = '';
    }

  //2 °
    if($data_time_confr2 == $data_time_confr8){
      $a2 = $temperatura2;
      $b2 = $temp_min2;
      $c2 = $temp_max2;
      $d2 = $pressure2;
      $e2 = $sea_level2;
      $f2 = $ground_level2;
      $g2 = $humidity2;

    //weather list
      $n2 = $weather_condition_id2;
      $o2 = $weather_condition_main2;
      $p2 = $weather_description2;
      $q2 = $weather_icon2;
    //clouds
      $r2 = $clouds2;
    //wind
      $s2 = $wind_speed2;
      $t2 = $wind_deg2;
      $h2 = $direction_wind2;
      $z2 = $wind_force2;
      $l2 = $sea_force2;
    //rain
      $u2 = $rain2;
    //snow
      $v2 = $snow2;
    //data/time of calculation
      $w2 = $data_time_pre2;
      $y2 = $data_time2;
    }
    else{
      $a2 = '';
      $b2 = '';
      $c2 = '';
      $d2 = '';
      $e2 = '';
      $f2 = '';
      $g2 = '';

      //temperatura in gradi c
        $i2 = '';
        $l2 = '';
        $m2 = '';
    //weather list
      $n2 = '';
      $o2 = '';
      $p2 = '';
      $q2 = '';
    //clouds
      $r2 = '';
    //wind
      $s2 = '';
      $t2 = '';
      $h2 = '';
      $z2 = '';
      $l2 = '';
    //rain
      $u2 = '';
    //snow
      $v2 = '';
    //data/time of calculation
      $w2 = '';
      $y2 = '';
    }

  //3 °
    if($data_time_confr3 == $data_time_confr8){
      $a3 = $temperatura3;
      $b3 = $temp_min3;
      $c3 = $temp_max3;
      $d3 = $pressure3;
      $e3 = $sea_level3;
      $f3 = $ground_level3;
      $g3 = $humidity3;

    //weather list
      $n3 = $weather_condition_id3;
      $o3 = $weather_condition_main3;
      $p3 = $weather_description3;
      $q3 = $weather_icon3;
    //clouds
      $r3 = $clouds3;
    //wind
      $s3 = $wind_speed3;
      $t3 = $wind_deg3;
      $h3 = $direction_wind3;
      $z3 = $wind_force3;
      $l3 = $sea_force3;
    //rain
      $u3 = $rain3;
    //snow
      $v3 = $snow3;
    //data/time of calculation
      $w3 = $data_time_pre3;
      $y3 = $data_time3;
    }
    else{
      $a3 = '';
      $b3 = '';
      $c3 = '';
      $d3 = '';
      $e3 = '';
      $f3 = '';
      $g3 = '';

      //temperatura in gradi c
        $i3 = '';
        $l3 = '';
        $m3 = '';
    //weather list
      $n3 = '';
      $o3 = '';
      $p3 = '';
      $q3 = '';
    //clouds
      $r3 = '';
    //wind
      $s3 = '';
      $t3 = '';
      $h3 = '';
      $z3 = '';
      $l3 = '';
    //rain
      $u3 = '';
    //snow
      $v3 = '';
    //data/time of calculation
      $w3 = '';
      $y3 = '';
    }

  //4 °
    if($data_time_confr4 == $data_time_confr8){
      $a4 = $temperatura4;
      $b4 = $temp_min4;
      $c4 = $temp_max4;
      $d4 = $pressure4;
      $e4 = $sea_level4;
      $f4 = $ground_level4;
      $g4 = $humidity4;

    //weather list
      $n4 = $weather_condition_id4;
      $o4 = $weather_condition_main4;
      $p4 = $weather_description4;
      $q4 = $weather_icon4;
    //clouds
      $r4 = $clouds4;
    //wind
      $s4 = $wind_speed4;
      $t4 = $wind_deg4;
      $h4 = $direction_wind4;
      $z4 = $wind_force4;
      $l4 = $sea_force4;
    //rain
      $u4 = $rain4;
    //snow
      $v4 = $snow4;
    //data/time of calculation
      $w4 = $data_time_pre4;
      $y4 = $data_time4;
    }
    else{
      $a4 = '';
      $b4 = '';
      $c4 = '';
      $d4 = '';
      $e4 = '';
      $f4 = '';
      $g4 = '';

      //temperatura in gradi c
        $i4 = '';
        $l4 = '';
        $m4 = '';
    //weather list
      $n4 = '';
      $o4 = '';
      $p4 = '';
      $q4 = '';
    //clouds
      $r4 = '';
    //wind
      $s4 = '';
      $t4 = '';
      $h4 = '';
      $z4 = '';
      $l4 = '';
    //rain
      $u4 = '';
    //snow
      $v4 = '';
    //data/time of calculation
      $w4 = '';
      $y4 = '';
    }

  //5 °
    if($data_time_confr5 == $data_time_confr8){
      $a5 = $temperatura5;
      $b5 = $temp_min5;
      $c5 = $temp_max5;
      $d5 = $pressure5;
      $e5 = $sea_level5;
      $f5 = $ground_level5;
      $g5 = $humidity5;

    //weather list
      $n5 = $weather_condition_id5;
      $o5 = $weather_condition_main5;
      $p5 = $weather_description5;
      $q5 = $weather_icon5;
    //clouds
      $r5 = $clouds5;
    //wind
      $s5 = $wind_speed5;
      $t5 = $wind_deg5;
      $h5 = $direction_wind5;
      $z5 = $wind_force5;
      $l5 = $sea_force5;
    //rain
      $u5 = $rain5;
    //snow
      $v5 = $snow5;
    //data/time of calculation
      $w5 = $data_time_pre5;
      $y5 = $data_time5;
    }
    else{
      $a5 = '';
      $b5 = '';
      $c5 = '';
      $d5 = '';
      $e5 = '';
      $f5 = '';
      $g5 = '';

      //temperatura in gradi c
        $i5 = '';
        $l5 = '';
        $m5 = '';
    //weather list
      $n5 = '';
      $o5 = '';
      $p5 = '';
      $q5 = '';
    //clouds
      $r5 = '';
    //wind
      $s5 = '';
      $t5 = '';
      $h5 = '';
      $z5 = '';
      $l5 = '';
    //rain
      $u5 = '';
    //snow
      $v5 = '';
    //data/time of calculation
      $w5 = '';
      $y5 = '';
    }

  //6 °
    if($data_time_confr6 == $data_time_confr8){
      $a6 = $temperatura6;
      $b6 = $temp_min6;
      $c6 = $temp_max6;
      $d6 = $pressure6;
      $e6 = $sea_level6;
      $f6 = $ground_level6;
      $g6 = $humidity6;

    //weather list
      $n6 = $weather_condition_id6;
      $o6 = $weather_condition_main6;
      $p6 = $weather_description6;
      $q6 = $weather_icon6;
    //clouds
      $r6 = $clouds6;
    //wind
      $s6 = $wind_speed6;
      $t6 = $wind_deg6;
      $h6 = $direction_wind6;
      $z6 = $wind_force6;
      $l6 = $sea_force6;
    //rain
      $u6 = $rain6;
    //snow
      $v6 = $snow6;
    //data/time of calculation
      $w6 = $data_time_pre6;
      $y6 = $data_time6;
    }
    else{
      $a6 = '';
      $b6 = '';
      $c6 = '';
      $d6 = '';
      $e6 = '';
      $f6 = '';
      $g6 = '';

      //temperatura in gradi c
        $i6 = '';
        $l6 = '';
        $m6 = '';
    //weather list
      $n6 = '';
      $o6 = '';
      $p6 = '';
      $q6 = '';
    //clouds
      $r6 = '';
    //wind
      $s6 = '';
      $t6 = '';
      $h6 = '';
      $z6 = '';
      $l6 = '';
    //rain
      $u6 = '';
    //snow
      $v6 = '';
    //data/time of calculation
      $w6 = '';
      $y6 = '';
    }

  //7 °
    if($data_time_confr7 == $data_time_confr8){
      $a7 = $temperatura7;
      $b7 = $temp_min7;
      $c7 = $temp_max7;
      $d7 = $pressure7;
      $e7 = $sea_level7;
      $f7 = $ground_level7;
      $g7 = $humidity7;

    //weather list
      $n7 = $weather_condition_id7;
      $o7 = $weather_condition_main7;
      $p7 = $weather_description7;
      $q7 = $weather_icon7;
    //clouds
      $r7 = $clouds7;
    //wind
      $s7 = $wind_speed7;
      $t7 = $wind_deg7;
      $h7 = $direction_wind7;
      $z7 = $wind_force7;
      $l7 = $sea_force7;
    //rain
      $u7 = $rain7;
    //snow
      $v7 = $snow7;
    //data/time of calculation
      $w7 = $data_time_pre7;
      $y7 = $data_time7;
    }
    else{
      $a7 = '';
      $b7 = '';
      $c7 = '';
      $d7 = '';
      $e7 = '';
      $f7 = '';
      $g7 = '';

      //temperatura in gradi c
        $i7 = '';
        $l7 = '';
        $m7 = '';
    //weather list
      $n7 = '';
      $o7 = '';
      $p7 = '';
      $q7 = '';
    //clouds
      $r7 = '';
    //wind
      $s7 = '';
      $t7 = '';
      $h7 = '';
      $z7 = '';
      $l7 = '';
    //rain
      $u7 = '';
    //snow
      $v7 = '';
    //data/time of calculation
      $w7 = '';
      $y7 = '';
    }

  //9 °
    if($data_time_confr9 == $data_time_confr8){
      $a9 = $temperatura9;
      $b9 = $temp_min9;
      $c9 = $temp_max9;
      $d9 = $pressure9;
      $e9 = $sea_level9;
      $f9 = $ground_level9;
      $g9 = $humidity9;

    //weather list
      $n9 = $weather_condition_id9;
      $o9 = $weather_condition_main9;
      $p9 = $weather_description9;
      $q9 = $weather_icon9;
    //clouds
      $r9 = $clouds9;
    //wind
      $s9 = $wind_speed9;
      $t9 = $wind_deg9;
      $h9 = $direction_wind9;
      $z9 = $wind_force9;
      $l9 = $sea_force9;
    //rain
      $u9 = $rain9;
    //snow
      $v9 = $snow9;
    //data/time of calculation
      $w9 = $data_time_pre9;
      $y9 = $data_time9;
    }
    else{
      $a9 = '';
      $b9 = '';
      $c9 = '';
      $d9 = '';
      $e9 = '';
      $f9 = '';
      $g9 = '';

      //temperatura in gradi c
        $i9 = '';
        $l9 = '';
        $m9 = '';
    //weather list
      $n9 = '';
      $o9 = '';
      $p9 = '';
      $q9 = '';
    //clouds
      $r9 = '';
    //wind
      $s9 = '';
      $t9 = '';
      $h9 = '';
      $z9 = '';
      $l9 = '';
    //rain
      $u9 = '';
    //snow
      $v9 = '';
    //data/time of calculation
      $w9 = '';
      $y9 = '';
    }

  //10 °
    if($data_time_confr10 == $data_time_confr8){
      $a10 = $temperatura10;
      $b10 = $temp_min10;
      $c10 = $temp_max10;
      $d10 = $pressure10;
      $e10 = $sea_level10;
      $f10 = $ground_level10;
      $g10 = $humidity10;

    //weather list
      $n10 = $weather_condition_id10;
      $o10 = $weather_condition_main10;
      $p10 = $weather_description10;
      $q10 = $weather_icon10;
    //clouds
      $r10 = $clouds10;
    //wind
      $s10 = $wind_speed10;
      $t10 = $wind_deg10;
      $h10 = $direction_wind10;
      $z10 = $wind_force10;
      $l10 = $sea_force10;
    //rain
      $u10 = $rain10;
    //snow
      $v10 = $snow10;
    //data/time of calculation
      $w10 = $data_time_pre10;
      $y10 = $data_time10;
    }
    else{
      $a10 = '';
      $b10 = '';
      $c10 = '';
      $d10 = '';
      $e10 = '';
      $f10 = '';
      $g10 = '';

      //temperatura in gradi c
        $i10 = '';
        $l10 = '';
        $m10 = '';
    //weather list
      $n10 = '';
      $o10 = '';
      $p10 = '';
      $q10 = '';
    //clouds
      $r10 = '';
    //wind
      $s10 = '';
      $t10 = '';
      $h10 = '';
      $z10 = '';
      $l10 = '';
    //rain
      $u10 = '';
    //snow
      $v10 = '';
    //data/time of calculation
      $w10 = '';
      $y10 = '';
    }

  //11 °
    if($data_time_confr11 == $data_time_confr8){
      $a11 = $temperatura11;
      $b11 = $temp_min11;
      $c11 = $temp_max11;
      $d11 = $pressure11;
      $e11 = $sea_level11;
      $f11 = $ground_level11;
      $g11 = $humidity11;

    //weather list
      $n11 = $weather_condition_id11;
      $o11 = $weather_condition_main11;
      $p11 = $weather_description11;
      $q11 = $weather_icon11;
    //clouds
      $r11 = $clouds11;
    //wind
      $s11 = $wind_speed11;
      $t11 = $wind_deg11;
      $h11 = $direction_wind11;
      $z11 = $wind_force11;
      $l11 = $sea_force11;
    //rain
      $u11 = $rain11;
    //snow
      $v11 = $snow11;
    //data/time of calculation
      $w11 = $data_time_pre11;
      $y11 = $data_time11;
    }
    else{
      $a11 = '';
      $b11 = '';
      $c11 = '';
      $d11 = '';
      $e11 = '';
      $f11 = '';
      $g11 = '';

      //temperatura in gradi c
        $i11 = '';
        $l11 = '';
        $m11 = '';
    //weather list
      $n11 = '';
      $o11 = '';
      $p11 = '';
      $q11 = '';
    //clouds
      $r11 = '';
    //wind
      $s11 = '';
      $t11 = '';
      $h11 = '';
      $z11 = '';
      $l11 = '';
    //rain
      $u11 = '';
    //snow
      $v11 = '';
    //data/time of calculation
      $w11 = '';
      $y11 = '';
    }

  //12 °
    if($data_time_confr12 == $data_time_confr8){
      $a12 = $temperatura12;
      $b12 = $temp_min12;
      $c12 = $temp_max12;
      $d12 = $pressure12;
      $e12 = $sea_level12;
      $f12 = $ground_level12;
      $g12 = $humidity12;

    //weather list
      $n12 = $weather_condition_id12;
      $o12 = $weather_condition_main12;
      $p12 = $weather_description12;
      $q12 = $weather_icon12;
    //clouds
      $r12 = $clouds12;
    //wind
      $s12 = $wind_speed12;
      $t12 = $wind_deg12;
      $h12 = $direction_wind12;
      $z12 = $wind_force12;
      $l12 = $sea_force12;
    //rain
      $u12 = $rain12;
    //snow
      $v12 = $snow12;
    //data/time of calculation
      $w12 = $data_time_pre12;
      $y12 = $data_time12;
    }
    else{
      $a12 = '';
      $b12 = '';
      $c12 = '';
      $d12 = '';
      $e12 = '';
      $f12 = '';
      $g12 = '';

      //temperatura in gradi c
        $i12 = '';
        $l12 = '';
        $m12 = '';
    //weather list
      $n12 = '';
      $o12 = '';
      $p12 = '';
      $q12 = '';
    //clouds
      $r12 = '';
    //wind
      $s12 = '';
      $t12 = '';
      $h12 = '';
      $z12 = '';
      $l12 = '';
    //rain
      $u12 = '';
    //snow
      $v12 = '';
    //data/time of calculation
      $w12 = '';
      $y12 = '';
    }

  //13 °
    if($data_time_confr13 == $data_time_confr8){
      $a13 = $temperatura13;
      $b13 = $temp_min13;
      $c13 = $temp_max13;
      $d13 = $pressure13;
      $e13 = $sea_level13;
      $f13 = $ground_level13;
      $g13 = $humidity13;

    //weather list
      $n13 = $weather_condition_id13;
      $o13 = $weather_condition_main13;
      $p13 = $weather_description13;
      $q13 = $weather_icon13;
    //clouds
      $r13 = $clouds13;
    //wind
      $s13 = $wind_speed13;
      $t13 = $wind_deg13;
      $h13 = $direction_wind13;
      $z13 = $wind_force13;
      $l13 = $sea_force13;
    //rain
      $u13 = $rain13;
    //snow
      $v13 = $snow13;
    //data/time of calculation
      $w13 = $data_time_pre13;
      $y13 = $data_time13;
    }
    else{
      $a13 = '';
      $b13 = '';
      $c13 = '';
      $d13 = '';
      $e13 = '';
      $f13 = '';
      $g13 = '';

      //temperatura in gradi c
        $i13 = '';
        $l13 = '';
        $m13 = '';
    //weather list
      $n13 = '';
      $o13 = '';
      $p13 = '';
      $q13 = '';
    //clouds
      $r13 = '';
    //wind
      $s13 = '';
      $t13 = '';
      $h13 = '';
      $z13 = '';
      $l13 = '';
    //rain
      $u13 = '';
    //snow
      $v13 = '';
    //data/time of calculation
      $w13 = '';
      $y13 = '';
    }

  //14 °
    if($data_time_confr14 == $data_time_confr8){
      $a14 = $temperatura14;
      $b14 = $temp_min14;
      $c14 = $temp_max14;
      $d14 = $pressure14;
      $e14 = $sea_level14;
      $f14 = $ground_level14;
      $g14 = $humidity14;

    //weather list
      $n14 = $weather_condition_id14;
      $o14 = $weather_condition_main14;
      $p14 = $weather_description14;
      $q14 = $weather_icon14;
    //clouds
      $r14 = $clouds14;
    //wind
      $s14 = $wind_speed14;
      $t14 = $wind_deg14;
      $h14 = $direction_wind14;
      $z14 = $wind_force14;
      $l14 = $sea_force14;
    //rain
      $u14 = $rain14;
    //snow
      $v14 = $snow14;
    //data/time of calculation
      $w14 = $data_time_pre14;
      $y14 = $data_time14;
    }
    else{
      $a14 = '';
      $b14 = '';
      $c14 = '';
      $d14 = '';
      $e14 = '';
      $f14 = '';
      $g14 = '';

      //temperatura in gradi c
        $i14 = '';
        $l14 = '';
        $m14 = '';
    //weather list
      $n14 = '';
      $o14 = '';
      $p14 = '';
      $q14 = '';
    //clouds
      $r14 = '';
    //wind
      $s14 = '';
      $t14 = '';
      $h14 = '';
      $z14 = '';
      $l14 = '';
    //rain
      $u14 = '';
    //snow
      $v14 = '';
    //data/time of calculation
      $w14 = '';
      $y14 = '';
    }

  //15 °
    if($data_time_confr15 == $data_time_confr8){
      $a15 = $temperatura15;
      $b15 = $temp_min15;
      $c15 = $temp_max15;
      $d15 = $pressure15;
      $e15 = $sea_level15;
      $f15 = $ground_level15;
      $g15 = $humidity15;

    //weather list
      $n15 = $weather_condition_id15;
      $o15 = $weather_condition_main15;
      $p15 = $weather_description15;
      $q15 = $weather_icon15;
    //clouds
      $r15 = $clouds15;
    //wind
      $s15 = $wind_speed15;
      $t15 = $wind_deg15;
      $h15 = $direction_wind15;
      $z15 = $wind_force15;
      $l15 = $sea_force15;
    //rain
      $u15 = $rain15;
    //snow
      $v15 = $snow15;
    //data/time of calculation
      $w15 = $data_time_pre15;
      $y15 = $data_time15;
    }
    else{
      $a15 = '';
      $b15 = '';
      $c15 = '';
      $d15 = '';
      $e15 = '';
      $f15 = '';
      $g15 = '';

      //temperatura in gradi c
        $i15 = '';
        $l15 = '';
        $m15 = '';
    //weather list
      $n15 = '';
      $o15 = '';
      $p15 = '';
      $q15 = '';
    //clouds
      $r15 = '';
    //wind
      $s15 = '';
      $t15 = '';
      $h15 = '';
      $z15 = '';
      $l15 = '';
    //rain
      $u15 = '';
    //snow
      $v15 = '';
    //data/time of calculation
      $w15 = '';
      $y15 = '';
    }

  //messaggio secondo giorno
  $bollettino_meteo_2 ="
<b>Day: $data_time_confr8</b>
<b>T</b>: $a1 $a2 $a3 $a4 $a5 $a6 $a7 $temperatura8 $a9 $a10 $a11 $a12 $a13 $a14 $a15
<b>Tmin</b>: $b1 $b2 $b3 $b4 $b5 $b6 $b7 $temp_min8 $b9 $b10 $b11 $b12 $b13 $b14 $b15
<b>Tmax</b>: $c1 $c2 $c3 $c4 $c5 $c6 $c7 $temp_max8 $c9 $c10 $c11 $c12 $c13 $c14 $c15
<b>Pressure</b>: $d1 $d2 $d3 $d4 $d5 $d6 $d7 $pressure8 $d9 $d10 $d11 $d12 $d13 $d14 $d15
<b>Sea Level</b>: $e1 $e2 $e3 $e4 $e5 $e6 $e7 $sea_level8 $e9 $e10 $e11 $e12 $e13 $e14 $e15
<b>Ground level</b>: $f1 $f2 $f3 $f4 $f5 $f6 $f7 $ground_level8 $f9 $f10 $f11 $f12 $f13 $f14 $f15
<b>Humidity</b>: $g1 $g2 $g3 $g4 $g5 $g6 $g7 $humidity8 $g9 $g10 $g11 $g12 $g13 $g14 $g15

<b>Weather</b>: $q1 $q2 $q3 $q4 $q5 $q6 $q7 $weather_icon8 $q9 $q10 $q11 $q12 $q13 $q14 $q15
<b>Weather Condiction</b>: $n1 $n2 $n3 $n4 $n5 $n6 $n7 $weather_condition_main8 $n9 $n10 $n11 $n12 $n13 $n14 $n15
<b>Weather Descriptione</b>: $o1 $o2 $o3 $o4 $o5 $o6 $o7 $weather_description8 $o9 $o10 $o11 $o12 $o13 $o14 $o15

<b>Clouds</b>: $r1 $r2 $r3 $r4 $r5 $r6 $r7 $clouds8 $r9 $r10 $r11 $r12 $r13 $r14 $r15

<b>Wind Speed</b>: $s1 $s2 $s3 $s4 $s5 $s6 $s7 $wind_speed8 $s9 $s10 $s11 $s12 $s13 $s14 $s15
<b>Wind Deg</b>: $t1 $t2 $t3 $t4 $t5 $t6 $t7 $wind_deg8 $t9 $t10 $t11 $t12 $t13 $t14 $t15
<b>Wind Direction</b>: $h1 $h2 $h3 $h4 $h5 $h6 $h7 $direction_wind8 $h9 $h10 $h11 $h12 $h13 $h14 $h15
<b>Wind Force</b>: $z1 $z2 $z3 $z4 $z5 $z6 $z7 $wind_force8 $z9 $z10 $z11 $z12 $z13 $z14 $z15
<b>Sea Force</b>: $l1 $l2 $l3 $l4 $l5 $l6 $l7 $sea_force8 $l9 $l10 $l11 $l12 $l13 $l14 $l15

<b>Rain</b>: $u1 $u2 $u3 $u4 $u5 $u6 $u7 $rain8 $u9 $u10 $u11 $u12 $u13 $u14 $u15

<b>Snow</b>: $v1 $v2 $v3 $v4 $v5 $v6 $v7 $snow8 $v9 $v10 $v11 $v12 $v13 $v14 $v15

<b>Time</b>: $y1 $y2 $y3 $y4 $y5 $y6 $y7 $data_time8 $y9 $y10 $y11 $y12 $y13 $y14 $y15
  ";
  sendMessage($chat_id, $bollettino_meteo_2);

/*
   _   _     _         _
 | | | |   (_)       | |
| |_| |__  _ _ __ __| |  _ __ ___   ___  ___  __ _  __ _  ___
| __| '_ \| | '__/ _` | | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
| |_| | | | | | | (_| | | | | | | |  __/\__ \ (_| | (_| |  __/
\__|_| |_|_|_|  \__,_| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                   __/ |
                                                  |___/
*/
  //9 °
    if($data_time_confr9 == $data_time_confr16){
      $a9 = $temperatura9;
      $b9 = $temp_min9;
      $c9 = $temp_max9;
      $d9 = $pressure9;
      $e9 = $sea_level9;
      $f9 = $ground_level9;
      $g9 = $humidity9;

    //weather list
      $n9 = $weather_condition_id9;
      $o9 = $weather_condition_main9;
      $p9 = $weather_description9;
      $q9 = $weather_icon9;
    //clouds
      $r9 = $clouds9;
    //wind
      $s9 = $wind_speed9;
      $t9 = $wind_deg9;
      $h9 = $direction_wind9;
      $z9 = $wind_force9;
      $l9 = $sea_force9;
    //rain
      $u9 = $rain9;
    //snow
      $v9 = $snow9;
    //data/time of calculation
      $w9 = $data_time_pre9;
      $y9 = $data_time9;
    }
    else{
      $a9 = '';
      $b9 = '';
      $c9 = '';
      $d9 = '';
      $e9 = '';
      $f9 = '';
      $g9 = '';

      //temperatura in gradi c
        $i9 = '';
        $l9 = '';
        $m9 = '';
    //weather list
      $n9 = '';
      $o9 = '';
      $p9 = '';
      $q9 = '';
    //clouds
      $r9 = '';
    //wind
      $s9 = '';
      $t9 = '';
      $h9 = '';
      $z9 = '';
      $l9 = '';
    //rain
      $u9 = '';
    //snow
      $v9 = '';
    //data/time of calculation
      $w9 = '';
      $y9 = '';
    }

  //10 °
    if($data_time_confr10 == $data_time_confr16){
      $a10 = $temperatura10;
      $b10 = $temp_min10;
      $c10 = $temp_max10;
      $d10 = $pressure10;
      $e10 = $sea_level10;
      $f10 = $ground_level10;
      $g10 = $humidity10;

    //weather list
      $n10 = $weather_condition_id10;
      $o10 = $weather_condition_main10;
      $p10 = $weather_description10;
      $q10 = $weather_icon10;
    //clouds
      $r10 = $clouds10;
    //wind
      $s10 = $wind_speed10;
      $t10 = $wind_deg10;
      $h10 = $direction_wind10;
      $z10 = $wind_force10;
      $l10 = $sea_force10;
    //rain
      $u10 = $rain10;
    //snow
      $v10 = $snow10;
    //data/time of calculation
      $w10 = $data_time_pre10;
      $y10 = $data_time10;
    }
    else{
      $a10 = '';
      $b10 = '';
      $c10 = '';
      $d10 = '';
      $e10 = '';
      $f10 = '';
      $g10 = '';

      //temperatura in gradi c
        $i10 = '';
        $l10 = '';
        $m10 = '';
    //weather list
      $n10 = '';
      $o10 = '';
      $p10 = '';
      $q10 = '';
    //clouds
      $r10 = '';
    //wind
      $s10 = '';
      $t10 = '';
      $h10 = '';
      $z10 = '';
      $l10 = '';
    //rain
      $u10 = '';
    //snow
      $v10 = '';
    //data/time of calculation
      $w10 = '';
      $y10 = '';
    }

  //11 °
    if($data_time_confr11 == $data_time_confr16){
      $a11 = $temperatura11;
      $b11 = $temp_min11;
      $c11 = $temp_max11;
      $d11 = $pressure11;
      $e11 = $sea_level11;
      $f11 = $ground_level11;
      $g11 = $humidity11;

    //weather list
      $n11 = $weather_condition_id11;
      $o11 = $weather_condition_main11;
      $p11 = $weather_description11;
      $q11 = $weather_icon11;
    //clouds
      $r11 = $clouds11;
    //wind
      $s11 = $wind_speed11;
      $t11 = $wind_deg11;
      $h11 = $direction_wind11;
      $z11 = $wind_force11;
      $l11 = $sea_force11;
    //rain
      $u11 = $rain11;
    //snow
      $v11 = $snow11;
    //data/time of calculation
      $w11 = $data_time_pre11;
      $y11 = $data_time11;
    }
    else{
      $a11 = '';
      $b11 = '';
      $c11 = '';
      $d11 = '';
      $e11 = '';
      $f11 = '';
      $g11 = '';

      //temperatura in gradi c
        $i11 = '';
        $l11 = '';
        $m11 = '';
    //weather list
      $n11 = '';
      $o11 = '';
      $p11 = '';
      $q11 = '';
    //clouds
      $r11 = '';
    //wind
      $s11 = '';
      $t11 = '';
      $h11 = '';
      $z11 = '';
      $l11 = '';
    //rain
      $u11 = '';
    //snow
      $v11 = '';
    //data/time of calculation
      $w11 = '';
      $y11 = '';
    }

  //12 °
    if($data_time_confr12 == $data_time_confr16){
      $a12 = $temperatura12;
      $b12 = $temp_min12;
      $c12 = $temp_max12;
      $d12 = $pressure12;
      $e12 = $sea_level12;
      $f12 = $ground_level12;
      $g12 = $humidity12;

    //weather list
      $n12 = $weather_condition_id12;
      $o12 = $weather_condition_main12;
      $p12 = $weather_description12;
      $q12 = $weather_icon12;
    //clouds
      $r12 = $clouds12;
    //wind
      $s12 = $wind_speed12;
      $t12 = $wind_deg12;
      $h12 = $direction_wind12;
      $z12 = $wind_force12;
      $l12 = $sea_force12;
    //rain
      $u12 = $rain12;
    //snow
      $v12 = $snow12;
    //data/time of calculation
      $w12 = $data_time_pre12;
      $y12 = $data_time12;
    }
    else{
      $a12 = '';
      $b12 = '';
      $c12 = '';
      $d12 = '';
      $e12 = '';
      $f12 = '';
      $g12 = '';

      //temperatura in gradi c
        $i12 = '';
        $l12 = '';
        $m12 = '';
    //weather list
      $n12 = '';
      $o12 = '';
      $p12 = '';
      $q12 = '';
    //clouds
      $r12 = '';
    //wind
      $s12 = '';
      $t12 = '';
      $h12 = '';
      $z12 = '';
      $l12 = '';
    //rain
      $u12 = '';
    //snow
      $v12 = '';
    //data/time of calculation
      $w12 = '';
      $y12 = '';
    }

  //13 °
    if($data_time_confr13 == $data_time_confr16){
      $a13 = $temperatura13;
      $b13 = $temp_min13;
      $c13 = $temp_max13;
      $d13 = $pressure13;
      $e13 = $sea_level13;
      $f13 = $ground_level13;
      $g13 = $humidity13;

    //weather list
      $n13 = $weather_condition_id13;
      $o13 = $weather_condition_main13;
      $p13 = $weather_description13;
      $q13 = $weather_icon13;
    //clouds
      $r13 = $clouds13;
    //wind
      $s13 = $wind_speed13;
      $t13 = $wind_deg13;
      $h13 = $direction_wind13;
      $z13 = $wind_force13;
      $l13 = $sea_force13;
    //rain
      $u13 = $rain13;
    //snow
      $v13 = $snow13;
    //data/time of calculation
      $w13 = $data_time_pre13;
      $y13 = $data_time13;
    }
    else{
      $a13 = '';
      $b13 = '';
      $c13 = '';
      $d13 = '';
      $e13 = '';
      $f13 = '';
      $g13 = '';

      //temperatura in gradi c
        $i13 = '';
        $l13 = '';
        $m13 = '';
    //weather list
      $n13 = '';
      $o13 = '';
      $p13 = '';
      $q13 = '';
    //clouds
      $r13 = '';
    //wind
      $s13 = '';
      $t13 = '';
      $h13 = '';
      $z13 = '';
      $l13 = '';
    //rain
      $u13 = '';
    //snow
      $v13 = '';
    //data/time of calculation
      $w13 = '';
      $y13 = '';
    }

  //14 °
    if($data_time_confr14 == $data_time_confr16){
      $a14 = $temperatura14;
      $b14 = $temp_min14;
      $c14 = $temp_max14;
      $d14 = $pressure14;
      $e14 = $sea_level14;
      $f14 = $ground_level14;
      $g14 = $humidity14;

    //weather list
      $n14 = $weather_condition_id14;
      $o14 = $weather_condition_main14;
      $p14 = $weather_description14;
      $q14 = $weather_icon14;
    //clouds
      $r14 = $clouds14;
    //wind
      $s14 = $wind_speed14;
      $t14 = $wind_deg14;
      $h14 = $direction_wind14;
      $z14 = $wind_force14;
      $l14 = $sea_force14;
    //rain
      $u14 = $rain14;
    //snow
      $v14 = $snow14;
    //data/time of calculation
      $w14 = $data_time_pre14;
      $y14 = $data_time14;
    }
    else{
      $a14 = '';
      $b14 = '';
      $c14 = '';
      $d14 = '';
      $e14 = '';
      $f14 = '';
      $g14 = '';

      //temperatura in gradi c
        $i14 = '';
        $l14 = '';
        $m14 = '';
    //weather list
      $n14 = '';
      $o14 = '';
      $p14 = '';
      $q14 = '';
    //clouds
      $r14 = '';
    //wind
      $s14 = '';
      $t14 = '';
      $h14 = '';
      $z14 = '';
      $l14 = '';
    //rain
      $u14 = '';
    //snow
      $v14 = '';
    //data/time of calculation
      $w14 = '';
      $y14 = '';
    }

  //15 °
    if($data_time_confr15 == $data_time_confr16){
      $a15 = $temperatura15;
      $b15 = $temp_min15;
      $c15 = $temp_max15;
      $d15 = $pressure15;
      $e15 = $sea_level15;
      $f15 = $ground_level15;
      $g15 = $humidity15;

    //weather list
      $n15 = $weather_condition_id15;
      $o15 = $weather_condition_main15;
      $p15 = $weather_description15;
      $q15 = $weather_icon15;
    //clouds
      $r15 = $clouds15;
    //wind
      $s15 = $wind_speed15;
      $t15 = $wind_deg15;
      $h15 = $direction_wind15;
      $z15 = $wind_force15;
      $l15 = $sea_force15;
    //rain
      $u15 = $rain15;
    //snow
      $v15 = $snow15;
    //data/time of calculation
      $w15 = $data_time_pre15;
      $y15 = $data_time15;
    }
    else{
      $a15 = '';
      $b15 = '';
      $c15 = '';
      $d15 = '';
      $e15 = '';
      $f15 = '';
      $g15 = '';

      //temperatura in gradi c
        $i15 = '';
        $l15 = '';
        $m15 = '';
    //weather list
      $n15 = '';
      $o15 = '';
      $p15 = '';
      $q15 = '';
    //clouds
      $r15 = '';
    //wind
      $s15 = '';
      $t15 = '';
      $h15 = '';
      $z15 = '';
      $l15 = '';
    //rain
      $u15 = '';
    //snow
      $v15 = '';
    //data/time of calculation
      $w15 = '';
      $y15 = '';
    }

  //17 °
    if($data_time_confr17 == $data_time_confr16){
      $a17 = $temperatura17;
      $b17 = $temp_min17;
      $c17 = $temp_max17;
      $d17 = $pressure17;
      $e17 = $sea_level17;
      $f17 = $ground_level17;
      $g17 = $humidity17;

    //weather list
      $n17 = $weather_condition_id17;
      $o17 = $weather_condition_main17;
      $p17 = $weather_description17;
      $q17 = $weather_icon17;
    //clouds
      $r17 = $clouds17;
    //wind
      $s17 = $wind_speed17;
      $t17 = $wind_deg17;
      $h17 = $direction_wind17;
      $z17 = $wind_force17;
      $l17 = $sea_force17;
    //rain
      $u17 = $rain17;
    //snow
      $v17 = $snow17;
    //data/time of calculation
      $w17 = $data_time_pre17;
      $y17 = $data_time17;
    }
    else{
      $a17 = '';
      $b17 = '';
      $c17 = '';
      $d17 = '';
      $e17 = '';
      $f17 = '';
      $g17 = '';

      //temperatura in gradi c
        $i17 = '';
        $l17 = '';
        $m17 = '';
    //weather list
      $n17 = '';
      $o17 = '';
      $p17 = '';
      $q17 = '';
    //clouds
      $r17 = '';
    //wind
      $s17 = '';
      $t17 = '';
      $h17 = '';
      $z17 = '';
      $l17 = '';
    //rain
      $u17 = '';
    //snow
      $v17 = '';
    //data/time of calculation
      $w17 = '';
      $y17 = '';
    }

  //18 °
    if($data_time_confr18 == $data_time_confr16){
      $a18 = $temperatura18;
      $b18 = $temp_min18;
      $c18 = $temp_max18;
      $d18 = $pressure18;
      $e18 = $sea_level18;
      $f18 = $ground_level18;
      $g18 = $humidity18;

    //weather list
      $n18 = $weather_condition_id18;
      $o18 = $weather_condition_main18;
      $p18 = $weather_description18;
      $q18 = $weather_icon18;
    //clouds
      $r18 = $clouds18;
    //wind
      $s18 = $wind_speed18;
      $t18 = $wind_deg18;
      $h18 = $direction_wind18;
      $z18 = $wind_force18;
      $l18 = $sea_force18;
    //rain
      $u18 = $rain18;
    //snow
      $v18 = $snow18;
    //data/time of calculation
      $w18 = $data_time_pre18;
      $y18 = $data_time18;
    }
    else{
      $a18 = '';
      $b18 = '';
      $c18 = '';
      $d18 = '';
      $e18 = '';
      $f18 = '';
      $g18 = '';

      //temperatura in gradi c
        $i18 = '';
        $l18 = '';
        $m18 = '';
    //weather list
      $n18 = '';
      $o18 = '';
      $p18 = '';
      $q18 = '';
    //clouds
      $r18 = '';
    //wind
      $s18 = '';
      $t18 = '';
      $h18 = '';
      $z18 = '';
      $l18 = '';
    //rain
      $u18 = '';
    //snow
      $v18 = '';
    //data/time of calculation
      $w18 = '';
      $y18 = '';
    }

  //19 °
    if($data_time_confr19 == $data_time_confr16){
      $a19 = $temperatura19;
      $b19 = $temp_min19;
      $c19 = $temp_max19;
      $d19 = $pressure19;
      $e19 = $sea_level19;
      $f19 = $ground_level19;
      $g19 = $humidity19;

    //weather list
      $n19 = $weather_condition_id19;
      $o19 = $weather_condition_main19;
      $p19 = $weather_description19;
      $q19 = $weather_icon19;
    //clouds
      $r19 = $clouds19;
    //wind
      $s19 = $wind_speed19;
      $t19 = $wind_deg19;
      $h19 = $direction_wind19;
      $z19 = $wind_force19;
      $l19 = $sea_force19;
    //rain
      $u19 = $rain19;
    //snow
      $v19 = $snow19;
    //data/time of calculation
      $w19 = $data_time_pre19;
      $y19 = $data_time19;
    }
    else{
      $a19 = '';
      $b19 = '';
      $c19 = '';
      $d19 = '';
      $e19 = '';
      $f19 = '';
      $g19 = '';

      //temperatura in gradi c
        $i19 = '';
        $l19 = '';
        $m19 = '';
    //weather list
      $n19 = '';
      $o19 = '';
      $p19 = '';
      $q19 = '';
    //clouds
      $r19 = '';
    //wind
      $s19 = '';
      $t19 = '';
      $h19 = '';
      $z19 = '';
      $l19 = '';
    //rain
      $u19 = '';
    //snow
      $v19 = '';
    //data/time of calculation
      $w19 = '';
      $y19 = '';
    }

  //20 °
    if($data_time_confr20 == $data_time_confr16){
      $a20 = $temperatura20;
      $b20 = $temp_min20;
      $c20 = $temp_max20;
      $d20 = $pressure20;
      $e20 = $sea_level20;
      $f20 = $ground_level20;
      $g20 = $humidity20;

    //weather list
      $n20 = $weather_condition_id20;
      $o20 = $weather_condition_main20;
      $p20 = $weather_description20;
      $q20 = $weather_icon20;
    //clouds
      $r20 = $clouds20;
    //wind
      $s20 = $wind_speed20;
      $t20 = $wind_deg20;
      $h20 = $direction_wind20;
      $z20 = $wind_force20;
      $l20 = $sea_force20;
    //rain
      $u20 = $rain20;
    //snow
      $v20 = $snow20;
    //data/time of calculation
      $w20 = $data_time_pre20;
      $y20 = $data_time20;
    }
    else{
      $a20 = '';
      $b20 = '';
      $c20 = '';
      $d20 = '';
      $e20 = '';
      $f20 = '';
      $g20 = '';

      //temperatura in gradi c
        $i20 = '';
        $l20 = '';
        $m20 = '';
    //weather list
      $n20 = '';
      $o20 = '';
      $p20 = '';
      $q20 = '';
    //clouds
      $r20 = '';
    //wind
      $s20 = '';
      $t20 = '';
      $h20 = '';
      $z20 = '';
      $l20 = '';
    //rain
      $u20 = '';
    //snow
      $v20 = '';
    //data/time of calculation
      $w20 = '';
      $y20 = '';
    }

  //21 °
    if($data_time_confr21 == $data_time_confr16){
      $a21 = $temperatura21;
      $b21 = $temp_min21;
      $c21 = $temp_max21;
      $d21 = $pressure21;
      $e21 = $sea_level21;
      $f21 = $ground_level21;
      $g21 = $humidity21;

    //weather list
      $n21 = $weather_condition_id21;
      $o21 = $weather_condition_main21;
      $p21 = $weather_description21;
      $q21 = $weather_icon21;
    //clouds
      $r21 = $clouds21;
    //wind
      $s21 = $wind_speed21;
      $t21 = $wind_deg21;
      $h21 = $direction_wind21;
      $z21 = $wind_force21;
      $l21 = $sea_force21;
    //rain
      $u21 = $rain21;
    //snow
      $v21 = $snow21;
    //data/time of calculation
      $w21 = $data_time_pre21;
      $y21 = $data_time21;
    }
    else{
      $a21 = '';
      $b21 = '';
      $c21 = '';
      $d21 = '';
      $e21 = '';
      $f21 = '';
      $g21 = '';

      //temperatura in gradi c
        $i21 = '';
        $l21 = '';
        $m21 = '';
    //weather list
      $n21 = '';
      $o21 = '';
      $p21 = '';
      $q21 = '';
    //clouds
      $r21 = '';
    //wind
      $s21 = '';
      $t21 = '';
      $h21 = '';
      $z21 = '';
      $l21 = '';
    //rain
      $u21 = '';
    //snow
      $v21 = '';
    //data/time of calculation
      $w21 = '';
      $y21 = '';
    }

  //22 °
    if($data_time_confr22 == $data_time_confr16){
      $a22 = $temperatura22;
      $b22 = $temp_min22;
      $c22 = $temp_max22;
      $d22 = $pressure22;
      $e22 = $sea_level22;
      $f22 = $ground_level22;
      $g22 = $humidity22;

    //weather list
      $n22 = $weather_condition_id22;
      $o22 = $weather_condition_main22;
      $p22 = $weather_description22;
      $q22 = $weather_icon22;
    //clouds
      $r22 = $clouds22;
    //wind
      $s22 = $wind_speed22;
      $t22 = $wind_deg22;
      $h22 = $direction_wind22;
      $z22 = $wind_force22;
      $l22 = $sea_force22;
    //rain
      $u22 = $rain22;
    //snow
      $v22 = $snow22;
    //data/time of calculation
      $w22 = $data_time_pre22;
      $y22 = $data_time22;
    }
    else{
      $a22 = '';
      $b22 = '';
      $c22 = '';
      $d22 = '';
      $e22 = '';
      $f22 = '';
      $g22 = '';

      //temperatura in gradi c
        $i22 = '';
        $l22 = '';
        $m22 = '';
    //weather list
      $n22 = '';
      $o22 = '';
      $p22 = '';
      $q22 = '';
    //clouds
      $r22 = '';
    //wind
      $s22 = '';
      $t22 = '';
      $h22 = '';
      $z22 = '';
      $l22 = '';
    //rain
      $u22 = '';
    //snow
      $v22 = '';
    //data/time of calculation
      $w22 = '';
      $y22 = '';
    }

  //23 °
    if($data_time_confr23 == $data_time_confr16){
      $a23 = $temperatura23;
      $b23 = $temp_min23;
      $c23 = $temp_max23;
      $d23 = $pressure23;
      $e23 = $sea_level23;
      $f23 = $ground_level23;
      $g23 = $humidity23;

    //weather list
      $n23 = $weather_condition_id23;
      $o23 = $weather_condition_main23;
      $p23 = $weather_description23;
      $q23 = $weather_icon23;
    //clouds
      $r23 = $clouds23;
    //wind
      $s23 = $wind_speed23;
      $t23 = $wind_deg23;
      $h23 = $direction_wind23;
      $z23 = $wind_force23;
      $l23 = $sea_force23;
    //rain
      $u23 = $rain23;
    //snow
      $v23 = $snow23;
    //data/time of calculation
      $w23 = $data_time_pre23;
      $y23 = $data_time23;
    }
    else{
      $a23 = '';
      $b23 = '';
      $c23 = '';
      $d23 = '';
      $e23 = '';
      $f23 = '';
      $g23 = '';

      //temperatura in gradi c
        $i23 = '';
        $l23 = '';
        $m23 = '';
    //weather list
      $n23 = '';
      $o23 = '';
      $p23 = '';
      $q23 = '';
    //clouds
      $r23 = '';
    //wind
      $s23 = '';
      $t23 = '';
      $h23 = '';
      $z23 = '';
      $l23 = '';
    //rain
      $u23 = '';
    //snow
      $v23 = '';
    //data/time of calculation
      $w23 = '';
      $y23 = '';
    }

  //messaggio secondo giorno
  $bollettino_meteo_3 ="
  <b>Day: $data_time_confr16</b>
  <b>T</b>: $a9 $a10 $a11 $a12 $a13 $a14 $a15 $temperatura16 $a17 $a18 $a19 $a20 $a21 $a22 $a23
  <b>Tmin</b>: $b9 $b10 $b11 $b12 $b13 $b14 $b15 $temp_min16 $b17 $b18 $b19 $b20 $b21 $b22 $b23
  <b>Tmax</b>: $c9 $c10 $c11 $c12 $c13 $c14 $c15 $temp_max16 $c17 $c18 $c19 $c20 $c21 $c22 $c23
  <b>Pressure</b>: $d9 $d10 $d11 $d12 $d13 $d14 $d15 $pressure16 $d17 $d18 $d19 $d20 $d21 $d22 $d23
  <b>Sea Level</b>: $e9 $e10 $e11 $e12 $e13 $e14 $e15 $sea_level16 $e17 $e18 $e19 $e20 $e21 $e22 $e23
  <b>Ground level</b>: $f9 $f10 $f11 $f12 $f13 $f14 $f15 $ground_level16 $f17 $f18 $f19 $f20 $f21 $f22 $f23
  <b>Humidity</b>: $g9 $g10 $g11 $g12 $g13 $g14 $g15 $humidity16 $g17 $g18 $g19 $g20 $g21 $g22 $g23

  <b>Weather</b>: $q9 $q10 $q11 $q12 $q13 $q14 $q15 $weather_icon16 $q17 $q18 $q19 $q20 $q21 $q22 $q23
  <b>Weather Condiction</b>: $n9 $n10 $n11 $n12 $n13 $n14 $n15 $weather_condition_main16 $n17 $n18 $n19 $n20 $n21 $n22 $n23
  <b>Weather Descriptione</b>: $o9 $o10 $o11 $o12 $o13 $o14 $o15 $weather_description16 $o17 $o18 $o19 $o20 $o21 $o22 $o23

  <b>Clouds</b>: $r9 $r10 $r11 $r12 $r13 $r14 $r15 $clouds16 $r17 $r18 $r19 $r20 $r21 $r22 $r23

  <b>Wind Speed</b>: $s9 $s10 $s11 $s12 $s13 $s14 $s15 $wind_speed16 $s17 $s18 $s19 $s20 $s21 $s22 $s23
  <b>Wind Direction</b>: $t9 $t10 $t11 $t12 $t13 $t14 $t15 $wind_deg16 $t17 $t18 $t19 $t20 $t21 $t22 $t23
//da completare
  <b>Wind Speed</b>: $s1 $s2 $s3 $s4 $s5 $s6 $s7 $wind_speed8 $s9 $s10 $s11 $s12 $s13 $s14 $s15
  <b>Wind Deg</b>: $t1 $t2 $t3 $t4 $t5 $t6 $t7 $wind_deg8 $t9 $t10 $t11 $t12 $t13 $t14 $t15
  <b>Wind Direction</b>: $h1 $h2 $h3 $h4 $h5 $h6 $h7 $direction_wind8 $h9 $h10 $h11 $h12 $h13 $h14 $h15
  <b>Wind Force</b>: $z1 $z2 $z3 $z4 $z5 $z6 $z7 $wind_force8 $z9 $z10 $z11 $z12 $z13 $z14 $z15
  <b>Sea Force</b>: $l1 $l2 $l3 $l4 $l5 $l6 $l7 $sea_force8 $l9 $l10 $l11 $l12 $l13 $l14 $l15

  <b>Rain</b>: $u9 $u10 $u11 $u12 $u13 $u14 $u15 $rain16 $u17 $u18 $u19 $u20 $u21 $u22 $u23

  <b>Snow</b>:  $v9 $v10 $v11 $v12 $v13 $v14 $v15 $snow16 $v17 $v18 $v19 $v20 $v21 $v22 $v23

  <b>Time</b>: $y9 $y10 $y11 $y12 $y13 $y14 $y15 $data_time16 $y17 $y18 $y19 $y20 $y21 $y22 $y23
  ";
  sendMessage($chat_id, $bollettino_meteo_3);

/*

  __                 _   _
 / _|               | | | |
| |_ ___  _   _ _ __| |_| |__    _ __ ___   ___  ___  __ _  __ _  ___
|  _/ _ \| | | | '__| __| '_ \  | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
| || (_) | |_| | |  | |_| | | | | | | | | |  __/\__ \ (_| | (_| |  __/
|_| \___/ \__,_|_|   \__|_| |_| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                            __/ |
                                                           |___/

*/


//17 °
  if($data_time_confr17 == $data_time_confr24){
    $a17 = $temperatura17;
    $b17 = $temp_min17;
    $c17 = $temp_max17;
    $d17 = $pressure17;
    $e17 = $sea_level17;
    $f17 = $ground_level17;
    $g17 = $humidity17;

  //weather list
    $n17 = $weather_condition_id17;
    $o17 = $weather_condition_main17;
    $p17 = $weather_description17;
    $q17 = $weather_icon17;
  //clouds
    $r17 = $clouds17;
  //wind
    $s17 = $wind_speed17;
    $t17 = $wind_deg17;
    $h17 = $direction_wind17;
    $z17 = $wind_force17;
    $l17 = $sea_force17;
  //rain
    $u17 = $rain17;
  //snow
    $v17 = $snow17;
  //data/time of calculation
    $w17 = $data_time_pre17;
    $y17 = $data_time17;
  }
  else{
    $a17 = '';
    $b17 = '';
    $c17 = '';
    $d17 = '';
    $e17 = '';
    $f17 = '';
    $g17 = '';

    //temperatura in gradi c
      $i17 = '';
      $l17 = '';
      $m17 = '';
  //weather list
    $n17 = '';
    $o17 = '';
    $p17 = '';
    $q17 = '';
  //clouds
    $r17 = '';
  //wind
    $s17 = '';
    $t17 = '';
    $h17 = '';
    $z17 = '';
    $l17 = '';
  //rain
    $u17 = '';
  //snow
    $v17 = '';
  //data/time of calculation
    $w17 = '';
    $y17 = '';
  }

//18 °
  if($data_time_confr18 == $data_time_confr24){
    $a18 = $temperatura18;
    $b18 = $temp_min18;
    $c18 = $temp_max18;
    $d18 = $pressure18;
    $e18 = $sea_level18;
    $f18 = $ground_level18;
    $g18 = $humidity18;

  //weather list
    $n18 = $weather_condition_id18;
    $o18 = $weather_condition_main18;
    $p18 = $weather_description18;
    $q18 = $weather_icon18;
  //clouds
    $r18 = $clouds18;
  //wind
    $s18 = $wind_speed18;
    $t18 = $wind_deg18;
    $h18 = $direction_wind18;
    $z18 = $wind_force18;
    $l18 = $sea_force18;
  //rain
    $u18 = $rain18;
  //snow
    $v18 = $snow18;
  //data/time of calculation
    $w18 = $data_time_pre18;
    $y18 = $data_time18;
  }
  else{
    $a18 = '';
    $b18 = '';
    $c18 = '';
    $d18 = '';
    $e18 = '';
    $f18 = '';
    $g18 = '';

    //temperatura in gradi c
      $i18 = '';
      $l18 = '';
      $m18 = '';
  //weather list
    $n18 = '';
    $o18 = '';
    $p18 = '';
    $q18 = '';
  //clouds
    $r18 = '';
  //wind
    $s18 = '';
    $t18 = '';
    $h18 = '';
    $z18 = '';
    $l18 = '';
  //rain
    $u18 = '';
  //snow
    $v18 = '';
  //data/time of calculation
    $w18 = '';
    $y18 = '';
  }

//19 °
  if($data_time_confr19 == $data_time_confr24){
    $a19 = $temperatura19;
    $b19 = $temp_min19;
    $c19 = $temp_max19;
    $d19 = $pressure19;
    $e19 = $sea_level19;
    $f19 = $ground_level19;
    $g19 = $humidity19;

  //weather list
    $n19 = $weather_condition_id19;
    $o19 = $weather_condition_main19;
    $p19 = $weather_description19;
    $q19 = $weather_icon19;
  //clouds
    $r19 = $clouds19;
  //wind
    $s19 = $wind_speed19;
    $t19 = $wind_deg19;
    $h19 = $direction_wind19;
    $z19 = $wind_force19;
    $l19 = $sea_force19;
  //rain
    $u19 = $rain19;
  //snow
    $v19 = $snow19;
  //data/time of calculation
    $w19 = $data_time_pre19;
    $y19 = $data_time19;
  }
  else{
    $a19 = '';
    $b19 = '';
    $c19 = '';
    $d19 = '';
    $e19 = '';
    $f19 = '';
    $g19 = '';

    //temperatura in gradi c
      $i19 = '';
      $l19 = '';
      $m19 = '';
  //weather list
    $n19 = '';
    $o19 = '';
    $p19 = '';
    $q19 = '';
  //clouds
    $r19 = '';
  //wind
    $s19 = '';
    $t19 = '';
    $h19 = '';
    $z19 = '';
    $l19 = '';
  //rain
    $u19 = '';
  //snow
    $v19 = '';
  //data/time of calculation
    $w19 = '';
    $y19 = '';
  }

//20 °
  if($data_time_confr20 == $data_time_confr24){
    $a20 = $temperatura20;
    $b20 = $temp_min20;
    $c20 = $temp_max20;
    $d20 = $pressure20;
    $e20 = $sea_level20;
    $f20 = $ground_level20;
    $g20 = $humidity20;

  //weather list
    $n20 = $weather_condition_id20;
    $o20 = $weather_condition_main20;
    $p20 = $weather_description20;
    $q20 = $weather_icon20;
  //clouds
    $r20 = $clouds20;
  //wind
    $s20 = $wind_speed20;
    $t20 = $wind_deg20;
    $h20 = $direction_wind20;
    $z20 = $wind_force20;
    $l20 = $sea_force20;
  //rain
    $u20 = $rain20;
  //snow
    $v20 = $snow20;
  //data/time of calculation
    $w20 = $data_time_pre20;
    $y20 = $data_time20;
  }
  else{
    $a20 = '';
    $b20 = '';
    $c20 = '';
    $d20 = '';
    $e20 = '';
    $f20 = '';
    $g20 = '';

    //temperatura in gradi c
      $i20 = '';
      $l20 = '';
      $m20 = '';
  //weather list
    $n20 = '';
    $o20 = '';
    $p20 = '';
    $q20 = '';
  //clouds
    $r20 = '';
  //wind
    $s20 = '';
    $t20 = '';
    $h20 = '';
    $z20 = '';
    $l20 = '';
  //rain
    $u20 = '';
  //snow
    $v20 = '';
  //data/time of calculation
    $w20 = '';
    $y20 = '';
  }

//21 °
  if($data_time_confr21 == $data_time_confr24){
    $a21 = $temperatura21;
    $b21 = $temp_min21;
    $c21 = $temp_max21;
    $d21 = $pressure21;
    $e21 = $sea_level21;
    $f21 = $ground_level21;
    $g21 = $humidity21;

  //weather list
    $n21 = $weather_condition_id21;
    $o21 = $weather_condition_main21;
    $p21 = $weather_description21;
    $q21 = $weather_icon21;
  //clouds
    $r21 = $clouds21;
  //wind
    $s21 = $wind_speed21;
    $t21 = $wind_deg21;
    $h21 = $direction_wind21;
    $z21 = $wind_force21;
    $l21 = $sea_force21;
  //rain
    $u21 = $rain21;
  //snow
    $v21 = $snow21;
  //data/time of calculation
    $w21 = $data_time_pre21;
    $y21 = $data_time21;
  }
  else{
    $a21 = '';
    $b21 = '';
    $c21 = '';
    $d21 = '';
    $e21 = '';
    $f21 = '';
    $g21 = '';

    //temperatura in gradi c
      $i21 = '';
      $l21 = '';
      $m21 = '';
  //weather list
    $n21 = '';
    $o21 = '';
    $p21 = '';
    $q21 = '';
  //clouds
    $r21 = '';
  //wind
    $s21 = '';
    $t21 = '';
    $h21 = '';
    $z21 = '';
    $l21 = '';
  //rain
    $u21 = '';
  //snow
    $v21 = '';
  //data/time of calculation
    $w21 = '';
    $y21 = '';
  }

//22 °
  if($data_time_confr22 == $data_time_confr24){
    $a22 = $temperatura22;
    $b22 = $temp_min22;
    $c22 = $temp_max22;
    $d22 = $pressure22;
    $e22 = $sea_level22;
    $f22 = $ground_level22;
    $g22 = $humidity22;

  //weather list
    $n22 = $weather_condition_id22;
    $o22 = $weather_condition_main22;
    $p22 = $weather_description22;
    $q22 = $weather_icon22;
  //clouds
    $r22 = $clouds22;
  //wind
    $s22 = $wind_speed22;
    $t22 = $wind_deg22;
    $h22 = $direction_wind22;
    $z22 = $wind_force22;
    $l22 = $sea_force22;
  //rain
    $u22 = $rain22;
  //snow
    $v22 = $snow22;
  //data/time of calculation
    $w22 = $data_time_pre22;
    $y22 = $data_time22;
  }
  else{
    $a22 = '';
    $b22 = '';
    $c22 = '';
    $d22 = '';
    $e22 = '';
    $f22 = '';
    $g22 = '';

    //temperatura in gradi c
      $i22 = '';
      $l22 = '';
      $m22 = '';
  //weather list
    $n22 = '';
    $o22 = '';
    $p22 = '';
    $q22 = '';
  //clouds
    $r22 = '';
  //wind
    $s22 = '';
    $t22 = '';
    $h22 = '';
    $z22 = '';
    $l22 = '';
  //rain
    $u22 = '';
  //snow
    $v22 = '';
  //data/time of calculation
    $w22 = '';
    $y22 = '';
  }

//23 °
  if($data_time_confr23 == $data_time_confr24){
    $a23 = $temperatura23;
    $b23 = $temp_min23;
    $c23 = $temp_max23;
    $d23 = $pressure23;
    $e23 = $sea_level23;
    $f23 = $ground_level23;
    $g23 = $humidity23;

  //weather list
    $n23 = $weather_condition_id23;
    $o23 = $weather_condition_main23;
    $p23 = $weather_description23;
    $q23 = $weather_icon23;
  //clouds
    $r23 = $clouds23;
  //wind
    $s23 = $wind_speed23;
    $t23 = $wind_deg23;
    $h23 = $direction_wind23;
    $z23 = $wind_force23;
    $l23 = $sea_force23;
  //rain
    $u23 = $rain23;
  //snow
    $v23 = $snow23;
  //data/time of calculation
    $w23 = $data_time_pre23;
    $y23 = $data_time23;
  }
  else{
    $a23 = '';
    $b23 = '';
    $c23 = '';
    $d23 = '';
    $e23 = '';
    $f23 = '';
    $g23 = '';

    //temperatura in gradi c
      $i23 = '';
      $l23 = '';
      $m23 = '';
  //weather list
    $n23 = '';
    $o23 = '';
    $p23 = '';
    $q23 = '';
  //clouds
    $r23 = '';
  //wind
    $s23 = '';
    $t23 = '';
    $h23 = '';
    $z23 = '';
    $l23 = '';
  //rain
    $u23 = '';
  //snow
    $v23 = '';
  //data/time of calculation
    $w23 = '';
    $y23 = '';
  }

//25 °
  if($data_time_confr25 == $data_time_confr24){
    $a25 = $temperatura25;
    $b25 = $temp_min25;
    $c25 = $temp_max25;
    $d25 = $pressure25;
    $e25 = $sea_level25;
    $f25 = $ground_level25;
    $g25 = $humidity25;

  //weather list
    $n25 = $weather_condition_id25;
    $o25 = $weather_condition_main25;
    $p25 = $weather_description25;
    $q25 = $weather_icon25;
  //clouds
    $r25 = $clouds25;
  //wind
    $s25 = $wind_speed25;
    $t25 = $wind_deg25;
    $h25 = $direction_wind25;
    $z25 = $wind_force25;
    $l25 = $sea_force25;
  //rain
    $u25 = $rain25;
  //snow
    $v25 = $snow25;
  //data/time of calculation
    $w25 = $data_time_pre25;
    $y25 = $data_time25;
  }
  else{
    $a25 = '';
    $b25 = '';
    $c25 = '';
    $d25 = '';
    $e25 = '';
    $f25 = '';
    $g25 = '';

    //temperatura in gradi c
      $i25 = '';
      $l25 = '';
      $m25 = '';
  //weather list
    $n25 = '';
    $o25 = '';
    $p25 = '';
    $q25 = '';
  //clouds
    $r25 = '';
  //wind
    $s25 = '';
    $t25 = '';
    $h25 = '';
    $z25 = '';
    $l25 = '';
  //rain
    $u25 = '';
  //snow
    $v25 = '';
  //data/time of calculation
    $w25 = '';
    $y25 = '';
  }

//26 °
  if($data_time_confr26 == $data_time_confr24){
    $a26 = $temperatura26;
    $b26 = $temp_min26;
    $c26 = $temp_max26;
    $d26 = $pressure26;
    $e26 = $sea_level26;
    $f26 = $ground_level26;
    $g26 = $humidity26;

  //weather list
    $n26 = $weather_condition_id26;
    $o26 = $weather_condition_main26;
    $p26 = $weather_description26;
    $q26 = $weather_icon26;
  //clouds
    $r26 = $clouds26;
  //wind
    $s26 = $wind_speed26;
    $t26 = $wind_deg26;
    $h26 = $direction_wind26;
    $z26 = $wind_force26;
    $l26 = $sea_force26;
  //rain
    $u26 = $rain26;
  //snow
    $v26 = $snow26;
  //data/time of calculation
    $w26 = $data_time_pre26;
    $y26 = $data_time26;
  }
  else{
    $a26 = '';
    $b26 = '';
    $c26 = '';
    $d26 = '';
    $e26 = '';
    $f26 = '';
    $g26 = '';

    //temperatura in gradi c
      $i26 = '';
      $l26 = '';
      $m26 = '';
  //weather list
    $n26 = '';
    $o26 = '';
    $p26 = '';
    $q26 = '';
  //clouds
    $r26 = '';
  //wind
    $s26 = '';
    $t26 = '';
    $h26 = '';
    $z26 = '';
    $l26 = '';
  //rain
    $u26 = '';
  //snow
    $v26 = '';
  //data/time of calculation
    $w26 = '';
    $y26 = '';
  }

//27 °
  if($data_time_confr27 == $data_time_confr24){
    $a27 = $temperatura27;
    $b27 = $temp_min27;
    $c27 = $temp_max27;
    $d27 = $pressure27;
    $e27 = $sea_level27;
    $f27 = $ground_level27;
    $g27 = $humidity27;

  //weather list
    $n27 = $weather_condition_id27;
    $o27 = $weather_condition_main27;
    $p27 = $weather_description27;
    $q27 = $weather_icon27;
  //clouds
    $r27 = $clouds27;
  //wind
    $s27 = $wind_speed27;
    $t27 = $wind_deg27;
    $h27 = $direction_wind27;
    $z27 = $wind_force27;
    $l27 = $sea_force27;
  //rain
    $u27 = $rain27;
  //snow
    $v27 = $snow27;
  //data/time of calculation
    $w27 = $data_time_pre27;
    $y27 = $data_time27;
  }
  else{
    $a27 = '';
    $b27 = '';
    $c27 = '';
    $d27 = '';
    $e27 = '';
    $f27 = '';
    $g27 = '';

    //temperatura in gradi c
      $i27 = '';
      $l27 = '';
      $m27 = '';
  //weather list
    $n27 = '';
    $o27 = '';
    $p27 = '';
    $q27 = '';
  //clouds
    $r27 = '';
  //wind
    $s27 = '';
    $t27 = '';
    $h27 = '';
    $z27 = '';
    $l27 = '';
  //rain
    $u27 = '';
  //snow
    $v27 = '';
  //data/time of calculation
    $w27 = '';
    $y27 = '';
  }

//28 °
  if($data_time_confr28 == $data_time_confr24){
    $a28 = $temperatura28;
    $b28 = $temp_min28;
    $c28 = $temp_max28;
    $d28 = $pressure28;
    $e28 = $sea_level28;
    $f28 = $ground_level28;
    $g28 = $humidity28;

  //weather list
    $n28 = $weather_condition_id28;
    $o28 = $weather_condition_main28;
    $p28 = $weather_description28;
    $q28 = $weather_icon28;
  //clouds
    $r28 = $clouds28;
  //wind
    $s28 = $wind_speed28;
    $t28 = $wind_deg28;
    $h28 = $direction_wind28;
    $z28 = $wind_force28;
    $l28 = $sea_force28;
  //rain
    $u28 = $rain28;
  //snow
    $v28 = $snow28;
  //data/time of calculation
    $w28 = $data_time_pre28;
    $y28 = $data_time28;
  }
  else{
    $a28 = '';
    $b28 = '';
    $c28 = '';
    $d28 = '';
    $e28 = '';
    $f28 = '';
    $g28 = '';

    //temperatura in gradi c
      $i28 = '';
      $l28 = '';
      $m28 = '';
  //weather list
    $n28 = '';
    $o28 = '';
    $p28 = '';
    $q28 = '';
  //clouds
    $r28 = '';
  //wind
    $s28 = '';
    $t28 = '';
    $h28 = '';
    $z28 = '';
    $l28 = '';
  //rain
    $u28 = '';
  //snow
    $v28 = '';
  //data/time of calculation
    $w28 = '';
    $y28 = '';
  }

//29 °
  if($data_time_confr29 == $data_time_confr24){
    $a29 = $temperatura29;
    $b29 = $temp_min29;
    $c29 = $temp_max29;
    $d29 = $pressure29;
    $e29 = $sea_level29;
    $f29 = $ground_level29;
    $g29 = $humidity29;

  //weather list
    $n29 = $weather_condition_id29;
    $o29 = $weather_condition_main29;
    $p29 = $weather_description29;
    $q29 = $weather_icon29;
  //clouds
    $r29 = $clouds29;
  //wind
    $s29 = $wind_speed29;
    $t29 = $wind_deg29;
    $h29 = $direction_wind29;
    $z29 = $wind_force29;
    $l29 = $sea_force29;
  //rain
    $u29 = $rain29;
  //snow
    $v29 = $snow29;
  //data/time of calculation
    $w29 = $data_time_pre29;
    $y29 = $data_time29;
  }
  else{
    $a29 = '';
    $b29 = '';
    $c29 = '';
    $d29 = '';
    $e29 = '';
    $f29 = '';
    $g29 = '';

    //temperatura in gradi c
      $i29 = '';
      $l29 = '';
      $m29 = '';
  //weather list
    $n29 = '';
    $o29 = '';
    $p29 = '';
    $q29 = '';
  //clouds
    $r29 = '';
  //wind
    $s29 = '';
    $t29 = '';
    $h29 = '';
    $z29 = '';
    $l29 = '';
  //rain
    $u29 = '';
  //snow
    $v29 = '';
  //data/time of calculation
    $w29 = '';
    $y29 = '';
  }

//30 °
  if($data_time_confr30 == $data_time_confr24){
    $a30 = $temperatura30;
    $b30 = $temp_min30;
    $c30 = $temp_max30;
    $d30 = $pressure30;
    $e30 = $sea_level30;
    $f30 = $ground_level30;
    $g30 = $humidity30;

  //weather list
    $n30 = $weather_condition_id30;
    $o30 = $weather_condition_main30;
    $p30 = $weather_description30;
    $q30 = $weather_icon30;
  //clouds
    $r30 = $clouds30;
  //wind
    $s30 = $wind_speed30;
    $t30 = $wind_deg30;
    $h30 = $direction_wind30;
    $z30 = $wind_force30;
    $l30 = $sea_force30;
  //rain
    $u30 = $rain30;
  //snow
    $v30 = $snow30;
  //data/time of calculation
    $w30 = $data_time_pre30;
    $y30 = $data_time30;
  }
  else{
    $a30 = '';
    $b30 = '';
    $c30 = '';
    $d30 = '';
    $e30 = '';
    $f30 = '';
    $g30 = '';

    //temperatura in gradi c
      $i30 = '';
      $l30 = '';
      $m30 = '';
  //weather list
    $n30 = '';
    $o30 = '';
    $p30 = '';
    $q30 = '';
  //clouds
    $r30 = '';
  //wind
    $s30 = '';
    $t30 = '';
    $h30 = '';
    $z30 = '';
    $l30 = '';
  //rain
    $u30 = '';
  //snow
    $v30 = '';
  //data/time of calculation
    $w30 = '';
    $y30 = '';
  }

//31 °
  if($data_time_confr31 == $data_time_confr24){
    $a31 = $temperatura31;
    $b31 = $temp_min31;
    $c31 = $temp_max31;
    $d31 = $pressure31;
    $e31 = $sea_level31;
    $f31 = $ground_level31;
    $g31 = $humidity31;

  //weather list
    $n31 = $weather_condition_id31;
    $o31 = $weather_condition_main31;
    $p31 = $weather_description31;
    $q31 = $weather_icon31;
  //clouds
    $r31 = $clouds31;
  //wind
    $s31 = $wind_speed31;
    $t31 = $wind_deg31;
    $h31 = $direction_wind31;
    $z31 = $wind_force31;
    $l31 = $sea_force31;
  //rain
    $u31 = $rain31;
  //snow
    $v31 = $snow31;
  //data/time of calculation
    $w31 = $data_time_pre31;
    $y31 = $data_time31;
  }
  else{
    $a31 = '';
    $b31 = '';
    $c31 = '';
    $d31 = '';
    $e31 = '';
    $f31 = '';
    $g31 = '';

    //temperatura in gradi c
      $i31 = '';
      $l31 = '';
      $m31 = '';
  //weather list
    $n31 = '';
    $o31 = '';
    $p31 = '';
    $q31 = '';
  //clouds
    $r31 = '';
  //wind
    $s31 = '';
    $t31 = '';
    $h31 = '';
    $z31 = '';
    $l31 = '';
  //rain
    $u31 = '';
  //snow
    $v31 = '';
  //data/time of calculation
    $w31 = '';
    $y31 = '';
  }


  //messaggio quarto giorno
  $bollettino_meteo_4 =
  "Day: $data_time_confr24
  T: $a17 $a18 $a19 $a20 $a21 $a22 $a23 $temperatura24 $a25 $a26 $a27 $a28 $a29 $a30 $a31
  Tmin: $b17 $b18 $b19 $b20 $b21 $b22 $b23 $temp_min24 $b25 $b26 $b27 $b28 $b29 $b30 $b31
  Tmax: $c17 $c18 $c19 $c20 $c21 $c22 $c23 $temp_max24 $c25 $c26 $c27 $c28 $c29 $c30 $c31
  pressure: $d17 $d18 $d19 $d20 $d21 $d22 $d23 $pressure24 $d25 $d26 $d27 $d28 $d29 $d30 $d31
  sea level: $e17 $e18 $e19 $e20 $e21 $e22 $e23 $sea_level24 $e25 $e26 $e27 $e28 $e29 $e30 $e31
  ground level: $f17 $f18 $f19 $f20 $f21 $f22 $f23 $ground_level24 $f25 $f26 $f27 $f28 $f29 $f30 $f31
  humidity: $g17 $g18 $g19 $g20 $g21 $g22 $g23 $humidity24 $g25 $g26 $g27 $g28 $g29 $g30 $g31

  weather : $q17 $q18 $q19 $q20 $q21 $q22 $q23 $weather_icon24 $q25 $q26 $q27 $q28 $q29 $q30 $q31
  weather condiction: $n17 $n18 $n19 $n20 $n21 $n22 $n23 $weather_condition_main24 $n25 $n26 $n27 $n28 $n29 $n30 $n31
  weather descriptione: $o17 $o18 $o19 $o20 $o21 $o22 $o23 $weather_description24 $o25 $o26 $o27 $o28 $o29 $o30 $o31

  clouds: $r17 $r18 $r19 $r20 $r21 $r22 $r23 $clouds24 $r25 $r26 $r27 $r28 $r29 $r30 $r31

  wind speed: $s17 $s18 $s19 $s20 $s21 $s22 $s23 $wind_speed24 $s25 $s26 $s27 $s28 $s29 $s30 $s31
  wind deg: $t17 $t18 $t19 $t20 $t21 $t22 $t23 $wind_deg24 $t25 $t26 $t27 $t28 $t29 $t30 $t31

  rain: $u17 $u18 $u19 $u20 $u21 $u22 $u23 $rain24 $u25 $u26 $u27 $u28 $u29 $u30 $u31

  snow: $v17 $v18 $v19 $v20 $v21 $v22 $v23 $snow24 $v25 $v26 $v27 $v28 $v29 $v30 $v31

  data/time: $y17 $y18 $y19 $y20 $y21 $y22 $y23 $data_time24 $y25 $y26 $y27 $y28 $y29 $y30 $y31
  ";
  sendMessage($chat_id, $bollettino_meteo_4);

/*

  __ _  __ _   _
 / _(_)/ _| | | |
| |_ _| |_| |_| |__    _ __ ___   ___  ___  __ _  __ _  ___
|  _| |  _| __| '_ \  | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
| | | | | | |_| | | | | | | | | |  __/\__ \ (_| | (_| |  __/
|_| |_|_|  \__|_| |_| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                  __/ |
                                                 |___/

*/
//25 °
  if($data_time_confr25 == $data_time_confr32){
    $a25 = $temperatura25;
    $b25 = $temp_min25;
    $c25 = $temp_max25;
    $d25 = $pressure25;
    $e25 = $sea_level25;
    $f25 = $ground_level25;
    $g25 = $humidity25;

  //weather list
    $n25 = $weather_condition_id25;
    $o25 = $weather_condition_main25;
    $p25 = $weather_description25;
    $q25 = $weather_icon25;
  //clouds
    $r25 = $clouds25;
  //wind
    $s25 = $wind_speed25;
    $t25 = $wind_deg25;
    $h25 = $direction_wind25;
    $z25 = $wind_force25;
    $l25 = $sea_force25;
  //rain
    $u25 = $rain25;
  //snow
    $v25 = $snow25;
  //data/time of calculation
    $w25 = $data_time_pre25;
    $y25 = $data_time25;
  }
  else{
    $a25 = '';
    $b25 = '';
    $c25 = '';
    $d25 = '';
    $e25 = '';
    $f25 = '';
    $g25 = '';

    //temperatura in gradi c
      $i25 = '';
      $l25 = '';
      $m25 = '';
  //weather list
    $n25 = '';
    $o25 = '';
    $p25 = '';
    $q25 = '';
  //clouds
    $r25 = '';
  //wind
    $s25 = '';
    $t25 = '';
    $h25 = '';
    $z25 = '';
    $l25 = '';
  //rain
    $u25 = '';
  //snow
    $v25 = '';
  //data/time of calculation
    $w25 = '';
    $y25 = '';
  }

//26 °
  if($data_time_confr26 == $data_time_confr32){
    $a26 = $temperatura26;
    $b26 = $temp_min26;
    $c26 = $temp_max26;
    $d26 = $pressure26;
    $e26 = $sea_level26;
    $f26 = $ground_level26;
    $g26 = $humidity26;

  //weather list
    $n26 = $weather_condition_id26;
    $o26 = $weather_condition_main26;
    $p26 = $weather_description26;
    $q26 = $weather_icon26;
  //clouds
    $r26 = $clouds26;
  //wind
    $s26 = $wind_speed26;
    $t26 = $wind_deg26;
    $h26 = $direction_wind26;
    $z26 = $wind_force26;
    $l26 = $sea_force26;
  //rain
    $u26 = $rain26;
  //snow
    $v26 = $snow26;
  //data/time of calculation
    $w26 = $data_time_pre26;
    $y26 = $data_time26;
  }
  else{
    $a26 = '';
    $b26 = '';
    $c26 = '';
    $d26 = '';
    $e26 = '';
    $f26 = '';
    $g26 = '';

    //temperatura in gradi c
      $i26 = '';
      $l26 = '';
      $m26 = '';
  //weather list
    $n26 = '';
    $o26 = '';
    $p26 = '';
    $q26 = '';
  //clouds
    $r26 = '';
  //wind
    $s26 = '';
    $t26 = '';
    $h26 = '';
    $z26 = '';
    $l26 = '';
  //rain
    $u26 = '';
  //snow
    $v26 = '';
  //data/time of calculation
    $w26 = '';
    $y26 = '';
  }

//27 °
  if($data_time_confr27 == $data_time_confr32){
    $a27 = $temperatura27;
    $b27 = $temp_min27;
    $c27 = $temp_max27;
    $d27 = $pressure27;
    $e27 = $sea_level27;
    $f27 = $ground_level27;
    $g27 = $humidity27;

  //weather list
    $n27 = $weather_condition_id27;
    $o27 = $weather_condition_main27;
    $p27 = $weather_description27;
    $q27 = $weather_icon27;
  //clouds
    $r27 = $clouds27;
  //wind
    $s27 = $wind_speed27;
    $t27 = $wind_deg27;
    $h27 = $direction_wind27;
    $z27 = $wind_force27;
    $l27 = $sea_force27;
  //rain
    $u27 = $rain27;
  //snow
    $v27 = $snow27;
  //data/time of calculation
    $w27 = $data_time_pre27;
    $y27 = $data_time27;
  }
  else{
    $a27 = '';
    $b27 = '';
    $c27 = '';
    $d27 = '';
    $e27 = '';
    $f27 = '';
    $g27 = '';

    //temperatura in gradi c
      $i27 = '';
      $l27 = '';
      $m27 = '';
  //weather list
    $n27 = '';
    $o27 = '';
    $p27 = '';
    $q27 = '';
  //clouds
    $r27 = '';
  //wind
    $s27 = '';
    $t27 = '';
    $h27 = '';
    $z27 = '';
    $l27 = '';
  //rain
    $u27 = '';
  //snow
    $v27 = '';
  //data/time of calculation
    $w27 = '';
    $y27 = '';
  }

//28 °
  if($data_time_confr28 == $data_time_confr32){
    $a28 = $temperatura28;
    $b28 = $temp_min28;
    $c28 = $temp_max28;
    $d28 = $pressure28;
    $e28 = $sea_level28;
    $f28 = $ground_level28;
    $g28 = $humidity28;

  //weather list
    $n28 = $weather_condition_id28;
    $o28 = $weather_condition_main28;
    $p28 = $weather_description28;
    $q28 = $weather_icon28;
  //clouds
    $r28 = $clouds28;
  //wind
    $s28 = $wind_speed28;
    $t28 = $wind_deg28;
    $h28 = $direction_wind28;
    $z28 = $wind_force28;
    $l28 = $sea_force28;
  //rain
    $u28 = $rain28;
  //snow
    $v28 = $snow28;
  //data/time of calculation
    $w28 = $data_time_pre28;
    $y28 = $data_time28;
  }
  else{
    $a28 = '';
    $b28 = '';
    $c28 = '';
    $d28 = '';
    $e28 = '';
    $f28 = '';
    $g28 = '';

    //temperatura in gradi c
      $i28 = '';
      $l28 = '';
      $m28 = '';
  //weather list
    $n28 = '';
    $o28 = '';
    $p28 = '';
    $q28 = '';
  //clouds
    $r28 = '';
  //wind
    $s28 = '';
    $t28 = '';
    $h28 = '';
    $z28 = '';
    $l28 = '';
  //rain
    $u28 = '';
  //snow
    $v28 = '';
  //data/time of calculation
    $w28 = '';
    $y28 = '';
  }

//29 °
  if($data_time_confr29 == $data_time_confr32){
    $a29 = $temperatura29;
    $b29 = $temp_min29;
    $c29 = $temp_max29;
    $d29 = $pressure29;
    $e29 = $sea_level29;
    $f29 = $ground_level29;
    $g29 = $humidity29;

  //weather list
    $n29 = $weather_condition_id29;
    $o29 = $weather_condition_main29;
    $p29 = $weather_description29;
    $q29 = $weather_icon29;
  //clouds
    $r29 = $clouds29;
  //wind
    $s29 = $wind_speed29;
    $t29 = $wind_deg29;
    $h29 = $direction_wind29;
    $z29 = $wind_force29;
    $l29 = $sea_force29;
  //rain
    $u29 = $rain29;
  //snow
    $v29 = $snow29;
  //data/time of calculation
    $w29 = $data_time_pre29;
    $y29 = $data_time29;
  }
  else{
    $a29 = '';
    $b29 = '';
    $c29 = '';
    $d29 = '';
    $e29 = '';
    $f29 = '';
    $g29 = '';

    //temperatura in gradi c
      $i29 = '';
      $l29 = '';
      $m29 = '';
  //weather list
    $n29 = '';
    $o29 = '';
    $p29 = '';
    $q29 = '';
  //clouds
    $r29 = '';
  //wind
    $s29 = '';
    $t29 = '';
    $h29 = '';
    $z29 = '';
    $l29 = '';
  //rain
    $u29 = '';
  //snow
    $v29 = '';
  //data/time of calculation
    $w29 = '';
    $y29 = '';
  }

//30 °
  if($data_time_confr30 == $data_time_confr32){
    $a30 = $temperatura30;
    $b30 = $temp_min30;
    $c30 = $temp_max30;
    $d30 = $pressure30;
    $e30 = $sea_level30;
    $f30 = $ground_level30;
    $g30 = $humidity30;

  //weather list
    $n30 = $weather_condition_id30;
    $o30 = $weather_condition_main30;
    $p30 = $weather_description30;
    $q30 = $weather_icon30;
  //clouds
    $r30 = $clouds30;
  //wind
    $s30 = $wind_speed30;
    $t30 = $wind_deg30;
    $h30 = $direction_wind30;
    $z30 = $wind_force30;
    $l30 = $sea_force30;
  //rain
    $u30 = $rain30;
  //snow
    $v30 = $snow30;
  //data/time of calculation
    $w30 = $data_time_pre30;
    $y30 = $data_time30;
  }
  else{
    $a30 = '';
    $b30 = '';
    $c30 = '';
    $d30 = '';
    $e30 = '';
    $f30 = '';
    $g30 = '';

    //temperatura in gradi c
      $i30 = '';
      $l30 = '';
      $m30 = '';
  //weather list
    $n30 = '';
    $o30 = '';
    $p30 = '';
    $q30 = '';
  //clouds
    $r30 = '';
  //wind
    $s30 = '';
    $t30 = '';
    $h30 = '';
    $z30 = '';
    $l30 = '';
  //rain
    $u30 = '';
  //snow
    $v30 = '';
  //data/time of calculation
    $w30 = '';
    $y30 = '';
  }

//31 °
  if($data_time_confr31 == $data_time_confr32){
    $a31 = $temperatura31;
    $b31 = $temp_min31;
    $c31 = $temp_max31;
    $d31 = $pressure31;
    $e31 = $sea_level31;
    $f31 = $ground_level31;
    $g31 = $humidity31;

  //weather list
    $n31 = $weather_condition_id31;
    $o31 = $weather_condition_main31;
    $p31 = $weather_description31;
    $q31 = $weather_icon31;
  //clouds
    $r31 = $clouds31;
  //wind
    $s31 = $wind_speed31;
    $t31 = $wind_deg31;
    $h31 = $direction_wind31;
    $z31 = $wind_force31;
    $l31 = $sea_force31;
  //rain
    $u31 = $rain31;
  //snow
    $v31 = $snow31;
  //data/time of calculation
    $w31 = $data_time_pre31;
    $y31 = $data_time31;
  }
  else{
    $a31 = '';
    $b31 = '';
    $c31 = '';
    $d31 = '';
    $e31 = '';
    $f31 = '';
    $g31 = '';

    //temperatura in gradi c
      $i31 = '';
      $l31 = '';
      $m31 = '';
  //weather list
    $n31 = '';
    $o31 = '';
    $p31 = '';
    $q31 = '';
  //clouds
    $r31 = '';
  //wind
    $s31 = '';
    $t31 = '';
    $h31 = '';
    $z31 = '';
    $l31 = '';
  //rain
    $u31 = '';
  //snow
    $v31 = '';
  //data/time of calculation
    $w31 = '';
    $y31 = '';
  }

//33 °
  if($data_time_confr33 == $data_time_confr32){
    $a33 = $temperatura33;
    $b33 = $temp_min33;
    $c33 = $temp_max33;
    $d33 = $pressure33;
    $e33 = $sea_level33;
    $f33 = $ground_level33;
    $g33 = $humidity33;

  //weather list
    $n33 = $weather_condition_id33;
    $o33 = $weather_condition_main33;
    $p33 = $weather_description33;
    $q33 = $weather_icon33;
  //clouds
    $r33 = $clouds33;
  //wind
    $s33 = $wind_speed33;
    $t33 = $wind_deg33;
    $h33 = $direction_wind33;
    $z33 = $wind_force33;
    $l33 = $sea_force33;
  //rain
    $u33 = $rain33;
  //snow
    $v33 = $snow33;
  //data/time of calculation
    $w33 = $data_time_pre33;
    $y33 = $data_time33;
  }
  else{
    $a33 = '';
    $b33 = '';
    $c33 = '';
    $d33 = '';
    $e33 = '';
    $f33 = '';
    $g33 = '';

    //temperatura in gradi c
      $i33 = '';
      $l33 = '';
      $m33 = '';
  //weather list
    $n33 = '';
    $o33 = '';
    $p33 = '';
    $q33 = '';
  //clouds
    $r33 = '';
  //wind
    $s33 = '';
    $t33 = '';
    $h33 = '';
    $z33 = '';
    $l33 = '';
  //rain
    $u33 = '';
  //snow
    $v33 = '';
  //data/time of calculation
    $w33 = '';
    $y33 = '';
  }

//34 °
  if($data_time_confr34 == $data_time_confr32){
    $a34 = $temperatura34;
    $b34 = $temp_min34;
    $c34 = $temp_max34;
    $d34 = $pressure34;
    $e34 = $sea_level34;
    $f34 = $ground_level34;
    $g34 = $humidity34;

  //weather list
    $n34 = $weather_condition_id34;
    $o34 = $weather_condition_main34;
    $p34 = $weather_description34;
    $q34 = $weather_icon34;
  //clouds
    $r34 = $clouds34;
  //wind
    $s34 = $wind_speed34;
    $t34 = $wind_deg34;
    $h34 = $direction_wind34;
    $z34 = $wind_force34;
    $l34 = $sea_force34;
  //rain
    $u34 = $rain34;
  //snow
    $v34 = $snow34;
  //data/time of calculation
    $w34 = $data_time_pre34;
    $y34 = $data_time34;
  }
  else{
    $a34 = '';
    $b34 = '';
    $c34 = '';
    $d34 = '';
    $e34 = '';
    $f34 = '';
    $g34 = '';

    //temperatura in gradi c
      $i34 = '';
      $l34 = '';
      $m34 = '';
  //weather list
    $n34 = '';
    $o34 = '';
    $p34 = '';
    $q34 = '';
  //clouds
    $r34 = '';
  //wind
    $s34 = '';
    $t34 = '';
    $h34 = '';
    $z34 = '';
    $l34 = '';
  //rain
    $u34 = '';
  //snow
    $v34 = '';
  //data/time of calculation
    $w34 = '';
    $y34 = '';
  }

//35 °
  if($data_time_confr35 == $data_time_confr32){
    $a35 = $temperatura35;
    $b35 = $temp_min35;
    $c35 = $temp_max35;
    $d35 = $pressure35;
    $e35 = $sea_level35;
    $f35 = $ground_level35;
    $g35 = $humidity35;

  //weather list
    $n35 = $weather_condition_id35;
    $o35 = $weather_condition_main35;
    $p35 = $weather_description35;
    $q35 = $weather_icon35;
  //clouds
    $r35 = $clouds35;
  //wind
    $s35 = $wind_speed35;
    $t35 = $wind_deg35;
    $h35 = $direction_wind35;
    $z35 = $wind_force35;
    $l35 = $sea_force35;
  //rain
    $u35 = $rain35;
  //snow
    $v35 = $snow35;
  //data/time of calculation
    $w35 = $data_time_pre35;
    $y35 = $data_time35;
  }
  else{
    $a35 = '';
    $b35 = '';
    $c35 = '';
    $d35 = '';
    $e35 = '';
    $f35 = '';
    $g35 = '';

    //temperatura in gradi c
      $i35 = '';
      $l35 = '';
      $m35 = '';
  //weather list
    $n35 = '';
    $o35 = '';
    $p35 = '';
    $q35 = '';
  //clouds
    $r35 = '';
  //wind
    $s35 = '';
    $t35 = '';
    $h35 = '';
    $z35 = '';
    $l35 = '';
  //rain
    $u35 = '';
  //snow
    $v35 = '';
  //data/time of calculation
    $w35 = '';
    $y35 = '';
  }

//36 °
  if($data_time_confr36 == $data_time_confr32){
    $a36 = $temperatura36;
    $b36 = $temp_min36;
    $c36 = $temp_max36;
    $d36 = $pressure36;
    $e36 = $sea_level36;
    $f36 = $ground_level36;
    $g36 = $humidity36;

  //weather list
    $n36 = $weather_condition_id36;
    $o36 = $weather_condition_main36;
    $p36 = $weather_description36;
    $q36 = $weather_icon36;
  //clouds
    $r36 = $clouds36;
  //wind
    $s36 = $wind_speed36;
    $t36 = $wind_deg36;
    $h36 = $direction_wind36;
    $z36 = $wind_force36;
    $l36 = $sea_force36;
  //rain
    $u36 = $rain36;
  //snow
    $v36 = $snow36;
  //data/time of calculation
    $w36 = $data_time_pre36;
    $y36 = $data_time36;
  }
  else{
    $a36 = '';
    $b36 = '';
    $c36 = '';
    $d36 = '';
    $e36 = '';
    $f36 = '';
    $g36 = '';

    //temperatura in gradi c
      $i36 = '';
      $l36 = '';
      $m36 = '';
  //weather list
    $n36 = '';
    $o36 = '';
    $p36 = '';
    $q36 = '';
  //clouds
    $r36 = '';
  //wind
    $s36 = '';
    $t36 = '';
    $h36 = '';
    $z36 = '';
    $l36 = '';
  //rain
    $u36 = '';
  //snow
    $v36 = '';
  //data/time of calculation
    $w36 = '';
    $y36 = '';
  }

//37 °
  if($data_time_confr37 == $data_time_confr32){
    $a37 = $temperatura37;
    $b37 = $temp_min37;
    $c37 = $temp_max37;
    $d37 = $pressure37;
    $e37 = $sea_level37;
    $f37 = $ground_level37;
    $g37 = $humidity37;

  //weather list
    $n37 = $weather_condition_id37;
    $o37 = $weather_condition_main37;
    $p37 = $weather_description37;
    $q37 = $weather_icon37;
  //clouds
    $r37 = $clouds37;
  //wind
    $s37 = $wind_speed37;
    $t37 = $wind_deg37;
    $h37 = $direction_wind37;
    $z37 = $wind_force37;
    $l37 = $sea_force37;
  //rain
    $u37 = $rain37;
  //snow
    $v37 = $snow37;
  //data/time of calculation
    $w37 = $data_time_pre37;
    $y37 = $data_time37;
  }
  else{
    $a37 = '';
    $b37 = '';
    $c37 = '';
    $d37 = '';
    $e37 = '';
    $f37 = '';
    $g37 = '';

    //temperatura in gradi c
      $i37 = '';
      $l37 = '';
      $m37 = '';
  //weather list
    $n37 = '';
    $o37 = '';
    $p37 = '';
    $q37 = '';
  //clouds
    $r37 = '';
  //wind
    $s37 = '';
    $t37 = '';
    $h37 = '';
    $z37 = '';
    $l37 = '';
  //rain
    $u37 = '';
  //snow
    $v37 = '';
  //data/time of calculation
    $w37 = '';
    $y37 = '';
  }

//38 °
  if($data_time_confr38 == $data_time_confr32){
    $a38 = $temperatura38;
    $b38 = $temp_min38;
    $c38 = $temp_max38;
    $d38 = $pressure38;
    $e38 = $sea_level38;
    $f38 = $ground_level38;
    $g38 = $humidity38;

  //weather list
    $n38 = $weather_condition_id38;
    $o38 = $weather_condition_main38;
    $p38 = $weather_description38;
    $q38 = $weather_icon38;
  //clouds
    $r38 = $clouds38;
  //wind
    $s38 = $wind_speed38;
    $t38 = $wind_deg38;
    $h38 = $direction_wind38;
    $z38 = $wind_force38;
    $l38 = $sea_force38;
  //rain
    $u38 = $rain38;
  //snow
    $v38 = $snow38;
  //data/time of calculation
    $w38 = $data_time_pre38;
    $y38 = $data_time38;
  }
  else{
    $a38 = '';
    $b38 = '';
    $c38 = '';
    $d38 = '';
    $e38 = '';
    $f38 = '';
    $g38 = '';

    //temperatura in gradi c
      $i38 = '';
      $l38 = '';
      $m38 = '';
  //weather list
    $n38 = '';
    $o38 = '';
    $p38 = '';
    $q38 = '';
  //clouds
    $r38 = '';
  //wind
    $s38 = '';
    $t38 = '';
    $h38 = '';
    $z38 = '';
    $l38 = '';
  //rain
    $u38 = '';
  //snow
    $v38 = '';
  //data/time of calculation
    $w38 = '';
    $y38 = '';
  }

//39 °
  if($data_time_confr39 == $data_time_confr32){
    $a39 = $temperatura39;
    $b39 = $temp_min39;
    $c39 = $temp_max39;
    $d39 = $pressure39;
    $e39 = $sea_level39;
    $f39 = $ground_level39;
    $g39 = $humidity39;

  //weather list
    $n39 = $weather_condition_id39;
    $o39 = $weather_condition_main39;
    $p39 = $weather_description39;
    $q39 = $weather_icon39;
  //clouds
    $r39 = $clouds39;
  //wind
    $s39 = $wind_speed39;
    $t39 = $wind_deg39;
    $h39 = $direction_wind39;
    $z39 = $wind_force39;
    $l39 = $sea_force39;
  //rain
    $u39 = $rain39;
  //snow
    $v39 = $snow39;
  //data/time of calculation
    $w39 = $data_time_pre39;
    $y39 = $data_time39;
  }
  else{
    $a39 = '';
    $b39 = '';
    $c39 = '';
    $d39 = '';
    $e39 = '';
    $f39 = '';
    $g39 = '';

    //temperatura in gradi c
      $i39 = '';
      $l39 = '';
      $m39 = '';
  //weather list
    $n39 = '';
    $o39 = '';
    $p39 = '';
    $q39 = '';
  //clouds
    $r39 = '';
  //wind
    $s39 = '';
    $t39 = '';
    $h39 = '';
    $z39 = '';
    $l39 = '';
  //rain
    $u39 = '';
  //snow
    $v39 = '';
  //data/time of calculation
    $w39 = '';
    $y39 = '';
  }

  //messaggio quinto giorno
  $bollettino_meteo_5 =
  "Day: $data_time_confr32
  T: $a25 $a26 $a27 $a28 $a29 $a30 $a31 $temperatura32 $a33 $a34 $a35 $a36 $a37 $a38 $a39
  Tmin: $b25 $b26 $b27 $b28 $b29 $b30 $b31 $temp_min32 $b33 $b34 $b35 $b36 $b37 $b38 $b39
  Tmax: $c25 $c26 $c27 $c28 $c29 $c30 $c31 $temp_max32 $c33 $c34 $c35 $c36 $c37 $c38 $c39
  pressure: $d25 $d26 $d27 $d28 $d29 $d30 $d31 $pressure32 $d33 $d34 $d35 $d36 $d37 $d38 $d39
  sea level: $e25 $e26 $e27 $e28 $e29 $e30 $e31 $sea_level32 $e33 $e34 $e35 $e36 $e37 $e38 $e39
  ground level: $f25 $f26 $f27 $f28 $f29 $f30 $f31 $ground_level32 $f33 $f34 $f35 $f36 $f37 $f38 $f39
  humidity: $g25 $g26 $g27 $g28 $g29 $g30 $g31 $humidity32 $g33 $g34 $g35 $g36 $g37 $g38 $g39

  weather : $q25 $q26 $q27 $q28 $q29 $q30 $q31 $weather_icon32 $q33 $q34 $q35 $q36 $q37 $q38 $q39
  weather condiction: $n25 $n26 $n27 $n28 $n29 $n30 $n31 $weather_condition_main32 $n33 $n34 $n35 $n36 $n37 $n38 $n39
  weather descriptione: $o25 $o26 $o27 $o28 $o29 $o30 $o31 $weather_description32 $o33 $o34 $o35 $o36 $o37 $o38 $o39

  clouds: $r25 $r26 $r27 $r28 $r29 $r30 $r31 $clouds32 $r33 $r34 $r35 $r36 $r37 $r38 $r39

  wind speed: $s25 $s26 $s27 $s28 $s29 $s30 $s31 $wind_speed32 $s33 $s34 $s35 $s36 $s37 $s38 $s39
  wind deg: $t25 $t26 $t27 $t28 $t29 $t30 $t31 $wind_deg32 $t33 $t34 $t35 $t36 $t37 $t38 $t39

  rain: $u25 $u26 $u27 $u28 $u29 $u30 $u31 $rain32 $u33 $u34 $u35 $u36 $u37 $u38 $u39

  snow: $v25 $v26 $v27 $v28 $v29 $v30 $v31 $snow32 $v33 $v34 $v35 $v36 $v37 $v38 $v39

  data/time: $y25 $y26 $y27 $y28 $y29 $y30 $y31 $data_time32 $y33 $y34 $y35 $y36 $y37 $y38 $y39
  ";
  sendMessage($chat_id, $bollettino_meteo_5);

/*

     _      _   _
    (_)    | | | |
 ___ ___  _| |_| |__    _ __ ___   ___  ___  __ _  __ _  ___
/ __| \ \/ / __| '_ \  | '_ ` _ \ / _ \/ __|/ _` |/ _` |/ _ \
\__ \ |>  <| |_| | | | | | | | | |  __/\__ \ (_| | (_| |  __/
|___/_/_/\_\\__|_| |_| |_| |_| |_|\___||___/\__,_|\__, |\___|
                                                   __/ |
                                                  |___/

*/
//33 °
  if($data_time_confr33 == $data_time_confr39){
    $a33 = $temperatura33;
    $b33 = $temp_min33;
    $c33 = $temp_max33;
    $d33 = $pressure33;
    $e33 = $sea_level33;
    $f33 = $ground_level33;
    $g33 = $humidity33;

  //weather list
    $n33 = $weather_condition_id33;
    $o33 = $weather_condition_main33;
    $p33 = $weather_description33;
    $q33 = $weather_icon33;
  //clouds
    $r33 = $clouds33;
  //wind
    $s33 = $wind_speed33;
    $t33 = $wind_deg33;
    $h33 = $direction_wind33;
    $z33 = $wind_force33;
    $l33 = $sea_force33;
  //rain
    $u33 = $rain33;
  //snow
    $v33 = $snow33;
  //data/time of calculation
    $w33 = $data_time_pre33;
    $y33 = $data_time33;
  }
  else{
    $a33 = '';
    $b33 = '';
    $c33 = '';
    $d33 = '';
    $e33 = '';
    $f33 = '';
    $g33 = '';

    //temperatura in gradi c
      $i33 = '';
      $l33 = '';
      $m33 = '';
  //weather list
    $n33 = '';
    $o33 = '';
    $p33 = '';
    $q33 = '';
  //clouds
    $r33 = '';
  //wind
    $s33 = '';
    $t33 = '';
    $h33 = '';
    $z33 = '';
    $l33 = '';
  //rain
    $u33 = '';
  //snow
    $v33 = '';
  //data/time of calculation
    $w33 = '';
    $y33 = '';
  }

//34 °
  if($data_time_confr34 == $data_time_confr39){
    $a34 = $temperatura34;
    $b34 = $temp_min34;
    $c34 = $temp_max34;
    $d34 = $pressure34;
    $e34 = $sea_level34;
    $f34 = $ground_level34;
    $g34 = $humidity34;

  //weather list
    $n34 = $weather_condition_id34;
    $o34 = $weather_condition_main34;
    $p34 = $weather_description34;
    $q34 = $weather_icon34;
  //clouds
    $r34 = $clouds34;
  //wind
    $s34 = $wind_speed34;
    $t34 = $wind_deg34;
    $h34 = $direction_wind34;
    $z34 = $wind_force34;
    $l34 = $sea_force34;
  //rain
    $u34 = $rain34;
  //snow
    $v34 = $snow34;
  //data/time of calculation
    $w34 = $data_time_pre34;
    $y34 = $data_time34;
  }
  else{
    $a34 = '';
    $b34 = '';
    $c34 = '';
    $d34 = '';
    $e34 = '';
    $f34 = '';
    $g34 = '';

    //temperatura in gradi c
      $i34 = '';
      $l34 = '';
      $m34 = '';
  //weather list
    $n34 = '';
    $o34 = '';
    $p34 = '';
    $q34 = '';
  //clouds
    $r34 = '';
  //wind
    $s34 = '';
    $t34 = '';
    $h34 = '';
    $z34 = '';
    $l34 = '';
  //rain
    $u34 = '';
  //snow
    $v34 = '';
  //data/time of calculation
    $w34 = '';
    $y34 = '';
  }

//35 °
  if($data_time_confr35 == $data_time_confr39){
    $a35 = $temperatura35;
    $b35 = $temp_min35;
    $c35 = $temp_max35;
    $d35 = $pressure35;
    $e35 = $sea_level35;
    $f35 = $ground_level35;
    $g35 = $humidity35;

  //weather list
    $n35 = $weather_condition_id35;
    $o35 = $weather_condition_main35;
    $p35 = $weather_description35;
    $q35 = $weather_icon35;
  //clouds
    $r35 = $clouds35;
  //wind
    $s35 = $wind_speed35;
    $t35 = $wind_deg35;
    $h35 = $direction_wind35;
    $z35 = $wind_force35;
    $l35 = $sea_force35;
  //rain
    $u35 = $rain35;
  //snow
    $v35 = $snow35;
  //data/time of calculation
    $w35 = $data_time_pre35;
    $y35 = $data_time35;
  }
  else{
    $a35 = '';
    $b35 = '';
    $c35 = '';
    $d35 = '';
    $e35 = '';
    $f35 = '';
    $g35 = '';

    //temperatura in gradi c
      $i35 = '';
      $l35 = '';
      $m35 = '';
  //weather list
    $n35 = '';
    $o35 = '';
    $p35 = '';
    $q35 = '';
  //clouds
    $r35 = '';
  //wind
    $s35 = '';
    $t35 = '';
    $h35 = '';
    $z35 = '';
    $l35 = '';
  //rain
    $u35 = '';
  //snow
    $v35 = '';
  //data/time of calculation
    $w35 = '';
    $y35 = '';
  }

//36 °
  if($data_time_confr36 == $data_time_confr39){
    $a36 = $temperatura36;
    $b36 = $temp_min36;
    $c36 = $temp_max36;
    $d36 = $pressure36;
    $e36 = $sea_level36;
    $f36 = $ground_level36;
    $g36 = $humidity36;

  //weather list
    $n36 = $weather_condition_id36;
    $o36 = $weather_condition_main36;
    $p36 = $weather_description36;
    $q36 = $weather_icon36;
  //clouds
    $r36 = $clouds36;
  //wind
    $s36 = $wind_speed36;
    $t36 = $wind_deg36;
    $h36 = $direction_wind36;
    $z36 = $wind_force36;
    $l36 = $sea_force36;
  //rain
    $u36 = $rain36;
  //snow
    $v36 = $snow36;
  //data/time of calculation
    $w36 = $data_time_pre36;
    $y36 = $data_time36;
  }
  else{
    $a36 = '';
    $b36 = '';
    $c36 = '';
    $d36 = '';
    $e36 = '';
    $f36 = '';
    $g36 = '';

    //temperatura in gradi c
      $i36 = '';
      $l36 = '';
      $m36 = '';
  //weather list
    $n36 = '';
    $o36 = '';
    $p36 = '';
    $q36 = '';
  //clouds
    $r36 = '';
  //wind
    $s36 = '';
    $t36 = '';
    $h36 = '';
    $z36 = '';
    $l36 = '';
  //rain
    $u36 = '';
  //snow
    $v36 = '';
  //data/time of calculation
    $w36 = '';
    $y36 = '';
  }

//37 °
  if($data_time_confr37 == $data_time_confr39){
    $a37 = $temperatura37;
    $b37 = $temp_min37;
    $c37 = $temp_max37;
    $d37 = $pressure37;
    $e37 = $sea_level37;
    $f37 = $ground_level37;
    $g37 = $humidity37;

  //weather list
    $n37 = $weather_condition_id37;
    $o37 = $weather_condition_main37;
    $p37 = $weather_description37;
    $q37 = $weather_icon37;
  //clouds
    $r37 = $clouds37;
  //wind
    $s37 = $wind_speed37;
    $t37 = $wind_deg37;
    $h37 = $direction_wind37;
    $z37 = $wind_force37;
    $l37 = $sea_force37;
  //rain
    $u37 = $rain37;
  //snow
    $v37 = $snow37;
  //data/time of calculation
    $w37 = $data_time_pre37;
    $y37 = $data_time37;
  }
  else{
    $a37 = '';
    $b37 = '';
    $c37 = '';
    $d37 = '';
    $e37 = '';
    $f37 = '';
    $g37 = '';

    //temperatura in gradi c
      $i37 = '';
      $l37 = '';
      $m37 = '';
  //weather list
    $n37 = '';
    $o37 = '';
    $p37 = '';
    $q37 = '';
  //clouds
    $r37 = '';
  //wind
    $s37 = '';
    $t37 = '';
    $h37 = '';
    $z37 = '';
    $l37 = '';
  //rain
    $u37 = '';
  //snow
    $v37 = '';
  //data/time of calculation
    $w37 = '';
    $y37 = '';
  }

//38 °
  if($data_time_confr38 == $data_time_confr39){
    $a38 = $temperatura38;
    $b38 = $temp_min38;
    $c38 = $temp_max38;
    $d38 = $pressure38;
    $e38 = $sea_level38;
    $f38 = $ground_level38;
    $g38 = $humidity38;

  //weather list
    $n38 = $weather_condition_id38;
    $o38 = $weather_condition_main38;
    $p38 = $weather_description38;
    $q38 = $weather_icon38;
  //clouds
    $r38 = $clouds38;
  //wind
    $s38 = $wind_speed38;
    $t38 = $wind_deg38;
    $h38 = $direction_wind38;
    $z38 = $wind_force38;
    $l38 = $sea_force38;
  //rain
    $u38 = $rain38;
  //snow
    $v38 = $snow38;
  //data/time of calculation
    $w38 = $data_time_pre38;
    $y38 = $data_time38;
  }
  else{
    $a38 = '';
    $b38 = '';
    $c38 = '';
    $d38 = '';
    $e38 = '';
    $f38 = '';
    $g38 = '';

    //temperatura in gradi c
      $i38 = '';
      $l38 = '';
      $m38 = '';
  //weather list
    $n38 = '';
    $o38 = '';
    $p38 = '';
    $q38 = '';
  //clouds
    $r38 = '';
  //wind
    $s38 = '';
    $t38 = '';
    $h38 = '';
    $z38 = '';
    $l38 = '';
  //rain
    $u38 = '';
  //snow
    $v38 = '';
  //data/time of calculation
    $w38 = '';
    $y38 = '';
  }

  //messaggio sesto giorno
  $bollettino_meteo_6 =
  "Day: $data_time_confr39
  T: $a33 $a34 $a35 $a36 $a37 $a38 $temperatura39
  Tmin: $b33 $b34 $b35 $b36 $b37 $b38 $temp_min39
  Tmax: $c33 $c34 $c35 $c36 $c37 $c38 $temp_max39b
  pressure: $d33 $d34 $d35 $d36 $d37 $d38 $pressure39
  sea level: $e33 $e34 $e35 $e36 $e37 $e38 $sea_level39
  ground level: $f33 $f34 $f35 $f36 $f37 $f38 $ground_level39
  humidity: $g33 $g34 $g35 $g36 $g37 $g38 $humidity39

  weather : $q33 $q34 $q35 $q36 $q37 $q38 $weather_
  weather condiction: $n33 $n34 $n35 $n36 $n37 $n38 $weather_condition_main39
  weather descriptione: $o33 $o34 $o35 $o36 $o37 $o38 $weather_description39

  clouds: $r33 $r34 $r35 $r36 $r37 $r38 $clouds39

  wind speed: $s33 $s34 $s35 $s36 $s37 $s38 $wind_speed39
  wind deg: $t33 $t34 $t35 $t36 $t37 $t38 $wind_deg39

  rain: $u33 $u34 $u35 $u36 $u37 $u38 $rain39

  snow: $v33 $v34 $v35 $v36 $v37 $v38 $snow39

  data/time: $y33 $y34 $y35 $y36 $y37 $y38 $data_time39
  ";
  sendMessage($chat_id, $bollettino_meteo_6);
  }
      else{
            sendMessage($chat_id, "city not found with name, please try send location with coordinate");
      }
}
?>
