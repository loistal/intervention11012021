<?php

require('preload/employee.php');
require('preload/absence_reason.php');

$PA['absence_requestid'] = 'int';
$PA['saveme'] = 'int';
$PA['ampm'] = 'int';
$PA['absence_reasonid'] = 'int';
$PA['absence_request_comment'] = '';
$PA['startdate'] = 'date';
$PA['stopdate'] = 'date';
require('inc/readpost.php');

$send_email = 0;
if ($absence_requestid > 0)
{
  if ($saveme)
  {
    $accepted = 2;
    if ($_POST['action'] == 'Accepter') { $accepted = 1; } # French language hardcode
    $query = 'update absence_request set startdate=?,stopdate=?,ampm=?,absence_request_comment=?,absence_reasonid=?,accepted=? where absence_requestid=?';
    $query_prm = array($startdate,$stopdate,$ampm,$absence_request_comment,$absence_reasonid,$accepted,$absence_requestid);
    require('inc/doquery.php');

    $send_email = 1;
  }
  
  $query = 'select * from absence_request where absence_requestid=?';
  $query_prm = array($absence_requestid);
  require('inc/doquery.php');
  $employeeid = $query_result[0]['employeeid'];
  $startdate = $query_result[0]['startdate'];
  $stopdate = $query_result[0]['stopdate'];
  $ampm = $query_result[0]['ampm'];
  $absence_reasonid = $query_result[0]['absence_reasonid'];
  $absence_request_comment = $query_result[0]['absence_request_comment'];
  $accepted = $query_result[0]['accepted'];
  
  if ($send_email == 1)
  {
    $query = 'select employeeemail from employee where employeeid=?';
    $query_prm = array($employeeid);
    require('inc/doquery.php');
    $employeeemail = $query_result[0]['employeeemail'];
    $subject = 'Demande d\'absence pour ' . d_output($employeeA[$employeeid]) . ': ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
    $replytoaddress = 'contact@temtahiti.com';
    $messagetext = '<div style="font-weight: bold;">';
    $messagetext .= $subject;
    $messagetext .= '</div>';
    if ($ampm == 1) { $messagetext .= '<p>Matin</p>'; }
    if ($ampm == 2) { $messagetext .= '<p>Après-midi</p>'; }
    $messagetext .= '<p>Motif : ' . $absence_reasonA[$absence_reasonid] . '</p>';
    if ($absence_request_comment != '') { $messagetext .= '<p>' . d_output($employeeA[$employeeid]) . ' a écrit : ' . $absence_request_comment . '</p>'; }
    if ($accepted == 1) { $messagetext .= '<p>Votre demande d\'absence a été acceptée.</p>'; }
    elseif ($accepted == 2) { $messagetext .= '<p>Votre demande d\'absence a été refusée.</p>'; }
    if (d_sendemail($employeeemail,$replytoaddress,$subject,$messagetext)) { echo '<p>E-mail envoyé à l\'employé(e).</p>'; }; #
  }
  
  echo '<h2>Demande d\'absence pour ', d_output($employeeA[$employeeid]), ': ', datefix2($startdate), ' à ', datefix2($stopdate), '</h2>';
  echo '<form method="post" action="hr.php"><table><tr>';
  echo '<tr><td>' . d_trad('date:') . '</td><td colspan=2>';
  $datename = 'startdate'; $dp_selecteddate = $startdate; require('inc/datepicker.php');
  echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
  $datename = 'stopdate'; $dp_selecteddate = $stopdate; require('inc/datepicker.php');

  echo '<tr><td colspan=2 align=center><select name="ampm">
  <option value=0>Journée(s) Entière(s)</option>
  <option value=1'; if ($ampm == 1) { echo ' selected'; } echo '>Matin</option>
  <option value=2'; if ($ampm == 2) { echo ' selected'; } echo '>Après-midi</option>
  </select>';

  $dp_description = 'Motif'; $dp_selectedid = $absence_reasonid;
  $dp_itemname = 'absence_reason'; require('inc/selectitem.php'); echo ' <span class="alert">Obligatoire</span>'; #$dp_noblank = 1; 
  
  ?><tr><td colspan=3><input autofocus type=text name="absence_request_comment" value="<?php echo d_input($absence_request_comment); ?>" size=60><?php
  
  if ($accepted == 1) { echo '<tr><td colspan=2><span class="alert"><b>Accepté</b></p>'; }
  elseif ($accepted == 2) { echo '<tr><td colspan=2><span class="alert"><b>Rejeté</b></p>'; }
  
  if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager'] == $employee_teamidA[$employeeid])
  {
    echo '<tr><td colspan=2><input type=submit name="action" value="Accepter"> <input type=submit name="action" value="Rejeter"></p>
    <input type=hidden name="saveme" value=1><input type=hidden name="absence_requestid" value=',$absence_requestid,'><input type=hidden name="hrmenu" value="', $hrmenu, '">';
  }
  ?>
  </table></form><?php
}

?>