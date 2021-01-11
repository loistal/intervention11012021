<?php

# ALL relevant preloads must be loaded
# clientbalance requires $row[$i]['clientid']

# input: $fieldname $y (for any subtotals)
# output: $showfield $rightalign_temp

# used in showfield, showsubtotal, showgrandtotal

$rightalign_temp = 0;
switch ($fieldname)
{
  
  # formatted integers
  case 'clientid':
  $rightalign_temp = 1;
  $showfield = myround($showfield, 0);
  break;
    
  
  # specifics below
  case 'clientcategoryid':
  $showfield = $clientcategoryA[$showfield];
  break;
  
  case 'clientcategory2id':
  $showfield = $clientcategory2A[$showfield];
  break;
  
  case 'clientname':
  $showfield = d_decode($showfield);
  break;  
  
  case 'clienttermid':
  $showfield = $clienttermA[$showfield];;
  break;
  
  case 'employeeid':
  case 'employeeid2':
  $showfield = $employeeA[$showfield];
  break;
   
  
  case 'clientbalance':
  $rightalign_temp = 1;
  $dp_clientid = $row[$i]['clientid'];
  require('inc/clientbalance.php');
  $showfield = myfix($dr_balance);
  $showsubtotal[$y] += $dr_balance;
  $showgrandtotal[$y] += $dr_balance;  
  break;

}

?>