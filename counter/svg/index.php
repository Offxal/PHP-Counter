<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.:. SVG::PHP - SVG-Diagramme mit PHP generieren .:.</title>
<meta name="Author" content="Dr. Thomas Meinike - thomas@handmadecode.de">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<link rel="stylesheet" href="styles.css" type="text/css" media="all">
<script language="JavaScript" src="scripts.js" type="text/javascript"></script>
</head>
<body onload="Init()">
<h2>SVG::PHP &#8211; Dateneingabe</h2>
<form action="gosvg.php" method="post">
<fieldset>
<legend>Vorgaben</legend>
<br>
Titel:&nbsp;<input class="titel" type="text" name="titel" size="30" value="SVG-Kreisdiagramm"><br><br>
<input class="radbtn" type="radio" name="format" value="kreis" checked onclick="SetKreis()">&nbsp;Kreisdiagramm&nbsp;&nbsp;
cx:&nbsp;<input class="vorgaben" type="text" name="cx" size="5" value="200">&nbsp;&nbsp;cy:&nbsp;<input class="vorgaben" type="text" name="cy" size="5" value="200">&nbsp;&nbsp;r:&nbsp;<input class="vorgaben" type="text" name="r"  size="5" value="100">&nbsp;<br><br>
<input class="radbtn" type="radio" name="format" value="balken" onclick="SetBalken()">&nbsp;Balkendiagramm&nbsp;&nbsp;
x:&nbsp;<input class="vorgaben" type="text" name="x" size="5" value="50">&nbsp;&nbsp;y:&nbsp;<input class="vorgaben" type="text" name="y" size="5" value="50">&nbsp;&nbsp;Balkenh&ouml;he:&nbsp;<input class="vorgaben" type="text" name="balkenhoehe" size="5" value="15">&nbsp;&nbsp;max. Balkenbreite (=100%):&nbsp;<input class="vorgaben" type="text" name="maxbreite" size="5" value="800"><br>
</fieldset>
<br>
<fieldset>
<legend>Daten</legend>
<table summary="Dateneingabe" border="0">
<tr><th>Nr.</th><th>Wert</th><th>Beschreibung</th><th>Farbe</th></tr>
<tr><td>01</td><td><input type="text" name="daten[]" value="30.36"></td><td><input type="text" name="texte[]" value="Text 1"></td><td><input type="text" name="farben[]" value="#FFFFCC"></td></tr>
<tr><td>02</td><td><input type="text" name="daten[]" value="15"></td><td><input type="text" name="texte[]" value="Text 2"></td><td><input type="text" name="farben[]" value="#FF0000"></td></tr>
<tr><td>03</td><td><input type="text" name="daten[]" value="25.1"></td><td><input type="text" name="texte[]" value="Text 3"></td><td><input type="text" name="farben[]" value="#009900"></td></tr>
<tr><td>04</td><td><input type="text" name="daten[]" value="20.97"></td><td><input type="text" name="texte[]" value="Text 4"></td><td><input type="text" name="farben[]" value="#0000CC"></td></tr>
<tr><td>05</td><td><input type="text" name="daten[]" value="10.8"></td><td><input type="text" name="texte[]" value="Text 5"></td><td><input type="text" name="farben[]" value="#000000"></td></tr>
<tr><td>06</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>07</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>08</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>09</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>10</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>11</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>12</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>13</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>14</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
<tr><td>15</td><td><input type="text" name="daten[]"></td><td><input type="text" name="texte[]"></td><td><input type="text" name="farben[]" value="#"></td></tr>
</table>
<p class="hinweis"><b>Hinweis:</b>&nbsp;Diese Anwendung dient zum Testen eines PHP-Moduls mit SVG-Funktionen. Spezielle Pr&uuml;fungen der Eingabedaten finden nicht statt, d.&nbsp;h. es werden sinnvolle Daten erwartet. Geben Sie die Daten aufeinander folgend ab Datensatz Nr. 01 ein. Jeder Datensatz muss dabei aus (positivem) Wert, Beschreibung und Farbe bestehen. Leere Datens&auml;tze werden durch das #-Zeichen im Feld Farbe erkannt.&nbsp;&copy;&nbsp;<a href="mailto:thomas@handmadecode.de">Dr. Thomas Meinike 2002</a></p>
</fieldset>
<br>
<input class="diagramm" type="submit" value="Diagramm erstellen">&nbsp;&nbsp;&nbsp;<input class="anfang" type="reset" value="Anfangswerte" onclick="if(!window.opera){document.forms[0].elements[1].value='SVG-Kreisdiagramm';for(i=7;i<=10;i++)document.forms[0].elements[i].disabled=true;for(i=3;i<=5;i++)document.forms[0].elements[i].disabled=false}">
</form>
</body>
</html>
