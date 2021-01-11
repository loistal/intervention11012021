
<?php

if (!isset($trainingA)) { require('preload/training.php'); }

$STEP_FORM_TRAINING = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_NB_PLACES = 500;
$MAX_LENGTH_DISPLAYED = 60;
$ds_numtotalemployees = $_SESSION['ds_numtotalemployees'];
if ( $_POST['submitformtraining'] == d_trad('add') ) { $currentstep =  $STEP_FORM_ADD;}

#title
switch ($currentstep)
{
  case $STEP_FORM:
    echo '<h2>' . d_trad('planatraining') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('planatraining') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifytrainingplanning') . '</h2>';
    $trainingplanningid = $_GET['trainingplanningid'];    
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . d_trad('planatraining') . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('modifytrainingplanning') . '</h2>';
    break;       
}

$numrows = 0;
# Form to choose wich kind of training
if ($currentstep == $STEP_FORM_TRAINING)
{
  echo '<h2>' . d_trad('planatraining') . '</h2>'; ?>
  <form method="post" action="hr.php"><table>
  <?php $dp_itemname = 'training'; $dp_allowall = 1;$dp_noblank = 1; $dp_description = d_trad('trainingname');
  require('inc/selectitem.php');?>

  <tr><td colspan=2>&nbsp;</td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <input type="submit" name="submitformtraining" value="<?php echo d_trad('add');?>"> 
  <input type="submit" name="submitformtraining" value="<?php echo d_trad('list');?>">
  </td></tr>
  </table></form><?php
}
else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
{
  # save
  if ($currentstep == $STEP_FORM_VALIDATE_MOD) { $trainingplanningid = $_POST['trainingplanningid'] +0;}
  $trainingid = $_POST['trainingid'] + 0;     
  $trainingname = $trainingA[$trainingid];
  $datename = 'startdate'; require('inc/datepickerresult.php');   
  $datename = 'stopdate'; require('inc/datepickerresult.php');   
  $place = $_POST['place'];
  $nbplaces = $_POST['nbplaces']+0;
  $nbreservedplaces = $_POST['nbreservedplaces'] + 0;
  $pricebyemployee = $_POST['pricebyemployee'] + 0;    
  $deleted = $_POST['deleted']+0;  
  
  if( $trainingid == 0)
  {
    echo '<p class=alert>' . d_trad('trainingmandatory') . '</p>';
    if ($currentstep == $STEP_FORM_VALIDATE_ADD ) { $currentstep = $STEP_FORM_ADD;}
  }
  else
  {
    $query = 'REPLACE INTO trainingplanning (trainingplanningid,trainingid,startdate,stopdate,place,nbplaces,nbreservedplaces,pricebyemployee,deleted) values (?,?,?,?,?,?,?,?,?)';
    $query_prm = array($trainingplanningid,$trainingid,$startdate,$stopdate,$place,$nbplaces,$nbreservedplaces,$pricebyemployee,$deleted);
    require ('inc/doquery.php');
    if ( $num_results > 0 )
    {
      $trainingplanningid = $query_insert_id;
      if ($currentstep == $STEP_FORM_VALIDATE_ADD)
      {
        echo '<p>' . d_trad('trainingplanningadded',d_output($trainingname)) . '</p><br>';   
      }
      else
      {
        echo '<p>' . d_trad('trainingplanningmodified') . '</p><br>';      
      }
    }  
  }
}

if ( $currentstep > $STEP_FORM_TRAINING)
{
  if ($currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form 
    $query = 'select * from trainingplanning where';
    $query_prm = array();
    
    if ( $currentstep == $STEP_FORM)
    {
      $trainingid = $_POST['trainingid'] + 0;       
    
      if ($trainingid > 0)
      {
        $query .= ' trainingid=? and';
        array_push($query_prm,$trainingid);
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
      $query .= ' order by startdate';
    }
    else
    {
      $query .= ' trainingplanningid=?';
      $query_prm = array($trainingplanningid);
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
        <th><?php echo d_trad('reference'); ?></th>
        <th><?php echo d_trad('trainingname'); ?></th>
        <th><?php echo d_trad('startdate'); ?></th>        
        <th><?php echo d_trad('stopdate'); ?></th>
        <th><?php echo d_trad('place'); ?></th>
        <th><?php echo d_trad('nbplaces'); ?></th>
        <th><?php echo d_trad('nbreservedplaces'); ?></th>
        <th><?php echo d_trad('pricebyemployee'); ?></th>        
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
      $trainingid = $row[$r]['trainingid'];
      $trainingplanningid = $row[$r]['trainingplanningid'];
      $href = 'hr.php?hrmenu=trainingplanning&step=' . $STEP_FORM_MODIFY . '&trainingplanningid=' . $trainingplanningid;
      echo d_tr();
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
      
      $nbplaces = $row[$r]['nbplaces']+0;
      $nbreservedplaces = $row[$r]['nbreservedplaces']+0;
      echo '<td style="align:right;"><a href="' . $href . '">' . $nbplaces . '</a></td>';
      echo '<td style="align:right;"><a href="' . $href . '">' . $nbreservedplaces . '</a></td>';
      echo '<td style="align:right;"><a href="' . $href . '">' . myfix($row[$r]['pricebyemployee']) . '</a></td>';
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
    # to prefill form with previous choices (ADD) or result (MODIFY)
    if ( $currentstep == $STEP_FORM_ADD)
    {
      $trainingid = $_POST['trainingid'] +0;
      if($trainingid == 0){ $trainingid = $_GET['trainingid'] +0;}
      $place = '';
      $nbplaces = 0;
      $nbreservedplaces = 0;        
      $pricebyemployee = 0;        
    }
    else
    {
      $trainingid = $row[0]['trainingid'];
      $startdate = $row[0]['startdate'];     
      $stopdate = $row[0]['stopdate'];     
      $trainingplanningid = $row[0]['trainingplanningid'];
      $place = $row[0]['place'];    
      $nbplaces = $row[0]['nbplaces']+0;
      $nbreservedplaces = $row[0]['nbreservedplaces']+0;  
      $pricebyemployee = $row[0]['pricebyemployee']+0;  
    }
    $trainingname = $trainingA[$trainingid];
    $dp_itemname = 'training'; $dp_description = d_trad('trainingname');$dp_selectedid = $trainingid; require('inc/selectitem.php');?>
    
    <tr><td><?php echo d_trad('startdate:'); ?></td>
    <td><?php $datename = 'startdate';$selecteddate = $startdate;$dp_datepicker_min='2014-01-01';require('inc/datepicker.php');?></td></tr>
    
    <tr><td><?php echo d_trad('stopdate:'); ?></td>
    <td><?php $datename = 'stopdate';$selecteddate = $stopdate;require('inc/datepicker.php');?></td></tr>  
    
    <tr><td><?php echo d_trad('trainingplace:'); ?></td><?php
    echo '<td><textarea name="place" rows=1 cols=' . $MAX_LENGTH_DISPLAYED . '>' . d_input($place) . '</textarea></td></tr>';?>
    
    <tr><td><?php echo d_trad('nbplaces:'); ?></td>
    <td><select name="nbplaces">
    <?php for($i=0;$i<=$MAX_NB_PLACES;$i++)
    {
      $selected = '';
      if ( $nbplaces == $i) { $selected = ' SELECTED'; }
      echo '<option value=' . $i . $selected . '>' . $i . '</option>';
    }?>
    </select></td></tr>
    
    <?php 
    if ( $currentstep != $STEP_FORM_ADD )
    {?>
      <tr><td><?php echo d_trad('nbreservedplaces:'); ?></td>
      <td><select name="nbreservedplaces">
      <?php for($i=0;$i<=$ds_numtotalemployees;$i++)
      {
        $selected = '';   
        if ( $nbreservedplaces == $i) { $selected = ' SELECTED'; }
        echo '<option value=' . $i . $selected . '>' . $i . '</option>';
      }?>
      </select></td></tr><?php 
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="trainingplanningid" value=' . $trainingplanningid .'>';
    }?>
    </select></td></tr>
    <tr><td><?php echo d_trad('pricebyemployee:'); ?></td>
    <td><input name=pricebyemployee value="<?php echo $pricebyemployee;?>"></td><tr>
    </table><?php   
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
  else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
}
?>
</table>
</form>