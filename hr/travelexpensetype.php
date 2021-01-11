<?php

# TODO IMPORTANT replace into => on duplicate key update

if (!isset($travelexpensetypeA)) { require('preload/travelexpensetype.php'); }

$STEP_FORM_TRAVELEXP = 0;
$STEP_FORM = 1;
$STEP_FORM_LIST = 10;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED = 60;

if ( $_POST['submitform'] == d_trad('add') ) { $currentstep =  $STEP_FORM_ADD;}

#title
switch ($currentstep)
{
  case $STEP_FORM:
    echo '<h2>' . d_trad('travelexpensetypes') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addtravelexpensetype') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifytravelexpensetype') . '</h2>';
    $travelexpensetypeid = $_GET['travelexpensetypeid'];    
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . d_trad('travelexpensetype') . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('travelexpensetype') . '</h2>';
    break;       
}

$numrows = 0;
# Form to choose wich kind of travelexpensetype
if ($currentstep == $STEP_FORM_TRAVELEXP)
{
  echo '<h2>' . d_trad('travelexpensetype') . '</h2>'; ?>
  <form method="post" action="hr.php"><table>
  <?php $dp_itemname = 'travelexpensetype'; $dp_allowall = 1; $dp_noblank = 1;$dp_description = d_trad('travelexpensetype');
  require('inc/selectitem.php');?>
  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" name="submitform" value="<?php echo d_trad('add');?>">
  <input type="submit" name="submitform" value="<?php echo d_trad('list');?>"></td></tr>  
  </table></form><?php
}
else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
{
  # save
  if ($currentstep == $STEP_FORM_VALIDATE_MOD) { $travelexpensetypeid = $_POST['travelexpensetypeid'] +0;}
  $travelexpensetypename = d_input($_POST['travelexpensetypename']);       
  $refundlimit = $_POST['refundlimit']+0;    
  $refundlimitvat = $_POST['refundlimitvat']+0;    
  $deleted = $_POST['deleted'] + 0;  
  
  $query = 'REPLACE INTO travelexpensetype (travelexpensetypeid,travelexpensetypename,refundlimit,refundlimitVAT,deleted) values (?,?,?,?,?)';
  $query_prm = array($travelexpensetypeid,$travelexpensetypename,$refundlimit,$refundlimitvat,$deleted);
  require ('inc/doquery.php');
  if ( $num_results > 0 )
  {
    $travelexpensetypeid = $query_insert_id;
    if ($currentstep == $STEP_FORM_VALIDATE_ADD)
    {
      echo '<p>' . d_trad('travelexpensetypeadded',$travelexpensetypename) . '</p><br>';   
    }
    else
    {
      echo '<p>' . d_trad('travelexpensetypemodified',$travelexpensetypename) . '</p><br>';      
    }
  }  
  $currentstep = $STEP_FORM_LIST;
}
  
if ( $currentstep > $STEP_FORM_TRAVELEXP)
{
  if ($currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form 
    $query = 'select * from travelexpensetype where';
    $query_prm = array();
    
    if ( $currentstep == $STEP_FORM || $currentstep == $STEP_FORM_LIST)
    {
      if ( $currentstep == $STEP_FORM_LIST)
      {
        $travelexpensetypeid = 0;
        $currentstep = $STEP_FORM;
      }
      else
      {
        $travelexpensetypeid = $_POST['travelexpensetypeid'] + 0;
      }
    
      if ($travelexpensetypeid > 0)
      {
        $query .= ' travelexpensetypeid=? and';
        array_push($query_prm,$travelexpensetypeid);
      }
      if ($ds_showdeleteditems != 1)
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
      $query .= ' travelexpensetypeid=?';
      $query_prm = array($travelexpensetypeid);
    }

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
    echo '<p>' . d_trad('noresult') . '</p>';
  }
  else
  {    
    if ( $currentstep == $STEP_FORM)
    {?>
      <table class="report">
      <thead>
        <th><?php echo d_trad('travelexpensetype'); ?></th>   
        <th><?php echo d_trad('refundlimit'); ?></th>        
        <th><?php echo d_trad('refundlimitvat'); ?></th>        
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
      $travelexpensetypeid = $row[$r]['travelexpensetypeid'];
      $href = 'hr.php?hrmenu=travelexpensetype&step=' . $STEP_FORM_MODIFY . '&travelexpensetypeid=' . $travelexpensetypeid;
      echo d_tr();   
      
      $travelexpensetypenamedisplayed = d_output($row[$r]['travelexpensetypename']);
      if ( strlen($travelexpensetypenamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $travelexpensetypenamedisplayed = substr($travelexpensetypenamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $travelexpensetypenamedisplayed. '</a></td>';  
      echo '<td align=right><a href="' . $href . '">' . myfix($row[$r]['refundlimit']). '</a></td>'; 
      echo '<td align=right><a href="' . $href . '">' . myfix($row[$r]['refundlimitVAT']). '</a></td>'; 
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
    echo '</table>';
  }
  else if ($numrows > 0 || $currentstep == $STEP_FORM_ADD )
  {
    echo '<tr><td>' . d_trad('travelexpensetype:') . '</td>';
    echo '<td><textarea name="travelexpensetypename" rows=1 cols=' . $MAX_LENGTH_DISPLAYED . '>' . d_output($row[0]['travelexpensetypename']) . '</textarea></td></tr>';
    echo '<tr><td>' . d_trad('refundlimit:') . '</td><td><input name="refundlimit" value="' . d_input($row[0]['refundlimit'],'decimal') .'"</td></tr>';    
    echo '<tr><td>' . d_trad('refundlimitvat:') . '</td><td><input name="refundlimitvat" value="' . d_input($row[0]['refundlimitVAT'],'decimal') .'"</td></tr>';    
    if ($currentstep != $STEP_FORM_ADD)
    {
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="travelexpensetypeid" value=' . $travelexpensetypeid .'>';
    }
    echo '</table>';    
  }
} ?>

<input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
<input type=hidden name="numrows" value="<?php echo $numrows; ?>">
<?php
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
?>
</table>
</form>