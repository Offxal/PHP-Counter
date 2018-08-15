<?php

// Variablen belegen
if(isset($_POST))
{
  $format=$_POST["format"];
  $daten=$_POST["daten"];
  $texte=$_POST["texte"];
  $farben=$_POST["farben"];
  $titel=$_POST["titel"];
  $svgfile=$_POST["svgfile"];

  if($format=="kreis")
  {
    $cx=$_POST["cx"];
    $cy=$_POST["cy"];
    $r=$_POST["r"];
  }
  elseif($format=="balken")
  {
    $x=$_POST["x"];
    $y=$_POST["y"];
    $balkenhoehe=$_POST["balkenhoehe"];
    $maxbreite=$_POST["maxbreite"];
  }
}
elseif(isset($HTTP_POST_VARS))
{
  $format=$HTTP_POST_VARS["format"];
  $daten=$HTTP_POST_VARS["daten"];
  $texte=$HTTP_POST_VARS["texte"];
  $farben=$HTTP_POST_VARS["farben"];
  $titel=$HTTP_POST_VARS["titel"];
  $svgfile=$HTTP_POST_VARS["svgfile"];

  if($format=="kreis")
  {
    $cx=$HTTP_POST_VARS["cx"];
    $cy=$HTTP_POST_VARS["cy"];
    $r=$HTTP_POST_VARS["r"];
  }
  elseif($format=="balken")
  {
    $x=$HTTP_POST_VARS["x"];
    $y=$HTTP_POST_VARS["y"];
    $balkenhoehe=$HTTP_POST_VARS["balkenhoehe"];
    $maxbreite=$HTTP_POST_VARS["maxbreite"];
  }
}


if(count($daten)==0 || count($texte)==0 || count($farben)==0)
{
Header("Location: index.php");
exit();
}

Header("Cache-control: private, no-cache, must-revalidate");
Header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
Header("Date: Sat, 01 Jan 2000 00:00:00 GMT");
Header("Pragma: no-cache");

// Array-Daten bearbeiten (leere Datensaetze entfernen)
$test=array_count_values($farben);
$anz=15-$test["#"]; 

$daten=array_slice($daten,0,$anz);   
$texte=array_slice($texte,0,$anz);   
$farben=array_slice($farben,0,$anz);   

// SVG-Modul einbinden
include("svgmod.php");

if($format=="kreis")
{
// Ausgabedatei
$svgfile="kreisdiagramm.svg";

// Aufruf der Funktion Kreisdiagramm()
$check=Kreisdiagramm($cx,$cy,$r,$daten,$texte,$farben,$titel,$svgfile);
}
else
{
// Ausgabedatei
$svgfile="balkendiagramm.svg";

// Aufruf der Funktion Balkendiagramm()
$check=Balkendiagramm($x,$y,$balkenhoehe,$maxbreite,$daten,$texte,$farben,$titel,$svgfile);
}

if($check==1)
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.:. SVG::PHP - SVG-Diagramme mit PHP generieren .:.</title>
<meta name="Author" content="Dr. Thomas Meinike - thomas@handmadecode.de">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<meta http-equiv="expires" content="Sat, 01 Jan 2000 00:00:00 GMT"> 
<meta http-equiv="cache-control" content="no-cache">
<meta http-equiv="pragma" content="no-cache">
</head>
<body style="margin: 0; padding: 0; overflow-y: auto">
<?php

$ua=$HTTP_SERVER_VARS["HTTP_USER_AGENT"];
if(strstr($ua,"Gecko") && !strstr($ua,"rv:0."))
{
  print "<iframe id=\"dynsvg\" src=\"$svgfile"."?generated=".time()."\" width=\"100%\" height=\"100%\" frameborder=\"0\">\n";
  print "  <p>Zur Anzeige der Grafik wird der <a href=\"http://www.adobe.com/svg/viewer/install/main.html\">SVG Viewer von Adobe</a> ben&ouml;tigt!</p>\n";
  print "</iframe>\n";
}
else
{
  print "<object id=\"dynsvg\" data=\"$svgfile"."?generated=".time()."\" width=\"100%\" height=\"100%\" type=\"image/svg+xml\">\n";
  print "  <p>Zur Anzeige der Grafik wird der <a href=\"http://www.adobe.com/svg/viewer/install/main.html\">SVG Viewer von Adobe</a> ben&ouml;tigt!</p>\n";
  print "</object>\n";
}

?>
</body>
</html>
<?php
}
else
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>.:. SVG::PHP - SVG-Diagramme mit PHP generieren .:.</title>
<meta name="Author" content="Dr. Thomas Meinike - thomas@handmadecode.de">
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
</head>
<body>
<p><b>Die SVG-Grafik konnte nicht generiert werden.</b></p>
</body>
</html>
<?php
}
?>
