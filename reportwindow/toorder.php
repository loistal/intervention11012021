<?php

require('preload/unittype.php');

$PA['productfamilygroupid'] = 'int';
$PA['productdepartmentid'] = 'int';
$PA['temperatureid'] = 'int';
$PA['vexcpt'] = 'uint';
$PA['exarr'] = 'uint';
$PA['supplierid'] = 'uint';
$PA['exclude_supplier'] = 'uint';
$PA['whichavg'] = 'uint';
require('inc/readpost.php');
if ($exarr != 1) { $exarr = 0; }
$tvol = $tweight = 0; $vexcpt_alert = 0;
$exclcounter = 0; $excludeid = array();

showtitle_new('Produits à commander');

$currentdate = $_SESSION['ds_curdate'];
$currentday = substr($currentdate,8,2);
$currentmonth = substr($currentdate,5,2);
$currentyear = substr($currentdate,0,4);
$currentdate = d_builddate(1,$currentmonth,$currentyear);
$lastyeardate = d_builddate(1,$currentmonth,$currentyear-1);
$monthstart = d_builddate($currentday,$currentmonth-1,$currentyear);
$monthend = d_builddate($currentday,$currentmonth,$currentyear);

$query_prm = array();
$query = 'select unittypeid,client.clientid as supplierid,product.productid,productname,numberperunit,netweightlabel
,client.leadtime,currentstock,clientname as suppliername,weight,volume,cartonweight';
if ($whichavg == 1)
{ $query .= ',(if(avgmonthlyspec=0,avgmonthly,avgmonthlyspec)/numberperunit) as avgmonthly
,(currentstock/(if(avgmonthlyspec=0,avgmonthly,avgmonthlyspec)/numberperunit)) as coeff'; }
else { $query .= ',(avgmonthly/numberperunit) as avgmonthly
,(currentstock/(avgmonthly/numberperunit)) as coeff'; }
$query .= ' from product,productfamily,client,productfamilygroup
where product.supplierid=client.clientid and product.productfamilyid=productfamily.productfamilyid
and productfamily.productfamilygroupid=productfamilygroup.productfamilygroupid
and notforsale=0 and discontinued=0 and avgmonthly>0 and client.leadtime>0';
if ($productfamilygroupid != 0)
{ $query .= ' and productfamily.productfamilygroupid=?'; array_push($query_prm, $productfamilygroupid); }
if ($productdepartmentid > 0)
{ $query .= ' and productfamilygroup.productdepartmentid=?'; array_push($query_prm, $productdepartmentid); }
if ($temperatureid > -1) { $query .= ' and product.temperatureid=?'; array_push($query_prm, $temperatureid); }
if ($supplierid > 0)
{
  if ($exclude_supplier) { $query .= ' and product.supplierid<>' . $supplierid; }
  else { $query .= ' and product.supplierid=' . $supplierid; }
}
$query .= ' order by suppliername,coeff asc';
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;

echo '<table class="report">';
echo '<tr><td><b>Produit</td><td><b>Cond</td><td><b>Stock</td><td><b>Arrivages<td><b>Avg monthly<td><b>Lead months
<td><b>Coeff<td><b>Avg CBM monthly<td><b>Avg Weight monthly<td><b>Fournisseur</td></tr>';
for ($y=0; $y < $num_results_main; $y++)
{
  $row2 = $main_result[$y];
  $dmp = $unittype_dmpA[$row2['unittypeid']];
  $npu = $row2['numberperunit'];
  $showavgmonthly = ($row2['avgmonthly']+0) . ' ';
  
  # 2020 08 07 new algo for Wing Chong, products in KG, convert to m³
  if ($dmp == 1000)
  {
    /*
    il faut rectifier pour les produits au KILO, les infos sont dans les mauvaises colonnes.
    Avg monthly = afficher en carton
    Avg CBM monthly = afficher en m3
    Avg Weight monthly = afficher en kg
    aussi : colonne stock = afficher en kg
    
    use pid 3857 from PM WORLD (4607) to test
    */
    if ($row2['cartonweight'] == 0) { $row2['cartonweight'] = 999999999; }
    $row2['currentstock'] /= $dmp;
    $showcurrentstock = $row2['currentstock'] . ' ' . $unittypeA[$row2['unittypeid']];
    $show_as_weight = ($row2['currentstock'] /= $row2['cartonweight']) . ' KG';
    $row2['currentstock'] /= $row2['cartonweight'];
    $row2['currentstock'] *= $row2['volume'];
    $row2['currentstock'] = myround($row2['currentstock'],4)+0;
    $row2['coeff'] = $row2['currentstock'] / $row2['avgmonthly'];
    $showavgcbmmonthly = $showavgmonthly.'m³';
    $show_as_cartons = round($row2['avgmonthly'] / $row2['volume'],2) .' Cartons'; # TODO verify
  }
  else
  {
    $showavgmonthly .= $unittypeA[$row2['unittypeid']];
    $showcurrentstock = $row2['currentstock'] . ' ' . $unittypeA[$row2['unittypeid']];
  }

  ### check purchase and local purchase for incoming stock
  $incoming = 0;
  $query = 'select sum(amount) as amount from purchase,shipment where purchase.shipmentid=shipment.shipmentid and productid=? and shipmentstatus<>"Fini"';
  $query_prm = array($row2['productid']);
  require('inc/doquery.php');
  if ($query_result[0]['amount'] > 0)
  {
    $incoming += ($query_result[0]['amount'] / $npu);
  }
  ###

  $show_line = (($row2['currentstock']+$incoming) - ($row2['leadtime'] * $row2['avgmonthly']));

  if ($exarr == 0 || ($exarr == 1 && $incoming == 0))
  {
    $show_line = -1; ### show all products even if ordered
    if ($row2['coeff'] > $row2['leadtime'])
    {
      $excludeid[$exclcounter] = $row2['productid'] . ': ' . d_decode($row2['productname']) . ' ' . $row2['numberperunit'] . ' x ' . $row2['netweightlabel'] . ' &nbsp; [Coeff= ' . $row2['coeff'] . ' &nbsp; Lead= ' . $row2['leadtime'] . ']';
      $exclcounter++;
      $show_line = 1; # dont show
    }

    ### check for ventes exceptionnelles TODO fix for dmp=1000
    if ($vexcpt == 1)
    {
      $total = 0; $vexcpt_alert = 0;
      $query = 'select sum(quantity) as sales from invoicehistory,invoiceitemhistory
      where invoiceitemhistory.invoiceid=invoicehistory.invoiceid and isnotice=0 and proforma=1 and cancelledid=0 and confirmed=1
      and invoiceitemhistory.productid=? and accountingdate>=? and accountingdate<?';
      $query_prm = array($row2['productid'],$monthstart,$monthend);
      require('inc/doquery.php');
      for ($i=0;$i < $num_results; $i++)
      {
        $row = $query_result[$i];
        $total = $total + $row['sales'];
      }
      $total = $total / $row2['numberperunit'];
      $ourtestmonthly = $row2['avgmonthly'] * 1.5;
      if ($total > $ourtestmonthly) { $show_line = -1; $vexcpt_alert = 1; }
    }

    if ($show_line <= 0)
    {
      echo '<tr><td valign=top>' . $row2['productid'] . ': ' . d_decode($row2['productname']);
      if ($vexcpt_alert == 1) { echo ' <font color=red>AVE</font>'; }
      echo '<td align=right valign=top>' . $row2['numberperunit'] . ' x ' . $row2['netweightlabel'] . '
      <td align=right valign=top>' . $showcurrentstock . '<td valign=top><font color=blue>';
      if ($incoming > 0)
      {
        $query = 'select amount,arrivaldate from purchase,shipment
        where purchase.shipmentid=shipment.shipmentid and productid=? and shipmentstatus<>"Fini"';
        $query_prm = array($row2['productid']);
        require('inc/doquery.php');
        for ($x=0; $x < $num_results; $x++)
        {
          echo datefix2($query_result[$x]['arrivaldate']) . ' +' . $query_result[$x]['amount']/$npu . '<br>';
        }
      }
      if ($dmp == 1000)
      {
        echo '<td align=right valign=top>' . $show_as_cartons
        .'<td align=center valign=top>' . $row2['leadtime']
        .'<td align=right valign=top>' . round($row2['coeff'],2)
        .'<td align=right valign=top>' . $showavgcbmmonthly
        .'<td align=right valign=top>' . $show_as_weight;
      }
      else
      {
        $vol = $row2['avgmonthly']*$row2['volume'];
        $tvol += $vol;
        $weight = $row2['avgmonthly']*$row2['weight']/1000;
        $tweight += $weight;
        echo '<td align=right valign=top>' . $showavgmonthly . '<td align=center valign=top>' . $row2['leadtime'] . '
        <td align=right valign=top>' . round($row2['coeff'],2);
        echo '<td align=right valign=top>' . myfix($vol,2).'<td align=right valign=top>' . myfix($weight,2) . ' KG';
      }
      echo '<td valign=top>' . $row2['supplierid'] . ': ' . d_output(d_decode($row2['suppliername']));
    }
  }
}
echo '<tr><td><b>Total<td colspan=6><td align=right><b>',myfix($tvol,2),'<td align=right><b>',myfix($tweight,2),' KG<td></table>';
echo '<p><font color=red>Les produits DISCONTINUE, NON MIS A LA VENTE ou CO-PACK ne figurent pas dans ce rapport.</font></p>';
echo '<p><font color=red>Les produits des fournisseurs polynesiens et des fournisseurs avec un LEAD TIME ZERO ne figurent pas dans ce rapport.</font></p>';

echo '<p><font color=red>Les produits suivants ne figurent pas dans ce rapport, car leur Coeff > Lead months:</font></p>';
echo '<font color=red>';
for ($y=0; $y < $exclcounter; $y++)
{
  echo $excludeid[$y] . '<br>';
}
echo '<br>';

echo '<p><font color=red>Les produits suivants ne figurent pas dans ce rapport, car les ventes moyenne par mois sont ZERO:</font></p>';
echo '<font color=red>';
$query_prm = array();
$query = 'select productid,productname,numberperunit,netweightlabel from product,client where product.supplierid=client.clientid and notforsale=0 and discontinued=0 and avgmonthly=0 and client.leadtime>0';
if ($temperatureid > -1) { $query .= ' and product.temperatureid=?'; array_push($query_prm, $temperatureid); }
if ($supplierid > 0) { $query .= ' and product.supplierid=?'; array_push($query_prm, $supplierid); }
require('inc/doquery.php');
for ($y=0; $y < $num_results; $y++)
{
  $row = $query_result[$y];
  echo ' ' . $row['productid'] . ': ' . d_decode($row['productname']) . ' ' . $row['numberperunit'] . ' x ' . $row['netweightlabel'] . '<br>';
}

?>