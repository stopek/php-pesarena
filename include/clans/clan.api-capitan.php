<?
if (!empty($_POST['goPosition'])) changePosition((int)$_POST['player_id'], (int)$_POST['clan_id'], (int)$_POST['position']);
if (!empty($_POST['goName'])) changeClanName(czysta_zmienna_post($_POST['name']), (int)$_POST['clan_id']);
switch ($_GET[FIRST_HTACCESS_VAR]) {
    case 'logout':
        lOut();
        break;
    case 'results':
        switch ($_GET[THIRD_HTACCESS_VAR]) {
            case 'typeresult':
                typeClanResult((int)$_GET[SECOND_HTACCESS_VAR], $smarty);
                break;
            case 'edit':
                note(clans_accessDenied, "blad");
                break;
            case 'acceptresult':
                acceptResult((int)$_GET[SECOND_HTACCESS_VAR]);
                break;
            case 'reject':
                rejectResult((int)$_GET[SECOND_HTACCESS_VAR]);
                break;
        }
        clanResults($smarty);
        break;
    case 'profile':
        showClanProfile((int)$_GET[SECOND_HTACCESS_VAR], $smarty);
        break;
    case 'match':
        generateMatches(1);
        break;
    case 'ready':
        setReadyStatus(1, (int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers();
        break;
    case 'unready':
        setReadyStatus(0, (int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers();
        break;
    case 'timetable':
        showTimetable((int)$_GET['clan_id'], $smarty);
        break;
    case 'table':
        showTable((int)$_GET['clan_id'], 'table');
        break;
    case 'ranking':
        showTable((int)$_GET['clan_id'], 'ranking');
        break;

    case 'setTeam':
    case 'createClan':
    case 'startRecords':
    case 'replecement':
    case 'uploadAvatar':
    case 'addPlayer':
    case 'setCaptain':
    case 'deletePlayer':
    case 'deleteClan':
        note(clans_accessDenied, "blad");
        break;


    default:
        if (ACCESS == 3) showTimetable((int)$_GET['clan_id'], $smarty);
        else showClansWithPlayers($smarty);
        break;
}
?>