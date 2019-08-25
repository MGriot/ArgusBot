<?php
function message_current_weather($callback_ID_from){
  //messaggio
  sendMessage($callback_ID_from, 'mandami il nome della città che ti interessa o \"nome città, stato\", oppure mandami la tua posizione, mandami le coordinate digitando \"lat ; lon\", o ancora il zip code (codice postale)');
}

function current_weather($chat_id, $text, $location_latitude, $location_longitude, $owmapi, $Weather_units_json, $weather_language_json){

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
       $uri = "http://api.openweathermap.org/data/2.5/weather?q=".$text."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
      }//if
      elseif($text_exploded[1] != ""){
        if(is_numeric($text_exploded[0])){
           $uri = "http://api.openweathermap.org/data/2.5/weather?zip=".$text_exploded[0].$text_exploded[1]."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
        }
        else{
          $uri = "http://api.openweathermap.org/data/2.5/weather?q=".$text_exploded[0].$text_exploded[1]."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
        }//else
      }//elseif
    }//if
    elseif($text_exploded[1] == ";" and $text_exploded[2] != ""){
      $lat = $text_exploded[0];
      $lon = $text_exploded[2];
      $uri = "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
    }//elseif
   
  }//if
  elseif($location_latitude != '' and $location_longitude != ''){//city by geografial coordinates  weather?lat={lat}&lon={lon} inseriti via gps
     $uri = "http://api.openweathermap.org/data/2.5/weather?lat=".$location_latitude."&lon=".$location_longitude."&units=".$Weather_units_json."&lang=".$Weather_language_json."&APPID=".$owmapi;
  }//elseif
  
      //prendo i dati in uscita dall'uri
        $uri_meteo = file_get_contents($uri);

      //decodifico il risultato mamdato da $uri_meteo
        $clima = json_decode($uri_meteo, TRUE);

      //coord
        $country = $clima['sys']['country'];
        $longitudine = $clima['coord']['lon'];
        $latitudine = $clima['coord']['lat'];

      //weather (lo 0 vuol dire che cerco dentro l'array del meteo nella prima riga)
        $weather_condition_id = $clima['weather'][0]['id'];
        $weather_condition_main = $clima['weather'][0]['main'];
        $weather_description = $clima['weather'][0]['description'];
        $weather_icon = $clima['weather'][0]['icon'];
        // simbolo del meteo con wheather icon
        if ($weather_icon == "01d") {
          $immage = "☀️";
          /*800	clear sky	 01d
          */
        }
        elseif ($weather_icon == "01n") {
          $immage = "🌖";
          /*800	clear sky	 01n
          */
        }
        elseif ($weather_icon == "02d") {
          $immage = "🌤";
          /*801	few clouds	 02d
          */
        }
        elseif ($weather_icon == "02n") {
          $immage = "☁️";
          /*801	few clouds	 02n
          */
        }
        elseif ($weather_icon == "03d") {
          $immage = "⛅️";
          /*802	scattered clouds	 03d
          */
        }
        elseif ($weather_icon == "03n") {
          $immage = "☁️";
          /*802	scattered clouds	 03n
          */
        }
        elseif ($weather_icon == "04d") {
          $immage = "🌥";
          /*803	broken clouds	 04d
          */
        }
        elseif ($weather_icon == "04n") {
          $immage = "☁️";
          /*803	broken clouds	 04d
          */
        }
        elseif ($weather_icon == "9d"){ //
          $immage = "🌧";
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
        elseif ($weather_icon == "9n"){ //
          $immage = "🌧";
        /*

        */
        }
        elseif ($weather_icon == "10d"){
          $immage = "🌦";
        /*500	light rain	 10d
          501	moderate rain	 10d
          502	heavy intensity rain	 10d
          503	very heavy rain	 10d
          504	extreme rain	 10d
        */
        }
        elseif ($weather_icon == "10n"){
          $immage = "🌦";
        /*

        */
        }
        elseif($weather_icon == "11d"){
          $immage = "⛈";
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
        elseif($weather_icon == "11n"){
          $immage = "⛈";
          /*

          */
        }
        elseif ($weather_icon == "13d") {
          $immage = "🌨";
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
      elseif ($weather_icon == "13n") {
        $immage = "🌨";
        /*

        */
      }
        elseif ($weather_icon == "50d") {
          $immage = "🌫";
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
        elseif ($weather_icon == "50n") {
          $immage = "🌫";
          /*

          */
        }

      //main
        $temperatura = $clima['main']['temp'];
        $temp_min = $clima['main']['temp_min'];
        $temp_max = $clima['main']['temp_max'];
        $pressure = $clima['main']['pressure'];
        $sea_level = $clima['main']['sea_level'];
        $ground_level = $clima['main']['grnd_level'];
        $humidity = $clima['main']['humidity'];
        //temperatura in gradi c
          //round($numero, n) dove n sono il numero di cifre da arrotondare
          //ceil($numero) arrotonda in eccesso
          //floor($numero) arrotonda in difetto
          $temperatura_C = round($temperatura - 273.15, 3);
          $temp_min_C = round($temp_min - 273.15, 3);
          $temp_max_C = round($temp_max - 273.15, 3);
        //conversione pressione in atm
          $pressure_atm = round($pressure*0.00098692, 3);
      //wind
        $wind_speed = $clima['wind']['speed'];
        $wind_deg = $clima['wind']['deg'];
        //wind con direzioni cardinali
         $degre = (int)$wind_deg;

         if($degre == 0){
           $direction_wind = "North";
         }
         elseif($degre > 0 && $degre <45){
           $direction_wind = "North-NorthEast";
         }
         elseif ($degre == 45) {
           $direction_wind = "North-East";
         }
         elseif ($degre > 45 && $degre <90){
           $direction_wind = "East-NorthEast";
         }
         elseif ($degre == 90){
           $direction_wind = "East";
         }
         elseif($degre > 90 && $degre <135){
           $direction_wind = "East-SouthEast";
         }
         elseif($degre == 135){
           $direction_wind = "South-East";
         }
         elseif($degre > 135 && $degre <180){
           $direction_wind = "South-SouthEast";
         }
         elseif($degre == 180){
           $direction_wind = "South";
         }
         elseif($degre > 180 && $degre <225){
           $direction_wind = "West-SouthWest";
         }
         elseif($degre == 225){
           $direction_wind = "South-West";
         }
         elseif($degre > 225 && $degre <270){
           $direction_wind = "West-SouthWest";
         }
         elseif($degre == 270){
           $direction_wind = "West";
         }
         elseif($degre > 270 && $degre <315){
           $direction_wind = "West-NortWest";
         }
         elseif($degre == 315){
           $direction_wind = "North-West";
         }
         elseif($degre > 315 && $degre <360){
           $direction_wind = "North-NorthWest";
         }
         else{
           $direction_wind = "North";
         }
        //wind force
        if($wind_speed <= 0.3){
          $wind_force = "Calm";
          $sea_force = "Zero force";
        }
        elseif($wind_speed > 0.3 && $wind_speed < 1.5){
          $wind_force = "Burr of wind";
          $sea_force = "Force one";
        }
        elseif($wind_speed > 1.5 && $wind_speed < 3.3){
          $wind_force = "Light breeze";
          $sea_force = "Force two";
        }
        elseif($wind_speed > 3.3 && $wind_speed < 5.4){
          $wind_force = "Breeze";
          $sea_force = "Force two";
        }
        elseif($wind_speed > 5.4 && $wind_speed < 7.9){
          $wind_force = "Livelly breeze";
          $sea_force = "Force three";
        }
        elseif($wind_speed > 7.9 && $wind_speed < 10.7){
          $wind_force = "Tense breeze";
          $sea_force = "Force four";
        }
        elseif($wind_speed > 10.7 && $wind_speed < 13.8){
          $wind_force = "Fresh wind";
          $sea_force = "Force five";
        }
        elseif($wind_speed > 13.8 && $wind_speed < 17.1){
          $wind_force = "Strong wind";
          $sea_force = "Force six";
        }
        elseif($wind_speed > 17.1 && $wind_speed < 20.7){
          $wind_force = "Moderate storm";
          $sea_force = "Force seven";
        }
        elseif($wind_speed > 20.7 && $wind_speed < 24.4){
          $wind_force = "Strong storm";
          $sea_force = "Force eight";
        }
        elseif($wind_speed > 24.4 && $wind_speed < 28.4){
          $wind_force = "Storm";
          $sea_force = "Force nine";
        }
        elseif($wind_speed > 28.4 && $wind_speed < 32.6){
          $wind_force = "Fortunale";
          $sea_force = "Force ten";
        }
        elseif($wind_speed > 32.6){
          $wind_force = "Hurricane";
          $sea_force = "Force ten";
        }
        else{
          $wind_force = "zero";
          $sea_force = "zero";
        }
      //clouds
        $clouds = $clima['clouds']['all'];

      //rain
        $rain1 = $clima['rain']['1h'];
        $rain3 = $clima['rain']['3h'];

      //snow
        $snow1 = $clima['snow']['1h'];
        $snow3 = $clima['snow']['3h'];

      //time of data calculation
        $time_of_data_calculation = $clima['dt'];
        $time_of_data_calculation_correct = date("F j, Y, H:i:s",$time_of_data_calculation);
      //sys
        $internal_parameter_type = $clima['sys']['type'];
        $internal_parameter_id = $clima['sys']['id'];
        $internal_parameter_message = $clima['sys']['message'];
        $internal_parameter_country = $clima['sys']['country'];
        $internal_parameter_sunrise = $clima['sys']['sunrise'];
        $internal_parameter_sunset = $clima['sys']['sunset'];
        //time aggiustato
         $sunrise_time = date("H:i:s",$internal_parameter_sunrise);
         $sunset_time = date("H:i:s",$internal_parameter_sunset);

            //calcolo ore di sole
            $sunset_time_H = date("H",$internal_parameter_sunset);
            $sunset_time_i = date("i",$internal_parameter_sunset);
            $sunset_time_s = date("s",$internal_parameter_sunset);
            $sunset_time_s_tot = $sunset_time_s + $sunset_time_i*60 + $sunset_time_H*3600;

            $sunrise_time_H = date("H",$internal_parameter_sunrise);
            $sunrise_time_i = date("i",$internal_parameter_sunrise);
            $sunrise_time_s = date("s",$internal_parameter_sunrise);
            $sunrise_time_s_tot = $sunrise_time_s + $sunrise_time_i*60 + $sunrise_time_H*3600;

         $sunshine =$sunset_time_s_tot - $sunrise_time_s_tot;
         $sunshine_s = $sunshine%60;
         $sunshine_i_p = (int)($sunshine/60);
         $sunshine_i = $sunshine_i_p%60;
         $sunshine_H = (int)($sunshine_i_p/60);

         $sunshine_time = "$sunshine_H:$sunshine_i:$sunshine_s";
           /*
            $today = date("F j, Y, g:i a");                 // March 10, 2001, 5:16 pm
            $today = date("m.d.y");                         // 03.10.01
            $today = date("j, n, Y");                       // 10, 3, 2001
            $today = date("Ymd");                           // 20010310
            $today = date('h-i-s, j-m-y, it is w Day');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01
            $today = date('\i\t \i\s \t\h\e jS \d\a\y.');   // it is the 10th day.
            $today = date("D M j G:i:s T Y");               // Sat Mar 10 17:16:18 MST 2001
            $today = date('H:m:s \m \i\s\ \m\o\n\t\h');     // 17:03:18 m is month
            $today = date("H:i:s");                         // 17:16:18
          */

      //id
        $city_id = $clima['id'];
      //name
        $city_name = $clima['name'];
      //cod
        $cod = $clima['cod'];

        //controllo sulle intemperie 1h, nostra i valori solo se ci sono empty()verifica che la stringa si vuota, isset() verifica che la stringa abbia valore
        if(isset($rain1)){
          $rain_a = "\nRain 1h: $rain1 $precipitation_unit";
        }
        else{
          $rain_a = '';
        }
        if(isset($snow1)){
          $snow_a = "\nSnow 1h: $snow1 $precipitation_unit";
        }
        else{
          $snow_a= '';
        }
        //controllo sulle intemperie 3h, mostra i valori solo se ci sono
        if(isset($rain3)){
          $rain_b = "\nRain 3h: $rain3 $precipitation_unit";
        }
        else{
          $rain_b = '';
        }
        if(isset($snow3)){
          $snow_b = "\nSnow 3h: $snow3 $precipitation_unit";
        }
        else{
          $snow_b = '';
        }
        //controllo sea level Pressure
        if(isset($sea_level)){
          $sea_level_a = "\nSea level: $sea_level $pressure_unit";
        }
        else{
          $sea_level_a = '';
        }
        //controllo groun level Pressure
        if(isset($ground_level)){
          $ground_level_a = "\nGround level: $ground_level  $pressure_unit";
        }
        else{
          $ground_level_a = '';
        }

      //bollettino meteo, raccoglie tutte le informazioni che sono disponibili
$bollettino_meteo = ("
Current weather for: <b>$city_name, $internal_parameter_country</b>
Longitude: $longitudine
Latitude: $latitudine

Weather conditions: $weather_condition_main $immage
Weather description: $weather_description

Temperature: $temperatura $temperature_unit
Temperature minime: $temp_min $temperature_unit
Temperature massime: $temp_max $temperature_unit
Pressure: $pressure $pressure_unit $sea_level_a $ground_level_a
Humidity: $humidity $cloud_or_humidity_unit 

Wind speed: $wind_speed $speed_unit
Wind Force: $wind_force
Wind deg: $wind_deg $wind_direction_unit ($direction_wind)
Sea force: $sea_force

Clouds: $clouds $cloud_or_humidity_unit $rain_a $rain_b $snow_a $snow_b
Sunrise time: $sunrise_time
Sunset time: $sunset_time
Total hours of sunshine: $sunshine_time

Time of data calculation:
<b>$time_of_data_calculation_correct</b>
");
    if($city_name != ""){
        sendMessage($chat_id,$bollettino_meteo);
      }//if
    else {
        sendMessage($chat_id, "city not found with name, please try with another method");
      }//else
}//function

 ?>
