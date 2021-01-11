<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['bystatus'] = 'uint';
$PA['quarter'] = 'uint';
$PA['built_format'] = 'uint';
require('inc/readpost.php');
$endmonth = $month;
$quarter_text = 'MENSUELLE';
$quarter_text2 = 'MOIS';
if ($quarter)
{
  $quarter_text = 'TRIMESTRIELLE';
  $quarter_text2 = 'TRIMESTRE';
  #echo $month,' ',$month%3; #HERE TODO
}

$query = 'select idtahiti,companyname,infophonenumber,infoemail,postalcode,infocity,postaladdress
from companyinfo limit 1';
$query_prm = array();
require('inc/doquery.php');
$idtahiti = $query_result[0]['idtahiti'];
$companyname = $query_result[0]['companyname'];
$infophonenumber = $query_result[0]['infophonenumber'];
$infoemail = $query_result[0]['infoemail'];
$commune = $query_result[0]['postalcode'].' '.$query_result[0]['infocity'];
$address = $query_result[0]['postaladdress'];

$cst_bracket = array(); $cst_bracket_base = array();
for ($i=0;$i <= 10;$i++) { $cst_bracket[$i] = 0; $cst_bracket_base[$i] = 0; $cst_count[$i] = 0; }
$t1 = $t2 = 0;

$query = 'select employeeid,payslip.payslipid,bracket0,bracket1,bracket2,bracket3,bracket4,bracket5,bracket6,bracket7,bracket8,bracket9,bracket10,
                 bracket_base0,bracket_base1,bracket_base2,bracket_base3,bracket_base4,bracket_base5,bracket_base6,bracket_base7,bracket_base8,bracket_base9,bracket_base10
from payslip,payslip_tax_bracket
where payslip_tax_bracket.payslipid=payslip.payslipid
and month(payslipdate)>=? and month(payslipdate)<=? and year(payslipdate)=?';
if ($bystatus) { $query .= ' and status=1'; }
$query_prm = array($month,$endmonth,$year);
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  for ($y = 0; $y <= 10; $y++)
  {
    $cst_bracket[$y] += $query_result[$i]['bracket'.$y];
    $cst_bracket_base[$y] += $query_result[$i]['bracket_base'.$y];
    if ($query_result[$i]['bracket'.$y] > 0) { $cst_count[$y]++; }
  }
}

if ($built_format) # if this is not good enough, download PDF, save as full screen image and insert values on top of image
{
ob_start();
echo '
<!doctype html>
<html>
<head>
<meta http-equiv=content-type content="text/html; charset=UTF-8">
<link rel="icon" href="pics/temico.png" type="image/png">
<link rel="stylesheet" type="text/css" href="style.css">
<style type="css">
body {
  font-family: "Times New Roman";
}
</style>
</head>
<body>
<table class="transparent" style="width: 1286px;">
<tr><td align=center width=240px><img src="declaration/2/img/1.png">
<td align=center valign=center><font size=+1><b>CONTRIBUTION DE SOLIDARITE TERRITORIALE</b></font><br>
(sur les traitements, salaires, pensions, rentes viagères et indemnités diverses)<br>
<br>
DECLARATION '.$quarter_text.'
<td align=right valign=top><br><font size=-1>DECL. 4010</font>
</table>
<table class="transparent" style="width: 1286px; margin: 3px;">
<tr><td align=center width=240px><b>N° TAHITI : &nbsp;'.implode(' ',str_split(d_output($idtahiti))).'</b>
<td width=643px>Nom, prénom/Raison sociale : '.d_output($companyname).'
<td width=403px>Téléphone/Fax : '.d_output($infophonenumber).'
</table>
<table class="transparent" style="width: 1286px; margin: 3px;">
<tr><td width=530px>Adresse mail de la société oudu représentant légal : '.d_output($infoemail).'
<td width=353px>BP / Adresse correspondance : '.d_output($address).'
<td width=403px>Commune : '.d_output($commune).'
</table>
<table class="report" style="width: 1286px; font-size: small; white-space: normal;">
<tr><td width=321px><b>1 - Base de la contribution
<td width=321px colspan=3> <b>Mois : '.$month.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Année : '.$year.'
<td width=321px colspan=3> <b>Mois : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Année :
<td width=321px colspan=3> <b>Mois : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Année :
<tr><td style="width: 321px; font-size: x-small;"> &nbsp;&nbsp; 1.1 - Nombre total de personnes déclarées sur la période
<td width=321px colspan=3>
<td width=321px colspan=3>
<td width=321px colspan=3>
<tr><td style="width: 321px; font-size: x-small;"> &nbsp;&nbsp; 1.2 - Montant total des revenus versés au cours de la période
<td width=321px colspan=3>
<td width=321px colspan=3>
<td width=321px colspan=3>
<tr><td style="width: 321px; font-size: x-small;" rowspan=2><b>2 - Revenus individuels inférieurs à 150.000 F cfp et donnant lieu à cotisations individuelles inférieures à 750 F cfp (non précomptées)
<td width=40px align=center>Nb
<td width=160px align=center>Montant total<br>des revenus non taxables
<td width=121px align=center>CST due
<td width=40px align=center>Nb
<td width=160px align=center>Montant total<br>des revenus non taxables
<td width=121px align=center>CST due
<td width=40px align=center>Nb
<td width=160px align=center>Montant total<br>des revenus non taxables
<td width=121px align=center>CST due
<tr><td width=40px>
<td width=160px>
<td width=121px align=right>&nbsp;
<td width=40px>
<td width=160px>
<td width=121px align=right>
<td width=40px>
<td width=160px>
<td width=121px align=right>
<tr><td style="width: 321px; font-size: x-small;"><b>3 - Calcul de la contribution par tranches des revenus individuels supérieurs ou égaux à 150.000 F cfp
<td width=40px align=center>Nb
<td width=160px align=center>Montant total des revenus taxablespar tranche
<td width=121px align=center>CST due
<td width=40px align=center>Nb
<td width=160px align=center>Montant total des revenus taxablespar tranche
<td width=121px align=center>CST due
<td width=40px align=center>Nb
<td width=160px align=center>Montant total des revenus taxablespar tranche
<td width=121px align=center>CST due';
for ($y = 0; $y <= 10; $y++)
{
  echo '<tr><td> &nbsp;&nbsp; 3.'.($y+1);
  switch($y)
  {
    case 0:
      echo ' - de 0 à 150.000 F cfp (0,5%)';
    break;
    case 1:
      echo ' - de 150.001 à 250.000 F cfp (3%)';
    break;
    case 2:
      echo ' - de 250.001 à 400.000 F cfp (5%)';
    break;
    case 3:
      echo ' - de 400.001 à 700.000 F cfp (7%)';
    break;
    case 4:
      echo ' - de 700.001 à 1.000.000 F cfp (9%)';
    break;
    case 5:
      echo ' - de 1.000.001 à 1.250.000 F cfp (12%)';
    break;
    case 6:
      echo ' - de 1.250.001 à 1.500.000 F cfp (15%)';
    break;
    case 7:
      echo ' - de 1.500.001 à 1.750.000 F cfp (18%)';
    break;
    case 8:
      echo ' - de 1.750.001 à 2.000.000 F cfp (21%)';
    break;
    case 9:
      echo ' - de 2.000.001 à 2.500.000 F cfp (23%)';
    break;
    case 10:
      echo ' - plus de 2.500.000 F cfp (25%)';
    break;
  }
  echo '<td align=right>',myfix($cst_count[$y]),'<td align=right>',myfix($cst_bracket_base[$y]),'<td align=right>',myfix($cst_bracket[$y]);
  $t1 += $cst_bracket_base[$y];
  $t2 += $cst_bracket[$y];
  echo '<td><td><td><td><td><td>';
}
echo '<tr><td rowspan=2><b>4 - Total de la contribution due
<td colspan=2 align=center>Sous-total :
<td align=right><b>'.myfix($t2).'
<td colspan=2 align=center>Sous-total :
<td align=right><b>
<td colspan=2 align=center>Sous-total :
<td align=right><b>
<tr><td colspan=9 align=center style="font-size: medium;"><b>TOTAL DU '.$quarter_text2.' : '.myfix($t2).' F CFP
</table>
<img src="declaration/2/img/bottom.png" width=1286px>';

require('inc/bottom.php');
}else{
?>
<!DOCTYPE html >
<html lang="en">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta charset="utf-8" />
</head>

<body style="margin: 0;">

<div id="p1" style="overflow: hidden; position: relative; background-color: white; width: 1286px; height: 909px;">

<!-- Begin shared CSS values -->
<style class="shared-css" type="text/css" >
.t {
	-webkit-transform-origin: bottom left;
	-moz-transform-origin: bottom left;
	-o-transform-origin: bottom left;
	-ms-transform-origin: bottom left;
	-webkit-transform: scale(0.25);
	-moz-transform: scale(0.25);
	-o-transform: scale(0.25);
	-ms-transform: scale(0.25);
	z-index: 2;
	position: absolute;
	white-space: pre;
	overflow: visible;
}
</style>
<!-- End shared CSS values -->


<!-- Begin inline CSS -->
<style type="text/css" >

#t1_1{left:26px;bottom:723px;letter-spacing:0.4px;}
#t2_1{left:45px;bottom:723px;word-spacing:-0.1px;}
#t3_1{left:319px;bottom:720px;}
#t4_1{left:482px;bottom:720px;}
#t5_1{left:636px;bottom:720px;}
#t6_1{left:798px;bottom:720px;}
#t7_1{left:952px;bottom:720px;}
#t8_1{left:1115px;bottom:720px;}
#t9_1{left:45px;bottom:697px;letter-spacing:0.1px;}
#ta_1{left:73px;bottom:697px;word-spacing:-0.3px;}
#tb_1{left:45px;bottom:671px;letter-spacing:0.1px;}
#tc_1{left:73px;bottom:671px;word-spacing:0.2px;}
#td_1{left:26px;bottom:645px;letter-spacing:0.4px;}
#te_1{left:45px;bottom:645px;word-spacing:0.1px;}
#tf_1{left:45px;bottom:633px;}
#tg_1{left:45px;bottom:621px;}
#th_1{left:327px;bottom:643px;letter-spacing:0.1px;}
#ti_1{left:342px;bottom:649px;letter-spacing:-0.1px;}
#tj_1{left:416px;bottom:645px;word-spacing:0.3px;}
#tk_1{left:391px;bottom:633px;word-spacing:0.1px;}
#tl_1{left:558px;bottom:643px;letter-spacing:-0.2px;word-spacing:0.2px;}
#tm_1{left:643px;bottom:643px;letter-spacing:0.1px;}
#tn_1{left:658px;bottom:649px;letter-spacing:-0.1px;}
#to_1{left:732px;bottom:645px;word-spacing:0.3px;}
#tp_1{left:707px;bottom:633px;word-spacing:0.1px;}
#tq_1{left:875px;bottom:643px;letter-spacing:-0.2px;word-spacing:0.1px;}
#tr_1{left:959px;bottom:643px;letter-spacing:0.1px;}
#ts_1{left:974px;bottom:649px;letter-spacing:-0.1px;}
#tt_1{left:1048px;bottom:645px;word-spacing:-0.3px;}
#tu_1{left:1023px;bottom:633px;word-spacing:-0.2px;}
#tv_1{left:1189px;bottom:643px;letter-spacing:-0.2px;word-spacing:0.2px;}
#tw_1{left:576px;bottom:607px;}
#tx_1{left:892px;bottom:607px;}
#ty_1{left:1207px;bottom:607px;}
#tz_1{left:26px;bottom:586px;letter-spacing:0.4px;}
#t10_1{left:45px;bottom:586px;word-spacing:0.1px;}
#t11_1{left:45px;bottom:574px;word-spacing:-0.1px;}
#t12_1{left:327px;bottom:584px;letter-spacing:0.1px;}
#t13_1{left:342px;bottom:590px;letter-spacing:-0.1px;}
#t14_1{left:370px;bottom:586px;word-spacing:-0.3px;}
#t15_1{left:421px;bottom:574px;letter-spacing:0.1px;word-spacing:-0.4px;}
#t16_1{left:558px;bottom:584px;letter-spacing:-0.2px;word-spacing:0.2px;}
#t17_1{left:643px;bottom:584px;letter-spacing:0.1px;}
#t18_1{left:658px;bottom:590px;letter-spacing:-0.1px;}
#t19_1{left:686px;bottom:586px;word-spacing:-0.3px;}
#t1a_1{left:737px;bottom:574px;letter-spacing:0.1px;word-spacing:-0.4px;}
#t1b_1{left:875px;bottom:584px;letter-spacing:-0.2px;word-spacing:0.1px;}
#t1c_1{left:959px;bottom:584px;letter-spacing:0.1px;}
#t1d_1{left:974px;bottom:590px;letter-spacing:-0.1px;}
#t1e_1{left:1003px;bottom:586px;word-spacing:0.3px;}
#t1f_1{left:1053px;bottom:574px;word-spacing:0.3px;}
#t1g_1{left:1189px;bottom:584px;letter-spacing:-0.2px;word-spacing:0.2px;}
#t1h_1{left:45px;bottom:548px;letter-spacing:0.1px;}
#t1i_1{left:77px;bottom:547px;}
#t1j_1{left:278px;bottom:548px;letter-spacing:-0.1px;}
#t1k_1{left:45px;bottom:518px;letter-spacing:0.1px;}
#t1l_1{left:77px;bottom:517px;}
#t1m_1{left:282px;bottom:518px;}
#t1n_1{left:45px;bottom:488px;letter-spacing:0.1px;}
#t1o_1{left:77px;bottom:487px;}
#t1p_1{left:282px;bottom:488px;}
#t1q_1{left:45px;bottom:457px;letter-spacing:0.1px;}
#t1r_1{left:77px;bottom:456px;}
#t1s_1{left:282px;bottom:457px;}
#t1t_1{left:45px;bottom:427px;letter-spacing:0.1px;}
#t1u_1{left:77px;bottom:426px;}
#t1v_1{left:282px;bottom:427px;}
#t1w_1{left:45px;bottom:397px;letter-spacing:0.1px;}
#t1x_1{left:77px;bottom:396px;}
#t1y_1{left:279px;bottom:397px;}
#t1z_1{left:45px;bottom:366px;letter-spacing:0.1px;}
#t20_1{left:77px;bottom:365px;}
#t21_1{left:279px;bottom:366px;}
#t22_1{left:45px;bottom:336px;letter-spacing:0.1px;}
#t23_1{left:77px;bottom:335px;}
#t24_1{left:279px;bottom:336px;}
#t25_1{left:45px;bottom:306px;letter-spacing:0.1px;}
#t26_1{left:77px;bottom:305px;}
#t27_1{left:279px;bottom:306px;}
#t28_1{left:45px;bottom:275px;letter-spacing:0.1px;}
#t29_1{left:77px;bottom:274px;}
#t2a_1{left:279px;bottom:275px;}
#t2b_1{left:45px;bottom:245px;letter-spacing:-0.4px;}
#t2c_1{left:77px;bottom:244px;}
#t2d_1{left:279px;bottom:245px;}
#t2e_1{left:26px;bottom:219px;letter-spacing:0.2px;}
#t2f_1{left:43px;bottom:219px;letter-spacing:-0.3px;word-spacing:0.6px;}
#t2g_1{left:393px;bottom:216px;}
#t2h_1{left:710px;bottom:216px;}
#t2i_1{left:1026px;bottom:216px;letter-spacing:0.1px;}
#t2j_1{left:644px;bottom:188px;letter-spacing:-0.5px;word-spacing:0.7px;}
#t2k_1{left:1217px;bottom:188px;letter-spacing:-0.7px;word-spacing:0.8px;}
#t2l_1{left:26px;bottom:154px;}
#t2m_1{left:50px;bottom:139px;}
#t2n_1{left:50px;bottom:123px;word-spacing:-0.1px;}
#t2o_1{left:50px;bottom:106px;word-spacing:0.1px;}
#t2p_1{left:50px;bottom:91px;word-spacing:-0.1px;}
#t2q_1{left:340px;bottom:154px;letter-spacing:-0.1px;word-spacing:-0.4px;}
#t2r_1{left:756px;bottom:152px;}
#t2s_1{left:766px;bottom:152px;}
#t2t_1{left:914px;bottom:152px;letter-spacing:-0.3px;}
#t2u_1{left:927px;bottom:152px;}
#t2v_1{left:1131px;bottom:154px;}
#t2w_1{left:319px;bottom:131px;}
#t2x_1{left:824px;bottom:131px;word-spacing:-0.1px;}
#t2y_1{left:319px;bottom:110px;word-spacing:0.3px;}
#t2z_1{left:611px;bottom:110px;word-spacing:0.4px;}
#t30_1{left:319px;bottom:89px;word-spacing:-0.3px;}
#t31_1{left:319px;bottom:69px;word-spacing:0.3px;}
#t32_1{left:1201px;bottom:875px;word-spacing:-0.4px;}
#t33_1{left:550px;bottom:867px;letter-spacing:-0.1px;word-spacing:-0.4px;}
#t34_1{left:557px;bottom:852px;word-spacing:0.2px;}
#t35_1{left:609px;bottom:827px;letter-spacing:-0.2px;}
#t36_1{left:923px;bottom:833px;letter-spacing:-0.1px;}
#t37_1{left:435px;bottom:816px;}
#t38_1{left:59px;bottom:788px;letter-spacing:-0.5px;word-spacing:-0.4px;}
#t39_1{left:299px;bottom:788px;word-spacing:-0.1px;}
#t3a_1{left:473px;bottom:788px;}
#t3b_1{left:487px;bottom:788px;}
#t3c_1{left:909px;bottom:788px;word-spacing:-0.3px;}
#t3d_1{left:1005px;bottom:788px;}
#t3e_1{left:1019px;bottom:788px;}
#t3f_1{left:58px;bottom:768px;word-spacing:0.1px;}
#t3g_1{left:59px;bottom:753px;word-spacing:-0.1px;}
#t3h_1{left:237px;bottom:753px;}
#t3i_1{left:251px;bottom:753px;}
#t3j_1{left:615px;bottom:753px;letter-spacing:-0.1px;word-spacing:-0.4px;}
#t3k_1{left:792px;bottom:753px;}
#t3l_1{left:806px;bottom:753px;}
#t3m_1{left:1063px;bottom:753px;word-spacing:-0.3px;}
#t3n_1{left:1128px;bottom:753px;}
#t3o_1{left:23px;bottom:66px;letter-spacing:-0.1px;}
#t3p_1{left:30px;bottom:63px;word-spacing:-0.1px;}
#t3q_1{left:23px;bottom:54px;word-spacing:-0.1px;}
#t3r_1{left:23px;bottom:46px;word-spacing:0.3px;}
#t3s_1{left:23px;bottom:40px;letter-spacing:-0.1px;}
#t3t_1{left:30px;bottom:37px;}
#t3u_1{left:339px;bottom:53px;}
#t3v_1{left:663px;bottom:44px;word-spacing:-0.2px;}
#t3w_1{left:535px;bottom:20px;letter-spacing:-0.3px;word-spacing:0.5px;}
#t3x_1{left:1170px;bottom:22px;}
#t3y_1{left:1258px;bottom:22px;}

#x31_nb_1{right:915px;bottom:547px;}
#x32_nb_1{right:915px;bottom:517px;}
#x33_nb_1{right:915px;bottom:487px;}
#x34_nb_1{right:934px;bottom:456px;}
#x35_nb_1{right:915px;bottom:426px;}
#x36_nb_1{right:915px;bottom:396px;}
#x37_nb_1{right:915px;bottom:365px;}
#x38_nb_1{right:915px;bottom:335px;}
#x39_nb_1{right:915px;bottom:305px;}
#x310_nb_1{right:915px;bottom:274px;}
#x311_nb_1{right:915px;bottom:244px;}

#x31_rev_1{right:660px;bottom:547px;}
#x32_rev_1{right:660px;bottom:517px;}
#x33_rev_1{right:660px;bottom:487px;}
#x34_rev_1{right:688px;bottom:456px;}
#x35_rev_1{right:762px;bottom:426px;}
#x36_rev_1{right:762px;bottom:396px;}
#x37_rev_1{right:762px;bottom:365px;}
#x38_rev_1{right:762px;bottom:335px;}
#x39_rev_1{right:762px;bottom:305px;}
#x310_rev_1{right:762px;bottom:274px;}
#x311_rev_1{right:762px;bottom:244px;}

#x31_cst_1{right:590px;bottom:547px;}
#x32_cst_1{right:590px;bottom:517px;}
#x33_cst_1{right:590px;bottom:487px;}
#x34_cst_1{right:590px;bottom:456px;}
#x35_cst_1{right:654px;bottom:426px;}
#x36_cst_1{right:654px;bottom:396px;}
#x37_cst_1{right:654px;bottom:365px;}
#x38_cst_1{right:654px;bottom:335px;}
#x39_cst_1{right:654px;bottom:305px;}
#x310_cst_1{right:654px;bottom:274px;}
#x311_cst_1{right:654px;bottom:244px;}

.s1_1{
	FONT-SIZE: 42.8px;
	FONT-FAMILY: TimesNewRomanPS-BoldMT_a;
	color: rgb(0,0,0);
}

.s2_1{
	FONT-SIZE: 48.9px;
	FONT-FAMILY: TimesNewRomanPS-BoldMT_a;
	color: rgb(0,0,0);
}

.s3_1{
	FONT-SIZE: 42.8px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s4_1{
	FONT-SIZE: 48.9px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s5_1{
	FONT-SIZE: 28.1px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s6_1{
	FONT-SIZE: 61.1px;
	FONT-FAMILY: TimesNewRomanPS-BoldMT_a;
	color: rgb(0,0,0);
}

.s7_1{
	FONT-SIZE: 55px;
	FONT-FAMILY: TimesNewRomanPS-BoldMT_a;
	color: rgb(0,0,0);
}

.s8_1{
	FONT-SIZE: 55px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s9_1{
	FONT-SIZE: 73.3px;
	FONT-FAMILY: TimesNewRomanPS-BoldMT_a;
	color: rgb(0,0,0);
}

.s10_1{
	FONT-SIZE: 31.8px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s11_1{
	FONT-SIZE: 35.4px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s12_1{
	FONT-SIZE: 17.7px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s13_1{
	FONT-SIZE: 30.6px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(0,0,0);
}

.s14_1{
	FONT-SIZE: 30.6px;
	FONT-FAMILY: TimesNewRomanPS-ItalicMT_i;
	color: rgb(0,0,0);
}

.s15_1{
	FONT-SIZE: 30.6px;
	FONT-FAMILY: TimesNewRomanPSMT_e;
	color: rgb(255,0,0);
}

</style>
<!-- End inline CSS -->

<!-- Begin embedded font definitions -->
<style id="fonts1" type="text/css" >

@font-face {
	font-family: TimesNewRomanPS-ItalicMT_i;
	src: url("declaration/fonts/TimesNewRomanPS-ItalicMT_i.woff") format("woff");
}

@font-face {
	font-family: TimesNewRomanPS-BoldMT_a;
	src: url("declaration/fonts/TimesNewRomanPS-BoldMT_a.woff") format("woff");
}

@font-face {
	font-family: TimesNewRomanPSMT_e;
	src: url("declaration/fonts/TimesNewRomanPSMT_e.woff") format("woff");
}

</style>
<!-- End embedded font definitions -->

<!-- Begin page background -->
<div id="pg1Overlay" style="width:100%; height:100%; position:absolute; z-index:1; background-color:rgba(0,0,0,0); -webkit-user-select: none;"></div>
<div id="pg1" style="-webkit-user-select: none;"><object width="1286" height="909" data="declaration/2/1.svg" type="image/svg+xml" id="pdf1" style="width:1286px; height:909px; -moz-transform:scale(1); z-index: 0;"></object></div>
<!-- End page background -->


<!-- Begin text definitions (Positioned/styled in CSS) -->
<div id="t1_1" class="t s1_1">-1</div>
<div id="t2_1" class="t s1_1">Base de la contribution</div>
<div id="t3_1" class="t s2_1">Mois :</div>
<div id="t4_1" class="t s2_1">Année :</div>
<div id="t5_1" class="t s2_1">Mois :</div>
<div id="t6_1" class="t s2_1">Année :</div>
<div id="t7_1" class="t s2_1">Mois :</div>
<div id="t8_1" class="t s2_1">Année :</div>
<div id="t9_1" class="t s3_1">1.1-</div>
<div id="ta_1" class="t s3_1">Nombre total de personnes déclarées sur la période</div>
<div id="tb_1" class="t s3_1">1.2-</div>
<div id="tc_1" class="t s3_1">Montant total des revenus versés au cours de la période</div>
<div id="td_1" class="t s1_1">-2</div>
<div id="te_1" class="t s1_1">Revenus individuels inférieurs à 150.000 F cfp et donnant </div>
<div id="tf_1" class="t s1_1">lieu à cotisations individuelles inférieures à 750 F cfp (non </div>
<div id="tg_1" class="t s1_1">précomptées)</div>
<div id="th_1" class="t s4_1">Nb</div>
<div id="ti_1" class="t s5_1">(2)</div>
<div id="tj_1" class="t s3_1">Montant total</div>
<div id="tk_1" class="t s3_1">des revenus non taxables</div>
<div id="tl_1" class="t s4_1">CST due</div>
<div id="tm_1" class="t s4_1">Nb</div>
<div id="tn_1" class="t s5_1">(2)</div>
<div id="to_1" class="t s3_1">Montant total</div>
<div id="tp_1" class="t s3_1">des revenus non taxables</div>
<div id="tq_1" class="t s4_1">CST due</div>
<div id="tr_1" class="t s4_1">Nb</div>
<div id="ts_1" class="t s5_1">(2)</div>
<div id="tt_1" class="t s3_1">Montant total</div>
<div id="tu_1" class="t s3_1">des revenus non taxables</div>
<div id="tv_1" class="t s4_1">CST due</div>
<div id="tw_1" class="t s6_1">0</div>
<div id="tx_1" class="t s6_1">0</div>
<div id="ty_1" class="t s6_1">0</div>
<div id="tz_1" class="t s1_1">-3</div>
<div id="t10_1" class="t s1_1">Calcul de la contribution par tranches des revenus </div>
<div id="t11_1" class="t s1_1">individuels supérieurs ou égaux à 150.000 F cfp</div>
<div id="t12_1" class="t s4_1">Nb</div>
<div id="t13_1" class="t s5_1">(2)</div>
<div id="t14_1" class="t s3_1">Montant total des revenus taxables</div>
<div id="t15_1" class="t s3_1">par tranche</div>
<div id="t16_1" class="t s4_1">CST due</div>
<div id="t17_1" class="t s4_1">Nb</div>
<div id="t18_1" class="t s5_1">(2)</div>
<div id="t19_1" class="t s3_1">Montant total des revenus taxables</div>
<div id="t1a_1" class="t s3_1">par tranche</div>
<div id="t1b_1" class="t s4_1">CST due</div>
<div id="t1c_1" class="t s4_1">Nb</div>
<div id="t1d_1" class="t s5_1">(2)</div>
<div id="t1e_1" class="t s3_1">Montant total des revenus taxables</div>
<div id="t1f_1" class="t s3_1">par tranche</div>
<div id="t1g_1" class="t s4_1">CST due</div>
<div id="t1h_1" class="t s3_1">3.1-</div>
<div id="t1i_1" class="t s4_1">de 0 à 150.000 F cfp</div>
<div id="t1j_1" class="t s3_1">0,5%</div>
<div id="t1k_1" class="t s3_1">3.2-</div>
<div id="t1l_1" class="t s4_1">de 150.001 à 250.000 F cfp</div>
<div id="t1m_1" class="t s3_1">3%</div>
<div id="t1n_1" class="t s3_1">3.3-</div>
<div id="t1o_1" class="t s4_1">de 250.001 à 400.000 F cfp</div>
<div id="t1p_1" class="t s3_1">5%</div>
<div id="t1q_1" class="t s3_1">3.4-</div>
<div id="t1r_1" class="t s4_1">de 400.001 à 700.000 F cfp</div>
<div id="t1s_1" class="t s3_1">7%</div>
<div id="t1t_1" class="t s3_1">3.5-</div>
<div id="t1u_1" class="t s4_1">de 700.001 à 1.000.000 F cfp</div>
<div id="t1v_1" class="t s3_1">9%</div>
<div id="t1w_1" class="t s3_1">3.6-</div>
<div id="t1x_1" class="t s4_1">de 1.000.001 à 1.250.000 F cfp</div>
<div id="t1y_1" class="t s3_1">12%</div>
<div id="t1z_1" class="t s3_1">3.7-</div>
<div id="t20_1" class="t s4_1">de 1.250.001 à 1.500.000 F cfp</div>
<div id="t21_1" class="t s3_1">15%</div>
<div id="t22_1" class="t s3_1">3.8-</div>
<div id="t23_1" class="t s4_1">de 1.500.001 à 1.750.000 F cfp</div>
<div id="t24_1" class="t s3_1">18%</div>
<div id="t25_1" class="t s3_1">3.9-</div>
<div id="t26_1" class="t s4_1">de 1.750.001 à 2.000.000 F cfp</div>
<div id="t27_1" class="t s3_1">21%</div>
<div id="t28_1" class="t s3_1">3.10-</div>
<div id="t29_1" class="t s4_1">de 2.000.001 à 2.500.000 F cfp</div>
<div id="t2a_1" class="t s3_1">23%</div>
<div id="t2b_1" class="t s3_1">3.11-</div>
<div id="t2c_1" class="t s4_1">plus de 2.500.000 F cfp</div>
<div id="t2d_1" class="t s3_1">25%</div>
<div id="t2e_1" class="t s1_1">4- </div>
<div id="t2f_1" class="t s1_1">Total de la contribution due</div>
<div id="t2g_1" class="t s4_1">Sous-total :</div>
<div id="t2h_1" class="t s4_1">Sous-total :</div>
<div id="t2i_1" class="t s4_1">Sous-total :</div>
<div id="t2j_1" class="t s7_1">TOTAL DU MOIS OU DU TRIMESTRE</div>
<div id="t2k_1" class="t s7_1">F CFP</div>
<div id="t2l_1" class="t s2_1">Moyens de paiement :</div>
<div id="t2m_1" class="t s4_1">En numéraire</div>
<div id="t2n_1" class="t s4_1">Par chèque (à l'ordre du Trésor Public)</div>
<div id="t2o_1" class="t s4_1">Par virement au bénéfice de la Recette des impôts</div>
<div id="t2p_1" class="t s3_1">(voir au verso)</div>
<div id="t2q_1" class="t s2_1">CADRE RESERVE AU SERVICE CHARGE DU RECOUVREMENT</div>
<div id="t2r_1" class="t s8_1">A</div>
<div id="t2s_1" class="t s8_1">.......................................</div>
<div id="t2t_1" class="t s8_1">le,</div>
<div id="t2u_1" class="t s8_1">.........................................</div>
<div id="t2v_1" class="t s4_1">Date de réception</div>
<div id="t2w_1" class="t s3_1">Date d'encaissement :</div>
<div id="t2x_1" class="t s3_1">Signature du représentant de l'entreprise</div>
<div id="t2y_1" class="t s3_1">Écriture comptable :</div>
<div id="t2z_1" class="t s3_1">N° Déclaration</div>
<div id="t30_1" class="t s3_1">Montant encaissé :</div>
<div id="t31_1" class="t s3_1">Pénalités :</div>
<div id="t32_1" class="t s3_1">DECL. 4010</div>
<div id="t33_1" class="t s9_1">CONTRIBUTION DE SOLIDARITE TERRITORIALE</div>
<div id="t34_1" class="t s8_1">(sur les traitements, salaires, pensions, rentes viagères et indemnités diverses)</div>
<div id="t35_1" class="t s8_1">DECLARATION MENSUELLE OU TRIMESTRIELLE</div>
<div id="t36_1" class="t s10_1">(1)</div>
<div id="t37_1" class="t s11_1">(à adresser accompagnée du paiement à la Recette des impôts : ouverture de 7h30 à 14h30 et le vendredi de 7h30 à 13h30 – B.P. 72 – 98713 Papeete – Tél. : 40 46 13 13 – Fax : 40 46 13 03)</div>
<div id="t38_1" class="t s7_1">N° TAHITI :</div>
<div id="t39_1" class="t s8_1">Nom, prénom/Raison sociale : </div>
<div id="t3a_1" class="t s8_1">…</div>
<div id="t3b_1" class="t s8_1">........................................................................................................................</div>
<div id="t3c_1" class="t s8_1">Téléphone/Fax : </div>
<div id="t3d_1" class="t s8_1">…</div>
<div id="t3e_1" class="t s8_1">......................................................................</div>
<div id="t3f_1" class="t s8_1">Adresse mail de la société ou</div>
<div id="t3g_1" class="t s8_1">du représentant légal :</div>
<div id="t3h_1" class="t s8_1">…</div>
<div id="t3i_1" class="t s8_1">........................................................................................................</div>
<div id="t3j_1" class="t s8_1">BP / Adresse correspondance : </div>
<div id="t3k_1" class="t s8_1">…</div>
<div id="t3l_1" class="t s8_1">.........................................................................</div>
<div id="t3m_1" class="t s8_1">Commune :</div>
<div id="t3n_1" class="t s8_1">......................................</div>
<div id="t3o_1" class="t s12_1">(1)</div>
<div id="t3p_1" class="t s13_1">Les entreprises et débiteurs dont les prélèvements globaux effectués sur l'année au titre de la </div>
<div id="t3q_1" class="t s13_1">contribution sont égaux ou inférieurs à 240.000 F cfp sont admis à déposer une déclaration par </div>
<div id="t3r_1" class="t s13_1">trimestre civil.</div>
<div id="t3s_1" class="t s12_1">(2)</div>
<div id="t3t_1" class="t s13_1">Nombre de personnes concernées par la tranche.</div>
<div id="t3u_1" class="t s14_1">« Les dispositions des articles 39 et 40 de la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers et aux libertés, modifiée par la loi n°2004-801 du 6 août 2004, garantissent les droits des personnes physiques à l'égard des traitements </div>
<div id="t3v_1" class="t s14_1">des données à caractère personnel. »</div>
<div id="t3w_1" class="t s8_1">B.P. 72 – 98713 PAPEETE – Tél. : 40 46 13 13 – Fax : 40 46 13 03</div>
<div id="t3x_1" class="t s13_1">DICP/CSTS/4010/N007/V1/</div>
<div id="t3y_1" class="t s15_1">18</div>

<?php
for ($y = 0; $y <= 10; $y++)
{
  /*
  echo '<tr><td>3.'.($y+1).'<td align=right>',myfix($cst_count[$y]),'<td align=right>',myfix($cst_bracket_base[$y]),'<td align=right>',myfix($cst_bracket[$y]);
  $t1 += $cst_bracket_base[$y];
  $t2 += $cst_bracket[$y];
  */
  echo '<div id="x3'.($y+1).'_nb_1" class="t s4_1">'.myfix($cst_count[$y]).'</div>';
  echo '<div id="x3'.($y+1).'_rev_1" class="t s4_1">'.myfix($cst_bracket_base[$y]).'</div>';
  echo '<div id="x3'.($y+1).'_cst_1" class="t s4_1">'.myfix($cst_bracket[$y]).'</div>';
}

?>


<!-- End text definitions -->


</div>
</body>
</html>
<?php } ?>