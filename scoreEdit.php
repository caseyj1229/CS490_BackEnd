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


$compileGrade = $arr["CompileGrade"];
$testCase1 = $arr["case1"];
$testCase2 = $arr["case2"];
$testCase3 = $arr["case3"];
$testCase4 = $arr["case4"];
$functionName = $arr["FunctionName"];
$OutputType = $arr["OutputType"];
$ProfRemarks = $arr["ProfRemarks"];


$query = "SELECT MAX(TestID) FROM betaTestBank";
$result = $connect->query($query);
$stuff = $result->fetch_array(MYSQLI_ASSOC);
//print_r($stuff);
$query2 = "SELECT * FROM betaScores WHERE TestID =".$stuff["MAX(TestID)"];
$result2 = $connect->query($query2);
//print_r($result2);
$info = $result2->fetch_array(MYSQLI_NUM);
$query3 = "SELECT ScoreID FROM betaScores WHERE TestID = ".$stuff["MAX(TestID)"];
$result3 = $connect->query($query3);
$info3 = $result3->fetch_array(MYSQLI_NUM);
$scoreID = $info[0];

$scores = array();
$totalScore = 0;

  for($i = 0; $i < sizeof($compileGrade); $i++){
    $questionScore = $testCase1[$i] + $testCase2[$i] + $testCase3[$i] + $testCase4[$i]+$compileGrade[$i]+$functionName[$i]+$OutputType[$i];

    array_push($scores,$questionScore);
    $totalScore = $totalScore + $questionScore;
  }

//print_r($scores);
//print_r($ProfRemarks);

$stmt = "UPDATE `betaScores` SET Score = ".$totalScore;

for($i = 1; $i <= sizeof($scores); $i++){
  if($i == (sizeof($scores))){
    $stmt = $stmt.", `Q".$i."score` = ".$scores[$i-1];
    $stmt = $stmt.", `TeacherRemarks".$i."` = '".$ProfRemarks[$i-1]."'";
    $stmt = $stmt." WHERE `ScoreID` = ".$scoreID;
  }else{
    $stmt = $stmt.", `Q".$i."score` = ".$scores[$i-1];
    $stmt = $stmt.", `TeacherRemarks".$i."` = '".$ProfRemarks[$i-1]."'";
  }
}
//echo $stmt;

$stmtResult = $connect->query($stmt);

if($stmtResult){
  echo json_encode("OK");
}
//4-total score
//10-14 q1-q5 score
//20 - teacher remarks
?>