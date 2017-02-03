<?php

require_once('classes/connect.php');
class ticket extends connect
{
//Add a ticket to the database.
function add_ticket($uid, $message, $subject, $product, $department, $stakeholders, $licensees, $royalty )
{
//Add the ticket.
$query = "INSERT INTO ticket (uid, message, edittstamp, subject, product, department, stakeholders, licensees, royalty) 
VALUES (?, ?, now(), ?, ?, ?,?, ?, ?);" or die($this->
mysqli->
error);
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param("isssssss", $uid, $message, $subject, $product, $department, $stakeholders, $licensees, $royalty);
$addticket = $statement->
execute();

//Check for errors.
if(!$addticket) {
$this->
error = "Database error, user not added.";
return false;
} else {
return $this->
mysqli->
insert_id;
}
}




function add_ticket2($uid, $tid)
{
//Add the ticket.
$query = "INSERT INTO newconfig (uid, tid) 
VALUES (?, ?);" or die($this->
mysqli->
error);
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param("ii", $uid, $tid);
$addticket = $statement->
execute();

//Check for errors.
if(!$addticket) {
$this->
error = "Database error, user not added.";
return false;
} else {
return $this->
mysqli->
insert_id;
}
}




function IPC_check()
{
$query = " SELECT COUNT(*) FROM ticket WHERE userlevel = 3;" or die($this-> mysqli-> error);
$count = $this-> mysqli-> prepare($query);

return $count; 
}

//Get all tickets from the database.
function get_all_tickets($page = 0, $order = "DESC", $category = "edittstamp", $limit = 15, $datefrom = NULL, $dateto = NULL, $status = NULL)
{
//Initialize array to hold tickets.
$rows = array();

//Calculate the offset.
$page = $page * $limit;

//Define query depending on filters are active.
if($datefrom != NULL) {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE UNIX_TIMESTAMP(tstamp) >
= '$datefrom' AND UNIX_TIMESTAMP(tstamp) 
<= '$dateto' ORDER BY $category $order LIMIT $page, $limit;";
} elseif($status != NULL) {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE status = '$status' ORDER BY $category $order LIMIT $page, $limit;";
} else {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket ORDER BY $category $order LIMIT $page, $limit;";
}

//Execute the query.
$statement = $this->
mysqli->
prepare($query);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if any tickets.
if($numrows 
< 1) {
$statement->
close();
$this->
error = "No tickets.";
return false;
} else {
//Bind the results and store in a multidimensional array.
$statement->
bind_result($tid, $tstamp, $edittstamp, $subject, $status);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$row = array('tid' =>
$tid, 'tstamp' =>
$tstamp, 'edittstamp' =>
$edittstamp,
'subject' =>
$subject, 'status' =>
$status);
$rows[] = $row;	
}
$statement->
close();

//Get the total number of tickets, without limit. This is done to be able to make previous and next buttons.
$query = "SELECT COUNT(*) FROM ticket;";
$statement = $this->
mysqli->
prepare($query);
$statement->
execute();
$statement->
store_result();
$this->
totalrows = $statement->
num_rows;

return $rows;
}
}	

function ipc_member_count()
{
	$query = "SELECT COUNT(*) FROM user WHERE userlevel=3;";
	$statement = $this->
	mysqli->query($query);
		
	$result=mysqli_fetch_row($statement);
	//echo $result[0];

	if($result[0] >= 4)
		return true;

	return false;
}

function ipc_member_count2()
{
	$query = "SELECT COUNT(*) FROM user WHERE userlevel=3;";
	$statement = $this->
	mysqli->query($query);
		
	$result=mysqli_fetch_row($statement);
	//echo $result[0];

	if($result[0] >= 3)
		return true;

	return false;
}


function ipc_members()
{
	$query = "SELECT email FROM user WHERE userlevel=3;";
	$statement = $this->
	mysqli->query($query);
		
	
	//echo $statement -> num_rows;
	for ($i=0; $i< ($statement -> num_rows)    ; $i++)
	{
		$result=mysqli_fetch_row($statement);
		echo $result[0] . " , ";
	}	


	return false;
}

//Get all tickets belonging to a user.
function get_user_tickets($uid, $page = 0, $order = "DESC", $category = "edittstamp", $limit = 15, $datefrom = NULL, $dateto = NULL, $status = NULL)
{
//Initialize array to hold tickets.
$rows = array();

//Calculate the offset.
$page = $page * $limit;

//Define query depending on filters are active.
if($datefrom == NULL && $dateto == NULL && $status == NULL) {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE uid=? ORDER BY $category $order LIMIT $page, $limit;";
} elseif($status != NULL) {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE uid=? AND status = '$status' ORDER BY $category $order LIMIT $page, $limit;";
} else {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE uid=? AND UNIX_TIMESTAMP(tstamp) >
= '$datefrom' AND UNIX_TIMESTAMP(tstamp) 
<= '$dateto' ORDER BY $category $order LIMIT $page, $limit;";		
}

//Execute the query.
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param("s", $uid);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if any tickets.
if($numrows 
< 1) {
$statement->
close();
$this->
error = "No tickets.";
return false;
} else {
//Bind the results and store in multidimensional array.
$statement->
bind_result($tid, $tstamp, $edittstamp, $subject, $status);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$row = array('tid' =>
$tid, 'tstamp' =>
$tstamp, 'edittstamp' =>
$edittstamp, 'subject' =>
$subject, 'status' =>
$status);
$rows[] = $row;
}
$statement->
close();

//Get the total number of tickets, without limit. This is done to be able to make previous and next buttons.
$query = "SELECT COUNT(*) FROM ticket WHERE uid=$uid;";
$statement = $this->
mysqli->
prepare($query);
$statement->
execute();
$statement->
store_result();
$this->
totalrows = $statement->
num_rows;

return $rows;
}
}

//Search tickets.
function search_tickets($squery, $page = 0, $admin = false, $uid = false, $limit = 15, $category = 'edittstamp', $order = 'DESC')
{
//Intialize array to store tickets.
$rows = array();
//Prepare the search query.
$squery = '%' . $squery . '%';
//Calculate the offset.
$page = $page * $limit;

//Define queries depending on staff user or not.
if($admin) {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE subject LIKE ? ORDER BY ?, ? LIMIT ?, ?;";
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param('sssii', $squery, $category, $order, $page, $limit);
$rowquery = "SELECT COUNT(*) FROM ticket WHERE subject LIKE '$squery';";
} else {
$query = "SELECT tid, UNIX_TIMESTAMP(tstamp) AS tstamp, UNIX_TIMESTAMP(edittstamp) as edittstamp, subject, status FROM ticket WHERE uid=? && subject LIKE ? ORDER BY ?, ? LIMIT ?, ?;";
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param('isssii', $uid, $squery, $category, $order, $page, $limit);
$rowquery = "SELECT COUNT(*) FROM ticket WHERE uid=$uid AND subject LIKE '$squery';";
}

//Execute the query.
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if any tickets.
if($numrows 
< 1) {
$statement->
close();
$this->
error = "No tickets.";
return false;
} else {
//Bind the results and store in multidimensional array.
$statement->
bind_result($tid, $tstamp, $edittstamp, $subject, $status);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$row = array('tid' =>
$tid, 'tstamp' =>
$tstamp, 'edittstamp' =>
$edittstamp, 'subject' =>
$subject, 'status' =>
$status);
$rows[] = $row;	
}
$statement->
close();

//Get the total number of tickets, without limit. This is done to be able to make previous and next buttons.
$statement = $this->
mysqli->
prepare($rowquery);
$statement->
execute();
$statement->
store_result();
$this->
totalrows = $statement->
num_rows;

return $rows;
}
}

//Return the total number of rows from the get_ticket functions.
function get_total_rows()
{
return $this->
totalrows;
}

//Get a single ticket.
function get_ticket($tid)
{
//Define and execute the query.
$query = "SELECT uid, message, UNIX_TIMESTAMP(tstamp), UNIX_TIMESTAMP(edittstamp), subject, product, department, status, stakeholders, licensees, royalty FROM ticket WHERE tid=?";

$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param("s", $tid);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if ticket exists.
if($numrows != 1) {
$statement->
close();
$this->
error = "No ticket with that ID.";
return false;
} else {
//Bind the results and put in an array.
$statement->
bind_result($uid, $message, $tstamp, $edittstamp, $subject, $product, $department, $status, $stakeholders, $licensees, $royalty);

$statement->
fetch();
$row = array(
	'uid' => $uid, 
	'message' => $message, 
	'tstamp' => $tstamp, 
	'edittstamp' => $edittstamp, 
	'subject' => $subject, 
	'product' => $product, 
	'department' => $department, 
	'status' => $status, 
	'stakeholders' => $stakeholders, 
	'licensees' => $licensees, 
	'royalty' => $royalty);

$statement->
close();

return $row;
}
}

//Close or re-open a ticket.
function close_ticket($tid, $open = NULL,$rejected=NULL,$ordered=NULL,$procured=NULL) {
//Define query base on command.
if($open == NULL AND $rejected==NULL AND $ordered==NULL AND $procured==NULL) {
$query = "UPDATE ticket SET status = 'Approved' WHERE tid=?;";
}
elseif ($open == NULL AND $rejected != NULL) {
$query = "UPDATE ticket SET status = 'Rejected' WHERE tid=?;";
}
elseif($open==NULL and $ordered != NULL) {
$query = "UPDATE ticket SET status = 'Ordered' WHERE tid=?;";
}
elseif($open==NULL and $procured != NULL) {
$query = "UPDATE ticket SET status = 'Procured' WHERE tid=?;";
}
	else {
$query = "UPDATE ticket SET status = 'Open' WHERE tid=?;";
}

//Execute query.
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param("i", $tid);
$editticket = $statement->
execute();

//Check for errors.
if(!$editticket) {
$this->
error = "Database error, reply not added." . $this->
mysqli->
error;
return false;
} else return true;
}

//Get product categories.
function get_products()
{
$rows = array();

$query = "SELECT product FROM product ORDER BY product ASC";

//Execute query.
$statement = $this->
mysqli->
prepare($query);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if any products exist.
if($numrows 
< 1) {
$statement->
close();
$this->
error = "No products.";
return false;
} else {
//Bind the results and return array.
$statement->
bind_result($product);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$rows[] = $product;	
}
$statement->
close();

return $rows;
}
}

//Get department categories.
function get_departments()
{
$rows = array();

$query = "SELECT department FROM department ORDER BY department ASC";

//Execute query.
$statement = $this->
mysqli->
prepare($query) or die ($this->
mysqli->
error);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;

//Check if any departments exist.
if($numrows 
< 1) {
$statement->
close();
$this->
error = "No departments.";
return false;
} else {
//Bind the results and return array.
$statement->
bind_result($department);

for ($i=0; $i 
< $numrows; $i++) { 
$statement->
fetch();
$rows[] = $department;	
}
$statement->
close();

return $rows;
}
}
}
?>