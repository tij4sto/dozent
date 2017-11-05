function getAllDozenten(arr){
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    console.log(obj.name);
  }
}

function listAllDozent(arr){
  var str = "";
  str += '<label>Dozent(in):</br><select onChange="updateProf()" id="selectDozent" name="top5"><option selected disabled hidden>Prof auswählen</option>';
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    str += '<option value="'+ obj.IDDOZENT + '">' + obj.NAME + '</option>';
  }
  str += '</select></label>';
  return str;
}

function listAllVeranstaltung(arr){
  var str = "";
  str += '<table class="vtable"></tr>';
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    str += '<tr><td value="'+ obj.BEZEICHNUNG + '">' + obj.BEZEICHNUNG + '</td><td style="border: none; padding-left: 5px;"><button onClick=updateVeranstaltung(\'' + obj.IDVERANSTALTUNG + '\') id="'+ obj.IDVERANSTALTUNG +'" class="button"><i class="icon fa fa-arrow-right"></i></button></td></tr>';
  }
  str += '</table>';
  return str;
}

function listFilteredVeranstaltung(arr){
  var str = "";
  str += '<table class="vtable">';
  for(var i = 0; i < arr.length; i++){
    var obj = arr[i];
    str += '<tr><td class="vtd" id="v'+ obj.IDVERANSTALTUNG + '">' + obj.BEZEICHNUNG + '</td><td style="border: none"><button onClick=deleteVeranstaltung(\'' + obj.IDVERANSTALTUNG + '\') class="button"><i class="icon fa fa-close"></i></button></td></tr>';
  }
  str += '</table>';
  return str;
}

function updateProf(){
  var yourSelect = document.getElementById( "selectDozent" );
  var data = yourSelect.options[yourSelect.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText)
              document.getElementById('veranstaltungen').innerHTML = listFilteredVeranstaltung(JSON.parse(this.responseText));
            }
        };
  xmlhttp.open("GET", "profSelected.php?id=" + data, true);
  xmlhttp.send();
}

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

//Von links nach rechts
function updateVeranstaltung(str){
  var yourSelectDozent = document.getElementById( "selectDozent" );
  var data = yourSelectDozent.options[yourSelectDozent.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      updateProf();
    }
  };
  xmlhttp.open("GET", "moveVeranstaltung.php?vid=" + str + "&pid=" + data, true);
  xmlhttp.send();
}

function deleteVeranstaltung(str){
  var yourSelectDozent = document.getElementById( "selectDozent" );
  var data = yourSelectDozent.options[yourSelectDozent.selectedIndex].value;
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              console.log(this.responseText);
              updateProf();
            }
        };
  xmlhttp.open("GET", "deleteVeranstaltung.php?vid=" + str + "&pid=" + data, true);
  xmlhttp.send();
}

function openVList(evt, vname) {
    // Declare all variables
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
