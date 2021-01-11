<?php

# TODO 0=blank -1=all (some params currently use 0=all)

require('preload/regulationzone.php');
require('preload/clientterm.php');
require('preload/bank.php');

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['show_paybydate'] = 'uint';
require('inc/readpost.php');

$dontshownotice = (int) $_POST['dontshownotice'];
$showcomments = 0; if ($_POST['showcomments'] == 1) { $showcomments = 1; }
if ($_POST['showoperations'] == 1 && $_POST['showoperations_public'] == 1)
{
  $showcomments = 1;
  require('inc/fulltextcurrency_func.php');
}
$showpayments = 1; if ($_POST['showoperations'] == 1 && $_POST['relevenopayment'] == 1) { $showpayments = 0; }
$showtelephone = 0; if ($_POST['showtelephone'] == 1) { $showtelephone = 1; }
$format = 1; if ($_POST['format'] == 0) { $format = 0; }

$client = $_POST['client'];
require('inc/findclient.php');
if (isset($_POST['client']) && $_POST['client'] != '' && $clientid < 1) { $clientid = 9999999; } # todo fix

$clientlist = '';
if ($_POST['clientlist'] != '')
{
  $clientids_unv = explode(" ", $_POST['clientlist']);
  $clientlist = '(';
  foreach ($clientids_unv as $clientid_candidate)
  {
    $clientid_candidate = (int) $clientid_candidate;
    $clientlist = $clientlist . $clientid_candidate . ',';
  }
  $clientlist = rtrim($clientlist,',') . ')';
}

$query = 'select distinct client.clientid,clienttermid,clientname,outstandinglimit,telephone,cellphone,tahitinumber,companytypename
,address,quarter,postaladdress,townname,postalcode,islandname,regulationzoneid
from client,town,island';
if ($_POST['onlyrental'] == 1) { $query = $query . ',vmt_rental'; } # Vaimato custom
$query = $query . ' where town.islandid=island.islandid and client.townid=town.townid';
if ($_POST['onlyrental'] == 1) { $query = $query . ' and vmt_rental.clientid=client.clientid and vmt_rental.deleted=0'; } # Vaimato custom
if ($_POST['clientsectorid'] > 0) { $query = $query . ' and client.clientsectorid="' . $_POST['clientsectorid'] . '"'; }
if ($_POST['islandid'] > 0) { $query = $query . ' and town.islandid="' . $_POST['islandid'] . '"'; }
if ($_POST['regulationzoneid'] > 0) { $query = $query . ' and regulationzoneid="' . $_POST['regulationzoneid'] . '"'; }
if ($_POST['employeeid'] > 0) { $query = $query . ' and client.employeeid="' . $_POST['employeeid'] . '"'; }
if ($_POST['employeeid2'] > 0) { $query = $query . ' and client.employeeid2="' . $_POST['employeeid2'] . '"'; }
if ($_POST['clientcategoryid'] > 0) { $query = $query . ' and client.clientcategoryid="' . $_POST['clientcategoryid'] . '"'; }
#}
if ($clientid > 0) { $query = $query . ' and clientid="'.$clientid.'"'; }
if ($clientlist != '') { $query = $query . ' and clientid in '.$clientlist; }
if ($_POST['creditlimit'] == 1) { $query = $query . ' and outstandinglimit>0'; }
if ($_POST['byclientid'] == 1)
{
  $query = $query . ' and client.clientid>="' . $_POST['startid'] . '" and client.clientid<="' . $_POST['stopid'] . '"';
}
if ($_POST['myorderby'] == 1) { $query = $query . ' order by client.clientid'; }
if ($_POST['myorderby'] == 2) { $query = $query . ' order by clientname'; }
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

for ($x=0; $x < $num_results_main; $x++)
{
  $outputstring = "";
  if ($x>0) { $outputstring = $outputstring . '<p class=breakhere></p>'; }
  #$row = mysql_fetch_array($resultMAIN);
  $row = $main_result[$x];
  $clientid = $row['clientid'];
  $clientname = $row['clientname'];
  $outstandinglimit = $row['outstandinglimit'];
  $outputstring = $outputstring . '<table class="transparent" border=0 cellspacing=1 cellpadding=1><tr><td valign=top width=400>';

  if (1==0 && $_SESSION['ds_customname'] == 'Wing Chong')
  {
    echo '<STYLE type=text/css>
    .tr4{
      color: red;
    }
    </STYLE>
    <div class="tr4">Ouverture Samedis 16, 23, 30 Décembre: 08h00-12h00<br>
Fermeture Inventaire Mardi 02 au Vendredi 05 Janvier 2018<br>
              Réouverture LUNDI 08 JANVIER 2018<br></div>';
    $ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
    if (file_exists($ourlogofile)) { $outputstring = $outputstring . '<p><img src="' . $ourlogofile . '" height=50></p>'; }
  }
  else
  {
    $ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
    if (file_exists($ourlogofile)) { $outputstring = $outputstring . '<p><img src="' . $ourlogofile . '"></p>'; }
  }
  
  $outputstring = $outputstring . '<p>';
  $outputstring = $outputstring . $_SESSION['ds_accounttop'];
  $outputstring = $outputstring . '</p>';

  $outputstring = $outputstring . '</td><td valign=top>&nbsp; &nbsp; &nbsp;</td><td>';
  if ($_POST['showoperations'] == 1)
  {
    $outputstring = $outputstring . '<p><b>Relevé ';
    if ($_POST['showoperations_public'] == 1) { $outputstring .= ' Factures Administratives'; }
    else { $outputstring .= 'client'; }
    $outputstring .= '</b></p><p>Toutes transactions<br>' . datefix($startdate) . ' au ' . datefix($stopdate) . '</p>';
  }
  else { $outputstring = $outputstring . '<p><b>Compte client</b></p><p>' . datefix($_SESSION['ds_curdate']) . '</p>'; }

  $outputstring = $outputstring . '<p>Client n<span class=sup>o</span> ' . $row['clientid'];
  if ($_POST['dateref'] == 1) { $outputstring = $outputstring . '<br>Numéro relevé: ' . $row['clientid'] . date("YmdH"); }
  $outputstring = $outputstring . '</p>';
  if ($row['tahitinumber'] != "") { $outputstring = $outputstring . '<p>Numéro Tahiti ' . $row['tahitinumber'] . '</p>'; }
  else { $outputstring = $outputstring . '<p>&nbsp;</p>'; }
  if ($showtelephone)
  {
    if ($row['telephone'] != "" || $row['cellphone'] != "") { $outputstring .= '<p>Tél ' . $row['telephone'] . ' ' . $row['cellphone'] . '</p>'; }
  }
  $outputstring = $outputstring . '<p>' . d_output(d_decode($row['clientname'])) . ' ' . $row['companytypename'];
  if ($row['address'] != "") { $outputstring = $outputstring . '<br>' . $row['address']; }
  if ($row['postaladdress'] != "") { $outputstring = $outputstring . '<br>' . $row['postaladdress']; }
  $outputstring = $outputstring . '<br>' . $row['postalcode'] . ' ' . $row['townname'];
  $outputstring = $outputstring . '<br>' . $row['islandname'];
  $zone = $regulationzoneA[$row['regulationzoneid']];
  if ($zone != '' and $zone != $row['islandname']) { $outputstring = $outputstring . '<br>' . $regulationzoneA[$row['regulationzoneid']]; }
  $outputstring = $outputstring . '</p>';
  $outputstring = $outputstring . '</td></tr></table>';
  $outputstring .= '<b>Règlement : &nbsp; </b>' . $clienttermA[$row['clienttermid']];
  
  if ($format) { $outputstring = $outputstring . '<table class="transparent" border=0><tr><td valign=top>'; }
  else { $t = -1; unset($line_date, $line_id, $line_vat, $line_debit, $line_credit, $line_comment); }
  $totaldebit = 0; $totalcredit = 0;

  if ($_POST['dontshowdebit'] != 1)
  {
    # DEBIT
    if ($format)
    {
      $outputstring = $outputstring . '<table class="report" border=1>';
      $outputstring = $outputstring . '<tr><td colspan=5><font size=+1><b>DEBIT</td></tr>';
    }
    $query = 'select matchingid,invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference,paybydate
    from invoicehistory where cancelledid<1 and isreturn=0 and invoiceprice>0 and clientid="' . $clientid . '"';
    if ($dontshownotice == 1) { $query .= ' and isnotice=0'; }
    if ($_POST['showoperations'] == 1)
    {
      $query = $query . ' and accountingdate>="' . $startdate . '" and accountingdate<="' . $stopdate . '" and confirmed=1';
      if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
    }
    else { $query = $query . ' and matchingid=0'; }
    $query = $query . ' order by accountingdate';
    $query_prm = array();
    require('inc/doquery.php');
    $num_results1 = $num_results;
    if ($num_results1 > 0)
    {
      if ($format)
      { 
        $outputstring = $outputstring . '<tr><td><b>Facture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>dont TVA</td>';
        if ($show_paybydate) { $outputstring .= '<td><b>Echeance'; }
        if ($showcomments) { $outputstring .= '<td><b>'.d_output($_SESSION['ds_term_reference']).'</b></td>'; }
        $outputstring .= '</tr>';
      }
    }
    $t1 = 0; $t2 = 0;
    for ($i=0; $i < $num_results1; $i++)
    {
      $row = $query_result[$i];
      if ($format)
      {
        $outputstring = $outputstring . '<tr><td align=right>';
        if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
        $outputstring .= '<a href="printwindow.php?report=showinvoice&invoiceid='.$row['id'].'" target=_blank>' . myfix($row['id']) . '</a>';
        $outputstring .= '<td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td align=right>' . myfix($row['invoicevat']) . '</td>';
        if ($show_paybydate) { $outputstring .= '<td>' . datefix($row['paybydate'],'short'); }
        if ($showcomments) { $outputstring .= '<td>' . $row['reference'] . '</td>'; }
        $outputstring .= '</tr>';
      }
      else
      {
        $t++;
        $line_date[$t] = $row['date']; $line_id[$t] = 'Fact ' . $row['id']; $line_vat[$t] = $row['invoicevat']; $line_debit[$t] = $row['totalprice']; $line_credit[$t] = 0;
        $line_comment[$t] = $row['reference'];
      }
      $totaldebit = $totaldebit + $row['totalprice'];
      $t1 = $t1 + $row['totalprice']; $t2 = $t2 + $row['invoicevat'];
    }
    if ($format && ($t1 > 0 || $t2 > 0))
    {
      $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td align=right><i>' . myfix($t2) . '</i></td>';
      if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
      $outputstring .= '</tr>';
    }
    $debitvat = $t2;
    if ($showpayments)
    {
      $query = 'select matchingid,paymenttypeid,paymentdate as date,paymentid as id,value as totalprice,chequeno,bankid,paymentcomment from payment where value>0 and reimbursement=1 and clientid="' . $clientid . '"';
      if ($_POST['showoperations'] == 1)
      {
        $query = $query . ' and paymentdate>="' . $startdate . '" and paymentdate<="' . $stopdate . '"';
        if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
      }
      else { $query = $query . ' and matchingid=0'; }
      $query = $query . ' order by paymentdate';
      $query_prm = array();
      require('inc/doquery.php');
      $num_results2 = $num_results;
      if ($num_results2 > 0)
      {
        if ($format)
        {
          $outputstring = $outputstring . '<tr><td><b>Remb.</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>Chèque</b></td>';
          if ($showcomments) { $outputstring .= '<td><b>Commentaire</b></td>'; }
          $outputstring .= '</tr>';
        }
      }
      $t1 = 0; $t2 = 0;
      for ($i=0; $i < $num_results2; $i++)
      {
        $row = $query_result[$i];
        $myid = myfix($row['id']);
        $chequeno = $row['chequeno'];
        if ($chequeno != '')
        {
          $chequeno = $bankA[$row['bankid']] . '&nbsp;' . $chequeno;
        }
        $paymentcomment = '';
        if ($row['forinvoiceid'] > 0)
        {
          $paymentcomment .= 'Fact <a href="printwindow.php?report=showinvoice&invoiceid='.$row['forinvoiceid'].'" target=_blank>' . $row['forinvoiceid'] . '</a>';
        }
        $paymentcomment .= $row['paymentcomment'];
        if ($format)
        {
          $outputstring = $outputstring . '<tr><td align=right>';
          if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
          $outputstring .= $myid . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><font size=-1>' . $chequeno . '</font></td>';
          if ($showcomments) { $outputstring .= '<td>' . $paymentcomment . '</td>'; }
          $outputstring .= '</tr>';
        }
        else
        {
          $t++;
          $line_date[$t] = $row['date']; $line_id[$t] = 'Remb ' . $row['id']; $line_vat[$t] = 0; $line_debit[$t] = $row['totalprice']; $line_credit[$t] = 0;
          $line_comment[$t] = $paymentcomment;
        }
        $totaldebit = $totaldebit + $row['totalprice'];
        $t1 = $t1 + $row['totalprice'];
      }
      if ($format && $t1 > 0)
      {
        $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td>';
        if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
        $outputstring .= '</tr>';
      }
    }
    $query = 'select matchingid,adjustmentcomment,adjustmentdate as date,adjustment.adjustmentgroupid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and nomatch=0 and deleted=0 and closing=0 and debit=1 and accountingnumberid=1 and referenceid="' . $clientid . '"';
    if ($_POST['showoperations'] == 1)
    {
      $query = $query . ' and adjustmentdate>="' . $startdate . '" and adjustmentdate<="' . $stopdate . '"';
      if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
    }
    else { $query = $query . ' and matchingid=0'; }
    $query = $query . ' order by adjustmentdate';
    $query_prm = array();
    require('inc/doquery.php');
    $num_results5 = $num_results;
    if ($num_results5 > 0)
    {
      if ($format)
      {
        $outputstring = $outputstring . '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>&nbsp;</td>';
        if ($showcomments) { $outputstring .= '<td><b>Commentaire</b></td>'; }
        $outputstring .= '</tr>';
      }
    }
    $t1 = 0; $t2 = 0;
    for ($i=0; $i < $num_results5; $i++)
    {
      $row = $query_result[$i];
      $date = $row['date'];
      if ($format)
      {
        $outputstring = $outputstring . '<tr><td align=right>';
        if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
        $outputstring .= myfix($row['id']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td>&nbsp;</td>';
        if ($showcomments) { $outputstring .= '<td>' . $row['adjustmentcomment'] . '</td>'; }
        $outputstring .= '</tr>';
      }
      else
      {
        $t++;
        $line_date[$t] = $row['date']; $line_id[$t] = 'Ecr ' . $row['id']; $line_vat[$t] = 0; $line_debit[$t] = $row['totalprice']; $line_credit[$t] = 0;
        $line_comment[$t] = $row['adjustmentcomment'];
      }
      $totaldebit = $totaldebit + $row['totalprice'];
      $t1 = $t1 + $row['totalprice'];
    }
    if ($format && $t1 > 0)
    {
      $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td>';
      if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
      $outputstring .= '</tr>';
    }
    if ($format) { $outputstring = $outputstring . '</table>'; }
  }

  if ($format) { $outputstring = $outputstring . '</td><td width=25></td><td valign=top>'; }

  if ($_POST['dontshowcredit'] != 1)
  {
    # CREDIT
    if ($format)
    {
      $outputstring = $outputstring . '<table class="report" border=1>';
      $outputstring = $outputstring . '<tr><td colspan=5><font size=+1><b>CREDIT</td></tr>';
    }
    if ($showpayments)
    {
      $query = 'select matchingid,forinvoiceid,paymentdate as date,paymentid as id,value as totalprice,chequeno,bankid,paymentcomment from payment where value>0 and reimbursement=0 and clientid="' . $clientid . '"';
      if ($_POST['showoperations'] == 1)
      {
        $query = $query . ' and paymentdate>="' . $startdate . '" and paymentdate<="' . $stopdate . '"';
        if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
      }
      else { $query = $query . ' and matchingid=0'; }
      $query = $query . ' order by paymentdate';
      $query_prm = array();
      require('inc/doquery.php');
      $num_results3 = $num_results;
      if ($format && $num_results3 > 0)
      {
        $outputstring = $outputstring . '<tr><td><b>Payment</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>Chèque</b></td>';
        if ($showcomments) { $outputstring .= '<td><b>Commentaire</b></td>'; }
        $outputstring .= '</tr>';
      }
      $t1 = 0; $t2 = 0;
      for ($i=0; $i < $num_results3; $i++)
      {
        $row = $query_result[$i];
        $chequeno = $row['chequeno'];
        if ($chequeno != '')
        {
          $chequeno = $bankA[$row['bankid']] . '&nbsp;' . $chequeno;
        }
        $paymentcomment = '';
        if ($row['forinvoiceid'] > 0)
        {
          $paymentcomment .= 'Fact <a href="printwindow.php?report=showinvoice&invoiceid='.$row['forinvoiceid'].'" target=_blank>' . $row['forinvoiceid'] . '</a>';
        }
        $paymentcomment .= $row['paymentcomment'];
        if ($format)
        { 
          $outputstring = $outputstring . '<tr><td align=right>';
          if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
          $outputstring .= myfix($row['id']) . '</td><td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td><font size=-1>' . $chequeno . '</font></td>';
          if ($showcomments) { $outputstring .= '<td>' . $paymentcomment . '</td>'; }
          $outputstring .= '</tr>';
        }
        else
        {
          $t++;
          $line_date[$t] = $row['date']; $line_id[$t] = 'Paym ' . $row['id']; $line_vat[$t] = 0; $line_debit[$t] = 0; $line_credit[$t] = $row['totalprice'];
          $line_comment[$t] = $paymentcomment;
        }
        $totalcredit = $totalcredit + $row['totalprice'];
        $t1 = $t1 + $row['totalprice'];
      }
      if ($format && $t1 > 0)
      {
        $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td>';
        if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
        $outputstring .= '</tr>';
      }
    }
  
    $query = 'select matchingid,invoicevat,accountingdate as date,invoiceid as id,invoiceprice as totalprice,reference,paybydate
    from invoicehistory where cancelledid<1 and isreturn=1 and invoiceprice>0 and clientid="' . $clientid . '"';
    if ($dontshownotice == 1) { $query .= ' and isnotice=0'; }
    if ($_POST['showoperations'] == 1)
    {
      $query = $query . ' and accountingdate>="' . $startdate . '" and accountingdate<="' . $stopdate . '" and confirmed=1';
      if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
    }
    else { $query = $query . ' and matchingid=0'; }
    $query = $query . ' order by accountingdate';
    $query_prm = array();
    require('inc/doquery.php');
    $num_results4 = $num_results;
    if ($format && $num_results4 > 0)
    {
      $outputstring = $outputstring . '<tr><td><b>Avoir</b></td><td><b>Date</b></td><td><b>Montant</b></td><td><b>dont TVA</b></td>';
      if ($showcomments) { $outputstring .= '<td><b>'.d_output($_SESSION['ds_term_reference']).'</b></td>'; }
    }
    $t1 = 0; $t2 = 0;
    for ($i=0; $i < $num_results4; $i++)
    {
      $row = $query_result[$i];
      if ($format)
      {
        $outputstring = $outputstring . '<tr><td align=right>';
        if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
        $outputstring .= '<a href="printwindow.php?report=showinvoice&invoiceid='.$row['id'].'" target=_blank>' . myfix($row['id']) . '</a>';
        $outputstring .= '<td>' . datefix2($row['date']) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td align=right>' . myfix($row['invoicevat']) . '</td>';
        if ($show_paybydate) { $outputstring .= '<td>' . datefix($row['paybydate'],'short'); }
        if ($showcomments) { $outputstring .= '<td>' . $row['reference'] . '</td>'; }
      }
      else
      {
        $t++;
        $line_date[$t] = $row['date']; $line_id[$t] = 'Avoir ' . $row['id']; $line_vat[$t] = $row['invoicevat']; $line_debit[$t] = 0; $line_credit[$t] = $row['totalprice'];
        $line_comment[$t] = $row['reference'];
      }
      $totalcredit = $totalcredit + $row['totalprice'];
      $t1 = $t1 + $row['totalprice']; $t2 = $t2 + $row['invoicevat'];
    }
    if ($format && ($t1 > 0 || $t2 > 0))
    {
      $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td align=right><i>' . myfix($t2) . '</i></td>';
      if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
      $outputstring .= '</tr>';
    }
    $creditvat = $t2;
    $query = 'select matchingid,adjustmentcomment,adjustmentdate as date,adjustment.adjustmentgroupid as id,value as totalprice from adjustment,adjustmentgroup where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid and nomatch=0 and deleted=0 and closing=0 and debit=0 and accountingnumberid=1 and referenceid="' . $clientid . '"';
    if ($_POST['showoperations'] == 1)
    {
      $query = $query . ' and adjustmentdate>="' . $startdate . '" and adjustmentdate<="' . $stopdate . '"';
      if ($_POST['relevenomatched'] == 1) { $query = $query . ' and matchingid=0'; }
    }
    else { $query = $query . ' and matchingid=0'; }
    $query = $query . ' order by adjustmentdate';
    $query_prm = array();
    require('inc/doquery.php');
    $num_results6 = $num_results;
    if ($format && $num_results6 > 0)
    {
      $outputstring = $outputstring . '<tr><td><b>Ecriture</b></td><td><b>Date</b></td><td><b>Montant</b></td><td>&nbsp;</td>';
      if ($showcomments) { $outputstring .= '<td><b>Commentaire</b></td>'; }
      $outputstring .= '</tr>';
    }
    $t1 = 0; $t2 = 0;
    for ($i=0; $i < $num_results6; $i++)
    {
      $row = $query_result[$i];
      $date = $row['date']; if (substr($row['operationdate'],0,4) > 0) { $date = $row['operationdate']; }
      if ($format)
      {
        $outputstring = $outputstring . '<tr><td align=right>';
        if ($_POST['showoperations'] == 1 && $row['matchingid'] > 0) { $outputstring .= '&radic; '; }
        $outputstring .= myfix($row['id']) . '</td><td>' . datefix2($date) . '</td><td align=right>' . myfix($row['totalprice']) . '</td><td>&nbsp;</td>';
        if ($showcomments) { $outputstring .= '<td>' . $row['adjustmentcomment'] . '</td>'; }
        $outputstring .= '</tr>';
      }
      else
      {
        $t++;
        $line_date[$t] = $row['date']; $line_id[$t] = 'Ecr ' . $row['id']; $line_vat[$t] = 0; $line_debit[$t] = 0; $line_credit[$t] = $row['totalprice'];
        $line_comment[$t] = $row['adjustmentcomment'];
      }
      $totalcredit = $totalcredit + $row['totalprice'];
      $t1 = $t1 + $row['totalprice'];
    }
    if ($format && $t1 > 0)
    {
      $outputstring = $outputstring . '<tr><td><i>Total</i></td><td>&nbsp;</td><td align=right><i>' . myfix($t1) . '</i></td><td>&nbsp;</td>';
      if ($showcomments) { $outputstring .= '<td>&nbsp;</td>'; }
      $outputstring .= '</tr>';
    }
    if ($format) { $outputstring = $outputstring . '</table>'; }
  }
  
  $oktoshow = 1;
  
  if ($format == 1)
  {
    $outputstring = $outputstring . '</td></tr><tr><td colspan="2" align="center"></td></tr></table>';
    $outputstring = $outputstring . '<br>';
  
    $outputstring = $outputstring . '<table class="transparent" cellpadding=1><tr><td><b>Total debit</b>:</td><td align=right>' . myfix($totaldebit);
    #$outputstring = $outputstring . '</td><td align=right>' . myfix($debitvat);
    $outputstring = $outputstring . '</td></td><td></tr><tr><td><b>Total credit</b>:</td><td align=right>' . myfix($totalcredit);
    #$outputstring = $outputstring . '</td><td align=right>' . myfix($creditvat);
    $outputstring = $outputstring . '</td></td><td></tr><tr><td><b>Solde dû ';
    if ($totalcredit > $totaldebit) { $outputstring = $outputstring . '(credit)'; }
    if ($totaldebit > $totalcredit) { $outputstring = $outputstring . '(debit)'; }
    $grandtotal = d_abs($totalcredit-$totaldebit);
    $outputstring = $outputstring . '</b>: </td><td align=right>' . myfix($grandtotal);
    $netvat = d_abs($creditvat - $debitvat);
    if ($showpayments == 0 && $netvat > 0)
    {
      $outputstring = $outputstring . '</td><td> &nbsp; (TVA ' . myfix($netvat);
      $outputstring = $outputstring . ' , HT ' . myfix($grandtotal-$netvat) . ')';
    }
    $outputstring = $outputstring . '</td></tr></table>';
  }
  else
  {
    if(isset($line_date))
    {
      asort($line_date);
      $outputstring .= '<table class="report" border=1><tr><td><b>Date</b></td><td><b>No Piece</b></td><td><b>Dont TVA</b></td><td><b>Débit</b></td><td><b>Crédit</b></td>';
      if ($showcomments) { $outputstring .= '<td><b>Infos</b></td>'; }
      $lastmonth = -1; $lastdate = -1; $sub_line_vat = 0; $sub_line_debit = 0; $sub_line_credit = 0; unset ($monthtotal);
      foreach($line_date as $t => $date)
      {
        if (substr($date,5,2) != $lastmonth && $lastmonth != -1)
        {
          $outputstring .= '<tr><td colspan=2><b>Total du mois</b></td><td align=right><b>'.myfix($sub_line_vat).'</b></td><td align=right><b>'.myfix($sub_line_debit).'</b></td><td align=right><b>'.myfix($sub_line_credit).'</b></td>';
          if ($showcomments) { $outputstring .= '<td><b>&nbsp;</b></td>'; }
          $i_lastmonth = $lastmonth+0;
          $kladd = d_trad('month2_' . $i_lastmonth) .'&nbsp;'.$lastyear;
          $monthtotal[$kladd] = $sub_line_debit - $sub_line_credit;
          $sub_line_vat = 0; $sub_line_debit = 0; $sub_line_credit = 0;
        }
        $outputstring .= '<tr><td>';
        if ($date != $lastdate) { $outputstring .= datefix($date); }
        $outputstring .= '</td><td align=right>' . $line_id[$t] . '</td><td align=right>' . myfix($line_vat[$t]) . '</td><td align=right>' . myfix($line_debit[$t]) . '</td><td align=right>' . myfix($line_credit[$t]) . '</td>';
        if ($showcomments) { $outputstring .= '<td>'.$line_comment[$t].'</td>'; }
        $sub_line_debit += $line_debit[$t]; $sub_line_credit += $line_credit[$t];
        if ($line_debit[$t] > 0) { $sub_line_vat += $line_vat[$t]; }
        else { $sub_line_vat -= $line_vat[$t]; }
        $lastmonth = substr($date,5,2); $lastdate = $date; $lastyear = substr($date,0,4);
      }
      # subtotal, copy from above
      $outputstring .= '<tr><td colspan=2><b>Total du mois</b></td><td align=right><b>'.myfix($sub_line_vat).'</b></td><td align=right><b>'.myfix($sub_line_debit).'</b></td><td align=right><b>'.myfix($sub_line_credit).'</b></td>';
      if ($showcomments) { $outputstring .= '<td><b>&nbsp;</b></td>'; }
      $i_lastmonth = $lastmonth+0;
      $kladd = d_trad('month2_' . $i_lastmonth) .'&nbsp;'.$lastyear;
      $monthtotal[$kladd] = $sub_line_debit - $sub_line_credit;
      # grand total, reusing
      $netvat = $debitvat - $creditvat; $grandtotal = d_abs($totalcredit-$totaldebit);
      $outputstring .= '<tr><td colspan=2><b>Total</b></td><td align=right><b>'.myfix($netvat).'</b></td><td align=right><b>'.myfix($totaldebit).'</b></td><td align=right><b>'.myfix($totalcredit).'</b></td></tr>';
      $outputstring .= '<tr><td colspan=2><b>Solde dû</b></td><td>&nbsp;</td>';
      if ($totalcredit >= $totaldebit) { $outputstring .= '<td>&nbsp;</td><td align=right><b>'.myfix($grandtotal).'</b></td>'; }
      else { $outputstring .= '<td align=right><b>'.myfix($grandtotal).'</b></td><td>&nbsp;</td>'; }
      if (isset($monthtotal) && count($monthtotal)>0 && count($monthtotal)<6)
      {
        $outputstring .= '<tr>';
        foreach ($monthtotal as $month => $amount)
        {
          #$outputstring .= '<td><b>' . $month . '</b></td><td align=right><b>' . myfix($amount) . '</b></td>';
          $outputstring .= '<td><b>' . $month . '</b></td>';
        }
        $outputstring .= '<tr>';
        foreach ($monthtotal as $month => $amount)
        {
          $outputstring .= '<td align=right><b>' . myfix($amount) . '</b></td>';
        }
      }
      $outputstring .= '</table>';
    }
    else
    {
      $oktoshow = 0;
    }
  }

  $outputstring = $outputstring . '<p STYLE="font-size: 65%">' . $_SESSION['ds_accountbottom'] . '</p>';
  if ($_POST['showoperations'] == 1 && $_POST['showoperations_public'] == 1)
  {
    $outputstring = $outputstring . '<p STYLE="font-size: 65%">Vérifié et arrêté le présent Relevé de Factures administratives à la somme de '.convertir($grandtotal).'.</p>';
  }
  $outputstring = $outputstring . '<span STYLE="text-align: right; width: 99%"><img src="pics/logo.png" height="50"></span>';

  if ($_POST['creditlimit'] == 1)
  {
    if (($totaldebit - $totalcredit) <= $outstandinglimit) { $oktoshow = 0; }
  }
  if ($_POST['onlydebitors'] == 1)
  {
    if ($totalcredit >= $totaldebit) { $oktoshow = 0; }
  }
  if ($_POST['showoperations'] == 1)
  {
    if ($totalcredit == 0 && $totaldebit == 0) { $oktoshow = 0; }
  }  
  if ($oktoshow) { echo $outputstring; }

}

?>