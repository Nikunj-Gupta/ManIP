<?php

require_once('classes/connect.php');
class user extends connect
{
//Add a new user to the database. 
//Userlevel is as follows: 1 = Regular user, 2 = Staff user and 3 = Adminstrator
function add_user($name, $email, $emailconfirm, $password, $passwordconfirm, $userlevel = 0)
{
//Checks that the passwords and emails match.
if($password != $passwordconfirm) {
$this->
error = "Passwords do not match.";
return false;
}

if($email != $emailconfirm) {
$this->
error = "E-mails do not match.";
return false;
}

$length=strlen($email);
if(substr($email,$length-9)=="iiitb.org"){
	if(isset($_SESSION['userlevel']) && $_SESSION['userlevel'] == 4)
		$userlevel=3;
	else
		$userlevel=1;
}
else{
	$userlevel=5;
}


//Check if email already exists.
$statement = $this->
mysqli->
prepare("SELECT * FROM user WHERE email=?;");
$statement->
bind_param("s", $email);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows();
$statement->
close();

if($numrows != 0) {
$this->
error = 'E-email already used.';
return false;
} else {
$this->
mysqli->
autocommit(FALSE); //Turn off autocommit to allow transactions.

//Password hashing.
$hashed = hash('sha256', $password); 		//hash the password.
$randomstring = md5(uniqid(rand(), true)); 	//Get a random unique string for salt.
$salt = substr($randomstring, 0, 8); 		//Cut the salt from the random string.
$hashed = hash('sha256', $salt . $hashed); 	//Hash the password with salt.

//Insert the user into the database.
$statement = $this->
mysqli->
prepare("INSERT INTO user (email, password, salt, userlevel) VALUES (?, ?, ?, ?);");
$statement->
bind_param("sssi", $email, $hashed, $salt, $userlevel);
$statement->
execute();
$userid = $statement->
insert_id;
$arows = $statement->
affected_rows;
$statement->
close();

//Check for errors.
if($arows != 1) {
$this->
error = "Database error, user not added.";
$this->
mysqli->
rollback();
return false;
} else {
//Insert uid and name in userdata table.
$query = "INSERT INTO userdata (uid, name) 
VALUES (?, ?);";
$statement = $this->
mysqli->
prepare($query);
$statement->
bind_param('is', $uid, $name);
$insertuserdata = $statement->
execute();

//Check for errors.
if(!$insertuserdata) {
$this->
error = "Database error: " . $this->
mysqli->
error;
$this->
mysqli->
rollback();
return false;
} else {
$this->
mysqli->
commit();
return "User created.";
}
}
$this->
mysqli->
autocommit(TRUE);
}
}
/*
//Check login data.
function take_login($email, $password, $code)
{
//Get userinfo belonging to the email from the database.
$statement = $this->
mysqli->
prepare("SELECT uid, email, password, salt, userlevel
FROM user
WHERE email=?;");
$statement->
bind_param("s", $email);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;
$statement->
bind_result($uid, $femail, $fpassword, $fsalt, $fuserlevel);
$statement->
fetch();
$statement->
close();


$query="SELECT flag FROM user WHERE email='$email';";
$statement = $this->
  			mysqli->query($query);

$result=mysqli_fetch_row($statement);

$query2="SELECT salt FROM user WHERE email='$email';";
$statement = $this->
  			mysqli->query($query2);

$result2=mysqli_fetch_row($statement);
//echo $result2[0];
$check=substr($result2[0],0,4);

if($result[0]==1 || $code==$check)
{

	//anshul
	$query3="UPDATE user SET flag=1 WHERE email='$email';";
	$statement = $this->
  				mysqli->query($query3);



			//Check if email exists.
			if($numrows 
			< 1) {
			$this->
			error = "No user with that email.";
			return false;
			} else {
			//Hash the supplied password.
			$hashed = hash('sha256', $fsalt . hash('sha256', $password));

			//Compare to the stored hashed password.
			if($hashed != $fpassword) {
			$this->
			error = "Wrong password.";
			return false;
			} elseif ($hashed == $fpassword) {
			//Set session parameters.
			session_start();
			$_SESSION['login'] = "yes";
			$_SESSION['uid'] = $uid;
			$_SESSION['userlevel'] = $fuserlevel;
			return true;
			}
			}
			}
			else
			return false;
}
*/

//Check login data.
function take_login($email, $password, $code)
{
//Get userinfo belonging to the email from the database.
$statement = $this->
mysqli->
prepare("SELECT uid, email, password, salt, userlevel
FROM user
WHERE email=?;");
$statement->
bind_param("s", $email);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;
$statement->
bind_result($uid, $femail, $fpassword, $fsalt, $fuserlevel);
$statement->
fetch();
$statement->
close();

//Check if email exists.


//anshul

//echo $code;

$query="SELECT flag FROM user WHERE email='$email';";
$statement = $this->
  			mysqli->query($query);

$result=mysqli_fetch_row($statement);

$query2="SELECT salt FROM user WHERE email='$email';";
$statement = $this->
  			mysqli->query($query2);

$result2=mysqli_fetch_row($statement);
//echo $result2[0];
$check=substr($result2[0],0,4);

if($result[0]==1 || $code==$check)
{

	//anshul
	$query3="UPDATE user SET flag=1 WHERE email='$email';";
	$statement = $this->
  				mysqli->query($query3);

	if($numrows 
	< 1) {
		$this->
		error = "No user with that email.";
		return false;
	} 
	else {
		//Hash the supplied password.
		$hashed = hash('sha256', $fsalt . hash('sha256', $password));

		//Compare to the stored hashed password.
		if($hashed != $fpassword) {
			$this->
			error = "Wrong password.";
			return false;
		} 
		elseif ($hashed == $fpassword) {
		//Set session parameters.
			session_start();
			$_SESSION['login'] = "yes";
			$_SESSION['uid'] = $uid;
			$_SESSION['userlevel'] = $fuserlevel;
			return true;
		}
	}
}
else
	return false;
}


//Update the user profile.
function update_userdata($uid, $name, $address = NULL)
{
//Update the userdata table.
$statement = $this->
mysqli->
prepare("UPDATE userdata SET name = ?, address = ? WHERE uid = ?") or die($this->
mysqli->
error);
$statement->
bind_param("sssssisssi", $name, $address, $uid);
$statement->
execute();
$arows = $statement->
affected_rows;
$statement->
close();

//Check for errors.
if($arows 
< 1 && $this->
mysqli->
error) {
$this->
error = "Database error, could not update profile: " . $this->
mysqli->
error;
} else {
return "Profile has been updated.";
}
}

//Fetch the userdata from the database.
function get_userdata($uid) 
{
$statement = $this->
mysqli->
prepare("SELECT * FROM userdata WHERE uid=?;");
$statement->
bind_param("i", $uid);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows();

//check for errors.
if($numrows != 1) {
$error = 'No user with that ID.';
return false;
} else {
//Bind the returned data and store in an array.
$statement->
bind_result($uid, $name, $address);
$statement->
fetch();
$row = array('uid' =>
$uid, 'name' =>
$name, 'address' =>
$address);
$statement->
close();
return $row;
}
}

//Change a user or admins password.
function change_password($uid, $oldpassword, $newpassword, $confirm)
{
//Check if new passwords match.
if($newpassword != $confirm) {
$this->
error = "Passwords do not match.";
return false;
}

//Get the old password and salt from the database.
$statement = $this->
mysqli->
prepare("SELECT password, salt
FROM user
WHERE uid=?;");
$statement->
bind_param("s", $uid);
$statement->
execute();
$statement->
store_result();
$numrows = $statement->
num_rows;
$statement->
bind_result($password, $salt);
$statement->
fetch();
$statement->
close();

//Hash the supplied old password.
$oldpassword = hash('sha256', $salt . hash('sha256', $oldpassword));

//Compared to the stored old password.
if($password != $oldpassword) {
$this->
error = "Old password is wrong.";
return false;
} elseif ($password == $oldpassword) {
//Hash the new password.
$hashed = hash('sha256', $newpassword);
$randomstring = md5(uniqid(rand(), true));
$newsalt = substr($randomstring, 0, 8);
$hashed = hash('sha256', $newsalt . $hashed);

//Store the new password and salt in the database.
$statement = $this->
mysqli->
prepare("UPDATE user SET password=?, salt=? WHERE uid=?");
$statement->
bind_param("sss", $hashed, $newsalt, $uid);
$statement->
execute();
$arows = $statement->
affected_rows;
$statement->
close();

//Check for errors.
if($arows != 1 || $this->
mysqli->
error) {
$this->
error = "Database Error.";
return false;
} else {
return "Password changed.";
}
}
}


}
?>