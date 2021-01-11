<?php

require ('inc/standard.php');
require ('inc/top.php');

$PA['copydate'] = 'date';
$PA['confirm3'] = 'uint';
$PA['confirm4'] = 'uint';
$PA['invoice_list'] = '';
require('inc/readpost.php');

$invoice_list = unserialize(base64_decode($invoice_list));

if ($confirm3 && $confirm4 && $copydate>'2000-01-01' && is_array($invoice_list))
{
  echo '<h2>Copie des facture à la date du ',datefix($copydate,'short'),'</h2>';
  echo '<table class="report"><thead><th>Original<th>Copie</thead>';
  foreach($invoice_list as $invoice_client_list)
  {
    $invoiceidA = explode("|", $invoice_client_list);
    foreach($invoiceidA as $invoiceid)
    {
      $invoiceid = (int) $invoiceid;
      $query = 'select invoiceid from invoicehistory where invoiceid=?';
      $query_prm = array($invoiceid);
      require('inc/doquery.php');
      if ($num_results) { $invoiceid = $query_result[0]['invoiceid']; }
      else { $invoiceid = 0; }
      if ($invoiceid>0)
      {
        echo d_tr(),d_td_unfiltered('<a href="printwindow.php?report=showinvoice&invoiceid='.$invoiceid.'" target=_blank>'.$invoiceid.'</a>');
        $query = 'insert into invoice select * from invoicehistory where invoiceid=?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoice"';
        $query_prm = array();
        require('inc/doquery.php');
        $newinvoiceid = $query_insert_id;
        if ($newinvoiceid < 1) { echo '<p class=alert>critical error attributing invoiceid</p>'; exit; }
        $query = 'update invoice set invoiceid=?,cancelledid=0,confirmed=0,toacc=0,matchingid=0 where invoiceid=?';
        $query_prm = array($newinvoiceid,$invoiceid);
        require('inc/doquery.php');
        
        # set dates
        $query = 'select daystopay,paybydate,accountingdate from clientterm,client,invoice
        where invoice.clientid=client.clientid and client.clienttermid=clientterm.clienttermid
        and invoiceid=?';
        $query_prm = array($newinvoiceid);
        require('inc/doquery.php');
        $accountingdate = $copydate;
        $paybydate = date_create($accountingdate);
        date_add($paybydate, date_interval_create_from_date_string($query_result[0]['daystopay'].' days'));
        $paybydate = date_format($paybydate, 'Y-m-d');
        $query = 'update invoice set accountingdate=?,paybydate=?,deliverydate=?,invoicedate=curdate(),invoicetime=curtime()
        where invoiceid=?';
        $query_prm = array($accountingdate,$paybydate,$accountingdate,$newinvoiceid);
        require('inc/doquery.php');
        
        $query = 'insert into invoiceitem select * from invoiceitemhistory where invoiceid=?';
        $query_prm = array($invoiceid);
        require('inc/doquery.php');
        $query = 'select invoiceitemid from invoiceitem where invoiceid=?';
        $query_prm = array($invoiceid);
        require ('inc/doquery.php');
        $main_result = $query_result; $num_results_main = $num_results;
        for ($i=0; $i < $num_results_main; $i++)
        {
          $query = 'update seq set lastid = LAST_INSERT_ID(lastid + 1) where seqname="invoiceitem"';
          $query_prm = array();
          require('inc/doquery.php');
          $newinvoiceitemid = $query_insert_id;
          if ($newinvoiceitemid < 1) { echo '<p class=alert>critical error attributing invoiceitemid</p>'; exit; }
          $query = 'update invoiceitem set invoiceid=?,invoiceitemid=? where invoiceitemid=?';
          $query_prm = array($newinvoiceid,$newinvoiceitemid,$main_result[$i]['invoiceitemid']);
          require ('inc/doquery.php');
        }
        echo d_td_unfiltered('<a href="printwindow.php?report=showinvoice&invoiceid='.$newinvoiceid.'" target=_blank>'.$newinvoiceid.'</a>');
      }
    }
  }
}

require ('inc/bottom.php');

?>