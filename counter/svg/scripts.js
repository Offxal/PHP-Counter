/*************************************************************************\
|   SVG::PHP - scripts.js - JavaScript-Funktionen fuer die Dateneingabe   |
| (C) by Dr. Thomas Meinike 02/02 - http://www.datenverdrahten.de/svgphp/ |
\*************************************************************************/

var d;

function Init()
{
  d=document.forms["0"];

  if(d.elements["format"][0].checked)
  {
    d.elements["x"].disabled=true;
    d.elements["y"].disabled=true;
    d.elements["balkenhoehe"].disabled=true;
    d.elements["maxbreite"].disabled=true;
  }

  if(d.elements["format"][1].checked)
  {
    d.elements["cx"].disabled=true;
    d.elements["cy"].disabled=true;
    d.elements["r"].disabled=true;
  }
}

function SetKreis()
{
  if(d.elements["titel"].value=="SVG-Balkendiagramm")d.elements["titel"].value="SVG-Kreisdiagramm";

  d.elements["x"].disabled=true;
  d.elements["y"].disabled=true;
  d.elements["balkenhoehe"].disabled=true;
  d.elements["maxbreite"].disabled=true;

  d.elements["cx"].disabled=false;
  d.elements["cy"].disabled=false;
  d.elements["r"].disabled=false;
}

function SetBalken()
{
  if(d.elements["titel"].value=="SVG-Kreisdiagramm")d.elements["titel"].value="SVG-Balkendiagramm";

  d.elements["x"].disabled=false;
  d.elements["y"].disabled=false;
  d.elements["balkenhoehe"].disabled=false;
  d.elements["maxbreite"].disabled=false;

  d.elements["cx"].disabled=true;
  d.elements["cy"].disabled=true;
  d.elements["r"].disabled=true;
}
