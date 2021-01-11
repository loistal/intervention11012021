<?php

# TODO complete refactor

$PA['product'] = '';
$PA['saveme'] = 'uint';
require('inc/readpost.php');

require('inc/findproduct.php'); if (!isset($productid)) { $productid = -1; }
require('preload/taxcode.php');
require('preload/unittype.php');
require('preload/producttype.php');

if ($productid < 1)
{
    echo '<h2>Ajuster prix pour un produit:</h2><form method="post" action="products.php"><table><tr><td>';
    require('inc/selectproduct.php');
    echo '</td></tr><tr><td colspan="2" align="center"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type="submit" value="Valider"></td></tr></table></form>';
}
else
{
  if ($saveme)
  {
    $query = 'select salesprice,suppliercode,numberperunit,taxcodeid,producttypeid,unittypeid from product where productid=?';
    $query_prm = array($productid);
    require('inc/doquery.php');
    $row = $query_result[0];
    $suppliercode = $row['suppliercode'];
    $taxcode = $taxcodeA[$row['taxcodeid']];
    $producttypename = $producttypeA[$row['producttypeid']];
    $dmp = $unittype_dmpA[$row['unittypeid']]; if ($dmp < 1) { $dmp = 1; }
    $old_salesprice = $row['salesprice']+0;
    
    $salesprice = (int) $_POST['salesprice'];
    $islandregulatedprice = $_POST['islandregulatedprice']+0;
    $retailprice = $_POST['retailprice']+0;
    if ($_POST['salespricettc'] > 0)
    {
      $salesprice = ((100 * $_POST['salespricettc']) / (100 + $taxcode)); # 2016 12 05 took off myround()
    }
    $detailsalesprice = (double) $_POST['detailsalesprice'];
    if ($_POST['detailsalespricettc'] > 0)
    {
      $detailsalesprice = ((100 * $_POST['detailsalespricettc']) / (100 + $taxcode));
    }
    $unitsalesprice = $_POST['unitsalesprice']+0;
    if ($_POST['unitsalespricettc'] > 0)
    {
      $unitsalesprice = ((100 * $_POST['unitsalespricettc']) / (100 + $taxcode));
    }
    $unitdetailsalesprice = $_POST['unitdetailsalesprice']+0;
    if ($_POST['unitdetailsalespricettc'] > 0)
    {
      $unitdetailsalesprice = ((100 * $_POST['unitdetailsalespricettc']) / (100 + $taxcode));
    }
    $salesprice = $salesprice / $dmp;
    $detailsalesprice = $detailsalesprice / $dmp;
    $unitsalesprice = $unitsalesprice / $dmp;
    $unitdetailsalesprice = $unitdetailsalesprice / $dmp;
    $islandregulatedprice = $islandregulatedprice / $dmp;
    $retailprice = $retailprice / $dmp;

    $query = 'insert into log_salesprice (userid,productid,retailprice,islandregulatedprice,old_salesprice,salesprice
    ,taxcodeid,detailsalesprice,unitsalesprice,unitdetailsalesprice,logdate,logtime) values (?,?,?,?,?,?,?,?,?,?,curdate(),curtime())';
    $query_prm = array($_SESSION['ds_userid'],$productid,$retailprice,$islandregulatedprice,$old_salesprice,$salesprice
    ,$row['taxcodeid'],$detailsalesprice,$unitsalesprice,$unitdetailsalesprice);
    require('inc/doquery.php');
    
    $query = 'update product set retailprice="' . $retailprice . '",islandregulatedprice="' . $islandregulatedprice . '",salesprice="' . $salesprice . '",detailsalesprice="' . $detailsalesprice . '",unitsalesprice="' . $unitsalesprice . '",unitdetailsalesprice="' . $unitdetailsalesprice . '" where productid="' . $productid . '"';
    $query_prm = array();
    require('inc/doquery.php');
    if ($_SESSION['ds_useproductcode'] == 1) 
    {
      echo 'Produit ' . $suppliercode . ' mis au prix ' . $salesprice;
      if ($_SESSION['ds_term_prixalternatif'] != '') { echo ', '.$_SESSION['ds_term_prixalternatif'].' ' . $detailsalesprice . '.'; }
    }
    else
    { 
      echo '<p>Produit ';
      if ($_SESSION['ds_useproductcode'] == 1) { echo $suppliercode; }
      else { echo $productid; }
      echo ' mis au prix ' . d_input($salesprice,'decimal');
      if ($_SESSION['ds_term_prixalternatif'] != '') { echo ', '.$_SESSION['ds_term_prixalternatif'].' ' . $detailsalesprice . '.</p><br>'; }
    }
  }

  $query = 'select countstock,margin,margintype,retailprice,islandregulatedprice,producttypeid,product.productid,suppliercode
  ,currentstock,currentstockrest,numberperunit,netweightlabel,product.unittypeid as unittypeid,productname,salesprice
  ,detailsalesprice,unitsalesprice,unitdetailsalesprice,weight,taxcodeid from product where';
  #if ($_SESSION['ds_useproductcode'] == 1 && $_POST['productiddirect'] != "") { $query = $query . ' and suppliercode like "%' . $productid . '%" order by suppliercode limit 1'; }
  #else { $query = $query . ' and productid="' . $productid . '"'; }
  $query = $query . ' productid="' . $productid . '" limit 1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results == 0) { echo 'No such productID.'; exit; }
  $row = $query_result[0];
  $countstock = $row['countstock'];
  $margin = $row['margin'];
  $margintype = $row['margintype'];
  $currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
  #$taxcode = $row['taxcode']; ###
  $taxcode = $taxcodeA[$row['taxcodeid']];
  $producttypename = $producttypeA[$row['producttypeid']];
  $productname = d_decode($row['productname']) . ' ';
  $realproductid = $row['productid']; if ($_SESSION['ds_useproductcode'] == 1) { $realproductid = $row['suppliercode']; }
  $numberperunit = $row['numberperunit']; if ($numberperunit < 1) { $numberperunit = 1; }
  $currentstock = ($row['currentstock'] * $numberperunit) + $row['currentstockrest'];
  if ($_SESSION['ds_useunits'] && $numberperunit > 1) { $productname = $productname . $numberperunit . ' x '; }
  $productname = $productname . $row['netweightlabel'];
  $currentprice = $row['salesprice']+0;
  $detailsalesprice = $row['detailsalesprice']+0;
  $islandregulatedprice = $row['islandregulatedprice']+0;
  $retailprice = $row['retailprice']+0;
  $unitsalesprice = $row['unitsalesprice']+0;
  $unitdetailsalesprice = $row['unitdetailsalesprice']+0;
  $margintypename = "%";
  if ($margintype == 1) { $margintypename = "XPF"; }
  if ($margintype == 2) { $margintypename = "Prix Entr. Réel/Carton"; }
  #if ($producttypename == "PPN") { $margintypename = "XPF"; }
  #if ($producttypename == "PAO") { $margintypename = "PAO"; } # might be different for PAO
  $unittypename = $unittypeA[$row['unittypeid']];
  if ($_SESSION['ds_useproductcode'] == 1) { $showproductid = $row['suppliercode']; }
  else { $showproductid = $row['productid']; }
  
  $dmp = $unittype_dmpA[$row['unittypeid']];
  if($dmp != 1)
  {
    $currentprice = bcmul($currentprice,$dmp);
    $detailsalesprice = bcmul($detailsalesprice,$dmp);
    $islandregulatedprice = bcmul($islandregulatedprice,$dmp);
    $retailprice = bcmul($retailprice,$dmp);
    $unitsalesprice = bcmul($unitsalesprice,$dmp);
    $unitdetailsalesprice = bcmul($unitdetailsalesprice,$dmp);
  }
  
  ?><h2>Ajuster prix:</h2>
  <form method="post" action="products.php"><table>
<?php
  echo '<tr><td>Produit:</td><td>' . $showproductid . ': ' . $productname . '</td><td>&nbsp &nbsp;</td><td>' . round($taxcode) . '% TVA</td></tr>';
  echo '<tr><td>Prix de vente HT (' . $unittypename . '):</td><td><input type="text" STYLE="text-align:right" name="salesprice" size=10 value="' . d_input($currentprice,'decimal') . '"></td><td>&nbsp; &nbsp;</td><td>' . ($currentprice + ($currentprice*$taxcode/100)) . ' TTC</td></tr>';
  if ($_SESSION['ds_term_prixalternatif'] != '')
  {
    echo '<tr><td>'.$_SESSION['ds_term_prixalternatif'].' HT (' . $unittypename . '):</td><td><input type="text" STYLE="text-align:right" name="detailsalesprice" size=10 value="' . d_input($detailsalesprice,'decimal') . '"></td><td>&nbsp; &nbsp;</td><td>' . ($detailsalesprice + ($detailsalesprice*$taxcode/100)) . ' TTC</td></tr>';
  }
  if ($producttypename == 'PGL')
  {
    echo '<tr><td>Prix de vente <i>PGL</i> (' . $unittypename . '):</td><td><input type="text" STYLE="text-align:right" name="islandregulatedprice" size=10 value="' . d_input($islandregulatedprice,'decimal') . '"></td><td>&nbsp; &nbsp;</td><td>' . ($islandregulatedprice + ($islandregulatedprice*$taxcode/100)) . ' TTC</td></tr>';
  }
  if ($_SESSION['ds_useretailprice'])
  {
    #retailprice
    echo '<tr><td>Prix réglementé (' . $unittypename . '):
    <td><input type="text" STYLE="text-align:right" name="retailprice" size=10 value="' . d_input($retailprice,'decimal') . '">';
    echo '<td>&nbsp; &nbsp;</td><td>' . ($retailprice + ($retailprice*$taxcode/100)) . ' TTC';
  }
  if ($_SESSION['ds_useunits'] && $numberperunit > 1)
  {
    echo '<tr><td>Prix de vente (unité):</td><td><input type="text" STYLE="text-align:right" name="unitsalesprice" size=10 value="' . d_input($unitsalesprice,'decimal') . '"></td><td>&nbsp; &nbsp;</td><td>' . ($unitsalesprice + ($unitsalesprice*$taxcode/100)) . ' TTC</td></tr>';
    echo '<tr><td>'.$_SESSION['ds_term_prixalternatif'].' (unité):</td><td><input type="text" STYLE="text-align:right" name="unitdetailsalesprice" size=10 value="' . d_input($unitdetailsalesprice,'decimal') . '"></td><td>&nbsp; &nbsp;</td><td>' . ($unitdetailsalesprice + ($unitdetailsalesprice*$taxcode/100)) . ' TTC</td></tr>';
  }
?>
  <tr><td><br></td></tr>
  <tr><td>Prix de vente TTC souhaité (<?php echo $unittypename; ?>):</td><td><input type="text" STYLE="text-align:right" name="salespricettc" size=10></td></tr>
  <?php
  if ($_SESSION['ds_term_prixalternatif'] != '')
  {
    ?><tr><td><?php echo $_SESSION['ds_term_prixalternatif']; ?> TTC souhaité (<?php echo $unittypename; ?>):</td><td><input id="myfocus" type="text" STYLE="text-align:right" name="detailsalespricettc" size=10></td></tr>
    <?php
  }
if ($_SESSION['ds_useunits'] && $numberperunit > 1)
{
?>
  <tr><td>Prix de vente (unité) TTC souhaité:</td><td><input type="text" STYLE="text-align:right" name="unitsalespricettc" size=10></td></tr>
  <tr><td><?php $_SESSION['ds_term_prixalternatif']; ?> (unité) TTC souhaité:</td><td><input type="text" STYLE="text-align:right" name="unitdetailsalespricettc" size=10></td></tr>
<?php
}
  echo '<tr><td colspan="2" align="center"><input type=hidden name="saveme" value="1"><input type=hidden name="productsmenu" value="' . $productsmenu . '"><input type=hidden name="product" value="' . $realproductid . '"><input type="submit" value="Valider"></td></tr>';
  echo '</table></form><br>';
  
  # COPY from modifystock
  # needs $stock
  $stock = $currentstock;
  # list of all purchasebatches
  $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby,purchaseid';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
  $query = $query . ' from purchasebatch,usertable';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
  $query = $query . ' where purchasebatch.userid=usertable.userid and purchasebatch.deleted=0';
  if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
  $query = $query . ' and productid="' . $productid . '"';
  $query = $query . ' order by ';
  #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } TODO
  $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
  $query_prm = array();
  require('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results; unset($query_result, $num_results);
  if ($num_results_main <= 0)
  {
    $query = 'insert into purchasebatch (productid,arrivaldate,origamount,amount,cost,totalcost,vat,userid,useby,placementid)';
    $query = $query . ' values ("' . $productid . '",curdate(),0,0,0,0,0,"' . $_SESSION['ds_userid'] . '",curdate(),1)';
    $query_prm = array();
    require('inc/doquery.php');
    $query = 'select prev,shipmentid,batchname,supplierbatchname,initials,purchasebatch.userid,purchasebatchid,arrivaldate,origamount,amount,totalcost,vat,useby';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementname,warehousename'; }
    $query = $query . ' from purchasebatch,usertable';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ',placement,warehouse'; }
    $query = $query . ' where purchasebatch.userid=usertable.userid';
    if ($_SESSION['ds_useemplacement']) { $query = $query . ' and purchasebatch.placementid=placement.placementid and placement.warehouseid=warehouse.warehouseid'; }
    $query = $query . ' and productid="' . $productid . '"';
    $query = $query . ' order by ';
    #if ($_SESSION['ds_useemplacement']) { $query = $query . 'placementrank asc,'; } # TODO
    $query = $query . 'arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc'; #echo $query;
    $query_prm = array();
    require('inc/doquery.php');
  }
  echo '<br><table class="report"><tr><td><b>ID</b><td><b>Dossier</b></td><td><b>Conteneur<td><b>Batch</b></td>';
  if ($_SESSION['ds_usesofix'] == 1) { echo '<td><b>Pallet ID fourn.'; }
  echo '<td><b>Arrivé le</b></td><td><b>Utilisateur</b></td><td><b>Procuré</b></td><td><b>Taille</b></td><td><b>En stock</b></td><td><b>Prix Rev</b></td>';
  if ($_SESSION['ds_useretailprice']) { echo '<td><b>Prix Rev + Marge'; }
  if ($_SESSION['ds_usedlv']) { echo '<td><b>DLV</b></td>'; }
  if ($_SESSION['ds_useemplacement']) { echo '<td><b>Emplacement</b></td></tr>'; }
  $showemptylots = 100;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $row = $main_result[$i];
    $spb = '';
    if ($_SESSION['ds_usesofix'] == 1 && $row['purchaseid']>0)
    {
      $query = 'select supplier_pallet_barcode from purchase where purchaseid=?';
      $query_prm = array($row['purchaseid']);
      require('inc/doquery.php');
      if ($num_results)
      {
        $spb = $query_result[0]['supplier_pallet_barcode'];
      }
    }
    if ($showemptylots > -1)
    {
      $shipmentid = $row['shipmentid']; if ($shipmentid < 1) { $shipmentid = '&nbsp;'; }
      $lotsize = $row['amount'];
      $showlotsize = floor($lotsize/$numberperunit); $showlotsizerest = $lotsize%$numberperunit;
      $showlotorigsize = floor($row['origamount']/$numberperunit); $showlotorigsizerest = $lotsize%$numberperunit;
      if ($_SESSION['ds_useunits'] && $showlotsizerest)
      {
        $showlotsize = $showlotsize . ' <font size=-1>' . $showlotsizerest . '</font>';
        $showlotorigsize = $showlotorigsize . ' <font size=-1>' . $showlotorigsizerest . '</font>';
      }
      $stock = $stock - $lotsize;
      $amountleft = $lotsize;
      if ($stock < 0) { $amountleft = $amountleft + $stock; }
      if ($amountleft < 0) { $amountleft = 0; }
      $showamountleft = floor(($amountleft/$numberperunit)/$dmp); $showamountleftrest = $amountleft%$numberperunit;
      if ($_SESSION['ds_useunits'] && $showamountleftrest) { $showamountleft = $showamountleft . ' <font size=-1>' . $showamountleftrest . '</font>'; }
      if ($stock <= 0) { $showemptylots--; }
      $prev = $row['prev']+0;
      ### backwards compat
      if ($prev == 0)
      {
        if ($row['origamount'] == 0) { $prev = 0; }
        else { $prev = (($row['totalcost']-$row['vat'])*$numberperunit)/$row['origamount']; }
      }
      ###
      echo '<tr><td align=right>' . $row['purchasebatchid'] . '</td><td align=right>' . $shipmentid . '</td><td align=right>' . d_output($row['batchname']) . '</td>';
      echo '<td align=right>' . d_output($row['supplierbatchname']) . '</td>'; if ($_SESSION['ds_usesofix'] == 1) { echo '<td>'.$spb; }
      echo '<td>' . datefix($row['arrivaldate']) . '
      <td>' . $row['initials'] . '
      <td align=right>' . $showlotorigsize/$dmp . '
      <td align=right>' . $showlotsize/$dmp . '
      <td align=right>' . $showamountleft . '
      <td align=right>' . $prev;
      if ($_SESSION['ds_useretailprice'])
      {
        if ($margintype == 1) { echo '<td align=right>' . ($prev+$margin); }
        elseif ($margintype == 0) { echo '<td align=right>' . ($prev+($prev*$margin/100)); }
        else { echo '<td>'; }
      }
      if ($_SESSION['ds_usedlv']) { echo '<td>' . datefix($row['useby']) . '</td>'; }
      if ($_SESSION['ds_useemplacement']) { echo '<td>' . $row['placementname'] . ' (' . $row['warehousename'] . ')</td>'; }
    }
  }
  echo '</table>';
}
?>