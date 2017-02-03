<?php


session_start();
if(!isset($_SESSION['login']) || $_SESSION['login'] != "yes" || $_SESSION['login'] == "") {
header("Location: login.php");
exit();
}

include('classes/ticket.php');

$ticket = new ticket();
$ticket->
db_open();

$departments = $ticket->
get_departments();
$products = $ticket->
get_products();

if(isset($_POST['department'])) 
{
$product = $_POST['product'];
$department = $_POST['department'];
$subject = $_POST['subject'];
$message = $_POST['message'];
$stakeholders = $_POST['stakeholders'];
$licensees = $_POST['licensees'];
$royalty = $_POST['royalty'];

preg_match_all('!\d+!', $royalty, $matches);
$newflag = 0;
    if(array_sum($matches[0])==100 AND strpos($stakeholders, 'IIITB') !== false AND $matches[0][0]>=20)
    {
      $newflag = 1;
      $addticket = $ticket-> add_ticket($_SESSION['uid'], $message, $subject, $product, $department, $stakeholders, $licensees, $royalty);
    }
    if($newflag == 0) {
$errormsg = "Note: You have entered wrong Royalty Percentages... They DO NOT Add up to 100!!!   
Also check whether you have added IIITB as a FIRST stakeholder or not (with atleast 20% Royalty)!!!";
} 
else {
$addticket = $ticket-> add_ticket2($_SESSION['uid'], 100);
header('Location: index.php');
}
}




?>

<!DOCTYPE html>
<html>
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>
    IPMS - Submit new Request
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
  <?php if ($_SESSION['userlevel'] == 4) echo '
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
        New Request for Review
      </div>
    </div>
    <div id="optionbar">
      <div id="submitselect">
		<form action="send.php" method="POST">
          <span class="whitemedium">
            Department:
          </span>
          <select class="submitboxes" name="department">
            <?php
foreach ($departments as $department) {
echo "
<option>
$department
</option>
";
}
?>
          </select>
          
          <span class="whitemedium">
            Issue:
          </span>
          <select class="submitboxes" name="product">
            <?php
foreach ($products as $product) {
echo "
<option>
$product
</option>
";
}
?>
          </select>
      </div>
  </div>
  
  <?php 

if(isset($errormsg)) {
echo '
<div class="notification error">
' . htmlspecialchars($errormsg, ENT_QUOTES) . '
</div>
';
}
?>
  
  <table id="submittable">
	<tr>
      <td class="submitlabel">
        <span class="blackmedium">
          Title:
        </span>
      </td>
      <td class="submitfield">
        <input type="text" name="subject" class="submitinput" style="width: 50%;" required>
      </input>
  </td>
  </tr>
  
  <tr>
    <td class="submitlabel">
      <span class="blackmedium">
        Details:
      </span>
    </td>
	<td class="submitfield">
      <textarea name="message" class="submitinput" required>
      </textarea>
      </td>
  </tr>


  <tr>
      <td class="submitlabel">
        <span class="blackmedium">
          Stakeholders:
        </span>
      </td>
      <td class="submitfield">
        <input type="text" name="stakeholders" class="submitinput" style="width: 30%;margin-left:35px;" required>
      </input>
      <span class="blackmedium">
          Royalty of each Stakeholder(%):         
        </span>
        <input type="text" name="royalty" class="submitinput" style="width: 25%; margin-left:15px;" required>
      </input>
  </td>
  </tr>

  <tr>
      <td class="submitlabel">
        <span class="blackmedium">
          All-Licensees:
        </span>
      </td>
      <td class="submitfield">
        <input type="text" name="licensees" class="submitinput" style="width: 30%;margin-left:35px;" >
      </input>
  </td>
  </tr>
<!--
   <tr>
      <td class="submitlabel">
        <span class="blackmedium">
          Royalty(%):         
        </span>
      </td>
      <td class="submitfield">
        <input type="text" name="royalty" class="submitinput" style="width: 30%;margin-left:35px;" required>
      </input>
  </td>
  </tr>

-->


<!--

 <table cellpadding="10"> 
 <tr>
      <td class="submitlabel">
        <span class="blackmedium">
          Stakeholders:
        </span>
      </td>
      <td class="submitfield">
<!DOCTYPE html>
<html lang="en">
    <span>
        <meta charset="utf-8">
    </span>
    <body>
    <select id="val">
    <option>1</option>
    <option>2</option>
    <option>3</option>
    </select>
    <button onclick="func()">DONE</button>
    <div id="qwerty"></div>
    <script type="text/javascript">
      function func(){
        var no = document.getElementById('val').value;
        console.log(no);
        for (var i = 0; i < no; i++){
          var temp = "val"+i;
          document.getElementById('qwerty').innerHTML = document.getElementById('qwerty').innerHTML + "<input id="+temp+"><br>"
        }
        document.getElementById('qwerty').innerHTML = document.getElementById('qwerty').innerHTML + "<button onclick='func1()'>DONE</button>"
      }
      function func1(){
        var no = document.getElementById('val').value;
        for (var i = 0; i < no; i++){
          var temp = "val"+i;
          console.log(document.getElementById(temp).value);
        }
      }
    </script>
    </body>
</html>




      </input>
  </td>
  </tr>


<table cellpadding="10"> 

  <tr>
      <td class="submitlabel">
        <span class="blackmedium">
          All-Licensees:
        </span>
      </td>
      <td class="submitfield">
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
    <select id="val2">
    <option>1</option>
    <option>2</option>
    <option>3</option>
    </select>
    <button onclick="func2()">DONE</button>
    <div id="qwerty2"></div>
    <script type="text/javascript">
      function func2(){
        var no = document.getElementById('val2').value;
        console.log(no);
        for (var i = 0; i < no; i++){
          var temp = "val"+i;
          document.getElementById('qwerty2').innerHTML = document.getElementById('qwerty2').innerHTML + "<input id="+temp+"><br>"
        }
        document.getElementById('qwerty2').innerHTML = document.getElementById('qwerty2').innerHTML + "<button onclick='func1()'>DONE</button>"
      }
      function func1(){
        var no = document.getElementById('val').value;
        for (var i = 0; i < no; i++){
          var temp = "val"+i;
          console.log(document.getElementById(temp).value);
        }
      }
    </script>
    </body>
</html>

      </input>
  </td>
  </tr>


       -->



  <tr>
    <td>
    </td>
    <td>
      <input style="float: left;" class="submitbutton" type="reset" value="Clear all"/>
      <?php 

      if($_SESSION['userlevel'] != 3) 
      {
        if($ticket->ipc_member_count2())
          echo '<input style="float: right;" class="submitbutton" type="submit" value="Submit"/>';
        else
          echo 'Not enough ipc members... Notification sent to admin';
        //echo '<input style="float: right;" class="submitbutton" type="submit" value="Submit"/>';
      }
     
      else
      {
        if($ticket->ipc_member_count())
          echo '<input style="float: right;" class="submitbutton" type="submit" value="Submit"/>';
        else
          echo 'Not enough ipc members... Notification sent to admin';
      }
      ?>
    </td>
  </tr>
  </table>
</form>
</div>

</body>
</html>