<?php
#############################################################################################
# Copyright des PHP-Counter Scripts by ErasSoft.de (2014)                                   #
# Scriptänderungen dürfen nur mit Einzugsermächtigung von Eras vorgenommen werden           #
#############################################################################################

# Hier kann man das Aussehen des Counters ändern
# Das Copyright Zeichen, sowie der link zu ErasSoft Homepage müssen innerhalb des Counters gut sichtbar bleiben!
# Infos:
# $lesausGESTERN[0]   = Besucheranzahl Gestern
# $lesausHEUTE[0]     = Besucheranzahl Heute
# $lesausINSGESAMT[0] = Besucheranzahl Insgesamt

$ErasSoft_copyright = "<div class=\"copyright\"><a target=\"_blank\" href=\"http://erassoft.de\">&copy;ErasSoft</a></div>";
if($counteras_punktformat == true){
  $lesausGESTERN[0] = intval($lesausGESTERN[0]);
  $lesausHEUTE[0] = intval($lesausHEUTE[0]);
  $lesausINSGESAMT[0] = intval($lesausINSGESAMT[0]);
  $lesausGESTERN[0] = number_format($lesausGESTERN[0], 0, ",", ".");
  $lesausHEUTE[0] = number_format($lesausHEUTE[0], 0, ",", ".");
  $lesausINSGESAMT[0] = number_format($lesausINSGESAMT[0], 0, ",", ".");
}

if($counteras_visible){
  if($counteras_box == true) {
    include("style.php") ;
    echo "<div class=\"counteras_box\">";
  }

### Hier kann die HTML Ausgabe angepasst werden... ###
?>

<b><u>Besucher</u></b><br>
<b>Gestern: </b><?php echo "$lesausGESTERN[0]"; ?><br>
<b>Heute: </b><?php echo $lesausHEUTE[0]; ?><br>
<b>Gesamt: </b><?php echo $lesausINSGESAMT[0]; ?><br>
<?php echo $ErasSoft_copyright; ?>



<?php
  if($counteras_box == TRUE) {
    echo "</div>" ;
  }
}
?>