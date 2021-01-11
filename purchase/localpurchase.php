<style>
#comment {
  width: 350px;
}
</style>
<?php

# needs massive refactor

require('inc/autocomplete_product.php');

$purchaselines = (int) $_SESSION['ds_purchaselines'];
if ($purchaselines < 5) { $purchaselines = 5; }
if ($purchaselines > 1000) { $purchaselines = 1000; }

$PA['save'] = '';
$PA['description'] = '';
require('inc/readpost.php');

$grandtotal = 0;
$datename = 'purchasebatchgroupdate'; require('inc/datepickerresult.php');
if ($save == d_trad('validate')) { $currentstep = 2; }
elseif ($save == d_trad('update')) { $currentstep = 1; }
else { $currentstep = 0; }
$pbgid = -1; $readvalues = 0;
if (isset($_POST['localpurchasegroudpid']) && $_POST['localpurchasegroudpid'] > 0)
{
  $pbgid = (int) $_POST['localpurchasegroudpid'];
  $currentstep = 1;
  $readvalues = 1;
  $query = 'select prev,productid,amount,description,cost,arrivaldate from purchasebatch where purchasebatchgroupid=? order by purchasebatchid';
  $query_prm = array($pbgid);
  require('inc/doquery.php');
  $readvalues_result = $query_result;
  if ($num_results)
  {
    $description = $readvalues_result[0]['description'];
    $purchasebatchgroupdate = $readvalues_result[0]['arrivaldate'];
    if ($num_results > $_SESSION['ds_purchaselines'])
    {
      $purchaselines = $_SESSION['ds_purchaselines'] = $num_results;
    }
  }
  else { $pbgid = -1; $readvalues = 0; }
}
elseif (isset($_POST['pbgid']) && $_POST['pbgid'] > 0)
{
  $pbgid = (int) $_POST['pbgid'];
}

if ($pbgid > 0 && $currentstep == 2) # TODO should update by linenr instead of deleting
{
  $query = 'delete from purchasebatch where purchasebatchgroupid=?';
  $query_prm = array($pbgid);
  require('inc/doquery.php');
}

#preload placement data
if ($_SESSION['ds_useemplacement'])
{
  $query = 'select placementid,placementname,warehousename from placement,warehouse where placement.warehouseid=warehouse.warehouseid order by warehousename,placementname';
  $query_prm = array();
  require('inc/doquery.php');
  $num_placements = $num_results;$placementA = $query_result;unset($num_results,$query_result);
}

echo '<h2>' . d_trad('localpurchase:'); if ($currentstep == 2) { echo ' ', d_trad('saved'); }
echo '</h2>';
echo '<form method="post" action="purchase.php">';
echo '<table>';
echo '<tr><td>Date :<td>'; $datename = 'purchasebatchgroupdate'; require('inc/datepicker.php');
echo '<tr><td>' . d_trad('comment:') . '</td>';?>
<td><input id="myfocus" type="text" STYLE="text-align:right;width:610px" name="description" value="<?php echo $description;  ?>" size=20></td></tr></table>
<?php
if ($currentstep != 2) { echo '<div class="center"><input name="save" type="submit" value="' . d_trad('update') . '">  <input name="save" type="submit" value="' . d_trad('validate') . '"></div>'; }

echo '<table class=report><thead><td><td>';
if ($_SESSION['ds_useproductcode']) { echo d_trad('productcode'); }
else { echo d_trad('productnum'); }
echo '</td><td>' . d_trad('quantity') . '</td><td>' . d_trad('unitcostwithouttax') . '</td><td>' . d_trad('product');
if ($_SESSION['ds_usesofix']) { echo '<td>Code Fourn.'; }
echo '<td>' . d_trad('supplier') . '</td>';
if ($_SESSION['ds_usedlv']) { echo '<td>' . d_trad('SBD') . '</td>'; }
if ($_SESSION['ds_useemplacement']) { echo '<td>' . d_trad('place') . '</td>'; }
if ($_SESSION['ds_stockperuser'] && $pbgid <= 0) { echo '<td>Pour utilisateur</td>'; }
if ($currentstep == 2) { echo '<td>' . d_trad('saved') . '<td>Total HT<td>Prev'; }
echo '</thead>';
for ($i=0; $i < $purchaselines; $i++)
{
  $suppliername = '&nbsp;';
  if ($_POST['productid' . $i] != "")
  {
    $product = $_POST['productid' . $i];
    require('inc/findproduct.php');
    if ($productid > 0)
    {
      $query = 'select generic,discontinued,notforsale,productname,brand,supplierid,numberperunit,taxcode,netweightlabel,unittypename,displaymultiplier,suppliercode,suppliercode2,product.productid
      from product,taxcode,unittype
      where product.unittypeid=unittype.unittypeid and product.taxcodeid=taxcode.taxcodeid
      and productid=?';
      $query_prm = array($productid);
      require('inc/doquery.php');
      $row = $query_result[0];
      if ($row['supplierid'] > 0)
      {
        $query = 'select clientname as suppliername from client where clientid=?';
        $query_prm = array($row['supplierid']);
        require('inc/doquery.php');
        $suppliername = $query_result[0]['suppliername'];
      }
    }
    else { unset($row); }
  }
  
  $prodid = $_POST['productid' . $i]; if ($_SESSION['ds_useproductcode'] == 1) { $prodid = $row['suppliercode']; }
  $postamount = $_POST['amount' . $i];
  if ($readvalues)
  {
    $prodid = $readvalues_result[$i]['productid'];
    $postamount = $readvalues_result[$i]['amount']+0;
    $_POST['purchaseprice' . $i] = $readvalues_result[$i]['cost']+0;
  }
  
  $discont = '<font color=' . $_SESSION['ds_alertcolor'] . '>';
  if ($row['discontinued']) { $discont = $discont . d_trad('discontinued'); }
  if ($row['discontinued'] && $row['notforsale']) { $discont = $discont . " "; }
  if ($row['notforsale']) { $discont = $discont . d_trad('notforsale'); }
  $discont = $discont . "</font> ";
  if ($row['discontinued'] || $row['notforsale']) { $productname = $discont; }
  else { $productname = ""; }
  $productname = $productname . $row['productname'] . ' ';
  if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productname = $productname . $row['numberperunit'] . ' x '; }
  $productname = $productname . $row['netweightlabel'];
  
  $realproductid = $row['productid'];
  $utn = $row['unittypename']; $costtext = '';
  #if ($utn == 'KG' && $row['displaymultiplier'] == 1000) { $utn = '<b>GR</b>'; $costtext = '/KG'; } # TODO need a better way to do this   
  echo d_tr() .'<td>' . ($i+1) . '</td>';
  
  echo '<td><input type="text" STYLE="text-align:right" name="productid' . $i . '" id="product_autocomplete' . $i . '" autocomplete="off" value="' . $prodid . '" size=10>';
  
  echo '<td><input type="text" STYLE="text-align:right" name="amount' . $i . '" value="' . $postamount . '" size=5> '.$utn.'</td>
  <td><input type="text" STYLE="text-align:right" name="purchaseprice' . $i . '"';
  if (isset($_POST['purchaseprice' . $i])) { echo ' value="' . d_input($_POST['purchaseprice' . $i]) . '"'; }
  echo ' size=10> '.$costtext.'</td>
  <td>' . $productname;
  if ($_SESSION['ds_usesofix'])
  {
    echo '<td>';
    if (isset($row['suppliercode2'])) { echo $row['suppliercode2']; }
  }
  echo '<td>' . $suppliername . '</td>';
  if ($_SESSION['ds_usedlv'])
  {
    $datename = 'dlvdate'.$i;
    require('inc/datepickerresult.php');
    
    $datename = 'dlvdate'.$i;
    echo '<td>';
    require('inc/datepicker.php');
    /*
    echo '<td><select name="day' . $i . '">';
    for ($y=1; $y <= 31; $y++)
    { 
      if ($y == $_POST["day" . $i]) { echo '<option value="' . $y . '" SELECTED>' . $y . '</option>'; }
      else { echo '<option value="' . $y . '">' . $y . '</option>'; }
    }
    echo '</select><select name="month' . $i . '">';
    for ($y=1; $y <= 12; $y++)
    {
      if ($y == $_POST["month" . $i]) { echo '<option value="' . $y . '" SELECTED>' . $y . '</option>'; }
      else { echo '<option value="' . $y . '">' . $y . '</option>'; }
    }
    echo '</select><select name="year' . $i . '">';
    for ($y=$_SESSION['ds_startyear']; $y <= $_SESSION['ds_endyear']; $y++)
    {
      if ($y == $_POST["year" . $i]) { echo '<option value="' . $y . '" SELECTED>' . $y . '</option>'; }
      else { echo '<option value="' . $y . '">' . $y . '</option>'; }
    }
    echo '</td>';
    */
  }
  if ($_SESSION['ds_useemplacement'])
  {
    echo '<td><select name="placementid' . $i . '">';
    for ($y=0; $y < $num_placements; $y++)
    {
      $row2 = $placementA[$y];
      if ($row2['placementid'] == $_POST["placementid" . $i]) { echo '<option value="' . $row2['placementid'] . '" selected>' . $row2['placementname'] . ' (' . $row2['warehousename'] . ')</option>'; }
      else { echo '<option value="' . $row2['placementid'] . '">' . $row2['placementname'] . ' (' . $row2['warehousename'] . ')</option>'; }
    }
    echo '</select>';
  }
  if ($_SESSION['ds_stockperuser'] && $pbgid <= 0)
  {
    echo '<td><select name="userid' . $i . '"><option value=0></option>';
    $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
    $query_prm = array();
    require('inc/doquery.php');
    for ($x=0; $x < $num_results; $x++)
    {
      echo '<option value='.$query_result[$x]['userid'];
      if (!isset($_POST['userid' . $i]) && $_SESSION['ds_stockperthisuser']) { $_POST['userid' . $i] = $_SESSION['ds_userid']; }
      if ($_POST['userid' . $i] == $query_result[$x]['userid']) { echo ' selected'; }
      echo '>'.$query_result[$x]['username'].'</option>';
    }
    echo '</select>';
  }
  if ($currentstep != 0 || $pbgid > 0)
  {
    if (isset($row) && $row['productname'] != "" && $row['discontinued'] < 1)
    {

      $vatpercent = $row['taxcode'];
      $amount = (double) $_POST['amount' . $i] * $row['numberperunit'] * $row['displaymultiplier'];
      $purchaseprice = (double) $_POST['amount' . $i] * (double) $_POST['purchaseprice' . $i]; #/ $row['displaymultiplier']
      $vat = $purchaseprice * $vatpercent / 100;
      $totalprice = $purchaseprice + $vat;
      $vat = $totalprice - $purchaseprice;
      if ($amount > 0) { $pru = $purchaseprice / $amount; }
      else { $pru = 0; }
      $prev = $pru * $row['numberperunit'] * $row['displaymultiplier'];
      
      if ($currentstep == 2)
      {
        if ($pbgid < 1)
        {
          $query = 'insert into purchasebatchgroup (userid,purchasebatchgroupdate) values (?,?)';
          $query_prm = array($_SESSION['ds_userid'],$purchasebatchgroupdate);
          require('inc/doquery.php');
          $pbgid = $query_insert_id;
        }
        else
        {
          $query = 'update purchasebatchgroup set userid=?,purchasebatchgroupdate=? where purchasebatchgroupid=?';
          $query_prm = array($_SESSION['ds_userid'],$purchasebatchgroupdate,$pbgid);
          require('inc/doquery.php');
        }
        
        $query = 'insert into purchasebatch (purchasebatchgroupid,productid,arrivaldate,origamount,amount,prev,cost,totalcost,vat,userid,description';
        if ($_SESSION['ds_usedlv']) { $query = $query . ',useby'; }
        if ($_SESSION['ds_useemplacement']) { $query = $query . ',placementid'; }
        $query = $query . ') values (?,?,?,"' . $amount . '","' . $amount . '","' . $prev . '","' . $pru . '","' . myround($totalprice) . '","' . myround($vat) . '","' . $_SESSION['ds_userid'] . '","' . $description . '"';
        if ($_SESSION['ds_usedlv'])
        {
          #$useby = d_builddate($_POST['day' . $i],$_POST['month' . $i],$_POST['year' . $i]);
          $datename = 'dlvdate'.$i;
          $useby = $$datename;
          $query = $query . ',"' . $useby . '"';
        }
        if ($_SESSION['ds_useemplacement'])
        {
          $query = $query . ',"' . $_POST["placementid" . $i] . '"';
        }
        $query = $query . ')';
        $query_prm = array($pbgid, $realproductid, $purchasebatchgroupdate);
        require('inc/doquery.php');
        
        # 2016 01 04
        $query = 'update product set recent_prev=? where productid=?';
        $query_prm = array($prev,$realproductid);
        require('inc/doquery.php');
        
        if ($_SESSION['ds_continuousstock'] == 1)
        {
          # mandatory input: $productid $currentyear $numberperunit
          $productid = $realproductid;
          $currentyear = mb_substr($_SESSION['ds_curdate'],0,4)+0;
          $numberperunit = $row['numberperunit'];
          require('inc/calcstock.php');
        }
        
        if ($_SESSION['ds_stockperuser'] && $_POST['userid' . $i] > 0 && $amount > 0)
        {
          $query = 'insert into modifiedstock_user (productid,netchange,netvalue,changedate,changetime,userid,foruserid,modifiedstockcomment,modifiedstockreasonid) values (?,?,?,CURDATE(),CURTIME(),?,?,?,?)';
          $query_prm = array($realproductid, $amount, 0, $_SESSION['ds_userid'], $_POST['userid' . $i], $description, 0);
          require('inc/doquery.php');
        }
      }
      echo '<td>' . ($_POST['amount' . $i]) . ' ' . $row['unittypename']; #if ($amount != 1) { echo 's'; }
      echo '<td align=right>' . myfix($totalprice - $vat) . '<td align=right>' . myfix($prev) . '/'.$row['unittypename'];
      $grandtotal += $totalprice;
      $grandtotal -= $vat;
    }
    else { echo '<td><td><td>'; }
  }
  echo '</tr>';
  unset($row);
}
echo '<tr><td colspan=7><td><b>Total</b><td align=right>',myfix($grandtotal),'<td></table>';
if ($currentstep != 2) { echo '<div class="center"><input name="save" type="submit" value="' . d_trad('update') . '"> <input name="save" type="submit" value="' . d_trad('validate') . '"></div>'; }
echo '<input type=hidden name=step value=0><input type=hidden name=pbgid value=',$pbgid,'>';
?><input type=hidden name="purchasemenu" value="<?php echo $purchasemenu; ?>"></form>