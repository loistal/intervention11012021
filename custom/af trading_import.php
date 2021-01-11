<?php

# config
$separator = ',';

echo '<h2>Import Shopify</h2>';

if (isset($_POST['importme']))
{
  $fp = fopen ($_FILES['userfile']['tmp_name'],'r');
  $i = 0;
  echo '<table class=report>';
  while (($data = fgetcsv($fp, 1000, ",")) !== FALSE)
  {
    $lineA[$i] = $data;
    $num = count($data);
    echo '<tr>';
    for ($c=0; $c < $num; $c++)
    {
      echo '<td>',$data[$c];
      if ($i == 0) { echo '[',$c,']'; }
    }
    $i++;
  }
  echo '</table>';
  $num = $i;
  for ($i=1; $i < $num; $i++)
  {
    if ($lineA[$i][0] != $lineA[($i-1)][0] && $lineA[$i][2] == "paid")
    {
      $query = 'select invoiceid from invoice where reference=?
      union
      select invoiceid from invoicehistory where reference=?';
      $query_prm = array($lineA[$i][0],$lineA[$i][0]);
      require('inc/doquery.php');
      if ($num_results == 0)
      {
        if ($lineA[$i][24] == '') { $lineA[$i][24] = $lineA[$i][1]; }
        $query = 'select clientid from client where email=?';
        $query_prm = array($lineA[$i][1]);
        require('inc/doquery.php');
        if ($num_results)
        {
          $clientid = $query_result[0]['clientid'];
          $query = 'update client set clienttermid=?,email=?,clientname=?,address=?,postaladdress=?,postalcode=?,telephone=? where clientid=?';
          $query_prm = array(1,$lineA[$i][1],$lineA[$i][24],$lineA[$i][26],$lineA[$i][27],$lineA[$i][30].' '.$lineA[$i][29],$lineA[$i][33],$clientid);
          require('inc/doquery.php');
        }
        else
        {
          $query = 'insert into client (clienttermid,email,clientname,address,postaladdress,postalcode,telephone,townid) values (?,?,?,?,?,?,?,1)';
          $query_prm = array(1,$lineA[$i][1],$lineA[$i][24],$lineA[$i][26],$lineA[$i][27],$lineA[$i][30].' '.$lineA[$i][29],$lineA[$i][33]);
          require('inc/doquery.php');
          $clientid = $query_insert_id;
        }
        
        ############### invoice creation
        $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
        $query_prm = array();
        require('inc/doquery.php');

        $invoiceid = $query_insert_id;
        if ($invoiceid < 1) { echo '<p class=alert>critical error attributing invoiceid</p>'; exit; }
        
        $query = 'insert into invoice (invoiceid,matchingid,cancelledid,invoicegroupid,confirmed) values (?,0,0,0,0)';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        
        $done = 0;
        while (!$done)
        {
          # productid 2 without tax (total minus shipping)
          $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
          $query_prm = array();
          require('inc/doquery.php');
          $invoiceitemid = $query_insert_id;
          $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat
          ,itemcomment,linetaxcodeid) values (?,?,?,?,?,?,?,?,?,?)';
          $query_prm = array($invoiceitemid,$invoiceid,2,1,0,$lineA[$i][8],$lineA[$i][8],0,'',1);
          require('inc/doquery.php');
          
          # productid 1 (shipping, 13% tax, taxcodeid 5)
          $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
          $query_prm = array();
          require('inc/doquery.php');
          $invoiceitemid = $query_insert_id;
          $lineprice = $lineA[$i][9]/1.13;
          $invoicevat = round($lineA[$i][9]-$lineprice);
          $query = 'insert into invoiceitem (invoiceitemid,invoiceid,productid,quantity,givenrebate,basecartonprice,lineprice,linevat
          ,itemcomment,linetaxcodeid) values (?,?,?,?,?,?,?,?,?,?)';
          $query_prm = array($invoiceitemid,$invoiceid,1,1,0,$lineprice,$lineprice,$invoicevat,'',5);
          require('inc/doquery.php');
          $done = 1;
        }
        
        $accountingdate = substr($lineA[$i][3],0,10);
        if ($accountingdate == '0000-00-00' || $accountingdate == '') { $accountingdate = $_SESSION['ds_curdate']; }
        $query = 'update invoice set paybydate=?,accountingdate=?,deliverydate=?,invoicedate=curdate(),invoicetime=curtime(),clientid=?
        ,userid=' . $_SESSION['ds_userid'] . ',invoiceprice=?,invoicevat=?,reference=? where invoiceid=?';
        $query_prm = array($accountingdate,$accountingdate,$accountingdate,$clientid,
        $lineA[$i][11],$invoicevat,$lineA[$i][0],$invoiceid);
        require('inc/doquery.php');
        
        $query = 'insert into payment (forinvoiceid,clientid,paymentdate,paymenttime,value,paymenttypeid,userid,matchingid,reimbursement) values (?,?,?,CURTIME(),?,?,?,?,?)';
        $query_prm = array($invoiceid,$clientid,$accountingdate,$lineA[$i][11],4,$_SESSION['ds_userid'],0,0);
        require ('inc/doquery.php');
        
        echo '<br>Facture créée: ',$invoiceid;

        #################
      }
    }
  }
}
else
{
  ?>
  <form enctype="multipart/form-data" method="post" action="custom.php">
  <table>
  <tr><td>File:</td><td><input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="userfile" size=50></td></tr>
  <tr><td colspan="2" align="left"><input type=hidden name="importme" value="1"><input type=hidden name="custommenu" value="<?php echo $custommenu; ?>"><input type="submit" value="Import"></form></td></tr></table><?php
}

?>