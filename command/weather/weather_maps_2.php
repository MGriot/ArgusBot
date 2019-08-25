<?php
function weather_map_2(){
  $op =cl;// Weather map layer. Full list of available weather map layers
  /*
  {Op}	Meaning	Unit of measurement
PAC0	Convective precipitation	mm
PR0	Precipitation intensity	mm/s
PA0	Accumulated precipitation	mm
PAR0	Accumulated precipitation - rain	mm
PAS0	Accumulated precipitation - snow	m
SD0	Depth of snow	m
WS10	Wind speed at an altitude of 10 meters	m/s
WND	Joint display of speed wind (color) and wind direction (arrows), received by U and V components	m/s
APM	Atmospheric pressure on mean sea level	hPa
TA2	Air temperature at a height of 2 meters	°C
TD2	Temperature of a dew point	°C
TS0	Soil temperature 0-10 сm	K
TS10	Soil temperature >10 сm	K
HRD0	Relative humidity	%
CL	Cloudiness	%
  */
  $z =100;//number of zoom level
  $x = 100; //number of x tile coordinate
  $y = 100; //number of y tile coordinate
  global $owmapi; //personal API key
  //$date = ; //Date and time of (Unix time, UTC), e.g. date=1527811200. If you do not specify any date and time, you will get Current weather map.
/*
1. Current weather maps. Specify the current date and time and to get Current weather map. The date and time specified in the request will be rounded to the previous 3-hour interval.

2. Forecast weather maps. You can specify any date and time within the next 10 days to get the Forecast weather maps for the specified time. If you do not specify any date and time, you will get Current weather map.
Not available for TS0 and TS10 layers.

3. Historical weather maps. If you specify any date and time since 9 February 2018, you will receive Historical weather maps for the selected date and time. If you do not specify any date and time, you will get Current weather map.
*/
  $opacity = 0.8; // Degree of layer opacity. Available value from 0 to 1 (default - 0.8)
//  $palette = ; //Color palette. You can use custom palettes for each layer. You can also create a palette for yourself and pass it to the URL as follows {value}:{HEX color};..;{value}:{HEX color}.
  $fill_bound = true;// 'true' or 'false'. If 'true', then all weather values outside the specified set of values will be filled by color corresponding to the nearest specified value (default value - 'false': all weather values outside the specified set of values are not filled)
//  $arrow_step = ; //Step of values for drawing wind arrows, specify in pixels (default - 32). Parameter only for wind layers (WS10, WND).
//  $use_norm = ; //'true' or 'false'. If 'true', then the length of the arrows is normalizing (default - 'false': the length of the arrows is proportional to the speed wind value). Parameter only for wind layers (WS10, WND)

  $uri = "http://maps.openweathermap.org/maps/2.0/weather/$op/$z/$x/$y?date=$date&opacity=$opacity&fill_bound=$fill_bound&appid=$owmapi";

  sendMessage($chat_id_from,$uri);
}
 ?>
