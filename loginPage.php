
<?php

//retrieve form variables
$email_id=$_POST['email_id'];
$password=$_POST['psw'];
$usertype=$_POST['userType'];
// Create connection to Oracle
$conn = oci_connect("ayush", "aayushji100");
$count=0;

if($usertype=='Customer'){
	$query = "select * from Customer where email_id = '$email_id' and password='$password'";
	$stid = oci_parse($conn, $query);
    oci_execute($stid);
		    // Fetch the results in an associative array
		while($row=oci_fetch_array($stid,OCI_BOTH)){
		  echo 'Success';
		  
		  session_start();
		  $count++;

		  $_SESSION['NAME']=$row[0];
		   echo $_SESSION['NAME'];
		   require('BookCab.html');
		}
	if($count==0)	
	  echo 'Login Failure';
	

}
else {$query="select * from Driver where email_id = '$email_id' and password='$password'";
		$stid1 = oci_parse($conn, $query);
		oci_execute($stid1);

		// Fetch the results in an associative array
		while($row1=oci_fetch_array($stid1,OCI_BOTH)){
		  //echo 'Success';
		  $count++;
		  session_start();
		  $_SESSION['NAME']=$row1[0];
		  $_SESSION['driver_id']=$row1[5];
		   //echo $_SESSION['NAME'];
		   //echo $_SESSION['driver_id'];
		 require('DriverPage.php');
		}

		if($count==0)
	  echo 'Login Failure';

}
// Close the Oracle connection
oci_close($conn);

?>