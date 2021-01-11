<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<title>Releve de charges</title>
<link rel="stylesheet" href="declaration/dicp_relevecharges.css">
<link rel="stylesheet" href="declaration/print.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
</HEAD>
<?php
$f = array();
$f[0] = '';
$f[1] = '';
$f[2] = '';
$f[3] = '';
$f[4] = '';
$f[5] = '';
$f[6] = '';
$f[7] = '';
$f[8] = '';
$f[9] = '';
$f[10] = '';
$f[11] = '';
$f[12] = '';
$f[13] = '';
$f[14] = '';
$f[15] = '';
$f[16] = '';
$f[17] = '';
$f[18] = '';
$f[19] = '';
$f[20] = '';
$f[21] = '';
$f[22] = '';
$f[23] = '';
$f[24] = '';
$f[25] = '';
$f[26] = '';
$f[27] = '';
$f[28] = '';
$f[29] = '';
$f[30] = '';
$f[31] = '';
$f[32] = '';
$f[33] = '';
$f[34] = '';
$f[35] = '';
$f[36] = '';
$f[37] = '';

if ($_SESSION['ds_userid'] > 0 && file_exists('inc/doquery.php'))
{
	$query = 'select * from companyinfo where companyinfoid=1';
	$query_prm = array();
	require('inc/doquery.php');
	$numero_tahiti = $query_result[0]['idtahiti'];		
  /*
  $datename = 'startdate';
  require('inc/datepickerresult.php');
  $datename = 'stopdate';
  require('inc/datepickerresult.php');
  # assuming year
  $year = mb_substr($startdate, 0, 4) + 0;
  */
  $year = (int) $_POST['year'];
  $startdate = d_builddate(1, 1, $year);
  $stopdate = d_builddate(31, 12, $year);

  /*
  $query = 'select turnoverindexid from turnoverindex order by turnoverindexid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['turnoverindexid'];
    $f[$index] = 0;
  }
  */
  $query = 'select value,debit,acnumber,turnoverindexid from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=? and turnoverindexid>0';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['turnoverindexid'] - 101; # offset 101, so field 101 is 0 and field 137 is 36
    if ($index >= 0)
    {
#echo '<br>DEBUG Setting index='.$index;
      if ($query_result[$i]['debit'] == 1)
      {
        $f[$index] = d_add($f[$index], $query_result[$i]['value']); # charges, so debit is positive
      }
      else
      {
        $f[$index] = d_subtract($f[$index], $query_result[$i]['value']);
      }
    }
  }
  # TODO use d_add etc
  $f[33] = 0;
  for ($i = 0; $i < 33; $i++)
  {
    $f[33] = d_add($f[33],$f[$i]);
  }
}
else
{
  $duedate = '';
  $period = '<br>';
}
#TEST VALUES
/*$f[0] = 100000;
$f[1] = 110000;
$f[2] = 200000;
$f[3] = 300000;
$f[4] = 400000;
$f[5] = 500000;
$f[6] = 600000;
$f[7] = 700000;
$f[8] = 800000;
$f[9] = 900000;
$f[10] = 1000000;
$f[11] = 1100000;
$f[12] = 1200000;
$f[13] = 1300000;
$f[14] = 1400000;
$f[15] = 1500000;
$f[16] = 1600000;
$f[17] = 1700000;
$f[18] = 1800000;
$f[19] = 1900000;
$f[20] = 2000000;
$f[21] = 2100000;
$f[22] = 2200000;
$f[23] = 2300000;
$f[24] = 2400000;
$f[25] = 2500000;
$f[26] = 2600000;
$f[27] = 2700000;
$f[28] = 2800000;
$f[29] = 2900000;
$f[30] = 3000000;
$f[31] = 3100000;
$f[32] = 3200000;
  $f[33] = 0;
  for ($i = 0; $i < 33; $i++)
  {
    $f[33] += $f[$i];
  }
$f[34] = 3400000;
$f[35] = 3500000;
$f[36] = 3600000;
$f[37] = 3700000;*/
?>

<BODY>
<section id="share">
  <a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
</section>
<div id="maindicp">
<DIV id="page_1">
<DIV id="dimg1">
<IMG src="declaration/img/dicp6.jpg" id="img1">
</DIV>

<DIV id="tx1"><SPAN class="ft0">(chiffre d’affaires supérieur à 6 ou 15 millions selon les cas)</SPAN></DIV>

<DIV id="id_1">
<TABLE cellpadding=0 cellspacing=0 class="t0">
<TR>
	<TD class="tr0 td0"><P class="p0bis ft1">N° TAHITI : <?php echo $numero_tahiti;?>……………</P></TD>
	<TD rowspan=2 class="tr1 td1"><P class="p0 ft2">Relevé détaillé des charges</P></TD>
</TR>
<TR>
	<TD class="tr2 td0"><P class="p0 ft3">&nbsp;</P></TD>
</TR>
</TABLE>
<P class="p1 ft4">Partie à renseigner par les prestataires de service ne bénéficiant d’aucun coefficient modérateur et par ceux bénéficiant d’un coefficient modérateur astreints au dépôt d’un bilan</P>
<P class="p2 ft4">et d’un compte de résultat</P>
<TABLE cellpadding=0 cellspacing=0 class="t1">
<TR>
	<TD class="tr3 td2"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr4 td3"><P class="p3 ft6">ACHATS DE MARCHANDISES (DONT DROITS DE DOUANES) DANS LE TERRITOIRE</P></TD>
	<TD class="tr4 td4"><P class="p4 ft7">RC 1</P></TD>
	<TD class="tr4 td5"><P class="p0 ft5bis"><?php print myfix($f[0]); ?></P></TD>
	<TD class="tr3 td6"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">ACHATS DE MARCHANDISES IMPORTEES PAR L’ENTREPRISE</P></TD>
	<TD class="tr5 td9"><P class="p5 ft8">RC 2</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[1]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr6 td12"><P class="p3 ft6">VARIATION DU STOCK DE MARCHANDISES</P></TD>
	<TD class="tr6 td13"><P class="p5 ft8">RC 3</P></TD>
	<TD class="tr6 td14"><P class="p0 ft5bis"><?php print myfix($f[2]); ?></P></TD>
	<TD class="tr6 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td7"><P class="p0 ft9">&nbsp;</P></TD>
	<TD class="tr8 td8"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td9"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td10"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr7 td11"><P class="p0 ft9">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr6 td12"><P class="p3 ft11">ACHATS DE MATIERES PREMIERES ET AUTRES APPROVISIONNEMENTS</P></TD>
	<TD class="tr6 td13"><P class="p5 ft8">RC 4</P></TD>
	<TD class="tr6 td14"><P class="p0 ft5bis"><?php print myfix($f[3]); ?></P></TD>
	<TD class="tr6 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">VARIATION STOCK DE MATIERES PREMIERES ET AUTRES APPROVISIONNEMENTS</P></TD>
	<TD class="tr10 td13"><P class="p5 ft8">RC 5</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[4]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6"><NOBR>SOUS-TRAITANCE</NOBR></P></TD>
	<TD class="tr10 td13"><P class="p5 ft8">RC 6</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[5]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">LOCATIONS MOBILIERES</P></TD>
	<TD class="tr10 td13"><P class="p5 ft8">RC 7</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[6]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">REDEVANCES DE <NOBR>CREDIT-BAIL</NOBR> MOBILIER</P></TD>
	<TD class="tr10 td13"><P class="p5 ft8">RC 8</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[7]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">LOCATIONS IMMOBILIERES</P></TD>
	<TD class="tr10 td13"><P class="p6 ft13">RC9</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[8]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">REDEVANCES DE <NOBR>CREDIT-BAIL</NOBR> IMMOBILIER</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 10</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[9]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">CHARGES LOCATIVES (NON RECUPERABLES) ET DE COPROPRIETE</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 11</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[10]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">ENTRETIENS ET REPARATIONS</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 12</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[11]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">ASSURANCES</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 13</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[12]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr6 td12"><P class="p3 ft11">ETUDES ET RECHERCHES</P></TD>
	<TD class="tr6 td13"><P class="p6 ft8">RC 14</P></TD>
	<TD class="tr6 td14"><P class="p0 ft5bis"><?php print myfix($f[13]); ?></P></TD>
	<TD class="tr6 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">PERSONNEL EXTERIEUR A L’ENTREPRISE</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 15</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[14]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">REMUNERATION D’INTERMEDIAIRES ET HONORAIRES (HORS RETROCESSIONS)</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 16</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[15]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">RETROCESSIONS D’HONORAIRES</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 17</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[16]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">PUBLICITE</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 18</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[17]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">MISSIONS, VOYAGES ET DEPLACEMENTS</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 19</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[18]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">RECEPTIONS</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 20</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[19]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">TRANSPORT</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 21</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[20]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">FRAIS POSTAUX ET TELECOMMUNICATION</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 22</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[21]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">SERVICES BANCAIRES ET ASSIMILES</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 23</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[22]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">AUTRES ACHATS ET CHARGES EXTERNES</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 24</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[23]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr5 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr0 td8"><P class="p3 ft14">IMPOTS, TAXES ET VERSEMENTS ASSIMILES (à l’exclusion de l’impôt sur les transactions, des pénalités et amendes)</P></TD>
	<TD class="tr0 td9"><P class="p6 ft8">RC 25</P></TD>
	<TD class="tr0 td10"><P class="p0 ft5bis"><?php print myfix($f[24]); ?></P></TD>
	<TD class="tr5 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft14">REMUNERATION DU PERSONNEL (salaires et traitements du personnel à l’exclusion des prélèvements de l’exploitant et</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 26</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[25]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr11 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr12 td8"><P class="p3 ft6">de son conjoint et des salaires des gérants)</P></TD>
	<TD class="tr12 td9"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr12 td10"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr11 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr4 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr5 td8"><P class="p3 ft6">CHARGES SOCIALES OBLIGATOIRES</P></TD>
	<TD class="tr5 td9"><P class="p6 ft8">RC 27</P></TD>
	<TD class="tr5 td10"><P class="p0 ft5bis"><?php print myfix($f[26]); ?></P></TD>
	<TD class="tr4 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr6 td12"><P class="p3 ft6">DOTATIONS AUX AMORTISSEMENTS</P></TD>
	<TD class="tr6 td13"><P class="p6 ft8">RC 28</P></TD>
	<TD class="tr6 td14"><P class="p0 ft5bis"><?php print myfix($f[27]); ?></P></TD>
	<TD class="tr6 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td7"><P class="p0 ft9">&nbsp;</P></TD>
	<TD class="tr8 td8"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td9"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td10"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr7 td11"><P class="p0 ft9">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr6 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr6 td12"><P class="p3 ft11">DOTATIONS AUX PROVISIONS SUR IMMOBILISATIONS</P></TD>
	<TD class="tr6 td13"><P class="p6 ft8">RC 29</P></TD>
	<TD class="tr6 td14"><P class="p0 ft5bis"><?php print myfix($f[28]); ?></P></TD>
	<TD class="tr6 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">DOTATIONS AUX PROVISIONS SUR ACTIF CIRCULANT</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 30</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[29]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">DOTATIONS AUX PROVISIONS POUR RISQUES ET CHARGES</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 31</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[30]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft14">CHARGES FINANCIERES (Intérêts, escomptes accordés, pertes sur créances liées à des participations, pertes de changes)</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 32</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[31]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">AUTRES CHARGES (à préciser )</P></TD>
	<TD class="tr10 td13"><P class="p6 ft8">RC 33</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[32]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td7"><P class="p0 ft9">&nbsp;</P></TD>
	<TD class="tr8 td8"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td9"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr8 td10"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr7 td11"><P class="p0 ft9">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr3 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr4 td8"><P class="p3 ft15">TOTAL CHARGES</P></TD>
	<TD class="tr4 td9"><P class="p7 ft16">RC 34</P></TD>
	<TD class="tr4 td10"><P class="p0 ft5bis"><?php print myfix($f[33]); ?></P></TD>
	<TD class="tr3 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr13 td15"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr11 td16"><P class="p0 ft5">&nbsp;</P></TD>
	<TD colspan=2 class="tr11 td17"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr13 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr0 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr0 td12"><P class="p3 ft6">IMPOT SUR LES TRANSACTIONS</P></TD>
	<TD class="tr0 td13"><P class="p7 ft17">RC 35</P></TD>
	<TD class="tr0 td14"><P class="p0 ft5bis"><?php print myfix($f[34]); ?></P></TD>
	<TD class="tr0 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">PENALITES ET AMENDES</P></TD>
	<TD class="tr10 td13"><P class="p7 ft13">RC 36</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[35]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">PRELEVEMENTS DE L’EXPLOITANT ET DE SON CONJOINT, SALAIRES DES GERANTS</P></TD>
	<TD class="tr10 td13"><P class="p7 ft13">RC 37</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[36]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr10 td7"><P class="p0 ft5">&nbsp;</P></TD>
	<TD class="tr10 td12"><P class="p3 ft6">COTISATIONS PERSONNELLES DE L’EXPLOITANT</P></TD>
	<TD class="tr10 td13"><P class="p7 ft13">RC 38</P></TD>
	<TD class="tr10 td14"><P class="p0 ft5bis"><?php print myfix($f[37]); ?></P></TD>
	<TD class="tr10 td11"><P class="p0 ft5">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr8 td7"><P class="p0 ft10">&nbsp;</P></TD>
	<TD class="tr9 td8"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td9"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr9 td10"><P class="p0 ft12">&nbsp;</P></TD>
	<TD class="tr8 td11"><P class="p0 ft10">&nbsp;</P></TD>
</TR>
</TABLE>
<P class="p8 ft18"><NOBR>DICP/IT/SN/N004-3/V1/14</NOBR></P>
<P class="p9 ft19">Signature</P>
<P class="p10 ft6">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
</DIV>
<DIV id="id_2">
<P class="p11 ft20">1</P>
</DIV>
</DIV>
</DIV>
</BODY>
</HTML>
