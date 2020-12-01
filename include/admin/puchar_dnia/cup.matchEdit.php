<?
// deklaracja zmiennych 
$wybrana_gra = czysta_zmienna_get($_GET['wybrana_gra']);
$edycja = (int)$_GET['edycja'];
$zapisz = czysta_zmienna_post($_POST['zapisz']);
$nowe_wynik_1 = (int)$_POST['n_w_1'];
$nowe_wynik_2 = (int)$_POST['n_w_2'];
$nowe_punkty_1 = (int)$_POST['n_p_1'];
$nowe_punkty_2 = (int)$_POST['n_p_2'];
require_once('include/admin/functions/admin.match.php');
require_once('include/functions/function.host.php');
require_once('include/admin/funkcje/function.table.php');
if (!empty($wybrana_gra)) {


    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('puchar_edycja', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                if (!empty($zapisz)) {
                    if (isset($nowe_wynik_1) && isset($nowe_wynik_2)) {
                        szczegoly_meczu($edycja, TABELA_PUCHAR_DNIA);
                        $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where id='{$edycja}';"));
                        if ($ile != '1') {
                            note(admsg_IdDontExists, "blad");
                        } else {
                            $wynik2 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-'{$punkty_ma_1}' WHERE id_gracza='{$GLOBALS['gral1']}' && vliga='{$wybrana_gra}';");
                            $wynik3 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-'{$punkty_ma_2} WHERE id_gracza='{$GLOBALS['gral2']}' && vliga='{$wybrana_gra}';");
                            $wynik4 = mysql_query("UPDATE " . TABELA_PUCHAR_DNIA . "	set w1='{$nowe_wynik_1}',w2='{$nowe_wynik_2}' WHERE id='{$edycja}';");
                            define('ADMIN_EDIT', TRUE);
                            zakoncz_spotkanie($edycja, $GLOBALS['gral2'], TABELA_PUCHAR_DNIA);
                        }
                    } else {
                        note(admsg_wFields, "blad");
                    }
                } else {
                    if (!empty($edycja) && empty($zapisz)) {
                        formularz_edycja_meczu(TABELA_PUCHAR_DNIA, $edycja);
                    } else {


                        if (!empty($edycja)) print "<form method=\"post\" action=\"\">";

                        $a1 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$wybrana_gra}' && status='3'  && r_id='{$r_id}' ORDER BY spotkanie,rozegrano DESC;");
                        $b = 1;
                        while ($as2 = mysql_fetch_array($a1)) {
                            if ($as2['spotkanie'] != 'ele') {
                                $codalej = jaka_faza_gry_nastepna($as2['spotkanie']);
                                $jest_w = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where spotkanie='{$codalej}' &&  vliga='{$wybrana_gra}' && r_id='{$r_id}';"));
                            } else {
                                $mozliwe = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " WHERE vliga='{$wybrana_gra}' && r_id='{$r_id}' && spotkanie!='ele';"));
                            }


                            $arr[$b][] = $b++;
                            $arr[$b][] = $as2['id'];
                            $arr[$b][] = formatuj_date($as2['rozegrano']);
                            $arr[$b][] = linkownik('profil', $as2['n1'], '');
                            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($as2['n1'], TABELA_PUCHAR_DNIA_GRACZE, $r_id));
                            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($as2['n2'], TABELA_PUCHAR_DNIA_GRACZE, $r_id));
                            $arr[$b][] = linkownik('profil', $as2['n2'], '');
                            $arr[$b][] = $as2['w1'] . ":" . $as2['w2'];
                            $arr[$b][] = $as2['k_1'] . ":" . $as2['k_2'];

                            $arr[$b][] = ($as2['spotkanie'] == 'ele' ?
                                (empty($mozliwe) ? "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>" : "<a class=\"i-access-denied\"></a>")
                                :
                                (empty($jest_w) ? "<a href=\"" . AKT . "&edycja={$as2[0]}\"  class=\"i-edit\"></a>" : "<a class=\"i-access-denied\"></a>")
                            );
                            $arr[$b][] = str_replace('_', '/', $as2['spotkanie']);
                        }


                        $table = new createTable('adminFullTable center');
                        $table->setTableHead(array('', admsg_id, admsg_rozegrano, admsg_gospodarz, '', '', admsg_gosc, admsg_wynik, admsg_punkty, admsg_edytuj, admsg_typ), 'adminHeadersClass');
                        $table->setTableBody($arr);
                        echo $table->getTable();

                    }
                }
            } else {
                wyswietl_listy_rozgrywek(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }

}
wybor_gry_admin(19);
?>
