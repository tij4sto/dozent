<?php
  if(isset($_GET['id'])){
    require 'models/db.php';
    $db = new Database();
    $selected = $_GET['id'];
    $veranstaltungenByID = $db->getUeberschneidungenById($selected);
    echo $veranstaltungenByID;
  }
?>
