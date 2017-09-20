<?php

  /******************************Login Code*************************************/
  //Recieve Information From Middle Tier
$info=file_get_contents("php://input",'r');
$arr = json_decode($info,true);

$UCID=$arr["UCID"];
$userPassword=$arr["Password"];

//Setup Connection to Database 
$servername="SQL2.njit.edu";
$username="user";
$password="pw";
$dbname="jpc34";

$connect = new mysqli($servername,$username,$password,$dbname);

//Check Connection
if($connect->connect_error)
  {
    die("Connection Failed!".$connect->connect_error);
  }

//SQL request for UCID(Make sure this UCID exists)
$request="SELECT * FROM betaLogin WHERE UCID='".$UCID."'";

//Execute Query
$result = mysqli_query($connect,$request);
$row = mysqli_fetch_array($result);

//Get password that is stored in DB
$ucidSystem=$row["UCID"];
$pwSystem = $row["Password"];
$accessType=$row["AccessType"];
$fName=$row["Fname"];
$lName=$row["Lname"];

$answer=array('First'=>$fName, 'Last'=>$lName, "Access"=>$accessType);
//Compare password from Middle Tier to password returned from database
if($pwSystem === $userPassword)
  {
    echo json_encode($answer);
  }
else
  {
    $err=array("Access"=>2);
    echo json_encode($err);
  }

$connect->close();

 ?>