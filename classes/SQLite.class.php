<?php
/**
 * SQLite is the database class that operates SQLite database files
 * The IDatabase interface allows csv file database's and mysql ect to be 
 * easily added.
 * @author Patrick
 */
require_once 'classes/IDatabase.php';
class SQLite extends BaseDatabase implements IDatabase {
  
  private $sqlite;
  private $file;
  
  function __construct($filepath)
  {
    $this->file = $filepath;
    $this->connect();    
  }
  
  function connect(){
    $this->sqlite = new SQLite3($this->file);
  }
  
  function getLastId(){
    return $this->sqlite->lastInsertRowId();
  }
  
  function query($query){
    return $this->sqlite->query($query);
  }
  
  function disconnect() {
    $this->sqlite->close();
  }
  
  function select($table, $cols = '*', $where= ''){
    if($where != ''){
      $where = ' WHERE '.$where;
    }
    $temp = $this->sqlite->query('SELECT '.$cols.' FROM '.$table.$where);
    $i = 0;
    $set = array();
    while($row = $temp->fetchArray(SQLITE3_ASSOC)){
      $set[$i++] = $row; 
    }
    if(is_array($set) && is_array(reset($set)))
    {
      return $set;
    }else{
      return array($set);
    }
  }
  
  public function insert($table, $data){
    if(is_array($data)){ // make sure is array
      $arr = $data;
      if(is_array(reset($arr))){
        // multiple rows
        foreach($data as $row){
          $this->insert($table,$row);
        }
      }else{
        // single row
        $cols = '';
        $values = '';
        foreach($data as $col_name => $value){
          $cols .= $col_name. ',';
          if(is_numeric($value)){
            $values .= $value . ',';
          }else{
            $values .= '\''.$value . '\',';
          }
        }
        $values = rtrim($values,',');
        $cols = rtrim($cols,', ');
        $query = 'INSERT INTO '.$table.' ('.$cols.') VALUES('.$values.');';
        print $query;
        return $this->sqlite->exec($query);
      }
    }
    return false;
  }
  
  public function delete($table, $where){
    return $this->sqlite->exec('DELETE FROM '.$table.' WHERE '.$where);
  }
  
  public function update($table, $newData, $where = ''){
    return false;
  }
}
