<?php

$days = (int)$_POST['days'];
$excludesupplier = $_POST['excludesupplier'];
$num_results=0;$client = $_POST['client'];require('inc/findclient.php');$supplierid=$clientid;$suppliername=$clientname;$suppliernum_results=$num_results;
$product = $_POST['product']; require('inc/findproduct.php');
$productdepartmentid = $_POST['productdepartmentid'];
$productfamilygroupid = $_POST['productfamilygroupid'];
$productfamilyid = $_POST['productfamilyid'];
$temperatureid = $_POST['temperatureid'];
$ds_useunits = $_SESSION['ds_useunits'];
$currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
$t_sellbydateproducts = d_trad('sellbydateproducts',$days);
$t_noresult = d_trad('noresult');
$hidenodate = (int) $_POST['hidenodate'];

if ($supplierid > 0)
{
  if($excludesupplier > 0)
  {
    $t_supplier = d_trad('excludedsupplierwithid:',$supplierid);
  }
  else
  {
    $t_supplier = d_trad('supplierwithid:',$supplierid);
  }
}

$t_temperature = d_trad('temperature:');
$t_refrigerated = d_trad('refrigerated');
$t_frozen = d_trad('frozen');
$t_product = d_trad('product');
$t_packaging = d_trad('packaging');
$t_arrivaldate = d_trad('arrivaldate');
$t_stock = d_trad('stock');
$t_SBD = d_trad('SBD');
$t_wholesalepricewithouttax = d_trad('wholesalepricewithouttax');
$t_value = d_trad('value');
$t_productfamily = d_trad('productfamily:');

session_write_close();


echo '<title>' . $t_sellbydateproducts . '</title>';
echo '<h2>' . $t_sellbydateproducts . '</h2><br>';

if ($supplierid != "")
{
  $query = 'select clientname from client where clientid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  echo '<p>' . $t_supplier . '&nbsp;' . d_output(d_decode($query_result[0]['clientname'])) . '</p>';
}
if($productfamilygroupid > 0 || $productdepartmentid > 0 || $productfamilyid > 0)
{
  echo '<p>' . $t_productfamily . '&nbsp;';
  if(!isset($productdepartmentA)){require('preload/productdepartment.php');}
  if(!isset($productfamilygroupA)){require('preload/productfamilygroup.php');}  
  if(!isset($productfamilyA)){require('preload/productfamily.php');}  
  if ($productfamilyid > 0)
  {
    echo d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . ' / ' . $productfamilyA[$productfamilyid]);
  }
  else if($productfamilygroupid > 0)
  {
    echo d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamilygroupid]] . ' / ' . $productfamilygroupA[$productfamilygroupid] );
  }
  else if($productdepartmentid > 0)
  {
    echo d_output($productdepartmentA[$productdepartmentid]);      
  } 
  echo '</p>';
}

if ($temperatureid > 0)
{
  echo '<p>' . $t_temperature . '&nbsp;';
  switch($temperatureid)
  {
    case 1:
      echo $t_refrigerated;
      break;
    case 2:
      echo $t_frozen;
      break;
  }
  echo '</p>';
}

$query = 'select avgmonthly,avgmonthlyspec,p.salesprice,p.unittypeid,p.currentstock,p.currentstockrest,p.productid,p.productname,p.numberperunit,p.netweightlabel,pf.productfamilyname,pg.productfamilygroupname,pd.productdepartmentname,u.unittypename,u.displaymultiplier as dmp
from product p,productfamily pf,productfamilygroup pg,productdepartment pd,unittype u
where p.unittypeid=u.unittypeid and p.productfamilyid=pf.productfamilyid
and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid=pd.productdepartmentid
and p.discontinued=0';
$query_prm = array();

if ($product > 0) { $query .= ' and p.productid=?'; array_push($query_prm,$product); }
if ($supplierid  > 0) { if($excludesupplier){$query .= ' and p.supplierid<>?';}else{$query .= ' and p.supplierid=?';}array_push($query_prm,$supplierid); }
if ($productfamilyid  > 0) { $query .= ' and p.productfamilyid=?';array_push($query_prm,$productfamilyid); }
if ($productfamilygroupid  > 0) { $query .= ' and pf.productfamilygroupid=?';array_push($query_prm,$productfamilygroupid); }
if ($productdepartmentid  > 0) { $query .= ' and pg.productdepartmentid=?';array_push($query_prm,$productdepartmentid);}
if ($temperatureid >= 0) { $query .= ' and p.temperatureid=?';array_push($query_prm,$temperatureid);}

$query .= ' order by pd.departmentrank,pg.familygrouprank,pf.familyrank,productname';
require('inc/doquery.php');
$rowproduct = $query_result; $num_rows = $num_results; unset($query_result, $num_results);
$counter = 0;

for ($i=0;$i < $num_rows; $i++)
{
  $row = $rowproduct[$i];
  $dmp = $row['dmp'];
  $stock = ($row['currentstock'] * $row['numberperunit']) + $row['currentstockrest'];
  $query = 'select purchasebatchid,cost,prev,arrivaldate,amount,useby,to_days(CURDATE()) as currentdays,to_days(useby) as usebydays from purchasebatch where productid=?
  order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc';
  $query_prm = array($row['productid']);
  require('inc/doquery.php');
  $rowpurchase = $query_result; $num_results2 = $num_results; unset($query_result, $num_results);

  for ($y=0; $y < $num_results2; $y++)
  {
    if ($stock > 0)
    {
      $row2 = $rowpurchase[$y];
      $amount = $row2['amount'];
      $mydays = $row2['usebydays'] - $row2['currentdays'];
      $stock = $stock - $amount;
      $amountleft = $amount;
      if ($stock < 0) { $amountleft = $amountleft + $stock; }
      if ($amountleft < 0) { $amountleft = 0; }
      if ($y == $num_results2 && $stock > 0) { $amountleft = $amountleft + $stock; $stock = 0; }
      if ($mydays <= $days)
      { 
        # create array to be sorted
        $counter++;
        $descA[$counter] = d_decode($row['productname']) . ' (' . $row['productid'] . ')';
        $salespriceA[$counter] = myfix($row['salesprice'] * $dmp);
        $arrivaldateA[$counter] = datefix2($row2['arrivaldate']);
        $condA[$counter] = $row['netweightlabel'];
        if ($row['numberperunit'] > 1) { $condA[$counter] = $row['numberperunit'] . ' x ' . $row['netweightlabel']; }
        $stockA[$counter] = floor(($amountleft/$dmp)/$row['numberperunit']);
        $usebyA[$counter] = datefix($row2['useby']);
        if (is_null($row2['useby']) || $row2['useby'] == '0000-00-00') { $usebyA[$counter] = ''; }
        $prevA[$counter] = $row2['prev'];
        if ($prevA[$counter] == 0) { $prevA[$counter] = $row2['cost']*$row['numberperunit']; } # backwards compat
        $mydaysA[$counter] = $mydays;
        $orderA[$counter] = 0;
        if ($row['avgmonthlyspec'] > 0) { $monthsstockA[$counter] = round(($stockA[$counter] * $row['numberperunit']) / $row['avgmonthlyspec'],1); }
        else
        {
          if ($row['avgmonthly'] > 0) { $monthsstockA[$counter] = round(($stockA[$counter] * $row['numberperunit']) / $row['avgmonthly'],1); }
          else { $monthsstockA[$counter] = ''; }
        }
        
      }
    }
  }
}
$totalcount = $counter;
######### sort array ########
for ($y=1; $y <= $totalcount; $y++)
{
  $mydays = $days + 1;
  for ($i=1; $i <= $totalcount; $i++)
  {
    if ($orderA[$i] == 0 && $mydaysA[$i] < $mydays) { $currentindex = $i; $mydays = $mydaysA[$i]; }
  }
  $orderA[$currentindex] = $y; # mark as ordered
  $todisplay[$y] = $currentindex; # save order
}
############################
if(isset($todisplay))
{
  echo '<table class="report"><thead><th>' . $t_product . '</th><th>' . $t_packaging . '</th><th>' . $t_arrivaldate .'</th></th><th>' .$t_stock . '</th><th>Mois de stock</th><th>' . $t_SBD . '</th><th>' . $t_wholesalepricewithouttax . '</th><th>' . $t_value . '</th></th></thead>';
  for ($i=1; $i <= $totalcount; $i++)
  {
    $y = $todisplay[$i];
    if ($stockA[$y] > 0 && ($hidenodate == 0 || $usebyA[$y] != ''))
    {
      echo d_tr() .'<td>' . d_output($descA[$y]) . '</td><td>' . d_output($condA[$y]) . '</td><td>' . $arrivaldateA[$y] . '</td><td align=right>' . $stockA[$y] . '</td>
      <td align=right>' . $monthsstockA[$y] . '<td align=right>' . $usebyA[$y] . '</td><td align=right>' . $salespriceA[$y] . '</td><td align=right>' . myfix($prevA[$y]*$stockA[$y]) . '</td></tr>';
    }
  }
}
else
{
  echo '<p>' . $t_noresult . '</p>';
}
echo '</table>';

?>