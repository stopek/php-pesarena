<?
require_once('include/admin/functions/admin.liga.php');
require_once('include/admin/functions/admin.game.php');
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_start', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            if (!empty($r_id)) {
                wlacz_kolejki($_POST['srodek_1'], 1, $r_id);
                wlacz_kolejki($_POST['srodek_2'], 2, $r_id);
                wlacz_kolejki($_POST['srodek_3'], 3, $r_id);
                wlacz_kolejki($_POST['srodek_4'], 4, $r_id);
                wlacz_kolejki($_POST['srodek_5'], 5, $r_id);


                wlacz_kolejki($_POST['srodek_A'], A, $r_id);
                wlacz_kolejki($_POST['srodek_B'], B, $r_id);
                wlacz_kolejki($_POST['srodek_C'], C, $r_id);
                wlacz_kolejki($_POST['srodek_D'], D, $r_id);
                wlacz_kolejki($_POST['srodek_E'], E, $r_id);


                $tablica = array('A', 'B', 'C', 'D', 'E', '1', '2', '3', '4', '5');


                //
                //print "";

                print "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\"></li>
						<li class=\"glowne_bloki_zawartosc\">
							<form method=\"post\" action=\"\">";


                foreach ($tablica as $key => $ktora) {
                    $maxRound = mysql_fetch_array(mysql_query("SELECT max(kolejka) FROM " . TABELA_LIGA . "
									WHERE nr_ligi='{$ktora}' && spotkanie='off' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}' GROUP BY r_id"));


                    echo "<fieldset class=\"adminAccessList\">
									<legend>Liga/Grupa: {$ktora}</legend><span class=\"downIkon\">
									<a href=\"javascript:rozwin('roundsNoLeague_{$key}')\"></a></span>
									<div id=\"roundsNoLeague_{$key}\">
										<ul class=\"adminAccessOption\">";
                    $checkExists = FALSE;
                    for ($kolejka = 1; $kolejka <= $maxRound[0]; $kolejka++) {
                        $counter = mysql_fetch_array(mysql_query("
														SELECT count(id) as isMatch,
															(SELECT count(id) FROM  " . TABELA_LIGA . "  where nr_ligi='{$ktora}' && kolejka='{$kolejka}' && spotkanie='off' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}') as counter,
															(SELECT count(id) FROM  " . TABELA_LIGA . "  where nr_ligi='{$ktora}' && kolejka='{$kolejka}' && spotkanie='akt' && status!='3' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}') as endMatch,
															(SELECT count(id) FROM  " . TABELA_LIGA . "  where nr_ligi='{$ktora}' && kolejka='{$kolejka}' && spotkanie='akt' && status='3' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}') as e
														FROM  " . TABELA_LIGA . " where  nr_ligi='{$ktora}' && kolejka='{$kolejka}' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}' GROUP BY r_id	
													"));
                        if (!empty($counter['isMatch'])) {
                            $percent = @round($counter['endMatch'] / $counter['e'], 2) * 100;
                            echo "
														<li>
															<input type=\"checkbox\" name=\"srodek_{$ktora}[]\" value=\"{$kolejka}\" 
															" . ($counter['counter'] == 0 ? "checked=\"checked\" disabled " : null) . ">
															<em>Kolejka {$kolejka}</em>
															<span>Zakonczonych: {$counter['e']}({$percent}%)</span>
														</li>
														";
                            $checkExists = TRUE;
                        }
                    }
                    echo "
										</ul>
										" . (empty($checkExists) ? "<span class=\"error\"><span>W lidze/Grupie: <b>{$ktora}</b> brak kolejek</span></span>" : null) . "
									</div>
								</fieldset>";


                }
                print "<input type=\"submit\" name=\"zapisz\" value=\"zapisz\"/>
							</form>
					</li>
				</ul>";
            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
            }
        } ###

    } else {
        note("Najprawodopodobniej tutaj nie masz dostepu!!", "blad");
    }
}
wybor_gry_admin(5);

?>

