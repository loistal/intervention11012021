<?php

require('preload/deliverytype.php');
require('preload/localvessel.php');
require('preload/companytransport.php');
require('preload/user.php');
require('preload/placement.php');
require('preload/employee.php');

$form = 0; # TODO remove all options for form > 0
$pallet_exit = 0;
if ($_SESSION['ds_use_warehouse']) { $pallet_exit = 1; } # always reserve/destock

$orderby = (int) $_SESSION['ds_deliveryorderby'];

$PA['invoicegroupid'] = 'int';
require('inc/readpost.php');

if ($invoicegroupid <= 0) { $invoicegroupid = -1; }

if ($invoicegroupid == -1)
{
  $titletext = 'APERCU AVANT VALIDATION';
  $preparationtext = '';
  $pallet_exit = 0; # do not reserve for previews
	$invoicelist = '(';
	foreach($_POST as $key => $value)
	{
		$param_name = 'invoice';
		if(substr($key, 0, strlen($param_name)) == $param_name)
		{
			$invoicelist .= $value . ',';
		}
	}
	$invoicelist = rtrim($invoicelist,',') . ')';
}
else
{
  $query = 'select * from invoicegroup where invoicegroupid=?';
  $query_prm = array($invoicegroupid);
  require('inc/doquery.php');
  $row = $query_result[0];
  $preparationtext = '';
  if (isset($employeeA[$row['employeeid']])) { $preparationtext = $employeeA[$row['employeeid']]; }
  if ($row['preparationtext'] != '') { $preparationtext .= ' '.$row['preparationtext']; }
  $curdate = $row['invoicegroupdate'];
  if ($curdate < '2017-10-15') { $pallet_exit = 0; } # do not reserve old preparations
  $curtime = $row['invoicegrouptime'];
  $ctid = $row['companytransportid'];
  $returns = $row['returns'];
  if ($returns > 0) { $pallet_exit = 0; } # do not reserve returns
  $titletext = datefix2($curdate) . ' ' . $curtime;
  if ($ctid > 0) { $titletext = $titletext . ' - ' . $companytransportA[$ctid]; }
  if ($returns == 1)
  {
    $titletext = $titletext . ' - Réception ' . $invoicegroupid;
  }
  else
  {
    $titletext = $titletext . ' - Livraison ' . $invoicegroupid;
  }
}
function showtitle($title) # TODO remove
{
  echo '<TITLE>'.d_output($_SESSION['ds_customname']).' ' . $title . '</TITLE>';
}
showtitle($titletext);

$query = 'select invoiceid,invoice.clientid,clientname,deliverydate,localvesselid,townname,islandname,outerisland,userid,deliverytypeid
from invoice,client,town,island where client.townid=town.townid and town.islandid=island.islandid and invoice.clientid=client.clientid';
if ($invoicegroupid == -1 && $invoicelist != '()') { $query .= ' and invoiceid IN '.$invoicelist; }
else { $query .= ' and invoicegroupid="' . $invoicegroupid . '"'; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 0 && $_SESSION['ds_deliveryaccessreturns'] == 1) { $query .= ' and isreturn=1'; }
if ($_SESSION['ds_deliveryaccessinvoices'] == 1 && $_SESSION['ds_deliveryaccessreturns'] == 0) { $query .= ' and isreturn=0'; }
if ($orderby == 1) { $query .= ' order by townname,clientname,invoiceid'; }
else { $query .= ' order by clientname,invoiceid'; }
$query_prm = array();
require('inc/doquery.php');
$main_result = $query_result; $num_results_main = $num_results;
$totalcartons = 0;
$totalunits = 0;
$totalweight = 0;
$totalvolume = 0;
if ($num_results)
{
echo '<h2>' . $titletext . ' &nbsp; <img src="barcode.php?size=40&text=' . $invoicegroupid . '" width=80; height=40></h2>';
echo '<table class="report">';
if ($preparationtext != '') { echo '<tr><td colspan=5><b>' . d_output($preparationtext) . '</b></td></tr>'; }
for ($i=0; $i < $num_results_main; $i++)
{
  $row = $main_result[$i];
  if ($i !=0 && $lastclient != $row['clientname']) { echo '<tr><td colspan=5>&nbsp;</td></tr>'; }
  $lastclient = $row['clientname'];
  $localvesselname = ''; #$localvesselA[$row['localvesselid']];
  if ($localvesselname != '') { $localvesselname = $localvesselname . ': '; }
  $firstline = ' par ' . d_output($user_initialsA[$row['userid']]);
  $firstline .= ' (' . d_output($deliverytypeA[$row['deliverytypeid']]) . ') ';
  echo '<tr><td colspan=3><b>F ' . $row['invoiceid'],$firstline . d_output($localvesselname) . d_output(d_decode($row['clientname'])) . ' - ' . $row['townname'] . ', ' . $row['islandname'] . '<td><b>' . datefix($row['deliverydate']);
  if ($pallet_exit != 1) { echo '<td><b>DLV'; }
  
	$query = 'select enteredpurchasebatchid,invoiceitemid,regroupnumber,quantity,unittypename,productname,product.productid as productid,suppliercode,weight,volume,numberperunit,displaymultiplier,netweightlabel,invoiceitem.currentpurchasebatchid
  from invoiceitem,product,unittype,regulationtype
  where product.regulationtypeid=regulationtype.regulationtypeid and invoiceitem.productid=product.productid and product.unittypeid=unittype.unittypeid
  and invoiceid=?';
	if ($_SESSION['ds_customname'] == 'Wing Chong') { $query = $query . ' and regroupnumber<=8'; } # TODO excludefromdelivery
	$query = $query . ' order by regroupnumber asc,invoiceitem.productid,invoiceitemid';
  $query_prm = array($row['invoiceid']);
  require('inc/doquery.php');
  $main_result2 = $query_result; $num_results_main2 = $num_results;
  for ($y=0; $y < $num_results_main2; $y++)
  {
    $row2 = $main_result2[$y];
    if ($_SESSION['ds_customname'] == 'Wing Chong') # another great request
    {
      if (!isset($productlistA))
      {
        $productlistA = array(817,2205,2336,3235,2743,2741,2742,882,345154,55,363,366,3378,367,4492,372,373,75,76,4562,701
        ,374,376,375,3379,5037,3567,815,4836,4835,2057,4396,4019,281,282,283,285,279,4614,4612,4613,4615,4616,4617
        ,54,3242,4043,392,3883,4466,4464,4465,280,698,4526,4525,4360,4361,3779,907,2169,4511,4524,866,3398,3400);
        sort($productlistA);
      }
      if (in_array($row2['productid'], $productlistA))
      {
        for ($x=0;$x<10;$x++)
        {
          $row2['productname'] = str_replace($x, '<b>'.$x.'</b>', $row2['productname']);
        }
      }
      elseif ($row2['productid'] == 3309) { $row2['productname'] = str_replace('Fraise', '<b>Fraise</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3310) { $row2['productname'] = str_replace('Abricot', '<b>Abricot</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4843) { $row2['productname'] = str_replace('Myrtilles', '<b>Myrtilles</b>', $row2['productname']); }
      elseif ($row2['productid'] == 870) { $row2['productname'] = str_replace('Blanc', '<b>Blanc</b>', $row2['productname']); }
      elseif ($row2['productid'] == 871) { $row2['productname'] = str_replace('Rouge', '<b>Rouge</b>', $row2['productname']); }
      elseif ($row2['productid'] == 315) { $row2['productname'] = str_replace('S/L', '<b>S/L</b>', $row2['productname']); }
      elseif ($row2['productid'] == 316) { $row2['productname'] = str_replace('A/L', '<b>A/L</b>', $row2['productname']); }
      elseif ($row2['productid'] == 2533)
      {
        $row2['productname'] = str_replace('GAL', '<b>GAL</b>', $row2['productname']);
        $row2['productname'] = str_replace('2.3', '<b>2.3</b>', $row2['productname']);
      }
      elseif ($row2['productid'] == 3282) { $row2['productname'] = str_replace('Glace 500', '<b>Glace 500</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3964) { $row2['productname'] = str_replace('BLEU 50', '<b>BLEU 50</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4519) { $row2['productname'] = str_replace('VERT 50', '<b>VERT 50</b>', $row2['productname']); }
      elseif ($row2['productid'] == 810) { $row2['productname'] = str_replace('Roux', '<b>Roux</b>', $row2['productname']); }
      elseif ($row2['productid'] == 1287) { $row2['productname'] = str_replace('Ancienne', '<b>Ancienne</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4297) { $row2['productname'] = str_replace('Lait', '<b>Lait</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3776) { $row2['productname'] = str_replace('Mayonnaise', '<b>Mayonnaise</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3777) { $row2['productname'] = str_replace('Miette', '<b>Miette</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3778) { $row2['productname'] = str_replace('Morceaux', '<b>Morceaux</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3905) { $row2['productname'] = str_replace('Naturel', '<b>Naturel</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4882) { $row2['productname'] = str_replace('Poche', '<b>Poche</b>', $row2['productname']); }
      elseif ($row2['productid'] == 2045) { $row2['productname'] = str_replace('Lemon', '<b>Lemon</b>', $row2['productname']); }
      elseif ($row2['productid'] == 2046) { $row2['productname'] = str_replace('Hoi Sin', '<b>Hoi Sin</b>', $row2['productname']); }
      elseif ($row2['productid'] == 573) { $row2['productname'] = str_replace('Plum', '<b>Plum</b>', $row2['productname']); }
      elseif ($row2['productid'] == 5051) { $row2['productname'] = str_replace('BLEU', '<b>BLEU</b>', $row2['productname']); }
      elseif ($row2['productid'] == 5052) { $row2['productname'] = str_replace('VERT', '<b>VERT</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4040) { $row2['productname'] = str_replace('Long', '<b>Long</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4750) { $row2['productname'] = str_replace('Gluant', '<b>Gluant</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4669) { $row2['productname'] = str_replace('Brun', '<b>Brun</b>', $row2['productname']); }
      elseif ($row2['productid'] == 5037) { $row2['productname'] = str_replace('Colza', '<b>Colza</b>', $row2['productname']); }
      elseif ($row2['productid'] == 5553) { $row2['productname'] = str_replace('Colza', '<b>Colza</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4492) { $row2['productname'] = str_replace('Olive', '<b>Olive</b>', $row2['productname']); }
      elseif ($row2['productid'] == 3810) { $row2['productname'] = str_replace('Tropicaux', '<b>Tropicaux</b>', $row2['productname']); }
      elseif ($row2['productid'] == 353) { $row2['productname'] = str_replace('Blanc', '<b>Blanc</b>', $row2['productname']); }
      elseif ($row2['productid'] == 355) { $row2['productname'] = str_replace('Rose', '<b>Rose</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4543) { $row2['productname'] = str_replace('Beef', '<b>Beef</b>', $row2['productname']); }
      elseif ($row2['productid'] == 4544) { $row2['productname'] = str_replace('Chicken', '<b>Chicken</b>', $row2['productname']); }
      elseif ($row2['productid'] == 5678) { $row2['productname'] = str_replace('150g', '<b><span class="alert">150g</span></b>', $row2['productname']); }
      elseif ($row2['productid'] == 5676) { $row2['productname'] = str_replace('GRATED', '<b><span class="alert">GRATED</span></b>', $row2['productname']); }
      elseif ($row2['productid'] == 5679) { $row2['productname'] = str_replace('PORTIONS', '<b><span class="alert">PORTIONS</span></b>', $row2['productname']); }
      elseif ($row2['productid'] == 5675) { $row2['productname'] = str_replace('SLICES', '<b><span class="alert">SLICES</span></b>', $row2['productname']); }
    }
    $epbid = $row2['enteredpurchasebatchid'];
    $invoiceitemid = $row2['invoiceitemid'];
    $weight = $row2['weight'];
    $volume = $row2['volume'];
    $numberperunit = $row2['numberperunit']; if ($numberperunit == 0) { $numberperunit = 1; }
    $amountcarton = floor($row2['quantity'] / $numberperunit);
    $amountunit = $row2['quantity'] - ($amountcarton * $numberperunit);
    if ($row2['displaymultiplier'] != 1) { $amountcarton = $amountcarton / $row2['displaymultiplier']; $amountunit = 0; }
    if ($amountcarton > 0)
    {
      $kilocartons = ""; $supplierbatchname = "";
      $totalcartons = $totalcartons + $amountcarton;
      $totalweight = $totalweight + ($amountcarton * $weight);
      $totalvolume = $totalvolume + ($amountcarton * $volume);
      if ($form)
      {
        if (isset($_POST['supplierbatchname'.$invoiceitemid]) && $_POST['supplierbatchname'.$invoiceitemid] != '')
        {
          # current input is supplierbatchname, need to find unique purchasebatchid
          $query = 'select purchasebatchid from purchasebatch where supplierbatchname=? and productid=? order by arrivaldate desc limit 1';
          $query_prm = array($_POST['supplierbatchname'.$invoiceitemid], $row2['productid']);
          require('inc/doquery.php');
          $pbid = $query_result[0]['purchasebatchid'];
          if ($num_results)
          {
            $query = 'update invoiceitem set enteredpurchasebatchid=? where invoiceitemid=?';
            $query_prm = array($pbid, $invoiceitemid);
            require('inc/doquery.php');
            $supplierbatchname = $_POST['supplierbatchname'.$invoiceitemid];
          }
        }
        elseif ($epbid > 0)
        {
          # TODO optimize
          $query = 'select supplierbatchname from purchasebatch where purchasebatchid=?';
          $query_prm = array($epbid);
          require('inc/doquery.php');
          $supplierbatchname = $query_result[0]['supplierbatchname'];
        }
      }
      echo '<tr>';
      $location_text = '';
      if ($pallet_exit)
      {
        ############################## pallet_exit
        # if already pallet_exit : display
        # else :
        # find location in warehouse to get stock from
        # if enough stock, deduct, else repeat looking for more stock
        # save operation to log pallet_exit table
        ##############################
        $query = 'select pallet_exit.placementid,exit_quantity,barcode,expiredate
        from pallet_exit,pallet,pallet_barcode
        where pallet_exit.palletid=pallet.palletid and pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
        and invoiceitemid=?';
        $query_prm = array($invoiceitemid);
        require('inc/doquery.php');
        if ($num_results)
        {
          for ($x=0; $x < $num_results; $x++)
          {
            if ($x > 0) { $location_text .= '<br>'; }
            #$location_text .= $placementA[$query_result[$x]['placementid']].' ('.$query_result[$x]['barcode'].')';
            #$location_text .= ' x '.$query_result[$x]['exit_quantity']/$numberperunit;
            $location_text .= ' ['.datefix($query_result[$x]['expiredate'],'short').']';
          }
        }
        else
        {
          $done = 0; $counter = 0;
          $quantity = (int) $row2['quantity']; /* in grammes for products in KG */
          while (!$done)
          {
            $query = 'select palletid,pallet.placementid,expiredate,quantity,barcode
            from pallet,pallet_barcode,placement
            where pallet.pallet_barcodeid=pallet_barcode.pallet_barcodeid
            and pallet.placementid=placement.placementid
            and transportzone=0 and productid=? and quantity>0 and pallet.deleted=0 and expiredate is not null
            order by expiredate,palletid limit 1';
            $query_prm = array($row2['productid']);
            require('inc/doquery.php');
            if ($num_results)
            {
              $palletid = $query_result[0]['palletid'];
              $placementid = $query_result[0]['placementid'];
              $barcode = $query_result[0]['barcode'];
              $expiredate = $query_result[0]['expiredate'];

              if ($palletid > 0)
              {
                if ($query_result[0]['quantity'] >= $quantity) # enough quantity on this pallet
                {
                  $pallet_quantity = $query_result[0]['quantity'] - $quantity;
                  $exit_quantity = $quantity;
                  $quantity = 0;
                }
                else # not enough quantity on this pallet
                {
                  $pallet_quantity = 0;
                  $exit_quantity = $query_result[0]['quantity'];
                  $quantity -= $query_result[0]['quantity'];
                }
                
                # deduct quantity
                $query = 'update pallet set quantity=?';
                $query_prm = array($pallet_quantity);
                #if ($pallet_quantity == 0)  { $query .= ',deleted=1'; } # perhaps just set quantity to 0 (needs testing/dev)
                $query .= ' where palletid=?';
                array_push($query_prm,$palletid);
                require('inc/doquery.php');
                
                # insert pallet_exit
                $query = 'insert into pallet_exit (exit_quantity,invoiceitemid,palletid,placementid,userid,pallet_exitdate,pallet_exittime) values (?,?,?,?,?,curdate(),curtime())';
                $query_prm = array($exit_quantity,$invoiceitemid,$palletid,$placementid,$_SESSION['ds_userid']);
                require('inc/doquery.php');
                
                if ($counter > 0) { $location_text .= '<br>'; }
                #$location_text .= $placementA[$placementid].' ('.$barcode.')';
                #$location_text .= ' x '.$exit_quantity/$numberperunit;
                $location_text .= ' ['.datefix($query_result[$x]['expiredate'],'short').']';
                
                if ($quantity <= 0) { $done = 1; }
              }
              else { $done = 1; }
              $counter++;
            }
            else { $done = 1; }
          }
        }
        ##############################
      }
      if ($form)
      {
        echo '<input type="text" STYLE="text-align:right" name="supplierbatchname',$invoiceitemid,'" value="' . $supplierbatchname . '" size=10>
        <td>' . $amountcarton . ' ' . $row2['unittypename'] . ' ' . $kilocartons . '</b></td><td>' . $row2['productname'];
      }
      else
      {
        #if ($y == 0) { echo $firstline; }
        echo '<td>' . $amountcarton . ' ' . $row2['unittypename'] . ' ' . $kilocartons . '</b></td><td>' . $row2['productname'];
      }
      if ($row2['suppliercode'] != "") { echo ' (' . $row2['suppliercode'] . ') '; }
      echo ' - ' . $row2['productid'];
      echo '<td>';
      if ($numberperunit>1) { echo $numberperunit . ' x '; }
      echo $row2['netweightlabel'];
      if ($pallet_exit != 1 || $location_text == ''
      || substr($location_text,0,7) == '<br> []'
      || $location_text == ' []')
      {
        $sellbydate = ''; # TODO different if pallet_exit
        if ($row2['currentpurchasebatchid'] > 0)
        {
          # TODO optimize
          $query = 'select useby from purchasebatch where purchasebatchid=?';
          $query_prm = array($row2['currentpurchasebatchid']);
          require('inc/doquery.php');
          $sellbydate = $query_result[0]['useby'];
        }
        $location_text = datefix2($sellbydate);
      }
    }
    echo '<td>', $location_text;
    if ($amountunit > 0)
    {
      $totalunits = $totalunits + $amountunit;
      $totalweight = $totalweight + ($amountunit * $weight / $numberperunit);
      echo '<tr><td>' . $amountunit . ' sous-unité</b></td><td>' . $row2['productname'] . '</td><td>' . $row2['netweightlabel'];
    }
  }
}
echo '<tr><td valign=top><b>' . $num_results_main . ' factures</b></td><td><b>';
if ($totalcartons > 0) { echo $totalcartons . ' colis<br>'; }
if ($totalunits > 0) { echo $totalunits . ' sous-unité<br>'; }
$totalweight = round($totalweight / 1000);
if ($totalweight > 0) { echo $totalweight . ' Kg<br>'; }
if ($totalvolume > 0) { echo $totalvolume . ' m<superscript>3</superscript>'; }
echo '</b></td><td colspan=3></td></tr></table>';
if ($form)
{
  echo '<input type=hidden name="report" value="deliverylist"><input type=hidden name="form" value=1>
<input type=hidden name="invoicegroupid" value="',$invoicegroupid,'">
 &nbsp; &nbsp; &nbsp; <input type="submit" value="Valider">
</form>
';
}
}
#if ($_SESSION['ds_use_warehouse'] && isset($_GET['form']) && $_GET['form'] == 2)
#{
#  echo '<meta http-equiv="refresh" content="0; url=delivery.php?reservedinvoicegroupid='.$invoicegroupid.'">';
#}
?>