<?php

/**
 * Description of MySQL
 */
require_once 'IDatabase.interface.php';
require_once 'BaseDatabase.class.php';

class MySQL extends BaseDatabase implements IDatabase {
  /* @var $conn myqli */

  private $conn;
  private $host;
  private $user;
  private $password;
  private $dbname;

  public function __construct($server, $username, $password, $dbname) {

    $this->host = $server;

    $this->user = $username;

    $this->password = $password;

    $this->dbname = $dbname;

    $error = $this->connect();
  }

  public function connect() {

    $this->conn = new mysqli($this->host, $this->user, $this->password);

    if ($this->conn->connect_error) {
      return false;
    } else {
      $this->conn->select_db($this->dbname);
    }
    return true;
  }

  public function disconnect() {

    $this->conn->close();
  }

  public function query($statement) {
    //print $statement;
    // need to fix clean func
    return $this->conn->query($statement);
  }

  public function getLastId($table, $tableID = null) {
    if ($tableID == null) {
      $tableID = $table . 'ID';
    }
    $data = $this->query('SELECT MAX(' . $tableID . ') FROM ' . $table)->fetch_array();

    return $data[0];
  }

  public function delete($table, $where) {
    return $this->query("DELETE FROM $table WHERE $where");
  }

  public function insert($table, $data) {

    if (is_array($data)) { // make sure is array
      $arr = $data;

      if (is_array(reset($arr))) {

        // multiple rows

        foreach ($data as $row) {

          $this->insert($table, $row);
        }
      } else {

        // single row

        $cols = '';

        $values = '';

        foreach ($data as $col_name => $value) {

          $cols .= '`'.$col_name . '`,';

          if (is_numeric($value)) {

            $values .= $value . ',';
          } else {

            $values .= "'" . $value . "',";
          }
        }

        $values = rtrim($values, ',');

        $cols = rtrim($cols, ', ');

        $query = "INSERT INTO " . $table . " (" . $cols . ") VALUES (" . $values . ")";

        if ($this->query($query)) {
          return true;
        }

        return false;
      }
    }

    print 'Error: not array';

    return false;
  }

  public function select($table, $cols = '*', $where = '', $order = '', $limit = '') {

    if ($where != '') {
      $where = ' WHERE ' . $where;
    }
    if($order != ''){
      $order = ' ORDER BY ' . $order;
    }
    if($limit != ''){
        $limit = ' LIMIT ' . $limit;
    }
    $statement = 'SELECT ' . $cols . ' FROM ' . $table . $where . $order . $limit;

    $temp = $this->query($statement);

    if ($temp != false) {

      $i = 0;
      $set = false;
      while ($row = $temp->fetch_assoc()) {

        $set[$i++] = $row;
      }

      if (is_array($set) && is_array(reset($set))) {
        
        return $set;
      } else {

        return $set; // can add outer array here
      }
    } else {

      return false;
    }
  }

  public function update($table, $data, $where) {

    if (is_array($data)) { // make sure is array
      $arr = $data;

      if (is_array(reset($arr))) {

        // multiple rows

        foreach ($data as $row) {

          $this->update($table, $row, $where);
        }
      } else {

        // single row

        $values = '';

        foreach ($data as $col_name => $value) {

          $values .= '`' . $col_name . '`' . '=';

          if (is_numeric($value)) {

            $values .= $value . ', ';
          } else {

            $values .= '\'' . $value . '\', ';
          }
        }

        $valquery = rtrim($values, ', ');

        $query = 'UPDATE ' . $table . ' SET ' . $valquery . ' WHERE ' . $where;

        if ($this->query($query)) {

          return true;
        }

        return false;
      }
    }

    return false;
  }

}
