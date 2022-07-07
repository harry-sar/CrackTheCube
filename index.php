<?php
include("styles.css");

function SetContent(){
    $Box= <<< BOX
<div id="view" style="width:350px; height:250px; margin:80px auto 0 auto;">
<div id="box">
<div id="box1" class="one"><img src="assets/cubeFace.jpg"></div>
<div id="box2" class="two"><img src="assets/cubeFace.jpg"></div>
<div id="box3" class="three"><img src="assets/cubeFace.jpg"></div>
<div id="box4" class="four"><img src="assets/cubeFace.jpg"></div>
<div id="box5" class="five"><img src="assets/cubeFace.jpg"></div>
<div id="box6" class="six"><img src="assets/cubeFace.jpg"></div>
</div>
</div>
BOX;
    $Content = <<< CONTENT
<head>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1.0">
<title>LJMU MC SOC</title>
<link rel="icon" href="https://www.bucs.org.uk/static/b52f32ab-9a28-4f41-ab2b633c387c924a/company_logo_b5d466fa1eb899dc2dfc8a63ad3a41ee/Liverpool-John-Moores.png">
</head>

<img src="assets/LJMU-Logo.jpg" class="LJMUlogo">
<h1>Welcome to Crack the cube</h1>
<p>Try to guess the Computer Science related word to win a prize!</p>
{$Box}
<br><br><br>
<h1>Your hint is:</h1>
CONTENT;
    return $Content;
}

function GetLengthWord($ArrWords,$position){
    $output[]="";
    $arrKeys=array_keys($ArrWords);
    $tempLen="";
    for($a=0;$a<=count($ArrWords)-1;$a++){
        if($position==$a){
            $tempLen=strlen($arrKeys[$a]);
            $output[0]=$ArrWords[$arrKeys[$a]];
            $output[1]=$tempLen;
        }
    }
    return $output;
}

function GenTextBox($HintLength){
    $returnArr=[];
    $BoxVal=" ";

    for($loop=0;$loop<=$HintLength[1]-1;$loop++){
        $BoxVal.="_ ";
    }

    $textbox=<<<box
<form id="EntryForm" action="" method="post">
<input class="EntryWord" type="text" name="enteredword" size="{$HintLength[1]}" maxlength="{$HintLength[1]}" placeholder="{$BoxVal}"><br>
<input type="submit" value="Submit" name="submit">
</form>

<form id="ChangeWord" action="" method="post">
<input type="submit" value="Get new word" name="newWord">
</form>
box;
    $returnArr[0]=$HintLength[0];
    $returnArr[1]=$HintLength[1];
    $returnArr[2]=$textbox;
    return $returnArr;
}

function answerResponse($word){
    if(strtolower($_POST["enteredword"])==strtolower($word)){
        $GLOBALS["answer"] = "Congratulations you've won!";
        removeCube();
        $revealPrize= <<<PRIZE
<div class="prizebox">
<svg class="swing-in-top-bck">
    <g>
  <rect x="" y="0" width="180" height="180" style="fill:#a1d983"/>
  <text x="10" y="70" font-family="Verdana" font-size="20" fill="blue">Pick a prize</text>
  <text x="10" y="120" font-family="Verdana" font-size="20" fill="blue">out of the box :)</text>
  </g>
</svg>
</div>

PRIZE;
echo $revealPrize;

    }
    else{
        $GLOBALS["answer"] ="Incorrect";
    }
}

function changeWord($ArrWords){
    $randomChoiceIndex=rand(0,(count($ArrWords)-1));
    $_SESSION["randomIndex"]=$randomChoiceIndex;
}

function removeCube(){
    $cubeRem=<<<CUBE
    <script>
const myTimeout = setTimeout(CubeSide1, 2000);
const myTimeout2 = setTimeout(CubeSide2, 2500);
const myTimeout3 = setTimeout(CubeSide3, 3000);
const myTimeout4 = setTimeout(CubeSide4, 3500);
const myTimeout5 = setTimeout(CubeSide5, 4000);
const myTimeout6 = setTimeout(CubeSide6, 4500);

function CubeSide1() {
        document.getElementById("box1").outerHTML = "";
}
function CubeSide2() {
        document.getElementById("box2").outerHTML = "";
}
function CubeSide3() {
        document.getElementById("box3").outerHTML = "";
}
function CubeSide4() {
        document.getElementById("box4").outerHTML = "";
}
function CubeSide5() {
        document.getElementById("box5").outerHTML = "";
}
function CubeSide6() {
        document.getElementById("box6").outerHTML = "";
}
</script>
CUBE;
    echo $cubeRem;
}

function importFromFile(){
    $ArrWords=[];
    $file = file_get_contents('assets/answers.txt');
    $line = explode("\n", $file);

    foreach ($line as $each) {
        $data = explode(":", $each);
        $key = $data[0];
        $hint = $data[1];
        $ArrWords[$key]=$hint;
    }
    return $ArrWords;
}


session_start();

$ArrWords=importFromFile();
$answer="";

if (!isset($_SESSION["randomIndex"])) {
    $randomChoiceIndex=rand(0,(count($ArrWords)-1));
    $_SESSION["randomIndex"]=$randomChoiceIndex;
}

if(isset($_POST['submit']))
{
    answerResponse(array_keys($ArrWords)[$_SESSION["randomIndex"]]);
}

if(isset($_POST['newWord']))
{
    changeWord($ArrWords);
    header('Location: index.php');
}


$EchoMe=SetContent();

$Done=GetLengthWord($ArrWords,$_SESSION["randomIndex"]);
$textbox=GenTextBox($Done);

echo $EchoMe;
echo "{$textbox[0]}<br><br>";
echo $textbox[2];
echo $GLOBALS["answer"];


importFromFile();

?>