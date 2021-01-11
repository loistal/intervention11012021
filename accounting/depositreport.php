<?php

$PA['depositid'] = 'uint';
require('inc/readpost.php');

if ($depositid > 0)
{
  $query = 'select adjustmentdate from adjustmentgroup where closing=1 and deleted=0 order by adjustmentdate desc limit 1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results == 0) { $closingdate = '0000-00-00'; }
  else { $closingdate = $query_result[0]['adjustmentdate']; }
  
  $query = 'select depositid from deposit where depositdate>=? and depositid=?';
  $query_prm = array($closingdate, $depositid);
  require('inc/doquery.php');
  
  if ($num_results)
  {
    $query = 'select adjustmentgroupid from adjustmentgroup where integrated=3 and reference=?';
    $query_prm = array($depositid);
    require('inc/doquery.php');
    if ($num_results) { $adjustmentgroupid = $query_result[0]['adjustmentgroupid']; }
    else { $adjustmentgroupid = -1; }
    
    $query = 'update bankstatement set adjustmentgroupid=0,adjustmentid=0 where adjustmentgroupid=?';
    $query_prm = array($adjustmentgroupid);
    require('inc/doquery.php');
    
    $query = 'update adjustmentgroup set deleted=1 where integrated=3 and reference=?';
    $query_prm = array($depositid);
    require('inc/doquery.php');
    
    $query = 'update payment set depositid=0 where depositid=?';
    $query_prm = array($depositid);
    require('inc/doquery.php');
    
    $query = 'update deposit set employeeid=0,num_payments=0,paymenttypeid=0,depositbankaccountid=0,toacc=0,value=0
    where depositid=?';
    $query_prm = array($depositid);
    require('inc/doquery.php');
    
    echo '<p>Dépôt '.$depositid.' supprimé.</p><br>';
  }
}

echo '<h2>Rapport Dépôt</h2><form method="post" action="reportwindow.php" target=_blank><table>';
echo '<tr><td>De:<td>'; $datename = 'startdate'; $startdate = d_builddate(1,1,mb_substr($_SESSION['ds_curdate'],0,4)); require('inc/datepicker.php');
echo '<tr><td>A:<td>'; $datename = 'stopdate'; $stopdate = d_builddate(31,12,mb_substr($_SESSION['ds_curdate'],0,4)); require('inc/datepicker.php');
echo '<tr><td>Lister clients<td><input type=checkbox name="showclients" value=1>';
echo '<tr><td colspan="2" align="center"><input type=hidden name=report value="depositreport"><input type="submit" value="Valider"></td></tr></table></form>';

?><br><br>
<h2>Re-faire Dépôt</h2><form method="post" action="accounting.php"><table>
<tr><td>Numéro :<td><input autofocus type=number name="depositid" style="text-align: right">
<tr><td colspan="2" align="center">
<input type=hidden name=listme value=1><input type=hidden name="accountingmenu" value="<?php echo $accountingmenu; ?>">
<input type="submit" value="Valider"></td></tr></table></form>
