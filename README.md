# PHP-Counter
Ein Counter für die eigene Homepage.

Dieser Counter basiert auf PHP und kommt ohne Datenbank aus (z.B. MySQL).
Er zählt die gestrigen, die heutigen und die Gesamtbesucheranzahl.
Der Counter hat einen Schutz vor Massenspam und eine IP-Sperre, sodass diese nicht mitgezählt werden.
Außerdem kann der Webmaster mittels dieses Counters eine Übersicht erstellen, welche verschiedene Informationen ausgibt, wie welches Betriebssystem oder Browser an diesem Tag am häufigsten genutzt wurde.

Anwendung:
Lade den Counter auf deine Homepage hoch, am besten in einen Ordner namens: counter
Nutze den PHP-Befehl:
<?php include("counter/counter.php"); ?>
Und schon wird der Counter auf der Seite angezeigt.
Beispiele für die Nutzung sind im Zip Paket mitgeliefert.
Einstellungen anpassen in der Settings.php Datei!

Funktionsweise:
Das Programm speichert die IP und ein paar weitere Informationen in Form einer Textdatei und man kann diese auch wieder auslesen.
Am nächsten Tag werden alle IPs und Informationen resetet.
Deswegen sollten alle Dateien und der Ordner Vollzugriff, Schreib- und Leserechte haben. (z.B. 777)

Achtung: Benutzen des Counters auf eigene Gefahr, wir übernehmen keine Haftung für eventuelle Schäden, die durch dieses Script hervorgerufen wurden.
