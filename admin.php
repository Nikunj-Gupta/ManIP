<?php

session_start();
if(!isset($_SESSION['login']) || !$_SESSION['login'] == "yes" || $_SESSION['userlevel'] != 3) {
header("Location: index.php");
exit();
}

include('classes/ticket.php');
include('classes/support.php');

$ticket = new ticket();
$ticket->
db_open();
$count = $ticket -> IPC_Check();




?>

<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  
  <title>
        IPMS - Review Requests
  </title>
  <link rel="stylesheet" href="styles/stylesheet.css" type="text/css" media="screen">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
  </head>
  <body>
	<br>
  <center>
    <a href="index.php" style="text-decoration: none;" >
      <span style="color:black">
        <b>
          <font size="6">
            IP Asset Management System
          </font>
        </b>
      </span>
    </a>
  </center>
  
  <nav>
    <ul id="navigation">
      <li>
        <a href="index.php">
          View IP Assets
        </a>
      </li>
    </li>
  <li>
      <a href="send.php">
        Submit new Asset
      </a>
  </li>
  <li>
    <a href="profile.php">
      Change Password
    </a>
  </li>

 <li>
    <a href="logout.php">
      Log Out
    </a>
  </li>
  </ul>
  </nav>
  
  
 
</body>
</html>