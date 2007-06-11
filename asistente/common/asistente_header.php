<?php
    
require_once(dirname(__FILE__).'/../../includes/lib.php');

beginSession('A');
do_header();
?>
<div id="login-info">
    <p class="yacomas_login">
    Login: <?=$_SESSION['YACOMASVARS']['asilogin'] ?> | 
    <a class="precaucion" href="signout.php">Desconectarme</a>
    </p>
</div>
