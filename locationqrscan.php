<?php

if (!isset($_SESSION['ds_userid']) || $_SESSION['ds_userid'] < 1) { header("refresh:0; url=index.php"); exit; }
$locationid = (int) $_GET['qr_locationid']; if (!$locationid) { exit; }

require ('inc/standard.php');
require('preload/qr_location.php');
require ('inc/top.php');
echo '<style>
body {
   transform: scale(5);
   transform-origin: 0 0;
}
</style><h2>';

$employeeid = $_SESSION['ds_myemployeeid'];

$query = 'select employeeid,employeename,employeefirstname,curdate() as date,curtime() as time from employee where employeeid=?';
$query_prm = array($employeeid);
require('inc/doquery.php');
if (!$num_results)
{
  echo 'Employé non identifié.';
}
else
{
  $employeeid = $query_result[0]['employeeid'];
  $date = $query_result[0]['date'];
  $time = $query_result[0]['time'];
  $name = $query_result[0]['employeename'];
  if ($query_result[0]['employeefirstname']) { $name .= ' ' . $query_result[0]['employeefirstname']; }
  
  echo d_output($qr_locationA[$locationid]) . '<br>';
  if ($qr_location_clientidA[$locationid])
  {
    require('preload/client.php'); # TODO optimize? perhaps even save in SESSION for reuse
    echo d_output($clientA[$qr_location_clientidA[$locationid]]) . '<br>';
  }
  echo d_output($name) . '<br>' . datefix($date) . '<br>' . substr($time,0,5);
  
  $query = 'insert into qr_location_event (qr_locationid,eventdate,eventtime,employeeid) values (?,?,?,?)';
  $query_prm = array($locationid,$date,$time,$employeeid);
  require('inc/doquery.php');
  $qr_location_eventid = $query_insert_id;

  echo '<br><br><form enctype="multipart/form-data" method="post" action="locationqrscan_event.php"><table>
  <tr><td>Infos:<td><input autofocus type="text" name="qr_location_text" size=20>
  <tr><td>Image:<td><input name="imagefile" type="file" size=40>
  <tr><td colspan=2><input type="submit" value="Valider">
  <input type=hidden name="qr_location_eventid" value="'.$qr_location_eventid.'">
  </table></form>';
}

echo '</h2>';
require ('inc/bottom.php');

?>