<?php
if ($_SESSION['ds_menustyle'] == 5)
{
?>
<main role="main" >
  <nav id="side-nav">
  <div>
    <div class="close" id="closeSidenav"><span class="marker marker-open"></span></div>
    <ul>
      <li><span class="subtitle">Absence / Présence</span></li>
      <?php
      if ($_SESSION['ds_time_management'] == 1 && $_SESSION['ds_ishrsuperuser'])
      {
        echo '<li><a href="hr.php?hrmenu=badgeimportfile">', d_trad('importfile'), ' BioStar</a></li>';
      }
      if ($_SESSION['ds_time_management'] == 0)
      {
        echo '<li><a href="hr.php?hrmenu=employee_day">Ma journée de travail</a></li>';
      }
      echo '<li><a href="hr.php?hrmenu=request_absence">Demande d\'absence</a></li>';
      echo '<li><a href="hr.php?hrmenu=badge_employeemonth">Tableau de pointage</a></li>';
      if ($_SESSION['ds_ishrsuperuser'])
      {
        echo '<li><a href="hr.php?hrmenu=employee_month">Tableau Sommaire</a></li>';
        echo '<li><a href="hr.php?hrmenu=hr_badge_late">Rapport Retards</a></li>';
      }
      if ($_SESSION['ds_ishrsuperuser'])
      {
        echo '<li class="separator"></li>';
        ?>
        <li><a href="hr.php?hrmenu=payroll_overview"><?php echo d_trad('payroll'); ?></a></li>
        <li><a href="hr.php?hrmenu=payroll_advance">Avances</a></li>
        <li><a href="hr.php?hrmenu=payroll_reports">Rapports paie</a></li>
        <li class="separator"></li>
        <li><a href="hr.php?hrmenu=travelexpenses&step=1"><?php echo d_trad('travelexpense'); ?></a></li>
        <li class="separator"></li>
        <li><span class="subtitle"><?php echo d_trad('persofile'); ?></span></li>
        <li><a href="hr.php?hrmenu=listemployee"><?php echo d_trad('employees') ?></a></li>    
        <li><a href="hr.php?hrmenu=persoinfos"><?php echo d_trad('persoinfos'); ?></a></li>
        <li><a href="hr.php?hrmenu=employeereport">Rapport</a></li>
        <li><a href="hr.php?hrmenu=careerpath"><?php echo d_trad('careerpath'); ?></a></li>
        <li><a href="hr.php?hrmenu=disciplinaryfile"><?php echo d_trad('disciplinaryfile'); ?></a></li>
        <li><a href="hr.php?hrmenu=annualinterview"><?php echo d_trad('annualinterview'); ?></a></li>
        <li><a href="hr.php?hrmenu=medicalcheckup"><?php echo d_trad('medicalcheckup'); ?></a></li>
        <li class="separator"></li>
        <li><span class="subtitle"><?php echo d_trad('training'); ?></span></li>
        <li><a href="hr.php?hrmenu=trainingbudget"><?php echo d_trad('trainingbudget'); ?></a></li>
        <li><a href="hr.php?hrmenu=trainingoffer"><?php echo d_trad('trainingoffer'); ?></a></li>        
        <li><a href="hr.php?hrmenu=trainingplanning"><?php echo d_trad('plantraining'); ?></a></li> 
        <li><a href="hr.php?hrmenu=trainingemployeeplanning"><?php echo d_trad('reservetraining'); ?></a></li>
        <li><a href="hr.php?hrmenu=alerttraining"><?php echo d_trad('alerttraining'); ?></a></li>
        <li><a href="hr.php?hrmenu=trainingreportform"><?php echo d_trad('report'); ?></a></li>
        <li class="separator"></li>
        <li><span class="subtitle"><?php echo d_trad('parameters'); ?></span></li>
        <li><a href="hr.php?hrmenu=parameters&hr_parametername=employeecategory"><?php echo d_trad('employeecategorys') ?></a></li>          
        <li><a href="hr.php?hrmenu=parameters&hr_parametername=job"><?php echo d_trad('jobs') ?></a></li>           
        <li><a href="hr.php?hrmenu=parameters&hr_parametername=contract"><?php echo d_trad('contracts') ?></a></li> 
        <li><a href="hr.php?hrmenu=parameters&hr_parametername=familysituation"><?php echo d_trad('familysituations') ?></a></li> 
        <li><a href="hr.php?hrmenu=parameters&hr_parametername=qualification"><?php echo d_trad('qualifications') ?></a></li>   
        <li><a href="hr.php?hrmenu=travelexpensetype"><?php echo d_trad('travelexpensetypes'); ?></a></li>
        <li><a href="hr.php?hrmenu=weeklyhours">Horaires hebdomadaires</a></li>
        <?php
      }
      ?>
    </ul>
    <?php require('inc/copyright.php'); ?>
  </div>
</nav>
<div class="container" id="main-container">
<?php
}
else
{
?>
</div><div id="wrapper">
<div id="leftmenu">
<div class="selectaction">
  <div class="selectactiontitle">Absence / Présence</div>  
  <div class="selectactionlist">
  <?php
  if ($_SESSION['ds_time_management'] == 1 && $_SESSION['ds_ishrsuperuser'])
  {
    echo '<a class="leftmenu" href="hr.php?hrmenu=badgeimportfile">', d_trad('importfile'), ' BioStar</a><br>';
  }
  if ($_SESSION['ds_time_management'] == 0)
  {
    echo '<a class="leftmenu" href="hr.php?hrmenu=employee_day">Ma journée de travail</a><br>';
  }
  echo '<a class="leftmenu" href="hr.php?hrmenu=request_absence">Demande d\'absence</a><br>';
  echo '<a class="leftmenu" href="hr.php?hrmenu=badge_employeemonth">Tableau de pointage</a><br>';
  if ($_SESSION['ds_ishrsuperuser'])
  {
    echo '<a class="leftmenu" href="hr.php?hrmenu=employee_month">Tableau Sommaire</a><br>';
    echo '<a class="leftmenu" href="hr.php?hrmenu=hr_badge_late">Rapport Retards</a><br>';
  }
  ?>
  </div>
</div>

<?php

if ($_SESSION['ds_ishrsuperuser'])
{
  ?>
  <div class="selectaction">
    <div class="selectactionlist">
      <a class="leftmenu" href="hr.php?hrmenu=payroll_overview"><?php echo d_trad('payroll'); ?></a><br>
      <a class="leftmenu" href="hr.php?hrmenu=payroll_advance">Avances</a><br>
      <a class="leftmenu" href="hr.php?hrmenu=payroll_reports">Rapports paie</a><br>
    </div>
  </div>
  
  <div class="selectaction">
    <div class="selectactionlist"> 
      <a class="leftmenu" href="hr.php?hrmenu=travelexpenses&step=1"><?php echo d_trad('travelexpense'); ?></a><br>  
      <?php
      #travelexpensesreportform TODO completely new report           also allow users/managers to access travelexpenses
      ?>
    </div>
  </div>

  <div class="selectaction">
    <div class="selectactiontitle"><?php echo d_trad('persofile'); ?></div>  
    <div class="selectactionlist">  
      <a class="leftmenu" href="hr.php?hrmenu=listemployee"><?php echo d_trad('employees') ?></a><br>    
      <a class="leftmenu" href="hr.php?hrmenu=persoinfos"><?php echo d_trad('persoinfos'); ?></a><br>
      <?php
      echo '<a class="leftmenu" href="hr.php?hrmenu=employeereport">Rapport</a><br><br>';
      ?>
      <br>
      <a class="leftmenu" href="hr.php?hrmenu=careerpath"><?php echo d_trad('careerpath'); ?></a><br>
      <a class="leftmenu" href="hr.php?hrmenu=disciplinaryfile"><?php echo d_trad('disciplinaryfile'); ?></a><br>
      <a class="leftmenu" href="hr.php?hrmenu=annualinterview"><?php echo d_trad('annualinterview'); ?></a><br>
      <a class="leftmenu" href="hr.php?hrmenu=medicalcheckup"><?php echo d_trad('medicalcheckup'); ?></a><br>
    </div>
  </div>
  
  <?php
  
  echo '
  <div class="selectaction">
    <div class="selectactiontitle">', d_trad('training'),'</div>  
    <div class="selectactionlist">
      <a class="leftmenu" href="hr.php?hrmenu=trainingbudget">', d_trad('trainingbudget'),'</a><br>
      <a class="leftmenu" href="hr.php?hrmenu=trainingoffer">', d_trad('trainingoffer'),'</a><br>        
      <a class="leftmenu" href="hr.php?hrmenu=trainingplanning">', d_trad('plantraining'),'</a><br> 
      <a class="leftmenu" href="hr.php?hrmenu=trainingemployeeplanning">', d_trad('reservetraining'),'</a><br>
      <a class="leftmenu" href="hr.php?hrmenu=alerttraining">', d_trad('alerttraining'),'</a><br>
      <a class="leftmenu" href="hr.php?hrmenu=trainingreportform">', d_trad('report'),'</a>
    </div>
  </div>
  '
  ?>
  
  <div class="selectaction"> 
    <div class="selectactiontitle"><?php echo d_trad('parameters'); ?></div>  
    <div class="selectactionlist">
      <a class="leftmenu" href="hr.php?hrmenu=parameters&hr_parametername=employeecategory"><?php echo d_trad('employeecategorys') ?></a><br>          
      <a class="leftmenu" href="hr.php?hrmenu=parameters&hr_parametername=job"><?php echo d_trad('jobs') ?></a><br>           
      <a class="leftmenu" href="hr.php?hrmenu=parameters&hr_parametername=contract"><?php echo d_trad('contracts') ?></a><br> 
      <a class="leftmenu" href="hr.php?hrmenu=parameters&hr_parametername=familysituation"><?php echo d_trad('familysituations') ?></a><br> 
      <a class="leftmenu" href="hr.php?hrmenu=parameters&hr_parametername=qualification"><?php echo d_trad('qualifications') ?></a><br>   
      <a class="leftmenu" href="hr.php?hrmenu=travelexpensetype"><?php echo d_trad('travelexpensetypes'); ?></a><br>
      <a class="leftmenu" href="hr.php?hrmenu=weeklyhours">Horaires hebdomadaires</a><br>
    </div>
  </div>
  
  <?php
}

?>
</div>
<div id="mainprogram">
<?php
}
?>