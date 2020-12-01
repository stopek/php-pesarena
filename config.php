<?php session_start();
$token = md5(uniqid(rand(), true));
$_SESSION['token'] = $token;
if (!isset($_SESSION['sprawdz'])) {
    session_regenerate_id();
    $_SESSION['sprawdz'] = true;
    $_SESSION['adres_ip'] = $_SERVER['REMOTE_ADDR'];
}
if ($_SESSION['adres_ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_destroy();
}

include('include/admin/conf.php');
foreach ($conf as $keyToDefine => $valueToDefine) {
    define('conf_' . $keyToDefine, $valueToDefine);
}


setcookie("pesarena_remember_language", "pl", time() + 2592000);

error_reporting(1);


$link_akt = $_SERVER['REQUEST_URI'];


/*



if (empty($_COOKIE['pesarena_remember_intro']) || empty($_COOKIE['pesarena_remember_language']) )
{
    $dostepne = array('pl','de','en');
    if (in_array($_GET['cookie_set_language'],$dostepne))
    {
        setcookie("pesarena_remember_intro", 1, time()+2592000);
        setcookie("pesarena_remember_language", $_GET['cookie_set_language'], time()+2592000);
        header("Location: index.htm");
    }
    include("doc/intro/index.htm");

    exit;
}
else
{
    if ($_GET['opcja'] == 'intro')
    {
        unset($_COOKIE['pesarena_remember_intro']);
        unset($_COOKIE['pesarena_remember_language']);
        setcookie("pesarena_remember_intro",0);
        setcookie("pesarena_remember_language",0);
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/');
    }
    else
    {
        setcookie("pesarena_remember_intro", 1, time()+2592000);
        setcookie("pesarena_remember_language", $_COOKIE['pesarena_remember_language'], time()+2592000);
    }
}
*/


include("language/pl.php");


$ar = explode('&', $link_akt);
foreach ($ar as $wartosc) {
    if ($ta != 0) {
        $aae = explode('=', $wartosc);
        $tablica[$aae[0]][] = $aae[1];
    } else {
        $first_link = $wartosc;
    }
    $ta++;
}
$create_link = $first_link;
if (count($tablica) != 0) foreach ($tablica as $wa => $ab) {
    $create_link .= "&{$wa}=" . array_pop($tablica[$wa]);
}
define(AKT, $create_link);


function con_lost()
{
    $wc = 5;


    if ($wc == 2) {
        $config['baza'] = 'testLiga';
        $config['host'] = 'localhost';
        $config['user'] = 'root';
        $config['pass'] = '';
    } else {
        $config['baza'] = 'stopczynopdb';
        $config['host'] = 'stopczynopdb.mysql.db';
        $config['user'] = 'stopczynopdb';
        $config['pass'] = 'STopek112188';
    }


    if (!mysql_connect($config['host'], $config['user'], $config['pass'])) {
        echo "Nie mozna nawiazac polaczenia z baza danych. ";
        exit;
    }
    if (!mysql_select_db($config['baza'])) {
        echo "Nie mozna wybrac tej bazy";
        exit;
    }
    mysql_query("SET CHARSET utf8");
    mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
    //mysql_query("SET NAMES 'latin2'");
}

con_lost();

// wylogowanie z cookie
if ($_GET['opcja'] == 'wyloguj') {
    session_destroy();
    mysql_query("DELETE FROM cookie WHERE id_gracza='{$_COOKIE['save_user_id']}'");

    setcookie('save_uniqid', '');
    setcookie('save_user_id', '');

}
// wylogowanie z cookie


if (!empty($_SESSION['c']['zapisz_cookie'])) {
    setcookie('save_uniqid', $_SESSION['c']['save_uniqid'], time() + 604800);
    //setcookie('save_user_id',$_SESSION['c']['save_user_id'],time()+604800);
    unset($_SESSION['c']['zapisz_cookie']);


}


function con_forum(&$db_)
{
    $db_ = mysql_connect('sql.pesarena.nazwa.pl', 'pesarena_2', 'TomekTristan12111984') or die ('FORUM blad bazy');
    mysql_select_db('pesarena_2', $db_) or die ('FORUM blad tabeli');
}

$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);


define(TABELA_KOMENTARZE_NEWS, 'komentarze_news');
define(TABELA_NEWS, 'news');
define(TABELA_LIGA, 'liga');
define(TABELA_LIGA_GRACZE, 'liga_gracze');
define(TABELA_LIGA_LISTA, 'liga_lista');
define(TABELA_GRACZE, 'gracze');
define(TABELA_GAME, 'game');
define(TABELA_VIP, 'vip');
define(TABELA_UZYTKOWNICY, 'uzytkownicy');
define(TABELA_UZYTKOWNICY_BAN, 'uzytkownicy_ban');
define(TABELA_ZGODA_HOST, 'zgoda_host');
define(TABELA_PUCHAR_DNIA, 'puchar_dnia');
define(TABELA_PUCHAR_DNIA_GRACZE, 'puchar_dnia_gracze');
define(TABELA_PUCHAR_DNIA_LISTA, 'puchar_dnia_lista');
define(TABELA_PUCHAR_DNIA_TEMP, 'puchar_dnia_temp');
define(TABELA_WYZWANIA, 'wyzwania');
define(TABELA_SONDA, 'sonda');
define(TABELA_OSTRZEZENIA, 'ostrzezenia');
define(TABELA_ZGODA_HOST, 'zgoda_host');
define(TABELA_ONLINE, 'online');
define(TABELA_ADMINI, 'admini');
define(TABELA_USTAWIENIA, 'ustawienia');
define(TABELA_OCENA_LISTA, 'ocena_lista');
define(TABELA_OCENA_SESJA, 'ocena_sesja');
define(TABELA_OCENA_GRALI, 'ocena_grali');
define(TABELA_RANKING, 'ranking');
define(TABELA_RANKING_LISTA, 'ranking_lista');
define(TABELA_DODATKOWE_PUNKTY, 'dodatkowe_punkty');
define(TABELA_DRUZYNY, 'druzyny');
define(TABELA_DRUZYNY_POLACZ, 'druzyny_polacz');
define(TABELA_DRUZYNY_KATEGORIE, 'druzyny_kategorie');
define(TABELA_DRUZYNY_KATEGORIE_GLOWNE, 'kategorie_glowne');
define(TABELA_TURNIEJ, 'turniej');
define(TABELA_TURNIEJ_GRACZE, 'turniej_gracze');
define(TABELA_TURNIEJ_LISTA, 'turniej_lista');
define(TABELA_TURNIEJ_TEMP_TABLE, 'turniej_temp_tabele');
define(TABELA_TURNIEJ_USTAWIENIA, 'turniej_ustawienia');
define(TABELA_GAME, 'game');
define(BLOKI_MENU_LISTA, 'bloki_menu_lista');
define(BLOKI_MENU_PRZYPISANIE, 'bloki_menu_przypisanie');


define(PKT_1, conf_pointsWinCup_1);
define(PKT_2, confpointsWinCup_2);
define(PKT_3, confpointsWinCup_3);
define(MIEJSCE_1_DZIEN, conf_dayRank_1);
define(MIEJSCE_2_DZIEN, conf_dayRank_2);
define(MIEJSCE_3_DZIEN, conf_dayRank_3);
define(MIEJSCE_1_TYDZIEN, conf_weekRank_1);
define(MIEJSCE_2_TYDZIEN, conf_weekRank_2);
define(MIEJSCE_3_TYDZIEN, conf_weekRank_3);
define(MIEJSCE_1_MIESIAC, conf_monthsRank_1);
define(MIEJSCE_2_MIESIAC, conf_monthsRank_2);
define(MIEJSCE_3_MIESIAC, conf_monthsRank_3);
define(ONLINE_GOSC, conf_showLimitGuestOnline);
define(ONLINE_GOSP, conf_showLimitUserOnline);
define(ONLINE_TIME, conf_onlineUpdateTime);
define(PKT_WYGRANE, conf_pointsWin);
define(PKT_REMISU, conf_NoWiner);
define(PKT_LIGA_WYGRANE, conf_pointsLeagueWin);
define(PKT_LIGA_REMISU, conf_pointsLeagueNoWiner);
define(PKT_TURNIEJ_WYGRANE, conf_pointsTournamentWin);
define(PKT_TURNIEJ_REMISU, conf_pointsTournamentNoWiner);
define(PKT_ZA_SESJE_OCEN_UJEMNE, conf_pointsSessionMatchMinus);
define(PKT_ZA_SESJE_OCEN_DODATNIE, conf_pointsSessionMatchPlus);
define(SESJA_OCENY, conf_counterSessionMatch);
define(POBOR_PKT_ZA_POKER_RANK, conf_pokerRankingGetPoints);
define(PULA_PKT_ZA_POKER_RANK, conf_pokerRankingPoul);
define(POKER_RANK_PROCENT_1, conf_percentPokerRankWin_1);
define(POKER_RANK_PROCENT_2, conf_percentPokerRankWin_2);
define(POKER_RANK_PROCENT_3, conf_percentPokerRankWin_3);
define(GG_STRONY, conf_botNo);
define(LIMIT_RANKING_NA_STRONIE, conf_limitRankSite);
define(LIMIT_DODATKOWE_PUNKTY_NA_STRONIE, conf_limitAddedPointsSite);
define(MAX_WYNIK, conf_limitMaxResult);
define(DOMYSLNA_GRA, conf_defaultGame);
define(GG_OPIS, conf_botDescription);


define(LIGA_WO_30, conf_pointsWoLeague3_0);
define(LIGA_WO_03, conf_pointsWoLeague0_3);
define(LIGA_WO_00, conf_pointsWoLeague0_0);


define(GG_HASLO, 'kontodostrony');
define(KOLOR_A, '#d8d8d8'); // kolor w tabelach jasny
define(KOLOR_B, '#ececec'); // kolor w tabelach ciemny
define(KOLOR_C, 'green'); // kolor punktacji wygranego
define(KOLOR_D, 'red'); // kolor punktacji przegranego


$sql = mysql_query("SELECT * FROM " . TABELA_GAME);
while ($rek = mysql_fetch_array($sql)) {
    $wersje_gry[] = $rek['skrot'];
    $opis_gry[$rek['skrot']][] = $rek['nazwa'];
    $logo_gry[$rek['skrot']][] = $rek['logo'];
    $info_gry[$rek['skrot']][] = $rek['view'];
}


define('PEBLOCK', true);
require_once('include/functions/function.ranking.php');
require_once('include/functions/function.news.php');
require_once('include/functions/function.online.php');
require_once('include/functions/function.sonda.php');
require_once('include/functions/function.liga.php');

include("include/funkcje/headlines.php");
include("include/funkcje/calculation.php");
include("include/funkcje/functions.php");
include("include/funkcje/display_functions.php");
include("include/funkcje/js.php");
include("include/funkcje/other_functions.php");
$opcja = $podmenu = $podopcja = $menu = 0;
$opcja = czysta_zmienna_get($_GET['opcja']);
$podmenu = czysta_zmienna_get($_GET['podmenu']);
$podopcja = czysta_zmienna_get($_GET['podopcja']);
$menu = czysta_zmienna_get($_GET['menu']);


function sprawdz_cookie()
{
    $ipk = $_SERVER['REMOTE_ADDR'];

    $u = $_COOKIE['save_uniqid'];
    $r = $_COOKIE['save_user_id'];
    $z = mysql_fetch_array(mysql_query("SELECT *  FROM cookie WHERE cookie='{$u}'"));
    if (!empty($z['cookie']) && $z['cookie'] == $u) {
        $_SESSION['zalogowany'] = sprawdz_login_id($z['id_gracza']);
    }
}

sprawdz_cookie();

$zalogowany = czysta_zmienna_post($_SESSION['zalogowany']);


//deklaracja zmiennych definiowanych bedacych


$id_zalogowanego_usera = sprawdz_id_login($zalogowany);
define('DEFINIOWANE_ID', $id_zalogowanego_usera);
//  deklaracja zmiennych  - END

// deklarowanie histori do przeladowania
if (empty($_SESSION['starsza'])) {
    $_SESSION['starsza'] = 'index.htm';
}
$_SESSION['starsza'] = strip_tags($_SESSION['stara']);
$_SESSION['stara'] = strip_tags($_SESSION['nowa']);
$_SESSION['nowa'] = strip_tags(basename($_SERVER['REQUEST_URI']));
// deklarowanie histori do przeladowania - END


$l_u = 'pes4';
// jaka wersja gry w sesji
if (!empty($zalogowany)) {
    if (isset($_SESSION['v_gry_z_sesji'])) {
        $l_u = $_SESSION['v_gry_z_sesji'];
    } else {
        $l_u = jaka_gra($id_zalogowanego_usera);
    }
}

// jaki puchar liga turniej w sesji
if ($opcja == 'przelacz') {
    switch ($podmenu) {
        case 'puchar':
            unset($_SESSION['sesja_puchar']);
            $_SESSION['sesja_puchar'] = (int)$podopcja;
            przeladuj($_SESSION['stara']);
            break;
        case 'liga':
            unset($_SESSION['sesja_liga']);
            $_SESSION['sesja_liga'] = (int)$podopcja;
            przeladuj($_SESSION['stara']);
            break;
        case 'turniej':
            unset($_SESSION['sesja_turniej']);
            $_SESSION['sesja_turniej'] = (int)$podopcja;
            przeladuj($_SESSION['stara']);
            break;
    }
}
// jaki puchar liga turniej w sesji - END


// deklaracja zmiennych sesyjnych z r_id danego obiektu


$sesja_liga = (int)$_SESSION['sesja_liga'];
$sesja_puchar = (int)$_SESSION['sesja_puchar'];
$sesja_turniej = (int)$_SESSION['sesja_turniej'];


if (empty($sesja_puchar) || ($_SESSION['last_sesja_puchar'] != $l_u)) {
    $s = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_PUCHAR_DNIA_LISTA . " WHERE status!='4' && vliga='{$l_u}'		ORDER BY id DESC LIMIT 1"));
    $_SESSION['sesja_puchar'] = $s['id'];
    $_SESSION['last_sesja_puchar'] = $l_u;
}

if (empty($sesja_turniej) || ($_SESSION['last_sesja_turniej'] != $l_u)) {
    $s = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_TURNIEJ_LISTA . " WHERE status!='4' && vliga='{$l_u}'	 ORDER BY id DESC LIMIT 1"));
    $_SESSION['sesja_turniej'] = $s['id'];
    $_SESSION['last_sesja_turniej'] = $l_u;
}
if (empty($sesja_liga) || ($_SESSION['last_sesja_liga'] != $l_u)) {
    $s = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_LIGA_LISTA . " WHERE status!='4' && vliga='{$l_u}'	 ORDER BY id DESC LIMIT 1"));
    $_SESSION['sesja_liga'] = $s['id'];
    $_SESSION['last_sesja_liga'] = $l_u;
}


function define_index($sesja_liga, $sesja_puchar, $sesja_turniej)
{
    define(R_ID_L, $sesja_liga);
    define(R_ID_P, $sesja_puchar);
    define(R_ID_T, $sesja_turniej);
}

define('DEFINIOWANA_GRA', $l_u);


$_SESSION['DEFINIOWANA_GRA_OPIS'] = $opis_gry[$l_u];
$wlaczona_gra = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where id_gracza='" . $id_zalogowanego_usera . "' && vliga='" . $l_u . "';"));        // sprawdzam czy w ta wersje gra..


if ($opcja == 'gra' && isset($podmenu) && in_array($podmenu, $wersje_gry)) {
    $_SESSION['v_gry_z_sesji'] = $podmenu;
    przeladuj($_SESSION['stara']);
}

require_once('include/functions/other/other.title-site.php');
require_once('include/functions/other/other.new-party.php');
require_once('include/functions/other/other.points.php');

require_once('include/functions/other/other.black-list.php');
require_once('include/functions/function.validate.php');
require_once('include/functions/function.mysql.php');
require_once('include/functions/other/other.redakcja.php');
require_once('include/functions/function.wyzwania.php');
$naglowek_title = naglowek_title();


?>
