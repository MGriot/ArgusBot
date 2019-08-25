<?php
function weather($chat_id_from){
  $keyboard = '[{"text":"Weather set units","callback_data":"Weather set units"}],
               [{"text":"Current weather","callback_data":"Current weather"},{"text":"5 day weather forecast","callback_data":"5 day weather forecast"}],
               [{"text":"Weather maps 1.0","callback_data":"Weather maps 1.0"},{"text":"UV index","callback_data":"UV index"},{"text":"Weather alerts","callback_data":"Weather alerts"}]'
  ;

  //se il messagio inviato al bot è start allora manda il messaggio di benevenuto
    sendMessage($chat_id_from,"
hai avviato il comando weather, scegli che tipo di bollettino meteo desideri, una volta selezionato mandami la città di cui ti interessa sapere il meteo.
puoi impostare anche le unità di misura che più preferisci per il tuo bollettino meteo.
Vanno bene per le città: \"nome\", \"nome + stato\", \"coordinate GPS\", \"latitudine ; longitudine\" oppure numero identificativo su https://openweathermap.org .
",$keyboard, "inline");
}
?>