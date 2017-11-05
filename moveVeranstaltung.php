<?php

  if(isset($_GET['vid'])){
    if(isset($_GET['pid'])){
      require 'models/db.php';
      $db = new Database();
      $vid = $_GET['vid'];
      $pid = $_GET['pid'];
      $veranstaltungenByID = $db->insertVeranstaltungByIDandProf($vid, $pid);
      echo $vid;
    }
  }
?>
