<?php

/*
Les champs sont :

n° Dossier // n° Déclaration // Date // Valeur FOB + CFR
Date = date de la declaration de douanes.

Valeur FOB + CFR = valeur dans TEM (>> Achat >> Calcul de prix >> Valeur FOB + CFR XPF)

*** Attention, les dossiers en CAF ne sont pas prises en compte car l'assurance est incluse.


1. Merci de lister FOB, CFR, EXW, FCA.

Par contre j'ai besoin que tu ajoutes dans Achat >> Commande >> Incoterm : FOB DHL, CFR DHL, EXW DHL, FCA DHL.

En resume, NE PAS LISTER : CAF, FOB DHL, CFR DHL, EXW DHL, FCA DHL.
2. Merci de lister uniquement le montant et le total.

2019 01 04:
Juste enlever les dossiers CFR avec assurance =0 dans calcul de prix.

2019 01 10 the saga continues...
 or incotermname="CIF"

*/

$PA['year'] = 'int';
require('inc/readpost.php');

$totalvalue = 0;

$title = 'Déclaration importations en valeur ' . $year;
showtitle($title);
echo '<h2>' . $title . '</h2>';

$query = 'select sofixdate,shipmentid,sofixvessel,customscode,incotermname,exchangerate,insurance
from shipment,incoterm
where shipment.incotermid=incoterm.incotermid
and noimportvalue=0 and year(sofixdate)=?
and (incotermname="FOB" or (incotermname="CFR" and insurance>0) or incotermname="EXW" or incotermname="FCA")
order by shipmentid';
$query_prm = array($year);
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<table class=report><thead><th>N° Dossier</th><th>N° Déclaration</th><th>Date</th><th>Incoterm</th><th>Valeur</th></thead>';
for ($i = 0; $i < $num_results_main; $i++)
{
  $query = 'select sum(purchaseprice) as tpp from purchase where shipmentid=?';
  $query_prm = array($main_result[$i]['shipmentid']);
  require('inc/doquery.php');
  $value = $query_result[0]['tpp'] * $main_result[$i]['exchangerate'];
  if ($main_result[$i]['incotermname'] == 'CIF')
  {
    $main_result[$i]['incotermname'] = 'CAF';
  }
  echo '<tr><td align=center>' . $main_result[$i]['shipmentid'] . '</td><td align=center>' . substr($main_result[$i]['customscode'],10,6) . '</td>
  <td align=center>' . datefix2($main_result[$i]['sofixdate']) . '</td><td align=center>' . d_output($main_result[$i]['incotermname']) . '</td>
  <td align=right>' . round($value); # Est ce que tu peux modifier le format des valeurs, SANS ESPACE entre les milliers, example : 8 842 482 >> 8842482.
  $totalvalue += $value;
}
echo '';
echo '<tr><td><b>Total<td colspan=4 align=right><b>',round($totalvalue);
echo '</table>';

?>