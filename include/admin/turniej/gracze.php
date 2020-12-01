<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.player.php');
require_once('include/admin/functions/admin.turniej.php');
require_once('include/admin/functions/admin.boot.php');
require_once('include/admin/functions/admin.game.php');


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('turniej_gracze', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                // wykluczam gracza z Turnieju
                if (!empty($_GET['wyklucz'])) {
                    wyklucz_gracza(TABELA_TURNIEJ_GRACZE, (int)$_GET['wyklucz'], $r_id);
                }
                // wykluczam gracza z Turnieju - END

                // dodaje nowego gracza do pucharu
                if (!empty($_POST['nowygracz'])) {
                    dodaj_gracza(TABELA_TURNIEJ_GRACZE, (int)$_POST['nowygracz'], (int)$_POST['klub'], $r_id);
                }
                // dodaje nowego gracza do Turnieju  - END

                // wyswietlenie mozliwosci dodania gracza
                mozliwosc_dodania_graczy();
                // wyswietlenie mozliwosci dodania gracza - END

                // wyswietlenie graczy bedacych w turnieju
                wyswietl_graczy_gry(TABELA_TURNIEJ_GRACZE, $r_id);
                // wyswietlenie graczy bedacych w turnieju - END
            } else {
                wyswietl_listy_rozgrywek(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
            }
        } ###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(38);
?>
