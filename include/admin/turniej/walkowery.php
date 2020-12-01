<?

//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.match.php');
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.show-list.php');
require_once('include/admin/functions/admin.turniej.php');
require_once('include/admin/functions/tournament/tt.listMatchWo.php');

$zmienna = czysta_zmienna_post($_POST['zmienna']);
$wynik_1_p = (int)$_POST['wynik_1_p'];
$wynik_2_p = (int)$_POST['wynik_2_p'];
$tablica = array(
    'for1' => array('pkt' => array(conf_pointsWoTurniej3_0, conf_pointsWoTurniej0_3), 'gole' => array('3', '0')),
    'for2' => array('pkt' => array(conf_pointsWoTurniej0_3, conf_pointsWoTurniej3_0), 'gole' => array('0', '3')),
    'for3' => array('pkt' => array(conf_pointsWoTurniej0_0, conf_pointsWoTurniej0_0), 'gole' => array('0', '0'))
);
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('turniej_wo', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                // wystawianie walkoweru
                if (!empty($_POST['walkower'])) {
                    admin_wystaw_wo((int)$_POST['identyf'], TABELA_TURNIEJ, $tablica, $zmienna);
                }
                // wystawianie walkoweru - END

                //wystawia grupie zaznaczonych graczy wo
                if (!empty($_POST['wystaw_wo_grup'])) {
                    wystaw_wo_grup(TABELA_TURNIEJ, $_POST['wo_grup'], $_POST['wo_grup_type'], $tablica);
                }
                //wystawia grupie zaznaczonych graczy wo  _END

                // wpisywanie wyniku gdy gosc nie chce !!
                if (!empty($_POST['wpisz'])) {
                    wpisz_wynik_turniej($wynik_1_p, $wynik_2_p, (int)$_POST['identyf']);
                }
                // wpisywanie wyniku gdy gosc nie chce !! - END


                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik
                if (!empty($_GET['zatwierdz_spotkanie'])) {
                    potwierdz_spotkanie_turniej((int)$_GET['zatwierdz_spotkanie']);
                }
                // zatwierdzanie spotkan w ktorych jest juz wpisany wynik  - END


                //wystietla liste rozegranych meczy w lidze
                showInfoAboutWo($tablica);
                showMatchListWoTournament($tablica);
                //wystietla liste rozegranych meczy w lidze - END
            } else {
                wyswietl_listy_rozgrywek(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
            }
        } ###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(12);
?>
