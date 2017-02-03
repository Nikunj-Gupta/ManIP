<?php
//require_once('classes/connect.php');

$conn=new connect();
$conn->db_open();

$to=$_POST['regemail'];

// Your subject
$subject="Your confirmation code";


// Your message
$message="Confirmation code: \r\n";

// From
$header="from: IPMS";

$query = "SELECT salt FROM user WHERE email='$to';";
$statement = $conn->
			mysqli->query($query);
		
$result=mysqli_fetch_row($statement);
$code=substr($result[0],0,4);

//$code = "Sup!";

$message.=$code;


// send email
$sentmail = mail($to,$subject,$message,$header);




// if your email succesfully sent
if($sentmail){
echo "Your Confirmation link Has Been Sent To Your Email Address.";
}
else {
echo "Cannot send Confirmation link to your e-mail address";
}

?>