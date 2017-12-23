<?php
class Database
{

  function __construct() {
    $this->servername = "127.0.0.3";
    $this->username = "db499406_3";
    $this->password = "UniWeb1989";
    $this->dbname = "db499406_3";
  }

  function getAllDozent(){
    $conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
    $conn->set_charset("utf8");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM DOZENT";
    $result = $conn->query($sql) or die($conn->error);

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
    $sql = "SELECT * FROM VERANSTALTUNG";
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
    FROM ZURODNUNG_DOZENT_VERANSTALTUNG, VERANSTALTUNG
    where ZURODNUNG_DOZENT_VERANSTALTUNG.IDDOZENT = $id
    AND ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG = VERANSTALTUNG.IDVERANSTALTUNG";
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
    $sql = "INSERT INTO ZURODNUNG_DOZENT_VERANSTALTUNG(IDVERANSTALTUNG, IDDOZENT, ANTEIL_PROZENT) VALUES ($vid, $pid, 1)";

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
    $sql = "DELETE FROM ZURODNUNG_DOZENT_VERANSTALTUNG WHERE IDVERANSTALTUNG=$vid AND IDDOZENT=$pid";

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
    FROM VERANSTALTUNG
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

    $sql = "SELECT BEZEICHNUNG, NAME, ANTEIL_PROZENT FROM `ZURODNUNG_DOZENT_VERANSTALTUNG`
            INNER JOIN `VERANSTALTUNG` ON ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG = VERANSTALTUNG.IDVERANSTALTUNG INNER JOIN `dozent` ON ZURODNUNG_DOZENT_VERANSTALTUNG.IDDOZENT = dozent.IDDOZENT
            WHERE ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG IN(
              SELECT IDVERANSTALTUNG FROM (
                SELECT IDVERANSTALTUNG, SUM(ANTEIL_PROZENT) AS 'anteilSumme' FROM `ZURODNUNG_DOZENT_VERANSTALTUNG` WHERE ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG IN (
                SELECT ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG FROM `ZURODNUNG_DOZENT_VERANSTALTUNG` WHERE IDDOZENT = $pid
              )  GROUP BY ZURODNUNG_DOZENT_VERANSTALTUNG.IDVERANSTALTUNG
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

    $sql = "SELECT (SWS * FAKTOR_DOPPELUNG) AS ERG FROM VERANSTALTUNG where IDVERANSTALTUNG = $vid";

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
        $sql = "UPDATE ZURODNUNG_DOZENT_VERANSTALTUNG
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
