<?php

# TODO refactor

$user_admin_theme = 0;

if (isset($_POST['changelayoutandcolor']) && $_POST['changelayoutandcolor'] == 1)
{
  $layoutid = (int) $_POST['layoutid'];
  $colorthemeid = (int) $_POST['colorthemeid'];
  
  if ($layoutid == 4) { $_SESSION['ds_displayicons'] = 0; } # TODO BUG should update $_SESSION['ds_displayicons'] to correct value according to layoutid

  $query = 'SELECT * FROM color_theme WHERE themeid = ?';
  $query_prm = array($colorthemeid);
  require('inc/doquery.php');

  $result = $query_result[0];

  #We update the usertable of the user
  $query = 'UPDATE usertable SET bgcolor = ?, fgcolor = ?, linkcolor = ?, menucolor = ?, alertcolor = ?, infocolor = ?, formcolor = ?, inputcolor = ?, menubordercolor = ?, menufontcolor = ?, tablecolor = ?, tablecolor1 = ?, tablecolor2 = ?, tablecolorsub = ?, hovercolor = ?, nbtablecolors = ?, usetablecolorsub = ?, usehovercolor = ?, menustyle = ?, colorthemeid = ? WHERE userid = ?';
  $query_prm = array(
    $result['bgcolor'],
    $result['fgcolor'],
    $result['linkcolor'],
    $result['menucolor'],
    $result['alertcolor'],
    $result['infocolor'],
    $result['formcolor'],
    $result['inputcolor'],
    $result['menubordercolor'],
    $result['menufontcolor'],
    $result['tablecolor'],
    $result['tablecolor1'],
    $result['tablecolor2'],
    $result['tablecolorsub'],
    $result['hovercolor'],
    $result['nbtablecolors'],
    $result['usetablecolorsub'],
    $result['usehovercolor'],
    $layoutid,
    $colorthemeid,
    $_SESSION['ds_userid']
  );

  require('inc/doquery.php');

  $_SESSION['ds_menustyle'] = $layoutid;
  $_SESSION['ds_bgcolor'] = '#' . $result['bgcolor'];
  $_SESSION['ds_fgcolor'] = '#' . $result['fgcolor'];
  $_SESSION['ds_linkcolor'] = '#' . $result['linkcolor'];
  $_SESSION['ds_menucolor'] = '#' . $result['menucolor'];
  $_SESSION['ds_alertcolor'] = '#' . $result['alertcolor'];
  $_SESSION['ds_infocolor'] = '#' . $result['infocolor'];
  $_SESSION['ds_formcolor'] = '#' . $result['formcolor'];
  $_SESSION['ds_inputcolor'] = '#' . $result['inputcolor'];
  $_SESSION['ds_menubordercolor'] = '#' . $result['menubordercolor'];
  $_SESSION['ds_menufontcolor'] = '#' . $result['menufontcolor'];
  $_SESSION['ds_tablecolor'] = '#' . $result['tablecolor'];
  $_SESSION['ds_tablecolor1'] = '#' . $result['tablecolor1'];
  $_SESSION['ds_tablecolor2'] = '#' . $result['tablecolor2'];
  $_SESSION['ds_tablecolorsub'] = '#' . $result['tablecolorsub'];
  $_SESSION['ds_hovercolor'] = '#' . $result['hovercolor'];
  $_SESSION['ds_nbtablecolors'] = $result['nbtablecolors'];
  $_SESSION['ds_usetablecolorsub'] = $result['usetablecolorsub'];
  $_SESSION['ds_usehovercolor'] = $result['usehovercolor'];

  if ($num_results > 0)
  {
    $_SESSION['refreshcolorpage'] = TRUE;
    header("refresh:0;  url=options.php?optionsmenu=changeappeareance");
    exit;
  }
}

$query = 'SELECT * FROM layout';
$query_prm = array();
require('inc/doquery.php');
$available_layout = '';
foreach ($query_result as $layout)
{
  $layoutid = $layout['layoutid'];
  $layoutname = $layout['layoutname'];

  if ($layoutid == $_SESSION['ds_menustyle'])
  {
    $available_layout .= '<option value="' . $layoutid . '" selected>' . $layoutname . '</option>';
  }
  else
  {
    $available_layout .= '<option value="' . $layoutid . '">' . $layoutname . '</option>';
  }
}

#We find the current color theme of the user
$query = 'SELECT colorthemeid FROM usertable WHERE userid = ?';
$query_prm = array($_SESSION['ds_userid']);
require('inc/doquery.php');

$colorthemeiduser = $query_result[0]['colorthemeid'];

#We select all available color_theme created by admin, and current user
$query = 'SELECT themeid, themename FROM color_theme WHERE (userid = ? OR userid = ?)';

$query_prm = array($user_admin_theme, $_SESSION['ds_userid']);
require('inc/doquery.php');
$available_color_theme = '';
foreach ($query_result as $color_theme)
{
  $theme_id = $color_theme['themeid'];
  $theme_name = $color_theme['themename'];

  if ($theme_id == $colorthemeiduser)
  {
    $available_color_theme .= '<option value="' . $theme_id . '" selected>' . $theme_name . '</option>';
  }
  else
  {
    $available_color_theme .= '<option value="' . $theme_id . '">' . $theme_name . '</option>';
  }
}

if (isset($_SESSION['refreshcolorpage']) && $_SESSION['refreshcolorpage'])
{
  print '<p>Votre apparence a bien ete modifiee</p>';
  unset($_SESSION['refreshcolorpage']);
}
?>

<h2>Modifier votre apparence</h2>
<form method="post" action="options.php">
  <table>
    <tr>
      <td>Choisissez une mise en page:</td>
      <td>
        <select name="layoutid">
          <?php print $available_layout; ?>
        </select>
      </td>
    </tr>

    <tr>
      <td>Choisissez une palette:</td>
      <td>
        <select name="colorthemeid">
          <?php print $available_color_theme; ?>
        </select>
      </td>
    </tr>

    <tr>
      <td colspan="2" align="center">
        <input type=hidden name="changelayoutandcolor" value="1">
        <input type=hidden name="optionsmenu"
          value="<?php echo $optionsmenu; ?>">
        <input type="submit" value="<?php echo d_trad('validate'); ?>">
      </td>
    </tr>
  </table>
</form>




