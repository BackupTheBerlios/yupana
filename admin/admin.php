<?
        require_once('../includes/lib.php');

        $option = optional_param('opc');

		switch ($option) 
		{
            case 'config':
			case 'C': include "config.php";
				break;

            case 'add':
			case '0': include "Nadmin.php";
				break;

            case 'list':
			case '1': include "Ladmin.php";
				break;

            case 'papers/deleted':
			case 2: include "LBponencias.php";
				break;

            case 'edit':
			case 3: include "Madmin.php";
				break;

            case 'rooms/add':
			case 4: include "Nlugar.php";
				break;

            case 'rooms/list':
			case 5: include "Llugar.php";
				break;

            case 'speakers/list':
			case 6: include "Lponentes.php";
				break;

            case 'papers/list':
			case 7: include "Lponencias.php";
				break;

            case 'events/add':
			case 8: include "Aevento.php";
				break;

            case 'events/list':
			case 9: include "Leventos.php";
				break;

            case 'dates/register':
			case 11: include "Nfecha.php";
				break;

            case 'dates/list':
			case 12: include "Lfecha.php";
				break;

            case 'persons/list':
			case 13: include "Lasistentes.php";
				break;

            case 'persons/control':
			case 15: include "RAsistencia.php";
				break;

            case 'speaker/add':
			case 16: include "Nponente.php";
				break;

            case 'papers/add':
			case 17: include "Nponencia.php";
				break;

            case 'workshop/addperson':
			case 18: include "RAsistente.php";
				break;

            case 'workshop/removeperson':
			case 19: include "BajaAsistenteTaller.php";
				break;

			default: include "signin.php";
				break;
		}	
?>
