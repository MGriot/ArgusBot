<?php
function not_admin_message(){
  //se non si è un admi manda un messaggio di accesso invalido al comando
    sendMessage($chat_id_from,"Non sei un admin, non puoi usare questo comando");
}
 ?>
