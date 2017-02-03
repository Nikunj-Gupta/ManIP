<?php
  

session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] != "yes" || $_SESSION['login'] == "") {
header("Location: login.php");
exit();
}

include('classes/user.php');

$user = new user();
$user->
db_open();

$uid = $_SESSION['uid'];

if(isset($_POST['name'])) {
$updateuserdata = $user-> update_userdata($uid, $_POST['name'], $_POST['address']);
if(!$updateuserdata) {
$errormsg = htmlspecialchars($user-> get_error(), ENT_QUOTES);
} else {
$successmsg = htmlspecialchars($updateuserdata, ENT_QUOTES);
$successmsg2 = htmlspecialchars($updateuserdata2, ENT_QUOTES);
}
}

if(isset($_POST['oldpassword'])) {
$updateuserdata = $user->
change_password($uid, $_POST['oldpassword'], $_POST['newpassword'], $_POST['confirmpassword']);

if(!$updateuserdata) {
$errormsg = htmlspecialchars($user->
get_error(), ENT_QUOTES);
} else {
$successmsg = htmlspecialchars($updateuserdata, ENT_QUOTES);
}
}

$array = $user->
get_userdata($uid);

if($array) {
$name = htmlspecialchars($array['name'], ENT_QUOTES);
$address = htmlspecialchars($array['address'], ENT_QUOTES);
} else {
$name = NULL;
$address = NULL;
}
?>

<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>
    IPMS - Change Password
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
  <?php if($_SESSION['userlevel'] != 5) echo '
  <li>
      <a href="send.php">
        Submit new Asset
      </a>
  </li>
  '; ?>
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
        Change Password
      </div>
    </div>
    <div id="optionbar">
      <div class="whitemedium" style="text-align: center; padding-top: 35px">
         Change your password.
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
  
  <!--<form id="profileforms" action="profile.php" method="POST">
    <div id="profileform">
      <div style="margin-left: 10px; margin-bottom: 30px" class="greytext">
        Your information.
      </div>
      <div id="profileone">
		<label>
          Name
          <span class="small">
            Your full name
          </span>
      </label>
      <input type="text" name="name" id="name" value="
<?php echo $name ?>
">
  </input>
  </div>
  <div id="profiletwo">
    <label>
      Address
      <span class="small">
        Full address
      </span>
    </label>
    <input type="text" name="address" id="address" value="
<?php echo $address ?>
">
  </input>
  </div>
  
  <input type="submit" class="submitbutton" value="Submit">
  </div>
</form>-->
<form id="profileforms" action="profile.php" method="POST">
  <div id="passwordform">
	<div style="margin-left: 10px; margin-bottom: 30px" class="greytext">
      Change password
  </div>
  <div id="passwordone">
    <label id="pwformlabels">
      Old password
      <span class="small">
        Your old password
      </span>
    </label>
    <input type="password" name="oldpassword" id="oldpassword" required>
  </input>
  <label id="pwformlabels">
    New password
    <span class="small">
      New password
    </span>
  </label>
  <input type="password" name="newpassword" id="newpassword" required>
  </input>
  <label id="pwformlabels">
    Confirm password
    <span class="small">
      Repeat password
    </span>
  </label>
  <input type="password" name="confirmpassword" id="confirmpassword" required>
</input>
</div>
<input type="submit" value="Submit" class="submitbutton">
</button>
</div>
</form>
</div>

</body>
</html>