<?php
//Enable error detection
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

?>

<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="style.css">	
	<title>Wiki Quote</title>
</head>

<body>
	<div class="header">
	<h2>Quote Wiki</h2>
	<h3><i>Life Lessons Frozen in Time</i></h3>
	</div>
	
	<div id="intro">
		<p>Welcome to Quote Wiki. Here you can create and share your favorite quotes with friends
		or simply view them later for inspiration. The site is work-in-progress with many more enhancements
		to come.
	</div>
	
	<!-- Member Login -->
	<div>
		<form id="currentMembers">
		<fieldset>
		<legend>Members</legend>
			<ul>
				<li>
					<label>Email:</label>
					<input id="memberEmail" type="text" name="memberEmail"> 
				</li>
				
				<li>
					<label>Password:</label>
					<input id="memberPass" type="password" name="memberPass"> 
				</li>		
			</ul>
			<input id="memberLogin" type="button" value="Login" onClick="loginMember()"> 
		</fieldset>
		</form>
	</div>
	</br>
		
	<!-- Register New Account -->		
	<div id="newUser">
		<input id="createAccount" type="button" value="Create New Account" onClick="unhideRegForm()">	
		</br>
		</br>
		<div id="registerForm" style="display: none;">	
			<form id="newMembers">
			<fieldset>
			<legend>Register New Account</legend>
				</br>
				<label>Please complete the following</label>
				<ul>
					<li>
						<label>First Name:</label>
						<input id="f_name" type="text" name="f_name" onblur="validateFirstName()"> 
						<label>(2-20 letters)</label>
					</li>
					
					<li>
						<label>Last Name:</label>
						<input id="l_name" type="text" name="l_name" onblur="validateLastName()"> 
						<label>(2-20 letters)</label>
					</li>		
					
					<li>
						<label>Email:</label>
						<input id="emailAddress" type="text" name="emailAddress" onblur="validateEmailAddress()">
					</li>
					
					<li>
						<label>Password:</label>
						<input id="userPass" type="password" name="userPass" onblur="validatePassword()"> 
						<label>(6-20 characters)</label>
					</li>		
					
					<li>
						<label>DOB:</label>
						<input id="dob" type="date" name="dob"> 
					</li>						
				</ul>
				<input id="register" type="button" value="Register" onClick="registerUser()"> 
			</fieldset>
			</form>
		</div>
	</div>
	
	<script type="text/javascript" src="http://web.engr.oregonstate.edu/~eslamif/final_project/src/jquery.js"></script>	
	<script type="text/javascript" src="http://web.engr.oregonstate.edu/~eslamif/final_project/src/jsFunctions.js"></script>
</body>
</html>