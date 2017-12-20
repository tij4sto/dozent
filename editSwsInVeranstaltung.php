<?php

  if(isset($_GET['sws'])){
    if(isset($_GET['pid'])){
      if(isset($_GET['vid'])){
        require 'models/db.php';
        $db = new Database();
        $sws = $_GET['sws'];
        $pid = $_GET['pid'];
        $vid = $_GET['vid'];
        $neuerAnteil = $db->updateAnteilAnVeranstaltung($vid, $pid, $sws);
        echo $neuerAnteil;
      }
    }
  }
?>
