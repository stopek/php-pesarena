<?


// wyswietla terminarz glowny pucharu dnia
function pokaz_terminarz_pucharu_dnia($gra, $faza)
{
    $stany = array('1_1' => M173, '1_2' => M174, '1_4' => M175, '1_8' => M176, '1_16' => M177);
    naglowek_faz_pucharu_dnia($stany[$faza]);
    $z = mysql_query("SELECT * FROM  " . TABELA_PUCHAR_DNIA . "  where  spotkanie='{$faza}' && vliga='{$gra}' && r_id='" . R_ID_P . "';");
    while ($as2 = mysql_fetch_array($z)) {
        $w1 = $as2['w1'];
        $w2 = $as2['w2'];
        $k_1 = $as2['k_1'];
        $k_2 = $as2['k_2'];
        $n_l_1 = $n_l_2 = $rozegrano = $jak1 = $jak2 = '';
        if ($as2['status'] != 3) {
            $w1 = "-";
            $w2 = "-";
            $k_1 = "-";
            $k_2 = "-";
            $rozegrano = "<i>" . M121 . "</i>";
        } else {
            $rozegrano = formatuj_date($as2['rozegrano']);
        }
        print "
		<tr" . kolor($a++ . '_' . $faza) . "  class=\"text_center\">
			<td>" . linkownik('profil', $as2['n1'], '') . "</td>
			<td>" . linkownik('profil', $as2['n2'], '') . "</td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " {$w1}:{$w2} " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td>{$k_1} : {$k_2}</td>
			<td>{$rozegrano}</td>
		</tr>";
    }
    end_table();
}  // wyswietla terminarz glowny pucharu dnia - END


// wyswietla terminarz eliminacji
function pokaz_terminarz_eliminacji($gra)
{
    naglowek_terminarzu_eliminacji();
    $z = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where vliga='{$gra}' && spotkanie='ele' && r_id='" . R_ID_P . "';");
    while ($as2 = mysql_fetch_array($z)) {
        $rozegrano = formatuj_date($as2['rozegrano']);
        $k_1 = $as2['k_1'];
        $w1 = $as2['w1'];
        $k_2 = $as2['k_2'];
        $w2 = $as2['w2'];
        $obrazek = "tak.png\" title=\"" . M111 . "\" alt=\"" . M111 . "\"";
        $obrazek = "tak.png\" title=\"" . M111 . "\" alt=\"" . M111 . "\"";
        if ($as2['status'] != '3') {
            $w1 = "-";
            $w2 = "-";
            $k_1 = "-";
            $k_2 = "-";
            $rozegrano = '<i>' . M487 . '</i>';
            $obrazek = "nie.png\" title=\"" . M121 . "\" alt=\"" . M121 . "\"";
        }
        print "<tr" . kolor($a++) . "  class=\"text_center\">
			<td>" . linkownik('profil', $as2['n1'], '') . "</a></td>
			<td>" . linkownik('profil', $as2['n2'], '') . "</td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " {$w1}:{$w2} " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td>{$k_1}:{$k_2}</td>
			<td>" . $rozegrano . "</td>";
        print "<td width=\"10\"><img src=\"img/{$obrazek} /></td>
		</tr>";
    }
    end_table();
}  // wyswietla terminarz eliminacji  - END


// sprawdza liczbe zozegranych spotkan
function rozegrane_mecze_eliminacja($id_gracza)
{
    $ilema = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where status='3'  && (n1='{$id_gracza}' || n2='{$id_gracza}') && spotkanie='ele' && r_id='" . R_ID_P . "' "));
    return $ilema;
} // sprawdza liczbe zozegranych spotkan - END


// sprawdza ilosc wygranych spotkan
function wygrane_mecze_eliminacja($id_gracza)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  status='3' && ((n1='{$id_gracza}' && (w1>w2)) || (n2='{$id_gracza}' && (w1<w2))) && spotkanie='ele' && r_id='" . R_ID_P . "' "));
    return $licz;
} // sprawdza ilosc wygranych spotkan - END


// sprawdza liczbe remisow
function remisy_eliminacja($id_gracza)
{
    $aa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  status='3' && (n1='{$id_gracza}' || n2='{$id_gracza}') && (w1=w2) && spotkanie='ele' && r_id='" . R_ID_P . "' "));
    return $aa;
} // sprawdza liczbe remisow - END


//sprawdza liczbe przegranych spotkan
function przegrane_mecze_eliminacja($id_gracza)
{
    $aa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  status='3' && n1='{$id_gracza}' && (w1<w2) && spotkanie='ele' && r_id='" . R_ID_P . "' "));
    $bb = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where  status='3' && n2='{$id_gracza}' && (w1>w2) && spotkanie='ele' && r_id='" . R_ID_P . "' "));
    return $aa + $bb;
} //sprawdza liczbe przegranych spotkan - END


//liczy ilosc strzelonych bramek
function strzelone_bramki_eliminacja($id_gracza)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_PUCHAR_DNIA . " WHERE n2='{$id_gracza}' && status='3' && spotkanie='ele' && r_id='" . R_ID_P . "' GROUP BY n2"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_PUCHAR_DNIA . " WHERE n1='{$id_gracza}' && status='3' && spotkanie='ele' && r_id='" . R_ID_P . "'  GROUP BY n1"));
    return $d[0] + $c[0];
} //liczy ilosc strzelonych bramek - END


// liczy ilosc straconych bramek
function stracone_bramki_eliminacja($id_gracza)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_PUCHAR_DNIA . " WHERE n1='{$id_gracza}' && status='3' && spotkanie='ele' && r_id='" . R_ID_P . "'  && vliga='" . DEFINIOWANA_GRA . "'  GROUP BY n1"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_PUCHAR_DNIA . " WHERE n2='{$id_gracza}' && status='3' && spotkanie='ele' && r_id='" . R_ID_P . "'  && vliga='" . DEFINIOWANA_GRA . "' GROUP BY n2"));
    return $d[0] + $c[0];
}


// +- dla bramek strzelonych i straconych
function plusminus_bramki_eliminacja($id_gracza)
{
    return strzelone_bramki_eliminacja($id_gracza) - stracone_bramki_eliminacja($id_gracza);
}


// sumuje pkt do tabeli eliminacji
function pkt($id_gracza)
{
    $b = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " WHERE ((n2='{$id_gracza}' && w2=w1) || (n1='{$id_gracza}' && w2=w1)) && status='3' && r_id='" . R_ID_P . "' && spotkanie='ele'");
    while ($g = mysql_fetch_array($b)) {
        $a = $a + 1;
    }
    $c = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " WHERE ((n1='{$id_gracza}' && w2<w1) || (n2='{$id_gracza}' && w2>w1)) && status='3' && r_id='" . R_ID_P . "'  && spotkanie='ele'");
    while ($f = mysql_fetch_array($c)) {
        $a = $a + 3;
    }
    if (empty($a)) {
        return 0;
    } else {
        return $a;
    }
}


function zwyciezcy_pucharu_dnia($gra)
{

    $sql = "SELECT count(pd.id),pl.miejsce1,pl.miejsce2,pl.miejsce3,pl.miejsce4,pl.id,pl.nazwa FROM " . TABELA_PUCHAR_DNIA_LISTA . " pl 
	LEFT JOIN " . TABELA_PUCHAR_DNIA . " pd
	ON pl.id=pd.r_id
	where pd.status='3' &&  pl.vliga='{$gra}' && pl.status='4' && pl.miejsce1!='' GROUP BY pl.id ORDER BY id DESC ";


    $podmenu = $_GET['podopcja'];
    $na_stronie_u = 10;
    if (!$podmenu) {
        $podmenu = 1;
    }
    $wszystkie_rekordy = mysql_num_rows(mysql_query($sql));
    $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie_u + 1);
    $start = ($podmenu - 1) * $na_stronie_u;

    $z = mysql_query($sql . " limit {$start},{$na_stronie_u}");
    while ($as2 = mysql_fetch_array($z)) {


        echo "
		<ul class=\"cupWinners\">
			<li class=\"cupName\">{$as2['nazwa']} | meczy: {$as2[0]}</li>
			<li class=\"cupWinner\">" . linkownik('profil', $as2['miejsce1'], '') . "</li>
			<li class=\"anotherA\">" . linkownik('profil', $as2['miejsce2'], '') . "</li>
			<li class=\"anotherB\">" . linkownik('profil', $as2['miejsce3'], '') . "<br/>
			" . linkownik('profil', $as2['miejsce4'], '') . "</li>
		</ul>
		";
        $temp = TRUE;
    }
    wyswietl_stronnicowanie($podmenu, $wszystkie_strony, 'puchar-zwyciezcy-', '.htm');


    if (empty($temp)) {
        note(M488, "blad");
    }
}

function wyswietl_szczegoly_pucharu($id)
{
    $_SESSION['R_ID_P'] = $id;
    pokaz_terminarz_eliminacji(DEFINIOWANA_GRA);
}

// wyswietla tabele elimminacyjna
function pokaz_tabele_eliminacji($gra, $dla_kogo, $r_id)
{
    $b = 0;
    $a2 = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where vliga='{$gra}' && status='1' && r_id='{$r_id}';");
    while ($rek = mysql_fetch_array($a2)) {
        $a[$b]['name'] = sprawdz_login_id($rek['id_gracza']);
        $a[$b]['num3'] = pkt($rek['id_gracza']);
        $a[$b]['num1'] = strzelone_bramki_eliminacja($rek['id_gracza']);
        $a[$b]['num2'] = plusminus_bramki_eliminacja($rek['id_gracza']);
        $b++;
    }
    $licz = mysql_num_rows($a2);
    if ($licz > '8' && $licz < '16') {
        $ilu = '8';
    }
    if ($licz > '16' && $licz < '32') {
        $ilu = '16';
    }
    if ($licz > '32') {
        $ilu = '32';
    }

    $sortuj = sortowanie_d_d_a();
    if ($licz != '0') {
        $p = 0;
        $str2 = $str3 = '';
        $str1 = naglowek_tabeli();
        sortx($a, $sortuj);
        if ($dla_kogo == 'dla_admina') {
            $admin_form = "<form method=\"post\" action=\"\">";
            $admin_button = "<input type=\"submit\"  name=\"zapisz\" value=\"zatwierdz\"  onclick=\"return confirm('" . M113 . " Jesli w tabeli istenieje jakas niejasnosc typu takie same statystyki - wstrzymaj sie z akceptacja bo pozniej nie bedzie odwrotu!');\"/></form>";
        }
        print $admin_form;
        while (list($key, $value) = each($a)) {
            $p++;
            $id = sprawdz_id_login($value['name']);
            $przegrane = przegrane_mecze_eliminacja($id);
            $wygrane = wygrane_mecze_eliminacja($id);
            $zremisowane = remisy_eliminacja($id);
            $rozegrane = rozegrane_mecze_eliminacja($id);
            $stracone = stracone_bramki_eliminacja($id);
            $id_dru = sprawdz_moj_klub_w_grze($id, TABELA_PUCHAR_DNIA_GRACZE, $r_id);
            if ($dla_kogo == 'dla_admina') {
                $zazn = '';
                if ($p <= $ilu) {
                    $zazn = " checked ";
                }
                $admin_select = "<input style=\"float:right;\" type=\"checkbox\" name=\"zrodlo[]\" {$zazn} value=\"{$id}\"/>";
                $dla_a = "<td>" . $admin_select . "</td>";
            }
            $str2 .= "<tr " . kolor($p . p) . ">
			<td><div class=\"ranking_g\">{$p}</div></td>
			<td>" . linkownik('profil', $id, '') . "</td>
			<td>" . linkownik('gg', $id, '') . "</td>
			<td>" . mini_logo_druzyny($id_dru) . " " . linkownik('profil_druzyny', $id_dru, '') . "</td>
			<td>{$rozegrane}</td>
			<td>{$wygrane}</td>
			<td>{$przegrane}</td>
			<td>{$zremisowane}</td>
			<td>{$value['num1']}</td>
			<td>{$stracone}</td>
			<td>{$value['num2']}</td>
			<td><font style=\"float:left\">{$value['num3']}</font></td>
			{$dla_a}
			</tr>";

        }
        $str4 = $admin_button;
        echo $str1 . $str2 . $str4 . "</table>";
    } else {
        note(M120, "info");
    }
} // wyswietla tabele elimminacyjna - END


function puchar_podaj_wynik($id, $id_zalogowanego_usera)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . "  WHERE id='{$id}' && n1='{$id_zalogowanego_usera}' && status='1' && r_id='" . R_ID_P . "';"));
    if ($licz == '1') {
        // szczegoly meczu , wyswietla dynamiczna zmiane pkt
        szczegoly_meczu($id, TABELA_PUCHAR_DNIA);
        $druzyna = mysql_fetch_array(mysql_query("SELECT w.klub_1, w.klub_2,
		(SELECT d1.nazwa FROM " . TABELA_DRUZYNY . " d1 WHERE d1.id=w.klub_1),(SELECT d2.nazwa FROM " . TABELA_DRUZYNY . " d2 WHERE d2.id=w.klub_2),
		(SELECT d3.punkty FROM " . TABELA_DRUZYNY . " d3 WHERE d3.id=w.klub_1),(SELECT d4.punkty FROM " . TABELA_DRUZYNY . " d4 WHERE d4.id=w.klub_2)
		FROM " . TABELA_PUCHAR_DNIA . " w WHERE w.id='{$id}' "));

        js_podaj_wynik($GLOBALS['gral1'], $GLOBALS['gral2'], $druzyna[4], $druzyna[5]);
        print "
		<form method=\"post\" action=\"\">
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
		<tr><td colspan=\"2\"  class=\"text_center\" ><input type=\"hidden\" name=\"id_spotkania\" value=\"{$id}\"/>
		<input type=\"hidden\" name=\"wynik_spotkania\" value=\"1\"/>
		<input type=\"image\"  src=\"img/podaj_ten_wynik.jpg\" title=\"" . M57 . "\" alt=\"" . M57 . "\"/>
		</form>
		</td></table>";
    } else {
        note(M43, "blad");
    }
}


// wyswietla tabele z meczami eliminacyjnymi
function pokaz_mecze_eliminacji($id_gracza, $type)
{
    $tab = array('all' => '', 'this' => "&& r_id='" . R_ID_P . "'");

    naglowek_meczy();
    $a2 = mysql_query("SELECT  o.g_1_ocena, o.g_2_ocena,p.* FROM " . TABELA_PUCHAR_DNIA . " p 
	LEFT JOIN ocena_lista o ON p.id = o.id_meczu && o.gdzie='p'
	where p.vliga='" . DEFINIOWANA_GRA . "' 
	&& (p.n1='{$id_gracza}' || p.n2='{$id_gracza}') && (p.n1!='' && p.n2!='') {$tab[$type]}	ORDER BY p.id DESC;");
    while ($as2 = mysql_fetch_array($a2)) {
        print "<tr" . kolor($kol++) . " class=\"text_center\">
			<td>" . $as2['id'] . "</td>
			<td>" . linkownik('profil', $as2['n1'], '') . "<u>(" . (isset($as2[0]) ? $as2[0] : "?") . ")</u></td>
			<td>" . linkownik('profil', $as2['n2'], '') . "<u>(" . (isset($as2[1]) ? $as2[1] : "?") . ")</u></td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " vs. " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td class=\"text_center\">" . ($as2['status'] < 2 ? "- : -" : $as2['w1'] . " - " . $as2['w2']) . "</td>
			<td>";
        if ($as2['status'] == 3) {
            print M111;
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            print M105;
        }
        if ($as2['n2'] == $id_gracza && $as2['status'] == 1) {
            print M106;
        }
        if ($as2['n1'] == $id_gracza && $as2['status'] == 2) {
            print M107;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            print M109;
        }
        print "</td>
			<td width=\"30\">" . str_replace('_', '/', $as2['spotkanie']) . "</td>";
        print "<td class=\"text_center\">";
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            echo linkownik('zakoncz', $as2['id'], 'puchar');
            echo linkownik('odrzuc_wynik', $as2['id'], 'puchar');
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            echo linkownik('podaj_wynik', $as2['id'], 'puchar');
        } else {
            print "-";
        }
        print "</td>
			<td class=\"text_center\">";
        if (!empty($as2['k_1']) && !empty($as2['k_2'])) {
            echo pokaz_moje_pkt($as2['n1'], $as2['n2'], $as2['k_1'], $as2['k_2'], $id_gracza);
        } else {
            print "-:-";
        }
        print "</td></tr>";
        $temp = TRUE;
    }
    end_table();
    if (empty($temp)) {
        note(M581, "blad");
    }

} // wyswietla tabele z meczami eliminacyjnymi - END


?>
