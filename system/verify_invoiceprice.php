<?php

$PA['minid'] = 'uint';
$PA['maxid'] = 'uint';
require('inc/readpost.php');

if ($minid && $maxid && $maxid >= $minid)
{
  for ($i=$minid; $i <= $maxid; $i++)
  {
    $query = 'select invoiceprice from invoicehistory where confirmed=1 and cancelledid=0 and invoiceid=?';
    $query_prm = array($i);
    require('inc/doquery.php');
    if ($num_results && $query_result[0]['invoiceprice'] != 0)
    {
      $invoiceprice = $query_result[0]['invoiceprice'];
      $query = 'select sum(lineprice+linevat) as linepricesum from invoiceitemhistory where invoiceid=?';
      $query_prm = array($i);
      require('inc/doquery.php');
      if ($query_result[0]['linepricesum'] != $invoiceprice)
      {
        echo '<br>Checking invoiceid ',$i,' <span class="alert">ERROR</span>';
        echo ' &nbsp; invoiceprice= ',$invoiceprice,' sum of lines= ',$query_result[0]['linepricesum'];
      }
    }
  }
}
else
{
  echo '<h2>Verify invoiceprice (total of invoice = sum of lines)</h2>';
  echo '<form method="post" action="system.php">';
  echo 'From invoiceid: <input type=text name=minid><br>To invoiceid: <input type=text name=maxid>';
  echo '<input type=hidden name="systemmenu" value="' . $systemmenu . '">
  <input type="submit" value="Verify"></form>';
}

?>