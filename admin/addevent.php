<?php
$step = 0;
if (isset($_POST['step'])) { $step = (int) $_POST['step']; }
switch($step)
{

  # form
  case 0:
    ?><h2><?php echo d_trad('addplanningteamvalue');?></h2>
    <form method="post" action="admin.php"><table>
    <tr><td><?php echo d_trad('date:');?></td><td><?php $datename = 'date'; require('inc/datepicker.php');?></td></tr>
    <tr><td><?php echo d_trad('event:');?></td><td><input type="text" name="event" size=20></td></tr>
    <tr><td><?php echo d_trad('isbankholiday:');?></td><td><input type="checkbox" name="isbankholiday" value="1"></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1"><input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
    <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
    </table></form><?php
    break;

  # action
  case 1:
    $date = $_POST['date'];
    $date_todisplay = datefix2($date);
    $event = $_POST['event'];
    $isbankholiday = $_POST['isbankholiday'] +0;
    
    #date is mandatory
    if ($date == '') { echo '<p class="alert">' .d_trad('datemustnotbeempty') . '</p>'; exit; }

    #check if date already exist
    $query = 'select calendarid,event from calendar where date=? and deleted=0';
    $query_prm = array($date);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      echo '<p class="alert">' . d_trad('eventonthisdate',array($date_todisplay,$query_result[0]['event'])) . '<p>';
    }
    else
    {
      $query = 'insert into calendar (date,event,isbankholiday) values (?,?,?)';
      $query_prm = array($date,$event,$isbankholiday);
      require('inc/doquery.php');
      if($num_results > 0)
      {
        echo '<p>' . d_trad('eventadded',array($event)) .'</p>';
      }
      break;
    }

}
?>