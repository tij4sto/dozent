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
}
?>
