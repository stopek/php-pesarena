<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.player.php');
require_once('include/admin/functions/admin.boot.php');

$wyklucz = (int)$_GET['wyklucz'];
$nowygracz = (int)$_POST['nowygracz'];
$klub = (int)$_POST['klub'];

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_gracze', explode(',', POZIOM_U_A))) {
            return note(acessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                // wykluczam gracza z ligi
                if (!empty($wyklucz)) {
                    wyklucz_gracza(TABELA_LIGA_GRACZE, $wyklucz, R_ID);
                }
                // wykluczam gracza z ligi - END

                // dodaje nowego gracza do pucharu
                if (!empty($nowygracz)) {
                    dodaj_gracza(TABELA_LIGA_GRACZE, $nowygracz, $klub, R_ID);
                }
                // dodaje nowego gracza do ligi  - END

                // wyswietlenie mozliwosci dodania gracza
                mozliwosc_dodania_graczy();
                // wyswietlenie mozliwosci dodania gracza - END

                // wyswietlenie graczy bedacych w pucharze
                wyswietl_graczy_gry(TABELA_LIGA_GRACZE, R_ID);
                // wyswietlenie graczy bedacych w lidze - END


            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            }
        }###
    } else {
        note(gameDenied, "blad");
    }
}
wybor_gry_admin(38);
?>
