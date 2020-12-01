<? if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST']);
    exit;
}
$id = (int)$_GET['podopcja'];
$licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . "  WHERE id='{$id}' && n1='{$id_zalogowanego_usera}' && status='1';"));
$wynik_spotkania = (int)$_POST['wynik_spotkania'];
$chec = (int)$_POST['zapis_do'];
$mozliwe_checi = array('5');
require_once('include/functions/function.admin-druzyny.php');
require_once('include/functions/function.host.php');
require_once('include/functions/function.validate.php');
require_once('include/functions/function.wyzwania.php');
require_once('include/functions/other/other.history.php');

// podawanie wyniku
$wynik_spotkania = (int)$_POST['wynik_spotkania'];
if (!empty($wynik_spotkania)) {
    podaj_wynik((int)$_POST['przykladowy_wynik_1'], (int)$_POST['przykladowy_wynik_2'], (int)$_POST['id_spotkania'], $id_zalogowanego_usera, TABELA_WYZWANIA);
} //podawanie wyniku - END


switch ($podmenu) {
    case 'zakoncz,spotkanie':
        $id = (int)$_GET['podopcja'];
        $glos_k_ = $_POST['glos_k_'];
        $komentarze_opcje = array('0', '+', '-');
        if (isset($glos_k_) && in_array($glos_k_, $komentarze_opcje)) {
            zakoncz_spotkanie((int)$_GET['podopcja'], $id_zalogowanego_usera, TABELA_WYZWANIA);
        } else {

            if (!empty($glos_k_) && !in_array($glos_k_, $komentarze_opcje)) {
                note(M423, "blad");
            }


            reklamy_adkontekst();
            echo "<div id=\"wait\" style=\"text-align:center\">Prosze czekaj. Trwa ladowanie formularza konczacego spotkanie. Zapoznaj sie z naszymi partnerami wyzej <span style=\"display:none;\">";
            formularz_komentarz();
            echo "</span></div>";
        }

        break;
    case 'odrzuc,wynik':
        odrzuc_wynik((int)$_GET['podopcja'], $id_zalogowanego_usera, TABELA_WYZWANIA);
        break;
    case 'odrzuc,spotkanie':
        odrzuc_spotkanie((int)$_GET['podopcja'], $id_zalogowanego_usera, TABELA_WYZWANIA);
        break;

    case 'wyniki':
        echo "
		<div id=\"zakladka_1_wy\" class=\"display_none\">";
        pokaz_tabele_wyzwan($id_zalogowanego_usera, "WHERE (w.n2='{$id_zalogowanego_usera}' || w.n1='{$id_zalogowanego_usera}') &&  w.status!='3'");
        echo "</div>
		<div id=\"zakladka_2_wy\" class=\"display_none\">";
        pokaz_tabele_wyzwan($id_zalogowanego_usera, "WHERE w.n1='{$id_zalogowanego_usera}' && w.status='1'");
        echo "</div>
		<div id=\"zakladka_3_wy\" class=\"display_none\">";
        pokaz_tabele_wyzwan($id_zalogowanego_usera, "WHERE w.n2='{$id_zalogowanego_usera}' && w.status='2'");
        echo "</div>
		<div id=\"zakladka_4_wy\" class=\"display_none\">";
        pokaz_tabele_wyzwan($id_zalogowanego_usera, "WHERE w.n2='{$id_zalogowanego_usera}' && w.status='p'");
        echo "</div>
		<div id=\"zakladka_5_wy\" class=\"display_none\">";
        pokaz_tabele_wyzwan($id_zalogowanego_usera, "WHERE (w.n2='{$id_zalogowanego_usera}' || w.n1='{$id_zalogowanego_usera}') && w.status='3'");
        echo "</div>";
        break;

    case 'historia':
        historia_wszystkich_meczy(TABELA_WYZWANIA, $l_u);
        break;
    case 'podaj,wynik':
        if (!empty($id)) {
            if (!empty($licz)) {
                if (empty($wynik_spotkania)) {
                    $druzyna = mysql_fetch_array(mysql_query("SELECT w.klub_1, w.klub_2,
				(SELECT d1.nazwa FROM druzyny d1 WHERE d1.id=w.klub_1),(SELECT d2.nazwa FROM druzyny d2 WHERE d2.id=w.klub_2),
				(SELECT d3.punkty FROM druzyny d3 WHERE d3.id=w.klub_1),(SELECT d4.punkty FROM druzyny d4 WHERE d4.id=w.klub_2)
				FROM " . TABELA_WYZWANIA . " w WHERE w.id='{$id}' "));

                    szczegoly_meczu($id, 'wyzwania');
                    js_podaj_wynik($GLOBALS['gral1'], $GLOBALS['gral2'], $druzyna[4], $druzyna[5]);
                    print "<form method=\"post\" action=\"\">
				<table border=\"0\" width=\"100%\">
				<Tr>
				<td>";
                    center();
                    start_table();
                    naglowek_meczu(M56);
                    nazwa_gracza($GLOBALS['gral1']);
                    logo_druzyny(1, $GLOBALS['klub_1']);
                    przykladowy_wynik(1);
                    aktualne_miejsce($GLOBALS['pamiec_miejsca_1']);
                    pamiec_pkt($GLOBALS['pamiec_punktow_1']);
                    pkt_za_mecz(1);
                    pkt_z_druzyne(1);
                    bonus(1);
                    nowy_status_pkt(1);
                    moja_druzyna($druzyna[2]);
                    end_table();
                    ecenter();
                    print "</td><td>";
                    center();
                    start_table();
                    naglowek_meczu(M55);
                    nazwa_gracza($GLOBALS['gral2']);
                    logo_druzyny(2, $GLOBALS['klub_2']);
                    przykladowy_wynik(2);
                    aktualne_miejsce($GLOBALS['pamiec_miejsca_2']);
                    pamiec_pkt($GLOBALS['pamiec_punktow_2']);
                    pkt_za_mecz(2);
                    pkt_z_druzyne(2);
                    bonus(2);
                    nowy_status_pkt(2);
                    moja_druzyna($druzyna[3]);
                    end_table();
                    ecenter();
                    print "</td>
				</tr>
				<tr>
				<td colspan=\"2\" class=\"text_center\">
				<input type=\"hidden\" name=\"id_spotkania\" value=\"{$id}\"/>
				<input type=\"hidden\" name=\"wynik_spotkania\" value=\"1\"/>
				<input type=\"image\" src=\"img/podaj_ten_wynik.jpg\" title=\"" . M57 . "\" alt=\"" . M57 . "\"/>
				</td>
				</tr>
				</table></form>";
                }
            } else {
                note(M339, "blad");
            }
        } else {
            note(M37, "blad");
        }
        break;
}
// zawartosc wyzwan - END


// rzucanie wyzwania
if ($podmenu == 'graj') {
    if (!empty($wlaczona_gra['status'])) {
        $last_add = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where n1='{$id_zalogowanego_usera}' && vliga='{$l_u}' order by rozegrano desc;"));
        $wczesniej = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where n1='{$id_zalogowanego_usera}' && vliga='{$l_u}';"));
        $czeka = poczekaj($last_add[8], 60);
        if ($czeka < "0" || $wczesniej == "0") {
            if (!empty($id) && !empty($id)) {
                if ($id != $id_zalogowanego_usera) {
                    $czy_masz_taka_gre = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where vliga='{$l_u}' && id_gracza='{$id_zalogowanego_usera}';"));
                    if ($czy_masz_taka_gre == 1) {
                        $czy_ma_taka_gre = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where vliga='{$l_u}' && id_gracza='{$id}';"));
                        if ($czy_ma_taka_gre == 1) {
                            $ileee = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where n1='{$id_zalogowanego_usera}' && n2='{$id}' && status!='3' && vliga='{$l_u}';"));
                            if ($ileee == '0') {
                                $ex = explode('-', $_POST['klub_1']);
                                $klub_1 = (int)$ex[0];
                                $wyzwanie = (int)$_POST['wyzwanie'];
                                if (!empty($wyzwanie)) {
                                    if (!empty($klub_1)) {
                                        if (mysql_query("INSERT INTO " . TABELA_WYZWANIA . " values('','{$id_zalogowanego_usera}','{$id}','','','p','','','','{$l_u}','{$klub_1}','');")) {
                                            add_to_poker_rank($chec, $mozliwe_checi, $id_zalogowanego_usera);
                                            note(M24, "info");
                                        } else {
                                            note(M422, "blad");
                                        }
                                    } else {
                                        note(M25, "blad");
                                    }
                                } else {
                                    $gral1 = $id_zalogowanego_usera;
                                    $gral2 = $id;
                                    obliczenie_punktow($id_zalogowanego_usera, $id);
                                    ?>
                                    <SCRIPT LANGUAGE="JavaScript">
                                        function pokaz(form) {
                                            dr1 = form.klub_1.value;
                                            a = eval(form.przykladowy_wynik_1.value);
                                            b = eval(form.przykladowy_wynik_2.value);
                                            var podzielona1 = dr1.split("-");
                                            var p1 = podzielona1[1];

                                            if (dr1) {
                                                var sciezka_1 = 'grafiki/loga/' + podzielona1[0] + '.png';
                                            } else {
                                                var sciezka_1 = 'img/brak_druzyny.png';
                                            }
                                            document.getElementById('logo_druzyny_1').src = sciezka_1;
                                            if (((a && b) || (a == 0 || b == 0)) && p1) {
                                                form.pkt_z_druzyne_1.value = podzielona1[1];
                                                form.pkt_z_druzyne_2.value = "?";
                                                if (a > b) {
                                                    form.pkt_za_mecz_1.value = "<?print$pkt_w_1;?>";
                                                    form.nowy_status_pkt_1.value = zaokraglenie(parseFloat(<?print$pkt_w_1 + $pamiec_punktow_1;?>+p1));
                                                    form.pkt_za_mecz_2.value = zaokraglenie(parseFloat(<?print$pkt_w_1 / -2;?>));
                                                    form.nowy_status_pkt_2.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_2 - ($pkt_w_1 / 2);?>));
                                                } else if (b > a) {
                                                    form.pkt_za_mecz_2.value = <?print$pkt_w_2;?>;
                                                    form.nowy_status_pkt_2.value = <?print$pkt_w_2 + $pamiec_punktow_2;?>;
                                                    form.pkt_za_mecz_1.value = <?print$pkt_w_2 / -2;?>;
                                                    form.nowy_status_pkt_1.value = <?print$pamiec_punktow_1 - ($pkt_w_2 / 2);?>+p1;
                                                } else if (a == b) {
                                                    form.pkt_za_mecz_1.value = <?print$pkt_r_1;?>;
                                                    form.nowy_status_pkt_1.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_1 + ($pkt_r_1);?>+p1));
                                                    form.pkt_za_mecz_2.value = <?print$pkt_r_2;?>;
                                                    form.nowy_status_pkt_2.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_2 + ($pkt_r_2);?> ));
                                                }
                                            }
                                        }
                                    </SCRIPT>
                                    <?
                                    print "
									<form method=\"post\" action=\"\">
									<input type=\"hidden\" name=\"wyzwanie\" value=\"1\"/>
									<table border=\"0\" width=\"100%\">
										<tr>
											<td valign=\"top\" width=\"250\">";
                                    center();
                                    start_table();
                                    naglowek_meczu(M56);
                                    nazwa_gracza($gral1);
                                    logo_druzyny(1, "img/brak_druzyny.png");
                                    przykladowy_wynik(1);
                                    aktualne_miejsce($pamiec_miejsca_1);
                                    pamiec_pkt($pamiec_punktow_1);
                                    pkt_za_mecz(1);
                                    pkt_z_druzyne(1);
                                    nowy_status_pkt(1);
                                    wybor_klubu('klub_1');
                                    end_table();
                                    ecenter();
                                    print "</td>
											<td valign=\"top\" width=\"250\">";
                                    center();
                                    start_table();
                                    naglowek_meczu(M55);
                                    nazwa_gracza($gral2);
                                    logo_druzyny(2, "img/brak_druzyny.png");
                                    przykladowy_wynik(2);
                                    aktualne_miejsce($pamiec_miejsca_2);
                                    pamiec_pkt($pamiec_punktow_2);
                                    pkt_za_mecz(2);
                                    pkt_z_druzyne(2);
                                    nowy_status_pkt(2);
                                    moja_druzyna(M340);
                                    end_table();
                                    ecenter();
                                    print "</td>
										</tr>
										<tr>
											<td colspan=\"2\">";
                                    check_player_poker_rank($id_zalogowanego_usera);
                                    echo "</td>
										</tr>
										<tr><td colspan=\"2\" class=\"text_center\">
										<input  type=\"image\" src=\"img/rzuc_wyzwanie.jpg\" title=\"" . M59 . "\" alt=\"" . M59 . "\"/>
										</td></tr>
									</table>
									</form>";
                                }
                            } else {
                                note(M28, "blad");
                            }
                        } else {
                            note(M264 . " (<b>{$opis_gry[$l_u][0]}</b>)", "blad");
                        }
                    } else {
                        note(M265, "blad");
                    }
                } else {
                    note(M266, "blad");
                }
            } else {
                note(M29, "blad");
            }
        } else {
            note(M30, "blad");
        }
    } else {
        note(M302, "blad");
    }
} // rzucanie wyzwania - END


####### akceptacja wyzwania z podaniem swojego klubu ##############################
$id = (int)$_GET['podopcja'];
if ($podmenu == 'akceptuj,spotkanie') {
    $czy_to_gracz_dla_niego = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where id='{$id}' && status='p' && n2='{$id_zalogowanego_usera}';"));
    if ($czy_to_gracz_dla_niego == '1') {
        $wyzwanie = (int)$_POST['wyzwanie'];
        szczegoly_meczu($id, TABELA_WYZWANIA);
        szczegoly_klubu($klub_1);
        obliczenie_punktow($gral1, $gral2, ranking);
        if (!empty($wyzwanie)) {
            $klub_2 = id_druzyny(czysta_zmienna_post($_POST['klub_2']));
            if ($klub_2) {
                $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  id='{$id}' && n2='{$id_zalogowanego_usera}' && status='p';"));
                if ($ile == '1') {
                    if (mysql_query("UPDATE " . TABELA_WYZWANIA . " SET status='1',klub_2='{$klub_2}' WHERE id='{$id}' && n2='{$id_zalogowanego_usera}' && status='p';")) {
                        add_to_poker_rank($chec, $mozliwe_checi, $id_zalogowanego_usera);
                        note(M54, "info");
                    } else {
                        note(M341, 'blad');
                    }
                } else {
                    $czy_w = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  id='{$id}' && n2='{$id_zalogowanego_usera}' && status='1';"));
                    if ($czy_w == '1') {
                        note(M41, "info");
                    } else {
                        note(M38, "blad");
                    }
                }
            } else {
                note(M12, "blad");
            }
        } else {
            ?>
            <script>
                function pokaz(form) {

                    // nazwy klubow pelne
                    dr1 = "<?echo sprawdz_nazwe_klubu($klub_1);?>";
                    dr2 = form.klub_2.value;

                    // wartosci bramek
                    a = eval(form.przykladowy_wynik_1.value);
                    b = eval(form.przykladowy_wynik_2.value);

                    // pkt za druzyny
                    var podzielona1 = dr1.split("-");
                    var podzielona2 = dr2.split("-");

                    // zadeklarowanie pkt jako FLOAT
                    var p1 = parseFloat(podzielona1[1]);
                    var p2 = parseFloat(podzielona2[1]);

                    // do wyswietlenia ikon klubow
                    if (dr1) {
                        var sciezka_2 = 'grafiki/loga/' + podzielona2[0] + '.png';
                    } else {
                        var sciezka_2 = 'img/brak_druzyny.png';
                    }
                    document.getElementById('logo_druzyny_2').src = sciezka_2;

                    // do roznicy miejsc
                    if (p1 > p2) {
                        var r_m = parseFloat(p1 - p2);
                    } else {
                        var r_m = parseFloat(p2 - p1);
                    }

                    // glowny przelicznik bonusu
                    var glowny_bonus = zaokraglenie(parseFloat(10 + Math.sqrt(r_m)));


                    // wartosc pkt :do formularza:
                    form.pkt_z_druzyne_1.value = p1;
                    form.pkt_z_druzyne_2.value = p2;

                    if (((a && b) || (a == 0 || b == 0)) && p2) {
                        if (a > b) {
                            if (p1 > p2) {
                                var bonusik1 = glowny_bonus;
                                var bonusik2 = glowny_bonus / 4;
                            } else if (p1 < p2) {
                                var bonusik1 = glowny_bonus / 2;
                                var bonusik2 = glowny_bonus / 4;
                            } else {
                                var bonusik1 = 5;
                                var bonusik2 = 5;
                            }
                            form.bonus_1.value = zaokraglenie(bonusik1);
                            form.bonus_2.value = zaokraglenie(bonusik2);
                            form.pkt_za_mecz_1.value = "<?print$pkt_w_1;?>";
                            form.nowy_status_pkt_1.value = zaokraglenie(parseFloat(<?print$pkt_w_1 + $pamiec_punktow_1;?>+p1) + bonusik1);
                            form.pkt_za_mecz_2.value = zaokraglenie(parseFloat(<?print$pkt_w_1 / -2;?>));
                            form.nowy_status_pkt_2.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_2 - ($pkt_w_1 / 2);?>) + bonusik2);


                        } else if (b > a) {
                            if (p1 > p2) {
                                var bonusik1 = glowny_bonus / 4;
                                var bonusik2 = glowny_bonus / 2;
                            } else if (p1 < p2) {
                                var bonusik1 = glowny_bonus / 4;
                                var bonusik2 = glowny_bonus;
                            } else {
                                var bonusik1 = 5;
                                var bonusik2 = 5;
                            }
                            form.bonus_1.value = zaokraglenie(bonusik1);
                            form.bonus_2.value = zaokraglenie(bonusik2);
                            form.pkt_za_mecz_2.value = <?print$pkt_w_2;?>;
                            form.nowy_status_pkt_2.value = <?print$pkt_w_2 + $pamiec_punktow_2;?>+bonusik2;
                            form.pkt_za_mecz_1.value = <?print$pkt_w_2 / -2;?>;
                            form.nowy_status_pkt_1.value = <?print$pamiec_punktow_1 - ($pkt_w_2 / 2);?>+p1 + bonusik1;


                        } else if (a == b) {
                            if (p1 > p2) {
                                var bonusik1 = (glowny_bonus - 3) / 4;
                                var bonusik2 = (glowny_bonus - 3) / 2;
                            } else if (p1 < p2) {
                                var bonusik1 = (glowny_bonus - 3) / 4;
                                var bonusik2 = (glowny_bonus - 3);
                            } else {
                                var bonusik1 = 5;
                                var bonusik2 = 5;
                            }
                            form.bonus_1.value = zaokraglenie(bonusik1);
                            form.bonus_2.value = zaokraglenie(bonusik2);
                            form.pkt_za_mecz_1.value = <?print$pkt_r_1;?>;
                            form.nowy_status_pkt_1.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_1 + ($pkt_r_1);?>+p1 + bonusik1));
                            form.pkt_za_mecz_2.value = <?print$pkt_r_2;?>;
                            form.nowy_status_pkt_2.value = zaokraglenie(parseFloat(<?print$pamiec_punktow_2 + ($pkt_r_2);?>+bonusik2));
                        }
                    }

                }
            </script>
            <?
            print "<form method=\"post\" action=\"\">
			<input type=\"hidden\" name=\"wyzwanie\" value=\"1\"/>
			<center>
			<table width=\"100%\">
			<tr>
				<td>";
            center();
            start_table();
            naglowek_meczu(M56);
            nazwa_gracza($gral1);
            logo_druzyny(1, $klub_1);
            przykladowy_wynik(1);
            aktualne_miejsce($pamiec_miejsca_1);
            pamiec_pkt($pamiec_punktow_1);
            pkt_za_mecz(1);
            pkt_z_druzyne(1);
            bonus(1);
            nowy_status_pkt(1);
            moja_druzyna($druzyna[2]);
            end_table();
            ecenter();
            print "</td><td>";
            center();
            start_table();
            naglowek_meczu(M55);
            nazwa_gracza($gral2);
            logo_druzyny(2, "img/brak_druzyny.png");
            przykladowy_wynik(2);
            aktualne_miejsce($pamiec_miejsca_2);
            pamiec_pkt($pamiec_punktow_2);
            pkt_za_mecz(2);
            pkt_z_druzyne(2);
            bonus(2);
            nowy_status_pkt(2);
            wybor_klubu('klub_2');
            end_table();
            ecenter();
            print "</td></tr>
				<tr>
				<td colspan=\"2\">";
            check_player_poker_rank($id_zalogowanego_usera);
            echo "</td>
				</tr>
				</table>
				<input type=\"image\" alt=\"" . M116 . "\" title=\"" . M116 . "\" src=\"img/akceptuj_spotkanie.jpg\"/>
				</form></center>";
        }
    } else {
        note(M43, "blad");
    }
}
?>