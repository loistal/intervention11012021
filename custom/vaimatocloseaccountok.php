<?php

$query = 'select rentalid from vmt_rental';
$query = $query . ' where vmt_rental.clientid=? and deleted=0';
$query_prm = array($clientid);
require('inc/doquery.php');

if ($num_results > 0) { $customok = 0; }

?>