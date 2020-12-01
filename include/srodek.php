<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
$opcja = empty($opcja) ? 0 : $opcja;
if ($zalogowany) {
    print '<div id="zawartosc">';
    if (!empty($opcja) && !eregi("^([a-z,])+$", $opcja)) {
        wyswietl_newsy('');

    } else {
        if (!empty($opcja)) {
            switch ($opcja) {
                //index.php?opcja=$1&podmenu=$2&podopcja=$3&menu=$4
                case 'wyszukiwarka':
                    include("include/szukaj.php");
                    break;
                case 'wiadomosci':
                    include("include/wiadomosci.php");
                    break;
                case 'wyzwania':
                    include("include/nowe.php");
                    break;
                case 'liga':
                    include("include/liga.php");
                    break;
                case 'turniej':
                    include("include/turniej.php");
                    break;
                case 'puchar' :
                    include("include/puchar_dnia.php");
                    break;
                case 'wyloguj':
                    note("Wlasnie zostales wylogowany! Zapraszamy ponownie!", "info");
                    przeladuj('index.php');
                    break;
                case 'rejestracja':
                    include("include/rejestracja.php");
                    break;
                case 'profil,edytuj':
                    include("include/profil.php");
                    break;
                case 'pokaz,komentarze':
                    wyswietl_komentarze((int)$_GET['podmenu']);
                    break;
                case 'wszystkie,wersje,gier':
                    require_once('include/functions/other/other.all-games.php');
                    wyswietl_wersje_gier($wersje_gry);
                    break;
                case 'dodatkowe,punkty':
                    wyswietl_wszystkie_dodatkowe_punkty();
                    break;
                case 'wyswietl,szczegoly,pucharu':
                    require_once('include/functions/function.puchar.php');
                    wyswietl_szczegoly_pucharu((int)$_GET['podmenu']);
                    break;
                case 'uzytkownicy':
                    include("include/uzytkownicy.php");
                    break;
                case 'banowani':
                    czarna_lista();
                    break;
                case 'profil,pokaz':
                    include("include/profil_usera.php");
                    break;
                case 'historia':
                    include("include/historia.php");
                    break;
                case 'regulamin,a':
                    include("doc/regulamin_strony.php");
                    break;
                case 'regulamin,b':
                    include("doc/regulamin_pes10.php");
                    break;
                case 'polecamy':
                    include("doc/polecamy.php");
                    break;
                case 'lacze':
                    include("doc/test_lacza.php");
                    break;
                case 'redakcja':
                    wyswietl_redakcje();
                    break;
                case 'ranking':
                    wyswietl_ranking($l_u);
                    break;
                case 'news':
                    wyswietl_newsy((int)$_GET['podmenu']);
                    break;
                case 'walkowery':
                    include("include/pokaz_walkowery.php");
                    break;
                case 'irc':
                    przeladuj("irc://irc.quakenet.org/pesforum");
                    break;
                case 'kontakt':
                    przeladuj("mailto:stopek.pawel@gmail.com");
                    break;
                case 'deklaracje':
                    include("include/zapisy.php");
                    break;
                case 'druzyny':
                    include("include/druzyny.php");
                    break;
                case 'online':
                    szczegoly_online((int)$_GET['podmenu']);
                    break;
                default:
                    wyswietl_newsy('');
                    break;
            }

        } else {
            wyswietl_newsy('');
        }
    }

    print "</div>";
} else {
    if (!empty($opcja)) {
        $opcje = array('rejestracja', 'logowanie', 'regulamin,a', 'regulamin,b', 'redakcja', 'polecamy', 'irc', 'kontakt', 'news');
        if (in_array($opcja, $opcje)) {
            switch ($opcja) {
                case 'rejestracja':
                    include("include/rejestracja.php");
                    break;
                case 'logowanie':
                    include("include/logowanie.php");
                    formularz_logowania2();
                    break;
                case 'regulamin,a':
                    include("doc/regulamin_strony.php");
                    break;
                case 'regulamin,b':
                    include("doc/regulamin_pes10.php");
                    break;
                case 'redakcja':
                    wyswietl_redakcje();
                    break;
                case 'news':
                    wyswietl_newsy((int)$_GET['podmenu']);
                    break;
                case 'polecamy':
                    include("doc/polecamy.php");
                    break;
                case 'irc':
                    przeladuj("irc://irc.quakenet.org/pesforum");
                    break;
                case 'kontakt':
                    przeladuj("mailto:stopek.pawel@gmail.com");
                    break;
                default:
                    wyswietl_newsy('');
                    break;
            }
        } else {
            include("include/logowanie.php");
            formularz_logowania2();
        }
    } else {
        include("include/logowanie.php");
        wyswietl_newsy('');
    }
}
?>
