<?php

if (isset($_POST['runcheck']))
{
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  
  if ($_POST['runcheck'] == 1)
  {
    $query = 'select sum(invoiceprice) as invoicepricesum from invoicehistory where confirmed=1 and accountingdate>=? and accountingdate<=?';
    $query_prm = array($startdate,$stopdate);
    require ('inc/doquery.php');
    $invoicepricesum = $query_result[0]['invoicepricesum']+0;
    $query = 'select sum(lineprice+linevat) as linepricesum from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and confirmed=1 and accountingdate>=? and accountingdate<=?';
    $query_prm = array($startdate,$stopdate);
    require ('inc/doquery.php');
    $linepricesum = $query_result[0]['linepricesum']+0;
    if ($invoicepricesum != $linepricesum)
    {
      echo '<span class=alert>Problème détecté (invoicepricesum='.$invoicepricesum.' / '.$linepricesum.').</span><br>';
    }
    echo 'Vérification global terminé.<br><br>';
  }
  
  if ($_POST['runcheck'] == 2)
  {
    $query = 'select invoiceid,invoiceprice from invoicehistory where confirmed=1 and accountingdate>=? and accountingdate<=? order by invoiceid';
    $query_prm = array($startdate,$stopdate);
    require ('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    for ($i=0;$i<$num_results_main;$i++)
    {
      $query = 'select sum(lineprice+linevat) as sumlines from invoiceitemhistory where invoiceid=?';
      $query_prm = array($main_result[$i]['invoiceid']);
      require ('inc/doquery.php');
      if ($main_result[$i]['invoiceprice'] != ($query_result[0]['sumlines']+0))
      {
        echo 'Problem with invoiceid: ' . $main_result[$i]['invoiceid'] . ' (invoiceprice='.$main_result[$i]['invoiceprice'].'&nbsp;sumoflines='.($query_result[0]['sumlines']+0).')<br>';
      }
    }
    echo 'Vérification détaillé terminé.<br><br>';
  }
}

echo '<h2>Vérifier factures:</h2><h2 class=alert>Operation très lourde</h2><form method="post" action="system.php"><table>';
echo '<tr><td>De:</td><td>';
$datename = 'startdate';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>A:</td><td>';
$datename = 'stopdate';
require('inc/datepicker.php');
echo '</td></tr>';
echo '<tr><td>Type:</td><td><select name="runcheck"><option value=1>Global</option><option value=2>Détaillé</option></select></td></tr>';
echo '<tr><td colspan="2" align="center"><input type="submit" value="Valider"></td></tr>';
echo '</table><input type=hidden name="systemmenu" value="'.$systemmenu.'"></form>';

?>