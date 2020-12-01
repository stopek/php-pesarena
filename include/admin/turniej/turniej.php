<?
require_once('include/admin/functions/admin.turniej.php');
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.player.php');
require_once('include/admin/functions/admin.turniej-drabinka.php');
require_once('include/admin/functions/tournament/tt.startForm.php');
require_once('include/admin/functions/tournament/tt.generator.php');

$ile_grup = (int)$_GET['ile_grup'];
$mozliwe = array("1", "2", "3", "4", "5");

$pokaz = czysta_zmienna_get($_GET['pokaz']);
$wyklucz = (int)$_GET['wyklucz'];
$nowygracz = (int)$_POST['nowygracz'];
$nowy_gracz_add = czysta_zmienna_post($_POST['nowy_gracz_add']);
$klub = id_druzyny((int)$_POST['klub']);
$r_id = (int)$_GET['r_id'];
$wykonaj = (int)$_POST['wykonaj'];
$grupy = (int)$_POST['grupy'];

$do_l = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}';"));
$wyszlo = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}' && status='1';"));
$st = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_LISTA . " where vliga='{$wybrana_gra}' && id='{$r_id}';"));
$do_konca = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where vliga='{$wybrana_gra}' && r_id='{$r_id}' && status!='3';"));
$nazwy_kosze = array(0 => 1, 1 => 2, 2 => 3, 3 => 4);


$rememberConfig = mysql_query("SELECT * FROM turniej_ustawienia where vliga='{$wybrana_gra}' && r_id='{$r_id}'");
while ($configurations = mysql_fetch_array($rememberConfig)) $config[$configurations[1]] = $configurations[2];


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('turniej_generowanie', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if (!empty($r_id)) {


                if ($_POST['zapisz_ustawienie']) {
                    $allowOption = array('start_type', 'elimination_type', 'ilosc_koszy', 'team_rand', 'team_rand_cup', 'druzyny_tur',
                        'druzyny_tur_cup', 'druzyny_tur_fg', 'ilosc_kolejek', 'reszta', 'ilosc_awans', 'kosze_fg', 'sys_cup', 'fg_num_players');
                    foreach ($_POST as $key => $value) {
                        if (in_Array($key, $allowOption))
                            zapisz_ustawienie_gra($key, czysta_zmienna_post($value), TABELA_TURNIEJ_USTAWIENIA);
                    }
                    przeladuj($_SESSION['stara']);
                }


                try {


                    switch ($st['status']) {


                        case '0':
                            echo "<div class=\"leagueStatus\">Aktualny stan: <b>Zapisy do Turnieju wlaczone</b></div>";
                            break;


                        case 'S':
                            echo showTournamentStartForm($do_l, $config);
                        case 'P':


                            $typ_meczu = array(
                                'status' => array(
                                    'S' => 'Zapisy wstrzymane',
                                    'P' => 'Eliminacje zakonczone. Gramy faze glowna'),
                                'kolejny_status' => array('S' => ($config['start_type'] == 2 ? 'KE' : 'K'), 'P' => 'KE'),
                                'ilosc_koszy' => array('S' => ($config['start_type'] == 2 ? $config['kosze_fg'] : $config['ilosc_koszy']), 'P' => $config['kosze_fg']),
                                'ilosc_graczy' => array('S' => ($config['start_type'] == 2 ? $config['fg_num_players'] : $do_l), 'P' => $config['fg_num_players'])
                            );

                            $changeT = changeT($config, $do_l);

                            echo "<ul class=\"glowne_bloki\">
									<li class=\"glowne_bloki_naglowek legenda\"><span>Turniej informacje</span></li>
									<li class=\"glowne_bloki_zawartosc\">
										<div class=\"leagueStatus generatorInfo\">Aktualnie: <span>{$typ_meczu['status'][$st['status']]}</span></div>
										" . makeGenerationButton($changeT) . "
									</li>
								</ul>";


                            $graczy_w_koszu = @floor($do_l / $typ_meczu['ilosc_koszy'][$st['status']]);
                            $reszta = @($do_l % $typ_meczu['ilosc_koszy'][$st['status']]);


                            $wykorzystaj = null;

                            $sql = mysql_query("SELECT t.id_gracza,g.id,g.ranking FROM " . TABELA_TURNIEJ_GRACZE . " t 
								LEFT JOIN " . TABELA_GRACZE . " g ON t.id_gracza=g.id_gracza WHERE t.r_id='{$r_id}' && g.vliga='{$wybrana_gra}' ORDER BY ranking DESC ");

                            $n = $temp = 0;
                            if ($typ_meczu['ilosc_graczy'][$st['status']] == $do_l || $st['status'] == 'S') {
                                while ($rek = mysql_fetch_array($sql)) {
                                    $temp++;
                                    for ($a = 0; $a <= $typ_meczu['ilosc_koszy'][$st['status']]; $a++) {
                                        if ($temp > $graczy_w_koszu * $a) {
                                            $t = $a;
                                            if ($t + 1 > $typ_meczu['ilosc_koszy'][$st['status']]) {
                                                if ($config['reszta'] == 1)
                                                    $rMiejsce = $n++;
                                                else
                                                    $rMiejsce = losuj_liczbe($reszta, 0);
                                            } else {
                                                $rMiejsce = $t;
                                            }
                                        }
                                    }
                                    if (!empty($_GET['podziel'])) {
                                        $error = wrzuc_gracz_do_kosza_turniej($rMiejsce, $rek['id_gracza'], $error, $st, $losowanieDruzyn);
                                    }
                                    $wykorzystaj .= "$temp.({$rek['id_gracza']}) Gracz: " . sprawdz_login_id($rek[0]) . " Ranking: " . $rek[2] . " Trafil do kosza: " . ($rMiejsce + 1) . " \n";
                                }
                            } else {
                                note("Zla liczba graczy dla tej fazy: {$do_l}. Wymagane jest {$typ_meczu['ilosc_graczy'][$st['status']]}", "blad");
                            }

                            unset($_SESSION[0]);


                            if (!empty($error['error'])) {
                                note("Turniej jest juz na innej fazie", "blad");
                            }
                            if (!empty($error['confirm'])) {
                                mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='{$typ_meczu['kolejny_status'][$st['status']]}' WHERE id='{$r_id}'");
                                note("Gracze zostali przypisani do koszy", "info");
                                przeladuj($_SESSION['stara']);
                            }
                            if (!empty($error['count'])) {
                                note("Liczba graczy do turnieju nie odpowiada minimum", "blad");
                            }


                            echo "
									<ul class=\"glowne_bloki\">
										<li class=\"glowne_bloki_naglowek bot\">Tresc na forum o przynalezeniu do koszy</li>
										<li class=\"glowne_bloki_zawartosc\"><textarea class=\"textarea_a_full\">{$wykorzystaj}</textarea></li>
									</ul>
								";
                            break;


                        case 'K':
                        case 'KE':


                            $typ_meczu = array(
                                'status' => array(
                                    'K' => 'Gracze podzieleni na kosze(przed eliminacja)',
                                    'KE' => 'Gracze podzieleni na kosze (po eliminacji - przed faza glowna tur.)'),
                                'kolejny_status' => array('K' => 'G', 'KE' => 'GE'),
                                'ilosc_koszy' => array('K' => $config['ilosc_koszy'], 'KE' => $config['kosze_fg']),
                                'select_team' => array('K' => $config['druzyny_tur'], 'KE' => $config['druzyny_tur_fg'])
                            );
                            $graczy_w_koszu = floor($do_l / $typ_meczu['ilosc_koszy'][$st['status']]);
                            //tworzy wpis w tabeli turniej_temp z tym do jakiej grupy trafil by gracz
                            //po kliknieciu w przycisk zapisujacy
                            //sluzy to do tego zeby opiekun mial podglad na jakie grupy dziala sie gracze
                            //i w razieczego moze odswiezyc zmienia


                            $checkTeam = ((($config['start_type'] == 1 || $config['start_type'] == 3) && ($config['team_rand'] == 1 || $config['team_rand'] == 4) ||
                                (($config['start_type'] == 2) && ($config['team_rand'] == 4 || $config['team_rand'] == 3))) ? 1 : null);


                            if ($_GET['podziel'] == 'grupy') {
                                podziel_grupy_turniej($typ_meczu['kolejny_status'][$st['status']], $checkTeam);
                            }

                            //ustawianie automatyczne przynaleznosci do grupy - problem sesji
                            if ($_GET['wys'] == 'save') {
                                turniej_zapisz_status_grupy_gracza_wszyscy($graczy_w_koszu, $typ_meczu['ilosc_koszy'][$st['status']], $do_l, $typ_meczu['select_team'][$st['status']],
                                    $checkTeam);
                            }
                            //ustawianie automatyczne przynaleznosci do grupy - problem sesji

                            //ustawianie recznie przynaleznosci do grupy pojedyncze ustawianie
                            elseif ($_GET['wys'] == 'once') {
                                turniej_zapisz_status_grupy_gracza_jeden($config['druzyny_tur']);
                            } //ustawianie recznie przynaleznosci do grupy pojedyncze ustawianie


                            else {
                                echo "<div id=\"moreOptionGenerateTournament\">
										<ul>
											<li class=\"lA\"><a href=\"" . AKT . "&wys=save\"></a></li>
											<li class=\"lH\"><a href=\"" . AKT . "&wys=once\"></a></li>
										</ul>
									</div>";
                            }
                            break;


                        case 'GE':


                        case 'G':


                            $typ_meczu = array(
                                'skroty' => array('G' => 'ele', 'GE' => 'tur'),
                                'status' =>
                                    array(
                                        'G' => 'Gracze podzieleni na grupy(przed eliminacja)',
                                        'GE' => 'Eliminacje zakonczone (generuj mecze fazy glownej turnieju)'
                                    ),
                                'kolejny_status' => array('G' => ($config['start_type'] == 2 ? 'FG' : 1), 'GE' => 'FG'),
                                'ilosc_koszy' => array('G' => ($config['start_type'] == 2 ? $config['kosze_fg'] : $config['ilosc_koszy']), 'GE' => $config['kosze_fg']),
                                'ilosc_kolejek' => array('G' => 4, 'GE' => null)
                            );
                            $graczy_w_koszu = floor($do_l / $typ_meczu['ilosc_koszy'][$st['status']]);
                            note("<b>{$typ_meczu['status'][$st['status']]}</b>", "blad");


                            if ($_GET['podziel'] == 'mecze') {
                                for ($gr = 1; $gr <= $graczy_w_koszu; $gr++) {
                                    $var = ((($config['start_type'] == 1 || $config['start_type'] == 3) && $config['elimination_type'] == 2 && $st['st'] != 'GE') ?
                                        $typ_meczu['ilosc_kolejek'][$st['status']] : $typ_meczu['ilosc_kolejek'][$st['status']]);

                                    echo "<fieldset><legend>Zapis meczy dla grupy: <b>{$gr}</b></legend>";

                                    generateTournamentMatches($gr, $typ_meczu['skroty'][$st['status']], $var);
                                    echo "</fieldset>";

                                }

                                mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='{$typ_meczu['kolejny_status'][$st['status']]}' WHERE id='{$r_id}'");
                            } else {


                                echo "<div id=\"moreOptionGenerateTournament\">
										<ul>
											<li class=\"lCM\"><a href=\"" . AKT . "&podziel=mecze\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"></a></li>
										</ul>
									</div>";
                            }

                            break;


                        case 'FG':
                        case '1':
                            $typ_meczu = array(
                                'skroty' => array('FG' => 'tur', '1' => 'ele'),
                                'przechodza' => array('FG' => $config['sys_cup'], '1' => $config['ilosc_awans']),
                                'kolejny_status' => array('1' => ($config['start_type'] == 3 ? 'KM' : 'P'), 'FG' => 'KM')
                            );

                            note("Mecze turniejowe wygenerowane, do konca pozostalo: <b> " . (empty($do_konca) ? "mecze zakonczone" : $do_konca . " meczy") . "</b>", "info");

                            //zmieniam statusy graczom
                            //albo gra, albo nie gra - (usuwam z bazy)
                            if (!empty($_POST['zapisz'])) {
                                zakoncz_eliminacje_turniej($typ_meczu['przechodza'][$st['status']], $typ_meczu['kolejny_status'][$st['status']], $do_konca);
                            }


                            //wyswietlam tabele dla wszystkich grup
                            //licze maxymalna liczbe dla nr. grupy
                            //definiuje MAX_GRUPA
                            $max_grupa = mysql_fetch_array(mysql_query("SELECT MAX(status) FROM " . TABELA_TURNIEJ_GRACZE . " WHERE r_id='{$r_id}' && vliga='{$wybrana_gra}' GROUP BY r_id"));
                            define(MAX_GRUPA, $max_grupa[0]);

                            echo "
										<form method=\"post\"><input type=\"hidden\" name=\"zapisz\" value=\"1\"/>
											<div id=\"moreOptionGenerateTournament\">
												<ul>
													<li class=\"lEF cHand\"><button></button></li>
												</ul>
											</div>";

                            for ($ii = 1; $ii <= MAX_GRUPA; $ii++) {
                                if ($ii % 2 != 0) {
                                    echo "<fieldset>";
                                }
                                turniej_mecze_eliminacji_tabela_admin($ii, $typ_meczu['skroty'][$st['status']], $typ_meczu['przechodza'][$st['status']]);
                                if ($ii % 2 == 0) {
                                    echo "</fieldset>";
                                }
                            }

                            echo "</form>";
                            break;


                        case 'P':
                            echo "Eliminacje zakonczone";
                        case 'KM':
                            echo "MS zakonczone . teraz system drabinkowy";


                            $max_grupa = mysql_fetch_array(mysql_query("SELECT MAX(status) FROM " . TABELA_TURNIEJ_GRACZE . " WHERE r_id='{$r_id}' && vliga='{$wybrana_gra}' GROUP BY r_id"));
                            define(MAX_GRUPA, $max_grupa[0]);
                            echo "<form method=\"post\" action=\"\">
									<fieldset><input type=\"submit\" name=\"zapisz\" onclick=\"return confirm('" . admsg_confirmQuestion . "')\" value=\"" . admsg_save . "\"/></fieldset>
									";

                            echo "<div id=\"moreOptionGenerateTournament\">
										<ul>
											<li class=\"lZMD\"><a href=\"" . AKT . "&zapisz_m_drabinki=zapisz\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"></a></li>
										</ul>
									</div>";

                            for ($ii = 1; $ii <= MAX_GRUPA; $ii++) {
                                if ($ii % 2 != 0) {
                                    echo "<fieldset>";
                                }
                                turniej_mecze_eliminacji_tabela_admin($ii, 'tur', 1);
                                if ($ii % 2 == 0) {
                                    echo "</fieldset>";
                                }
                            }
                            echo "</form>";


                            if ($_GET['zapisz_m_drabinki'] == 'zapisz') {
                                //a -1
                                //b -2
                                //c -3
                                //d -4
                                //e -5
                                //f -6
                                //g -7
                                //h -8
                                $t = 1;
                                //m1,g1;m2,g2


                                zapisz_mecz_drabinki(1, 1, 2, 2, $t++, '1_8');
                                zapisz_mecz_drabinki(2, 1, 1, 2, $t++, '1_8');

                                zapisz_mecz_drabinki(1, 3, 2, 4, $t++, '1_8');
                                zapisz_mecz_drabinki(2, 3, 1, 4, $t++, '1_8');

                                if ($config['sys_cup'] == 16) {
                                    zapisz_mecz_drabinki(1, 5, 2, 6, $t++, '1_8');
                                    zapisz_mecz_drabinki(2, 5, 1, 6, $t++, '1_8');

                                    zapisz_mecz_drabinki(1, 7, 2, 8, $t++, '1_8');
                                    zapisz_mecz_drabinki(2, 7, 1, 8, $t++, '1_8');
                                }
                                mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='D' WHERE id='{$r_id}'");
                                mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='1' WHERE r_id='{$r_id}'");

                            }


                            break;
                        case 'D':
                            if ($wyszlo == '1' && !empty($_GET['end'])) {
                                endParty($r_id, TABELA_TURNIEJ_LISTA);
                            }


                            switch ($_GET['start']) {
                                case '1-4':
                                    echo wlaczanie_etapow_meczu_drabinki("1_4", 4, $r_id);
                                    break;
                                case '1-2':
                                    echo wlaczanie_etapow_meczu_drabinki("1_2", 2, $r_id);
                                    break;
                                case '1-1':
                                    echo wlaczanie_etapow_meczu_drabinki("1_1", 1, $r_id);
                                    break;
                            }


                            echo "
								<div id=\"moreOptionGenerateTournament\">
									<ul>
										<li class=\"lG1-4 cHand\"><a href=\"" . AKT . "&start=1-4\"></a></li>
										<li class=\"lG1-2 cHand\"><a href=\"" . AKT . "&start=1-2\"></a></li>
										<li class=\"lG1-1 cHand\"><a href=\"" . AKT . "&start=1-1\"></a></li>
									</ul>
								</div>";


                            echo "<ul class=\"glowne_bloki\">
										<li class=\"glowne_bloki_naglowek\">Aktualnie trwa system drabinkowy</li>
										<li class=\"glowne_bloki_zawartosc\">
											<div class=\"leagueStatus\">
												" . ($wyszlo == 1
                                    ?
                                    "Mecze zakonczone: 
													<span>
														<a href=\"" . AKT . "&end={$r_id}\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-end-party\"></a>
													</span>"
                                    : null) . "
											</div>
										</li>
									</ul>";


                            break;


                        default:
                            echo 123;
                            break;
                    } // menu glowek Turnieju - END


                } catch (Exception $e) {
                    var_dump($e);
                }
            } else {
                wyswietl_listy_rozgrywek(TABELA_TURNIEJ_LISTA, TABELA_TURNIEJ_GRACZE);
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(21);
?>
