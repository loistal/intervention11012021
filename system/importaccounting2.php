<?php

set_time_limit (600);

$separator = ';';

echo '<h2>Import accounting BG general TNI 2020 02 12</h2>';

if ($_POST['importme'] == 1)
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0; $linenr = 0;

  $query = 'insert into adjustmentgroup (userid,adjustmentdate,originaladjustmentdate,adjustmentcomment,reference) values (?,?,?,?,?)';
  $query_prm = array($_SESSION['ds_userid'],'2020-01-31','2020-01-31','IMPORT BG','');
  require('inc/doquery.php');
  $agid = $query_insert_id;

  #
  echo '<table class=report>';
  while ($line=fgets($fp))
  {
    $i++;
    $lineA = explode($separator, $line);
    
    echo '<tr>';
    foreach ($lineA as $value)
    {
      echo '<td>' . $value;
    }
    
    $acnumber = $lineA[0];
    $acname = $lineA[1];
    $debit = $lineA[2];
    $credit = $lineA[3];
    if ($debit > 0) { $value = $debit; $debit = 1; }
    else { $value = $credit; $debit = 0; }
    
    # find accountingnumberid (insert compte if not exists)
    $query = 'select accountingnumberid from accountingnumber where acnumber=?';
    $query_prm = array($acnumber);
    require('inc/doquery.php');
    if ($num_results == 0)
    {
      $query = 'insert into accountingnumber (acnumber,acname,accountinggroupid) values (?,?,?)';
      $query_prm = array($acnumber,$acnumber,(int)substr($acnumber,0,1));
      require('inc/doquery.php');
      $accountingnumberid = $query_insert_id;
    }
    else { $accountingnumberid = $query_result[0]['accountingnumberid']; }

    $query = 'insert into adjustment (adjustmentgroupid,value,debit,accountingnumberid,linenr) values (?,?,?,?,?)';
    $query_prm = array($agid,$value,$debit,$accountingnumberid,$linenr);
    require('inc/doquery.php');
    $adjustmentid = $query_insert_id;

    $linenr++;

  }
  echo '</table>';
}
else
{
  ?>
  <form enctype="multipart/form-data" method="post" action="system.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

?>