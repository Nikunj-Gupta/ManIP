<?php

session_start();
if(!isset($_SESSION['login']) || !$_SESSION['login'] == "yes" || $_SESSION['userlevel'] != 4) {
header("Location: index.php");
exit();
}

include('classes/ticket.php');
include('classes/support.php');

$ticket = new ticket();
$ticket->
db_open();

//$ticket -> ipc_members();



if(isset($_POST['name'])) {
$user = new user();
$user->
db_open();
$adduser = $user->
add_user($_POST['name'], $_POST['regemail'], $_POST['confirmemail'], $_POST['regpassword'], $_POST['confirmpassword'], 3);
if($adduser) {
$successmsg = "New user created.";
} else {
$errormsg = $user-> get_error;
}
}
$departments = $ticket-> get_departments();
$products = $ticket->
get_products();
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
<?php if($_SESSION['userlevel'] == 4) echo '
<li>
<a href="ipc.php">
Admin Panel
</a>
</li>
'; ?>
 <li>
    <a href="logout.php">
      Log Out
    </a>
  </li>
  </ul>
  </nav>
   <div class="main">
    <div id="spacer">
      <div class="blacksmall">
        Admin Area
      </div>
    </div>
    <div id="optionbar">
      <div class="whitemedium" style="text-align: center; padding-top: 35px">
        Add a new IP Committee Member
      </div>
    </div>
    
    <?php 
if(isset($successmsg)) {
echo '
<div class="notification success">
' . $successmsg . '
</div>
';
} if(isset($errormsg)) {
echo '
<div class="notification error">
' . $errormsg . '
</div>
';
}
?>
  
  <form id="profileforms" action="ipc.php" method="POST">
    <div id="profileform" style="height: 440px;">
      <div style="margin-left: 10px; margin-bottom: 30px" class="greytext">
        Add New IPC Member 
      </div>
      <label style="width: 150px;">
        Full Name
      </label>
      <input style="float: left; margin-left: 20px; width: 250px;" type="text" name="name" id="name"placeholder="Enter full name" required>
    </input>
    <br />
    <label style="width: 150px;">
      E-Mail
    </label>
    <input style="float: left; margin-left: 20px; width: 250px;" type="email" name="regemail" id="regemail"placeholder="Enter Email ID" required>
  </input>
  <label style="width: 150px;">
    Confirm E-mail
  </label>
  <input style="float: left; margin-left: 20px; width: 250px;" type="email" name="confirmemail" id="confirmemail" placeholder="Confirm Email ID" required>
  </input>
  <br />
  <label style="width: 150px;">
    Password
  </label>
  <input style="float: left; margin-left: 20px; width: 250px;" type="password" name="regpassword"placeholder="Enter password" id="regpassword" required>
</input>
<label style="width: 150px;">
  Confirm Password
</label>
<input style="float: left; margin-left: 20px; width: 250px;" type="password" name="confirmpassword" id="confirmpassword" placeholder="Confirm Password" required>
</input>
<input style="clear: both;" type="submit" value="Submit">


<label style="width: 150px;">
Current Members: 
</label>

<?php
$ticket-> ipc_members();
?> 




</form>
</div>
</form>
</form>
</div>
</form>
</div>
</div>

</div>












  </body>
</html>