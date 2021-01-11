<?php

if ($_SESSION['ds_menustyle'] == 5)
{
  ?><main role="main"><div class="container" id="main-container"><?php
}
else { ?><br><?php }

if ($_SESSION['ds_myemployeeid'] > 0)
{
  require('reportwindow/calendar.php');
}

if ($_SESSION['ds_userrepresentsclientid'] < 1)
{
  ### https://gist.github.com/stemar/8287074
  function mb_substr_replace($string, $replacement, $start, $length=NULL) {
    if (is_array($string)) {
        $num = count($string);
        // $replacement
        $replacement = is_array($replacement) ? array_slice($replacement, 0, $num) : array_pad(array($replacement), $num, $replacement);
        // $start
        if (is_array($start)) {
            $start = array_slice($start, 0, $num);
            foreach ($start as $key => $value)
                $start[$key] = is_int($value) ? $value : 0;
        }
        else {
            $start = array_pad(array($start), $num, $start);
        }
        // $length
        if (!isset($length)) {
            $length = array_fill(0, $num, 0);
        }
        elseif (is_array($length)) {
            $length = array_slice($length, 0, $num);
            foreach ($length as $key => $value)
                $length[$key] = isset($value) ? (is_int($value) ? $value : $num) : 0;
        }
        else {
            $length = array_pad(array($length), $num, $length);
        }
        // Recursive call
        return array_map(__FUNCTION__, $string, $replacement, $start, $length);
    }
    preg_match_all('/./us', (string)$string, $smatches);
    preg_match_all('/./us', (string)$replacement, $rmatches);
    if ($length === NULL) $length = mb_strlen($string);
    array_splice($smatches[0], $start, $length, $rmatches[0]);
    return join($smatches[0]);
  }
  ###
  
  $query = 'select frontpage from globalvariables where primaryunique=1';
  $query_prm = array();
  require('inc/doquery.php');
  if ($query_result[0]['frontpage'] != "")
  {
    $frontpage = $query_result[0]['frontpage'];
    $done = 0; $first = 1;
    while (!$done)
    {
      $begin = mb_strpos($frontpage, 'image_begin');
      $end = mb_strpos($frontpage, 'image_end');
      if (isset($begin) && isset($end) && $end > $begin)
      {
        $productid = mb_substr($frontpage, $begin+11, $end-$begin-11);
        #echo '<br>',$begin,' ',$end,' ',$productid;
        $frontpage = mb_substr_replace($frontpage, '<a href="reportwindow.php?report=productimages&productid='.$productid.'" target=_blank>'.$productid.'</a>', $begin, $end-$begin+10);
        $first = 0;
      }
      else
      {
        $done = 1;
      }
    }
    echo '<div class="myblock" style="width:90%;margin:auto;">';
    echo nl2br($frontpage);
    echo '</div><br>';
  }
}

if ($_SESSION['ds_allowedclientlist'] != '')
{
  require('preload/clientterm.php');
  $query = 'select clientid,clientname,clienttermid,blocked from client where clientid in ' . $_SESSION['ds_allowedclientlist'] . ' order by clientname';
  $query_prm = array();
  require('inc/doquery.php');
  if ($num_results > 0) {
    echo '<div class="myblock" style="width:95%;margin:auto;"><h2>Planning visites clients</h2><table class=report><thead><th>Numéro client</th><th>Client</th><th>Paiement</th><th>Statut</th></thead><tbody>';
    for ($i = 0; $i < $num_results; $i++) {
      echo d_tr();
      echo d_td_old($query_result[$i]['clientid'], 1);
      echo d_td_old(d_decode($query_result[$i]['clientname']), 0, 0, 0, 'reportwindow.php?report=showclient&client=' . $query_result[$i]['clientid']);
      echo d_td_old($clienttermA[$query_result[$i]['clienttermid']]);
      $status = '&nbsp;';
      if ($query_result[$i]['blocked'] == 1) { $status = 'Interdit'; }
      elseif ($query_result[$i]['blocked'] == 2) { $status = 'Suspendu'; }
      echo d_td_old($status);
    }
    echo '</tbody></table></div><br>';
  }
}

if ($_SESSION['ds_accountingaccess'] && $_SESSION['ds_accountingalert'])
{
  $day = mb_substr($_SESSION['ds_curdate'], 8, 2) + 0;
  $month = mb_substr($_SESSION['ds_curdate'], 5, 2) + 0;
  $year = mb_substr($_SESSION['ds_curdate'], 0, 4) + 0;
  if ($month == 0) {
    $month = 12;
    $year = $year - 1;
  }
  $onemonthago = d_builddate($day, $month, $year);
  $query = 'select count(invoiceid) as numresults from invoicehistory where confirmed=1 and matchingid=0 and paybydate<?';
  $query_prm = array($onemonthago);
  require('inc/doquery.php');
  $num_results = $query_result[0]['numresults'];
  if ($num_results > 0)
  {
    echo '<div class="myblock" style="width:90%;margin:auto;">';
    echo '<center><form class="loginbox" method="post" action="reportwindow.php" target="_blank">';
    echo '<center><span class="alert">Vous avez ' . $num_results . ' factures non payées à vérifier. ';
    echo '</span></center>';
    echo '<input type=hidden name="startdate" value="' . $_SESSION['ds_startyear'] . '-01-01"><input type=hidden name="stopdate" value="' . $onemonthago . '">';
    echo '<input type=hidden name="accountingalert" value=1><input type=hidden name="report" value="invoicereport2">';
    echo '<input type="submit" value="Consulter"></form></center>';
    echo '</div><br>';
  }
  
  $query = 'select count(paymentid) as numresults from payment where value>0 and depositid=0 and reimbursement=0 and (paymenttypeid=2 or paymenttypeid=3)';
  $query_prm = array($onemonthago);
  require('inc/doquery.php');
  $num_results = $query_result[0]['numresults'];
  if ($num_results > 0)
  {
    echo '<div class="myblock" style="width:90%;margin:auto;">';
    echo '<center><form class="loginbox" method="post" action="accounting.php">';
    echo '<center><span class="alert">Vous avez ' . $num_results . ' chèque(s)/virement(s) non déposé(s). ';
    echo '</span></center>';
    echo '<input type=hidden name="accountingmenu" value="deposit">';
    echo '<input type="submit" value="Consulter"></form></center>';
    echo '</div><br>';
  }
}

$query = 'select localvesselid,reference,isnotice,proforma,isreturn,invoiceid,accountingdate,clientname,invoice.clientid as clientid,invoiceprice,initials
from invoice,client,usertable
where invoice.userid=usertable.userid and invoice.clientid=client.clientid
and cancelledid=0 and confirmed=0 and invoice.userid=? order by invoiceid';
$query_prm = array($_SESSION['ds_userid']);
require('inc/doquery.php');
if ($num_results)
{
  echo '<div class="myblock" style="width:90%;margin:auto;">';
  echo '<center><form class="loginbox" method="post" action="sales.php">';
  echo '<center><span class="alert">Vous avez ' . $num_results . ' facture';
  if ($num_results > 0) { echo 's'; }
  echo ' à confirmer / annuler. ';
  echo '</span></center>';
  echo '<input type=hidden name="myuserid" value="' . $_SESSION['ds_userid'] . '">
  <input type=hidden name="proforma" value=-1><input type=hidden name="invoicetagid" value=-1>
  <input type=hidden name="startdate" value=""><input type=hidden name="stopdate" value="">
  <input type=hidden name="accountingalert" value=1><input type=hidden name="salesmenu" value="confirm">
  <input type="submit" value="Consulter"></form></center>
  </div><br>';
}

if ($_SESSION['ds_myemployeeid'] > 0)
{
  $date = date_create();
  $date->modify('this week'); # http://stackoverflow.com/questions/8541466/getting-first-last-date-of-the-week
  $startdate = date_format($date,'Y-m-d');
  $date->modify('this week +6 days');
  $stopdate = date_format($date,'Y-m-d');
  $query = 'select employeeid,badgedate,badgetime
  from badgelog
  where deleted=0 and badgetime is not null and badgedate>=? and badgedate<=? and employeeid=?
  order by badgedate,badgetime';
  $query_prm = array($startdate,$stopdate,$_SESSION['ds_myemployeeid']);
  require('inc/doquery.php');
  if ($num_results)
  {
    echo '<div class="myblock" style="width:90%;margin:auto;">';
    echo '<table class="report"><thead><th colspan=7>Vos pointages de la semaine</thead>';
    for ($i=0; $i < $num_results; $i++)
    {
      if ($i == 0 || $query_result[$i]['badgedate'] != $query_result[($i-1)]['badgedate'])
      {
        echo '<tr><td>', datefix($query_result[$i]['badgedate']);
      }
      echo '<td>', substr($query_result[$i]['badgetime'],0,5);
    }
    echo '</table></div><br>';
  }
}

require('inc/copyright.php');

?>

