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
//$UCIDprofessor = $arr["UCIDprofessor"];
$query = "SELECT MAX(TestID) FROM betaTestBank";
$result = $connect->query($query);
$stuff = $result->fetch_array(MYSQLI_ASSOC);
$TestID = $stuff["MAX(TestID)"];

$releaseCheck = "SELECT ReleaseStatus FROM betaScores WHERE UCID='".$UCID."' AND TestID = ".$TestID;
$check = $connect->query($releaseCheck);
$res = $check->fetch_array(MYSQLI_ASSOC);

$accessCheck = "SELECT AccessType FROM betaLogin WHERE UCID = '".$UCID."'";
$accessRequest = $connect->query($accessCheck);
$accessVal = $accessRequest->fetch_array(MYSQLI_ASSOC);

if($res["ReleaseStatus"] == 1 || $accessVal["AccessType"] == 1){
  $UCID = "abc1";

  $stmt = "SELECT * FROM betaScores WHERE UCID = '".$UCID."' AND TestID = ".$TestID;
  $result = $connect->query($stmt);
  $output = $result->fetch_array(MYSQLI_NUM);

  $stmt2 = "SELECT * FROM `betaTestBank` WHERE TestID = ".$TestID;
  $result2 = $connect->query($stmt2);
  $output2 = $result2->fetch_array(MYSQLI_NUM);

  $outputA = array();
  $outputQ = array();
  $outputR = array();
  // print_r($output2);
  //print_r($output);
  for($i=0;$i<5;$i++){
    $ans = $output[$i+4];
    $ans = str_replace("\r\n","",$ans);
    array_push($outputA,$ans);
    array_push($outputQ,$output[$i+9]);
    array_push($outputR,$output[$i+19]);
  }
  // print_r($outputS);
  $compileS = array();
  $testCase1 = array();
  $testCase2 = array();
  $testCase3 = array();
  $testCase4 = array();
  $functionS = array();
  $outputS = array();

  for($j=0;$j<5;$j++){
    $errors = str_split($output[$j+14]);
    // print_r($errors);
    if($errors[2] == '0'){
      array_push($compileS,($output2[$j+6]*0.3));
    }else{
      array_push($compileS,0);
    }
    //Handle 1,2,3,4 testCases
    if($errors[0] == '0'){
      array_push($functionS,($output2[$j+6]*0.1));
    }else{
      array_push($functionS,0);
    }
    if($errors[1] == '0'){
      array_push($outputS,($output2[$j+6]*0.1));
    }else{
      array_push($outputS,0);
    }
    if(count($errors) == 4){
      if($errors[3] == '0'){
	array_push($testCase1,($output2[$j+6]*0.5));
      }
      else{
	array_push($testCase1,0);
      }
      array_push($testCase2,"N/A");
      array_push($testCase3,"N/A");
      array_push($testCase4,"N/A");
    }
    else if(count($errors) == 5){
      if($errors[3] == '0'){
	array_push($testCase1,($output2[$j+6]*0.25));
      }else{
	array_push($testCase1,0);
      }
      if($errors[4] == '0'){
	array_push($testCase2,($output2[$j+6]*0.25));
      }else{
	array_push($testCase2,0);
      }
      array_push($testCase3,"N/A");
      array_push($testCase4,"N/A");
    }
    else if(count($errors) == 6){
      if($errors[3] == '0'){
	array_push($testCase1,($output2[$j+6]*0.1667));
      }else{
	array_push($testCase1,0);
      }
      if($errors[4] == '0'){
	array_push($testCase2,($output2[$j+6]*0.1666));
      }else{
	array_push($testCase2,0);
      }
      if($errors[5] == '0'){
	array_push($testCase3,($output2[$j+6]*0.1666));
      }else{
	array_push($testCase3,0);
      }
      array_push($testCase4,"N/A");
    }
    else{
      if($errors[3] == '0'){
	array_push($testCase1,($output2[$j+6]*0.125));
      }else{
	array_push($testCase1,0);
      }
      if($errors[4] == '0'){
	array_push($testCase2,($output2[$j+6]*0.125));
      }else{
	array_push($testCase2,0);
      }
      if($errors[5] == '0'){
	array_push($testCase3,($output2[$j+6]*0.125));
      }else{
	array_push($testCase3,0);
      }
      if($errors[6] == '0'){
	array_push($testCase4,($output2[$j+6]*0.125));
      }else{
	array_push($testCase4,0);
      }      
    }  
  }

  $finalArray = array("Answers"=>$outputA,"Scores"=>$outputQ,"CompileGrade"=>$compileS,"testCase1"=>$testCase1,"testCase2"=>$testCase2,"testCase3"=>$testCase3,"testCase4"=>$testCase4,"FunctionName"=>$functionS,"OutputType"=>$outputS,"ProfRemarks"=>$outputR);

}
else{
  $outputA = array("Scores Not Released");
  $finalArray = array("Answers"=>$outputA);
}
  echo json_encode($finalArray);

?>