<?php

if(isset($_POST['email']) && isset($_POST['password'])) {
include('classes/user.php');

//create a new user
$user = new user();
$user->
db_open();

if($user->
take_login($_POST['email'], $_POST['password'], $_POST['code'])) {
header('Location: index.php');
exit();
} else {
$errormsg = $user->
get_error();
}
}

//Check for POST data from register form.
if(isset($_POST['name']) && isset($_POST['regemail']) && isset($_POST['confirmemail']) && isset($_POST['regpassword']) && isset($_POST['confirmpassword']))
{
include('classes/user.php');

$user = new user();
$user->
db_open();

if($user->
add_user($_POST['name'], $_POST['regemail'], $_POST['confirmemail'], $_POST['regpassword'], $_POST['confirmpassword'])) {
  include('mail.php');

$successmsg = "Account Succesfully Created, please log in.";
} else {
$errormsg = $user->
get_error();
}
}
?>

<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>
    IPMS - Login
  </title>
  <link rel="stylesheet" href="styles/stylesheet.css" type="text/css" media="screen">
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800|Open+Sans+Condensed:300,700' rel='stylesheet' type='text/css'>
  <script src="http://code.jquery.com/jquery-latest.js">
  </script>
  
  <script>
    $(document).ready(function() {
      $(".loginbuttons.loginbutton").click( function() {
        $('.loginforms.registerform').fadeOut('slow', function() {
          $('.loginbuttons.loginbutton').css("color", "white");
          $('.loginbuttons.loginbutton').css("background-color", "#49729b");
          $('.loginbuttons.registerbutton').css("color", "black");
          $('.loginbuttons.registerbutton').css("background-color", "transparent");
          $('.loginforms.loginform').fadeIn('slow', function() {
          }
                                           );
        }
                                             );
      }
                                          );
      
      $(".loginbuttons.registerbutton").click( function() {
        $('.loginforms.loginform').fadeOut('slow', function() {
          $('.loginbuttons.loginbutton').css("color", "black");
          $('.loginbuttons.registerbutton').css("background-color", "#49729b");
          $('.loginbuttons.registerbutton').css("color", "white");
          $('.loginbuttons.loginbutton').css("background-color", "transparent");
          $('.loginforms.registerform').fadeIn('slow', function() {
          }
                                              );
        }
                                          );
      }
                                             );
    }
                     );
  </script>
  </head>
  <body>
	<br>
  <center>
    <a href="index.php" style="text-decoration: none;" >
      <span style="color:black">
        <b>
          <font size="6">
        
          </font>
        </b>
      </span>
    </a>
  </center>
  </ul>
</nav>


<div class="main mainlogin">
  <div id="loginbar">
    <div class="loginbuttons loginbutton">
      Login
    </div>
    <div class="loginbuttons registerbutton">
      Register
    </div>
  </div>
  
  <?php 
if(isset($errormsg)) {
echo '
<div class="notification error">
' . htmlspecialchars($errormsg, ENT_QUOTES) . '
</div>
';
} else if(isset($successmsg)) {
echo '
<div class="notification success">
' . htmlspecialchars($successmsg, ENT_QUOTES) . '
</div>
';
}
?>
  
  <div class="loginforms loginform">
	<br />
  <div class="greymedium">
    Log in to use the IPMS
  </div>
  <br />
  <div class="greysmall">
    Log in with your registered details
  </div>
  <div class="blueseperator">
  </div>
  <form id="profileforms" action="login.php" method="POST">
	<div id="loginformspec">
      <br />
      <label>
        E-mail  
      </label>
      <input type="email" name="email" placeholder="Enter your Registered Email ID" id="email" required>
  </input>
  <br />
  <label>
    Password
  </label>
  <input type="password" name="password" placeholder = "Enter your Registered Password" id="password" required>
  </input>
  
<br />
  <label>
    Code
  </label>
  <input type="code" name="code" placeholder = "(Required Once)" id="code" >
  </input>
  
  </div>
  <input style="clear: both;" type="submit" value="Log In">
</input>





</form>
</form>
</div>

<br />

<div class="loginforms registerform">
  <div class="greymedium">
    Register a new user
  </div>
  <br />
  <div class="greysmall">
    Please fill in the required information to create a new user
  </div>
  <div class="blueseperator">
  </div>
  <form id="profileforms" action="login.php" method="POST">
	<div id="loginformspec">
      <br />
      <label>
        Full Name
      </label>
      <input type="text" name="name" placeholder="Enter your full name" id="name" required>
  </input>
  <br />
  <label>
    E-Mail
  </label>
  <input type="email" name="regemail" placeholder="Enter Your Email ID" id="regemail" required>
  </input>
  <label>
    Confirm E-mail
  </label>
  <input type="email" name="confirmemail" placeholder="Confirm your Email ID" id="confirmemail" required>
</input>
<br />
<label>
  Password
</label>
<input type="password" name="regpassword"  placeholder="Enter your Password" id="regpassword" required>
</input>
<label>
  Confirm Password
</label>
<input type="password" name="confirmpassword" placeholder="Confirm your Email ID" id="confirmpassword" required>
</input>
</div>
<input style="clear: both;" type="submit" value="Register">
</input>
</form>
</form>
</div>
</div>
</body>
</html>