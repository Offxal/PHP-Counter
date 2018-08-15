<?php

/****************************************************\
|                                                    |
| SVG::PHP-Modul by Dr. Thomas Meinike 02/02...12/05 |
|                                                    |
\****************************************************/

// Modulname
$MODUL_NAME="SVG::PHP-Modul";

// Modulversion
$MODUL_VERSION="1.06";

// letzte Aenderung des Moduls
$MODUL_LAST_MODIFIED=date("d.m.Y - H:i:s",filemtime('svg/svgmod.php'));

// Basis-URL - wird fuer externe CSS- und JavaScript-Dateien benoetigt
$BASEURL="http://www.datenverdrahten.de/svgphp/";

// Anzahl der Nachkommastellen fuer Koordinaten- und Laengenangaben
$NKS=0;


/**************************\
|                          |
| Funktion Kreisdiagramm() |
|                          |
|--------------------------|
| Parameter:               |
| x-Mittelpunkt    Float   |
| y-Mittelpunkt    Float   |
| Radius           Float   |
| Daten            Array   |
| Texte            Array   |
| Farben           Array   |
| Titel            String  |
| SVG-Dateiname    String  |
\**************************/

function Kreisdiagramm($fl_cx,$fl_cy,$fl_r,$arr_daten,$arr_texte,$arr_farben,$str_titel,$str_svgfile)
{
global $MODUL_NAME;
global $MODUL_VERSION;
global $MODUL_LAST_MODIFIED;
global $BASEURL;
global $NKS;

$svgout=fopen($str_svgfile,"w");
flock($svgout,2);

$out="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>
<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" 
  \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">
<?xml-stylesheet href=\"".$BASEURL."svgphp.css\" type=\"text/css\"?>
<!-- saved from $MODUL_NAME $MODUL_VERSION | (C) by Dr. Thomas Meinike | last modified: $MODUL_LAST_MODIFIED -->

<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" onload=\"Init(evt)\" onzoom=\"ZoomControl()\">

<title>$str_titel</title>
<desc>Das Kreisdiagramm wurde dynamisch aus den Daten generiert.</desc>

<defs>
<script xlink:href=\"".$BASEURL."svgphp.js\" type=\"text/javascript\"/>
</defs>\n";

// Startpunkt fuer Ueberschrift
$top=$fl_cy-1.50*$fl_r;
$left=$fl_cx-$fl_r;
$out.="  <text x=\"".number_format($left,$NKS)."\" y=\"".number_format($top,$NKS)."\" style=\"fill: #FF0000; font-size: 20px; text-anchor: left\">$str_titel</text>\n";

$out.="\n<!-- Kreisdiagramm - Beginn -->\n";

// Variablen fuer Kreismittelpunkt, Radius und Start-/Endwinkel des ersten Punktes
$kreisx=$fl_cx;
$kreisy=$fl_cy-20;
$kreisr=$fl_r;
$startwinkel=270;
$endwinkel=270;

// Startpunkt fuer Legende
$top=$fl_cy-$fl_r-20;
$left=$fl_cx+$fl_r+50;

$anzahl=count($arr_daten);
$summe=0;
for($i=0;$i<$anzahl;$i++)
{
  $summe+=$arr_daten[$i];
}

for($i=0;$i<$anzahl;$i++)
{
  $startwinkel=$endwinkel;
  $winkelanteil=$arr_daten[$i]/$summe*360;
  $endwinkel=$startwinkel+$winkelanteil;

  // Startpunkt auf Kreisbahn
  $punktx=number_format(cos(deg2rad($startwinkel))*$kreisr+$kreisx,$NKS);
  $punkty=number_format(sin(deg2rad($startwinkel))*$kreisr+$kreisy,$NKS);

  // Pfad erstellen und ausgeben
  $out.="<g onmousemove=\"ShowTooltip(evt,'$arr_texte[$i]');SetOpacity(evt,'0.5')\" onmouseout=\"HideTooltip();SetOpacity(evt,'1.0')\">\n";
  $out.="  <path d=\"M ".number_format($kreisx,$NKS).",".number_format($kreisy,$NKS)." L $punktx,$punkty";

  // Endpunkt auf Kreisbahn
  $punktx=number_format(cos(deg2rad($endwinkel))*$kreisr+$kreisx,$NKS);
  $punkty=number_format(sin(deg2rad($endwinkel))*$kreisr+$kreisy,$NKS);

  if($winkelanteil<=180)
  {
    $arcflags="0,1";
  }
  else
  {
    $arcflags="1,1";
  }

  $out.=" A ".number_format($kreisr,$NKS).",".number_format($kreisr,$NKS)." 0 $arcflags $punktx,$punkty Z\"";

  // Punkt fuer Verschiebung des Kreisbogens beim Anklicken
  $diagonal=15;
  $dx=number_format(cos(deg2rad($endwinkel-$winkelanteil/2))*$diagonal+$kreisx-2*$kreisr,$NKS);
  $dy=number_format(sin(deg2rad($endwinkel-$winkelanteil/2))*$diagonal+$kreisx-2*$kreisr,$NKS);
  $out.=" style=\"fill: $arr_farben[$i]\" onclick=\"MoveArc(evt,$dx,$dy)\"/>\n";

  // Legende - Farbkasten 
  $out.="  <rect x=\"".number_format((float)$left,$NKS)."\" y=\"".number_format((float)($top+$i*20),$NKS)."\" width=\"30\" height=\"12\" style=\"fill: $arr_farben[$i]\"/>\n";
  // Legende - Beschriftung
  $out.="  <text x=\"".number_format((float)($left+40),$NKS)."\" y=\"".number_format((float)($top+$i*20+10),$NKS)."\" style=\"font-size: 12px; text-anchor: right\">$arr_texte[$i] [".number_format($arr_daten[$i],$NKS)." | ".number_format($arr_daten[$i]/$summe*100,$NKS)."%]</text>\n";

  $out.="</g>\n";
}

$out.="<!-- Kreisdiagramm - Ende -->\n";

// Startpunkt fuer Status-Informationen
$top=number_format($fl_cy+1.3*$fl_r,$NKS);
$left=number_format($fl_cx-$fl_r,$NKS);

$LAST_MODIFIED=date("d.m.Y &#8211; H:i:s",time());

$out.="\n<!-- Tooltip - Beginn (ttr=Tooltip-Rechteck, ttt=Tooltip-Text) -->
<g id=\"tooltip\">
  <rect id=\"ttr\" x=\"0.00\" y=\"0.00\" rx=\"5.00\" ry=\"5.00\" width=\"100.00\" height=\"16.00\" style=\"visibility: hidden\"/>
  <text id=\"ttt\" x=\"0.00\" y=\"0.00\" style=\"visibility: hidden\">dyn. Text</text>
</g>
<!-- Tooltip - Ende -->\n";

$out.="\n</svg>\n";
fwrite($svgout,$out);
flock($svgout,3);
fclose($svgout);

return 1;
}


/***************************\
|                           |
| Funktion Balkendiagramm() |
|                           |
|---------------------------|
| Parameter:                |
| x-Start           Float   |
| y-Start           Float   |
| Balkenhoehe       Float   |
| max. Breite=100%  Float   |
| Daten             Array   |
| Texte             Array   |
| Farben            Array   |
| Titel             String  |
| SVG-Dateiname     String  |
\***************************/

function Balkendiagramm($fl_x,$fl_y,$fl_balkenhoehe,$fl_maxbreite,$arr_daten,$arr_texte,$arr_farben,$str_titel,$str_svgfile)
{
global $MODUL_NAME;
global $MODUL_VERSION;
global $MODUL_LAST_MODIFIED;
global $BASEURL;
global $NKS;

$svgout=fopen($str_svgfile,"w");
flock($svgout,2);

$out="<?xml version=\"1.0\" encoding=\"ISO-8859-1\" standalone=\"no\"?>
<!DOCTYPE svg PUBLIC \"-//W3C//DTD SVG 1.0//EN\" 
  \"http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd\">
<?xml-stylesheet href=\"".$BASEURL."svgphp.css\" type=\"text/css\"?>
<!-- saved from $MODUL_NAME $MODUL_VERSION | (C) by Dr. Thomas Meinike | last modified: $MODUL_LAST_MODIFIED -->

<svg xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" onload=\"Init(evt);SetPosition()\" onzoom=\"ZoomControl()\">

<title>$str_titel</title>
<desc>Das Balkendiagramm wurde dynamisch aus den Daten generiert.</desc>

<defs>
<script xlink:href=\"".$BASEURL."svgphp.js\" type=\"text/javascript\"/>
<filter id=\"flt\">
  <feGaussianBlur in=\"SourceAlpha\" stdDeviation=\"0.5\" result=\"blur\"/>
  <feSpecularLighting in=\"blur\" surfaceScale=\"5\" specularConstant=\"0.5\"	specularExponent=\"10\" result=\"specOut\" style=\"lighting-color:rgb(255,255,255)\">
    <fePointLight x=\"-5000\" y=\"-5000\" z=\"5000\"/>
  </feSpecularLighting>
  <feComposite in=\"specOut\" in2=\"SourceAlpha\" operator=\"in\" result=\"specOut2\"/>
  <feComposite in=\"SourceGraphic\" in2=\"specOut2\" operator=\"arithmetic\" k1=\"0\" k2=\"1\" k3=\"1\" k4=\"0\"/>
</filter>
</defs>\n";

// Startpunkt fuer Ueberschrift
$top=$fl_y;
$left=$fl_x;
$out.="  <text x=\"".number_format($left,$NKS)."\" y=\"".number_format($top,$NKS)."\" style=\"fill: #FF0000; font-size: 20px; text-anchor: left\">$str_titel</text>\n";

$out.="\n<!-- Balkendiagramm - Beginn -->\n";

$anzahl=count($arr_daten);
$summe=0;
for($i=0;$i<$anzahl;$i++)
{
  $summe+=$arr_daten[$i];
}

for($i=0;$i<$anzahl;$i++)
{
  // Balken zeichnen mit Animation (10 s)
  $balkenbreite=number_format((float)($arr_daten[$i]/$summe*$fl_maxbreite),$NKS);
  $balkenleft=number_format($left+100,$NKS);
  $balkentop=number_format((float)($top+30+$i*1.5*$fl_balkenhoehe),$NKS);
  $textleft1=number_format($left,$NKS);
  $textleft2=number_format($balkenleft+10+$balkenbreite,$NKS);
  $texttop=number_format((float)($top+30+$i*1.5*$fl_balkenhoehe+0.75*$fl_balkenhoehe),$NKS);

  $out.="<g onmousemove=\"ShowTooltip(evt,'$arr_texte[$i]');SetOpacity(evt,'0.5')\" onmouseout=\"HideTooltip();SetOpacity(evt,'1.0')\">\n";
  // $out.="  <rect x=\"$balkenleft\" y=\"$balkentop\" width=\"0\" height=\"$fl_balkenhoehe\" style=\"fill: $arr_farben[$i]; filter: url(#flt)\">
  $out.="  <rect x=\"$balkenleft\" y=\"$balkentop\" width=\"$balkenbreite\" height=\"$fl_balkenhoehe\" style=\"fill: $arr_farben[$i]; filter: url(#flt)\">
    <animate attributeName=\"width\" attributeType=\"XML\" begin=\"0s\" dur=\"1s\" fill=\"freeze\" from=\"0\" to=\"$balkenbreite\"/>
  </rect>\n";

  // Balken-Beschriftung (Beschreibungstexte)
  $out.="  <text x=\"$textleft1\" y=\"$texttop\" style=\"font-size: 12px; text-anchor: right\">$arr_texte[$i]</text>\n";

  // Balken-Beschriftung (Prozentwerte erscheinen nach 12 s)
  $out.="  <text x=\"$textleft2\" y=\"$texttop\" style=\"font-size: 12px; text-anchor: right; visibility: hidden\">[".number_format($arr_daten[$i],$NKS)." | ".number_format($arr_daten[$i]/$summe*100,2)."%]
    <animate attributeName=\"visibility\" attributeType=\"CSS\" begin=\"1s\" dur=\"1s\" fill=\"freeze\" from=\"hidden\" to=\"visible\" calcMode=\"discrete\"/>
  </text>\n";

  $out.="</g>\n";
}

$out.="<!-- Balkendiagramm - Ende -->\n";

// Status-Informationen
$top=number_format($balkentop+3*$fl_balkenhoehe,$NKS);
$left=number_format($textleft1,$NKS);

$LAST_MODIFIED=date("d.m.Y &#8211; H:i:s",time());

$out.="\n<!-- Tooltip - Beginn (ttr=Tooltip-Rechteck, ttt=Tooltip-Text) -->
<g id=\"tooltip\">
  <rect id=\"ttr\" x=\"0.00\" y=\"0.00\" rx=\"5.00\" ry=\"5.00\" width=\"100.00\" height=\"16.00\" style=\"visibility: hidden\"/>
  <text id=\"ttt\" x=\"0.00\" y=\"0.00\" style=\"visibility: hidden\">dyn. Text</text>
</g>
<!-- Tooltip - Ende -->\n";

$out.="\n</svg>\n";
fwrite($svgout,$out);
flock($svgout,3);
fclose($svgout);

return 1;
}

?>
