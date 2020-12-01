<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.player.php');
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('turniej_zamiana', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                if (!empty($_POST['podmien'])) {
                    mozliwosc_zamiany_gracza((int)$_POST['zkogo'], (int)$_POST['nakogo'], (int)$_POST['klub'], $r_id, TABELA_TURNIEJ_GRACZE, TABELA_TURNIEJ, (int)$_POST['whatWithMatch']);
                }


                $licz = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_TURNIEJ_LISTA . " where vliga='{$wybrana_gra}' && status!='4' GROUP BY id;"));
                if (!empty($licz[0])) {
                    funkcja_podmien_gracza(TABELA_TURNIEJ_GRACZE, $r_id);
                } else {
                    note(admsg_tournamentIsEnded'','blad');
				}
            } else {
                wyswietl_listy_rozgrywek(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
            }
        } ###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(15);
?>
