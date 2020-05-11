<?php

	//retrieve form variables
	$uname=$_POST['name'];
	$uphone=$_POST['phone'];
	$uemail=$_POST['email'];
	$password=$_POST['psw'];
	$usertype=$_POST['userType'];

/*
echo $uname;
echo $password;*/

	// Create connection to Oracle
	$conn = oci_connect("ayush", "aayushji100");

	if($usertype=="Customer"){
			$query = "insert into Customer(customer_id,name,mob_no,email_id,password) values(seq_customer_id.nextval,'$uname','$uphone','$uemail','$password')";
				}				

	else if($usertype=="Driver"){
			$query="insert into Driver(driver_id,name,mob_no,email_id,password) values(seq_driver_id.nextval,'$uname','$uphone','$uemail','$password')";
	}
	

	$stid = oci_parse($conn, $query);
	oci_execute($stid);

	//echo oci_num_rows($stid);

	// Fetch the results in an associative array
	if (oci_num_rows($stid)==1){
	  echo '<i>Success<i>';
	  require('reglog.html');

	}
	else{
	  echo '<b>Failure</b>';
	}


// Close the Oracle connection
oci_close($conn);

?>



