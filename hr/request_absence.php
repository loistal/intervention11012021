<?php

require('preload/employee.php');
require('preload/team.php');
require('preload/absence_reason.php');

$PA['employeeid'] = 'int';
$PA['saveme'] = 'int';
$PA['absence_reasonid'] = 'int';
$PA['ampm'] = 'int';
$PA['absence_request_comment'] = '';
require('inc/readpost.php');

#if ($employeeid > 0 && !$_SESSION['ds_ishrsuperuser']) { $employeeid = 0; }
if ($employeeid == 0 && $_SESSION['ds_myemployeeid'] > 0) { $employeeid = $_SESSION['ds_myemployeeid']; }

echo '<h2>Demande d\'absence - ',$employeeA[$employeeid],'</h2>';

if ($saveme && $employeeid)
{
  # TODO check permissions (teamlead, superuser)
  $datename = 'startdate'; require('inc/datepickerresult.php');
  $datename = 'stopdate'; require('inc/datepickerresult.php');
  if ($stopdate < $startdate) { $stopdate = $startdate; }
  $query = 'insert into absence_request (employeeid, startdate, stopdate, absence_reasonid, ampm, absence_request_comment) values (?,?,?,?,?,?)';
  $query_prm = array($employeeid, $startdate, $stopdate, $absence_reasonid, $ampm, $absence_request_comment);
  require('inc/doquery.php');
  $absence_requestid = $query_insert_id;
  echo '<p>Demande d\'absence ajouté.</p>';
  
  if ($_SESSION['ds_ishrsuperuser'] == 1 || ($_SESSION['ds_unionrep'] == 1 && $absence_reasonid == 11)) # union rep does not need confirmation for this type of absence
  {
    $query = 'update absence_request set accepted=1 where absence_requestid=?';
    $query_prm = array($absence_requestid);
    require('inc/doquery.php');
    echo '<p>Demande d\'absence accepté.</p>';
  }
  else
  {
    $email_sent = 0;
    $subject = 'Demande d\'absence pour ' . $employeeA[$employeeid] . ': ' . datefix2($startdate) . ' à ' . datefix2($stopdate);
    $replytoaddress = 'contact@temtahiti.com';
    $link_description = 'Cliquer ici pour confirmer ou rejeter';
    $messagetext = '<div style="font-weight: bold;">';
    $messagetext .= $subject;
    $messagetext .= '</div>';
    if ($ampm == 1) { $messagetext .= '<p>Matin</p>'; }
    if ($ampm == 2) { $messagetext .= '<p>Après-midi</p>'; }
    $messagetext .= '<p>Motif : ' . $absence_reasonA[$absence_reasonid] . '</p>';
    if ($absence_request_comment != '') { $messagetext .= '<p>' . $employeeA[$employeeid] . ' a écrit : ' . $absence_request_comment . '</p>'; }
    $messagetext .= '<p><a href ="http://'. $_SERVER['SERVER_NAME'] . '/hr.php?hrmenu=confirm_absence&absence_requestid='.$absence_requestid.'">' . $link_description . '</a></p>';
    
    if ($employee_teamidA[$employeeid] > 0)
    {
      $query = 'select employeeemail,employeename,employeefirstname from employee where teamid=? and ismanager=1 and deleted=0';
      $query_prm = array($employee_teamidA[$employeeid]);
      require('inc/doquery.php');
      if ($num_results)
      {
        $namelist = '';
        for ($i=0; $i < $num_results; $i++)
        {
          if (d_sendemail($query_result[$i]['employeeemail'],$replytoaddress,$subject,$messagetext))
          {
            $email_sent = 1;
            $namelist .= ', '.$query_result[$i]['employeename'].' '.$query_result[$i]['employeefirstname'];
          }
        }
        if ($email_sent) { echo '<p>E-mail envoyé à : ',d_output(ltrim($namelist,',')),'</p>'; }
      }
    }
    if (!$email_sent)
    {
      $namelist = '';
      $query = 'select useremail,name from usertable where ishrsuperuser=1 and deleted=0';
      $query_prm = array();
      require('inc/doquery.php');
      if ($num_results)
      {
        for ($i=0; $i < $num_results; $i++)
        {
          if (d_sendemail($query_result[$i]['useremail'],$replytoaddress,$subject,$messagetext))
          {
            $email_sent = 1;
            $namelist .= ', '.$query_result[$i]['name'];
          }
        }
        if ($email_sent) { echo '<p>E-mail envoyé à : ',d_output(ltrim($namelist,',')),'</p>'; }
      }
    }
    if (!$email_sent)
    {
      echo '<p>Aucun e-mail n\'as pus être envoyé.</p>';
    }
  }
}

echo '<form method="post" action="hr.php"><table><tr>';

if ($_SESSION['ds_ishrsuperuser'])
{
  $dp_description = 'Employé(e)';
  $dp_selectedid = $_SESSION['ds_myemployeeid']; if ($employeeid > 0) { $dp_selectedid = $employeeid; }
  $dp_itemname = 'employee'; $dp_noblank = 1; require('inc/selectitem.php');
}

echo '<tr><td>' . d_trad('date:') . '</td><td colspan=2>';
$datename = 'startdate'; require('inc/datepicker.php');
echo ' &nbsp; '.d_trad('validity_to') .' &nbsp; ';
$datename = 'stopdate'; require('inc/datepicker.php');

echo '<tr><td colspan=2 align=center><select name="ampm">
<option value=0>Journée(s) Entière(s)</option>
<option value=1>Matin</option>
<option value=2>Après-midi</option>
</select>';

$dp_description = 'Motif';
$dp_itemname = 'absence_reason'; require('inc/selectitem.php');

?>
<tr><td colspan=3><input autofocus type=text name="absence_request_comment" size=60>
<tr><td colspan=3 align=center><input name="Soumettre" type="submit" value="Valider"></td></tr>
<input type=hidden name="saveme" value="1">
<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
</table></form>

<?php

$query = 'select * from absence_request order by startdate desc limit 100'; # TODO add filter by date, this month and last month?
$query_prm = array();
require('inc/doquery.php');
if ($num_results)
{
  echo '<br><br><h2>Demandes d\'absence</h2><form method="post" action="hr.php"><table class="report"><tr>';
  for ($i=0; $i < $num_results; $i++)
  {
    $employeeid = $query_result[$i]['employeeid'];
    
    $ok = 0;
    if ($_SESSION['ds_ishrsuperuser']) { $ok = 1; }
    if ($_SESSION['ds_ismanager'] > 0 && $_SESSION['ds_ismanager'] == $employee_teamidA[$employeeid]) { $ok = 1; }
    if ($_SESSION['ds_ismanager'] == 0 && $employeeid == $_SESSION['ds_myemployeeid']) { $ok = 1; }
    
    if ($ok)
    {
      $absence_requestid = $query_result[$i]['absence_requestid'];
      $startdate = $query_result[$i]['startdate'];
      $stopdate = $query_result[$i]['stopdate'];
      $absence_reasonid = $query_result[$i]['absence_reasonid'];
      $absence_request_comment = $query_result[$i]['absence_request_comment'];
      $accepted = '';
      if ($query_result[$i]['accepted'] == 1) { $accepted = 'Accepté'; }
      elseif ($query_result[$i]['accepted'] == 2) { $accepted = 'Rejeté'; }
      
      echo '<tr><td><input type=radio name="absence_requestid" value="',$absence_requestid,'">'; # TODO
      echo '<td>', d_output($employeeA[$employeeid]);
      echo '<td>', datefix2($startdate);
      echo '<td>', datefix2($stopdate);
      echo '<td>'; if (isset($absence_reasonA[$absence_reasonid])) { echo d_output($absence_reasonA[$absence_reasonid]); }
      echo '<td>', d_output($absence_request_comment);
      echo '<td>', d_output($accepted);
    }
  }

  if ($_SESSION['ds_ishrsuperuser'] || $_SESSION['ds_ismanager'])
  {
    echo '<tr><td colspan=7 align=center><input type="submit" value="Valider">
    <input type=hidden name="hrmenu" value="confirm_absence">';
  }
  echo '</table></form>';
}

?>
