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
                $id = (int)$_GET[SECOND_HTACCESS_VAR];
                if (!empty($_POST['goResult'])) editResult($id, (int)$_POST['w1'], (int)$_POST['w2']);

                $rec = mysql_fetch_array(mysql_query("SELECT * FROM `-klany_mecze` WHERE id = '{$id}'"));
                if (empty($_POST['goResult'])) $smarty->display('clans/typeResults.tpl');
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
    case 'createClan':
        if (!empty($_POST['go'])) createClan(czysta_zmienna_post($_POST['name']), (int)$_POST['team_id']); // tworze nowy klan


        $smarty->assign('x', returnArray("druzyny", "", "id, nazwa"));
        $smarty->display('clans/createClan.tpl');


        showClansWithPlayers($smarty);
        break;
    case 'profile':
        showClanProfile((int)$_GET[SECOND_HTACCESS_VAR], $smarty);
        break;
    case 'timetable':
        showTimetable((int)$_GET[SECOND_HTACCESS_VAR], $smarty);
        break;
    case 'table':
        showTable((int)$_GET['clan_id'], 'table');
        break;
    case 'ranking':
        showTable((int)$_GET['clan_id'], 'ranking');
        break;
    case 'uploadAvatar':
        $smarty->assign('id', (int)$_GET[SECOND_HTACCESS_VAR]);
        $smarty->display('clans/upload.tpl');
        break;
    case 'addPlayer':
        if (!empty($_POST['go'])) addPlayersToClan((int)$_POST['player_id'], (int)$_GET[SECOND_HTACCESS_VAR]); //dodanie zwyklego gracza


        $smarty->assign('clanName', checkClanName((int)$_GET[SECOND_HTACCESS_VAR]));
        $smarty->assign('x', returnArray("uzytkownicy", "WHERE status = 2 ORDER BY pseudo ASC", "id, pseudo"));
        $smarty->display('clans/addPlayer.tpl');


        showClansWithPlayers($smarty);
        break;
    case 'setCaptain':
        setCaptain((int)$_GET[SECOND_HTACCESS_VAR], (int)$_GET[THIRD_HTACCESS_VAR]);
        showClansWithPlayers($smarty);
        break;
    case 'deletePlayer':
        deletePlayerFromClan((int)$_GET[THIRD_HTACCESS_VAR], (int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers($smarty);
        break;
    case 'deleteClan':
        deleteClan((int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers($smarty);
        break;


    case 'replecement':
        $id = (int)$_GET[SECOND_HTACCESS_VAR];

        if (!empty($_POST['goReplecement']) && $_GET['reType'] == 1) replecementPlayerNew((int)$_POST['old_player'], (int)$_POST['new_player'], $id);
        if (!empty($_POST['goReplecement']) && $_GET['reType'] == 2) replecementPlayerInside((int)$_POST['old_player'], (int)$_POST['new_player'], $id);


        $smarty->assign('oldPlayer', returnArray("`-klany_gracze` k", "WHERE k.id_klanu = '{$id}'", "k.id, k.id_gracza, 
					(SELECT u.pseudo FROM uzytkownicy u WHERE u.id = k.id_gracza) as pseudo"));
        $smarty->assign('clanName', checkClanName($id));
        $smarty->assign('allPlayer', returnArray("uzytkownicy", "WHERE status = 2 ORDER BY pseudo ASC", "id, pseudo"));
        $smarty->assign('reType', (int)$_GET['reType']);
        $smarty->display('clans/replecement.tpl');
        break;


    case 'match':
        $dr = (int)$_GET['dr'];
        if (!empty($dr)) deleteRound($dr);
        generateMatches(1); //generuje lige dla klanow w rozgrywkach klanowych o id 1
        break;
    case 'startRecords':

        if (!empty($_POST['goStatus'])) changes((int)$_POST['type'], (int)$_POST['id']);


        if (!empty($_POST['goStart'])) startRecords(czysta_zmienna_post($_POST['name']));
        showListPartys($smarty);
        break;
    case 'setTeam':

        if (!empty($_POST['goClanTeam'])) renameClanTeam((int)$_GET[SECOND_HTACCESS_VAR], (int)$_POST['team_id']);


        $smarty->assign('clanName', checkClanName((int)$_GET['round']));
        $smarty->assign('teams', returnArray("druzyny", "", "id, nazwa"));
        $smarty->display('clans/clanTeam.tpl');
        break;
    case 'ready':
        setReadyStatus(1, (int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers($smarty);
        break;
    case 'unready':
        setReadyStatus(0, (int)$_GET[SECOND_HTACCESS_VAR]);
        showClansWithPlayers($smarty);
        break;
    case 'results':
        clanResults();
        break;
    default:
        showClansWithPlayers($smarty);
        break;
}
?>