<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
require_once('include/functions/function.liga.php');
require_once('include/functions/function.host.php');
require_once('include/functions/function.admin-druzyny.php');
require_once('include/functions/other/other.history.php');
if (!empty($sesja_liga)) {
    $league = array('', 'a', 'b', 'c', 'd', 'e', 'ex', '1', '2', '3', '4', '5');
    $status_ligi = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_LIGA_LISTA . " WHERE id='" . R_ID_L . "'"));
    $max_grupa = mysql_fetch_array(mysql_query("SELECT MAX(status) FROM " . TABELA_LIGA_GRACZE . " WHERE r_id='" . R_ID_L . "' GROUP BY `r_id`"));
    if ($status_ligi['status'] == '2') {
        $a = 6;
        $b = 11;
    } else {
        $a = 1;
        $b = 5;
    }
    if ($podmenu == 'wyniki') {

        echo "<div id=\"zakladka_1_lig\" class=\"display_none\">";
        pokaz_tabele_ligowa($id_zalogowanego_usera, "WHERE (l.n2='{$id_zalogowanego_usera}' || l.n1='{$id_zalogowanego_usera}') &&  l.status!='3'");
        echo "</div>
		<div id=\"zakladka_2_lig\" class=\"display_none\">";
        pokaz_tabele_ligowa($id_zalogowanego_usera, "WHERE (l.n2='{$id_zalogowanego_usera}' || l.n1='{$id_zalogowanego_usera}') && l.status='3'");
        echo "</div>
		<div id=\"zakladka_3_lig\" class=\"display_none\">";
        pokaz_tabele_ligowa($id_zalogowanego_usera, "WHERE l.n1='{$id_zalogowanego_usera}' && l.status='1'");
        echo "</div>";


    } elseif ($podmenu == 'tabela') {
        print "<div class=\"text_center\"><fieldset><legend>" . M426 . "</legend>";

        for ($temp = $a; $temp <= $b; $temp++) {

            print linkownik('tabela_ligowa', $league[$temp], $league[($status_ligi['status'] == '2' ? $temp + 1 : $temp)]);


            if (strtoupper($league[$temp]) == $max_grupa[0]) {
                break;
            }
        }


        print "</div>";
        if (!empty($podopcja) && in_array($podopcja, $league) && $status_ligi['status'] != '0') {
            pokaz_tabele($podopcja);
        }

    } elseif ($podmenu == 'terminarz') {
        print "<div class=\"text_center\"><fieldset><legend>" . M427 . "</legend>";
        for ($temp = $a; $temp <= $b; $temp++) {

            print linkownik('terminarz_ligowy', $league[$temp], $league[($status_ligi['status'] == '2' ? $temp + 1 : $temp)]);


            if ($temp % 5 == '0') {
                print "<br/>";
            }

            if (strtoupper($league[$temp]) == $max_grupa[0]) {
                break;
            }
        }

        print "</fieldset></div>";
        if (in_array($podopcja, $league)) {
            pokaz_terminarz($podopcja);
        }
    } elseif ($podmenu == 'historia') {
        historia_wszystkich_meczy(TABELA_LIGA, DEFINIOWANA_GRA);
    } elseif ($podmenu == 'uczestnicy') {
        pokaz_uczestnikow_gry(DEFINIOWANA_GRA, TABELA_LIGA_GRACZE, R_ID_L);
    } elseif ($podmenu == 'podaj,wynik') {
        $id = (int)$_GET['podopcja'];
        $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . "  WHERE id='{$id}' && n1='{$id_zalogowanego_usera}' && status='1' && spotkanie='akt';"));
        if ($podmenu = 'podaj,wynik' && !empty($id) && $licz != '0') {
            // szczegoly meczu , wyswietla dynamiczna zmiane pkt
            define(WCOGRA, 'liga');
            $druzyna = mysql_fetch_array(mysql_query("SELECT w.klub_1, w.klub_2,
				(SELECT d1.nazwa FROM druzyny d1 WHERE d1.id=w.klub_1),(SELECT d2.nazwa FROM druzyny d2 WHERE d2.id=w.klub_2)
				FROM " . TABELA_LIGA . " w WHERE w.id='{$id}' "));

            szczegoly_meczu($id, TABELA_LIGA);
            js_podaj_wynik_liga($GLOBALS['gral1'], $GLOBALS['gral2']);
            $wynik_spotkania = (int)$_POST['wynik_spotkania'];

            if (!empty($wynik_spotkania)) {
                podaj_wynik((int)$_POST['przykladowy_wynik_1'], (int)$_POST['przykladowy_wynik_2'], (int)$_POST['id_spotkania'], $id_zalogowanego_usera, TABELA_LIGA);
            } else {

                if (!empty($wynik_spotkania) && !in_array($glos_k_, $komentarze_opcje)) {
                    note(M423, "blad");
                }
                print "<form method=\"post\" action=\"\">
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
                //pkt_za_mecz(1);
                //nowy_status_pkt(1);
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
                //pkt_za_mecz(2);
                //nowy_status_pkt(2);
                moja_druzyna($druzyna[3]);
                end_table();
                ecenter();
                print "</td>
					</tr>
					<tr>
						<td colspan=\"2\" class=\"text_center\">
							<input type=\"hidden\" name=\"id_spotkania\" value=\"{$id}\"/>
							<input type=\"hidden\" name=\"wynik_spotkania\" value=\"1\"/>
							<input type=\"image\"  src=\"img/podaj_ten_wynik.jpg\"/>
						</td>
					</tr>
					</table>
				</form>";
            }
        }
    } elseif ($podmenu == 'zakoncz,spotkanie') {
        $id = (int)$_GET['podopcja'];
        $glos_k_ = $_POST['glos_k_'];
        $komentarze_opcje = array('0', '+', '-');

        if (isset($glos_k_) && in_array($glos_k_, $komentarze_opcje)) {
            zakoncz_spotkanie($id, $id_zalogowanego_usera, TABELA_LIGA);
        } else {
            reklamy_adkontekst();
            if (!empty($glos_k_) && !in_array($glos_k_, $komentarze_opcje)) {
                note(M423, "blad");
            }
            formularz_komentarz();
        }
    } elseif ($podmenu == 'odrzuc,wynik') {
        odrzuc_wynik((int)$_GET['podopcja'], $id_zalogowanego_usera, TABELA_LIGA);
    }
} else {
    note(M402, "blad");
}
?>