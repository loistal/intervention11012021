<?php

$PA['productdepartmentid'] = 'int';
require('inc/readpost.php');

require('preload/productdepartment.php');
require('preload/productfamilygroup.php');
require('preload/producttype.php');
require('d3/temgraph.php');

$PA['clientcategoryid'] = 'int';
$PA['clientcategory2id'] = 'int';
$PA['clientcategory3id'] = 'int';
$PA['islandid'] = 'int';
require('inc/readpost.php');

$resultA = array();
$rresultA = array();
$year = $_POST['year']+0;
$lastyear = $year - 1;
$employeeid = $_POST['employeeid']+0;
$supplierid = (int) $_POST['supplierid'];
$exsupplierid = $_POST['exsupplierid']+0;
$orderby = (int) $_POST['orderby'];
$productid = (int) $_POST['productid'];
$mytype = $_POST['mytype']+0;
$showreturns = (int) $_POST['showreturns'];
$showthis = (int) $_POST['showthis'];
$showlast = (int) $_POST['showlast'];
$showpercent = (int) $_POST['showpercent'];
$unit = (int) $_POST['unit'];
$comrateid2 = (int) $_POST['comrateid2'];

$client = $_POST['client'];
if (!isset($client)) { $client = $_GET['client']; }
require ('inc/findclient.php');

$titlename = 'Ventes ' . $year . ' vs. ' . $lastyear;
showtitle($titlename);
echo '<h2>' . $titlename . '</h2>';

if ($unit == 3) { echo '<p>En cartons</p>'; }
elseif ($unit == 2) { echo '<p>En poids brut (KG)</p>'; }
elseif ($unit == 4) { echo '<p>En volume</p>'; }
else { echo '<p>En valeur HT</p>'; }
if ($employeeid > 0)
{
  require('preload/employee.php');
  echo '<p>Employé: ' . d_output($employeeA[$employeeid]) . '</p>';
}
if ($clientid > 0)
{
  echo '<p>Client: ' . $clientid . ': ' . $clientname . '</p>';
}
if ($supplierid > 0)
{
  $query = 'select clientid,clientname from client where clientid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $suppliername = $row['clientid'] . ': ' . d_output(d_decode($row['clientname']));
  echo '<p>Fournisseur: ' . $suppliername;
  if ($exsupplierid == 1) { echo ' exclu'; }
  echo '</p>';
}
if ($productid > 0)
{
  $query = 'select productname,netweightlabel,numberperunit from product where productid=?';
  $query_prm = array($supplierid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $productname = $productid . ' ' . d_output(d_decode($row['productname']));
  echo '<p>Produit: ' . $productname . '</p>';
}
if ($clientcategoryid > 0)
{
  require('preload/clientcategory.php');
  echo '<p>'.$_SESSION['ds_term_clientcategory'].': ' . d_output($clientcategoryA[$clientcategoryid]) . '</p>';
}
if ($clientcategory2id > 0)
{
  require('preload/clientcategory2.php');
  echo '<p>'.$_SESSION['ds_term_clientcategory2'].': ' . d_output($clientcategory2A[$clientcategory2id]) . '</p>';
}
if ($clientcategory3id > 0)
{
  require('preload/clientcategory3.php');
  echo '<p>'.$_SESSION['ds_term_clientcategory3'].': ' . d_output($clientcategory3A[$clientcategory3id]) . '</p>';
}
if ($islandid > 0)
{
  require('preload/island.php');
  echo '<p>Île : ' . d_output($islandA[$islandid]) . '</p>';
}


$tableheader = '<td><b>Jan</td><td><b>Fév</td><td><b>Mars</td><td><b>Avr</td><td><b>Mai</td><td><b>Juin</td><td><b>Juil</td><td><b>Août</td><td><b>Sept</td><td><b>Oct</td><td><b>Nov</td><td><b>Déc</td><td><b>Total</td>';

echo '<table class=report>';
echo '<tr><td>&nbsp;</td>' . $tableheader . '</tr>';

$query_prm = array();
if ($unit == 3) { $query = 'select sum(quantity/numberperunit) as value,'; }
elseif ($unit == 2) { $query = 'select sum(((quantity/numberperunit)*weight)/1000) as value,'; }
elseif ($unit == 4) { $query = 'select sum(((quantity/numberperunit)*volume)/1000) as value,'; }
else { $query = 'select sum(lineprice) as value,'; }
$query .= 'invoiceitemhistory.productid,productname,month(accountingdate) as month,year(accountingdate) as year,
productfamilygroupname,productfamily.productfamilygroupid,product.producttypeid,productfamilygroup.productdepartmentid
from invoicehistory,invoiceitemhistory,product,productfamily,productfamilygroup,productdepartment
where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and invoiceitemhistory.productid=product.productid
and product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
and isreturn=0 and confirmed=1 and cancelledid=0 and (year(accountingdate)="' . $year . '" or year(accountingdate)="' . $lastyear . '")';
if ($supplierid > 0)
{
  if ($exsupplierid == 1) { $query = $query . ' and product.supplierid<>"' . $supplierid . '"'; }
  else { $query = $query . ' and product.supplierid="' . $supplierid . '"'; }
}
if ($productdepartmentid > 0) { $query .= ' and productfamilygroup.productdepartmentid=?'; $query_prm[] = $productdepartmentid; }
if ($employeeid != -1) { $query = $query . ' and invoicehistory.employeeid="' . $employeeid . '"'; }
if ($clientid > 0) { $query = $query . ' and invoicehistory.clientid="' . $clientid . '"'; }
if ($productid > 0) { $query = $query . ' and invoiceitemhistory.productid="' . $productid . '"'; }
if ($comrateid2) { $query .= ' and product.commissionrateid>1'; }
if ($clientcategoryid >= 0)
{
  $query .= ' and invoicehistory.clientid in (select clientid from client where clientcategoryid=?)';
  array_push($query_prm, $clientcategoryid);
}
if ($clientcategory2id >= 0)
{
  $query .= ' and invoicehistory.clientid in (select clientid from client where clientcategory2id=?)';
  array_push($query_prm, $clientcategory2id);
}
if ($clientcategory3id >= 0)
{
  $query .= ' and invoicehistory.clientid in (select clientid from client where clientcategory3id=?)';
  array_push($query_prm, $clientcategory3id);
}
if ($islandid >= 0)
{
  $query .= ' and invoicehistory.clientid in (
  select clientid
  from client,town
  where client.townid=town.townid
  and town.islandid=?
  )';
  array_push($query_prm, $islandid);
}

$query = $query . ' group by ';
if ($orderby == 1) { $query = $query . 'productfamilygroupid'; }
elseif ($orderby == 2) { $query = $query . 'producttypeid'; }
elseif ($orderby == 3) { $query = $query . 'productid'; }
elseif ($orderby == 0) { $query = $query . 'productdepartmentid'; }
$query = $query . ',year,month';

$query = $query . ' order by ';
if ($orderby == 1 || $orderby == 0) { $query = $query . 'departmentrank,productdepartmentname,familygrouprank,productfamilygroupname'; }
elseif ($orderby == 2) { $query = $query . 'producttypeid'; }
elseif ($orderby == 3) { $query = $query . 'departmentrank,productdepartmentname'; }
$query = $query . ',year,month';

require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($orderby == 1) { $id = $row['productfamilygroupid']; }
  elseif ($orderby == 2) { $id = $row['producttypeid']; }
  elseif ($orderby == 3) { $id = $row['productid']; }
  elseif ($orderby == 0) { $id = $row['productdepartmentid']; }
  $resultmonth = $row['month'];
  $resultyear = $row['year'];
  $resultA[$id][$resultyear][$resultmonth] = $row['value'];
}

#returns
$query = str_replace('isreturn=0', 'isreturn=1', $query);
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  $row = $query_result[$i];
  if ($orderby == 1) { $id = $row['productfamilygroupid']; }
  elseif ($orderby == 2) { $id = $row['producttypeid']; }
  elseif ($orderby == 3) { $id = $row['productid']; }
  elseif ($orderby == 0) { $id = $row['productdepartmentid']; }
  $resultmonth = $row['month'];
  $resultyear = $row['year'];
  if ($showreturns == 1) { $rresultA[$id][$resultyear][$resultmonth] = $row['value']; }
  else { $resultA[$id][$resultyear][$resultmonth] -= $row['value']; }
}

#for multiline graph
$graphxvaluesA = array();
for ($y=1; $y <= 12; $y++) 
{ 
  $monthtotal[$y] = 0; $monthtotal_ly[$y] = 0; $rmonthtotal[$y] = 0; $rmonthtotal_ly[$y] = 0; 
  #for multiline graph
  $graphxvaluesA[$y] = d_getfirstdayofmonth($y,$year);  
}
$grandtotal = 0; $grandtotal_ly = 0; $rgrandtotal = 0; $rgrandtotal_ly = 0; $rlinetotal = 0;

if ($orderby == 1) { $query = 'select productfamilygroupname,productfamilygroupid as id,productdepartmentname from productdepartment,productfamilygroup where productfamilygroup.productdepartmentid=productdepartment.productdepartmentid order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname'; }
elseif ($orderby == 2) { $query = 'select producttypename,producttypeid as id from producttype order by producttypename'; }
elseif ($orderby == 3)
{
  $query = 'select productdepartmentname,productfamilygroupname,productfamilyname,productname,productid as id
  from productdepartment,productfamilygroup,productfamily,product
  where product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
  and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid
  order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname';
}
elseif ($orderby == 0)
{
  $query = 'select productdepartmentid as id,productdepartmentname
  from productdepartment
  order by departmentrank,productdepartmentname';
}

$query_prm = array();
require('inc/doquery.php');
#for graph 
$graphnamesA = array();$graphdataA = array();$graphdatumA = array();$graphdatalastA = array();$graphdatumlastA = array();$igraph=0;
for ($i=0; $i < $num_results; $i++)
{
  $row = $query_result[$i];
  $id = $row['id'];
  $linetotal = 0; $linetotal_ly = 0;
  if ($orderby == 1) # familygroup
  {
    $idname = $productdepartmentA[$productfamilygroup_pdidA[$id]] . ' / ' . $productfamilygroupA[$id];
    $outputline = '<tr><td><b>' . $idname . '</td>';
  }
  elseif ($orderby == 2) # producttype
  {
    $idname = $producttypeA[$id];
    $outputline = '<tr><td><b>' . $idname . '</td>';
  }
  elseif ($orderby == 3) # productid
  {
    $idname = d_decode($row['productname']) . ' (' . $row['id'] .  ')';
    $outputline = '<tr><td><b>' . $idname . '</td>'; 
  }
  elseif ($orderby == 0) # productdepartment
  {
    $idname = $productdepartmentA[$id];
    $outputline = '<tr><td><b>' . $idname . '</td>';
  }

  for ($y=1; $y <= 12; $y++)
  {
    $outputline .= '<td align=right valign=top>';
    if ($showlast) 
    { 
      if (isset($resultA[$id][$lastyear][$y])) { $outputline .= myfix($resultA[$id][$lastyear][$y]); }
      #$graphdataA[$y] = $resultA[$id][$lastyear][$y] + 0;    
      #$graphdatalastA[$y] = $resultA[$id][$lastyear][$y] + 0;    
    }
    if ($showthis)
    {
      if ($showlast) { $outputline .= '<br>'; }
      if (isset($resultA[$id][$year][$y])) { $outputline .= myfix($resultA[$id][$year][$y]); }
      #$graphdataA[$y] = $resultA[$id][$year][$y] + 0;
    }
    if ($showpercent)
    {
      $progress = '';
      if (isset($resultA[$id][$year][$y]) && isset($resultA[$id][$lastyear][$y]) && $resultA[$id][$lastyear][$y] > 0)
      {
        if ($showthis || $showlast) { $progress = '<br>'; }
        $progress .= round(100*(($resultA[$id][$year][$y]/$resultA[$id][$lastyear][$y])-1)) . '%';
      } 
      $outputline .= $progress;
    }
    $outputline .= '</td>';
    if (isset($resultA[$id][$year][$y]))
    {
      $linetotal += $resultA[$id][$year][$y];
      $monthtotal[$y] += $resultA[$id][$year][$y];
    }
    if (isset($resultA[$id][$lastyear][$y]))
    {
      $linetotal_ly += $resultA[$id][$lastyear][$y];
      $monthtotal_ly[$y] += $resultA[$id][$lastyear][$y];
    }
  }

  $outputline .= '<td align=right valign=top>';
  if ($showlast) { $outputline .= myfix($linetotal_ly); }
  if ($showthis)
  {
    if ($showlast) { $outputline .= '<br>'; }
    $outputline .= myfix($linetotal);
  }
  if ($showpercent)
  {
    $progress = '';
    if ($linetotal_ly > 0)
    {
      if ($showthis || $showlast) { $progress = '<br>'; }
      $progress .= round(100*(($linetotal/$linetotal_ly)-1)) . '%';
    }
    $outputline .= $progress;
  }
  $outputline .= '</td></tr>';
  $grandtotal = $grandtotal + $linetotal;
  $grandtotal_ly = $grandtotal_ly + $linetotal_ly;
  if ($showreturns == 1)
  {
    $rlinetotal = 0; $rlinetotal_ly = 0;
    $routputline = '<tr><td> &nbsp; Avoirs</td>';
    for ($y=1; $y <= 12; $y++)
    {
      $routputline .= '<td align=right valign=top>';
      if ($showlast) { $routputline .= myfix($rresultA[$id][$lastyear][$y]); }
      if ($showthis)
      {
        if ($showlast) { $routputline .= '<br>'; }
        $routputline .= myfix($rresultA[$id][$year][$y]);
      }
      if ($showpercent)
      {
        $progress = '';
        if ($rresultA[$id][$lastyear][$y] > 0)
        {
          if ($showthis || $showlast) { $progress = '<br>'; }
          $progress .= round(100*(($rresultA[$id][$year][$y]/$rresultA[$id][$lastyear][$y])-1)) . '%';
        }
        $routputline .= $progress;
      }
      $routputline .= '</td>';
      $rlinetotal = $rlinetotal + $rresultA[$id][$year][$y];
      $rlinetotal_ly = $rlinetotal_ly + $rresultA[$id][$lastyear][$y];
      $rmonthtotal[$y] = $rmonthtotal[$y] + $rresultA[$id][$year][$y];
      $rmonthtotal_ly[$y] = $rmonthtotal_ly[$y] + $rresultA[$id][$lastyear][$y];
    }
    $routputline .= '<td align=right valign=top>';
    if ($showlast) { $routputline .= myfix($rlinetotal_ly); }
    if ($showthis)
    {
      if ($showlast) { $routputline .= '<br>'; }
      $routputline .= myfix($rlinetotal);
    }
    if ($showpercent)
    {
      $progress = '';
      if ($rlinetotal_ly > 0)
      {
        if ($showthis || $showlast) { $progress = '<br>'; }
        $progress .= round(100*(($rlinetotal/$rlinetotal_ly)-1)) . '%';
      }
      $routputline .= $progress;
    }
    $routputline .= '</td></tr>';
    
    $rgrandtotal = $rgrandtotal + $rlinetotal;
    $rgrandtotal_ly = $rgrandtotal_ly + $rlinetotal_ly;
  }
  if ($linetotal > 0 || $rlinetotal > 0)
  {
    echo $outputline;
    if (isset($routputline)) { echo $routputline; }
  }
}

$outputline = '<tr><td><b>Total</b></td>';
for ($y=1; $y <= 12; $y++)
{
  $outputline .= '<td align=right valign=top><b>';
  if ($showlast) { $outputline .= myfix($monthtotal_ly[$y]); }
  if ($showthis)
  {
    if ($showlast) { $outputline .= '<br>'; }
    $outputline .= myfix($monthtotal[$y]);
  }
  if ($showpercent)
  {
    $progress = '';
    if ($monthtotal_ly[$y] > 0)
    {
      if ($showthis || $showlast) { $progress = '<br>'; }
      $progress .= round(100*(($monthtotal[$y]/$monthtotal_ly[$y])-1)) . '%';
    }
    $outputline .= $progress;
  }
  $outputline .= '</td>';
}
$outputline .= '<td align=right valign=top><b>';
if ($showlast) { $outputline .= myfix($grandtotal_ly); }
if ($showthis)
{
  if ($showlast) { $outputline .= '<br>'; }
  $outputline .= myfix($grandtotal);
}
if ($showpercent)
{
  $progress = '';
  if ($grandtotal_ly > 0)
  {
    if ($showthis || $showlast) { $progress = '<br>'; }
    $progress .= round(100*(($grandtotal/$grandtotal_ly)-1)) . '%';
  }
  $outputline .= $progress;
}
$outputline .= '</td></tr>';
echo $outputline;

if ($showreturns == 1)
{
  $outputline = '<tr><td> &nbsp; Avoir total</b></td>';
  for ($y=1; $y <= 12; $y++)
  {
    $outputline .= '<td align=right valign=top><b>';
    if ($showlast) { $outputline .= myfix($rmonthtotal_ly[$y]); }
    if ($showthis)
    {
      if ($showlast) { $outputline .= '<br>'; }
      $outputline .= myfix($rmonthtotal[$y]);
    }
    if ($showpercent)
    {
      $progress = '';
      if ($rmonthtotal_ly[$y] > 0)
      {
        if ($showthis || $showlast) { $progress = '<br>'; }
        $progress .= round(100*(($rmonthtotal[$y]/$rmonthtotal_ly[$y])-1)) . '%';
      }
      $outputline .= $progress;
    }
    $outputline .= '</td>';
  }
  $outputline .= '<td align=right valign=top><b>';
  if ($showlast) { $outputline .= myfix($rgrandtotal_ly); }
  if ($showthis)
  {
    if ($showlast) { $outputline .= '<br>'; }
    $outputline .= myfix($rgrandtotal);
  }
  if ($showpercent)
  {
    $progress = '';
    if ($rgrandtotal_ly > 0)
    {
      if ($showthis || $showlast) { $progress = '<br>'; }
      $progress .= round(100*(($rgrandtotal/$rgrandtotal_ly)-1)) . '%';
    }
    $outputline .= $progress;
  }
  $outputline .= '</td></tr>';
  echo $outputline;
  
  $outputline = '<tr><td><b>Total net</b></td>';
  for ($y=1; $y <= 12; $y++)
  {
    $outputline .= '<td align=right valign=top><b>';
    if ($showlast) { $outputline .= myfix($monthtotal_ly[$y]-$rmonthtotal_ly[$y]); }
    if ($showthis)
    {
      if ($showlast) { $outputline .= '<br>'; }
      $outputline .= myfix($monthtotal[$y]-$rmonthtotal[$y]);
    }
    if ($showpercent)
    {
      $progress = '';
      if ($monthtotal_ly[$y]-$rmonthtotal_ly[$y] > 0)
      {
        if ($showthis || $showlast) { $progress = '<br>'; }
        $progress .= round(100*((($monthtotal[$y]-$rmonthtotal[$y])/($monthtotal_ly[$y]-$rmonthtotal_ly[$y]))-1)) . '%';
      }
      $outputline .= $progress;
    }
    $outputline .= '</td>';
  }
  $outputline .= '<td align=right valign=top><b>';
  if ($showlast) { $outputline .= myfix($grandtotal_ly-$rgrandtotal_ly); }
  if ($showthis)
  {
    if ($showlast) { $outputline .= '<br>'; }
    $outputline .= myfix($grandtotal-$rgrandtotal);
  }
  if ($showpercent)
  {
    $progress = '';
    if ($grandtotal_ly-$rgrandtotal_ly > 0)
    {
      if ($showthis || $showlast) { $progress = '<br>'; }
      $progress .= round(100*((($grandtotal-$rgrandtotal)/($grandtotal_ly-$rgrandtotal_ly))-1)) . '%';
    }
    $outputline .= $progress;
  }
  $outputline .= '</td></tr>';
  echo $outputline;
}

echo '</table>';

?>