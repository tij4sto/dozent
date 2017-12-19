<!DOCTYPE html>
<html>
  <head>
    <title></title>
    <?php
      require 'models/db.php';
      $db = new Database();
      $profs = $db->getAllDozent();
      $veranstaltungen = $db->getAllVeranstaltung();
    ?>
    <script type="text/javascript" src="functions.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="s.css">
  </head>
  <body>
    <div id="profs"></div>
    <div id="wrapper">
      <div class="left">
        <div class="headline">
          <h1>Veranstaltungen</h1>
        </div>
        <div class="tab">
          <input id="vsuche" value="" onkeyup="updateAlleVeranstaltungen(value)" type="text" name="suche" placeholder="Suche..">
        </div>
        <div id="alleVeranstaltungen"></div>
      </div>
      <div class="right">
        <div class="headline">
          <h1>Veranstaltungen</h1>
        </div>
        <div class="tab">
<<<<<<< HEAD
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen')">Veranstaltungen SoSe</button>
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen')">Veranstaltungen WiSe</button>
          <button class="tablinks" onclick="openVList(event, 'ueberschneidungen')">Überschneidungen mit Kollegen</button>
        </div>
        <div id="veranstaltungen" class="tabcontent"></div>
        <div id="uberschneidungen" class="tabcontent"></div>
=======
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen')">Alle Veranstaltungen</button>
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen-sommer')">Veranstaltungen SoSe</button>
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen-winter')">Veranstaltungen WiSe</button>
          <button class="tablinks" onclick="openVList(event, 'veranstaltungen-ueberschneidungen')">Überschneidungen</button>
        </div>
        <div id="veranstaltungen" class="tabcontent"></div>
        <div id="veranstaltungen-sommer" class="tabcontent"></div>
        <div id="veranstaltungen-winter" class="tabcontent"></div>
        <div id="veranstaltungen-ueberschneidungen" class="tabcontent"></div>
>>>>>>> ab989e5088845efbb274432c31021327513143d6
      </div>
    </div>
    <script>
      var profs = <?php echo $profs ?>;
      document.getElementById('profs').innerHTML = listAllDozent(profs);
      document.getElementById('alleVeranstaltungen').innerHTML = listAllVeranstaltung(<?php echo $veranstaltungen ?>)
    </script>
  </body>
</html>
