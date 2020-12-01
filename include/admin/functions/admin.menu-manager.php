<?

function sQd($table, $gracze, $r_id)
{
    $ret = mysql_fetch_array(mysql_query("SELECT nazwa,opiekun,(SELECT count(*) FROM {$gracze} WHERE r_id='{$r_id}') FROM {$table} WHERE id = '{$r_id}'"));
    echo(!empty($r_id) ? "<div class=\"showPartyName\"><span>{$ret['nazwa']}<span><br/>opiekun: {$ret[1]} graczy: {$ret[2]}</span></span></div>" : null);
}

$sl = array(13, 12, 35, 5, 38, 33, 21, 42);
$sp = array(15, 7, 8, 39, 16, 19);
$st = array(30, 41, 29, 36, 37);

if (in_array($opcja, $sl)) sQd(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE, R_ID);
if (in_array($opcja, $st)) sQd(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE, R_ID);
if (in_array($opcja, $sp)) sQd(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE, R_ID);


switch ($opcja) {
    //menu dla ligi
    case 13:
        include("include/admin/liga/league.renamePlayer.php");
        break;
    case 12:
        include("include/admin/liga/league.wo.php");
        break;
    case 35:
        include("include/admin/liga/zlicz_wo_graczy.php");
        break;
    case 5:
        include("include/admin/liga/league.matchActivation.php");
        break;

    case 38:
        include("include/admin/liga/league.playersManager.php");
        break;
    case 33:
        include("include/admin/liga/league.matchEdit.php");
        break;
    case 21:
        include("include/admin/liga/league.matchGenerator.php");
        break;
    case 42:
        include("include/admin/liga/show-league-tables.php");
        break;
    case 26:
        rozpoczynanie_zapisow(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
        wybor_gry_admin(26);
        break;
    //menu dla ligi - koniec


    //menu dla pucharu
    case 15:
        include("include/admin/puchar_dnia/cup.playersRename.php");
        break;
    case 7:
        include("include/admin/puchar_dnia/cup.generation.php");
        break;
    case 8:
        include("include/admin/puchar_dnia/cup.elimination.php");
        break;
    case 39:
        include("include/admin/puchar_dnia/cup.playersManager.php");
        break;
    case 16:
        include("include/admin/puchar_dnia/cup.wo.php");
        break;
    case 19:
        include("include/admin/puchar_dnia/cup.elimination.php");
        break;
    case 17:
        rozpoczynanie_zapisow(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
        wybor_gry_admin(17);
        break;
    //menu dla pucharu - koniec


    //menu dla turnieju
    case 30:
        include("include/admin/turniej/zamiana.php");
        break;
    case 41:
        include("include/admin/turniej/gracze.php");
        break;
    case 29:
        include("include/admin/turniej/turniej.php");
        break;
    case 36:
        include("include/admin/turniej/walkowery.php");
        break;
    case 37:
        include("include/admin/turniej/edytowanie.php");
        break;
    case 32:
        rozpoczynanie_zapisow(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
        wybor_gry_admin(32);
        break;
    // menu dla turnieju - koniec


    case 34:
        include("include/admin/inne/manage.ranking.php");
        break;
    case 9:
        include("include/admin/inne/manage.user.php");
        break;
    case 28:
        include("include/admin/inne/manage.team.php");
        break;
    case 40:
        include("include/admin/inne/manage.points.php");
        break;
    case 43:
        include("include/admin/functions/admin.profile.php");
        break;
    case 44:
        require_once("include/admin/functions/admin.configurations.php");
        ustawienia('include/admin/conf.php', 1);
        break;
    case 45:
        require_once("include/admin/functions/admin.configurations.php");
        ustawienia('include/admin/msg.php', 2);
        break;

    case 22:
        include("include/admin/inne/manage.match.php");
        break;
    case 24:
        include("include/admin/inne/manage.sonda.php");
        break;
    case 25:
        include("include/admin/inne/manage.game.php");
        break;
    case 18:
        include("include/admin/inne/manage.admin.php");
        break;
    case 20:
        include("include/admin/inne/manage.news.php");
        break;
    case 27:
        require_once('include/admin/functions/admin.ip.php');
        zarzadzaj_graczami_ip();
        break;
    case 'wyloguj':
        note("Zostales pomyslnie wylogowany z panelu administracyjnego", "info");
        session_destroy();
        przeladuj('administrator.php');
        break;
    default:
        statystyki();
        break;
}
?>