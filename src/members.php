<?php
//Enable error detection
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html');

//Validate user is logged in
session_start();
	
if(!isset($_SESSION['loggedIn'])) {
	echo "You must log in first. Please click 
	<a href=http://web.engr.oregonstate.edu/~eslamif/final_project/src/index.php>here</a> to log in.";	
}
else if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true){
	displayMembersPage();
}


/*------------------- PHP FUNCTION DEFINITIONS -------------------*/
	function displayMembersPage() {
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
	
	<div id="menuWrapper">		
		<div id="logOutDiv">
			<input type="button" value="Logout" onClick="logOut()">
		</div>
		
		<section id="actionMenu">
		<div id="quotesMenu">
			<ul>
				<h3>Quotes</h3>
				<li><input type='button' value='Add Quote' onClick='unhideAddQuote()';></li>
				<li><input type='button' value='View Quotes' onClick="getQuotes()"></li>
				<li><input type='button' value='View Topics' onClick="getTopics()"></li>			
			</ul>
		</div>
		
		<div id="friendsMenu">
			<ul>
				<h3>Friends</h3>
				<li><input type='button' value='Add Friend' onClick="unhideAddFriend()"></li>			
				<li><input type='button' value='View Friends List' onClick="getFriends()"></li>
			</ul>
		</div>	
		</section>
	</div>
	
	<!-- Add New Quote -->
	<div id="newQuoteForm" style="display: none;">	
		<form id="newQuote">
		<fieldset>
		<legend>Add New Quote</legend>
		</br>
		<label>Please complete the following:</label>
			<ul>
				<li>
					<label>Title:</label>
					<input id="quote_title" type="text" name="quote_title" onblur="validateQuoteTitle()"> 
					<label>(4-20 characters)</label>
				</li>
				
				<li>
					<label>Quote:</label>
					<textarea id="quote" rows="4" cols="50" name="quote" form="newQuote"
					placeholder="Enter Quote..." onblur="validateQuote()"></textarea>
					<label>(8-100 characters)</label>
				</li>	

				<li>
					<label>Topic:</label>
					<input id="quote_topic" type="text" name="quote_topic" onblur="validateQuoteTopic()"
					placeholder="What topic to file under?">
					<label>(4-20 characters)</label>
				</li>
				
				<li>
					<input id="addQuote" type="button" value="Add Quote" onClick="addNewQuote()">
				</li>
			</ul>	
		</fieldset>
		</form>
	</div>
	
	<!-- Add New Friend -->
	<div id="newFriend" style="display: none">
		<form id="newFriendForm">
		<fieldset>
		<legend>Add New Friend</legend>
		</br>
		<label>Please complete the following:</label>
		<ul>
			<li>
				<label>Friend's First Name:</label>
				<input id="friend_f_name" type="text" name="friend_f_name" onblur="validateFriendFName()"> 
				<label>(2-20 letters)</label>
			</li>
			
			<li>
				<label>Friend's Last Name:</label>
				<input id="friend_l_name" type="text" name="friend_l_name" onblur="validateFriendLName()"> 
				<label>(2-20 letters)</label>
			</li>
			
			<li>
				<label>Friend's Email Address:</label>
				<input id="friend_email" type="text" name="friend_email" onblur="validateFriendEmail()"> 
			</li>

			<li>
				<input id="addFriend" type="button" value="Add Friend" onClick="addNewFriend()">
			</li> 		
		</ul>
		</fieldset>
		</form>
	</div>
	
	<!-- Display Databse Results -->
	<div id="databaseResults" style="display: none">
		<p>Please refresh page if you wish to view more more Quotes, Topics, or Friends.
		<table>
			<caption>Results</caption>
			<tbody id='nextRow'></tbody>
		</table>
	</div>
	
	
	<script type="text/javascript" src="http://web.engr.oregonstate.edu/~eslamif/final_project/src/jquery.js"></script>	
	<script type="text/javascript" src="http://web.engr.oregonstate.edu/~eslamif/final_project/src/jsFunctions.js"></script>	
</body>
</html>

<?php
};	//function displayMembersPage()


?>
