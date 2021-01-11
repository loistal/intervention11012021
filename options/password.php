<?php

require('inc/password.php'); # TODO remove once Wing Chong Server at PHP5.5+

# also used by clientaccess

if(!isset($_POST['newpass']))
{
  echo '<h2>' . d_trad('changepassword:') . '</h2>';
  echo '<form method="post" action="options.php"><table>';
  echo '<tr><td>' . d_trad('oldpassword:') . '</td><td><input autofocus type="password" STYLE="text-align:right" name="oldpass" size=20></td></tr>';
  echo '<tr><td>' . d_trad('newpassword:') . '</td><td><input type="password" STYLE="text-align:right" name="newpass" size=20></td></tr>';
  echo '<tr><td>' . d_trad('verifynewpassword:') . '</td><td><input type="password" STYLE="text-align:right" name="newpass2" size=20></td></tr>';

  echo '<tr><td colspan="2" align="center"><input type=hidden name="step" value="1"><input type=hidden name="optionsmenu" value="' . $optionsmenu . '">';
  
  echo '<input type="submit" value="' . d_trad('validate') . '"></td></tr>';
  echo '</table></form>';
}
else
{
  $error = 0;
  $query = 'select shadow,salt,password_hash from usertable where userid=?'; # shadow,salt for backwards compat
  $query_prm = array($_SESSION['ds_userid']);
  require('inc/doquery.php');
  $password_hash = $query_result[0]['password_hash'];
  if ($_POST['newpass'] != $_POST['newpass2']) { $error = 1; echo '<p class=alert>' . d_trad('twodifferentpasswords') .'</p>';  }
  if ($error == 0 && mb_strlen($_POST['newpass']) < 6) { $error = 3; echo '<p class=alert>Votre mot de passe doit comporter au minimum 6 caractères.</p>'; }
  $oldpass = $_POST['oldpass'];
  if ($error == 0 && !password_verify($oldpass, $password_hash)) { $error = 2; }
  if ($error == 2 && $password_hash == '' && $query_result[0]['shadow'] != '') # backwards compat
  {
    if ($query_result[0]['shadow'] == hash('sha512',$oldpass . $query_result[0]['salt'])) { $error = 0; }
  }
  if ($error == 2) { echo '<p class=alert>' . d_trad('olpasswordko') .'</p>' ; }
  if ($error == 0)
  {
    $newpass = $_POST['newpass'];
    $password_hash = password_hash($newpass, PASSWORD_DEFAULT, ["cost" => 13]);
    $passwordok = 0;
    if (mb_strlen($_POST['newpass']) > 5) { $passwordok++; } # 1
    if (mb_strlen($_POST['newpass']) > 11) { $passwordok++; } # 2
    if (preg_match('#[0-9]#',$_POST['newpass'])) { $passwordok++; } # 3
    if (strtolower($_POST['newpass']) != $_POST['newpass'] && mb_strtoupper($_POST['newpass']) != $_POST['newpass']) { $passwordok++; } # 4
    if (preg_match('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $_POST['newpass'])) { $passwordok++; } # 5
    $query = 'update usertable set shadow="",salt="",password=?,password_hash=? where userid=?'; # shadow,salt for backwards compat
    $query_prm = array($passwordok,$password_hash,$_SESSION['ds_userid']);
    require('inc/doquery.php');
    
    $passwordstrength = d_trad('extremelyweak');
    if ($passwordok == 1) { $passwordstrength = d_trad('veryweak'); }
    if ($passwordok == 2) { $passwordstrength = d_trad('weak'); }
    if ($passwordok == 3) { $passwordstrength = d_trad('medium2'); }
    if ($passwordok == 4) { $passwordstrength = d_trad('strong'); }
    if ($passwordok == 5) { $passwordstrength = d_trad('verystrong'); }
    if ($passwordok < 3) { $passwordstrength = '<span class="alert">' . $passwordstrength . '</span>'; }
    echo d_trad('passwordmodified') . ' ( ' . $passwordstrength . ' ).';

    $ok = 1; $problem = '';
    if (mb_strlen($_POST['newpass']) < 6) { $ok = 0; $problem .= '<br> * Moins de 6 caractères'; } # 1
    elseif (mb_strlen($_POST['newpass']) > 12) { $ok = 0; $problem .= '<br> * Moins de 12 caractères!'; } # 2
    if (!preg_match('#[0-9]#',$_POST['newpass'])) { $ok = 0; $problem .= '<br> * Absence de chiffre'; } # 3
    if (strtolower($_POST['newpass']) != $_POST['newpass'] && mb_strtoupper($_POST['newpass']) != $_POST['newpass']) { } else { $ok = 0; $problem .= '<br> * Absence de majuscule ou de minuscule'; } # 4
    if (!preg_match('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $_POST['newpass'])) { $ok = 0; $problem .= '<br> * Absence de caractère spécial'; } # 5
    if (!$ok) { echo '<br><br>Problèmes avec votre mot de passe:' . $problem; }
  }
}

echo '<br><br><div class="myblock">Les caractères spéciaux (emojis, caractères étrangers) sont acceptés dans les mots de passe.</div>';
?>