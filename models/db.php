<?php
class Database
{

  function __construct() {
    $this->servername = "localhost";
    $this->username = "Jannis";
    $this->password = "123";
    $this->dbname = "curriculum_planung";
  }

  function getAllDozent(){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM dozent";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();
    $conn->close();
    return json_encode($rows);
  }

  function getAllVeranstaltung(){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM veranstaltung";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();
    $conn->close();
    return json_encode($rows);
  }

  function getVeranstaltungenByID($id){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT *
    FROM zuordnung_dozent_veranstaltung, veranstaltung
    where zuordnung_dozent_veranstaltung.IDDOZENT = $id
    AND zuordnung_dozent_veranstaltung.IDVERANSTALTUNG = veranstaltung.IDVERANSTALTUNG";
    $result = $conn->query($sql);

    if($result->num_rows === 0)
    {
        echo '{"BEZEICHNUNG":"Keine Veranstaltungen"}';
    }

    else{
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
      $conn->close();
      return json_encode($rows);
    }
  }

  function insertVeranstaltungByIDandProf($vid, $pid){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "INSERT INTO zuordnung_dozent_veranstaltung(IDVERANSTALTUNG, IDDOZENT, ANTEIL_PROZENT) VALUES ($vid, $pid, 1)";

    if ($conn->query($sql) === TRUE) {

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }

  function deleteVeranstaltungByIDs($vid, $pid){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "DELETE FROM zuordnung_dozent_veranstaltung WHERE IDVERANSTALTUNG=$vid AND IDDOZENT=$pid";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
  }

  function sucheVeranstaltung($pattern){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT *
    FROM veranstaltung
    where BEZEICHNUNG LIKE '%{$pattern}%'";

    $result = $conn->query($sql);

    if($result->num_rows === 0)
    {
        echo '{"BEZEICHNUNG":"Keine Veranstaltungen"}';
    }

    else{
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
      $conn->close();
      return json_encode($rows);
    }
  }

  function getUeberschneidungenById($pid){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT BEZEICHNUNG, NAME, ANTEIL_PROZENT FROM `zuordnung_dozent_veranstaltung`
            INNER JOIN `veranstaltung` ON zuordnung_dozent_veranstaltung.IDVERANSTALTUNG = veranstaltung.IDVERANSTALTUNG INNER JOIN `dozent` ON zuordnung_dozent_veranstaltung.IDDOZENT = dozent.IDDOZENT
            WHERE zuordnung_dozent_veranstaltung.IDVERANSTALTUNG IN(
              SELECT IDVERANSTALTUNG FROM (
                SELECT IDVERANSTALTUNG, SUM(ANTEIL_PROZENT) AS 'anteilSumme' FROM `zuordnung_dozent_veranstaltung` WHERE zuordnung_dozent_veranstaltung.IDVERANSTALTUNG IN (
                SELECT zuordnung_dozent_veranstaltung.IDVERANSTALTUNG FROM `zuordnung_dozent_veranstaltung` WHERE IDDOZENT = $pid
              )  GROUP BY zuordnung_dozent_veranstaltung.IDVERANSTALTUNG
                ) t WHERE anteilSumme > 1)";

    $result = $conn->query($sql);

    if($result->num_rows === 0)
    {
        echo '{"BEZEICHNUNG":"Keine Veranstaltungen"}';
    }

    else{
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
      $conn->close();
      return json_encode($rows);
    }
  }

  function getAnteilVeranstaltung($sws, $vid){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT (SWS * FAKTOR_DOPPELUNG) AS ERG FROM veranstaltung where IDVERANSTALTUNG = $vid";

    $result = $conn->query($sql);

    if($result->num_rows === 0)
    {
        echo '{"BEZEICHNUNG":"Keine Veranstaltungen"}';
    }

    else{
      while($row = $result->fetch_assoc()) {
        $rows[] = $row;
      }
      $result->free();
      $conn->close();
      return $rows[0]["ERG"];
    }
  }

  function updateAnteilAnVeranstaltung($vid, $pid, $sws){
      $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
      $conn->set_charset("utf8");
      if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
      }

      $t = $this->getAnteilVeranstaltung($sws, $vid);
      $t = intval($t);

      $neuer_Anteil = $sws / $t;

      if($neuer_Anteil <= 1){
        $sql = "UPDATE zuordnung_dozent_veranstaltung
        SET ANTEIL_PROZENT = $neuer_Anteil
        where IDVERANSTALTUNG = $vid AND IDDOZENT = $pid";

        $result = $conn->query($sql);
        return "1";
      }


      if($neuer_Anteil > 1){
        $str = "Dieses Fach bietet nur $t SWS. Bitte ändern Sie Ihre Eingabe";
        return $str;
      }


      else {
        return "Falsche Eingabe";
      }
  }
}
?>
