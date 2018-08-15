<?php
#############################################################################################
# Copyright des PHP-Counter Scripts by ErasSoft.de (2014)                                   #
# Scriptänderungen dürfen nur mit Einzugsermächtigung von Eras vorgenommen werden           #
#############################################################################################


#############################################################################################
#                                    WICHTIG                                                #
# Mit dem PHP-Befehl:                                                                       #
# include("counter/counter.php");                                                           #
# oder als Bild                                                                             #
# <img src="bild.php" />                                                                    #
# kann der Counter angezeigt werden                                                         #
#############################################################################################


#############################################################################################
#                                Dateien Überblick:                                         #
#                                                                                           #
#   erassoft_counter.php = API-Klasse für alle Funktionen des Counters                      #
#   ausgabe.php   = Dort wird der Counter ausgegeben, das Copyright Zeichen und der Link    #
#                   dazu muss innerhalb des Counters gut sichtbar bestehen bleiben          #
#   settings.php  = Dort kann man den Counter einstellen, Ordnername, PW usw.               #
#   style.php     = Hier kann man das Style/CSS des Counters ändern                         #
#   admin.php     = Ist der Zugangsweg zur Statistischen Ausgabe (PW nötig)                 #
#   index.php     = Es kann eine index.php Seite erstellt werden, ohne das sie gelöscht wird#
#                                                                                           #
#   counter-gestern.dat    = Steht die Besucheranzahl von gestern drin                      #
#   counter-heute.dat      = Steht die aktuelle Besucheranzahl von heute drin               #
#   counter-insgesamt.dat  = Steht die insgesammte Besucheranzahl drin                      #
#   user.dat               = Stehen die Anzahl der Besucher der vergangenen Tage drin       #
#                                                                                           #
#   ips/           = In diesem Ordner stehen die ips der User drin für einen Tag            #
#                                                                                           #
# Alle Dateien und auch der Ordner müssen mit allen Rechten ausgestattest sein (Leserechte, #
# Schreibrechte, Ausführrechte) kurz: 777 oder rwxrwxrwx, damit dieses Script funktioniert! #
#############################################################################################






#################################################
### PHP-Counter - Script Beginn - (c)ErasSoft ###
#################################################

// WICHTIG!!! Einbinden der API Klasse
include_once('erassoft_counter.php');
$ecnt = new erassoft_counter();

if(!isset($counteras_bild)){
  $ecnt->start_counter();
}

# Einstellungen laden
include("counter/settings.php");
# IP-Adresse auslesen
$ip = $ecnt->get_ip();
# Datum und Uhrzeit
$datum = $ecnt->get_date();
$uhrzeit = $ecnt->get_time();
# Das heute File auslesen
$lesaus = $ecnt->read_today($counteras_ordner); //counter-heute.dat

# Wenn neuer Tag ist!
if($datum > $lesaus[1]){
  $userdatum = $ecnt->convert_Ymd_to_dmY($lesaus[1]);
  $datenmUSER = "$userdatum - User: $lesaus[0]";
  $ecnt->write_user($counteras_ordner,$counteras_user,$datenmUSER); //user.dat
  $datenmGESTERN = "$lesaus[0]$lesaus[1]";
  $ecnt->write_yesterday($counteras_ordner,$datenmGESTERN); //counter-gestern.dat
  $ecnt->delete_ips($counteras_ordner,$counteras_ips); // $counteras_ips
}


# Überprüfen ob IP-Datei schon erstellt ist
if(file_exists("$counteras_ordner/$counteras_ips/$ip.ip")){
  $lesausGESTERN = $ecnt->read_yesterday($counteras_ordner); //counter-gestern.dat
  $lesausHEUTE = $ecnt->read_today($counteras_ordner); //counter-heute.dat
  $lesausINSGESAMT = $ecnt->read_insum($counteras_ordner); //counter-insgesamt.dat
}
# Ab hier beginnt die Verneinung - sprich 'die Datei $ip.ip existiert nicht'
else{
  # Informationen über User auslesen
  $sonstiges = $ecnt->get_useragent();
  $agent = $ecnt->get_useragent();

  # Spambots mitzählen/nicht mitzählen
  $nicht_zaehlen=0;
  if($counteras_spambot==FALSE){
    # Prüft erstes Zeichen von $agent auf Zahl oder Buchstabe
    # Zahl = Spambot, Buchstabe = Normal
    # 1 = Spambot,    0 = Normal
    $nicht_zaehlen=is_numeric($agent[0]);
  }

  # Betriebssystem und Browser ermitteln
  $os = $ecnt->get_os($agent);
  $browser = $ecnt->get_browser($agent);
  # Referer auslesen (Seite die vorher war)
  $referer = $ecnt->get_referer();

  # Googlebot mitzählen/nicht mitzählen (Speichert aber IP zur Auswertung)
  if($counteras_googlebot==FALSE){
    if($os=="Googlebot"){
      $nicht_zaehlen = 1;
    }
  }

  # Unbekannte OS mitzählen/nicht mitzählen (Speichert aber IP zur Auswertung)
  if($counteras_unbekannt==FALSE){
    if($os=="Unbekannt"){
      $nicht_zaehlen=1;
    }
  }

  # Bei bestimmten Sachen nicht +1 erhöhen, aber Eintrag speichern!
  if($nicht_zaehlen==0){
    $lesausGESTERN = $ecnt->read_yesterday($counteras_ordner); //counter-gestern.dat
    $lesausHEUTE = $ecnt->read_today($counteras_ordner); //counter-heute.dat
    $lesausINSGESAMT = $ecnt->read_insum($counteras_ordner); //counter-insgesamt.dat

    $lesausHEUTE[0]=$lesausHEUTE[0]+1;
    $lesausINSGESAMT[0]=$lesausINSGESAMT[0]+1;

    $datenmHEUTE="$lesausHEUTE[0]\n$datum";
    $ecnt->write_today($counteras_ordner,$datenmHEUTE); //counter-heute.dat
    $datenmINSGESAMT="$lesausINSGESAMT[0]\n$datum";
    $ecnt->write_insum($counteras_ordner,$datenmINSGESAMT); //counter-insgesamt.dat
  }

  # IP + Informationen speichern
  $datenm="IP-Adresse:\n$ip\nBetriebssystem:\n$os\nBrowser:\n$browser\nSonstiges:\n$sonstiges\nUhrzeit:\n$uhrzeit\nReferer:\n$referer";
  $ecnt->write_ip($counteras_ordner,$counteras_ips,$ip,$datenm); //ip.ip

  if($counteras_emailuser){
    $datenm="\nDatum: ".date("d.m.Y",time() )."\nUhrzeit: $uhrzeit\nIP-Adresse: $ip\nBetriebssystem: $os\nBrowser: $browser\nSonstiges: $sonstiges\nReferer: $referer";
    $ecnt->send_emailuser($counteras_emailadress, $counteras_emailbetreff, $datenm);
  }
}

if(!isset($counteras_bild)){
  # Ausgabe der Tabelle mit den Counter Infos
  include("$counteras_ordner/ausgabe.php");
  $ecnt->ende_counter();
}

###############################################
### PHP-Counter - Script Ende - (c)ErasSoft ###
###############################################
?>