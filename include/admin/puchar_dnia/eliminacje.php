<?
//--------------------//
// komunikaty zebrane //
//--------------------//
require_once('include/functions/function.puchar.php');
$wybrana_gra = czysta_zmienna_get($_GET['wybrana_gra']);
$zapisz = czysta_zmienna_post($_POST['zapisz']);
$r_id = (int)$_GET['r_id'];
$mozliwe = array(8, 16, 32);
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('puchar_eliminacje', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            if (!empty($r_id)) {
                $status = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " where vliga='{$wybrana_gra}' && status!='4' && id='{$r_id}';"));
                if ($status['status'] == '1') {
                    $czy_sa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$wybrana_gra}' && spotkanie='ele'  && r_id='{$r_id}';"));
                    $jak_eliminacje = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$wybrana_gra}' && status!='3' && spotkanie='ele'  && r_id='{$r_id}';"));
                    if ($jak_eliminacje == '0') {
                        if ($czy_sa != '0') {
                            if ($zapisz == 'zatwierdz') {
                                $czy_jeszcze_moze = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$wybrana_gra}' && status='1' && r_id='{$r_id}';"));
                                if ($czy_jeszcze_moze != '0') {
                                    if (count($_POST['zrodlo']) && in_array(count($_POST['zrodlo']), $mozliwe)) {


                                        $wynik = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}'");
                                        while ($rekordy = mysql_fetch_array($wynik)) {
                                            if (!in_array($rekordy[1], $_POST['zrodlo'])) {
                                                $wynik4 = mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_GRACZE . " set status='0' where vliga='{$wybrana_gra}' && id_gracza='{$rekordy[1]}' && r_id='{$r_id}';");
                                            }
                                            $zakoncz_eliminacje = mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_LISTA . " SET status='2' WHERE status='1' &&  vliga='{$wybrana_gra}' && id='{$r_id}';");
                                        }
                                    } else {
                                        note(admsg_badSelectedUserElimination, "blad");
                                    }
                                } else {
                                    note(admsg_eliminationEnded, "blad");
                                }
                            }
                            note(admsg_eliminationEnded, "info");
                            pokaz_tabele_eliminacji($wybrana_gra, 'dla_admina', $r_id);
                        } else {
                            note(admsg_noElimination, "blad");
                        }
                    } else {
                        note(admsg_areElimination, "blad");
                    }
                } else {
                    note(admsg_noCupExiststs, 'blad');
                }


            } else {
                wyswietl_listy_rozgrywek(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
            }
        }
    } else {
        note(admsg_gameDenied, "blad");
    }
}


wybor_gry_admin(8);
?>
