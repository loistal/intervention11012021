<?php

if ((!isset($currentstep) || $currentstep == 0) && isset($_GET['currentstep'])) { $currentstep = $_GET['currentstep']; }

switch($currentstep)
{

  #
  case 0:
  ?>
  <h2>Modifier date lettrage:</h2>
  <form method="post" action="clients.php">
  <table>
  <tr><td>Facture / Avoir:</td>
  <td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=5></td></tr>
  <tr><td colspan=2 align=center>ou
  <tr><td>Paiement / Remboursement:</td>
  <td><input type="text" STYLE="text-align:right" name="paymentid" size=5></td></tr>
  <tr><td colspan=2 align=center>ou
  <tr><td>Numéro lettrage:</td><td><input type="text" STYLE="text-align:right" name="matchingid" size=5></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1">
  <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  break;

  case 1:
  if (isset($_POST['matchingid']) && $_POST['matchingid'] > 0) { $row['matchingid'] = (int) $_POST['matchingid']; }
  elseif (isset($_GET['matchingid']) && $_GET['matchingid'] > 0) { $row['matchingid'] = (int) $_GET['matchingid']; }
  else
  {
    $query = 'select name,date,invoicehistory.matchingid from matching,usertable,invoicehistory,client where invoicehistory.matchingid=matching.matchingid and matching.userid=usertable.userid and invoicehistory.clientid=client.clientid and client.deleted=0 and invoicehistory.invoiceid=?';
    $query_prm = array($_POST['invoiceid']);
    require('inc/doquery.php');
    if ($num_results < 1)
    {
      $query = 'select name,date,payment.matchingid from matching,usertable,payment,client where payment.matchingid=matching.matchingid and matching.userid=usertable.userid and payment.clientid=client.clientid and client.deleted=0 and payment.paymentid=?';
      $query_prm = array($_POST['paymentid']);
      require('inc/doquery.php');
    }
    if ($num_results < 1)
    {
      echo '<p>Lettrage non trouvé.</p>';
      exit;
    }
    $row = $query_result[0];
  }
  if (!isset($row['name']))
  {
    $query = 'select date,name from matching,usertable where matching.userid=usertable.userid and matchingid=?';
    $query_prm = array($row['matchingid']);
    require ('inc/doquery.php');
    $row['name'] = $query_result[0]['name'];
    $row['date'] = $query_result[0]['date'];
  }
  $query = 'select date from matching where matchingid=?';
  $query_prm = array($row['matchingid']);
  require ('inc/doquery.php');
  $row['date'] = $query_result[0]['date'];
  ?><h2>Modifier date lettrage:</h2>
  <form method="post" action="clients.php">
  <table class=report>
  <tr><td>Numero lettrage:</td>
  <td><?php echo $row['matchingid']; ?>
  <tr><td>Par:</td>
  <td><?php echo $row['name']; ?>
  <tr><td>Date:</td>
  <td><?php
  $datename = 'matchingdate'; $selecteddate = $row['date'];
  require('inc/datepicker.php');
  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2">
  <input type=hidden name="matchingid" value="<?php echo $row['matchingid']; ?>">
  <input type=hidden name="clientsmenu" value="<?php echo $clientsmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
  
  $num_results_main = 1;
  $main_result[0]['matchingid'] = $row['matchingid'];
  echo '<br><br><table class=report>';
  for ($i=0;$i<$num_results_main;$i++)
  {
    echo '<thead><th colspan=4>' . $main_result[$i]['matchingid'] . ' - ' . datefix2($row['date']);
    echo ' (' . d_output($row['name']) . ')';
    echo '</th></thead><thead><th>Débit</th><th>Montant</th><th>Crédit</th><th>Montant</th></thead><tr><td valign=top>';
    
    $td1 = ''; $td2 = ''; $td3 = ''; $td4 = ''; $debit = 0; $credit = 0;
    $query = 'select invoiceid,invoiceprice from invoicehistory where isreturn=0 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Facture ' . $query_result[$y]['invoiceid'] . '<br>';
      $td2 .= 0+($query_result[$y]['invoiceprice']) . '<br>';
      $debit += $query_result[$y]['invoiceprice'];
    }
    $query = 'select paymentid,value from payment where reimbursement=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Remboursement ' . $query_result[$y]['paymentid'] . '<br>';
      $td2 .= 0+($query_result[$y]['value']) . '<br>';
      $debit += $query_result[$y]['value'];
    }
    $query = 'select adjustmentgroupid,value from adjustment where debit=1 and accountingnumberid=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td1 .= 'Ecriture ' . $query_result[$y]['adjustmentgroupid'] . '<br>';
      $td2 .= 0+($query_result[$y]['value']) . '<br>';
      $debit += $query_result[$y]['value'];
    }
    $query = 'select paymentid,value from payment where reimbursement=0 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Paiement ' . $query_result[$y]['paymentid'] . '<br>';
      $td4 .= 0+($query_result[$y]['value']) . '<br>';
      $credit += $query_result[$y]['value'];
    }
    $query = 'select invoiceid,invoiceprice from invoicehistory where isreturn=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Avoir ' . $query_result[$y]['invoiceid'] . '<br>';
      $td4 .= 0+($query_result[$y]['invoiceprice']) . '<br>';
      $credit += $query_result[$y]['invoiceprice'];
    }
    $query = 'select adjustmentgroupid,value from adjustment where debit=0 and accountingnumberid=1 and matchingid=?';
    $query_prm = array($main_result[$i]['matchingid']);
    require('inc/doquery.php');
    for ($y=0;$y<$num_results;$y++)
    {
      $td3 .= 'Ecriture ' . $query_result[$y]['adjustmentgroupid'] . '<br>';
      $td4 .= 0+($query_result[$y]['value']) . '<br>';
      $credit += $query_result[$y]['value'];
    }
    
    echo $td1 . '</td><td valign=top align=right>' . $td2 . '</td><td valign=top>' . $td3 . '</td><td valign=top align=right>' . $td4 . '</td></tr>';
    echo '<tr><td><td align=right><b>'.$debit.'<td><td align=right><b>'.$credit;
  }
  echo '</table>';
  
  break;

  case 2:
  $PA['matchingdate'] = 'date';
  require('inc/readpost.php');
  
  $query = 'update matching set date=? where matchingid=?';
  $query_prm = array($matchingdate,$_POST['matchingid']);
  require('inc/doquery.php');
  
  echo '<p>Date modifié pour lettrage : ' . $_POST['matchingid'] . '</p>';
  break;

}
?>