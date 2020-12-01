<?


// wyswietla terminarz glowny pucharu dnia
function pokaz_terminarz_turnieju_puchar($gra, $faza)
{
    $stany = array('1_1' => M173, '1_2' => M174, '1_4' => M175, '1_8' => M176);
    naglowek_faz_pucharu_dnia($stany[$faza]);
    $z = mysql_query("SELECT * FROM  " . TABELA_TURNIEJ . "  where  spotkanie='{$faza}' && vliga='{$gra}' && r_id='" . R_ID_T . "';");
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
		<tr " . kolor($a++ . '_' . $faza) . "  class=\"text_center\">
			<td class=\"text_center\">" . linkownik('profil', $as2['n1'], '') . "</td>
			<td class=\"text_center\">" . linkownik('profil', $as2['n2'], '') . "</td>
			<td class=\"text_center\">" . mini_logo_druzyny($as2['klub_1']) . " {$w1}:{$w2} " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td class=\"text_center\">{$k_1} : {$k_2}</td>
			<td class=\"text_center\">{$rozegrano}</td>
		</tr>";
    }
    end_table();
}  // wyswietla terminarz glowny pucharu dnia - END


function zamien_typ_spotkania($a)
{
    $tab = array(
        'ele' => 'Spotkanie eliminacyjne',
        'tur' => 'Spotkanie Turniejowe',
        '1_8' => '1/8',
        '1_4' => '1/4',
        '1_2' => '1/2',
        '1_1' => 'Final',

    );
    return $tab[$a];
}

// wyswietla tabele z meczami eliminacyjnymi
function pokaz_mecze_turnieju($id_gracza)
{
    echo "<fieldset><legend>Twoje mecze turniejowe</legend>";
    naglowek_meczy();
    $a2 = mysql_query("SELECT  o.g_1_ocena, o.g_2_ocena,p.* FROM " . TABELA_TURNIEJ . " p 
	LEFT JOIN ocena_lista o ON p.id = o.id_meczu && o.gdzie='p'
	where p.vliga='" . DEFINIOWANA_GRA . "' 
	&& (p.n1='{$id_gracza}' || p.n2='{$id_gracza}') && (p.n1!='' && p.n2!='') && p.r_id='" . R_ID_T . "' ORDER BY p.id DESC;");
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
			<td width=\"30\">" . zamien_typ_spotkania($as2['spotkanie']) . "</td>";
        print "<td class=\"text_center\">";
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            echo linkownik('zakoncz', $as2['id'], 'turniej');
            echo linkownik('odrzuc_wynik', $as2['id'], 'turniej');
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            echo linkownik('podaj_wynik', $as2['id'], 'turniej');
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
    }
    end_table();
    echo "</fieldset>";
} // wyswietla tabele z meczami eliminacyjnymi - END


// pokazuje mecze eliminacyjne (po podzieleniu graczy na grupy) dla turnieju
function turniej_mecze_eliminacji($grupa, $typ)
{
    echo "<fieldset><legend>Terminarz turnieju dla grupy: <b>{$grupa}</b></legend>";
    naglowek_terminarzu_ligowego($liga, $kolejka);

    $z = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  spotkanie='{$typ}' && kolejka='{$grupa}' && r_id='" . R_ID_T . "'");

    while ($as2 = mysql_fetch_array($z)) {
        $w1 = $as2['w1'];
        $w2 = $as2['w2'];
        $k_1 = $as2['k_1'];
        $k_2 = $as2['k_2'];
        $rozegrano = formatuj_date($as2['rozegrano']);
        if ($as2['status'] != '3') {
            $w1 = "-";
            $w2 = "-";
            $k_1 = "-";
            $k_2 = "-";
            $rozegrano = '<i>' . M121 . '</i>';
        }
        print "<tr " . kolor($a++) . " class=\"text_center\">
			<td>" . linkownik('profil', $as2['n1'], '') . "</td>
			<td>" . linkownik('profil', $as2['n2'], '') . "</td>
			<td class=\"text_center\">" . mini_logo_druzyny($as2['klub_1']) . " {$w1}:{$w2} " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td class=\"text_center\">{$k_1} : {$k_2}</td>
			<td class=\"text_center\">" . $rozegrano . " :: id: {$as2['id']}</td>
		</tr>";
    }
    end_table();
    echo "</fieldset>";
}

function turniej_mecze_eliminacji_tabela($grupa, $typ)
{
    echo "<fieldset>
	<legend>Tabela turnieju dla grupy: <b>{$grupa}</b></legend>";
    $a = wrzuc_graczy_turniejowych($grupa, $typ);
    if (count($a) != 0) {
        $sortuj = sortowanie_d_d_a();
        $str1 = naglowek_tabeli();
        sortx($a, $sortuj);
        while (list($key, $value) = each($a)) {
            $id = $value['name'];
            $przegrane = przegrane_mecze_turniej($id, $typ);
            $wygrane = wygrane_mecze_turniej($id, $typ);
            $zremisowane = remisy_turniej($id, $typ);
            $rozegrane = rozegrane_mecze_turniej($id, $typ);
            $stracone = stracone_bramki_turniej($id, $typ);
            $str2 .= "<tr" . kolor($p++) . ">
			<td><div class=\"ranking_g\">{$p}</div></td>
			<td>" . linkownik('profil', $value['name'], '') . "</td>
			<td>" . linkownik('gg', $value['name'], '') . "</td>
			<td>" . mini_logo_druzyny(sprawdz_moj_klub_w_grze($value['name'], TABELA_TURNIEJ_GRACZE, R_ID_T)) . "
			" . linkownik('profil_druzyny', sprawdz_moj_klub_w_grze($value['name'], TABELA_TURNIEJ_GRACZE, R_ID_T), '') . "</td>
			<td>{$rozegrane}</td>
			<td>{$wygrane}</td>
			<td>{$przegrane}</td>
			<td>{$zremisowane}</td>
			<td>{$value['num1']}</td>
			<td>{$stracone}</td>
			<td>{$value['num2']}</td>
			<td>{$value['num3']}</td>
			</tr>";
        }
        echo $str1 . $str2 . $str4 . "</table>";
    } else {
        note(M483, "blad");
    }
    echo "</fieldset>";


}


function pkt_turniej($id_gracza, $typ)
{
    $b = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " WHERE ((n2='{$id_gracza}' && w2=w1) || (n1='{$id_gracza}' && w2=w1)) && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "'");
    while ($g = mysql_fetch_array($b)) {
        $a = $a + 1;
    }
    $c = mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " WHERE ((n1='{$id_gracza}' && w2<w1) || (n2='{$id_gracza}' && w2>w1)) && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "'");
    while ($f = mysql_fetch_array($c)) {
        $a = $a + 3;
    }
    if (empty($a)) {
        return 0;
    } else {
        return $a;
    }
}


function rozegrane_mecze_turniej($id_gracza, $typ)
{
    $ilema = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where status='3'  && (n1='{$id_gracza}' || n2='{$id_gracza}') && spotkanie='{$typ}' && r_id='" . R_ID_T . "'"));
    return $ilema;
}


function wygrane_mecze_turniej($id_gracza, $typ)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  status='3' && ((n1='{$id_gracza}' && (w1>w2)) || (n2='{$id_gracza}' && (w1<w2))) && spotkanie='{$typ}' && r_id='" . R_ID_T . "'"));
    return $licz;
}


function remisy_turniej($id_gracza, $typ)
{
    $aa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  status='3' && (n1='{$id_gracza}' || n2='{$id_gracza}') && (w1=w2) && spotkanie='{$typ}' && r_id='" . R_ID_T . "'"));
    return $aa;
}


function przegrane_mecze_turniej($id_gracza, $typ)
{
    $aa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  status='3' && n1='{$id_gracza}' && (w1<w2) && spotkanie='{$typ}' && r_id='" . R_ID_T . "'"));
    $bb = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where  status='3' && n2='{$id_gracza}' && (w1>w2) && spotkanie='{$typ}' && r_id='" . R_ID_T . "'"));
    return $aa + $bb;

}

function strzelone_bramki_turniej($id_gracza, $typ)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_TURNIEJ . " WHERE n2='{$id_gracza}' && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "' GROUP BY n2"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_TURNIEJ . " WHERE n1='{$id_gracza}' && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "' GROUP BY n1"));
    return $d[0] + $c[0];
}


function stracone_bramki_turniej($id_gracza, $typ)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_TURNIEJ . " WHERE n1='{$id_gracza}' && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "' GROUP BY n1"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_TURNIEJ . " WHERE n2='{$id_gracza}' && status='3' && spotkanie='{$typ}' && r_id='" . R_ID_T . "' GROUP BY n2"));
    return $d[0] + $c[0];
}

function plusminus_bramki_turniej($id_gracza, $typ)
{
    return strzelone_bramki_turniej($id_gracza, $typ) - stracone_bramki_turniej($id_gracza, $typ);
}


function wrzuc_graczy_turniejowych($grupa, $typ)
{

    if (R_ID_T_ADMIN == 1) {
        define(R_I, R_ID);
    } else {
        define(R_I, R_ID_T);
    }

    $b = 0;
    $wyn = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE status='{$grupa}' && vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_I . "';");
    while ($rek = mysql_fetch_array($wyn)) {
        $a[$b]['name'] = $rek['id_gracza'];
        $a[$b]['num3'] = pkt_turniej($rek['id_gracza'], $typ);
        $a[$b]['num1'] = strzelone_bramki_turniej($rek['id_gracza'], $typ);
        $a[$b]['num2'] = plusminus_bramki_turniej($rek['id_gracza'], $typ);
        $b++;
    }
    return $a;
}


?>
