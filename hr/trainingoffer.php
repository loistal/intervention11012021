<?php

require('preload/employeecategory.php');

$STEP_FORM_TRAINING = 0;
$STEP_FORM = 1;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED = 60;

if (isset($_POST['submitformtraining']) && $_POST['submitformtraining'] == d_trad('add') ) { $currentstep =  $STEP_FORM_ADD;}

#title
switch ($currentstep)
{
  case $STEP_FORM:
    echo '<h2>' . d_trad('trainingoffer') . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addtraining') . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifytraining') . '</h2>';
    $trainingid = $_GET['trainingid'];    
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . d_trad('trainingoffer') . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . d_trad('trainingoffer') . '</h2>';
    break;       
}

$numrows = 0;
# Form to choose wich kind of training
if ($currentstep == $STEP_FORM_TRAINING)
{
  echo '<h2>' . d_trad('trainingoffer') . '</h2>'; ?>
  <form method="post" action="hr.php"><table>
  <?php $dp_itemname = 'training'; $dp_allowall = 1; $dp_noblank = 1;$dp_description = d_trad('trainingname');
  require('inc/selectitem.php');?>
  
  <?php $dp_itemname = 'employeecategory'; $dp_allowall = 1;  $dp_noblank = 1;$dp_description = d_trad('employeecategory');
  require('inc/selectitem.php');?>
  
  <tr><td><?php echo d_trad('mandatory:'); ?></td>
  <td><input type=checkbox name="mandatory" value=1></td>
  <tr><td><?php echo d_trad('periodic:'); ?></td> 
  <td><select name="periodic">
    <option value=-1><?php echo d_trad('selectall'); ?></option>    
    <option value=0><?php echo d_trad('punctual'); ?></option>
    <?php
    for($p=6;$p<=60;$p+=6)
    {
      echo '<option value="' . $p . '">' . d_trad('periodicparam',$p) . '</option>';
    }
    ?>
  </select></td></tr>
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
  if ($currentstep == $STEP_FORM_VALIDATE_MOD) { $trainingid = $_POST['trainingid'] +0;}
  $trainingref = $_POST['trainingref'];    
  $trainingname = $_POST['trainingname'];    
  $employeecategoryid = $_POST['employeecategoryid'] + 0;    
  $mandatory = $_POST['mandatory'] + 0;    
  $periodic = $_POST['periodic'] + 0;  
  $deleted = $_POST['deleted'] + 0;  
  
  $query = 'REPLACE INTO training (trainingid,trainingref,trainingname,employeecategoryid,mandatory,periodic,deleted) values (?,?,?,?,?,?,?)';
  $query_prm = array($trainingid,$trainingref,$trainingname,$employeecategoryid,$mandatory,$periodic,$deleted);
  require ('inc/doquery.php');
  if ( $num_results > 0 )
  {
    $trainingid = $query_insert_id;
    if ($currentstep == $STEP_FORM_VALIDATE_ADD)
    {
      echo '<p>' . d_trad('trainingadded',$trainingname) . '</p><br>';   
    }
    else
    {
      echo '<p>' . d_trad('trainingmodified',$trainingname) . '</p><br>';      
    }
  }  
}
  
if ( $currentstep > $STEP_FORM_TRAINING)
{
  if ($currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form 
    $query = 'select * from training where';
    $query_prm = array();
    
    if ( $currentstep == $STEP_FORM)
    {
      $trainingid = $_POST['trainingid'] + 0; 
      //d_debug('trainingid',$trainingid);    
      $employeecategoryid = $_POST['employeecategoryid'] + 0;    
      $mandatory = $_POST['mandatory'] + 0;    
      $periodic = $_POST['periodic'] + 0;    
      $deleted = $_POST['deleted'] + 0;     
    
      if ($trainingid > 0)
      {
        $query .= ' trainingid=? and';
        array_push($query_prm,$trainingid);
      }
      if ($employeecategoryid > 0)
      {
        $query .= ' employeecategoryid=? and';
        array_push($query_prm,$employeecategoryid);
      }
      if ($mandatory > 0)
      {
        $query .= ' mandatory=? and';
        array_push($query_prm,$mandatory);
      }
      if ($periodic >= 0)
      {
        $query .= ' periodic=? and';
        array_push($query_prm,$periodic);
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
      $query .= ' trainingid=?';
      $query_prm = array($trainingid);
    }

    require ('inc/doquery.php');
    $numrows = $num_results;
    if ($numrows > 0 )
    {
      $row = $query_result; 
      $employeecategoryid = $row[0]['employeecategoryid'];   
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
        <th><?php echo d_trad('employeecategory'); ?></th>
        <th><?php echo d_trad('mandatory'); ?></th>
        <th><?php echo d_trad('periodic'); ?></th>
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
      $href = '';
      if ($ds_ishrsuperuser || $ismanager)
      {
        $href = '<a href="hr.php?hrmenu=trainingoffer&step=' . $STEP_FORM_MODIFY . '&trainingid=' . $trainingid . '">';
      }
      echo d_tr();
      $trainingrefdisplayed = d_output($row[$r]['trainingref']);
      if ( strlen($trainingrefdisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingrefdisplayed = substr($trainingrefdisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td>' . $href  . $trainingrefdisplayed. '</a></td>';   
      
      $trainingnamedisplayed = d_output($row[$r]['trainingname']);
      if ( strlen($trainingnamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $trainingnamedisplayed = substr($trainingnamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td>' . $href . $trainingnamedisplayed. '</a></td>';  
      
      $employeecategoryid = $row[$r]['employeecategoryid'];
      $employeecategoryname = '';
      if ($employeecategoryid > 0)
      {
        $employeecategoryname = $employeecategoryA[$employeecategoryid];
      }
      echo '<td>' . $href . d_output($employeecategoryname) . '</a></td>';

      echo '<td align=center>' . $href;
      if ($row[$r]['mandatory'] == 1) { echo '&radic;'; }
      echo '</a></td>';
      
      $periodic = $row[$r]['periodic'];
      $periodicdisplayed = '';
      if ($periodic == 0)
      {
        $periodicdisplayed = d_trad('punctual');
      }
      else if ($periodic > 0)
      {
        $periodicdisplayed = d_trad('periodicparam',$periodic);
      }
      echo '<td>' . $href . $periodicdisplayed . '</a></td>';   
      
      if ($currentstep != $STEP_FORM_ADD && $ds_showdeleteditems)
      {
        echo '<td align=center>' . $href;
        if ($row[$r]['deleted'] == 1) { echo '&radic;'; }
        echo '</a></td>';
      }
      echo '</tr>';     
    }
    echo '</table>';
  }
  else if ($numrows > 0 || $currentstep == $STEP_FORM_ADD )
  {
    if (!isset($row)) { $row[0]['trainingref'] = $row[0]['trainingname'] = $row[0]['mandatory'] = $row[0]['employeecategoryid'] = ''; }
    echo '<tr><td>' . d_trad('reference:') . '</td>';
    echo '<td><textarea name="trainingref" rows=1 cols=' . $MAX_LENGTH_DISPLAYED . '>' . d_input($row[0]['trainingref']) . '</textarea></td></tr>';
    echo '<tr><td>' . d_trad('trainingname:') . '</td>';
    echo '<td><textarea name="trainingname" rows=1 cols=' . $MAX_LENGTH_DISPLAYED . '>' . d_input($row[0]['trainingname']) . '</textarea></td></tr>';
    $dp_itemname = 'employeecategory';$dp_description = d_trad('employeecategory');$dp_selectedid = $row[0]['employeecategoryid']; require('inc/selectitem.php'); 
    echo '<tr><td>' . d_trad('mandatory:') . '</td>';
    echo '<td><input type=checkbox name="mandatory" value=1 ';if ($row[0]['mandatory']) { echo ' checked '; } echo '></td></tr>';
    echo '<tr><td>' . d_trad('periodic:') . '</td>';?>
    <td><select name="periodic">
      <option value=0><?php echo d_trad('punctual'); ?></option>
      <?php
      for($p=6;$p<=60;$p+=6)
      {
        $selected = '';
        if ( $p == $row[0]['periodic'] )
        {
          $selected = ' SELECTED';
        }
        echo '<option value="' . $p . '"' . $selected . '>' . d_trad('periodicparam',$p) . '</option>';
      } ?>
    </select></td></tr><?php
    if ($currentstep != $STEP_FORM_ADD)
    {
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="trainingid" value=' . $trainingid .'>';
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
  else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
  {
    echo '<input type=hidden name="step" value="' . $STEP_FORM_VALIDATE_MOD . '"><br><div align="center"><input type="submit" value="' . d_trad('modify') . '"></div>';
  } 
}
?>
</table>
</form>