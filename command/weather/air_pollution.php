<?php
function air_pollution(){
  $location_pollution = "0.0,10.0";
  global $text;
  $compound = substr($text,15,16);
  $data = '2016-01Z';
  global $owmapi;

  $uri_pollution ="http://samples.openweathermap.org/pollution/v1/$compound/$location_pollution/$data.json?appid=$owmapi";
  //prendo i dati in uscita dall'uri
    $uri_pollution_ex = file_get_contents($uri_pollution);
  //decodifico il risultato mamdato da $uri_meteo
    $pollution = json_decode($uri_pollution_ex, TRUE);
  //estrggo i valori dall'url
  $time = $pollution['time'];
  $location_latitude = $pollution['location']['latitude'];
  $location_longitude = $pollution['location']['longitude'];
  $data_array = $pollution['data'];
//sistemo l'array in modo che mi faccia vedre tutti i risultati
for ($i=0, $n=count($data_array); $i<$n; $i++){
  $value = $pollution['data'][i]['value'];
  $pressure = $pollution['data'][i]['pressure'];
  $precision = $pollution['data'][i]['precision'];
}
//raccolgo i dati
  for ($i=0, $n=count($data_array); $i <$n ; $i++) {
    $value_pollution ="Pollutioner: $compound
    Time: $time
    Langitude: $location_latitude
    Longitude: $location_longitude

    Value: $value
    Pressure: $pressure
    Precision: $precision

    Url: $uri_pollution";
  }

  sendMessage($chat_id_from,$value_pollution);
}
 ?>
