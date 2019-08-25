<?php
function weather_set_units($callback_ID_from){
    $keyboard = '[{"text":"standard","callback_data":"standard"},{"text":"metric","callback_data":"metric"},{"text":"imperial","callback_data":"imperial"}]';
    sendMessage($callback_ID_from, "Scegli in che unità di misura preferisci gli output del meteo", $keyboard, "inline");
}

function standard_units($callback_ID_from){
  sendMessage($callback_ID_from,"You have selected standard");
  sendMessage($callback_ID_from,"Temperature	: Kelvin\n	Humidity :	%\nPressure : hPa\nSpeed : meter/sec\nCloudiness :	%\nPrecipitation : mm");
}
function metric_units($callback_ID_from){
  sendMessage($callback_ID_from,"You have selected metric");
  sendMessage($callback_ID_from,"Temperature	: Celsius\n	Humidity :	%\nPressure : hPa\nSpeed : meter/sec\nCloudiness :	%\nPrecipitation : mm");

}
function imperial_units($callback_ID_from){
  sendMessage($callback_ID_from,"You have selected imperial");
  sendMessage($callback_ID_from,"Temperature	: Fahrenheit\n	Humidity :	%\nPressure : hPa\nSpeed : 	miles/hour\nCloudiness :	%\nPrecipitation : mm");
}
?>
