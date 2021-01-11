<?php

$title = d_trad('replaceemployee:');
echo '<TITLE>' . $title . '</TITLE></HEAD><BODY>';
echo '<h2>' . $title . '</h2>';

if(!isset($_POST['currentstep']))
{?>
  <form method="post" action="hr.php?hrmenu=replaceemployee">
  <input type=hidden name="currentstep" value="1">  
  <table>
    <tr><td><?php $dp_itemname = 'employee'; $dp_addtoid = 'old'; $dp_description = d_trad('replaceemployee'); $dp_noblank = 1; require('inc/selectitem.php');?></td></tr>
    <tr><td><?php $dp_itemname = 'employee'; $dp_addtoid = 'new'; $dp_description = d_trad('byemployee'); $dp_noblank = 1; require('inc/selectitem.php');?></td></tr>
    <tr><td colspan=2><?php echo d_trad('fordata:'); ?></td></tr>
    <tr><td>&nbsp;</td><td><input type="checkbox" name="replaceclient1" value="1">&nbsp;&nbsp;<?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee1'])); ?></td></tr>  
    <tr><td>&nbsp;</td><td><input type="checkbox" name="replaceclient2" value="1">&nbsp;&nbsp;<?php echo d_trad('employee1clients',array($_SESSION['ds_term_clientemployee2'])); ?></td></tr>      
    <tr><td>&nbsp;</td><td><input type="checkbox" name="replaceextraaddress" value="1">&nbsp;&nbsp;<?php echo d_trad('Extraaddress'); ?></td></tr>      
    <tr><td>&nbsp;</td><td><input type="checkbox" name="replaceplanning" value="1">&nbsp;&nbsp;<?php echo d_trad('Planning'); ?></td></tr>  
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan=2 align=right><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </form>
  </table>
<?php
}
else if($_POST['currentstep'] >= 1)
{
  //employees id and name
  $oldemployeeid = $_POST['employeeoldid']; 
  $newemployeeid = $_POST['employeenewid'];
  require('preload/employee.php');
  $oldemployeename = $employeeA[$oldemployeeid];  
  $newemployeename = $employeeA[$newemployeeid];  
  
  //tables where employee must be replaced
  $replaceclient1 = $_POST['replaceclient1'];
  $replaceclient2 = $_POST['replaceclient2'];
  $replaceextraaddress = $_POST['replaceextraaddress'];
  $replaceplanning = $_POST['replaceplanning'];
  
  $showtablenamesA = array();
  $nbtables = 0;
  if($replaceclient1 == 1) { $nbtables++;array_push($showtablenamesA, d_trad('employee1clients',array($_SESSION['ds_term_clientemployee1'])));}
  if($replaceclient2 == 1) { $nbtables++;array_push($showtablenamesA, d_trad('employee1clients',array($_SESSION['ds_term_clientemployee2'])));}
  if($replaceextraaddress == 1) { $nbtables++;array_push($showtablenamesA, d_trad('extraaddress'));}
  if($replaceplanning == 1) { $nbtables++;array_push($showtablenamesA, d_trad('planning'));}

  if($_POST['currentstep'] == 1)
  {
    if($nbtables == 0)
    {?>
      <form method="post" action="hr.php?hrmenu=replaceemployee"> 
      <table>
        <tr><td colspan=2 align=left><?php echo d_trad('pleaseselectdata',array($oldemployeename,$newemployeename));?></td></tr>
        <tr><td colspan=3>&nbsp;</td></tr>        
        <tr><td colspan=3 align=left><input type="submit" value="<?php echo d_trad('back');?>"></td></tr>
      </form>
      </table>      
    <?php
    }
    else
    {?>
      <form method="post" action="hr.php?hrmenu=replaceemployee">
      <input type=hidden name="currentstep" value="2">   
      <input type=hidden name="employeeoldid" value="<?php echo $oldemployeeid;?>">
      <input type=hidden name="employeenewid" value="<?php echo $newemployeeid;?>">  
      <input type=hidden name="replaceclient1" value="<?php echo $replaceclient1;?>">
      <input type=hidden name="replaceclient2" value="<?php echo $replaceclient2;?>">
      <input type=hidden name="replaceextraaddress" value="<?php echo $replaceextraaddress;?>">
      <input type=hidden name="replaceplanning" value="<?php echo $replaceplanning;?>">
      <table>
        <tr><td colspan=3 align=left><?php echo d_trad('replaceemployee1byemployee2?',array($oldemployeename,$newemployeename));?></td></tr>
        <tr><td colspan=3>&nbsp;</td></tr>
        <?php 
        foreach($showtablenamesA as $showtablenames)
        {
          echo '<tr><td>&nbsp;</td><td colspan=2 align=left>- ' . $showtablenames . '</td></tr>';
        }
        ?>
        <tr><td colspan=3 align=right><input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
      </form>
      </table>
    <?php 
    }
  }
  else
  {
    $showresulttableA = array();
    if($replaceclient1 == 1)
    {
      $query = 'update client set employeeid=? where employeeid=?';
      $query_prm = array($newemployeeid,$oldemployeeid);
      require('inc/doquery.php');
      array_push($showresulttableA,$num_results);
    }
    
    if($replaceclient2 == 1)
    {
      $query = 'update client set employeeid2=? where employeeid2=?';
      $query_prm = array($newemployeeid,$oldemployeeid);
      require('inc/doquery.php');
      array_push($showresulttableA,$num_results);      
    }
    
    if($replaceextraaddress == 1)
    {
      $query = 'update extraaddress set employeeid=? where employeeid=?';
      $query_prm = array($newemployeeid,$oldemployeeid);
      require('inc/doquery.php'); 
      array_push($showresulttableA,$num_results);
    }    

    if($replaceplanning == 1)
    {
      $query = 'update planning_employee set employeeid=? where employeeid=?';
      $query_prm = array($newemployeeid,$oldemployeeid);
      require('inc/doquery.php');
      array_push($showresulttableA,$num_results);      
    }
    echo '<br><p>' . d_trad('employee1replacedbyemployee2fordata:',array($oldemployeename,$newemployeename)) . '</p>';
    $i = 0;
    foreach($showtablenamesA as $showtablenames)
    {
      $nbrows = $showresulttableA[$i];$i++;
      if(isset($nbrows) && $nbrows != 0)
      {
        echo '<br><p class=indent><b>- ' . $showtablenames . ':</b> ' . d_trad('nbrowsmodified',array($nbrows)) . '</p>';              
      }
      else
      {
        echo '<br><p class=indent><b>- ' . $showtablenames . '</b>: ' . d_trad('norowmodified') . '</p>';     
      }      
    }
  }
}

?>