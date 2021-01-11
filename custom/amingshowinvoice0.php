<?php 
require('printwindow/style_print.php');
require('custom/aming_style_showinvoice0.php');
require ('inc/bottom.php');
require_once('printwindow/invoice_options.php'); 

require('inc/fulltextcurrency_func.php');
require('preload/taxcode.php');
require('preload/localvessel.php');
require('preload/employee.php');
require('preload/town.php');
require('preload/island.php');
require('preload/bank.php');
require('preload/producttype.php');
require('preload/regulationtype.php');
require('preload/unittype.php'); # TODO use this and dont link unittype table

$ds_term_accountingdate = $_SESSION['ds_term_accountingdate'];
$ds_customname = mb_strtolower($_SESSION['ds_customname']);
$usehistory = 0;
$totaltva = 0;
$totalht = 0;
$showregulation_header = 0;

unset($tvaM);
unset($tvaMt);

$pagenumber = $_POST['pagenumber'] + 0;

if ($pagenumber < 1)
{
  $pagenumber = 1;
}

$query = 'SELECT localvesselid,invoice.employeeid,invoicevat,field1,field2,townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,
          invoice.clientid,clientname,extraname,accountingdate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
          proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,
          cellphone,email,fax,extraaddressid,clienttermname,confirmed
          FROM invoice,client,usertable,clientterm
          WHERE invoice.clientid = client.clientid
          AND invoice.userid = usertable.userid
          AND client.clienttermid = clientterm.clienttermid
          AND invoice.invoiceid = ?';

$query_prm = array();
$query_prm[] = $invoiceid;

if ($_SESSION['ds_clientaccess'] == 1)
{
  $query = $query . ' AND client.clientid = ?';
  $query_prm[] = $_SESSION['ds_userid'];
}

if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query = $query . ' AND invoice.clientid IN ' . $_SESSION['ds_allowedclientlist'];
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' AND (invoice.userid = ?';
  $query_prm[] = $_SESSION['ds_userid'];

  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' OR invoice.employeeid = ?';
    $query_prm[] = $_SESSION['ds_myemployeeid'];
  }

  $query .= $queryadd . ')';
}

require('inc/doquery.php');

if (!$num_results)
{
  $usehistory = 1;

  $query = 'SELECT localvesselid,invoicehistory.employeeid,invoicevat,field1,field2,townid,invoicehistory.userid,invoicetagid,vatexempt,isnotice,
            contact,invoicehistory.clientid,clientname,extraname,accountingdate, deliverydate,paybydate,name,tahitinumber,rc,
            invoiceprice,isreturn,proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,
            postaladdress,postalcode,telephone,cellphone,email,fax,extraaddressid,clienttermname,confirmed
            FROM invoicehistory,client,usertable,clientterm
            WHERE invoicehistory.clientid = client.clientid
            AND invoicehistory.userid = usertable.userid
            AND client.clienttermid = clientterm.clienttermid
            AND invoicehistory.invoiceid = ?';
            
  $query_prm = array();
  $query_prm[] = $invoiceid;

  if ($_SESSION['ds_clientaccess'] == 1)
  {
    $query = $query . ' AND client.clientid = ?';
    $query_prm[] = $_SESSION['ds_userid'];
  }

  if ($_SESSION['ds_allowedclientlist'] != '')
  {
    $query = $query . ' AND invoicehistory.clientid IN ' . $_SESSION['ds_allowedclientlist'];
  }

  if ($_SESSION['ds_confirmonlyown'] == 1)
  {

    $queryadd = ' AND (invoicehistory.userid = ?';
    $query_prm[] = $_SESSION['ds_userid'];

    if ($_SESSION['ds_myemployeeid'] > 0)
    {
      $queryadd .= ' OR invoicehistory.employeeid = ?';
      $query_prm[] = $_SESSION['ds_myemployeeid'];
    }

    $query .= $queryadd . ')';
  }

  require('inc/doquery.php');
}

if (!$num_results)
{
  echo '<p class="alert">Facture inexistante.</p>';
  exit;
}

$row = $query_result[0];

$isnotice = $row['isnotice'] + 0;
$invoiceprice = $row['invoiceprice'];
$userid = (int) $row['userid'];
$totaltva = $row['invoicevat'];

$vesselname = $localvesselA[$row['localvesselid']];

$cancelledid = $row['cancelledid'];
$reference = $row['reference'];
$extraname = $row['extraname'];

$deliverydate = $row['deliverydate'];
$accountingdate = $row['accountingdate'];
$paybydate = $row['paybydate'];

$invoicetagid = $row['invoicetagid'];

$field1 = $row['field1'];
$field2 = $row['field2'];

$invoicecomment = $row['invoicecomment'];
$invoicecomment2 = $row['invoicecomment2'];

$clientid = $row['clientid'];
$clientname = $row['clientname'];
$tahitinumber = $row['tahitinumber'];
$companytypename = $row['companytypename'];
$clienttermname = $row['clienttermname'];
$extraaddressid = $row['extraaddressid'];
$postaladdress = $row['postaladdress'];
$address = $row['address'];
$postalcode = $row['postalcode'];
$townid = $row['townid'];
$towname = $townA[$townid];
$islandname = $islandA[$town_islandidA[$townid]];

$telephone = $row['telephone'];
$cellphone = $row['cellphone'];
$showphone = '';
if (isset($telephone) && !empty($telephone)) { $showphone .= $telephone;}
if (isset($cellphone) && !empty($cellphone)) 
{ 
  if ($showphone != '') { $showphone .= ' - '; } 
  $showphone .= $cellphone;
}

$employeeid = $row['employeeid'];

$proforma = $row['proforma'];
$isreturn = $row['isreturn'];
$confirmed = $row['confirmed'];

$typetext = 'Facture ' . $clienttermname;

if ($proforma == 1)
{
  $typetext = 'Proforma ';
}

if ($isnotice)
{
  $typetext = $_SESSION['ds_term_invoicenotice'];
}

if ($isreturn == 1)
{
  $typetext = 'Avoir ';
}

if ( $confirmed == 0) # ($template == 2 || $template == 3) && 
{
  $typetext = 'Devis'; # TODO option for AFEQ to disable this (the reason why model 1 is excluded)

  if ($isreturn == 1)
  {
    $typetext .= ' Avoir ';
  }
}

/* Options for invoice title like Pacitech */

$year = mb_substr($row['accountingdate'], 0, 4) + 0;

if ($_SESSION['ds_showinvoice_dateformat'] == 1)
{
  if (mb_strlen($invoiceid) == 1)
  {
    $format_invoiceid = '000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 2)
  {
    $format_invoiceid = '00' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 3)
  {
    $format_invoiceid = '0' . $invoiceid;
  }

  elseif(mb_strlen($invoiceid) > 4) {
    $format_invoiceid = substr($invoiceid, -4);
  }

  showtitle($typetext . $year . $format_invoiceid);

  $format_invoiceid = $year . $format_invoiceid;
}
elseif ($_SESSION['ds_showinvoice_dateformat'] == 2)
{
  if (mb_strlen($invoiceid) == 1)
  {
    $format_invoiceid = '0000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 2)
  {
    $format_invoiceid = '000' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 3)
  {
    $format_invoiceid = '00' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) == 4)
  {
    $format_invoiceid = '0' . $invoiceid;
  }

  elseif (mb_strlen($invoiceid) > 5)
  {
    $format_invoiceid = substr($invoiceid, -5);
  }

  showtitle($typetext . $year . $format_invoiceid);
  $format_invoiceid = $year . $format_invoiceid;
}
else
{
  showtitle($typetext . $invoiceid);
  $format_invoiceid = myfix($invoiceid);
}

$query_prm = array();

if ($usehistory)
{
  $query = 'SELECT concat(employeename," ",employeefirstname) as employeename
            FROM invoicehistory,employee
            WHERE invoicehistory.employeeid = employee.employeeid
            AND invoiceid = ?';

  $query_prm[] = $invoiceid;
}
else
{
  $query = 'SELECT concat(employeename," ",employeefirstname) as employeename
            FROM invoice,employee
            WHERE invoice.employeeid = employee.employeeid
            AND invoiceid = ?';

  $query_prm[] = $invoiceid;
}

require('inc/doquery.php');

if ($num_results > 0)
{
  $employeename = $query_result[0]['employeename'];
}

$isretail = 1;
if ($invoicetagid > 0 )
{
  $query = 'SELECT invoicetagname FROM invoicetag WHERE invoicetagid = ?';
  $query_prm = array($invoicetagid);

  require('inc/doquery.php');
  $invoicetagname = $query_result[0]['invoicetagname'];
  if (mb_strtolower($invoicetagname) == 'gros')
  {
    $isretail = 0;
  }
}


$ourlogofile = 'custom_available/' . mb_strtolower($ds_customname) . '.jpg';

if ($extraaddressid > 1)
{
  $query = 'SELECT address,postaladdress,postalcode,telephone,townname,islandname
            FROM extraaddress,town,island
            WHERE extraaddress.townid = town.townid
            AND town.islandid = island.islandid
            AND extraaddressid = ?';

  $query_prm = array();
  $query_prm[] = $extraaddressid;

  require('inc/doquery.php');

  $row3 = $query_result[0];
  $postaladdress = stripslashes($row3['postaladdress']);
  $address = stripslashes($row3['address']);
  $towname = $row3['towname'];
  $islandname = $row3['islandname'];  
}

if (!isset($postaladdress) || empty($postaladdress)){ $postaladdress = '';}
if (!isset($address) || empty($address)){ $address = '';}
$showaddress = d_output($postaladdress) . ' ' . d_output($address) . ' ' . d_output($postalcode). ' ' . d_output($townname) . ' ' . d_output($islandname);

$query = 'SELECT invoiceitem.retailprice,product.unittypeid,regulationtypeid,producttypeid,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
          productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,
          lineprice,linevat,itemcomment,taxcode,islandregulatedprice
          FROM invoiceitem,product,unittype,taxcode
          WHERE invoiceitem.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          AND invoiceitem.invoiceid = ?
          ORDER BY invoiceitemid';

if ($usehistory)
{
  $query = 'SELECT invoiceitemhistory.retailprice,product.unittypeid,regulationtypeid,producttypeid,eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
            unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,
            linevat,itemcomment,taxcode,islandregulatedprice
            FROM invoiceitemhistory,product,unittype,taxcode
            WHERE invoiceitemhistory.productid = product.productid
            AND product.unittypeid = unittype.unittypeid
            AND product.taxcodeid = taxcode.taxcodeid
            AND invoiceitemhistory.invoiceid = ?
            ORDER BY invoiceitemid';
}

$query_prm = array();
$query_prm[] = $invoiceid;
require('inc/doquery.php');

$totalnum = $num_results;
$num_lines = $num_results;
$main_result = $query_result;

$totalpages = ceil($num_lines / $linesperpage);

$totalrebate = 0;

for ($y = 0; $y < $num_lines; $y++)
{
  $totalrebate += $main_result[$y]['givenrebate'];
}

$colspan = 7; $colspan2 = '2 '; $colspan3 = '5 ';
if ($totalrebate == 0)
{
  $colspan--;
}

$informationTable = '';

for ($y = 0; $y < $num_lines; $y++)
{
  $row2 = $main_result[$y];
  $totalht = $totalht + $row2['lineprice'];
  $quantity = $row2['quantity'] / $row2['numberperunit'];
  $unittypename = $row2['unittypename'];
  $bcp = myround($row2['basecartonprice']);

  if ($_SESSION['ds_useunits'] && $row2['quantity'] % $row2['numberperunit'])
  {
    $quantity = $row2['quantity'];
    $unittypename = 'pièce';
    $bcp = myround($bcp / $row2['numberperunit']);
  }

  $bcpdivider = $bcp;

  if ($bcpdivider == 0)
  {
    $bcpdivider = 1;
  }

  $gr = round((100 * $row2['givenrebate'] / $bcpdivider) / ($quantity), 0);

  if ($gr == 0)
  {
    $gr = '&nbsp;';
  }
  else
  {
    $gr = $gr . '<span class="small-percent">%</span>';
  }

  $showtva = myround($row2['taxcode']) . '<span class="small-percent">%</span>';

  $kladd = $row2['taxcode'];

  if ($row2['linetaxcodeid'] > 0)
  {
    $kladd = $taxcodeA[$row2['linetaxcodeid']];
    if ($row2['linetaxcodeid'] == 59999)
    {
      $showtva = '0<span class="small-percent">%</span>';
    }
    else
    {
      $showtva = myround($taxcodeA[$row2['linetaxcodeid']]) . '<span class="small-percent">%</span>';
    }
  }

  $tvaM[$kladd] = $tvaM[$kladd] + myround($row2['linevat']);
  $tvaMt[$kladd] = $tvaMt[$kladd] + myround($row2['lineprice']);

  $productname = ucfirst(d_decode($row2['productname'])) . ' ';

  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1)
  {
    $productname = $productname . $row2['numberperunit'] . ' x ';
  }

  $productname = $productname . $row2['netweightlabel'];
  $islandregulatedprice = $row2['islandregulatedprice'];
  
  
  ### RETAIL PRICE CALCULATION ###
  
  # definitions
  $producttype = $producttypeA[$row2['producttypeid']];
  $outerisland = $island_outerislandA[$town_islandidA[$townid]];
  $regulationtypeid = $row2['regulationtypeid'];
  $showasterix = $regulationtype_showasterixA[$regulationtypeid];
  $invoiceitemid = $row2['invoiceitemid'];
  $productid = $row2['productid'];
  $dmp = $unittype_dmpA[$row2['unittypeid']];
  #$clientid
  #$islandname
  $numberperunit = $row2['numberperunit'];
  $netdecimals = 0; # number of decimals for prix details
  $regulationzoneid = $island_regulationzoneidA[$town_islandidA[$townid]];
  $taxcode2 = $taxcodeA[$row2['linetaxcodeid']];
  $retailprice = $row2['retailprice'];
  $regulationzonerate = 1;
  #

  $showregulation = 0;
  
  if ($producttype == 'PGL' && $outerisland == 0) { $showasterix = 0; }

  if ($showasterix || ($isretail == 0))
  {
    $showregulation = 1; $showregulation_header = 1;
    $colspan += 5; 
    $colspan2 = '7 '; $colspan3 = '10 ';   
    
    $RPT = round(($retailprice * $dmp) / $numberperunit,$netdecimals);
    
    $query = 'select freightpriceperkilo,regulationmargin from regulationmatrix where regulationtypeid=? and regulationzoneid=?';
    $query_prm = array($regulationtypeid, $regulationzoneid);
    require('inc/doquery.php');
    $fppk = $query_result[0]['freightpriceperkilo']+0;
    $rm = $query_result[0]['regulationmargin']+0;

    $RPI = round((($RPT * $regulationzonerate) + $fppk) * $rm,$netdecimals);
    $RPTvat = round($RPT * (1 + ($taxcode2/100)),$netdecimals);
    $RPIvat = round($RPI * (1 + ($taxcode2/100)),$netdecimals);
    if ($islandname == "Tahiti") { $RPI = ""; $RPIvat = ""; }
    if ($producttype == 'PGL') { $RPT = ""; $RPTvat = ""; }
  }
  
  ### END RETAIL PRICE CALCULATION ##
  
  
  
  if (floor($y / $linesperpage) == ($pagenumber - 1))
  {
    $informationTable .= '<tr>';
 
    if ($_SESSION['ds_useproductcode'] == 1)
    {
      $informationTable .= '<td id="maintdnumberswithoutborder">' . d_decode($row2['suppliercode']);
    }
    else
    {
      $informationTable .= '<td id="maintdnumberswithoutborder">' . myfix($row2['productid']);
    }

    $informationTable .= '<td id="maintdnumbers">' . $quantity . ' ' . $unittypename;
    $informationTable .= '<td id="maintdbreakme">' . $productname;  
    if ($showregulation)
    {
      $informationTable .= '<td id="maintdbreakme">'.$producttype.'</td>';
      $informationTable .= '<td id="maintdnumbers">'.$RPT.'</td>';
      $informationTable .= '<td id="maintdnumbers">'.$RPTvat.'</td>';
      $informationTable .= '<td id="maintdnumbers">'.$RPI.'</td>';
      $informationTable .= '<td id="maintdnumbers">'.$RPIvat.'</td>';
    } 
    $informationTable .= '<td id="maintdnumbers">' . myfix($bcp) . '</td>';
    $informationTable .= '<td id="maintdnumbers">' . $gr . '</td>';   
    $informationTable .= '<td id="maintdnumbers">' . myfix($row2['lineprice']) . '</td>';
    $informationTable .= '<td id="maintdnumbers">' . $showtva . '</td>';    
    $informationTable .= '<td id="maintdnumberswithoutborder">' . myfix($row2['lineprice']+$row2['linevat']) . '</td>';
  }
}

if ($totalpages == 1 || $pagenumber == $totalpages)
{
  $informationTotalPages = '';

  if (!$isnotice)
  {
    if ($_SESSION['ds_invoicedeductions'] == 1)
    {
      $query = 'SELECT deduction_desc, deduction, linenr
                FROM invoicededuction
                WHERE invoiceid = ?
                AND deduction_prevat = 1
                ORDER BY linenr';

      $query_prm = array($invoiceid);
      require('inc/doquery.php');

      for ($i = 0; $i < $num_results; $i++)
      {
        $informationTotalPages .= '<tr><td colspan=' . $colspan . '>' . d_output($query_result[$i]['deduction_desc']) . '<td id="maintdnumbers">-' . myfix($query_result[$i]['deduction']);

        $totalht -= $query_result[$i]['deduction'];
        $tvaMt[$kladd] -= $query_result[$i]['deduction'];

        $tvaM[$kladd] = $tvaMt[$kladd] * ($kladd / 100);
      }
    }
    /*if ($_SESSION['ds_invoicedeductions'] == 1)
    {
      $query = 'SELECT deduction_desc, deduction, linenr
                FROM invoicededuction
                WHERE invoiceid = ?
                AND deduction_prevat = 0
                ORDER BY linenr';

      $query_prm = array($invoiceid);
      require('inc/doquery.php');

      for ($i = 0; $i < $num_results; $i++)
      {
        $informationTotalPages .= '<tr><td colspan=' . $colspan . '>' . d_output($query_result[$i]['deduction_desc']) . '<td id="maintdnumbers">-' . myfix($query_result[$i]['deduction']);
      }
    } */   

    $informationTotalPages .= '<tr><td colspan=' .$colspan .' id=maintdnumberswithoutborder></td></tr>';
    $informationTotalPages .= '<tr><td colspan=' .$colspan .'  id=maintdnumberswithoutborder></td></tr>';
    $informationTotalPages .= '<tr><td colspan=' .$colspan .'  id=maintdnumberswithoutborder></td></tr>';
    $informationTotalPages .= '<tr><td colspan=2 id=num>&nbsp;</td><td id=num>Nombre d\'unités: ' . $totalnum;
    $informationTotalPages .= '</td><td colspan=' . $colspan2 .' id=total></td><td id=total><b>Net à ';
    if ($isreturn == 1)
    {
      $informationTotalPages .= 'rembourser';
    }
    else
    {
      $informationTotalPages .= 'payer';
    }
    $informationTotalPages .= '</b><td colspan=2 id=maintdnumberswithoutborder><b>' . myfix($invoiceprice) . '<b></td></tr>';    

    if ($totaltva > 0)
    {
      $informationTotalPages .= '<tr><td colspan=' . $colspan3 .' id=total>&nbsp;</td><td id=total>Dont TVA</td><td colspan=2 id="maintdnumberswithoutborder">' . myfix($totaltva) . '</td></tr>';
    }
  }
}

if ($isnotice == 0)
{
  $informationIsNotice = '';

  $totalpaid = 0;
  $paymentid = 0;

  $query = 'SELECT paymentid, value, reimbursement, paymenttypename, payment.paymenttypeid, bankid, chequeno
            FROM payment,paymenttype
            WHERE payment.paymenttypeid = paymenttype.paymenttypeid
            AND forinvoiceid = ?';

  $query_prm = array();
  $query_prm[] = $invoiceid;
  require('inc/doquery.php');
  
  if ($num_results > 0)
  {
    $informationIsNotice .= '<p>PAIEMENT: <br>';

    for ($y = 0; $y < $num_results; $y++)
    {
      $row = $query_result[$y];
      $value = $row['value'];
      if ($row['reimbursement'] == 1)
      {
        $totalpaid = $totalpaid - $value;
      }
      else
      {
        $totalpaid = $totalpaid + $value;
        $paymentid = $row['paymentid'];
        $paymenttypename = $row['paymenttypename'];
        $paymenttypeid = $row['paymenttypeid'];
        $bankid = $row['bankid'];
        $chequeno = $row['chequeno'] . '';
         
        $informationIsNotice .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        //if ($paymenttypeid > 1)
        //{
          $informationIsNotice .= strtoupper($paymenttypename) . ': ';
        //}
        $informationIsNotice .= myfix($value);

        if ($chequeno != '')
        {
          $informationIsNotice .= ' CHEQUE n°' . $chequeno;
        }
        if ($bankid > 0)
        {
          $informationIsNotice .= ' ' . $bankA[$bankid];
        }
        $informationIsNotice .= '<br>';
      }
    }
    $informationIsNotice .= '</p>';
  }

  if ($totalpaid >= $invoiceprice)
  {
    $informationIsNotice .= '<p>Cette facture a été entièrement réglée.<br>';
  }
}

$ourlogofile2 = 'custom_available/' . $ds_customname . '2.jpg';
 ?>
<div class="main">
  
  <div class="company-logo-footer">
      <?php if (file_exists($ourlogofile2)): ?>
        <div class="logo"><img src="<?php echo $ourlogofile2; ?>"></div>     
      <?php endif; ?>
  </div>
  <br><br><br><br><br><br><div class=lineheader>&nbsp;</div><br> 
    
  <div class="box1">  
    <p align="center"><b><?php echo strtoupper($typetext); ?></b></p>
  </div>

  
  <div class="invoiceinfos">
     <table>
      <tr>
        <td id=invoiceinfostdleft><?php echo 'N° Facture: ' . $format_invoiceid; ?></td>
        <td id=invoiceinfostdmiddle>&nbsp;</td>
        <td id=invoiceinfostdright>
          <?php if ($isretail == 0) { echo 'N° Client: ' . $clientid . ' - N° Tahiti: ' . $tahitinumber; } ?>
        </td>
      </tr>
      <tr>
        <td id=invoiceinfostdleft><?php echo $ds_term_accountingdate . ': ' . datefix2($accountingdate); ?></td>
        <td id=invoiceinfostdmiddle>&nbsp;</td>
        <td id=invoiceinfostdright><b>
          <?php  echo strtoupper($clientname); ?>
        </b></td>
      </tr>
      <tr>
        <td id=invoiceinfostdleft><?php echo 'Bon commande: ' . $reference; ?></td>
        <td id=invoiceinfostdmiddle>&nbsp;</td>
        <td id=invoiceinfostdright>
          <?php if ($isretail == 0) { echo $showaddress; }?>
        </td>
      </tr> 
      <tr>
        <td id=invoiceinfostdleft><?php echo 'Livraison: ' . datefix2($deliverydate); ?></td>
        <td id=invoiceinfostdmiddle>&nbsp;</td>
        <td id=invoiceinfostdright>
          <?php if ($isretail == 0) { echo 'Tél: ' . $showphone; }?>
        </td>
      </tr>   
      <?php if ($isretail == 0)   
      {?>   
        <tr>
          <td id=invoiceinfostdleft><?php echo 'Destination: ' . $showaddress;?>
          </td>
          <td id=invoiceinfostdmiddle>&nbsp;</td>
          <td id=invoiceinfostdright>&nbsp;</td>
        </tr>  
        <tr>
          <td id=invoiceinfostdleft><?php echo 'Destinataire: ' . d_output(d_decode($clientname));
           if (isset($extraname) && empty($extraname))
           {
            echo ' ' . d_output($extraname);
           }?>
          </td>
          <td id=invoiceinfostdmiddle>&nbsp;</td>
          <td id=invoiceinfostdright>&nbsp;</td>
        </tr>
      <?php } ?>
      <tr>
        <td id=invoiceinfostdleft><?php echo 'Vendeur: ' . d_output(d_decode($employeename));?></td>
        <td id=invoiceinfostdmiddle>&nbsp;</td>
        <td id=invoiceinfostdright>&nbsp;</td>
      </tr>
    </table>
   
    <?php if ($cancelledid): ?>
      &nbsp; <font color="<?php echo $alertcolor; ?>">ANNULEE</font><br>
    <?php endif; ?>

    <table class="report" style="width: 770px">
      <thead>
        <th class=mainth>Ref</th>
        <th class=mainth>Qté</th>
        <th class=mainth>Désignation</th>
        <?php
        if ($showregulation_header)
        { 
          echo '<th colspan=5 class=mainth>Prix détail (T.HT/T.TTC/Î.HT/Î.TTC)</th>';
        }
        ?>         
        <th class=mainth>P.Unit HT</th>
        <th class=mainth>R%</th>
        <th class=mainth>Montant Net HT</th>
        <th class=mainth>TVA</th>
        <th class=mainth>TOT net TTC</th>
      </thead>
       

      <?php echo $informationTable; ?>
      <?php echo $informationTotalPages; ?>
    </table>

    <?php if ($totaltva > 0 && ($totalpages == 1 || $pagenumber == $totalpages)): ?>
      <br>

      <table class="report">
        <thead>
          <th class=vatth>TAUX</th>
          <th class=vatth>MONTANT HT</th>
          <th class=vatth>T.V.A</th>
          <th class=empty>&nbsp;</th>
        </thead>       

        <?php $taxcount = 0;$taxcodeid_prev = -1;
        foreach ($taxcodeA as $taxcodeid => $taxcode)
        {
          if ($taxcount > 0 && $tvaM[$taxcode_prev] > 0 && $tvaM[$taxcode] > 0 && $taxcount != (count($taxcodeA)-1))  
          {     
            echo '&nbsp;</td></tr>';        
          }
            
          if ($tvaM[$taxcode] > 0)
          {
          ?>
            <tr>
              <td id=maintdnumberswithoutborder> <?php echo $taxcode; ?><span class="small-percent">%</span></td>
              <td id=vattdnumbers>  <?php echo myfix($tvaMt[$taxcode]); ?></td>
              <td id=maintdnumberswithoutborder> <?php echo myfix($tvaM[$taxcode]); ?> </td>
              <td id=maintdnumberswithoutborder>
            <?php
            $taxcode_prev = $taxcode;
          }
          $taxcount ++;          

        }?>
        <b>&nbsp;&nbsp;&nbsp;&nbsp;TVA ACQUITTEE D'APRES LES DEBITS<b></td></tr>
      </table>
    <?php endif; ?>

    <?php if ($totalpages == 1 || ($totalpages > 1 && $pagenumber == $totalpages)): ?>
      <?php if ($proforma == 0 && $isnotice == 0): ?>
        <br>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo strtoupper(convertir($invoiceprice)); ?> CFP.</p><br>
      <?php endif; ?>
    <?php endif; ?>

    <?php if ($isnotice == 0): ?>
      <?php echo $informationIsNotice; ?>
    <?php endif; ?>
  </div>
  
  <div class="footer">  
      <div class=textfooter>Toutes nos grandes marques sont garanties 1an, seuls les appareils électroniques, les vélos sont couverts par une garantie de 6 mois, ne couvrant que les défauts de fabrication. Les frais de retour et de renvoi sont à la charge du client. L'établissement AMING se réserve le droit de majorer de 1% par mois la somme non payée dans les 30 jours suivant la fabrication. Les pièces électriques ne sont ni reprises, ni échangées. Nos marchandises voyagent aux risques et périls du client, nous vous conseillons d'assurer vos marchandises transportées par bateau.</div>
      <div class=linefooter>&nbsp;</div><br>
      <div class="logo-tem-footer"><img src="pics/logo.png" height="50"></div>
  </div>
</div>