<?php

$invoiceid = $_POST['invoiceid']+0;

if ($invoiceid > 0 && $_POST['redeliver']==1)
{
  $datename = 'deliverydate';
  require('inc/datepickerresult.php');
  $redelivercomment = $_POST['redelivercomment'];
  
  if($redelivercomment != '')
  {
    $deliverytypeid = $_POST['deliverytypeid']+0;
    $query = 'insert into redeliverlog (userid,redeliverdate,redelivertime,deliverydate,redelivercomment,invoiceid) values (?,curdate(),curtime(),?,?,?)';
    $query_prm = array($_SESSION['ds_userid'],$deliverydate,$redelivercomment,$invoiceid);
    require('inc/doquery.php');
    $query = 'update invoicehistory set invoicegroupid=0,deliverydate=?,deliverytypeid=? where invoiceid=?';
    $query_prm = array($deliverydate,$deliverytypeid,$invoiceid);
    require('inc/doquery.php');
    echo '<p>' . d_trad('invoicetoberedelivered',$invoiceid) . '</p>';
    $invoiceid = 0;
  }
  else
  {
    echo '<p class=alert>' . d_trad('mandatorycause') . '</p>';
  }
}

if ($invoiceid > 0)
{
  $query = 'select invoicehistory.clientid,clientname,invoicegroupdate,invoicegrouptime,preparationtext,deliverytypeid from invoicehistory,client,invoicegroup where invoicehistory.clientid=client.clientid and invoicehistory.invoicegroupid=invoicegroup.invoicegroupid and isreturn=0 and cancelledid=0 and invoiceid=?';
  $query_prm = array($invoiceid);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<h2>' . d_trad('redeliverinvoicenb',$invoiceid) . '</h2><form method="post" action="delivery.php"><table cellspacing=3 cellpadding=3>';
    echo '<tr><td>' . d_trad('client:') . '</td><td>' . $query_result[0]['clientname'] . '('.$query_result[0]['clientid'].')</td></tr>';
    echo '<tr><td>' . d_trad('deliveredon:') . '</td><td>' . datefix($query_result[0]['invoicegroupdate']) .' ' . mb_substr($query_result[0]['invoicegrouptime'],5);

    echo '</td></tr>';
    
    echo '<tr><td>' . d_trad('infos:') . '</td><td>' . $query_result[0]['preparationtext'] . '</td></tr>';
    echo '<tr><td colspan=2>&nbsp;</td></tr>';
    echo '<tr><td>' . d_trad('redeliveron:') . '</td><td>';
    $datename = 'deliverydate';
    require('inc/datepicker.php');
    if ($_SESSION['ds_usedelivery'] > 0)
    {
      ### NEW deliverytype
      echo ' &nbsp; ';
      $dp_itemname = 'deliverytype'; $dp_selectedid = $query_result[0]['deliverytypeid']; $dp_noblank = 1;
      require('inc/selectitem.php');
      ###
    }
    echo '</td></tr>';
    echo '<tr><td>' . d_trad('cause:') . '</td><td><input autofocus type="text" STYLE="text-align:right" name="redelivercomment" size=40></td></tr>';
    echo '<tr><td colspan="2" align="center"><input type=hidden name="deliverymenu" value="' . $deliverymenu . '"><input type="submit" value="' . d_trad('validate') . '"></td></tr>';
    echo '</table><input type=hidden name="redeliver" value="1"><input type=hidden name="invoiceid" value="' . $invoiceid . '"></form>';
  }
  else 
  { 
    echo '<p>' . d_trad('noresult') . '</p>';  
  }
}

if ($invoiceid == 0)
{
  echo '<h2>' . d_trad('redeliverinvoice') . '</h2><form method="post" action="delivery.php"><table>';
  echo '<tr><td>' . d_trad('invoicenumber:') . '</td><td><input autofocus type="text" STYLE="text-align:right" name="invoiceid" size=10></td></tr>';
  echo '<tr><td colspan="2" align="center"><input type=hidden name="deliverymenu" value="' . $deliverymenu . '"><input type="submit" value="' . d_trad('validate') . '"></td></tr>';
  echo '</table></form>';
}


?>