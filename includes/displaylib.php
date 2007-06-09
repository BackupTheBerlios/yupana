<?php
//display functions



function do_header () {
    global $CFG;

    include('header.tmpl.php');
}

function do_footer () {
    global $CFG;
	include "footer.tmpl.php";
}

function do_table ($data, $class='table-data', $toggle=true) {
    if (!is_array($data)) {
        return false;
    }

    if ($toggle) {
        $trclass = 'table-headers';
    } else {
        $trclass = '';
    }

    $trfirst = true;
    $even = true;
?>

<table class="<?=$class ?>"> 

<?php
    foreach ($data as $row) {
        if (!is_array($row)) {
            break;
        }

        if ($trfirst) {
            $trfirst = false;
?>

    <tr class="<?=$trclass ?>">

<?php   } elseif ($toggle) { ?>
    
    <tr class="<?=($even) ? 'even' : 'odd' ?>">

<?php   } else { ?>
    
    <tr>

<?php   }

        $ncol = 1;
        foreach ($row as $column) {
?> 
    <td class="column-<?=$ncol ?>"><?=$column ?></td>

<?php
            $ncol++;
        }
?>
    </tr>
<?php
        // toggle tr class even odd
        if ($toggle) {
            $even = ($even) ? false : true;
        }
    }
?>

</table>

<?php
}

function do_table_values ($values, $class='table-values') {
    if (!empty($values) && is_array($values)) {
        if ($class != 'table-values') {
            $tdclass = $class.'-';
        } else {
            $tdclass = '';
        }
?>  

    <table class="<?=$class ?>">

<?php  
        foreach ($values as $name => $value) {
?>
    <tr>
        <td class="<?=$tdclass ?>name"><?=$name ?>:</td>
        <td class="<?=$tdclass ?>result"><?=$value ?></td>
    </tr>
<?php
        }
?>
    </table>
<?php
    }
}

// print a bold message in an optional color
function notify ($message, $style='error', $align='center') {
    $message = clean_text($message);
?>
    <div class="<?=$style ?>" align="<?=$align ?>"><?=$message ?></div>

<?php
}

function showError($errmsg) {
    if (is_array($errmsg)) {
?>
    <div class="messages">
        <p class="error">Por favor verifique lo siguiente:</p>
        <ul>
<?php
        foreach ($errmsg as $msg) {
?>
            <li><?=$msg ?></li>
<?php
        }
?>
        </ul>
    </div>
<?php
    }
    else {
        
  print "<p><span class=\"err\">Por favor verifique lo siguiente:<ul>$errmsg</ul></span><p><hr><p>\n";
    }
}



?>
