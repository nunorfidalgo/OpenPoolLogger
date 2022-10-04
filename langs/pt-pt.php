<?php
error_reporting(E_ALL & ~E_NOTICE);

// $array = array(
//     "foo" => "bar",
//     42    => 24,
//     "multi" => array(
//          "dimensional" => array(
//              "array" => "foo"
//          )
//     )
// );

// var_dump($array["foo"]);
// var_dump($array[42]);
// var_dump($array["multi"]["dimensional"]["array"]);
// echo "1<br>";
// $jsonEncoded =  json_encode($array);
// echo "2 <br>";
// $jsonDecoded = json_decode($jsonEncoded, TRUE);
// var_dump( $jsonEncoded );

$lang = array(
  "page" => array(
    "logs" => array (
      "title" => "Registos"
    )
  )
);
header('Content-Type: application/json'); // firefox json viewer: about:confg -> devtools.jsonview.enabled
// print_r($lang);
echo json_encode($lang, JSON_PRETTY_PRINT);
?>
