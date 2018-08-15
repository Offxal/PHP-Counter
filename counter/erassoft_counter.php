<?php
#############################################################################################
# Copyright des PHP-Counter Scripts by ErasSoft.de (2014)                                   #
# Scriptänderungen dürfen nur mit Einzugsermächtigung von Eras vorgenommen werden           #
#############################################################################################

class erassoft_counter{
  public function get_ip(){
    if (getenv("REMOTE_ADDR") == "::1"){
      $ip = "127.0.0.1";
    }
    else{
      $ip = getenv("REMOTE_ADDR");
    }
    return $ip;
  }
  public function get_referer(){
    if(isset($_SERVER['HTTP_REFERER'])){
      $referer = $_SERVER['HTTP_REFERER'];
    }
    else{
      $referer = "-";
    }
    return $referer;
  }
  public function get_date(){
    $timestamp = time();
    return date("Ymd",$timestamp);
  }
  public function get_time(){
    $timestamp = time();
    return date("H:i:s",$timestamp);
  }
  public function get_useragent(){
    if(isset($_SERVER['HTTP_USER_AGENT'])){
      $useragent = $_SERVER['HTTP_USER_AGENT'];
    }
    else{
      $useragent = "-";
    }
    return $useragent;
  }
  public function read_today($counteras_ordner){
    return file("$counteras_ordner/counter-heute.dat");
  }
  public function read_yesterday($counteras_ordner){
    return file("$counteras_ordner/counter-gestern.dat");
  }
  public function read_insum($counteras_ordner){
    return file("$counteras_ordner/counter-insgesamt.dat");
  }
  public function read_user($counteras_user){
    return file("$counteras_user");
  }
  public function convert_Ymd_to_dmY($lesaus){
    $userdatumJAHR  = substr($lesaus, 0, 4);
    $userdatumMONAT = substr($lesaus, 4, 2);
    $userdatumTAG   = substr($lesaus, 6, 2);
    return "$userdatumTAG.$userdatumMONAT.$userdatumJAHR";
  }
  public function write_user($counteras_ordner,$counteras_user,$datenmUSER){
    $dateiUSER = fopen("$counteras_ordner/$counteras_user","a+");
    fwrite($dateiUSER,$datenmUSER);
    fclose($dateiUSER);
    return true;
  }
  public function write_yesterday($counteras_ordner,$datenmGESTERN){
    $dateiGESTERN = fopen("$counteras_ordner/counter-gestern.dat","w");
    fwrite($dateiGESTERN,$datenmGESTERN);
    fclose($dateiGESTERN);
    return true;
  }
  public function write_today($counteras_ordner,$datenmHEUTE){
    $dateiHEUTE = fopen("$counteras_ordner/counter-heute.dat","w");
    fwrite($dateiHEUTE,$datenmHEUTE);
    fclose($dateiHEUTE);
    return true;
  }
  public function write_insum($counteras_ordner,$datenmINSGESAMT){
    $dateiINSGESAMT = fopen("$counteras_ordner/counter-insgesamt.dat","w");
    fwrite($dateiINSGESAMT,$datenmINSGESAMT);
    fclose($dateiINSGESAMT);
    return true;
  }
  public function write_ip($counteras_ordner,$counteras_ips,$ip,$datenm){
    $file = fopen("$counteras_ordner/$counteras_ips/$ip.ip","w");
    fwrite($file,$datenm);
    fclose($file);
    return true;
  }
  public function read_ips($counteras_ordner,$counteras_ips){
    $ordner=opendir ("$counteras_ordner/$counteras_ips");
    $by = 0;
    while ($file = readdir ($ordner)) {
      if($file != "." && $file != "..") {
        $alleDateien[$by] = $file;
        $by++;
      }
    }
    closedir($ordner);
    return $alleDateien;
  }
  public function get_length($alleDateien){
    return count($alleDateien);
  }
  public function delete_ips($counteras_ordner,$counteras_ips){
    $alleDateien = $this->read_ips($counteras_ordner,$counteras_ips);
    $länge = $this->get_length($alleDateien);

    for($i=0; $i<$länge; $i++){
      unlink("$counteras_ordner/$counteras_ips/$alleDateien[$i]");
    }

    $lesaus[0]="0";
    $lesaus[1]=$this->get_date();

    $dateiHEUTE = fopen("$counteras_ordner/counter-heute.dat","w");
    $datenmHEUTE="$lesaus[0]\n$lesaus[1]";
    fwrite($dateiHEUTE,$datenmHEUTE);
    fclose($dateiHEUTE);
    return true;
  }
  public function send_emailuser($adress, $betreff, $daten){
    $text="Ein neuer User mit folgenden Daten: \n".$daten;

    mail($adress, $betreff, $text, "From: Counter Skript <$adress>");
    return true;
  }
  public function start_counter(){
echo"
<!-- Counter Beginn -->
<!-- (c)ErasSoft.de -->
";
    return true;
  }
  public function ende_counter(){
echo"
<!-- (c)ErasSoft.de -->
<!-- Counter Ende -->
";
    return true;
  }
  public function get_os($agent){
    $os   ="Unbekannt";
    if     (strstr($agent, "Googlebot"))     $os="Googlebot";
    elseif (strstr($agent, "Windows 95"))    $os="Windows 95";
    elseif (strstr($agent, "Windows 98"))    $os="Windows 98";
    elseif (strstr($agent, "NT 4.0"))        $os="Windows NT ";
    elseif (strstr($agent, "NT 5.0"))        $os="Windows 2000";
    elseif (strstr($agent, "NT 5.1"))        $os="Windows XP";
    elseif (strstr($agent, "NT 5.2"))        $os="Windows XP";
    elseif (strstr($agent, "NT 6.0"))        $os="Windows Vista";
    elseif (strstr($agent, "NT 6.1"))        $os="Windows 7";
    elseif (strstr($agent, "NT 6.2"))        $os="Windows 8";
    elseif (strstr($agent, "NT 6.3"))        $os="Windows 8";
    elseif (strstr($agent, "NT 6.4"))        $os="Windows 9";
    elseif (strstr($agent, "NT 7.0"))        $os="Windows 9";
    elseif (strstr($agent, "NT 7.1"))        $os="Windows 9";
    elseif (strstr($agent, "Windows Phone")) $os="Windows Phone";
    elseif (strstr($agent, "NT"))            $os="Windows";
    elseif (strstr($agent, "Windows"))       $os="Windows";

    elseif (strstr($agent, "Linux"))         $os="Linux";
    elseif (strstr($agent, "Unix"))          $os="Unix";
    elseif (strstr($agent, "Debian"))        $os="Debian";
    elseif (strstr($agent, "openSuse"))      $os="openSUSE";
    elseif (strstr($agent, "Kubuntu"))       $os="Kubuntu";
    elseif (strstr($agent, "Ubuntu"))        $os="Ubuntu";
    elseif (strstr($agent, "Fedora"))        $os="Fedora";
    elseif (strstr($agent, "Mandriva"))      $os="Mandriva";

    elseif (strstr($agent, "PlayStation Portable")) $os="PSP";
    elseif (strstr($agent, "PLAYSTATION 2")) $os="PS2";
    elseif (strstr($agent, "PLAYSTATION 3")) $os="PS3";
    elseif (strstr($agent, "PLAYSTATION 4")) $os="PS4";
    elseif (strstr($agent, "PLAYSTATION"))   $os="PS";

    elseif (strstr($agent, "BlackBerry"))    $os="BlackBerry";
    elseif (strstr($agent, "Vodafone"))      $os="Vodafone";
    elseif (strstr($agent, "Android"))       $os="Android";
    elseif (strstr($agent, "iPhone"))        $os="iOS";
    elseif (strstr($agent, "iPad"))          $os="iOS";
    elseif (strstr($agent, "Mac"))           $os="Mac OS";
    elseif (strstr($agent, "CrOS"))          $os="Chrome OS";
    elseif (strstr($agent, "Firefox"))       $os="Firefox OS";

    return $os;
  }
  public function get_browser($agent){
    $browser = "Unbekannt";
    if (strpos($agent, "Mozilla/5.0"))       {$browser = "Mozilla";}
    if (strpos($agent, "Iceweasel"))         {$browser = "Iceweasel";}
    if (strpos($agent, "Mozilla/4"))         {$browser = "Netscape";}
    if (strpos($agent, "Mozilla/3"))         {$browser = "Netscape";}
    if (strpos($agent, "Firefox") || strpos($agent, "Firebird")) {$browser = "Firefox";}
    if (strpos($agent, "MSIE")) {$browser = "IE";}
    if (strpos($agent, "Netscape"))          {$browser = "Netscape";}
    if (strpos($agent, "Opera"))             {$browser = "Opera";}
    if (strpos($agent, "Presto"))            {$browser = "Opera";}
    if (strpos($agent, "Camino"))            {$browser = "Camino";}
    if (strpos($agent, "Galeon"))            {$browser = "Galeon";}
    if (strpos($agent, "Konqueror"))         {$browser = "Konqueror";}
    if (strpos($agent, "Safari"))            {$browser = "Safari";}
    if (strpos($agent, "Chrome"))            {$browser = "Chrome";}
    if (strpos($agent, "OmniWeb"))           {$browser = "OmniWeb";}
    if (strpos($agent, "SeaMonkey"))         {$browser = "SeaMonkey";}
    if (strpos($agent, "PlayStation Portable")) {$browser = "PSP";}
    if (strpos($agent, "PLAYSTATION 2"))     {$browser = "PS2";}
    if (strpos($agent, "PLAYSTATION 3"))     {$browser = "PS3";}
    if (strpos($agent, "PLAYSTATION 4"))     {$browser = "PS4";}
    if (strpos($agent, "PLAYSTATION"))       {$browser = "PS";}
    if (strpos($agent, "Obigo"))             {$browser = "Obigo";}
    if (strpos($agent, "Googlebot"))         {$browser = "Googlebot";}

    return $browser;
  }

}
?>