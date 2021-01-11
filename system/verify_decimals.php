<?php

if ($_POST['go'] == 1)
{
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  
  $query = 'select adjustmentgroupid from adjustmentgroup where deleted=0 and adjustmentdate>=? and adjustmentdate<=?';
  $query_prm = array($startdate, $stopdate);
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $agid = $main_result[$i]['adjustmentgroupid'];
    
    $query = 'select sum(value) as value from adjustment where debit=1 and adjustmentgroupid=?';
    $query_prm = array($agid);
    require('inc/doquery.php');
    $d_total = $query_result[0]['value']+0;

    $query = 'select sum(value) as value from adjustment where debit=0 and adjustmentgroupid=?';
    $query_prm = array($agid);
    require('inc/doquery.php');
    $c_total = $query_result[0]['value']+0;

    if ($d_total != $c_total)
    {
      echo '<p>Problem with écriture: ',$agid;
    }
  }
}

?>

<h2>Verify écritures</h2>
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
