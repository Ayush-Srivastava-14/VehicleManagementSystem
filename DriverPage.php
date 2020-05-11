<?php
require('NavigationDriver.html');

?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<title>Driver_page</title>
<style>
  input[type=submit] {
    background-color: #F89104;
    border: none;
    width: 15%;
    color: white;
    font-weight: bold;
    padding: 0.7em 0;
    border-radius: 2.5em;
    font-size: 1.1em;
    cursor: pointer;
    margin-left: 40%;
}

input[type=submit]:hover {
    background-color: rgb(216, 126, 1);
}
</style>

</head>
<body style="background-image:url(images/cab_pic.jpg); background-size: cover;">
    

<div class= "container" style="background-image:url('images/download.jpg') ;margin-top: 5%; background-size: cover;">
	<h1><i>Welcome  </i></h1>
	<h2><b>Choose the trip as per your convenience</b></h2>
  <a href="RatingForm.php"><h3>See Ratings</h3></a>
 <br/>



<?php
echo 'Hello: '. $_SESSION['NAME'];
$conn=oci_connect("ayush", "aayushji100");
$query="select * from customer_request where status='N'";//status=N means not yet accepted & status=Y means accepted
$dr=oci_parse($conn,$query);
oci_execute($dr);

echo '<form action="TripConfirm.php" method="POST"> <table class="table table-hover table-bordered"
     >
     <thead>
  <tr>
  <th>Choose</th>
    <th>Pickup Location</th> 
    <th>Drop Location</th> 
    <th>Booking From</th> 
    <th>Booking Upto</th> 
  </tr>
  </thead>';

$count=0;
while($row=oci_fetch_array($dr)){
echo '<tbody><tr><td><input type="radio" name="customer_request" value="'. $row[0].'"></td>
    <td>'.$row[3].'</td>
     <td>'.$row[4].'</td>
      <td>'.$row[5].'</td>
       <td>'.$row[6].'</td>
  </tr>';
  $count++;
}

echo '<tr><input type="submit" name="submit" value="Confirm Trip"></tr></tbody></table></form>';
if($count==0)echo '<b>No active request at the moment<b>';



?>

</div>
</body>
</html>