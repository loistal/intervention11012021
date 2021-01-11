<?php

require('reportwindow/invoicereport_cf.php');
$history = 'history';

$datefield = (int) $_POST['datefield'];
$datename = 'startdate'; require('inc/datepickerresult.php');
if ($_SESSION['ds_restrict_sales_reports'] && $startdate < (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01') { $startdate = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }
$datename = 'stopdate'; require('inc/datepickerresult.php');
$bynumber = (int) $_POST['bynumber'];
$startid = (int) $_POST['startid'];
$stopid = (int) $_POST['stopid'];
$invoicetype = (int) $_POST['invoicetype'];
$invoicestatus = (int) $_POST['invoicestatus'];
if ($invoicestatus == 3 || $invoicestatus == 5) { $history = ''; }
require('inc/findclient.php');
$islandid = (int) $_POST['islandid'];
$localvesselid = (int) $_POST['localvesselid'];
$userid = (int) $_POST['userid'];
if (isset($_POST['employeeid'])) { $employeeid = (int) $_POST['employeeid']; }
else { $employeeid = -1; }
if (isset($_POST['employee1id'])) { $employee1id = (int) $_POST['employee1id']; }
else { $employee1id = -1; }
if (isset($_POST['employee2id'])) { $employee2id = (int) $_POST['employee2id']; }
else { $employee2id = -1; }
if (isset($_POST['clientcategoryid'])) { $clientcategoryid = (int) $_POST['clientcategoryid']; }
else { $clientcategoryid = -1; }
if (isset($_POST['clientcategory2id'])) { $clientcategory2id = (int) $_POST['clientcategory2id']; }
else { $clientcategory2id = -1; }
if (isset($_POST['clientcategory3id'])) { $clientcategory3id = (int) $_POST['clientcategory3id']; }
else { $clientcategory3id = -1; }
$clienttermid = (int) $_POST['clienttermid'];
$invoicetagid = (int) $_POST['invoicetagid'];
$exinvoicetag = (int) $_POST['exinvoicetag'];
$reference = $_POST['reference']; $exreference = (int) $_POST['exreference'];
$extraname = $_POST['extraname']; $exextraname = (int) $_POST['exextraname'];
$field1 = $_POST['field1'];
$field2 = $_POST['field2'];
$invoicecomment = $_POST['invoicecomment'];
$ig_boolean = (int) $_POST['ig_boolean'];
$orderby = (int) $_POST['orderby'];

if ($_POST['accountingalert'] == 1)
{
  $datefield = 3;
  $invoicestatus = 1;
  $employeeid = $employee1id = $employee2id = -1;
  $clientcategoryid = $clientcategory2id = $clientcategory3id = -1;
}

$title = d_trad('invoicereport');
$datecolum = 'accountingdate';
if ($bynumber == 1) { $title .= ' numéros ' . $startid . ' à ' . $stopid; }
else
{
  $title .= ' ' . d_trad('between',array(datefix2($startdate),datefix2($stopdate)));
  if ($datefield == 1) { $title .= ' (' . d_output($_SESSION['ds_term_deliverydate']) . ')'; $datecolum = 'deliverydate'; }
  elseif ($datefield == 2) { $title .= ' (' . d_trad('inputdate') . ')'; $datecolum = 'invoicedate'; }
  elseif ($datefield == 3) { $title .= ' (' . d_trad('tobepaidbefore') . ')'; $datecolum = 'paybydate'; }
}
session_write_close(); 
showtitle($title);
echo '<h2>' . $title . '</h2>';

require('inc/showparams.php');

$query = 'select localvesselid,invoiceid,client.clientid,clientname,accountingdate,invoicedate,deliverydate,paybydate,userid,invoice'.$history.'.employeeid,
client.employeeid as employee1id,client.employeeid2 as employee2id,clientcategoryid,clientcategory2id,clientcategory3id,clienttermid,invoicetagid,reference,extraname,
field1,field2,invoiceprice,invoicevat,invoiceprice-invoicevat as invoicepricenet,invoicecomment,returnreasonid,isreturn,isnotice,isreturn,returnreasonid,
confirmed,cancelledid,matchingid,client.townid,town.islandid,custominvoicedate,invoicetag2id,invoicetime,invoicegroupid,email,batchemail
from invoice'.$history.',client,town where invoice'.$history.'.clientid=client.clientid and client.townid=town.townid';
$query_prm = array();
if ($invoicetype == 1) { $query .= ' and isreturn=0'; }
elseif ($invoicetype == 2) { $query .= ' and isreturn=1'; }
elseif ($invoicetype == 3) { $query .= ' and proforma=1'; }
elseif ($invoicetype == 4) { $query .= ' and isnotice=1'; }
elseif ($invoicetype == 5) { $query .= ' and isreturn=1 and isnotice=1'; }
if ($ig_boolean == 0) { $query .= ' and invoicegroupid=0'; }
elseif ($ig_boolean == 1) { $query .= ' and invoicegroupid>0'; }
if ($invoicestatus == 1) { $query .= ' and confirmed=1 and cancelledid=0 and matchingid=0'; }
elseif ($invoicestatus == 2) { $query .= ' and confirmed=1 and cancelledid=0 and matchingid>0'; }
elseif ($invoicestatus == 3) { $query .= ' and confirmed=0 and cancelledid=0'; }
elseif ($invoicestatus == 4) { $query .= ' and cancelledid=1'; }
elseif ($invoicestatus == 5) { $query .= ' and cancelledid=2'; }
elseif ($invoicestatus == 0)  { $query .= ' and confirmed=1 and cancelledid=0'; }
if ($bynumber == 1) { $query .= ' and invoiceid>=? and invoiceid<=?'; array_push($query_prm, $startid, $stopid); }
else { $query .= ' and ' . $datecolum . '>=? and ' . $datecolum . '<=?'; array_push($query_prm, $startdate, $stopdate); }
if ($clientid > 0) { $query .= ' and invoice'.$history.'.clientid=?'; array_push($query_prm, $clientid); }
if ($islandid > 0) { $query .= ' and town.islandid=?'; array_push($query_prm, $islandid); }
if ($localvesselid > 0) { $query .= ' and localvesselid=?'; array_push($query_prm, $localvesselid); }
if ($userid > 0) { $query .= ' and invoice'.$history.'.userid=?'; array_push($query_prm, $userid); }
if ($employeeid >= 0) { $query .= ' and invoice'.$history.'.employeeid=?'; array_push($query_prm, $employeeid); }
if ($employee1id >= 0) { $query .= ' and client.employeeid=?'; array_push($query_prm, $employee1id); }
if ($employee2id >= 0) { $query .= ' and client.employeeid2=?'; array_push($query_prm, $employee2id); }
if ($clientcategoryid >= 0) { $query .= ' and clientcategoryid=?'; array_push($query_prm, $clientcategoryid); }
if ($clientcategory2id >= 0) { $query .= ' and clientcategory2id=?'; array_push($query_prm, $clientcategory2id); }
if ($clientcategory3id >= 0) { $query .= ' and clientcategory3id=?'; array_push($query_prm, $clientcategory3id); }
if ($clienttermid > 0) { $query .= ' and clienttermid=?'; array_push($query_prm, $clienttermid); }
if ($invoicetagid > 0)
{
  if ($exinvoicetag) { $query .= ' and invoicetagid<>?'; array_push($query_prm, $invoicetagid); }
  else { $query .= ' and invoicetagid=?'; array_push($query_prm, $invoicetagid); }
}
if ($reference != '')
{
  $query .= ' and reference';
  if ($exreference == 1) { $query .= ' not'; }
  $query .= ' like ?'; array_push($query_prm, '%' . $reference . '%');
}
if ($extraname != '')
{
  $query .= ' and extraname';
  if ($exextraname == 1) { $query .= ' not'; }
  $query .= ' like ?'; array_push($query_prm, '%' . $extraname . '%');
}
if ($field1 != '') { $query .= ' and field1 like ?'; array_push($query_prm, '%' . $field1 . '%'); }
if ($field2 != '') { $query .= ' and field2 like ?'; array_push($query_prm, '%' . $field2 . '%'); }
if ($invoicecomment != '') { $query .= ' and invoicecomment like ?'; array_push($query_prm, '%' . $invoicecomment . '%'); }
if ($_SESSION['ds_allowedclientlist'] != '') { $query .= ' and invoice'.$history.'.clientid in ' . $_SESSION['ds_allowedclientlist']; }
if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoice'.$history.'.userid=?'; array_push($query_prm, $_SESSION['ds_userid']);
  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoice'.$history.'.employeeid=?'; array_push($query_prm, $_SESSION['ds_myemployeeid']);
  }
  $query .= $queryadd.')';
}

if ($invoicestatus == -1)
{
  $query = $query . ' UNION ' . str_replace('history', '', $query);
  $query_prm = array_merge($query_prm, $query_prm);
}

if ($orderby == 1) { $query .= ' order by clientid,invoiceid'; $subtfield1 = 'clientid'; }
elseif ($orderby == 2) { $query .= ' order by reference,invoiceid'; }
elseif ($orderby == 3) { $query .= ' order by field1,invoiceid'; }
elseif ($orderby == 4) { $query .= ' order by field2,invoiceid'; }
elseif ($orderby == 5) { $query .= ' order by clientname,invoiceid'; $subtfield1 = 'clientname'; }
elseif ($orderby == 6) { $query .= ' order by '.$datecolum.',invoiceid'; }
else { $query .= ' order by invoiceid'; }

if ($_SESSION['ds_sqllimit'] > 0) { $query .= ' limit ' . $_SESSION['ds_sqllimit']; }

require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

require('inc/showreport.php');

if ($_SESSION['ds_can_send_emails'])
{
  $invoice_list = array();
  $orderby = array('clientid','invoiceid');
  d_sortresults($row, $orderby, $num_rows);
  $temp = '';
  for ($i=0; $i < $num_rows; $i++)
  {
    if ($temp == '') { $temp .= $row[$i]['invoiceid']; }
    else { $temp .= '|' . $row[$i]['invoiceid']; }
    if (!isset($row[($i+1)]['clientid']) || $row[$i]['clientid'] != $row[($i+1)]['clientid'])
    {
      $invoice_list[] = $temp;
      $temp = '';
    }
  }
}

echo '<br><br><table class="transparent"><tr><td valign=top>';
if ($_SESSION['ds_invoicereport_menus'])
{
  if ($_SESSION['ds_can_send_emails'])
  {
    $query = 'select email_bodyid,subject from email_body order by subject';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<form method="post" action="send_emails.php">
      <h2>Expédier ces factures par e-mail</h2>';
      echo '<br> Sujet: <select name="email_bodyid">';
      for ($i=0; $i < $num_results; $i++)
      {
        echo '<option value="'.$query_result[$i]['email_bodyid'].'">'.d_output($query_result[$i]['subject']).'</option>';
      }
      echo '</select>';
      echo '<br><br> <input type=checkbox name="add_attachments" value=1 checked> Joindre les images<sup>*</sup>';
      echo '<br><br> <span class="alert">ATTENTION &nbsp; Envoi irréversible</span>
      <br><br> <input type=checkbox name="confirm1" value=1>
      <input type="submit" value="Envoyer">
      <input type=checkbox name="confirm2" value=1>
      <input type=hidden name="invoice_list" value="'.base64_encode(serialize($invoice_list)).'">
      </form>';
      echo '<p><sup>*</sup>Si plusieurs factures pour un client, uniquement les images de la dernière facture seront inclus</p>';
    }
  }
  echo '<td width=50><td valign=top>';

  echo '<form method="post" action="copy_invoices.php">
  <h2>Copier ces factures</h2><br>À la date: ';
  $datename = 'copydate'; require('inc/datepicker.php');
  echo '<br><br> <span class="alert">Seules les factures confirmées peuvent être copiées</span>
  <br><br> <input type=checkbox name="confirm3" value=1>
  <input type="submit" value="Copier">
  <input type=checkbox name="confirm4" value=1>
  <input type=hidden name="invoice_list" value="'.base64_encode(serialize($invoice_list)).'">
  </form>';
}
echo '</table>';

?>