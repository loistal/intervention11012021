<?php

require('reportwindow/outofstockreport_cf.php');
require('preload/product.php');
require('preload/productfamily.php');
require('preload/productfamilygroup.php');
require('preload/productdepartment.php');

function show_subtotal($row,$i,$fieldnum,$subtfield1,$subtfield1_descr,$subtfield1_count,$subtfield1_count_descr,$showsubtotal,$stockfield)
{
  $subtotal_todisplay = '';
  $usesubunits = false;if($_SESSION['ds_useunits']){$usesubunits = true;}

  if (isset($showsubtotal) && $row[$i][$subtfield1] != $row[$i+1][$subtfield1])
  {
    $showfield = $row[$i][$subtfield1_descr];
    $subtotal_todisplay .= '<tr class=trtablecolorsub>'; 
    
    $showline_temp = '';
    $iscolspan_temp = true;  
    $colspan_temp = 0; 
    
    for ($y=1;$y <= $fieldnum;$y++)
    {
      if ($y == 1)
      {
        $showline_temp .= '<td colspan=##cs## class=subtotal>';
        $showsubtotal_temp = d_trad('total') . '&nbsp;' . $showfield;
        if (isset($showsubtotal[$y]))
        {
          $showline_temp = mb_ereg_replace("##cs##", 1 , $showline_temp);     
          if($usesubunits && (($y == $stockfield)))
          {
            $showsubtotal_temp .= ': ' . $showsubtotal[$y];
          }
          else
          {
            $showsubtotal_temp .= ': ' . myfix($showsubtotal[$y], 0);        
          }
          //reinit subtotal
          $showsubtotal[$y] = 0;
          $iscolspan_temp = false;
        }

        $showline_temp .= $showsubtotal_temp;
        //optional: number of rows for subtotal and label
        if(isset($subtfield1_count) && isset($subtfield1_count_descr))
        {
          $showline_temp .= ':&nbsp;' . d_trad($subtfield1_count_descr,($subtfield1_count));
          $subtfield1_count = 0;
        }
        $showline_temp .='</td>';
      }      
      else if (isset($showsubtotal[$y]))
      {
        if($usesubunits && ($y == $stockfield))
        {  
          $showline_temp .= '<td class=subtotal align=right>' . $showsubtotal[$y];
        }
        else
        {         
          $showline_temp .= '<td class=subtotal align=right>' . myfix($showsubtotal[$y], 0);        
        }  
        $showline_temp .= '</td>';
        //reinit subtotal
        $showsubtotal[$y] = 0;
        //colspan only for title
        if ($iscolspan_temp && $colspan_temp >= 1)
        {
          $showline_temp = mb_ereg_replace("##cs##", $colspan_temp , $showline_temp);
          $iscolspan_temp = false;
        }      
      }
      else if(!$iscolspan_temp)
      {
        $showline_temp .= '<td class=subtotal>&nbsp;</td>';    
      }
      $colspan_temp ++;
    }
    //no subtotal 
    if($iscolspan_temp){$showline_temp = mb_ereg_replace("##cs##", $colspan_temp , $showline_temp);}
    
    $subtotal_todisplay .= $showline_temp . '</tr>';
  }
  $subtfield1_count ++;
  return $subtotal_todisplay;
}

$productdepartmentid = $_POST['productdepartmentid'];
$productfamilygroupid = $_POST['productfamilygroupid'];
$productfamilyid = $_POST['productfamilyid'];
$num_results=0;$product = $_POST['product'];require('inc/findproduct.php');$productnum_results=$num_results;
$dp_updatestock = (int) $_POST['updatestock']+0; if ($dp_updatestock == 1) { $currentyear = mb_substr($_SESSION['ds_curdate'],0,4); }
$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$orderby = $_POST['orderby'];
$ORDER_BY_ID = 1;
$ORDER_BY_PRODUCT_NAME = 2;
$ORDER_BY_FAMILY = 3;

//TITLE
$title = d_trad('outofstockreport');
showtitle($title);
echo '<h2>' . $title . '</h2>';
$ourparams = '<br>';
if ($productdepartmentid >= 0) { $ourparams .= '<p>' . d_trad('department') . ': ' . d_output($productdepartmentA[$productdepartmentid]) . '</p>'; }
if ($productfamilygroupid >= 0) { $ourparams .= '<p>' . d_trad('family') . ': ' . d_output($productdepartmentA[$productdepartmentid] . '/' . $productfamilygroupA[$productfamilygroupid]) . '</p>'; }
if ($productfamilyid >= 0) { $ourparams .= '<p>' . d_trad('subfamily') . ': ' . d_output($productdepartmentA[$productdepartmentid] . '/' . $productfamilygroupA[$productfamilygroupid] . '/' . $productfamilyA[$productfamilyid]) . '</p>'; }
if ($startdate  >= 0 && $stopdate >=0) { $ourparams .= '<p>' . d_trad('between',array(datefix2($startdate),datefix2($stopdate))).'</p>'; }
echo $ourparams . '<br>';

# read in supplier names and lead times
$suppliernameA = array();
$supplierleadtimeA = array();
$query = 'select clientid,clientname,leadtime from client where issupplier=1 and leadtime>0';
$query_prm = array();
require('inc/doquery.php');
for ($i=0;$i < $num_results; $i++)
{
  $id = $query_result[$i]['clientid'];
  $suppliernameA[$id] = d_decode($query_result[$i]['clientname']);
  $supplierleadtimeA[$id] = d_decode($query_result[$i]['leadtime']);
}

//SELECT
$query = 'select p.productid,p.leadtime,p.supplierid from product p';
if($orderby == $ORDER_BY_FAMILY || $productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0)
{
  $query .= ',productfamily pf, productdepartment pd, productfamilygroup pg';
}

//WHERE
$query_prm = array();

$query .= ' where p.discontinued=0';

if ($product > 0)
{ 
  $query .= ' and p.productid=?'; 
  array_push($query_prm, $product); 
}
elseif ($productnum_results > 0)
{ 
  $query .= ' and lower(p.productname) LIKE ?'; 
  array_push($query_prm, '%' .  mb_strtolower(d_encode($_POST['product'])) . '%' ); 
}
if($orderby == $ORDER_BY_FAMILY || $productfamilyid > 0 || $productfamilygroupid > 0 || $productdepartmentid > 0)
{
  $query .= ' and p.productfamilyid= pf.productfamilyid and pf.productfamilygroupid = pg.productfamilygroupid and pg.productdepartmentid = pd.productdepartmentid ';
  if($productfamilyid > 0)
  {
    $query .= ' and pf.productfamilyid=?';
    array_push($query_prm, $productfamilyid);
  }
}

if ($productdepartmentid > 0) 
{ 
  $query .= ' and pg.productdepartmentid=?';
  array_push($query_prm,$productdepartmentid); 
}
if ($productfamilygroupid > 0) 
{ 
  $query .= ' and pf.productfamilygroupid=?';
  array_push($query_prm,$productfamilygroupid); 
}
if ($productfamilyid > 0) 
{ 
  $query .= ' and p.productfamilyid=?';
  array_push($query_prm,$productfamilyid); 
}

//ORDER BY
$query_order = '';
switch($orderby)
{
  case $ORDER_BY_ID:
    if ($_SESSION['ds_useproductcode'] == 1) 
    { 
      $query_order .= ' order by p.suppliercode';$subtfield1 = 'productid';$subtfield1_descr='productname';break;
    }
    else 
    { 
      $query_order .= ' order by p.productid';$subtfield1 = 'productid';$subtfield1_descr='productname';break; 
    }
    break;
  case $ORDER_BY_PRODUCT_NAME:
    $query_order .= ' order by productname';$subtfield1 = 'productid';$subtfield1_descr='productname';break; 
    break;
  case $ORDER_BY_FAMILY :
    $query_order .= ' order by pd.departmentrank,pg.familygrouprank,pf.familyrank,p.productname';$subtfield1 = 'productfamilyid';$subtfield1_descr = 'producthierarchy';break; 
    break;
}
$query .= $query_order;
require('inc/doquery.php');
$rowproduct = $query_result; $num_rows = $num_results; unset($query_result, $num_results);

//RESULTS TO BE DISPLAYED
echo '<table class=report>';
$productid_in = "('";
$num_products = 0;
$leadtimeA = array();
for ($i=0;$i < $num_rows; $i++)
{
  if($rowproduct[$i]['leadtime'] == 0) { $rowproduct[$i]['leadtime'] = $supplierleadtimeA[$rowproduct[$i]['supplierid']]; } #setting lead time to supplier lead time
  if ($rowproduct[$i]['leadtime'] > 0)
  {
    $productid_in .= $rowproduct[$i]['productid'] . "','";
  }
  else
  {
    unset($rowproduct[$i]);
  }
}
//reindex array
$rowproduct = array_values($rowproduct);
//delete the last ,' 
if((mb_strlen($productid_in)) > 2)
{
  $productid_in = mb_substr($productid_in,0,mb_strlen($productid_in)-2) . ")";
  # check stock here, are we out of stock or were we?
  $query = 'select p.productid,p.productname,p.suppliercode,p.currentstock,p.currentstockrest,p.leadtime,p.supplierid,ms.month,ms.year';
  if($orderby == $ORDER_BY_FAMILY)
  {
    $query .= ',pf.productfamilyid';
  }
  $query .= ' from product p,monthlystock ms';
  if($orderby == $ORDER_BY_FAMILY)
  {
    $query .= ',productfamily pf, productdepartment pd, productfamilygroup pg';
  }
  $query .= ' where p.discontinued=0 and ms.productid=p.productid and ms.stock=0';
  if($orderby == $ORDER_BY_FAMILY)
  {
    $query .= ' and p.productfamilyid= pf.productfamilyid and pf.productfamilygroupid = pg.productfamilygroupid and pg.productdepartmentid = pd.productdepartmentid ';
  }
  if(mb_strlen($productid_in) > 2){ $query .= ' and p.productid in ' . $productid_in;}
  
  $query_prm  = array();
  if($startdate > 0)
  {
    $query .= ' and year >= DATE_FORMAT(?,"%Y") and month >= DATE_FORMAT(?,"%m")';
    array_push($query_prm, $startdate,$startdate);
  }
  if($stopdate  > 0)
  {
    $query .= ' and year <= DATE_FORMAT(?,"%Y") and month <= DATE_FORMAT(?,"%m")';
    array_push($query_prm, $stopdate,$stopdate);      
  }
  $query .= $query_order;
  require('inc/doquery.php');
  $rowstock= $query_result; $num_rows_stock = $num_results; $fieldnum = $dp_numfields;unset($query_result, $num_results);

  if($num_rows_stock > 0)
  {  
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    
    //show results
    $trcolor = 0;$productid_prev = 0;$i = 0;$subtfield1_count=0;
    $showsubtotalcurrentstock = 0;$showsubtotalcurrentstockrest = 0;$showsubtotal = array();
    for ($i = 0; $i < $num_rows_stock; $i++)
    {
      $trcolor++;
      echo '<tr class=trtablecolor' . $trcolor .'>';
      if($trcolor % $_SESSION['ds_nbtablecolors'] == 0) { $trcolor = 0; }
   
      if ($_SESSION['ds_useproductcode']) 
      { 
        echo '<td align=right>' . d_output($rowstock[$i]['suppliercode']) . '</td>'; 
      }
      else 
      { 
        echo '<td align=right>' . d_output($rowstock[$i]['productid']) . '</td>';  
      }        
      echo '<td>' . d_output(d_decode($rowstock[$i]['productname'])) . '</td>';  
      echo '<td align=right>' . d_trad('month' . $rowstock[$i]['month']) . '</td>';   
      echo '<td align=right>' . d_output($rowstock[$i]['year']) . '</td>';    
      echo '<td align=right>' . myfix($rowstock[$i]['currentstock']) .'&nbsp;<font size=-1>' . myfix($rowstock[$i]['currentstockrest']) . '</font>';      
      echo '<td align=right>' . d_output($rowstock[$i]['leadtime']) . '</td>';
      echo '<td align=right>' . d_output($suppliernameA[$rowstock[$i]['supplierid']]) . '</td>';
      echo '</tr>';
      
      //subtotal
      $showsubtotalcurrentstock += $rowstock[$i]['currentstock'];
      $showsubtotalcurrentstockrest += $rowstock[$i]['currentstockrest'];   
      $showsubtotal[$stockfield] = myfix($showsubtotalcurrentstock);
      if($showsubtotalcurrentstockrest > 0){ $showsubtotal[$stockfield] .= '&nbsp;<font size=-1>' . myfix($showsubtotalcurrentstockrest) . '</font>';}
      //$temp = $showsubtotal[$istockfield];echo "subotal=$temp";
      $subtfield1_count ++;$subtfield1_count_descr = 'nbmonth';
      
      if($orderby == $ORDER_BY_FAMILY)
      {
        if (!isset($productfamilyA))
        {
          require('preload/productfamily.php');
          require('preload/productfamilygroup.php');
          require('preload/productdepartment.php');
        }
        $productfamilyid = $rowstock[$i]['productfamilyid'];
        $rowstock[$i]['producthierarchy'] =  $productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . '&nbsp;/&nbsp;' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . '&nbsp;/&nbsp;' . $productfamilyA[$productfamilyid];
      }
      $subtotaltodisplay = show_subtotal($rowstock,$i,$fieldnum,$subtfield1,$subtfield1_descr,$subtfield1_count,$subtfield1_count_descr,$showsubtotal,$stockfield);

      if($subtotaltodisplay != '')
      {
        echo $subtotaltodisplay;
        //reinit subtotal        
        $showsubtotal[$istockfield] = 0;
        $showsubtotalcurrentstock = 0;
        $showsubtotalcurrentstockrest = 0;
        $subtfield1_count = 0;
      }
     }
    echo '</table>';       
  } 
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}

 # debug, let's test manually for now

#
# let's list products, stock and lead times before we worry about anything else
#
/*
//FILTER RESULTS TO DELETE PRODUCTS WITH ARRIVAL DATE >= curdate()
$productid_in = "('";
$purchasedA = array();
for($i=0;$i<$num_rows-1;$i++)
{
  $productid_in .= $row[$i]['productid'] . "','";
}
$productid_in .= $row[$i]['productid'] . "')";
//SELECT
$query = 'select distinct(productid) from purchasebatch where arrivaldate >= curdate() and productid in ' . $productid_in;
$query_prm = array();
require('inc/doquery.php');
$rowpurchased= $query_result; $num_rowspurchased = $num_results; unset($query_result, $num_results);
$row_filtered = array();
for($i=0;$i<$num_rowspurchased;$i++)
{
  array_push($purchasedA,$rowpurchased[$i]['productid']);   
}
for($i=0;$i<$num_rows;$i++)
{
  if(array_search($row[$i]['productid'],$purchasedA) === false)
  {
     array_push($row_filtered,$row[$i]);
  }
}
$row = $row_filtered;
$num_rows = count($row);
*/

//TODO
unset ($ourparams,$subtfield1,$showsubtfield1,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$showsubtotal,$showgrandtotal);

?>