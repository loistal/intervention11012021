<?php

require('inc/password.php'); # TODO remove once Wing Chong Server at PHP5.5+

# TODO refactor

$err_username_dup = false;
switch($currentstep)
{

  # 
  case 0:
  ?><h2><?php echo d_trad('adduser');?></h2>
  <form method="post" action="system.php"><table>
  <tr><td><?php echo d_trad('namelogin:');?></td><td><input type="text" name="username" size=20></td></tr>
  <tr><td><?php echo d_trad('password:');?></td><td><input type="text" name="password" size=20></td></tr>
  <tr><td><?php echo d_trad('completename:');?></td><td><input type="text" name="name" size=20></td></tr>
  <tr><td><?php echo d_trad('initials:');?></td><td><input type="text" name="initials" size=20></td></tr>
  <tr><td colspan="2" align="center">
  <input type=hidden name="step" value="1"><input type=hidden name="systemmenu" value="<?php echo $systemmenu; ?>">
  <input type="submit" value="<?php echo d_trad('validate');?>"></td></tr>
  </table></form><?php
  break;

  # 
  case 1:
  if ($_POST['username'] == "") { echo '<p class="alert">' .d_trad('usermustnotbeempty') . '</p>'; exit; }
  
    $oldpassword = $_POST['password'];
    $ourcharacters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $salt = '';
    $ourlength = mb_strlen($ourcharacters) - 1;
    for ($p = 0; $p < 10; $p++)
    {
      $salt .= $ourcharacters[mt_rand(0, $ourlength)];
    }
    #$shadow = hash('sha512',$oldpassword . $salt);
    $password_hash = password_hash($oldpassword, PASSWORD_DEFAULT, ["cost" => 13]);
    $passwordok = 0;
    if (mb_strlen($oldpassword) > 5) { $passwordok++; } # 1
    if (mb_strlen($oldpassword) > 11) { $passwordok++; } # 2
    if (preg_match('#[0-9]#',$oldpassword)) { $passwordok++; } # 3
    if (strtolower($oldpassword) != $oldpassword && mb_strtoupper($oldpassword) != $oldpassword) { $passwordok++; } # 4
    if (preg_match('/[|!@#$%&*\/=?,;.:\-_+~^\\\]/', $oldpassword)) { $passwordok++; } # 5
  
    //check if user already exist
    $username = $_POST['username'];
    $query = 'select userid from usertable where username=?';
    $query_prm = array($username);
    require('inc/doquery.php');
    if ($num_results > 0)
    {
      echo '<p class="alert">' . d_trad('useralreadyexists',array($username)) . '<p>';
    }
    else
    {
      $query = 'insert into usertable (username,name,initials,password,password_hash) values (?,?,?,?,?)';
      $query_prm = array($username,$_POST['name'],$_POST['initials'],$passwordok,$password_hash);
      require('inc/doquery.php');
      if($num_results > 0)
      {
        echo '<p>' . d_trad('useradded',array($username)) .'</p>';
      }
      break;
    }

}
?>