<?php

if (!isset($trainingA)) { require('preload/training.php'); }

$STEP_FORM_TRAINING = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_CHOOSE_ADD = 3;
$STEP_FORM_MODIFY = 4;
$STEP_FORM_VALIDATE_ADD = 5;
$STEP_FORM_VALIDATE_MOD = 6;

$ALL = 'ALL';
$MYTEAM = 'MYTEAM';
$TEAMIMANAGE = 'TEAMIMANAGE';
$OTHERTEAM = '-';
$MAX_LENGTH_DISPLAYED = 60;

if ( $_POST['submitformtraining'] == d_trad('choosesession') ) { $currentstep =  $STEP_CHOOSE_ADD;}
//d_debug('currentstep',$currentstep);

$numrows = 0;
if($currentstep == $STEP_CHOOSE_ADD)
{
  $trainingid = $_POST['trainingid'] + 0;     
  $trainingname = $trainingA[$trainingid];
  $employeeid = $_POST['employeeid'];   
  require('hr/chooseemployeewithteams.php');
  $employeeid_old = $_POST['employeeid_old'] + 0;
  //d_debug('trainingid',$trainingid);
  //d_debug('employeeid',$employeeid);
  if ( $trainingid < 0)
  {
    echo '<p class=alert>' . d_trad('trainingmandatory') . '</p>';
    $currentstep = $STEP_FORM_TRAINING;
  }
  else if ( !isset($employeeid))
  {
    echo '<p class=alert>' . d_trad('employeemandatory') . '</p>';
    $currentstep = $STEP_FORM_TRAINING;
  }
}

#title
switch ($currentstep)
{
  case $STEP_FORM_TRAINING:
  case $STEP_FORM:
  case $STEP_FORM_ADD:
  case $STEP_FORM_VALIDATE_ADD:
    $title = d_trad('reserveatraining');
    echo '<h2>' . $title . '</h2>';
    break;

  case $STEP_CHOOSE_ADD:
    $title = d_trad('choosesession');
    echo '<h2>' . d_trad('choosesession') . '</h2>';      
    break;     

  case $STEP_FORM_MODIFY:
    $title = d_trad('modifytrainingreservation');  
    echo '<h2>' . d_trad('modifytrainingreservation') . '</h2>';
    $trainingemployeeplanningid = $_GET['trainingemployeeplanningid'];    
    break;    

  case $STEP_FORM_VALIDATE_MOD:
    $title = d_trad('modifytrainingreservation');  
    echo '<h2>' . d_trad('modifytrainingreservation') . '</h2>';
    break;       
}

# Form to choose wich kind of training
if ($currentstep == $STEP_FORM_TRAINING)
{?>
  <form method="post" action="hr.php"><table>
  <?php $dp_itemname = 'training'; $dp_allowall = 1;$dp_noblank=1;$dp_description = d_trad('trainingname');
  require('inc/selectitem.php');
  
  $dp_itemname = 'employee'; $dp_noblank = 1; $dp_description = 'Employé(e)';
  require('inc/selectitem.php');
  ?>
  
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="<?php echo $STEP_FORM; ?>"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" name="submitformtraining" value="<?php echo d_trad('choosesession');?>"> 
  <input type="submit" name="submitformtraining" value="<?php echo d_trad('list');?>">
  </td></tr>
  </table></form><?php
}
else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD ) 
{
  # save
  if ($currentstep == $STEP_FORM_VALIDATE_MOD) { $trainingemployeeplanningid = $_POST['trainingemployeeplanningid'] +0; }
  $trainingid = $_POST['trainingid'] + 0;     
  $trainingname = $trainingA[$trainingid];
  $trainingplanningid = $_POST['trainingplanningid'] + 0;
  $trainingplanningid_old = $_POST['trainingplanningid_old'] + 0;
  
  $employeeid = $_POST['employeeid'];   
  require('hr/chooseemployeewithteams.php');  
  $employeeid_old = $_POST['employeeid_old'] + 0;
  $deleted = $_POST['deleted'] + 0;  
  $deleted_old = $_POST['deleted_old'] + 0;  

  if ( $trainingid == 0)
  {
    echo '<p class=alert>' . d_trad('trainingmandatory') . '</p>';
    if ($currentstep == $STEP_FORM_VALIDATE_ADD ) { $currentstep = $STEP_FORM_ADD;}
  }
  else if ( !isset($employeeid))
  {
    echo '<p class=alert>' . d_trad('employeemandatory') . '</p>';
    if ($currentstep == $STEP_FORM_VALIDATE_ADD ) { $currentstep = $STEP_FORM_ADD;}
  }
  else if ( $trainingplanningid == 0)
  {
    echo '<p class=alert>' . d_trad('trainingplanningmandatory') . '</p>';
    if ($currentstep == $STEP_FORM_VALIDATE_ADD ) { $currentstep = $STEP_CHOOSE_ADD;}  
  }
  else
  {
    #get infos for this planning to verify if there are places left
    $query = 'select * from trainingplanning where trainingplanningid=?';
    $query_prm = array($trainingplanningid);
    require('inc/doquery.php');
    $num_plannings = $num_results;$planningA = $query_result;
    $nbplacesleft = 0;
    if ( $num_plannings > 0)
    {
      $nbplaces = $planningA[0]['nbplaces'] + 0;//d_debug('nbplaces',$nbplaces);
      $nbreservedplaces = $planningA[0]['nbreservedplaces'] + 0;//d_debug('nbreservedplaces',$nbreservedplaces);
      $nbplacesleft = $nbplaces - $nbreservedplaces;//d_debug('nbplacesleft',$nbplacesleft);
    }

    if (( $deleted == 0 && $nbplacesleft >= $nbemployees ) || ( $deleted == 1))
    {
      $ok = '';
      $error = '';
      $isfirsterror = 1;    
      $isfirstok = 1;    
      for($e=0;$e<$nbemployees;$e++)
      {
        $eid = $employee_todisplayA[$e]['employeeid'];
        $ename = $employeeA[$eid];
        # if employee name changes
        #check if the new employee is allowed to reserve this formation
        $trainingallowed = 1;
        //d_debug('employeeid',$employeeid);d_debug('employeeid_old',$employeeid_old);d_debug('eid',$eid);
        
        if ($employeeid !== $employeeid_old)
        {
          $trainingemployeecategoryid = $training_employeecategoryidA[$trainingid];
          if ($trainingemployeecategoryid > 0)
          {
            if (!isset($employeecategoryA)) { require ('preload/employeecategory.php');}    
            $employeecategoryid = $employeesorted_categoryidA[$eid];
            if ($employeecategoryid != $trainingemployeecategoryid)
            {
              $trainingallowed = 0;
              if($employeecategoryid > 0)
              {
                $employeecategory = $employeecategoryA[$employeecategoryid];
              }
              else
              {
                $employeecategory = d_trad('unspecified');
                //d_debug('employeecategory',$employeecategory);
              }
              $trainingname = $trainingA[$trainingid];
              if ($nbemployees == 1)
              {
                $error = d_trad('planningtobedefined',array($trainingname,$employeecategory)); 
              }
              else
              {
                if ($isfirsterror == 1)
                {
                  $error =  d_trad('trainingnotreservedforemployees:');
                  $isfirsterror = 0;
                }
                $error .= '<br> - ' . $ename . ' (' . $employeecategory . ')';
              }
            }
          }
        }
        if ($trainingallowed == 1)
        {   
          if ($deleted == 0)
          {
            #verify if reservation does no exists yet
            $query = 'select * from trainingemployeeplanning where employeeid=? and trainingplanningid = ? and deleted=0 ';
            $query_prm =  array($eid,$trainingplanningid);
            require ('inc/doquery.php');
            if ($num_results > 0)
            {
                $trainingallowed == 0;
                if ($isfirsterror == 1)
                {
                  $error =  d_trad('trainingnotreservedforemployees:');
                  $isfirsterror = 0;
                }
                $error .= '<br> - ' . $ename . ' ('.  d_trad('alreadyreserved') .')';
            }
          }
        }
        
        if ($trainingallowed == 1)
        { 
          if ($currentstep == $STEP_FORM_VALIDATE_ADD) { $trainingemployeeplanningid = NULL;}
          $query = 'REPLACE INTO trainingemployeeplanning (trainingemployeeplanningid,trainingid,trainingplanningid,employeeid,deleted) values (?,?,?,?,?)';
          $query_prm = array($trainingemployeeplanningid,$trainingid,$trainingplanningid,$eid,$deleted);
          require ('inc/doquery.php');
          if ( $num_results > 0 )
          {
            $trainingemployeeplanningid = $query_insert_id;
            if ($currentstep == $STEP_FORM_VALIDATE_ADD)
            { 
              if ($isfirstok == 1)
              {
                $ok =  d_trad('trainingreservedforemployees:');
                $isfirstok = 0;
              }
              $ok .= '<br> - ' . $ename; 
              #update nbreservedplaces (+1)
              $query = 'UPDATE trainingplanning set nbreservedplaces = (nbreservedplaces +1) where trainingplanningid = ?';
              $query_prm = array($trainingplanningid);
              require('inc/doquery.php');
            }
            else
            {             
              echo '<p>' . d_trad('trainingplanningmodified') . '</p><br>';          
              #update nbreservedplaces in trainingplanning
              $addforplanningid = 0;
              if ( $trainingplanningid_old  != $trainingplanningid )
              {
                if ( $deleted == 0)
                {
                  $addforplanningid = 1;
                }
                #else: don't do anything for this trainingplanningid 
                
                # delete 1 place for old trainingplanningid if it was not deleted
                if ( $deleted_old == 0)
                {
                  $query = 'UPDATE trainingplanning set nbreservedplaces = (nbreservedplaces -1) where trainingplanningid = ?';
                  $query_prm = array($trainingplanningid_old);
                  require('inc/doquery.php');
                }
              }
              else if ( $deleted != $deleted_old )
              {
                switch ($deleted)
                {
                  case 0:
                    $addforplanningid = 1;
                    break;
                  case 1:
                    #update nbreservedplaces (-1)
                    $query = 'UPDATE trainingplanning set nbreservedplaces = (nbreservedplaces -1) where trainingplanningid = ?';
                    $query_prm = array($trainingplanningid);
                    require('inc/doquery.php');              
                    break;
                }
              }
              
             if ( $addforplanningid == 1 )
              {
                #update nbreservedplaces (+1)
                $query = 'UPDATE trainingplanning set nbreservedplaces = (nbreservedplaces +1) where trainingplanningid = ?';
                $query_prm = array($trainingplanningid);
                require('inc/doquery.php');                       
              }    
            }
          }
        }
      }
    }
    else
    {
      echo '<p class="alert">' . d_trad('chooseanothersession') . '</p>';
      $currentstep = $STEP_CHOOSE_ADD; 
    }
    
    #ok and error messages 
    if ($ok != '')
    {
      echo '<p>' . $ok . '</p><br>';
    }
    if ($error != '')
    {
      echo '<p class=alert>' . $error . '</p>';
    }
  }
}
  
if ( $currentstep > $STEP_FORM_TRAINING)
{
  if ($currentstep != $STEP_FORM_ADD && $currentstep != $STEP_CHOOSE_ADD)
  {
    # pre-filled form 
    $queryselect = 'select tep.trainingemployeeplanningid,tep.employeeid,tep.trainingid ,tp.trainingplanningid,tp.startdate,tp.stopdate,tp.place,tep.deleted ';
    $queryselect .= ' from trainingemployeeplanning tep, trainingplanning tp ';
    if ($currentstep == $STEP_FORM)
    {
      $trainingid = $_POST['trainingid'] + 0;    
      $employeeid = $_POST['employeeid'];   
    }    
    $queryselect .= ' where tep.trainingplanningid = tp.trainingplanningid and tp.deleted=0 and';
    $queryselect_prm = array();
    
    if ( $currentstep == $STEP_FORM)
    {      
      if ($trainingid > 0)
      {
        $queryselect .= ' tep.trainingid=? and';
        array_push($queryselect_prm,$trainingid);
      }
      require('hr/chooseemployeewithteams.php');
      $queryselect .= ' tep.employeeid in ( '; 
      for($e=0;$e<$nbemployees;$e++)
      {
        $queryselect .= '?';
        if ($e < $nbemployees -1 ) { $queryselect .= ','; }
        array_push($queryselect_prm,$employee_todisplayA[$e]['employeeid']);
      }
      $queryselect .= ') and';
 
      if ($ds_showdeleteditems != 1)
      {
        $queryselect .= ' tep.deleted=0';
      }
      #if queryselect endswith 'where' : delete it
      #else delete 'and'
      $poswhere = strripos($queryselect,'where');
      $posand = strripos($queryselect,'and');
      if ( $poswhere == (strlen($queryselect) - strlen('where')))
      {
        $queryselect = substr($queryselect,0,$poswhere);
      }
      else if ( $posand == (strlen($queryselect) - strlen('and')))
      {
        $queryselect = substr($queryselect,0,$posand);
      }
      $queryselect .= ' order by tp.startdate';
    }
    else
    {
      $queryselect .= ' tep.trainingemployeeplanningid=?';
      $queryselect_prm = array($trainingemployeeplanningid);
    }

    $query = $queryselect;
    $query_prm = $queryselect_prm;
    require ('inc/doquery.php');
    $numrows = $num_results;
    if ($numrows > 0 )
    {
      $row = $query_result; 
    }
  }

  ?>
  <form method="post" action="hr.php">
  <?php 
  if ( $numrows == 0 && ($employeeid != $TEAMIMANAGE) && ($employeeid != $ALL) && ($currentstep != $STEP_FORM_ADD) && ($currentstep != $STEP_CHOOSE_ADD))
  { 
    $currentstep = $STEP_CHOOSE_ADD;
  }
  
  if ( $numrows > 0 || ($currentstep == $STEP_FORM_ADD) || ($currentstep == $STEP_CHOOSE_ADD))
  { 
    if ( $currentstep == $STEP_FORM)
    {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('employee'); ?></th>      
        <th><?php echo d_trad('reference'); ?></th>
        <th><?php echo d_trad('trainingname'); ?></th>
        <th><?php echo d_trad('startdate'); ?></th>        
        <th><?php echo d_trad('stopdate'); ?></th>
        <th><?php echo d_trad('place'); ?></th>
        <?php
        if(( $ds_showdeleteditems  || $currentstep == $STEP_FORM_MODIFY) && $currentstep != $STEP_FORM_ADD)
        {
          echo '<th>' . d_trad('deleted') . '</th>';
        } ?>
      </thead><?php 
    }
    else
    {   
      echo '<table>';
    }
  }
  
  if ( $currentstep ==  $STEP_FORM)
  {
    for ($r=0;$r<$numrows;$r++)
    {
      $trainingemployeeplanningid = $row[$r]['trainingemployeeplanningid'];
      $trainingplanningid = $row[$r]['trainingplanningid'];
      $trainingid = $row[$r]['trainingid'];
      $employeeid = $row[$r]['employeeid'];

      $href = 'hr.php?hrmenu=trainingemployeeplanning&step=' . $STEP_FORM_MODIFY . '&trainingemployeeplanningid=' . $trainingemployeeplanningid;
      echo d_tr();
      echo '<td><a href="' . $href . '">' . $employeesortedA[$employeeid]. '</a></td>';   
      
      $trainingrefdisplayed = d_output($training_refA[$trainingid]);
      if ( strlen($trainingrefdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingrefdisplayed = substr($trainingrefdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingrefdisplayed. '</a></td>';   
      
      $trainingnamedisplayed = d_output($trainingA[$trainingid]);
      if ( strlen($trainingnamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingnamedisplayed = substr($trainingnamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $trainingnamedisplayed. '</a></td>';  
      
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['startdate']) . '</a></td>';
      echo '<td><a href="' . $href . '">' . datefix2($row[$r]['stopdate']) . '</a></td>';
      
      $placedisplayed = d_output($row[$r]['place']);
      if ( strlen($placedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $placedisplayed = substr($placedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $placedisplayed. '</a></td>';
      
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
  }
  else if ($numrows > 0 || $currentstep == $STEP_FORM_ADD || $currentstep == $STEP_CHOOSE_ADD )
  {  
    # to prefill form with previous choices (ADD) or result (MODIFY)
    if ( $currentstep == $STEP_FORM_ADD || $currentstep == $STEP_CHOOSE_ADD)
    {
      $employeeid = $_POST['employeeid']; 
      if(!isset($employeeid)){ $employeeid = $_GET['employeeid'];}      
      if(!isset($employeeid)) {$employeeid = $ds_myemployeeid;}
      $trainingid = $_POST['trainingid'];   
      if($trainingid == 0){ $trainingid = $_GET['trainingid'] +0;}
      $trainingplanningid = $_POST['trainingplanningid'];        
    }
    else
    {
      $employeeid = $row[0]['employeeid'];
      $trainingid = $row[0]['trainingid'];
      $trainingplanningid = $row[0]['trainingplanningid'];
    }
    $trainingname = $trainingA[$trainingid];  
      
    #get all trainings planned
    $employeecategoryid = $employeeimanage_categoryidA[$employeeid];
    
    if($employeecategoryid > 0)
    {
      if (!isset($employeecategoryA)) { require ('preload/employeecategory.php');}    
      $employeecategory = $employeecategoryA[$employeecategoryid];
    }
    else
    {
      $employeecategory = d_trad('unspecified');
    }
    
    $query = 'select * from trainingplanning tp, training t where tp.deleted=0 and tp.trainingid = t.trainingid';
    $query_prm = array();    
    
    if ($currentstep != $STEP_FORM_ADD)
    {
      if ($trainingid > 0)
      {
        $query .= ' and tp.trainingid=?';
        array_push($query_prm,$trainingid); 
      }
      if ($employeecategoryid > 0)
      {
        $query .= '  and (t.employeecategoryid = 0 || t.employeecategoryid=?)';
        array_push($query_prm,$employeecategoryid);       
      }
    }
    require('inc/doquery.php');
    $num_plannings = $num_results;$planningA = $query_result;
    
    if ( $num_plannings > 0 )
    {
      if ( $currentstep == $STEP_CHOOSE_ADD)
      {
        #post the parameters
        echo '<input type=hidden name="trainingid" value="' . $trainingid . '">';
        echo '<input type=hidden name="employeeid" value="' . $employeeid . '">';      
      }
      else
      { 
        $dp_selectedid = $employeeid;
        $dp_itemname = 'employee'; $dp_noblank = 1; $dp_description = 'Employé(e)';
        require('inc/selectitem.php');
      }
      
      if ($currentstep == $STEP_FORM_ADD)
      {
        $dp_itemname = 'training'; $dp_description = d_trad('trainingname');$dp_selectedid = $trainingid;$dp_noblank=1;require('inc/selectitem.php');
      }
      else
      {?>
        <tr><td><?php echo d_trad('trainingname:'); ?></td>
        <td><?php echo d_output($trainingA[$trainingid]); ?></td></tr>
        <input type=hidden name="trainingid" value="<?php echo $trainingid; ?>">
        <tr><td><?php echo d_trad('planning:'); ?></td>
        <td><select name="trainingplanningid"><?php 
          for($p=0;$p<$num_plannings;$p++)
          {
            $startdate = datefix2($planningA[$p]['startdate']);
            $stopdate = datefix2($planningA[$p]['stopdate']);
            $place = d_output($planningA[$p]['place']);
            $nbplaces = $planningA[$p]['nbplaces'] +0;
            $nbreservedplaces = $planningA[$p]['nbreservedplaces'] +0;
            $trainingplanninginfo = d_trad('trainingplanninginfo',array($startdate,$stopdate,$place));
            if($nbreservedplaces == $nbplaces)
            {
              $trainingplanninginfo .= ' (' . d_trad('full') . ')';
            }
            else if ($nbreservedplaces < $nbplaces)
            {
              $trainingplanninginfo .= ' (' . d_trad('placesleft',$nbplaces-$nbreservedplaces) . ')';            
            }
            $selected = '';
            if ( $planningA[$p]['trainingplanningid'] == $trainingplanningid )
            {
              $selected = ' selected';
            }
            echo '<option value=' . $planningA[$p]['trainingplanningid'] . $selected . '>' . $trainingplanninginfo . '</option>';
          } ?>
        </select>
        </tr><?php
      }

      if ( $currentstep == $STEP_FORM_MODIFY || $currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
      {
        $deleted = $row[0]['deleted'];
        echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
        if ($deleted) { echo ' checked '; }
        echo '></td></tr>';
        echo '<input type=hidden name="trainingemployeeplanningid" value=' . $trainingemployeeplanningid .'>';
        echo '<input type=hidden name="deleted_old" value=' . $deleted .'>';      
        echo '<input type=hidden name="trainingplanningid_old" value=' . $trainingplanningid .'>';      
        echo '<input type=hidden name="employeeid_old" value=' . $employeeid .'>';      
      }
      
    }
    else
    { 
      echo '<p class=alert>' . d_trad('planningtobedefined',array($trainingname,$employeecategory)) . '</p>';          
    }    
  }
  echo '</table>';  
} ?>

<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
if ($_SESSION['ds_ishrsuperuser'])
{
  if (($currentstep == $STEP_FORM_ADD) || ($currentstep == $STEP_FORM ))
  {
    echo '<input type=hidden name="step" value="' . $STEP_CHOOSE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('choosesession') . '"></div>';
  }
  else if ($currentstep == $STEP_CHOOSE_ADD)
  {
    if ( $num_plannings > 0 )
    {  
      echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('choose') . '"></div>';
    }
  }  
  else if ($currentstep == $STEP_FORM_MODIFY)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  } 
  else if (( $numrows > 0 ) && ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD))
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
}
?>
</table>
</form>