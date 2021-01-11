<?php

require('inc/fulltextcurrency_func.php');
require('preload/taxcode.php');
require('preload/localvessel.php');
require('preload/employee.php');
require('preload/town.php');
require('preload/island.php');
require('preload/bank.php');

$ds_customname = mb_strtolower($_SESSION['ds_customname']);
$usehistory = 0;
$totaltva = 0;
$totalht = 0;

unset($tvaM);
unset($tvaMt);

$pagenumber = $_POST['pagenumber'] + 0;

if ($pagenumber < 1)
{
  $pagenumber = 1;
}

$query = 'SELECT localvesselid,invoice.employeeid,invoicevat,field1,field2,townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,
          invoice.clientid,clientname,extraname,invoicedate,invoicetime,accountingdate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,
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
            contact,invoicehistory.clientid,clientname,extraname,invoicedate,invoicetime,accountingdate, deliverydate,paybydate,name,tahitinumber,rc,
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
$invoicedate = $row['invoicedate'];
$invoicetime = $row['invoicetime'];
$paybydate = $row['paybydate'];

$invoicetagid = $row['invoicetagid'];

$field1 = $row['field1'];
$field2 = $row['field2'];

$invoicecomment = $row['invoicecomment'];
$invoicecomment2 = $row['invoicecomment2'];

$clientid = $row['clientid'];
$clientname = $row['clientname'];
$companytypename = $row['companytypename'];

$extraaddressid = $row['extraaddressid'];
$postaladdress = $row['postaladdress'];
$address = $row['address'];

$postalcode = $row['postalcode'];
$townid = $row['townid'];

$employeeid = $row['employeeid'];

$proforma = $row['proforma'];
$isreturn = $row['isreturn'];
$confirmed = $row['confirmed'];

$typetext = 'TICKET DE CAISSE';

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

if ($invoicetagid > 0)
{
  $query = 'SELECT invoicetagname FROM invoicetag WHERE invoicetagid = ?';
  $query_prm = array($invoicetagid);

  require('inc/doquery.php');
  $invoicetagname = $query_result[0]['invoicetagname'];
}


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

  if ($postaladdress != "")
  {
    $address = stripslashes($row3['postaladdress']);
  }
  else
  {
    $address = stripslashes($row3['address']);
  }
}

$query = 'SELECT eancode,displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,
          productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,
          lineprice,linevat,itemcomment,taxcode
          FROM invoiceitem,product,unittype,taxcode
          WHERE invoiceitem.productid = product.productid
          AND product.unittypeid = unittype.unittypeid
          AND product.taxcodeid = taxcode.taxcodeid
          AND invoiceitem.invoiceid = ?
          ORDER BY invoiceitemid';

if ($usehistory)
{
  $query = 'SELECT eancode,displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,
            unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,
            linevat,itemcomment,taxcode
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

$colspan = 8;

/*if ($totalrebate == 0)
{
  $colspan--;
}*/


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


  $productname = strtoupper(d_decode($row2['productname'])) . ' ';

  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1)
  {
    $productname = $productname . $row2['numberperunit'] . ' x ';
  }

  $productname = $productname . $row2['netweightlabel'];
  if (floor($y / $linesperpage) == ($pagenumber - 1))
  {
    $informationTable .= '<tr>';
    $informationTable .= '<td id="main" colspan=4>' . $quantity . ' ' . $productname . '</td>';
    $informationTable .= '<td id="mainprice">' . myfix($row2['lineprice']+$row2['linevat']) . '</td></tr>';
  }
}

if ($totalpages == 1 || $pagenumber == $totalpages)
{

  $informationTotalPages .= '<tr><td id=totaltext colspan=4>TOTAL TTC</td><td id=totalnumber>' . myfix($invoiceprice) . '</td></tr>';    

  if ($totaltva > 0)
  {
    $informationTotalPages .= '<tr><td colspan=5>&nbsp;</td></tr><tr><td id=totaltext colspan=3>&nbsp;</td><td colspan=2>';
    $informationTotalPages .= '
      <table class="vat">
        <tr>
          <td id=vattdtitle>TAUX</th>
          <td id=vattdtitle>MONTANT HT</th>
          <td id=vattdtitle>T.V.A</th>
        </tr>';      

    $taxcount = 0;$taxcodeid_prev = -1;
    foreach ($taxcodeA as $taxcodeid => $taxcode)
    {
      if ($taxcount > 0 && $tvaM[$taxcode_prev] > 0 && $tvaM[$taxcode] > 0 && $taxcount != (count($taxcodeA)-1))  
      {     
        $informationTotalPages .= '&nbsp;</td></tr>';        
      }
        
      if ($tvaM[$taxcode] > 0)
      {
        $informationTotalPages .= '
        <tr>
          <td id=maintdnumberswithoutborder> ' . $taxcode . '<span class="small-percent">%</span></td>
          <td id=vattdnumbers>  ' . myfix($tvaMt[$taxcode]) .'</td>
          <td id=maintdnumberswithoutborder> ' . myfix($tvaM[$taxcode]) .'</td>';
        $taxcode_prev = $taxcode;
      }
      $taxcount ++;          
    }
    $informationTotalPages .= '</table>';
  }
  
  $informationTotalPages .= '<tr><td colspan=5 id=main>Nombre d\'articles = ' . $totalnum . '</td>';
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
    $informationIsNotice .= '<tr><td colspan=5 id=main>PAIEMENT:</td></tr>';

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
        $chequeno = $row['chequeno'];
         
        //if ($paymenttypeid > 1)
        //{
          $informationIsNotice .= '<tr><td id=main>&nbsp;</td><td colspan=2 id=main>' . strtoupper($paymenttypename) . ': ';
        //}
        $informationIsNotice .= myfix($value);

        if ($bankid > 0)
        {
          $informationIsNotice .= ' ' . $bankA[$bankid];
        }
        $informationIsNotice .= ' ' . $chequeno . '</td><td id=main colspan=2>&nbsp;</td></tr>';
      }
    }
  }
}

$ourlogofile = 'custom_available/' . $ds_customname . '_small.jpg';
 ?>
<pre>
  <table style="width: 10cm">
      <?php if (file_exists($ourlogofile)): ?>
        <tr><td colspan=5 id=titlebold><img src="<?php echo $ourlogofile; ?>"></td></tr>     
      <?php endif; ?>  
    <tr><td colspan=5 id=titlebold>BOULANGERIE AFAAHITI</td></tr>
    <tr><td colspan=5 id=title>RC 7258 B - N° TAHITI 514547</td></tr>
    <tr><td colspan=5 id=title>PK 2.2 COTÉ MONTAGNE</td></tr>
    <tr><td colspan=5 id=title>BP 7030 - TARAVAO 98719</td></tr>
    <tr><td colspan=5 id=title>Tél : 40 57 14 12 - Fax : 40 57 51 84</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>
    <tr><td id=main>Facture N°</td><td id=main>:</td><td id=main><?php echo $format_invoiceid; ?></td><td id=main colspan=2>&nbsp;</td></tr>
    <tr><td id=main>LIVREUR <td id=main>:</td></td><td id=main><?php echo d_output($employeename); ?></td><td id=main colspan=1>&nbsp;</td></tr>
    <tr><td id=main>Date<td id=main>:</td></td><td id=main colspan=2><?php echo datefix2($invoicedate) . ' à ' . $invoicetime; ?></td><td id=main colspan=2>&nbsp;</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>
    <tr><td colspan=5 id=titlebold>--------------------------------------------------</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>    
    <?php echo $informationTable;?>
    <tr><td colspan=5>&nbsp;</td></tr>
    <?php if (($totalpages == 1 || $pagenumber == $totalpages))
    {
         echo $informationTotalPages;
    }?>
    <tr><td colspan=5>&nbsp;</td></tr> 
    <?php echo $informationIsNotice; ?>
    <tr><td colspan=5>&nbsp;</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>
    <tr><td colspan=5>&nbsp;</td></tr>        
    <tr><td colspan=5 id=title>Délai de retour 48h sous condition.</td></tr>
    <tr><td colspan=5 id=titlebold>MERCI DE VOTRE FIDELITE</td></tr>
    <tr><td colspan=5 id=titlebold>A BIENTOT</td></tr>
  </table>
</pre>