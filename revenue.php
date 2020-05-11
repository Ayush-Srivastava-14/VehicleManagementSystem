<?php
session_start();

$conn=oci_connect("ayush", "aayushji100");

if(isset($_POST['submit']) && $_POST['submit']=='rating'){
	 
	//retrieve rating using POST
	$DriverRating=$_POST['rating'];
	//take customer_id and driver_id from session variables

$Drive_id=$_SESSION['DRIVER_ID'];
$cust_id=$_SESSION['CUSTOMER_ID'];

	//insert into feedback table
$query="insert into feedback (customer_id,driver_id,rating) values('$cust_id','$Drive_id','$DriverRating')";
	//insert and execute query
$RatingSubmitted=oci_parse($conn, $query);
oci_execute($RatingSubmitted);
	//display success result
if(oci_num_rows($RatingSubmitted)==1){

require('NavigationCustomer.html');


echo '<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body style="background-image:url(images/cab_pic.jpg); background-size: cover;">
		<div class= "container" style="background-color:white;background-position: center;margin-top: 5%">
	<h1>Rating submitted Successfully</h1>
	<h2>Thank you </h2>
</div>
</body>
</html>';
}
}
else{

$cust_req_id=$_SESSION['customer_request'];
//echo $cust_req_id;
$query1="select * from Books where cust_req_id='$cust_req_id' ";
$BookingDetail=oci_parse($conn,$query1);
oci_execute($BookingDetail);

$bookingStatus=0;
while($row=oci_fetch_array($BookingDetail)){
	/*echo 'inside';*/
	$book_id=$row[0];
	$customer_req_id=$row[1];
	$customer_id=$row[2];
	$vehicle_id=$row[3];
	$PICKUP_LOC =$row[4];
	$DROP_LOC=$row[5];
 	$JOURNEYDF=$row[6];         
 	$JOURNEYDT=$row[7];
	$bookingStatus=1;
}
if($bookingStatus==1){
$query2="select vehicle_rate_per_hour from vehicle where vehicle_id='$vehicle_id' ";
$vehicleRate=oci_parse($conn,$query2);
oci_execute($vehicleRate);
$row1=oci_fetch_array($vehicleRate);
$vehicle_rate_per_hour=$row1[0];

$query3="select TO_CHAR((EXTRACT(hour FROM journeydt - journeydf))*'$row1[0]','9999.99') FROM BOOKS where cust_req_id= '$cust_req_id' ";

$TotalFare=oci_parse($conn,$query3);
oci_execute($TotalFare);
$row2=oci_fetch_array($TotalFare);
$Total_fare=$row2[0];


$query4="select name,mob_no,driver_id from driver where vehicle_id='$vehicle_id'";

$driverContact=oci_parse($conn,$query4);
oci_execute($driverContact);
$row3=oci_fetch_array($driverContact);
$Dname=$row3[0];
$Dmob_no=$row3[1];
$driver_id=$row3[2];

$_SESSION['DRIVER_ID']=$driver_id;
$_SESSION['CUSTOMER_ID']=$customer_id;
}


echo '<html>
	<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<body style="background-image:url(images/cab_pic.jpg); background-size: cover;">
		<div class= "container" style="background-color:white;background-position: center;margin-top: 5%">
			<h1><i>Booking Details  </i></h1>';
	if($bookingStatus==1){								
	echo '<table class="table table-hover table-bordered">
					<thead>
					<tr>
						<th>Book Id</th>
						<th>Customer Req.Id</th>
						<th>Vehicle Id</th>
						<th>PickUp Location</th>
						<th>Drop Location</th>
						<th>Start Time </th>
						 <th>End Time</th>
						 <th>Total Fare </th>
						 <th>Driver name</th>
						 <th>Driver Contact</th>

					</tr>
					</thead>
					<tbody>
						<tr>
						<td>' . $book_id.'</td>		
						<td>' . $customer_req_id .'</td>
						<td>'. $vehicle_id .'</td>
						<td>' .$PICKUP_LOC .' </td>
						<td> '. $DROP_LOC . '</td>
						<td>'. $JOURNEYDF . '</td>
						<td>'. $JOURNEYDT. '</td>
						<td>'. $Total_fare. '</td>
						<td>'.$Dname.'</td>
						<td>'.$Dmob_no. '</td>
						</tr>
					</tbody>
				</table>';

			
			echo '
			<form action="revenue.php" method="POST">
  <div class="form-group">
    <label for="rating">Give Ratings:</label>
    <input type="number" class="form-control" name="rating" >
  </div>
 <button type="submit" class="btn btn-default" name="submit" value="rating">Submit</button>
			';
		}
			else{
	echo '<b>Request not yet accepted. Wait for some more time then refresh the page.</b>';
		}			
	echo'</div>
		</body>
	</head>
	</html>';
}



?>