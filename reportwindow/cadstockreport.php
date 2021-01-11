<style> .emptyline{
  background-color: white;
  border: 0px;
} </style>

<?php

ini_set('max_execution_time', 3*600);

$PA['update_endofyearstock'] = 'int';
$PA['unittypeid'] = 'int';
$PA['year'] = 'int';
$PA['showvalue'] = '';
$PA['excludesupplier'] = '';
$PA['updatemonthlystock'] = 'int';
$PA['client'] = 'supplier';
$PA['product'] = 'product';
$PA['productdepartmentid'] = '';
$PA['productfamilygroupid'] = '';
$PA['productfamilyid'] = '';
$PA['temperatureid'] = '';
$PA['calcavg'] = 'uint';
require('inc/readpost.php');

$u_e = $update_endofyearstock;
$lastyear = $year - 1; $nextyear = $year + 1;
$ds_useunits = $_SESSION['ds_useunits']; if ($showvalue == 1) { $ds_useunits = 0; }
$ds_term_invoicenotice = $_SESSION['ds_term_invoicenotice'];
$thisonewasshown = 0; $productlistA = array();
$lastpdn = $lastpfgn = $lastpfn = $highestmonth = -1;
$testcartonspermonth = 0;
$t_cadenceofstock = d_trad('cadenceofstock',$year);
if($excludesupplier > 0) { $t_supplier = d_trad('excludedsupplierwithid:',$supplierid); }
else { $t_supplier = d_trad('supplierwithid:',$supplierid); }
$t_product = d_trad('product');
$t_total = d_trad('total');
$t_average = d_trad('average');
$t_sale = d_trad('sale');
$t_return = d_trad('return');
$t_between2 = 'Achat';
$t_adjust = d_trad('adjust');
$t_warehouse = d_trad('warehouse:');
$t_stock = d_trad('stock');
$t_productfamily = d_trad('productfamily:');
$t_temperature = d_trad('temperature:');
for($i=1;$i<=12;$i++)
{
  $t_shortmonth[$i] = d_trad('shortmonth' . $i);
}
session_write_close();

showtitle_new($t_cadenceofstock);

if ($u_e) { echo '<p>Updating endofyearstock....</p>'; }
if ($supplierid > 0)
{
  echo '<p>' . $t_supplier . '&nbsp;' . d_output($suppliername) . '</p>';
}
if($productfamilygroupid > 0 || $productdepartmentid > 0 || $productfamilyid > 0)
{
  require('preload/productdepartment.php');
  require('preload/productfamilygroup.php');
  require('preload/productfamily.php');
  echo '<p>' . $t_productfamily . '&nbsp;';
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
if ($temperatureid >= 0)
{
  require('preload/temperature.php');
  echo '<p>' . $t_temperature . '&nbsp;';
  if ($temperatureid == 0) { echo ' Non refrigéré'; }
  else { echo d_output($temperatureA[$temperatureid]); }
  echo '</p>';
}
echo '<br>';

$query = 'select p.currentstock,p.productid,p.numberperunit,p.netweightlabel,p.productname,pf.productfamilyname
,pg.productfamilygroupname,pd.productdepartmentname,u.unittypename,u.displaymultiplier as dmp,volume
from product p,productfamily pf,productfamilygroup pg,productdepartment pd,unittype u
where p.unittypeid=u.unittypeid and p.productfamilyid=pf.productfamilyid
and pf.productfamilygroupid=pg.productfamilygroupid and pg.productdepartmentid=pd.productdepartmentid
and p.discontinued=0';
$query_prm = array();

if ($productid > 0) { $query .= ' and p.productid=?'; array_push($query_prm,$productid); }
if ($supplierid  > 0) { if($excludesupplier){$query .= ' and p.supplierid!=?';}else{$query .= ' and p.supplierid=?';}array_push($query_prm,$supplierid); }
if ($productfamilyid  > 0) { $query .= ' and p.productfamilyid=?';array_push($query_prm,$productfamilyid); }
if ($productfamilygroupid  > 0) { $query .= ' and pf.productfamilygroupid=?';array_push($query_prm,$productfamilygroupid); }
if ($productdepartmentid  > 0) { $query .= ' and pg.productdepartmentid=?';array_push($query_prm,$productdepartmentid);}
if ($temperatureid >= 0) { $query .= ' and p.temperatureid=?';array_push($query_prm,$temperatureid);}
if ($unittypeid >= 0) { $query .= ' and p.unittypeid=?';array_push($query_prm,$unittypeid);}

$query .= ' order by pd.departmentrank,pg.familygrouprank,pf.familyrank';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

for ($i=0;$i < $num_results_main; $i++)
{
  $productlistA[$i] = $query_result[$i]['productid'];
}
$productlistA = array_filter(array_unique($productlistA));
sort($productlistA);
$productlist = '(';
foreach ($productlistA as $kladd)
{
  $productlist .= $kladd . ',';
}
$productlist = rtrim($productlist,',') . ')';
if ($productlist == '()') { exit; }

$query = 'select stock,productid from endofyearstock where productid in '.$productlist.' and year=?';
$query_prm = array($lastyear);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  $endofyearstockA[$query_result[$i]['productid']] = $query_result[$i]['stock'];
}

$query = 'select sum(quantity) as sales,month(accountingdate) as month,productid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and isreturn=0 and isnotice=0 and cancelledid=0 and productid in '.$productlist.'
and year(accountingdate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $salesA[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['sales'];
}

$query = 'select sum(quantity) as sales,month(accountingdate) as month,productid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and isreturn=0 and isnotice<>0 and cancelledid=0 and productid in '.$productlist.'
and year(accountingdate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $destockA[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['sales'];
}

$query = 'select sum(quantity) as loss,month(accountingdate) as month,productid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and isreturn=1 and returntostock=1 and isnotice=0 and cancelledid=0 and productid in '.$productlist.'
and year(accountingdate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $lossA[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['loss'];
}

$query = 'select sum(quantity) as loss,month(accountingdate) as month,productid
from invoicehistory,invoiceitemhistory
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and isreturn=1 and isnotice=1 and cancelledid=0 and productid in '.$productlist.'
and year(accountingdate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $loss2A[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['loss'];
}

if ($_SESSION['ds_unconfirmedcountsinstock'])
{
  # TODO from invoicetables x4
}

$query = 'select sum(netchange) as netchange,month(changedate) as month,productid
from modifiedstock
where productid in '.$productlist.' and year(changedate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $netchangeA[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['netchange'];
}

$query = 'select sum(origamount) as purchase,month(arrivaldate) as month,productid
from purchasebatch where deleted=0 and productid in '.$productlist.'
and year(arrivaldate)=?
group by productid,month';
$query_prm = array($year);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  if ($query_result[$i]['month'] > $highestmonth) { $highestmonth = $query_result[$i]['month']; }
  $purchaseA[$query_result[$i]['productid']][$query_result[$i]['month']] = $query_result[$i]['purchase'];
}

for ($y=0;$y < $num_results_main; $y++)
{
  $endyearstock = 0;
  $oversold = 0;
  $currentstock = 0;
  $sales = array();
  $highestmonth = 1;
  for ($i=1;$i <= 12; $i++)
  {
    $sales[$i] = 0;
    $destock[$i] = 0;
    $purchase[$i] = 0;
    $loss[$i] = 0;
    $loss2[$i] = 0;
    $netchange[$i] = 0;
    $salesunits[$i] = 0;
    $destockunits[$i] = 0;
    $purchaseunits[$i] = 0;
    $netchangeunits[$i] = 0;
    $lossunits[$i] = 0;
    $lossunits2[$i] = 0;
  }

  $rowproduct = $main_result[$y];
  $dmp = $rowproduct['dmp'];
  $volume = $rowproduct['volume'];
  $testcurrentstock = $rowproduct['currentstock'];
  if ($y != 0 && $thisonewasshown == 1) { echo '<tr rowspan=2 class="emptyline"><td colspan=18 >&nbsp;</td></tr>'; }
  
  $productid = $rowproduct['productid'];
  $numberperunit = $rowproduct['numberperunit']; if ($numberperunit == 0) { $numberperunit = 1; }
  $cond = $rowproduct['numberperunit'] . ' x ' . $rowproduct['netweightlabel'];
  $unittypename = $rowproduct['unittypename'];
  $productname = d_decode($rowproduct['productname']);
  $productfamilyname = $rowproduct['productfamilyname'];
  $productfamilygroupname = $rowproduct['productfamilygroupname'];
  $productdepartmentname = $rowproduct['productdepartmentname'];

  if (($productdepartmentname != $lastpdn || $productfamilygroupname != $lastpfgn || $productfamilyname != $lastpfn) && $y > 0) { echo '</table>'; }
  if ($y == 0 || $productdepartmentname != $lastpdn || $productfamilygroupname != $lastpfgn || $productfamilyname != $lastpfn) { echo '<table class="report">'; }

  $lastpdn = $productdepartmentname;
  $lastpfgn = $productfamilygroupname;
  $lastpfn = $productfamilyname;
  
  if (isset($endofyearstockA[$productid]))
  {
    $endyearstock = floor($endofyearstockA[$productid] / $numberperunit) / $dmp;
    $endyearstockunits = $endofyearstockA[$productid] % $numberperunit / $dmp;
  }
  else { $endyearstock = $endyearstockunits = 0; }
  
  if ($showvalue) # TODO optimize
  {
    $query = 'select prev from purchasebatch where productid=? order by arrivaldate desc LIMIT 1'; # using prev
    $query_prm = array($productid);
    require('inc/doquery.php');
    $rowpurchase = $query_result; $num_rowspurchase = $num_results; unset($query_result, $num_results);
    $cartonvalue = $rowpurchase[0]['prev'];
    $endyearstock = $endyearstock * $cartonvalue + (($endyearstockunits/$numberperunit) * $cartonvalue);
  }
  
  for ($i=1;$i <= 12; $i++)
  {
    if (isset($salesA[$productid][$i]))
    {
      $sales[$i] = $salesA[$productid][$i];
      $salesunits[$i] = $sales[$i] % $numberperunit;
      $sales[$i] = floor($sales[$i] / $numberperunit) / $dmp;
    }
    if (isset($destockA[$productid][$i]))
    {
      $destock[$i] += floor($destockA[$productid][$i] / $numberperunit) / $dmp;
      $testcartonspermonth += floor($destockA[$productid][$i] / $numberperunit);
      $destockunits[$i] += $destockA[$productid][$i] % $numberperunit;
    }
    if (isset($purchaseA[$productid][$i]))
    {
      $purchase[$i] = floor($purchaseA[$productid][$i] / $numberperunit) / $dmp;
      $purchaseunits[$i] = $purchaseA[$productid][$i] % $numberperunit;
    }
    if (isset($lossA[$productid][$i]))
    {
      $loss[$i] += floor($lossA[$productid][$i] / $numberperunit) / $dmp;
      $lossunits[$i] += $lossA[$productid][$i] % $numberperunit;
    }
    if (isset($loss2A[$productid][$i]))
    {
      $loss2[$i] += floor($loss2A[$productid][$i] / $numberperunit) / $dmp;
      $lossunits2[$i] += $loss2A[$productid][$i] % $numberperunit;
    }
    if (isset($netchangeA[$productid][$i]))
    {
      $netchange[$i] += floor($netchangeA[$productid][$i] / $numberperunit) / $dmp;
      $netchangeunits[$i] += $netchangeA[$productid][$i] % $numberperunit;
      if ($netchange[$i] < 0 && $netchangeunits[$i] <> 0) { $netchange[$i] += 1; } # hack for floor on negative numbers
    }
  }

  if ($showvalue)
  {
    for ($kladd=1;$kladd <= 12; $kladd++)
    {
      $sales[$kladd] = $sales[$kladd] * $cartonvalue;
      $destock[$kladd] = $destock[$kladd] * $cartonvalue;
      $purchase[$kladd] = $purchase[$kladd] * $cartonvalue;
      $loss[$kladd] = $loss[$kladd] * $cartonvalue;
      $loss2[$kladd] = $loss2[$kladd] * $cartonvalue;
      $netchange[$kladd] = $netchange[$kladd] * $cartonvalue;
    }
  }

  $testcartonspermonth = $testcartonspermonth / $highestmonth;
  $thisonewasshown = 1;
  
  echo '<thead><th>' . $t_product . '&nbsp' . $productid . '</th><th>' . $lastyear . '</th><th>&nbsp;</th>';
  for($i=1;$i<=12;$i++)
  {
    echo '<th>' . $t_shortmonth[$i] . '</th>';
  }
  echo '<th></th><th>' . $t_total . '</th><th>' . $t_average . '</th></thead>';
  $result = $endyearstock; $totalsales = 0; $totalloss = 0; $totalloss2 = 0; $totalpurchase = 0; $totaldestock = 0; $totalnetchange = 0; $counter = 0;
  $resultunits = $endyearstockunits;
  for ($i=1;$i <= 12; $i++)
  {
    if (!isset($loss2units[$i])) { $loss2units[$i] = 0; }
    if ($i == 1) { echo d_tr() . '<td valign=top>' . $productname . '<br>' . $cond . '<br><br>' . $productdepartmentname . '<br>' . $productfamilygroupname . '<br>' . $productfamilyname . '</td><td align=right>&nbsp;</td><td>' . $t_sale . '<br>&nbsp;' . $t_return .'<br>' . $ds_term_invoicenotice .'<br>&nbsp;' . $t_return . '<br>' . $t_between2 . '<br>' . $t_adjust . '</td>'; }
    echo '<td align=right>&nbsp;' . myfix($sales[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($salesunits[$i]) . '</font>'; }
    echo '<br>&nbsp;' . myfix($loss[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($lossunits[$i]) . '</font>'; }
    echo '<br>&nbsp;' . myfix($destock[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($destockunits[$i]) . '</font>'; }
    echo '<br>&nbsp;' . myfix($loss2[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($loss2units[$i]) . '</font>'; }
    echo '<br>&nbsp;' . myfix($purchase[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($purchaseunits[$i]) . '</font>'; }
    echo '<br>&nbsp;' . myfix($netchange[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($netchangeunits[$i]) . '</font>'; }
    echo '</td>';
    $result = $result - $sales[$i] - $destock[$i] + $loss[$i] + $loss2[$i] + $purchase[$i] + $netchange[$i];
    $resultunits = $resultunits - $salesunits[$i] - $destockunits[$i] + $lossunits[$i] + $loss2units[$i] + $purchaseunits[$i] + $netchangeunits[$i];
    $kladdresult = ($result * $numberperunit) + $resultunits;
    $result = floor($kladdresult / $numberperunit);
    if($numberperunit > 1)
    {
      $resultunits = $kladdresult % $numberperunit; if ($result < 0 && $resultunits <> 0) { $result = $result + 1; }
    }
    $monthresult[$i] = $result;
    if ($showvalue)
    {
      $monthresult[$i] += ($resultunits/$numberperunit) * $cartonvalue;
    }
    $monthresultunits[$i] = $resultunits;
    $totalsales = $totalsales + ($sales[$i] * $numberperunit) + $salesunits[$i];
    $totaldestock = $totaldestock + ($destock[$i] * $numberperunit) + $destockunits[$i];
    $totalloss = $totalloss + ($loss[$i] * $numberperunit) + $lossunits[$i];
    $totalloss2 = $totalloss2 + ($loss2[$i] * $numberperunit) + $loss2units[$i];
    $totalpurchase = $totalpurchase + ($purchase[$i] * $numberperunit) + $purchaseunits[$i];
    $totalnetchange = $totalnetchange + ($netchange[$i] * $numberperunit) + $netchangeunits[$i];
    if ($sales[$i] > 0 || $destock[$i] > 0 || $loss[$i] > 0 || $purchase[$i] > 0) { $counter = $i; }
    if ($counter == 0) { $counter = 1; }
    if ($i == 12)
    {
      echo '<td>'. $t_sale .'<br>&nbsp;'. $t_return .'<br>'. $ds_term_invoicenotice .'<br>&nbsp;'. $t_return .'<br>'. $t_between2 .'<br>'. $t_adjust .'</td><td align=right><b>&nbsp;' . myfix($totalsales / $numberperunit);
      echo '<br>&nbsp;' . myfix($totalloss / $numberperunit);
      echo '<br>&nbsp;' . myfix($totaldestock / $numberperunit);
      echo '<br>&nbsp;' . myfix($totalloss2 / $numberperunit);
      echo '<br>&nbsp;' . myfix($totalpurchase / $numberperunit);
      echo '<br>&nbsp;' . myfix($totalnetchange / $numberperunit);
      echo '</b></td><td align=right><b>&nbsp;' . myfix($totalsales/$counter/$numberperunit);
      echo '<br>&nbsp;' . myfix($totalloss/$counter/$numberperunit);
      echo '<br>&nbsp;' . myfix($totaldestock/$counter/$numberperunit);
      echo '<br>&nbsp;' . myfix($totalloss2/$counter/$numberperunit);
      echo '<br>&nbsp;' . myfix($totalpurchase/$counter/$numberperunit);
      echo '<br>&nbsp;' . myfix($totalnetchange/$counter/$numberperunit);
      echo '</b></td></tr>';
      # update avgmonthly
      if ($calcavg == 1)
      {
        # 2020 07 27 new algo for Wing Chong, products in KG  NB! I think this algorithm drastically underestimates sales
        # TODO remove
        if ($dmp = 1000)
        {
          $grandaverage = 0;
          $query = 'select count(invoiceitemid) as value
          from invoicehistory,invoiceitemhistory
          where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and cancelledid=0 and confirmed=1 and isreturn=0
          and productid=? and year(accountingdate)=?';
          $query_prm = array($productid,$year);
          require('inc/doquery.php');
          if ($num_results) { $grandaverage += $query_result[0]['value']; }
          $query = 'select count(invoiceitemid) as value
          from invoicehistory,invoiceitemhistory
          where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and cancelledid=0 and confirmed=1 and isreturn=1
          and productid=? and year(accountingdate)=?';
          $query_prm = array($productid,$year);
          require('inc/doquery.php');
          if ($num_results) { $grandaverage -= $query_result[0]['value']; }
          if ($grandaverage > 0)
          {
            $grandaverage = $grandaverage / $counter;
            $grandaverage = $grandaverage * $volume;
          }
        }
        else { $grandaverage = myfix(($totalsales - $totalloss + $totaldestock - $totalloss2) / $counter,2); }
        $query = 'update product set avgmonthly=? where productid=?';
        $query_prm = array($grandaverage,$productid);
        require('inc/doquery.php');
      }
      
    }
  }
  echo '<tr class=trtablecolorsub><td><b>'. $t_warehouse .'&nbsp;' . myfix($monthresult[12]);
  if ($ds_useunits) { echo ' <font size=-2>' . myfix($monthresultunits[12]) . '</font>'; }
  echo '</b></td><td align=right><b>' . myfix($endyearstock);
  if ($ds_useunits) { echo ' <font size=-2>' . myfix($endyearstockunits) . '</font>'; }
  echo '</b></td><td><b>' . $t_stock . '</b></td>';

  for ($i=1;$i <= 12; $i++)
  {
    echo '<td align=right><b>' . myfix($monthresult[$i]);
    if ($ds_useunits) { echo ' <font size=-2>' . myfix($monthresultunits[$i]) . '</font>'; }
    echo '</b></td>';
    if ($u_e && $i == 12)
    {
      $query = 'select stock from endofyearstock where productid=? and year=?';
      $query_prm = array($productid, $year);
      require('inc/doquery.php');
      if ($num_results)
      {
        $kladd = $monthresult[$i] * $dmp;
        $query = 'update endofyearstock set stock=? where productid=? and year=?';
        $query_prm = array($kladd,$productid,$year);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into endofyearstock (stock,productid,year) values (?,?,?)';
        $query_prm = array($monthresult[$i],$productid,$year);
        require('inc/doquery.php');
      }
    }
    if ($updatemonthlystock)
    {
      $query = 'update monthlystock set stock=? where productid=? and year=? and month=?';
      $query_prm = array(($monthresult[$i]*$numberperunit)+$monthresultunits[$i],$productid,$year,$i);
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        $query = 'select monthlystockid from monthlystock where productid=? and year=? and month=?';
        $query_prm = array($productid,$year,$i);
        require('inc/doquery.php');
        if ($num_results == 0)
        {
          $query = 'insert into monthlystock (stock,productid,year,month) values (?,?,?,?)';
          $query_prm = array(($monthresult[$i]*$numberperunit)+$monthresultunits[$i],$productid,$year,$i);
          require('inc/doquery.php');
        }
      }
    }
  }
  $supertotal = 0;
  echo '<td><b>' . $t_stock . '</b></td><td>&nbsp;</td><td align=right>&nbsp;</td>';
  
  if ($u_e && $_SESSION['ds_stockperuser'])
  {
    echo '<tr><td colspan=20>';
    $currentyear = $year;
    $npu = $numberperunit;
    $query = 'select userid,username from usertable where deleted=0 and stockperthisuser=1 order by username';
    $query_prm = array();
    require('inc/doquery.php');
    $user_result = $query_result; $num_results_user = $num_results;
    for ($i=0; $i < $num_results_user; $i++)
    {
      # mandatory input: $productid $currentyear $npu $dp_userid
      $dp_userid = $user_result[$i]['userid'];
      require('inc/calcstock_user.php');
      echo ' ',$user_result[$i]['username'],' ',$userstock;
      $query = 'select stock from endofyearstock_user where productid=? and year=? and userid=?';
      $query_prm = array($productid, $year, $user_result[$i]['userid']);
      require('inc/doquery.php');
      if ($num_results)
      {
        $query = 'update endofyearstock_user set stock=? where productid=? and year=? and userid=?';
        $query_prm = array($userstock,$productid,$year,$user_result[$i]['userid']);
        require('inc/doquery.php');
      }
      else
      {
        $query = 'insert into endofyearstock_user (stock,productid,year,userid) values (?,?,?,?)';
        $query_prm = array($userstock,$productid,$year,$user_result[$i]['userid']);
        require('inc/doquery.php');
      }
    }
  }
}
echo '</table>';
  
?>