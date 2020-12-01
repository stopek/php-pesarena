<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.match.php');
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.show-list.php');
require_once('include/functions/function.host.php');
require_once('include/admin/funkcje/function.table.php');

$zmienna = czysta_zmienna_post($_POST['zmienna']);
$tablica = array(
    'for1' => array('pkt' => array(conf_pointsWoCup0_3, conf_pointsWoCup3_0), 'gole' => array('3', '0')),
    'for2' => array('pkt' => array(conf_pointsWoCup3_0, conf_pointsWoCup0_3), 'gole' => array('0', '3'))
);

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('puchar_wo', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                // wystawianie walkoweru
                if (!empty($_POST['walkower'])) {
                    admin_wystaw_wo((int)$_POST['identyf'], TABELA_PUCHAR_DNIA, $tablica, $zmienna);
                }
                // wystawianie walkoweru - END


                // wpisywanie wyniku
                if (!empty($_POST['wpisz'])) {
                    wpisz_wynik_puchar_dnia((int)$_POST['wynik_1_p'], (int)$_POST['wynik_2_p'], (int)$_POST['identyf']);
                }
                // wpisywanie wyniku


                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik
                if (!empty($_GET['zatwierdz_spotkanie'])) {
                    zatwierdz_spotkanie_puchar_dnia((int)$_GET['zatwierdz_spotkanie']);
                }
                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik - END


                showInfoAboutWo($tablica);
                showMatchListWoCup();

            } else {
                wyswietl_listy_rozgrywek(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
            }
        }###

    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(16);
?>
