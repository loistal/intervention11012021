<?php

if (isset($_POST['saveme']) && $_POST['saveme'] == 1)
{
  $query = 'update usertable set user_font=?,user_font_size=?,user_font_print=? where userid=?';
  $query_prm = array($_POST['user_font'],$_POST['user_font_size'],$_POST['user_font_print'],$_SESSION['ds_userid']);
  require ('inc/doquery.php');
  $_SESSION['ds_user_font'] = $_POST['user_font'];
  $_SESSION['ds_user_font_size'] = $_POST['user_font_size'];
  $_SESSION['ds_user_font_print'] = $_POST['user_font_print']; /* TODO apply all printwindows */
  echo '<p>' . d_trad('fontmodified') . '</p><br>'; 
}

echo '<h2>'. d_trad('choosefont:') . '</h2>';
echo '<form method="post" action="options.php"><table>';

#todo make array and foreach 
echo '<tr><td><b>' . d_trad('font:') . '</td><td><select name="user_font">';
echo '<option value="Baskerville"'; if ($_SESSION['ds_user_font'] == "Baskerville") echo ' selected'; echo '>Baskerville</option>';
echo '<option value="Calibri"'; if ($_SESSION['ds_user_font'] == "Calibri") echo ' selected'; echo '>' . d_trad('calibri') . '</option>';
echo '<option value="Century Gothic"'; if ($_SESSION['ds_user_font'] == "Century Gothic") echo ' selected'; echo '>' . d_trad('centurygothic') . '</option>';
echo '<option value="Copperplate Gothic Light"'; if ($_SESSION['ds_user_font'] == "Copperplate Gothic Light") echo ' selected'; echo '>' . d_trad('copperplategothiclight') . '</option>';
echo '<option value="Courier"'; if ($_SESSION['ds_user_font'] == "Courier") echo ' selected'; echo '>' . d_trad('courier') . '</option>';
echo '<option value="Courier New"'; if ($_SESSION['ds_user_font'] == "Courier New") echo ' selected'; echo '>Courier New</option>';
echo '<option value="Georgia"'; if ($_SESSION['ds_user_font'] == "Georgia") echo ' selected'; echo '>Georgia (défaut)</option>';
echo '<option value="Helvetica"'; if ($_SESSION['ds_user_font'] == "Helvetica") echo ' selected'; echo '>Helvetica</option>';
echo '<option value="Impact"'; if ($_SESSION['ds_user_font'] == "Impact") echo ' selected'; echo '>' . d_trad('impact') . '</option>';
echo '<option value="Lucida Console"'; if ($_SESSION['ds_user_font'] == "Lucida Console") echo ' selected'; echo '>' . d_trad('lucidaconsole') . '</option>';
echo '<option value="Lucida Sans Unicode"'; if ($_SESSION['ds_user_font'] == "Lucida Sans Unicode") echo ' selected'; echo '>' . d_trad('lucidasansunicode') . '</option>';
echo '<option value="Palatino Linotype"'; if ($_SESSION['ds_user_font'] == "Palatino Linotype") echo ' selected'; echo '>' . d_trad('palatinolinotype') . '</option>';
echo '<option value="Tahoma"'; if ($_SESSION['ds_user_font'] == "Tahoma") echo ' selected'; echo '>' . d_trad('tahoma') . '</option>';
echo '<option value="Times New Roman"'; if ($_SESSION['ds_user_font'] == "Times New Roman") echo ' selected'; echo '>' . d_trad('timesnewroman') . '</option>';
echo '<option value="Verdana"'; if ($_SESSION['ds_user_font'] == "Verdana") echo ' selected'; echo '>' . d_trad('verdana') . '</option>';
echo '</select></td></tr>';

echo '<tr><td><b>' . d_trad('fontsize:') . '</td><td><select name="user_font_size">';
echo '<option value="Medium"'; if ($_SESSION['ds_user_font_size'] == "Medium") echo ' selected'; echo '>' . d_trad('medium') . '</option>';
echo '<option value="Large"'; if ($_SESSION['ds_user_font_size'] == "Large") echo ' selected'; echo '>' . d_trad('large') . '</option>';
echo '<option value="X-Large"'; if ($_SESSION['ds_user_font_size'] == "X-Large") echo ' selected'; echo '>' . d_trad('xl') . '</option>';
echo '<option value="XX-Large"'; if ($_SESSION['ds_user_font_size'] == "XX-Large") echo ' selected'; echo '>' . d_trad('xxl') . '</option>';
echo '<option value="Small"'; if ($_SESSION['ds_user_font_size'] == "Small") echo ' selected'; echo '>' . d_trad('small') . '</option>';
echo '<option value="X-Small"'; if ($_SESSION['ds_user_font_size'] == "X-Small") echo ' selected'; echo '>' . d_trad('xs') . '</option>';
echo '<option value="XX-Small"'; if ($_SESSION['ds_user_font_size'] == "XX-Small") echo ' selected'; echo '>' . d_trad('xxs') . '</option>';
echo '</select></td></tr>';

echo '<tr>';

echo '<tr><td><b>Police (documents à imprimer)</td><td><select name="user_font_print">';
echo '<option value="Baskerville"'; if ($_SESSION['ds_user_font_print'] == "Baskerville") echo ' selected'; echo '>Baskerville</option>';
echo '<option value="Calibri"'; if ($_SESSION['ds_user_font_print'] == "Calibri") echo ' selected'; echo '>' . d_trad('calibri') . '</option>';
echo '<option value="Century Gothic"'; if ($_SESSION['ds_user_font_print'] == "Century Gothic") echo ' selected'; echo '>' . d_trad('centurygothic') . '</option>';
echo '<option value="Copperplate Gothic Light"'; if ($_SESSION['ds_user_font_print'] == "Copperplate Gothic Light") echo ' selected'; echo '>' . d_trad('copperplategothiclight') . '</option>';
echo '<option value="Courier"'; if ($_SESSION['ds_user_font_print'] == "Courier") echo ' selected'; echo '>' . d_trad('courier') . '</option>';
echo '<option value="Courier New"'; if ($_SESSION['ds_user_font_print'] == "Courier New") echo ' selected'; echo '>Courier New</option>';
echo '<option value="Georgia"'; if ($_SESSION['ds_user_font_print'] == "Georgia") echo ' selected'; echo '>Georgia</option>';
echo '<option value="Helvetica"'; if ($_SESSION['ds_user_font_print'] == "Helvetica") echo ' selected'; echo '>Helvetica</option>';
echo '<option value="Impact"'; if ($_SESSION['ds_user_font_print'] == "Impact") echo ' selected'; echo '>' . d_trad('impact') . '</option>';
echo '<option value="Lucida Console"'; if ($_SESSION['ds_user_font_print'] == "Lucida Console") echo ' selected'; echo '>' . d_trad('lucidaconsole') . '</option>';
echo '<option value="Lucida Sans Unicode"'; if ($_SESSION['ds_user_font_print'] == "Lucida Sans Unicode") echo ' selected'; echo '>' . d_trad('lucidasansunicode') . '</option>';
echo '<option value="Palatino Linotype"'; if ($_SESSION['ds_user_font_print'] == "Palatino Linotype") echo ' selected'; echo '>' . d_trad('palatinolinotype') . '</option>';
echo '<option value="Tahoma"'; if ($_SESSION['ds_user_font_print'] == "Tahoma") echo ' selected'; echo '>' . d_trad('tahoma') . ' (défaut)</option>';
echo '<option value="Times New Roman"'; if ($_SESSION['ds_user_font_print'] == "Times New Roman") echo ' selected'; echo '>' . d_trad('timesnewroman') . '</option>';
echo '<option value="Verdana"'; if ($_SESSION['ds_user_font_print'] == "Verdana") echo ' selected'; echo '>' . d_trad('verdana') . '</option>';
echo '</select></td></tr>';

echo '<tr><td colspan=2><input type=hidden name="saveme" value="1"><input type=hidden name="optionsmenu" value="' . $optionsmenu . '">';
echo '<input type="submit" value="' . d_trad('validate') .'"></td></tr>';
echo '</table></form>';

echo '<br><div class="myblock">';
echo d_trad('pleaseaskforotherfont');
echo '</div>';

?>