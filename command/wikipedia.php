<?php
function wikipedia_search(){

}
function wikipedia_search_inline_query($inline_query_id, $inline_query_from_id, $inline_query_query){
    $inline_query_query_exploded = explode(" ", $inline_query_query);
    $inline_query_query_search = str_replace( $inline_query_query_exploded[0], '', $inline_query_query);
  sendMessage($inline_query_from_id, $inline_query_query_search);
    //url_wikipedia
    $uri = "https://en.wikipedia.org/w/api.php?action=opensearch&format=json&search=".$inline_query_query_search;
    $uri = file_get_contents($uri);
    $json = json_decode($uri, true);

    $title0 = $json[1][0];
    $description0 = $json[2][0];
    $link0 = $json[3][0];
        $uri0 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title0;
        $uri0 = file_get_contents($uri0);
        $json0 = json_decode($uri0, true);
    $image0 = $json0["query"]["pages"][0]["original"]["source"];
   

    $title1 = $json[1][1];
    $description1 = $json[2][1];
    $link1 = $json[3][1];
        $uri1 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title1;
        $uri1 = file_get_contents($uri1);
        $json1 = json_decode($uri1, true);
    $image1 = $json1["query"]["pages"][0]["original"]["source"];

    $title2 = $json[1][2];
    $description2 = $json[2][2];
    $link2 = $json[3][2];
        $uri2 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title2;
        $uri2 = file_get_contents($uri2);
        $json2 = json_decode($uri2, true);
    $image2 = $json2["query"]["pages"][0]["original"]["source"];

    $title3 = $json[1][3];
    $description3 = $json[2][3];
    $link3 = $json[3][3];
        $uri3 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title3;
        $uri3 = file_get_contents($uri3);
        $json3 = json_decode($uri3, true);
    $image3 = $json3["query"]["pages"][0]["original"]["source"];

    $title4 = $json[1][4];
    $description4 = $json[2][4];
    $link4 = $json[3][4];
        $uri4 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title4;
        $uri4 = file_get_contents($uri4);
        $json4 = json_decode($uri4, true);
    $image4 = $json4["query"]["pages"][0]["original"]["source"];
    
    $title5 = $json[1][5];
    $description5 = $json[2][5];
    $link5 = $json[3][5];
        $uri5 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title5;
        $uri5 = file_get_contents($uri5);
        $json5 = json_decode($uri5, true);
    $image5 = $json5["query"]["pages"][0]["original"]["source"];
    
    $title6 = $json[1][6];
    $description6 = $json[2][6];
    $link6 = $json[3][6];
        $uri6 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title6;
        $uri6 = file_get_contents($uri6);
        $json6 = json_decode($uri6, true);
    $image6 = $json6["query"]["pages"][0]["original"]["source"];
    
    $title7 = $json[1][7];
    $description7 = $json[2][7];
    $link7 = $json[3][7];
        $uri7 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title7;
        $uri7 = file_get_contents($uri7);
        $json7 = json_decode($uri7, true);
    $image7 = $json7["query"]["pages"][0]["original"]["source"];
    
    $title8 = $json[1][8];
    $description8 = $json[2][8];
    $link8 = $json[3][8];
        $uri8 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title8;
        $uri8 = file_get_contents($uri8);
        $json8 = json_decode($uri8, true);
    $image8 = $json8["query"]["pages"][0]["original"]["source"];
    
    $title9 = $json[1][9];
    $description9 = $json[2][9];
    $link9 = $json[3][9];
        $uri9 = "https://en.wikipedia.org/w/api.php?action=query&format=json&formatversion=2&prop=pageimages|pageterms&piprop=original&titles=".$title9;
        $uri9 = file_get_contents($uri9);
        $json9 = json_decode($uri9, true);
    $image9 = $json9["query"]["pages"][0]["original"]["source"];

    $result = array(
        array( "type" => "article",
          "id" => "0",
          "title" => "$title0",
          "input_message_content" => array("message_text" => "$title0 \n$description1 \n$link1", "parse_mode" => "HTML"),
          "description" => "$description0",
          "thumb_url" => "$image0"),
        array( "type" => "article",
          "id" => "1",
          "title" => "$title1",
          "input_message_content" => array("message_text" => "$title1 \n$description1 \n$link1", "parse_mode" => "HTML"),
          "description" => "$description1",
          "thumb_url" => "$image1"),
        array( "type" => "article",
          "id" => "2",
          "title" => "$title2",
          "input_message_content" => array("message_text" => "$title2 \n$description2 \n$link2", "parse_mode" => "HTML"),
          "description" => "$description2",
          "thumb_url" => "$image2"),
        array( "type" => "article",
          "id" => "3",
          "title" => "$title3",
          "input_message_content" => array("message_text" => "$title3 \n$description3 \n$link3", "parse_mode" => "HTML"),
          "description" => "$description3",
          "thumb_url" => "$image3"),
        array( "type" => "article",
          "id" => "4",
          "title" => "$title4",
          "input_message_content" => array("message_text" => "$title4 \n$description4 \n$link4", "parse_mode" => "HTML"),
          "description" => "$description4",
          "thumb_url" => "$image4"),
        array( "type" => "article",
          "id" => "5",
          "title" => "$title0",
          "input_message_content" => array("message_text" => "$title5 \n$description5 \n$link5", "parse_mode" => "HTML"), 
          "description" => "$description5",
          "thumb_url" => "$image5"),
        array( "type" => "article",
          "id" => "6",
          "title" => "$title6",
          "input_message_content" => array("message_text" => "$title6 \n$description6 \n$link6", "parse_mode" => "HTML"),
          "description" => "$description6",
          "thumb_url" => "$image6"),
        array( "type" => "article",
          "id" => "7",
          "title" => "$title7",
          "input_message_content" => array("message_text" => "$title7 \n$description7 \n$link7", "parse_mode" => "HTML"),
          "description" => "$description7",
          "thumb_url" => "$image7"),
        array( "type" => "article",
          "id" => "8",
          "title" => "$title8",
          "input_message_content" => array("message_text" => "$title8 \n$description8 \n$link8", "parse_mode" => "HTML"),
          "description" => "$description8",
          "thumb_url" => "$image8"),
        array( "type" => "article",
          "id" => "9",
          "title" => "$title9",
          "input_message_content" => array("message_text" => "$title9 \n$description9 \n$link9", "parse_mode" => "HTML"),
          "description" => "$description9",
          "thumb_url" => "$image9"));
    answerInlineQuery($inline_query_id, $result);
}
?>