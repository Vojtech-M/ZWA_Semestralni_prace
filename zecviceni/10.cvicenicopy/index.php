<?php

$errors = [];
$jmeno = "";
$email = "";
$interests = [];




##session set cookie params(86400 * 30)  







if ($_SERVER["REQUEST_METHOD"]=== "POST"){
	$jmeno = trim($_POST["jmeno"]);
	$email = trim($_POST["email"]);
	$interests = trim($_POST["interes"] ?? []);

	if (empty($jmeno)){
		$errors["jmeno"] = "Jmeno je povinne";
	}
	if (empty($email)){
		$errors["email"] = "Email je povinny";
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$errors["email"] = "Email je ve spatnem formatu";
	}

	if (empty($interests)){
		$errors["interests"] = "Musite si vybrat alespon jeden zajem";
	}

	if (empty($errors)){
		$_SESSION["jmeno"] = $jmeno;
		$_SESSION["email"]  = $email;
		$_SESSION["interests"]  = $interests;
	}
	var_dump($errors);
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="styl.css" type="text/css" charset="utf-8" />
	<title>Formulář</title>
</head>

<style>


</style>



<body>
	<form action="/" method="post">
		<fieldset id="zakladni">
			<legend>Základní údaje</legend>
			<label for="jmeno">Jméno
				
			<input type="text"
			 id="jmeno" 
			 name="jmeno"
			 value="<?= htmlspecialchars($jmeno)?>"
			 /> <!-- < ? to je zkratka pro php echo -->
			</label>
			<?php if (isset($errors["jmeno"])): ?  ?>
				<p> </p>

			<label for="email">Email<input type="text" id="email" name="email"/></label>
		</fieldset>
		<fieldset id="zajmy">
			<legend>Zájmy</legend>
			<label for="auta">Automobily<input type="checkbox" id="auta" name="interests[]"/></label>
			<label for="bio">Biopotraviny<input type="checkbox" id="bio" name="interests[]" /></label>
			<label for="cyklo">Cyklistika<input type="checkbox" id="cyklo" name="interests[]"/></label>
			</fieldset>
		<fieldset id="tlacitka">
			<input type="submit" value="Odeslat" />
			<input type="reset" value="Vymazat formulář" />
		</fieldset>
	</form>

</body>
</html>
