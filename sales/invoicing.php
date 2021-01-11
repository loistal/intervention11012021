<?php

# 2017 07 18 slowly starting refactoring
# TODO separate logic and presentation (move presentation to bottom)

#if ($_SESSION['ds_beta_access']) { require_once('invoicing_beta.php'); exit; }

require('inc/autocomplete_product.php');
require('preload/localvessel.php');
require('preload/producttype.php');
require('preload/returnreason.php');
require('preload/town.php');
require('preload/island.php');
require('preload/taxcode.php');
require('preload/advance.php');
if ($_SESSION['ds_select_itemcomment']) { require('preload/select_itemcomment.php'); }

$PA['save'] = '';
$PA['modify'] = '';
$PA['modifyinvoiceid'] = 'uint';
$PA['copyinvoice'] = 'uint';
$PA['reorder'] = 'uint';
$PA['reorderdate'] = 'date';
$PA['productdepartmentid'] = 'uint';
$PA['productfamilygroupid'] = 'uint';
$PA['confirmme'] = 'uint';
$PA['invoiceid'] = 'uint';
$PA['invoicetype'] = 'uint';
$PA['newinv'] = 'uint';
$PA['notfirstrequest'] = 'int';
$PA['invoicelines'] = 'uint';
$PA['showcomments'] = 'uint';
$PA['proforma'] = 'uint';
if ($_SESSION['ds_paybydateselect'] == 1) { $PA['paybydate'] = 'date'; }
if ($_SESSION['ds_term_custominvoicedate'] != '') { $PA['custominvoicedate'] = 'date'; }
$PA['returnreasonid'] = 'uint';
$PA['localvesselid'] = 'uint';
$PA['employeeid'] = 'uint';
$PA['invoicetagid'] = 'uint';
$PA['invoicetag2id'] = 'uint';
$PA['deliverytypeid'] = 'uint';
$PA['advanceid'] = 'uint';
$PA['extraaddressid'] = 'uint';
$PA['extraname'] = '';
$PA['invoicereference'] = '';
$PA['field1'] = '';
$PA['field2'] = '';
$PA['invoicecomment'] = '';
$PA['invoicecomment2'] = '';
$PA['extraaddressid'] = 'uint';
$PA['lastclientid'] = 'int';
$PA['confirm1'] = 'uint';
$PA['confirm2'] = 'uint';
require('inc/readpost.php');
$postinvoiceid = $invoiceid;
if ($modify != '') { $modify = 1; } else { $modify = 0; }
if ($save != '') { $save = 1; } else { $save = 0; }
if ($save && $_SESSION['ds_prevent_duplicate'])
{
  $temp_interval = time()-$_SESSION['ds_prevent_duplicate_time'];
  if ($temp_interval < 10) { $save = 0; echo '<p>Duplicate prevention</p>'; }
  else { $_SESSION['ds_prevent_duplicate_time'] = time(); }
}
if ($save && $confirm1 == 1 && $confirm2 == 1) { $confirmme = 1; } else { $confirmme = 0; }

require('inc/findclient.php');
if ($clientid != $lastclientid) { $clienthaschanged = 1; } else { $clienthaschanged = 0; }
if (!isset($_POST['client']) && $clientid < 1 && isset($_SESSION['ds_defaultclientid'])) { $clientid = $_SESSION['ds_defaultclientid']; }

$invoiceweight = 0; $proforma = 0; $isnotice = 0; $isreturn = 0; $returntostock = 0; $copyinvoicefromhistory = 0;
$hascold = 0; $totalprice = 0; $vat = 0; $tender = 0; $nontender = 0; $vatexempt = 0;
$proforma = 0; $isnotice = 0; $isreturn = 0; $returntostock = 0; $thisinvoicehasbeensaved = 0;
$clientid2 = $clientid3 = 0; $colspan_mod = 0;
$num_resultsLINES = 0;
$productfieldsize = 5;
if ($_SESSION['ds_useproductcode'] == 1) { $productfieldsize = 15;  }
$input_number = 'number';
$addresstodisplay = '';
$vatA = array();
foreach ($taxcodeA as $taxcode)
{
  $vatA[$taxcode] = 0;
}

if ($invoicelines == 0) { $invoicelines = $_SESSION['ds_invoicelines']; }
elseif ($invoicelines > 1000) { $invoicelines = 1000; }
elseif (isset($_POST['productid' . $invoicelines]) && $_POST['productid' . $invoicelines] != "") { $invoicelines++; }

if ($_SESSION['ds_caninvoicedate'] == 1)
{
  $datename = 'select';
  require('inc/datepickerresult.php');
  #$ourdate = $datepicker_date;
  $ourdate = $select;
  if ($_SESSION['ds_noretrodates'] && $ourdate < $_SESSION['ds_curdate']) { $ourdate = $_SESSION['ds_curdate']; }
}
else { $ourdate = $_SESSION['ds_curdate']; }
if ($notfirstrequest != 1 && $_SESSION['ds_lastselecteddate'] != ""
&& $_SESSION['ds_customname'] != 'Pro Peinture') { $ourdate = $_SESSION['ds_lastselecteddate']; }

$datename = 'delivery';
require('inc/datepickerresult.php');
#$deliverydate = $datepicker_date;
$deliverydate = $delivery;
if ($notfirstrequest != 1 && $_SESSION['ds_lastselecteddeliverydate'] != "") { $deliverydate = $_SESSION['ds_lastselecteddeliverydate']; }
if ($_SESSION['ds_noretrodates'] && $deliverydate < $_SESSION['ds_curdate']) { $deliverydate = $_SESSION['ds_curdate']; }
if ($_SESSION['ds_noretrodates'] && $deliverydate < $ourdate) { $deliverydate = $ourdate; }

if (!isset($_POST['invoicetagid'])) { $invoicetagid = $_SESSION['ds_definvoicetagid']; }
if (!isset($_POST['deliverytypeid'])) { $deliverytypeid = $_SESSION['ds_defdeliverytypeid']; }

if ($notfirstrequest != 1)
{
  if ($_SESSION['ds_defshowcomments'] == 1) { $showcomments = 1; }
  if ($_SESSION['ds_autoproforma'] == 1) { $proforma = 1; }
  if ($_SESSION['ds_lastemployeeid'] != "") { $employeeid = $_SESSION['ds_lastemployeeid']; }
  else { $employeeid = $_SESSION['ds_definvoiceemployeeid']; }
}

if ($modify)
{
  if ($reorder == 1)
  {
    if ($clientid > 0)
    {
      $query = 'select distinct invoiceitemhistory.productid from invoiceitemhistory,invoicehistory';
      if ($productdepartmentid > 0 || $productfamilygroupid > 0) { $query .= ',product,productfamily,productfamilygroup'; }
      $query .= ' where invoiceitemhistory.invoiceid=invoicehistory.invoiceid';
      if ($productdepartmentid > 0 || $productfamilygroupid > 0)
      {
        $query .= ' and invoiceitemhistory.productid=product.productid and product.productfamilyid=productfamily.productfamilyid
        and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid';
      }
      $query .= ' and clientid=? and confirmed=1 and accountingdate>=? and accountingdate<=?';
      if ($productdepartmentid > 0) { $query .= ' and productdepartmentid=?'; }
      if ($productfamilygroupid > 0) { $query .= ' and productfamily.productfamilygroupid=?'; }
      $query .= ' order by productid';
      $query_prm = array($clientid,$reorderdate,$_SESSION['ds_curdate']);
      if ($productdepartmentid > 0) { array_push($query_prm, $productdepartmentid); }
      if ($productfamilygroupid > 0) { $query_prm[] = $productfamilygroupid; }
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        $_POST['productid'.($i+1)] = $query_result[$i]['productid'];
      }
    }
  }
  elseif ($copyinvoice == 1)
  {
    $query = 'select * from invoicehistory where invoiceid=?';
    $query_prm = array((int) $postinvoiceid);
    require('inc/doquery.php');
    $copyinvoicefromhistory = 1;
    if ($num_results == 0)
    {
      $query = 'select * from invoice where invoiceid=?';
      $query_prm = array((int) $postinvoiceid);
      require('inc/doquery.php');
      $copyinvoicefromhistory = 0;
    }
    if ($num_results)
    {
      $rowREAD = $query_result[0];
      $clientid = $rowREAD['clientid'];
    }
    # TODO else show why we cannot copy the invoice
  }
  else
  {
    $modifyinvoiceid = (int) $postinvoiceid;

    $query = 'select * from invoice where invoiceid=?';
    $query_prm = array($modifyinvoiceid);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      # cannot modify invoice, now find out why and let user add to comment
      $usehistory = 0;
      $query = 'select invoicecomment from invoice where invoiceid="' . $modifyinvoiceid . '"';
      $query_prm = array();
      require('inc/doquery.php');
      if (!$num_results)
      {
        $usehistory = 1;
        $query = 'select invoicecomment from invoicehistory where invoiceid="' . $modifyinvoiceid . '"';
        $query_prm = array();
        require('inc/doquery.php');
      }
      if (!$num_results) { echo '<h2 class=alert>'.$_SESSION['ds_term_invoice'].' ' . d_output($modifyinvoiceid) . ' n\'existe pas</h2><br>'; }
      elseif ($modifyinvoiceid != "")
      {
        echo '<h2 class=alert>'.$_SESSION['ds_term_invoice'].' ' . htmlentities($modifyinvoiceid) . ' est confirmée.</h2><br>';
        $rowX = $query_result[0];
        $invoicecomment = $rowX['invoicecomment'];
        require("addtocomment.php");
      }
      $modifyinvoiceid = "";
    }
    else
    {
      $rowREAD = $query_result[0];
      if ($rowREAD['userid'] != $_SESSION['ds_userid'] && $_SESSION['ds_confirmonlyown']) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Ne peut modifier la '.$_SESSION['ds_term_invoice'].' de quelq\'un d\'autre.</font>'; exit; }
      if ($rowREAD['cancelledid'] == 1) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Ne peut modifier une '.$_SESSION['ds_term_invoice'].' annulée.</font>'; exit; }
      if ($rowREAD['confirmed'] > 0 || $rowREAD['matchingid'] > 0) { echo '<font color="' . $_SESSION['ds_alertcolor'] . '">Ne peut modifier une '.$_SESSION['ds_term_invoice'].' confirmée.</font>'; exit; }
      $clientid = $rowREAD['clientid'];
      # exception, option? 
      if ($_SESSION['ds_customname'] == 'Pro Peinture') { $rowREAD['accountingdate'] = $_SESSION['ds_curdate']; }
    }
  }
  if ($clientid > 0 && isset($rowREAD))
  {
    $clientid2 = $rowREAD['clientid2'];
    $clientid3 = $rowREAD['clientid3'];
    $returnreasonid = $rowREAD['returnreasonid'];
    $localvesselid = $rowREAD['localvesselid'] + 0;
    $employeeid = $rowREAD['employeeid'] + 0;
    $invoicetagid = $rowREAD['invoicetagid'] + 0;
    $invoicetag2id = $rowREAD['invoicetag2id'] + 0;
    $deliverytypeid = $rowREAD['deliverytypeid'] + 0;
    $advanceid = $rowREAD['advanceid'] + 0;
    if ($_SESSION['ds_customname'] == 'Pro Peinture') { $ourdate = $_SESSION['ds_curdate']; } # TODO option
    else { $ourdate = $rowREAD['accountingdate']; }
    $deliverydate = $rowREAD['deliverydate'];
    $paybydate = $rowREAD['paybydate'];
    $custominvoicedate = $rowREAD['custominvoicedate'];
    $extraaddressid = $rowREAD['extraaddressid'] + 0;
    $extraname = $rowREAD['extraname'];
    $invoicereference = $rowREAD['reference'];
    $field1 = $rowREAD['field1'];
    $field2 = $rowREAD['field2'];
    $proforma = $rowREAD['proforma'] + 0;
    $isnotice = $rowREAD['isnotice'] + 0;
    $isreturn = $rowREAD['isreturn'] + 0;
    $returntostock = $rowREAD['returntostock'] + 0;
    $invoicecomment = $rowREAD['invoicecomment'];
    $invoicecomment2 = $rowREAD['invoicecomment2'];

    $invoiceitemtable_kladd = 'invoiceitem';
    if ($copyinvoicefromhistory == 1) { $invoiceitemtable_kladd = 'invoiceitemhistory'; }
    $query = 'select lineproducttypeid,linedate,employeeid,'.$invoiceitemtable_kladd.'.retailprice,linevalue,lineproducttypeid
    ,serial,rebate_type,invoiceitemid,'.$invoiceitemtable_kladd.'.productid,quantity,givenrebate,itemcomment,lineprice
    ,displaymultiplier,basecartonprice,numberperunit,serial,invoice_priceoption1id,invoice_priceoption2id,invoice_priceoption3id
    from '.$invoiceitemtable_kladd.',product,unittype
    where '.$invoiceitemtable_kladd.'.productid=product.productid and product.unittypeid=unittype.unittypeid
    and invoiceid=? order by invoiceitemid';
    $query_prm = array($postinvoiceid);
    require('inc/doquery.php');
    $resultLINES = $query_result;
    $num_resultsLINES = $num_results;
    if ($num_resultsLINES > $invoicelines) { $invoicelines = $num_resultsLINES; }
  }
}

if ($clientid > 0)
{
  $query = 'select client.clientid,vatexempt,clientname,postalcode,postaladdress,address,townid,usedetail
  ,client.clienttermid as clienttermid,clienttermname,daystopay,special,clienttermname,discount,surcharge,clientcategoryid
  ,clientcategory2id,clientcategory3id,clientcomment,use_loyalty_points,loyalty_start
  from client,clientterm where client.clienttermid=clientterm.clienttermid and blocked<>1 and clientid=?';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  if ($num_results == 0) { $clientid = -1; }
  else
  {
    $row = $query_result[0];
    $loyalty_start = $row['loyalty_start'];
    $use_loyalty_points = $row['use_loyalty_points'];
    $vatexempt = $row['vatexempt'];
    $clientid = $row['clientid'];
    $address = stripslashes($row['address']);
    $postaladdress = $row['postaladdress'];
    $postalcode = $row['postalcode'];
    $townname = $townA[$row['townid']];
    $islandid = $town_islandidA[$row['townid']]; # for price algorithms, do not remove
    $islandname = $islandA[$town_islandidA[$row['townid']]];
    $outerisland = $island_outerislandA[$town_islandidA[$row['townid']]];
    $regulationzoneid = $island_regulationzoneidA[$town_islandidA[$row['townid']]];
    $usedetail = $row['usedetail'];
    $surcharge = $row['surcharge'];
    $default_discount = $row['discount'];
    $clientcategoryid = $row['clientcategoryid'];
    $clientcategory2id = $row['clientcategory2id'];
    $clientcategory3id = $row['clientcategory3id'];
    $daystopay = $row['daystopay'];
    $special = $row['special'];
    if ($special == 1) # end of month
    {
      $endofmonthdate = new DateTime($ourdate);
      $endofmonthdate->add(date_interval_create_from_date_string($daystopay.' days'));
      $endofmonthdate->modify('last day of '.$endofmonthdate->format('Y-m'));
      $kladd = $endofmonthdate->diff(new DateTime($ourdate));
      $daystopay = $kladd->format('%a');
    }
    $clienttermname = $row['clienttermname'];
    $clientcomment = $row['clientcomment'];
    if ($_SESSION['ds_paybydateselect'] == 1)
    {
      $addresstodisplay .= '(' . $clienttermname . ') ';
    }
    $addresstodisplay .= $townname . ', ' . $islandname;
    # TODO check extraaddress, should islandid be read from extraaddress?
  }
}

if (!$save)
{
  if ($modifyinvoiceid > 0)
  {
    echo '<h2>Modifier ';
    if ($proforma || $invoicetype == 2) { echo ' proforma '; }
    elseif ($isnotice || $invoicetype == 3) { echo ' bon '; }
    elseif ($isreturn || $invoicetype == 4 || $invoicetype == 5) { echo ' avoir '; }
    else { echo ' '.$_SESSION['ds_term_invoice'].' '; }
    echo $modifyinvoiceid;
    if ($rowREAD['cancelledid'] == 2) { echo ' (Archivé(e))'; }
    echo '</h2>';
  }
  else
  {
    if ($invoicetype == 2 || $proforma) { echo '<h2>Nouveau proforma</h2>'; }
    elseif ($invoicetype == 3) { echo '<h2>Nouveau bon</h2>'; }
    elseif ($invoicetype == 4) { echo '<h2>Nouvel avoir</h2>'; }
    elseif ($invoicetype == 5) { echo '<h2>Nouvel avoir</h2>'; }
    else
    {
      if ($_SESSION['ds_term_invoice'] == 'Facture') { echo '<h2>Nouvelle '; }
      else { echo '<h2>Nouveau '; }
      echo $_SESSION['ds_term_invoice'].'</h2>';
    }
  }
}
else
{
  $invoicedescr = $_SESSION['ds_term_invoice'].' ';
  if ($proforma || $invoicetype == 2) { $invoicedescr = ' Proforma '; }
  if ($isnotice || $invoicetype == 3) { $invoicedescr = ' Bon '; }
  if ($isreturn || $invoicetype == 4 || $invoicetype == 5 || $invoicetype == 6) {$invoicedescr = ' Avoir '; $isreturn = 1; }
  $dp_noblockedclients = 1;
  
  if ($clientid<1) # TODO bug can create invoice with no clientid if trying to add duplicate client
  {
    echo '<h2><font color=' . $_SESSION['ds_alertcolor'] . '>Ne peut pas enregistrer, il n\'y a pas de client</font></h2>';
  }
  elseif ($isreturn == 1 && !empty($returnreasonA) && $returnreasonid == 0)
  {
    echo '<h2><font color=' . $_SESSION['ds_alertcolor'] . '>Ne peut pas enregistrer, il n\'y a pas raison d\'avoir</font></h2>';
  }
  elseif ($_SESSION['ds_usedelivery'] > 0
  && $localvesselid == 0
  && $deliverytypeid == 1
  && $islandid > 1
  && $_SESSION['ds_term_localvessel'] != '') # need delivery boat outside Tahiti
  {
    echo '<h2><font color=' . $_SESSION['ds_alertcolor'] . '>Ne peut pas enregistrer, il n\'y a pas de ' . d_output($_SESSION['ds_term_localvessel']) . '</font></h2>';
  }
  elseif ($modifyinvoiceid > 0)
  {
    $thisinvoicehasbeensaved = 1;
    echo '<h2>' . $invoicedescr . $modifyinvoiceid . ' modifié';
    if ($invoicedescr == 'Facture ') { echo 'e'; }
    if ($confirmme == 1) { echo ' et confirmée'; }
    echo ' &nbsp; <a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $modifyinvoiceid . '" target=_blank>Afficher</a>';
    if (!$confirmme) { echo ' &nbsp; <a href="sales.php?salesmenu=invoicing&modify=1&invoiceid=' . $modifyinvoiceid . '">Modifier</a>'; }
    if ($_SESSION['ds_uselocalbol'] == 1 && $_SESSION['ds_nolocalbol'] == 0)
    {
      echo ' &nbsp; <a href="reportwindow.php?report=showlocalbolnew&invoiceid=' . $modifyinvoiceid . '" target=_blank>Connaissement</a>';
    }
    if (isset($rowREAD['cancelledid']) && $rowREAD['cancelledid'] == 2) { echo ' (Archivé(e))'; }
    echo '</h2>';
    $payment_invoiceid = $modifyinvoiceid;
  }
  else
  {
    $thisinvoicehasbeensaved = 1;

    $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
    $query_prm = array();
    require('inc/doquery.php');

    $invoiceid = $query_insert_id;
    if ($invoiceid < 1) { echo '<p class=alert>critical error attributing invoiceid</p>'; exit; }

    $query = 'insert into invoice (invoiceid,matchingid,cancelledid,invoicegroupid,confirmed) values (?,0,0,0,0)';
    if ($confirmme == 1) { $query = 'insert into invoice (invoiceid,matchingid,cancelledid,invoicegroupid,confirmed) values (?,0,0,0,1)'; }
    $query_prm = array($invoiceid);
    require('inc/doquery.php');


    echo '<h2>' . $invoicedescr . $invoiceid . ' enregistré';
    if ($invoicedescr == 'Facture ') { echo 'e'; }
    echo ' &nbsp; <a href="printwindow.php?report=showinvoice&usedefaultstyle=1&invoiceid=' . $invoiceid . '" target=_blank>Afficher</a>';
    if (!$confirmme) { echo ' &nbsp; <a href="sales.php?salesmenu=invoicing&modify=1&invoiceid=' . $invoiceid . '">Modifier</a>'; }
    if ($_SESSION['ds_uselocalbol'] == 1 && $_SESSION['ds_nolocalbol'] == 0)
    {
      echo ' &nbsp; <a href="reportwindow.php?report=showlocalbolnew&invoiceid=' . $invoiceid . '" target=_blank>Connaissement</a>';
    }
    echo '</h2>';
    $payment_invoiceid = $invoiceid;
  }
  
  $ok = 0;
  if ($_SESSION['ds_canpayments']) { $ok = 1; }
  if ($thisinvoicehasbeensaved && $_SESSION['ds_invoicedirecttopayment'] && $ok)
  {
    $reimburse = 0; $payment_text = 'Paiement';
    if ($isreturn) { $reimburse = 1; $payment_text = 'Remboursement'; }
    
    $paymenttypeid = 1;
    if ($_SESSION['ds_defpaymenttypeid'] == 2) { $paymenttypeid = 2; }
    if ($_SESSION['ds_defpaymenttypeid'] == 4) { $paymenttypeid = 4; }
    
    $showamount = ($_POST['totaltopay']+0); if ($showamount <= 0) { $showamount = ''; }
    # TODO
    # bug doesn't show just added products (need to Mettre à jour first)
    # to fix all output needs to be buffered and all calculations performed first (separate logic and presentation) MAJOR CHANGE

    echo '<br><h2>'.$payment_text.' immédiat';
    if ($_SESSION['ds_canpayments'])
    {
      $link = 'sales.php?salesmenu=payment&clientid=' . $clientid; if ($showamount > 0) { $link .= '&amount='.$showamount; }
      echo ' &nbsp; <a href="'.$link.'" target=_blank>Autre paiement</a>';
    }
    echo '</h2>';
    echo '<form method="post" action="sales.php"><table>';
    echo '<input type=hidden name="client" value="' . $clientid . '">
    <input type=hidden name="forinvoiceid" value="' . $payment_invoiceid . '">
    <input type=hidden name="directfrominvoice" value="1">';
    echo '<tr><td><input type="radio" name="paymenttypeid" value="1"';
    if ($paymenttypeid == 1) { echo ' checked'; }
    echo '>Espèces</td><td></td></tr>';
    echo '<tr><td><input type="radio" name="paymenttypeid" value="2"';
    if ($paymenttypeid == 2) { echo ' checked'; }
    echo '>Chèque</td><td></td></tr>';
    echo '<tr><td><input type="radio" name="paymenttypeid" value="3"';
    if ($paymenttypeid == 3) { echo ' checked'; }
    echo '>Virement</td><td></td></tr>';
    if ($reimburse == 0)
    {
      echo '<tr><td><input type="radio" name="paymenttypeid" value="4"';
      if ($paymenttypeid == 4) { echo ' checked'; }
      echo '>Carte Bancaire</td><td></td></tr>';
    }
    if ($_SESSION['ds_customname'] == 'ANIMALICE')
    {
      echo '<tr><td><input type="radio" name="paymenttypeid" value="9"';
      if ($paymenttypeid == 9) { echo ' checked'; }
      echo '>AMEX</td><td></td></tr>';
    }
    if ($_SESSION['invoicedirecttopayment_overpay'])
    {
      echo '<tr><td><td>Montant à payer: &nbsp; '.myfix($showamount);
      $showamount = '';
    }
    echo '<tr><td>Montant:</td><td><input autofocus type="text" STYLE="text-align:right" name="value" value="' . $showamount . '" size=20> XPF';
    if (!$_SESSION['invoicedirecttopayment_overpay'])
    {
      echo '<tr><td>Monnaie rendue:</td><td><input type="text" STYLE="text-align:right" name="reimbursed" size=20> XPF';
    }
    $datename = 'select';
    require('inc/datepickerresult.php');
    if ($_SESSION['ds_persistentdates']) { $selecteddate = $select; }
    echo '<tr><td>Date:</td><td>';
    $datename = 'paymentdate';
    require('inc/datepicker.php');
    echo '<tr><td>Commentaire:</td><td><input type="text" STYLE="text-align:right" name="paymentcomment" size=70></td></tr>';
    echo '<tr><td>Banque:</td><td><select name="bankid"><option value=0></option>';

    $query = 'select bankid,bankname from bank order by bankname';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row = $query_result[$i];
      if ($row['bankid'] == $_POST['bankid']) { echo '<option value="' . $row['bankid'] . '" SELECTED>' . $row['bankname'] . '</option>'; }
      else { echo '<option value="' . $row['bankid'] . '">' . $row['bankname'] . '</option>'; }
    }
    echo '</select></td></tr>';
    echo '<tr><td>Chèque numéro:</td><td><input type="text" STYLE="text-align:right" name="chequeno" size=20></td></tr>';
    echo '<tr><td>Tireur:</td><td><input type="text" STYLE="text-align:right" name="payer" size=50></td></tr>';
    echo '<input type=hidden name="reimburse" value="'.$reimburse.'">';
    echo '<tr><td align=center><input type=hidden name="step" value="0"><input type=hidden name="salesmenu" value="payment"><input type=hidden name="employeeid" value="' . $employeeid . '">';
    echo '<label class="search">&nbsp;</label><button type="submit">Enregistrer</button></td><td>&nbsp;</td></tr>';
    echo '</table></form>';
  }
}

echo '<form method="post" action="sales.php"><table valign=top><tr><td valign=top><table><tr><td>';
$canaddclient = 1;
if ($_SESSION['ds_customname'] == 'Wing Chong')
{
  $canaddclient = 0; # TODO option  consider default 0
}
$dp_noblockedclients = 1; $dp_nosuspendedclients = 1; $dp_allowpopup = 1;
require('inc/selectclient.php');

$query = 'select extraaddressid,address,postaladdress,postalcode,townid from extraaddress where clientid=? and deleted<>1'; # todo 
$query_prm = array($clientid);
require('inc/doquery.php');
if ($num_results > 0)
{
  require('preload/town.php');
  require('preload/island.php');
  echo '<select name="extraaddressid"><option value="0">' . $addresstodisplay . '</option>';
  for ($iy=0; $iy < $num_results; $iy++)
  {
    $row49 = $query_result[$iy];
    $temp_townid = $row49['townid']; echo $townid;
    $temp_townname = $townA[$temp_townid];
    $temp_islandid = $town_islandidA[$temp_townid];
    $temp_islandname = $islandA[$temp_islandid];
    $addresstodisplay = $row49['address']; if ($row49['address'] != "") { $addresstodisplay = $addresstodisplay . ','; }
    $addresstodisplay = $addresstodisplay . ' ' . $row49['postaladdress']; if ($row49['postaladdress'] != "") { $addresstodisplay = $addresstodisplay . ','; }
    $addresstodisplay = $addresstodisplay . ' ' . $row49['postalcode'] . ' ' . $temp_townname . ', ' . $temp_islandname;
    if ($row49['extraaddressid'] == $extraaddressid) { echo '<option value="' . $row49['extraaddressid'] . '" SELECTED>' . $addresstodisplay . '</option>'; }
    else { echo '<option value="' . $row49['extraaddressid'] . '">' . $addresstodisplay . '</option>'; }
  }
  echo '</select>';
}
else
{
  echo ' &nbsp; ' . $addresstodisplay;
}
echo '<tr><td>Type:</td><td>';

if ($invoicetype == 2) { $proforma = 1; $isnotice = 0; $isreturn = 0; $returntostock = 0; }
elseif ($invoicetype == 3) { $proforma = 0; $isnotice = 1; $isreturn = 0; $returntostock = 0; }
elseif ($invoicetype == 4) { $proforma = 0; $isnotice = 0; $isreturn = 1; $returntostock = 0; }
elseif ($invoicetype == 6) { $proforma = 0; $isnotice = 1; $isreturn = 1; $returntostock = 0; }
if (empty($returnreasonA)) { if ($invoicetype == 5) { $proforma = 0; $isnotice = 0; $isreturn = 1; $returntostock = 1; } } # TODO check logic here???
elseif ($returnreasonid > 0) { $returntostock = $returnreason_returntostockA[$returnreasonid]; }
else { $returntostock = 0; }

echo '<select name="invoicetype">';
echo '<option value=1>'.$_SESSION['ds_term_invoice'].'</option>';
if ($_SESSION['ds_usenotice']) { echo '<option value=3'; if ($isnotice) { echo ' selected'; } ; echo '>' . d_output($_SESSION['ds_term_invoicenotice']) . '</option>'; }
echo '<option value=2'; if ($proforma) { echo ' selected'; } ; echo '>Proforma</option>';
if ($_SESSION['ds_canreturns'])
{
  echo '<option value=4'; if ($isreturn && !$isnotice) { echo ' selected'; } ; echo '>Avoir</option>';
  if (empty($returnreasonA)) { echo '<option value=5'; if ($returntostock) { echo ' selected'; } ; echo '>Avoir/Remettre stock</option>'; }
}
if ($_SESSION['ds_usenotice'])
{
  echo '<option value=6'; if ($isreturn && $isnotice) { echo ' selected'; } ;
  echo '>Avoir (' . d_output($_SESSION['ds_term_invoicenotice']) . ')</option>';
}
echo '</select>';

if (!empty($returnreasonA))
{
  $dp_itemname = 'returnreason'; $dp_selectedid = $returnreasonid; $dp_notable = 1;
  require('inc/selectitem.php');
}
elseif ($isreturn) { echo ' <span class="alert">(Raisons à définir dans <b>Admin</b>)</span>'; }

if ($_SESSION['ds_term_accountingdate'] != "") { echo '<tr><td>' . $_SESSION['ds_term_accountingdate'] . ':</td><td>'; }
else { echo '<tr><td>Date:</td><td>'; }
if ($_SESSION['ds_caninvoicedate'] == 1)
{
  $datename = 'select';
  if (isset($ourdate))
  {
    $selecteddate = $ourdate;
  }
  else
  {
    unset($selecteddate);
  }
  if ($_SESSION['ds_noretrodates']) { $dp_datepicker_min = $_SESSION['ds_curdate']; }
  require('inc/datepicker.php');
}
else { echo datefix2($_SESSION['ds_curdate']); }
if ($_SESSION['ds_usedelivery'] > 0)
{
  $dp_itemname = 'deliverytype'; $dp_selectedid = $deliverytypeid; $dp_noblank = 1; $dp_notable = 1;
  require('inc/selectitem.php');
}
if (isset($advanceA))
{
  $dp_itemname = 'advance'; $dp_selectedid = $advanceid; $dp_notable = 1;
  require('inc/selectitem.php');
}

if ($_SESSION['ds_hidedeliverydate'] == 1)
{
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
  if (isset($ourdate)) { $deliverydate = $ourdate; }
}
else
{
  if ($_SESSION['ds_term_deliverydate'] != "") { echo '<tr><td>' . $_SESSION['ds_term_deliverydate'] . ':</td><td>'; }
  else { echo '<tr><td>Date livraison:</td><td>'; }
  $datename = 'delivery';
  if (isset($deliverydate))
  {
    $selecteddate = $deliverydate;
  }
  else
  {
    unset($selecteddate);
  }
  if ($_SESSION['ds_noretrodates']) { $dp_datepicker_min = $_SESSION['ds_curdate']; }
  require('inc/datepicker.php');
  echo '</td></tr>';
}
if ($_SESSION['ds_paybydateselect'] == 1)
{
  echo '<tr><td>Date d\'échéance:</td><td>';
  $datename = 'paybydate';
  if (isset($paybydate))
  {
    $selecteddate = $paybydate;
  }
  else
  {
    unset($selecteddate);
  }
  if ($_SESSION['ds_noretrodates']) { $dp_datepicker_min = $_SESSION['ds_curdate']; }
  require('inc/datepicker.php');
}
/* TODO show paybydate
elseif (isset($ourdate) && isset($daystopay))
{
  $paybydate = strtotime ( '+' . $daystopay . ' day' , strtotime ( $ourdate ) ) ; # TODO do not use strtotime
  $paybydate = date ( 'Y-m-j' , $paybydate );
  echo '<tr><td>Date d\'échéance:<td>', datefix($paybydate,'short');
}
*/
if ($_SESSION['ds_term_custominvoicedate'] != '')
{
  echo '<tr><td>' . $_SESSION['ds_term_custominvoicedate'] . ':</td><td>';
  $datename = 'custominvoicedate';
  if (isset($custominvoicedate))
  {
    $selecteddate = $custominvoicedate;
  }
  else
  {
    unset($selecteddate);
  }
  if ($_SESSION['ds_noretrodates']) { $dp_datepicker_min = $_SESSION['ds_curdate']; }
  require('inc/datepicker.php');
}

if ($_SESSION['ds_useinvoicetag'] == 1)
{
  echo '<tr><td>';
  if ($_SESSION['ds_term_invoicetag'] != "")
  {
    if ($_SESSION['ds_term_invoicetag'] != "") { echo d_output($_SESSION['ds_term_invoicetag']); }
    else { echo 'Tag'; }
    echo ':<td><select name="invoicetagid">';
    if ($_SESSION['ds_customname'] != 'Terevau') { echo '<option value="0"></option>'; } #TODO option

    $query = 'select invoicetagid,invoicetagname from invoicetag
    where deleted=0 and (invoicetag_clientid=0 or invoicetag_clientid=?) order by invoicetagname';
    $query_prm = array($clientid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['invoicetagid'] == $invoicetagid) { echo '<option value="' . $row2['invoicetagid'] . '" SELECTED>' . d_output($row2['invoicetagname']) . '</option>'; }
      else { echo '<option value="' . $row2['invoicetagid'] . '">' . d_output($row2['invoicetagname']) . '</option>'; }
    }
    echo '</select>';
  }
  
  echo '<tr><td>';
  if ($_SESSION['ds_term_invoicetag2'] != "")
  {
    echo d_output($_SESSION['ds_term_invoicetag2']);
    echo ':</td><td><select name="invoicetag2id"><option value="0"></option>';

    $query = 'select invoicetag2id,invoicetag2name from invoicetag2 where deleted=0 order by invoicetag2name';
    $query_prm = array();
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['invoicetag2id'] == $invoicetag2id) { echo '<option value="' . $row2['invoicetag2id'] . '" SELECTED>' . d_output($row2['invoicetag2name']) . '</option>'; }
      else { echo '<option value="' . $row2['invoicetag2id'] . '">' . d_output($row2['invoicetag2name']) . '</option>'; }
    }
    echo '</select>';
  }
}

echo '</table>';

echo '</td><td width=50></td><td>';

echo '<table>';

if ($_SESSION['ds_hide_invoice_fields'] != 1)
{
  $dp_itemname = 'employee'; $dp_issales = 1; $dp_description = 'Employé(e)'; $dp_selectedid = $employeeid;
  require('inc/selectitem.php');
}

if ($_SESSION['ds_term_invoiceclient2'] != '')
{
  $real_clientid = $clientid;
  
  if (isset($_POST['client2'])) { $client = $_POST['client2']; }
  else { $client = $clientid2; }
  if ($client == 0) { $client = ''; }
  echo '<tr><td>'.$_SESSION['ds_term_invoiceclient2'].':<td>'; $dp_description = '';
  $dp_addtoid = 2; $canaddclient = 0;
  require('inc/selectclient.php');
  $clientid2 = $clientid;
  
  $clientid = $real_clientid;
}
if ($_SESSION['ds_term_invoiceclient3'] != '')
{
  $real_clientid = $clientid;
  
  if (isset($_POST['client3'])) { $client = $_POST['client3']; }
  else { $client = $clientid3; }
  if ($client == 0) { $client = ''; }
  echo '<tr><td>'.$_SESSION['ds_term_invoiceclient3'].':<td>'; $dp_description = '';
  $dp_addtoid = 3; $canaddclient = 0;
  require('inc/selectclient.php');
  $clientid3 = $clientid;
  
  $clientid = $real_clientid;
}

if ($_SESSION['ds_term_extraname'] != "") { echo '<tr><td>' . $_SESSION['ds_term_extraname'] . ':</td><td>'; }
else { echo '<tr><td>Extension du Nom:</td><td>'; }
echo '<input type="text" STYLE="text-align:right" name="extraname" value="' . $extraname . '" size=20> &nbsp; ';
if (isset($localvesselA) && !empty($localvesselA) && $_SESSION['ds_hide_invoice_fields'] != 1)
{
  $query = 'select localvesselid,localvesselname from localvessel where deleted=0 order by localvesselname';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<tr><td>' . $_SESSION['ds_term_localvessel'] . ': </td><td><select name="localvesselid"><option value=0></option>';
    for ($i=0; $i < $num_results; $i++)
    {
      $row2 = $query_result[$i];
      if ($row2['localvesselid'] == $localvesselid) { echo '<option value="' . $row2['localvesselid'] . '" SELECTED>' . $row2['localvesselname'] . '</option>'; }
      else { echo '<option value="' . $row2['localvesselid'] . '">' . $row2['localvesselname'] . '</option>'; }
    }
    echo '</select></td></tr>';
  }
}

if ($_SESSION['ds_hide_invoice_fields'] != 1)
{
  if ($_SESSION['ds_term_reference'] != "") { echo '<tr><td>' . $_SESSION['ds_term_reference'] . ':</td><td>'; }
  else { echo '<tr><td>Référence à afficher:</td><td>'; }
  echo '<input type="text" STYLE="text-align:right" name="invoicereference" value="' . $invoicereference . '" size=20>';
  echo '</td></tr>';
}

if ($_SESSION['ds_term_field1'] != '')
{
  echo '<tr><td>' . $_SESSION['ds_term_field1'] . ':</td><td><input type="text" STYLE="text-align:right" name="field1" value="' . $field1 . '" size=20></td></tr>';
}
if ($_SESSION['ds_term_field2'] != '')
{
  echo '<tr><td>' . $_SESSION['ds_term_field2'] . ':</td><td><input type="text" STYLE="text-align:right" name="field2" value="' . $field2 . '" size=20></td></tr>';
}

if ($_SESSION['ds_useinvoicetag'] == 1)
{
  echo '<tr><td colspan=2>&nbsp;</td></tr>';
}
echo '</table>';
echo '</table>';

if ($proforma == "") { $proforma = 0; }
if ($isnotice == "") { $isnotice = 0; }
if ($isreturn == "") { $isreturn = 0; }

echo '<input type="checkbox" name="showcomments" value="1"';
if ($showcomments == 1) { echo ' checked'; }
echo '>';
echo '&nbsp; Commentaires: <input type="text" name="invoicecomment" value="' . $invoicecomment . '" size=80>';
if ($proforma == 1 && $invoicecomment2 == "" && $_SESSION['ds_proformadefaultcomment'] != "") { $invoicecomment2 = $_SESSION['ds_proformadefaultcomment']; }
if ($showcomments == 1 || $invoicecomment2 != "") { echo '<br><textarea type="textarea" name="invoicecomment2" cols=80 rows=2>' . $invoicecomment2 . '</textarea>'; }

if ($_SESSION['ds_use_loyalty_points'] && $use_loyalty_points)
{
  $points = $loyalty_start;
  
  $query = 'select givenrebate,linetaxcodeid,lineprice,linevat,isreturn,rebate_type
  from invoiceitemhistory,invoicehistory
  where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
  and clientid=? and cancelledid=0 and confirmed=1 and isreturn=0';
  $query_prm = array($clientid);
  require('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    if ($query_result[$i]['givenrebate'] > 0)
    {
      if ($query_result[$i]['rebate_type'] == 3)
      {
        $kladd = round($query_result[$i]['givenrebate'] + ($query_result[$i]['givenrebate'] * $taxcodeA[$query_result[$i]['linetaxcodeid']] / 100)); #echo ' -',$kladd;
        if ($query_result[$i]['isreturn'] == 1) { $points += $kladd; }
        else { $points -= $kladd; }
      }
    }
    else
    {
      $kladd = round(($query_result[$i]['lineprice'] + $query_result[$i]['linevat']) * $_SESSION['ds_loyalty_points_percent'] / 100); #echo ' +',$kladd;
      if ($query_result[$i]['isreturn'] == 1) { $points -= $kladd; }
      else { $points += $kladd; }
    }
  }

  $points = round($points);
  
  if ($points > 0) { echo '<br><center>Points de fidelité : ',myfix($points),'</center>'; }
}

echo '<table class="detailinput"><tr>';
echo '<td align=right>Lignes: <input type='.$input_number.' STYLE="text-align:right" name="invoicelines" size=4 min="1" max="1000" step="1" value="' . $invoicelines . '"></td>';

if ($_SESSION['ds_useitemadd'])
{
  echo '<td><b>Date</b></td><td><b>Début</b></td><td><b>Fin</b></td><td><b>Employé</b></td>';
}
if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><b>Code<td><b>Volume'; }
else { echo '<td><b>Produit<td><b>Quantité'; }
if ($_SESSION['ds_use_salesprice_mod'])
{
  require('preload/invoice_priceoption1.php');
  if (isset($invoice_priceoption1A)) { echo '<td><b>',$_SESSION['ds_term_invoice_priceoption1']; $colspan_mod++; }
  require('preload/invoice_priceoption2.php');
  if (isset($invoice_priceoption1A)) { echo '<td><b>',$_SESSION['ds_term_invoice_priceoption2']; $colspan_mod++; }
  require('preload/invoice_priceoption3.php');
  if (isset($invoice_priceoption1A)) { echo '<td><b>',$_SESSION['ds_term_invoice_priceoption3']; $colspan_mod++; }
}
if ($_SESSION['ds_useserialnumbers']) { echo '<td><b>No Serie</b></td>'; }
if ($_SESSION['ds_useunits']) { echo '<td colspan=2><b>Sous-unités</b></td>'; }
if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td><b>Stock</b></td>'; }
echo '<td><b>Produit';
if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><b>Poids<td><b>Quantité';echo '<td><b>Valeur déclaré'; }
echo '<td><b>Prix';
if ($_SESSION['ds_enterttcq'] === 1) { echo '<td><b>Prix TTC/Q.</td>'; }
if ($_SESSION['can_rebate_invoice']) { echo '<td><b>Remise</b>'; }
if ($_SESSION['ds_showlinevat']) { echo '<td><b>TVA</b></td>'; }
echo'<td><b>Total</b></td></tr>';

for ($i=1; $i <= $invoicelines; $i++)
{
  if (!isset($_POST['percentrebate' . $i])) { $rebate_type[$i] = $_SESSION['ds_defrebate_type']; }
  else { $rebate_type[$i] = (int) $_POST['percentrebate' . $i]; }
  if (isset($_POST['invoiceitemid'.$i])) { $invoiceitemid[$i] = $_POST['invoiceitemid'.$i]; }
  else { $invoiceitemid[$i] = 0; }
  if (isset($_POST['productid'.$i])) { $productidA['productid' . $i] = $_POST['productid' . $i]; }
  else { $productid[$i] = 0; }
  ### 2017 02 13 find product DOES NOT WORK WITH SUPPLIERCODE (TODO rename $productcode[$i] to $productcodeA[$i], test)
  if ($_SESSION['ds_useproductcode'] != 1 && isset($_POST['productid'.$i]))
  {
    $product = $productidA['productid' . $i];
    require('inc/findproduct.php');
    if (isset($productid)) { $productidA['productid' . $i] = $productid; }
  }
  ### TODO should use findproduct
  if ($_SESSION['ds_useproductcode'] == 1 && isset($productidA['productid' . $i]) && $productidA['productid' . $i] != "")
  {
    $query = 'select productid,suppliercode from product where suppliercode=? order by suppliercode limit 1';
    $query_prm = array($productidA['productid' . $i]);
    require('inc/doquery.php');
    if ($num_results)
    {
      $productidA['productid' . $i] = $query_result[0]['productid'];
      $productcode[$i] = $query_result[0]['suppliercode'];
    }
    else
    {
      $query = 'select productid,suppliercode from product where suppliercode like ? or eancode=? order by suppliercode limit 1';
      $query_prm = array('%' . $productidA['productid' . $i] . '%', $productidA['productid' . $i]);
      require('inc/doquery.php');
      if ($num_results)
      {
        $productidA['productid' . $i] = $query_result[0]['productid'];
        $productcode[$i] = $query_result[0]['suppliercode'];
      }
      else
      {
        $productidA['productid' . $i] = '';
        $productcode[$i] = '';
      }
    }
  }
  if (!isset($_POST['quantity' . $i])) { $quantityA['quantity' . $i] = $_SESSION['ds_definvoicequantity']; }
  else { $quantityA['quantity' . $i] = $_POST['quantity' . $i]; }
  if (isset($_POST['unitorcarton'.$i])) { $unitorcartonA['unitorcarton' . $i] = $_POST['unitorcarton' . $i]; }
  else { $unitorcartonA['unitorcarton' . $i] = 0; }
  if (isset($_POST['itemaddvalue'.$i]))
  {
    $itemaddvalue[$i] = $_POST['itemaddvalue' . $i];
  }
  else { $itemaddvalue[$i] = 0; }
  
  if ($_SESSION['ds_useserialnumbers'] || $_SESSION['ds_uselocalbol'] == 2)
  {
    if (isset($_POST['serial' . $i])) { $serial[$i] = $_POST['serial' . $i]; }
    else { $serial[$i] = ''; }
  }
  if ($_SESSION['ds_uselocalbol'] == 2)
  {
    if (isset($_POST['retailprice' . $i])) { $retailprice[$i] = $_POST['retailprice' . $i]; }
    else { $retailprice[$i] = 0; }
    if (isset($_POST['unittype_line'.$i.'id'])) { $unittype_lineid[$i] = $_POST['unittype_line'.$i.'id']; }
    else { $unittype_lineid[$i] = 0; }
  }
}
for ($i=1; $i <= $invoicelines; $i++)
{
  $temp_allowdecimals = 0; $allownongeneric = 1;
  if ($isreturn == 1 && $_SESSION['ds_returnproductsaregeneric'] == 1) { $allownongeneric = 0; $temp_allowdecimals = 2; }

  if ($_SESSION['ds_select_itemcomment'] && isset($_POST['select_itemcomment'.$i.'id']))
  {
    $select_itemcommentid[$i] = $_POST['select_itemcomment'.$i.'id'];
  }
  if ($_SESSION['ds_select_itemcomment'] && isset($_POST['colorid'.$i]))
  {
    $colorid[$i] = $_POST['colorid'.$i];
  }
  if (isset($_POST['itemcomment'.$i])) { $itemcomment['itemcomment'.$i] = $_POST['itemcomment'.$i]; }
  else { $itemcomment['itemcomment' . $i] = ''; }

  if (!isset($productidA['productid' . $i]) || $productidA['productid' . $i] == 0) { $productidA['productid' . $i] = ""; }
  if ($quantityA['quantity' . $i] == 0)
  {
    if ($_SESSION['ds_customname'] == 'A9') { $quantityA['quantity'.$i] = 1; } # TODO option
    else { $quantityA['quantity'.$i] = ""; }
  }
  if (!isset($_POST['unitorcarton' . $i])) { $unitorcartonA['unitorcarton' . $i] = 0; }
  
  if (isset($_POST['invoice_priceoption1'.$i.'id']))
  { $invoice_priceoption1id[$i] = (int) $_POST['invoice_priceoption1'.$i.'id']; }
  if (isset($_POST['invoice_priceoption2'.$i.'id']))
  { $invoice_priceoption2id[$i] = (int) $_POST['invoice_priceoption2'.$i.'id']; }
  if (isset($_POST['invoice_priceoption3'.$i.'id']))
  { $invoice_priceoption3id[$i] = (int) $_POST['invoice_priceoption3'.$i.'id']; }

  if ($postinvoiceid && $num_resultsLINES >= $i && !$newinv)
  {
    $rowREADLINES = $resultLINES[($i-1)];
    $invoice_priceoption1id[$i] = $rowREADLINES['invoice_priceoption1id'];
    $invoice_priceoption2id[$i] = $rowREADLINES['invoice_priceoption2id'];
    $invoice_priceoption3id[$i] = $rowREADLINES['invoice_priceoption3id'];
    $itemaddvalue[$i] = $rowREADLINES['linevalue']+0;
    $invoiceitemid[$i] = $rowREADLINES['invoiceitemid']+0;
    $productidA['productid' . $i] = $rowREADLINES['productid'] + 0;
    if ($_SESSION['ds_useproductcode'] == 1 && $productidA['productid' . $i] != "")
    {
      $query = 'select suppliercode from product where productid="' . $productidA['productid' . $i] . '"';
      $query_prm = array();
      require('inc/doquery.php');
      $row = $query_result[0];
      $productcode[$i] = $row['suppliercode'];
    }
    $quantityA['quantity' . $i] = $rowREADLINES['quantity'] + 0;
    
    # TODO why exception for bulle sucré returns?
    if ($rowREADLINES['numberperunit'] > 1 && $_SESSION['ds_customname'] == 'La Bulle Sucrée' && $isreturn)
    {
      $quantityA['quantity' . $i] = $quantityA['quantity' . $i] / $rowREADLINES['numberperunit'];
    }
    
    if ($rowREADLINES['displaymultiplier'] != 1 && $rowREADLINES['displaymultiplier'] != 0)
    {
      $quantityA['quantity' . $i] = $quantityA['quantity' . $i] / $rowREADLINES['displaymultiplier'];
    }
    if ($quantityA['quantity' . $i] == 0) { $quantityA['quantity' . $i] = ""; }
    $itemcomment['itemcomment' . $i] = $rowREADLINES['itemcomment'];
    if ($_SESSION['ds_select_itemcomment'])
    {
      $select_itemcommentid[$i] = array_search($rowREADLINES['serial'],$select_itemcommentA);
      if (substr($rowREADLINES['serial'],0,7) == 'colorid')
      { $colorid[$i] = substr($rowREADLINES['serial'],7); }
    }
    $serial[$i] = $rowREADLINES['serial'];
    $unittype_lineid[$i] = $rowREADLINES['lineproducttypeid'];
    $retailprice[$i] = $rowREADLINES['retailprice'];
  }

  echo '<tr><td><input type=hidden name="invoiceitemid'.$i.'" value="'.$invoiceitemid[$i].'">' . $i . '.';
  
  $group_optionalA[$i] = 0;
  if ($_SESSION['ds_use_invoiceitemgroup'])
  {
    $groupnumberA[$i] = 0; $grouptitleA[$i] = '';
    if ($invoiceitemid[$i] > 0)
    {
      $query = 'select invoiceitemgroupnumber,invoiceitemgrouptitle,is_optional from invoiceitemgroup where invoiceitemid=? limit 1';
      $query_prm = array($invoiceitemid[$i]);
      require('inc/doquery.php');
      if ($num_results)
      {
        $groupnumberA[$i] = $query_result[0]['invoiceitemgroupnumber'];
        $grouptitleA[$i] = $query_result[0]['invoiceitemgrouptitle'];
        $group_optionalA[$i] = $query_result[0]['is_optional'];
      }
    }
    if (isset($_POST['groupnumber'.$i]))
    {
      $groupnumberA[$i] = (double) $_POST['groupnumber'.$i];
      $grouptitleA[$i] = $_POST['grouptitle'.$i];
      if (isset($_POST['group_optional'.$i]))
      {
        $group_optionalA[$i] = (int) $_POST['group_optional'.$i];
      }
      else { $group_optionalA[$i] = 0; }
    }
    echo ' &nbsp; <input type=number min=0 step=0.01 name="groupnumber'.$i.'" value="'.$groupnumberA[$i].'">
    <input type=text name="grouptitle'.$i.'" value="'.$grouptitleA[$i].'"><input type=checkbox name="group_optional'.$i.'" value=1';
    if ($group_optionalA[$i]) { echo ' checked'; }
    echo '>';
  }
  
  if ($_SESSION['ds_useitemadd'])
  {
    echo '<td>';
    $datename = 'linedate' . $i;
    require('inc/datepickerresult.php');
    #$linedate[$i] = $datepicker_date;
    $linedate[$i] = $$datename;
    $selecteddate = $linedate[$i];
    if (isset($rowREADLINES['linedate'])) { $selecteddate = $rowREADLINES['linedate']; }
    $datename = 'linedate' . $i;
    require('inc/datepicker.php');
    echo '</td><td>&nbsp;</td><td>&nbsp;</td><td>';
    $dp_itemname = 'employee'; $dp_addtoid = $i; $dp_issales = 1; $dp_description = '';
    if (isset($_POST[$dp_itemname . $dp_addtoid . 'id'])) { $lineemployeeid[$i] =  $_POST[$dp_itemname . $dp_addtoid . 'id']; }
    else { $lineemployeeid[$i] = 0; }
    $dp_selectedid = $lineemployeeid[$i];
    if (isset($rowREADLINES['employeeid'])) { $dp_selectedid = $rowREADLINES['employeeid']; }
    require('inc/selectitem.php');
    echo '</td>';
  }

  if ($_SESSION['ds_userrepresentsclientid'])
  {
    $query = 'select supplierid from product where productid=?';
    $query_prm = array($productidA['productid' . $i]);
    require('inc/doquery.php');
    if (!isset($query_result[0]['supplierid']) || $query_result[0]['supplierid'] != $_SESSION['ds_userrepresentsclientid']) { $productidA['productid' . $i] = ''; }
  }

  if ($productidA['productid' . $i] != "")
  {
    $query = 'select quantity_convert,only_quantity_rebate,retailprice,weight,volume,producttypeid,generic,temperatureid
    ,currentstock,currentstockrest,productname,islandregulatedprice,salesprice,detailsalesprice,countstock
    ,unitsalesprice,unitdetailsalesprice,netweightlabel,numberperunit,unittypename,displaymultiplier,taxcode
    ,product.taxcodeid,notforsale,currentpurchasebatchid,defaultitemcomment,productfamilyid,no_client_discount,paletteid
    ,invoice_priceoption2_filterid
    from product,unittype,taxcode
    where product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid
    and productid=? limit 1';
    $query_prm = array($productidA['productid' . $i]);
    require('inc/doquery.php');
    $paletteid[$i] = $query_result[0]['paletteid'];
    
    $row = $query_result[0];
    
    $invoice_priceoption2_filterid[$i] = $row['invoice_priceoption2_filterid'];
    $no_client_discount[$i] = $row['no_client_discount'];
    ###
    $discount_from_post[$i] = 0;
    if (isset($_POST['discount'.$i]))
    {
      $discountA['discount' . $i] = $_POST['discount' . $i];
      if ($discountA['discount' . $i] != '') { $discount_from_post[$i] = 1; }
    }
    else { $discountA['discount' . $i] = 0; }
    if(isset($productidA['productid' . $i]) && $productidA['productid' . $i] > 0
    && isset($default_discount) && $default_discount > 0
    && $discount_from_post[$i] == 0)
    {
      echo $no_client_discount[$i]; # HERE TODO not set
      if (isset($no_client_discount[$i]) && $no_client_discount[$i] == 1) { }
      else { $discountA['discount' . $i] = $default_discount; $rebate_type[$i] = 1; }
    }
    ###
    if ($discountA['discount' . $i] == 0 && $discount_from_post[$i] == 0) { $discountA['discount' . $i] = ""; }
    
    $productfamilyidA[$i] = $row['productfamilyid'];
    if ($itemcomment['itemcomment' . $i] == '') { $itemcomment['itemcomment' . $i] = $row['defaultitemcomment']; }
    $volumeA[$productidA['productid' . $i]] = $row['volume']; # for pricealgorithm
    $currentpurchasebatchid[$i] = $row['currentpurchasebatchid'];
    $producttypeid[$i] = $row['producttypeid'];
    if ($_SESSION['ds_useretailprice']) { $retailprice[$i] = $row['retailprice']; }
    $displaymultiplier[$i] = $row['displaymultiplier'];
    if ($displaymultiplier[$i] == 1) { $quantityA['quantity' . $i] = floor($quantityA['quantity' . $i]); }
    $linetaxcodeid[$i] = $row['taxcodeid'];
    $generic = $row['generic'];
    $only_quantity_rebate[$i] = $row['only_quantity_rebate'];
    $numberperunit[$i] = $row['numberperunit'];
    $quantity_convert[$i] = $row['quantity_convert'];
    $currentstock = $row['currentstock'];
    if ($_SESSION['ds_useunits'] && $row['currentstockrest']) { $currentstock = $currentstock . ' <font size=-1>' . $row['currentstockrest'] . '</font>'; }
    if ($_SESSION['ds_stockperuser'] && $_SESSION['ds_stockperthisuser'])
    {
      $productid = $productidA['productid' . $i]; $currentyear = mb_substr($_SESSION['ds_curdate'],0,4); $npu = $numberperunit[$i]; $dp_userid = $_SESSION['ds_userid'];
      require('inc/calcstock_user.php');
      $currentstock = $userstock . ' ['.$currentstock.']';
    }
    if ($row['countstock'] != 1) { $currentstock = ''; }
    $countstock[$i] = $row['countstock'];
    $taxcode = $row['taxcode']+0; $taxcode_prodA[$i] = $taxcode;
    $nontender = 1;
    if ($row['temperatureid'] > $hascold && $quantityA['quantity' . $i] > 0) { $hascold = $row['temperatureid']; }

    if ($unitorcartonA['unitorcarton' . $i] == 1 && ($quantityA['quantity' . $i] % $row['numberperunit'] == 0 && $isreturn == 0))
    {
      $quantityA['quantity' . $i] = $quantityA['quantity' . $i] / $row['numberperunit']; $unitorcartonA['unitorcarton' . $i] = 0;
    }

    if ($postinvoiceid && $num_resultsLINES >= $i)
    {
      $unitorcartonA['unitorcarton' . $i] = 1;
      if ($quantityA['quantity' . $i] % $row['numberperunit'] == 0 && $isreturn == 0)
      {
        $quantityA['quantity' . $i] = $quantityA['quantity' . $i] / $row['numberperunit'];
        $unitorcartonA['unitorcarton' . $i] = 0;
      }
    }

    if ($quantity_convert[$i] > 0 && $quantityA['quantity' . $i] > 0)
    {
      $new_quantity = ceil ($quantityA['quantity' . $i] / $quantity_convert[$i]);
      $itemcomment['itemcomment' . $i] = $new_quantity . ' carton';
      if ($new_quantity > 1) { $itemcomment['itemcomment' . $i] .= 's'; }
      $itemcomment['itemcomment' . $i] .= ' de '.($quantity_convert[$i]+0).' '.$row['unittypename'];
      $new_quantity *= $quantity_convert[$i];
      $quantityA['quantity' . $i] = $new_quantity;
    }

    $lineweight = d_multiply($quantityA['quantity' . $i],$row['weight']);
    if ($unitorcartonA['unitorcarton' . $i] == 1) { $lineweight = $lineweight/$numberperunit[$i]; }
    $invoiceweight = $invoiceweight + $lineweight;

    echo '<td align=right><input type="text" STYLE="text-align:right" name="productid' . $i . '" value="';
    if ($_SESSION['ds_useproductcode'] == 1) { echo $productcode[$i]; }
    else { echo $productidA['productid' . $i]; }
    echo '" id="product_autocomplete' . $i . '" autocomplete="off" size=' . $productfieldsize . '></td>';
    echo '<td align=right><input type='.$input_number.' STYLE="text-align:right" name="quantity' . $i . '"
    value="' . $quantityA['quantity' . $i] . '" size=8 min="0" step=any>';
    
    $pricemod = 0;
    if ($_SESSION['ds_use_salesprice_mod'])
    {
      if (isset($invoice_priceoption1A))
      {
        $dp_itemname = 'invoice_priceoption1'; $dp_noblank = 1; $dp_addtoid = $i; 
        if (isset($invoice_priceoption1id[$i]))
        {
          $pricemod += $invoice_priceoption1_salesprice_modA[$invoice_priceoption1id[$i]];
          $dp_selectedid = $invoice_priceoption1id[$i];
        }
        require('inc/selectitem.php');
      }
      if (isset($invoice_priceoption2A))
      {
        $dp_itemname = 'invoice_priceoption2'; $dp_noblank = 1; $dp_addtoid = $i;
        if (isset($invoice_priceoption2id[$i]))
        {
          $pricemod += $invoice_priceoption2_salesprice_modA[$invoice_priceoption2id[$i]];
          $dp_selectedid = $invoice_priceoption2id[$i];
        }
        if ($invoice_priceoption2_filterid[$i] > 0)
        {
          # only show options in filter 2020 06 30
          echo '<td><select name="invoice_priceoption2'.$i.'id">';
          $query = 'select * from invoice_priceoption2
          where invoice_priceoption2id in
          (select invoice_priceoption2id from invoice_priceoption2_filter_matrix where invoice_priceoption2_filterid=?)
          order by deleted,`rank`,invoice_priceoption2name';
          $query_prm = array($invoice_priceoption2_filterid[$i]);
          require('inc/doquery.php');
          for ($temp=0;$temp<$num_results;$temp++)
          {
            echo '<option value="'.$query_result[$temp]['invoice_priceoption2id'].'"';
            if ($query_result[$temp]['invoice_priceoption2id'] == $invoice_priceoption2id[$i]) { echo ' selected'; }
            echo '>'.d_output($query_result[$temp]['invoice_priceoption2name']).'</option>';
          }
          echo '</select>';
        }
        else { require('inc/selectitem.php'); }
      }
      if (isset($invoice_priceoption2A))
      {
        $dp_itemname = 'invoice_priceoption3'; $dp_noblank = 1; $dp_addtoid = $i;
        if (isset($invoice_priceoption3id[$i]))
        {
          $pricemod += $invoice_priceoption3_salesprice_modA[$invoice_priceoption3id[$i]];
          $dp_selectedid = $invoice_priceoption3id[$i];
        }
        require('inc/selectitem.php');
      }
    }

    if ($_SESSION['ds_useserialnumbers'])
    { echo '<td align=right><input type="text" STYLE="text-align:right" name="serial' . $i . '" value="' . $serial[$i] . '" size=15>'; }

  if ($generic == 0 && $allownongeneric == 1)
  {
    ### PRICE DETERMINATION ###   TODO move to separate module
    $price = $row['salesprice'];
    if ($usedetail) { $price = $row['detailsalesprice']; }
    $unitprice = $row['unitsalesprice'];
    if ($usedetail) { $unitprice = $row['unitdetailsalesprice']; }
    if ($row['islandregulatedprice'] > 0 && $outerisland == 1 && $producttypeA[$row['producttypeid']] == 'PGL')
    {
      $price = $row['islandregulatedprice'];
      if ($usedetail) { $unitprice = $row['islandregulatedprice'] / $row['numberperunit']; }
    }

    if ($displaymultiplier[$i] != 1) { $price = $price * $displaymultiplier[$i]; }

    if ($surcharge > 0) { $price = $price + myround($price * $surcharge / 100); }

    $query = 'select categoryprice as salesprice,percentagerebate
    from categorypricing
    where deleted=0 and (startdate IS NULL or (startdate<=curdate() and stopdate>=curdate())) and productid=? and clientcategoryid=? order by categorypricingid desc limit 1';
    $query_prm = array($productidA['productid' . $i], $clientcategoryid);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      if ($query_result[0]['salesprice'] > 0) { $price = $query_result[0]['salesprice']; }
      if ($query_result[0]['percentagerebate'] > 0 && ($clienthaschanged || $discount_from_post[$i] == 0)) { $discountA['discount' . $i] = $query_result[0]['percentagerebate']; $rebate_type[$i] = 1; }
    }
    
    $query = 'select categoryprice as salesprice,percentagerebate
    from categorypricing2
    where deleted=0 and (startdate IS NULL or (startdate<=curdate() and stopdate>=curdate())) and productid=? and clientcategory2id=? order by categorypricing2id desc limit 1';
    $query_prm = array($productidA['productid' . $i], $clientcategory2id);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      if ($query_result[0]['salesprice'] > 0) { $price = $query_result[0]['salesprice']; }
      if ($query_result[0]['percentagerebate'] > 0 && ($clienthaschanged || $discount_from_post[$i] == 0)) { $discountA['discount' . $i] = $query_result[0]['percentagerebate']; $rebate_type[$i] = 1; }
    }
    
    $query = 'select categoryprice as salesprice,percentagerebate
    from categorypricing3
    where deleted=0 and (startdate IS NULL or (startdate<=curdate() and stopdate>=curdate())) and productid=? and clientcategory3id=? order by categorypricing3id desc limit 1';
    $query_prm = array($productidA['productid' . $i], $clientcategory3id);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      if ($query_result[0]['salesprice'] > 0) { $price = $query_result[0]['salesprice']; }
      if ($query_result[0]['percentagerebate'] > 0 && ($clienthaschanged || $discount_from_post[$i] == 0)) { $discountA['discount' . $i] = $query_result[0]['percentagerebate']; $rebate_type[$i] = 1; }
    }

    $query = 'select regionprice,retailprice from regionpricing where deleted=0 and productid="' . $productidA['productid' . $i] . '" and regulationzoneid="' . $regulationzoneid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row2 = $query_result[0];
      if ($row2['regionprice'] > 0) { $price = $row2['regionprice']; }
      if ($row2['retailprice'] > 0 && $_SESSION['ds_useretailprice']) { $retailprice[$i] = $row2['retailprice']/$displaymultiplier[$i]; }
    }

    if ($outerisland == 1)
    {
      $query = 'select regionprice,retailprice from regionpricing where deleted=0 and productid="' . $productidA['productid' . $i] . '" and regulationzoneid=9999';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results > 0)
      {
        $row2 = $query_result[0];
        if ($row2['regionprice'] > 0) { $price = $row2['regionprice']; }
        if ($row2['retailprice'] > 0 && $_SESSION['ds_useretailprice']) { $retailprice[$i] = $row2['retailprice']/$displaymultiplier[$i]; }
      }
    }

    $query = 'select islandprice,retailprice from islandpricing where deleted=0 and productid="' . $productidA['productid' . $i] . '" and islandid="' . $islandid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row2 = $query_result[0];
      if ($row2['islandprice'] > 0) { $price = $row2['islandprice']; }
      if ($row2['retailprice'] > 0 && $_SESSION['ds_useretailprice']) { $retailprice[$i] = $row2['retailprice']/$displaymultiplier[$i]; }
    }

    $query = 'select salesprice,retailprice
    from clientpricing
    where deleted=0 and (fromdate IS NULL or (fromdate<=curdate() and todate>=curdate())) and productid=? and clientid=?
    order by clientpricingid desc limit 1';
    $query_prm = array($productidA['productid' . $i], $clientid);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $row2 = $query_result[0];
      $price = $row2['salesprice'];
      if ($row2['retailprice'] > 0 && $_SESSION['ds_useretailprice']) { $retailprice[$i] = $row2['retailprice']/$displaymultiplier[$i]; }
      # 2018 09 27 remove automatic discount if clientpricing found
      if ($discount_from_post[$i] == 0) { $discountA['discount' . $i] = 0; }
    }

    $query = 'select dateprice,rebate,rebate_type from datepricing
    where deleted=0 and productid=? and startdate<=? and stopdate>=?
    order by datepricingid';
    $query_prm = array($productidA['productid' . $i], $_SESSION['ds_curdate'], $_SESSION['ds_curdate']);
    require('inc/doquery.php');
    for ($y=0; $y < $num_results; $y++)
    {
      if ($query_result[$y]['dateprice'] > 0) { $price = $query_result[$y]['dateprice']; }
      if ($query_result[$y]['rebate'] > 0 && ($clienthaschanged || $discount_from_post[$i] == 0))
      {
        $discountA['discount' . $i] = $query_result[0]['rebate']+0;
        $rebate_type[$i] = $query_result[0]['rebate_type'];
      }
    }
    
    $price += $pricemod;

    $basecartonprice[$i] = $price; if ($isnotice) { $basecartonprice[$i] = 0; }

    if ($postinvoiceid && $num_resultsLINES >= $i)
    {
      if ($rowREADLINES['rebate_type'] == 1)
      {
        $bcpdivider = myround($rowREADLINES['basecartonprice']);
        if ($_SESSION['ds_useunits'] && $rowREADLINES['quantity']%$numberperunit[$i]) { $bcpdivider = myround($bcpdivider/$numberperunit[$i]); }
        if ($bcpdivider == 0) { $bcpdivider = 1; }
        if (($rowREADLINES['givenrebate'] + $rowREADLINES['lineprice']) == 0) { $discountA['discount'.$i] = 0; }
        else
        {
          $discountA['discount' . $i] = 100 * $rowREADLINES['givenrebate'] / ($rowREADLINES['givenrebate'] + $rowREADLINES['lineprice']);
        }
        $rebate_type[$i] = 1;
        $discountA['discount' . $i] = myround($discountA['discount' . $i],0);
        if ($discountA['discount' . $i] == 0) { $discountA['discount' . $i] = ""; }
      }
      elseif ($rowREADLINES['rebate_type'] == 2)
      {
        if ($rowREADLINES['givenrebate'] == 0) { $rebate_type[$i] = 0; }
        else
        {
          if ($unitorcartonA['unitorcarton' . $i]) { $discountA['discount' . $i] = $rowREADLINES['givenrebate'] / ($rowREADLINES['basecartonprice'] / $numberperunit[$i]); }
          else { $discountA['discount' . $i] = ($rowREADLINES['quantity'] / $numberperunit[$i]) - ($rowREADLINES['lineprice'] / $rowREADLINES['basecartonprice']); }
          $rebate_type[$i] = 2;
        }
      }
      elseif ($rowREADLINES['rebate_type'] == 0)
      {
        $discountA['discount' . $i] = $rowREADLINES['givenrebate']+0;
        if ($rowREADLINES['givenrebate'] == 0) { $rebate_type[$i] = 0; }
      }
    }

    if ($_SESSION['ds_useunits'])
    {
      if ($numberperunit[$i] > 1)
      {
        #echo '<td align=right>'.$row['unittypename'].' <input type="checkbox" name="unitorcarton' . $i . '" value="1"';
        #if ($unitorcartonA['unitorcarton' . $i]) { echo ' checked'; }
        #echo '>';
        echo '<script>
        function unitorcartonFunction' . $i . '() {
          var checkBox = document.getElementById("unitorcarton' . $i . '");
          var unittext = document.getElementById("unittext' . $i . '");
          var cartontext = document.getElementById("cartontext' . $i . '");
          if (checkBox.checked == true)
          {
            unittext.style.display = "block";
          } else
          {
            unittext.style.display = "none";
          }
          if (checkBox.checked == true)
          {
            cartontext.style.display = "none";
          } else
          {
            cartontext.style.display = "block";
          }
        }
        </script>';   
        echo '<td align=center><input type="checkbox" name="unitorcarton' . $i . '" value="1" id="unitorcarton' . $i . '" onclick="unitorcartonFunction' . $i . '()"';
        if ($unitorcartonA['unitorcarton' . $i]) { echo ' checked'; }
        echo '>
        <td><span id="unittext' . $i . '"';
        if (!$unitorcartonA['unitorcarton' . $i]) { echo ' style="display:none"'; }
        echo '>unité</span>
        <span id="cartontext' . $i . '"';
        if ($unitorcartonA['unitorcarton' . $i]) { echo ' style="display:none"'; }
        echo '>'.$row['unittypename'].'</span>';
      }
      else { echo '<td><td>',$row['unittypename']; }
    }

    if ($_SESSION['ds_dontshowstock'] != 1)
    {
      if ($countstock[$i] != 1) { echo '<td align=right>' . d_output($row['unittypename']); }
      elseif ($displaymultiplier[$i] > 1) { echo '<td align=right>' . ($currentstock/$displaymultiplier[$i]) . '&nbsp;',$row['unittypename'],'</td>'; }
      else { echo '<td align=right>' . $currentstock . '</td>'; }
    }
    
    if ($_SESSION['ds_uselocalbol'] == 2)
    {
      $retailprice[$i] = d_add($retailprice[$i],0);
      if ($itemaddvalue[$i] == 0) { $itemaddvalue[$i] = ''; }
      if ($retailprice[$i] == 0) { $retailprice[$i] = ''; }
      echo '<td><input type=number step=0.001 name="itemaddvalue'.$i.'" value="'.$itemaddvalue[$i].'">';
      echo '<td><input type=number step=1 name="serial'.$i.'" value="'.$serial[$i].'">'; # quantity
      $dp_itemname = 'unittype_line'; $dp_notable=1; $dp_addtoid = $i; 
      $dp_selectedid = $unittype_lineid[$i]; require('inc/selectitem.php');
      if (!isset($retailprice[$i])) { $retailprice[$i] = 0; }
      echo '<td><input type=number step=1 name="retailprice'.$i.'" value="'.$retailprice[$i].'">'; # declared value
    }
    
    $productname = d_decode($row['productname']) . ' ';
    if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productname = $productname . $row['numberperunit'] . ' x '; }
    $productname = $productname . $row['netweightlabel'];
    echo '<td class="breakme">' . $productname;
    if ($_SESSION['ds_select_itemcomment'])
    {
      if ($paletteid[$i] > 0)
      {
        if (!isset($colorid[$i])) { $colorid[$i] = 0; }
        echo '<select name="colorid'.$i.'"><option value=0></option>';
        $query = 'select palette_color_matrix.colorid,colorname
        from palette_color_matrix,color
        where palette_color_matrix.colorid=color.colorid
        and paletteid=? order by colorname';
        $query_prm = array($paletteid[$i]);
        require('inc/doquery.php');
        for ($temp=0; $temp < $num_results; $temp++)
        {
          echo '<option value='.$query_result[$temp]['colorid'];
          if ($colorid[$i] == $query_result[$temp]['colorid']) { echo ' selected'; }
          echo '>'.$query_result[$temp]['colorname'].'</option>';
        }
        echo '</select>';
      }
      else
      {
        $dp_selectedid = $select_itemcommentid[$i]; $dp_notable = 1;
        $dp_itemname = 'select_itemcomment'; $dp_addtoid = $i; require('inc/selectitem.php');
      }
    }
    $showdiscount = ''; $lineprice[$i] = 0;
    if ($row['notforsale'] == 1) { $price = 0; }
    if ($price == 0)
    {
      echo '<td><font color=red><i>Non disponible</i></font>';
      $quantityA['quantity' . $i] = 0;
      $enterttcqlocal = '';
    }
    
    if ($price > 0)
    {
      if ($unitorcartonA['unitorcarton' . $i])
      {
        if ($unitprice > 0) { $price = $unitprice; }
        else { $price = round($price / $numberperunit[$i]); }
      }
      echo '<td align=right>',($price+0);
      if ($_SESSION['ds_enterttcq'] === 1)
      {
        if(isset($_POST['enterttcqlocal' . $i])) { $enterttcqlocal = (int) $_POST['enterttcqlocal' . $i]; }
        else { $enterttcqlocal = 0; }
        $testprice = myround($price + ($price * $taxcode/100));
        if ($enterttcqlocal > 0 && $enterttcqlocal < $testprice)
        {
          $discountA['discount' . $i] = ($price * $quantityA['quantity' . $i]) - myround($enterttcqlocal * $quantityA['quantity' . $i] * 100 / (100+$taxcode));
          $rebate_type[$i] = 0;
        }
        else { $enterttcqlocal = ""; }
      }
      $set_rebate_type = 0; $set_discount = '';
      if ($rebate_type[$i] == 3)
      {
        $set_rebate_type = 3; $set_discount = $discountA['discount' . $i];
        $showdiscount = $discountA['discount' . $i];
        $discountA['discount' . $i] = (double) $discountA['discount' . $i];
        $discountA['discount' . $i] = $discountA['discount' . $i] / (1+($taxcode/100)); # points in TTC. only works if price is defined with decimals too !
        $rebate_type[$i] = 0;
      }
      if ($rebate_type[$i] == 1)
      {
        $discountA['discount' . $i] = (double) $discountA['discount' . $i];
        if ($discountA['discount' . $i] < 0) { $discountA['discount' . $i] = 0; }
        if ($discountA['discount' . $i] > 100) { $discountA['discount' . $i] = 100; }
        if (!is_numeric($discountA['discount' . $i])) { $discountA['discount' . $i] = 0; }
        $lineprice[$i] = myround($quantityA['quantity' . $i]
        * (double) $price # TODO use proper functions
        * (1-$discountA['discount' . $i]/100));
        $linevat[$i] = myround((($quantityA['quantity' . $i]
        * (double) $price
        * (1-$discountA['discount' . $i]/100))
        * $taxcode/100));
        $showdiscount = $discountA['discount' . $i];
        $discountA['discount' . $i] = myround($quantityA['quantity' . $i]
        * myround((double)$price)
        * ($discountA['discount' . $i]/100)); # save givenrebate in value not percent
      }
      elseif ($rebate_type[$i] == 2)
      {
        $showdiscount = $discountA['discount' . $i];
        $discountA['discount' . $i] = myround($discountA['discount' . $i] * $price);
        $lineprice[$i] = myround($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]);
        if ($lineprice[$i] < 0) {  $discountA['discount' . $i] = $discountA['discount' . $i] - abs($lineprice[$i]); $lineprice[$i] = $lineprice[$i] + abs($lineprice[$i]); }
        $linevat[$i] = myround((($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]) * $taxcode/100));
      }
      else
      {
        $discountA['discount' . $i] = (double) $discountA['discount' . $i];
        if (isset($quantityA['quantity' . $i]) && $quantityA['quantity' . $i] > 0)
        { $lineprice[$i] = myround($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]); }
        else { $lineprice[$i] = 0; }
        if ($lineprice[$i] < 0) {  $discountA['discount' . $i] = $discountA['discount' . $i] - abs($lineprice[$i]); $lineprice[$i] = $lineprice[$i] + abs($lineprice[$i]); }
        if (isset($quantityA['quantity' . $i]) && $quantityA['quantity' . $i] > 0)
        { $linevat[$i] = myround((($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]) * $taxcode/100)); }
        else { $linevat[$i] = 0; }
        $showdiscount = $discountA['discount' . $i];
        if (isset($set_discount) && $set_discount != '') { $showdiscount = $set_discount; }
      }
      if ($isnotice) { $itemaddvalue[$i] = $lineprice[$i] + $linevat[$i]; $linevat[$i] = 0; $lineprice[$i] = 0; $discountA['discount' . $i] = 0; }
      if ($group_optionalA[$i]) { $linevat[$i] = 0; $lineprice[$i] = 0; }
      $vat += $linevat[$i];
      $vatA[$taxcode] += $linevat[$i];
      $totalprice += $lineprice[$i];
    }
    if ($discountA['discount' . $i] == 0) { $discountA['discount' . $i] = ""; }

    if ($_SESSION['ds_enterttcq'] === 1)
    {
      echo '<td><input type="text" STYLE="text-align:right" name="enterttcqlocal' . $i . '" value="' . $enterttcqlocal . '" size=8></td>';
    }
    
    if ($_SESSION['can_rebate_invoice'])
    {
      if ($only_quantity_rebate[$i] == 2) { echo '<td>'; }
      else
      {
        if (isset($set_rebate_type) && $set_rebate_type == 3) { $rebate_type[$i] = 3; }
        echo '<td align=right><input type="text" STYLE="text-align:right" name="discount' . $i . '"
        value="' . $showdiscount . '" size=8>&nbsp;
        <select style="text-align:right;" name="percentrebate' . $i . '">';
        if ($only_quantity_rebate[$i] == 0)
        {
          echo '<option value=1';
          if ($rebate_type[$i] == 1) { echo ' selected'; }
          echo '>%</option>';
          echo '<option value=0';
          if ($rebate_type[$i] == 0) { echo ' selected'; }
          echo '>'.$_SESSION['ds_currencyname'].'</option>';
        }
        else { $rebate_type[$i] = 2; }
        echo '<option value=2';
        if ($rebate_type[$i] == 2) { echo ' selected'; }
        echo '>Quan.</option>';
        if ($_SESSION['ds_use_loyalty_points'])
        {
          echo '<option value=3';
          if ($rebate_type[$i] == 3) { echo ' selected'; }
          echo '>Points</option>';
        }
        echo '</select>';
      }
      if ($_SESSION['ds_showlinevat'])
      {
        if ($vatexempt == 1) { echo '<td>&nbsp;</td>'; }
        else { echo '<td align=right>' . floor($taxcode) . '%</td>'; }
      }
    }
    echo '<td align=right>' . myfix($lineprice[$i]);
  }
  else # generic products
  {
    $generic_priceA[$i] = 0;
    if (isset($_POST['generic_price'.$i])) { $generic_priceA[$i] = $_POST['generic_price'.$i]; }
    $genericinputvalue = '';
    if ($postinvoiceid && $num_resultsLINES >= $i)
    {
      $generic_priceA[$i] = $rowREADLINES['lineprice'] + $rowREADLINES['givenrebate'];
      $rebate_type[$i] = $rowREADLINES['rebate_type'];
      $discountA['discount' . $i] = $rowREADLINES['givenrebate'];
      ###
      if ($rowREADLINES['rebate_type'] == 1)
      {
        $bcpdivider = myround($rowREADLINES['basecartonprice']);
        if ($_SESSION['ds_useunits'] && $rowREADLINES['quantity']%$numberperunit[$i]) { $bcpdivider = myround($bcpdivider/$numberperunit[$i]); }
        if ($bcpdivider == 0) { $bcpdivider = 1; }
        if (($rowREADLINES['givenrebate'] + $rowREADLINES['lineprice']) == 0) { $discountA['discount' . $i] = 0; }
        else { $discountA['discount' . $i] = 100 * $rowREADLINES['givenrebate'] / ($rowREADLINES['givenrebate'] + $rowREADLINES['lineprice']); }
        $rebate_type[$i] = 1;
        $discountA['discount' . $i] = myround($discountA['discount' . $i],0);
        if ($discountA['discount' . $i] == 0) { $discountA['discount' . $i] = ""; }
      }
      elseif ($rowREADLINES['rebate_type'] == 2)
      {
        if ($rowREADLINES['givenrebate'] == 0) { $rebate_type[$i] = 0; }
        else
        {
          if ($unitorcartonA['unitorcarton' . $i]) { $discountA['discount' . $i] = $rowREADLINES['givenrebate'] / ($rowREADLINES['basecartonprice'] / $numberperunit[$i]); }
          else { $discountA['discount' . $i] = ($rowREADLINES['quantity'] / $numberperunit[$i]) - ($rowREADLINES['lineprice'] / $rowREADLINES['basecartonprice']); }
          $rebate_type[$i] = 2;
        }
      }
      elseif ($rowREADLINES['rebate_type'] == 0)
      {
        $discountA['discount' . $i] = $rowREADLINES['givenrebate']+0;
        if ($rowREADLINES['givenrebate'] == 0) { $rebate_type[$i] = 0; }
      }
      ###
      if ($generic != 2)
      {
        $genericinputvalue = $generic_priceA[$i] / $quantityA['quantity' . $i];
        if ($isreturn)
        {
          $generic_priceA[$i] = $generic_priceA[$i] / $quantityA['quantity' . $i];
        }
      }
      if ($generic_priceA[$i] == 0) { $generic_priceA[$i] = ""; }
    }
    $productname = d_decode($row['productname']) . ' ';
    $productname = $productname . $row['netweightlabel'];
    $price = d_add($generic_priceA[$i],0);
    if ($modify && !$isreturn && $generic != 2) { $price = $price / $quantityA['quantity' . $i]; }
    $price += $pricemod;
    $basecartonprice[$i] = d_add($price,0);
    if ($generic != 2)
    {
      $lineprice[$i] = myround(d_multiply($quantityA['quantity' . $i],$price));
      $linevat[$i] = myround($lineprice[$i] * $taxcode/100);
    }
    else # generic = 2, quantity only for display
    {
      $lineprice[$i] = myround(myround($price,$temp_allowdecimals));
      $linevat[$i] = myround((($price) * $taxcode/100));
    }
    if ($_SESSION['ds_useunits'])
    {
      echo '<td><td>Unité<input type="hidden" name="unitorcarton' . $i . '" value="1"></td>';
      $unitorcartonA['unitorcarton' . $i] = 1;
    }
    if ($_SESSION['ds_dontshowstock'] != 1)
    {
      if ($countstock[$i] != 1) { echo '<td align=right>' . d_output($row['unittypename']); }
      elseif ($displaymultiplier[$i] > 1) { echo '<td align=right>' . ($currentstock/$displaymultiplier[$i]) . '&nbsp;',$row['unittypename'],'</td>'; }
      else { echo '<td align=right>' . $currentstock . '</td>'; }
    }
    echo '<td class="breakme">' . $productname;
    if ($_SESSION['ds_select_itemcomment'])
    {
      if ($paletteid[$i] > 0)
      {
        echo '<select name="colorid'.$i.'">';
        $query = 'select palette_color_matrix.colorid,colorname
        from palette_color_matrix,color
        where palette_color_matrix.colorid=color.colorid
        and paletteid=? order by colorname';
        $query_prm = array($paletteid[$i]);
        require('inc/doquery.php');
        for ($temp=0; $temp < $num_results; $temp++)
        {
          echo '<option value='.$query_result[$temp]['colorid'];
          if ($colorid[$i] == $query_result[$temp]['colorid']) { echo ' selected'; }
          echo '>'.$query_result[$temp]['colorname'].'</option>';
        }
        echo '</select>';
      }
      else
      {
        $dp_selectedid = $select_itemcommentid[$i]; $dp_notable = 1;
        $dp_itemname = 'select_itemcomment'; $dp_addtoid = $i; require('inc/selectitem.php');
      }
    }
    if ($_SESSION['ds_uselocalbol'] == 2)
    {
      $retailprice[$i] = d_add($retailprice[$i],0);
      if ($itemaddvalue[$i] == 0) { $itemaddvalue[$i] = ''; }
      if ($retailprice[$i] == 0) { $retailprice[$i] = ''; }
      echo '<td><input type=number step=0.001 name="itemaddvalue'.$i.'" value="'.$itemaddvalue[$i].'">';
      echo '<td><input type=number step=1 name="serial'.$i.'" value="'.$serial[$i].'">'; # quantity
      $dp_itemname = 'unittype_line'; $dp_notable=1; $dp_addtoid = $i; 
      $dp_selectedid = $unittype_lineid[$i]; require('inc/selectitem.php');
      echo '<td><input type=number step=1 name="retailprice'.$i.'" value="'.$retailprice[$i].'">'; # declared value
    }

    echo '<td align=right>';
    $query = 'select listpricingcatname,price from listpricing,listpricingcat
    where listpricing.listpricingcatid=listpricingcat.listpricingcatid and listpricingcat.deleted=0
    and productid=? and price>0';
    $query_prm = array($productidA['productid' . $i]);
    require ('inc/doquery.php');
    if ($num_results > 0)
    {
      echo '<select name="generic_price' . $i . '">';
      for ($iii=0;$iii<$num_results;$iii++)
      {
        echo '<option value="' . $query_result[$iii]['price'] . '"';
        $compare1 = $query_result[$iii]['price']+0;
        $compare2 = d_add($generic_priceA[$i],0);
        if ($modify) { $compare2 = $compare2 / $quantityA['quantity' . $i]; }
        if ($compare1 == $compare2) { echo ' selected'; $add_to_serial[$i] = $query_result[$iii]['listpricingcatname']; }
        echo '>' . $query_result[$iii]['listpricingcatname'] . ' (' . ($query_result[$iii]['price']+0) . ')</option>';
      }
      echo '</select>';
      if ($_SESSION['ds_enterttcq'] === 1) { echo '<td>&nbsp;</td>'; }
    }
    else
    {
      if ($generic_priceA[$i] == '')
      {
        require('inc/pricealgorithm.php');
        if ($alg_value > 0)
        {
          $generic_priceA[$i] = $alg_value;
          $price = $generic_priceA[$i];
          $basecartonprice[$i] = $price;
          $lineprice[$i] = myround($quantityA['quantity' . $i] * myround($price,$temp_allowdecimals));
          $linevat[$i] = myround((($quantityA['quantity' . $i] * $price) * $taxcode/100));
        }
      }
      if ($genericinputvalue == '') { $genericinputvalue = $generic_priceA[$i]; }
      if ($_SESSION['ds_enterttcq'] === 1)
      {
        if(isset($_POST['enterttcqlocal' . $i])) { $enterttcqlocal = (int) $_POST['enterttcqlocal' . $i]; }
        else { $enterttcqlocal = 0; }
        if ($enterttcqlocal > 0)
        {
          $generic_priceA[$i] = myround(($enterttcqlocal * 100) / (100+$taxcode));
        }
        else { $enterttcqlocal = ''; }
        echo '<input type="text" STYLE="text-align:right" name="generic_price' . $i . '" value="' . $genericinputvalue . '" size=8>';
        echo '<td><input type="text" STYLE="text-align:right" name="enterttcqlocal' . $i . '" value="' . $enterttcqlocal . '" size=8></td>';
      }
      else
      {
        echo '<input type="text" STYLE="text-align:right" name="generic_price' . $i . '" value="' . $genericinputvalue . '" size=8>';
      }
    }
    if ($unitorcartonA['unitorcarton' . $i])
    {
      if (isset($unitprice) && $unitprice > 0) { $price = $unitprice; }
      elseif ($allownongeneric == 1) { $price = round($price / $numberperunit[$i]); }
    }
    if ($_SESSION['ds_enterttcq'] === 1)
    {
      if(isset($_POST['enterttcqlocal' . $i])) { $enterttcqlocal = (int) $_POST['enterttcqlocal' . $i]; }
      else { $enterttcqlocal = 0; }
      $testprice = myround($price + ($price * $taxcode/100));
      if ($enterttcqlocal > 0 && $enterttcqlocal < $testprice)
      {
        $discountA['discount' . $i] = ($price * $quantityA['quantity' . $i]) - myround($enterttcqlocal * $quantityA['quantity' . $i] * 100 / (100+$taxcode));
        $rebate_type[$i] = 0;
      }
      else { $enterttcqlocal = ""; }
    }
    $set_rebate_type = 0; $set_discount = '';
    if ($rebate_type[$i] == 3)
    {
      $set_rebate_type = 3; $set_discount = $discountA['discount' . $i];
      $showdiscount = $discountA['discount' . $i];
      $discountA['discount' . $i] = $discountA['discount' . $i] / (1+($taxcode/100)); # points in TTC. only works if price is defined with decimals too !
      $rebate_type[$i] = 0;
    }
    if ($rebate_type[$i] == 1)
    {
      if ($discountA['discount' . $i] < 0) { $discountA['discount' . $i] = 0; }
      if ($discountA['discount' . $i] > 100) { $discountA['discount' . $i] = 100; }
      if (!is_numeric($discountA['discount' . $i])) { $discountA['discount' . $i] = 0; }
      $lineprice[$i] = myround($quantityA['quantity' . $i] * $price * (1-$discountA['discount' . $i]/100)); # took off myround(price)
      $linevat[$i] = myround((($quantityA['quantity' . $i] * $price * (1-$discountA['discount' . $i]/100)) * $taxcode/100)); # took off myround(price)
      $showdiscount = $discountA['discount' . $i];
      $discountA['discount' . $i] = myround($quantityA['quantity' . $i] * myround($price) * ($discountA['discount' . $i]/100)); # save givenrebate in value not percent
    }
    elseif ($rebate_type[$i] == 2)
    {
      $showdiscount = $discountA['discount' . $i];
      $discountA['discount' . $i] = myround((double) $discountA['discount' . $i] * $price);
      $lineprice[$i] = myround($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]);
      if ($lineprice[$i] < 0) {  $discountA['discount' . $i] = $discountA['discount' . $i] - abs($lineprice[$i]); $lineprice[$i] = $lineprice[$i] + abs($lineprice[$i]); }
      $linevat[$i] = myround((($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]) * $taxcode/100));
    }
    else
    {
      $discountA['discount' . $i] = (double) $discountA['discount' . $i];
      if (isset($quantityA['quantity' . $i]) && $quantityA['quantity' . $i] > 0)
      { $lineprice[$i] = myround($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]); }
      else { $lineprice[$i] = 0; }
      if ($lineprice[$i] < 0) {  $discountA['discount' . $i] = $discountA['discount' . $i] - abs($lineprice[$i]); $lineprice[$i] = $lineprice[$i] + abs($lineprice[$i]); }
      if (isset($quantityA['quantity' . $i]) && $quantityA['quantity' . $i] > 0)
      { $linevat[$i] = myround((($quantityA['quantity' . $i] * $price - $discountA['discount' . $i]) * $taxcode/100)); }
      else { $linevat[$i] = 0; }
      $showdiscount = $discountA['discount' . $i];
      if (isset($set_discount) && $set_discount != '') { $showdiscount = $set_discount; }
    }
    if ($isnotice) { $itemaddvalue[$i] = $lineprice[$i] + $linevat[$i]; $linevat[$i] = 0; $lineprice[$i] = 0; $discountA['discount' . $i] = 0; }
    if ($group_optionalA[$i]) { $linevat[$i] = 0; $lineprice[$i] = 0; }
    if ($discountA['discount' . $i] == 0) { $discountA['discount' . $i] = ""; }
    if ($_SESSION['can_rebate_invoice'])
    {
      if ($only_quantity_rebate[$i] == 2) { echo '<td>'; }
      else
      {
        if (!isset($showdiscount)) { $showdiscount = ""; }
        if (isset($set_rebate_type) && $set_rebate_type == 3) { $rebate_type[$i] = 3; }
        echo '<td align=right><input type="text" STYLE="text-align:right" name="discount' . $i . '"
        value="' . $showdiscount . '" size=8>&nbsp;
        <select style="text-align:right;" name="percentrebate' . $i . '">';
        if ($only_quantity_rebate[$i] == 0)
        {
          echo '<option value=1';
          if ($rebate_type[$i] == 1) { echo ' selected'; }
          echo '>%</option>';
          echo '<option value=0';
          if ($rebate_type[$i] == 0) { echo ' selected'; }
          echo '>'.$_SESSION['ds_currencyname'].'</option>';
        }
        else { $rebate_type[$i] = 2; }
        echo '<option value=2';
        if ($rebate_type[$i] == 2) { echo ' selected'; }
        echo '>Quan.</option>';
        if ($_SESSION['ds_use_loyalty_points'])
        {
          echo '<option value=3';
          if ($rebate_type[$i] == 3) { echo ' selected'; }
          echo '>Points</option>';
        }
        echo '</select>';
      }
    }
    if ($_SESSION['ds_showlinevat']) { echo '<td align=right>' . floor($taxcode) . '%</td>'; }
    echo '<td align=right>' . myfix($lineprice[$i]) . '</td>';
    if ($_SESSION['ds_rebate_listpricing']) # TODO check how this interacts with rebates
    {
      $discountA['discount' . $i] = myround(($row['salesprice'] * $quantityA['quantity' . $i] * $displaymultiplier[$i]) - $lineprice[$i]);
      if ($discountA['discount' . $i] <= 0) { $discountA['discount' . $i] = ""; }
      $rebate_type[$i] = 1;
    }
    $vat += $linevat[$i];
    $vatA[$taxcode] += $linevat[$i];
    $totalprice += $lineprice[$i];
  }#end generic products

  }
  else
  {
    echo '<td align=right><input autofocus type="text" STYLE="text-align:right" name="productid' . $i . '" id="product_autocomplete' . $i . '" autocomplete="off" size=' . $productfieldsize . '></td>';
    echo '<td align=right><input type='.$input_number.' STYLE="text-align:right" name="quantity' . $i . '" value="' . $quantityA['quantity' . $i] . '" size=8 min="0" step=any></td>';
    if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td>&nbsp;</td>'; }
    echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
    if ($_SESSION['can_rebate_invoice']) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_useserialnumbers'] == 1) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_enterttcq'] == 1) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_useunits']) { echo '<td><td>'; }
    if ($_SESSION['ds_showlinevat']) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><td><td>'; }
    for ($temp = 0; $temp < $colspan_mod; $temp++) # TODO for all
    {
      echo '<td>';
    }
  }
  if ($allownongeneric == 0 && $productidA['productid' . $i] != "")
  {
    $query = 'select invoicehistory.invoiceid,accountingdate,lineprice,quantity from invoicehistory,invoiceitemhistory
    where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and productid=? and clientid=? and cancelledid=0
    order by accountingdate desc,invoicehistory.invoiceid desc limit 3';
    $query_prm = array($productidA['productid' . $i],$clientid);
    require('inc/doquery.php');
    if ($num_results)
    {
      echo '<tr><td colspan=20 align=right>';
      for ($i_temp=0;$i_temp<$num_results;$i_temp++)
      {
        # display last 3 sub-unit prices
        if ($i_temp > 0) { echo ' &nbsp / &nbsp; '; }
        echo datefix2($query_result[$i_temp]['accountingdate']).'&nbsp[<a href="printwindow.php?report=showinvoice&invoiceid=' . $query_result[$i_temp]['invoiceid'] . '" target=_blank>';
        echo $query_result[$i_temp]['invoiceid'].'</a>]='.myround($query_result[$i_temp]['lineprice']/$query_result[$i_temp]['quantity'],$temp_allowdecimals);
      }
    }
  }

  if ($productidA['productid' . $i] != "")
  {
    $query = 'select freequantity from freemonthly where deleted=0 and clientid="' . $clientid . '" and productid="' . $productidA['productid' . $i] . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      $rowFR = $query_result[0];
      $freequantity = $rowFR['freequantity'];
      $curmonth = mb_substr($_SESSION['ds_curdate'],5,2);
      $curyear = mb_substr($_SESSION['ds_curdate'],0,4);

      $query = 'select sum(quantity) as usedquantity from invoice,invoiceitem where invoiceitem.invoiceid=invoice.invoiceid and clientid="' . $clientid . '" and productid="' . $productidA['productid' . $i] . '" and DATE_FORMAT(accountingdate,"%Y")="' . $curyear . '" and DATE_FORMAT(accountingdate,"%m")="' . $curmonth . '" and lineprice=0';
      $query_prm = array();
      require('inc/doquery.php');
      $rowFR = $query_result[0];
      $freequantity = $freequantity - floor($rowFR['usedquantity']/$row['numberperunit']);

      $query = 'select sum(quantity) as usedquantity from invoicehistory,invoiceitemhistory where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and clientid="' . $clientid . '" and productid="' . $productidA['productid' . $i] . '" and DATE_FORMAT(accountingdate,"%Y")="' . $curyear . '" and DATE_FORMAT(accountingdate,"%m")="' . $curmonth . '" and lineprice=0';
      $query_prm = array();
      require('inc/doquery.php');
      $rowFR = $query_result[0];
      $freequantity = $freequantity - floor($rowFR['usedquantity']/$row['numberperunit']);

      if ($freequantity > 0) { echo '<tr><td>&nbsp;</td><td colspan=7> &nbsp; &nbsp; Gratuité / mois restant: ' . $freequantity . ' ' . $rowFR['usedquantity'] . '</td></tr>'; }
    }
  }
  if ($showcomments == 1 || $itemcomment['itemcomment' . $i] != "") { echo '<tr><td>Notes:</td><td colspan="9"><textarea type="textarea" name="itemcomment' . $i . '" cols=80 rows=2>' . $itemcomment['itemcomment' . $i] . '</textarea></td></tr>'; }
}# end for invoicelines

if ($vatexempt == 1) { $vat = 0; }
echo '<tr><td>Total HT</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
if ($_SESSION['can_rebate_invoice']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useserialnumbers'] == 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_enterttcq'] === 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useunits']) { echo '<td><td>'; }
if ($_SESSION['ds_showlinevat']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useitemadd']) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><td><td>'; }
for ($temp = 0; $temp < $colspan_mod; $temp++) { echo '<td>'; } # TODO for all
echo '<td align=right>' . myfix($totalprice);

### Deductions - keep ALL functionality concerning deductions here    2017 01 24 not prod ready, REDO/REMOVE/FIX
if ($_SESSION['ds_invoicedeductions'] == 1)
{
  if ($modify)
  {
    $query = 'select deduction_desc,deduction,linenr from invoicededuction where invoiceid=? order by linenr';
    $query_prm = array($postinvoiceid);
    require('inc/doquery.php');
    for ($i = 0; $i < $num_results; $i++)
    {
      $deduction_descA[$query_result[$i]['linenr']] = $query_result[$i]['deduction_desc'];
      $deductionA[$query_result[$i]['linenr']] = $query_result[$i]['deduction']+0;
    }
  }
  else
  {
    for ($i = 0; $i < 5; $i++) # hardcode max 5 deductions
    {
      $deduction_descA[$i] = $_POST['deduction_desc'.$i];
      $deductionA[$i] = myround($_POST['deduction'.$i]);
    }
  }
  for ($i = 0; $i < 5; $i++) # hardcode max 5 deductions
  {
    if (!isset($deductionA[$i]) || $deductionA[$i] == 0) { $deductionA[$i] = ''; }
    if (!isset($deduction_descA[$i])) { $deduction_descA[$i] = ''; }
    if (!isset($deduction_prevatA[$i])) { $deduction_prevatA[$i] = ''; }
    echo '<tr><td>';
    if ($i == 0) { echo 'Déductions'; }
    echo '<td colspan=5><input type="text" name="deduction_desc' . $i . '" value="' . d_input($deduction_descA[$i]) . '" size=50>';
    ### TODO calc colspan
    if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_useserialnumbers'] == 1) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_enterttcq'] === 1) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_useunits']) { echo '<td><td>'; }
    if ($_SESSION['ds_showlinevat']) { echo '<td>&nbsp;</td>'; }
    if ($_SESSION['ds_useitemadd']) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
    if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><td><td>'; }
    ###
    echo '<td align=right><input type="text" STYLE="text-align:right" name="deduction' . $i . '" value="' . $deductionA[$i] . '" size=8>';
  }
  if ($save && $clientid > 0 && $thisinvoicehasbeensaved)
  {
    if ($modifyinvoiceid > 0)
    {
      $query = 'delete from invoicededuction where invoiceid=?';
      $query_prm = array($modifyinvoiceid);
      require('inc/doquery.php');
    }
    if (!isset($invoiceid) || $invoiceid<=0) { $invoiceid = $modifyinvoiceid; }
    for ($i = 0; $i < 5; $i++) # hardcode max 5 deductions TODO update don't delete
    {
      if ($deductionA[$i] > 0)
      {
        $query = 'insert into invoicededuction (deduction_desc,deduction,linenr,invoiceid) values (?,?,?,?)';
        $query_prm = array($deduction_descA[$i],$deductionA[$i],$i,$invoiceid);
        require('inc/doquery.php');
      }
    }
  }
}
###

if ($_SESSION['ds_globalise_vat'] && $vatexempt == 0)
{
  $global_linepriceA = array();
  $global_vatA = array(); #?
  foreach ($taxcodeA as $taxcode)
  {
    $global_linepriceA[$taxcode] = 0;
    $global_vatA[$taxcode] = 0; #?
  }
  for ($i=1; $i <= $invoicelines; $i++)
  {
    if ($productidA['productid' . $i] != "" && $quantityA['quantity' . $i] > 0)
    {
      $global_linepriceA[$taxcode_prodA[$i]] += $lineprice[$i];
      $global_vatA[$taxcode_prodA[$i]] += $vatA[$taxcode_prodA[$i]]; #?
    }
  }
  foreach ($global_linepriceA as $global_taxcode => $global_net)
  {
    $global_diff = myround($global_net * $global_taxcode / 100) - $vatA[$global_taxcode];
    if ($global_diff != 0)
    {
      $i = 0;
      while ($global_diff != 0 && $i <= $invoicelines)
      {
        $i++;
        if (($linevat[$i] + $global_diff) >= 0)
        {
          $linevat[$i] += $global_diff;
          $vat += $global_diff;
          $global_diff = 0;
        }
      }
    }
  }
}

echo '<tr><td>TVA</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
if ($_SESSION['can_rebate_invoice']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useserialnumbers'] == 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_enterttcq'] === 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useunits']) { echo '<td><td>'; }
if ($_SESSION['ds_showlinevat']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useitemadd']) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><td><td>'; }
for ($temp = 0; $temp < $colspan_mod; $temp++) { echo '<td>'; } # TODO
echo '<td align=right>' . myfix($vat);
$totalprice = myround($totalprice);
$vat = myround($vat);
$totalprice = $totalprice + $vat;

echo '<tr><td>Total à payer</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>';
if ($_SESSION['can_rebate_invoice']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_dontshowstock'] != 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useserialnumbers'] == 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_enterttcq'] === 1) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useunits']) { echo '<td><td>'; }
if ($_SESSION['ds_showlinevat']) { echo '<td>&nbsp;</td>'; }
if ($_SESSION['ds_useitemadd']) { echo '<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'; }
if ($_SESSION['ds_uselocalbol'] == 2) { echo '<td><td><td>'; }
for ($temp = 0; $temp < $colspan_mod; $temp++) { echo '<td>'; } # TODO for all
echo '<td align=right>' . myfix($totalprice) . '</td></tr>';

echo d_table_end();
echo '<input type="hidden" name="modifyinvoiceid" value="' . $modifyinvoiceid . '"><input type="hidden" name="totaltopay" value="' . $totalprice . '">
<input type="hidden" name="step" value="0"><input type="hidden" name="notfirstrequest" value="1"><input type=hidden name="salesmenu" value="' . $salesmenu . '">
<input type="hidden" name="lastclientid" value="' . $clientid . '">';
if ($thisinvoicehasbeensaved != 1)
{
  echo '<div class="center"><input type="submit" value="Mettre à jour"> <input name="save" type="submit" value="Valider">';
  if ($_SESSION['ds_canconfirm'] == 1)
  {
    echo ' <input type=checkbox name="confirm1" value=1> Confirmer <input type=checkbox name="confirm2" value=1>';
  }
  echo '</div>';
}
echo '</form>';


if ($save && $clientid > 0 && $thisinvoicehasbeensaved)
{
  if ($tender)
  {
    $query = 'select daystopay from client,clientterm where client.tenderclienttermid=clientterm.clienttermid and clientid=?';
    $query_prm = array($clientid);
    require('inc/doquery.php');
    $row2 = $query_result[0];
    $tenderdaystopay = $row2['daystopay'];
  }
  if ($tender > 0 && $nontender == 0)
  {
    $daystopay = $tenderdaystopay;
  }
  if ($tender > 0 && $nontender > 0)
  {
    if ($tenderdaystopay > $daystopay) { $daystopay = $tenderdaystopay; }
  }

  $_SESSION['ds_lastselecteddate'] = $ourdate;
  $_SESSION['ds_lastselecteddeliverydate'] = $deliverydate;
  $_SESSION['ds_lastemployeeid'] = $employeeid;


  if ($modifyinvoiceid > 0)
  {
    $invoiceid = $modifyinvoiceid;
    $query = 'delete from invoiceitem where invoiceid=?';
    $query_prm = array($modifyinvoiceid);
    require('inc/doquery.php');
  }
  
  if ($_SESSION['ds_paybydateselect'] != 1)
  {
    $paybydate = strtotime ( '+' . $daystopay . ' day' , strtotime ( $ourdate ) ) ; # TODO do not use strtotime
    $paybydate = date ( 'Y-m-j' , $paybydate );
  }

  if ($returntostock == "") { $returntostock = 0; }
  
  $confirmed = 0;
  if ($confirmme == 1)
  {
    $confirmed = 1;
    if ($_SESSION['ds_confirmchangesdate'] == 1)
    {
      $ourdate = $_SESSION['ds_curdate'];
      if ($special == 1) # end of month
      {
        $endofmonthdate = new DateTime($_SESSION['ds_curdate']);
        $endofmonthdate->modify('last day of '.$endofmonthdate->format('Y-m'));
        $paybydate = $endofmonthdate->format('Y-m-d');
      }
    }
  }

  ### TODO fix properly 2019 01 08
  if ($deliverydate == "0000-00-00") { $deliverydate = $ourdate; }
  ###
  
  $query = 'update invoice set returnreasonid=?,invoiceweight=?,deliverytypeid=?,invoicetag2id=?,invoicetagid=?,confirmed=?,hascold=?
  ,invoicecomment=?,invoicecomment2=?,extraaddressid=?,extraname=?,paybydate=?,accountingdate=?,deliverydate=?,invoicedate=curdate()
  ,invoicetime=curtime(),employeeid=?,clientid=?,userid=?,invoiceprice=?,invoicevat=?,localvesselid=?,field1=?,field2=?,reference=?
  ,proforma=?,isnotice=?,isreturn=?,returntostock=?,clientid2=?,clientid3=?,advanceid=?';
  $query_prm = array($returnreasonid,$invoiceweight,$deliverytypeid,$invoicetag2id,$invoicetagid,$confirmed,$hascold
  ,$invoicecomment,$invoicecomment2,$extraaddressid,$extraname,$paybydate,$ourdate,$deliverydate
  ,$employeeid,$clientid,$_SESSION['ds_userid'],$totalprice,$vat,$localvesselid,$field1,$field2,$invoicereference
  ,$proforma,$isnotice,$isreturn,$returntostock,$clientid2,$clientid3,$advanceid);
  if (isset($custominvoicedate)) { $query .= ',custominvoicedate=?'; array_push($query_prm, $custominvoicedate); }
  $query .= ' where invoiceid=?'; array_push($query_prm, $invoiceid);
  require('inc/doquery.php');

  for ($i=1; $i <= $invoicelines; $i++)
  {
    if ($productidA['productid' . $i] != "" && $quantityA['quantity' . $i] > 0)
    {
      if ($unitorcartonA['unitorcarton' . $i] == 0) { $quantityA['quantity' . $i] = $quantityA['quantity' . $i] * $numberperunit[$i]; }
      if ($discountA['discount' . $i] == "") { $discountA['discount' . $i] = 0; }
      if ($vatexempt == 1) { $linetaxcodeid[$i] = 1; $linevat[$i] = 0; } # hard code to no tax (id=1)
      if ($displaymultiplier[$i] != 1)
      {
        $quantityA['quantity' . $i] = $quantityA['quantity' . $i] * $displaymultiplier[$i];
        $basecartonprice[$i] = $basecartonprice[$i] / $displaymultiplier[$i];
      }

      $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
      $query_prm = array();
      require('inc/doquery.php');
      $invoiceitemid[$i] = $query_insert_id;
      if ($invoiceitemid[$i] < 1) { echo '<p class=alert>critical error attributing invoiceitemid</p>'; exit; }

      $discountA['discount' . $i] = myround($discountA['discount' . $i]);
      
      if ($_SESSION['ds_select_itemcomment'])
      {
        if (isset($colorid[$i]) && $colorid[$i] > 0) { $serial[$i] = 'colorid'.$colorid[$i]; }
        elseif (isset($select_itemcommentid[$i]) && $select_itemcommentid[$i] > 0)
        {
          $serial[$i] = $select_itemcommentA[$select_itemcommentid[$i]];
          if (isset($add_to_serial[$i]))
          {
            $serial[$i] = $add_to_serial[$i].'§'.$serial[$i];
          }
        }
      }
      
      $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat
      ,itemcomment,linetaxcodeid,rebate_type) values (?,?,?,?,?,?,?,?,?,?,?)';
      $query_prm = array($invoiceitemid[$i], $invoiceid, $productidA['productid' . $i], $quantityA['quantity' . $i]
      , $discountA['discount' . $i], $basecartonprice[$i], $lineprice[$i], $linevat[$i], $itemcomment['itemcomment'.$i]
      , $linetaxcodeid[$i], $rebate_type[$i]);
      require('inc/doquery.php');

      if (1==1) # 2020 05 12 just always update all variables
      {
        if ($_SESSION['ds_uselocalbol'] == 2) { $producttypeid[$i] = $unittype_lineid[$i]; }
        if (!isset($retailprice[$i])) { $retailprice[$i] = 0; }
        $query = 'update invoiceitem set retailprice=?,lineproducttypeid=?,currentpurchasebatchid=?';
        $query_prm = array($retailprice[$i],$producttypeid[$i],$currentpurchasebatchid[$i]);
        if (isset($linedate[$i])) { $query .= ',linedate=?'; array_push($query_prm, $linedate[$i]); }
        if (isset($lineemployeeid[$i])) { $query .= ',employeeid=?'; array_push($query_prm, $lineemployeeid[$i]); }
        if (isset($itemaddvalue[$i])) { $query .= ',linevalue=?'; array_push($query_prm, $itemaddvalue[$i]); }
        if (isset($serial[$i])) { $query .= ',serial=?'; array_push($query_prm, $serial[$i]); }
        if (isset($invoice_priceoption1id[$i]))
        { $query .= ',invoice_priceoption1id=?'; array_push($query_prm, $invoice_priceoption1id[$i]); }
        if (isset($invoice_priceoption2id[$i]))
        { $query .= ',invoice_priceoption2id=?'; array_push($query_prm, $invoice_priceoption2id[$i]); }
        if (isset($invoice_priceoption3id[$i]))
        { $query .= ',invoice_priceoption3id=?'; array_push($query_prm, $invoice_priceoption3id[$i]); }
        $query .= ' where invoiceitemid=?'; array_push($query_prm, $invoiceitemid[$i]);
        require('inc/doquery.php');
      }
      
      if ($_SESSION['ds_use_invoiceitemgroup'])
      {
        $query = 'select invoiceitemgroupnumber from invoiceitemgroup where invoiceitemid=? limit 1';
        $query_prm = array($invoiceitemid[$i]);
        require('inc/doquery.php');
        if ($num_results)
        {
          $query = 'update invoiceitemgroup set invoiceid=?,invoiceitemgroupnumber=?,invoiceitemgrouptitle=?,is_optional=? where invoiceitemid=?';
          $query_prm = array($invoiceid,$groupnumberA[$i],$grouptitleA[$i],$group_optionalA[$i],$invoiceitemid[$i]);
          require('inc/doquery.php');
        }
        else
        {
          $query = 'insert into invoiceitemgroup (invoiceitemid,invoiceid,invoiceitemgroupnumber,invoiceitemgrouptitle,is_optional) values (?,?,?,?,?)';
          $query_prm = array($invoiceitemid[$i],$invoiceid,$groupnumberA[$i],$grouptitleA[$i],$group_optionalA[$i]);
          require('inc/doquery.php');
        }
      }
    }
    elseif ($invoiceitemid[$i] > 0)
    {
      $query = 'delete from invoiceitem where invoiceitemid=? limit 1';
      $query_prm = array($invoiceitemid[$i]);
      require('inc/doquery.php');
    }
  }
  
  # 2018 12 30 minimum price per product ASSUMES NO VAT
  # 2019 01 12 fix for VAT
  if ($_SESSION['ds_customname'] == 'Terevau' || $_SESSION['ds_customname'] == 'TERE UTA') # TODO option for min_invoiceprice and menu to change value
  {
    $pidA = array();
    $query = 'select distinct invoiceitem.productid,min_invoiceprice from invoiceitem,product
    where invoiceitem.productid=product.productid and invoiceid=? and min_invoiceprice>0';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    for ($i=0; $i < $num_results; $i++)
    {
      $pidA[$query_result[$i]['productid']] = $query_result[$i]['min_invoiceprice'];
    }
    foreach ($pidA as $pid => $min_price)
    {
      $query = 'select sum(lineprice) as price from invoiceitem where invoiceid=? and productid=?';
      $query_prm = array($invoiceid,$pid);
      require('inc/doquery.php');
      $price = $query_result[0]['price'];
      if ($price < $min_price)
      {
        $distribute = $min_price - $price;
        $sum = 0;
        $rest_distribute = $distribute;
        #echo $pid,' distribute ',$distribute,'<br>';
        $query = 'select invoiceitemid,lineprice,linetaxcodeid from invoiceitem
        where invoiceid=? and productid=?';
        $query_prm = array($invoiceid,$pid);
        require('inc/doquery.php');
        $main_result = $query_result; $num_results_main = $num_results;
        for ($i=0; $i < $num_results_main; $i++)
        {
          $sum += $main_result[$i]['lineprice'];
        }
        for ($i=0; $i < $num_results_main; $i++)
        {
          if ($sum > 0) { $add = myround($distribute * $main_result[$i]['lineprice']/$sum); }
          else { $add = 0; }
          #echo $main_result[$i]['invoiceitemid'],' ',$main_result[$i]['lineprice'];
          #echo ' add ',$add;
          #echo '<br>';
          $rest_distribute -= $add;
          #echo ' remains: ',$rest_distribute,'<br>';
          if ($i == ($num_results_main-1) && $rest_distribute != 0) { $add += $rest_distribute; }
          $new_vat = 0;
          if ($taxcodeA[$main_result[$i]['linetaxcodeid']] > 0)
          {
            $new_vat = myround(($main_result[$i]['lineprice'] + $add) * ($taxcodeA[$main_result[$i]['linetaxcodeid']] / 100));
          }
          # query update invoiceitem
          $query = 'update invoiceitem set lineprice=lineprice+?';
          $query_prm = array($add);
          if ($new_vat) { $query .= ',linevat=?'; array_push($query_prm, $new_vat); }
          $query .= ' where invoiceitemid=?'; array_push($query_prm,$main_result[$i]['invoiceitemid']);
          require('inc/doquery.php');
        }
        $new_vat = 0;
        $query = 'select sum(linevat) as new_vat from invoiceitem where invoiceid=?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        if ($num_results) { $new_vat = $query_result[0]['new_vat']; }
        $query = 'update invoice set invoiceprice=invoiceprice+?';
        $query_prm = array($distribute);
        if ($new_vat) { $query .= ',invoicevat=?'; array_push($query_prm, $new_vat); }
        $query .= ' where invoiceid=?'; array_push($query_prm,$invoiceid);
        require('inc/doquery.php');
      }
    }
  }

  if ($confirmed)
  {
    ### check if sum of lines equals invoiceprice
    $query = 'select invoiceprice from invoice where invoiceid=?';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    $invoicetotal = $query_result[0]['invoiceprice']+0;
    $query = 'select sum(lineprice+linevat) as linetotals from invoiceitem where invoiceid=?';
    $query_prm = array($invoiceid);
    require('inc/doquery.php');
    $linetotals = $query_result[0]['linetotals']+0;
    $linetotals = myround($linetotals); $invoicetotal = myround($invoicetotal);
    if ($linetotals != $invoicetotal)
    {
      $query = 'update invoice set confirmed=0 where invoiceid=?'; #cancelledid=1,
      $query_prm = array($invoiceid);
      require('inc/doquery.php');
      $errortext = '<span class="alert">Erreur sur facture '.$invoiceid
      . '</span> Veuillez la remodifier.<br>';
      echo $errortext;
      if (!isset($_SESSION['last_sqlerror_time']) || time() > ($_SESSION['last_sqlerror_time']+60))
      {
        if (d_sendemail('svein.tjonndal@gmail.com','svein.tjonndal@gmail.com',$errortext,$errortext))
        { echo '<p class=alert>Un e-mail a été envoyé au service technique.</p>'; }
        else { echo '<p class=alert>Veuillez contacter le service technique.</p>'; }
        $_SESSION['last_sqlerror_time'] = time();
      }
    }
    ###
    require('inc/move_to_history.php');
  }
  
  if ($_SESSION['ds_customname'] == 'Wing Chong')
  {
    if ($isnotice && !$confirmed && !$isreturn)
    {
      $emailaddress = 'facturation.nestle@pf.nestle.com';
      $bccaddress = 'cindy.devon@wico.pf';
      $replytoaddress = 'contact@temtahiti.com';
      $subject = 'BdL Nestlé '.$invoiceid;
      if ($modifyinvoiceid > 0) { $subject .= ' modifié'; }
      else { $subject .= ' créé'; }
      $messagetext = 'Bonjour,
      Veuillez-vous connecter sur TEM pour confirmer votre bon de livraison cité en objet.
      Merci de ne pas répondre à ce message.';
      d_sendemail($emailaddress,$replytoaddress,$subject,$messagetext,'',$bccaddress);
    }
  }
  
}

?>