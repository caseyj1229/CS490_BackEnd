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

//print_r($arr["extra"]);

$Q1id = $arr["Checkbox"][0];
$Q2id = $arr["Checkbox"][1];
$Q3id = $arr["Checkbox"][2];
$Q4id = $arr["Checkbox"][3];
$Q5id = $arr["Checkbox"][4];
$arr1 = array();
$i = 0;
while($i < sizeof($arr["extra"])){
  // echo $arr["extra"][$i];
  if($arr["extra"][$i] != ""){
    array_push($arr1,$arr["extra"][$i]);
  }
  $i++;
}
$Q1points = $arr1[0];
$Q2points = $arr1[1];
$Q3points = $arr1[2];
$Q4points = $arr1[3];
$Q5points = $arr1[4];

$TotalPoints = $Q1points + $Q2points + $Q3points + $Q4points + $Q5points;

if($Q2id == NULL){
  $insert = "INSERT INTO betaTestBank (`QuestionOneID`,`Q1score`,`TotalScore`) VALUES(".$Q1id.", " .$Q1points.", ".$TotalPoints.")";
}
else if($Q3id == NULL){
  $insert = "INSERT INTO betaTestBank (`QuestionOneID`,`QuestionTwoID`,`Q1score`,`Q2score`,`TotalScore`) VALUES(".$Q1id.",".$Q2id.", ".$Q1points.", ".$Q2points.", ".$TotalPoints.")";
}
else if($Q4id == NULL){
  $insert = "INSERT INTO betaTestBank (`QuestionOneID`,`QuestionTwoID`,`QuestionThreeID`,`Q1score`,`Q2score`,`Q3score`,`TotalScore`) VALUES(".$Q1id.",".$Q2id.",".$Q3id.", ".$Q1points.", ".$Q2points.", ".$Q3points.", ".$TotalPoints.")";
}
else if($Q5id == NULL){
  $insert = "INSERT INTO betaTestBank (`QuestionOneID`,`QuestionTwoID`,`QuestionThreeID`,`QuestionFourID`,`Q1score`,`Q2Score`,`Q3score`,`Q4score`,`TotalScore`) VALUES(".$Q1id.",".$Q2id.",".$Q3id.",".$Q4id.",".$Q1points.", ".$Q2points.", ".$Q3points.", ".$Q4points.", ".$TotalPoints.")";
  }
else{
  $insert = "INSERT INTO betaTestBank (`QuestionOneID`,`QuestionTwoID`,`QuestionThreeID`,`QuestionFourID`,`QuestionFiveID`,`Q1score`,`Q2score`,`Q3score`,`Q4score`,`Q5score`,`TotalScore`) VALUES(".$Q1id.",".$Q2id.",".$Q3id.",".$Q4id.",".$Q5id.", ".$Q1points.", ".$Q2points.", ".$Q3points.", ".$Q4points.", ".$Q5points.", ".$TotalPoints.")";
  }

$updates = $connect->query($insert);

if ($updates === TRUE) {
  echo json_encode("OK");
  $getNewId = "SELECT MAX(TestID) FROM betaTestBank";
  $idQuery = $connect->query($getNewId);

    if($idQuery == TRUE){
      $getId = $idQuery->fetch_array(MYSQLI_NUM);

      $testID = $getId[0];
    }

  $getUsers = "SELECT UCID FROM betaLogin WHERE AccessType = FALSE";
  $users = $connect->query($getUsers);
  if($users == TRUE){
    $createEntry = $users->fetch_array(MYSQLI_NUM);
    $i=0;
    $userArr = array();
    while($i < sizeof($createEntry)){
      array_push($userArr,$createEntry[$i]);
      $i += 1;
    }
    $i=0;
    foreach($userArr as $value){
      if($i%2 == 0){
	$newStmt = "INSERT INTO betaScores (`UCID`,`TestID`) VALUES ('".$value."', ".$testID.")";
	$connect->query($newStmt);
	$i+=1;
      }
      else{
	continue;
      }
    }
  }
} 

$connect->close();

?>