<?php
#iWe register value
if (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  #We retrieve the themename
  $theme_name = $_POST['themename'];

  $currentthemecolorid = $_POST['themeid'];

  #We select all available color
  $query = 'SELECT themeid FROM color_theme WHERE themename = ? ';
  $query_prm = array($theme_name);
  require('inc/doquery.php');

  if ($num_results > 1)
  {
    $messages = '<p>Le nom de thème "' . $theme_name . '" que vous avez entré existe déjà !</p>';
  }
  else
  {
    $themeid = $_POST['themeid'];

    if (isset($_POST['colorpicker']))
    {
      $bgcolor = str_replace('#', '', $_POST['bgcolor']);
      $fgcolor = str_replace('#', '', $_POST['fgcolor']);
      $linkcolor = str_replace('#', '', $_POST['linkcolor']);
      $menucolor = str_replace('#', '', $_POST['menucolor']);
      $alertcolor = str_replace('#', '', $_POST['alertcolor']);
      $infocolor = str_replace('#', '', $_POST['infocolor']);
      $formcolor = str_replace('#', '', $_POST['formcolor']);
      $tablecolor = str_replace('#', '', $_POST['tablecolor']);
      $inputcolor = str_replace('#', '', $_POST['inputcolor']);
      $menubordercolor = str_replace('#', '', $_POST['menubordercolor']);
      $menufontcolor = str_replace('#', '', $_POST['menufontcolor']);
      $tablecolor1 = str_replace('#', '', $_POST['tablecolor1']);
      $tablecolor2 = str_replace('#', '', $_POST['tablecolor2']);
      $hovercolor = str_replace('#', '', $_POST['hovercolor']);
      $usehovercolor = str_replace('#', '', $_POST['usehovercolor']);
      $nbtablecolors = str_replace('#', '', $_POST['nbtablecolors']);
      $usetablecolorsub = str_replace('#', '', $_POST['usetablecolorsub']);
      $tablecolorsub = str_replace('#', '', $_POST['tablecolorsub']);
    }
    else
    {
      $bgcolor = $_POST['bgcolor'];
      $fgcolor = $_POST['fgcolor'];
      $linkcolor = $_POST['linkcolor'];
      $menucolor = $_POST['menucolor'];
      $alertcolor = $_POST['alertcolor'];
      $infocolor = $_POST['infocolor'];
      $formcolor = $_POST['formcolor'];
      $tablecolor = $_POST['tablecolor'];
      $inputcolor = $_POST['inputcolor'];
      $menubordercolor = $_POST['menubordercolor'];
      $menufontcolor = $_POST['menufontcolor'];
      $tablecolor1 = $_POST['tablecolor1'];
      $tablecolor2 = $_POST['tablecolor2'];
      $hovercolor = $_POST['hovercolor'];
      $usehovercolor = $_POST['usehovercolor'];
      $nbtablecolors = $_POST['nbtablecolors'];
      $usetablecolorsub = $_POST['usetablecolorsub'];
      $tablecolorsub = $_POST['tablecolorsub'];
    }

    $query = 'UPDATE color_theme SET themename = ?, bgcolor = ?, fgcolor = ?, linkcolor = ?, menucolor = ?, alertcolor = ?, infocolor = ?, formcolor = ?, tablecolor = ?, inputcolor = ?, menubordercolor = ?, menufontcolor = ?, tablecolor1 = ?, tablecolor2 = ?, hovercolor = ?, usehovercolor = ?, tablecolorsub = ?, usetablecolorsub = ?, nbtablecolors = ? WHERE themeid = ?';

    $query_prm = array(
      $theme_name,
      $bgcolor,
      $fgcolor,
      $linkcolor,
      $menucolor,
      $alertcolor,
      $infocolor,
      $formcolor,
      $tablecolor,
      $inputcolor,
      $menubordercolor,
      $menufontcolor,
      $tablecolor1,
      $tablecolor2,
      $hovercolor,
      $usehovercolor,
      $tablecolorsub,
      $usetablecolorsub,
      $nbtablecolors,
      $themeid
    );

    require('inc/doquery.php');

    if ($num_results > 0)
    {
      $messages = '<p>La palette "' . $theme_name . '" a bien été modifée !</p>';

      if (isset($_POST['changelayoutandcolor']) && $_POST['changelayoutandcolor'] == 1)
      {
        $colorthemeid = $_POST['themeid'];

        #We update the usertable of the user
        $query = 'UPDATE usertable SET bgcolor = ?, fgcolor = ?, linkcolor = ?, menucolor = ?, alertcolor = ?, infocolor = ?, formcolor = ?, inputcolor = ?, menubordercolor = ?, menufontcolor = ?, tablecolor = ?, tablecolor1 = ?, tablecolor2 = ?, tablecolorsub = ?, hovercolor = ?, nbtablecolors = ?, usetablecolorsub = ?, usehovercolor = ?, menustyle = ?, colorthemeid = ? WHERE userid = ?';
        $query_prm = array(
          $bgcolor,
          $fgcolor,
          $linkcolor,
          $menucolor,
          $alertcolor,
          $infocolor,
          $formcolor,
          $inputcolor,
          $menubordercolor,
          $menufontcolor,
          $tablecolor,
          $tablecolor1,
          $tablecolor2,
          $tablecolorsub,
          $hovercolor,
          $nbtablecolors,
          $usetablecolorsub,
          $usehovercolor,
          $_SESSION['ds_menustyle'],
          $themeid,
          $_SESSION['ds_userid']
        );

        require('inc/doquery.php');

        #We update all variables session of the user
        $_SESSION['ds_bgcolor'] = '#' . $bgcolor;
        $_SESSION['ds_fgcolor'] = '#' . $fgcolor;
        $_SESSION['ds_linkcolor'] = '#' . $linkcolor;
        $_SESSION['ds_menucolor'] = '#' . $menucolor;
        $_SESSION['ds_alertcolor'] = '#' . $alertcolor;
        $_SESSION['ds_infocolor'] = '#' . $infocolor;
        $_SESSION['ds_formcolor'] = '#' . $formcolor;
        $_SESSION['ds_inputcolor'] = '#' . $inputcolor;
        $_SESSION['ds_menubordercolor'] = '#' . $menubordercolor;
        $_SESSION['ds_menufontcolor'] = '#' . $menufontcolor;
        $_SESSION['ds_tablecolor'] = '#' . $tablecolor;
        $_SESSION['ds_tablecolor1'] = '#' . $tablecolor1;
        $_SESSION['ds_tablecolor2'] = '#' . $tablecolor2;
        $_SESSION['ds_tablecolorsub'] = '#' . $tablecolorsub;
        $_SESSION['ds_hovercolor'] = '#' . $hovercolor;
        $_SESSION['ds_nbtablecolors'] = $nbtablecolors;
        $_SESSION['ds_usetablecolorsub'] = $usetablecolorsub;
        $_SESSION['ds_usehovercolor'] = $usehovercolor;

        if ($num_results > 0)
        {
          if (isset($_POST['colorpicker']) && $_POST['colorpicker'] == 'on') {
            $_SESSION['colorpicker'] = TRUE;
          }

          $_SESSION['refreshcolorpage'] = TRUE;
          $_SESSION['messages'] = $messages;
          $_SESSION['currentthemecolorid'] = $colorthemeid;
          header("refresh:0;  url=options.php?optionsmenu=modifycolors");
          exit;
        }
      }
    }
  }
}

if (isset($_SESSION['refreshcolorpage']) && $_SESSION['refreshcolorpage'])
{
  $currentthemecolorid = $_SESSION['currentthemecolorid'];
  print $_SESSION['messages'];
  print '<p>La couleur de votre thème a été modifiée !</p>';

  if(isset($_SESSION['colorpicker'])) {
    $_POST['colorpicker'] = 'on';

    unset($_SESSION['colorpicker']);
  }

  unset($_SESSION['messages']);
  unset($_SESSION['refreshcolorpage']);
  unset($_SESSION['currentthemecolorid']);
}

if (isset($messages) && !empty($messages))
{
  echo $messages;
}

#We fill value
if (isset($_POST['colorthemeid']) || isset($currentthemecolorid))
{
  if (!empty($_POST['colorthemeid']))
  {
    $color_theme_id = $_POST['colorthemeid'];
    $currentthemecolorid = $_POST['themeid'];
  }
  else
  {
    $color_theme_id = $currentthemecolorid;
  }

  $query = 'SELECT themename, bgcolor, fgcolor, linkcolor, menucolor, alertcolor, infocolor, formcolor, tablecolor, inputcolor, menubordercolor, menufontcolor, tablecolor1, tablecolor2, hovercolor, tablecolorsub FROM color_theme WHERE themeid = ?';
  $query_prm = array($color_theme_id);
  require('inc/doquery.php');

  $color_theme_result = $query_result[0];

  #We select all available color
  $query = 'SELECT colorname, colorcode FROM color';
  $query_prm = array();
  require('inc/doquery.php');

  $all_color = $query_result;

  if (isset($_POST['colorpicker']) && $_POST['colorpicker'] == 'on')
  {
    foreach ($color_theme_result as $index => $theme_color)
    {
      $$index = '<input value="' . $theme_color . '" class="minicolorspicker" name="' . $index . '">';
    }
  }
  else
  {
    foreach ($color_theme_result as $index => $theme_color)
    {
      $matched = FALSE;
      $$index = '<select name="' . $index . '">';

      foreach ($all_color as $color)
      {
        $colorcode = $color['colorcode'];
        $colorname = $color['colorname'];

        #If its the same color as the theme we add a selected attribute
        if ($colorcode == $theme_color)
        {
          $hexcolorcode = '#' . $colorcode;

          #if the color is black we need to force to display as color white because the background is already black
          if ($hexcolorcode == '#000000')
          {
            $hexcolorcode = '#ffffff';
            $$index .= '<option value="' . $colorcode . '" style="background-color: #000000; color: ' . $hexcolorcode . ';" selected>' . $colorname . '</option>';
            $matched = TRUE;
          }
          else
          {
            $$index .= '<option value="' . $colorcode . '" style="background-color: #000000; color: ' . $hexcolorcode . ';" selected>' . $colorname . '</option>';
            $matched = TRUE;
          }
        }
        else
        {
          $hexcolorcode = '#' . $colorcode;

          #if the color is black we need to force to display as color white because the background is already black
          if ($hexcolorcode == '#000000')
          {
            $hexcolorcode = '#ffffff';
            $$index .= '<option value="' . $colorcode . '" style="background-color: #000000; color: ' . $hexcolorcode . ';">' . $colorname . '</option>';
          }
          else
          {
            $$index .= '<option value="' . $colorcode . '" style="background-color: #000000; color: ' . $hexcolorcode . ';">' . $colorname . '</option>';
          }
        }
      }

      if ($matched === FALSE)
      {
        $$index .= '<option value="' . $theme_color . '" style="background-color: #000000; color: #' . $theme_color . ';" selected>#' . $theme_color . '</option>';
      }

      $$index .= '</select>';
    }
  }

  $query = 'SELECT themeid, themename, usehovercolor, nbtablecolors, usetablecolorsub  FROM color_theme WHERE themeid = ?';
  $query_prm = array($color_theme_id);
  require('inc/doquery.php');

  $color_theme_integer_parameters = $query_result[0];

  $themeid = $color_theme_integer_parameters['themeid'];
  $themename = $color_theme_integer_parameters['themename'];
  $usehovercolor = $color_theme_integer_parameters['usehovercolor'];
  $nbtablecolors = $color_theme_integer_parameters['nbtablecolors'];
  $usetablecolorsub = $color_theme_integer_parameters['usetablecolorsub'];
  ?>

  <script type="text/javascript" src="jq/jquery.js"></script>

  <?php if (isset($_POST['colorpicker']) && $_POST['colorpicker'] == 'on')
{ ?>
  <link rel="stylesheet" href="jq/jquery.minicolors.css" type="text/css">
  <script type="text/javascript" src="jq/jquery.minicolors.min.js"></script>
  <style>
    .minicolors-theme-default .minicolors-input {
      height: 26px;
    }
  </style>

  <script type="text/javascript">
    $(document).ready(function () {
      $('input.minicolorspicker').minicolors();
    });
  </script>

  <?php require('options/colorchangelive.php'); ?>
<?php } ?>


  <table>
    <tr>
      <td style="width: 50%;">
        <h2>Modifier votre pallette de couleur</h2>

        <form method="post" action="options.php">
          <table>
            <tr>
              <td>Nom de votre palette de couleur</td>
              <td>
                <input type="text" name="themename" size="20"
                  value="<?php print $themename; ?>" required>
              </td>
            </tr>

            <tr>
              <td><?php print d_trad('background:'); ?></td>
              <td>
                <?php print $bgcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('text:'); ?></td>
              <td>
                <?php print $fgcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('link:'); ?></td>
              <td>
                <?php print $linkcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('menu:'); ?></td>
              <td>
                <?php print $menucolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('warning:'); ?></td>
              <td>
                <?php print $alertcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('button:'); ?></td>
              <td>
                <?php print $infocolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('form:'); ?></td>
              <td>
                <?php print $formcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('report:'); ?></td>
              <td>
                <?php print $tablecolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('field:'); ?></td>
              <td>
                <?php print $inputcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('menuoutline:'); ?></td>
              <td>
                <?php print $menubordercolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('menutext:'); ?></td>
              <td>
                <?php print $menufontcolor; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo 'Couleur rapport ligne 1'; ?></td>
              <td>
                <?php print $tablecolor1; ?>
              </td>
            </tr>

            <tr>
              <td><?php echo 'Couleur rapport ligne 2'; ?></td>
              <td>
                <?php print $tablecolor2; ?>
              </td>
            </tr>

            <tr>
              <td>Couleur surlignage souris</td>
              <td>
                <?php print $hovercolor; ?>
              </td>
            </tr>

            <tr>
              <td>Surlignage souris</td>
              <td>
                <select name="usehovercolor">
                  <option value="0"
                    <?php if ($usehovercolor == 0)
                    {
                      echo 'selected';
                    } ?>>Non
                  </option>
                  <option value="1"
                    <?php if ($usehovercolor == 1)
                    {
                      echo 'selected';
                    } ?>>Rapports
                  </option>
                  <option value="2"
                    <?php if ($usehovercolor == 2)
                    {
                      echo 'selected';
                    } ?>>Partout
                  </option>
                </select>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('reportcolorsalternation:'); ?></td>
              <td>
                <select name="nbtablecolors">
                  <option value="1"
                    <?php if ($nbtablecolors == 1)
                    {
                      echo 'selected';
                    } ?>>Non
                  </option>
                  <option value="2"
                    <?php if ($nbtablecolors == 2)
                    {
                      echo 'selected';
                    } ?>>Oui
                  </option>
                </select>
              </td>
            </tr>

            <tr>
              <td><?php echo d_trad('subtotaldifferentcolor:'); ?></td>
              <td>
                <select name="usetablecolorsub">
                  <option value="0"
                    <?php if ($usetablecolorsub == 0)
                    {
                      echo 'selected';
                    } ?>>Non
                  </option>
                  <option value="1"
                    <?php if ($usetablecolorsub == 1)
                    {
                      echo 'selected';
                    } ?>>Oui
                  </option>
                </select>
              </td>
            </tr>

            <tr>
              <td><?php print d_trad('subtotalcolor:'); ?></td>
              <td>
                <?php print $tablecolorsub; ?>
              </td>
            </tr>

            <tr>
              <td>
                <input type="checkbox" name="changelayoutandcolor"
                  value="1"> Appliquer comme nouveau thème
              </td>
              <td>
                <input type=hidden name="themeid"
                  value="<?php echo $themeid; ?>">

                <?php if (isset($_POST['colorpicker']) && $_POST['colorpicker'] == 'on')
                { ?>
                  <input type=hidden name="colorpicker"
                    value="on">
                <?php } ?>

                <input type="hidden" name="saveme" value="1"> <input
                  type="hidden"
                  name="optionsmenu"
                  value="modifycolors"> <input type="submit"
                  value="<?php echo d_trad('validate'); ?>">
              </td>
            </tr>
          </table>
        </form>
      </td>

      <td style="width: 10%;">&nbsp</td>

      <td style="width: 40%; vertical-align: top;">
        <h2>Démonstration couleurs rapport</h2>
        <table class="report">
          <thead>
          <tr>
            <th>Couleur rapport ligne 1</th>
            <th>Couleur rapport ligne 2</th>
          </tr>
          </thead>
          <tbody>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td>Couleur rapport ligne 1</td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td><span class="alert">Couleur rapport ligne 2 - Alert</span>
            </td>
          </tr>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td>Couleur rapport ligne 1</td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td><span class="alert">Couleur rapport ligne 2 - Alert</span>
            </td>
          </tr>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td><span class="alert">Couleur rapport ligne 1 - Alert</span>
            </td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td><span class="alert">Couleur rapport ligne 2- Alert</span></td>
          </tr>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td><span class="alert">Couleur rapport ligne 1 - Alert</span>
            </td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td>Couleur rapport ligne 2</td>
          </tr>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td><span class="alert">Couleur rapport ligne 1 - Alert</span>
            </td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td>Couleur rapport ligne 2</td>
          </tr>
          <tr class="trtablecolor1">
            <td>Couleur rapport ligne 1</td>
            <td>Couleur rapport ligne 1</td>
          </tr>
          <tr class="trtablecolor2">
            <td>Couleur rapport ligne 2</td>
            <td>Couleur rapport ligne 2</td>
          </tr>
          <tr class="trtablecolorsub">
            <td>Couleur sous-total</td>
            <td>Valeur sous-total</td>
          </tr>
          </tbody>
        </table>
      </td>
    </tr>
  </table>
<?php
}
else
{
  #We select all available color_theme created by the current user
  $query = 'SELECT themeid, themename FROM color_theme WHERE userid = ?';
  $query_prm = array($_SESSION['ds_userid']);
  require('inc/doquery.php');

  if ($num_results > 0)
  {
    #Clear the variable
    unset($available_color_theme);

    foreach ($query_result as $color_theme)
    {
      $theme_id = $color_theme['themeid'];
      $theme_name = $color_theme['themename'];
      $available_color_theme .= '<option value="' . $theme_id . '">' . $theme_name . '</option>';
    }
    ?>
    <h2>Modifier vos palettes de couleurs</h2>
    <form method="post" action="options.php">
      <table>
        <tr>
          <td>Palettes de couleurs que vous avez créées</td>
          <td>
            <select name="colorthemeid">
              <?php print $available_color_theme; ?>
            </select>
          </td>
        </tr>

        <?php if ($_SESSION['ds_autocomplete'] == 1)
        { ?>
          <td>
            <input type="checkbox" name="colorpicker" checked>Utiliser le
            colorpicker
          </td>
        <?php } ?>

        <tr>
          <td colspan="2" align="center">
            <input type="hidden" name="optionsmenu"
              value="modifycolors"> <input type="submit"
              value="<?php echo d_trad('validate'); ?>">
          </td>
        </tr>
      </table>
    </form>
  <?php
  }
  else
  {
    echo '<p> Vous n\'aver crée aucune palette. Veuillez d\'abord créer une palette de couleur !</p>';
  }
}
?>


