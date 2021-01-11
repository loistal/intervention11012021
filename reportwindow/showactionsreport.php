<?php

# o boy refactor needed

require('reportwindow/showactionsreport_cf.php');

$PA['field1'] = '';
$PA['field1stop'] = '';
$PA['clientaction_caseid'] = 'int';
require('inc/readpost.php');

#We initialize all $_POST value
$userid = (int) $_POST['userid'];

#We find the client
$clientid = $_POST['client'];

if (!empty($clientid))
{
  if (is_string($clientid))
  {
    $clientidterm = $clientid;
  }

  require('inc/findclient.php');

  if ($num_clients > 1)
  {
    $query = 'SELECT clientid FROM client WHERE clientname LIKE ?';
    $query_prm = array('%' . $client . '%');

    require('inc/doquery.php');
    $listClients = array();

    foreach ($query_result as $result)
    {
      $listClients[] = $result['clientid'];
    }

    $formatClients = implode(',', $listClients);
    $clientid = '(' . $formatClients . ')';
  }
}

$employeeid = (int) $_POST['employeeid'];
if (isset($_POST['clientactioncatid'])) { $clientactioncatid = (int) $_POST['clientactioncatid']; }
else { $clientactioncatid = -1; }

$showdeleted = (int) $_POST['showdeleted'];

#We retrieve the $_POST['startdate'] and then we filter his value with the datepickerresult
$datename = 'startdate';
require('inc/datepickerresult.php');
if ($_SESSION['ds_restrict_sales_reports'] && $startdate < (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01') { $startdate = (mb_substr($_SESSION['ds_curdate'],0,4)-1).'-01-01'; }

#We retrieve the $_POST['stopdate'] and then we filter his value with the datepickerresult
$datename = 'stopdate';
require('inc/datepickerresult.php');

if ($stopdate < $startdate)
{
  $stopdate = $startdate;
}

#We configure the title of the page
$title = 'Évènements ' . d_trad('between', array(
    datefix2($startdate),
    datefix2($stopdate)
  ));

session_write_close();
showtitle($title);

print '<h2>' . $title . '</h2>';

require('inc/showparams.php');

#We build the initial query
$query = 'SELECT clientaction_caseid,c.clientname, c.telephone, ca.employeeid, ca.clientactionfield1, ca.clientid, ca.actiondate,
            ca.actionname, ca.employeeid, u.userid, u.initials, ca.imageid, originid, contact_typeid, clientactioncatid
            FROM clientaction AS ca, client AS c, usertable AS u
            WHERE ca.actiondate >= ?
            AND ca.actiondate <= ?
            AND ca.userid = u.userid
            AND ca.clientid = c.clientid';

$query_prm = array();
$query_prm[] = $startdate;
$query_prm[] = $stopdate;

#We add filter for userid
if ($userid != -1)
{
  $query .= ' AND ca.userid = ?';
  $query_prm[] = $userid;
}

#We add filter for clientid
if (!empty($client))
{
  if ($num_clients == 1)
  {
    $query .= ' AND ca.clientid = ?';
    $query_prm[] = $clientid;
  }
  else
  {
    if ($num_clients > 1)
    {
      $query .= ' AND ca.clientid  IN ' . $clientid;
    }
  }
}

#We add filter for employeeid
if ($employeeid != -1)
{
  $query .= ' AND ca.employeeid = ?';
  $query_prm[] = $employeeid;
}

#We add filter for clientactioncatid
if ($clientactioncatid != -1)
{
  $query .= ' AND ca.clientactioncatid = ?';
  $query_prm[] = $clientactioncatid;
}

#We add filter for allowed client
if ($_SESSION['ds_allowedclientlist'] != '')
{
  $query .= ' AND ca.clientid IN ' . $_SESSION['ds_allowedclientlist'];
}

#We add filter for type client
if ($_SESSION['ds_purchaseaccess'] != 1 && $_SESSION['ds_accountingaccess'] != 1)
{
  $query .= ' AND c.issupplier = 0';
}

#We add filter for field1
if (isset($field1stop) && !empty($field1stop) && isset($field1) && !empty($field1))
{
  $query .= ' AND ca.clientactionfield1 >= ? AND ca.clientactionfield1 <= ?';
  $query_prm[] = $field1;
  $query_prm[] = $field1stop;
}
elseif (isset($field1) && !empty($field1))
{
  $query .= ' AND ca.clientactionfield1 LIKE ?';
  $query_prm[] = '%' . $field1 . '%';
}

if ($_SESSION['ds_confirmonlyown'] == 1)
{
  $query .= ' AND (ca.userid = ?';

  $query_prm[] = $_SESSION['ds_userid'];

  if ($_SESSION['ds_myemployeeid'] > 0)
  {
    $query .= ' OR ca.employeeid = ?';
    $query_prm[] = $_SESSION['ds_myemployeeid'];
  }

  $query .= ')';
}

if ($clientaction_caseid >= 0)
{
  $query .= ' and clientaction_caseid=?'; array_push($query_prm, $clientaction_caseid);
}

#We add filter for showdeleted
if (isset($showdeleted) && !empty($showdeleted))
{
  if ($showdeleted == 1)
  {
    $query .= ' AND ca.deleted = 1';

  }
  elseif ($showdeleted == -1)
  {
    $query .= ' AND ca.deleted = 0';
  }
  elseif ($showdeleted == 2)
  {
    $query .= ' AND (ca.deleted = 0 OR ca.deleted = 1)';
  }
}


$query .= ' ORDER BY actiondate, clientname';

if ($_SESSION['ds_sqllimit'] > 0)
{
  $query .= ' LIMIT '.$_SESSION['ds_sqllimit'];
}

require('inc/doquery.php');

$row = $query_result;
$num_rows = $num_results;

unset($query_result, $num_results);

require('inc/showreport.php');
?>