<?php
    
require_once(dirname(__FILE__).'/../includes/lib.php');

beginSession('R');
do_header();
?>
<div id="login-info">
    <p class="yacomas_login">
    Login: <?=$_SESSION['YACOMASVARS']['rootlogin'] ?> | 
    <a class="precaucion" href="signout.php">Desconectarme</a>
    </p>
</div>
