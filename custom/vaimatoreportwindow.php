<?php

function d_nolinebreak($string) # TODO remove
{
  #return str_replace(" ", "&nbsp;", $string);
  return $string;
}

$reportwindow = 1;
require ('inc/top.php');
function showtitle($title)
{
  echo '<TITLE>' . $title . '</TITLE></HEAD><BODY>';
}

$report = $_POST['report'];
if ($report == "" && $_GET['report'] != "") { $report = $_GET['report']; }


# TODO completely remove oldfunc
if($report != 'showfr'
   && $report != 'listloc'
  )
{
  require('custom/oldfunc.php');
}


switch($report)
{

  case 'cdj':
  require('preload/bank.php');
  require('preload/employee.php');
  $datename = 'cdjdate';
  require('inc/datepickerresult.php');
  $date = $cdjdate;
  
  unset($total);
  echo '<h3>Caisse du jour ' . datefix($date) . '</h3>';
  for ($x = 1; $x <= 2; $x++)
  {
    $subtotal = 0;
    $query = 'select employeeid,reimbursement,value,name,paymenttypename,paymenttime,depositbankid as bankid from payment,usertable,paymenttype where payment.userid=usertable.userid and payment.paymenttypeid=paymenttype.paymenttypeid and paymentdate="' . $date . '"';
    if ($x == 1) { $query .= ' and paymenttime < "12:00:00"'; echo '<h4>Matin</h4>'; }
    if ($x == 2) { $query .= ' and paymenttime >= "12:00:00"'; echo '<h4>Après-midi</h4>'; }
    $query .= ' order by name,employeeid,paymenttypename,bankid,reimbursement';
    #echo $query;
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results = mysql_num_rows($result);
    
    echo '<table class="report">';
    echo '<tr><td><b>Utilisateur</b></td><td><b>Employé<td><b>Type</b></td><td><b>Dépôt banque</b></td><td><b>Montant</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = mysql_fetch_array($result);
      
      if ($i != 0 && ($lastname != $row['name'] || $lastpaym != $row['paymenttypename'] || $lastbankid != $row['bankid'] || $lastemployeeid != $row['employeeid'])) # || $lastreimb != $row['reimbursement']
      {
        echo '<tr><td>' . $lastname . '</td><td>' . $employeeA[$lastemployeeid] . '</td><td>' . $lastpaym . '</td><td>' . $bankA[$lastbankid] . '</td><td align=right>' . myfix($subtotal) . '</td></tr>';
        $subtotal = 0;
      }
      
      $value = $row['value'];
      if ($row['reimbursement'] == 1) { $value = 0 - $value; }
      #echo '<tr><td>' . $row['name'] . '</td><td>' . $row['paymenttypename'] . '</td><td>' . $bankA[$row['bankid']] . '</td><td align=right>' . myfix($value) . '</td></tr>';
      $lastname = $row['name']; $lastpaym = $row['paymenttypename']; $lastbankid = $row['bankid']; $lastreimb = $row['reimbursement']; $lastemployeeid = $row['employeeid'];
      $total[$row['paymenttypename']] += $value;
      $subtotal += $value;
    }
    ### copy from above
    echo '<tr><td>' . $lastname . '</td><td>' . $employeeA[$lastemployeeid] . '</td><td>' . $lastpaym . '</td><td>' . $bankA[$lastbankid] . '</td><td align=right>' . myfix($subtotal) . '</td></tr>';
    ###
    echo '</table>';
  }

  echo '<h4>Totaux</h4>'; $gtotal = 0;
  echo '<table class="report">';
  foreach ($total as $key => $value)
  {
    echo '<tr><td><b>' . $key . '</td><td align=right>' . myfix($value) . '</td></tr>';
    $gtotal += $value;
  }
  echo '<tr><td><b>Total</td><td align=right><b>' . myfix($gtotal) . '</td></tr>';
  echo '</table>';
  break;

case 'rfa':

$islandid = (int) $_POST['islandid'];
$clientsectorid = (int) $_POST['clientsectorid'];
$client = $_POST['client']; require('inc/findclient.php');
$productfamilyid = (int) $_POST['productfamilyid'];
$islandid = (int) $_POST['islandid'];
$range1 = (int) $_POST['range1'];
$range2 = (int) $_POST['range2'];
$range3 = (int) $_POST['range3'];
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');

$ourtitle = 'RFA PERIODE du ' . datefix2($startdate) . ' au ' . datefix2($stopdate);
showtitle($ourtitle);

$query = 'select address,invoicehistory.invoiceid,productid,invoicehistory.clientid,clientname,accountingdate,sum(quantity) as quantity,isreturn,sum(lineprice) as lineprice';
$query = $query . ' from invoicehistory,invoiceitemhistory,client';
if ($islandid > 0) { $query = $query . ',town'; }
$query = $query . ' where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoicehistory.clientid=client.clientid';
if ($islandid > 0) { $query = $query . ' and client.townid=town.townid'; }
$query = $query . ' and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
$query = $query . ' and (productid=1 or productid=4) and cancelledid=0 and confirmed=1'; # hardcoding the two product ids (1,4) and npu = (6,12)       and isreturn=0
# client,island,sector
if ($clientid > 0) { $query = $query . ' and invoicehistory.clientid=?'; array_push($query_prm,$clientid); }
if ($clientsectorid > 0) { $query = $query . ' and client.clientsectorid=?'; array_push($query_prm,$clientsectorid); }
if ($islandid > 0) { $query = $query . ' and town.islandid=?'; array_push($query_prm,$islandid); }
$query = $query . ' group by clientid,invoiceid,productid';
$query = $query . ' order by clientid,invoiceid,productid';
require('inc/doquery.php');

$lastclientid = -1; $lastinvoiceid = -1; $lastproductid = -1; $linequantity = 0; $linetotal = 0; #$linevalue = 0; $clienttotal = 0;
for ($i=0;$i<$num_results;$i++)
{
  if ($i != 0 && $query_result[$i]['invoiceid'] != $lastinvoiceid)
  {
    if ($lastproductid == 1) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
    echo '<td align=right>' . myfix($linetotal) .'</td></tr>';
    $linetotal = 0;
    # $linequantity = 0; $linevalue = 0; 
  }
  if ($query_result[$i]['clientid'] != $lastclientid)
  {
    if ($i > 0)
    {
      echo '<tr><td colspan=8>TOTAL</td><td align=right>' . myfix($clienttotal) .'</td></tr></table>';
      $clienttotal = 0;
      echo '<p class=breakhere></p>';
    }
    echo '<h2>REMISE DE FIN D\'ANNEE SUR VAIMATO 1,5L<br>PERIODE du ' . datefix2($startdate) . ' au ' . datefix2($stopdate) . '</h2>';
    echo '<p>' . d_output(d_decode($query_result[$i]['clientname'])) . ' (' . $query_result[$i]['clientid'] . ') ' . d_output($query_result[$i]['address']) . '</p>'; # +address
    echo '<table class="report"><tr><td><b>Date</td><td><b>No Facture</td><td><b>6x1.5l</td><td><b>Valeur</td><td><b>Taux remise</td><td><b>12x1.5l</td><td><b>Valeur</td><td><b>Taux remise</td><td><b>Remise</td></tr>';
  }
  if ($i == 0 || $query_result[$i]['invoiceid'] != $lastinvoiceid)
  {
    echo '<tr><td align=right>' . datefix2($query_result[$i]['accountingdate']) . '</td><td align=right>';
    if ($query_result[$i]['isreturn'] == 1) { echo '(Avoir) '; }
    echo $query_result[$i]['invoiceid'] . '</td>';
  }
  $productid = $query_result[$i]['productid'];
  $quantity = $query_result[$i]['quantity'];
  if ($productid == 1) { $npu = 6; $divider = 1; }
  else { $npu = 12; $divider = 1; }
  $quantity = $quantity / $npu;
  if ($productid == 4 && $query_result[$i]['invoiceid'] != $lastinvoiceid) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
  $lineprice = $query_result[$i]['lineprice']; if ($query_result[$i]['isreturn'] == 1) { $lineprice = 0 - $lineprice; }
  echo '<td align=right>' . myfix($quantity) . '</td><td align=right>' . myfix($lineprice) . '</td>';
    $taux = $range3;
    if ($quantity < 100) { $taux = $range2; }
    if ($quantity < 50) { $taux = $range1; }
    if ($quantity < 20) { $taux = 0; }
    $remise = myround($lineprice * $taux/100);
    $clienttotal = $clienttotal + $remise;
    $linetotal = $linetotal + $remise;
	echo '<td align=right>'.$taux.'%</td>';
#  $linequantity = $linequantity + ($quantity/$divider);
#  $linevalue = $linevalue + $query_result[$i]['lineprice'];
  $lastclientid = $query_result[$i]['clientid'];
  $lastinvoiceid = $query_result[$i]['invoiceid'];
  $lastproductid = $query_result[$i]['productid'];
}
if ($num_results)
{
  if ($lastproductid == 1) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
  echo '<td align=right>' . myfix($linetotal) .'</td></tr>';
  echo '<tr><td colspan=8>TOTAL</td><td align=right>' . myfix($clienttotal) .'</td></tr></table>';
}

break;

case 'locpaybymonth':
$month = $_POST['month']+0;
$year = $_POST['year']+0;
echo '<h2>Locations encaissées '.$month.'/'.$year.'</h2>' . $ourparams;
#echo '<p class="alert">Ce rapport demande que le champs "paiement pour facture no" est rempli</p>';
echo '<table border=1 cellpadding=1 cellspacing=1>';
# date reglement -nom du client- montant HT -montant TVA -montant TTC-N° Facture-Période de location mois x à mois y --tel que imprimé sur facture-
echo '<tr><td><b>Date reglement</b></td><td><b>Client</b></td><td><b>Montant HT</b></td><td><b>Montant TVA</b></td><td><b>Montant TTC</b></td>';
echo '<td><b>N<sup>o</sup> facture</b></td><td><b>Période de location</b></td></tr>';
### old query needs "forinvoiceid"
#$query = 'select paymentid,paymentdate,payment.clientid,clientname,value,forinvoiceid,reference,reimbursement from payment,client,invoicehistory';
#$query = $query . ' where payment.clientid=client.clientid and payment.forinvoiceid=invoicehistory.invoiceid';
#$query = $query . ' and year(paymentdate)=? and month(paymentdate)=? and reference like "%Contrat%"';
#  $query = $query . ' union ';
#  $query = $query . 'select paymentid,paymentdate,payment.clientid,clientname,value,forinvoiceid,paymentcomment,reimbursement as reference from payment,client';
#  $query = $query . ' where payment.clientid=client.clientid';
#  $query = $query . ' and year(paymentdate)=? and month(paymentdate)=? and (forinvoiceid=0 or forinvoiceid IS NULL) and paymentcomment like "%Prélèvement%"';
#$query = $query . ' order by paymentdate,paymentid';
#$query_prm = array($year,$month,$year,$month);
$query = 'select paymentid,paymentdate,payment.clientid,clientname,value,forinvoiceid,paymentcomment as reference,reimbursement from payment,client';
$query = $query . ' where payment.clientid=client.clientid and value>0 and paymenttypeid<>7';
$query = $query . ' and year(paymentdate)=? and month(paymentdate)=? and paymentcategoryid=2';
$query = $query . ' order by paymentdate,paymentid';
$query_prm = array($year,$month);
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$tval=0; $tvat=0; $tttc=0;
for ($i=0;$i<$num_results_main;$i++)
{
  echo '<tr><td align=right>' . datefix2($main_result[$i]['paymentdate']) . '</td><td>' . $main_result[$i]['clientid'] . ': ' . d_decode($main_result[$i]['clientname']) . '</td>';
  $value = $main_result[$i]['value'];
  $ttc = $value;
  $value = round($main_result[$i]['value']*10/11);
  $vat = $ttc - $value;
  if ($main_result[$i]['reimbursement']) { $value = 0 - $value; $ttc = 0 - $ttc; $vat = 0 - $vat; }
  $ttc = $value + $vat;
  $tval = $tval + $value;
  $tvat = $tvat + $vat;
  $tttc = $tttc + $ttc;
  echo '<td align=right>' . myfix($value) . '</td><td align=right>' . myfix($vat) . '</td><td align=right>' . myfix($ttc) . '</td>';
  echo '<td align=right>' . $main_result[$i]['forinvoiceid'] . '</td><td>' . $main_result[$i]['reference'] . '</td></tr>';
}
echo '<tr><td colspan=2><b>Total</td>';
echo '<td align=right><b>' . myfix($tval) . '</td><td align=right><b>' . myfix($tvat) . '</td><td align=right><b>' . myfix($tttc) . '</td>';
echo '<td colspan=10>&nbsp;</tr>';
echo '</table>';
break;

case 'locclientpayOLD';
$_POST['showbalance'] = 0;
require('preload/employee.php');
$lastyear = $year = substr($_SESSION['ds_curdate'],0,4)-1;


$debcred = $_POST['debcred'];
$onlydebitbalance =  $_POST['onlydebitbalance'];
$ourparams = '';
if (!$_POST['ss'])
{
  if ($_POST['clientcategoryid'] > 0)
  {
    $query = 'select clientcategoryname from clientcategory where clientcategoryid="' . $_POST['clientcategoryid'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $ourparams = $ourparams . '<p><b>Catégorie client: ' . $row['clientcategoryname'] . '</b></p>';
  }
  if ($_POST['clientcategory2id'] > 0)
  {
    $query = 'select clientcategory2name from clientcategory2 where clientcategory2id="' . $_POST['clientcategory2id'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $ourparams = $ourparams . '<p><b>Catégorie 2 client: ' . $row['clientcategory2name'] . '</b></p>';
  }
  if ($_POST['islandid'] > 0)
  {
    $query = 'select islandname from island where islandid="' . $_POST['islandid'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $ourparams = $ourparams . '<p><b>Île: ' . $row['islandname'] . '</b></p>';
  }
  if ($_POST['employeeid'] > 0)
  {
    $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $_POST['employeeid'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $ourparams = $ourparams . '<p><b>Employee ' . $_SESSION['ds_term_clientemployee1'] . ': ' . $row['employeename'] . '</b></p>';
  }
  if ($_POST['employeeid2'] > 0)
  {
    $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $_POST['employeeid2'] . '"';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $ourparams = $ourparams . '<p><b>Employee ' . $_SESSION['ds_term_clientemployee2'] . ': ' . $row['employeename'] . '</b></p>';
  }
  if ($_POST['interdit'] == 1) { $ourparams = $ourparams . '<p><b>Clients interdits</b></p>'; }
  if ($_POST['interdit'] == 2) { $ourparams = $ourparams . '<p><b>Clients non interdits</b></p>'; }
  if ($_POST['interdit'] == 3) { $ourparams = $ourparams . '<p><b>Comptes fermés</b></p>'; }
}
if ($ourparams == '') { $ourparams = '<br>'; }

    $query = 'select months,islandname,client.clientid,clientname,address,postaladdress,client.quarter,townname,telephone,cellphone,fax,contact,clientcategoryname,clienttermname,blocked,employeeid from client,town,island,clientcategory,clientterm,vmt_rental where vmt_rental.clientid=client.clientid and client.clienttermid=clientterm.clienttermid and client.clientcategoryid=clientcategory.clientcategoryid and client.townid=town.townid and town.islandid=island.islandid';
    if ($_POST['clientcategoryid'] > 0) { $query = $query . ' and client.clientcategoryid=' . $_POST['clientcategoryid']; }
    if ($_POST['clientcategory2id'] > 0) { $query = $query . ' and client.clientcategory2id=' . $_POST['clientcategory2id']; }
    if ($_POST['islandid'] > 0) { $query = $query . ' and town.islandid="' . $_POST['islandid'] . '"'; }
    if ($_POST['employeeid'] > 0) { $query = $query . ' and client.employeeid="' . $_POST['employeeid'] . '"'; }
    if ($_POST['employeeid2'] > 0) { $query = $query . ' and client.employeeid2="' . $_POST['employeeid2'] . '"'; }
    if ($_POST['interdit'] == 1) { $query = $query . ' and client.blocked=0'; }
    if ($_POST['interdit'] == 2) { $query = $query . ' and client.blocked>0'; }
    if ($_POST['interdit'] == 3) { $query = $query . ' and client.deleted=1'; }
    else { $query = $query . ' and client.deleted=0'; }
    $query = $query . ' and months>1';
#$query = $query . ' and client.clientid in (select clientid from vmt_rental where months>1)';
#$query = $query . ' LIMIT 20';
    $result = mysql_query($query, $db_conn); querycheck($result);
    $num_results_main = mysql_num_rows($result);
    #echo '<h2>Liste des ' . ($num_results_main) . ' clients ' . $_SESSION['ds_customname'] . ' location >1mois ' . $lastyear .'</h2>' . $ourparams;
    echo '<h2>Clients location >1mois ' . $lastyear .'</h2>' . $ourparams;
	if ($_POST['ss'])
	{
	  echo 'Numéro;Nom;';
    echo 'Contrat;Crédit(PaymLoc);';
    echo 'Adresse;Adresse Postale;Quartier;Ville;Île;Tél;Vini;Fax;Contact;Catégorie;Délai P.;Interdit;Employée<br>';
	}
	else
	{
      echo '<table border=1 cellpadding=2 cellspacing=2><tr><td><b>Numéro</b></td><td><b>Nom</b></td>';
      echo '<td><b>Contrat</b></td><td><b>Crédit(PaymLoc)</b></td>';
      echo '<td><b>Adresse</b></td><td><b>Adresse Postale</b></td><td><b>Quartier</b></td><td><b>Ville</b></td><td><b>Île</b></td><td><b>Tél</b></td><td><b>Vini</b></td><td><b>Fax</b></td><td><b>Contact</b></td><td><b>Catégorie</b></td><td><b>Délai P.</b></td><td><b>Interdit</b></td><td><b>Employée</b></td></tr>';
	}
  $total = 0; $totd = 0; $totc = 0;
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = mysql_fetch_array($result);
      $employeeid = $row['employeeid'];
      $employee = $employeeA[$employeeid];
      $blocked = $row['blocked'];
      if ($blocked == 0) { $blocked = '&nbsp;'; }
      elseif ($blocked == 1) { $blocked = 'Interdit'; }
      else { $blocked = 'Suspendu'; }
      
      $balance = 0; $showtext = '';
      $query2 = 'select reference,invoicehistory.invoiceid,matchingid,month(accountingdate) as month,invoiceprice,accountingdate from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $row['clientid'] . '" and reference like "%Contrat%" and year(accountingdate)="'.$lastyear.'" and quantity="' . $row['months'] . '" and invoicehistory.cancelledid=0 and matchingid>0';
      if ($row['months'] == 3) { $query2 = $query2 . ' and accountingdate>="'.$lastyear.'-10-01"'; }
      if ($row['months'] == 6) { $query2 = $query2 . ' and accountingdate>="'.$lastyear.'-07-01"'; }
      $result2 = mysql_query($query2, $db_conn); querycheck($result2);
      $num_results2 = mysql_num_rows($result2);
      $row2 = mysql_fetch_array($result2);
      if ($num_results2 > 0)
      {
        if ($debcred != 1) {
        $kladd = $row['months'] + $row2['month'] - 13;
        $balance = $kladd * $row2['invoiceprice'] / $row['months'];
        $totc = $totc + $balance;
        $showtext .= d_nolinebreak(d_output($row2['reference'])) . '<br>';
        #$balance = $balance . '<br><br>debug: ' . $num_results2;
        }
      }
      else
      {
        if ($debcred != 2) {
        $query2 = 'select reference,invoicehistory.invoiceid,matchingid,month(accountingdate) as month,invoiceprice,accountingdate from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $row['clientid'] . '" and reference like "%Contrat%" and year(accountingdate)="'.$lastyear.'" and quantity="' . $row['months'] . '" and invoicehistory.cancelledid=0 and matchingid=0';
        $result2 = mysql_query($query2, $db_conn); querycheck($result2);
        $num_results2 = mysql_num_rows($result2);
        $balance = 0;
        for ($y=0; $y < $num_results2; $y++)
        {
          $row2 = mysql_fetch_array($result2);
          $kladd = $row['months'] + $row2['month'] - 13; if ($kladd < 0) { $kladd = 0; }
          $balance = $balance - $row2['invoiceprice'];
          if ($kladd > 0) { $balance = $balance + ($kladd * $row2['invoiceprice'] / $row['months']); }
          $showtext .= d_nolinebreak(d_output($row2['reference'])) . '<br>';
        }
        $totd = $totd + $balance;
        #$balance = $balance . '<br><br>debug: ' . $num_results2;
        }
      }
      $total = $total + $balance;
      
      if ($balance != 0) {
      if ($_POST['ss'])
      {
        if ($blocked == '&nbsp;') { $blocked = ''; }
        echo $row['clientid'] . ';' . d_decode($row['clientname']) . ';';
        echo $showtext . ';';
        echo $balance . ';';
        echo $row['address'] . ';' . $row['postaladdress'] . ';' . $row['quarter'] . ';' . $row['townname'] . ';' . $row['islandname'] . ';' . $row['telephone'] . ';' . $row['cellphone'] . ';' . $row['fax'] . ';' . $row['contact'] . ';' . $row['clientcategoryname'] . ';' . $row['clienttermname'] . ';' . $blocked . ';' . $employee . '<br>';
      }
      else
      {
          echo '<tr><td align=right>' . $row['clientid'] . '</td><td>' . d_output(d_decode($row['clientname'])) . '</td>';
          echo '<td align=right>' . $showtext . '</td>';
          echo '<td align=right>' . myfix($balance) . '</td>';
          echo '<td>' . $row['address'] . '</td><td>' . $row['postaladdress'] . '</td><td>' . $row['quarter'] . '</td><td>' . $row['townname'] . '</td><td>' . $row['islandname'] . '</td><td>' . $row['telephone'] . '</td><td>' . $row['cellphone'] . '</td><td>' . $row['fax'] . '</td><td>' . $row['contact'] . '</td><td>' . $row['clientcategoryname'] . '</td><td>' . $row['clienttermname'] . '</td><td>' . $blocked . '</td><td>' . $employee . '</td></tr>';
      }
      }
    }
    if ($debcred != 2) { echo '<tr><td colspan=2><b>Total débit location</b></td><td align=right><b>'.myfix($totd).'</td><td colspan=20>&nbsp;</td></tr>'; }
    if ($debcred != 1) { echo '<tr><td colspan=2><b>Total crédit location</b></td><td align=right><b>'.myfix($totc).'</td><td colspan=20>&nbsp;</td></tr>'; }
    echo '<tr><td colspan=2><b>Total débit+crédit location</b></td><td align=right><b>'.myfix($total).'</td><td colspan=20>&nbsp;</td></tr>';
    echo '</table>';
break;






case 'locclientpay';

ini_set('max_execution_time', 600*2);

require('preload/employee.php');
require('preload/clientterm.php');
require('preload/clientcategory.php');
require('preload/island.php');

$PA['from'] = 'uint';
$PA['to'] = 'uint';
require('inc/readpost.php');

$lastyear = substr($_SESSION['ds_curdate'],0,4)-1;
$ourparams = '';

if ($_POST['clientcategoryid'] > 0)
{
  $query = 'select clientcategoryname from clientcategory where clientcategoryid="' . $_POST['clientcategoryid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $ourparams = $ourparams . '<p><b>Catégorie client: ' . $row['clientcategoryname'] . '</b></p>';
}
if ($_POST['clientcategory2id'] > 0)
{
  $query = 'select clientcategory2name from clientcategory2 where clientcategory2id="' . $_POST['clientcategory2id'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $ourparams = $ourparams . '<p><b>Catégorie 2 client: ' . $row['clientcategory2name'] . '</b></p>';
}
if ($_POST['islandid'] > 0)
{
  $query = 'select islandname from island where islandid="' . $_POST['islandid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $ourparams = $ourparams . '<p><b>Île: ' . $row['islandname'] . '</b></p>';
}
if ($_POST['employeeid'] > 0)
{
  $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $_POST['employeeid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $ourparams = $ourparams . '<p><b>Employee ' . $_SESSION['ds_term_clientemployee1'] . ': ' . $row['employeename'] . '</b></p>';
}
if ($_POST['employeeid2'] > 0)
{
  $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $_POST['employeeid2'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row = $query_result[0];
  $ourparams = $ourparams . '<p><b>Employee ' . $_SESSION['ds_term_clientemployee2'] . ': ' . $row['employeename'] . '</b></p>';
}
if ($_POST['interdit'] == 1) { $ourparams = $ourparams . '<p><b>Clients interdits, comptes non fermés</b></p>'; }
if ($_POST['interdit'] == 2) { $ourparams = $ourparams . '<p><b>Clients non interdits, comptes non fermés</b></p>'; }
if ($_POST['interdit'] == 3) { $ourparams = $ourparams . '<p><b>Comptes fermés</b></p>'; }
if ($from > 0)
{ $ourparams .= '<p><b>De '.$from.' à '.$to.'</b></p>'; }


if ($ourparams == '') { $ourparams = '<br>'; }

$query_prm = array();
$query = 'select reference,months,rentalid,client.clientid,clientname,address,postaladdress,client.quarter,townname,telephone,cellphone,fax,contact,islandid,clientcategoryid,clienttermid,client.employeeid
from vmt_rental,client,town
where vmt_rental.clientid=client.clientid and client.townid=town.townid and vmt_rental.deleted=0';
if ($_POST['clientcategoryid'] > 0) { $query = $query . ' and client.clientcategoryid=' . $_POST['clientcategoryid']; }
if ($_POST['clientcategory2id'] > 0) { $query = $query . ' and client.clientcategory2id=' . $_POST['clientcategory2id']; }
if ($_POST['islandid'] > 0) { $query = $query . ' and town.islandid="' . $_POST['islandid'] . '"'; }
if ($_POST['employeeid'] > 0) { $query = $query . ' and client.employeeid="' . $_POST['employeeid'] . '"'; }
if ($_POST['employeeid2'] > 0) { $query = $query . ' and client.employeeid2="' . $_POST['employeeid2'] . '"'; }
if ($_POST['interdit'] == 1) { $query = $query . ' and client.blocked>0 and client.deleted=0'; }
if ($_POST['interdit'] == 2) { $query = $query . ' and client.blocked<1 and client.deleted=0'; }
if ($_POST['interdit'] == 3) { $query = $query . ' and client.deleted=1'; }
$query = $query . ' and months>1';
if ($from > 0)
{
  $query .= ' and rentalid>=? and rentalid<=?';
  array_push($query_prm,$from);
  array_push($query_prm,$to);
}
require('inc/doquery.php');
$query_result_main = $query_result; $num_results_main = $num_results;

echo '<h2>Clients location >1mois ' . $lastyear .'</h2>' . $ourparams;
echo '<table class=report><tr><td><b>Client</b></td><td><b>Facture</b></td><td><b>Date facture</b></td>';
echo '<td><b>Contrat</b></td><td><b>Débit</b></td><td><b>Crédit</b></td>';
echo '<td><b>Adresse</b></td><td><b>Adresse Postale</b></td><td><b>Quartier</b></td><td><b>Ville</b></td><td><b>Île</b></td><td><b>Tél</b></td><td><b>Vini</b></td><td><b>Fax</b></td><td><b>Contact</b></td><td><b>Catégorie</b></td><td><b>Délai P.</b></td><td><b>Employée</b></td></tr>';

$totd = 0; $totc = 0;
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $query_result_main[$i];
  $query = 'select accountingdate,reference,invoicehistory.invoiceid,matchingid,month(accountingdate) as month,invoiceprice,accountingdate
  from invoicehistory,invoiceitemhistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
  and clientid="' . $row['clientid'] . '" and reference like "%Contrat '.$row['reference'].'%" and year(accountingdate)="'.$lastyear.'"
  and quantity="' . $row['months'] . '" and invoicehistory.cancelledid=0';
/*$query = 'select accountingdate,reference,invoicehistory.invoiceid,matchingid,month(accountingdate) as month,invoiceprice,accountingdate
  from invoicehistory,invoiceitemhistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
  and clientid="' . $row['clientid'] . '" and reference like "%Contrat '.$row['reference'].'%" and year(accountingdate)<"'.$lastyear.'"
  and quantity="' . $row['months'] . '" and invoicehistory.cancelledid=0 and invoicehistory.matchingid=0';
  */
  $query_prm = array();
  require('inc/doquery.php');
  for ($y=0; $y < $num_results; $y++)
  {
    $row2 = $query_result[$y];
    $ok = 1;
    
    ###
    $balance = 0;
    $kladd = $row['months'] + $row2['month'] - 13;
    $balance = myround($kladd * $row2['invoiceprice'] / $row['months']);
    if ($balance == 0 || ($balance < 0 && $row2['matchingid'] > 0)) { $ok = 0; }
    if ($row2['matchingid'] == 0) { $balance = $row2['invoiceprice'] - $balance; }
    ###
    
    if ($ok)
    {
      $showline = '<tr><td>' . d_nolinebreak(d_output(d_decode($row['clientname'])) . ' (' . $row['clientid'] . ')') . '</td>';
      $showline .= '<td align=right>' . $row2['invoiceid'] . '</td><td align=right>' . d_nolinebreak(datefix($row2['accountingdate'])) . '</td>';
      $showline .= '<td align=right>' . d_nolinebreak(d_output($row2['reference'])) . '</td>'; #' invp='.($row2['invoiceprice']+0).
      if ($row2['matchingid'] > 0) { $showline .= '<td></td><td align=right>' . d_nolinebreak(myfix($balance)) . '</td>'; }
      else { $showline .= '<td align=right>' . d_nolinebreak(myfix($balance)) . '</td><td></td>'; }
      $showline .= '<td>' . d_nolinebreak($row['address']) . '</td><td>' . d_nolinebreak($row['postaladdress']) . '</td><td>' . d_nolinebreak($row['quarter']) . '</td><td>' . d_nolinebreak($row['townname']) . '</td>
      <td>' . d_nolinebreak($islandA[$row['islandid']]) . '</td><td>' . d_nolinebreak($row['telephone']) . '</td><td>' . d_nolinebreak($row['cellphone']) . '</td><td>' . d_nolinebreak($row['fax']) . '</td>
      <td>' . d_nolinebreak($row['contact']) . '</td>
      <td>' . d_nolinebreak($clientcategoryA[$row['clientcategoryid']]) . '</td>
      <td>' . d_nolinebreak($clienttermA[$row['clienttermid']]);
      echo '<td>'; if ($row['employeeid']>0) { echo d_nolinebreak($employeeA[$row['employeeid']]); }
      echo $showline;
      if ($row2['matchingid'] == 0) { $totd += $balance; }
      else { $totc += $balance; }
    }
  }
}
echo '<tr><td colspan=4><b>Total débit location</b></td><td align=right><b>'.myfix($totd).'</td><td></td><td colspan=20>&nbsp;</td></tr>';
echo '<tr><td colspan=4><b>Total crédit location</b></td><td></td><td align=right><b>'.myfix($totc).'</td><td colspan=20>&nbsp;</td></tr>';
echo '</table>';
break;





  case 'instreport':
  
  $datename = 'instdate';
  require('inc/datepickerresult.php');
  $startdate = $instdate;
  $datename = 'instdate2';
  require('inc/datepickerresult.php');
  $stopdate = $instdate2;
  echo '<h2>Rapport installations '.datefix2($startdate).' à '.datefix2($stopdate).'</h2><br>';
  $query = 'select fountainname,vmt_inst.clientid,clientname,rental_reference,concat(employeename," ",employeefirstname) as employeename,instdate from vmt_inst,vmt_fountain,client,employee where vmt_inst.clientid=client.clientid and vmt_inst.fountainid=vmt_fountain.fountainid and vmt_inst.employeeid=employee.employeeid';
  $query = $query .' and instdate>="' . $startdate . '" and instdate<="' . $stopdate . '"';
  $query = $query . ' order by instdate';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  echo '<table class="report" border=1 cellspacing=1 cellpadding=1><tr><td><b>Commercial</td><td><b>Client</td><td><b>Contrat</td><td><b>Fontaine</td><td><b>Date</td></tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    echo '<tr><td>' . $row['employeename'] . '</td><td>' . $row['clientid'] . ': ' . $row['clientname'] . '</td><td>' . $row['rental_reference'] . '</td><td>' . $row['fountainname'] . '</td><td>' . datefix2($row['instdate']) . '</td></tr>';
  }
  echo '</table>';
  break;

  case 'listloc':
  require('preload/clientcategory.php');
  $htext = '';
  $frigo = (int) $_POST['frigo'];
  
  if ($_POST['filter']==5)
  {
    $startdate = d_builddate($_POST['startday2'],$_POST['startmonth2'],$_POST['startyear2']);
    $stopdate = d_builddate($_POST['stopday2'],$_POST['stopmonth2'],$_POST['stopyear2']);
    $htext = datefix($startdate) . ' au ' . datefix($stopdate);
    echo '<h2>Prélèvements refoulés ' . $htext . '</h2><br>';
    $query = 'select paymentid,value,payment.clientid,clientname,paymentdate,paymentcomment from payment,client where payment.clientid=client.clientid and paymentcomment like "%Prélèvement refoulé%"';
    $query = $query . ' and paymentdate>="' . $startdate . '" and paymentdate<="' . $stopdate . '"';
    if ($_POST['clientcategoryid'] > 0) { $query = $query . ' and client.clientcategoryid="' . $_POST['clientcategoryid'] . '"'; }
    $query = $query . ' and matchingid=0';
    if ($frigo > 0) { $query .= ' and frigo='.$frigo; }
    if ($_POST['clientcategoryid'] > 0) { $query = $query . ' order by clientname,paymentdate'; }
    else { $query = $query . ' order by paymentdate'; }
    $query_prm = array();
    require('inc/doquery.php');
    $total = 0;
    echo '<table class="report"><tr><td><b>No Refoulement</b></td><td><b>Client</b></td><td><b>Date</b></td><td><b>Valeur</b></td><td><b>Infos</b></td></tr>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      echo '<tr><td align=right>' . $row['paymentid'] . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td>' . datefix2($row['paymentdate']) . '</td><td align=right>' . myfix($row['value']) . '</td><td>' . $row['paymentcomment'] . '</td></tr>';
    }
    echo '<tr><td><b>Total (' . $num_results . ')</td><td>&nbsp;</td><td colspan=5>&nbsp;</td></tr>';
    echo '</table>';
  }
  else
  {
    unset($resilmotifnameA);
    $query = 'select resilmotifid,resilmotifname from vmt_resilmotif order by resilmotifname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0;$i<$num_results;$i++)
    {
      $resilmotifid = (int) ($query_result[$i]['resilmotifid']+0);
      $resilmotifnameA[$resilmotifid] = $query_result[$i]['resilmotifname'];
    }
  
    $query = 'select clientcategoryid,month(contractdate) as month,year(contractdate) as year,vmt_rental.rentalid,rentaldate,contractdate,rentalprice,reference,vmt_rental.clientid,clientname,months,lastcreatedate,vmt_rental.deleted,deleteddate,address,resilmotifid from vmt_rental,client';
    $query = $query . ' where vmt_rental.clientid=client.clientid';
    if ($_POST['filter']==6)
    {
      $datename = 'starttotal';
      require('inc/datepickerresult.php');
      $datename = 'stoptotal';
      require('inc/datepickerresult.php');
      $query = $query . ' and contractdate>="' . $starttotal . '" and contractdate<="' . $stoptotal . '"';
      $htext = 'Total nouveaux contrats année ' . datefix2($starttotal) . ' au ' . datefix2($stoptotal);
    }
    if ($_POST['filter']==2)
    {
      $month = mb_substr($_SESSION['ds_curdate'],5,2)+0;
      $year = mb_substr($_SESSION['ds_curdate'],0,4)-5;
      $query = $query . ' and DATE_FORMAT(contractdate,"%Y")="' . $year . '" and DATE_FORMAT(contractdate,"%c")="' . $month . '"';
      $htext = '"Dans le mois" 5 ans';
    }
    if ($_POST['filter']==3)
    {
      $startdate = d_builddate($_POST['startday'],$_POST['startmonth'],$_POST['startyear']);
      $stopdate = d_builddate($_POST['stopday'],$_POST['stopmonth'],$_POST['stopyear']);
      $query = $query . ' and vmt_rental.deleted=1 and deleteddate>="' . $startdate . '" and deleteddate<="' . $stopdate . '"';
      $htext = ' resiliés ' . datefix($startdate) . ' au ' . datefix($stopdate);
    }
    else { $query = $query . ' and vmt_rental.deleted=0'; }
    if ($frigo > 0) { $query .= ' and frigo='.$frigo; }
    if ($_POST['clientcategoryid'] > 0) { $query = $query . ' and client.clientcategoryid="' . $_POST['clientcategoryid'] . '"'; }
    if ($_POST['filter']==4)
    {
      if ($_POST['employeeid'] > 0) { $query = $query . ' and client.employeeid2="'.$_POST['employeeid'].'"'; }
      $query = $query . ' and (months=1';
      $ok1 = $_POST['month'];
      $ok2 = $_POST['month'] + 3; if ($ok2 > 12) { $ok2 = $ok2 - 12; }
      $ok3 = $_POST['month'] + 6; if ($ok3 > 12) { $ok3 = $ok3 - 12; }
      $ok4 = $_POST['month'] + 9; if ($ok4 > 12) { $ok4 = $ok4 - 12; }
      $query = $query . ' or (months=3 and (month(rentaldate)="' . $ok1 . '" or month(rentaldate)="' . $ok2 . '" or month(rentaldate)="' . $ok3 . '" or month(rentaldate)="' . $ok4 . '"))';
      $ok1 = $_POST['month'];
      $ok2 = $_POST['month'] + 6; if ($ok2 > 12) { $ok2 = $ok2 - 12; }
      $query = $query . ' or (months=6 and (month(rentaldate)="' . $ok1 . '" or month(rentaldate)="' . $ok2 . '"))';
      $ok1 = $_POST['month'];
      $query = $query . ' or (months=12 and month(rentaldate)="' . $ok1 . '")';
      $query = $query . ')';
      $testdate = d_builddate('1',$_POST['month'],$_POST['year']);
      $query = $query . ' and rentaldate<="' . $testdate . '"';
      $htext = ' facturés ' . $_POST['month'] . '/' . $_POST['year'];
    }
    if ($_POST['onlyprelev'] == 1) { $query = $query . ' and months=1 and noprelev=0'; }
    #if ($_POST['onlynonmatched'] == 1) { $query = $query . ' and matchingid=0'; }
    elseif ($_POST['clientcategoryid'] > 0 || $_POST['byclientname'] == 1 || $_POST['filter']==4) { $query = $query . ' order by clientname,clientid,contractdate,reference'; }
    else { $query = $query . ' order by contractdate,reference'; }
    $query_prm = array();
    require('inc/doquery.php');
    echo '<h2>Locations ' . $htext . '</h2>';
    if ($frigo == 0) { echo '<p>Frigo exclu</p>'; }
    elseif ($frigo == 1) { echo '<p>Uniquement frigo</p>'; }
    $total = 0; $lastclientname = ''; $sameclient = 0; $clienttotal = 0; $subtotal = 0; $lastyear = 0; $lastmonth = 0; $totalttc = 0;
    echo '<table  class="report"><tr><td><b>Reference</b></td><td><b>Client</b></td><td><b>Catégorie</b></td><td><b>Adresse</b></td><td><b>Prix Mensuel</b></td><td><b>TTC</b></td><td><b>Date contrat</b></td><td><b>Début</b></td><td><b>Periodicité</b></td><td><b>Fontaines</b></td><td><b>Dèrniere Facture</b></td><td><b>Date résiliation</b></td><td><b>Motif résiliation</b></td></tr>';
    $main_result = $query_result; $num_results_main = $num_results;
    for ($i=0; $i < $num_results_main; $i++)
    {
      $row = $main_result[$i];
      if (($_POST['clientcategoryid'] > 0 || $_POST['filter'] == 4) && $sameclient && $lastclientname != $row['clientname'])
      {
        echo '<tr><td>&nbsp;</td><td><i>Total ' . $lastclientname . '</td>';
        if ($_POST['filter'] == 4) { echo '<td colspan=2>&nbsp;</td>'; }
        echo '<td align=right><i>' . myfix($clienttotal) . '</td><td colspan=6>&nbsp;</td></tr>'; $sameclient = 0; $clienttotal = 0;
      }
      if ($_POST['filter']==6 && $i > 0 && $row['month'] != $lastmonth)
      {
        echo '<tr><td colspan=3><b>Total '.$lastmonth.'/'.$lastyear.'</td><td align=right><b>'.myfix($subtotal).'</td><td colspan=8>&nbsp;</td></tr>';
        $subtotal = 0;
      }
      $lastcd = datefix2($row['lastcreatedate']); if ($row['lastcreatedate'] < "2000-01-01") { $lastcd = '&nbsp;'; }
      $deleteddate = datefix2($row['deleteddate']); if ($row['deleteddate'] < "2000-01-01") { $deleteddate = '&nbsp;'; }
      $query = 'select fountainname from vmt_fountain where rentalid="' . $row['rentalid'] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $fountains = "";
      for ($x=0; $x < $num_results; $x++)
      {
        $row2 = $query_result[$x];
        $fountains = $fountains . $row2['fountainname'] . ' ';
      }
      $resilmotifid = $row['resilmotifid'];
      if ($row['deleted'] == 0) { $deleteddate = '&nbsp;'; }
      echo '<tr><td>' . $row['reference'] . '</td><td>' . $row['clientid'] . ': ' . d_output(d_decode($row['clientname'])) . '</td><td>' . $clientcategoryA[$row['clientcategoryid']] . '</td><td>' . d_output($row['address']) . '</td><td align=right>' . myfix($row['rentalprice']) . '</td><td align=right>' . myfix($row['rentalprice']*1.1) . '</td><td>' . datefix2($row['contractdate']) . '</td><td>' . datefix2($row['rentaldate']) . '</td><td>' . $row['months'] . ' mois</td><td>' . $fountains . '</td><td>' . $lastcd . '</td><td>' . $deleteddate . '</td><td>' . $resilmotifnameA[$resilmotifid] . '</td></tr>';
      $total = $total + $row['rentalprice'];
      if ($_POST['filter'] == 4) { $clienttotal = $clienttotal + $row['rentalprice']*1.1; }
      else { $clienttotal = $clienttotal + $row['rentalprice']; }
      $totalttc = $totalttc + round($row['rentalprice']*1.1);
      if ($lastclientname == $row['clientname']) { $sameclient = 1; }
      else { $clienttotal = $row['rentalprice']; }
      $lastclientname = $row['clientname'];
      if ($_POST['filter']==6) { $lastmonth = $row['month']; $lastyear = $row['year']; $subtotal = $subtotal + $row['rentalprice']; }
    }
    if ($_POST['clientcategoryid'] > 0 && $sameclient) { echo '<tr><td>&nbsp;</td><td><i>Total ' . $lastclientname . '</td><td align=right><i>' . myfix($clienttotal) . '</td><td colspan=6>&nbsp;</td></tr>'; $sameclient = 0; $clienttotal = 0; }
    if ($_POST['filter']==6 && $i > 0)
    {
      echo '<tr><td colspan=3><b>Total '.$lastmonth.'/'.$lastyear.'</td><td align=right><b>'.myfix($subtotal).'</td><td colspan=8>&nbsp;</td></tr>';
    }
    echo '<tr><td><b>Total (' . $num_results_main . ')</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align=right><b>' . myfix($total) . '</td><td align=right><b>' . myfix($totalttc) . '</td><td colspan=7>&nbsp;</td></tr>';
    echo '</table>';
  }
  break;
  
  
  
  

  ### showfr ###
  case 'showfr':
  echo '<style>
  table {
    white-space: normal;
  }
  </style>';
  $showbalance = 0; $_POST['showbalance'] = 0;
  $linesperpage = $_POST['linesperpage'];
  $switch_oddeven = $_POST['switch_oddeven'];
  $day = $_POST['day']; $month = $_POST['month']; $year = $_POST['year']; $employeeid = $_POST['employeeid']; $frtype = $_POST['frtype'];
  if (isset($_POST['compterendu'])) { $compterendu = $_POST['compterendu']; }
  else { $compterendu = 0; }
  $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $employeeid . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $employeename = $query_result[0]['employeename'];
  $ourdate = d_builddate($day,$month,$year);
  
  $day = mb_substr($ourdate,8,2);
  # find day of week
  $ourmktime = mktime(0,0,0,$month,$day,$year);
  $ourdate = d_builddate($day,$month,$year);
  $ourday = date("w",$ourmktime);
  $weeknumber = date("W",$ourmktime);
  $odd = 0; $even = 0;
  if ($weeknumber % 2) { $odd = 1; }
  else { $even = 1; }
  $monthlyweek = ($weeknumber % 4)+0;
  if ($compterendu != 1 && $frtype == 0)
  {
    # read prices
$productid = 10; # hardcode
    $query = 'select salesprice,detailsalesprice,taxcode from product,taxcode where product.taxcodeid=taxcode.taxcodeid and productid=10'; # hardcode
    $query_prm = array();
    require('inc/doquery.php');
    $row = $query_result[0];
    $sp = myround($row['salesprice']);
    $dsp = myround($row['detailsalesprice']);
    $taxcode = $row['taxcode'];
  }
# counts
$totalclients = 0; $totalamount = 0;
$query = 'select vmt_delivery.clientid,quantity,daytype from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.extraaddressid=0 and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $ok = 1;
  if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
  if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
  if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
  if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
  if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
  if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
  if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
  if ($ok)
  {
    $totalclients++;
    $totalamount = $totalamount + $row['quantity'];
  }
}
$query = 'select vmt_delivery.clientid,quantity,daytype from vmt_delivery,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and periodic=1 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($switch_oddeven)
  {
    if ($row['daytype'] == 2) { $row['daytype'] = 3; }
    elseif ($row['daytype'] == 3) { $row['daytype'] = 2; }
  }
  $ok = 1;
  if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
  if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
  if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
  if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
  if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
  if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
  if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
  if ($ok)
  {
    $totalclients++;
    $totalamount = $totalamount + $row['quantity'];
  }
}

$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.extraaddressid=0 and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and periodic=0 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$query_prm = array();
require('inc/doquery.php');
$row = $query_result[0];
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
# end counts

$totalcost = 0;

  # periodic with no extraaddressid
  $query = 'select clientcategoryid,clientsectorrank,extraaddressid,rentalid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,client.clientcategoryid,clientname,frtype,telephone,cellphone,quantity,client.quarter,address,contact,day,daytype,blocked,islandid from vmt_delivery,client,town,clientsector where client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid and client.townid=town.townid';
  $query = $query . ' and extraaddressid=0 and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by address,islandid,townrank,clientname'; # Vaimato wants it ordered by the address field directly
  $query_prm = array();
  require('inc/doquery.php');
  $num_resultsX = $num_results; $x_result = $query_result;
  if ($frtype == 0) { $frtypename = 'Bonbonnes'; }
  if ($frtype == 1) { $frtypename = 'Pack'; }
  if ($frtype == 2) { $frtypename = ''; }
  $ourtitle = 'FEUILLE DE ROUTE ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename;
  if ($compterendu == 1) { $ourtitle = 'COMPTE RENDU ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename; }
  showtitle($ourtitle);
  echo '<h2>' . $ourtitle . '</h2>';
  echo '<p>Nombre de clients: ' . $totalclients . ' &nbsp; &nbsp; &nbsp; Nombre ' . $frtypename . ': ' . $totalamount . '</p>';
  $counter =  0;
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = $x_result[$i];
    if ($switch_oddeven)
    {
      if ($row['daytype'] == 2) { $row['daytype'] = 3; }
      elseif ($row['daytype'] == 3) { $row['daytype'] = 2; }
    }
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
    }
    $ok = 1;
    if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
    if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
    if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
    if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
    if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
    if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
    if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
    if ($ok)
    {
      $totalcost = $totalcost + $price;
        if ($row['day'] == 1) { $day = 'Lundi'; }
        if ($row['day'] == 2) { $day = 'Mardi'; }
        if ($row['day'] == 3) { $day = 'Mercredi'; }
        if ($row['day'] == 4) { $day = 'Jeudi'; }
        if ($row['day'] == 5) { $day = 'Vendredi'; }
        if ($row['daytype'] == 1) { $day = $day . ' Tous'; }
        if ($row['daytype'] == 2) { $day = $day . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $day = $day . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $day = $day . ' Premier du mois'; }
        if ($row['daytype'] == 6) { $day = $day . ' Mensuel (1)'; }
        if ($row['daytype'] == 7) { $day = $day . ' Mensuel (2)'; }
        if ($row['daytype'] == 8) { $day = $day . ' Mensuel (3)'; }
        if ($row['daytype'] == 9) { $day = $day . ' Mensuel (4)'; }

      $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
      if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $query = 'select address,townname from extraaddress,town,telephone where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0)
        {
          $rowEA = $query_result[0];
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
      if ($compterendu == 1)
      {
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      }
      else
      {
        $reference = $row['reference'];
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>' . $day . '</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
      }
      $linerank[$counter] = $row['clientsectorrank'];
      $counter++;
    }
  }
  
  # periodic extraaddress
  $query = 'select clientcategoryid,clientsectorrank,vmt_delivery.extraaddressid,rentalid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,client.clientcategoryid,clientname,frtype,cellphone,quantity,client.quarter,contact,day,daytype,blocked,islandid from vmt_delivery,client,town,clientsector,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid and client.townid=town.townid';
  $query = $query . ' and periodic=1 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by extraaddress.address,islandid,townrank,clientname'; # Vaimato wants it ordered by the address field directly
  $query_prm = array();
  require('inc/doquery.php');
  $num_resultsX = $num_results; $x_result = $query_result;
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = $x_result[$i];
    if ($switch_oddeven)
    {
      if ($row['daytype'] == 2) { $row['daytype'] = 3; }
      elseif ($row['daytype'] == 3) { $row['daytype'] = 2; }
    }
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
    }
    $ok = 1;
    if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
    if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
    if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
    if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
    if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
    if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
    if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
    if ($ok)
    {
      $totalcost = $totalcost + $price;
        if ($row['day'] == 1) { $day = 'Lundi'; }
        if ($row['day'] == 2) { $day = 'Mardi'; }
        if ($row['day'] == 3) { $day = 'Mercredi'; }
        if ($row['day'] == 4) { $day = 'Jeudi'; }
        if ($row['day'] == 5) { $day = 'Vendredi'; }
        if ($row['daytype'] == 1) { $day = $day . ' Tous'; }
        if ($row['daytype'] == 2) { $day = $day . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $day = $day . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $day = $day . ' Premier du mois'; }
        if ($row['daytype'] == 6) { $day = $day . ' Mensuel (1)'; }
        if ($row['daytype'] == 7) { $day = $day . ' Mensuel (2)'; }
        if ($row['daytype'] == 8) { $day = $day . ' Mensuel (3)'; }
        if ($row['daytype'] == 9) { $day = $day . ' Mensuel (4)'; }

      $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
      if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $query = 'select address,townname,telephone from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0)
        {
          $rowEA = $query_result[0];
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
      if ($compterendu == 1)
      {
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      }
      else
      {
        $reference = $row['reference'];
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>' . $day . '</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
      }
      $linerank[$counter] = $row['clientsectorrank'];
      $counter++;
    }
  }
  
  # non periodic with no extraaddressid
  $query = 'select clientcategoryid,clientsectorrank,extraaddressid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,clientname,frtype,telephone,cellphone,quantity,client.quarter,address,contact,daytype,blocked from vmt_delivery,client,clientsector,town where client.townid=town.townid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid';
  $query = $query . ' and extraaddressid=0 and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by clientname';
  $query_prm = array();
  require('inc/doquery.php');
  $num_resultsX = $num_results; $x_result = $query_result;
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = $x_result[$i];
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
        $totalcost = $totalcost + $price;
    }
    $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
    if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      if ($row['extraaddressid'] > 0)
      {
        $query = 'select address,townname from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0)
        {
          $rowEA = $query_result[0];
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
        }
      }
    if ($compterendu == 1)
    {
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }
    else
    {
      $reference = $row['reference'];
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>-</td><td>' . $row['telephone'] . '/' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
    }
    $linerank[$counter] = $row['clientsectorrank'];
    $counter++;
  }
  
  # non periodic extraaddress
  $query = 'select clientcategoryid,clientsectorrank,vmt_delivery.extraaddressid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,clientname,frtype,cellphone,quantity,client.quarter,contact,daytype,blocked from vmt_delivery,client,clientsector,town,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and client.townid=town.townid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid';
  $query = $query . ' and periodic=0 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by clientname';
  $query_prm = array();
  require('inc/doquery.php');
  $num_resultsX = $num_results; $x_result = $query_result;
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = $x_result[$i];
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '" and deleted=0';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_results > 0) { $price = $query_result[0]['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
        $totalcost = $totalcost + $price;
    }
    $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
        if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $query = 'select address,townname,telephone from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $query_prm = array();
        require('inc/doquery.php');
        if ($num_resultsEA > 0)
        {
          $rowEA = $query_result[0];
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
    if ($compterendu == 1)
    {
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }
    else 
    {
      $reference = $row['reference'];
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>-</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
    }
    $linerank[$counter] = $row['clientsectorrank'];
    $counter++;
  }

  # sort tables HERE
  for ($i=0; $i < $counter; $i++) 
  {
#    echo $i . ' ' . $linerank[$i] . '<br>';
    $alreadysorted[$i] = 0;
  }
  
  # find lowest rank
  for ($x=0; $x < $counter; $x++) 
  {
    $lowestfound = 9999999;
    for ($i=0; $i < $counter; $i++) 
    {
      if ($alreadysorted[$i] != 1 && $linerank[$i] < $lowestfound) { $lowestfound = $linerank[$i]; $lowestindex = $i; }
    }
    $newindex[$x] = $lowestindex;
    $alreadysorted[$lowestindex] = 1;
  }

#echo '<br>';
#for ($i=0; $i < $counter; $i++) 
#{
#  $x = $newindex[$i];
#  echo $x . ' ' . $linerank[$x] . '<br>';
#}

  # display after sort
  echo '<table class="report" border=1 cellpadding=5 cellspacing=5 width=1200>';
  if ($compterendu == 1)
  {
    echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
    echo '<tr><td><b>Client';
    if ($showbalance) { echo '&nbsp;(Débit)'; }
    echo '</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
  }
  else 
  {
    echo '<tr><td><b>Client';
    if ($showbalance) { echo '&nbsp;(Débit)'; }
    echo '</b></td><td><b>Adresse</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>';
  }
  for ($i=0; $i < $counter; $i++)
  {
    $x = $newindex[$i];
#echo '<tr><td>' . $linerank[$x] .'</td></tr>'; # debug
    echo $lineshow[$x];
    $testvar = $i + 1;
    if ($testvar%$linesperpage==0 && $testvar != $counter)
    {
      echo '</table><P class="breakhere"></p><table class="report" border=1 cellpadding=5 cellspacing=5 width=1200>';
      if ($compterendu == 1)
      {
        echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
        echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
      }
      else { echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
    }
  }
  if ($compterendu != 1) { echo '<tr><td><b>Total</td><td colspan=3>&nbsp;</td><td align=right>' . myfix($totalamount) . '</td><td align=right>' . myfix($totalcost) . '</td><td>&nbsp;</td></tr>'; }
  echo '</table>';
  break;


  case 'fonhis':
  require('preload/employee.php');
  
  $query = 'select fountainid,fountainname from vmt_fountain where fountainname="' . $_POST['fountainname'] . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  if (mysql_num_rows($result) == 0)
  {
    echo "Fontaine " . $_POST['fountainname'] . " n'existe pas.";
    break;
  }
  $row = mysql_fetch_array($result);
  $fountainid = $row['fountainid'];
  echo '<h2>Historique fontaine: ' . $_POST['fountainname'] . '</h2>';
  
  echo '<table border=1 cellspacing=1 cellpadding=1><tr><td><b>Date</td><td><b>Etat</td><td><b>Client</td><td><b>Contrat</td><td><b>Utilisateur</td><td><b>Employé</td></tr>';
  $query = 'select fonhisdate,fountaindescname as etat,clientid,rentalid,initials,employeeid from vmt_fonhis,vmt_fountaindesc,usertable where vmt_fonhis.userid=usertable.userid and vmt_fonhis.fountaindescid=vmt_fountaindesc.fountaindescid and fountainid="' . $fountainid . '" order by fonhisdate desc';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $num_results = mysql_num_rows($result);
  for ($i=0; $i < $num_results; $i++)
  {
    $row = mysql_fetch_array($result);
    $clientname = '&nbsp;'; $reference = '&nbsp;';
    if ($row['clientid'] > 0)
    {
      $query2 = 'select clientname from client where clientid="' . $row['clientid'] . '"';
      $result2 = mysql_query($query2, $db_conn); querycheck($result2);
      $row2 = mysql_fetch_array($result2);
      $clientname = $row['clientid'] . ': ' . $row2['clientname'];
    }
    if ($row['rentalid'] > 0)
    {
      $query2 = 'select reference from vmt_rental where rentalid="' . $row['rentalid'] . '"';
      $result2 = mysql_query($query2, $db_conn); querycheck($result2);
      $row2 = mysql_fetch_array($result2);
      $reference = $row2['reference'];
    }
    echo '<tr><td>' . datefix2($row['fonhisdate']) . '</td><td>' . $row['etat'] . '</td><td>' . $clientname . '</td><td>' . $reference . '</td><td>' . $row['initials'] . '</td>';
    $empid = $row['employeeid'];
    if ($empid > 0) { echo '<td>' . $employeeA[$empid] . '</td>'; }
    else { echo '<td>&nbsp;</td>'; }
    echo '</tr>';
  }
  echo '</table>';
  break;

  
  
  
  
  case 'showfrOLD':
  echo '<style>
  table {
    white-space: normal;
  }
  </style>';
  $showbalance = (int) $_POST['showbalance'];
  
  $linesperpage = $_POST['linesperpage'];
  $day = $_POST['day']; $month = $_POST['month']; $year = $_POST['year']; $employeeid = $_POST['employeeid']; $frtype = $_POST['frtype'];
  $compterendu = $_POST['compterendu'];
  $query = 'select concat(employeename," ",employeefirstname) as employeename from employee where employeeid="' . $employeeid . '"';
  $result = mysql_query($query, $db_conn); querycheck($result);
  $row = mysql_fetch_array($result);
  $employeename = $row['employeename'];
  $ourdate = d_builddate($day,$month,$year);
  
  $day = mb_substr($ourdate,8,2);
  # find day of week
  $ourmktime = mktime(0,0,0,$month,$day,$year);
  $ourdate = d_builddate($day,$month,$year);
  $ourday = date("w",$ourmktime);
  $weeknumber = date("W",$ourmktime);
  $odd = 0; $even = 0;
  if ($weeknumber % 2) { $odd = 1; }
  else { $even = 1; }
  $monthlyweek = ($weeknumber % 4)+0;
  if ($compterendu != 1 && $frtype == 0)
  {
    # read prices
$productid = 10; # hardcode
    $query = 'select salesprice,detailsalesprice,taxcode from product,taxcode where product.taxcodeid=taxcode.taxcodeid and productid=10'; # hardcode
    $result = mysql_query($query, $db_conn); querycheck($result);
    $row = mysql_fetch_array($result);
    $sp = myround($row['salesprice']);
    $dsp = myround($row['detailsalesprice']);
    $taxcode = $row['taxcode'];
  }
# counts
$totalclients = 0; $totalamount = 0;
$query = 'select vmt_delivery.clientid,quantity,daytype from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.extraaddressid=0 and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=0; $i < $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $ok = 1;
  if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
  if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
  if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
  if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
  if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
  if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
  if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
  if ($ok)
  {
    $totalclients++;
    $totalamount = $totalamount + $row['quantity'];
  }
}
$query = 'select vmt_delivery.clientid,quantity,daytype from vmt_delivery,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and periodic=1 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$result = mysql_query($query, $db_conn); querycheck($result);
$num_results = mysql_num_rows($result);
for ($i=0; $i < $num_results; $i++)
{
  $row = mysql_fetch_array($result);
  $ok = 1;
  if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
  if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
  if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
  if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
  if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
  if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
  if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
  if ($ok)
  {
    $totalclients++;
    $totalamount = $totalamount + $row['quantity'];
  }
}

$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,client where vmt_delivery.clientid=client.clientid and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.extraaddressid=0 and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$result = mysql_query($query, $db_conn); querycheck($result);
$row = mysql_fetch_array($result);
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
$query = 'select count(vmt_delivery.clientid) as amountclient,sum(quantity) as quantitytotal from vmt_delivery,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and periodic=0 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
$query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
$result = mysql_query($query, $db_conn); querycheck($result);
$row = mysql_fetch_array($result);
$totalclients = $totalclients + $row['amountclient'];
$totalamount = $totalamount + $row['quantitytotal'];
# end counts

$totalcost = 0;

  # periodic with no extraaddressid
  $query = 'select clientcategoryid,clientsectorrank,extraaddressid,rentalid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,client.clientcategoryid,clientname,frtype,telephone,cellphone,quantity,client.quarter,address,contact,day,daytype,blocked,islandid from vmt_delivery,client,town,clientsector where client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid and client.townid=town.townid';
  $query = $query . ' and extraaddressid=0 and periodic=1 and client.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by address,islandid,townrank,clientname'; # Vaimato wants it ordered by the address field directly
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsX = mysql_num_rows($resultX);
  if ($frtype == 0) { $frtypename = 'Bonbonnes'; }
  if ($frtype == 1) { $frtypename = 'Pack'; }
  if ($frtype == 2) { $frtypename = ''; }
  $ourtitle = 'FEUILLE DE ROUTE ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename;
  if ($compterendu == 1) { $ourtitle = 'COMPTE RENDU ' . datefix2($ourdate) . ' ' . $frtypename . ' ' . $employeename; }
  showtitle($ourtitle);
  echo '<h2>' . $ourtitle . '</h2>';
  echo '<p>Nombre de clients: ' . $totalclients . ' &nbsp; &nbsp; &nbsp; Nombre ' . $frtypename . ': ' . $totalamount . '</p>';
  #echo '<table border=1 cellpadding=5 cellspacing=5 width=1200>';
#  if ($compterendu == 1)
#  {
#    #echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
#    #echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
#  }
#  else { #echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Secteur</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
  $counter =  0;
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = mysql_fetch_array($resultX);
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
    }
    $ok = 1;
    if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
    if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
    if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
    if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
    if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
    if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
    if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
    if ($ok)
    {
      $totalcost = $totalcost + $price;
        if ($row['day'] == 1) { $day = 'Lundi'; }
        if ($row['day'] == 2) { $day = 'Mardi'; }
        if ($row['day'] == 3) { $day = 'Mercredi'; }
        if ($row['day'] == 4) { $day = 'Jeudi'; }
        if ($row['day'] == 5) { $day = 'Vendredi'; }
        if ($row['daytype'] == 1) { $day = $day . ' Tous'; }
        if ($row['daytype'] == 2) { $day = $day . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $day = $day . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $day = $day . ' Premier du mois'; }
        if ($row['daytype'] == 6) { $day = $day . ' Mensuel (1)'; }
        if ($row['daytype'] == 7) { $day = $day . ' Mensuel (2)'; }
        if ($row['daytype'] == 8) { $day = $day . ' Mensuel (3)'; }
        if ($row['daytype'] == 9) { $day = $day . ' Mensuel (4)'; }

      $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
      if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $queryEA = 'select address,townname from extraaddress,town,telephone where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $resultEA = mysql_query($queryEA, $db_conn); querycheck($resultEA);
        $num_resultsEA = mysql_num_rows($resultEA);
        if ($num_resultsEA > 0)
        {
          $rowEA = mysql_fetch_array($resultEA);
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
      if ($compterendu == 1)
      {
        #echo '<tr><td>' . $clientname . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      }
      else
      {
        #echo '<tr><td>' . $clientname . '</td><td><font size=-1>' . $address . '</font></td><td>' . $row['clientsectorname'] . '</td><td>' . $day . '</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $row['reference'] . '</td></tr>';
        $reference = $row['reference'];
        if ($_POST['showbalance'] && 1==0)
        {
          ####
              $totaldebit = 0; $totalcredit = 0; $clientid = $row['clientid'];
              # DEBIT
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results1 = mysql_num_rows($result);
              for ($i=0; $i < $num_results1; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results2 = mysql_num_rows($result);
              for ($i=0; $i < $num_results2; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results5 = mysql_num_rows($result);
              for ($i=0; $i < $num_results5; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              # CREDIT
              $query = 'select paymentcomment,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results3 = mysql_num_rows($result);
              for ($i=0; $i < $num_results3; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results4 = mysql_num_rows($result);
              for ($i=0; $i < $num_results4; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results6 = mysql_num_rows($result);
              for ($i=0; $i < $num_results6; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
          ####
          $xyz = $totaldebit - $totalcredit;
          if ($xyz > 0) { $reference = '<font size=-1>' . $reference . ' (Solde=' . $xyz . ')</font>'; }
        }
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>' . $day . '</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
      }
      $linerank[$counter] = $row['clientsectorrank'];
$counter++;
#if ($counter%$linesperpage==0)
#{
#  #echo '</table><P class="breakhere"></p><table border=1 cellpadding=5 cellspacing=5 width=1200>';
#  if ($compterendu == 1)
#  {
#    #echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
#    #echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
#  }
#  else { #echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Secteur</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
#}
    }
  }
  
  # periodic extraaddress
  $query = 'select clientcategoryid,clientsectorrank,vmt_delivery.extraaddressid,rentalid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,client.clientcategoryid,clientname,frtype,cellphone,quantity,client.quarter,contact,day,daytype,blocked,islandid from vmt_delivery,client,town,clientsector,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid and client.townid=town.townid';
  $query = $query . ' and periodic=1 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.day="' . $ourday . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by extraaddress.address,islandid,townrank,clientname'; # Vaimato wants it ordered by the address field directly
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsX = mysql_num_rows($resultX);
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = mysql_fetch_array($resultX);
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
    }
    $ok = 1;
    if ($row['daytype'] == 2 && $odd == 1) { $ok = 0; }
    if ($row['daytype'] == 3 && $even == 1) { $ok = 0; }
    if ($row['daytype'] == 4 && $day > 7) { $ok = 0; }
    if ($row['daytype'] == 6 && $monthlyweek != 1) { $ok = 0; }
    if ($row['daytype'] == 7 && $monthlyweek != 2) { $ok = 0; }
    if ($row['daytype'] == 8 && $monthlyweek != 3) { $ok = 0; }
    if ($row['daytype'] == 9 && $monthlyweek != 0) { $ok = 0; }
    if ($ok)
    {
      $totalcost = $totalcost + $price;
        if ($row['day'] == 1) { $day = 'Lundi'; }
        if ($row['day'] == 2) { $day = 'Mardi'; }
        if ($row['day'] == 3) { $day = 'Mercredi'; }
        if ($row['day'] == 4) { $day = 'Jeudi'; }
        if ($row['day'] == 5) { $day = 'Vendredi'; }
        if ($row['daytype'] == 1) { $day = $day . ' Tous'; }
        if ($row['daytype'] == 2) { $day = $day . ' Semaine Pair'; }
        if ($row['daytype'] == 3) { $day = $day . ' Semaine Impair'; }
        if ($row['daytype'] == 4) { $day = $day . ' Premier du mois'; }
        if ($row['daytype'] == 6) { $day = $day . ' Mensuel (1)'; }
        if ($row['daytype'] == 7) { $day = $day . ' Mensuel (2)'; }
        if ($row['daytype'] == 8) { $day = $day . ' Mensuel (3)'; }
        if ($row['daytype'] == 9) { $day = $day . ' Mensuel (4)'; }

      $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
      if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $queryEA = 'select address,townname,telephone from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $resultEA = mysql_query($queryEA, $db_conn); querycheck($resultEA);
        $num_resultsEA = mysql_num_rows($resultEA);
        if ($num_resultsEA > 0)
        {
          $rowEA = mysql_fetch_array($resultEA);
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
      if ($compterendu == 1)
      {
        #echo '<tr><td>' . $clientname . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '<br><br></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      }
      else
      {
        #echo '<tr><td>' . $clientname . '</td><td><font size=-1>' . $address . '</font></td><td>' . $row['clientsectorname'] . '</td><td>' . $day . '</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $row['reference'] . '</td></tr>';
                $reference = $row['reference'];
        if ($_POST['showbalance'])
        {
          ####
              $totaldebit = 0; $totalcredit = 0; $clientid = $row['clientid'];
              # DEBIT
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results1 = mysql_num_rows($result);
              for ($i=0; $i < $num_results1; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results2 = mysql_num_rows($result);
              for ($i=0; $i < $num_results2; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results5 = mysql_num_rows($result);
              for ($i=0; $i < $num_results5; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              # CREDIT
              $query = 'select paymentcomment,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results3 = mysql_num_rows($result);
              for ($i=0; $i < $num_results3; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results4 = mysql_num_rows($result);
              for ($i=0; $i < $num_results4; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results6 = mysql_num_rows($result);
              for ($i=0; $i < $num_results6; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
          ####
          $xyz = $totaldebit - $totalcredit;
          if ($xyz > 0) { $reference = '<font size=-1>' . $reference . ' (Solde=' . $xyz . ')</font>'; }
        }
        $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>' . $day . '</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
      }
      $linerank[$counter] = $row['clientsectorrank'];
$counter++;
#if ($counter%$linesperpage==0)
#{
#  #echo '</table><P class="breakhere"></p><table border=1 cellpadding=5 cellspacing=5 width=1200>';
#  if ($compterendu == 1)
#  {
#    #echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
#    #echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
#  }
#  else { #echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Secteur</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
#}
    }
  }
  
  
  
  
  
  
  # non periodic with no extraaddressid
  $query = 'select clientcategoryid,clientsectorrank,extraaddressid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,clientname,frtype,telephone,cellphone,quantity,client.quarter,address,contact,daytype,blocked from vmt_delivery,client,clientsector,town where client.townid=town.townid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid';
  $query = $query . ' and extraaddressid=0 and periodic=0 and client.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by clientname';
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsX = mysql_num_rows($resultX);
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = mysql_fetch_array($resultX);
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }
  #if ($row['clientid'] == 13062) { echo $query . ' ' . $row2['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
        $totalcost = $totalcost + $price;
    }
    $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
    if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      if ($row['extraaddressid'] > 0)
      {
        $queryEA = 'select address,townname from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $resultEA = mysql_query($queryEA, $db_conn); querycheck($resultEA);
        $num_resultsEA = mysql_num_rows($resultEA);
        if ($num_resultsEA > 0)
        {
          $rowEA = mysql_fetch_array($resultEA);
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
        }
      }
    if ($compterendu == 1)
    {
      #echo '<tr><td>' . $clientname . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }
    else
    {
      #echo '<tr><td>' . $clientname . '</td><td><font size=-1>' . $address . '</font></td><td>' . $row['clientsectorname'] . '</td><td>-</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $row['reference'] . '</td></tr>';
              $reference = $row['reference'];
        if ($_POST['showbalance'] && 1==0)
        {
          ####
              $totaldebit = 0; $totalcredit = 0; $clientid = $row['clientid'];
              # DEBIT
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results1 = mysql_num_rows($result);
              for ($i=0; $i < $num_results1; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results2 = mysql_num_rows($result);
              for ($i=0; $i < $num_results2; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results5 = mysql_num_rows($result);
              for ($i=0; $i < $num_results5; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              # CREDIT
              $query = 'select paymentcomment,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results3 = mysql_num_rows($result);
              for ($i=0; $i < $num_results3; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results4 = mysql_num_rows($result);
              for ($i=0; $i < $num_results4; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results6 = mysql_num_rows($result);
              for ($i=0; $i < $num_results6; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
          ####
          $xyz = $totaldebit - $totalcredit;
          if ($xyz > 0) { $reference = '<font size=-1>' . $reference . ' (Solde=' . $xyz . ')</font>'; }
        }
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>-</td><td>' . $row['telephone'] . '/' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
    }
    $linerank[$counter] = $row['clientsectorrank'];
$counter++;
#if ($counter%$linesperpage==0)
#{
#  #echo '</table><P class="breakhere"></p><table border=1 cellpadding=5 cellspacing=5 width=1200>';
#  if ($compterendu == 1)
#  {
#    #echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
#    #echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
#  }
#  else { #echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Secteur</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
#}
  }
  
  # non periodic extraaddress
  $query = 'select clientcategoryid,clientsectorrank,vmt_delivery.extraaddressid,townname,townrank,clientsectorname,surcharge,usedetail,reference,client.clientid,clientname,frtype,cellphone,quantity,client.quarter,contact,daytype,blocked from vmt_delivery,client,clientsector,town,extraaddress where vmt_delivery.extraaddressid=extraaddress.extraaddressid and client.townid=town.townid and client.clientsectorid=clientsector.clientsectorid and vmt_delivery.clientid=client.clientid';
  $query = $query . ' and periodic=0 and extraaddress.employeeid="' . $employeeid . '" and vmt_delivery.deliverydate="' . $ourdate . '" and frtype="' . $frtype . '"';
  $query = $query . ' and (vacationdate is null or vacationdate<="' . $ourdate . '")';
  $query = $query . ' order by clientname';
  $resultX = mysql_query($query, $db_conn); querycheck($resultX);
  $num_resultsX = mysql_num_rows($resultX);
  for ($i=0; $i < $num_resultsX; $i++)
  {
    $row = mysql_fetch_array($resultX);
    if ($compterendu != 1 && $frtype == 0)
    {
        $usedetail = $row['usedetail']; $surcharge = $row['surcharge'];
        ### PRICE DETERMINATION ### COPY FROM sales.php
        $price = $sp;
        if ($usedetail) { $price = $dsp; }

        # surcharge
        if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

        # check if there is a special category price
        $query = 'select categoryprice as salesprice from categorypricing where productid="' . $productid . '" and clientcategoryid="' . $row['clientcategoryid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # check if there is a special client price
        $query = 'select salesprice from clientpricing where productid="' . $productid . '" and clientid="' . $row['clientid'] . '"';
        $result = mysql_query($query, $db_conn); querycheck($result);
        $num_results = mysql_num_rows($result);
        if ($num_results > 0) { $row2 = mysql_fetch_array($result); $price = $row2['salesprice']; }

        # add VAT
        $price = myround($price + ($price * $taxcode/100));
        $totalcost = $totalcost + $price;
    }
    $clientname = $row['clientid'] . ': ' . $row['clientname']; if ($row['blocked'] > 0) { $clientname = $clientname . ' <font color="' . $_SESSION['ds_alertcolor'] . '">INTERDIT</font>'; }
        if ($showbalance)
      {
        $dp_clientid = $row['clientid'];
        require('inc/clientbalance.php');
        if ($dr_balance>0) { $clientname .= ' (' .$dr_balance.')'; }
      }
      $address = $row['address'] . ' ' . $row['quarter'];
      $telephone = $row['telephone'] . ' ' . $row['cellphone'];
      if ($row['extraaddressid'] > 0)
      {
        $queryEA = 'select address,townname,telephone from extraaddress,town where extraaddress.townid=town.townid and extraaddressid="' . $row['extraaddressid'] . '"';
        $resultEA = mysql_query($queryEA, $db_conn); querycheck($resultEA);
        $num_resultsEA = mysql_num_rows($resultEA);
        if ($num_resultsEA > 0)
        {
          $rowEA = mysql_fetch_array($resultEA);
          $address = $rowEA['address'] . ' ' . $rowEA['townname'];
          $telephone = $rowEA['telephone'];
        }
      }
    if ($compterendu == 1)
    {
      #echo '<tr><td>' . $clientname . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';
    }
    else 
    {
      #echo '<tr><td>' . $clientname . '</td><td><font size=-1>' . $address . '</font></td><td>' . $row['clientsectorname'] . '</td><td>-</td><td>' . $row['telephone'] . ' ' . $row['cellphone'] . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $row['reference'] . '</td></tr>';
              $reference = $row['reference'];
        if ($_POST['showbalance'] && 1==0)
        {
          ####
              $totaldebit = 0; $totalcredit = 0; $clientid = $row['clientid'];
              # DEBIT
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results1 = mysql_num_rows($result);
              for ($i=0; $i < $num_results1; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select paymenttypeid,chequeno,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results2 = mysql_num_rows($result);
              for ($i=0; $i < $num_results2; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results5 = mysql_num_rows($result);
              for ($i=0; $i < $num_results5; $i++)
              {
                $row = mysql_fetch_array($result);
                $totaldebit = $totaldebit + $row['totalprice'];
              }
              # CREDIT
              $query = 'select paymentcomment,paymentdate as date,paymentid as id,value as totalprice from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by paymentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results3 = mysql_num_rows($result);
              for ($i=0; $i < $num_results3; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference from invoicehistory where cancelledid<1 and isreturn=1 and clientid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by accountingdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results4 = mysql_num_rows($result);
              for ($i=0; $i < $num_results4; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
              $query = 'select adjustmentcomment,adjustmentdate as date,adjustmentid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
              $query = $query . ' and matchingid=0';
              $query = $query . ' order by adjustmentdate';
              $result = mysql_query($query, $db_conn); querycheck($result);
              $num_results6 = mysql_num_rows($result);
              for ($i=0; $i < $num_results6; $i++)
              {
                $row = mysql_fetch_array($result);
                $totalcredit = $totalcredit + $row['totalprice'];
              }
          ####
          $xyz = $totaldebit - $totalcredit;
          if ($xyz > 0) { $reference = '<font size=-1>' . $reference . ' (Solde=' . $xyz . ')</font>'; }
        }
      $lineshow[$counter] = '<tr><td>' . d_decode($clientname) . '</td><td><font size=-1>' . $address . '</font></td><td>-</td><td>' . $telephone . '</td><td align=right>' . $row['quantity'] . '</td><td align=right>' . myfix($price) . '</td><td>' . $reference . '</td></tr>';
    }
    $linerank[$counter] = $row['clientsectorrank'];
$counter++;
#if ($counter%$linesperpage==0)
#{
#  #echo '</table><P class="breakhere"></p><table border=1 cellpadding=5 cellspacing=5 width=1200>';
#  if ($compterendu == 1)
#  {
#    #echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
#    #echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
#  }
#  else { #echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Secteur</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
#}
  }
  
#  if ($compterendu != 1) { #echo '<tr><td><b>Total</td><td colspan=4>&nbsp;</td><td align=right>' . myfix($totalamount) . '</td><td align=right>' . myfix($totalcost) . '</td><td>&nbsp;</td></tr>'; }
#  echo '</table>';

  # sort tables HERE
  for ($i=0; $i < $counter; $i++) 
  {
#    echo $i . ' ' . $linerank[$i] . '<br>';
    $alreadysorted[$i] = 0;
  }
  
  # find lowest rank
  for ($x=0; $x < $counter; $x++) 
  {
    $lowestfound = 9999999;
    for ($i=0; $i < $counter; $i++) 
    {
      if ($alreadysorted[$i] != 1 && $linerank[$i] < $lowestfound) { $lowestfound = $linerank[$i]; $lowestindex = $i; }
    }
    $newindex[$x] = $lowestindex;
    $alreadysorted[$lowestindex] = 1;
  }

#echo '<br>';
#for ($i=0; $i < $counter; $i++) 
#{
#  $x = $newindex[$i];
#  echo $x . ' ' . $linerank[$x] . '<br>';
#}

  # display after sort
  echo '<table class="report" border=1 cellpadding=5 cellspacing=5 width=1200>';
  if ($compterendu == 1)
  {
    echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
    echo '<tr><td><b>Client';
    if ($showbalance) { echo '&nbsp;(Débit)'; }
    echo '</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
  }
  else 
  {
    echo '<tr><td><b>Client';
    if ($showbalance) { echo '&nbsp;(Débit)'; }
    echo '</b></td><td><b>Adresse</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>';
  }
  for ($i=0; $i < $counter; $i++)
  {
    $x = $newindex[$i];
#echo '<tr><td>' . $linerank[$x] .'</td></tr>'; # debug
    echo $lineshow[$x];
    $testvar = $i + 1;
    if ($testvar%$linesperpage==0 && $testvar != $counter)
    {
      echo '</table><P class="breakhere"></p><table class="report" border=1 cellpadding=5 cellspacing=5 width=1200>';
      if ($compterendu == 1)
      {
        echo '<tr><td>&nbsp;</td><td colspan=6 align=center><b><font size=+1>No Fact</font></b></td><td colspan=2 align=center><b><font size=+1>Encaissement</font></b></td></tr>';
        echo '<tr><td><b>Client</b></td><td><b>No Fact</b></td><td><b>Consigne</b></td><td><b>CPT</b></td><td><b>CRDT</b></td><td><b>Montant</b></td><td><b>Modde Reglt</b></td><td><b>No Fact</b></td><td><b>Montant</b></td></tr>';
      }
      else { echo '<tr><td><b>Client</b></td><td><b>Adresse</b></td><td><b>Periodicité</b></td><td><b>Telephone</b></td><td><b>Q.</b></td><td><b>Tarif</b></td><td><b>Info</b></td></tr>'; }
    }
  }
  if ($compterendu != 1) { echo '<tr><td><b>Total</td><td colspan=3>&nbsp;</td><td align=right>' . myfix($totalamount) . '</td><td align=right>' . myfix($totalcost) . '</td><td>&nbsp;</td></tr>'; }
  echo '</table>';
  break;
  
  
  



  default:
  echo '<p>this is the report window</p>';
  break;
  }

  require ('inc/bottom.php');

?>

