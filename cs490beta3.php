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

//Test Case 3: Student can Take exam
$query = "SELECT MAX(TestID) FROM betaTestBank";
$result = $connect->query($query);
$stuff = $result->fetch_array(MYSQLI_ASSOC);
$query2 = "SELECT * FROM betaTestBank WHERE TestID =".$stuff["MAX(TestID)"];
$result2 = $connect->query($query2);

$info = $result2->fetch_array(MYSQLI_ASSOC);
//print_r($info);
$Q1=$info["QuestionOneID"] ;
$Q2=$info["QuestionTwoID"];
$Q3=$info["QuestionThreeID"];
$Q4=$info["QuestionFourID"];
$Q5=$info["QuestionFiveID"];
$Q1points =$info["Q1score"];
$Q2points =$info["Q2score"];
$Q3points =$info["Q3score"];
$Q4points =$info["Q4score"];
$Q5points =$info["Q5score"];

$sArray = array($Q1points,$Q2points,$Q3points,$Q4points,$Q5points);

$qArray=array($Q1);

if($Q2 !== NULL)
  {
    array_push($qArray, $Q2);
  }

if($Q3 !== NULL)
  {
    array_push($qArray, $Q3);
  }

if($Q4 !== NULL)
  {
    array_push($qArray, $Q4);
  }

if($Q5 !== NULL)
  {
    array_push($qArray, $Q5);
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
array_push($outputArray,$sArray);
echo json_encode($outputArray);


?>