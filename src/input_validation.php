<?php
//Enable error detection
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

//Validate access is via POST REQUEST and from index.php
$method = $_SERVER['REQUEST_METHOD'];
if(strtolower($method) != 'post' || !isset($_GET['action']) || $_GET['action'] != 'validate') {
	echo "You may not access this page directly. Please go back to the 
	<a href=http://web.engr.oregonstate.edu/~eslamif/final_project/src/index.php>Login</a> page";
}

//Validate First Name
if(isset($_GET['validate']) && $_GET['validate'] == 'f_name') {
	$f_name = $_POST['f_name'];
	if(validateName($f_name) == 0)
		echo "invalid";	
	else if(validateName($f_name) == 1)
		echo "valid";
}

//Validate Last Name
if(isset($_GET['validate']) && $_GET['validate'] == 'l_name') {
	$l_name = $_POST['l_name'];
	if(validateName($l_name) == 0)
		echo "invalid";	
	else if(validateName($l_name) == 1)
		echo "valid";
}

//Validate Email
if(isset($_GET['validate']) && $_GET['validate'] == 'emailAddress') {
	$e_mail = $_POST['emailAddress'];
	if(validateEmail($e_mail) == 0)
		echo "invalid";	
	else if(validateEmail($e_mail) == 1)
		echo "valid";
}

//Validate Password
if(isset($_GET['validate']) && $_GET['validate'] == 'userPassword') {
	$userPass = $_POST['userPass'];
	if(validatePassword($userPass) == 0)
		echo "invalid";	
	else if(validatePassword($userPass) == 1)
		echo "valid";
}

/*------------------- PHP FUNCTION DEFINITIONS -------------------*/
//Validate Name
function validateName($name) {
	if (!preg_match('/[^A-Za-z]+/', $name) && strlen($name) >= 2 && strlen($name) <= 20)
		return 1;	//name is valid
	else
		return 0;
}

//Validate Email
function validateEmail($e_mail) {
    if (!filter_var($e_mail, FILTER_VALIDATE_EMAIL))
		return 0;
	else
		return 1;
}

//Validate Password
function validatePassword($userPass) {
	if(strlen($userPass) >= 4 && strlen($userPass) <= 10)
		return 1;
	else
		return 0;
}

?>
