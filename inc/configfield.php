<?php

# input: $fieldname $y (for any subtotals) $row[$i] (for conditionals) other fields (see specific case)
# output: $showfield $rightalign_temp ( 1=right align, 4 = center align)

if (!function_exists('d_showquantity'))
{
  #in : $quan <-> quantity $npu <-> numberperunit $utn <-> unittypename in BDD
  # 2019 11 27 this is WRONG WRONG WRONG
  # 2019 11 27 rewrote, TODO npu
  function d_showquantity($quan, $npu = 1, $dmp = 1, $utn = '')
  {
    if ($quan == 0) { return ''; }
    elseif ($npu == 1) { return $quan; }
    $negative = 0;
    if (d_compare($quan, 0) == -1) { $negative = 1; $quan = d_abs($quan); }
    $showquantity = myfix(floor($quan/$npu));
    if ($quan % $npu)
    {
      if ($showquantity == '&nbsp;') { $showquantity = '0'; }
      $showquantity .= ' (' . myfix($quan % $npu) . ')';
    }
    if ($negative) { $showquantity = '-'.$showquantity; }
    return $showquantity;
  }
  
  #in : $npu as in BDD $numberperunit $netweightlabel
  #out: $showpackaging
  function d_showpackaging($npu, $netweightlabel)
  {
    $showpackaging = '';
    if($npu > 1)
    {
      $showpackaging .= $npu . ' X '; 
    }
    $showpackaging .= $netweightlabel;
    return $showpackaging;
  }
}

$rightalign_temp = 0;
$break_temp = 0;
$link_temp = '';
$temp_unfiltered = 0;
if (!isset($showsubtotal[$y])) { $showsubtotal[$y] = 0; }
if (!isset($showgrandtotal[$y])) { $showgrandtotal[$y] = 0; }
if (!isset($total_quantity)) { $total_quantity = 0; }
if ($showsubtotal[$y] == '') { $showsubtotal[$y] = 0; }
if ($showgrandtotal[$y] == '') { $showgrandtotal[$y] = 0; }
if ($total_quantity == '') { $total_quantity = 0; }
switch ($fieldname)
{
  case 'clienttype':
  $temp_showfield = '';
  if (substr($showfield,0,1) == '1') { $temp_showfield = 'Client'; }
  if (substr($showfield,1,1) == '1') { $temp_showfield .= ', Fournisseur'; }
  if (substr($showfield,2,1) == '1') { $temp_showfield .= ', Employé(e)'; }
  if (substr($showfield,3,1) == '1') { $temp_showfield .= ', Autre'; }
  $temp_showfield = ltrim($temp_showfield,', ');
  $showfield = $temp_showfield;
  break;
  
  case 'localvesselid':
    require('preload/localvessel.php');
    if ($showfield > 0) { $showfield = $localvesselA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'accountingnumberid':
    if ($showfield > 0)
    {
      require('preload/accountingnumber.php'); $showfield = $accountingnumberA[$showfield];
    }
    else { $showfield = ''; }
  break;
  
  case 'jobid':
    if ($showfield > 0) { require('preload/job.php'); $showfield = $jobA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'contractid':
    if ($showfield > 0) { require('preload/contract.php'); $showfield = $contractA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'weeklyhoursid':
    if ($showfield > 0) { require('preload/weeklyhours.php'); $showfield = $weeklyhoursA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'employeecategoryid':
    if ($showfield > 0) { require('preload/employeecategory.php'); $showfield = $employeecategoryA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'teamid':
    if ($showfield > 0) { require('preload/team.php'); $showfield = $teamA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'ismanager':
    if ($showfield > 0) { require('preload/team.php'); $showfield = $teamA[$showfield]; }
    else { $showfield = ''; }
  break;
  
	case 'competitorid':
    require('preload/competitor.php');
    if ($showfield > 0) { $showfield = $competitorA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'productactiontagid':
    require('preload/productactiontag.php');
    if ($showfield > 0) { $showfield = $productactiontagA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'actionname':
  $break_temp = 1;
  break;
  
  case 'vacationdays':
    $rightalign_temp = 1;
    $showfield = myfix($showfield,1);
    break;
  
  # formatted integers
  case 'hourspermonth':
  case 'clientid':
  case 'productid':
  case 'eancode':
	case 'matchingid' :
    $rightalign_temp = 1;
    $showfield = myround($showfield, 0);
    break;

  # id not formatted
  case 'suppliercode':
	case 'chequeno':
    $rightalign_temp = 1;
    break;

  # formatted integers with invoice link
  case 'forinvoiceid':
  case 'invoiceid':
    $rightalign_temp = 1;
    $link_temp = 'printwindow.php?report=showinvoice&invoiceid=' . $showfield;
    $showfield = myfix($showfield);
    /* moved to separate field, re-enable these if clients want them (option?)
    if ($row[$i]['proforma'] == 1) { $showfield = '(Proforma) ' . $showfield; }
    if ($row[$i]['isnotice'] == 1) { $showfield = '(' . d_output($_SESSION['ds_term_invoicenotice']) . ') ' . $showfield; }
    if ($row[$i]['isreturn'] == 1) { $showfield = '(Avoir) ' . $showfield; }
    */
    break;

  # formatted text with client link
  case 'clientname':
    $clientid_temp = $row[$i]['clientid'];
    if ($clientid_temp > 0)
    {
      $link_temp = 'reportwindow.php?report=showclient&client=' . $clientid_temp;
    }
    $showfield = d_decode($showfield);
    break;

  # formatted text
  case 'supplierid':
    if (!isset($clientA))
    {
      require('preload/client.php');
    }
    $showfield = $clientA[$showfield];
    break;  
		
  # preload clientcategory
  case 'clientcategoryid':
    if (!isset($clientcategoryA))
    {
      require('preload/clientcategory.php');
    }
    if (isset($clientcategoryA[$showfield])) { $showfield = $clientcategoryA[$showfield]; }
    else { $showfield = ''; }
    break; 

  case 'bankid':
	case 'depositbankid':
    if (!isset($bankA))
    {
      require('preload/bank.php');
    }
    $showfield = $bankA[$showfield];
    break;		

  case 'clientactioncatid':
    require('preload/clientactioncat.php');
    if (isset($clientactioncatA[$showfield])) { $showfield = $clientactioncatA[$showfield]; }
    else { $showfield = ''; }
  break;
  
  case 'productactioncatid':
    require('preload/productactioncat.php');
    if ($showfield > 0) { $showfield = $productactioncatA[$showfield]; }
    else { $showfield = ''; }
  break;

  case 'clientcategory2id':
    if (!isset($clientcategory2A))
    {
      require('preload/clientcategory2.php');
    }
    $showfield = $clientcategory2A[$showfield];
  break;
    
  case 'clientcategory3id':
    if (!isset($clientcategory3A))
    {
      require('preload/clientcategory3.php');
    }
    if (isset($clientcategory3A[$showfield])) { $showfield = $clientcategory3A[$showfield]; }
    else { $showfield = ''; }
  break;

  # preload clienttermid  
  case 'clienttermid':
    if (!isset($clienttermA))
    {
      require('preload/clientterm.php');
    }
    $showfield = $clienttermA[$showfield];
    break;

  # preload temperature
  case 'temperatureid':
    if (!isset($temperatureA))
    {
      require('preload/temperature.php');
    }
    $showfield = $temperatureA[$showfield];
    break;

  # preload country
  case 'countryid':
    if (!isset($countryA))
    {
      require('preload/country.php');
    }
    $showfield = $countryA[$showfield];
    break;
    
  case 'producttypeid':
    if (!isset($producttypeA))
    {
      require('preload/producttype.php');
    }
    $showfield = $producttypeA[$showfield];
    break;
    
  case 'regulationtypeid':
    if (!isset($regulationtypeA))
    {
      require('preload/regulationtype.php');
    }
    $showfield = $regulationtypeA[$showfield];
    break;

  # preload taxcode
  case 'taxcodeid':
  case 'linetaxcodeid':
    $rightalign_temp = 1;
    if (!isset($taxecodeA))
    {
      require('preload/taxcode.php');
    }
    $showfield = $taxcodeA[$showfield] + 0;
    $showfield .= ' %';
    break;


  # preload unittype
  case 'unittypeid':
    if (!isset($unittypeA))
    {
      require('preload/unittype.php');
    }
    $showfield = $unittypeA[$showfield];
    break;

  case 'employeeid':
  case 'employeeid2':
  case 'accountemployeeid':
  case 'employee1id':
  case 'employee2id':
    require('preload/employee.php');
    if ($showfield == 0) { $showfield = ''; }
    else { $showfield = $employeeA[$showfield]; }
    break;

  case 'productname':
    $showfield = d_decode($showfield);
    $break_temp = 1;
    break;

  case 'fullproductname':
    $showfield = d_decode($row[$i]['productname'] . ' ' . d_showpackaging($row[$i]['numberperunit'], $row[$i]['netweightlabel']));
    $break_temp = 1;
    break;

  #preload productfamily, productfamilygroup and productdepartment
  case 'productfamilyid':
      require('preload/productfamily.php');
      require('preload/productfamilygroup.php');
      require('preload/productdepartment.php');
    $showfield = $productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$showfield]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$showfield]] . ' / ' . $productfamilyA[$showfield];
    break;
    
    #preload productfamily, productfamilygroup and productdepartment
  case 'productfamilygroupid':
      require('preload/productfamily.php');
      require('preload/productfamilygroup.php');
      require('preload/productdepartment.php');
    $showfield = $productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$row[$i]['productfamilyid']]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$row[$i]['productfamilyid']]];
    break;
    
  case 'productdepartmentid':
    require('preload/productfamily.php');
    require('preload/productfamilygroup.php');
    require('preload/productdepartment.php');
  $showfield = $productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$row[$i]['productfamilyid']]]];
  break;

  #preload paymenttype
  case 'paymenttypeid':
    require('preload/paymenttype.php');
    $showfield = $paymenttypeA[$showfield];
   break;  
		
	#preload paymentcategory
  case 'paymentcategoryid':
    if (!isset($paymentcategoryA))
    {
      require('preload/paymentcategory.php');
    }
    $showfield = $paymentcategoryA[$showfield];
    break;

  # dates, short form
  case 'hiringdate':
  case 'dateofbirth':
  case 'paybydate':
  case 'accountingdate':
  case 'deliverydate':
  case 'invoicedate':
  case 'paymentdate':
  case 'depositdate':
  case 'startdate':
  case 'stopdate':
  case 'actiondate':
	case 'matchingdate':
  case 'custominvoicedate':
    $rightalign_temp = 1;
    $showfield = datefix2($showfield);
    break;
    
  case 'custominvoicedateplus':
    if (!isset($invoicetag2A))
    {
      require('preload/paymentcategory.php');
    }
    $rightalign_temp = 1;
    if ($invoicetag2_daysaddedtocustomdateA[$row[$i]['invoicetag2id']] != 0)
    {
      $showfield = new DateTime($row[$i]['custominvoicedate']);
      $showfield->add(new DateInterval('P' . $invoicetag2_daysaddedtocustomdateA[$row[$i]['invoicetag2id']] . 'D'));
      $showfield = datefix2($showfield->format('Y-m-d'));
    }
    break;
		
  # times
  case 'paymenttime':
    $rightalign_temp = 1;
    $showfield = $showfield;
    break;
		
		
  # stock  productid, displaymultiplier, numberperunit and unittypeA must be loaded!
  case 'currentstock':
    if ($row[$i]['countstock'] != 1)
    {
      $showfield = '';
    }
    else
    {
      $rightalign_temp = 1; #echo 'here ',$showfield;
      if ($row[$i]['displaymultiplier'] != 0) { $showfield /= $row[$i]['displaymultiplier']; }
      $showsubtotal[$y] = (double) $showsubtotal[$y];
      $showsubtotal[$y] += $showfield;
      $showgrandtotal[$y] = (double) $showgrandtotal[$y];
      $showgrandtotal[$y] += $showfield;
      if (!isset($total_quantity)) { $total_quantity = 0; }
      $total_quantity += $showfield;
      if ($row[$i]['currentstockrest'] > 0)
      {
        $showfield .= ' (' . myfix($row[$i]['currentstockrest']) . ')';
      }
    }
    break;
    
  case 'quantity':
    $rightalign_temp = 1;
    require('preload/unittype.php');#echo $row[$i]['isreturn'];
    if ($row[$i]['isreturn'] == 1)
    {
      $showfield = d_subtract(0, $row[$i]['quantity']);
    }
    else
    {
      $showfield = d_add(0, $row[$i]['quantity']);
    }
    $showsubtotal[$y] += $showfield;
    $showgrandtotal[$y] += $showfield;
    $total_quantity += $showfield;
    if ($unittype_dmpA[$row[$i]['unittypeid']] == 1) { $showfield = myround($showfield); }
    if ($row[$i]['numberperunit'] > 1 || $unittype_dmpA[$row[$i]['unittypeid']] > 1)
    {
      $showfield = d_showquantity($showfield, $row[$i]['numberperunit'], $unittype_dmpA[$row[$i]['unittypeid']]);
      if ($row[$i]['numberperunit'] > 1 && !isset($npu_for_total[$y])) { $npu_for_total[$y] = $row[$i]['numberperunit']; }
    }
    break;

  # currency without total
  case 'basesalary':
  case 'salesprice':
  case 'detailsalesprice':
  case 'unitsalesprice':
    $rightalign_temp = 1;
    $showfield = myfix($showfield);
    break;

  case 'recent_prev':
    if ($showfield == 0)
    {
      $query = 'select prev from purchasebatch where prev>0 and productid=? order by arrivaldate desc limit 1';
      $query_prm = array($row[$i]['productid']);
      require('inc/doquery.php');
      if ($num_results)
      {
         $showfield = $query_result[0]['prev'];
         $query = 'update product set recent_prev=? where productid=?';
         $query_prm = array($showfield,$row[$i]['productid']);
        require('inc/doquery.php');
      }
    }
    $rightalign_temp = 1;
    $showfield = myfix($showfield);
    break;

  case 'salespricevat': # salesprice + vat added in
    $rightalign_temp = 1;
    if (!isset($taxcodeA))
    {
      require('preload/taxcode.php');
    }
    $showfield = ($row[$i]['salesprice'] * (1 + $taxcodeA[$row[$i]['taxcodeid']] / 100));
    if ($unittype_dmpA[$row[$i]['unittypeid']] > 1) { $showfield *= $unittype_dmpA[$row[$i]['unittypeid']]; }
    $showfield = myfix($showfield);
    break;
    
  case 'detailsalespricevat':
    $rightalign_temp = 1;
    if (!isset($taxcodeA))
    {
      require('preload/taxcode.php');
    }
    $showfield = ($row[$i]['detailsalesprice'] * (1 + $taxcodeA[$row[$i]['taxcodeid']] / 100));
    if ($unittype_dmpA[$row[$i]['unittypeid']] > 1) { $showfield *= $unittype_dmpA[$row[$i]['unittypeid']]; }
    $showfield = myfix($showfield);
    break;

  case 'costprice': # not used?
    $rightalign_temp = 1;
    $query = 'select origamount,vat,totalcost from purchasebatch where totalcost>0 and productid=? order by arrivaldate desc limit 1';
    $query_prm = array($row[$i]['productid']);
    require('inc/doquery.php');
    $origamount = $query_result[0]['origamount'];
    if ($origamount == 0)
    {
      $origamount = 1;
    }
    $prev = (($query_result[0]['totalcost'] - $query_result[0]['vat']) * $row[$i]['numberperunit']) / $origamount;
    $row[$i]['costprice'] = $prev;
    $showsubtotal[$y] += $showfield;
    $showgrandtotal[$y] += $showfield;
    break;

  case 'basecartonprice':
    $rightalign_temp = 1;
    if ($row[$i]['isreturn'] == 1)
    {
      if ($showfield > 0)
      {
        $showfield = '-' . myfix($showfield);
      }
    }
    else
    {
      $showfield = myfix($showfield);
    }
    break;

  case 'lineprice':
  case 'linevat':
  case 'givenrebate':
  case 'invoiceprice': # ttc
  case 'invoicepricenet':
  case 'invoicevat':
    $rightalign_temp = 1;
    if ($row[$i]['isreturn'] == 1)
    {
      $showsubtotal[$y] -= $showfield;
      $showgrandtotal[$y] -= $showfield;
      if ($showfield > 0)
      {
        $showfield = '-' . myfix($showfield);
      }
      else { $showfield = '&nbsp;'; }
    }
    else
    {
      $showsubtotal[$y] += $showfield;
      $showgrandtotal[$y] += $showfield;
      $showfield = myfix($showfield);
    }
    break;

  # payments
  case 'value':
    if ($showsubtotal[$y] == '') { $showsubtotal[$y] = 0; }
    if ($showgrandtotal[$y] == '') { $showgrandtotal[$y] = 0; }
    $rightalign_temp = 1;
    if ($row[$i]['reimbursement'] == 1)
    {
      $showsubtotal[$y] -= $showfield;
      $showgrandtotal[$y] -= $showfield;
      if ($showfield > 0)
      {
        $showfield = '-' . myfix($showfield);
      }
    }
    else
    {
      $showsubtotal[$y] += $showfield;
      $showgrandtotal[$y] += $showfield;
      $showfield = myfix($showfield);
    }
    break;

  # payments with reimbursement
  case 'vattotal':
  case 'value':
    $rightalign_temp = 1;
    if ($row[$i]['reimbursement'] == 1)
    {
      $showsubtotal[$y] -= $showfield;
      $showgrandtotal[$y] -= $showfield;
      if ($showfield > 0)
      {
        $showfield = '-' . myfix($showfield);
      }
    }
    else
    {
      $showsubtotal[$y] += $showfield;
      $showgrandtotal[$y] += $showfield;
      $showfield = myfix($showfield);
    }
    break;

  #Y/N
  case 'isreturn':
  case 'confirmed':
  case 'deleted':
  case 'proforma':
  case 'isnotice':
  case 'returntostock':
    $rightalign_temp = 4;
    if ($showfield == 1)
    {
      #$showfield = d_trad('Y');
      $showfield = '&radic;';
    }
    else
    {
      #$showfield = d_trad('N');
      $showfield = '';
    }
    break;

  case 'clientbalance':
    $rightalign_temp = 1;
    $dp_clientid = $row[$i]['clientid'];
    require('inc/clientbalance.php');
    $showfield = myfix($dr_balance);
    $showsubtotal[$y] += $dr_balance;
    $showgrandtotal[$y] += $dr_balance;
    break;

  case 'packaging':
    $rightalign_temp = 1;
    $showfield = d_showpackaging($row[$i]['numberperunit'], $row[$i]['netweightlabel']);
    break;

  case 'percentage':
    $rightalign_temp = 1;
    if ($showfield <= 0)
    {
      $showfield = '< 0.1%';
    }
    elseif ($showfield < 100)
    {
      $showfield = myfix($showfield, 1) . '%';
    }
    else
    {
      $showfield = myfix($showfield, 0) . '%';
    }
    if ($showsubtotal[$linepricefield] <= 0)
    {
      $showsubtotal[$percentagefield] = '100';
    }
    else
    {
      $showsubtotal[$percentagefield] = myround(($showsubtotal[$givenrebatefield] * 100 / ($showsubtotal[$linepricefield] + $showsubtotal[$givenrebatefield])), 2);
    }
    break;

  case 'userid':
    if (!isset($userA))
    {
      require('preload/user.php');
    }
    $showfield = $userA[$showfield];
    break;

  case 'invoicetagid':
    if (!isset($invoicetagA))
    {
      require('preload/invoicetag.php');
    }
    if (isset($invoicetagA[$showfield])) { $showfield = $invoicetagA[$showfield]; }
    else { $showfield = ''; }
    break;
  
  case 'invoicetag2id':
    if (!isset($invoicetag2A))
    {
      require('preload/invoicetag2.php');
    }
    $showfield = $invoicetag2A[$showfield];
    break;

  case 'returnreasonid':
    require('preload/returnreason.php');
    if (isset($returnreasonA[$showfield])) { $showfield = $returnreasonA[$showfield]; }
    else { $showfield = ''; }
    break;

  case 'invoicestatus':
    $showfield = 'Erreur';
    if ($row[$i]['cancelledid'] == 1)
    {
      $showfield = 'Annulée';
    }
    elseif ($row[$i]['cancelledid'] == 2)
    {
      $showfield = 'Archivée';
    }
    elseif ($row[$i]['matchingid'] > 0)
    {
      $showfield = 'Lettrée';
    }
    elseif ($row[$i]['confirmed'] > 0)
    {
      $showfield = 'Confirmée';
    }
    else
    {
      $showfield = 'Non confirmée';
    }
    break;

  case 'invoicetype':
    $showfield = 'Facture';
    if ($row[$i]['confirmed'] == 0)
    {
      $showfield = 'Devis';
    }
    if ($row[$i]['isreturn'] == 1)
    {
      $showfield = 'Avoir';
    }
    if (isset($row[$i]['proforma']) && $row[$i]['proforma'] == 1)
    {
      $showfield = '(Proforma) ' . $showfield;
    }
    if (isset($row[$i]['isnotice']) && $row[$i]['isnotice'] == 1)
    {
      $showfield = '(' . $_SESSION['ds_term_invoicenotice'] . ') ' . $showfield;
    }
    break;

  case 'islandid':
    if (!isset($islandA))
    {
      require('preload/island.php');
    }
    $showfield = $islandA[$showfield];
    break;

  case 'townid':
    if (!isset($townA))
    {
      require('preload/town.php');
    }
    $showfield = $townA[$showfield];
    break;
    
  case 'imageid':
    if ($showfield > 0)
    {
      $temp_unfiltered = 1;
      $showfield = '<img src="viewimage.php?image_id=' . $showfield . '">';
    }
    else { $showfield = ''; }
    break;

  case 'image':
    if (!isset($imagetextA))
    {
      require('preload/image.php');
    }
    $isproductimage = FALSE;
    $isclientimage = FALSE;
    if (isset($row[$i]['productid']))
    {
      if (is_array($image_productA)) { $imageidA = array_keys($image_productA, $row[$i]['productid']); }
      $isproductimage = TRUE;
    }
    elseif (isset($row[$i]['clientid']))
    {
      if (is_array($image_clientA)) { $imageidA = array_keys($image_clientA, $row[$i]['clientid']); }
      $isclientimage = TRUE;
    }
    if (is_array($imageidA)) { $num_images = array_count_values($imageidA); } else { $num_images = 0; }
    $showfield = '';
    if ($num_images > 0)
    {
      foreach ($imageidA as $imageid)
      {
        if ($img > 0)
        {
          #echo ', '; TODO should this be added to a string?
        }
        if ($isproductimage)
        { # TODO fix link
          #$showfield .= '<a href="reportwindow.php?report=productimages&productid=' . $row[$i]['productid'] . '&imageid=' . $imageid . '" target=_blank>' . $imagetextA[$imageid] . '</a><br>';
          $link_temp = 'reportwindow.php?report=productimages&productid=' . $row[$i]['productid'] . '&imageid=' . $imageid;
          $showfield = $imagetextA[$imageid];
        }
        elseif ($isclientimage)
        {
          #$showfield .= '<a href="viewimage.php?image_id=' . $imageid . '" target=_blank>' . $imagetextA[$imageid] . '</a><br>';
          $link_temp = 'viewimage.php?image_id=' . $imageid;
          $showfield = $imagetextA[$imageid];
        }
      }
    }
    break;
		
	#0/1 values
  case 'unionrep':
	case 'reimbursement':
	case 'toacc':
		$rightalign_temp = 4;
		if ( $showfield == 1 )
		{
			$showfield = '&radic;';
		}
		else
		{
			$showfield = '';
		}
		break;
    
  case 'invoicetime':
    $rightalign_temp = 1;
    break;
    
  case 'clientaction_caseid':
    require('preload/clientaction_case.php');
    if ($showfield > 0) { $showfield = $clientaction_caseA[$showfield]; }
    else { $showfield = ''; }
    break;
    
  case 'originid':
    if ($showfield == 1) { $showfield = d_output($_SESSION['ds_customname']); }
    else { $showfield = 'Client'; }
    break;
    
  case 'contact_typeid':
    if ($showfield == 1) { $showfield = 'Téléphone'; }
    elseif ($showfield == 2) { $showfield = 'E-mail'; }
    elseif ($showfield == 3) { $showfield = 'Contact direct'; }
    else { $showfield = ''; }
    break;
		
	default:
    if (substr($fieldname,0,11) == 'stockuserid')
    {
      $productid = $row[$i]['productid'];
      $currentyear = mb_substr($_SESSION['ds_curdate'],0,4);
      $npu = $row[$i]['numberperunit'];
      $dp_userid = substr($fieldname,11);
      require('inc/calcstock_user.php'); # mandatory input: $productid $currentyear $npu $dp_userid
      $showfield = $userstock;
      $rightalign_temp = 1;
    }
		else
    {
      $showfield = d_output($showfield);
    }
    break;
}

?>