<?php

#TODO use showreport.php instead
require('preload/employee.php');
require('preload/product.php');
require('reportwindow/promoreport_cf.php');
require('preload/productfamily.php');
require('preload/productfamilygroup.php');
require('preload/productdepartment.php');
require('preload/unittype.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$percentage = $_POST['percentage'];$percentagelabel = array('Gratuit','>1% <100%');
$productfamilyid = $_POST['productfamilyid'];
$num_results=0;$product = $_POST['product'];require('inc/findproduct.php');$productnum_results=$num_results;
$num_results=0;$client = $_POST['client1'];require('inc/findclient.php');$supplierid=$clientid;$suppliername=$clientname;$suppliernum_results=$num_results;
$num_results=0;$client = $_POST['client'];require('inc/findclient.php');$clientnum_results=$num_results;
$reportsort = $_POST['promosort'];
$excludesupplier = $_POST['excludesupplier']+0;

//TITLE
$title = d_trad('promoreport');
showtitle($title);
echo '<h2>' . $title . '</h2>';
$ourparams = '<br>';
if ($startdate  >= 0 && $stopdate >=0) { $ourparams .= '<p>' . d_trad('between',array(datefix2($startdate),datefix2($stopdate))).'</p>'; }
if ($percentage > 0 && $percentage <=2) { $ourparams .= '<p>' . d_trad('percentage') . ': ' . d_output($percentagelabel[$percentage-1]) . '</p>'; }
if ($productfamilyid > 0)
{
  $ourparams .= '<p>' . d_trad('productfamily') . ': ' . d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$productfamilyid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$productfamilyid]] . ' / ' . $productfamilyA[$productfamilyid]) . '</p>';
}
if (isset($productname)) { $ourparams .= '<p>' . d_trad('product') . ': ' . d_output($productname) . '</p>'; }
if (isset($clientname)) { $ourparams .= '<p>' . d_trad('client') . ': ' . d_output($clientname) . '</p>'; }
if (isset($suppliername))
{
  $ourparams .= '<p>' . d_trad('supplier') . ': ' . d_output($suppliername);
  if ($excludesupplier) { $ourparams .= ' Exclu'; }
  $ourparams .= '</p>';
}
//echo ourparams later after switch case order by

//SELECT
$query = 'select iih.invoiceid,iih.productid,p.suppliercode,p.productid,p.productname,p.numberperunit,p.netweightlabel,p.unittypeid,s.clientname suppliername,p.numberperunit,p.unittypeid,iih.quantity,iih.givenrebate,iih.basecartonprice,iih.lineprice,ih.accountingdate,ih.employeeid,ih.clientid,c.clientname,p.productfamilyid,ih.isreturn';
//FROM
$query .= ' from invoiceitemhistory iih,invoicehistory ih, client c ';
if($productfamilyid > 0 || $reportsort == 1){$query .= ',productfamily pf, productdepartment pd, productfamilygroup pg';}
//LEFT JOIN
$query .= ',product p LEFT JOIN client s ON p.supplierid = s.clientid';
//WHERE
$query .= ' where iih.invoiceid=ih.invoiceid and c.clientid=ih.clientid and iih.givenrebate>0 and p.productid=iih.productid';

$query_prm = array();
if ($startdate  >= 0) { $query .= ' and ih.accountingdate>=?'; array_push($query_prm, $startdate); }
if ($stopdate >= 0) { $query .= ' and ih.accountingdate<=?'; array_push($query_prm, $stopdate); }

if ($clientid > 0){ $query .= ' and ih.clientid=?'; array_push($query_prm, $clientid); }
elseif ($clientnum_results > 0){ $query .= ' and lower(c.clientname) LIKE ?'; array_push($query_prm, '%' .  mb_strtolower(d_encode($_POST['client'])) . '%' ); }

if ($product > 0){ $query .= ' and iih.productid=?'; array_push($query_prm, $product); }
elseif ($productnum_results > 0){ $query .= ' and lower(p.productname) LIKE ?'; array_push($query_prm, '%' .  mb_strtolower(d_encode($_POST['product'])) . '%' ); }

if($reportsort == 1 || $productfamilyid > 0)
{
  $query .= ' and p.productfamilyid= pf.productfamilyid and pf.productfamilygroupid = pg.productfamilygroupid and pg.productdepartmentid = pd.productdepartmentid ';
  if($productfamilyid > 0){$query .= ' and pf.productfamilyid=?';array_push($query_prm, $productfamilyid);}
}

if($supplierid > 0)
{
  if ($excludesupplier) { $query .= ' and p.supplierid<>?';array_push($query_prm, $supplierid); }
  else { $query .= ' and p.supplierid=?';array_push($query_prm, $supplierid); }
}
elseif ($suppliernum_results > 0){ $query .= ' and lower(s.clientname) LIKE ?'; array_push($query_prm, '%' .  mb_strtolower(d_encode($_POST['client1'])) . '%' ); }

if ($percentage == 1){ $query .= ' having iih.lineprice = 0';}
if ($percentage == 2){ $query .= ' having iih.givenrebate > 0  && iih.lineprice > 0';}

//ORDER BY
switch($reportsort) {
  case 0: $query .= ' order by iih.givenrebate'; $subtfield1 = 'percentage';break;
  case 1: $query .= ' order by pd.departmentrank,pg.familygrouprank,pf.familyrank'; $subtfield1 = 'productfamilyid'; break;
  case 2: $query .= ' order by p.productname'; $subtfield1 = 'productname';break;
  case 3: $query .= ' order by c.clientname'; $subtfield1 = 'clientname';break;
  case 4: $query .= ' order by iih.invoiceid'; $subtfield1 = 'invoiceid';break;
  case 5: $query .= ' order by lower(suppliername)'; $subtfield1 = 'suppliername';break;
  case 6: $query .= ' order by ih.accountingdate'; $subtfield1 = 'accountingdate';break;  
  case 7: $query .= ' order by ih.employeeid'; $subtfield1 = 'employeeid';break;    
}
echo $ourparams . '<br>';

require('inc/doquery.php');
$row = $query_result; $num_rows = $num_results; $fieldnum = $dp_numfields;unset($query_result, $num_results);

if($num_rows > 0)
{
  echo '<table class=report><thead>';
  for ($i = 1; $i <= $fieldnum; $i++)
  {
    echo '<th>' . d_output($dp_fielddescrA[$i]) . '</th>';
  }
  echo '</thead>';
  
  //process results
  for ($i = 0; $i < $num_rows; $i++)
  {    
    //process percentage
    if($row[$i]['lineprice'] <= '0'){$row[$i]['percentage'] = 100;}
    else{$row[$i]['percentage'] = myround($row[$i]['givenrebate']*100/($row[$i]['lineprice']+$row[$i]['givenrebate']),1);}
  }  
  switch($reportsort)
  {
    case 0: 
      d_sortresults($row, 'percentage', $num_rows);
      break;
      
    case 7:
      for ($i = 0; $i < $num_rows; $i++)
      {      
        //process employee name for employee sort
        $row[$i]['employeename'] = $employeeA[$row[$i]['employeeid']];
      }
      
      //special sort    
      d_sortresults($row, 'employeename', $num_rows); 
      break;
  }

  //show results
  for ($i = 0; $i < $num_rows; $i++)
  {
    echo d_tr();
    for ($y = 1; $y <= $fieldnum; $y++)
    {
      $fieldname = $dp_fieldnameA[$y];
      $showfield = $row[$i][$fieldname];
      require('inc/configfield.php');
      echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp);
    }
    echo '</tr>';
   
    $subtfield1_count ++;$subtfield1_count_descr = 'nbpromo';require('inc/showsubtotal.php');
   }
  //after last line: Grand total
  $i = ($num_rows -1); 
  if(($showgrandtotal[$givenrebatefield] > 0))
  {
    $showgrandtotal[$percentagefield] = myround(($showgrandtotal[$givenrebatefield] * 100 / ($showgrandtotal[$linepricefield]+$showgrandtotal[$givenrebatefield])),2);
  } 
  $subtfield1_count_descr = 'nbpromo';require('inc/showgrandtotal.php');
  echo '</table>';
}
else
{
  echo '<p>' . d_trad('noresult') . '</p>';
}
unset ($ourparams,$subtfield1,$showsubtfield1,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$showsubtotal,$showgrandtotal);
?>