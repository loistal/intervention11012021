<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<title>Compte de Resultat</title>
<link rel="stylesheet" href="declaration/dicp.css">
<link rel="stylesheet" href="declaration/print.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
</HEAD>
<?php

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

  $query = 'select value,debit,acnumber,balancesheetindexid from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=? and balancesheetindexid not like "%000%"';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['balancesheetindexid'];
    if ($query_result[$i]['debit'] == 1)
    {
      $positive = 1;
      switch ($index)
      {
        case 'CA':
        case 'CB':
        case 'CC':
        case 'CD':
        case 'CE':
        case 'CF':
        case 'CH':
        case 'CI':
        case 'CJ':
        case 'CK':
        case 'CAD':
        case 'CAE':
        case 'CAI':
          $positive = 0;
          break;
      }
    }
    else
    {
      $positive = 0;
      switch ($index)
      {
        case 'CA':
        case 'CB':
        case 'CC':
        case 'CD':
        case 'CE':
        case 'CF':
        case 'CH':
        case 'CI':
        case 'CJ':
        case 'CK':
        case 'CAD':
        case 'CAE':
        case 'CAI':
          $positive = 1;
          break;
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
	# TEST VALUES
	/*$f['CA'] = $f['CB'] = $f['CC'] = $f['CD'] = $f['CE'] = $f['CF'] = 1000000;
	$f['CL'] = $f['CG'] = $f['CH'] = $f['CI'] = $f['CJ'] = $f['CK'] = 2000000;
	$f['CM'] = $f['CN'] = $f['CO'] = $f['CP'] = $f['CQ'] = $f['CR'] = 30000000;
  $f['CS'] = $f['CT'] = $f['CU'] = $f['CV'] = $f['CW'] = $f['CX'] = $f['CY'] = $f['CZ'] = $f['CAA'] = 4000000;*/
	
  $f['CG'] = $f['CA'] + $f['CB'] + $f['CC'] + $f['CD'] + $f['CE'] + $f['CF'];
  $f['CL'] = $f['CG'] + $f['CH'] + $f['CI'] + $f['CJ'] + $f['CK'];
  $f['CAB'] = $f['CM'] + $f['CN'] + $f['CO'] + $f['CP'] + $f['CQ'] + $f['CR'] + $f['CS'] + $f['CT'] + $f['CU'] + $f['CV'] + $f['CW'] + $f['CX'] + $f['CY'] + $f['CZ'] + $f['CAA'];
  $f['CAC'] = $f['CL'] - $f['CAB'];
  $f['CAF'] = $f['CAD'] + $f['CAE'];
  $f['CAH'] = $f['CAF'] - $f['CAG'];
  $f['CAK'] = $f['CAI'] - $f['CAJ'];
  $f['CAL'] = $f['CAC'] + $f['CAH'] + $f['CAK'];
  if ($f['CAL'] < 0)
  {
    #$f['CAL'] = '(' . myfix(d_abs($f['CAL'])) . ')';
    $f['CAL'] = '-' . myfix(d_abs($f['CAL']));
  }
  else
  {
    $f['CAL'] = myfix($f['CAL']);
  }
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
<DIV id="page_5">
<DIV id="dimg1">
<IMG src="declaration/img/dicp5.jpg" id="img1">
</DIV>


<DIV id="id_1">
<TABLE cellpadding=0 cellspacing=0 class="t11">
<TR>
	<TD class="tr1 td102"><P class="p15 ft48">N° TAHITI : <?php echo $numero_tahiti;?></P></TD>
	<TD class="tr1 td103"><P class="p15 ft49">COMPTE DE RESULTAT DE L’ENTREPRISE</P></TD>
</TR>
<TR>
	<TD class="tr18 td102"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr18 td103"><P class="p72 ft31">Exercice du 01/01/<?php echo $year; ?> au 31/12/<?php echo $year; ?></P></TD>
</TR>
</TABLE>
<P class="p73 ft6">Partie à renseigner par les seuls assujettis astreints aux nouvelles obligations de 2013 (1)</P>
<P class="p74 ft6">concernant les exercices clos à compter du 31/12/2012</P>
<P class="p75 ft50"><SPAN class="ft50">(1)</SPAN><SPAN class="ft51">Cette partie de la déclaration (bilan et compte de résultat) est à renseigner si votre chiffre d’affaires est supérieur à :</SPAN></P>
<P class="p76 ft50"><SPAN class="ft50">-</SPAN><SPAN class="ft52">15 millions F CFP si votre activité consiste à vendre des marchandises, objets, fournitures, denrées à emporter ou à consommer sur place, ou à fournir le logement ;</SPAN></P>
<P class="p77 ft33"><SPAN class="ft33">-</SPAN><SPAN class="ft36">6 millions F CFP pour les autres activités.</SPAN></P>
<TABLE cellpadding=0 cellspacing=0 class="t12">
<TR>
	<TD class="tr19 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td105"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td106"><P class="p78 ft37">I- RESULTAT D’EXPLOITATION</P></TD>
	<TD class="tr7 td107"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td108"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td109"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td110"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td111"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr2 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td114"><P class="p15 ft21">POSTES</P></TD>
	<TD class="tr20 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td116"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td117"><P class="p79 ft21">TOTAL N</P></TD>
	<TD class="tr20 td118"><P class="p80 ft21">TOTAL <NOBR>N-1</NOBR></P></TD>
</TR>
<TR>
	<TD class="tr17 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td113"><P class="p15 ft45">VENTES DE MARCHANDISES</P></TD>
	<TD class="tr7 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td115"><P class="p15 ft45">SUR LE TERRITOIRE</P></TD>
	<TD class="tr7 td116"><P class="p81 ft21">(A)</P></TD>
	<TD class="tr7 td117"><P class="p15ter ft15ter"><?php print myfix($f['CA']); ?></P></TD>
	<TD class="tr7 td118"><P class="p15ter ft15ter"><?php print myfix($f['CA2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p82 ft21">A L’EXPORT</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(B)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CB']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CB2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td113"><P class="p15 ft21">PRODUCTION VENDUE</P></TD>
	<TD class="tr3 td114"><P class="p83 ft21">- BIENS</P></TD>
	<TD class="tr3 td115"><P class="p15 ft21">SUR LE TERRITOIRE</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(C)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CC']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CC2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p82 ft21">A L’EXPORT</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(D)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CD']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CD2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td114"><P class="p83 ft21">- SERVICES</P></TD>
	<TD class="tr3 td115"><P class="p15 ft21">SUR LE TERRITOIRE</P></TD>
	<TD class="tr3 td116"><P class="p81 ft45">(E)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CE']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CE2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p82 ft21">A L’EXPORT</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(F)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CF']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CF2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr2 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td119"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 rowspan=2 class="tr17 td120"><P class="p15 ft37">TOTAL CHIFFRE D’AFFAIRES (G) = (A) + (B) + (C) + (D) + (E) + (F)</P></TD>
	<TD class="tr2 td121"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td122"><P class="p59 ft37">(G)</P></TD>
	<TD class="tr2 td123"><P class="p15ter ft15ter"><?php print myfix($f['CG']); ?></P></TD>
	<TD class="tr2 td124"><P class="p15ter ft15ter"><?php print myfix($f['CG2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr4 td104"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr12 td112"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td115"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td116"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td117"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td118"><P class="p15 ft29">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft21">PRODUCTION STOCKEE / IMMOBILISEE</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(H)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CH']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CH2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft21">SUBVENTION D’EXPLOITATION</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p39 ft21">(I)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CI']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CI2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td120"><P class="p15 ft21">REPRISE SUR AMORTISSEMENTS ET PROVISIONS, TRANSFERT DE CHARGES</P></TD>
	<TD class="tr3 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td116"><P class="p84 ft21">(J)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CJ']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CJ2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td113"><P class="p15 ft21">AUTRES PRODUITS</P></TD>
	<TD class="tr7 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr7 td116"><P class="p81 ft45">(K)</P></TD>
	<TD class="tr7 td117"><P class="p15ter ft15ter"><?php print myfix($f['CK']); ?></P></TD>
	<TD class="tr7 td118"><P class="p15ter ft15ter"><?php print myfix($f['CK2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr20 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td119"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 rowspan=2 class="tr7 td120"><P class="p15 ft37">TOTAL PRODUITS D’EXPLOITATION (L) = (G) + (H) + (I) + (J) + (K)</P></TD>
	<TD class="tr20 td121"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td122"><P class="p81 ft37">(L)</P></TD>
	<TD class="tr20 td123"><P class="p15ter ft15ter"><?php print myfix($f['CL']); ?></P></TD>
	<TD class="tr20 td124"><P class="p15ter ft15ter"><?php print myfix($f['CL2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr4 td104"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr12 td112"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td115"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td116"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td117"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td118"><P class="p15 ft29">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr15 td120"><P class="p15 ft21">ACHATS DE MARCHANDISES (DONT DROITS DE DOUANES)</P></TD>
	<TD class="tr15 td115"><P class="p15 ft21">SUR LE TERRITOIRE</P></TD>
	<TD class="tr15 td116"><P class="p59 ft21">(M)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CM']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CM2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr15 td125"><P class="p85 ft21">IMPORTEES PAR L’ENTREPRISE</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(N)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CN']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CN2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft21">VARIATION DU STOCK DE MARCHANDISES</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p59 ft21">(O)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CO']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CO2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td120"><P class="p15 ft21">ACHATS DE MATIERES PREMIERES ET AUTRES APPROVISIONNEMENTS</P></TD>
	<TD class="tr3 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(P)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CP']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CP2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr15 td120"><P class="p15 ft53">VARIATION STOCK DE MATIERES PREMIERES ET AUTRES APPROVISIONNEMENTS</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p59 ft21">(Q)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CQ']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CQ2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td113"><P class="p15 ft21">AUTRES ACHATS ET CHARGES EXTERNES</P></TD>
	<TD class="tr3 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(R)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CR']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CR2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft45">IMPOTS, TAXES ET VERSEMENTS ASSIMILES</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(S)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CS']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CS2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=3 class="tr3 td126"><P class="p15 ft21">SALAIRES ET TRAITEMENTS DU PERSONNEL (à l’exclusion des prélèvements et salaires de la ligne (U))</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(T)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CT']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CT2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=3 class="tr15 td126"><P class="p15 ft21">PRELEVEMENTS DE L’EXPLOITANT ET DE SON CONJOINT, SALAIRES DES GERANTS</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(U)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CU']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CU2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft21">CHARGES SOCIALES</P></TD>
	<TD class="tr15 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(V)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CV']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CV2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td113"><P class="p15 ft21">DOTATIONS</P></TD>
	<TD colspan=2 class="tr3 td125"><P class="p85 ft21">AMORTISSEMENTS SUR IMMOBILISATIONS</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(W</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CW']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CW2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td113"><P class="p15 ft21">DOTATIONS</P></TD>
	<TD colspan=2 class="tr15 td125"><P class="p85 ft21">PROVISIONS SUR IMMOBILISATIONS)</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(X)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CX']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CX2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td120"><P class="p15 ft21">DOTATIONS AUX PROVISIONS SUR ACTIF CIRCULANT</P></TD>
	<TD class="tr3 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td116"><P class="p81 ft21">(Y)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CY']); ?>&nbsp;</P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CY2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr15 td120"><P class="p15 ft45">DOTATIONS AUX PROVISIONS POUR RISQUES ET CHARGES</P></TD>
	<TD class="tr15 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td116"><P class="p81 ft21">(Z)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CZ']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CZ2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td112"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td113"><P class="p15 ft21">AUTRES CHARGES</P></TD>
	<TD class="tr3 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td116"><P class="p86 ft21">(AA)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CAA']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CA2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr15 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td119"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=3 class="tr15 td127"><P class="p15 ft39">TOTAL CHARGES D’EXPLOITATION (AB) = (M) + (N) + (O) + (P) + (Q) + (R) + (S) + (T) + (U) + (V) + (W) + (X) + (Y) + (Z) +</P></TD>
	<TD class="tr15 td122"><P class="p14 ft21">(AB)</P></TD>
	<TD class="tr15 td123"><P class="p15ter ft15ter"><?php print myfix($f['CAB']); ?></P></TD>
	<TD class="tr15 td124"><P class="p15ter ft15ter"><?php print myfix($f['CAB2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr16 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td119"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td128"><P class="p15 ft37">(AA)</P></TD>
	<TD class="tr16 td129"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td121"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td122"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td123"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td124"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr13 td104"><P class="p15 ft30">&nbsp;</P></TD>
	<TD class="tr21 td112"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td113"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td114"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td115"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td116"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td117"><P class="p15 ft54">&nbsp;</P></TD>
	<TD class="tr21 td118"><P class="p15 ft54">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr7 td104"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td130"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td131"><P class="p15 ft37">RESULTAT D’EXPLOITATION (AC) = (L) – (AB)</P></TD>
	<TD class="tr15 td132"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td133"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td134"><P class="p86 ft37">(AC)</P></TD>
	<TD class="tr7 td123"><P class="p15ter ft15ter"><?php print myfix($f['CAC']); ?></P></TD>
	<TD class="tr7 td124"><P class="p15ter ft15ter"><?php print myfix($f['CAC2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr3 td135"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr2 td136"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td137"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td138"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td139"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td140"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr2 td141"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr3 td142"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td143"><P class="p17 ft37">II- RESULTAT FINANCIER</P></TD>
	<TD class="tr3 td132"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td133"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td134"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td144"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td145"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr17 td146"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=3 class="tr17 td147"><P class="p17 ft53">PRODUITS FINANCIERS SUR IMMOBILISATIONS FINANCIERES ET PARTICIPATIONS</P></TD>
	<TD class="tr17 td148"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr17 td149"><P class="p17 ft37">(AD)</P></TD>
	<TD class="tr17 td150"><P class="p15ter ft15ter"><?php print myfix($f['CAD']); ?></P></TD>
	<TD class="tr17 td151"><P class="p15ter ft15ter"><?php print myfix($f['CAD2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr22 td152"><P class="p15 ft55">&nbsp;</P></TD>
	<TD colspan=2 rowspan=2 class="tr15 td153"><P class="p17 ft21">AUTRES PRODUITS FINANCIERS</P></TD>
	<TD class="tr22 td129"><P class="p15 ft55">&nbsp;</P></TD>
	<TD class="tr22 td121"><P class="p15 ft55">&nbsp;</P></TD>
	<TD class="tr22 td122"><P class="p17 ft56">(AE)</P></TD>
	<TD class="tr22 td123"><P class="p15 ft55<?php print myfix($f['CAE']); ?>P></TD>
	<TD class="tr22 td124"><P class="p15 ft55"><?php print myfix($f['CAE2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr4 td142"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr4 td114"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr4 td115"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr4 td116"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr4 td117"><P class="p15 ft14">&nbsp;</P></TD>
	<TD class="tr4 td118"><P class="p15 ft14">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr16 td152"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 rowspan=2 class="tr15 td153"><P class="p17 ft37">TOTAL PRODUITS FINANCIERS (AE) = (AD) + (AE)</P></TD>
	<TD class="tr16 td129"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td121"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td122"><P class="p14 ft37">(AF)</P></TD>
	<TD class="tr16 td123"><P class="p15ter ft15ter"><?php print myfix($f['CAF']); ?></P></TD>
	<TD class="tr16 td124"><P class="p15ter ft15ter"><?php print myfix($f['CAF2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr12 td142"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td114"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td115"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td116"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td117"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td118"><P class="p15 ft29">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr16 td152"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 rowspan=2 class="tr15 td153"><P class="p17 ft37">TOTAL CHARGES FINANCIERES</P></TD>
	<TD class="tr16 td129"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td121"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr16 td122"><P class="p17 ft37">(AG)</P></TD>
	<TD class="tr16 td123"><P class="p15ter ft15ter"><?php print myfix($f['CAG']); ?></P></TD>
	<TD class="tr16 td124"><P class="p15ter ft15ter"><?php print myfix($f['CAG2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr12 td142"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td114"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td115"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td116"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td117"><P class="p15 ft29">&nbsp;</P></TD>
	<TD class="tr12 td118"><P class="p15 ft29">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr15 td142"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr15 td154"><P class="p17 ft46">RESULTAT FINANCIER (AH) = (AF) – (AG)</P></TD>
	<TD class="tr15 td155"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td156"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr15 td157"><P class="p42 ft46">(AH)</P></TD>
	<TD class="tr15 td117"><P class="p15ter ft15ter"><?php print myfix($f['CAH']); ?></P></TD>
	<TD class="tr15 td118"><P class="p15ter ft15ter"><?php print myfix($f['CAH2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr20 td135"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr20 td158"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td159"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td160"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td161"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr20 td162"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr3 td142"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td163"><P class="p17 ft37">III- RESULTAT EXCEPTIONNEL</P></TD>
	<TD class="tr3 td132"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td133"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td134"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td144"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td145"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr17 td146"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr17 td136"><P class="p17 ft21">PRODUITS EXCEPTIONNELS</P></TD>
	<TD class="tr17 td137"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr17 td148"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr17 td149"><P class="p87 ft21">(AI)</P></TD>
	<TD class="tr17 td150"><P class="p15ter ft15ter"><?php print myfix($f['CAI']); ?></P></TD>
	<TD class="tr17 td151"><P class="p15ter ft15ter"><?php print myfix($f['CAI2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr17 td142"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr17 td158"><P class="p17 ft45">CHARGES EXCEPTIONNELLES</P></TD>
	<TD class="tr17 td114"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr17 td115"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr17 td116"><P class="p86 ft45">(AJ)</P></TD>
	<TD class="tr17 td117"><P class="p15ter ft15ter"><?php print myfix($f['CAJ']); ?></P></TD>
	<TD class="tr17 td118"><P class="p15ter ft15ter"><?php print myfix($f['CAJ2']); ?></P></TD>
</TR>
<TR>
	<TD class="tr3 td142"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD colspan=2 class="tr3 td164"><P class="p17 ft37">RESULTAT EXCEPTIONNEL (AK) = (AI) - (AJ)</P></TD>
	<TD class="tr3 td155"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td156"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr3 td157"><P class="p42 ft37">(AK)</P></TD>
	<TD class="tr3 td117"><P class="p15ter ft15ter"><?php print myfix($f['CAK']); ?></P></TD>
	<TD class="tr3 td118"><P class="p15ter ft15ter"><?php print myfix($f['CAK2']); ?></P></TD>
</TR>
</TABLE>
<TABLE cellpadding=0 cellspacing=0 class="t13">
<TR>
	<TD rowspan=2 class="tr7bis td165"><P class="p15 ft39">IV- BENEFICE ou &lt;PERTE&gt;</P></TD>
	<TD rowspan=2 class="tr7bis td166"><P class="p15 ft57"><SPAN class="ft39">(AL) = (AC) + (AH) + (AK) </SPAN>(si votre résultat est déficitaire, veuillez l’indiquer entre parenthèses)</P></TD>
	<TD class="tr7bis td78"><P class="p15 ft58"> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; (AL)</P></TD>
  <td class="tr7bis td78">><P class="p15ter ft15ter"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?php print $f['CAL']; ?></p>
</TR>
<TR>
	<TD class="tr13 td78"><P class="p15 ft30">&nbsp;</P></TD>
</TR>
<TR>
	<TD class="tr23 td165"><P class="p17 ft19"><NOBR>DICP/IT/SN/N004-2/V1/14</NOBR></P></TD>
	<TD class="tr23 td166"><P class="p15ter ft15ter">&nbsp;</P></TD>
	<TD class="tr23 td78"><P class="p15ter ft15ter">&nbsp;</P></TD>
</TR>
</TABLE>
<P class="p88 ft17">Signature</P>
<P class="p89 ft21">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
</DIV>
<DIV id="id_2">
<P class="p29 ft22">5</P>
</DIV>
</DIV>
</DIV>
</BODY>
</HTML>
