# PHP-Multi-SQL

This PHP script will allow you to use a common interface for popular database types, making it simple to change SQL database variants during the development of your projects.

## Supported Variants
* MySQL
* SQLite
* (Post a suggestion issue to request another adapter be added)

## API (How to use)
Every database variant implements the IDatabase interface, so they all follow a simple API.
Each variant takes different constructor parameters to initialize.

###Initialize
```php
require_once('PHP-Multi-SQL/classes/MySQL.class.php');
$db = new MySQL($db_info['host'], $db_info['user'], $db_info['password'], $db_info['db_name']);

require_once('PHP-Multi-SQL/classes/SQLite.class.php');
$db = new SQLite($filepath);
```

###Connect / Disconnect
After you have your initalized database object you can connect or disconnect. No parameters are needed, as they are passed with the constructor.
```php
$db->connect();
// do queries
$db->disconnect();
```

The next functions are the CRUD Functions
###Select
The Select function automatically builds your select query, just pass the table name as a string and by default it selects all rows of the table. (SELECT * FROM tblName) However, you can optionally include the parameters for filtering columns, or results. It will return an array of results, with associative arrays for the rows, with the column names as keys.
```php
//select($table, $cols = '*', $where = '');
$results = $db->select('people','firstname,occupation', 'age >= 21 AND gender = "male"');
```
###Insert
To Insert, pass the table name, and an associative array of the data with the column names as keys. (['col_name'=>value]). It will also accept a multidimensional array of the arrays with the format above and insert multiple rows. It will return true or false depending on success.
```php
// insert($table, $data);
$dataArray = array('firstname'=>"John", 'lastname'=>'doe', 'age'=>44, 'gender'=>"male");
$wasInsertSuccessful = $db->insert('people', $dataArray);
```
###Update
To Update, pass the table name, an associative array of columns to data, and then a where string to limit who you update. It will return true or false.
```php
  //update($table, $data, $where);
  $wasUpdateSuccess = $db->update('person',array('age'=>45),'firstname="John"');
```
###Delete
To Delete, pass the table name, and a where string to limit what rows you delete. It will return a delete result, allowing you to determine the number of effected rows etc.
```php
  //delete($table, $where);
  $deleteResult = $db->delete('person','firstname="John"');
```
###Custom Query
You can also run custom sql queries using the query method:
```php
  $result = $db->query('SELECT * FROM people LEFT JOIN occupations ON people.id=occupations.person_id');
```
###Get Last ID
And you can retrieve the last inserted row id using the getLastID method
```php
   $id = $db->getLastId();
```
## Todo:
* Add procedural support
* Add more database types
