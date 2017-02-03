<?php

//check if the file connect.php has been included or not
require_once('classes/connect.php');

class reply extends connect
{

//function that can be used to reply to the ticket
function add_reply($tid, $uid, $message, $admin = false) {

//Add the reply into the datebase
// A prepared statement is a feature used to execute the same (or similar) SQL statements repeatedly with high efficiency
$statement = $this->
mysqli->
prepare("INSERT INTO reply (tid, uid, message) VALUES (?, ?, ?);");
$statement->
bind_param("iis", $tid, $uid, $message);
$addreply = $statement->
execute();

//Check for errors
if(!$addreply) {
$this->
error = "Database error, reply not added." . $this->
mysqli->
error;
return false;
} else {

//Change status to 'Pending' if staff or the admin gives a response, else change to 'Open'
if($admin) {
$status = 'Pending';
} else 
$status = 'Open';

//Update the last reply timestamp for the ticket
$statement = $this->
mysqli->
prepare("UPDATE ticket SET edittstamp = now(), status = '$status' WHERE tid=?;");
$statement->
bind_param("i", $tid);
$updateticket = $statement->
execute();

//Check for errors
if(!$updateticket) {
$this->
error = "Database error." . $this->
mysqli->
error;
return false;
} else {
return "Your reply has been added.";
}
}
}

//Delete a reply
function delete_reply($rid)
{
//Delete the reply from the database.
$statement = $this->
mysqli->
prepare("DELETE FROM reply WHERE rid=$rid;");
$statement->
bind_param("i", $rid);
$deletereply = $statement->
execute();

//Check for errors.
if(!$deletereply) {
$this->
error = "Database error, user not added.";
return false;
} else {
return "Success!";
}
}

//Get all replies to the ticket specified.
function get_ticket_replies($tid)
{
//Get the ticket information and the userlevel and name of the creator of each reply.
$statement = $this->
mysqli->
prepare("SELECT rid, reply.uid, message, UNIX_TIMESTAMP(tstamp) AS tstamp, user.userlevel, userdata.name FROM reply INNER JOIN user ON reply.uid = user.uid INNER JOIN userdata ON reply.uid = userdata.uid WHERE tid=?;");
$statement->
bind_param("s", $tid);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check for errors.
if($numrows 
< 1) {
$this->
error = "No replies";
$statement->
close();
return false;
} elseif($this->
mysqli->
error) {
$this->
error = "Database error, user not added.";
$statement->
close();
return false;
} else {
//Bind the results and store them in a multidimensional array. 
$statement->
bind_result($rid, $uid, $message, $tstamp, $userlevel, $name);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$row = array('rid' =>
$rid, 'uid' =>
$uid, 'message' =>
$message, 'tstamp' =>
$tstamp, 'userlevel' =>
$userlevel, 'name' =>
$name);
$rows[] = $row;
}
$statement->
close();

return $rows;
}
}
}
?>