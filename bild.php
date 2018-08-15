<?php
# Pfad zum Counter!
$counteras_bild = true;
include("counter/counter.php");



### Das Bild erzeugen ###
# Grφίe des Bildes
$size_x = 100;
$size_y = 75;
# Erstelle einen Hintergrund
$bild = imagecreatefrompng("hg.png");
# Verteile die Farben
$farbe  = imageColorAllocate($bild, 0, 0, 0); // Textfarbe

$pos_x = 0; // links
$pos_y = 0; // oben
$text = "Besucher";
imageString($bild, 3, $pos_x, $pos_y, $text, $farbe);

$pos_y += 15; // oben
$text = "Gestern: ".trim($lesausGESTERN[0]);
imageString($bild, 2, $pos_x, $pos_y, $text, $farbe);

$pos_y += 15; // oben
$text = "Heute: ".trim($lesausHEUTE[0]);
imageString($bild, 2, $pos_x, $pos_y, $text, $farbe);

$pos_y += 15; // oben
$text = "Gesamt: ".trim($lesausINSGESAMT[0]);
imageString($bild, 2, $pos_x, $pos_y, $text, $farbe);



# Sende "browser header"
header("Content-Type: image/png");
# Sende das Bild zum Browser
echo imagePNG($bild);
# Lφsche das Bild
imageDestroy($bild);
?>