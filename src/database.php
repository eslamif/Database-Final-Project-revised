<?php
//Enable error detection
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

//Validate access is via POST REQUEST and from index.php
$method = $_SERVER['REQUEST_METHOD'];
if(strtolower($method) != 'post' || !isset($_GET['action']) || ($_GET['action'] != 'register' &&
	$_GET['action'] != 'login' && $_GET['action'] != 'add_quote' && $_GET['action'] != 'add_friend' &&
	$_GET['action'] != 'getQuotes' && $_GET['action'] != 'getTopics' && $_GET['action'] != 'getFriends')) {
	echo "You may not access this page directly. Please go back to the 
	<a href=http://web.engr.oregonstate.edu/~eslamif/final_project/src/index.php>Login</a> page";
}

//Register New User
if(isset($_GET['action']) && $_GET['action'] == 'register') {
	$f_name = $_POST['f_name'];
	$l_name = $_POST['l_name'];
	$e_mail = $_POST['emailAddress'];
	$password = $_POST['userPass'];	
	$dob = $_POST['dob'];
			
	//Connect to MySQL
	$mysqli = connectToSql();

	//Save New User to Database & Start Session
	if(setSqlNewUser($_POST, $mysqli) == true) {
		session_start();
		session($_POST);
		echo "user_registered";
	}
}

//Logout of Session
if(isset($_GET['action']) && $_GET['action'] == 'end') {
	session($_GET);
}

//Login Existing User
if(isset($_GET['action']) && $_GET['action'] == 'login') {
	$inputEmail = $_POST['memberEmail'];
	$inputPass = $_POST['memberPass'];		
		
	//Connect to MySQL
	$mysqli = connectToSql();

	//Validate if user exists in database
	$DbUserAndPass = getUserAndPassword($mysqli);		//Get all users & passwords from database
	$jsonStr = json_encode($DbUserAndPass);				//encode to JSON string
	
	if(isUserInDb($DbUserAndPass, $inputEmail, $inputPass) == true) {	//Validate Email		
		echo "member_exists";
		
		//Start Tracking Session
		session_start();
		session($_POST);		
	}
	else
		echo "DNE";
}

//Add New Quote
if(isset($_GET['action']) && $_GET['action'] == 'add_quote') {
	$quote_title = $_POST['quote_title'];
	$quote = $_POST['quote'];
	$quote_topic = $_POST['quote_topic'];
			
	//Connect to MySQL
	$mysqli = connectToSql();

	//Add quote to database
	if(setQuote($mysqli, $quote_title, $quote, $quote_topic) == true)
		echo "quote_added";
	else
		echo "DNE";
}

//Add Friend
if(isset($_GET['action']) && $_GET['action'] == 'add_friend') {
	$friend_f_name = $_POST['friend_f_name'];
	$friend_l_name = $_POST['friend_l_name'];
	$friend_email = $_POST['friend_email'];
			
	//Connect to MySQL
	$mysqli = connectToSql();
	
	//Confirm user does not already exist (key = email)
	
	
	//Save Friend to database
	if(setFriend($mysqli, $friend_f_name, $friend_l_name, $friend_email) == true) {
		echo "friend_added";
	}
	else
		echo "error occrere";
}

//Get quotes from database
if(isset($_GET['action']) && $_GET['action'] == 'getQuotes') {
	//Connect to MySQL
	$mysqli = connectToSql();

	$result = getQuoteFromDb($mysqli);
	$jsonStr = json_encode($result);				//encode to JSON string
	echo $jsonStr;
}

//Get topics from database
if(isset($_GET['action']) && $_GET['action'] == 'getTopics') {
	//Connect to MySQL
	$mysqli = connectToSql();
	
	$result = getTopicsFromDb($mysqli);
	$jsonStr = json_encode($result);				//encode to JSON string
	echo $jsonStr;
}

//Get friends from database
if(isset($_GET['action']) && $_GET['action'] == 'getFriends') {
	//Connect to MySQL
	$mysqli = connectToSql();
	
	$result = getFriendsFromDb($mysqli);
	$jsonStr = json_encode($result);				//encode to JSON string
	echo $jsonStr;
}
/*------------------- PHP FUNCTION DEFINITIONS -------------------*/
//Connect to mySQL
function connectToSql() {
	//Database access info
	$dbhost = "oniddb.cws.oregonstate.edu";
	$dbuser = "eslamif-db";
	$dbpass = "CjmY5rMbMAYg312u";
	$dbname = "eslamif-db";
	
	//Connect to MySQL Server
	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
	if($mysqli->connect_errno) {
		echo "An error occurred while connecting to the database server. Please try again later.";
		echo $mysqli->connect_errno . " $mysqli->connect_errno.";
	}
	return $mysqli;
}	

//Register New Users in Database
function setSqlNewUser($http, $mysqli) {
	//Variables to set
	$f_name = $http['f_name'];
	$l_name = $http['l_name'];
	$e_mail = $http['emailAddress'];
	$pass = $http['userPass'];
	$dob = $http['dob'];
	
	//Prepared Statement - prepare
	if (!($stmt = $mysqli->prepare("INSERT INTO users(f_name, l_name, email, password, dob) VALUES (?, ?, ?, ?, ?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 echo "An error occurred while communicating with the database server. Please try again later.";
		 return false;
	}	
	
	//Prepared Statement - bind and execute 
	if (!$stmt->bind_param('sssss', $f_name, $l_name, $e_mail, $pass, $dob)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}	
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}
	
	$stmt->close();		//close statement
	return true;
}

//Confirm User Exists in Database
function getUserAndPassword($mysqli) {
	$e_mail = NULL;
	$password = NULL;
	
	//Prepared Statement - prepare
	if (!($stmt = $mysqli->prepare("SELECT email, password FROM users"))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		echo "An error occurred while communicating with the database server. Please try again later."; 
	}
	
	//Prepared Statement - execute
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}

	//Bind results
	if (!$stmt->bind_result($e_mail, $password)) {
	    echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}
	
	//Store result in array
	$arrOuter = array();
	$arrInner = array();
	while($stmt->fetch()) {
		$arrInner = [$e_mail, $password];
		array_push($arrOuter, $arrInner);		
	}
	
	$stmt->close();	
	return $arrOuter;
}

//Track Session
function session($http) {
	//End Session
	//session_start();
	if(isset($http['action']) && $http['action'] == 'end') {
		echo "session is ending";
		//End session
		$_SESSION = array();
		session_destroy();
		
		//Redirect user
		$redirect = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/index.php";
		header("Location: {$redirect}", true);
		die();			
	}
	
	//Set username and user status as logged in
	if(session_status() == PHP_SESSION_ACTIVE && !isset($_SESSION['loggedIn'])) {
		/*
		if(isset($http['f_name'])) {
			$_SESSION['f_name'] = $http['f_name'];
		}
		*/
		
		//Set user status as logged in
		if(!isset($_SESSION['loggedIn'])) {
			$_SESSION['loggedIn'] = true;
		}	
	}
}

function isUserInDb($DbUserAndPass, $inputEmail, $inputPass) {
	$i = 0;
	foreach($DbUserAndPass as $dbEmail) {
		if($dbEmail[0] == $inputEmail) {
			if($DbUserAndPass[$i][1] == $inputPass)
				return true;
			else
				return false;
		}
	else
		$i++;
	}
}

//Add quote to database
function setQuote($mysqli, $quote_title, $quote, $quote_topic) {	
	//Prepared Statement - prepare for quotes table
	if (!($stmt = $mysqli->prepare("INSERT INTO quotes(title, quote) VALUES (?, ?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 echo "An error occurred while communicating with the database server. Please try again later.";
		 return false;
	}	
	
	//Prepared Statement - bind and execute for topics table
	if (!$stmt->bind_param('ss', $quote_title, $quote)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}	
	
	//Execute for topics table
	if (!$stmt->execute()) {  
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}
	$stmt->close();		//close statement for topics table
	
	//Prepared Statement - prepare for topics table
	if (!($stmt = $mysqli->prepare("INSERT INTO topics(title) VALUES (?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 echo "An error occurred while communicating with the database server. Please try again later.";
		 return false;
	}	
	
	//Prepared Statement - bind and execute for topics table
	if (!$stmt->bind_param('s', $quote_topic)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}	
	
	//Execute for topics table
	if (!$stmt->execute()) {  
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}
	$stmt->close();		//close statement for topics table
	
	return true;	
}

//Store New Friend to database
function setFriend($mysqli, $friend_f_name, $friend_l_name, $friend_email) {
	//Prepared Statement - prepare for quotes table
	if (!($stmt = $mysqli->prepare("INSERT INTO friends(f_name, l_name, email) VALUES (?, ?, ?)"))) {
		 //echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 echo "An error occurred while communicating with the database server. Please try again later.";
		 return false;
	}	
	
	//Prepared Statement - bind and execute for topics table
	if (!$stmt->bind_param('sss', $friend_f_name, $friend_l_name, $friend_email)) {
		//echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}	
	
	//Execute for topics table
	if (!$stmt->execute()) {  
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
		return false;
	}
	$stmt->close();		//close statement for topics table	
	return true;
}

//Get quotes from database
function getQuoteFromDb($mysqli) {
	$dbResults = NULL;
	
	//Prepared Statement - prepare
	if (!($stmt = $mysqli->prepare("SELECT quote FROM quotes"))) {
		//echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		echo "An error occurred while communicating with the database server. Please try again later."; 
	}
	
	//Prepared Statement - execute
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}

	//Bind results
	if (!$stmt->bind_result($dbResults)) {
	    //echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}
	
	//Store result in array
	$arrOuter = array();
	$arrInner = array();
	while($stmt->fetch()) {
		$arrInner = [$dbResults];
		array_push($arrOuter, $arrInner);		
	}
	
	$stmt->close();	
	return $arrOuter;
}

//Get topics from database
function getTopicsFromDb($mysqli) {
	$dbResults = NULL;
	
	//Prepared Statement - prepare
	if (!($stmt = $mysqli->prepare("SELECT title FROM topics"))) {
		//echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		echo "An error occurred while communicating with the database server. Please try again later."; 
	}
	
	//Prepared Statement - execute
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}

	//Bind results
	if (!$stmt->bind_result($dbResults)) {
	    //echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}
	
	//Store result in array
	$arrOuter = array();
	$arrInner = array();
	while($stmt->fetch()) {
		$arrInner = [$dbResults];
		array_push($arrOuter, $arrInner);		
	}
	
	$stmt->close();	
	return $arrOuter;
}

//Get friends from database
function getFriendsFromDb($mysqli) {
	$dbResults = NULL;
	
	//Prepared Statement - prepare
	if (!($stmt = $mysqli->prepare("SELECT f_name FROM friends"))) {
		//echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		echo "An error occurred while communicating with the database server. Please try again later."; 
	}
	
	//Prepared Statement - execute
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}

	//Bind results
	if (!$stmt->bind_result($dbResults)) {
	    //echo "Binding output parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		echo "An error occurred while communicating with the database server. Please try again later.";
	}
	
	//Store result in array
	$arrOuter = array();
	$arrInner = array();
	while($stmt->fetch()) {
		$arrInner = [$dbResults];
		array_push($arrOuter, $arrInner);		
	}
	
	$stmt->close();	
	return $arrOuter;
}


?>