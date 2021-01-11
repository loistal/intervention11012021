<?php

$PA['month'] = 'uint';
$PA['year'] = 'uint';
$PA['confirm'] = 'uint';
require('inc/readpost.php');

if ($year < 2000 || $month < 1 || $month > 12 || !$_SESSION['ds_ishrsuperuser']) { exit; }

if ($confirm)
{
  $query = 'update payslip set status=1 where year(payslipdate)=? and month(payslipdate)=?';
  $query_prm = array($year,$month);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<p>Verouillage Paie '.d_trad('shortmonth'.$month).' '.$year.' effectué</p>';
  }
}
else
{
  echo '<h2>Verouillage Paie '.d_trad('shortmonth'.$month).' '.$year.'</h2>';
  ?>
  <p class=alert>Êtes-vous sûr?</p>
  <form method="post" action="hr.php">
  <table>
  <td colspan=5 align=center>
  <input type=hidden name="hrmenu" value="payroll_all_confirm"> 
  <input type=hidden name="month" value="<?php echo $month; ?>">
  <input type=hidden name="year" value="<?php echo $year; ?>">
  <input type=hidden name="confirm" value=1>
  <input type="submit" value="<?php echo d_trad('validate');?>">
  </td>
  </tr>
  </table>
  </form>
  <?php
}
?>