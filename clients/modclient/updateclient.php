<?php 

# Takes care of building and executing the client update query, and displays some feedback on
# the update.

$datename = 'creationdate';
$dp_allowempty = 1;
require('inc/datepickerresult.php');
if ($creationdate == NULL) { $creationdate = ''; }

if ($surcharge < 0 || $surcharge > 100) { $surcharge = 0; }

$datename = 'loyaltydate';
$dp_allowempty = 1;
require('inc/datepickerresult.php');
if ($loyaltydate == NULL) { $loyaltydate = ''; }

# Start building the query
$query_update = 'update client set ';

# Import the array to be populated with the fields
require('query.php');

if ($_SESSION['ds_purchaseaccess'] == 1) { array_unshift($query_vars, 'issupplier', 'leadtime'); }

$clientname_modified = 0;
if ($clientname  == '') 
{
    $err_clientname_empty = 1;
} 
else 
{
    $query = 'select clientid from client where clientname=? and clientid<>?';
    $query_prm = array($clientname, $clientid);
    require('inc/doquery.php');
    if ($num_results > 0) { $err_clientname_dup = 1; }
}

if (!$err_clientname_empty && !$err_clientname_dup) 
{
    array_push($query_vars, 'clientname');
    $clientname_modified = 1;
}

require('queryprm.php');

if ($_SESSION['ds_purchaseaccess'] == 1) { array_unshift($query_prm, $issupplier, $leadtime); }

if ($clientname_modified) { array_push($query_prm, $clientname); }

if ($_SESSION['ds_term_client_telephone'] != '') 
{ 
    array_push($query_vars, 'telephone');
    array_push($query_prm, $telephone);
}

if ($_SESSION['ds_term_client_cellphone'] != '') 
{
    array_push($query_vars, 'cellphone');
    array_push($query_prm, $cellphone);
}

if ($_SESSION['ds_term_client_telephone3'] != '') 
{
    array_push($query_vars, 'telephone3');
    array_push($query_prm, $telephone3);
}

if ($_SESSION['ds_term_client_telephone4'] != '') 
{
    array_push($query_vars, 'telephone4');
    array_push($query_prm, $telephone4);
}

if ($_SESSION['ds_term_client_email'] != '') 
{
    array_push($query_vars, 'email');
    array_push($query_prm, $email);
}

if ($_SESSION['ds_term_client_email2'] != '') 
{
    array_push($query_vars, 'email2');
    array_push($query_prm, $email2);
}

if ($_SESSION['ds_term_client_email3'] != '') 
{
    array_push($query_vars, 'email3');
    array_push($query_prm, $email3);
}

if ($_SESSION['ds_term_client_email4'] != '') 
{
    array_push($query_vars, 'email4');
    array_push($query_prm, $email4);
}

array_push($query_vars, 'batchemail');
array_push($query_prm, $batchemail);

# Combine all the fields to make the query string
foreach ($query_vars as $index => $query_var)
{
    $query_update .= $query_var . '=?';

    # add a comma, except after the last field
    $last_field_index = count($query_vars) - 1;
    if ($index != $last_field_index) {
        $query_update .= ',';
    }
}

array_push($query_prm, $clientid);
$query = $query_update . ' where clientid=?';
require('inc/doquery.php');

$was_modified = 0;
if ($num_results > 0) { $was_modified = 1; }

$showclientname = $clientid;

//client name shown only if no error
if (!$err_clientname_empty && !$err_clientname_dup) 
{
    $showclientname .= '(' . d_output(d_decode($clientname)) . ')';
}

# Give feedback
if ($was_modified == 1) 
{ 
    echo '<p>Client ' . $showclientname . ' modifié.</p>'; 
}

if ($err_clientname_dup) 
{
    echo '<p class="alert">Le client ' . d_output(d_decode($clientname)) . ' existe déjà.</p>';
}

if ($err_clientname_empty) 
{
    echo '<p class="alert">Le nom du client doit être renseigné.</span></p>';
}