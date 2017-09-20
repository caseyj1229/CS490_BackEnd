<?php

$info=file_get_contents("php://input",'r');
$arr = json_decode($info,true);

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

$query = "SELECT MAX(TestID) FROM betaTestBank";
$res = $connect->query($query);
$stuff = $res->fetch_array(MYSQLI_ASSOC);

$releaseStatement = "UPDATE betaScores SET ReleaseStatus = True WHERE TestID = '".$stuff["MAX(TestID)"]."'";

$result = $connect->query($releaseStatement);
if($result)
  {
    echo json_encode("Scores Released");
  }


?>