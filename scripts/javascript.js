$(document).ready(function () {
  $(".location-tab").click(function location_tab () {
    $(".location-box").show();
    $(".user-box").hide();
    $(".location-tab").addClass("active");
    $(".user-tab").removeClass("active");
  });
  $(".user-tab").click(function user_tab () {
    $(".user-box").show();
    $(".location-box").hide();
    $(".user-tab").addClass("active");
    $(".location-tab").removeClass("active");
  });  
});


$(function () {
    $(window).on('scroll', function () {
        if ( $(window).scrollTop() > 10 ) {
            $('.navbar').addClass('active');
        } else {
            $('.navbar').removeClass('active');
        }
    });
});


function showTable() {
	document.getElementById("userTable").style.display = "block";
}

function closeTable() {
	document.getElementById("userTable").style.display = "none";
}


function showTableSQL() {
	document.getElementById("userTableSQL").style.display = "block";
}

function closeTableSQL() {
	document.getElementById("userTableSQL").style.display = "none";
}


function showAccountInfo(){
    document.getElementById("my_account").style.display = "block";
}

function closeAccountInfo(){
    document.getElementById("my_account").style.display = "none";
}

function showHealthStat(){
    document.getElementById("my_health_status").style.display = "block";
    
}

function closeHealthStat(){
    
    document.getElementById("my_health_status").style.display = "none";
}




function capitalizeName(choice) {
	
	if(choice == 'user'){
		let x = document.getElementById("name");
		let name = x.value;
		let separateWord = name.toLowerCase().split(' ');
		for (let i = 0; i < separateWord.length; i++) {
			separateWord[i] = separateWord[i].charAt(0).toUpperCase() +
			separateWord[i].substring(1);
		}
		x.value = separateWord.join(' ');
	}
	
	if(choice == 'loc'){
		let x = document.getElementById("loc_name");
		let name = x.value;
		let separateWord = name.toLowerCase().split(' ');
		for (let i = 0; i < separateWord.length; i++) {
			separateWord[i] = separateWord[i].charAt(0).toUpperCase() +
			separateWord[i].substring(1);
		}
		x.value = separateWord.join(' ');
	}
}

function showPassword(checkbox_, choice) {
	if(choice == 'user'){
		let w = document.getElementById("psw");
		let x = document.getElementById("psw-repeat");
		if(checkbox_.checked == true){
			w.type = "text";
			x.type = "text";
		}else{
			w.type = "password";
			x.type = "password";
		}
	}
	if(choice == 'loc'){
		let w = document.getElementById("loc_psw");
		let x = document.getElementById("loc_psw-repeat");
		if(checkbox_.checked == true){
			w.type = "text";
			x.type = "text";
		}else{
			w.type = "password";
			x.type = "password";
		}
	}
}




function checkUsername(){
	let error_msg = document.getElementById("usernameExist");
	error_msg.innerHTML = "<p></p>";
}

function checkPasswordMatch (choice) {
	if(choice == 'user'){
		let psw   = document.getElementById("psw");
		let repsw = document.getElementById("psw-repeat");
		let btn   = document.getElementById("registerbtn");
		let error_msg = document.getElementById("error_message");
		
		if(psw.value.length == 0 || repsw.value.length == 0){
			error_msg.innerHTML = "<p></p>";
			btn.disabled = false;
		}else{
			if(psw.value != repsw.value){
				error_msg.innerHTML = "<p>Passwords do not match.</p>";
				btn.disabled = true;
			}else if(psw.value == repsw.value) {
				error_msg.innerHTML = "<p></p>";
				btn.disabled = false;
			}
		}
	}
	
	if(choice == 'loc'){
		let psw   = document.getElementById("loc_psw");
		let repsw = document.getElementById("loc_psw-repeat");
		let btn   = document.getElementById("registerbtn");
		let error_msg = document.getElementById("loc_error_message");
		
		if(psw.value.length == 0 || repsw.value.length == 0){
			error_msg.innerHTML = "<p></p>";
			btn.disabled = false;
		}else{
			if(psw.value != repsw.value){
				error_msg.innerHTML = "<p>Passwords do not match.</p>";
				btn.disabled = true;
			}else if(psw.value == repsw.value) {
				error_msg.innerHTML = "<p></p>";
				btn.disabled = false;
			}
		}
	}
}

function clearErrors(choice){
	if(choice == 'user'){
		let error_msg = document.getElementById("error_message");
		error_msg.innerHTML = "<p></p>";
		
		error_msg = document.getElementById("username_message");
		error_msg.innerHTML = "<p></p>";
	}
	
	if(choice == 'loc'){
		let error_msg = document.getElementById("loc_error_message");
		error_msg.innerHTML = "<p></p>";
		
		error_msg = document.getElementById("loc_username_message");
		error_msg.innerHTML = "<p></p>";
	}
}

