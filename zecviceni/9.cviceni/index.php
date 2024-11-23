<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="styl.css" type="text/css" charset="utf-8" />
	<title>Formulář</title>
</head>

<body>
	<form action="vypisform.php" method="post">
		<fieldset id="zakladni">
			<legend>Základní údaje</legend>
			<label for="jmeno">Jméno<input type="text" id="jmeno" name="jmeno"/></label>
			<label for="email">Email<input type="text" id="email" name="email"/></label>
		</fieldset>
		<fieldset id="zajmy">
			<legend>Zájmy</legend>
			<label for="auta">Automobily<input type="checkbox" id="auta" name="interests[]"/></label>
			<label for="bio">Biopotraviny<input type="checkbox" id="bio" name="interests[]" /></label>
			<label for="cyklo">Cyklistika<input type="checkbox" id="cyklo" name="interests[]"/></label>
		</fieldset>
		<fieldset id="spam">
			<legend>Spam</legend>
			<!--vyber v radio button, dat stejny jmena -->
			<label for="spam0"><input type="radio" id="spam0"name="spam" />Nechci dostávat žádný spam</label>
			<label for="spam1"><input type="radio" id="spam1"name="spam" />Chci dostávat málo spamu</label>
			<label for="spam2"><input type="radio" id="spam2"name="spam" />Zajímají mě jen léky a repliky hodinek</label>
			<label for="spam3"><input type="radio" id="spam3"name="spam" />Posílejte mi léky, hodinky i diplomy</label>
			<label for="spam4"><input type="radio" id="spam4" name="spam"/>Posílejte spamu, co schránka stačí</label>
		</fieldset>
		<fieldset id="seznamy">
			<legend>Oblíbené předměty</legend>
			<select id="predmety" name="subjects[]" multiple>
				<option value="matika">Matematika</option>
				<option value="anglictina">Angličtina</option>
				<option value="nemcina">Němčina</option>
				<option value="techdok">Technická dokumentace</option>
				<option value="weby">Tvorba webů</option>
			</select>
			<label for="nejpredmet">Nejlepší z nich je
				<select id="nejpredmet">
					<option value="matika">Matematika</option>
					<option value="anglictina">Angličtina</option>
					<option value="nemcina">Němčina</option>
					<option value="techdok">Technická dokumentace</option>
					<option value="weby">Tvorba webů</option>
				</select>
			</label>
		</fieldset>
		<fieldset id="dodatek">
			<legend>Zde napište Váš vzkaz</legend>
			<textarea id="vzkaz" cols="40" rows="8"></textarea>
		</fieldset>
		<fieldset id="tlacitka">
			<input type="submit" value="Odeslat" />
			<input type="reset" value="Vymazat formulář" />
		</fieldset>
	</form>

</body>
</html>
