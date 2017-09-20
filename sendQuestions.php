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

  $stmt = "SELECT DISTINCT QuestionID FROM betaQuestionBank WHERE Difficulty =".$arr["Diff"];

$results = $connect->query($stmt);

$stuff = array();

while($row = $results->fetch_array())
  {
    array_push($stuff, $row);
  }

$arra = array();
$i=0;
while($i < sizeof($stuff)){
  array_push($arra,$stuff[$i]);
  $i += 1;
}
$qArray = array();
$keys = array();
$k = 0;

while($k<$i){
  array_push($qArray, $arra[$k]["QuestionID"]);
  array_push($keys, $qArray[$k]);
  $k = $k+1;
}

$outputArray=array();
foreach($qArray as &$value)
  {
    array_push($outputArray,$value);
    $query1="SELECT * FROM betaQuestionBank WHERE QuestionID = '".$value."'";
    $result1= $connect->query($query1);
    $qInfo=$result1->fetch_array(MYSQLI_ASSOC);
    $question="Write a function named ".$qInfo["FunctionName"]." that takes parameters ";
    if($qInfo["param1"] != NULL)
      {
	$question.=$qInfo["param1"].", ";
      }
    if($qInfo["param2"] != NULL)
      {
	$question.=$qInfo["param2"].", ";
      }
    if($qInfo["param3"] != NULL)
      {
	$question.=$qInfo["param3"].", ";
      }
    if($qInfo["param4"] != NULL)
      {
	$question.=$qInfo["param4"]." ";
      }
    $question.= "and ".$qInfo["OutputType"]."s the result of the following: ".$qInfo["ExpectedFunction"];
    array_push($outputArray,$question);
  }

$out = array("Keys"=>$keys,"Questions"=>$outputArray);

echo json_encode($out);

?>