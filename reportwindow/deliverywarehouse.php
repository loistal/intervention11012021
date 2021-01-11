<?php
require('preload/unittype.php');

$warehouseid = $_POST['warehouseid']+0;
$temperatureid = $_POST['temperatureid']+0;

$invoicegroupid = $_POST['invoicegroupid'];
if ($invoicegroupid == "") { $invoicegroupid = -1; }
if ($invoicegroupid > 0)
{
  if ($_POST['invoicegroupid2'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid2']; }
  if ($_POST['invoicegroupid3'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid3']; }
  if ($_POST['invoicegroupid4'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid4']; }
  if ($_POST['invoicegroupid5'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid5']; }
  if ($_POST['invoicegroupid6'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid6']; }
  if ($_POST['invoicegroupid7'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid7']; }
  if ($_POST['invoicegroupid8'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid8']; }
  if ($_POST['invoicegroupid9'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid9']; }
  if ($_POST['invoicegroupid10'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid10']; }
  if ($_POST['invoicegroupid11'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid11']; }
  if ($_POST['invoicegroupid12'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid12']; }
  if ($_POST['invoicegroupid13'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid13']; }
  if ($_POST['invoicegroupid14'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid14']; }
  if ($_POST['invoicegroupid15'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid15']; }
  if ($_POST['invoicegroupid16'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid16']; }
  if ($_POST['invoicegroupid17'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid17']; }
  if ($_POST['invoicegroupid18'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid18']; }
  if ($_POST['invoicegroupid19'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid19']; }
  if ($_POST['invoicegroupid20'] > 0) { $invoicegroupid = $invoicegroupid . ',' . $_POST['invoicegroupid20']; }
}
$invoicegroupid = '(' . $invoicegroupid . ')';
if ($invoicegroupid == '()') { $invoicegroupid = '(-1)'; }
$preparationtext = "";
$ourtitle = "Bon pour Entrepot";
if ($warehouseid > 0)
{
  $query = 'select warehousename from warehouse where warehouseid=?';
  $query_prm = array($warehouseid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $warehousename = $row['warehousename'];
  $ourtitle = $ourtitle . ' ' . $warehousename;
}

$query = 'select preparationtext,curdate() as curdate from invoicegroup where invoicegroupid IN ' . $invoicegroupid;
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($i > 1 && $row['preparationtext'] != "") { $preparationtext = $preparationtext . ' '; }
  $preparationtext = $preparationtext . $row['preparationtext'];
  $curdate = $row['curdate'];
}
$title = datefix($curdate) . ' - ' . $ourtitle . $invoicegroupid . ' - ' . $preparationtext;
showtitle($title);

echo '<font size=+1><b>' . datefix($curdate) . ' - ' . $ourtitle . $invoicegroupid . ' - ' . $preparationtext . '</b></font>';
$query = 'select product.productid,suppliercode,invoiceitemid,townname,invoicehistory.invoiceid,familyrank
,productfamily.productfamilyid,customorder,extraname,clientname,productname,quantity,numberperunit,unittypeid
,netweightlabel,unittypeid,islandname,productfamilygroupid,product.temperatureid
from client,invoicehistory,invoiceitemhistory,product,productfamily,town,island
where invoicehistory.clientid=client.clientid and product.productid=invoiceitemhistory.productid
and client.townid=town.townid and town.islandid=island.islandid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and product.productfamilyid=productfamily.productfamilyid';
$query = $query . ' and invoicegroupid IN ' . $invoicegroupid;
if ($warehouseid>0) { $query = $query . ' and product.warehouseid="'.$warehouseid.'"'; }
if ($temperatureid>=0) { $query = $query . ' and product.temperatureid="'.$temperatureid.'"'; }
if ($temperatureid==-2) { $query = $query . ' and product.temperatureid>0'; }
#$query = $query . ' order by customorder,islandname,townname,clientname';
#if ($temperatureid>0) { $query = $query . ',familyrank,productfamily.productfamilyid'; }
$query .= ' order by product.temperatureid desc';
$query_prm = array();
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $clientname = $row['townname'] . ': ' . d_decode($row['clientname']) . ' ' .  $row['extraname'];
  $productname = $row['productid'] . ' ' . d_decode($row['productname']) . ' ';
  if ($row['numberperunit'] > 1) { $productname .= $row['numberperunit'] . ' x '; }
  $productname .= $row['netweightlabel'];
  $productname .= ' (' . $row['suppliercode'] . ')';
  $toadd = $row['quantity'];
  if ($unittype_dmpA[$row['unittypeid']] != 1)
  {
    $toadd = $toadd / $unittype_dmpA[$row['unittypeid']];
    if (!isset($cowan2A[$clientname][$productname])) { $cowan2A[$clientname][$productname] = 0; }
    $cowan2A[$clientname][$productname] += 1;
  }
  if (!isset($cowanA[$clientname][$productname])) { $cowanA[$clientname][$productname] = 0; }
  $cowanA[$clientname][$productname] += $toadd; # productname as index!? refactor
  $npuA[$productname] = $row['numberperunit'];
  $unittypenameA[$productname] = $unittypeA[$row['unittypeid']];
  $clientA[$i] = $clientname;
  $islandA[$clientname] = $row['islandname'];
  $productA[$i] = $productname;
  $order[$i] = 3;
  if ($row['productfamilygroupid'] == 32) { $order[$i] = 1; }
  if ($row['productfamilygroupid'] == 31) { $order[$i] = 2; }
  $productA[$i] = $order[$i] . $productA[$i];
}
$clientA = array_unique($clientA);
$productA = array_unique($productA);
sort($productA);
sort($clientA);

echo '<table class="report"><tr><td><b>Chauffeur:</td><td colspan=3>&nbsp;</td><td colspan=3><b>';
if ($temperatureid>0) { echo 'Bateau:'; }
else { echo 'N<superscript>o</superscript>&nbsp;vehicule:'; }
echo '</td><td colspan=50>&nbsp;</td></tr>';
echo '<tr><td>&nbsp;</td>';
foreach ($productA as $productname)
{
  echo '<td class="breakme"><font size=-2>' . wordwrap($productname, 8, "<br>", TRUE) . '</font></td>';  #$productname = wordwrap($productname, 8, "<br>"); # substr($productname,1)
}
echo '</tr>';
$i=0;
foreach ($clientA as $clientname)
{
  $i++;
  if ($i==1 || $lastisland != $islandA[$clientname]) { echo '<tr><td colspan=50><font size=-2><b>' . $islandA[$clientname] . '</b></font></td></tr>'; }
  $lastisland = $islandA[$clientname];
  $showclientname = $clientname;
  $showclientname = str_ireplace('MAGASIN','Mag',$showclientname);
  $showclientname = str_ireplace('RESTAURANT','Res',$showclientname);
  $showclientname = str_ireplace('SNACK','Sna',$showclientname);
  $showclientname = str_ireplace('STATION','Sta',$showclientname);
  $showclientname = str_ireplace('PATISSERIE','Pat',$showclientname);
  $showclientname = str_ireplace('LYCEE','Lyc',$showclientname);
  $showclientname = str_ireplace('ROULOTTE','Rou',$showclientname);
  #$showclientname = substr($showclientname,0,25);
  echo '<tr><td><font size=-2>' . $showclientname . '</font></td>'; #class="breakme"
  foreach ($productA as $productname)
  {
    $productname = substr($productname,1);
    $units = 0;
    if (isset($npuA[$productname])) { $npu = $npuA[$productname]; } else { $npu = 1; }
    if (!isset($cowanA[$clientname][$productname])) { $cartons = 0; }
    else
    {
      $cartons = $cowanA[$clientname][$productname] / $npu;
      $units = $cowanA[$clientname][$productname] % $npu;
      if ($unittypenameA[$productname] != 'KG') { $cartons = floor($cartons); } # hack, throw this whole report away
    }
    $showcartons = $cartons;
    if (isset($cowan2A[$clientname][$productname]) && $cowan2A[$clientname][$productname] > 0)
    { $cartons = $cowan2A[$clientname][$productname]; $showcartons .= '&nbsp;('.$cartons.')'; }
    elseif ($unittypenameA[$productname] == 'Unité' && $showcartons > 0) { $showcartons = $cowanA[$clientname][$productname] . ' u'; }
    elseif ($units > 0) { $showcartons = $showcartons . ' +'.$units . 'u'; }
    if ($showcartons == "") { $showcartons = '&nbsp;'; }
    echo '<td align=right><font size=-1>' . $showcartons . '</font></td>';
    if (!isset($totalA[$productname])) { $totalA[$productname] = 0; }
    $totalA[$productname] += $cartons;
  }
  echo '</tr>';
}
echo '<tr><td><b>Total:</td>';
foreach ($totalA as $total)
{
  $total += 0;
  #$total = myfix($total,2);
  echo '<td align=right><b>' . $total . '</b></td>';
}
echo '</tr>';
if ($temperatureid == 0) { echo '<tr><td><b>Date:</td><td>&nbsp;</td><td><b>Signature:</td><td colspan=2>&nbsp;</td></tr>'; }
echo '</table>';
?>