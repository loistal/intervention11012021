<?php

require('preload/unittype.php');

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

$product = $_POST['product']; require('inc/findproduct.php');
if ($productid < 1) { echo d_trad('notfoundproduct',$product); exit; }
$year = $_POST['year']; $startyear = $year - 2; $middleyear = $year - 1;
$employeeid = (int) $_POST['employeeid'];
$showreturns = 1;
$showplannedclients = 0;
$quantitytype = (int) $_POST['quantitytype'];

$PA['compare3years'] = 'int';
$PA['orderby'] = 'uint';
require('inc/readpost.php');

if ($employeeid > 0 && $_POST['withplanningclients'] == 1)
{
  $showplannedclients = 1;
  $query = 'select planning_client.clientid from planning,planning_employee,planning_client where planning_employee.planningid=planning.planningid and planning_client.planningid=planning.planningid
  and planning.deleted=0 and planning_employee.employeeid=?';
  $query_prm = array($employeeid);
  require('inc/doquery.php');
  for ($i=0;$i<=$num_results;$i++)
  {
    $resultcount++;
    $clientlistA[$resultcount] = $query_result[$i]['clientid'];
  }
  $clientlistA = array_filter(array_unique($clientlistA));
  sort($clientlistA);
}

$query = 'select productname,numberperunit,netweightlabel,unittypeid from product where productid=?';
$query_prm = array($productid);
require('inc/doquery.php');
$row = $query_result[0];
$npu = $row['numberperunit'];
$dmp = $unittype_dmpA[$row['unittypeid']];

$title = d_trad('productsale:',$productid ) . d_output(d_decode($row['productname'])) . ' ' . d_showpackaging($row['numberperunit'], d_output($row['netweightlabel']));
showtitle ($title);
echo '<h2>' . $title . '</h2>';
if ($compare3years) { echo '<p>Années&nbsp;' . $startyear . ' à ' . $year . '</p>'; }
else { echo '<p>' . d_trad('year:') . '&nbsp;' . $year . '</p>'; }
if ($employeeid > 0)
{
  require('preload/employee.php');
  echo '<p>'. d_trad('employee:') . $employeeA[$employeeid] . '</p>';
}

$maxmonth = 1;

$query = 'select regulationzoneid,client.clientid,clientname,islandname,month(accountingdate) as month,year(accountingdate) as year';
if ($quantitytype == 1) { $query .= ',sum(lineprice) as quantity'; }
else { $query .= ',sum(quantity) as quantity'; }
$query .= ' from invoiceitemhistory,invoicehistory,client,town,island
where client.townid=town.townid and town.islandid=island.islandid and invoiceitemhistory.invoiceid=invoicehistory.invoiceid
and invoicehistory.clientid=client.clientid
and cancelledid=0 and isreturn=0 and invoiceitemhistory.productid=?';
if ($compare3years) { $query .= ' and year(accountingdate)>=? and year(accountingdate)<=?'; $query_prm = array($productid, $startyear, $year); }
else { $query .= ' and year(accountingdate)=?'; $query_prm = array($productid, $year); }
if ($employeeid >= 0) { $query .= ' and invoicehistory.employeeid=?'; array_push($query_prm, $employeeid); }
$query .= ' group by invoicehistory.clientid,';
if ($compare3years) { $query .= 'year'; }
else { $query .= 'month'; }
$query .= ' order by regulationzoneid,customorder,islandname,clientid';
require('inc/doquery.php');
$num_clients = 0; 
for ($i=0;$i < $num_results; $i++)
{
  $row = $query_result[$i];

  if ($i == 0 || $row['clientid'] != $clientid[$num_clients]) { $num_clients++; }
  if ($compare3years) { $month = $row['year']; }
  else { $month = $row['month']; if ($month > $maxmonth) { $maxmonth = $month; } }
  $clientid[$num_clients] = $row['clientid'];
  ###
  $num_clientsA[$row['clientid']] = $num_clients;
  ###
  $clientname[$num_clients] = d_decode($row['clientname']);
  $islandname[$num_clients] = $row['islandname'];
  $realislandname[$num_clients] = d_output($row['islandname']);
  if ($islandname[$num_clients] != "Tahiti" && $islandname[$num_clients] != "Moorea" && $islandname[$num_clients] != "ISLV") { $islandname[$num_clients] = "Autres"; }
  if ($row['regulationzoneid'] >= 3 && $row['regulationzoneid'] <= 7) { $islandname[$num_clients] = "ISLV"; }
  if ($row['regulationzoneid'] >= 8 && $row['regulationzoneid'] <= 12) { $islandname[$num_clients] = "Tuamotu"; }
  if ($row['regulationzoneid'] == 13) { $islandname[$num_clients] = "Gambier"; }
  if ($row['regulationzoneid'] == 14) { $islandname[$num_clients] = "Australes"; }
  if ($row['regulationzoneid'] == 15) { $islandname[$num_clients] = "SCI"; }
  if ($row['regulationzoneid'] == 16) { $islandname[$num_clients] = "Marquises"; }
  $quantity[$num_clients][$month] = ($row['quantity'] / $npu) / $dmp;
}

# returns
$query = str_replace('isreturn=0', 'isreturn=1', $query);
require('inc/doquery.php');
$num_clients_return = 0; 
for ($i=0;$i < $num_results; $i++)
{
  $row = $query_result[$i];
  if (isset($num_clientsA[$row['clientid']])) # TODO show clients with only returns
  {
    $num_clients_return = $num_clientsA[$row['clientid']];
    if ($compare3years) { $month = $row['year']; }
    else { $month = $row['month']; if ($month > $maxmonth) { $maxmonth = $month; } }
    if (!isset($quantity[$num_clients_return][$month])) { $quantity[$num_clients_return][$month] = 0; }
    $quantity[$num_clients_return][$month] -= ($row['quantity'] / $npu) / $dmp;
  }
}

if ($orderby == 0)
{
  #echo 'TODO';
  for ($i=1;$i <= $num_clients; $i++)
  {
    #echo $clientid[$i], ' ', array_sum($quantity[$i]),'<br>';
    $clienttotalA[$clientid[$i]] = array_sum($quantity[$i]);
  }
  arsort($clienttotalA);
  $i = 0;
  foreach ($clienttotalA as $cid => $kladd)
  {
    $i++;
    $clientorderA[$i] = $cid;
  }
  #var_dump($clientorderA);
}

if ($compare3years) { $maxmonth = 3; }

$total = 0; $subtotal = 0; $subtotala = 0; $totala = 0;
for ($month=1;$month <= 12;$month++)
{
  $addtotal[$month] = 0;
  $aggrtotal[$month] = 0;
}
echo '<table class="report">';
echo '<thead><th>'. d_trad('client') . '</th><th>' . d_trad('island') . '</th>';
if ($compare3years)
{
  echo '<th>',$startyear,'<th>',$middleyear;
  if ($compare3years) { echo '<th>%'; }
  echo '<th>',$year;
  if ($compare3years) { echo '<th>%'; }
}
else
{
  for($i=1;$i<=12;$i++)
  {
    echo '<th>' . d_trad('shortmonth' . $i) . '</th>';
  }
}
echo '<th>' . d_trad('total') . '</th><th>' . d_trad('average') . '</th></thead>';

$lastisland = '';
for ($i=1;$i <= $num_clients; $i++)
{
  $x = $i;
  if ($orderby == 0)
  {
    $x = array_search($clientorderA[$i], $clientid); #  # $clientid[$num_clients] = $row['clientid'];
  }
  if ($islandname[$x] != $lastisland && $i != 1)
  {
    if ($orderby) { echo '<tr class=trtablecolorsub><td><b>' . d_trad('subtotal') . '</b></td><td><b>' . $lastisland . '</b></td>'; }
    if ($compare3years)
    {
      for ($month=$startyear;$month <= $year;$month++)
      {
        echo '<td align=right><b>' . myfix($addtotal[$month]) . '</b></td>';
        if ($compare3years && $month > $startyear)
        {
          echo '<td align=right>';
          if ($lastsubtotal > 0) { echo '<font size=-1>',myround((($addtotal[$month] / $lastsubtotal) - 1) * 100),'%</font>'; }
        }
        if (!isset($aggrtotal[$month])) { $aggrtotal[$month] = 0; }
        $aggrtotal[$month] +=
        $addtotal[$month];
        $lastsubtotal = $addtotal[$month]; $addtotal[$month] = 0;
      }
    }
    else
    {
      for ($month=1;$month <= 12;$month++)
      {
        if ($orderby) { echo '<td align=right><b>' . myfix($addtotal[$month]) . '</b></td>'; }
        $aggrtotal[$month] = $aggrtotal[$month] + $addtotal[$month];
        $addtotal[$month] = 0;
      }
    }
    if ($orderby) { echo '<td align=right><b>' . myfix($subtotal) . '</b></td><td align=right><b>' . myfix($subtotal/$maxmonth) . '</b></td></tr>'; }
    $totala = $totala + $subtotala;
    $subtotal = 0; $subtotala = 0;
  }
  echo d_tr();
  echo '<td>' . $clientid[$x] . ': ' . $clientname[$x] . '</td><td>' . $realislandname[$x] . '</td>';
  $clienttotal = 0;
  if ($compare3years)
  {
    for ($month=$startyear;$month <= $year;$month++)
    {
      if (!isset($quantity[$x][$month])) { $quantity[$x][$month] = 0; }
      $clienttotal += $quantity[$x][$month];
      if (!isset( $addtotal[$month])) { $addtotal[$month] = 0; }
      $addtotal[$month] += $quantity[$x][$month];
      echo '<td align=right>' . myfix($quantity[$x][$month]) . '</td>';
      if ($compare3years && $month > $startyear)
      {
        echo '<td align=right>';
        if ($quantity[$x][$month-1] > 0) { echo '<font size=-1>',myround((($quantity[$x][$month] / $quantity[$x][$month-1]) - 1) * 100),'%</font>'; }
      }
    }
  }
  else
  {
    for ($month=1;$month <= 12;$month++)
    {
      if (isset($quantity[$x][$month]))
      {
        $clienttotal = $clienttotal + $quantity[$x][$month];
        $addtotal[$month] = $addtotal[$month] + $quantity[$x][$month];
        echo '<td align=right>' . myfix($quantity[$x][$month]) . '</td>';
      }
      else { echo '<td>'; }
    }
  }
  echo '<td align=right>' . myfix($clienttotal) . '</td><td align=right>' . myfix($clienttotal/$maxmonth) . '</td></tr>';
  $total = $total + $clienttotal;
  $subtotal = $subtotal + $clienttotal;
  if (isset($clienttotalR))
  {
    $total = $total + $clienttotalR;
    $subtotal = $subtotal + $clienttotalR;
  }
  $lastisland = $islandname[$x];
}
if ($orderby) { echo '<tr class=trtablecolorsub><td><b>' . d_trad('subtotal') . '</b></td><td><b>' . d_output($lastisland) . '</b></td>'; }
if ($compare3years)
{
  for ($month=$startyear;$month <= $year;$month++)
  {
    echo '<td align=right><b>' . myfix($addtotal[$month]) . '</b></td>';
    if ($compare3years && $month > $startyear)
    {
      echo '<td align=right>';
      if ($lastsubtotal > 0) { echo '<font size=-1>',myround((($addtotal[$month] / $lastsubtotal) - 1) * 100),'%</font>'; }
    }
    $aggrtotal[$month] = $aggrtotal[$month] + $addtotal[$month];
  }
}
else
{
  for ($month=1;$month <= 12;$month++)
  {
    if ($orderby) { echo '<td align=right><b>' . myfix($addtotal[$month]) . '</b></td>'; }
    $aggrtotal[$month] = $aggrtotal[$month] + $addtotal[$month];
  }
}
if ($orderby) { echo '<td align=right><b>' . myfix($subtotal) . '</b></td><td align=right><b>' . myfix($subtotal/$maxmonth) . '</b></td></tr>'; }
$totala = $totala + $subtotala;
echo '<tr class=trtablecolorsub><td colspan=2><b>' . d_trad('total') . '<b></td>';
if ($compare3years)
{
  for ($month=$startyear;$month <= $year;$month++)
  {
    echo '<td align=right><b>' . myfix($aggrtotal[$month]) . '</b></td>';
    if ($compare3years && $month > $startyear)
    {
      echo '<td align=right>';
      if ($aggrtotal[$month-1] > 0) { echo '<font size=-1>',myround((($aggrtotal[$month] / $aggrtotal[$month-1]) - 1) * 100),'%</font>'; }
    }
  }
}
else
{
  for ($month=1;$month <= 12;$month++)
  {
    echo '<td align=right><b>' . myfix($aggrtotal[$month]) . '</b></td>';
  }
}
echo '<td align=right><b>' . myfix($total) . '</b></td><td align=right><b>' . myfix($total/$maxmonth) . '</b></td></tr>';

if ($showplannedclients)
{
  $missingclientsA = array_diff($clientlistA, $clientid);
  $missingclients = '(';
  foreach ($missingclientsA as $kladd)
  {
    $missingclients .= $kladd . ',';
  }
  $missingclients = rtrim($missingclients,',') . ')';
  if ($missingclients == '()') { $missingclients = '(-1)'; }
  $query = 'select client.clientid,clientname,islandname from client,town,island where client.townid=town.townid and town.islandid=island.islandid and clientid in ' . $missingclients;
  $query .= ' order by regulationzoneid,customorder,islandname,clientid';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results) { echo '<tr><td align=middle><b>' . d_trad('inplanningwithoutsalesclient') . '</b></td><td><b>' . d_trad('island') . '</b></td><td colspan=14>&nbsp;</td><b></tr>'; }
  for ($i=0;$i < $num_results; $i++)
  {
    $islandname[$i] = $query_result[$i]['islandname'];
    ### copy from above
    if ($islandname[$i] != "Tahiti" && $islandname[$i] != "Moorea" && $islandname[$i] != "ISLV") { $islandname[$i] = "Autres"; }
    if ($row['regulationzoneid'] >= 3 && $row['regulationzoneid'] <= 7) { $islandname[$i] = "ISLV"; }
    if ($row['regulationzoneid'] >= 8 && $row['regulationzoneid'] <= 12) { $islandname[$i] = "Tuamotu"; }
    if ($row['regulationzoneid'] == 13) { $islandname[$i] = "Gambier"; }
    if ($row['regulationzoneid'] == 14) { $islandname[$i] = "Australes"; }
    if ($row['regulationzoneid'] == 15) { $islandname[$i] = "SCI"; }
    if ($row['regulationzoneid'] == 16) { $islandname[$i] = "Marquises"; }
    ### end copy
    echo d_tr();
    echo '<td>' . myfix($query_result[$i]['clientid']) . ': ' . d_output(d_decode($query_result[$i]['clientname'])) . '</td><td>' . d_output($islandname[$i]) . '</td>';
    echo '<td colspan=14>&nbsp;</td></tr>';
  }
}
echo '</table>';

?>