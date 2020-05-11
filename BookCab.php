<?php
session_start();
$email_id=$_POST['email_id'];
$pickup=$_POST['pickup'];
$drop=$_POST['drop'];
$journeydf=$_POST['journeydateF'];
$journeydt=$_POST['journeydateT'];
$journeyf=$_POST['journeytimeF'];
$journeyt=$_POST['journeytimeT'];
$displayName=$_SESSION['NAME'];
$cabtype=$_POST['cars'];

$conn=oci_connect("ayush", "aayushji100");
//echo "string";

$query1="select customer_id from Customer where email_id='$email_id'";
$s=oci_parse($conn,$query1);
oci_execute($s);

if (oci_num_fields($s)>0)
echo 'Success/n';
else{
	echo 'Failure/n';
}
$row=oci_fetch_array($s);
$custID=$row[0];
echo $custID;
//echo $row[0];

/*$query2="select vehicle_id from Vehicle where vehicle_type='$cabtype'";
$t=oci_parse($conn,$query2);
oci_execute($t);
$row2=oci_fetch_array($t);
$vehID=$row2[0];
 echo $row2[0];
 echo $vehID;
*/


echo $journeyf . "  ";
echo$journeyt . "   ";
echo $journeydf . "  ";
echo $journeydt;


$journeydf=$journeydf. " ".$journeyf;
$journeydt=$journeydt. " ".$journeyt;

$seq=oci_parse($conn, "select seq_request.nextval from dual");
oci_execute($seq);
$row=oci_fetch_array($seq);
$Cust_req_id=$row[0];


$query="insert into Customer_request(cust_req_id,customer_id,vehicle_id,
		pickup_loc,drop_loc,journeydf,journeydt,status) values
		(
		$Cust_req_id,'$custID',null,'$pickup',
		'$drop',TO_DATE('$journeydf','yyyy-mm-dd hh24:mi'),TO_DATE('$journeydt','yyyy-mm-dd hh24:mi'), 'N'
	)";
$u=oci_parse($conn,$query);
oci_execute($u);
if(oci_num_rows($u)==1){
	echo '<i>Success<i>';


$_SESSION['customer_request']=$Cust_req_id;
header('location:WaitingPage.php');
}
else{
	echo '<b>Failure</b>';
}
oci_close($conn);
?>