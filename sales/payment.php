<?php

# need refactor

# "Compensation" paymenttype taken off 2015 04 19 Compensation back for AF/AFEQ, need to find out what this is for accounting
# TODO hide VAT on payment?

require('preload/paymentcategory.php');
require('preload/bank.php');

$PA['paymentcomment'] = '';
$PA['chequeno'] = '';
$PA['payer'] = '';
$PA['paymentcategoryid'] = 'uint';
$PA['forinvoiceid'] = 'uint';
$PA['reimburse'] = 'uint';
$PA['value'] = 'currency';
$PA['vattotal'] = 'currency';
$PA['reimbursed'] = 'currency';
$PA['client'] = 'client';
$PA['directfrominvoice'] = 'uint';
$PA['payment_cardtypeid'] = 'uint';
require('inc/readpost.php');

$valueautofocus = 0; $extra_fieldstext = ''; $p_aid = 0;

if ($forinvoiceid > 0)
{
  $query = 'select invoicecomment,isreturn,confirmed,matchingid,invoiceid,invoiceprice,invoice.clientid,clientname 
  from invoice,client where invoice.clientid=client.clientid and cancelledid=0 and invoiceid=?
   UNION
  select invoicecomment,isreturn,confirmed,matchingid,invoiceid,invoiceprice,invoicehistory.clientid,clientname
  from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and invoiceid=?';
  $query_prm = array($forinvoiceid,$forinvoiceid);
  require ('inc/doquery.php');
  if ($num_results && $query_result[0]['invoiceid'] > 0)
  {
    $fid_read_isreturn = $query_result[0]['isreturn'];
    $fid_read_confirmed = $query_result[0]['confirmed'];
    $fid_read_invoiceid = $query_result[0]['invoiceid'];
    $fid_read_matchingid = $query_result[0]['matchingid'];
    $fid_read_value = $query_result[0]['invoiceprice'];
    $fid_read_clientid = $query_result[0]['clientid'];
    if ($client == "" && $value == 0)
    {
      $value = $query_result[0]['invoiceprice']+0;
      $client = $query_result[0]['clientid'];
      $clientname = $query_result[0]['clientname'];
    }
    if ($paymentcomment == '') { $paymentcomment = $query_result[0]['invoicecomment']; }
    $invoiceprice = $query_result[0]['invoiceprice'];
  }
  else { $forinvoiceid = 0; }
}

$ok = 1;
if ($clientid < 1 || $value <= 0) { $ok = 0; }
if (isset($_POST['paymenttypeid']))
{
  $paymenttypeid = $_POST['paymenttypeid']+0;
  if (($paymenttypeid == 2 || $paymenttypeid == 3) && $_POST['bankid'] < 1) { $ok = 0; $extra_fieldstext = 'Veuillez spécifier la banque'; }
  if ($paymenttypeid == 2 && $_SESSION['ds_musthavecheckinput'] == 1 && ($chequeno == '' || $payer == '')) { $ok = 0; $extra_fieldstext = 'Veuillez spécifier la banque,numéro cheque, tireur'; }
}
else { $paymenttypeid = $_SESSION['ds_defpaymenttypeid']+0; }
if ($paymenttypeid < 1) { $paymenttypeid = 1; }
if (!$ok && $extra_fieldstext != '') { echo '<p class="alert">'.$extra_fieldstext.'</p><br>'; }

if ($_SESSION['ds_canpaymentdate'] == 1)
{
  $datename = 'paymentdate';
  require('inc/datepickerresult.php');
  if ($_SESSION['ds_noretrodates'] && $paymentdate < $_SESSION['ds_curdate']) { $paymentdate = $_SESSION['ds_curdate']; }
}
else { $paymentdate = $_SESSION['ds_curdate']; }

if ($ok == 1)
{
  $firsttext = 'reçu du'; $secondtext = 'rendu au'; $firstclass = 'info'; $secondclass = 'alert';
  if ($reimburse == 1) { $firsttext = 'rendu au'; $secondtext = 'reçu du'; $firstclass = 'alert'; $secondclass = 'info'; }
  if (!isset($_POST['paymfield1'])) { $_POST['paymfield1'] = ''; }
  if (!isset($_POST['paymfield2'])) { $_POST['paymfield2'] = ''; }
  $save_paymentcomment = $paymentcomment;
  $query = 'insert into payment (paymfield1,paymfield2,vattotal,forinvoiceid,clientid,paymentdate,paymenttime,value,paymenttypeid,userid,chequeno,bankid,depositbankid,payer,paymentcomment,matchingid,reimbursement,employeeid,paymentcategoryid,payment_cardtypeid) values (?,?,?,?,?,?,CURTIME(),?,?,?,?,?,?,?,?,0,?,?,?,?)';
  $query_prm = array($_POST['paymfield1'],$_POST['paymfield2'],$vattotal,$forinvoiceid,$clientid,$paymentdate,$value,$paymenttypeid,$_SESSION['ds_userid'],$chequeno,$_POST['bankid'],0,$payer,$save_paymentcomment,$reimburse,$_POST['employeeid']+0,$paymentcategoryid,$payment_cardtypeid);
  require ('inc/doquery.php');
  $inserted_paymentid = $query_insert_id;
  echo '<p class="' . $firstclass . '">' . myfix($value) . ' XPF ' . $firsttext . ' client ' . $clientid . ': ' . d_output($clientname) . '</p> ';
  if ($_SESSION['ds_printcheck'] == 1 && $paymenttypeid == 2) { echo '<a href="printcheck.php?amount='.$value.'" target=_blank>Imprimer cheque</a>'; }
  if ($_SESSION['ds_customname'] == 'Vaimato' && $forinvoiceid > 0 && $reimburse == 0) # TODO option $_SESSION['ds_auto_reimburse']
  {
    if ($value > $fid_read_value) { $reimbursed = $value - $fid_read_value; }
  }
  if ($reimbursed > 0)
  {
    $doreimb = 1;
    if ($reimburse == 1) { $doreimb = 0; }
    $query = 'insert into payment (paymfield1,paymfield2,forinvoiceid,clientid,paymentdate,paymenttime,value,paymenttypeid,userid,chequeno,bankid,depositbankid,payer,paymentcomment,matchingid,reimbursement,employeeid,paymentcategoryid) values (?,?,?,?,?,CURTIME(),?,?,?,?,?,?,?,?,0,?,?,?)';
    $query_prm = array($_POST['paymfield1'],$_POST['paymfield2'],$forinvoiceid,$clientid,$paymentdate,$reimbursed,1,$_SESSION['ds_userid'],$chequeno,$_POST['bankid'],0,$payer,$save_paymentcomment,$doreimb,$_POST['employeeid']+0,$paymentcategoryid);
    require ('inc/doquery.php');
    $inserted_paymentid2 = $query_insert_id;
    echo '<p class="' . $secondclass . '">' . myfix($reimbursed) . ' XPF ' . $secondtext . ' client ' . $clientid . ': ' . d_output($clientname) . '</p> ';
  }
  echo '<br>';

  if ($_SESSION['ds_directtoacc'] == 1)
  {
    # NO decimals in accounting
    $value = myround($value);
    $reimbursed = myround($reimbursed);

    $min_adjustmentdate = '0000-00-00';
    $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results) { $min_adjustmentdate = $query_result[0]['adjustmentdate']; }
    
    if ($paymentdate >= $min_adjustmentdate)
    {
      # find accountingnumberid
      $query = 'select accountingnumberid from paymenttype where paymenttypeid=?';
      $query_prm = array($paymenttypeid);
      require('inc/doquery.php');
      $id = $query_result[0]['accountingnumberid'];
      if ($reimburse == 1)
      {
        $comment = 'Remboursement';
        $debit = 0;
      }
      else
      {
        $comment = 'Paiement';
        $debit = 1;
      }
      $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmenttime,adjustmentcomment,reference,integrated) values (?, ?, curdate(), curtime(), ?, ?, 2)';
      $query_prm = array($_SESSION['ds_userid'], $paymentdate, $comment, $inserted_paymentid);
      require('inc/doquery.php');
      $adjustmentgroupid = $query_insert_id; $payment_adjustmentgroupid = $adjustmentgroupid;
      $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
      $query_prm = array($debit, $adjustmentgroupid, $value, $id);
      require('inc/doquery.php');
      if ($debit == 1) { $debit = 0; }
      else { $debit = 1; }
      $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid,nomatch) values (?,?,?,?,?,0,1)';
      $query_prm = array($debit, $adjustmentgroupid, $value, $clientid, 1); # hardcode accountingnumberid=1 for client sales
      require('inc/doquery.php');
      $p_aid = $query_insert_id;
      if ($reimbursed > 0)
      {
        # find accountingnumberid
        $query = 'select accountingnumberid from paymenttype where paymenttypeid=1'; # hardcode to cash "Espèces"
        $query_prm = array($paymenttypeid);
        require('inc/doquery.php');
        $id = $query_result[0]['accountingnumberid'];
        $query = 'insert into adjustment (debit,adjustmentgroupid,value,accountingnumberid,matchingid,nomatch) values (?,?,?,?,0,1)';
        $query_prm = array($debit, $adjustmentgroupid, $reimbursed, $id);
        require('inc/doquery.php');
        if ($debit == 1) { $debit = 0; }
        else { $debit = 1; }
        $query = 'insert into adjustment (debit,adjustmentgroupid,value,referenceid,accountingnumberid,matchingid,nomatch) values (?,?,?,?,?,0,1)';
        $query_prm = array($debit, $adjustmentgroupid, $reimbursed, $clientid, 1); # hardcode accountingnumberid=1 for client sales
        require('inc/doquery.php');
        $p_aid2 = $query_insert_id;
      }
      $query = 'update payment set toacc=1 where paymentid=?';
      $query_prm = array($inserted_paymentid);
      if (isset($inserted_paymentid2) && $inserted_paymentid2 > 0)
      {
        $query .= ' or paymentid=?';
        array_push($query_prm, $inserted_paymentid2);
      }
      require('inc/doquery.php');
    }
  }
  
  $netpaid = $value - $reimbursed;
  
  if (isset($fid_read_confirmed) && isset($fid_read_clientid)
  && $fid_read_confirmed == 0 && $clientid == $fid_read_clientid && $fid_read_isreturn == 0 && $netpaid == $fid_read_value)
  {
    $query = 'update invoice set confirmed=1,proforma=0,invoicedate=curdate(),invoicetime=curtime() where invoiceid=?';
    $query_prm = array($forinvoiceid);
    require('inc/doquery.php');
    $move_to_history_invoiceid = $forinvoiceid;
    require('inc/move_to_history.php');
    $fid_read_confirmed = 1;
    echo 'Facture <a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $forinvoiceid . '" target=_blank>' . $forinvoiceid . '</a> confirmé.<br>';
  }

  if ($forinvoiceid > 0 && $fid_read_matchingid == 0 && $clientid == $fid_read_clientid && $fid_read_confirmed == 1 && $reimburse == 0 && $fid_read_isreturn == 0)
  {
    if ($netpaid == $fid_read_value)
    {
      #match invoice to payment (copy from matching)
      $query = 'insert into matching (userid,date,clientid) values (?,CURDATE(),?)';
      $query_prm = array($_SESSION['ds_userid'],$clientid);
      require ('inc/doquery.php');
      $matchingid = $query_insert_id;
      
      $query = 'update invoicehistory set matchingid=? where invoiceid=? limit 1';
      $query_prm = array($matchingid,$fid_read_invoiceid);
      require ('inc/doquery.php');
      $query = 'update invoice set matchingid=? where invoiceid=? limit 1'; # keep this in case move_to_history did not work
      $query_prm = array($matchingid,$fid_read_invoiceid);
      require ('inc/doquery.php');
      /*
      $query = 'select adjustmentgroupid from adjustmentgroup where integrated=1 and reference=? limit 1';
      $query_prm = array($fid_read_invoiceid);
      require ('inc/doquery.php');
      if ($num_results) { $agid = $query_result[0]['adjustmentgroupid']; }
      else { $agid = 0; }
      if ($agid > 0)
      {
        $query = 'update adjustment set matchingid=? where adjustmentgroupid=?';
        $query_prm = array($matchingid,$agid);
        require ('inc/doquery.php');
      }
      */
      $query = 'update payment set matchingid=? where paymentid=? limit 1';
      $query_prm = array($matchingid,$inserted_paymentid);
      require ('inc/doquery.php');/*
      if ($payment_adjustmentgroupid > 0)
      {
        $query = 'update adjustment set matchingid=? where adjustmentgroupid=?';
        $query_prm = array($matchingid,$payment_adjustmentgroupid);
        require ('inc/doquery.php');
      }*/
      if (isset($inserted_paymentid2) && $inserted_paymentid2 > 0)
      {
        $query = 'update payment set matchingid=? where paymentid=? limit 1';
        $query_prm = array($matchingid,$inserted_paymentid2);
        require ('inc/doquery.php');
      }
    }
  }
}

if (!isset($_POST['value']) && isset($_GET['amount']) && $_GET['amount'] > 0)
{
  # direct link from invoicing
  $value = $_GET['amount'];
  $client = $_GET['clientid'];
  $vattotal = "";
  $valueautofocus = 1;
}

if ($_SESSION['ds_canpayments'] == 1) {

if ($forinvoiceid > 0)
{
  require('preload/advance.php');
  echo '<div class="myblock">';
  $totalpaid = 0;
  $paymentid = 0;
  $query = 'select advanceid,matchingid,isreturn,invoiceprice,confirmed,proforma,isnotice,returnreasonid,cancelledid,clientid
  from invoice where invoiceid=?
  union
  select advanceid,matchingid,isreturn,invoiceprice,confirmed,proforma,isnotice,returnreasonid,cancelledid,clientid
  from invoicehistory where invoiceid=?';
  $query_prm = array($forinvoiceid,$forinvoiceid);
  require('inc/doquery.php');
  $advanceid = $query_result[0]['advanceid'];
  $matchingid = $query_result[0]['matchingid'];
  $isreturn = $query_result[0]['isreturn'];
  $invoiceprice = $query_result[0]['invoiceprice'];
  $confirmed = $query_result[0]['confirmed'];
  $proforma = $query_result[0]['proforma'];
  $isnotice = $query_result[0]['isnotice'];
  $returnreasonid = $query_result[0]['returnreasonid'];
  $cancelledid = $query_result[0]['cancelledid'];
  $forinvoiceid_clientid = $query_result[0]['clientid']; # must match!
  ### copy from showinvoice
  $typetext = 'Facture';
  if ($proforma == 1 && $confirmed == 0)
  {
    $typetext = 'Proforma';
  }
  if ($isnotice)
  {
    $typetext = $_SESSION['ds_term_invoicenotice'];
  }
  if ($isreturn == 1)
  {
    $typetext = 'Avoir';
    if ($isnotice)
    {
      if ($returnreasonid > 0)
      {
        require('preload/returnreason.php');
        $typetext = $_SESSION['ds_term_invoicenotice'] . ' ' . $returnreasonA[$returnreasonid];
      }
      else { $typetext .= ' '.$_SESSION['ds_term_invoicenotice']; }
    }
  }
  if ($confirmed == 0 && $isnotice == 0 && $cancelledid == 0)
  {
    $typetext = 'Devis';
    if ($isreturn == 1)
    {
      $typetext .= ' Avoir';
    }
  }
  echo '<h2>',$typetext;
  if ($cancelledid) { echo ' ANNULÉ(E)'; }
  else { echo ' '.myfix($forinvoiceid).' : &nbsp; '.myfix($invoiceprice).' XPF'; }
  echo '</h2>';
  ###
  $query = 'SELECT paymentid,value,reimbursement,paymenttypename,payment.paymenttypeid,bankid,chequeno,paymentdate
            FROM payment,paymenttype
            WHERE payment.paymenttypeid=paymenttype.paymenttypeid
            AND forinvoiceid=? and clientid=?';
  $query_prm = array($forinvoiceid,$forinvoiceid_clientid);
  require('inc/doquery.php');

  for ($y=0;$y<$num_results;$y++)
  {
    if ($query_result[$y]['reimbursement'] == 1)
    {
      $totalpaid = $totalpaid - $query_result[$y]['value'];
    }
    else
    {
      $totalpaid = $totalpaid + $query_result[$y]['value'];
    }
  }

  if ($advanceid > 0)
  {
    $advance_amount = myround($invoiceprice*$advance_percentageA[$advanceid]/100);
    if ($totalpaid < $advance_amount) { echo 'Acompte à verser'; }
    else { echo 'Acompte versé'; }
    echo ' : <b>' . myfix($advance_amount) . '</b><br>';
  }

  if ($totalpaid > 0 || $matchingid > 0)
  {
    if ($totalpaid >= $invoiceprice || $matchingid > 0)
    { echo '<p>'.$typetext.' entièrement réglé(e).'; $value = 0; }
    else { echo '<p>'.$typetext.' <i>partiellement</i> réglé(e).'; }

    for ($y = 0; $y < $num_results; $y++)
    {
      $paymentid = $query_result[$y]['paymentid'];
      $paymenttypename = $query_result[$y]['paymenttypename'];
      $paymenttypeid = $query_result[$y]['paymenttypeid'];
      $bankid = $query_result[$y]['bankid'];
      $chequeno = $query_result[$y]['chequeno'];
      
      if ($query_result[$y]['reimbursement'] == 0 && $query_result[$y]['value'] > 0)
      {
        if ($num_results > 1) { echo '<br>'; }
        else { echo ' '; }
        echo 'Paiement ' . $paymentid . ', ';
        echo datefix($query_result[$y]['paymentdate'],'short').', ';
        echo $paymenttypename;
        if ($bankid > 0)
        {
          echo ': ';
          echo $bankA[$bankid];
          echo ' ' . $chequeno;
        }
        if ($num_results > 1 || $totalpaid < $invoiceprice)
        {
          echo ' '.myfix($query_result[$y]['value']).' XPF';
        }
      }
    }
    if ($totalpaid > 0 && $totalpaid < $invoiceprice)
    {
      $value = $invoiceprice-$totalpaid;
      echo '<br><b>Reste à payer : '.myfix($value).' XPF</b>';
    }
    echo '</p>';
  }
  echo '</div><br>';
}

echo '<h2>Paiement</h2><form method="post" action="sales.php">';
echo '<table cellpadding=1 cellspacing=1>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="1"';
if ($paymenttypeid == 1) { echo ' checked'; }
echo '>Espèces</td><td></td></tr>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="2"';
if ($paymenttypeid == 2) { echo ' checked'; }
echo '>Chèque</td><td>';
  echo ' &nbsp; <input type="radio" name="reimburse" value="0"';
  if ($reimburse != 1) { echo ' checked'; }
  echo '>Encaissement';
  echo ' (nous recevons de l\'argent)</td></tr>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="3"';
if ($paymenttypeid == 3) { echo ' checked'; }
echo '>Virement</td><td>';
  echo ' &nbsp; <input type="radio" name="reimburse" value="1"';
  if ($reimburse == 1) { echo ' checked'; }
  echo '>Remboursement';
  echo ' (nous payons)</td></tr>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="4"';
if ($paymenttypeid == 4) { echo ' checked'; }
echo '>Carte Bancaire</td><td></td></tr>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="5"';
if ($paymenttypeid == 5) { echo ' checked'; }
echo '>Prélèvement</td><td></td></tr>';
echo '<tr><td><input type="radio" name="paymenttypeid" value="6"';
if ($paymenttypeid == 6) { echo ' checked'; }
echo '>Salaire</td><td></td></tr>';
if ($_SESSION['ds_accountingaccess'])
{
  echo '<tr><td><input type="radio" name="paymenttypeid" value="7"';
  if ($paymenttypeid == 7) { echo ' checked'; }
  echo '>Perte</td><td></td></tr>';
  if ($_SESSION['ds_customname'] == 'AirFroid' || $_SESSION['ds_customname'] == 'airfroideq')
  {
    echo '<tr><td><input type="radio" name="paymenttypeid" value="8"';
    if ($paymenttypeid == 8) { echo ' checked'; }
    echo '>Compensation</td><td></td></tr>';
  }
}
if ($_SESSION['ds_customname'] == 'ANIMALICE'
|| $_SESSION['ds_customname'] == 'Polymeubles'
|| $_SESSION['ds_customname'] == 'SYNAPSE') # TODO option
{
  echo '<tr><td><input type="radio" name="paymenttypeid" value="9"';
  if ($paymenttypeid == 9) { echo ' checked'; }
  echo '>AMEX</td><td></td></tr>';
}

$showforinvoiceid = $forinvoiceid; if ($ok || $showforinvoiceid == 0) { $showforinvoiceid = ''; }
echo '<tr><td>Pour facture n<sup>o</sup>:<td><input autofocus type="text" STYLE="text-align:right" name="forinvoiceid" value="' . $showforinvoiceid . '" size=20> (optionnel / chercher valeurs)';

echo '<tr><td>';
if ($ok) { $client = ''; unset($_POST['client']); }
require('inc/selectclient.php');

if ($ok || $value == 0) { $value = ""; }
echo '<tr><td>Montant TTC:</td><td><input type="text"'; if ($valueautofocus == 1) { echo ' autofocus'; }
echo ' STYLE="text-align:right" name="value" value="' . $value . '" size=20> XPF';

if ($ok || $reimbursed == 0) { $reimbursed = ""; }
echo '<tr><td>Monnaie rendue:</td><td><input type="text" STYLE="text-align:right" name="reimbursed" value="' . $reimbursed . '" size=20> XPF';

if ($ok) { $paymentcomment = ''; }
echo '<tr><td>Info:</td><td><input type="text" STYLE="text-align:right" name="paymentcomment" value="' . d_input($paymentcomment) . '" size=80></td></tr>';

echo '<tr><td>Date:</td><td>';
if ($_SESSION['ds_canpaymentdate'] == 1)
{
  $datename = 'paymentdate';
  if ($_SESSION['ds_noretrodates']) { $dp_datepicker_min = $_SESSION['ds_curdate']; }
  require('inc/datepicker.php');
}
else { echo datefix2($_SESSION['ds_curdate']); }
echo '<tr><td colspan=2>&nbsp;';
echo '<tr><td colspan=2><b>Chèque/Virement/Carte Bancaire';
echo '<tr><td>Banque:</td><td><select name="bankid"><option value=0> </option>';

$query = 'select bankid,fullbankname from bank where deleted=0 order by fullbankname';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $selectedbankid = $_POST['bankid'];
  if ($ok) { $selectedbankid = -99; }
  echo '<option value="' . $row['bankid'] . '"';
  if ($row['bankid'] == $selectedbankid) { echo ' selected'; }
  echo '>' . $row['fullbankname'] . '</option>';
}
echo '</select></td></tr>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=2><b>Chèque</td></tr>';
if ($ok) { $chequeno=''; }
echo '<tr><td>Numéro:</td><td><input type="text" STYLE="text-align:right" name="chequeno" value="' . $chequeno . '" size=20></td></tr>';
if ($ok) { $payer=''; }
echo '<tr><td>Tireur:</td><td><input type="text" STYLE="text-align:right" name="payer" value="' . $payer . '" size=50></td></tr>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=2><b>Carte Bancaire';
$dp_itemname = 'payment_cardtype'; $dp_description = 'Réseaux'; 
require('inc/selectitem.php');
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=2><b>Optionnels</td></tr>';

if ($ok || $value == 0 || $vattotal == 0) { $vattotal = ""; }
echo '<tr><td>TVA (total):</td><td><input type="text" STYLE="text-align:right" name="vattotal" value="' . $vattotal . '" size=20> XPF'; 

if (isset($paymentcategoryA))
{
  echo '<tr><td>Catégorie:</td><td><select name="paymentcategoryid"><option value="0"></option>';
  foreach ($paymentcategoryA as $paymentcategoryid => $paymentcategoryname)
  {
    echo '<option value="' . $paymentcategoryid . '"';
    if ($_SESSION['ds_defpaymcatid']) echo ' selected';
    echo '>' . $paymentcategoryname . '</option>';
  }
  echo '</td></tr>';
}

 ?>
<tr><td>Employé(e):</td>
<td><select name="employeeid"><option value="0"></option><?php

$query = 'select employeeid,concat(employeename," ",employeefirstname) as employeename from employee where issales=1 and deleted=0 order by employeename'; # TODO option deleted
$query_prm = array();
  require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row2 = $query_result[$i];
  echo '<option value="' . $row2['employeeid'] . '">' . $row2['employeename'] . '</option>';
}
?></select></td></tr>
<?php

if ($_SESSION['ds_term_paymfield1'] != '')
{
  echo '<tr><td>' . $_SESSION['ds_term_paymfield1'] . ':</td><td><input type="text" STYLE="text-align:right" name="paymfield1" value="' . d_input($_POST['paymfield1']) . '" size=70></td></tr>';
}
if ($_SESSION['ds_term_paymfield2'] != '')
{
  echo '<tr><td>' . $_SESSION['ds_term_paymfield2'] . ':</td><td><input type="text" STYLE="text-align:right" name="paymfield2"';
  if (isset($_POST['paymfield2'])) { echo ' value="' . d_input($_POST['paymfield2']) . '"'; }
  echo ' size=70>';
}
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td align=center><input type=hidden name="step" value="0"><input type=hidden name="directfrominvoice" value="' . $directfrominvoice . '">';
echo '<input type=hidden name="salesmenu" value="' . $salesmenu . '">';
echo '<button type="submit">Enregistrer</button></td><td>&nbsp;</td></tr>';
echo '</table></form>';

}

?>