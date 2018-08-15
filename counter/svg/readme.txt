Update 1.06 des Moduls SVG::PHP (C) 2002-2005 by Dr. Thomas Meinike
zum Artikel "Grafik-Tagwerk" in Internet Professionell 7/2002

Siehe:
------
- http://www.vnunet.de/internet-pro/webdesign/detail.asp?ArticleID=3589 (Artikel)
- http://www.vnunet.de/internet-pro/download/06_02_downloads/0702svg/0702svg.zip (Code)

Neu:
----
- Behebung des Absturzproblems von Mozilla 1.x mit dem Adobe SVG Viewer
  durch Ausgabe der SVG-Dokumente in einem IFRAME
  [vgl. http://www.styleassistant.de/tips/tip91.htm]
- Einsatz von Filtern beim Balkendiagramm
- Verwendung der globalen PHP-Arrays $HTTP_POST_VARS[] bzw. $_POST[]
- Tooltips werden nicht mehr mit skaliert
- Kreissegmente werden bei Mausklick verschoben
- Daten werden als Fließkommawerte behandelt
- Anzahl der Nachkommastellen kann festgelegt werden (Vorgabe: $NKS=2)
- Anpassungen für Firefox 1.5 (12/05)

Update:
-------
- Austauschen der enthaltenen Dateien unterhalb von http://hostname/svg/diagramm/
- $BASEURL in svgmod.php entsprechend anpassen: $BASEURL="http://hostname/svg/diagramm/";
