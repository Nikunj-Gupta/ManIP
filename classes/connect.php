<?php 

class connect
{
//initialize the connection variables
private $username = "root"; // 'root' is the username
private $password = ""; // password is blank 
private $host = "localhost";// host is 'localhost'
private $database = "ipms";//database name is 'seproject_db'
public $mysqli = "";
public $error;

//A function to open the database connection.
function db_open() 
{
$this->
mysqli = new mysqli($this->
host, $this->
username, $this->
password, $this->
database);
//connect_errno returns the error code from last connect call
if($this->
mysqli->
connect_errno != 0) {
// connect_error returns a string description of the last connect error an returns NULL if no error occurred
$this->
error = "Connect Error: " . $this->
mysqli->
connect_error;
return false;
} else {
return $this->
mysqli;
}
}

//A function to close the active database connection.
function db_close() 
{
// thread_id returns the thread_id for the active connection
$thread = $this->
mysqli->
thread_id; //Get active thread
$this->
mysqli->
kill($thread); 		//Kill the active thread
$this->
mysqli->
close();				//Close the connection
return "Connection closed";
}

//A function that Returns the last error.
function get_error() 
{
$error = $this->
error;
unset($this->
error);
return $error;
}
}
?>