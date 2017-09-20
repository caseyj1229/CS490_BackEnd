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

$UCID=$arr["UCID"];
$TestID=$arr["TestID"];
$answer=$arr["QuestionAnswer"];
$score=$arr["QuestionScore"];
$errors=$arr["Error"];
$count=$arr["QuestionCount"];
$Fscore=$arr["FinalScore"];

$answer = mysqli_real_escape_string($connect,$answer);

if($count === 1){
  $insertAnswer = "UPDATE betaScores SET Score = ".$Fscore.", Q1answer = '".$answer."' ,Q1score = ".$score.", Q1errors = '".$errors."' WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
}
else if($count === 2){
  $insertAnswer = "UPDATE betaScores SET Score = ".$Fscore.", Q2answer = '".$answer."',Q2score = ".$score.", Q2errors = '".$errors."' WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
}
else if($count === 3){
  $insertAnswer = "UPDATE betaScores SET Score = ".$Fscore.", Q3answer = '".$answer."',Q3score = ".$score.", Q3errors = '".$errors."' WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
}
else if($count === 4){
  $insertAnswer = "UPDATE betaScores SET Score = ".$Fscore.", Q4answer = '".$answer."',Q4score = ".$score.", Q4errors = '".$errors."' WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
}
else{
  $insertAnswer = "UPDATE betaScores SET Score = ".$Fscore.", Q5answer = '".$answer."', Q5score = ".$score.", Q5errors = '".$errors."' WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
}

//Execute
$result = $connect->query($insertAnswer);

$connect->close();
?>