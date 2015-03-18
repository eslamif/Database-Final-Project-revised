$(document).ready(
); //end $(document).ready


//Add New Quote
function addNewQuote() {
	//Validate newQuoteForm Form user inputs
	if(validateQuoteForm() == false) {
		alert("Please ensure you completed all fields and that all errors highlighted in red are fixed.");
		return false;
	}
			
	$.post("database.php?action=add_quote", 
	{
		quote_title: $('#quote_title').val(), 
		quote: $('#quote').val(),
		quote_topic: $('#quote_topic').val()
	}, 
		function(httpResponse) {
			//Redirect New Member to Member's Page
			if(httpResponse == "quote_added") {
				alert("Your quoted was added successfully");
				window.location = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/members.php";
			}
		}); 
}



//Login Existing User
function loginMember() {
	$.post("database.php?action=login", 
	{
		memberEmail: $('#memberEmail').val(),
		memberPass: $('#memberPass').val()
	}, 
		function(httpResponse) {
			if(httpResponse == "member_exists")
				//Redirect to Member's Page
				window.location = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/members.php";
			else
				alert("The email and password don't match an existing account. Please try again or register a new account");
		});
}

//Register New User
function registerUser() {
	//Validate Registration Form user inputs
	if(validateRegForm() == false) {
		alert("Please ensure you completed all fields and that all errors highlighted in red are fixed.");
		return false;
	}
			
	$.post("database.php?action=register", 
	{
		f_name: $('#f_name').val(), 
		l_name: $('#l_name').val(),
		emailAddress: $('#emailAddress').val(),
		userPass: $('#userPass').val(),
		dob: $('#dob').val()
	}, 
		function(httpResponse) {
			//$('#regConfirmation').text(httpResponse);

			//Redirect New Member to Member's Page
			if(httpResponse == "user_registered") {
				window.location = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/members.php";
			}
		}); 
}

//Unhide Registration Form
function unhideRegForm() {
	//Set all registration form inputs as invalid
	validFirstName = false;
	validLastName = false;
	validEmailAddress = false;
	validUserPassword = false;
	
	//Unhide Registration Form
	$('#registerForm').css("display", "block");
}

//Validate First Name 
function validateFirstName() {
		$.post("input_validation.php?action=validate&validate=f_name",
		{f_name: $('#f_name').val()},
		function(httpResponse) {
			if(httpResponse == 'invalid') {
				$('#f_name').css("background-color", "red");
				alert("Your first name is invalid. Please enter a name between 2-20 letters.");
				$('#f_name').focus();
				validFirstName = false;
			}
			else {
				$('#f_name').css("background-color", "transparent");
				validFirstName = true;
			}
		});
}

//Validate Last Name 
function validateLastName() {
		$.post("input_validation.php?action=validate&validate=l_name",
		{l_name: $('#l_name').val()},
		function(httpResponse) {
			if(httpResponse == 'invalid') {
				$('#l_name').css("background-color", "red");
				alert("Your last name is invalid. Please enter a name between 2-20 letters.");
				$('#l_name').focus();
				validLastName = false;
			}
			else {
				$('#l_name').css("background-color", "transparent");
				validLastName = true;
			}
		});
}

//Validate Email of Registration Form
function validateEmailAddress() {
		$.post("input_validation.php?action=validate&validate=emailAddress",
		{emailAddress: $('#emailAddress').val()},
		function(httpResponse) {
			if(httpResponse == 'invalid') {
				$('#emailAddress').css("background-color", "red");
				alert("Your E-mail is invalid. Please enter a proper e-mail address.");
				$('#emailAddress').focus();
				validEmailAddress = false;
			}
			else {
				$('#emailAddress').css("background-color", "transparent");
				validEmailAddress = true;
			}
		});
}

//Validate Password of Registration Form
function validatePassword() {
		$.post("input_validation.php?action=validate&validate=userPassword",
		{userPass: $('#userPass').val()},
		function(httpResponse) {
			if(httpResponse == 'invalid') {
				$('#userPass').css("background-color", "red");
				alert("Your Password is invalid. Please ensure it's between 6-10 characters.");
				$('#userPass').focus();
				validUserPassword = false;
			}
			else {
				$('#userPass').css("background-color", "transparent");
				validUserPassword = true;
			}
		});	
}

//Confirm all user inputs for the Registration Form are valid
function validateRegForm() {
	if(validFirstName == true && validLastName == true && validEmailAddress == true
		&& validUserPassword == true)
		return true;
	else
		return false;
}

//Unhide newQuoteForm
function unhideAddQuote() {
	//Set all Add Quote form inputs as invalid
	validQuoteTitle = false;
	validQuote = false;
	validQuoteTopic = false;
	
	$('#databaseResults').css("display", "none");		
	$('#newFriend').css("display", "none");	
	$('#newQuoteForm').css("display", "block");		
}

//validate quoteTitle of newQuoteForm
function validateQuoteTitle() {
	var titleLength = $('#quote_title').val().length;
	if(titleLength < 4 || titleLength > 20) {
		$('#quote_title').css("background-color", "red");
		alert("Please enter a title between 4-20 characters.");
		$('#quote_title').focus();
		validQuoteTitle = false;
	}
	else if(titleLength >= 4 && titleLength <= 20) {
		$('#quote_title').css("background-color", "transparent");
		validQuoteTitle = true;
	}
}

//Validate quote of newQuoteForm
function validateQuote() {
	var quote = $('#quote').val().length;
	if(quote < 8 || quote > 100) {
		$('#quote').css("background-color", "red");
		alert("Please enter a quote between 8-100 characters.");
		$('#quote').focus();
		validQuote = false;
	}
	else if(quote >= 8 && quote <= 100) {
		$('#quote').css("background-color", "transparent");
		validQuote = true;
	}	
}

//validate quote_topic of newQuoteForm
function validateQuoteTopic() {
	var topicLength = $('#quote_topic').val().length;
	if(topicLength < 4 || topicLength > 20) {
		$('#quote_topic').css("background-color", "red");
		alert("Please enter a topic between 4-20 characters.");
		$('#quote_topic').focus();
		validQuoteTopic = false;
	}
	else if(topicLength >= 4 && topicLength <= 20) {
		$('#quote_topic').css("background-color", "transparent");
		validQuoteTopic = true;
	}
}

//Confirm all user inputs for the newQuoteForm are valid
function validateQuoteForm() {
	if(validQuoteTitle == true && validQuote == true && validQuoteTopic == true)
		return true;
	else
		return false;
}

//Unhide newFriend Form
function unhideAddFriend() {
	//Set all registration form inputs as invalid
	validFriendFirstName = false;
	validFriendLastName = false;
	validFriendEmailAddress = false;
	
	//Unhide Registration Form
	$('#databaseResults').css("display", "none");	
	$('#newQuoteForm').css("display", "none");
	$('#newFriend').css("display", "block");	
}

//Logout of Session
function logOut() {
	window.location = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/database.php?action=end";
}

//Validate newFriendForm User Inputs
//Validate Friend First Name 
function validateFriendFName() {
	var length = $('#friend_f_name').val().length;
	if(length < 2 || length > 20) {
		$('#friend_f_name').css("background-color", "red");
		alert("Please enter a name between 2-20 characters.");
		$('#friend_f_name').focus();
		validFriendFirstName = false;
	}
	else if(length >= 2 && length <= 20) {
		$('#friend_f_name').css("background-color", "transparent");
		validFriendFirstName = true;
	}
}

//Validate Friend Last Name 
function validateFriendLName() {
	var length = $('#friend_l_name').val().length;
	if(length < 2 || length > 20) {
		$('#friend_l_name').css("background-color", "red");
		alert("Please enter a name between 2-20 characters.");
		$('#friend_l_name').focus();
		validFriendLastName = false;
	}
	else if(length >= 2 && length <= 20) {
		$('#friend_l_name').css("background-color", "transparent");
		validFriendLastName = true;
	}
}

//Validate Friend Email 
function validateFriendEmail() {
		$.post("input_validation.php?action=validate&validate=emailAddress",
		{emailAddress: $('#friend_email').val()},
		function(httpResponse) {
			if(httpResponse == 'invalid') {
				$('#friend_email').css("background-color", "red");
				alert("Your E-mail is invalid. Please enter a proper e-mail address.");
				$('#friend_email').focus();
				validFriendEmailAddress = false;
			}
			else {
				$('#friend_email').css("background-color", "transparent");
				validFriendEmailAddress = true;
			}
		});
}

function validateAddFriendForm() {
	if(validFriendFirstName == true && validFriendLastName == true && validFriendEmailAddress == true)
		return true;
	else
		return false;	
}

//Add New Friend to database
function addNewFriend() {
	//Validate newQuoteForm Form user inputs
	if(validateAddFriendForm() == false) {
		alert("Please ensure you completed all fields and that all errors highlighted in red are fixed.");
		return false;
	}
			
	$.post("database.php?action=add_friend", 
	{
		friend_f_name: $('#friend_f_name').val(), 
		friend_l_name: $('#friend_l_name').val(),
		friend_email: $('#friend_email').val()
	}, 
		function(httpResponse) {
			//Redirect New Member to Member's Page
			if(httpResponse == "friend_added") {
				alert("Your friend was added successfully");
				window.location = "http://web.engr.oregonstate.edu/~eslamif/final_project/src/members.php";
			}
		}); 
}

//Get quotes from database
function getQuotes() {
	$.post("database.php?action=getQuotes", 
	function(httpResponse) {
		//Display Div
		$('#newFriend').css("display", "none");	
		$('#newQuoteForm').css("display", "none");		
		$('#databaseResults').css("display", "block");	

		//Display Quotes
		var jsonObj = JSON.parse(httpResponse);			//convert JSON string to JSON object
		
		for(var i = 0; i < jsonObj.length; i++) {
			var name = jsonObj[i];
			var category = jsonObj[i];
			var length = jsonObj[i];
			var status = jsonObj[i];
			
			var tr = document.createElement("tr");
			document.getElementById('nextRow').appendChild(tr);
			
			var td = document.createElement("td");
			td.innerText = name;
			tr.appendChild(td);	
		}			
	});
}

//Get topics from database
function getTopics() {
	$.post("database.php?action=getTopics", 
	function(httpResponse) {
		//Display Div
		$('#newFriend').css("display", "none");	
		$('#newQuoteForm').css("display", "none");		
		$('#databaseResults').css("display", "block");	

		//Display Quotes
		var jsonObj = JSON.parse(httpResponse);			//convert JSON string to JSON object
		
		for(var i = 0; i < jsonObj.length; i++) {
			var name = jsonObj[i];
			var category = jsonObj[i];
			var length = jsonObj[i];
			var status = jsonObj[i];
			
			var tr = document.createElement("tr");
			document.getElementById('nextRow').appendChild(tr);
			
			var td = document.createElement("td");
			td.innerText = name;
			tr.appendChild(td);	
		}			
	});
}

//Get Friends from 
function getFriends() {
	$.post("database.php?action=getFriends", 
	function(httpResponse) {
		//Display Div
		$('#newFriend').css("display", "none");	
		$('#newQuoteForm').css("display", "none");		
		$('#databaseResults').css("display", "block");	

		//Display Quotes
		var jsonObj = JSON.parse(httpResponse);			//convert JSON string to JSON object
		
		for(var i = 0; i < jsonObj.length; i++) {
			var name = jsonObj[i];
			var category = jsonObj[i];
			var length = jsonObj[i];
			var status = jsonObj[i];
			
			var tr = document.createElement("tr");
			document.getElementById('nextRow').appendChild(tr);
			
			var td = document.createElement("td");
			td.innerText = name;
			tr.appendChild(td);	
		}			
	});	
}
