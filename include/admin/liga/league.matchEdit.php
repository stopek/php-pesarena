<?
//--------------------//
// komunikaty zebrane //
//--------------------//
$wybrana_gra = czysta_zmienna_get($_GET['wybrana_gra']);
$edycja = (int)$_GET['edycja'];
$zapisz = czysta_zmienna_post($_POST['zapisz']);
$nowe_wynik_1 = (int)$_POST['n_w_1'];
$nowe_wynik_2 = (int)$_POST['n_w_2'];
$nowe_punkty_1 = (int)$_POST['n_p_1'];
$nowe_punkty_2 = (int)$_POST['n_p_2'];

require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.match.php');
require_once('include/functions/function.host.php');
require_once('include/admin/funkcje/function.table.php');

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!empty($r_id)) {
            if (!in_array('liga_edycja', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
            } else { ###
                if (!empty($zapisz)) {
                    if (isset($nowe_wynik_1) && isset($nowe_wynik_2)) {
                        szczegoly_meczu($edycja, TABELA_LIGA);
                        $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where id='{$edycja}';"));
                        if ($ile != '1') {
                            note(admsg_IdDontExists, "blad");
                        } else {
                            $suma1 = ($punkty_ma_1 * (-1)) + $nowe_punkty_1;
                            $suma2 = ($punkty_ma_2 * (-1)) + $nowe_punkty_2;
                            $wynik2 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma1}' WHERE id_gracza='{$GLOBALS['gral1']}' && vliga='{$wybrana_gra}';");
                            $wynik3 = mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma2}' WHERE id_gracza='{$GLOBALS['gral2']}' && vliga='{$wybrana_gra}';");
                            $wynik4 = mysql_query("UPDATE " . TABELA_LIGA . "	set w1='{$nowe_wynik_1}',w2='{$nowe_wynik_2}' WHERE id='{$edycja}';");
                            define('ADMIN_EDIT', TRUE);
                            zakoncz_spotkanie($edycja, $GLOBALS['gral2'], TABELA_LIGA);
                        }
                    } else {
                        note(admsg_wFields, "blad");
                    }
                }
            } ###

            if (!empty($edycja) && empty($zapisz)) {
                formularz_edycja_meczu(TABELA_LIGA, $edycja);
            } else {
                echo "
						<div class=\"smallField\">
							<div>
								<fieldset><legend>" . admsg_searchMatch . "</legend>
									<form method=\"post\" action =\"\" class=\"smallInputs\">
									<ul>
										<li><input type=\"text\" name=\"searchIdMatch\"/></li>
										<li><input type=\"submit\" name=\"search\" value=\"" . admsg_szukaj . "\"/></li>
									</ul>
									</form>
								</fieldset>
							</div>
							<div>
								<fieldset><legend>" . admsg_searchLeague . "</legend>
									<form method=\"post\" action =\"\" class=\"smallInputs\">
									<ul>
										<li><input type=\"text\" name=\"searchLeagueMatch\"/></li>
										<li><input type=\"submit\" name=\"search\" value=\"" . admsg_szukaj . "\"/></li>
									</ul>
									</form>
								</fieldset>
							</div>
							<div>
								<fieldset><legend>" . admsg_matchType . "</legend>
									<form method=\"post\" action =\"\" class=\"smallInputs\">
									<ul>
										<li>
											<select name=\"searchMatchType\">
											<option value=\"akt\">Normal
											<option value=\"bar\">Baraz
											</select>
										</li>
										<li><input type=\"submit\" name=\"search\" value=\"" . admsg_szukaj . "\"/></li>
									</ul>
									</form>
								</fieldset>
							</div>
						</div>
					";


                $allowLeague = array('1', '2', '3', '4', '5', 'a', 'b', 'c', 'd', 'e');
                $allowMatchType = array('akt', 'bar');
                $search = $_POST['search'];
                $searchLeagueMatch = strtolower($_POST['searchLeagueMatch']);
                $searchIdMatch = (int)$_POST['searchIdMatch'];
                if (!empty($search) && (!empty($searchLeagueMatch) && in_array($searchLeagueMatch, $allowLeague))) {
                    $addSql = " && nr_ligi='{$searchLeagueMatch}'";
                }
                if (!empty($search) && !empty($searchIdMatch)) {
                    $addSql = " && id='{$searchIdMatch}' ";
                }
                if (!empty($search) && (empty($searchIdMatch) && (!in_array($searchMatchType, $allowMatchType)))) {
                    $addSql = " && spotkanie='{$searchMatchType}' ";
                }
                if (!empty($search) && (empty($searchIdMatch) && (!in_array($searchLeagueMatch, $allowLeague)))) {
                    note("Brak wynikow o podanych kryteriach", "blad");
                }


                $sql = "SELECT * FROM " . TABELA_LIGA . " where vliga='{$wybrana_gra}' && r_id='{$r_id}' && 
						spotkanie = 'akt' && status='3' {$addSql}  order by `rozegrano` DESC";


                $a1 = mysql_query($sql);
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
                    $arr[$b][] = status_meczu($as2['status']);
                    $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
                }


                $table = new createTable('adminFullTable center');
                $table->setTableHead(array('', admsg_id, admsg_rozegrano, admsg_gospodarz, '', '', admsg_gosc, admsg_wynik, admsg_punkty, admsg_status, admsg_edytuj), 'adminHeadersClass');
                $table->setTableBody($arr);
                echo $table->getTable();

            }
        } else {
            wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
        }

    } else {
        note(admsg_gameDenied, "blad");
    }

}
wybor_gry_admin(19);
?>
