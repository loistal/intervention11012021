<?php

# ALL relevant preloads must be loaded
# clientbalance requires $row[$i]['clientid']

# input: $fieldname $y (for any subtotals)
# output: $showfield $rightalign_temp

# used in showfield, showsubtotal, showgrandtotal

$rightalign_temp = 0;
switch ($fieldname)
{ 
  # formatted text
  case 'payer':
  case 'paymentcomment':
  case 'planningname':
  case 'planningcomment':
  case 'periodic': #processed in report
  $showfield = d_output($showfield);
  break;  
  
  # formatted integers with invoice link
  case 'forinvoiceid':
  case 'invoiceid':
  $rightalign_temp = 1;
  $showfield = '<a href=\'printwindow.php?report=showinvoice&invoiceid=' . $showfield .'\' target=_blank>' . myround($showfield, 0) . '</a>';
  break;   
    
  # formatted text with client link
  case 'clientname':
  $showfield = '<a href=\'reportwindow.php?report=showclient&client=' . $showfield .'\' target=_blank>' . d_decode($showfield) . '</a>';
  break;  
   
  # preload clientcategory
  case 'clientcategoryid':
  $showfield = $clientcategoryA[$showfield];
  break;
  
  
  #preload employee
  case 'employeeid':
  case 'employeeid2':  
  case 'accountemployeeid':
  $showfield = $employeeA[$showfield];
  break;
  
  #preload paymenttype
  case 'paymenttypeid':
  $showfield = $paymenttypeA[$showfield];
  break;
    
  # dates, short form
  case 'paybydate':
  case 'accountingdate':
  case 'deliverydate':
  case 'invoicedate':
  case 'paymentdate':
  case 'depositdate':
  case 'startdate':
  case 'stopdate':
  $rightalign_temp = 1;  
  $showfield = datefix2($showfield);
  break;
    
  # currency
  case 'value':
  case 'vattotal':
  $rightalign_temp = 1;
  $showsubtotal[$y] += $showfield;  
  $showgrandtotal[$y] += $showfield;   
  $showfield = myfix($showfield);
  break; 
  
  # currency with + and -
  case 'invoiceprice':
  $rightalign_temp = 1;
  $invoice_temp = $showfield;  
  if($row[$i]['isreturn'] == 1)
  {
    $showfield = '-' . myfix($showfield);  
    $showsubtotal[$y] -= $invoice_temp;  
    $showgrandtotal[$y] -= $invoice_temp; 
  }
  else
  {
    $showfield = myfix($showfield);  
    $showsubtotal[$y] += $invoice_temp;  
    $showgrandtotal[$y] += $invoice_temp;   
  }
  break; 

  #Y/N
  case 'isreturn':  
  case 'confirmed':
  case 'deleted':
  case 'reimbursement':
  $rightalign_temp = 1;  
  if($showfield == 1){$showfield = d_trad('Y');}
  else{$showfield = d_trad('N');}
  break;
  
  case 'percentage':
  $rightalign_temp = 1;
  $showfield = d_output($showfield) . '%';
  break; 
  
}

?>