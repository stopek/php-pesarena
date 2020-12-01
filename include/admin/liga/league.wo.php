<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.match.php');
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.show-list.php');
require_once('include/functions/function.host.php');

$zmienna = czysta_zmienna_post($_POST['zmienna']);
$tablica = array(
    'for1' => array('pkt' => array(LIGA_WO_30, LIGA_WO_03), 'gole' => array('3', '0')),
    'for2' => array('pkt' => array(LIGA_WO_03, LIGA_WO_30), 'gole' => array('0', '3')),
    'for3' => array('pkt' => array(LIGA_WO_00, LIGA_WO_00), 'gole' => array('0', '0'))
);
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_wo', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {

                // wystawianie walkoweru
                if (!empty($_POST['walkower'])) {
                    admin_wystaw_wo((int)$_POST['identyf'], TABELA_LIGA, $tablica, $zmienna);
                }
                // wystawianie walkoweru - END
                echo count($_POST['wo_grup']);
                //wystawia grupie zaznaczonych graczy wo
                if (!empty($_POST['wystaw_wo_grup'])) {
                    wystaw_wo_grup(TABELA_LIGA, $_POST['wo_grup'], $_POST['wo_grup_type'], $tablica);
                }
                //wystawia grupie zaznaczonych graczy wo  _END

                // wpisywanie wyniku gdy gosc nie chce !!
                if (!empty($_POST['wpisz'])) {
                    liga_wpisz_wynik((int)$_POST['wynik_1_p'], (int)$_POST['wynik_2_p'], (int)$_POST['identyf']);
                }
                // wpisywanie wyniku gdy gosc nie chce !! - END

                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik
                if (!empty($_GET['zatwierdz_spotkanie'])) {
                    liga_zatwierdz_spotkanie((int)$_GET['zatwierdz_spotkanie']);
                }
                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik  - END

                //wystietla liste rozegranych meczy w lidze

                showMatchListWoLeague($tablica);

                //wystietla liste rozegranych meczy w lidze - END
            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(12);
?>
