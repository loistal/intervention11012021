<?php

$PA['go'] = 'uint';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');

if ($go)
{
  require('preload/unittype.php');
  $query = 'select modifiedstockid,modifiedstock.productid,netchange,numberperunit,unittypeid
  from modifiedstock,product
  where modifiedstock.productid=product.productid
  and netchange<>0 and netvalue=0 and changedate>=? and changedate<=?';
  $query_prm = array($startdate, $stopdate);
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $msid = $main_result[$i]['modifiedstockid'];

    $query = 'select prev,origamount,totalcost,vat from purchasebatch where productid=?';
    $query_prm = array($main_result[$i]['productid']);
    $query .= ' order by arrivaldate desc,useby desc,purchasebatch.purchasebatchid desc limit 1';
    require('inc/doquery.php');
    if ($num_results)
    {
      $prev = $query_result[0]['prev']+0;
      $npu = $main_result[$i]['numberperunit'];
      $dmp = $unittype_dmpA[$main_result[$i]['unittypeid']];
      if ($dmp == 0) { $netvalue = 0; }
      else { $netvalue = $main_result[$i]['netchange'] * ($prev/$npu) / $dmp; }
    } else { $netvalue = 0; }#echo $prev,' ',$npu,' ',$dmp,'calculated ',$msid,' ',$netvalue,'<br>';
    if ($netvalue != 0)
    {
      $query = 'update modifiedstock set netvalue=? where modifiedstockid=?';
      $query_prm = array($netvalue, $msid);
      require('inc/doquery.php');
      if ($num_results) { echo 'updated id ',$msid,' ',$netvalue,'<br>'; }
    }
  }
}

?>

<h2>Determine Value of Modified Stock</h2>
<form method="post" action="system.php"><table><tr><td>Date:</td><td>
<?php
$datename = 'startdate'; require('inc/datepicker.php');
?>
</td></tr>
<tr><td>Date:</td><td>
<?php
$datename = 'stopdate'; require('inc/datepicker.php');
?>
</td></tr>
<tr><td colspan="2" align="center">
<input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>">
<input type=hidden name="go" value="1">
<input type="submit" value="Valider"></td></tr></table></form>
