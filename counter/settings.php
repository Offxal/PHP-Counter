<?php
#############################################################################################
# Copyright des PHP-Counter Scripts by ErasSoft.de (2014)                                   #
# Scriptänderungen dürfen nur mit Einzugsermächtigung von Eras vorgenommen werden           #
#############################################################################################

# Hier kann man den Counter einstellen



$counteras_ordner      = "counter";  # Ordner wählen (Standard: "counter")
$counteras_ips         = "ips";      # Ordner für die IP-Adressen (Standard: "ips")
$counteras_user        = "user.dat"; # Datei für die Useranzahl (Standard: "user.dat")
$counteras_passwort    = "admin";    # Passwort für Admin Bereich wählen (admin.php)
$counteras_newadmin    = true;       # Standardmäßig neue Grafik?      false - Tabelle mit allen Infos als erstes anzeigen
                                     #                                 true  - Grafische Ausgabe als erstes anzeigen (SVG-Diagramme)

$counteras_visible     = true;       # Counter anzeigen?               false - Counter unsichtbar machen
                                     #                                 true  - Counter anzeigen
$counteras_punktformat = true;       # Ausgabezahlen mit Trennzeichen? false - Ohne Trennzeichen
                                     # Bsp: 1.000.000                  true  - Mit 1000er Trennzeichen
$counteras_spambot     = false;      # Multi Spambots mitzählen?       false - Keine Spambots
                                     #                                 true  - Spambots
$counteras_googlebot   = false;      # Googlebot mitzählen?            false - Kein Googlebot
                                     #                                 true  - Googlebot
$counteras_unbekannt   = false;      # Unbekannte mitzählen?           false - Keine mit Unbekannten OS
                                     #                                 true  - Unbekannte mitzählen
$counteras_box         = true;      # Box um den Counter anzeigen?    false - Keine Box anzeigen
                                     #                                 true  - Box anzeigen

// E-Mail Optionen
$counteras_emailadress = "max@mustermann.de";
$counteras_emailbetreff= "Counter Skript Bericht vom ".date("d.m.Y",time() );
$counteras_emailuser   = false;      # Schick eine E-Mail pro User     false - Keine E-Mail senden
                                     #                                 true  - Sendet eine E-Mail bei jeden Eintrag!

// admin.php --> Listenfarbe
$counteras_rowcolor1   = "#D0D0D0";  # Listenfarbe für den Kopfbereich
$counteras_rowcolor2   = "#F0F0F0";  # Listenfarbe für die erste Zeile
$counteras_rowcolor3   = "#E0E0E0";  # Listenfarbe für jede zweite Zeile

?>