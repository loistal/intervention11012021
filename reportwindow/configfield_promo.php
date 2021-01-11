<?php

# ALL relevant preloads must be loaded

# input: $fieldname $y (for any subtotals)
# output: $showfield $rightalign_temp

# used in showfield, showsubtotal, showsubheader

$rightalign_temp = 0;
switch ($fieldname)
{
  # right align only
  case 'packaging':
  case 'quantity':  
  $rightalign_temp = 1;
  break;
  
  case 'employeeid':
  case 'employeename':  
  $showfield = d_output($employeeA[$showfield]);
  break;
  
  # formatted integers
  case 'clientid':
  case 'invoiceid':
  case 'productid':
  $rightalign_temp = 1;
  $showfield = myfix($showfield);
  break;
    
  # currency
  case 'lineprice':
  case 'givenrebate': 
  $rightalign_temp = 1;
  $showsubtotal[$y] += $showfield;  
  $showgrandtotal[$y] += $showfield;   
  $showfield = myfix($showfield);
  break; 
  
  # dates, short form
  case 'accountingdate':
  case 'startdate':
  case 'stopdate':
  $rightalign_temp = 1;  
  $showfield = datefix2($showfield);
  break;
  
  case 'clientname':
  case 'suppliername':
  $showfield = d_decode($showfield);
  break;
  
  case 'productname':  
  $showfield = d_output($productA[$showfield]);
  break;
  
  case 'productfamilyid':
  $showfield = d_output($productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$showfield]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$showfield]] . ' / ' . $productfamilyA[$showfield]);
  break;    
  
  case 'percentage':
  $rightalign_temp = 1;
  $showfield = d_output($showfield) . '%';
  break;  
}

?>