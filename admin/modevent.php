<?php
$ds_showdeleteditems = $_SESSION['ds_showdeleteditems'];

switch($currentstep)
{

  # form to choose wich event
  case 0:
    echo '<h2>' . d_trad('modifyevent') . '</h2>'; ?>
    <form method="post" action="admin.php"><table>
    <tr><td><?php echo d_trad('date:');?></td><td><?php $datename = 'date'; require('inc/datepicker.php');?></td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
    <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
    </table></form><?php
    break;

  # form to modify event
  case 1:
    $date = $_POST['date'];
    if(!isset($date)) { $date = $_GET['date']; }
    $query = 'select * from calendar where date=?';
    if(!$ds_showdeleteditems)
    {
      $query .= 'and deleted=0';
    }
    $query_prm = array($date);
    require('inc/doquery.php');
    $row = $query_result[0]; 
    $event = '';
    if(!empty($row['event'])){ $event = $row['event']; }
    
    if($num_results > 0)
    {
      echo '<h2>' . d_trad('modifyevent') . '</h2>'; ?>
      <form method="post" action="admin.php"><table>
      <tr><td><?php echo d_trad('date:');?></td><td><?php $datename = 'date'; require('inc/datepicker.php');?></td></tr>
      <tr><td><?php echo d_trad('event:');?></td><td><input type="text" name="event" size=20 value="<?php echo $event; ?>"></td></tr>
      <tr><td><?php echo d_trad('isbankholiday:');?></td><td><input type="checkbox" name="isbankholiday" value="1" <?php if( $row['isbankholiday'] ) {echo ' CHECKED';}?>></td></tr>
      <tr><td><?php echo d_trad('deleted:');?></td><td><input type="checkbox" name="deleted" value="1" <?php if( $row['deleted'] ) {echo ' CHECKED';}?>></td></tr>
      <tr><td colspan="2" align="center">
      <input type=hidden name="step" value="2">
      <input type=hidden name="calendarid" value="<?php echo $row['calendarid']; ?>">
      <input type=hidden name="adminmenu" value="<?php echo $adminmenu; ?>">
      <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
      </table></form><?php
    }
    else
    {
      echo d_trad('noresult');
    }
    break;

  #update event
  case 2:
    $calendarid = $_POST['calendarid'];
    $date = $_POST['date'];
    $event = $_POST['event'];
    $isbankholiday = $_POST['isbankholiday'] +0;
    $deleted = $_POST['deleted'] +0;
    
    $query_update = 'update calendar set event=?,isbankholiday=?,deleted=?'; 
    $query_update_prm = array($event,$isbankholiday,$deleted); 
 
    if($date == '')
    { 
      echo '<p class="alert">' . d_trad('datemustnotbeempty') . '<p>';      
    }
    else
    {
      #check if an event already exist at this date
      $query = 'select calendarid,event from calendar where date=? and calendarid<>?';
      $query_prm = array($date,$calendarid);
      require('inc/doquery.php');

      if ($num_results > 0)
      {
        $event_dup = $query_result[0]['event'];
        echo '<p class="alert">' . d_trad('eventonthisdate',array($date,$event_dup)) . '<p>';
      }
      else
      {
        $query_update .= ',date=?';
        array_push($query_update_prm,$date);
      }
      $query_update .= ' where calendarid=?';
      array_push($query_update_prm,$calendarid);
      $query = $query_update;
      $query_prm = $query_update_prm;
      require('inc/doquery.php');
      echo '<p>' . d_trad('eventmodified',array($date,$event)) . '</p><br>';      
    }
     
    break;
}//switch
?>