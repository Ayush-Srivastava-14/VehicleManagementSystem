<?php
session_start();
$cust_req_id=$_POST['customer_request'];

$conn=oci_connect("ayush", "aayushji100");

//fetch Customer request details selected by the driver
$query1="select * from Customer_request where cust_req_id='$cust_req_id'";
$s=oci_parse($conn,$query1);
oci_execute($s);

while($row=oci_fetch_array($s)){
	
	$customer_id = $row[1];
	$pickup_loc=$row[3];
	$drop_loc=$row[4];
	$journeydf=$row[5];
	$journeydt=$row[6];

	$driver_id = $_SESSION['driver_id'];

	$query2="select vehicle_id from Driver where driver_id='$driver_id'";
	$t=oci_parse($conn,$query2);
	oci_execute($t);
	

	while($row2=oci_fetch_array($t)){
		$vehicle_id=$row2[0];
	}

	
	//book_id to be generated
	//$book_id = seq_bookCab.nextval;

	$query3="select vehicle_rate_per_hour from vehicle where vehicle_id='$vehicle_id' ";
	$vehicleRate=oci_parse($conn,$query3);
	oci_execute($vehicleRate);
	$row3=oci_fetch_array($vehicleRate);
	$vehicle_rate_per_hour=$row3[0];


	//insert customer cab booking details after driver has accepted the request
	$query5="insert into Books(book_id,cust_req_id,customer_id,vehicle_id,
		pickup_loc,drop_loc,journeydf,journeydt) values
		(seq_bookCab.nextval ,'$cust_req_id' ,'$customer_id', '$vehicle_id', '$pickup_loc', '$drop_loc', '$journeydf', '$journeydt'
	)";
	$u=oci_parse($conn,$query5);
	oci_execute($u);
	if(oci_num_rows($u)==1){
		//echo '<i>Inserted<i><br>';
		//update customer request status as 'accepted'='Y'
		$query6=" update customer_request set status='Y' where cust_req_id='$cust_req_id' ";
		$update=oci_parse($conn,$query6);
		oci_execute($update);
		if(oci_num_rows($update)==1){
			//echo '<i>Updated<i><br>';
			//display customer details and booking details
			$query7 ="select name, mob_no from customer where customer_id = '$customer_id' ";

			$cust_detail = oci_parse($conn,$query7);
			oci_execute($cust_detail);

			while($row1 = oci_fetch_array($cust_detail)){
				$cust_name = $row1[0];
				$cust_mob_no = $row1[1];
			}


	$query4="select TO_CHAR((EXTRACT(hour FROM journeydt - journeydf))*'$vehicle_rate_per_hour','9999.99') FROM BOOKS where cust_req_id= '$cust_req_id' ";

	$TotalFare=oci_parse($conn,$query4);
	oci_execute($TotalFare);
	$row4=oci_fetch_array($TotalFare);
	$Total_fare=$row4[0];
require('NavigationDriver.html');


		echo 'Booking Details';
		echo '<html>
				<head>
		 			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
				</head>
					<body style="background-image:url(images/taxi_cab_pic.jpg); background-size: cover;">

						<div class= "container" style="background-color:white;background-position: center;margin-top: 5%">
						<h1><i>Booking Details  </i></h1>
	
								<table class="table table-hover table-bordered">
											
											<thead>
												<tr>
													<th>Customer Name</th>
													<th>Customer Contact</th>
													<th>Pick Up Location</th>
													<th>Drop Location</th>
													<th>Start Time </th> 
													<th>End Time</th>
													<th>Total Fare</th>
												</tr>
											</thead>

											<tbody>
												<tr>
													<td>' . $cust_name .'</td>
													<td>'. $cust_mob_no .'</td>
													<td>' .$pickup_loc .' </td>
													<td> '. $drop_loc . '</td>
													<td>'. $journeydf . '</td>
													<td>'. $journeydt. '</td>
													<td>'.  $Total_fare. '</td>
												</tr>
												</tbody>
								</table>
						</div>
					</body>
				</html>';



		}
	
	else{
		echo '<b>Updation Failed</b><br>';
	}

}
else{
	echo '<b>Insertion Updation</b><br>';
}

	
}//end of while loop

oci_close($conn);
?>