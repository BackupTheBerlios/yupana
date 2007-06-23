<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title><?=$title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Language" content="es" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta name="generator" content="Yupana" />
    <meta name="robots" content="index,follow" />
    <meta name="audience" content="All" />
    <meta name="distribution" content="Global" />
    <meta name="rating" content="General" />
    <meta name="revisit-after" content="1 days" />
    <meta name="description" content="<?=$CFG->conference_name ?>" />
    <meta name="keywords" content="congreso,bolivia,festival,software,libre,software libre,festival de software libre,linux,gnu,gpl,openbsd,freebsd,netbsd,gnu/linux, yupana, conference, management, system" />
    <link rel="icon" href="<?=get_url() ?>/images/yupana.ico" />
    <link rel="stylesheet" type="text/css" href="<?=$CFG->stylesheet ?>" media="all" />
</head>

<body>

<div id="container">

<div id="header">
    <a href="<?=$CFG->conference_link ?>"><h1><?=$CFG->conference_name ?></h1></a>
</div> <!-- #header -->

<div id="content">
<!-- main body -->

<?php if ($login_info) { ?>

    <div id="login-info">
    <p>Usuario: <?=$USER->login ?> | <a class="verde" href="<?=$CFG->home_url ?>">Inicio</a> | <a class="precaucion" href="<?=$CFG->logout_url ?>">Cerrar Sesión</a>
        </p>
    </div>

<?php } ?>
