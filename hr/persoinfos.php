<?php

# TODO IMPORTANT replace into => on duplicate key update

# need refactor
# especially images should be save with employeeid

require('preload/employee.php');

$PA['employeeid'] = 'uint';
require('inc/readpost.php');

if ($employeeid > 0)
{
  $employeename = $employeeA[$employeeid];
  $employeelastname = $employee_lastnameA[$employeeid];
  $employeefirstname = $employee_firstnameA[$employeeid];
  $employeemiddlename = $employee_middlenameA[$employeeid];
}

if (!isset($qualificationA)) { require('preload/qualification.php');}
if (!isset($qualificationA)) { $qualificationA = array(); }
$nbqualifications = count($qualificationA);

$STEP_FORM_PERSOINFOS = 0;
$STEP_FORM_VALIDATE_MOD = 2;
$MAX_LENGTH_DISPLAYED = 60;

switch($currentstep)
{
  # Form to choose wich employee
  case $STEP_FORM_PERSOINFOS:
    $title = d_trad('persoinfos');
    require('hr/chooseemployee.php');
    break;

  # save
  case $STEP_FORM_VALIDATE_MOD:
    $title = d_trad('modifyemployeepersoinfos');  
    echo '<h2>' . $title . '</h2>';
    $sex = $_POST['sex'];
    $countryid = $_POST['countryid'];
    $workpermit = $_POST['workpermit']+0;
    $hiringdate = $_POST['hiringdate'];
    $photoid = $_POST['photoid']+0;
    $deletephotoid = $_POST['deletephotoid']+0;
    $datename = 'dateofbirth'; require('inc/datepickerresult.php');
    $placeofbirth = $_POST['placeofbirth'] . '';
    $familysituationid = $_POST['familysituationid']+0;
    $numchildren = $_POST['numchildren'];
    $employeechildidA = array();
    $employeechildnameA = array();
    $employeechilddateofbirthA = array();
    for ($c=0;$c<$numchildren;$c++)
    {
      $employeechildidA[$c] = $_POST['employeechildid'.$c];    
      $employeechildnameA[$c] = $_POST['employeechildname'.$c];   
      $datename = 'employeechilddateofbirth'.$c; require('inc/datepickerresult.php');      
      $employeechilddateofbirthA[$c] = $$datename;
    }
    $dn = $_POST['dn'];
    $telnumber1 = $_POST['telnumber1'];
    $telnumber2 = $_POST['telnumber2'];
    $geoaddress = $_POST['geoaddress'];
    $geoimgid = $_POST['geoimgid']+0;
    $deletegeoimgid = $_POST['deletegeoimgid']+0;
    $postaladdress1 = $_POST['postaladdress1'];
    $postaladdress2 = $_POST['postaladdress2'];
    $postalcode = $_POST['postalcode'];
    $townid = $_POST['townid']+0;
    $nameincaseof1 = $_POST['nameincaseof1'];
    $nameincaseof2 = $_POST['nameincaseof2'];
    $telnumberincaseof1 = $_POST['telnumberincaseof1'];
    $telnumberincaseof2 = $_POST['telnumberincaseof2'];
    $deleted = $_POST['deleted'] + 0;    
    
    #delete photo
    if ($deletephotoid > 0)
    {
      $query = 'delete from image where imageid=?';
      $query_prm = array($deletephotoid);
      require ('inc/doquery.php');
    }
    
    #delete geoimg    
    if ($deletegeoimgid > 0)
    {
      $query = 'delete from image where imageid=?';
      $query_prm = array($deletegeoimgid);
      require ('inc/doquery.php');
    }
    
    #save photo
		$photofilename = $_FILES['photofile']['tmp_name'];
    if (is_uploaded_file($photofilename))
    {
      $photofile = file_get_contents($photofilename);
			$photoimagetype = pathinfo($_FILES['photofile']['name'], PATHINFO_EXTENSION);
      if ($photofile)
      {
        $query = 'insert into image (imagetext,image,imagetype) values (?,?,?)';
        $query_prm = array('employeeid' . $employeeid . ': persoinfosphoto',$photofile,$photoimagetype);
        require ('inc/doquery.php');
        $photoid = $query_insert_id;
      }
    }
    
    #save geoimg
		$geofilename = $_FILES['geoimgfile']['tmp_name'];		
    if (is_uploaded_file($geofilename))
    {
      $geoimgfile = file_get_contents($geofilename);
			$geoimagetype = pathinfo($_FILES['geoimgfile']['name'], PATHINFO_EXTENSION);			
      if ($geoimgfile)
      {
        $query = 'insert into image (imagetext,image,imagetype) values (?,?,?)';
        $query_prm = array('employeeid' . $employeeid . ': persoinfosgeoimg',$geoimgfile,$geoimagetype);
        require ('inc/doquery.php');
        $geoimgid = $query_insert_id;
      }
    }
    
    #verify if record already exists
    $query = 'select employeepersoinfosid from employeepersoinfos where employeeid=?';
    $query_prm = array($employeeid);
    require('inc/doquery.php');
    $employeepersoinfosid = NULL;
    if ( $num_results > 0 )
    {
      $employeepersoinfosid =  $query_result[0]['employeepersoinfosid'];
    }
    $query = 'REPLACE INTO employeepersoinfos (employeepersoinfosid,employeeid,sex,countryid,workpermit,photoid,dateofbirth,placeofbirth,familysituationid,numchildren,dn,telnumber1,telnumber2,geoaddress,geoimgid,postaladdress1,postaladdress2,postalcode,townid,nameincaseof1,nameincaseof2,telnumberincaseof1,telnumberincaseof2) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
    $query_prm = array($employeepersoinfosid,$employeeid,$sex,$countryid,$workpermit,$photoid,$dateofbirth,$placeofbirth,$familysituationid,$numchildren,$dn,$telnumber1,$telnumber2,$geoaddress,$geoimgid,$postaladdress1,$postaladdress2,$postalcode,$townid,$nameincaseof1,$nameincaseof2,$telnumberincaseof1,$telnumberincaseof2);
    require ('inc/doquery.php');
    if ( $num_results > 0 ){ $num_changes ++;}  
 
    #children
    for ($c=0;$c<$numchildren;$c++)
    {
      $employeechildid = $employeechildidA[$c];
      #to insert new one
      if ($employeechildid == 0) { $employeechildid = NULL;}
      $query = 'REPLACE INTO employeechild (employeechildid,employeeid,employeechildname,dateofbirth) values (?,?,?,?)';
      $query_prm = array($employeechildid,$employeeid,$employeechildnameA[$c],$employeechilddateofbirthA[$c]);
      require ('inc/doquery.php');
      if ( $num_results > 0 ){ $num_changes ++;}
    }

    #qualification
    $num_changes = 0;
    for($q=0;$q<count($qualificationA);$q++)
    {
      $qualification = $_POST['qualification'.$q] +0;
      if ($qualification === 1 ) { $deleted = 0;} else { $deleted = 1;}
      $qualificationid = $_POST['qualificationid'.$q] +0;
      $qualifimageid = $_POST['qualifimageid'.$q] +0;
      $deletequalifimageid = $_POST['deletequalifimageid'.$q] +0;
      $obtainingdate = $_POST['obtainingdate'.$q];
      $expiredate = $_POST['expiredate'.$q];
      $number = $_POST['number'.$q];
      
      #save image
			$qualiffilename = $_FILES['qualifimagefile'.$q]['tmp_name'];
      if (is_uploaded_file($qualiffilename))
      {
        $qualifimagefile = file_get_contents($qualiffilename);
				$qualifimagetype = pathinfo($_FILES['qualifimagefile'.$q]['name'], PATHINFO_EXTENSION);				
        if ($qualifimagefile)
        {
          $query = 'insert into image (imagetext,image,imagetype) values (?,?,?)';
          $query_prm = array('employeeid' . $employeeid . ': image de qualification ' .$qualificationid,$qualifimagefile,$qualifimagetype);
          require ('inc/doquery.php');
          $qualifimageid = $query_insert_id;
        }
      }
      
      #verify if record already exists
      $query = 'select employeequalificationid from employeequalification where employeeid=? and qualificationid=?';
      $query_prm = array($employeeid,$qualificationid);
      require('inc/doquery.php');
      $employeequalificationid = NULL;
      if ( $num_results > 0 )
      {
        $employeequalificationid =  $query_result[0]['employeequalificationid'];
      }
      
      #delete image
      if ($deletequalifimageid > 0 || ($employeequalificationid > 0 && $deleted === 1 && $qualifimageid > 0))
      {    
        $query = 'delete from image where imageid=?';
        $query_prm = array($deletequalifimageid);
        require ('inc/doquery.php');
        $qualifimageid = 0;
      }
      
      if ($employeequalificationid > 0 || ( $employeequalificationid == NULL && $deleted === 0))
      {
        $query = 'REPLACE INTO employeequalification (employeequalificationid,employeeid,qualificationid,imageid,obtainingdate,expiredate,number,deleted) values (?,?,?,?,?,?,?,?)';
        $query_prm = array($employeequalificationid,$employeeid,$qualificationid,$qualifimageid,$obtainingdate,$expiredate,$number,$deleted);
        require ('inc/doquery.php');
        if ( $num_results > 0 ){ $num_changes ++;}

      }
    }
    if ( $num_changes > 0 )
    {
      $row = $query_result[0]; 
      echo '<p>' . d_trad('modifiedemployeepersoinfos',$employeename) . '</p><br>';      
    }
    break;
}
  
if ( $currentstep > $STEP_FORM_PERSOINFOS )
{
  # Form pre-filled
  $query = 'select * from employeepersoinfos where employeeid=?';
  $query_prm = array($_POST['employeeid']+0);
  require('inc/doquery.php');
  $row = NULL;
  if ($num_results > 0 )
  {
    $row = $query_result[0]; 
  }
  
  #get children
  $query = 'select * from employeechild where employeeid=?';
  $query_prm = array($_POST['employeeid']+0);
  require('inc/doquery.php');
  $rowchild = NULL;$numchild = $num_results;
  if ($numchild > 0 )
  {
    $rowchild = $query_result; 
  }
  
  #get photo
  $photoid = $row['photoid'];
  if ( $photoid != NULL )
  {
    $query = 'select imageid,imagetext,imageorder,imagetype from image where imageid=? order by imageorder,imageid';
    $query_prm = array($photoid);
    require('inc/doquery.php');
    if ($num_results > 0 )
    {
      $photo = $query_result[0]; 
    }
  }  
  
  #get geoimg
  $geoimgid = $row['geoimgid'];
  if ( $geoimgid != NULL )
  {
    $query = 'select imageid,imagetext,imageorder,imagetype from image where imageid=? order by imageorder,imageid';
    $query_prm = array($geoimgid);
    require('inc/doquery.php');
    if ($num_results > 0 )
    {
      $geoimg = $query_result[0]; 
    }
  }
  
  #qualification
  $query = 'select * from employeequalification where employeeid=? and deleted=0';
  $query_prm = array($_POST['employeeid']+0);
  require('inc/doquery.php');
  $numqualif = $num_results;$rowqualif = $query_result; 
  $employeequalifA = array();
  for ($q=0;$q<$numqualif;$q++)
  {
    $qualificationid = $rowqualif[$q]['qualificationid'];
    array_push($employeequalifA,$qualificationid);
  }
  ?>
  <form enctype="multipart/form-data" method="post" action="hr.php"><table>
  <tr><td colspan=5><b><?php echo d_trad('civilstatus'); ?></b></td></tr>
  <tr>
    <td><?php echo d_trad('lastname:'); ?></td>
    <td><input type="text" name="employeename" value="<?php echo $employeelastname; ?>" size=20 disabled></td>   	
		
    <td>&nbsp;&nbsp;&nbsp;</td>    
    <td><?php echo d_trad('photo:'); ?></td>
    <?php 
    if (isset($photo))
    {
      $photoid = $photo['imageid'];
      $photoimagetype = $photo['imagetype'];?>
      <td rowspan=7>
        <input type=hidden name="photoid" value="<?php echo $photoid; ?>">
				<?php if ($photoimagetype == 'pdf')
				{
					echo '<object type="text/hmlt" codetype="application/pdf" data="viewpdf.php?image_id=' . $photoid. '"></object>';
				}
				else
				{
					echo '<img src="viewimage.php?image_id=' . $photoid . '">';
				} ?>
				<br>

        <input type="checkbox" name="deletephotoid" value="<?php echo $photoid; ?>"> &nbsp; <?php echo d_trad('deletephoto'); ?>
      </td><?php 
    } 
    else
    {?>
      <td rowspan=3><input name="photofile" type="file" value="' . $_FILES['photofile']['name'] . '" size=50></td><?php
    } ?>    
  </tr>
  </tr>
	<tr>	
		<td><?php echo d_trad('firstname:'); ?></td>
    <td><input type="text" name="employeefirstname" value="<?php echo $employeefirstname; ?>" size=20 disabled></td>	
  </tr>
  <tr>	
		<td><?php echo d_trad('middlename:'); ?></td>
    <td><input type="text" name="employeemiddlename" value="<?php echo $employeemiddlename; ?>" size=20 disabled></td>	
  <tr>
    <td><?php echo d_trad('dateofbirth:'); ?></td>
    <td><?php $datename = 'dateofbirth'; $dp_datepicker_min = '1920-01-01';$dp_datepicker_max = $_SESSION['ds_curdate'];
    $dp_setempty=1;$selecteddate=$row['dateofbirth'];require('inc/datepicker.php');?></td>
  </tr>
  <tr>
    <td>Lieu de naissance:
    <td><input type="text" name="placeofbirth" value="<?php echo d_input($row['placeofbirth']); ?>" size=20>
 <tr>
    <td><?php echo d_trad('sex:'); ?></td>
    <td><select name=sex>
          <option value=0 <?php if ($row['sex'] == 0) { echo ' selected';} ?>></option>
          <option value=1 <?php if ($row['sex'] == 1) { echo ' selected';} ?>><?php echo d_trad('male');?></option>
          <option value=2 <?php if ($row['sex'] == 2) { echo ' selected';} ?>><?php echo d_trad('female');?></option>
        </select></td>
  </tr>
  <tr>
    <td><?php echo d_trad('dn:'); ?></td>
    <td><input type=text name="dn" value="<?php echo d_input($row['dn']); ?>" size=10></td>
  </tr>  
  <?php $dp_itemname = 'country'; $dp_description = d_trad('nationality'); $dp_selectedid = $row['countryid'];
  require('inc/selectitem.php'); ?>  
  <tr>
    <td><?php echo d_trad('workpermit:'); ?></td>
    <td><input type=checkbox value=1 name="workpermit" <?php if ($row['workpermit'] == 1){ echo ' checked';} ?>></td>
  </tr>    
  <tr><td colspan=5>&nbsp;</td></tr> 
  <tr><td colspan=5><b><?php echo d_trad('familysituation'); ?></b></td></tr>
  <?php $dp_itemname = 'familysituation'; $dp_description = d_trad('familysituation'); $dp_selectedid = $row['familysituationid'];
  require('inc/selectitem.php'); ?>  
  
  <tr>
    <td><?php echo d_trad('numchildren:'); ?></td>
    <td><input type=number min=0 size=2 name=numchildren value="<?php echo $row['numchildren'];?>"></td>
  </tr> 

  <?php
  for ($c=0;$c<$row['numchildren'];$c++)
  {
    $employeechildid = 0;
    $employeechildname = '';    
    $employeechilddateofbirth = NULL;
    if( $c < $numchild)
    {
      $employeechildid = $rowchild[$c]['employeechildid'];
      $employeechildname = $rowchild[$c]['employeechildname'];
      $employeechilddateofbirth = $rowchild[$c]['dateofbirth'];    
    }
    ?>
      <tr>
        <td><?php echo d_trad('childname:'); ?></td>
        <td><input type=text name="employeechildname<?php echo $c;?>" value="<?php echo d_input($employeechildname);?>"></td>
        <td><?php echo d_trad('dateofbirth:'); ?></td>
        <td><?php $datename = 'employeechilddateofbirth'.$c; $dp_datepicker_min=1950; $selecteddate=$employeechilddateofbirth; require('inc/datepicker.php');?></td>
        <input type=hidden name="employeechildid<?php echo $c;?>" value="<?php echo $employeechildid;?>"></td>
      </tr>
    <?php 
  } ?>
 
  <tr><td colspan=2>&nbsp;</td></tr><!--colspan = 2 because of photo's rowspan -->
  <tr><td colspan=5><b><?php echo d_trad('contactdetails'); ?></b></td></tr>
  <tr>
    <td><?php echo d_trad('telnumber1:'); ?></td>
    <td colspan=4><input type="text" name="telnumber1" value="<?php echo d_input($row['telnumber1']); ?>" size=20></td>
  </tr>
  <tr>
    <td><?php echo d_trad('telnumber2:'); ?></td>
    <td colspan=4><input type="text" name="telnumber2" value="<?php echo d_input($row['telnumber2']); ?>" size=20></td>
  </tr>
  <tr><td colspan=5>&nbsp;</td></tr>
  <tr><td colspan=5><b><?php echo d_trad('addresses'); ?></b></td></tr>
  <tr>
    <td><?php echo d_trad('geoaddress:'); ?></td>
    <td><input type="text" name="geoaddress" value="<?php echo d_input($row['geoaddress']); ?>" size=20></td>
    <td>&nbsp;&nbsp;&nbsp;</td>    
    <td><?php echo d_trad('geoimg:'); ?></td>
    <?php 
    if (isset($geoimg))
    {
      $geoimgid = $geoimg['imageid'];
      $geoimgtype = $geoimg['imagetype'];?>
      <td rowspan=11>
        <input type=hidden name="geoimgid" value="<?php echo $geoimgid; ?>">
				<?php if ($geoimgtype == 'pdf')
				{
					echo '<object type="text/hmlt" codetype="application/pdf" data="viewpdf.php?image_id=' . $geoimgid. '"></object>';
				}
				else
				{
					echo '<img src="viewimage.php?image_id=' . $geoimgid . '">';
				} ?>
        <br>
        <input type="checkbox" name="deletegeoimgid" value="<?php echo $geoimgid; ?>"> &nbsp; <?php echo d_trad('deletegeoimg'); ?>
      </td><?php 
    } 
    else
    {?>
      <td rowspan=1><input name="geoimgfile" type="file" value="' . $_FILES['geoimgfile']['name'] . '" size=50></td><?php
    } ?>
  </tr>
  <tr>
    <td><?php echo d_trad('postaladdress1:'); ?></td>
    <td><input type="text" name="postaladdress1" value="<?php echo d_input($row['postaladdress1']); ?>" size=20></td>
  </tr>
  <tr>
    <td>&nbsp;&nbsp;&nbsp;</td>      
    <td><input type="text" name="postaladdress2" value="<?php echo d_input($row['postaladdress2']); ?>" size=20></td>
  </tr>
  <tr>
    <td><?php echo d_trad('postalcode:'); ?></td>
    <td><input type="text" name="postalcode" value="<?php echo d_input($row['postalcode']); ?>" size=20></td>
  </tr>
  <tr>
    <td><?php echo d_trad('town:'); ?></td>
    <td><select name="townid">
      <?php $query = 'select townid,townname,islandname from town,island where town.islandid=island.islandid order by islandname,townname';
      require('inc/doquery.php');
      for ($i=0; $i < $num_results; $i++)
      {
        $selected = '';      
        if ($query_result[$i]['townid'] == $row['townid']){$selected = ' SELECTED';}        
        echo '<option value="' . $query_result[$i]['townid'] . '"' . $selected . '>' . d_output($query_result[$i]['islandname']) . '/' . d_output($query_result[$i]['townname']) . '</option>'; 
      }?>
    </td>
  </tr>
  <tr><td colspan=2>&nbsp;</td></tr>  
  <tr><td colspan=2><b><?php echo d_trad('contactsincaseof'); ?></b></td></tr>  
  <tr>
    <td><?php echo d_trad('name:'); ?></td>
    <td><input type="text" name="nameincaseof1" value="<?php echo d_input($row['nameincaseof1']); ?>" size=20></td>
  </tr>
  <tr>
    <td><?php echo d_trad('telnumber:'); ?></td>
    <td><input type="text" name="telnumberincaseof1" value="<?php echo d_input($row['telnumberincaseof1']); ?>" size=20></td>
  </tr>  
  <tr>
    <td><?php echo d_trad('name:'); ?></td>
    <td><input type="text" name="nameincaseof2" value="<?php echo d_input($row['nameincaseof2']); ?>" size=20></td>
  </tr>
  <tr>
    <td><?php echo d_trad('telnumber:'); ?></td>
    <td><input type="text" name="telnumberincaseof2" value="<?php echo d_input($row['telnumberincaseof2']); ?>" size=20></td></tr>
  <tr><td>&nbsp;</td></tr>

  <?php
  if(isset($qualificationA))
  {?>
    <tr><td colspan=5><b><?php echo d_trad('qualification'); ?></b></td></tr><?php
    $q = 0;
    foreach($qualificationA as $qualificationid=>$qualificationname)
    {
      $checked = '';
      $qualificationkey = $qualifimageid = 0;
      $number = '';    
      $obtainingdate = NULL;
      $expiredate = NULL;

      if (in_array($qualificationid,$employeequalifA)) 
      { 
        $checked = ' CHECKED'; 
        $qualificationkey = array_search($qualificationid,$employeequalifA);     
        $obtainingdate = $rowqualif[$qualificationkey]['obtainingdate'];
        $expiredate = $rowqualif[$qualificationkey]['expiredate'];
        $number = $rowqualif[$qualificationkey]['number'];
        $qualifimageid = $rowqualif[$qualificationkey]['imageid'];
      }  
      echo '<tr><td>' . $qualificationname .': &nbsp;</td><td><input type="checkbox" name="qualification'.$q . '" value=1 '. $checked .'>';
      echo '<input type="hidden" name="qualificationid'.$q . '" value="' . $qualificationid . '" '. $checked .'>';
      echo '&nbsp;&nbsp;&nbsp;Obtention:&nbsp;';
      echo '<input type=date name="obtainingdate' .$q.'" value="' . $obtainingdate . '">';
      echo '&nbsp;&nbsp;&nbsp;Expiration:&nbsp;';
      echo '<input type=date name="expiredate' .$q.'" value="' . $expiredate . '">';
      echo '&nbsp;&nbsp;&nbsp;' . d_trad('number:') . '&nbsp;';
      echo '<input type=text name="number' .$q.'" value="' . d_input($number) . '"></td>';   
      echo '<td>&nbsp;&nbsp;&nbsp;</td>';
      echo '<td>' . d_trad('scan:') . '</td>';

      #get image
      if ( $qualifimageid > 0 )
      {
        $query = 'select imageid,imagetext,imageorder,imagetype from image where imageid=? order by imageorder,imageid';
        $query_prm = array($qualifimageid);
        require('inc/doquery.php');
        if ($num_results > 0 )
        {
          $qualifimageid = $query_result[0]['imageid']; 
					$qualifimagetype = $query_result[0]['imagetype'];					
        }
        echo '<td>';
        echo '<input type=hidden name="qualifimageid' . $q .'" value="' .$qualifimageid . '">';
				if ($qualifimagetype == 'pdf')
				{
					echo '<object type="text/hmlt" codetype="application/pdf" data="viewpdf.php?image_id=' . $qualifimageid. '"></object>';
				}
				else
				{
					echo '<img src="viewimage.php?image_id=' . $qualifimageid . '">';
				}			
        echo '<br>';
        echo '<input type="checkbox" name="deletequalifimageid' . $q .'" value="' . $qualifimageid . '"> &nbsp;' . d_trad('deleteimage');
        echo '</td>';
      } 
      else
      {
        echo '<td><input name="qualifimagefile' . $q . '" type="file"';
        if (isset($_FILES['qualifimagefile'.$q]['name'])) { echo ' value="' . $_FILES['qualifimagefile'.$q]['name'] . '"'; }
        echo ' size=50>';
      }    
      
      $q++;
      echo '</tr>';
    }
  } ?>
  <tr><td>&nbsp;</td></tr> 
  <tr>
    <td><?php echo d_trad('deleted:'); ?></td>
    <td><input type="checkbox" name="deleted" value="1" <?php if ($row['deleted'] == 1 || $employee_deletedA[$employeeid] == 1) { echo ' CHECKED'; } ?>></td>
  </tr>
  <tr><td colspan="5" align="center">
  <input type=hidden name="step" value="<?php echo $STEP_FORM_VALIDATE_MOD;?>"><input type=hidden name="hrmenu" value="<?php echo $hrmenu; ?>">
  <?php echo '<input type=hidden name="employeeid" value="' . $_POST['employeeid'] . '">'; ?>
  <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr> 
  </table></form><?php
}
?>