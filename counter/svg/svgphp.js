/****************************************************************************\
|           svgphp.js - JavaScript-Funktionen fuer SVG-Diagramme             |
| (C) by Dr. Thomas Meinike 02-12/05 - http://www.datenverdrahten.de/svgphp/ |
\****************************************************************************/


  var svgdoc,svgroot;


  function Init(load_evt)
  {
    svgdoc=load_evt.target.ownerDocument;
    svgroot=svgdoc.documentElement;
  }


  function SetPosition()
  {
    // alle Rechteck-Balken am breitesten Beschreibungstext (maxbreite) ausrichten
   
    var gelemente, anzahl, links, rect_x, text_x, maxbreite=30, abstand=10;
    gelemente=svgdoc.getElementsByTagName("g");
    anzahl=gelemente.length-1;

    links=parseInt(gelemente.item(0).getElementsByTagName("text").item(0).getAttribute("x"));

    for(i=0;i<anzahl;i++)
    {
      textbreite=gelemente.item(i).getElementsByTagName("text").item(0).getComputedTextLength();
      if(textbreite>maxbreite)maxbreite=textbreite;
    }

    rect_x=links+abstand+maxbreite;

    for(i=0;i<anzahl;i++)
    {
      gelemente.item(i).getElementsByTagName("rect").item(0).setAttribute("x",rect_x);
      text_x=rect_x+abstand+parseInt(gelemente.item(i).getElementsByTagName("rect").item(0).getElementsByTagName("animate").item(0).getAttribute("to"));
      gelemente.item(i).getElementsByTagName("text").item(1).setAttribute("x",text_x);
    }
  }


  function ShowTooltip(e,txt)
  {
    var ttrelem, ttrelem, posx, posy, curtrans, ctx, cty;

    ttrelem=svgdoc.getElementById("ttr");
    tttelem=svgdoc.getElementById("ttt");
    tttelem.childNodes.item(0).data=txt;
    posx=e.clientX;
    posy=e.clientY;
    curtrans=svgroot.currentTranslate;
    ctx=curtrans.x;
    cty=curtrans.y;
    ttrelem.setAttribute("x",posx-ctx);
    ttrelem.setAttribute("y",posy-cty-20);
    tttelem.setAttribute("x",posx-ctx+5);
    tttelem.setAttribute("y",posy-cty-8);
    ttrelem.setAttribute("width",tttelem.getComputedTextLength()+10);
    tttelem.setAttribute("style","fill: #0000CC; font-size: 11px; visibility: visible");
    ttrelem.setAttribute("style","fill: #FFFFCC; stroke: #000000; stroke-width: 0.5px; visibility: visible");
    // Fix fuer Corel SVG Viewer - nochmaliges Zuweisen des Textinhalts
    tttelem.childNodes.item(0).data=txt;
  }


  function HideTooltip()
  {
    var ttrelem, ttrelem;
    ttrelem=svgdoc.getElementById("ttr");
    tttelem=svgdoc.getElementById("ttt");
    ttrelem.setAttribute("style","visibility: hidden");
    tttelem.setAttribute("style","visibility: hidden");
  }


  function SetOpacity(e,op)
  {
    e.target.parentNode.setAttribute("fill-opacity",op);
  }


  function TextHover(objid,color,decor)
  {
    var element;

    element=svgdoc.getElementById(objid);
    element.setAttribute("style","font-size: 12px; fill: "+color+"; text-decoration: "+decor);
  }

  
  function ZoomControl()
  {
    var curzoom;

    curzoom=svgroot.currentScale;
    svgdoc.getElementById("tooltip").setAttribute("transform","scale("+1/curzoom+")");
  }


  function MoveArc(click_evt,dx,dy)
  {
    var obj,e,f;

    obj=click_evt.target;

    e=Math.abs(obj.getCTM().e);
    f=Math.abs(obj.getCTM().f);

    if(e < 1 || f < 1)
    {
      obj.setAttribute("transform","translate("+dx+","+dy+")");
      obj.setAttribute("stroke","#000000");
    }
    else
    {
      obj.setAttribute("transform","translate("+1/dx+","+1/dy+")");
      obj.setAttribute("stroke","#FFFFFF");
    }
  }
