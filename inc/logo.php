<?php
if (!isset($_SESSION['ds_hidetop']) || $_SESSION['ds_hidetop'] == 0)
{
  if (isset($_SESSION['ds_menustyle']) && $_SESSION['ds_menustyle'] == 3)
  {
    echo '<div id="header"><table class="transparent" style="width: 100%"><tr><td width=10% align=center>';
    if ($_SESSION['ds_companyname'] != '') { echo '<b>' . d_output($_SESSION['ds_companyname']) . '</b>'; }
    else { echo '<b>' . d_output($_SESSION['ds_customname']) . '</b>'; }
    if ($_SESSION['ds_displaydateandtime'])
    {
      echo '<br>' . datefix($_SESSION['ds_curdate'],'short') . ' ' . substr($_SESSION['ds_curtime'],0,8);
    }
    echo '<br>' , d_output($_SESSION['ds_username']);
    echo '<td width=80% align=right><a href="index.php"><img class="logo_s" alt="Système TEM" src="pics/logo.png" border=0 height=80></a> &nbsp; &nbsp; &nbsp; </table>';  
  }
  else
  {
    echo '<div id="header"><table class="transparent" style="width: 100%"><tr><td width=25% align=center>';
    if (isset($_SESSION['ds_userid']))
    {
      if ($_SESSION['ds_displaydateandtime'])
      {
        echo datefix($_SESSION['ds_curdate'],'short'),' ',substr($_SESSION['ds_curtime'],0,8),'<br>';
      }
      echo d_output($_SESSION['ds_username']),'<br><form class="form-transparent" method="post" action="logout.php"><button type="submit">Déconnexion</button></form>'; 
    }
    echo '<td align=center width=50%><a href="index.php"><img alt="Système TEM" src="pics/logo.png" border=0 height=80></a><td valign=center align=center width=25%>';
    if (isset($_SESSION['ds_customname']) && $_SESSION['ds_customname'] != "")
    {
      $ourlogofile = './custom_available/' . mb_strtolower($_SESSION['ds_customname']) . '.jpg';
      if (file_exists($ourlogofile)) { echo '<img alt="' . $_SESSION['ds_customname'] . '" src="' . $ourlogofile . '" border=0 style="max-height: 100px;">'; }
      else { echo '<b>' . d_output($_SESSION['ds_customname']) . '</b>'; } 
    }
    echo '</table>';
  }
}
?>