<?php

  /*************** Test Case: Add Questions to Bank **************/
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

//Get Out information that will be inserted( QuestionID will be autoIncremented)
$FunctionName=$arr["method"];
$Type=$arr["Type"];
$Difficulty=$arr["Difficulty"];
$param1=$arr["parameter1"];
$param2=$arr["parameter2"];
$param3=$arr["parameter3"];
$param4=$arr["parameter4"];
//$test1=$arr["testcase1"]." ".$arr["testcase2"]." ".$arr["testcase3"]." ".$arr["testcase4"]." ".$arr["output"];
$Output=$arr["value"];
$ExpectedFunc=$arr["Method"];

/*
if($arr["testcase1"] == NULL){
  $test1="NULL NULL NULL NULL ".$arr["output"];
}
else if($arr["testcase2"]==NULL){
$test1=$arr["testcase1"]." NULL NULL NULL ".$arr["output"];
}
else if($arr["testcase3"] == NULL){
$test1=$arr["testcase1"]." ".$arr["testcase2"]." NULL NULL ".$arr["output"];
}
else if($arr["testcase4"] == NULL){
$test1=$arr["testcase1"]." ".$arr["testcase2"]." ".$arr["testcase3"]." NULL ".$arr["output"];
}
else{
$test1=$arr["testcase1"]." ".$arr["testcase2"]." ".$arr["testcase3"]." ".$arr["testcase4"]." ".$arr["output"];
}
*/

$tcArray = array($arr["testcase1"],$arr["testcase2"],$arr["testcase3"],$arr["testcase4"]);
foreach($tcArray as &$val){
  if($val == NULL){
    continue;
  }
  $tc = explode(",",$val);
  $len = count($tc);
  if($len == 1){
    $val = "NULL,NULL,NULL,NULL,".$tc[0];
  }
  else if($len == 2){
    $val = $tc[0].",NULL,NULL,NULL,".$tc[1];
  }
  else if($len == 3){
    $val = $tc[0].",".$tc[1].",NULL,NULL,".$tc[2];
  }
  else if($len == 4){
    $val = $tc[0].",".$tc[1].",".$tc[2].",NULL,".$tc[3];
  }
  else{
    break;
  }
}

//Is Function Name Valid? (No starting with number, Not Null)
if(strlen($FunctionName) > 0 && is_numeric($FunctionName[0]) )
  {
    //Return Error: Question Not Entered Properly
    $err=array("Error"=>"Bad Function","Code"=>1);
    echo json_encode($err);
    $connect->close();
  }

//Fix parameter error correction
$validParams="int string double float NULL";
if(strlen($param1) > 0 && is_numeric($param1[0]) )
  {
    //Return Error: param1 invalid name
    $err=array("Error"=>"Bad Parameter","Code"=>1);
    echo json_encode($err);
    $connect->close();
  }

if(strlen($param2) > 0 && is_numeric($param2[0]) )
  {
    //Return Error: param2 invalid name
    $err=array("Error"=>"Bad Parameter","Code"=>1);
    echo json_encode($err);
    $connect->close();
  }

if(strlen($param3) > 0 && is_numeric($param3[0]) )
  {
    //Return Error: param3 invalid name
    $err=array("Error"=>"Bad Parameter","Code"=>1);
    echo json_encode($err);
    $connect->close();
  }


if(strlen($param4) > 0 && is_numeric($param4[0]) )
  {
    //Return Error: param4 invalid name
    $err=array("Error"=>"Bad Parameter","Code"=>1);
    echo json_encode($err);
    $connect->close();
  }

//If all the information is correctly entered then we can insert the question
$insert = "INSERT INTO `betaQuestionBank` (`FunctionName`, `Type`, `Difficulty`, `param1`, `param2`, `param3`, `param4`, `testCase1`, `testCase2`, `testCase3`, `testCase4`, `OutputType`, `ExpectedFunction`) VALUES ('".$FunctionName."','".$Type."',".$Difficulty.",'".$param1."','".$param2."','".$param3."','".$param4."','".$tcArray[0]."','".$tcArray[1]."','".$tcArray[2]."','".$tcArray[3]."','".$Output."','".$ExpectedFunc."')";

if ($connect->query($insert) === TRUE) {
  echo json_encode("OK");
} else {
    echo "NOPE";
}
$connect->close();
?>