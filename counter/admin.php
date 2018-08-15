<?php
#############################################################################################
# Copyright des PHP-Counter Scripts by ErasSoft.de (2014)                                   #
# Scriptänderungen dürfen nur mit Einzugsermächtigung von Eras vorgenommen werden           #
#############################################################################################



session_start();

// WICHTIG!!! Einbinden der API Klasse
include_once('erassoft_counter.php');
$ecnt = new erassoft_counter();


# Einstellungen laden
include("settings.php");

# Logout Funktion
if(isset($_GET['logout'])){
  unset($_SESSION['password']);
  header("Status: 301 Moved Permanently"); header("Location:admin.php");
}
# Nach Abschicken das Passwort in Session speichern
if(isset($_POST['password'])){
  $_SESSION['password']=$_POST['password'];
  unset($_SESSION['input_password']);
}
# Passwort in Textbox automatisch eintragen durch den Aufruf: admin.php?pw=PASSWORT
if(isset($_GET['pw'])){
  $_SESSION['input_password']=$_GET['pw'];
  header("Status: 301 Moved Permanently"); header("Location:admin.php");
}
# Wenn Passwort Falsch ist, Session löschen und einen Fehler ausgeben
$fehler = false;
if(isset($_SESSION['password'])){
  if($_SESSION['password']!=$counteras_passwort){
    unset($_SESSION['password']);
    $fehler = true;
  }
}



# AUSGABE
if(!isset($_SESSION['password'])){
  # Login Maske
  if(isset($_SESSION['input_password'])){
    $pw = $_SESSION['input_password'];
  }
  else{
    $pw = "";
  }
?>
<html>
<head><title>Counter Administration</title></head>
<body bgcolor="#eeeeee">
  <center>
  <font size="4"><b>Login:</b></font><br><br>
    <form action="" method="POST">
    Passwort:
    <input value="<?php echo $pw ?>" type="password" name="password">
    <input type="submit" value="Login">
    </form>
    <?php if($fehler){echo"<font color=\"#FF0000\"><b>Falsches Passwort!</b></font>";} ?>
  </center>
</body>
</html>
<?php

}
else if($_SESSION['password']==$counteras_passwort){
  # Zugriff: Tabelle mit Informationen
  # Informationen über User auslesen
  $your_ip = $ecnt->get_ip();
  $your_sonstiges=$ecnt->get_useragent();

  # Statistik Beginn
  $ordner=opendir ($counteras_ips);

  $by = 0;
  while ($file = readdir ($ordner)){
    if($file != "." && $file != ".."){
      $alleDateien[$by] = $file;
      $leseaus =  file($counteras_ips."/".$alleDateien[$by]);
      $ip[$by] = $leseaus[1];
      $os[$by] = $leseaus[3];
      $browser[$by] = $leseaus[5];
      $sonstige[$by] = $leseaus[7];
      $uhrzeit[$by] = $leseaus[9];
      $referer[$by] = $leseaus[11];
      $by++;
    }
  }
  $länge = $by;
  closedir($ordner);

  # Bubble Sort (absteigend sortiert)
  for($i=0; $i<$länge; $i++){
    for($by=0; $by<$länge-1; $by++){
      $test1 = str_replace(":","",$uhrzeit[$by]);
      $test2 = str_replace(":","",$uhrzeit[$by+1]);
      if($test1<$test2){
        $hilfsfeld=$uhrzeit[$by];
        $uhrzeit[$by]=$uhrzeit[$by+1];
        $uhrzeit[$by+1]=$hilfsfeld;

        $hilfsfeld=$ip[$by];
        $ip[$by]=$ip[$by+1];
        $ip[$by+1]=$hilfsfeld;

        $hilfsfeld=$os[$by];
        $os[$by]=$os[$by+1];
        $os[$by+1]=$hilfsfeld;

        $hilfsfeld=$browser[$by];
        $browser[$by]=$browser[$by+1];
        $browser[$by+1]=$hilfsfeld;

        $hilfsfeld=$sonstige[$by];
        $sonstige[$by]=$sonstige[$by+1];
        $sonstige[$by+1]=$hilfsfeld;

        $hilfsfeld=$referer[$by];
        $referer[$by]=$referer[$by+1];
        $referer[$by+1]=$hilfsfeld;
      }
    }
  }

  echo"
<html>
<head><title>Counter-Statistik</title></head>
<body"; if($counteras_newadmin){echo" onload=\"show('table','graphic')\" ";} echo">
  <script type=\"text/javascript\">
  function show(tab1,tab2) {
    if (document.getElementById) {
      if (document.getElementById(tab1).style.display == \"block\") {
        document.getElementById(tab1).style.display = \"none\";
        document.getElementById(tab2).style.display = \"block\";
        document.getElementById('change').src=\"svg/text.png\";
        document.getElementById('change').title=\"Tabellen anzeigen\";
        window.location = '#grafik';
      }
      else{
        document.getElementById(tab1).style.display = \"block\";
        document.getElementById(tab2).style.display = \"none\";
        document.getElementById('change').src=\"svg/diagramm.png\";
        document.getElementById('change').title=\"Diagramme anzeigen\";
        window.location = '#tabelle';
      }
    }
  }
  </script>
<a title=\"Logout\" href=\"?logout\"><img src=\"svg/logout.png\"></a> <a style=\"text-decoration:underline; cursor:pointer;\" onclick=\"show('table','graphic')\"><img title=\"Diagramme anzeigen\" id=\"change\" src=\"svg/diagramm.png\"></a>
<div id=\"table\" style=\"display:block\">
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";

$row_color = $counteras_rowcolor1;
  echo"
  <tr>
   <td bgcolor=\"".$row_color."\" colspan=\"7\"><div align=\"center\"><b>Übersicht</b> <i>(absteigend sortiert)</i></div></td>
  </tr>
  <tr>
   <td bgcolor=\"".$row_color."\" width=\"40\"><b>IDs</b></td>
   <td bgcolor=\"".$row_color."\" width=\"75\"><b>Uhrzeit</b></td>
   <td bgcolor=\"".$row_color."\" width=\"110\"><b>IP-Adressen</b></td>
   <td bgcolor=\"".$row_color."\" width=\"120\"><b>Betriebssysteme</b></td>
   <td bgcolor=\"".$row_color."\" width=\"80\"><b>Browser</b></td>
   <td bgcolor=\"".$row_color."\" width=\"110\"><b>Referer</b></td>
   <td bgcolor=\"".$row_color."\"><b>Weitere Informationen</b></td>
  </tr>";
$row_color = $counteras_rowcolor2;
echo"
  <tr>
   <td bgcolor=\"".$row_color."\"><div align=\"right\"><b>You</b></div></td>
   <td bgcolor=\"".$row_color."\"><div align=\"center\"><b>".date("H:i:s",time() )."</b></div></td>
   <td bgcolor=\"".$row_color."\"><b>$your_ip</b></td>
   <td bgcolor=\"".$row_color."\"><b>".$ecnt->get_os($ecnt->get_useragent() )."</b></td>
   <td bgcolor=\"".$row_color."\"><b>".$ecnt->get_browser($ecnt->get_useragent() )."</b></td>
   <td bgcolor=\"".$row_color."\"><b>-</b></td>
   <td bgcolor=\"".$row_color."\"><b>$your_sonstiges</b></td>
  </tr>";


  $id = $länge;
  $i_os = 0;
  $i_browser = 0;
  for($i=0; $i<$länge; $i++){
    if($i%2){
      $row_color = $counteras_rowcolor2;
    }
    else{
      $row_color = $counteras_rowcolor3;
    }
    echo"
  <tr>
   <td bgcolor=\"".$row_color."\"><div align=\"right\"><b>$id</b></div></td>
   <td bgcolor=\"".$row_color."\"><div align=\"center\">$uhrzeit[$i]</div></td>
   <td bgcolor=\"".$row_color."\"><a target=\"_blank\" href=\"http://www.ip-adress.com/ip_lokalisieren/$ip[$i]\">$ip[$i]</a></td>
   <td bgcolor=\"".$row_color."\">$os[$i]</td>
   <td bgcolor=\"".$row_color."\">$browser[$i]</td>";

    if($referer[$i]=='-'){
      echo"<td bgcolor=\"".$row_color."\">-</td>";
    }
    else{
      # Referer auf 32 Stellen ändern
      $referer_kurz = substr($referer[$i], 0, 18);
      echo"<td bgcolor=\"".$row_color."\"><a target=\"_blank\" href=\"$referer[$i]\">$referer_kurz</a></td>";
    }

    echo"
   <td bgcolor=\"".$row_color."\">$sonstige[$i]</td>
  </tr>";

    $id=$id-1;
  }
  echo"
</table><br><br>";


########################################################################################

  echo"
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
  <tr>
   <td valign=\"top\">";

# Betriebssystem

$row_color = $counteras_rowcolor1;
  echo"
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
  <tr>
   <td bgcolor=\"".$row_color."\" colspan=\"2\" width=\"150\"><div align=\"center\"><b>Betriebssysteme</b></div></td>
  </tr>";

  $i3=0;      
  for($i2=0; $i2<$länge; $i2++){
    if($i2==0){
      $row_color = $counteras_rowcolor2;
    }
    else{
      if($i2%2){
        $row_color = $counteras_rowcolor3;
      }
      else{
        $row_color = $counteras_rowcolor2;
      }
    }

    $osAnz[$i2]=0;
    $bed=0;
    for($test=0; $test<$i2; $test++){
      if($os[$test]==$os[$i2]){
        $test=$i2;
        $bed=1;
      }
    }
    if($bed==0){
      if($i3==0){
        $row_color = $counteras_rowcolor2;
      }
      else{
        if($i3%2){
          $row_color = $counteras_rowcolor3;
        }
        else{
          $row_color = $counteras_rowcolor2;
        }
      }
      $i3++;
      echo"
  <tr>
   <td bgcolor=\"".$row_color."\"><b>$os[$i2]</b></td>";

      for($i=0; $i<$länge; $i++){
        if($os[$i]==$os[$i2]){
          $osAnz[$i2]=$osAnz[$i2]+1;
        }
      }
      echo"
   <td bgcolor=\"".$row_color."\" width=\"50\"><div align=\"right\">$osAnz[$i2]</div></td>
  </tr>";

      if(trim($os[$i2]) != "Unbekannt" && trim($os[$i2]) != "Googlebot"){
        $diagramm_os_name[$i_os] = $os[$i2];
        $diagramm_os_anz[$i_os] = $osAnz[$i2];
        $i_os++;
      }
    }
  }
  echo"
</table>
   </td>
   <td width=\"50\"></td>
   <td valign=\"top\">";


# Browser

$row_color = $counteras_rowcolor1;
  echo"
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
  <tr>
   <td bgcolor=\"".$row_color."\" colspan=\"2\" width=\"150\"><div align=\"center\"><b>Browser</b></div></td>
  </tr>";

  $i3=0;
  for($i2=0; $i2<$länge; $i2++){
    $browserAnz[$i2]=0;
    $bed=0;
    for($test=0; $test<$i2; $test++){
      if($browser[$test]==$browser[$i2]){
        $test=$i2;
        $bed=1;
      }
    }
    if($bed==0){
      if($i3==0){
        $row_color = $counteras_rowcolor2;
      }
      else{
        if($i3%2){
          $row_color = $counteras_rowcolor3;
        }
        else{
          $row_color = $counteras_rowcolor2;
        }
      }
      $i3++;
      echo"
  <tr>
   <td bgcolor=\"".$row_color."\" width=\"100\"><b>$browser[$i2]</b></td>";

      for($i=0; $i<$länge; $i++){
        if($browser[$i]==$browser[$i2]){
          $browserAnz[$i2]=$browserAnz[$i2]+1;
        }
      }
      echo"
   <td bgcolor=\"".$row_color."\" width=\"50\"><div align=\"right\">$browserAnz[$i2]</div></td>
  </tr>";

      if(trim($browser[$i2]) != "Unbekannt" && trim($browser[$i2]) != "Googlebot"){
        $diagramm_browser_name[$i_browser] = $browser[$i2];
        $diagramm_browser_anz[$i_browser] = $browserAnz[$i2];
        $i_browser++;
      }
    }
  }
  echo"
</table>
   </td>
   <td width=\"50\"></td>
   <td valign=\"top\">";

# Statistik

$row_color = $counteras_rowcolor1;
  echo"
<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
  <tr>
   <td bgcolor=\"".$row_color."\" colspan=\"2\" width=\"250\"><div align=\"center\"><b>Statistik</b></div></td>
  </tr>";

  $teo=0;
  $statistikeAnz=0;
  for($i2=0; $i2<$länge; $i2++){
    $bed=0;
    for($test=0; $test<$i2; $test++){
      if($sonstige[$test]==$sonstige[$i2]){
        $test=$i2;
        $bed=1;
      }
    }
    if($bed==0){
      $teo=$teo+1;
      for($i=0; $i<$länge; $i++){
        if($sonstige[$i]==$sonstige[$i2]){
          $statistikAnz[$i2]=$statistikeAnz[$i2]+1;
        }
      }
    }
  }
  $besucher=$länge;
  $lesausHEUTE =  file("counter-heute.dat");
  echo"
  <tr>
   <td bgcolor=\"".$counteras_rowcolor2."\" width=\"150\"><b>Besucher verschiedene</b></td>
   <td bgcolor=\"".$counteras_rowcolor2."\" width=\"50\"><div align=\"right\">$teo</div></td>
  </tr>
  <tr>
   <td bgcolor=\"".$counteras_rowcolor3."\" width=\"150\"><b>Besucher gezählt</b></td>
   <td bgcolor=\"".$counteras_rowcolor3."\" width=\"50\"><div align=\"right\">$lesausHEUTE[0]</div></td>
  </tr>
  <tr>
   <td bgcolor=\"".$counteras_rowcolor2."\" width=\"150\"><b>Besucher insgesamt</b></td>
   <td bgcolor=\"".$counteras_rowcolor2."\" width=\"50\"><div align=\"right\">$besucher</div></td>
  </tr>
 </table>
   </td>
  </tr>
</table>
</div>
<div id=\"graphic\" style=\"display:none\">";



  // SVG-Modul einbinden
  include_once("svg/svgmod.php");
  $cx=200;
  $cy=200;
  $r=100;

  # Bubble Sort (absteigend sortiert)
  for($i=0; $i<count($diagramm_os_anz); $i++){
    for($by=0; $by<count($diagramm_os_anz)-1; $by++){
      $test1 = $diagramm_os_anz[$by];
      $test2 = $diagramm_os_anz[$by+1];
      if($test1<$test2){
        $hilfsfeld=$diagramm_os_anz[$by];
        $diagramm_os_anz[$by]=$diagramm_os_anz[$by+1];
        $diagramm_os_anz[$by+1]=$hilfsfeld;

        $hilfsfeld=$diagramm_os_name[$by];
        $diagramm_os_name[$by]=$diagramm_os_name[$by+1];
        $diagramm_os_name[$by+1]=$hilfsfeld;
      }
    }
  }
  for($i=0; $i<count($diagramm_os_name); $i++){
    switch(trim($diagramm_os_name[$i]) ){
    case 'Unbekannt':     { $farben[$i]="#000000"; break;}
    case 'Googlebot':     { $farben[$i]="#FFFF00"; break;}
    case 'Windows 95':    { $farben[$i]="#C0C0C0"; break;}
    case 'Windows 98':    { $farben[$i]="#C0C0C0"; break;}
    case 'Windows NT':    { $farben[$i]="#969696"; break;}
    case 'Windows 2000':  { $farben[$i]="#808080"; break;}
    case 'Windows XP':    { $farben[$i]="#009900"; break;}
    case 'Windows Vista': { $farben[$i]="#33CCCC"; break;}
    case 'Windows 7':     { $farben[$i]="#0000FF"; break;}
    case 'Windows 8':     { $farben[$i]="#FF6600"; break;}
    case 'Windows 8.1':   { $farben[$i]="#FF6600"; break;}
    case 'Windows 9':     { $farben[$i]="#009900"; break;}
    case 'Windows Phone': { $farben[$i]="#33CCCC"; break;}
    case 'Windows':       { $farben[$i]="#003366"; break;}
    case 'Linux':         { $farben[$i]="#FFFF00"; break;}
    case 'Unix':          { $farben[$i]="#FFFF00"; break;}
    case 'Debian':        { $farben[$i]="#F9432F"; break;}
    case 'openSuse':      { $farben[$i]="#99CC00"; break;}
    case 'Kubuntu':       { $farben[$i]="#CCFFFF"; break;}
    case 'Ubuntu':        { $farben[$i]="#FFFF99"; break;}
    case 'Fedora':        { $farben[$i]="#FFFF00"; break;}
    case 'Mandriva':      { $farben[$i]="#FFFF00"; break;}
    case 'PSP':           { $farben[$i]="#808080"; break;}
    case 'PS':            { $farben[$i]="#001428"; break;}
    case 'PS2':           { $farben[$i]="#001428"; break;}
    case 'PS3':           { $farben[$i]="#001428"; break;}
    case 'PS4':           { $farben[$i]="#001428"; break;}
    case 'BlackBerry':    { $farben[$i]="#333333"; break;}
    case 'Vodafone':      { $farben[$i]="#FF0000"; break;}
    case 'Android':       { $farben[$i]="#008000"; break;}
    case 'iOS':           { $farben[$i]="#FF5555"; break;}
    case 'Mac OS':        { $farben[$i]="#AA55FF"; break;}
    case 'Chrome OS':     { $farben[$i]="#D5D500"; break;}
    case 'Firefox OS':    { $farben[$i]="#FF6600"; break;}
    default:              { $farben[$i]="#000000"; }
    }
  }
  $titel="Betriebssysteme";
  // Ausgabedatei
  $svgfile="svg/betriebssysteme.svg";
  // Aufruf der Funktion Kreisdiagramm()
  $check=Kreisdiagramm($cx,$cy,$r,$diagramm_os_anz,$diagramm_os_name,$farben,$titel,$svgfile);
  echo "<object id=\"dynsvg1\" data=\"$svgfile"."?generated=".time()."\" width=\"560\" height=\"300\" type=\"image/svg+xml\">\n";
  echo "</object>\n";


  # Bubble Sort (absteigend sortiert)
  for($i=0; $i<count($diagramm_browser_anz); $i++){
    for($by=0; $by<count($diagramm_browser_anz)-1; $by++){
      $test1 = $diagramm_browser_anz[$by];
      $test2 = $diagramm_browser_anz[$by+1];
      if($test1<$test2){
        $hilfsfeld=$diagramm_browser_anz[$by];
        $diagramm_browser_anz[$by]=$diagramm_browser_anz[$by+1];
        $diagramm_browser_anz[$by+1]=$hilfsfeld;

        $hilfsfeld=$diagramm_browser_name[$by];
        $diagramm_browser_name[$by]=$diagramm_browser_name[$by+1];
        $diagramm_browser_name[$by+1]=$hilfsfeld;
      }
    }
  }
  for($i=0; $i<count($diagramm_browser_name); $i++){
    switch(trim($diagramm_browser_name[$i]) ){
    case 'Unbekannt':     { $farben[$i]="#000000"; break;}
    case 'Googlebot':     { $farben[$i]="#FFFF00"; break;}
    case 'Mozilla':       { $farben[$i]="#FF6600"; break;}
    case 'Iceweasel':     { $farben[$i]="#FF6600"; break;}
    case 'Netscape':      { $farben[$i]="#FF6600"; break;}
    case 'Firefox':       { $farben[$i]="#FF6600"; break;}
    case 'IE':            { $farben[$i]="#0000FF"; break;}
    case 'Opera':         { $farben[$i]="#B30000"; break;}
    case 'Camino':        { $farben[$i]="#969696"; break;}
    case 'Galeon':        { $farben[$i]="#969696"; break;}
    case 'Konqueror':     { $farben[$i]="#3366FF"; break;}
    case 'Safari':        { $farben[$i]="#99CC00"; break;}
    case 'Chrome':        { $farben[$i]="#D5D500"; break;}
    case 'OmniWeb':       { $farben[$i]="#969696"; break;}
    case 'SeaMonkey':     { $farben[$i]="#00CCFF"; break;}
    case 'PSP':           { $farben[$i]="#808080"; break;}
    case 'PS':            { $farben[$i]="#001428"; break;}
    case 'PS2':           { $farben[$i]="#001428"; break;}
    case 'PS3':           { $farben[$i]="#001428"; break;}
    case 'PS4':           { $farben[$i]="#001428"; break;}
    case 'Obigo':         { $farben[$i]="#969696"; break;}
    default:              { $farben[$i]="#000000"; }
    }
  }
  $titel="Browser";
  // Ausgabedatei
  $svgfile="svg/browser.svg";
  // Aufruf der Funktion Kreisdiagramm()
  $check=Kreisdiagramm($cx,$cy,$r,$diagramm_browser_anz,$diagramm_browser_name,$farben,$titel,$svgfile);
  echo "<object id=\"dynsvg1\" data=\"$svgfile"."?generated=".time()."\" width=\"560\" height=\"300\" type=\"image/svg+xml\">\n";
  echo "</object>\n";

  $farben[0]="#0000CC";
  $farben[1]="#FF00FF";
  $stat_daten[0] = (float) $lesausHEUTE[0];
  $stat_daten[1] = (float) $besucher - $lesausHEUTE[0];
  $stat_text[0] = "Gezählte besucher";
  $stat_text[1] = "Andere (Bots...)";
  $titel="Besucher";
  // Ausgabedatei
  $svgfile="svg/besucher.svg";
  // Aufruf der Funktion Kreisdiagramm()
  $check=Kreisdiagramm($cx,$cy,$r,$stat_daten,$stat_text,$farben,$titel,$svgfile);
  echo "<object id=\"dynsvg1\" data=\"$svgfile"."?generated=".time()."\" width=\"560\" height=\"300\" type=\"image/svg+xml\">\n";
  echo "</object>\n";



  $user = $ecnt->read_user($counteras_user); //user.dat
  $user_length = $ecnt->get_length($user);
  $user_daten[0] = (float) $lesausHEUTE[0];
  $user_text[0] = $ecnt->convert_Ymd_to_dmY($ecnt->get_date() );
  $i=1;
  for($j=$user_length-1; $j>=0; $j--){
    $teilung = explode(":", $user[$j]);;
    $user_daten[$i] = trim($teilung[1]);
    $teilung = explode("-", $user[$j]);;
    $user_text[$i] = trim($teilung[0]);
    $i++;
    if($i>=10){break;}
  }






  $x=50;
  $y=50;
  $balkenhoehe=15;
  $maxbreite=600;
  $farben[0]="#0000CC";
  $farben[1]="#FF00FF";
  $farben[2]="#009900";
  $farben[3]="#FF0000";
  $farben[4]="#D5D500";
  $farben[5]="#B30000";
  $farben[6]="#0000FF";
  $farben[7]="#99CC00";
  $farben[8]="#FF6600";
  $farben[9]="#33CCCC";
  $titel="Anzahl der Besucher der letzten Tage";
  // Ausgabedatei
  $svgfile="svg/user.svg";
  // Aufruf der Funktion Balkendiagramm()
  $check=Balkendiagramm($x,$y,$balkenhoehe,$maxbreite,$user_daten,$user_text,$farben,$titel,$svgfile);
  echo "<object id=\"dynsvg1\" data=\"$svgfile"."?generated=".time()."\" width=\"560\" height=\"300\" type=\"image/svg+xml\">\n";
  echo "</object>\n";

  echo"
</div>
</body>
</html>";
}
?>