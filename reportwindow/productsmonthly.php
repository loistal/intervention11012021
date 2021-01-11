<?php

$startdate = d_builddate(1,$_POST['month'],$_POST['year']);
$stopdate = d_builddate(31,($_POST['month']),$_POST['year']);

if ($_POST['bytag'] == 1)
{
  $query = 'select invoicetagid,invoicetagname from invoicetag order by invoicetagname';
  $query_prm = array();
  require ('inc/doquery.php');
  $tags_result = $query_result; $num_results_tags = $num_results;
  for ($x=0;$x<$num_results_tags;$x++)
  {
    $tagstotal[$x] = 0;
  }
}

$query = 'select productid,productname,suppliercode,numberperunit,netweightlabel from product where discontinued=0 order by productname';
$query_prm = array();
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
for ($i=0;$i<$num_results_main;$i++)
{
  $productid = $main_result[$i]['productid'];
  $numberperunit = $main_result[$i]['numberperunit'];
  if ($_SESSION['ds_useproductcode'] == 1) { $productname = $main_result[$i]['suppliercode']; }
  else { $productname = $main_result[$i]['productid']; }
  $productname = $productname . ': ' . $main_result[$i]['productname'] . ' ';
  if ($_SESSION['ds_useunits'] && $row['numberperunit'] > 1) { $productname = $productname . $main_result[$i]['numberperunit'] . ' x '; }
  $productname = $productname . $main_result[$i]['netweightlabel'];
  $productnameA[$productid] = $productname;
  $linepriceA[$productid] = 0;
  
  # paid products
  $qpaidA[$productid] = 0;
  if ($_POST['mychoice'] == 1)
  {
    $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitem,invoice where invoiceitem.productid=product.productid and invoiceitem.invoiceid=invoice.invoiceid and accountingdate>= ? and accountingdate<= ?';
    $query = $query . ' and cancelledid=0 and lineprice>0 and invoiceitem.productid=?';
    if ($_POST['bytag'] == 1) { $query = $query . ' and invoicetagid=0'; }
    $query_prm = array($startdate,$stopdate,$productid);
    require ('inc/doquery.php');
    $product_result = $query_result; $num_results_product = $num_results;
    $qpaidA[$productid] = $qpaidA[$productid] + $product_result[0]['quantity']/$numberperunit;
    $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
  }
  $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitemhistory,invoicehistory where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>= ? and accountingdate<= ?';
  $query = $query . ' and cancelledid=0 and lineprice>0 and confirmed=1 and invoiceitemhistory.productid=?';
  if ($_POST['bytag'] == 1) { $query = $query . ' and invoicetagid=0'; }
  $query_prm = array($startdate,$stopdate,$productid);
  require ('inc/doquery.php');
  $product_result = $query_result; $num_results_product = $num_results;
  $qpaidA[$productid] = $qpaidA[$productid] + $product_result[0]['quantity']/$numberperunit;
  $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
  
  # unpaid products (copy from above)
  $qunpaidA[$productid] = 0;
  if ($_POST['mychoice'] == 1)
  {
    $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitem,invoice where invoiceitem.productid=product.productid and invoiceitem.invoiceid=invoice.invoiceid and accountingdate>= ? and accountingdate<= ?';
    $query = $query . ' and cancelledid=0 and lineprice=0 and invoiceitem.productid=?';
    if ($_POST['bytag'] == 1) { $query = $query . ' and invoicetagid=0'; }
    $query_prm = array($startdate,$stopdate,$productid);
    require ('inc/doquery.php');
    $product_result = $query_result; $num_results_product = $num_results;
    $qunpaidA[$productid] = $qunpaidA[$productid] + $product_result[0]['quantity']/$numberperunit;
    $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
  }
  $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitemhistory,invoicehistory where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>= ? and accountingdate<= ?';
  $query = $query . ' and cancelledid=0 and lineprice=0 and confirmed=1 and invoiceitemhistory.productid=?';
  if ($_POST['bytag'] == 1) { $query = $query . ' and invoicetagid=0'; }
  $query_prm = array($startdate,$stopdate,$productid);
  require ('inc/doquery.php');
  $product_result = $query_result; $num_results_product = $num_results;
  $qunpaidA[$productid] = $qunpaidA[$productid] + $product_result[0]['quantity']/$numberperunit;
  $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
  
  # tagged products
  if ($_POST['bytag'] == 1)
  {
    for ($x=0;$x<$num_results_tags;$x++)
    {
#echo 'product ' . $productid . ' looking for tag ' . $tags_result[$x]['invoicetagname'] . '<br>';
      $qtagsA[$x][$productid] = 0;
      if ($_POST['mychoice'] == 1)
      {
        $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitem,invoice where invoiceitem.productid=product.productid and invoiceitem.invoiceid=invoice.invoiceid and accountingdate>= ? and accountingdate<= ?';
        $query = $query . ' and cancelledid=0 and invoiceitem.productid=?';
        $query = $query . ' and invoicetagid=?';
        $query_prm = array($startdate,$stopdate,$productid,$tags_result[$x]['invoicetagid']);
        require ('inc/doquery.php');
        $product_result = $query_result; $num_results_product = $num_results;
        $qtagsA[$x][$productid] = $qtagsA[$x][$productid] + $product_result[0]['quantity']/$numberperunit;
        $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
      }
      $query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice from product,invoiceitemhistory,invoicehistory where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>= ? and accountingdate<= ?';
      $query = $query . ' and cancelledid=0 and confirmed=1 and invoiceitemhistory.productid=?';
      $query = $query . ' and invoicetagid=?';
      $query_prm = array($startdate,$stopdate,$productid,$tags_result[$x]['invoicetagid']);
      require ('inc/doquery.php');
      $product_result = $query_result; $num_results_product = $num_results;
      $qtagsA[$x][$productid] = $qtagsA[$x][$productid] + $product_result[0]['quantity']/$numberperunit;
      $linepriceA[$productid] = $linepriceA[$productid] + $product_result[0]['lineprice'];
    }
  }
}

$totalpaid = 0; $totalunpaid = 0; $totalprice = 0;
$ourtitle = 'Produits vendus ' . $_POST['month'] . '/' . $_POST['year'];
showtitle($ourtitle);
echo '<h2>' . $ourtitle . '</h2>';
echo '<table class="report" border=1 cellspacing=1 cellpadding=1><tr><td><b>Produit</b></td><td><b>Payants</b></td><td><b>Gratuits</b></td>';
for ($x=0;$x<$num_results_tags;$x++)
{
  echo '<td><b>' . $tags_result[$x]['invoicetagname'] . '</b></td>';
}
echo '<td><b>Prix total</b></td></tr>';
foreach($productnameA as $productid => $productname)
{
  echo '<tr><td>' . $productname . '</td><td align=right>' . myfix($qpaidA[$productid]) . '</td><td align=right>' . myfix($qunpaidA[$productid]) . '</td>';
  for ($x=0;$x<$num_results_tags;$x++)
  {
    echo '<td>' . $qtagsA[$x][$productid] . '</td>';
    $tagstotal[$x] = $tagstotal[$x] + $qtagsA[$x][$productid];
  }
  echo '<td align=right>' . myfix($linepriceA[$productid]) . '</td></tr>';
  $totalpaid = $totalpaid + $qpaidA[$productid];
  $totalunpaid = $totalunpaid + $qunpaidA[$productid];
  $totalprice = $totalprice + $linepriceA[$productid];
}
echo '<tr><td><b>Total</td><td align=right><b>' . myfix($totalpaid) . '</td><td align=right><b>' . myfix($totalunpaid) . '</td>';
for ($x=0;$x<$num_results_tags;$x++)
{
  echo '<td><b>' . $tagstotal[$x] . '</td>';
}
echo '<td align=right><b>' . myfix($totalprice) . '</td></tr>';
echo '</table>';

if ($_POST['showgooglecharts'] == 1)
{

$chartcolor = str_replace("#", "", $_SESSION['ds_menucolor']);

### google pie chart top 5 quantity
echo '<br>';
$googlestring = '<img src="';
$googlestring = $googlestring . 'https://chart.googleapis.com/chart?chs=600x250&chd=t:';
# paid products (copy from above)
$query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice,product.productid,suppliercode,productname,netweightlabel,numberperunit from product,invoiceitem,invoice where invoiceitem.productid=product.productid and invoiceitem.invoiceid=invoice.invoiceid and accountingdate>= ? and accountingdate< ?';
$query = $query . ' and cancelledid=0 and lineprice>0';
if ($_POST['mychoice'] == 2) { $query = $query . ' and invoice.confirmed=1'; }
$query = $query . ' group by productid UNION ';
$query = $query . 'select sum(quantity) as quantity,sum(lineprice) as lineprice,product.productid,suppliercode,productname,netweightlabel,numberperunit from product,invoiceitemhistory,invoicehistory where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>= ? and accountingdate< ?';
$query = $query . ' and cancelledid=0 and lineprice>0';
if ($_POST['mychoice'] == 2) { $query = $query . ' and invoicehistory.confirmed=1'; }
$query = $query . ' group by productid order by quantity desc LIMIT 5';
$query_prm = array($startdate,$stopdate,$startdate,$stopdate);
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$total = 0;
for ($i=0;$i<$num_results_main;$i++)
{
  $total = $total + (($main_result[$i]['quantity']/$main_result[$i]['numberperunit'])+0);
}
for ($i=0;$i<$num_results_main;$i++)
{
  if ($i != 0) { $googlestring = $googlestring . ','; }
  $googlestring = $googlestring . round((($main_result[$i]['quantity']/$main_result[$i]['numberperunit'])+0)/$total,2);
}
$googlestring = $googlestring . '&cht=p3&chco=' . $chartcolor . '&chl=';
for ($i=0;$i<$num_results_main;$i++)
{
  if ($i != 0) { $googlestring = $googlestring . '|'; }
  $googlestring = $googlestring . $main_result[$i]['productname'];
}
$googlestring = $googlestring . '&chtt=Top 5 produits">';
echo $googlestring;

### google pie chart top 5 value
echo '<br><br>';
$googlestring = '<img src="';
$googlestring = $googlestring . 'https://chart.googleapis.com/chart?chs=600x250&chd=t:';
# paid products (copy from above)
$query = 'select sum(quantity) as quantity,sum(lineprice) as lineprice,product.productid,suppliercode,productname,netweightlabel,numberperunit from product,invoiceitem,invoice where invoiceitem.productid=product.productid and invoiceitem.invoiceid=invoice.invoiceid and accountingdate>= ? and accountingdate< ?';
$query = $query . ' and cancelledid=0 and lineprice>0';
if ($_POST['mychoice'] == 2) { $query = $query . ' and invoice.confirmed=1'; }
$query = $query . ' group by productid UNION ';
$query = $query . 'select sum(quantity) as quantity,sum(lineprice) as lineprice,product.productid,suppliercode,productname,netweightlabel,numberperunit from product,invoiceitemhistory,invoicehistory where invoiceitemhistory.productid=product.productid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid and accountingdate>= ? and accountingdate< ?';
$query = $query . ' and cancelledid=0 and lineprice>0';
if ($_POST['mychoice'] == 2) { $query = $query . ' and invoicehistory.confirmed=1'; }
$query = $query . ' group by productid order by lineprice desc LIMIT 5';
$query_prm = array($startdate,$stopdate,$startdate,$stopdate);
require ('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$total = 0;
for ($i=0;$i<$num_results_main;$i++)
{
  $total = $total + (($main_result[$i]['lineprice']/$main_result[$i]['numberperunit'])+0);
}
for ($i=0;$i<$num_results_main;$i++)
{
  if ($i != 0) { $googlestring = $googlestring . ','; }
  $googlestring = $googlestring . round((($main_result[$i]['lineprice']/$main_result[$i]['numberperunit'])+0)/$total,2);
}
$googlestring = $googlestring . '&cht=p3&chco=' . $chartcolor . '&chl=';
for ($i=0;$i<$num_results_main;$i++)
{
  if ($i != 0) { $googlestring = $googlestring . '|'; }
  $googlestring = $googlestring . $main_result[$i]['productname'];
}
$googlestring = $googlestring . '&chtt=Top 5 valeur">';
echo $googlestring;

}

?>