<style type="text/css">

  /*********************************************/
  /*          Base rules                       */
  /*********************************************/
  * {
    margin: 0;
    background: #FFF;
  }

  html, body {
    height: 100%;
    background: #FFF;
  }

  body, table {
    font-family: Calibri;
    font-size: Medium;
  }

  td {
    padding: 3px;
  }

  /*********************************************/
  /*         Modules Rules                     */
  /*********************************************/
  .logo {
    text-align: left;
    position: absolute;
    left: 0;
    top: 0;
    width: 250px;
    height: 150px;
  }

  .box1 {
    position: absolute;
    left: 0;
    top: 151px;
    width: 350px;
    height: 249px;
  }

  .box2 {
    position: absolute;
    left: 250px;
    top: 0;
    width: 250px;
    height: 150px;
    font-size: small;
  }

  .box2 span {
    position: absolute;
    bottom: 0;
    left: 0;
  }

  .box3 {
    position: absolute;
    left: 500px;
    top: 0;
    width: 250px;
    height: 150px;
  }

  .invoiceheading {
    font-size: X-Large;
    font-weight: bold;
  }

  .company {
    position: absolute;
    left: 400px;
    top: 175px;
    width: 250px;
    height: 125px;
  }

  .items {
    position: absolute;
    left: 0;
    top: 375px;
    width: 750px;
  }

  .small {
    font-size: small;
  }
</style>

<?php

$PA['invoiceid'] = 'uint';
require('inc/readpost.php');

require('inc/fulltextcurrency_func.php');
require('preload/taxcode.php');
require('preload/employee.php');
require('preload/town.php');
require('preload/island.php');

$totaltva = 0; $totalht = 0; unset($tvaM); unset($tvaMt);

$usehistory = 0;

$pagenumber = $_POST['pagenumber']+0;
$linesperpage = $_POST['linesperpage']+0;
if ($pagenumber < 1) { $pagenumber = 1; }
if ($linesperpage < 1) { $linesperpage = 16; }

$query = 'select townid,invoice.userid,invoicetagid,vatexempt,isnotice,contact,invoice.clientid,clientname,extraname,accountingdate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,email,fax,extraaddressid,clienttermname,confirmed from invoice,client,usertable,clientterm where invoice.clientid=client.clientid and invoice.userid=usertable.userid and client.clienttermid=clientterm.clienttermid and invoice.invoiceid="' . $invoiceid . '"';
if ($_SESSION['ds_clientaccess'] == 1) { $query = $query . ' and client.clientid="' . $_SESSION['ds_userid'] . '"'; }
if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoice.clientid in ' . $_SESSION['ds_allowedclientlist']; }
if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $queryadd = ' and (invoice.userid="'.$_SESSION['ds_userid'].'"';
  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $queryadd .= ' or invoice.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
  }
  $query .= $queryadd.')';
}
$query_prm = array();
require('inc/doquery.php');
if (!$num_results)
{
  $usehistory = 1;
  $query = 'select townid,invoicehistory.userid,invoicetagid,vatexempt,isnotice,contact,invoicehistory.clientid,clientname,extraname,accountingdate,deliverydate,paybydate,name,tahitinumber,rc,invoiceprice,isreturn,proforma,invoicecomment,invoicecomment2,reference,cancelledid,companytypename,address,postaladdress,postalcode,telephone,cellphone,email,fax,extraaddressid,clienttermname,confirmed from invoicehistory,client,usertable,clientterm where invoicehistory.clientid=client.clientid and invoicehistory.userid=usertable.userid and client.clienttermid=clientterm.clienttermid and invoicehistory.invoiceid="' . $invoiceid . '"';
  if ($_SESSION['ds_clientaccess'] == 1) { $query = $query . ' and client.clientid="' . $_SESSION['ds_userid'] . '"'; }
  if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and invoicehistory.clientid in ' . $_SESSION['ds_allowedclientlist']; }
  if ($_SESSION['ds_confirmonlyown'] == 1)
  {
    $queryadd = ' and (invoicehistory.userid="'.$_SESSION['ds_userid'].'"';
    if ($_SESSION['ds_myemployeeid'] > 0)
    {
      $queryadd .= ' or invoicehistory.employeeid="'.$_SESSION['ds_myemployeeid'].'"';
    }
    $query .= $queryadd.')';
  }
  $query_prm = array();
  require('inc/doquery.php');
}
if (!$num_results) { echo '<p class="alert">Facture inéxistante.</p>'; exit; } #,townname,islandname
$row = $query_result[0];
$isnotice = $row['isnotice']+0;
$userid = (int) $row['userid'];

$typetext = 'Facture ';
if ($row['proforma'] == 1)
{
  $typetext = 'Proforma ';
}
if ($isnotice)
{
  $typetext = $_SESSION['ds_term_invoicenotice'];
}
if ($row['isreturn'] == 1)
{
  $typetext = 'Avoir ';
}
#$year = mb_substr($_SESSION['ds_curdate'],0,4)+0;
$year = mb_substr($row['accountingdate'],0,4)+0;
$showinvoiceid = $invoiceid;
if (mb_strlen($invoiceid) == 1) { $showinvoiceid = '000' .  $invoiceid; }
if (mb_strlen($invoiceid) == 2) { $showinvoiceid = '00' .  $invoiceid; }
if (mb_strlen($invoiceid) == 3) { $showinvoiceid = '0' .  $invoiceid; }
showtitle($typetext . $year . $showinvoiceid);

/*
echo '<div class="tr1">';
$typetext = str_replace(" ", "&nbsp;", $typetext);
echo '<p align=center><b>' . $typetext . '</b></p><hr><p align=center><b>' . $invoiceid . '</b></p>';
echo '</div>';

echo '<div class="tr2">';
echo '<p align=center><b>';
echo $_SESSION['ds_term_accountingdate'];
echo '</b></p><hr><p align=center><b>' . datefix2($row['accountingdate']) . '</b></p>';
echo '</div>';

echo '<div class="tr3">';
echo '<p align=center><b>Client</b></p><hr><p align=center><b>' . $row['clientid'] . '</b></p>';
echo '</div>';
*/
/*
$ourlogofile = 'custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
if (file_exists($ourlogofile))
{
  echo '<div class="logo"><img src="' . $ourlogofile . '"></div>';
}
*/

echo '<div class="dlogo"><img src="pics/logo.png" height="50"></div>';

?>
<style>
.box2 {
  position: absolute;
  left: 0px;
  top: 0px;
  width: 450px;
  height: 150px;
  font-size: small;
}

.box2 span {
  position: relative;
}
</style>
<?php

echo '<div class="box2"><font size=+4><b>Pacific Technic</b></font><br><span>' . $_SESSION['ds_companyinfo'] . '</span></div>';



echo '<div class="box3"><br>
<span class="invoiceheading">' . mb_convert_case($typetext, MB_CASE_UPPER, "UTF-8") . ' ' . $year . $showinvoiceid . '</span><br>
' . datefix2($row['accountingdate']);
if ($row['accountingdate'] != $row['paybydate']) { echo '<br>Echéance ' . datefix2($row['paybydate']); } # TODO check french word     || 1==1
echo '</div>';

echo '<div class="company">';
echo '<p>' . d_output(d_decode($row['clientname'])) . ' ' . d_output($row['companytypename']);
#if ($row['extraname'] != "") { echo ' ' . $row['extraname']; }
if ($row['extraaddressid'] < 1)
{
  #if ($row['contact'] != "") { echo ' ATTN: ' . d_output($row['contact']); }
  echo '</p><p>' . d_output($row['address']) . ', ';
  if ($row['postaladdress'] != "")
  {
    echo d_output($row['postaladdress']) . ', ';
  }
  echo d_output($row['postalcode']) . ' ' . d_output($townA[$row['townid']]) . '</p>';
  $islandname = $islandA[$town_islandidA[$row['townid']]];
  if ($islandname != '') { echo '<p>' . $islandname . '</p>'; }
  #if ($row['telephone'] != "" || $row['cellphone'] != "" || $row['fax'] != "") { echo '<p>'; }
  #if ($row['telephone'] != "") { echo 'Tél: ' . $row['telephone'] . ' '; }
  #if ($row['cellphone'] != "") { echo 'Vini: ' . $row['cellphone'] . ' '; }
  #if ($row['fax'] != "") { echo 'Fax: ' . $row['fax']; }
  #if ($row['telephone'] != "" || $row['cellphone'] != "" || $row['fax'] != "") { echo '</p>'; }
  #    if ($row['email'] != "") { echo '<p>' . $row['email'] . '</p>'; }
}
else
{
  echo '</p>';
  $query = 'select address,postaladdress,postalcode,telephone,townname,islandname from extraaddress,town,island where extraaddress.townid=town.townid and town.islandid=island.islandid and extraaddressid="' . $row['extraaddressid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $row3 = $query_result[0];
  if ($row3['postaladdress'] != "") { $address = stripslashes($row3['postaladdress']); }
  else { $address = stripslashes($row3['address']); }
  echo '<p>' . $address . '</p>';
  echo '<p>' . $row3['postalcode'] . ' ' . $row3['townname'] . '</p>';
  echo '<p>' . $row3['islandname'] . '</p>';
  if ($row3['telephone'] != "") { echo '<p>' . $row3['telephone'] . '</p>'; }

}
echo '</div>';

/*
echo '<div class="items"><table class="report" style="width: 100%">
<tr><td>main table</td></tr>';
for ($i=1;$i<=30;$i++)
{
  echo '<tr><td>'.$i.'</td></tr>';
}
'</table></div>';
*/

/*
echo '<div class="infofact">';
echo $_SESSION['ds_infofact'];
echo '</div>';
*/

$query = 'select displaymultiplier,invoiceitemid,linetaxcodeid,invoiceitem.productid,productname,suppliercode,productdetails,unittypename,quantity,numberperunit,invoiceitem.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitem,product,unittype,taxcode where invoiceitem.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitem.invoiceid="' . $invoiceid . '" order by invoiceitemid';
if ($usehistory) { $query = 'select displaymultiplier,linetaxcodeid,invoiceitemhistory.productid,productname,suppliercode,productdetails,unittypename,quantity,numberperunit,invoiceitemhistory.productid,netweightlabel,givenrebate,basecartonprice,lineprice,linevat,itemcomment,taxcode from invoiceitemhistory,product,unittype,taxcode where invoiceitemhistory.productid=product.productid and product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid and invoiceitemhistory.invoiceid="' . $invoiceid . '" order by invoiceitemid'; }
$query_prm = array();
require('inc/doquery.php');
$num_results2 = $num_results;
$main_result = $query_result;
$totalpages = ceil($num_results2/$linesperpage);

echo '<div class="box1"><br>';
if ($_SESSION['ds_showtimeprinted']==1)
{
  #$query = 'select curtime() as curtime,curdate() as curdate,date_format(curdate(),"%w") as weekday';
  #$query_prm = array();
  #require ('inc/doquery.php');
  #$_SESSION['ds_curdate'] = $query_result[0]['curdate'];
  #$_SESSION['ds_curtime'] = $query_result[0]['curtime'];
  echo datefix2($_SESSION['ds_curdate']).' '.$_SESSION['ds_curtime'].' par '.d_output($_SESSION['ds_initials']).'<br>';
}
if ($usehistory) { $query = 'select concat(employeename," ",employeefirstname) as employeename from invoicehistory,employee where invoicehistory.employeeid=employee.employeeid and invoiceid="' . $invoiceid . '"'; }
else { $query = 'select concat(employeename," ",employeefirstname) as employeename from invoice,employee where invoice.employeeid=employee.employeeid and invoiceid="' . $invoiceid . '"'; }
$query_prm = array();
require('inc/doquery.php');
$num_results3 = $num_results;
if ($num_results3)
{
  $row3 = $query_result[0];
  echo '<p><b><u>';
  echo $_SESSION['ds_term_servedby'];
  echo ':</u> ' . $row3['employeename'] . '</b></p>';
}
if ($row['cancelledid']) { echo ' &nbsp; <font color="' . $alertcolor . '">ANNULEE</font><br>'; }
if ($row['reference'] != "")
{
  if ($_SESSION['ds_term_reference'] != "") { echo '<p><b>' . $_SESSION['ds_term_reference'] . ':</b> ' . $row['reference']; }
  else { echo '<p><b>Référence:</b> ' . $row['reference']; }
  echo '</p>';
}
if ($_SESSION['ds_term_extraname'] != "" && $row['extraname'] != "") { echo ' <b>' . $_SESSION['ds_term_extraname'] . ':</b> ' . $row['extraname']; }
if ($row['fax'] != "") { echo '&nbsp;-&nbsp;Fax: ' . d_output($row['fax']); }
if ($row['reference'] != "" || $_SESSION['ds_term_extraname'] != "") { echo '<br>'; }
if ($row['proforma'] == 1) { echo ' &nbsp <b>PROFORMA</b> '; }
if (($row['deliverydate'] != $row['accountingdate']) && ($_SESSION['ds_term_accountingdate'] != $_SESSION['ds_term_deliverydate']) && $_SESSION['ds_hidedeliverydate'] != 1)
{
  echo ' &nbsp; <b>';
  echo $_SESSION['ds_term_deliverydate'];
  echo '</b>: ' . datefix2($row['deliverydate']);
}
if ($row['invoicetagid'] > 0)
{
  echo ' &nbsp <b>';
  if ($_SESSION['ds_term_invoicetag']) { echo $_SESSION['ds_term_invoicetag']; }
  else { echo 'Tag'; }
  echo ':</b> ';
  $query = 'select invoicetagname from invoicetag where invoicetagid="' . $row['invoicetagid'] . '"';
  $query_prm = array();
  require('inc/doquery.php');
  $rowX = $query_result[0];
  echo $rowX['invoicetagname'];
  echo '<br>';
}
if ($row['invoicecomment'] != "") { echo $row['invoicecomment'] . '<br>'; }
/*
if ($row['invoicecomment2'] != "")
{
  $invoicecomment2 = str_replace("§", '<br>', $row['invoicecomment2']);
  echo '<span class="tiny">' . $invoicecomment2 . '</span>';
}
*/
if ($num_results2 > $linesperpage) { echo 'Page ' . $pagenumber . '/' . $totalpages . '<br>'; }
echo '</div>';







echo '<div class="items">';


echo '<style>
table {
  border-collapse: collapse;
}
table td { 
border: 1px solid;

 }

</style>';
echo '<table class="report" style="width: 100%"><tr>'; # class="invoiceitems
$colspan = 5;
if ($_SESSION['ds_useitemadd']) { echo '<td><b>Date</b></td><td><b>Début</b></td><td><b>Fin</b></td><td><b>Employé</b></td>'; $colspan=$colspan+4; }
echo '<td><b>Produit</b></td><td><b>Quantité</b></td>';
if ($isnotice) { }
else { echo '<td><b>Prix UHT</b></td><td><b>Remise</b></td><td><b>TVA</b></td><td><b>Total HT</b></td></tr>'; }
for ($y=1; $y <= $num_results2; $y++)
{
  $row2 = $main_result[($y-1)];
  $totalht = $totalht + $row2['lineprice'];
  $quantity = $row2['quantity']/$row2['numberperunit']; $unittypename = $row2['unittypename'];
  $bcp = myround($row2['basecartonprice']);
  if ($_SESSION['ds_useunits'] && $row2['quantity']%$row2['numberperunit']) { $quantity = $row2['quantity']; $unittypename = 'pièce'; $bcp = myround($bcp/$row2['numberperunit']); }
  $bcpdivider = $bcp; if ($bcpdivider == 0) { $bcpdivider = 1; }
  $gr = round((100*$row2['givenrebate']/$bcpdivider)/($quantity),0); # need percentage     2013 11 07 rounding to whole number as per AF request
  if ($gr == 0) { $gr = '&nbsp;'; }
  else { $gr = $gr . ' %'; }
  $totaltva = $totaltva + myround($row2['linevat']);
  $showtva = myround($row2['taxcode']) . '&nbsp;%';
  $kladd = $row2['taxcode']; # todo should be taxcodeID
  if ($row2['linetaxcodeid'] > 0)
  {
    $kladd = $taxcodeA[$row2['linetaxcodeid']];
    if ($row2['linetaxcodeid'] == 59999) { $showtva = '0&nbsp;%'; }
    else { $showtva = myround($taxcodeA[$row2['linetaxcodeid']]) . '&nbsp;%'; }
  }
  #$tvaM[$kladd] = $tvaM[$kladd] + myround($row2['lineprice'] * $kladd/100);
  $tvaM[$kladd] = $tvaM[$kladd] + myround($row2['linevat']);
  $tvaMt[$kladd] = $tvaMt[$kladd] + myround($row2['lineprice']);
  $productname = $row2['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $productname = $row2['suppliercode']; }
  $productname = $productname . ': ' . stripslashes($row2['productname']) . ' ';
  if ($_SESSION['ds_useunits'] == 1 && $row2['numberperunit'] > 1) { $productname = $productname . $row2['numberperunit'] . ' x '; }
  $productname = $productname . $row2['netweightlabel'];
  if (ceil($y/$linesperpage) == $pagenumber) # not a good way to do this, create start and stop $y
  {
    echo '<tr>';
    echo '<td valign=top>' . $productname . '';
    if ($row2['productdetails'] != "") { echo '<br>' . $row2['productdetails']; }
    if ($_POST['hidediscount'] == 1) { $bcp = $row2['lineprice']/$quantity; $gr = '&nbsp;'; }
    
    if ($row2['displaymultiplier'] != 1)
    {
      $quantity = $quantity / $row2['displaymultiplier'];
      $bcp = $row2['basecartonprice'] * $row2['displaymultiplier'];
    }
    
    echo '</td><td valign=top>' . $quantity . ' ' . $unittypename . '</td>';
    if ($isnotice) { }
    else { echo '<td align=right valign=top>' . myfix($bcp) . '</td><td align=right valign=top>' . $gr . '</td><td align=right valign=top>' .$showtva . '</td><td align=right valign=top>' . myfix($row2['lineprice']) . '</td></tr>'; }
    if ($row2['itemcomment'] != "")
    {
      $itemcomment = str_replace('§', '<br>', $row2['itemcomment']);
      echo '<tr><td class="breakme" colspan=' . ($colspan+1) . '><span class="tiny">' . $itemcomment . '</span></td></tr>';
    }
  }
}
if ($totalpages == 1 || $pagenumber == $totalpages)
{
  if ($row['freightcost'] > 0) { echo '<tr><td colspan=' . $colspan . '>Frêt</td><td align=right>' . myfix($row['freightcost']) . '</td></tr>'; } # deprecated
  if ($row['insurancecost'] > 0) { echo '<tr><td colspan=' . $colspan . '>Assurance</td><td align=right>' . myfix($row['insurancecost']) . '</td></tr>'; } # deprecated
  if ($isnotice == 0) { echo '<tr><td colspan=' . $colspan . '>Total HT</td><td align=right>' . myfix($totalht) . '</td></tr>'; }
  # need to check if vat was paid on this invoice, not if client is currently exempt
  #  && $row['vatexempt'] != 1
  if ($isnotice) { }
  else
  {
    if ($totaltva > 0) { echo '<tr><td colspan=' . $colspan . '>TVA</td><td align=right>' . myfix($totaltva) . '</td></tr>'; }
    echo '<tr><td colspan=' . $colspan . '>';
    if ($row['isreturn'] == 1) { echo 'Total à rembourser'; }
    else { echo 'Total à payer'; }
    echo '</td><td align=right><b>' . myfix($row['invoiceprice']) . '</b></td></tr>';
  }
}
echo '</table>';

if ($totaltva > 0 && ($totalpages == 1 || $pagenumber == $totalpages))
{
  echo '<br><table class="report"><tr><td><b>Taux TVA</b></td><td><b>Base HT</b></td><td><b>Montant TVA</b></td></tr>';
  foreach ($taxcodeA as $taxcodeid => $taxcode)
  {
    if ($tvaM[$taxcode] > 0) # todo should be taxcodeID
    {
      echo '<tr><td align=right>' . $taxcode . ' %</td><td align=right>' . myfix($tvaMt[$taxcode]) . '</td><td align=right>' . myfix($tvaM[$taxcode]) . '</td></tr>';
    }
  }
  echo '</table>';
}

if ($row['proforma'] == 1)
{
  /* echo '<br><table border=1 width=80%><tr><td><p class=small>
  <font size=+1><b><u>CONDITIONS COMMERCIALES</u></b></font><br>
  La présente offre a une durée de validité de 30 jours<br>
  Paiement : à ___ jours ou (___% à la commande et ___% à la réception du matériel par chèque)<br>
  Délais de fabrication : ___ jours<br>
  Délais d\'approvisionnement : ___ semaines<br>
  Garantie : ___ mois<br>
  Tous les travaux supplémentaires feront l\'objet d\'un devis complémentaire et d\'une commande, ou seront consignés sur attachement(s) dûment signé(s) par le client.
  </p></td></tr></table>'; */
  echo '<br><table border=1 width=80%><tr><td><p class=small>' . $row['invoicecomment2'] . '</p></td></tr></table>';
}
else
{
  echo '<p class="small">' . $_SESSION['ds_infofact'] . '</p>';
}

if ($totalpages == 1 || $pagenumber == $totalpages)
{
  if ($row['proforma'] == 0 && $isnotice == 0)
  {
    echo '<br><p>Arrêté la présente facture à la somme de : ' . convertir($row['invoiceprice']) . ' CFP.</p>';
  }
}

if ($isnotice == 0)
{
# show payments
$invoiceprice = $row['invoiceprice'];
$totalpaid = 0; $paymentid = 0;
$query = 'select paymentid,value,reimbursement,paymenttypename,payment.paymenttypeid,bankid,chequeno from payment,paymenttype where payment.paymenttypeid=paymenttype.paymenttypeid and forinvoiceid="' . $invoiceid . '"';
$query_prm = array();
require('inc/doquery.php');
for ($y=1; $y <= $num_results; $y++)
{
  $row = $query_result[($y-1)];
  if ($row['reimbursement'] == 1) { $totalpaid = $totalpaid - $row['value']; }
  else
  { 
    $totalpaid = $totalpaid + $row['value'];
    if ($paymentid > 0) { $paymentid = -1; }
    if ($paymentid == 0)
    { 
      $paymentid = $row['paymentid']; $paymenttypename = $row['paymenttypename'];  $paymenttypeid = $row['paymenttypeid']; $bankid = $row['bankid']; $chequeno = $row['chequeno'];
    }
  }
}
if ($totalpaid >= $invoiceprice)
{
  echo '<p>Cette facture a été entierement reglé.';
  if ($paymentid > 0)
  {
    echo ' (Paiement ' . $paymentid . ', ' . $paymenttypename;
    if ($paymenttypeid > 1)
    {
      echo ': ';
      if ($bankid > 0)
      {
        $query = 'select bankname from bank where bankid="' . $bankid . '"';
        $query_prm = array();
        require('inc/doquery.php');
        $row = $query_result[0];
        echo $row['bankname'];
      }
      echo ' ' . $chequeno;
    }
    echo ')';
  }
  echo '</p>';
}
}
echo '</div>';

if ($logmeout == 1)
{
  session_unset();
  session_destroy();
}

?>