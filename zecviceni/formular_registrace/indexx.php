<?php

include "validation.php";

$formSend = count($_POST) > 0;

$username = "";
if ($formSend) {
	$usernameValid = validateUsername($_POST["username"]);
	$passwordValid = validatePassword(
		$_POST["pass1"],
		$_POST["pass2"]
	);

	$username = htmlspecialchars($_POST["username"]);

	$formValid = $usernameValid && $passwordValid;
	if ($formValid) {
		header('Location: welcome.php?username=' . $_POST["username"]);
	}
}


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Registrační formulář</title>
	<style type="text/css">
		.error {
			border: 1px solid rgba(255, 0, 0, .5);
			background-color: rgba(255, 0, 0, .2);
		}
	</style>
</head>
<body>
	<?php
		var_dump($_POST);
	?>

	<form action="" method="post">
		<p>
			Jméno
			<input type="text" name="username" value="<?php echo $username ?>">
			<?php
				if ($formSend && !$usernameValid) writeError("username")
			?>
		</p>
		<p>
			Heslo
			<input type="password" name="pass1">
			<input type="password" name="pass2">
			<?php
				if ($formSend && !$passwordValid) writeError("password")
			?>

		</p>
		<input type="submit" value="Zaregistrovat">

	</form>

</body>
</html>