<?php

function validateUsername($username,$lenght) {
	return strlen($username) >= $lenght;
}

function validatePassword($pass1, $pass2) {
	return strlen($pass1) >= 5 && $pass1 === $pass2;
}        

function writeError($type) {
	$message = "";

	if ($type === "username") {
		$message = "Jméno musí mít alespoň 3 znaky";
	} else if ($type === "password") {
		$message = "Heslo musí mít alespoň 5 znaky, nebo se hesla neshodují";
	}

	echo "<span class='error'>$message</span>";
}
