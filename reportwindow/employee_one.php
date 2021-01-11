<style> h_title{
  background: transparent;
  margin-left: 4%;
  margin-right: 4%;
  text-align: left;
  font-size: 120%;
  font-weight: bold;
  white-space: nowrap;
  margin: 1px;
} </style>
<?php

#in : $periodic (0=punctual;1=weekly;2=monthly;3=yearly) $dayofweek (1=monday,etc) $periodic_spec(
#out: periodic trad
function d_showperiodic($periodic,$planning_date,$dayofweek,$periodic_spec)
{ 
  $showperiodic = '';
  switch($periodic)
  {
    case 0:
    $showperiodic = datefix2($planning_date);
    break;
    
    case 1:
    if ($periodic_spec == 0) { $showperiodic = d_trad('everydayofweek' . $dayofweek); }
    //else { $showperiodic = d_trad('everyweekspec' . $periodic_spec; }
    break;
    
    case 2:
    if ($periodic_spec == 0) { $month = mb_substr($planning_date,8,2);$showperiodic = d_trad('everymonth'. $month);};
    break;
    
    case 3:
    $month = d_trad('month' . mb_substr($planning_date,5,2)); $showperiodic = d_trad('everydate',array(mb_substr($planning_date,8,2),$month));
    break;
  }
  return $showperiodic;
}

require('preload/paymenttype.php');
require('preload/employee.php');
require('reportwindow/employee_one_cf.php');

$datename = 'startdate'; require('inc/datepickerresult.php');
$datename = 'stopdate'; require('inc/datepickerresult.php');
$employeeid = $_POST['employeeid'];

$PA['showemployee1clients'] = '';
$PA['showemployee2clients'] = '';
$PA['showinvoicesassets'] = '';
$PA['showinvoicesassets1'] = '';
$PA['showinvoicesassets2'] = '';
$PA['showpayments'] = '';
$PA['showexpenses'] = '';
$PA['showplanning'] = '';
require('inc/readpost.php');
$employeename = $employeeA[$employeeid];

$t_noresult = d_trad('noresult');
$t_theemployee1clients = d_trad('theemployee1clients:',array($employeename,$_SESSION['ds_term_clientemployee1']));
$t_theemployee2clients = d_trad('theemployee1clients:',array($employeename,$_SESSION['ds_term_clientemployee2']));
$t_invoicesassetsof = d_trad('invoicesassetsof:',array($employeename));
$t_paymentsof = d_trad('paymentsof:',array($employeename));
$t_expensesof = d_trad('expensesof:',array($employeename));
$t_planningof = d_trad('planningof:',array($employeename));

//TITLE
$title = d_trad('employeereport') . ' '.d_output($employeename);
showtitle($title);
echo '<h2>' . $title . '</h2>';

#$ourparams = $employeename . '<br>';
if ($startdate  >= 0 && $stopdate >=0) { echo '<p>' . d_trad('between',array(datefix2($startdate),datefix2($stopdate))).'</p><br>'; }
#echo $ourparams . '<br>';
session_write_close(); 

$nbtab = 0;

//TABLE 1 clients (employee 1)
if($showemployee1clients)
{
  require('preload/clientcategory.php');

  $query = 'select clientid,clientname,clientcategoryid,employeeid2 from client where employeeid=? order by clientname';
  $query_prm = array($employeeid);
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab1numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab1fieldnameA;$dp_fielddescrA = $dp_tab1fielddescrA;
       
  if($num_rows > 0)
  {
    $title = $t_theemployee1clients;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
  
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    
    //show results
    for ($i = 0; $i < $num_rows; $i++)
    {
      echo d_tr();
      for ($y = 1; $y <= $fieldnum; $y++)
      {
        $fieldname = $dp_fieldnameA[$y];
        if (isset($row[$i][$fieldname])) { $showfield = $row[$i][$fieldname]; }
        else { $showfield = ''; }
        require('inc/configfield.php');
        echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp);
      }
      echo '</tr>';
     }

    echo '</table><br>';  
    $nbtab ++;    
  } 
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);
}//end tab1

//TABLE 2 clients (employee 2)
if($showemployee2clients)
{
  require('preload/clientcategory.php');
  
  $query = 'select clientid,clientname,clientcategoryid,employeeid from client where employeeid2=? order by clientname';
  $query_prm = array($employeeid);
  
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab2numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab2fieldnameA;$dp_fielddescrA = $dp_tab2fielddescrA;
      
  if($num_rows > 0)
  {
    $title = $t_theemployee2clients;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
  
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>';
      if (isset($dp_fielddescrA[$i])) { echo $dp_fielddescrA[$i]; }
      echo '</th>';
    }
    echo '</thead>';
    
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
     }

    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);
}

# TABLE 3 invoices/assets 
if($showinvoicesassets)
{
  $query = 'select i.invoiceid,c.clientid,c.clientname,i.accountingdate,i.invoiceprice,i.invoicevat,i.isreturn,i.confirmed
  from invoicehistory i, client c
  where c.clientid = i.clientid and i.employeeid=?';
  $query_prm = array($employeeid);
  if ($startdate  >= 0) { $query .= ' and i.accountingdate>=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and i.accountingdate<=?'; array_push($query_prm, $stopdate); }
  $query .= ' order by i.accountingdate';
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab3numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab3fieldnameA;$dp_fielddescrA = $dp_tab3fielddescrA;
  if($num_rows > 0)
  {    
    $title = $t_invoicesassetsof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    for ($i = 0; $i < $num_rows; $i++)
    {
      if($row[$i]['invoicevat'] > 0)
      {
        $row[$i]['invoiceprice'] -= $row[$i]['invoicevat'];  
      }
    }
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
      $subtfield1 = 'accountingdate';$subtfield1_descr = 'accountingdate';
      require('inc/showsubtotal.php');        
    }
    $i = ($num_rows -1);   
    require('inc/showgrandtotal.php');
    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}

# TABLE 3-1 invoices/assets 
if($showinvoicesassets1)
{
  $query = 'select clientid,clientname,clientcategoryid,employeeid2 from client where employeeid=? order by clientname';
  $query_prm = array($employeeid);
  require('inc/doquery.php');
  $resultcount = 0;
  for ($i=0;$i<$num_results;$i++)
  {
    $resultcount++;
    $allowedclientlistA[$resultcount] = $query_result[$i]['clientid'];
  }
  $allowedclientlistA = array_filter(array_unique($allowedclientlistA));
  $allowedclientlist = '(';
  foreach ($allowedclientlistA as $kladd)
  {
    $allowedclientlist .= $kladd . ',';
  }
  $allowedclientlist = rtrim($allowedclientlist,',') . ')';
  if ($allowedclientlist == '()') { $allowedclientlist = '(-1)'; }
  unset($resultcount,$allowedclientlistA,$kladd);
  $query = 'select i.invoiceid,c.clientid,c.clientname,i.accountingdate,i.invoiceprice,i.invoicevat,i.isreturn,i.confirmed,
  isnotice,cancelledid,matchingid
  from invoicehistory i, client c
  where c.clientid = i.clientid and i.clientid in '.$allowedclientlist;
  $query_prm = array();
  if ($startdate  >= 0) { $query .= ' and i.accountingdate>=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and i.accountingdate<=?'; array_push($query_prm, $stopdate); }
  $query .= ' order by i.accountingdate';
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab3numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab3fieldnameA;$dp_fielddescrA = $dp_tab3fielddescrA;
  if($num_rows > 0)
  {    
    $title = $t_invoicesassetsof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    for ($i = 0; $i < $num_rows; $i++)
    {
      if($row[$i]['invoicevat'] > 0)
      {
        $row[$i]['invoiceprice'] -= $row[$i]['invoicevat'];  
      }
    }
    for ($i = 0; $i < $num_rows; $i++)
    {
      echo d_tr();
      for ($y = 1; $y <= $fieldnum; $y++)
      {
        $fieldname = $dp_fieldnameA[$y];
        if (isset($row[$i][$fieldname])) { $showfield = $row[$i][$fieldname]; }
        else { $showfield = ''; }
        require('inc/configfield.php');
        echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp);
      }
      echo '</tr>';
      $subtfield1 = 'accountingdate';$subtfield1_descr = 'accountingdate';
      require('inc/showsubtotal.php');        
    }
    $i = ($num_rows -1);   
    require('inc/showgrandtotal.php');
    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}

# TABLE 3-2 invoices/assets 
if($showinvoicesassets2)
{
  $query = 'select clientid,clientname,clientcategoryid,employeeid2 from client where employeeid2=? order by clientname';
  $query_prm = array($employeeid);
  require('inc/doquery.php');
  $resultcount = 0;
  for ($i=0;$i<$num_results;$i++)
  {
    $resultcount++;
    $allowedclientlistA[$resultcount] = $query_result[$i]['clientid'];
  }
  $allowedclientlistA = array_filter(array_unique($allowedclientlistA));
  $allowedclientlist = '(';
  foreach ($allowedclientlistA as $kladd)
  {
    $allowedclientlist .= $kladd . ',';
  }
  $allowedclientlist = rtrim($allowedclientlist,',') . ')';
  if ($allowedclientlist == '()') { $allowedclientlist = '(-1)'; }
  unset($resultcount,$allowedclientlistA,$kladd);
  $query = 'select i.invoiceid,c.clientid,c.clientname,i.accountingdate,i.invoiceprice,i.invoicevat,i.isreturn,i.confirmed,
  isnotice,cancelledid,matchingid
  from invoicehistory i, client c
  where c.clientid = i.clientid and i.clientid in '.$allowedclientlist;
  $query_prm = array();
  if ($startdate  >= 0) { $query .= ' and i.accountingdate>=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and i.accountingdate<=?'; array_push($query_prm, $stopdate); }
  $query .= ' order by i.accountingdate';
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab3numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab3fieldnameA;$dp_fielddescrA = $dp_tab3fielddescrA;
  if($num_rows > 0)
  {    
    $title = $t_invoicesassetsof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    for ($i = 0; $i < $num_rows; $i++)
    {
      if($row[$i]['invoicevat'] > 0)
      {
        $row[$i]['invoiceprice'] -= $row[$i]['invoicevat'];  
      }
    }
    for ($i = 0; $i < $num_rows; $i++)
    {
      echo d_tr();
      for ($y = 1; $y <= $fieldnum; $y++)
      {
        $fieldname = $dp_fieldnameA[$y];
        if (isset($row[$i][$fieldname])) { $showfield = $row[$i][$fieldname]; }
        else { $showfield = ''; }
        require('inc/configfield.php');
        echo d_td_old($showfield, $rightalign_temp, $break_temp, 0, $link_temp);
      }
      echo '</tr>';
      $subtfield1 = 'accountingdate';$subtfield1_descr = 'accountingdate';
      require('inc/showsubtotal.php');        
    }
    $i = ($num_rows -1);   
    require('inc/showgrandtotal.php');
    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}

//TABLE 4 payments
if($showpayments)
{
  require('preload/paymenttype.php');    
  //SELECT
  $query = 'select c.clientid,c.clientname,p.value,p.paymenttime,p.paymentdate,p.payer,p.paymentcomment,p.paymenttypeid,p.reimbursement,p.forinvoiceid,p.paymentcategoryid,p.depositdate,p.vattotal from payment p, client c where c.clientid = p.clientid and p.employeeid=?';
  $query_prm = array($employeeid);

  if ($startdate  >= 0) { $query .= ' and p.paymentdate>=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and p.paymentdate<=?'; array_push($query_prm, $stopdate); }

  $query .= ' order by p.paymentdate';
      
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab4numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab4fieldnameA;$dp_fielddescrA = $dp_tab4fielddescrA;

  if($num_rows > 0)
  {
    $title = $t_paymentsof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
    
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    
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
      $subtfield1 = 'paymentdate';$subtfield1_descr = 'paymentdate';
      require('inc/showsubtotal.php');           
    }
    //after last line: Grand total
    $i = ($num_rows -1);   
    require('inc/showgrandtotal.php');

    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}//end tab4  

//TABLE 5 expenses
$showexpenses = 0; # _exp table no longer used
if($showexpenses)
{
  //SELECT
  $query = 'select c.clientid,c.clientname,p.value,p.paymenttime,p.paymentdate,p.payer,p.paymentcomment,p.paymenttypeid,p.reimbursement,p.forinvoiceid,p.paymentcategoryid,p.depositdate,p.vattotal from payment_exp p, client c where c.clientid = p.clientid and p.employeeid=?';
  $query_prm = array($employeeid);

  if ($startdate  >= 0) { $query .= ' and p.paymentdate>=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and p.paymentdate<=?'; array_push($query_prm, $stopdate); }

  $query .= ' order by p.paymentdate';
  
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab5numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab5fieldnameA;$dp_fielddescrA = $dp_tab5fielddescrA;
   
  if($num_rows > 0)
  { 
    $title = $t_expensesof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
  
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    
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
      $subtfield1 = 'paymentdate';$subtfield1_descr = 'paymentdate';
      require('inc/showsubtotal.php');         
    }
    //after last line: Grand total
    $i = ($num_rows -1);   
    require('inc/showgrandtotal.php');

    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}//end tab5 

//TABLE 6 planning
if($showplanning)
{
  //SELECT
  $query = 'select p.planningname,p.planningcomment,p.planningdate,p.dayofweek,p.periodic,p.periodic_spec from planning p,planning_employee pe where pe.planningid = p.planningid and pe.employeeid=? and p.deleted=0';
  $query_prm = array($employeeid);

  if ($startdate  >= 0) { $query .= ' and p.planningstart<=?'; array_push($query_prm, $startdate); }
  if ($stopdate >= 0) { $query .= ' and p.planningstop>=?'; array_push($query_prm, $stopdate); }

  $query .= ' order by p.planningstart';
  
  require('inc/doquery.php');
  $row = $query_result; $num_rows = $num_results; $fieldnum = $dp_tab6numfields;unset($query_result, $num_results);
  $dp_fieldnameA = $dp_tab6fieldnameA;$dp_fielddescrA = $dp_tab6fielddescrA;
    
  if($num_rows > 0)
  {         
    $title = $t_planningof;
    showtitle($title);
    echo '<h_title>' . $title . '</h_title>';
  
    echo '<table class=report><thead>';
    for ($i = 1; $i <= $fieldnum; $i++)
    {
      echo '<th>' . $dp_fielddescrA[$i] . '</th>';
    }
    echo '</thead>';
    
    //process results
    for ($i = 0; $i < $num_rows; $i++)
    {   
      //process quantity
      $row[$i]['periodic'] = d_showperiodic($row[$i]['periodic'],$row[$i]['planningdate'],$row[$i]['dayofweek'],$row[$i]['periodic_spec']);  
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
    }
    echo '</table><br>';
    $nbtab ++;        
  }
  unset ($row,$num_rows,$fieldnum,$dp_numfields,$dp_fielddescrA,$dp_fieldnameA,$subtfield1,$showsubtfield1,$showsubtotal,$showgrandtotal);   
}//end tab6  

if($nbtab == 0)
{
  echo '<p>' . $t_noresult . '</p>';
}
  
unset ($ourparams);
?>