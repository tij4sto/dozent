<?php

  if(isset($_GET['key'])){
      require 'models/db.php';
      $db = new Database();
      $key = $_GET['key'];
      $veranstaltungen = $db->sucheVeranstaltung($key);
      echo $veranstaltungen;
  }
?>
