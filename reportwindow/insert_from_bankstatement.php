<?php

$PA['bankstatementid'] = 'uint';
$PA['anid'] = 'uint';
require('inc/readpost.php');

$query = 'select statementdate,amount,statementtext
from bankstatement where adjustmentgroupid=0 and bankstatementid=?';
$query_prm = array($bankstatementid);
require('inc/doquery.php');
if ($num_results != 1)
{
  echo '<p class="alert">Impossible d\'insérer cette ligne.</p>';
}
else
{
  $amount = d_abs($query_result[0]['amount']);
  $statementtext = $query_result[0]['statementtext'];
  $adjustmentdate = $query_result[0]['statementdate'];
  $query = 'select accounting_simplifiedid,accounting_simplifiedname from accounting_simplified
  where deleted=0 and for_bankstatement=1';
  $query_prm = array($anid);
  require('inc/doquery.php');
  if ($num_results == 0)
  {
    header('refresh:0; url="accounting.php?accountingmenu=entry&val='.$amount
    .'&anid='.$anid
    .'&adjustmentdate='.$adjustmentdate
    .'&com='.$statementtext
    .'"');
    exit;
  }
  elseif ($num_results == 1)
  {
    header('refresh:0; url="accounting.php?accountingmenu=simplified&asid='.$query_result[0]['accounting_simplifiedid']
    .'&val='.$amount
    .'&adjustmentdate='.$adjustmentdate
    .'&com='.$statementtext
    .'"');
    exit;
  }
  else
  {
    echo '<br><h2>&nbsp;Veuillez choisir le menu simplifié à utiliser :<br>';
    for ($i=0; $i < $num_results; $i++)
    {
      echo '<br>&nbsp;<a href="accounting.php?accountingmenu=simplified&asid='.$query_result[$i]['accounting_simplifiedid']
      .'&val='.$amount
      .'&adjustmentdate='.$adjustmentdate
      .'&com='.$statementtext
      .'">'.d_output($query_result[$i]['accounting_simplifiedname']).'</a>';
    }
    echo '</h2>';
  }
}
?>