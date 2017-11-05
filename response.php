<?php
function response(){
  if($_SERVER['REQUEST_METHOD']=='GET' && isset($_GET['q'])){
    $selected = $_GET['q'];
    return $selected;
  }
  else {
    "Läuft nicht";
  }
}


?>
