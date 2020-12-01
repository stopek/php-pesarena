<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
require_once('include/functions/function.turniej.php');
require_once('include/functions/function.host.php');
require_once('include/functions/function.admin-druzyny.php');
require_once('include/functions/other/other.history.php');
$st = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_LISTA . " where vliga='" . DEFINIOWANA_GRA . "' && id='" . R_ID_T . "';"));
$max = mysql_fetch_array(mysql_query("SELECT max(status) FROM " . TABELA_TURNIEJ_GRACZE . " where vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_T . "' GROUP BY 'id';"));


$ar = array('1' => 'ele', 'FG' => 'tur');
$typ = mysql_fetch_array(mysql_query("SELECT status FROM " . TABELA_TURNIEJ_LISTA . " WHERE id = '" . (int)$sesja_turniej . "'"));
$typ = $ar[$typ['status']];


if (!empty($sesja_turniej)) {
    if ($podmenu == 'wyniki') {
        pokaz_mecze_turnieju($id_zalogowanego_usera);
    } elseif ($podmenu == 'tabela') {
        if ($st['status'] == '1' || $st['status'] == 'FG') {
            if (!empty($podopcja)) {
                if ($podopcja == 'all') {
                    for ($a = 1; $a <= $max[0]; $a++) {
                        echo "<fieldset>";
                        turniej_mecze_eliminacji_tabela($a, $typ);
                        echo "</fieldset>";
                    }
                } else {
                    turniej_mecze_eliminacji_tabela($podopcja, $typ);
                }
            } else {

                echo "<fieldset style=\"width:450px; margin:auto; padding:auto;\"><legend>Wyswietl <b>tabele</b> dla grupy:</legend>";
                for ($a = 1; $a <= $max[0]; $a++) {
                    echo "<a href=\"turniej-tabela-{$a}.htm\"><div class=\"ranking_g\" style=\"float:left; margin-left:10px;\">{$a}</div></a>";
                    $tabela_byla = TRUE;
                }

                echo "<a href=\"turniej-tabela-all.htm\"><div class=\"ranking_g\" style=\"float:left; margin-left:10px;\">All</div></a>";
                if (empty($tabela_byla)) {
                    note('Brak tabel', 'blad');
                }
                echo "</fieldset>";

            }
        } else {
            note('Turniej jest na etapie, ktory nie wymaga tabeli', 'blad');
        }
    } elseif ($podmenu == 'terminarz') {
        //wyswietlenie buttonow do przesc do odpowiedniego terminarza
        //dla statusu tur. 1-eliminacje wlaczone
        if ($st['status'] == '1' || $st['status'] == 'FG') {
            if (!empty($podopcja)) {
                turniej_mecze_eliminacji($podopcja, $typ);
            } else {

                echo "<fieldset style=\"width:450px; margin:auto; padding:auto;\"><legend>Wyswietl <b>terminarz</b> dla grupy:</legend>";
                for ($a = 1; $a <= $max[0]; $a++) {
                    echo "<a href=\"turniej-terminarz-{$a}.htm\"><div class=\"ranking_g\" style=\"float:left; margin-left:10px;\">{$a}</div></a>";
                    $tabela_byla = TRUE;
                }
                if (empty($tabela_byla)) {
                    note('Brak terminarzy', 'blad');
                }
                echo "</fieldset>";

            }
        } else {
            note('Turniej jest na etapie, ktory nie wymaga terminarza', 'blad');
        }
    } elseif ($podmenu == 'historia') {
        historia_wszystkich_meczy(TABELA_TURNIEJ, DEFINIOWANA_GRA);
    } elseif ($podmenu == 'drabina') {
        pokaz_terminarz_turnieju_puchar($l_u, '1_1');
        pokaz_terminarz_turnieju_puchar($l_u, '1_2');
        pokaz_terminarz_turnieju_puchar($l_u, '1_4');
        pokaz_terminarz_turnieju_puchar($l_u, '1_8');
        pokaz_terminarz_turnieju_puchar($l_u, '1_16');
    } elseif ($podmenu == 'uczestnicy') {
        pokaz_uczestnikow_gry(DEFINIOWANA_GRA, TABELA_TURNIEJ_GRACZE, R_ID_T);
    } elseif ($podmenu == 'podaj,wynik') {

        $id = (int)$_GET['podopcja'];
        $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . "  WHERE id='{$id}' && n1='{$id_zalogowanego_usera}' && status='1';"));
        if ($podmenu = 'podaj,wynik' && !empty($id) && $licz != '0') {
            // szczegoly meczu , wyswietla dynamiczna zmiane pkt
            define(WCOGRA, 'turniej');
            $druzyna = mysql_fetch_array(mysql_query("SELECT w.klub_1, w.klub_2,
				(SELECT d1.nazwa FROM druzyny d1 WHERE d1.id=w.klub_1),(SELECT d2.nazwa FROM druzyny d2 WHERE d2.id=w.klub_2)
				FROM " . TABELA_TURNIEJ . " w WHERE w.id='{$id}' "));

            szczegoly_meczu($id, TABELA_TURNIEJ);
            js_podaj_wynik_turniej($GLOBALS['gral1'], $GLOBALS['gral2']);
            $wynik_spotkania = (int)$_POST['wynik_spotkania'];

            if (!empty($wynik_spotkania)) {
                podaj_wynik((int)$_POST['przykladowy_wynik_1'], (int)$_POST['przykladowy_wynik_2'], (int)$_POST['id_spotkania'], $id_zalogowanego_usera, TABELA_TURNIEJ);
            } else {
                print "<fieldset>
				<form method=\"post\" action=\"\">
				<center>
				<table width=\"100%\">
				<tr>
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
                nowy_status_pkt(2);
                moja_druzyna($druzyna[3]);
                end_table();
                ecenter();
                print "</td>
						
				</tr>
				</table>
				<input type=\"hidden\" name=\"id_spotkania\" value=\"{$id}\"/>
				<input type=\"hidden\" name=\"wynik_spotkania\" value=\"1\"/>
				<input type=\"image\" src=\"img/podaj_ten_wynik.jpg\"/>
				</form>
				</center>
				</fieldset>";
            }
        }
    } elseif ($podmenu == 'zakoncz,spotkanie') {
        //konczenie sptkania
        //wystawianie komentarza
        //wyswietlenie formularza
        $id = (int)$_GET['podopcja'];
        $glos_k_ = $_POST['glos_k_'];
        $komentarze_opcje = array('0', '+', '-');

        if (isset($glos_k_) && in_array($glos_k_, $komentarze_opcje)) {
            zakoncz_spotkanie($id, $id_zalogowanego_usera, TABELA_TURNIEJ);
        } else {
            if (!empty($glos_k_) && !in_array($glos_k_, $komentarze_opcje)) {
                note(M423, "blad");
            }


            reklamy_adkontekst();

            echo "<div id=\"wait\" style=\"text-align:center\">Prosze czekaj. Trwa ladowanie formularza konczacego spotkanie. Zapoznaj sie z naszymi partnerami wyzej <span style=\"display:none;\">";

            formularz_komentarz();
            echo "</span></div>";


        }
    } elseif ($podmenu == 'odrzuc,wynik') {
        odrzuc_wynik((int)$_GET['podopcja'], $id_zalogowanego_usera, TABELA_TURNIEJ);
    }
} else {
    note(M402, "blad");
}
?>
