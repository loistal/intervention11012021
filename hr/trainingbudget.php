<?php

$PA['deleted'] = 'uint';
$PA['trainingbudgetid'] = 'int';
require('inc/readpost.php');

$numtrainingbudgets = 0;

if (!isset($trainingbudgetA))
{
  $query = 'select * from trainingbudget';
  if(!$_SESSION['ds_showdeleteditems'])
  {
    $query .= ' where deleted = 0';
  }   
  $query .= ' order by year desc';
  $query_prm = array();
  require('inc/doquery.php');
  for ($kladd_i=0;$kladd_i<$num_results;$kladd_i++)
  {
    $trainingbudgetid_temp = (int) ($query_result[$kladd_i]['trainingbudgetid']+0);
    $trainingbudgetA[$trainingbudgetid_temp] = $query_result[$kladd_i]['year'];
    $trainingbudget_yearA[$trainingbudgetid_temp] = $query_result[$kladd_i]['year'];
    $trainingbudget_initialtrainingbudgetA[$trainingbudgetid_temp] = $query_result[$kladd_i]['initialtrainingbudget'];
    $trainingbudget_trainingbudgetA[$trainingbudgetid_temp] = $query_result[$kladd_i]['trainingbudget'];
    $trainingbudget_deletedA[$trainingbudgetid_temp] = $query_result[$kladd_i]['deleted'];
  }
  if ($num_results) { $numtrainingbudgets = count($trainingbudgetA); }
}

$parameterterm = d_trad('trainingbudget');

$STEP_FORM_PARAM = 0;
$STEP_FORM = 1;
$STEP_FORM_LIST = 10;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED = 60;

if (isset($_POST['submitform']) && $_POST['submitform'] == d_trad('add') ) { $currentstep =  $STEP_FORM_ADD;}

#title
switch ($currentstep)
{
  case $STEP_FORM:
  case $STEP_FORM_VALIDATE_ADD:  
    echo '<h2>' . $parameterterm . '</h2>'; 
    break;

  case $STEP_FORM_VALIDATE_MOD:  
     echo '<h2>' . $parameterterm . '</h2>';   
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addparam',$parameterterm) . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifyparam',$parameterterm) . '</h2>';
    $trainingbudgetid = $_GET['trainingbudgetid'];  
    break;         
}
$numrows = 0;
# Form to choose wich kind of trainingbudget
if ($currentstep == $STEP_FORM_PARAM)
{
  echo '<h2>' . $parameterterm . '</h2>'; ?>
  <form method="post" action="hr.php"><table>
  <?php
  if ($numtrainingbudgets == 0) 
  { 
    $currentstep = $STEP_FORM;
  }
  else
  {
    echo '<tr><td>' . d_trad('year') . '</td>';
    echo '<td><select name=trainingbudgetid>';
    echo '<option value="-1" SELECTED>' . d_trad('all') . '</option>';
    foreach($trainingbudgetA as $tid=>$tyear)
    {
      echo '<option value="' . $tid . '">' . $tyear . '</option>';
    }
    echo '</selected></td></tr>';
    ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
    <input type="submit" name="submitform" value="<?php echo d_trad('add');?>">
    <input type="submit" name="submitform" value="<?php echo d_trad('list');?>"></td></tr>  
    </table></form><?php
  }
}
else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
{
  # save
  $year = $_POST['year']; 
  $initialtrainingbudget = $_POST['initialtrainingbudget']+0;
  //$trainingbudget = $_POST['trainingbudget']+0; 
  
  $query = 'REPLACE INTO trainingbudget (trainingbudgetid,year,initialtrainingbudget,deleted) values (?,?,?,?)';
  $query_prm = array($trainingbudgetid,$year,$initialtrainingbudget,$deleted);    

  require ('inc/doquery.php');
  if ( $num_results > 0 )
  {
    $trainingbudgetid = $query_insert_id;
    if ($currentstep == $STEP_FORM_VALIDATE_ADD)
    { 
      echo '<p>' . d_trad('successaddtrainingbudget',$year) . '</p><br>';            
    }
    else
    {
      echo '<p>' . d_trad('successmodtrainingbudget',$year) . '</p><br>';              
    }
  }  
  $currentstep = $STEP_FORM_LIST;
}
  
if ( $currentstep > $STEP_FORM_PARAM)
{
  if ($currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form 
    $query = 'select * from trainingbudget where';
    $query_prm = array();
    
    if ( $currentstep == $STEP_FORM || $currentstep == $STEP_FORM_LIST)
    {
      if ( $currentstep == $STEP_FORM_LIST)
      {
        $parameterid = 0;
        $currentstep = $STEP_FORM;
      }
      else
      {
        $parameterid = $trainingbudgetid;   
      }
    
      if ($parameterid > 0)
      {
        $query .= ' trainingbudgetid=? and';
        array_push($query_prm,$parameterid);
      }
      if ($_SESSION['ds_showdeleteditems'] != 1)
      {
        $query .= ' deleted=0';
      }
      #if query endswith 'where' : delete it
      #else delete 'and'
      $poswhere = strripos($query,'where');
      $posand = strripos($query,'and');
      if ( $poswhere == (strlen($query) - strlen('where')))
      {
        $query = substr($query,0,$poswhere);
      }
      else if ( $posand == (strlen($query) - strlen('and')))
      {
        $query = substr($query,0,$posand);
      }
    }
    else
    {
      $query .= ' trainingbudgetid=?';
      $query_prm = array($trainingbudgetid);
    }
    $query .= ' order by year desc';
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
  if ( $numrows == 0 && ($currentstep != $STEP_FORM_ADD))
  { 
    #echo '<p>' . d_trad('noresult') . '</p>';
  }
  else
  {    
    if ( $currentstep == $STEP_FORM)
    {?>
      <table class="report">
      <thead>
        <?php
        echo '<th>' . d_trad('year') . '</th>';
        echo '<th>' . d_trad('initialbudget') . '</th>';        
        //echo '<th>' . d_trad('trainingbudget') . '</th>'; ?>
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
      $trainingbudgetid = $row[$r]['trainingbudgetid'];
      $href = 'hr.php?hrmenu=trainingbudget&trainingbudgetid=' . $trainingbudgetid .'&step=' . $STEP_FORM_MODIFY;
      echo d_tr();   
      echo '<td style="text-align:right;"><a href="' . $href . '">' . $row[$r]['year'] . '</td>';
      echo '<td style="text-align:right;"><a href="' . $href . '">' . myfix($row[$r]['initialtrainingbudget']) . '</td>';
      //echo '<td><a href="' . $href . '">' . myfix($row[$r]['trainingbudget']) . '</td>';
      echo '</tr>';     
    }
    echo '</table>';
  }
  else if ($numrows > 0 || $currentstep == $STEP_FORM_ADD )
  {
    if (isset($row[0]['year'])) { $year = $row[0]['year']; } else { $year = ''; }
    if (isset($row[0]['initialtrainingbudget'])) { $initialtrainingbudget = $row[0]['initialtrainingbudget']; } else { $initialtrainingbudget = ''; }
    echo '<tr><td>' . d_trad('year:') . '</td><td><input type="number" name="year" min=2014 style="text-align:right;" value="' . $year . '" size=4></td></tr>';
    echo '<tr><td>' . d_trad('initialbudget:') . '</td><td><input type="number" name="initialtrainingbudget" style="text-align:right;" value="' . $initialtrainingbudget . '" size=10></td></tr>';
    if ($currentstep != $STEP_FORM_ADD)
    {
      #calculation of provisional training budget
      $query = 'select sum(tp.pricebyemployee) from trainingemployeeplanning tep,trainingplanning tp where tep.trainingplanningid = tp.trainingplanningid and tep.deleted=0 and tp.deleted=0 and YEAR(tp.startdate)=?';
      $query_prm = array($year);
      require('inc/doquery.php');
      $provisionalbudget = $query_result[0]['sum(tp.pricebyemployee)'] + 0;
      
      #calculation of actual training budget
      /*for($pv=1;$pv<=$ds_numplanningteamvalue;$pv++)
      {
        $query = 'select tep.trainingemployeeplanningid from trainingemployeeplanning tep,trainingplanning tp where tep.trainingplanningid = tp.trainingplanningid and tep.deleted=0 and tp.deleted=0 and YEAR(tp.startdate)=?';
        $query = 'select * from planningteam pt,planningteamvalue pv where pt.deleted=0 and pt.state in (' . $STATE_SUBMITED . ',' . $STATE_ACCEPTED . ') and (YEAR(pt.startingdate) = ? or YEAR(pt.planningdate) = ?) and pt.planningteamvalueid = pv.planningteamvalueid and pv.istraining = 1';
        $query_prm = ;
        require('inc/doquery.php');
        $numtotal += $num_results *;
      } */     
      echo '<tr><td>' . d_trad('provisionalbudget:') . '</td><td style="text-align:right;">' . myfix($provisionalbudget) . '</td></tr>';
      //echo '<tr><td>' . d_trad('actualbudget:') . '</td><td style="text-align:right;">' . myfix($provisionalbudget) . '</td></tr>';
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="trainingbudgetid" value=' . $trainingbudgetid .'>';
    }
    echo '</table>';    
  }
} ?>
<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
if ($_SESSION['ds_ishrsuperuser'])
{
  if ($currentstep == $STEP_FORM )
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_ADD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_ADD . '"><br><div align="center"><input type="submit" value="' . d_trad('add') . '"></div>';
  }
  else if ($currentstep == $STEP_FORM_MODIFY)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('validate') . '"></div>';
  } 
}
?>
</table>
</form>