<?php #$_SESSION['debug']=1;

#$minid = (int) $_POST['minid'];
#$maxid = (int) $_POST['maxid'];

if ($_POST['checkme']) #$minid == 0 && 
{/*
  $datename = 'matchingdate'; require('inc/datepickerresult.php');
  $query = 'select min(matchingid) as minid from matching where date=?';
  $query_prm = array($matchingdate);
  require('inc/doquery.php');
  $minid = $query_result[0]['minid'];
  $query = 'select max(matchingid) as maxid from matching where date=?';
  $query_prm = array($matchingdate);
  require('inc/doquery.php');
  $maxid = $query_result[0]['maxid'];
  */
  #$minid = (int) $_POST['minid'];
  #$maxid = (int) $_POST['maxid'];
  require('inc/findclient.php');
  $datename = 'matchingdate'; require('inc/datepickerresult.php');
}
#if ($minid > 0 && $maxid > 0)
if ($clientid > 0)
{
  #echo '<b>Vérification lettrage '.datefix2($matchingdate).' ('.$minid.' à '.$maxid.')</b><br><br>';
  #for ($i=$minid;$i<=$maxid;$i++)
  $query = 'select matchingid from invoicehistory where matchingid>0 and clientid=? and accountingdate>=?
  union distinct
  select matchingid from payment where matchingid>0 and clientid=? and paymentdate>=?
  union distinct
  select matchingid from adjustment,adjustmentgroup
  where adjustment.adjustmentgroupid=adjustmentgroup.adjustmentgroupid
  and matchingid>0 and referenceid=? and adjustmentdate>=?';
  $query_prm = array($clientid,$matchingdate,$clientid,$matchingdate,$clientid,$matchingdate);
  require ('inc/doquery.php');
  $main_result = $query_result; $num_results_main = $num_results;
  for ($i=0; $i < $num_results_main; $i++)
  {
    $matchingid = $main_result[$i]['matchingid'];
    echo '<br>Checking matchingid : ',$matchingid;
    $mval = 0;
    $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and confirmed=1 and cancelledid=0 and isreturn=0';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval += $query_result[0]['value'];
    $query = 'select sum(invoiceprice) as value from invoicehistory where matchingid=? and confirmed=1 and cancelledid=0 and isreturn=1';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval -= $query_result[0]['value'];
    $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=1';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval += $query_result[0]['value'];
    $query = 'select sum(value) as value from payment where matchingid=? and reimbursement=0';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval -= $query_result[0]['value'];
    $query = 'select sum(value) as value from adjustment where matchingid=? and debit=1 and accountingnumberid=1';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval += $query_result[0]['value'];
    $query = 'select sum(value) as value from adjustment where matchingid=? and debit=0 and accountingnumberid=1';
    $query_prm = array($matchingid);
    require('inc/doquery.php');
    $mval -= $query_result[0]['value'];
    if ($mval != 0)
    {
      echo '<br>Delettrage matchingid ' . $matchingid;
      $query = 'update invoicehistory set matchingid=0 where matchingid=?';
      $query_prm = array($matchingid);
      require('inc/doquery.php');
      $query = 'update payment set matchingid=0 where matchingid=?';
      $query_prm = array($matchingid);
      require('inc/doquery.php');
      $query = 'update adjustment set matchingid=0 where matchingid=?';
      $query_prm = array($matchingid);
      require('inc/doquery.php');
    }
  }
}
else
{
  echo '<h2>Matching fix by client</h2>';
  /*
  $query = 'select max(matchingid) as maxid from matching';
  $query_prm = array();
  require('inc/doquery.php');
  echo 'max possible id = ',$query_result[0]['maxid'],'<br>';
  */
  echo '<form method="post" action="system.php">';
  require('inc/selectclient.php');
  #echo 'min matchingid: <input type=text name=minid><br>max matchingid: <input type=text name=maxid><br>';
  echo '<br>A partir de: '; $datename = 'matchingdate'; require('inc/datepicker.php');
  echo '<input type=hidden name="systemmenu" value="' . $systemmenu . '"><input type=hidden name=checkme value=1>
  <input type="submit" value="Verify"></form>';
}

?>