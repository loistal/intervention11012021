<?php
setcookie(session_id(),"",time()-3600);
session_unset();
if (isset($_SESSION)) { session_destroy(); }
header('refresh:0; url="index.php"');
exit;
?>