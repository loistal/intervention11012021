<?php

### find clients

# mandatory input: $client OR $_POST['client']

# optional input: $dp_noblockedclients $dp_allowdeletedclients $dp_no_suppliers
if (!isset($dp_noblockedclients)) { $dp_noblockedclients = 0; }
if (!isset($dp_nosuspendedclients)) { $dp_nosuspendedclients = 0; }
if (!isset($dp_allowdeletedclients)) { $dp_allowdeletedclients = 0; }
if (!isset($dp_no_suppliers)) { $dp_no_suppliers = 0; }

# output:
$clientid = ''; unset($clientname); $num_clients = 0; unset($client_deleted); unset($client_clienttermid);

if (!isset($client) && isset($_POST['client'])) { $client = $_POST['client']; }
if (isset($client) && $client != "")
{
  $keeplooking = 1;

  if ($keeplooking == 1 && $client > 0)
  {
    $query = 'select clientid,clientname,deleted,clienttermid from client where clientid=?';
    if (isset($dp_no_suppliers) && $dp_no_suppliers == 1) { $query .= ' and issupplier=0'; }
    if ($dp_allowdeletedclients != 1) { $query = $query . ' and deleted=0'; }
    if ($dp_noblockedclients) { $query = $query . ' and blocked<>1'; }
    if ($dp_nosuspendedclients) { $query .= ' and blocked<>2'; }
    if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and clientid in ' . $_SESSION['ds_allowedclientlist']; }
    if ($_SESSION['ds_clientaccess'] != 1
    && $_SESSION['ds_purchaseaccess'] != 1
    && $_SESSION['ds_accountingaccess'] != 1) { $query .= ' and (isclient=1 or isemployee=1)'; }
    $query_prm = array($client);
    require ('inc/doquery.php');
    if ($num_results == 1)
    { 
      $clientid = $query_result[0]['clientid'];
      $clientname = d_decode($query_result[0]['clientname']);
      $client_deleted = $query_result[0]['deleted'];
      $client_clienttermid = $query_result[0]['clienttermid'];
    }
    if ($num_results > 0) { $keeplooking = 0; $num_clients = $num_results; }
  }

  if ($keeplooking == 1)
  {
    $query = 'select clientid,clientname,deleted,clienttermid from client where (clientcode=? or clientname=?)';
    if (isset($dp_no_suppliers) && $dp_no_suppliers == 1) { $query .= ' and issupplier=0'; }
    if ($dp_allowdeletedclients != 1) { $query = $query . ' and deleted=0'; }
    if ($dp_noblockedclients) { $query = $query . ' and blocked<>1'; }
    if ($dp_nosuspendedclients) { $query .= ' and blocked<>2'; }
    if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and clientid in ' . $_SESSION['ds_allowedclientlist']; }
    if ($_SESSION['ds_clientaccess'] != 1
    && $_SESSION['ds_purchaseaccess'] != 1
    && $_SESSION['ds_accountingaccess'] != 1) { $query .= ' and (isclient=1 or isemployee=1)'; }
    $query_prm = array($client,d_encode($client));
    require ('inc/doquery.php');
    if ($num_results == 1)
    {
      $clientid = $query_result[0]['clientid'];
      $clientname = d_decode($query_result[0]['clientname']);
      $client_deleted = $query_result[0]['deleted'];
      $client_clienttermid = $query_result[0]['clienttermid'];
    }
    if ($num_results > 0) { $keeplooking = 0; $num_clients = $num_results; }
  }

  if ($keeplooking == 1)
  {
    $query = 'select clientid,clientname,deleted,clienttermid from client where (lower(clientcode)=? or lower(clientname) LIKE ?)';
    if (isset($dp_no_suppliers) && $dp_no_suppliers == 1) { $query .= ' and issupplier=0'; }
    if ($dp_allowdeletedclients != 1) { $query = $query . ' and deleted=0'; }
    if ($dp_noblockedclients) { $query = $query . ' and blocked<>1'; }
    if ($dp_nosuspendedclients) { $query .= ' and blocked<>2'; }
    if ($_SESSION['ds_allowedclientlist'] != '') { $query = $query . ' and clientid in ' . $_SESSION['ds_allowedclientlist']; }
    if ($_SESSION['ds_clientaccess'] != 1
    && $_SESSION['ds_purchaseaccess'] != 1
    && $_SESSION['ds_accountingaccess'] != 1) { $query .= ' and (isclient=1 or isemployee=1)'; }
    $query = $query . ' order by lower(clientname)';
    $query_prm = array('%' .  mb_strtolower($client) . '%','%' .  d_encode(mb_strtolower($client)) . '%');
    require ('inc/doquery.php');
    if ($num_results == 1)
    { 
      $clientid = $query_result[0]['clientid'];
      $clientname = d_decode($query_result[0]['clientname']);
      $client_deleted = $query_result[0]['deleted'];
      $client_clienttermid = $query_result[0]['clienttermid'];
    }
    if ($num_results > 0) { $keeplooking = 0; $num_clients = $num_results; }
  }

}

unset($dp_noblockedclients,$dp_allowdeletedclients,$dp_no_suppliers);
# do NOT unset $client as it is expected to be persistent

?>