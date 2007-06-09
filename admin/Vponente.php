<?php 
    require_once('header-common.php');

    $vopc = optional_param('vopc');

    $tok = strtok ($vopc," ");
    $idponente = (int)$tok;

    $tok = strtok (" ");
    $regresa = '';
        while ($tok) {
            $regresa .=$tok;
            $tok=strtok(" ");
        }
?>

<h1>Datos de ponente</h1>

<?php
$user = get_record('ponente', 'id', $idponente);
?>

<h2><?=$user->nombrep ?> <?=$user->apellidos ?></h2>

<?php
$values = array(
    'Correo Electrónico' => $user->mail,
    'Sexo' => ($user->sexo == 'M') ? 'Masculino' : 'Femenino',
    'Organización' => $user->org,
    'Estudios' => get_field('estudios', 'descr', 'id', $user->id_estudios),
    'Título' => $user->titulo,
    'Domicilio' => $user->domicilio,
    'Telefono' => chunk_split($user->telefono, 2),
    'Ciudad' => $user->ciudad,
    'Estado' => get_field('estado', 'descr', 'id', $user->id_estado),
    'Fecha de Nacimiento' => $user->fecha_nac,
    'Resumen Curricular' => $user->resume
);

do_table_values($values, 'narrow');
?>

<h2>Ponencias registradas</h2>

<?php
$props = get_records_select('propuesta', 'id_ponente=? AND id_status!=?', array($idponente,7));

if (!empty($props)) {
    $table_data = array();
    $table_data[] = array('Ponencia', 'Tipo', 'Status', 'Archivo');

    foreach ($props as $prop) {
        $l_ponencia = <<< END
<a class="azul" href="Vponencia.php?vopc={$prop->id} {$_SERVER['REQUEST_URI']}">{$prop->nombre}</a>
END;
        $l_tipo = get_field('prop_tipo', 'descr', 'id', $prop->id_prop_tipo);
        $l_status = get_field('prop_status', 'descr', 'id', $prop->id_status);
        $l_archivo = (empty($prop->nombreFile)) ? '--' : "<img src=\"{$CFG->wwwroot}/images/checkmark.gif\" />";

        $table_data[] = array($l_ponencia, $l_tipo, $l_status, $l_archivo);
    }

    do_table($table_data, 'wide');

} else {
?>

<p class="error center">El usuario no registro ninguna ponencia todavía.</p>

<?php } ?>

<p id="buttons">
    <input type="button" value="Volver" onClick="location.href='<?=$regresa ?>'" />
</p>

<?php
do_footer();
?>
