<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<title>Déclaration du Chiffre d\'Affaires</title>
<link rel="stylesheet" href="declaration/dicp.css">
<link rel="stylesheet" href="declaration/print.css">
<link rel="stylesheet" href="declaration/bootstrap.css">
<?php
$lieu = '';
$date = '';

$f = array();

$f[12] = '';
$f[5] = '';
$f[8] = '';
$f[7] = '';
$f[20] = '';
$f[6] = '';
$f[3] = '';
$f[9] = '';
$f[23] = '';
$f[24] = '';
$f[90] = '';
$f[10] = '';
$f[1] = '';
$f[2] = '';
$f[94] = '';
$f[11] = '';

$f['A'] = '';
$f['B'] = '';

$f[13] = '';
$f[14] = '';
$f[25] = '';
$f[15] = '';
$f[95] = '';
$f[22] = '';
$f[96] = '';
$f[97] = '';
$f[98] = '';
$f[19] = '';
$f[18] = '';
$f[99] = '';
$f[92] = '';
$f[91] = '';
$f[93] = '';
$f[17] = '';

$f['C'] = '';
$f['D'] = '';

$f['DX'] = '';
$f['DY'] = '';

$f['A+C'] = '';
$f['B+D'] = '';

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
  if (isset($_POST['choosedates']) && $_POST['choosedates'] == 1)
  {
    $datename = 'startdate';
    require('inc/datepickerresult.php');
    $datename = 'stopdate';
    require('inc/datepickerresult.php');
  }

  $query = 'select turnoverindexid from turnoverindex order by turnoverindexid';
  $query_prm = array();
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['turnoverindexid'];
    $f[$index] = 0;
  }
  $query = 'select value,debit,acnumber,turnoverindexid from adjustment,accountingnumber,adjustmentgroup
  where adjustment.accountingnumberid=accountingnumber.accountingnumberid and adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and adjustmentgroup.deleted=0 and adjustmentdate>=? and adjustmentdate<=? and turnoverindexid>0';
  $query_prm = array($startdate, $stopdate);
  require('inc/doquery.php');
  for ($i = 0; $i < $num_results; $i++)
  {
    $index = $query_result[$i]['turnoverindexid'];
    if ($query_result[$i]['debit'] == 1)
    {
      $f[$index] = d_subtract($f[$index], $query_result[$i]['value']);
    }
    else
    {
      $f[$index] = d_add($f[$index], $query_result[$i]['value']);
    }
  }

# TEST VALUES
/*$f[12] = 100000;
$f[5] = 2000000;
$f[8] =300000;
$f[7] =400000;
$f[20] =50000;
$f[6] =600000;
$f[3] =700000;
$f[9] =8888888;
$f[23] =9000000;
$f[24] =1111111;
$f[90] =22222222;
$f[10] =33333333;
$f[1] =44444444;
$f[2] =5555555;
$f[94] =66666666;
$f[11] =77777777;
$f[13] =88888888;
$f[14] =99999999;
$f[25] =10101010;
$f[15] =11111111;
$f[95] =121212121;
$f[22] =1313131313;
$f[96] =141411414;
$f[97] =1515151515;
$f[98] =161616161616;
$f[19] =1717171717;
$f[18] =181818181818;
$f[99] =19191919;
$f[92] =20202022;
$f[91] =21212121;
$f[93] =22222222;
$f[17] =232323232;*/

  # TODO use d_add etc
  $f['A'] = $f[12] + $f[5] + $f[8] + $f[7] + $f[20] + $f[6] + $f[3] + $f[9] + $f[23] + $f[24] + $f[90] + $f[10] + $f[1] + $f[2] + $f[94] + $f[11];
  $kladd = $f[90] + $f[10];
  if ($kladd > 20000000)
  {
    $f[10] = $kladd;
    $f[90] = '';
  }
  else
  {
    $f[90] = $kladd;
    $f[10] = '';
  }
  $f['C'] = $f[13] + $f[14] + $f[25] + $f[15] + $f[95] + $f[22] + $f[96] + $f[97] + $f[98] + $f[19] + $f[18] + $f[99] + $f[92] + $f[91] + $f[93] + $f[17];
  $f['A+C'] = $f['A'] + $f['C'];

}
else
{
  $duedate = '';
  $period = '<br>';
}?>

<section id="share">
  <a href="javascript:window.print()" class="btn btn-success"><?php echo d_trad('print');?></a>
</section>
<div id="maindicp">
<?php
if ($page == 1)
{?>
	<DIV id="page_2">
	<DIV id="dimg1">
	<IMG src="declaration/img/dicp2.jpg" id="img1">
	</DIV>

	<DIV id="id_1">
	<P class="p30 ft6">N° TAHITI : <?php echo $numero_tahiti;?></P>
	<P class="p31 ft1">DECLARATION DU CHIFFRE D’AFFAIRES OU DES RECETTES BRUTES HORS TVA REALISES AU COURS DE L’ANNEE / DE L’EXERCICE</P>
	<P class="p32 ft3">(Voir notice d’information "Comment remplir votre déclaration")</P>
	<P class="p33 ft6">Partie à renseigner par <SPAN class="ft23">tous </SPAN>les assujettis</P>
	<P class="p34 ft17">I – <SPAN class="ft18">VENTES : </SPAN>Les assujettis qui réalisent des ventes doivent déclarer le montant hors taxe de leur chiffre d’affaires dans la ou les lignes correspondant à leur(s) activité(s).</P>
	<TABLE cellpadding=0 cellspacing=0 class="t2">
	<TR>
		<TD class="tr8 td10"><P class="p35 ft24">12</P></TD>
		<TD class="tr8 td11"><P class="p15ter ft15ter"><?php echo myfix($f[12]); ?></P></TD>
		<TD class="tr8 td12"><P class="p16 ft20">Ventes de coprah</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">05</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[5]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes de lait frais</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">08</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[8]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes en gros de lait frais d’origine locale</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">07</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[7]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes de farine, riz, sucre cristallisé et en poudre</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">20</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[20]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes par des revendeurs de baguettes au prix de détail fixé par arrêté en conseil des ministres</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">06</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[6]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes de tabacs</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">03</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[3]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes d’hydrocarbures au détail</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">09</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[9]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes de <NOBR>timbres-poste</NOBR> et fiscaux</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">23</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[23]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes à l’aventure des armateurs de goélettes</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">24</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[24]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes à l’aventure des armateurs</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">90</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[90]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes au détail inférieures ou égales à 20 millions F CFP</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">10</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[10]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes au détail supérieures à 20 millions F CFP</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">01</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[1]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes des importateurs grossistes dont la marge commerciale est inférieure ou égale à 10%</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">02</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[2]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Ventes en gros</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">94</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[94]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft20">Apport à une société dans les conditions visées à l’article <NOBR>LP.182-2</NOBR> alinéa 3 du code des impôts (voir notice)</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td15"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">11</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[11]); ?></P></TD>
		<TD class="tr0 td18"><P class="p16 ft25"><SPAN class="ft20">Autres natures de ventes </SPAN>(cession de fonds de commerce, de clientèle…) à préciser :</P></TD>
	</TR>
	<TR>
		<TD class="tr9 td13"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td14"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td15"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	</TABLE>
	<TABLE cellpadding=0 cellspacing=0 class="t3">
	<TR>
		<TD class="tr8 td19"><P class="p36 ft24">A</P></TD>
		<TD class="tr8 td11"><P class="p15ter ft15ter"><?php print myfix($f['A']); ?></P></TD>
		<TD class="tr8 td20"><P class="p16 ft17">TOTAL CHIFFRE D’AFFAIRES AFFERENT AUX VENTES</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td21"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td22"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td23"><P class="p36 ft24">B</P></TD>
		<TD class="tr0 td17"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr0 td24"><P class="p16 ft17">TOTAL CHARGES AFFERENTES AUX VENTES <SPAN class="ft8">(pour information)</SPAN></P></TD>
	</TR>
	<TR>
		<TD class="tr5 td21"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td22"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	</TABLE>
	<P class="p37 ft21">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
	</DIV>
	<DIV id="id_2">
	<P class="p29 ft22">2</P>
	</DIV>
	</DIV>
<?php }
else
{?>
	<DIV id="page_3">
	<DIV id="dimg1">
	<IMG src="declaration/img/dicp3.jpg" id="img1">
	</DIV>


	<DIV class="dclr"></DIV>
	<P class="p38 ft17">II – PRESTATIONS DE SERVICE ET ASSIMILEES : Les assujettis qui réalisent des prestations de services doivent déclarer le montant de leur(s) chiffre(s) d’affaires dans la ou les lignes correspondant à leur(s) activité(s).</P>
	<TABLE cellpadding=0 cellspacing=0 class="t4">
	<TR>
		<TD class="tr8 td10"><P class="p35 ft24">13</P></TD>
		<TD class="tr8 td11"><P class="p15ter ft15ter"><?php print myfix($f[13]); ?></P></TD>
		<TD colspan=2 class="tr8 td25"><P class="p16 ft20">Prestations de service des entreprises d’acconage de coprah</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td26"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td27"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">14</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[14]); ?></P></TD>
		<TD class="tr0 td28"><P class="p16 ft20">Prestations de service des armateurs de goélette</P></TD>
		<TD class="tr0 td29"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td26"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td27"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">25</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[25]); ?></P></TD>
		<TD class="tr0 td28"><P class="p16 ft20">Prestations de service des armateurs</P></TD>
		<TD class="tr0 td29"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">15</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[15]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Prestations de service des entreprises de travaux publics et de constructions <SPAN class="ft27">(hors travaux de terrassement privés)</SPAN></P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">95</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[95]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Ventes des boulangeries de baguettes au prix de détail fixé par arrêté en conseil des ministres</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">22</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[22]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Ventes des boulangeries de baguettes au prix de gros fixé par arrêté en conseil des ministres</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">96</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[96]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Ventes des boulangeries (hors baguettes à prix fixé par arrêté en conseil des ministres)</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">97</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[97]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Ventes de denrées alimentaires à emporter ou à consommer sur place</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">98</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[98]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Prestations de services consistant à fournir le logement (hôtellerie, pension de familles, …)</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td26"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td27"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">19</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[19]); ?></P></TD>
		<TD class="tr0 td28"><P class="p16 ft20">Prestations de service : locations en meublé</P></TD>
		<TD class="tr0 td29"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td26"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td27"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">18</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[18]); ?></P></TD>
		<TD class="tr0 td28"><P class="p16 ft20">Prestations de service : locations non meublées</P></TD>
		<TD class="tr0 td29"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">99</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[99]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Prestations de service : locations de terrains nus non aménagés</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">92</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[92]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Prestations de service avec réduction d’impôts visées à l’article <NOBR>LP.188-4</NOBR> du code des impôts</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td30"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">91</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[91]); ?></P></TD>
		<TD colspan=2 class="tr0 td31"><P class="p16 ft20">Prestations de service sans réduction d’impôt (concerne les professions non visées à la ligne 92)</P></TD>
	</TR>
	<TR>
		<TD class="tr9 td13"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td14"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td26"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td27"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr1 td16"><P class="p35 ft24">93</P></TD>
		<TD class="tr1 td17"><P class="p15ter ft15ter"><?php print myfix($f[93]); ?></P></TD>
		<TD class="tr1 td28"><P class="p16 ft20">Professions libérales : avocats, notaires, huissiers, etc</P></TD>
		<TD class="tr1 td29"><P class="p15 ft20">(voir notice d’information)</P></TD>
	</TR>
	<TR>
		<TD class="tr9 td13"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td14"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td26"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td27"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td16"><P class="p35 ft24">17</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f[17]); ?></P></TD>
		<TD class="tr0 td28"><P class="p16 ft20">Autres natures de prestations de services (à préciser) :</P></TD>
		<TD class="tr0 td29"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td13"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td26"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td27"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	</TABLE>
	<TABLE cellpadding=0 cellspacing=0 class="t5">
	<TR>
		<TD class="tr8 td19"><P class="p39 ft24">C</P></TD>
		<TD class="tr8 td11"><P class="p15ter ft15ter"><?php print myfix($f['C']); ?></P></TD>
		<TD colspan=6 class="tr8 td32"><P class="p16 ft17">TOTAL CHIFFRE D’AFFAIRES AFFERENT AUX PRESTATIONS DE SERVICE</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td21"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td33"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=3 class="tr5 td34"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td35"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td36"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td23"><P class="p39 ft24">D</P></TD>
		<TD class="tr0 td17"><P class="p15 ft11">&nbsp;</P></TD>
		<TD colspan=6 class="tr0 td37"><P class="p16 ft17">TOTAL CHARGES AFFERENTES AUX PRESTATIONS DE SERVICE</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td21"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td33"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td38"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td39"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td40"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td35"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td36"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr8 td41"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr8 td17"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr8 td42"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr8 td43"><P class="p40 ft18">17</P></TD>
		<TD class="tr8 td44"><P class="p40 ft18">/ 18</P></TD>
		<TD class="tr8 td45"><P class="p16 ft18">/ 91 / 93 /</P></TD>
		<TD rowspan=2 class="tr10 td46"><P class="p17 ft24">DX</P></TD>
		<TD class="tr8 td47"><P class="p15ter ft15ter"><?php print myfix($f['DX']); ?></P></TD>
	</TR>
	<TR>
		<TD class="tr9 td41"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td17"><P class="p15 ft26">&nbsp;</P></TD>
		<TD rowspan=3 class="tr11 td42"><P class="p16 ft17">Dont charges afférentes aux lignes</P></TD>
		<TD rowspan=2 class="tr2 td43"><P class="p40 ft28">96</P></TD>
		<TD rowspan=2 class="tr2 td44"><P class="p40 ft28">/ 98</P></TD>
		<TD rowspan=2 class="tr2 td45"><P class="p16 ft28">/ 99</P></TD>
		<TD class="tr9 td47"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr6 td41"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td17"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td46"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td47"><P class="p15 ft16">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr6 td41"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td17"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td43"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td44"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td45"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td46"><P class="p15 ft16">&nbsp;</P></TD>
		<TD class="tr6 td47"><P class="p15 ft16">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr12 td41"><P class="p15 ft29">&nbsp;</P></TD>
		<TD class="tr12 td17"><P class="p15 ft29">&nbsp;</P></TD>
		<TD class="tr12 td42"><P class="p15 ft29">&nbsp;</P></TD>
		<TD class="tr13 td38"><P class="p15 ft30">&nbsp;</P></TD>
		<TD class="tr13 td39"><P class="p15 ft30">&nbsp;</P></TD>
		<TD class="tr13 td48"><P class="p15 ft30">&nbsp;</P></TD>
		<TD class="tr13 td49"><P class="p15 ft30">&nbsp;</P></TD>
		<TD class="tr13 td36"><P class="p15 ft30">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td41"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr0 td17"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr0 td42"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr0 td43"><P class="p40 ft18">13</P></TD>
		<TD class="tr0 td44"><P class="p40 ft18">/ 14</P></TD>
		<TD class="tr0 td45"><P class="p16 ft18">/ 15 et 95</P></TD>
		<TD class="tr0 td46"><P class="p17 ft24">DY</P></TD>
		<TD class="tr0 td47"><P class="p15ter ft15ter"><?php print myfix($f['DY']); ?></P></TD>
	</TR>
	<TR>
		<TD class="tr9 td50"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td14"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td51"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td38"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td39"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td48"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td49"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td36"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	</TABLE>
	<P class="p41 ft17">Rappel : Une réduction d’impôt de 50 % est accordée aux prestataires ne bénéficiant d’aucun coefficient modérateur et à ceux bénéficiant d’un coefficient modérateur astreints au dépôt d’un bilan et d’un compte de résultat (chiffre d’affaires supérieur à 6 millions ou 15 millions, selon les cas) qui déclarent avoir supporté des charges au moins égales à la moitié des recettes, à condition de joindre à la présente déclaration, un relevé détaillé des charges selon le modèle <NOBR>ci-après</NOBR> (cf. page 4).</P>
	<TABLE cellpadding=0 cellspacing=0 class="t6">
	<TR>
		<TD class="tr8 td19"><P class="p42 ft24">A+C</P></TD>
		<TD class="tr8 td11"><P class="p15ter ft15ter"><?php print myfix($f['A+C']); ?></P></TD>
		<TD colspan=3 class="tr8 td32"><P class="p16 ft17">TOTAL CHIFFRE D’AFFAIRES (VENTES + PRESTATIONS DE SERVICE )</P></TD>
	</TR>
	<TR>
		<TD class="tr5 td21"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td14"><P class="p15 ft15">&nbsp;</P></TD>
		<TD colspan=2 class="tr5 td52"><P class="p15 ft15">&nbsp;</P></TD>
		<TD class="tr5 td53"><P class="p15 ft15">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr0 td23"><P class="p42 ft24">B+D</P></TD>
		<TD class="tr0 td17"><P class="p15ter ft15ter"><?php print myfix($f['B+D']); ?></P></TD>
		<TD colspan=2 class="tr0 td54"><P class="p16 ft17">TOTAL CHARGES (VENTES + PRESTATIONS DE SERVICE)</P></TD>
		<TD class="tr0 td55"><P class="p15 ft11">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr9 td21"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td14"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td56"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td57"><P class="p15 ft26">&nbsp;</P></TD>
		<TD class="tr9 td53"><P class="p15 ft26">&nbsp;</P></TD>
	</TR>
	<TR>
		<TD class="tr14 td58"><P class="p15 ft11">&nbsp;</P></TD>
		<TD class="tr14 td59"><P class="p43 ft17">A</P></TD>
		<TD class="tr14 td60"><P class="p44 ft17">, le</P></TD>
		<TD class="tr14 td61"><P class="p45 ft17">201</P></TD>
		<TD class="tr14 td62"><P class="p15 ft17">Signature</P></TD>
	</TR>
	</TABLE>
	<P class="p46 ft21">« Les dispositions des articles 39 et 40 de la loi n° <NOBR>78-17</NOBR> du 6 janvier 1978 relative à l’informatique, aux fichiers et aux libertés, modifiée par la loi n° <NOBR>2004-801</NOBR> du 6 août 2004, garantissent les droits des personnes physiques à l’égard des traitements des données à caractère personnel. »</P>
	<P class="p47 ft22">3</P>
	</DIV>
<?php }?>
</DIV>
</BODY>
</HTML>