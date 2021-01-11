<?php
require('d3/temgraph.php');

$BY_TOTAL = 1;
$BY_DETAILEDCLIENTCATEGORY = 5;
$BY_DETAILEDCLIENTCATEGORYGROUP = 6;
$BY_EMPLOYEE = 3;
$BY_PRODUCT = 4;

$PA['annual'] = 'uint';
$PA['totalonleft'] = 'int';
$PA['byhour'] = 'uint';
$PA['showclientemployee'] = 'int';
$PA['excludesupplier'] = 'int';
$PA['excludenotice'] = 'int';
$PA['exludefromvatreport'] = 'int';
$PA['byquantity'] = 'int';
require('inc/readpost.php');

session_write_close();

$reporttype = $_POST['reporttype']+0;
$clientcategoryid = $_POST['clientcategoryid']+0;
$clientcategory2id = $_POST['clientcategory2id']+0;
$clientcategorygroupid = $_POST['clientcategorygroupid']+0;
$clientcategorygroup2id = $_POST['clientcategorygroup2id']+0;
$productdepartmentid = $_POST['productdepartmentid']+0;
$productfamilygroupid = $_POST['productfamilygroupid']+0;
$productfamilyid = $_POST['productfamilyid']+0;
$brand = $_POST['brand'];
$reference = $_POST['reference'];
$supplierid = (int) $_POST['supplierid'];
$porderby = (int) $_POST['porderby']+0;
$townid = (int)$_POST['townid'] + 0 ;
$islandid = (int)$_POST['islandid'] + 0 ;
$userid = (int)$_POST['userid']+0;

if ($annual == 1) { $totalonleft = 0; }
if ($annual == 1 || $reporttype > 1) { $byhour = 0; }
if ($byhour)
{
  for ($i=0;$i<24;$i++) { $sum_hour[$i] = 0; }
}

$porderbyfam = 0; $porderbytype = 0; # $porderbydpt = 0; 
switch ($porderby)
{
  case 1:
		$porderbyfam = 1;
		break;		
  case 3:
		$porderbytype = 1;
		break;
}

$isreport_bytotal = ($reporttype == $BY_TOTAL);
$isreport_bydetailedclientcategory = 0; $isreport_byclientcategory = 0;
$isreport_bydetailedclientcategorygroup = 0; $isreport_byclientcategorygroup = 0;
if ($reporttype == $BY_DETAILEDCLIENTCATEGORY)
{
	if ($clientcategoryid > 0 ) { $isreport_bydetailedclientcategory = 1;} else { $isreport_byclientcategory = 1;}
}
if ($reporttype == $BY_DETAILEDCLIENTCATEGORYGROUP)
{
	if ($clientcategorygroupid > 0 ) { $isreport_bydetailedclientcategorygroup = 1;	} else { $isreport_byclientcategorygroup = 1;}
}
$isreport_byemployee = ($reporttype == $BY_EMPLOYEE);
$isreport_byproduct = ($reporttype == $BY_PRODUCT);


#we don't want to show params who are not concerned by the report type choosen
#so put params to 0 before using showparams.php
#if (($isreport_bydetailedclientcategory == 0) && ($isreport_byclientcategory == 0)) { $clientcategoryid = 0;}
if (($isreport_bydetailedclientcategorygroup == 0) && ($isreport_byclientcategorygroup == 0)) { $clientcategorygroupid = 0;}
if ($isreport_byproduct == 0) { $productdepartmentid = 0; $productfamilygroupid = 0; $productfamilyid = 0; $supplierid = 0; $brand = '';}

$client = $_POST['client'];
if (!isset($client)) { $client = $_GET['client']; }
require ('inc/findclient.php');

if ($clientcategoryid > 0) { require('preload/clientcategory.php'); }
if ($clientcategory2id > 0) { require('preload/clientcategory2.php'); }
if ($clientcategorygroup2id > 0) { require('preload/clientcategorygroup2.php'); }
if ($isreport_byclientcategory || $isreport_bydetailedclientcategory) { require('preload/clientcategory.php'); }
if ($isreport_byclientcategorygroup || $isreport_bydetailedclientcategorygroup) { require('preload/clientcategorygroup.php'); }
if ($isreport_byemployee || $showclientemployee) { require('preload/employee.php'); }
if ($isreport_byproduct)
{
  require('preload/product.php');
  if ($porderbyfam)
  {
    require('preload/productfamily.php');
    require('preload/productfamilygroup.php');
    require('preload/productdepartment.php');
  }
	else if ($porderbytype)
	{
		require('preload/producttype.php');
	}
}
if ($isreport_bydetailedclientcategory)
{
  # preload a single client category
  $clientcategoryname = $clientcategoryA[$clientcategoryid];
  $query = 'select clientid,clientname from client where clientcategoryid=?';
  $query_prm = array($clientcategoryid);
  require ('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $temp_clientid = $query_result[$i]['clientid'];
    $clientA[$temp_clientid] = $query_result[$i]['clientname'];
  }
}
if ($isreport_bydetailedclientcategorygroup)
{
  # preload a single client category group
  $clientcategorygroupname = $clientcategorygroupA[$clientcategorygroupid];
  $query = 'select clientid,clientname from client c,clientcategory cc where c.clientcategoryid=cc.clientcategoryid and cc.clientcategorygroupid=?';
  $query_prm = array($clientcategorygroupid);
  require ('inc/doquery.php');
  for ($i=0; $i < $num_results; $i++)
  {
    $temp_clientid = $query_result[$i]['clientid'];
    $clientA[$temp_clientid] = $query_result[$i]['clientname'];
  }
}

$datename = 'start';
require('inc/datepickerresult.php');
$startdate = $start;

$datename = 'stop';
require('inc/datepickerresult.php');
$stopdate = $stop;

$year = mb_substr($startdate,0,4);

if ($annual == 1)
{
  $startdate = $year . '-01-01';
  $stopdate = $year . '-12-31';
}

if ($stopdate < $startdate) { $stopdate = $startdate; }

if ($byquantity)
{
  showtitle('Quantités ');
  echo '<h2>Quantités ';
}
else
{
  showtitle('Chiffre d\'Affaire');
  echo '<h2>Chiffre d\'Affaire ';
}
echo datefix($startdate) . ' à ' . datefix($stopdate) . '</h2>';
$dp_noshowempty = 1;require('inc/showparams.php');
# invoices
if ($isreport_byproduct)
{
  if ($byquantity) { $query = 'select sum(quantity/numberperunit) as revenue'; }
  else { $query = 'select sum(lineprice) as revenue'; }
}
else { $query = 'select sum(invoiceprice-invoicevat) as revenue'; }
if ($isreport_byclientcategory) { $query = $query . ',clientcategoryid as id'; }
if ($isreport_byclientcategorygroup) { $query = $query . ',clientcategorygroupid as id'; }
if ($isreport_byemployee) { $query = $query . ',invoicehistory.employeeid as id'; }
if ($isreport_byproduct) { $query = $query . ',invoiceitemhistory.productid as id'; }
if ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup) { $query = $query . ',invoicehistory.clientid as id'; }
if ($annual == 1) { $query = $query . ',month(accountingdate) as month'; }
if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0)) { $query = $query . ',client.employeeid as employeeid'; }
if ($isreport_byproduct)
{
	if ($porderbyfam)
	{ 
		$query .= ',product.productfamilyid,productfamily.productfamilygroupid,productdepartment.productdepartmentid';
	}
	elseif ($porderbytype)
	{
		$query .= ',product.producttypeid';
	}
}
if ($islandid > 0) { $query .= ',town.islandid'; }
if ($byhour) { $query .= ',invoicedate,time_format(invoicetime,"%H") as hour'; }

$query = $query . ' from invoicehistory';
if ($isreport_bydetailedclientcategorygroup) { $query = $query . ',clientcategory'; }
if ($clientcategorygroup2id > 0) { $query = $query . ',clientcategory2'; }
if ($isreport_byproduct)
{
  $query = $query . ',invoiceitemhistory,product,productfamily,productfamilygroup,productdepartment';
}
if ($islandid > 0) { $query .= ',town'; }

if ($isreport_byclientcategory || $isreport_bydetailedclientcategory || $isreport_byclientcategorygroup || $isreport_bydetailedclientcategorygroup || $clientid > 0
|| $clientcategoryid > 0 || $clientcategory2id > 0 || $clientcategorygroup2id > 0 || $townid > 0 || $islandid > 0)
{ $query = $query . ',client'; }
#LEFT JOIN
if ($isreport_byclientcategorygroup) { $query = $query . ' LEFT JOIN clientcategory on client.clientcategoryid=clientcategory.clientcategoryid '; }

#WHERE
$query = $query . ' where';
if ($isreport_byproduct)
{
  $query = $query . ' invoiceitemhistory.productid=product.productid and';
  $query .= ' product.productfamilyid=productfamily.productfamilyid and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid and productfamilygroup.productdepartmentid=productdepartment.productdepartmentid and';
}
if ($isreport_byclientcategory || $isreport_bydetailedclientcategory || $isreport_byclientcategorygroup || $isreport_bydetailedclientcategorygroup || $clientid > 0
|| $clientcategoryid > 0 || $clientcategory2id > 0 || $clientcategorygroup2id > 0 || $townid > 0 || $islandid > 0)
{ $query = $query . ' invoicehistory.clientid=client.clientid and'; }
if ($isreport_bydetailedclientcategorygroup) { $query = $query . ' client.clientcategoryid=clientcategory.clientcategoryid and'; }
if ($clientcategorygroup2id > 0) { $query = $query . ' client.clientcategory2id=clientcategory2.clientcategory2id and'; }
if ($isreport_byproduct) { $query = $query . ' invoiceitemhistory.invoiceid=invoicehistory.invoiceid and'; }

$query = $query . ' isreturn=0 and confirmed=1 and cancelledid=0 and accountingdate>=? and accountingdate<=?';
$query_prm = array($startdate,$stopdate);
if ($isreport_bydetailedclientcategory) { $query = $query . ' and client.clientcategoryid=?'; array_push($query_prm, $clientcategoryid); }
if ($isreport_bydetailedclientcategorygroup) { $query = $query . ' and clientcategory.clientcategorygroupid=?'; array_push($query_prm, $clientcategorygroupid); }
if ($clientid > 0) { $query = $query . ' and invoicehistory.clientid=?'; array_push($query_prm, $clientid); }
if ($clientcategoryid > 0) { $query = $query . ' and client.clientcategoryid=?'; array_push($query_prm, $clientcategoryid); }
if ($clientcategory2id > 0) { $query = $query . ' and client.clientcategory2id=?'; array_push($query_prm, $clientcategory2id); }
if ($clientcategorygroup2id > 0) { $query = $query . ' and clientcategory2.clientcategorygroup2id=?'; array_push($query_prm, $clientcategorygroup2id); }
if ($productdepartmentid > 0 && $isreport_byproduct) { $query = $query . ' and productdepartment.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($productfamilygroupid > 0 && $isreport_byproduct) { $query = $query . ' and productfamilygroup.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productfamilyid > 0 && $isreport_byproduct) { $query = $query . ' and product.productfamilyid=?'; array_push($query_prm, $productfamilyid); }
if ($supplierid > 0 && $isreport_byproduct)
{
  if ($excludesupplier == 1) { $query = $query . ' and product.supplierid<>?'; }
  else { $query = $query . ' and product.supplierid=?'; }
	array_push($query_prm, $supplierid);
}
if ($excludenotice == 1) { $query .= ' and invoicehistory.isnotice=0'; }
if ($isreport_byproduct && $exludefromvatreport == 1) { $query .= ' and exludefromvatreport=0'; }
if ($reference != "") { $query = $query . ' and invoicehistory.reference NOT LIKE ?'; $kladd = '%' . $reference . '%'; array_push($query_prm, $kladd); }
if ($isreport_byproduct && $brand != "") { $query = $query . ' and product.brand LIKE ?'; $kladd = '%' . $brand . '%'; array_push($query_prm, $kladd);}
if ($townid > 0 || $islandid > 0) { $query .= ' and town.townid = client.townid'; }
if ($townid > 0) { $query .= ' and client.townid=?'; array_push($query_prm, $townid); }
if ($islandid > 0) { $query .= ' and town.islandid=?'; array_push($query_prm, $islandid); }
if ($userid > 0) { $query .= ' and invoicehistory.userid=?'; array_push($query_prm, $userid); }

#GROUP BY
if ($reporttype > 1) { $query = $query . ' group by id'; }
if ($annual == 1 && $reporttype > 1) { $query = $query . ',month'; }
if ($annual == 1 && $isreport_bytotal) { $query = $query . ' group by month'; }
if ($byhour) { $query .= ' group by invoicedate,hour order by invoicedate,hour'; }

	
#ORDER BY
if ($isreport_byproduct)
{ 
	if ($porderbyfam) 
	{ 
		$query .= ' order by departmentrank,productdepartmentname,familygrouprank,productfamilygroupname,familyrank,productfamilyname,productname'; 
	}
	else if ($porderbytype)
	{
		$query .= ' order by producttypeid'; 
	}
}
#echo '<br>'.$query;
require ('inc/doquery.php');
$revenue = array();
for ($i=0; $i < $num_results; $i++)
{
  if ($isreport_bytotal) { $x = 0; }
  elseif(isset($query_result[$i]['id'])) { $x = $query_result[$i]['id']; }
  else { $x = 0; }
  if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0)) { $clientemployeeA[$x] = $query_result[$i]['employeeid']; }
  if ($isreport_byproduct)
	{
		if ($porderbyfam)
		{ 
			$prod_pfidA[$x] = $query_result[$i]['productfamilyid']; 
			$prod_pfgidA[$x] = $query_result[$i]['productfamilygroupid']; 
			$prod_pdpidA[$x] = $query_result[$i]['productdepartmentid'];
		}
		elseif ($porderbytype)
		{
			$prod_typeA[$x] = $query_result[$i]['producttypeid']; 
		}
	}
  if ($annual == 1)
  {
    $month = $query_result[$i]['month'];
    $revenuemonth[$x][$month] = $query_result[$i]['revenue'];
    if (!isset($totalrevenuemonth[$month])) { $totalrevenuemonth[$month] = 0; }
		$totalrevenuemonth[$month] += $revenuemonth[$x][$month];
    $revenue[$x] = 0;
  }
  elseif ($byhour)
  {
    $hour = (int) $query_result[$i]['hour'];
    $revenue[$query_result[$i]['invoicedate']][$hour] = $query_result[$i]['revenue'];
    $sum_hour[$hour] += $query_result[$i]['revenue'];
    if (!isset($revenue_total[$query_result[$i]['invoicedate']])) { $revenue_total[$query_result[$i]['invoicedate']] = 0; }
    $revenue_total[$query_result[$i]['invoicedate']] += $query_result[$i]['revenue'];
  }
  else
  {
    $revenue[$x] = $query_result[$i]['revenue'];
  }
}
if ($annual == 1)
{
  for ($i=0; $i < $num_results; $i++)
  {
    $x = $query_result[$i]['id']; if ($isreport_bytotal) { $x = 0; }
    $month = $query_result[$i]['month'];
    $revenue[$x] = $revenue[$x] + $revenuemonth[$x][$month];
  }
}

# returns
$query = str_replace('isreturn=0', 'isreturn=1', $query);
#echo '<br>'.$query;
require ('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  if ($isreport_bytotal) { $x = 0; }
  elseif(isset($query_result[$i]['id'])) { $x = $query_result[$i]['id']; }
  else { $x = 0; }
  if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0)) { $clientemployeeA[$x] = $query_result[$i]['employeeid']; }
  if ($isreport_byproduct)
	{
		if ($porderbyfam)
		{ 
			$prod_pfidA[$x] = $query_result[$i]['productfamilyid']; 
			$prod_pfgidA[$x] = $query_result[$i]['productfamilygroupid']; 
			$prod_pdpidA[$x] = $query_result[$i]['productdepartmentid'];
		}
		elseif ($porderbytype)
		{
			$prod_typeA[$x] = $query_result[$i]['producttypeid']; 
		}
	}

  if ($annual == 1)
  {
    $month = $query_result[$i]['month'];
    $revenuemonthreturn[$x][$month] = $query_result[$i]['revenue'];
    if (!isset($totalrevenuemonthreturn[$month])) { $totalrevenuemonthreturn[$month] = 0; }
		$totalrevenuemonthreturn[$month] += $revenuemonthreturn[$x][$month];		
  }
  elseif ($byhour)
  {
    /* leave returns for this case!
    if (!isset($revenue[$query_result[$i]['invoicedate']][$query_result[$i]['hour']]))
    { $revenue[$query_result[$i]['invoicedate']][$query_result[$i]['hour']] = 0; }
    $revenue[$query_result[$i]['invoicedate']][$query_result[$i]['hour']] -= $query_result[$i]['revenue'];
    */
  }
  else
  {
    $revenue[$x] = $revenue[$x] - $query_result[$i]['revenue'];
  }
}

# display report
if ($isreport_byproduct)
{
	if ($porderbyfam)
	{
		$lastpfid = -1;$lastpfgid = -1;$lastpdpid = -1;
	}
	elseif ($porderbytype)
	{
		$lastptypeid = -1;
	}
}
else
{
  arsort($revenue); # TODO use d_sortarray
}
$total = 0;
$numres = count($revenue);

#initialization
if ($annual)
{
	$subtotalmonth = array();$familygroupsubtotalmonth = array();$departmentsubtotalmonth = array();
	for ($month=1;$month<=12;$month++)
	{
		$subtotalmonth[$month] = 0;$familygroupsubtotalmonth[$month] = 0;$departmentsubtotalmonth[$month] = 0;
	}
}
if ($numres > 0 && !$byhour)
{
	echo '<br><table class="report" border=1 cellspacing=2 cellpadding=2><tr>';
	if ($totalonleft == 1) { echo '<td align=right><b>Total</td>'; } 
	if ($isreport_bytotal) { echo '<td><b>&nbsp;'; }
	if ($isreport_byclientcategory) { echo '<td><b>Catégorie client'; }
	if ($isreport_byclientcategorygroup) { echo '<td><b>Famille de catégorie client'; }
	if ($isreport_byemployee) { echo '<td><b>Employé'; }
	if ($isreport_byproduct)
	{ 
		echo '<td><b>Produit'; 
		if ($porderbytype) 
		{
			echo '<td><b>Type de produit';
		}
	}
	if ($isreport_bydetailedclientcategory) { echo '<td><b>Clients "' . $clientcategoryname . '"'; }
	if ($isreport_bydetailedclientcategorygroup) { echo '<td><b>Famille de clients "' . $clientcategorygroupname . '"'; }
	echo '</td>';
	if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0)) { echo '<td><b>Employé</td>'; }
	#for multiline graph
	$graphxvaluesA = array();
	if ($annual == 1)
	{
		for ($y=1;$y<13;$y++) 
		{ 
			$totalm[$y] = 0; 
			#for multiline graph
			$graphxvaluesA[$y] = d_getfirstdayofmonth($y,$year);
		}
		echo '<td align=right><b>Jan</td><td align=right><b>Fév</td><td align=right><b>Mars</td><td align=right><b>Avr</td><td align=right><b>Mai</td><td align=right><b>Juin</td><td align=right><b>Juil</td><td align=right><b>Août</td><td align=right><b>Sept</td><td align=right><b>Oct</td><td align=right><b>Nov</td><td align=right><b>Déc</td>';
	}
	if ($totalonleft == 0) { echo '<td align=right><b>Total</td>'; }
	echo '</tr>';
	#for simple graph
	$igraph = 0;$graphnamesA = array(); $graphvaluesA = array();
	#for multiline graph
	$graphdataA = array();$graphdatumA = array();
	$familysubtotal = 0;$familygroupsubtotal = 0;$departmentsubtotal = 0;$typesubtotal = 0;
	$isfirsttotal = 1; 
	foreach ($revenue as $id => $value)
	{
		
			
		if ($isreport_byclientcategory) { $idname = $clientcategoryA[$id]; }
		if ($isreport_byclientcategorygroup) { $idname = $clientcategorygroupA[$id]; }
		if ($isreport_byemployee) { $idname = $employeeA[$id]; }
		if ($isreport_byproduct)
		{
			$idname = $productA[$id] . ' ' . $product_packagingA[$id];
			if ($porderbyfam)
			{
				$pfid = $prod_pfidA[$id];
				$pfgid = $prod_pfgidA[$id];
				$pdpid = $prod_pdpidA[$id];
				#family subtotal
				if ($pfid != $lastpfid) 
				{ 
					if ($isfirsttotal == 0)
					{
						echo '<tr>';
						if ($totalonleft == 1) 
						{
							echo '<td align= right><b>' . myfix($familysubtotal) . '</b></td>';	
						}
						echo '<td><b> &nbsp; &nbsp; &nbsp; Sous-famille ' . $productfamilyA[$lastpfid] .  '</td>';
						if ($annual == 1)
						{ 
							for ($month=1;$month<=12;$month++) 
							{
								echo '<td align=right>' . myfix($subtotalmonth[$month]);
                $subtotalmonth[$month] = 0;
							}
						}
						if ($totalonleft == 0)
						{
							echo '<td align= right><b>' . myfix($familysubtotal) . '</b></td>';								
						}		
						$familysubtotal = 0;
						
						#familygroupsubtotal
						if ($porderbyfam && ($pfgid != $lastpfgid) )
						{ 
							echo '<tr>';					
							if ($totalonleft == 1) 
							{ 
								echo '<td align= right><b>' . myfix($familygroupsubtotal);	
							}
							echo '<td><b> &nbsp; &nbsp; Famille ' . $productfamilygroupA[$lastpfgid];
							if ($annual == 1)
							{ 
								for ($month=1;$month<=12;$month++) 
								{ 
									echo '<td align=right>' . myfix($familygroupsubtotalmonth[$month]) . '</td>'; 
								}
							}			
							if ($totalonleft == 0)
							{ 
								echo '<td align= right><b>' . myfix($familygroupsubtotal) . '</b></td>';	
							}							
							echo '</tr>';							
							$familygroupsubtotal = 0;
							if ($annual == 1) {	for ($month=1;$month<=12;$month++) { $familygroupsubtotalmonth[$month] = 0; }}
							
							#familydepartmentsubtotal
							if ($pdpid != $lastpdpid)
							{
                echo '<tr>';
                if ($totalonleft == 1)
                {
                  echo '<td align= right><b>' . myfix($departmentsubtotal) . '</b></td>';	
                }
                echo '<td><b> &nbsp; Département ';
                echo $productdepartmentA[$lastpdpid];
                if ($annual == 1)
                { 
                  for ($month=1;$month<=12;$month++) 
                  { 
                    echo '<td align=right>' . myfix($departmentsubtotalmonth[$month]) . '</td>'; 
                  }
                }																				
                if ($totalonleft == 0)
                { 
                  echo '<td align= right><b>' . myfix($departmentsubtotal) . '</b></td>';	
                }								
                echo '</tr>';									
                $departmentsubtotal = 0;
                if ($annual == 1)	{	for ($month=1;$month<=12;$month++){	$departmentsubtotalmonth[$month] = 0;}}									
							}
						}
					}
					$colspan = 2; if ($annual == 1) { $colspan = 14;}
					echo '<tr><td colspan=' . $colspan . '><b>' . $productdepartmentA[$productfamilygroup_pdidA[$productfamily_pfgidA[$pfid]]] . ' / ' . $productfamilygroupA[$productfamily_pfgidA[$pfid]] . ' / ' . $productfamilyA[$pfid];
					$isfirsttotal = 0;
				}
				$lastpfid = $pfid;
				$lastpfgid = $pfgid;
				$lastpdpid = $pdpid;
			}
			elseif ($porderbytype)
			{
				$ptypeid = $prod_typeA[$id];

				if ($ptypeid != $lastptypeid) 
				{ 
					if ($isfirsttotal == 0)
					{
						echo '<tr>';
						if ($totalonleft == 1) 
						{
							echo '<td align= right><b>' . myfix($typesubtotal) . '</b></td>';	
						}
						echo '<td><b>Total ' . $producttypeA[$lastptypeid] .  '</td><td></td>';
						if ($annual == 1)
						{ 
							for ($month=1;$month<=12;$month++) 
							{ 
								echo '<td align=right>' . myfix($subtotalmonth[$month]) . '</td>'; 
							}
						}
						if ($totalonleft == 0)
						{
							echo '<td align= right><b>' . myfix($typesubtotal) . '</b></td>';								
						}
						echo '</tr>';				
						$typesubtotal = 0;						
						if ($annual == 1)	{ for ($month=1;$month<=12;$month++) { $subtotalmonth[$month] = 0; }}
					}
					$colspan = 3; if ($annual == 1) { $colspan = 15;}
					if ($ptypeid != 0){	echo '<tr><td colspan=' . $colspan . '><b>' . $producttypeA[$ptypeid];}
					$isfirsttotal = 0;
				}
				$lastptypeid = $ptypeid;			
			}
		}
		if ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup) { $idname = $id . ': ' . $clientA[$id]; }
		echo '<tr>';
		if ($totalonleft == 1) { echo '<td align=right>' . myfix($value) . '</td>'; }		
		echo '<td>'; if (isset($idname)) { echo $idname; }
		if ($isreport_byproduct && $porderbytype)
		{
			$ptypename = '';
			if ($ptypeid > 0){ $ptypename = $producttypeA[$ptypeid];} 
			echo '<td>' . $ptypename;
		}
		if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0))
		{
			$employeeid = $clientemployeeA[$id];
			echo '<td>' . $employeeA[$employeeid] . '</td>';
		}
		if ($annual == 1)
		{
      $value = 0;
			for ($y=1;$y<13;$y++)
			{
        $revmonth = 0;
        if (isset($revenuemonth[$id][$y])) { $revmonth += $revenuemonth[$id][$y]; }
				if (isset($revenuemonthreturn[$id][$y])) { $revmonth -= $revenuemonthreturn[$id][$y]; }
				if ($revmonth == 0) { echo '<td>&nbsp;</td>'; }
				else
				{
					echo '<td align=right>' . myfix($revmonth) . '</td>';
				}
        $value += $revmonth;
			}
		}
		if ($totalonleft == 0) { echo '<td align=right>' . myfix($value); }
		$total = $total + $value;
		$familysubtotal += $value;
		$familygroupsubtotal += $value;
		$departmentsubtotal += $value;
		$typesubtotal += $value;
    if ($annual == 1)
		{
			for ($y=1;$y<13;$y++)
			{
        $revmonth = 0;
        if (isset($revenuemonth[$id][$y])) { $revmonth += $revenuemonth[$id][$y]; }
				if (isset($revenuemonthreturn[$id][$y])) { $revmonth -= $revenuemonthreturn[$id][$y]; }
				$totalm[$y] = $totalm[$y] + $revmonth;
				$subtotalmonth[$y] += $revmonth; # does not work for $porderbyfam
				$familygroupsubtotalmonth[$y] += $revmonth;
				$departmentsubtotalmonth[$y] += $revmonth;
				#for multiline graph
				$graphdataA[$y] = $revmonth;
			}
			#for multiline graph
			$graphdatumA[$igraph] = $graphdataA; 
		}
		
		#for simple graph
		$graphnamesA[$igraph] = ''; if (isset($idname)) { $graphnamesA[$igraph] = $idname; }
		$graphvaluesA[$igraph] = $value;
		$igraph++;
	}
	#last familysubtotal
	if ($isreport_byproduct)
	{
		if ($porderbyfam) 
		{ 
			echo '<tr>';
			if ($totalonleft == 1)
			{ 
				echo '<td align=right><b>' . myfix($familysubtotal) . '</b></td>';
			}
			echo '<td><b> &nbsp; &nbsp; &nbsp; Sous-famille ' . $productfamilyA[$lastpfid] .  '</td>'; 
			if ($annual == 1)
			{ 
				for ($month=1;$month<=12;$month++) 
				{ 
					echo '<td align=right>' . myfix($subtotalmonth[$month]) . '</td>'; 
				}
			}
			if ($totalonleft == 0)
			{ 
				echo '<td align=right><b>' . myfix($familysubtotal) . '</b></td>';
			}			
			echo '</tr>';
			if ($porderbyfam)
			{
				echo '<tr>';
				if ($totalonleft == 1)
				{ 
					echo '<td align=right><b>' . myfix($familygroupsubtotal) . '</b></td>';
				}
				echo '<td><b> &nbsp; &nbsp; Famille ' . $productfamilygroupA[$pfgid] . '</td>'; 
				if ($annual == 1)
				{ 
					for ($month=1;$month<=12;$month++) 
					{ 
						echo '<td align=right>' . myfix($familygroupsubtotalmonth[$month]) . '</td>'; 
					}
				}				
				if ($totalonleft == 0)
				{ 
					echo '<td align=right><b>' . myfix($familygroupsubtotal) . '</b></td>';
				}				
				echo '</tr>';
			}
      echo '<tr>';
      if ($totalonleft == 1)
      { 
        echo '<td align=right><b>' . myfix($departmentsubtotal) . '</b></td>';
      }
      echo '<td><b> &nbsp; Département ' . $productdepartmentA[$pdpid] . '</td>'; 
      if ($annual == 1)
      { 
        for ($month=1;$month<=12;$month++) 
        { 
          echo '<td align=right>' . myfix($departmentsubtotalmonth[$month]) . '</td>'; 
        }
      }					
      if ($totalonleft == 0)
      { 
        echo '<td align=right><b>' . myfix($departmentsubtotal) . '</b></td>';
      }
      echo '</tr>';				
		}
		elseif ($porderbytype)
		{
			echo '<tr>';
			if ($totalonleft == 1)
			{ 
				echo '<td align=right><b>' . myfix($typesubtotal) . '</b></td>';
			}
			echo '<td><b>Total ' . $producttypeA[$lastptypeid] .  '</td><td></td>'; 
			if ($annual == 1)
			{ 
				for ($month=1;$month<=12;$month++) 
				{ 
					echo '<td align=right>' . myfix($subtotalmonth[$month]) . '</td>'; 
				}
			}
			if ($totalonleft == 0)
			{ 
				echo '<td align=right><b>' . myfix($typesubtotal) . '</b></td>';
			}			
			echo '</tr>';
		}
	}
	echo '<tr>';
	if ($totalonleft == 0)
	{ 
		echo '<td><b>TOTAL</td>';
		if ($isreport_byproduct && $porderbytype){ echo '<td></td>';}
		if ($annual == 1)
		{
			for ($y=1;$y<13;$y++)
			{
				echo '<td align=right><b>' . myfix($totalm[$y]) . '</td>';
			}
		}
	}
	if ($showclientemployee == 1 && ($isreport_bydetailedclientcategory || $isreport_bydetailedclientcategorygroup || $clientid > 0)) { echo '<td>&nbsp;</td>'; }
	echo '<td align=right><b>' . myfix($total) . '</td>';
	if ($totalonleft == 1)
	{
		echo '<td><b>TOTAL</td>';
		if ($isreport_byproduct && $porderbytype){ echo '<td></td>';}		
		if ($annual == 1)
		{
			for ($y=1;$y<13;$y++)
			{
				echo '<td align=right><b>' . myfix($totalm[$y]) . '</td>';
			}
		}		
	}
	echo '</tr></table>';

	#no graph for report type "total"
	$graph = $_POST['graph'] +0;
	if ($reporttype != 1)
	{
		switch($graph)
		{
			case 1:
				d_callmultilinegraph('Graphique',1,1,$graphnamesA,$graphxvaluesA,$graphdatumA); 
				break;      
			case 2:
				d_callsimplegraph('verticalbar','Graphique',$graphnamesA,$graphvaluesA);
				break;
			case 3:
				d_callsimplegraph('horizontalbar','Graphique',$graphnamesA,$graphvaluesA);
				break;
			case 4:
				d_callsimplegraph('piechartandlegend','Graphique',$graphnamesA,$graphvaluesA);
				break;

		}
	}
}
elseif ($numres > 0 && $byhour)
{
  # 2019 11 26 TODO totals etc etc
  echo '<table class="report"><thead><th>Heure';
  $ourdate = new DateTime($startdate);
  while ($ourdate->format('Y-m-d') <= $stopdate)
  {
    if (isset($revenue_total[$ourdate->format('Y-m-d')]) && $revenue_total[$ourdate->format('Y-m-d')] > 0)
    { echo '<th>'.datefix($ourdate->format('Y-m-d'),"short").'</th>'; }
    $ourdate->add(new DateInterval('P1D'));
  }
  echo '<th>Total</thead>';
  for ($i=0;$i<24;$i++)
  {
    if ($sum_hour[$i])
    {
      echo d_tr();
      echo d_td($i.':00',"right");
      $ourdate = new DateTime($startdate);
      while ($ourdate->format('Y-m-d') <= $stopdate)
      {
        if (isset($revenue_total[$ourdate->format('Y-m-d')]) && $revenue_total[$ourdate->format('Y-m-d')] > 0)
        {
          if (isset($revenue[$ourdate->format('Y-m-d')][$i]))
          {
            echo d_td($revenue[$ourdate->format('Y-m-d')][$i],"currency");
          }
          else { echo d_td(); }
        }
        $ourdate->add(new DateInterval('P1D'));
      }
      echo d_td($sum_hour[$i], 'currency');
    }
  }
  echo d_tr(1),d_td("Total");
  $ourdate = new DateTime($startdate);
  while ($ourdate->format('Y-m-d') <= $stopdate)
  {
    if (isset($revenue_total[$ourdate->format('Y-m-d')]) && $revenue_total[$ourdate->format('Y-m-d')] > 0)
    {
      if (isset($revenue_total[$ourdate->format('Y-m-d')]))
      {
        echo d_td($revenue_total[$ourdate->format('Y-m-d')],"currency");
      }
      else { echo d_td(); }
    }
    $ourdate->add(new DateInterval('P1D'));
  }
  echo d_td(array_sum($sum_hour),'currency');
  echo '</table>';
  echo '<p>Les avoirs ne sont pas déduis.</p>';
}
else
{
	echo d_trad('noresult');
}
?>