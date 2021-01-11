<?php

$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
$PA['qr_locationid'] = 'int';
$PA['employeeid'] = 'int';
require('inc/readpost.php');

require('preload/employee.php');
require('preload/client.php');

$title = 'Rapport Sites QR';
showtitle_new($title);
echo d_table('report');
/* TODO
echo '<p>Numéro Employeur : '.$ssn;
echo '<p>No Tahiti : '.d_output($idtahiti);
echo '<p>Periode : ' . d_trad('month2_'.$month) . ' ' . $year;
*/
echo '<thead><th>Date<th>Heure<th>Employé(e)<th>Lieu QR<th>Client<th>Infos<th>Image</thead>';

$query = 'select employeeid,eventdate,eventtime,clientid,qr_locationname,qr_location_text,imageid
from qr_location_event,qr_location
where qr_location_event.qr_locationid=qr_location.qr_locationid
and eventdate>=? and eventdate<=?';
$query_prm = array($startdate,$stopdate);
if ($qr_locationid > 0) { $query .= ' and qr_location.qr_locationid=?'; array_push($query_prm, $qr_locationid); }
if ($employeeid > 0) { $query .= ' and qr_location_event.employeeid=?'; array_push($query_prm, $employeeid); }
require('inc/doquery.php');
for ($i=0; $i < $num_results; $i++)
{
  echo d_tr();
  echo d_td($query_result[$i]['eventdate'],'date');
  echo d_td($query_result[$i]['eventtime']);
  echo d_td($employeeA[$query_result[$i]['employeeid']]);
  echo d_td($query_result[$i]['qr_locationname']);
  echo d_td($clientA[$query_result[$i]['clientid']]);
  echo d_td($query_result[$i]['qr_location_text']);
  if ($query_result[$i]['imageid'] > 0)
  {
    echo d_td_unfiltered('<img src="viewimage.php?image_id=' . $query_result[$i]['imageid'] . '">');
  }
  else { echo d_td(); }
}

echo d_table_end();

?>