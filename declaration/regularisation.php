<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<title>Régularisation</title>

<head>
<style>

* {
	vertical-align: baseline;
	font-weight: inherit;
	font-family: inherit;
	font-style: inherit;
	font-size: 100%;
	border: 0 none;
	outline: 0;
	padding: 0;
	margin: 0;
	}
  
body {margin-top: 0px;margin-left: 0px;}

#mainreg {
    padding: 0 !important;
    padding-top: 3px !important;
    min-height: 281mm;
    width: 210mm;
    position: relative;
    margin: 20px auto 80px auto !important;
    -moz-box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7) !important;
    -webkit-box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7) !important;
    box-shadow: 0px 0px 20px rgba(80, 80, 80, 0.7) !important;
    border: none;
}

#page_1 {position:relative; overflow: hidden;margin: 11px 0px 25px 13px;padding: 0px;border: none;width: 780px;}
#page_1 #id_1 {border:none;margin: 64px 0px 0px 20px;padding: 0px;border:none;width: 760px;overflow: hidden;}
#page_1 #id_2 {border:none;margin: 0px 0px 0px 10px;padding: 0px;border:none;width: 770px;overflow: hidden;}
#page_1 #id_2 #id_2_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 8px;overflow: hidden;}
#page_1 #id_2 #id_2_2 {float:left;border:none;margin: 39px 0px 0px 8px;padding: 0px;border:none;width: 754px;overflow: hidden;}

#page_1 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:745px;height:1069px;}
#page_1 #dimg1 #img1 {width:745px;height:1069px;max-height: 1069px;}

.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

.ft0{font: bold 16px 'Times';line-height: 19px;}
.ft1{font: 1px 'Times';line-height: 1px;}
.ft2{font: 16px 'Times';line-height: 19px;}
.ft3{font: bold 16px 'Times';color: #003365;line-height: 19px;}
.ft4{font: 16px 'Times';color: #003365;line-height: 19px;}
.ft5{font: 1px 'Times';line-height: 14px;}
.ft6{font: 1px 'Times';line-height: 5px;}
.ft7{font: 1px 'Times';line-height: 9px;}
.ft8{font: bold 1px 'Times';line-height: 2px;}
.ft9{font: bold 5px 'Times';line-height: 5px;}
.ft10{font: 5px 'Times';line-height: 5px;}
.ft11{font: 6px 'Times';line-height: 6px;}
.ft12{font: 15px 'Times';line-height: 17px;}
.ft13{font: 13px 'Times';line-height: 15px;}
.ft14{font: bold 13px 'Times';line-height: 15px;}
.ft15{font: bold 15px 'Times';line-height: 17px;}
.ft16{font: 15px 'Times';margin-left: 16px;line-height: 17px;}
.ft17{font: bold 15px 'Times';margin-left: 3px;line-height: 17px;}
.ft18{font: 10px 'Times';line-height: 12px;}
.ft19{font: 11px 'Times';line-height: 12px;}
.ft20{font: 7px 'Tahoma';line-height: 8px;}
.ft21{font: 11px 'Times';line-height: 14px;}
.ft22{font: italic 11px 'Times';line-height: 14px;}
.ft23{font: 9px 'Times';line-height: 12px;}
.ft24{font: 11px 'Times';color: #003365;line-height: 14px;}

.p0{text-align: left;padding-left: 304px;margin-top: 0px;margin-bottom: 0px;}
.p1{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p2{text-align: right;padding-right: 10px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p3{text-align: left;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p4{text-align: right;padding-right: 97px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p5{text-align: left;padding-left: 88px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p6{text-align: left;padding-left: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p7{text-align: right;padding-right: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p8{text-align: left;padding-left: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p9{text-align: left;padding-left: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p10{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p11{text-align: right;padding-right: 15px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p12{text-align: left;padding-left: 13px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p13{text-align: left;padding-left: 161px;margin-top: 14px;margin-bottom: 0px;}
.p14{text-align: justify;padding-left: 11px;margin-top: 10px;margin-bottom: 0px;}
.p15{text-align: justify;padding-left: 312px;margin-top: 20px;margin-bottom: 0px;}
.p16{text-align: justify;padding-left: 11px;margin-top: 11px;margin-bottom: 0px;}
.p17{text-align: left;padding-left: 272px;margin-top: 20px;margin-bottom: 0px;}
.p18{text-align: left;padding-left: 97px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p19{text-align: left;padding-left: 140px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p20{text-align: center;padding-left: 8px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p21{text-align: right;padding-right: 46px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p22{text-align: left;padding-left: 43px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p23{text-align: left;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p24{text-align: left;padding-left: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p25{text-align: right;padding-right: 11px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
.p26{text-align: left;margin-top: 0px;margin-bottom: 0px;-webkit-transform: rotate(270deg);-moz-transform: rotate(270deg);filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);direction: rtl;block-progression: lr;width:254px;height:8px;}
.p27{text-align: left;padding-left: 499px;margin-top: 11px;margin-bottom: 0px;}
.p28{text-align: left;padding-left: 74px;margin-top: 81px;margin-bottom: 0px;}
.p29{text-align: left;padding-left: 184px;padding-right: 39px;margin-top: 34px;margin-bottom: 0px;text-indent: -184px;}
.p30{text-align: left;padding-left: 64px;margin-top: 5px;margin-bottom: 0px;}

.td0{padding: 0px;margin: 0px;width: 196px;vertical-align: bottom;}
.td1{padding: 0px;margin: 0px;width: 147px;vertical-align: bottom;}
.td2{padding: 0px;margin: 0px;width: 186px;vertical-align: bottom;}
.td3{padding: 0px;margin: 0px;width: 3px;vertical-align: bottom;}
.td4{padding: 0px;margin: 0px;width: 27px;vertical-align: bottom;}
.td5{padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
.td6{padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
.td7{padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td8{padding: 0px;margin: 0px;width: 156px;vertical-align: bottom;}
.td9{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 499px;vertical-align: bottom;}
.td10{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
.td11{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 3px;vertical-align: bottom;}
.td12{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 27px;vertical-align: bottom;}
.td13{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
.td14{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
.td15{border-left: #000000 1px solid;border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 724px;vertical-align: bottom;background: #ffcc99;}
.td16{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 195px;vertical-align: bottom;background: #ffcc99;}
.td17{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 147px;vertical-align: bottom;background: #ffcc99;}
.td18{border-right: #ffcc99 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 155px;vertical-align: bottom;background: #ffcc99;}
.td19{border-right: #ffcc99 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 29px;vertical-align: bottom;background: #ffcc99;}
.td20{border-right: #ffcc99 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 2px;vertical-align: bottom;background: #ffcc99;}
.td21{border-right: #ffcc99 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 26px;vertical-align: bottom;background: #ffcc99;}
.td22{border-right: #ffcc99 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;background: #ffcc99;}
.td23{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;background: #ffcc99;}
.td24{border-left: #000000 1px solid;border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 530px;vertical-align: bottom;}
.td25{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;}
.td26{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 342px;vertical-align: bottom;}
.td27{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 156px;vertical-align: bottom;}
.td28{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 2px;vertical-align: bottom;}
.td29{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 45px;vertical-align: bottom;}
.td30{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 343px;vertical-align: bottom;}
.td31{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 342px;vertical-align: bottom;}
.td32{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 195px;vertical-align: bottom;}
.td33{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 155px;vertical-align: bottom;}
.td34{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 29px;vertical-align: bottom;}
.td35{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
.td36{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 382px;vertical-align: bottom;}
.td37{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 195px;vertical-align: bottom;}
.td38{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 147px;vertical-align: bottom;}
.td39{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
.td40{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
.td41{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
.td42{padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
.td43{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 182px;vertical-align: bottom;}
.td44{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 180px;vertical-align: bottom;}
.td45{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
.td46{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
.td47{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 180px;vertical-align: bottom;}
.td48{padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
.td49{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
.td50{padding: 0px;margin: 0px;width: 168px;vertical-align: bottom;}
.td51{padding: 0px;margin: 0px;width: 154px;vertical-align: bottom;}
.td52{padding: 0px;margin: 0px;width: 24px;vertical-align: bottom;}

.tr0{height: 21px;}
.tr1{height: 22px;}
.tr2{height: 19px;}
.tr3{height: 14px;}
.tr4{height: 5px;}
.tr5{height: 9px;}
.tr6{height: 28px;}
.tr7{height: 27px;}
.tr8{height: 25px;}
.tr9{height: 26px;}
.tr10{height: 23px;}
.tr11{height: 15px;}
.tr12{height: 13px;}
.tr13{height: 12px;}

.t0{width: 726px;font: 13px 'Times'; margin: 0 0 0 1px;}
.t1{width: 725px;margin-top: 2px;font: 11px 'Times';}
.t2{width: 346px;margin-left: 347px;font: 13px 'Times';}

</STYLE>
<link rel="stylesheet" href="declaration/print.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
</HEAD>

<BODY>
<section id="share">
  <a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
</section>

<div id="mainreg">
<DIV id="page_1">
<DIV id="dimg1">
<IMG src="declaration/img/dicp7.jpg" id="img1">
</DIV>


<DIV class="dclr"></DIV>
<DIV id="id_1">
<P class="p0 ft0">TAXE SUR LA VALEUR AJOUTEE</P>
<TABLE cellpadding=0 cellspacing=0 class="t0">
<TR>
	<TD class="tr0 td0"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td1"><P class="p1 ft1">&nbsp;</P></TD>
	<TD colspan=2 class="tr0 td2"><P class="p2 ft2">______________________</P></TD>
	<TD class="tr0 td3"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td4"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td5"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td7"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr1 td0"><P class="p3 ft3">Recette des Impôts</P></TD>
	<TD class="tr1 td1"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td8"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td3"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td4"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td5"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr1 td7"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr2 td0"><P class="p4 ft4"><NOBR>------------</NOBR></P></TD>
	<TD class="tr2 td1"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td8"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td3"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td4"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td5"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr2 td7"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=3 class="tr3 td9"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td11"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td12"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td13"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td14"><P class="p1 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=11 class="tr0 td15"><P class="p5 ft0">REGULARISATION DU PRORATA DE DEDUCTION EN FIN D’EXERCICE</P></TD>
</TR>
<TR>
	<TD class="tr4 td16"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td17"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td18"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td19"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td20"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td21"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td22"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td19"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td19"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td19"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td23"><P class="p1 ft6">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=3 class="tr5 td9"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td11"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td12"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td13"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td14"><P class="p1 ft7">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=5 class="tr0 td24"><P class="p6 ft0">Identification de l’exercice concerné par la régularisation :</P></TD>
	<TD class="tr0 td4"><P class="p7 ft8">........................................</P></TD>
	<TD class="tr0 td5"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td6"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr0 td25"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=2 class="tr4 td26"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td27"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td10"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td28"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td12"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td13"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td10"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td10"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td10"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td29"><P class="p1 ft6">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=2 class="tr5 td30"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td27"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td11"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td12"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td13"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td10"><P class="p1 ft7">&nbsp;</P></TD>
	<TD class="tr5 td14"><P class="p1 ft7">&nbsp;</P></TD>
</TR>
<TR>
	<TD colspan=2 class="tr6 td31"><P class="p8 ft12">Nom/prénom/dénomination sociale : ...................................</P></TD>
	<TD colspan= 8 class="tr6 td8"><P class="p1 ft12">...................................................................................</P></TD>
	<TD class="tr6 td25"><P class="p1 ft12">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td32"><P class="p8 ft12">Enseigne : ................................</P></TD>
	<TD class="tr6 td1"><P class="p1 ft2">.................................</P></TD>
	<TD class="tr6 td33"><P class="p9 ft12">Numéro TAHITI</P></TD>
	<TD class="tr7 td34"><P class="p1 ft1">&nbsp;</P></TD>
	<TD colspan=2 class="tr7 td34"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr7 td35"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr7 td34"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr7 td34"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr7 td34"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr6 td25"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td32"><P class="p8 ft12">Activité exercée : ....................</P></TD>
	<TD class="tr8 td1"><P class="p10 ft12">..............................................</P></TD>
	<TD colspan=9 class="tr8 td36"><P class="p11 ft12">Adresse...............................................................</P></TD>
</TR>
<TR>
	<TD class="tr7 td32"><P class="p6 ft12">Tel : .........................................</P></TD>
	<TD class="tr7 td1"><P class="p12 ft12">Fax : ..........................</P></TD>
	<TD colspan=9 class="tr7 td36"><P class="p11 ft12"><NOBR>E-mail :...................................................................</NOBR></P></TD>
</TR>
<TR>
	<TD class="tr9 td32"><P class="p6 ft12">Boîte postale : ........................</P></TD>
	<TD class="tr9 td1"><P class="p1 ft12">Code postal : .......................</P></TD>
	<TD colspan=9 class="tr9 td36"><P class="p11 ft12">Commune :.....................................................</P></TD>
</TR>
<TR>
	<TD class="tr3 td37"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td38"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td27"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td11"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td12"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td13"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td10"><P class="p1 ft5">&nbsp;</P></TD>
	<TD class="tr3 td29"><P class="p1 ft5">&nbsp;</P></TD>
</TR>
</TABLE>
<P class="p13 ft15">I – Détermination du prorata de déduction définitifde l’exercice</P>
<P class="p14 ft12"><SPAN class="ft12">1</SPAN><SPAN class="ft16">Montant hors taxes du chiffre d’affaires taxable de l’exercice (FCFP)</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">2</SPAN><SPAN class="ft16">Montant hors taxes du chiffre d’affaires total de l’exercice (FCFP)</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">3</SPAN><SPAN class="ft16">Prorata de déduction définitif (%) (Ratio " ligne1 / ligne 2 X 100 ")</SPAN></P>
<P class="p15 ft15"><SPAN class="ft15">II</SPAN><SPAN class="ft17">– Liquidation</SPAN></P>
<P class="p16 ft12"><SPAN class="ft12">4</SPAN><SPAN class="ft16">Prorata de déduction provisoire déclaré (%)</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">5</SPAN><SPAN class="ft16">Montant total de la TVA déductible de l’exercice</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">6</SPAN><SPAN class="ft16">Montant de la TVA déduite par application du prorata provisoire</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">7</SPAN><SPAN class="ft16">Prorata de déduction définitif (report ligne 3)</SPAN></P>
<P class="p14 ft12"><SPAN class="ft12">8</SPAN><SPAN class="ft16">Montant de la TVA à déduire par application du prorata définitif</SPAN></P>
<P class="p17 ft15">III – Régularisation à opérer</P>
<TABLE cellpadding=0 cellspacing=0 class="t1">
<TR>
	<TD colspan=2 class="tr10 td39"><P class="p18 ft15">Déduction complémentaire</P></TD>
	<TD colspan=2 class="tr10 td40"><P class="p19 ft15">Reversement</P></TD>
</TR>
<TR>
	<TD colspan=2 class="tr11 td41"><P class="p20 ft13">(prorata définitif supérieur au prorata provisoire)</P></TD>
	<TD colspan=2 class="tr11 td42"><P class="p21 ft13">(prorata définitif inférieur au prorata provisoire)</P></TD>
</TR>
<TR>
	<TD class="tr4 td43"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td44"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td45"><P class="p1 ft6">&nbsp;</P></TD>
	<TD class="tr4 td45"><P class="p1 ft6">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr9 td46"><P class="p22 ft12">ligne 8 – ligne 6</P></TD>
	<TD class="tr9 td47"><P class="p23 ft13">................................ ............</P></TD>
	<TD class="tr9 td47"><P class="p24 ft12">ligne 6 – ligne 8</P></TD>
	<TD class="tr9 td48"><P class="p25 ft13">................................ .............</P></TD>
</TR>
<TR>
	<TD class="tr12 td46"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr12 td47"><P class="p8 ft18">à reporter dans la ligne 12 de la dernière</P></TD>
	<TD class="tr12 td47"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr12 td48"><P class="p8 ft18">à reporter dans la ligne 8 de la dernière</P></TD>
</TR>
<TR>
	<TD class="tr13 td46"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td47"><P class="p8 ft19">déclaration souscrite au titre de</P></TD>
	<TD class="tr13 td47"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td48"><P class="p8 ft19">déclaration souscrite au titre de</P></TD>
</TR>
<TR>
	<TD class="tr13 td46"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td47"><P class="p8 ft19">l’exercice</P></TD>
	<TD class="tr13 td47"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td48"><P class="p8 ft19">l’exercice</P></TD>
</TR>
<TR>
	<TD class="tr13 td49"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td44"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td44"><P class="p1 ft1">&nbsp;</P></TD>
	<TD class="tr13 td45"><P class="p1 ft1">&nbsp;</P></TD>
</TR>
</TABLE>
</DIV>
<DIV id="id_2">
<DIV id="id_2_1">
<!--[if lte IE 7]><P style="margin-left:0px;margin-top:0px;margin-right:-246px;margin-bottom:0px;" class="p26 ft20"><![endif]--><!--[if gte IE 8]><P style="margin-left:-246px;margin-top:0px;margin-right:0px;margin-bottom:246px;" class="p26 ft20"><![endif]--><![if ! IE]><P style="margin-left:-123px;margin-top:123px;margin-right:-123px;margin-bottom:123px;" class="p26 ft20"><![endif]>DICP/TVAPD/SN/N0221/V1/14</P>
</DIV>
<DIV id="id_2_2">
<TABLE cellpadding=0 cellspacing=0 class="t2">
<TR>
	<TD class="tr2 td50"><P class="p1 ft13">A .........................................</P></TD>
	<TD class="tr2 td51"><P class="p1 ft13">, le ...................................</P></TD>
	<TD class="tr2 td52"><P class="p10 ft12">201</P></TD>
</TR>
</TABLE>
<P class="p27 ft12">Signature</P>
<P class="p28 ft22"><SPAN class="ft21">(</SPAN>Cachet du service<SPAN class="ft21">)</SPAN></P>
<P class="p29 ft23">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatiqu e, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
<P class="p30 ft24">Recette des Impôts (CCP <NOBR>14168-00001-9062004Y068-32)</NOBR> BP 72 - 98713 PAPEETE - Tél : 40 46 13 56 / 40 4613 64 - Fax : 40 46 13 03</P>
</DIV>
</DIV>
</DIV>
</DIV>
</BODY>
</HTML>
