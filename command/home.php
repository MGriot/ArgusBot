<?php
function home(){
  global $chat_id_from;
  $keyboard = '[{"text":"Telegram","url":"https://core.telegram.org/"}],
               [{"text":"Telegram Bots","url":"https://core.telegram.org/bots"},{"text":"Telegram Bot API","url":"https://core.telegram.org/bots/api#inlinequeryresultarticle"}],
               [{"text":"Development","callback_data":"development"},{"text":"Normal Use","callback_data":"normal_use"}]';

  //se il messagio inviato al bot è start allora manda il messaggio di benevenuto
    sendMessage($chat_id_from,"
Per adesso i comandi disponibili sono:
/start - inizio chat
/home - lista completa dei comandi
/register - registrazione utente al bot
/get_data_user - solo admin, mostra tutti gli utenti registrati
/getinfo - ti darà delle informazioni della chat
/long - ti da la lunghezza della parola o del comando inserito dopo
/help - un aiuto più dettagliato per i comandi, devi digitare \"/help /nomecomando\"

/new_database - non so cosa fa

/current_weather - comando vero e proprio per il meteo
/5day_3hour_forecast - solo admin, comando per il meteo di 5 giorni (da completare)
/weather_map_2 - mappa meteo (ancora da vedere)
/air_pollution - comando per gli inquinanti atmosferici (CO, O3, SO2, NO2) (da completare)

/RSS - FeedRSS comando principale, aggiungere la parte sotto per le funzioni
  run - setta su 'run' lo stato del FeedRSS, avvia
  arrest - setta su 'arrest' lo stato del FeedRSS, ferma
          (file update_RSS-feed.php)
  show - mostra tutti i link RSS a cui si è iscritti
  add - aggiunge link RSS
  delet - elimina link RSS in base al numero ID del link che gli si da
          (change_link_RSS.php)

/feedback - feedback degli utenti

/generate_code - solo admin, genera un file di testo .txt nella dircetory

/chem - ricerca sostanze chimiche su PubChem
/xml - da le ultime news dai FeedRSS che hai passato

///////
/start - > avvio bot
/home -> info generali
/profile -> profilo utente
/weather -> apre menù meteo

se dgiti @ArgusBot_bot, seguito da wiki, puoi effettuare ricerche su wikipedia en
",$keyboard, "inline");
}

 ?>
