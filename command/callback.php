<?php
function callback($callback_ID_from, $callback_data){
    $file = "data/" . $callback_ID_from . ".json";
    $file_get = file_get_contents($file);
    $json = json_decode($file_get, TRUE);
    if ($callback_data == 'Current weather'){
        //Modify the command variable.
        $json['Last_command']["Command"] = "Current weather";
        //Encode the array back into a JSON string.
        $json = json_encode($json);
        //Save the file.
        file_put_contents($file, $json);
        //avvio funzione messaggio
        message_current_weather($callback_ID_from);
    }//if
    elseif ($callback_data == '5 day weather forecast'){
        //Modify the command variable.
        $json['Last_command']["Command"] = "5 day weather forecast";
        //Encode the array back into a JSON string.
        $json = json_encode($json);
        //Save the file.
        file_put_contents($file, $json);
        //avvio funzione messaggio
        message_fiveday_3hour_forecast($callback_ID_from);
    }//elseif
    elseif($callback_data == 'Weather set units'){
        weather_set_units($callback_ID_from);
    }//elseif
    elseif ($callback_data == 'standard'){
        //Modify the command variable.
        $json['Weather']["Units"] = "standard";
        //Encode the array back into a JSON string.
        $json = json_encode($json);
        //Save the file.
        file_put_contents($file, $json);
        //avvio funzione messaggio
        standard_units($callback_ID_from);
    }//elseif
    elseif ($callback_data == 'metric'){
        //Modify the command variable.
        $json['Weather']["Units"] = "metric";
        //Encode the array back into a JSON string.
        $json = json_encode($json);
        //Save the file.
        file_put_contents($file, $json);
        //avvio funzione messaggio
        metric_units($callback_ID_from);
    }//elseif
    elseif ($callback_data == 'imperial'){
        //Modify the command variable.
        $json['Weather']["Units"] = "imperial";
        //Encode the array back into a JSON string.
        $json = json_encode($json);
        //Save the file.
        file_put_contents($file, $json);
        //avvio funzione messaggio
        imperial_units($callback_ID_from);
    }//elseif
}


?>