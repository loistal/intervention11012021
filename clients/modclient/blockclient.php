<?php 

# If there is a change in the account status of a client, log this change in the clientaction table
# Input : $clientid $blocked (whether the client is blocked) 
# Output: no output

$query = 'select blocked from client where clientid=?';
$query_prm = array($clientid);
require('inc/doquery.php');
$old_blocked = $query_result[0]['blocked'];

# If we change blocked, then register this in clientaction
if ($old_blocked != $blocked) {
    if ($blocked == 0) { $temp_text = 'Client Débloqué'; } 
    elseif ($blocked == 1) { $temp_text = 'Client Interdit'; } 
    elseif ($blocked == 2) { $temp_text = 'Client Suspendu'; }

    $query = 'insert into clientaction ';
    $query .= '(clientid,actiondate,employeeid,clientactioncatid,actionname,userid) ';
    $query .= 'values (?,?,?,?,?,?)';
    $query_prm = array(
        $clientid, $_SESSION['ds_curdate'], $_SESSION['ds_myemployeeid'], 0,
        $temp_text, $_SESSION['ds_userid']
    );
    require('inc/doquery.php');
}