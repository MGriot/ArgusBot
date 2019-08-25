<?php
function inline_query($inline_query_id, $inline_query_from_id, $inline_query_query){
    $inline_query_query_exploded = explode(" ", $inline_query_query);
    if($inline_query_query_exploded[0] == 'wiki'){
        wikipedia_search_inline_query($inline_query_id, $inline_query_from_id, $inline_query_query);
    }//if wiki
}
?>