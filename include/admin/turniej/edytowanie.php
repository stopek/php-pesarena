<?
//--------------------//
// komunikaty zebrane //
//--------------------//
$edycja = (int)$_GET['edycja'];
$zapisz = czysta_zmienna_post($_POST['zapisz']);
$nowe_wynik_1 = (int)$_POST['n_w_1'];
$nowe_wynik_2 = (int)$_POST['n_w_2'];
$nowe_punkty_1 = (int)$_POST['n_p_1'];
$nowe_punkty_2 = (int)$_POST['n_p_2'];

require_once('include/admin/functions/admin.match.php');
require_once('include/functions/function.host.php');
require_once('include/admin/functions/admin.turniej.php');
require_once('include/admin/functions/tournament/tt.listMatchEdit.php');

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('turniej_edycja', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                if (!empty($zapisz)) {
                    if (isset($nowe_wynik_1) && isset($nowe_wynik_2)) {
                        szczegoly_meczu($edycja, TABELA_TURNIEJ);
                        $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where id='{$edycja}';"));
                        if ($ile != '1') {
                            note(admsg_IdDontExists, "blad");
                        } else {
                            $suma1 = ($punkty_ma_1 * (-1)) + $nowe_punkty_1;
                            $suma2 = ($punkty_ma_2 * (-1)) + $nowe_punkty_2;
                            $wynik2 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma1}' WHERE id_gracza='{$GLOBALS['gral1']}' && vliga='{$wybrana_gra}';");
                            $wynik3 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma2}' WHERE id_gracza='{$GLOBALS['gral2']}' && vliga='{$wybrana_gra}';");
                            $wynik4 = mysql_query("UPDATE " . TABELA_TURNIEJ . "	set w1='{$nowe_wynik_1}',w2='{$nowe_wynik_2}' WHERE id='{$edycja}';");
                            define('ADMIN_EDIT', TRUE);
                            zakoncz_spotkanie($edycja, $GLOBALS['gral2'], TABELA_TURNIEJ);
                        }
                    } else {
                        note(admsg_wFields, "blad");
                    }
                }
                if (!empty($edycja) && empty($zapisz)) {
                    formularz_edycja_meczu(TABELA_TURNIEJ, $edycja);
                } else {
                    showMatchesTournamentEdit();
                }
            } else {
                wyswietl_listy_rozgrywek(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
            }
        }
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(19);
?>
