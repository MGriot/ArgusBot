<?php
function generate_code(){
touch('generate/5day.txt');
$fp = fopen("generate/5day.txt", "r+");
if(!$fp) die ("Errore nella operaione con il file");

for ($a=0; $a <=39; $a++) {

fwrite($fp, "
//$a °
  if(%data_time_confr$a == %data_time_confr0){
    %a$a = %temperatura$a £(%temperaturaC$a)£;
    %b$a = %temp_min$a £(%temp_minC$a)£;
    %c$a = %temp_max$a £(%temp_maxC$a)£;
    %d$a = %pressure$a;
    %e$a = %sea_level$a;
    %f$a = %ground_level$a;
    %g$a = %humidity$a;

  //weather list
    %n$a = %weather_condition_id$a;
    %o$a = %weather_condition_main$a;
    %p$a = %weather_description$a;
    %q$a = %weather_icon$a;
  //clouds
    %r$a = %clouds$a;
  //wind
    %s$a = %wind_speed$a;
    %t$a = %wind_deg$a;
    %h$a = %direction_wind$a;
    %z$a = %wind_force$a;
    %l$a = %sea_force$a;
  //rain
    %u$a = %rain$a;
  //snow
    %v$a = %snow$a;
  //data/time of calculation
    %w$a = %data_time_pre$a;
    %y$a = %data_time$a;
  }
  else{
    %a$a = '';
    %b$a = '';
    %c$a = '';
    %d$a = '';
    %e$a = '';
    %f$a = '';
    %g$a = '';

    //temperatura in gradi c
      %i$a = '';
      %l$a = '';
      %m$a = '';
  //weather list
    %n$a = '';
    %o$a = '';
    %p$a = '';
    %q$a = '';
  //clouds
    %r$a = '';
  //wind
    %s$a = '';
    %t$a = '';
    %h$a = '';
    %z$a = '';
    %l$a = '';
  //rain
    %u$a = '';
  //snow
    %v$a = '';
  //data/time of calculation
    %w$a = '';
    %y$a = '';
  }
");
}
fclose($fp);
}
function generate_code1(){
global $text;
$substance = substr($text,5);

require('function\simplehtmldom\simple_html_dom.php');

// Create DOM from URL or file
$html = file_get_html('https://pubchem.ncbi.nlm.nih.gov/compound/702');

touch('generate/webscraping.txt');
$fp = fopen("generate/webscrapingtxt", "r+");
if(!$fp) die ("Errore nella operaione con il file");

fwrite($fp, $html);
fclose($fp);
}
 ?>
