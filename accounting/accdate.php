<?php

# TODO refactor

if (!$_SESSION['ds_acc_canmodinvoice']) { exit; }

$PA['invoiceid'] = 'int';
require('inc/readpost.php');

require('preload/user.php');
require('preload/employee.php');

$step = 0;
if (isset($_POST['step'])) { $step = (int) $_POST['step']; }

switch($step)
{

  # 
  case 1:
  if ($_SESSION['ds_exportfields'])
  {
    $query = 'select exported from invoicehistory where invoiceid=?';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    if ($query_result[0]['exported'] == 1) { echo '<p class=alert>ATTENTION: Facture '.$invoiceid.' est exportée.</p><br><br>'; }
  }

  $query = 'select deliverydate,deliverytypeid,invoicetagid,invoicecomment,reference,invoicehistory.userid,invoicehistory.employeeid
  ,accountingdate,invoicehistory.clientid,custominvoicedate,proforma
  ,clientname,isreturn,matchingid,confirmed,cancelledid,localvesselid
  from invoicehistory,client where invoicehistory.clientid=client.clientid and cancelledid=0 and invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  if ($num_results < 1) { echo '<p>Facture inexistante, non-confirmée ou annulée.</p><br><br>'; $step = 0; }
  else
  {
    $row = $query_result[0];
    $custominvoicedate = $row['custominvoicedate'];
    $localvesselid = $row['localvesselid'];
    $deliverytypeid = $row['deliverytypeid'];
    $clientname = $row['clientid'] . ': ' . d_decode($row['clientname']);
    $client = $row['clientid'];
    $employeeid = $row['employeeid'];
    $userid = $row['userid'];
    $invoicetagid = $row['invoicetagid'];
    ?><h2>Modifier facture</h2>
    <form method="post" action="accounting.php"><table><?php
    echo '<tr><td>';
    if ($row['isreturn'] == 1) { echo 'Avoir '; }
    else { echo 'Facture '; }
    echo '</td><td>' . $invoiceid . '</td></tr>';
    echo '<tr><td>';
    if ($row['matchingid'] == 0)
    {
      require('inc/selectclient.php');
    }
    else
    {
      echo 'Client</td><td>' . d_output($clientname);
      echo '<input type=hidden name="client" value="' . $row['clientid'] . '">';
    }

    echo '<tr><td>', $_SESSION['ds_term_accountingdate'],':<td>'; $datename = 'accountingdate'; $selecteddate = $row['accountingdate']; require('inc/datepicker.php');
    
    if ($_SESSION['ds_usedelivery'])
    {
      echo '<tr><td>', $_SESSION['ds_term_deliverydate'],':<td>'; $datename = 'deliverydate'; $selecteddate = $row['deliverydate']; require('inc/datepicker.php');
    }
    
    if ($_SESSION['ds_term_custominvoicedate'] != '')
    {
      echo '<tr><td>', $_SESSION['ds_term_custominvoicedate'],':<td>';
      $datename = 'custominvoicedate'; $selecteddate = $custominvoicedate; require('inc/datepicker.php');
    }

    $dp_itemname = 'employee'; $dp_issales = 1; $dp_selectedid = $employeeid; $dp_description = 'Employé(e)';
    require('inc/selectitem.php');
    
    $dp_itemname = 'user'; $dp_issales = 1; $dp_noblank = 1; $dp_selectedid = $userid; $dp_description = 'Facturier';
    require('inc/selectitem.php');
    
    $dp_itemname = 'localvessel'; $dp_description = 'Bateau'; $dp_selectedid = $localvesselid;
    require('inc/selectitem.php');
    
    if ($_SESSION['ds_useinvoicetag'])
    {
      $dp_itemname = 'invoicetag'; $dp_description = $_SESSION['ds_term_invoicetag']; $dp_selectedid = $invoicetagid;
      require('inc/selectitem.php');
    }
    
    echo '<tr><td>Proforma :<td><input type=checkbox name=proforma value=1';
    if ($row['proforma'] == 1) { echo ' checked'; }
    echo '>';
    
    if ($_SESSION['ds_term_reference'] != "") { echo '<tr><td>' . $_SESSION['ds_term_reference'] . ':</td><td>'; }
    else { echo '<tr><td>Référence à afficher:</td><td>'; }
    echo '<input type=text name=reference value="' . $row['reference'] . '"></td></tr>';
    echo '<tr><td>Commentaire:</td><td><input type=text name=invoicecomment value="' . $row['invoicecomment'] . '"></td></tr>';
    if ($row['matchingid'] > 0)
    {
      echo '<tr><td>Statut:</td><td>Lettré</td></tr>';
    }
    else
    {
      echo '<tr><td>Statut:</td><td><select name=cancelled>';
      echo '<option value=-1'; if ($row['confirmed']) { echo ' selected'; }; echo '>Confirmé</option>';
      echo '<option value=1'; if ($row['cancelledid']) { echo ' selected'; }; echo '>Annulé</option>';
      echo '</select></td></tr>';
    }
    
    if ($_SESSION['ds_usedelivery'] > 0)
    {
      ### NEW deliverytype
      echo '<tr><td>';
      $dp_itemname = 'deliverytype'; $dp_selectedid = $deliverytypeid; $dp_noblank = 1;
      require('inc/selectitem.php');
      ###
    }
  ?>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="2"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
    <input type=hidden name="invoiceid" value="<?php echo $invoiceid; ?>">
    <input type="submit" value="Valider"></td></tr>
    </table></form><?php
  }
  break;

  # 
  case 2:
  $PA['proforma'] = 'uint';
  require('inc/readpost.php');
  
  $localvesselid = (int) $_POST['localvesselid'];
  $deliverytypeid = $_POST['deliverytypeid']+0;
  $datename = 'accountingdate'; require('inc/datepickerresult.php');
  $datename = 'deliverydate'; require('inc/datepickerresult.php');
  $datename = 'custominvoicedate'; require('inc/datepickerresult.php');
  $employeeid = (int) $_POST['employeeid'];
  $userid = $_POST['userid']+0;
  $reference = $_POST['reference'];
  $invoicecomment = $_POST['invoicecomment'];
  $invoicetagid = $_POST['invoicetagid']+0;
  
  $PA['client'] = 'client';
  require('inc/readpost.php');
  
  if ($clientid > 0)
  {
    $query = 'select adjustmentgroupid from adjustmentgroup where integrated=1 and reference=? and deleted=0';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    if ($num_results) { $adjustmentgroupid = $query_result[0]['adjustmentgroupid']; }
    else { $adjustmentgroupid = 0; }
    if ($adjustmentgroupid > 0)
    {
      $query = 'update adjustmentgroup set adjustmentdate=?,originaladjustmentdate=curdate(),adjustmenttime=curtime(),userid=? where adjustmentgroupid=?';
      $query_prm = array($accountingdate,$_SESSION['ds_userid'],$adjustmentgroupid);
      require('inc/doquery.php');
      $query = 'update adjustment set referenceid=? where adjustmentgroupid=? and referenceid>0';
      $query_prm = array($clientid,$adjustmentgroupid);
      require('inc/doquery.php');
    }

    $query = 'update invoicehistory set proforma=?,custominvoicedate=?,localvesselid=?,deliverytypeid=?,invoicetagid=?,invoicecomment=?,reference=?,clientid=?,userid=?,employeeid=?,accountingdate=?,deliverydate=? where invoiceid=?';
    $query_prm = array($proforma,$custominvoicedate,$localvesselid,$deliverytypeid,$invoicetagid,$invoicecomment,$reference,$clientid,$userid,$employeeid,$accountingdate,$deliverydate,$invoiceid);
    require('inc/doquery.php');
    
    if ($_POST['cancelled'] == 1)
    {
      $query = 'update invoice set cancelledid=1,confirmed=0 where matchingid=0 and invoiceid=?';
      $query_prm = array($invoiceid);
      require('inc/doquery.php');
      $query = 'update invoicehistory set cancelledid=1,confirmed=0 where matchingid=0 and invoiceid=?';
      $query_prm = array($invoiceid);
      require('inc/doquery.php');

      if ($adjustmentgroupid > 0)
      {
        $query = 'update adjustmentgroup set deleted=1 where adjustmentgroupid=? limit 1';
        $query_prm = array($adjustmentgroupid);
        require('inc/doquery.php');
      }
    }

    echo 'Facture/avoir modifié.<br><br>';
  }
  break;

}

if ($step != 1)
{
  ?><h2>Modifier facture</h2>
  <form method="post" action="accounting.php"><table>
  <tr><td>Numéro facture/avoir: </td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10></td>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
  <input type="submit" value="Valider"></td></tr>
  </table></form><?php
}

?>