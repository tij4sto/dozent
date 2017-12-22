function getAllDozenten(arr){
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    console.log(obj.name);
  }
}

//Sendet alle Dozenten als HTML Select Feld zurück und ruft update() auf, wenn sich der Professor ändert.
function listAllDozent(arr){
  var str = "";
  str += '<label>Dozent(in):</br></label><select onChange="update()" id="selectDozent" class="profselect" name="top5"><option selected disabled hidden>Prof auswählen</option>';
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    str += '<option class="profoption" value="'+ obj.IDDOZENT + '">' + obj.NAME + '</option>';
  }
  str += '</select>';
  return str;
}

//Sendet alle verfügbaren Veranstaltungen zurück und fügt sie als Table in DIV ein.
function listAllVeranstaltung(arr){
  var str = "";
  str += '<div class="veranstaltungen"><table class="vtable"></tr>';
  str += '<tr><th class="thBez">Bezeichnung</th><th class="thSWS">SWS</th></tr>'
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    str += '<tr><td class="tdAlle" value="'+ obj.BEZEICHNUNG + '">' + obj.BEZEICHNUNG
    + '</td><td class="tdSWS">'+obj.SWS+'</td><td class="tdPfeil" style="border: none; padding-left: 5px;"><button onClick=updateVeranstaltung(\''
    + obj.IDVERANSTALTUNG + '\') id="'+ obj.IDVERANSTALTUNG +'" class="button"><i class="icon fa fa-arrow-right"></i></button></td></tr>';
  }
  str += '</table>';
  return str;
}

//Sendet alle Veranstaltungen als Table zurück, in die der ausgewählte Professor eingetragen ist
function listFilteredVeranstaltung(arr, zustand){
  var str = "";
  str += '<div class="veranstaltungen"><table class="vtable">';
  if(zustand == 'sommer'){
    for(var i = 0; i < arr.length; i++){
      var obj = arr[i];
      if(obj.SOMMER == 1){
        str += '<tr><td class="tdBezeichnung" id="v'+ obj.IDVERANSTALTUNG + '">' + obj.BEZEICHNUNG
        + '</td><td class="tdAnteil">'+obj.ANTEIL_PROZENT+'</td><td class="tdButtons" style="border: none"><button onClick=editVeranstaltung(\''
        + obj.IDVERANSTALTUNG + '\') class="button"><i class="icon fa fa-bars"></i></button><button onClick=deleteVeranstaltung(\''
        + obj.IDVERANSTALTUNG + '\') class="button"><i class="icon fa fa-close"></i></button></td></tr>';
      }
    }
  }

  if(zustand == 'winter'){
    for(var i = 0; i < arr.length; i++){
      var obj = arr[i];
      if(obj.HAEUFIGKEIT_PA == 2 || obj.SOMMER == 0){
        str += '<tr><td class="tdBezeichnung" id="v'+ obj.IDVERANSTALTUNG + '">' + obj.BEZEICHNUNG
        + '</td><td class="tdAnteil">'+obj.ANTEIL_PROZENT+'</td><td class="tdButtons" style="border: none"><button onClick=editVeranstaltung(\''
        + obj.IDVERANSTALTUNG + '\') class="button"><i class="icon fa fa-bars"></i></button><button onClick=deleteVeranstaltung(\'' + obj.IDVERANSTALTUNG
        + '\') class="button"><i class="icon fa fa-close"></i></button></td></tr>';
      }
    }
  }

  if(zustand == 'ueberschneidungen'){
    var t = "null"
    for(var i = 0; i < arr.length; i++){
      var obj = arr[i];
      if(t != "null"){
        if(obj.BEZEICHNUNG != t){
          str += '<tr><td colspan="3" style="width:100%"><hr style="border: solid #44729A 2px"></td></tr>';
        }
      }
      str += '<tr><td class="vtd" id="v'+ obj.BEZEICHNUNG + '">' + obj.BEZEICHNUNG
      + '</td><td style="text-align: center">'+ obj.NAME +'</td><td style="text-align: right">' + obj.ANTEIL_PROZENT +'</td></tr>';
      t = obj.BEZEICHNUNG;
    }
  }

  if(zustand == 'alle'){
    for(var i = 0; i < arr.length; i++){
      var obj = arr[i];
        str += '<tr><td class="tdBezeichnung" id="v'+ obj.IDVERANSTALTUNG + '">' + obj.BEZEICHNUNG
        + '</td><td class="tdAnteil">'+obj.ANTEIL_PROZENT+'</td><td class="tdButtons" style="border: none"><button onClick=editVeranstaltung(\''
        + obj.IDVERANSTALTUNG + '\') class="button"><i class="icon fa fa-bars"></i></button><button onClick=deleteVeranstaltung(\'' + obj.IDVERANSTALTUNG
        + '\') class="button"><i class="icon fa fa-close"></i></button></td></tr>';
      }
  }

  str += '</table></div>';
  zustand = "";
  return str;
}

//Wird gecallt, wenn sich der ausgewählte Professor geändert hat und nimmt Änderungen an den Tabellen vor
function update(){
  var yourSelect = document.getElementById( "selectDozent" );
  var data = yourSelect.options[yourSelect.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('veranstaltungen').innerHTML = listFilteredVeranstaltung(JSON.parse(this.responseText), 'alle');
              document.getElementById('veranstaltungen-sommer').innerHTML = listFilteredVeranstaltung(JSON.parse(this.responseText), 'sommer');
              document.getElementById('veranstaltungen-winter').innerHTML = listFilteredVeranstaltung(JSON.parse(this.responseText), 'winter');
            };
        };
  xmlhttp.open("GET", "profSelected.php?id=" + data, true);
  xmlhttp.send();


  var xmlhttp2 = new XMLHttpRequest();
  xmlhttp2.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById('veranstaltungen-ueberschneidungen').innerHTML = listFilteredVeranstaltung(JSON.parse(this.responseText), 'ueberschneidungen');
            };
  };
  xmlhttp2.open("GET", "getUeberschneidungen.php?id=" + data, true);
  xmlhttp2.send();
}

//Wird gecallt, wenn innerhalb der Suche nach einer Veranstaltung gesucht wird. Verändert die verfügbare Tabelle der Veranstaltungen
function updateAlleVeranstaltungen(str){
  var xmlhttp = new XMLHttpRequest();
  console.log(str);
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      document.getElementById('alleVeranstaltungen').innerHTML = listAllVeranstaltung(JSON.parse(this.responseText));
    }
  };
  xmlhttp.open("GET", "sucheVeranstaltung.php?key="+str, true);
  xmlhttp.send();
}

//Wird gecallt, wenn eine Veranstaltung aus dem Gesamtpool von einem Professor ausgewählt wird. Verändert die Tabelle auf der rechten Seite.
function updateVeranstaltung(str){
  var yourSelectDozent = document.getElementById( "selectDozent" );
  var data = yourSelectDozent.options[yourSelectDozent.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      update();
    }
  };
  xmlhttp.open("GET", "moveVeranstaltung.php?vid=" + str + "&pid=" + data, true);
  xmlhttp.send();
}

//Wird gecallt, wenn ein Professor eine Veranstaltung aus seiner Tabelle löscht.
function deleteVeranstaltung(str){
  var yourSelectDozent = document.getElementById( "selectDozent" );
  var data = yourSelectDozent.options[yourSelectDozent.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              update();
            }
        };
  xmlhttp.open("GET", "deleteVeranstaltung.php?vid=" + str + "&pid=" + data, true);
  xmlhttp.send();
}

function editVeranstaltung(vid){
  var modal = document.getElementById("myModal");
  modal.innerHTML = createInnerHTMLForModal(vid);
  modal.style.display = "inline";
}

function createInnerHTMLForModal(vid){
  var str = '<!-- Modal content --><div class="modal-content"><span onClick="hideModal()" class="close">&times;</span><h3>Anteil ändern</h3>';
  str += 'Neuer Anteil für SWS für die Veranstaltung: </br><input id="sws" class="modal-input" type="number" name="anteil"></br><p id="hinweis"></p><button style="background-color:#9fdf9f" onclick="saveEdit(\''
  + vid + '\')" class="modal-btn">Speichern</button>';
  str += '<button class="modal-btn" onclick="hideModal()">Abbrechen</button>';
  str += '</div>';
  return str;
}

function saveEdit(vid){
  console.log(document.getElementById('sws').value);
  var yourSelectDozent = document.getElementById( "selectDozent" );
  var prof = yourSelectDozent.options[yourSelectDozent.selectedIndex].value;
  var v = vid;
  var sws = document.getElementById('sws').value;

  if(sws >= 0){
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
                if(this.responseText == "1"){
                  hideModal();
                  update();
                }

                else{
                  document.getElementById('hinweis').style.display = 'block';
                  document.getElementById('hinweis').innerHTML = this.responseText;
                }
              }
          };
    xmlhttp.open("GET", "editSwsInVeranstaltung.php?vid=" + v + "&pid=" + prof + "&sws=" + sws , true);
    xmlhttp.send();
  }

  else{
    document.getElementById('hinweis').style.display = 'block';
    document.getElementById('hinweis').innerHTML = "Es sind nur positive Werte als Eingabe erlaubt.";
  }
}

function hideModal(){
  var modal = document.getElementById("myModal");
  modal.style.display = "none";
}

//Wird aufgerufen, wenn die verschiedenen Listen auf der rechten Seite angezeigt werden sollen.
function openVList(evt, vname) {
    var i, tabcontent, tablinks;

    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    document.getElementById(vname).style.display = "block";
    evt.currentTarget.className += " active";
}
