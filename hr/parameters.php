<?php

# TODO IMPORTANT replace into => on duplicate key update

#input $hr_parametername

$hr_parametername = $_GET['hr_parametername'];
if (!isset($hr_parametername)) { $hr_parametername = $_POST['hr_parametername'];}
$parameterterm = $_SESSION['ds_term_' . $hr_parametername];

if (!isset($parameterterm)) 
{ 
  $parameterterm = d_trad($hr_parametername);
  $parameterterms = d_trad($hr_parametername . 's');
}
else
{
  $parameterterm = $parameterterm;
  $parameterterms = $parameterterm . 's';  
}

require('preload/' . $hr_parametername .'.php');

$STEP_FORM_PARAM = 0;
$STEP_FORM = 1;
$STEP_FORM_LIST = 10;
$STEP_FORM_ADD = 2;
$STEP_FORM_MODIFY = 3;
$STEP_FORM_VALIDATE_ADD = 4;
$STEP_FORM_VALIDATE_MOD = 5;

$MAX_LENGTH_DISPLAYED = 60;

$ds_term_employeedepartment = $_SESSION['ds_term_employeedepartment'];
$ds_term_employeesection = $_SESSION['ds_term_employeesection'];

if ( $_POST['submitform'] == d_trad('add') ) { $currentstep =  $STEP_FORM_ADD;}
#to have the list directly
if ($currentstep == $STEP_FORM_PARAM) { $currentstep = $STEP_FORM;}

#title
switch ($currentstep)
{
  case $STEP_FORM:
    echo '<h2>' . $parameterterms . '</h2>';
    break;
    
  case $STEP_FORM_ADD:
    echo '<h2>' . d_trad('addparam',$parameterterm) . '</h2>';      
    break;   

  case $STEP_FORM_MODIFY:
    echo '<h2>' . d_trad('modifyparam',$parameterterm) . '</h2>';
    $parameterid = $_GET['parameterid'];    
    break;   

  case $STEP_FORM_VALIDATE_ADD:
    echo '<h2>' . $parameterterms . '</h2>';
    break;   

  case $STEP_FORM_VALIDATE_MOD:
    echo '<h2>' . $parameterterms . '</h2>';
    break;       
}

$numrows = 0;
# Form to choose wich kind of $hr_parametername
if ($currentstep == $STEP_FORM_PARAM)
{
  echo '<h2>' . $parameterterms . '</h2>'; ?>
  <form method="post" action="hr.php"><table>
  <?php $dp_itemname = $hr_parametername; $dp_allowall = 1; $dp_noblank = 1;$dp_description = $parameterterm;
  require('inc/selectitem.php');
  if ($num_results == 0) 
  { 
    $currentstep = $STEP_FORM;
  }
  else
  {
    ?>
    <tr><td colspan=2>&nbsp;</td></tr>
    <tr><td colspan="2" align="center">
    <input type=hidden name="step" value="1">
    <input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
    <input type=hidden name="hr_parametername" value="<?php echo $hr_parametername; ?>">
    <input type="submit" name="submitform" value="<?php echo d_trad('add');?>">
    <input type="submit" name="submitform" value="<?php echo d_trad('list');?>"></td></tr>  
    </table></form><?php
  }
}
else if ($currentstep == $STEP_FORM_VALIDATE_ADD || $currentstep == $STEP_FORM_VALIDATE_MOD)
{
  # save
  if ($currentstep == $STEP_FORM_VALIDATE_MOD) { $parameterid = $_POST['parameterid'] +0;}
  $parametername = d_input($_POST['parametername']); 
  #for employeecategory  
  $numdailylogs = $_POST['numdailylogs'] + 0;  
  #for contract
  $salaried_exempt = $_POST['salaried_exempt'] + 0;  
  #for employeesection
  $employeedepartmentid = $_POST['employeedepartmentid'] + 0; 
  $deleted = $_POST['deleted'] + 0;  
  
  $query = 'REPLACE INTO ' .$hr_parametername . ' (' . $hr_parametername . 'id,' . $hr_parametername . 'name,deleted';
  if ($hr_parametername == 'employeecategory')
  {
    $query .= ',numdailylogs) values (?,?,?,?)';
    $query_prm = array($parameterid,$parametername,$deleted,$numdailylogs);
  }
  else if ($hr_parametername == 'contract')
  {
    $query .= ',salaried_exempt) values (?,?,?,?)';
    $query_prm = array($parameterid,$parametername,$deleted,$salaried_exempt);  
  }
  else if ($hr_parametername == 'employeesection')
  {
    $query .= ',employeedepartmentid) values (?,?,?,?)';
    $query_prm = array($parameterid,$parametername,$deleted,$employeedepartmentid);  
  }  
  else
  {
    $query .= ') values (?,?,?)';
    $query_prm = array($parameterid,$parametername,$deleted);    
  }

  require ('inc/doquery.php');
  if ( $num_results > 0 )
  {
    $parameterid = $query_insert_id;
    if ($currentstep == $STEP_FORM_VALIDATE_ADD)
    { 
      echo '<p>' . d_trad('successaddparam:',array($parameterterm,d_output($parametername))) . '</p><br>';            
    }
    else
    {
      echo '<p>' . d_trad('modifiedparam:',array($parameterterm,d_output($parametername))) . '</p><br>';              
    }
  }  
  $currentstep = $STEP_FORM_LIST;
}
  
if ( $currentstep > $STEP_FORM_PARAM)
{
  if ($currentstep != $STEP_FORM_ADD)
  {
    # pre-filled form 
    $query = 'select * from ' . $hr_parametername . ' where';
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
        $parameterid = $_POST['parameterid'] + 0;   
      }
    
      if ($parameterid > 0)
      {
        $query .= ' ' . $hr_parametername . 'id=? and';
        array_push($query_prm,$parameterid);
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
      $query .= ' '. $hr_parametername . 'id=?';
      $query_prm = array($parameterid);
    }
    $query .= ' order by ' . $hr_parametername . 'name';
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
        <th><?php echo $parameterterm; ?></th>       
        <?php
        if ($hr_parametername == 'employeecategory')
        {
          echo '<th>' . d_trad('numdailylogs') . '</th>';
        }
        else if ($hr_parametername == 'contract')
        {     
          echo '<th>' . d_trad('salariedexempt') . '</th>';        
        } 
        else if ($hr_parametername == 'employeesection')
        {     
          echo '<th>' . $ds_term_employeedepartment . '</th>';        
        }         
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
      $parameterid = $row[$r][$hr_parametername .'id'];
      $href = 'hr.php?hrmenu=parameters&hr_parametername=' . $hr_parametername .'&step=' . $STEP_FORM_MODIFY . '&parameterid=' . $parameterid;
      echo d_tr();   
      
      $parameternamedisplayed = d_output($row[$r][$hr_parametername .'name']);
      if ( strlen($parameternamedisplayed) >= $MAX_LENGTH_DISPLAYED ) { $parameternamedisplayed = substr($parameternamedisplayed,0,$MAX_LENGTH_DISPLAYED) . '...'; }
      echo '<td><a href="' . $href . '">' . $parameternamedisplayed. '</a></td>';  
      if ($hr_parametername == 'employeecategory')
      {
        echo '<td><a href="' . $href . '">' . $row[$r]['numdailylogs'] . '</td>';
      } 
      else if ($hr_parametername == 'contract')
      {     
        echo '<td align=center><a href="' . $href . '">';
        if ($row[$r]['salaried_exempt'] == 1) { echo '&radic;'; }
        echo '</a></td>';    
      }  
      else if ($hr_parametername == 'employeesection')
      {     
        echo '<td align=center><a href="' . $href . '">';
        $did = $row[$r]['employeedepartmentid'];
        if ($did > 0 ) { require('preload/employeedepartment.php'); echo $employeedepartmentA[$did]; }
        echo '</a></td>';    
      }        
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
    echo '<tr><td>' . $parameterterm . ':</td>';
    echo '<td><textarea name="parametername" rows=1 cols=' . $MAX_LENGTH_DISPLAYED . '>' . d_output($row[0][$hr_parametername . 'name']) . '</textarea></td></tr>';
    if ($hr_parametername == 'employeecategory')
    {
      echo '<tr><td>' . d_trad('numdailylogs:') . '</td><td><input type="number" name="numdailylogs" value="' . $row[0]['numdailylogs'] . '" size=10></td></tr>';
    }  
    else if ($hr_parametername == 'contract')
    {     
      echo '<tr><td>' . d_trad('salariedexempt:') . '</td><td><input type=checkbox name="salaried_exempt" value=1 ';
      if ($row[0]['salaried_exempt']) { echo ' checked '; }
      echo '></td></tr>';
    }
    else if ($hr_parametername == 'employeesection')
    {     
      $dp_itemname = 'employeedepartment'; $dp_description = $ds_term_employeedepartment;$dp_allowall = 0; $dp_blank = 1; $dp_selectedid = $row[0]['employeedepartmentid'];
      require('inc/selectitem.php');   
    }      
    if ($currentstep != $STEP_FORM_ADD)
    {
      echo '<tr><td>' . d_trad('deleted:') . '</td><td><input type=checkbox name="deleted" value=1 ';
      if ($row[0]['deleted']) { echo ' checked '; }
      echo '></td></tr>';
      echo '<input type=hidden name="parameterid" value=' . $parameterid .'>';
    }
    echo '</table>';    
  }
} ?>
<input type=hidden name="hr_parametername" value="<?php echo $hr_parametername; ?>">
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