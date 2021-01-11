<?php

if (!$_SESSION['ds_acc_canmodpayment']) { exit; }

require('preload/user.php'); # needed?
require('preload/employee.php');
require('preload/paymentcategory.php');

$PA['paymentid'] = 'uint';
$PA['reason_payment_modifyid'] = 'uint';
$PA['payment_cardtypeid'] = 'uint';
require('inc/readpost.php');

$showmenu = 1;

if ($paymentid > 0 && $reason_payment_modifyid > 0)
{
  $value = $_POST['value'];
  $paymenttime = $_POST['paymenttime'];
  $bankid = $_POST['bankid']+0;
  $forinvoiceid = $_POST['forinvoiceid']+0;
  $payer = $_POST['payer'];
  $paymentdate = d_builddate($_POST['day'],$_POST['month'],$_POST['year']);
  $employeeid = $_POST['employeeid']+0;
  $userid = $_POST['userid']+0;
  $paymenttypeid = $_POST['paymenttypeid']+0;
  $reimbursement = $_POST['reimburse']+0;
  
  $PA['paymentcategoryid'] = 'uint';
  $PA['client'] = 'client';
  require('inc/readpost.php');
  
  if ($clientid > 0)
  {
    $query = 'select adjustmentgroupid from adjustmentgroup where integrated=2 and reference=?';
    $query_prm = array($paymentid);
    require('inc/doquery.php');
    if ($num_results) { $adjustmentgroupid = $query_result[0]['adjustmentgroupid']; }
    else { $adjustmentgroupid = 0; }
    if ($adjustmentgroupid > 0)
    {
      # find accountingnumberid
      $query = 'select accountingnumberid from paymenttype where paymenttypeid=?';
      $query_prm = array($paymenttypeid);
      require('inc/doquery.php');
      $id = $query_result[0]['accountingnumberid'];
      if ($reimbursement == 1)
      {
        $comment = 'Remboursement';
        $debit = 0;
      }
      else
      {
        $comment = 'Paiement';
        $debit = 1;
      }
      $query = 'update adjustmentgroup set adjustmentdate=?,originaladjustmentdate=curdate(),adjustmenttime=curtime(),userid=?,adjustmentcomment=? where adjustmentgroupid=?';
      $query_prm = array($paymentdate,$_SESSION['ds_userid'],$comment,$adjustmentgroupid);
      require('inc/doquery.php');
      $query = 'delete from adjustment where adjustmentgroupid=? limit 2';
      $query_prm = array($adjustmentgroupid);
      require('inc/doquery.php');
      $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
      $query_prm = array($debit, $adjustmentgroupid, $value, $id);
      require('inc/doquery.php');
      if ($debit == 1) { $debit = 0; }
      else { $debit = 1; }
      $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid,nomatch) values (?,?,?,?,?,0,1)';
      $query_prm = array($debit, $adjustmentgroupid, $value, $clientid, 1); # hardcode accountingnumberid=1 for client sales
      require('inc/doquery.php');
      $query = 'update payment set toacc=1 where paymentid=?';
      $query_prm = array($paymentid);
      require('inc/doquery.php');
    }

    $query = 'update payment set payment_cardtypeid=?,value="' . $value . '",reimbursement="' . $reimbursement . '",paymenttime="' . $paymenttime . '",payer="' . $payer . '",forinvoiceid="' . $forinvoiceid . '",bankid="' . $bankid . '",paymenttypeid="' . $paymenttypeid . '",chequeno="' . $_POST['chequeno'] . '",paymentcomment="' . $_POST['paymentcomment'] . '",clientid="' . $clientid . '",userid="' . $userid . '",employeeid="' . $employeeid . '",paymentdate="' . $paymentdate . '",paymentcategoryid="' . $paymentcategoryid . '" where paymentid=?';
    $query_prm = array($payment_cardtypeid,$paymentid);
    require('inc/doquery.php');
    echo 'Paiement ' . $paymentid . ' modifié.';
  }
}
elseif ($paymentid > 0)
{
  $showmenu = 0;
  
  if ($_SESSION['ds_exportfields'])
  {
    $query = 'select exported from payment where paymentid=?';
    $query_prm = array($paymentid);
    require('inc/doquery.php');
    if ($query_result[0]['exported'] == 1) { echo '<p class=alert>Paiement '.$paymentid.' est exporté.</p>'; exit; }
  }

  $query = 'select value,payer,forinvoiceid,bankid,reimbursement,paymenttypeid,paymentcategoryid,payment.userid
  ,payment.employeeid,paymentdate,paymenttime,payment.clientid,clientname,matchingid,paymentcomment,chequeno,payment_cardtypeid
  from payment,client where payment.clientid=client.clientid and matchingid=0 and paymentid=?';
  $query_prm = array($paymentid);
  require('inc/doquery.php');
  if ($num_results < 1) { echo '<p>Paiement inexistant ou lettré.</p>'; exit; }
  $row = $query_result[0];
  $clientname = $row['clientid'] . ': ' . d_output(d_decode($row['clientname']));
  $client = $row['clientid'];
  $bankid = $row['bankid'];
  $employeeid = $row['employeeid'];
  $userid = $row['userid'];
  $payment_cardtypeid = $row['payment_cardtypeid'];
  ?><h2>Modifier paiement</h2>
  <form method="post" action="accounting.php"><table><?php
  
  $dp_itemname = 'reason_payment_modify'; $dp_noblank = 1; $dp_description = 'Raison (obligatoire)';
  require('inc/selectitem.php');
  
  echo '<tr><td>&nbsp;<tr><td>Paiement</td><td>' . $paymentid . '</td></tr>';
  echo '<tr><td>';
  if ($row['matchingid'] == 0)
  {
    #echo '&nbsp;';
    require('inc/selectclient.php');
  }
  else
  {
    echo 'Client</td><td>' . $clientname;
    echo '<input type=hidden name="client" value="' . $row['clientid'] . '">';
  }
  echo '</td></tr>';
  ?><tr><td>Date:</td><td><?php
  $day = mb_substr($row['paymentdate'],8,2);
  $month = mb_substr($row['paymentdate'],5,2);
  $year = mb_substr($row['paymentdate'],0,4);
  ?><select name="day"><?php
  for ($i=1; $i <= 31; $i++)
  { 
    if ($i == $day) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="month"><?php
  for ($i=1; $i <= 12; $i++)
  {
    if ($i == $month) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select><select name="year"><?php
  #for ($i=$_SESSION['ds_startyear']; $i <= $_SESSION['ds_endyear']; $i++)
  for ($i=($year-1); $i <= $year; $i++)
  {
    if ($i == $year) { echo '<option value="' . $i . '" SELECTED>' . $i . '</option>'; }
    else { echo '<option value="' . $i . '">' . $i . '</option>'; }
  }
  ?></select></td></tr>
  <tr><td>Heure:</td><td><input type=text name=paymenttime value="<?php echo $row['paymenttime']; ?>"></td></tr>
  <tr><td>Montant:</td><td><input type=text STYLE="text-align:right" name=value value="<?php echo d_input($row['value']+0); ?>"></td></tr>
  <?php
  $dp_itemname = 'employee'; $dp_issales = 1; $dp_selectedid = $employeeid; $dp_description = 'Employé(e)';
  require('inc/selectitem.php');
  
  $dp_itemname = 'user'; $dp_issales = 1; $dp_selectedid = $userid; $dp_description = 'Facturier';
  require('inc/selectitem.php');

  echo '<tr><td>Banque';
  $dp_itemname = 'bank'; $dp_selectedid = $bankid;
  require('inc/selectitem.php');

  echo '<tr><td>Réseaux';
  $dp_itemname = 'payment_cardtype'; $dp_selectedid = $payment_cardtypeid;
  require('inc/selectitem.php');

  # new paymentcategory
  if (isset($paymentcategoryA))
  {
    echo '<tr><td>Catégorie:</td><td><select name="paymentcategoryid"><option value="0"></option>';
    foreach ($paymentcategoryA as $paymentcategoryid => $paymentcategoryname)
    {
      echo '<option value="' . $paymentcategoryid . '"';
      if ($row['paymentcategoryid'] == $paymentcategoryid) { echo ' selected'; }
      echo '>' . $paymentcategoryname . '</option>';
    }
    echo '</td></tr>';
  }
  ?>
  <tr><td>No cheque:</td><td><input type=text name=chequeno value="<?php echo $row['chequeno']; ?>"></td></tr>
  <tr><td>Tireur:</td><td><input type=text name=payer value="<?php echo $row['payer']; ?>"></td></tr>
  <tr><td>Info:</td><td><input type=text name=paymentcomment value="<?php echo $row['paymentcomment']; ?>"></td></tr>
  <tr><td>Pour facture no:</td><td><input type=text name=forinvoiceid value="<?php echo $row['forinvoiceid']; ?>"></td></tr>
  <?php
  $paymenttypeid = $row['paymenttypeid'];
  $reimburse = $row['reimbursement'];
  echo '<tr><td><input type="radio" name="paymenttypeid" value="1"';
  if ($paymenttypeid == 1) { echo ' checked'; }
  echo '>Espèces</td><td></td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="2"';
  if ($paymenttypeid == 2) { echo ' checked'; }
  echo '>Chèque</td><td>';
  echo ' &nbsp; <input type="radio" name="reimburse" value="0"';
  if ($reimburse != 1) { echo ' checked'; }
  echo '>Encaissement</td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="3"';
  if ($paymenttypeid == 3) { echo ' checked'; }
  echo '>Virement</td><td>';
  echo ' &nbsp; <input type="radio" name="reimburse" value="1"';
  if ($reimburse == 1) { echo ' checked'; }
  echo '>Remboursement</td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="4"';
  if ($paymenttypeid == 4) { echo ' checked'; }
  echo '>Carte Bancaire</td><td></td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="5"';
  if ($paymenttypeid == 5) { echo ' checked'; }
  echo '>Prélèvement</td><td></td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="6"';
  if ($paymenttypeid == 6) { echo ' checked'; }
  echo '>Salaire</td><td></td></tr>';
  echo '<tr><td><input type="radio" name="paymenttypeid" value="7"';
  if ($paymenttypeid == 7) { echo ' checked'; }
  echo '>Perte</td><td></td></tr>';

  if ($_SESSION['ds_customname'] == 'AirFroid' || $_SESSION['ds_customname'] == 'airfroideq')
  {
    echo '<tr><td><input type="radio" name="paymenttypeid" value="8"';
    if ($paymenttypeid == 8) { echo ' checked'; }
    echo '>Compensation</td><td></td></tr>';
  }
  if ($_SESSION['ds_customname'] == 'ANIMALICE' || $_SESSION['ds_customname'] == 'Polymeubles') # TODO option
  {
    echo '<tr><td><input type="radio" name="paymenttypeid" value="9"';
    if ($paymenttypeid == 9) { echo ' checked'; }
    echo '>AMEX</td><td></td></tr>';
  }

  ?>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="2"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type=hidden name="paymentid" value="<?php echo $paymentid; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}
if ($showmenu)
{
  ?><h2>Modifier paiement</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro paiement: </td><td><input autofocus type="text" STYLE="text-align:right" name="paymentid" size=10></td>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}
?>