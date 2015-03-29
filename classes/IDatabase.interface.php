<?php
/*
 * This interface defines the necessary functions that are required to 
 * add a new database technology type (eg. MySQL, SQLite, ect...)
 */

interface IDatabase {

    /*
     * The connect function defines how to connect to the DB server
     */
    public function connect();

    /*
     * The disconnect function defines how to disconnect from the server
     */
    public function disconnect();
    
    /*
     * The query function is the function where all SQL queries are routed through.
     *   This allows the developer to extend this function and add logging ect every time a query is sent to the server.
     */
    public function query($sqlStatement);
    
    /*
     * GetLastID is used to return the highest numeric value in the column tableID in the table specified
     * This is used to return the last inserted ID for use in your application.
     */
    public function getLastId($table, $tableID = null);

    /*
     * The function Select allows the developer to select one or more records from from the database.
     */
    public function select($table, $cols = '*', $where = '');
    
    public function insert($table, $data);

    public function delete($table, $where);

    public function update($table, $newData, $where);

}

