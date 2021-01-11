<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<title>Bilan de l\'entreprise</title>
<link rel="stylesheet" href="declaration/dicp.css">
<link rel="stylesheet" href="declaration/print.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
</HEAD>
<?php

error_reporting(E_ALL ^ E_NOTICE); # or define all of $f

$f = array();

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

  # TODO index to null
  
  ###### fixed
  $query = 'select value,debit,acnumber,balancesheetindexid from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=? and balancesheetindexid not like "%000%"';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');

  for ($i = 0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['balancesheetindexid'];
    if (!isset($f[$index])) { $f[$index] = 0; }
    $positive = 1;
    if ($query_result[$i]['debit'] == 1)
    {
      if (substr($index, 1, 2) == '1')
      {
        $positive = 1;
      }
      else
      {
        $positive = 0;
      }
    }
    else
    {
      if (substr($index, 1, 2) == '1')
      {
        $positive = 0;
      }
      else
      {
        $positive = 1;
      }
    }
    if ($positive == 1)
    {
      $f[$index] = d_add($f[$index], $query_result[$i]['value']);
    }
    else
    {
      $f[$index] = d_subtract($f[$index], $query_result[$i]['value']);
    }
  }
  ######
  
  ### 2015 04 23 new calc for field V
  $query = 'select sum(value) as val from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and accountinggroupid=7 and year(adjustmentdate)=? and debit=0';
  $query_prm = array($year);
  require('inc/doquery.php');
  $f['V'] = $query_result[0]['val'];
  $query = 'select sum(value) as val from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and accountinggroupid=7 and year(adjustmentdate)=? and debit=1';
  $query_prm = array($year);
  require('inc/doquery.php');
  $f['V'] -= $query_result[0]['val'];
  $query = 'select sum(value) as val from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and accountinggroupid=6 and year(adjustmentdate)=? and debit=0';
  $query_prm = array($year);
  require('inc/doquery.php');
  $f['V'] += $query_result[0]['val'];
  $query = 'select sum(value) as val from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and accountinggroupid=6 and year(adjustmentdate)=? and debit=1';
  $query_prm = array($year);
  require('inc/doquery.php');
  $f['V'] -= $query_result[0]['val'];
  
  # 2017 02 06 corrections for negative values
  # si Z, AA, AB, AC, AE, AF sont négatif tu mets sur J1. si I1, J1, K1 et L1 sont négatif tu mets sur AF
  if (d_compare($f['Z'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['Z'])); $f['Z'] = 0; }
  if (d_compare($f['AA'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['AA'])); $f['AA'] = 0; }
  if (d_compare($f['AB'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['AB'])); $f['AB'] = 0; }
  if (d_compare($f['AC'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['AC'])); $f['AC'] = 0; }
  if (d_compare($f['AE'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['AE'])); $f['AE'] = 0; }
  if (d_compare($f['AF'], 0) == -1) { $f['J1'] = d_add($f['J1'], d_abs($f['AF'])); $f['AF'] = 0; }
  
  if (d_compare($f['I1'], 0) == -1) { $f['AF'] = d_add($f['AF'], d_abs($f['I1'])); $f['I1'] = 0; }
  if (d_compare($f['J1'], 0) == -1) { $f['AF'] = d_add($f['AF'], d_abs($f['J1'])); $f['J1'] = 0; }
  if (d_compare($f['K1'], 0) == -1) { $f['AF'] = d_add($f['AF'], d_abs($f['K1'])); $f['K1'] = 0; }
  if (d_compare($f['L1'], 0) == -1) { $f['AF'] = d_add($f['AF'], d_abs($f['L1'])); $f['L1'] = 0; }
	
  # TEST VALUES
	/*$f['A1'] = 11111111; $f['B1'] = 2222222; $f['C1'] = 3333333; $f['D1'] = 4444444;
	$f['A2'] = 4444444; $f['B2'] = 5555555; $f['C2'] = 6666666; $f['D2'] = 7777777;
	$f['F1'] = 11111111; $f['G1']= 2222222; $f['H1']  = 3333333; $f['I1'] = 4444444; $f['J1'] = 5555555; $f['K1'] = 6666666; $f['L1']= 77777777;
  $f['F2'] = 4444444; $f['G2']= 5555555; $f['H2']  = 6666666; $f['I2'] = 7777777; $f['J2'] = 7777777; $f['K2'] = 88888888; $f['L2']= 9999999;
  $f['E1'] = 11111111; $f['M1']= 2222222; $f['N1']  = 3333333; $f['O1']= 4444444;
  $f['E2'] = 77777777; $f['M2']= 6666666;  $f['N2']  = 44444444; $f['O2']= 5555555;
  $f['Q'] = 11111111; $f['R']= 2222222;  $f['S']  = 3333333; $f['T'] = 4444444; $f['U'] = 5555555; $f['V'] = 6666666; $f['W']= 77777777;
  $f['Z'] = 11111111; $f['AA']= 2222222;  $f['AB']  = 3333333; $f['AC'] = 4444444; $f['AD'] = 5555555; $f['AE'] = 6666666; $f['AF']= 77777777;
  $f['X'] = 11111111; $f['Y'] = 2222222;  $f['AG']  = 3333333; $f['AH']= 4444444;*/
	
	
  $f['E1'] = $f['A1'] + $f['B1'] + $f['C1'] + $f['D1'];
  $f['E2'] = $f['A2'] + $f['B2'] + $f['C2'] + $f['D2'];
  $f['M1'] = $f['F1'] + $f['G1'] + $f['H1'] + $f['I1'] + $f['J1'] + $f['K1'] + $f['L1'];
  $f['M2'] = $f['F2'] + $f['G2'] + $f['H2'] + $f['I2'] + $f['J2'] + $f['K2'] + $f['L2'];
  $f['P1'] = $f['E1'] + $f['M1'] + $f['N1'] + $f['O1'];
  $f['P2'] = $f['E2'] + $f['M2'] + $f['N2'] + $f['O2'];
  $f['X'] = $f['Q'] + $f['R'] + $f['S'] + $f['T'] + $f['U'] + $f['V'] + $f['W'];
  $f['AG'] = $f['Z'] + $f['AA'] + $f['AB'] + $f['AC'] + $f['AD'] + $f['AE'] + $f['AF'];
  $f['AI'] = $f['X'] + $f['Y'] + $f['AG'] + $f['AH'];
  $f['A3'] = $f['A1'] - $f['A2'];
  $f['B3'] = $f['B1'] - $f['B2'];
  $f['C3'] = $f['C1'] - $f['C2'];
  $f['D3'] = $f['D1'] - $f['D2'];
  $f['E3'] = $f['E1'] - $f['E2'];
  $f['F3'] = $f['F1'] - $f['F2'];
  $f['G3'] = $f['G1'] - $f['G2'];
  $f['H3'] = $f['H1'] - $f['H2'];
  $f['I3'] = $f['I1'] - $f['I2'];
  $f['J3'] = $f['J1'] - $f['J2'];
  $f['K3'] = $f['K1'] - $f['K2'];
  $f['L3'] = $f['L1'] - $f['L2'];
  $f['M3'] = $f['M1'] - $f['M2'];
  $f['N3'] = $f['N1'] - $f['N2'];
  $f['O3'] = $f['O1'] - $f['O2'];
  $f['P3'] = $f['P1'] - $f['P2'];
}
else
{
  $duedate = '';
  $period = '<br>';
}
?>
<section id="share">
  <a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
</section>
<div id="maindicp">
<DIV id="page_4">
<DIV id="dimg1">
<IMG src="declaration/img/dicp4.jpg" id="img1">
</DIV>


<DIV id="id_1">
<TABLE cellpadding=0 cellspacing=0 class="t7">
<TR>
	<TD class="tr10 td63"><P class="p15 ft6">N° TAHITI : <?php echo $numero_tahiti;?></P></TD>
	<TD colspan=3 class="tr10 td64"><P class="p40 ft1">BILAN DE L’ENTREPRISE</P></TD>
</TR>
<TR>
	<TD class="tr1 td63"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr1 td65"><P class="p15 ft31">Exercice du 01/01/<?php echo $year; ?></P></TD>
	<TD class="tr1 td66"><P class="p15 ft31"> au 31/12/<?php echo $year; ?></P></TD>
</TR>
</TABLE>
<P class="p49 ft32">Partie à renseigner par les seuls assujettis astreints aux nouvelles obligations de 2013(1) concernant les exercices clos à compter du 31/12/2012</P>
<P class="p50 ft33">(1) cette partie de la déclaration (bilan et compte de résultat) est à renseigner si votre chiffre d’affaires est supérieur à :</P>
<P class="p51 ft34"><SPAN class="ft34">-</SPAN><SPAN class="ft35">15 millions F CFP si votre activité consiste à vendre des marchandises, objets, fournitures, denrées à emporter ou à consommer sur place, ou à fournir le logement ;</SPAN></P>
<P class="p52 ft33"><SPAN class="ft33">-</SPAN><SPAN class="ft36">6 millions F CFP pour les autres activités.</SPAN></P>
<P class="p53 ft37">I - ACTIF</P>
<TABLE cellpadding=0 cellspacing=0 class="t8">
<TR>
	<TD class="tr15 td68"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td69"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td70"><P class="p16 ft38">VALEUR BRUTE</P></TD>
	<TD class="tr15 td69"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td71"><P class="p54 ft38">AMORT/PROV</P></TD>
	<TD class="tr15 td72"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td73"><P class="p55 ft38">VALEUR NETTE</P></TD>
	<TD class="tr15 td74"><P class="p54 ft38">VALEUR NETTE</P></TD>
</TR>
<TR>
	<TD class="tr16 td75"><P class="p56 ft37">POSTES</P></TD>
	<TD colspan=2 class="tr16 td76"><P class="p57 ft39">A LA CLOTURE DE</P></TD>
	<TD class="tr16 td44"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr16 td77"><P class="p58 ft39">A LA CLOTURE DE</P></TD>
	<TD class="tr16 td78"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr16 td79"><P class="p58 ft39">A LA CLOTURE DE</P></TD>
	<TD class="tr16 td80"><P class="p59 ft37">A LA CLOTURE DE</P></TD>
</TR>
<TR>
	<TD class="tr15 td81"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td39"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td82"><P class="p60 ft40">N</P></TD>
	<TD class="tr15 td39"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td83"><P class="p58 ft40">N</P></TD>
	<TD class="tr15 td84"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr15 td85"><P class="p61 ft38">N</P></TD>
	<TD class="tr15 td86"><P class="p62 ft40"><NOBR>N-1</NOBR></P></TD>
</TR>
<TR>
	<TD class="tr15 td81"><P class="p42 ft21">FONDS DE COMMERCE (A)</P></TD>
	<TD class="tr15 td87"><P class="p63 ft41">(A1)</P></TD>
	<TD class="tr15 td82"><P class="p15ter ft15ter"><?php print myfix($f['A1']); ?></P></TD>
	<TD class="tr15 td87"><P class="p63 ft41">(A2)</P></TD>
	<TD class="tr15 td83"><P class="p15ter ft15ter"><?php print myfix($f['A2']); ?></P></TD>
	<TD class="tr15 td88"><P class="p63 ft41">(A3)</P></TD>
	<TD class="tr15 td85"><P class="p15ter ft15ter"><?php print myfix($f['A3']); ?></P></TD>
	<TD class="tr15 td86"><P class="p15ter ft15ter"><?php print myfix($f['A4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">AUTRES IMMOBILISATIONS INCORPORELLES (B)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft41">(B1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['B1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft41">(B2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['B2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft41">(B3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['B3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['B4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">IMMOBILISATIONS CORPORELLES (C)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(C1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['C1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(C2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['C2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft42">(C3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['C3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['C4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td81"><P class="p42 ft21">IMMOBILISATIONS FINANCIERES (D)</P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(D1)</P></TD>
	<TD class="tr17 td82"><P class="p15ter ft15ter"><?php print myfix($f['D1']); ?></P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(D2)</P></TD>
	<TD class="tr17 td83"><P class="p15ter ft15ter"><?php print myfix($f['D2']); ?></P></TD>
	<TD class="tr17 td88"><P class="p63 ft43">(D3)</P></TD>
	<TD class="tr17 td85"><P class="p15ter ft15ter"><?php print myfix($f['D3']); ?></P></TD>
	<TD class="tr17 td86"><P class="p15ter ft15ter"><?php print myfix($f['D4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td81"><P class="p42 ft38">TOTAL ACTIF IMMOBLISE (E) =(A)+(B)+(C)+(D)</P></TD>
	<TD class="tr17 td87"><P class="p64 ft44">(E1)</P></TD>
	<TD class="tr17 td82"><P class="p15ter ft15ter"><?php print myfix($f['E1']); ?></P></TD>
	<TD class="tr17 td87"><P class="p63 ft44">(E2)</P></TD>
	<TD class="tr17 td83"><P class="p15ter ft15ter"><?php print myfix($f['E2']); ?></P></TD>
	<TD class="tr17 td88"><P class="p63 ft44">(E3)</P></TD>
	<TD class="tr17 td85"><P class="p15ter ft15ter"><?php print myfix($f['E3']); ?></P></TD>
	<TD class="tr17 td86"><P class="p15ter ft15ter"><?php print myfix($f['E4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">STOCKS MARCHANDISES (F)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(F1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['F1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(F2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['F2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft42">(F3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['F3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['F4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td81"><P class="p42 ft21">AUTRES STOCKS ET ENCOURS (G)</P></TD>
	<TD class="tr15 td87"><P class="p63 ft41">(G1)</P></TD>
	<TD class="tr15 td82"><P class="p15ter ft15ter"><?php print myfix($f['G1']); ?></P></TD>
	<TD class="tr15 td87"><P class="p63 ft41">(G2)</P></TD>
	<TD class="tr15 td83"><P class="p15ter ft15ter"><?php print myfix($f['G2']); ?></P></TD>
	<TD class="tr15 td88"><P class="p63 ft41">(G3)</P></TD>
	<TD class="tr15 td85"><P class="p15ter ft15ter"><?php print myfix($f['G3']); ?></P></TD>
	<TD class="tr15 td86"><P class="p15ter ft15ter"><?php print myfix($f['G4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">ACOMPTES VERSES SUR COMMANDE (H)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(H1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['H1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(H2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['H2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft42">(H3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['H3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['H4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td81"><P class="p42 ft21">CREANCES CLIENTS (I)</P></TD>
	<TD class="tr15 td87"><P class="p64 ft41">(I1)</P></TD>
	<TD class="tr15 td82"><P class="p15ter ft15ter"><?php print myfix($f['I1']); ?></P></TD>
	<TD class="tr15 td87"><P class="p64 ft41">(I2)</P></TD>
	<TD class="tr15 td83"><P class="p15ter ft15ter"><?php print myfix($f['I2']); ?></P></TD>
	<TD class="tr15 td88"><P class="p63 ft41">(I3)</P></TD>
	<TD class="tr15 td85"><P class="p15ter ft15ter"><?php print myfix($f['I3']); ?></P></TD>
	<TD class="tr15 td86"><P class="p15ter ft15ter"><?php print myfix($f['I4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft45">AUTRES CREANCES (J)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft41">(J1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['J1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft41">(J2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['J2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft41">(J3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['J3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['J4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">VALEURS MOBILIERES PLACEMENT (K)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(K1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['K1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(K2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['K2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft42">(K3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['K3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['K4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td81"><P class="p42 ft21">DISPONIBILITES (L)</P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(L1)</P></TD>
	<TD class="tr17 td82"><P class="p15ter ft15ter"><?php print myfix($f['L1']); ?></P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(L2)</P></TD>
	<TD class="tr17 td83"><P class="p15ter ft15ter"><?php print myfix($f['L2']); ?></P></TD>
	<TD class="tr17 td88"><P class="p63 ft43">(L3)</P></TD>
	<TD class="tr17 td85"><P class="p15ter ft15ter"><?php print myfix($f['L3']); ?></P></TD>
	<TD class="tr17 td86"><P class="p15ter ft15ter"><?php print myfix($f['L4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td81"><P class="p42 ft40">TOTAL ACTIF CIRCULANT (M) =(F)+(G)+(H)+(I)+(J)+(K)+(L)</P></TD>
	<TD class="tr17 td87"><P class="p64 ft44">(M1)</P></TD>
	<TD class="tr17 td82"><P class="p15ter ft15ter"><?php print myfix($f['M1']); ?></P></TD>
	<TD class="tr17 td87"><P class="p63 ft44">(M2)</P></TD>
	<TD class="tr17 td83"><P class="p15ter ft15ter"><?php print myfix($f['M2']); ?></P></TD>
	<TD class="tr17 td88"><P class="p63 ft44">(M3)</P></TD>
	<TD class="tr17 td85"><P class="p15ter ft15ter"><?php print myfix($f['M3']); ?></P></TD>
	<TD class="tr17 td86"><P class="p15ter ft15ter"><?php print myfix($f['M4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td81"><P class="p42 ft21">CHARGES CONSTATEES D’AVANCE (N)</P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(N1)</P></TD>
	<TD class="tr7 td82"><P class="p15ter ft15ter"><?php print myfix($f['N1']); ?></P></TD>
	<TD class="tr7 td87"><P class="p63 ft42">(N2)</P></TD>
	<TD class="tr7 td83"><P class="p15ter ft15ter"><?php print myfix($f['N2']); ?></P></TD>
	<TD class="tr7 td88"><P class="p63 ft42">(N3)</P></TD>
	<TD class="tr7 td85"><P class="p15ter ft15ter"><?php print myfix($f['N3']); ?></P></TD>
	<TD class="tr7 td86"><P class="p15ter ft15ter"><?php print myfix($f['N4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td81"><P class="p42 ft21">COMPTE DE REGULARISATION ACTIF (O)</P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(O1)</P></TD>
	<TD class="tr17 td82"><P class="p15ter ft15ter"><?php print myfix($f['O1']); ?></P></TD>
	<TD class="tr17 td87"><P class="p63 ft43">(O2)</P></TD>
	<TD class="tr17 td83"><P class="p15ter ft15ter"><?php print myfix($f['O2']); ?></P></TD>
	<TD class="tr17 td88"><P class="p63 ft43">(O3)</P></TD>
	<TD class="tr17 td85"><P class="p15ter ft15ter"><?php print myfix($f['O3']); ?></P></TD>
	<TD class="tr17 td86"><P class="p15ter ft15ter"><?php print myfix($f['O4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td75"><P class="p42 ft38">TOTAL GENERAL (P) = (E) + (M) + (N) + (O)</P></TD>
	<TD class="tr15 td89"><P class="p63 ft44">(P1)</P></TD>
	<TD class="tr15 td90"><P class="p15ter ft15ter"><?php print myfix($f['P1']); ?></P></TD>
	<TD class="tr15 td89"><P class="p63 ft44">(P2)</P></TD>
	<TD class="tr15 td77"><P class="p15ter ft15ter"><?php print myfix($f['P2']); ?></P></TD>
	<TD class="tr15 td91"><P class="p63 ft44">(P3)</P></TD>
	<TD class="tr15 td79"><P class="p15ter ft15ter"><?php print myfix($f['P3']); ?></P></TD>
	<TD class="tr15 td80"><P class="p15ter ft15ter"><?php print myfix($f['P4']); ?></P></TD>
</TR>
<TR>
	<TD class="tr12 td81"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td87"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td82"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td87"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td83"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td88"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td85"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td86"><P class="p15 ft29">&nbsp;</P></TD>
</TR>
</TABLE>
<P class="p65 ft38">II - PASSIF</P>
<TABLE cellpadding=0 cellspacing=0 class="t9">
<TR>
	<TD class="tr16 td92"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr16 td92bis"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr16 td93"><P class="p66 ft37">NET</P></TD>
	<TD class="tr16 td94"><P class="p67 ft39">NET</P></TD>
</TR>
<TR>
	<TD class="tr16 td92"><P class="p68 ft37">POSTES</P></TD>
	<TD class="tr16 td92bis"><P class="p15 ft11">&nbsp;</P></TD>	
	<TD class="tr16 td93"><P class="p66 ft39">A LA CLOTURE DE</P></TD>
	<TD class="tr16 td94"><P class="p15 ft37">A LA CLOTURE DE</P></TD>
</TR>
<TR>
	<TD class="tr3 td95"><P class="p15 ft11">&nbsp;</P></TD>
	<TD class="tr3 td95bis"><P class="p15 ft11">&nbsp;</P></TD>	
	<TD class="tr3 td96"><P class="p66 ft40">N</P></TD>
	<TD class="tr3 td97"><P class="p67 ft38"><NOBR>N-1</NOBR></P></TD>
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">CAPITAL SOCIAL OU INDIVIDUEL</P></TD>
	<TD class="tr15 td95bis"><P class="p15bis ft21bis">(Q)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['Q']); ?></P></TD>	
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['Q2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td95"><P class="p42 ft21">ECARTS DE REEVALUATION</P></TD>
	<TD class="tr7 td95bis"><P class="p15bis ft21">(R)</P></TD>
	<TD class="tr7 td96"><P class="p15ter ft15ter"><?php print myfix($f['R']); ?></P></TD>
	<TD class="tr7 td97"><P class="p15ter ft15ter"><?php print myfix($f['R2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">RESERVE LEGALE</P></TD>
	<TD class="tr15 td95bis"><P class="p15bis ft21">(S)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['S']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['S2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">AUTRES RESERVES</P></TD>
	<TD class="tr15 td95bis"><P class="p15bis ft21">(T)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['T']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['T2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">REPORT A NOUVEAU</P></TD>
	<TD class="tr15 td95bis"><P class="p15bis ft21">(U)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['U']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['U2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td95"><P class="p42 ft21">RESULTAT DE L’EXERCICE</P></TD>
	<TD class="tr7 td95bis"><P class="p15bis ft45">(V)</P></TD>
	<TD class="tr7 td96"><P class="p15ter ft15ter"><?php print myfix($f['V']); ?></P></TD>
	<TD class="tr7 td97"><P class="p15ter ft15ter"><?php print myfix($f['V2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr11bis td95"><P class="p42 ft27">PROVISIONS REGLEMENTEES</P></TD>
	<TD class="tr11bis td95bis"><P class="p15bis ft27">(W)</P></TD>
	<TD class="tr11bis td96"><P class="p15ter ft15ter"><?php print myfix($f['W']); ?></P></TD>
	<TD class="tr11bis td97"><P class="p15ter ft15ter"><?php print myfix($f['W2']); ?></P></TD>
</TR>
</TABLE>
<TABLE cellpadding=0 cellspacing=0 class="t10">
<TR>
	<TD class="tr3 td95"><P class="p42 ft38">TOTAL CAPITAUX PROPRES (X) = (Q) + (R) + (S) + (T) + (U) + (V) + (W)</P></TD>
	<TD class="tr3 td95bis"><P class="p17 ft38">(X)</P></TD>
	<TD class="tr3 td96"><P class="p15ter ft15ter"><?php print myfix($f['X']); ?></P></TD>
	<TD class="tr3 td97"><P class="p15ter ft15ter"><?php print myfix($f['X2']); ?></P></TD>	
</TR>
<TR>
	<TD class="tr17 td95"><P class="p42 ft38">PROVISIONS POUR RISQUES ET CHARGES (Y)</P></TD>
	<TD class="tr17 td95bis"><P class="p17 ft38">(Y)</P></TD>
	<TD class="tr17 td96"><P class="p15ter ft15ter"><?php print myfix($f['Y']); ?></P></TD>
	<TD class="tr17 td97"><P class="p15ter ft15ter"><?php print myfix($f['Y2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">DETTES FOURNISSEURS</P></TD>
	<TD class="tr15 td95bis"><P class="p17 ft21">(Z)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['Z']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['Z2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr7 td95"><P class="p42 ft21">EMPRUNTS ET DETTES FINANCIERES DIVERS (DONT COMPTES COURANTS ASSOCIES)</P></TD>
	<TD class="tr7 td95bis"><P class="p17 ft21">(AA)</P></TD>
	<TD class="tr7 td96"><P class="p15ter ft15ter"><?php print myfix($f['AA']); ?></P></TD>
	<TD class="tr7 td97"><P class="p15ter ft15ter"><?php print myfix($f['AA2']); ?></P></TD>	
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">EMPRUNT AUPRES DES ETABLISSEMENT DE CREDIT</P></TD>
	<TD class="tr15 td95bis"><P class="p17 ft21">(AB)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['AB']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['AB2']); ?></P></TD>	
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">DETTES FISCALES ET SOCIALES</P></TD>
	<TD class="tr15 td95bis"><P class="p17 ft21">(AC)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['AC']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['AC2']); ?></P></TD>	
</TR>
<TR>
	<TD class="tr15 td95"><P class="p42 ft21">AVANCES ET ACOMPTES RECUS SUR COMMANDE EN COURS</P></TD>
	<TD class="tr15 td95bis"><P class="p17 ft21">(AD)</P></TD>
	<TD class="tr15 td96"><P class="p15ter ft15ter"><?php print myfix($f['AD']); ?></P></TD>
	<TD class="tr15 td97"><P class="p15ter ft15ter"><?php print myfix($f['AD2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr7 td95"><P class="p42 ft21">DETTES SUR IMMOBILISATIONS ET COMPTES RATTACHES</P></TD>
	<TD class="tr7 td95bis"><P class="p17 ft21">(AE)</P></TD>
	<TD class="tr7 td96"><P class="p15ter ft15ter"><?php print myfix($f['AE']); ?></P></TD>
	<TD class="tr7 td97"><P class="p15ter ft15ter"><?php print myfix($f['AE2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr17 td95"><P class="p42 ft21">AUTRES DETTES</P></TD>
	<TD class="tr17 td95bis"><P class="p17 ft45">(AF)</P></TD>
	<TD class="tr17 td96"><P class="p15ter ft15ter"><?php print myfix($f['AF']); ?></P></TD>
	<TD class="tr17 td97"><P class="p15ter ft15ter"><?php print myfix($f['AF2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr17 td95"><P class="p42 ft37">TOTAL DETTES (AG) = (Z) + (AA) + (AB) + (AC) + (AD) + (AE) + (AF)</P></TD>
	<TD class="tr17 td95bis"><P class="p15 ft46">(AG)</P></TD>
	<TD class="tr17 td96"><P class="p15ter ft15ter"><?php print myfix($f['AG']); ?></P></TD>
	<TD class="tr17 td97"><P class="p15ter ft15ter"><?php print myfix($f['AG2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr17 td95"><P class="p42 ft21">COMPTE DE REGULARISATION PASSIF</P></TD>
	<TD class="tr17 td95bis"><P class="p15 ft45">(AH)</P></TD>
	<TD class="tr17 td96"><P class="p15ter ft15ter"><?php print myfix($f['AH']); ?></P></TD>
	<TD class="tr17 td97"><P class="p15ter ft15ter"><?php print myfix($f['AH2']); ?></P></TD>		
</TR>
<TR>
	<TD class="tr1bis td95"><P class="p42 ft38">TOTAL GENERAL (AI) = (X) + (Y) + (AG) + (AH)</P></TD>
	<TD class="tr1bis td95bis"><P class="p17 ft38">(AI)</P></TD>
	<TD class="tr1bis td96"><P class="p15ter ft15ter"><?php print myfix($f['AI']); ?></P></TD>
	<TD class="tr1bis td97"><P class="p15ter ft15ter"><?php print myfix($f['AI2']); ?></P></TD>		
</TR>
</TABLE>
<P class="p69 ft47"><NOBR>DICP/IT/SN/N004-1/V1/14</NOBR></P>
<P class="p70 ft17">Signature</P>
<P class="p71 ft21">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
</DIV>
<DIV id="id_2">
<P class="p29 ft22">4</P>
</DIV>
</DIV>
</DIV>
</BODY>
</HTML>