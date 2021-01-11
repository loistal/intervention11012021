<?php
require('reportwindow/showproductactionsreport_cf.php');

$userid = (int) $_POST['userid'];
$competitorid = (int) $_POST['competitorid'];

#We find the client
$product = $_POST['product'];
require('inc/findproduct.php');


$employeeid = (int) $_POST['employeeid'];
$productactioncatid = (int) $_POST['productactioncatid'];

$showdeleted = (int) $_POST['showdeleted'];

#We retrieve the $_POST['startdate'] and then we filter his value with the datepickerresult
$datename = 'startdate';
require('inc/datepickerresult.php');

#We retrieve the $_POST['stopdate'] and then we filter his value with the datepickerresult
$datename = 'stopdate';
require('inc/datepickerresult.php');

if ($stopdate < $startdate)
{
  $stopdate = $startdate;
}

#We configure the title of the page
$title .= 'Évènements produit ' . d_trad('between', array(
    datefix2($startdate),
    datefix2($stopdate)
  ));

session_write_close();
showtitle($title);

print '<h2>' . $title . '</h2>';

require('inc/showparams.php');

#We build the initial query
$query = 'SELECT p.productname, pa.employeeid, pa.productid, pa.actiondate, pa.priceinfo, pa.imageid,
            pa.actionname, pa.employeeid, pa.productactioncatid, u.userid, u.initials, competitorid, productactiontagid, productactionfield1
            FROM productaction AS pa, product AS p, usertable AS u
            WHERE pa.actiondate >= ?
            AND pa.actiondate <= ?
            AND pa.userid = u.userid
            AND pa.productid = p.productid';

$query_prm = array();
$query_prm[] = $startdate;
$query_prm[] = $stopdate;

#We add filter for userid
if ($userid != -1)
{
  $query .= ' AND pa.userid = ?';
  $query_prm[] = $userid;
}

if ($competitorid > 0)
{
  $query .= ' AND pa.competitorid = ?';
  $query_prm[] = $competitorid;
}

if ($productid > 0)
{
  $query .= ' AND pa.productid = ?';
  $query_prm[] = $productid;
}

#We add filter for employeeid
if ($employeeid != -1)
{
  $query .= ' AND pa.employeeid = ?';
  $query_prm[] = $employeeid;
}

#We add filter for clientactioncatid
if ($productactioncatid != -1)
{
  $query .= ' AND pa.productactioncatid = ?';
  $query_prm[] = $productactioncatid;
}

#We add filter for showdeleted
if (isset($showdeleted) && !empty($showdeleted))
{
  if ($showdeleted == 1)
  {
    $query .= ' AND pa.deleted = 1';

  }
  elseif ($showdeleted == -1)
  {
    $query .= ' AND pa.deleted = 0';
  }
  elseif ($showdeleted == 2)
  {
    $query .= ' AND (pa.deleted = 0 OR pa.deleted = 1)';
  }
}


$query .= ' ORDER BY actiondate, productname';

/*
if ($_SESSION['ds_sqllimit'] > 0)
{
  $query .= ' LIMIT '.$_SESSION['ds_sqllimit'];
}
*/

require('inc/doquery.php');

$row = $query_result;
$num_rows = $num_results;


unset($query_result, $num_results);

require('inc/showreport.php');
?>