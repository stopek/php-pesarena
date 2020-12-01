<?
//--------------------//
// komunikaty zebrane //
//--------------------//
$wlacz = $_GET['wlacz'];
$ilu_sie_zglosilo = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}'"));
$wlacz_etapy = $_GET['generation'];


$mozliwosci = array(1, 2, 4, 8, 16, 32);
$ilu_sie_zglosilo = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}';"));
$czy_po_eliminacjach = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$wybrana_gra}' && spotkanie='ele' && status!='3'  && r_id='{$r_id}';"));
$ilu_wyszlo = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$wybrana_gra}' && status='1' && r_id='{$r_id}';"));
$s = mysql_fetch_row(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " where vliga='{$wybrana_gra}' && id='{$r_id}';"));
$status_pucharu = $s[8];
require_once('include/admin/funkcje/function.table.php');


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('puchar_generowanie', explode(',', POZIOM_U_A))) {
            return note(admsg_accessDenied, "blad");
        } else { ###
            if ($status_pucharu != 4) {
                if (!empty($r_id)) {
                    // wlaczanie eliminacji do pucharu
                    if (!empty($wlacz)) {
                        if ($ilu_sie_zglosilo > 8 && !in_array($ilu_sie_zglosilo, $mozliwosci) && $status_pucharu == 'S') {
                            generateCupMatches($r_id, 'ele', 1, 'S');
                            generateCupMatches($r_id, 'ele', 2, 1);
                            polacz_zpauzowanych($r_id);
                        } else {
                            if ($ilu_sie_zglosilo < 8) {
                                note(admsg_badCountUserCup, "blad");
                            }
                            if (in_array($ilu_sie_zglosilo, $mozliwosci)) {
                                note(admsg_goodCountUserCup, "blad");
                            }
                            if ($status_pucharu != 'S') {
                                note(admsg_cupStarted, "blad");
                            }
                        }
                    }

                    if ($ilu_wyszlo == '1' && !empty($_GET['end'])) {
                        endParty($r_id, TABELA_PUCHAR_DNIA_LISTA);
                    }


                    // wlaczanie poszczegolnych etapow pucharu
                    if ($wlacz_etapy) {
                        $c = array('2' => '1_1', '4' => '1_2', '8' => '1_4', '16' => '1_8', '32' => '1_16');
                        if (!empty($wlacz_etapy)) {
                            if (in_array($ilu_wyszlo, $mozliwosci)) {
                                $count = mysql_fetch_array(
                                    mysql_query("SELECT count(id) FROM " . TABELA_PUCHAR_DNIA . " 
								where spotkanie='{$c[$ilu_wyszlo]}' &&  vliga='{$wybrana_gra}' && 
								r_id='{$r_id}' GROUP BY id;"));

                                if (empty($count[0])) {
                                    //id rozgrywek,
                                    //status z jakim zostana zapisane mecze
                                    //sposob losowania meczy
                                    //status dla tabeli z graczami
                                    generateCupMatches($r_id, $c[$ilu_wyszlo], 2, 3);
                                } else {
                                    note(admsg_cupStarted, "blad");
                                }
                            }
                        }
                    }


                    // wyswietlanie glownej tabeli
                    $sprawdz_status_pucharu = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " where vliga='{$wybrana_gra}' && id = '{$id}';"));
                    $licz_puchary = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " where vliga='{$wybrana_gra}' && status!=4;"));

                    if ($sprawdz_status_pucharu['status'] == 'S' || $licz_puchary != 0) {
                        echo "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\">" . admsg_welcomeToCupSystem . "</li>
						<li class=\"glowne_bloki_zawartosc\">";


                        $arr[$b][] = admsg_savedPlayersInCup;
                        $arr[$b][] = $ilu_sie_zglosilo;
                        $b++;
                        $arr[$b][] = admsg_playedPlayersInCup;
                        $arr[$b][] = $ilu_wyszlo;
                        $b++;

                        if ($status_pucharu == 'S') {
                            if (in_array($ilu_wyszlo, $mozliwosci)) {
                                $arr[$b][] = admsg_countCupUserDone;
                                $arr[$b][] = "<span><a href=\"" . AKT . "&generation=1\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-start-party\"></a></span>";
                                $b++;
                            } else {
                                if ($ilu_sie_zglosilo > 8) {
                                    $arr[$b][] = admsg_turnOnElimination;
                                    $arr[$b][] = "<a href=\"" . AKT . "&wlacz=eliminacje\"  onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-start-party\"></a>";
                                    $b++;
                                } else {
                                    $arr[$b][] = array('cols' => 2, 'value' => admsg_tooSmallPlayersInCup);
                                    $b++;
                                }
                            }
                        } elseif ($status_pucharu == 1) {
                            if ($czy_po_eliminacjach == 0) {
                                $arr[$b][] = array('cols' => 2, 'value' => admsg_eliminationFinish);
                                $b++;
                            } else {
                                $arr[$b][] = admsg_elimination;
                                $arr[$b][] = $czy_po_eliminacjach;
                                $b++;
                            }
                        } elseif ($status_pucharu == 2) {
                            if (in_array($ilu_wyszlo, $mozliwosci)) {
                                $arr[$b][] = admsg_turnOnCup;
                                $arr[$b][] = "<a href=\"" . AKT . "&generation=1\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-start-party\"></a>";
                                $b++;
                            } else {
                                $arr[$b][] = array('cols' => 2, 'value' => admsg_badCountUser);
                                $b++;
                            }
                        } elseif ($status_pucharu == 3) {
                            $arr[$b][] = array('cols' => 2, 'value' => admsg_generationMatchesInCup);
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<strong>1/16</strong> <span><span class=\"cupStatus\">" . pokaz_status_meczy(32, $wybrana_gra) . "</span></span>");
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<strong>1/8</strong> <span><span class=\"cupStatus\">" . pokaz_status_meczy(16, $wybrana_gra) . "</span></span>");
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<strong>1/4</strong> <span><span class=\"cupStatus\">" . pokaz_status_meczy(8, $wybrana_gra) . "</span></span>");
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<strong>1/2</strong> <span><span class=\"cupStatus\">" . pokaz_status_meczy(4, $wybrana_gra) . "</span></span>");
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<strong>1/1</strong> <span><span class=\"cupStatus\">" . pokaz_status_meczy(2, $wybrana_gra) . "</span>");
                            $b++;
                            $arr[$b][] = array('cols' => 2, 'value' => "<span><a href=\"" . AKT . "&generation=1\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-start-party\"></a></span>");
                            $b++;
                        }
                        if ($ilu_wyszlo == 1) {
                            $arr[$b][] = admsg_cupEnded;
                            $arr[$b][] = "<span><a href=\"" . AKT . "&end={$r_id}\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" class=\"i-end-party\"></a></span>";
                            $b++;
                        }


                        $table = new createTable('adminFullTable', admsg_manageCupOption);
                        $table->setTableHead(array(admsg_opis, admsg_opcja), "adminHeadersClass-silver");
                        $table->setTableBody($arr);
                        echo $table->getTable();

                        echo "</li></ul>";
                    }
                } else {
                    wyswietl_listy_rozgrywek(TABELA_PUCHAR_DNIA_LISTA, TABELA_PUCHAR_DNIA_GRACZE);
                }
            } elseif ($status_pucharu == 4) {
                note(admsg_cupEnded, "info");
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(7);
?>


