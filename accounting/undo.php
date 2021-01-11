<?php

$showmenu = 1;

$PA['unreconciliate'] = 'uint';
$PA['unmatch'] = 'uint';
$PA['unmatch_adjustmentgroupid'] = 'uint';
$PA['adjustmentgroupid'] = 'uint';
$PA['matchingid'] = 'uint';
$PA['adjustmentid'] = 'uint';
require('inc/readpost.php');

if ($unmatch_adjustmentgroupid > 0)
{
  $query = 'select matchingid from adjustment where matchingid>0 and adjustmentgroupid=?';
  $query_prm = array($unmatch_adjustmentgroupid);
  require('inc/doquery.php');
  if ($num_results) { $matchingid = $query_result[0]['matchingid']; }
}

if ($matchingid > 0)
{
  if ($unmatch == 1)
  {
    $query = 'update invoicehistory set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update payment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update adjustment set matchingid=0 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $query = 'update matching set deleted=1 where matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    echo '<p class=alert>Lettrage '.$matchingid.' délettré.</p>';
  }
  else
  {
    $query = 'select date,name from matching,usertable where matching.userid=usertable.userid and matchingid=?';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $showmenu = 0; # TODO show details, accounts debit/credit value
      echo '<h2>Délettrer</h2><form method="post" action="accounting.php">
      <p>Lettrage '.$matchingid.' ('.datefix2($query_result[0]['date']).')</p>
      <br><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type=hidden name="matchingid" value="' . $matchingid . '">
      <input type=hidden name="unmatch" value=1><input type=hidden name="accountingmenu_sa" value="control">
      <input type="submit" value="Valider">';
    }
  }
}
elseif ($adjustmentgroupid > 0)
{
  if ($unreconciliate == 1)
  {
    # find all reconciliationids in adjustmentgroup
    $query = 'select reconciliationid from adjustment where adjustmentgroupid=?';
    $query_prm = array($adjustmentgroupid);
    require('inc/doquery.php');
    $main_result = $query_result; $num_results_main = $num_results;
    # update each reconciliationid=0 and delete reconciliation
    for ($i = 0; $i < $num_results_main; $i++)
    {
      $query = 'update adjustment set reconciliationid=0 where reconciliationid=?';
      $query_prm = array($main_result[$i]['reconciliationid']);
      require('inc/doquery.php');
      $query = 'update reconciliation set deleted=1 where reconciliationid=?';
      $query_prm = array($main_result[$i]['reconciliationid']);
      require('inc/doquery.php');
    }
    echo '<p class=alert>Écriture '.$adjustmentgroupid.' dé-rapproché.</p>';
  }
  else
  {
    $query = 'select adjustmentdate,name from adjustmentgroup,usertable where adjustmentgroup.userid=usertable.userid and adjustmentgroupid=?';
    $query_prm = array($adjustmentgroupid);
    require('inc/doquery.php');
    if ($num_results)
    {
      # TODO here check if this adjustmentgroup has any reconciliationids
      
      $showmenu = 0; # TODO show details, accounts debit/credit value
      echo '<h2>Dé-rapprocher</h2><form method="post" action="accounting.php">
      <p>Écriture '.$adjustmentgroupid.' ('.datefix2($query_result[0]['adjustmentdate']).')</p>
      <br><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type=hidden name="adjustmentgroupid" value="' . $adjustmentgroupid . '">
      <input type=hidden name="unreconciliate" value=1><input type=hidden name="accountingmenu_sa" value="control">
      <input type="submit" value="Valider"></form>';
    }
  }
}
elseif ($adjustmentid > 0)
{
  if ($unreconciliate == 1)
  {
    $query = 'update adjustment set reconciliationid=0 where adjustmentid=?';
    $query_prm = array($adjustmentid);
    require('inc/doquery.php');
    echo '<p class=alert>Ligne '.$adjustmentid.' dé-rapproché.</p>';
  }
  else
  {
    $query = 'select value from adjustment where adjustmentid=?';
    $query_prm = array($adjustmentid);
    require('inc/doquery.php');
    if ($num_results)
    {
      $showmenu = 0;
      echo '<h2>Dé-rapprocher</h2><form method="post" action="accounting.php">
      <p>Ligne '.$adjustmentid.' ( Valeur: '.$query_result[0]['value'].')</p>
      <br><input type=hidden name="accountingmenu" value="' . $accountingmenu . '"><input type=hidden name="adjustmentid" value="' . $adjustmentid . '">
      <input type=hidden name="unreconciliate" value=1><input type=hidden name="accountingmenu_sa" value="control">
      <input type="submit" value="Valider"></form>';
    }
  }
}

if ($showmenu == 1)
{
  ?>
  <h2>Délettrer</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro écriture:<td><input autofocus type="text" STYLE="text-align:right" name="unmatch_adjustmentgroupid" size=10>
  <tr><td colspan=2>ou
  <tr><td>Numéro lettrage:<td><input type="text" STYLE="text-align:right" name="matchingid" size=10>
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountingmenu_sa" value="control">
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <br>
  <?php
  ?>
  <br>
  <h2>Dé-rapprocher</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro écriture:<td><input type="text" STYLE="text-align:right" name="adjustmentgroupid" size=10>
  <tr><td colspan="2" align="center">
  <input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="accountingmenu_sa" value="control">
  <input type="submit" value="Valider"></td></tr>
  </table></form>
  <?php
}
?>