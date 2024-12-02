<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="styl.css" type="text/css" charset="utf-8" />
	<title>Co bylo ve formuláři</title>
</head>

<body>
<?php
	function vypisPole($nazev,$pole) {
		if(is_array($pole)) {
			echo '<h2>'.$nazev.'</h2>';
			if(!empty($pole)) {
				echo '<table border="1"><thead><tr><th>Klíč</th><th>Hodnota</th></tr></thead><tbody>';
				foreach($pole as $klic => $hodota) {
					echo "<tr><td>" . htmlspecialchars($klic) . "</td><td>";
					if (is_array($hodnota)){
						vypisPole($klic,$hodnota);
					}else {
						echo htmlspecialchars($hodnota);

					}

					echo '<tr><td>'.$klic.'</td><td>'.$hodota.'</td></tr>';
				}
				echo '</tbody></table>';
			} else {
				echo '<p class="chyba">Pole '.$nazev.' je prázdné.</p>';
			}
		} else {
			echo '<p class="chyba">'.$nazev.' není pole.</p>';
		}
	}

	vypisPole('REQUEST',$_REQUEST);
	vypisPole('GET',$_GET);
	vypisPole('POST',$_POST);
?>
</body>
</html>
