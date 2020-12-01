<?
// deklaracja zmiennych 
$wybrana_gra = czysta_zmienna_get($_GET['wybrana_gra']);
$edycja = (int)$_GET['edycja'];
$poka = $_GET['poka'];
$usun = (int)$_GET['usun'];
$zapisz = czysta_zmienna_post($_POST['zapisz']);
$nowe_wynik_1 = (int)$_POST['n_w_1'];
$nowe_wynik_2 = (int)$_POST['n_w_2'];

require_once('include/admin/functions/admin.match.php');
require_once('include/functions/function.host.php');


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!empty($zapisz)) {
            if (!in_array('wyzwania_edytuj', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
            } else { ###
                if (isset($nowe_wynik_1) && isset($nowe_wynik_2)) {
                    szczegoly_meczu($edycja, TABELA_WYZWANIA);
                    $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where id='{$edycja}';"));
                    if ($ile != '1') {
                        note("Mecz o podanym id nie istnieje", "blad");
                    } else {

                        $suma1 = ($punkty_ma_1 * (-1)) + $nowe_punkty_1;
                        $suma2 = ($punkty_ma_2 * (-1)) + $nowe_punkty_2;
                        $wynik2 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma1}' WHERE id_gracza='{$GLOBALS['gral1']}' && vliga='{$wybrana_gra}';");
                        $wynik3 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma2}' WHERE id_gracza='{$GLOBALS['gral2']}' && vliga='{$wybrana_gra}';");
                        $wynik4 = mysql_query("UPDATE " . TABELA_WYZWANIA . "	set w1='{$nowe_wynik_1}',w2='{$nowe_wynik_2}' WHERE id='{$edycja}';");
                        define('ADMIN_EDIT', TRUE);
                        zakoncz_spotkanie($edycja, $GLOBALS['gral2'], TABELA_WYZWANIA);
                    }
                } else {
                    note(admsg_wFields, "blad");
                }
            } ###
        }

        // usuwanie wyzwania
        if (!empty($usun)) {
            if (!in_array('wyzwania_usun', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
            } else { ###
                $ile = mysql_num_rows(mysql_query("SELECT * FROM wyzwania where id='{$usun}';"));
                if ($ile != '1') {
                    note("Mecz o podanym id nie istnieje", "blad");
                } else {
                    szczegoly_meczu($usun, TABELA_WYZWANIA);
                    if (mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-'{$punkty_ma_1}' WHERE id_gracza='{$GLOBALS['gral1']}';") &&
                        mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-'{$punkty_ma_2}' WHERE id_gracza='{$GLOBALS['gral2']}';") &&
                        mysql_query("DELETE FROM " . TABELA_WYZWANIA . "  WHERE id='{$usun}';")) {
                        note('Spotkanie zostalo usuniete', 'info');
                        przeladuj($_SESSION['stara']);
                    } else {
                        note('Blad podczas usuwania wyzwania', 'blad');
                    }
                }
            }###
        } // usuwanie wyzwania  - END

        $pod_id = (int)$_POST['pod_id'];
        if (!empty($edycja) && empty($zapisz)) {
            formularz_edycja_meczu(TABELA_WYZWANIA, $edycja);
        } else {

            $wy = czysta_zmienna_post($_POST['wy']);
            $na_stronie = 50;
            $podopcja = (int)$_GET['historia'];
            if (!$podopcja) {
                $podopcja = 1;
            }
            $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM wyzwania where  vliga='{$wybrana_gra}';"));
            $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
            $start = ($podopcja - 1) * $na_stronie;

            if ($poka == 'sz') {
                echo "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\">podaj identyfikator meczu</li>
						<li class=\"glowne_bloki_zawartosc\">
							<form method=\"post\" action=\"administrator.php?opcja=22&wybrana_gra={$wybrana_gra}\">
							<input type=\"text\" name=\"pod_id\"/>
							<input type=\"hidden\" name=\"wy\" value=\"podaj\"/>
							<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
							</form>
						</li>
					</ul>";


            } else {


                if ($wy == 'podaj') {
                    $reszta = " && id='{$pod_id}'";
                } elseif ($poka == 'lp') {
                    $reszta = " && status='3' order by `rozegrano` ASC";
                } else {
                    $reszta = " order by `rozegrano` DESC limit {$start},{$na_stronie}";
                }

                $a1 = mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where vliga='{$wybrana_gra}' {$reszta};");
                $b = 1;
                while ($as2 = mysql_fetch_array($a1)) {
                    $arr[$b][] = $b++;
                    $arr[$b][] = $as2['id'];
                    $arr[$b][] = formatuj_date($as2['rozegrano']);
                    $arr[$b][] = linkownik('profil', $as2['n1'], '');
                    $arr[$b][] = mini_logo_druzyny($as2['klub_1']);
                    $arr[$b][] = mini_logo_druzyny($as2['klub_2']);
                    $arr[$b][] = linkownik('profil', $as2['n2'], '');
                    $arr[$b][] = "{$as2['w1']}:{$as2['w2']}";
                    $arr[$b][] = "{$as2['k_1']}:{$as2['k_2']}";
                    $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&usun={$as2['id']}\" class=\"i-delete\" onclick=\"return confirm('" . admsg_confirmQuestion . "')\"></a>";
                    $arr[$b][] = status_meczu($as2['status']);
                }

                require_once('include/admin/funkcje/function.table.php');
                $table = new createTable('adminFullTable');
                $table->setTableHead(array('', admsg_id, admsg_rozegrano, admsg_gospodarz, '', '', admsg_gosc, admsg_wynik, admsg_punkty, admsg_edytuj, admsg_usun, admsg_status), 'adminHeadersClass-silver');
                $table->setTableBody($arr);
                echo $table->getTable();

                wyswietl_stronnicowanie($_GET['historia'], $wszystkie_strony, "administrator.php?opcja=22&wybrana_gra={$wybrana_gra}&historia=", '');
            }
        }
    } else {
        note("Najprawodopodobniej tutaj nie masz dostepu!!", "blad");
    }

}
wybor_gry_admin(22);
?>
