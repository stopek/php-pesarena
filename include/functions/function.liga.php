<?
function zlicz_mecze_zakonczone($id_gracza)
{
    $zakonczone = mysql_fetch_Array(mysql_query("SELECT count(id) FROM " . TABELA_LIGA . " WHERE (n1='{$id_gracza}' || n2='{$id_gracza}') && vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "' && status='3' GROUP BY vliga"));
    $aktywowane = mysql_fetch_Array(mysql_query("SELECT count(id) FROM " . TABELA_LIGA . " WHERE (n1='{$id_gracza}' || n2='{$id_gracza}') && vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "' && spotkanie='akt' GROUP BY vliga"));
    $wszystkie = mysql_fetch_Array(mysql_query("SELECT count(id) FROM " . TABELA_LIGA . " WHERE (n1='{$id_gracza}' || n2='{$id_gracza}') && vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "' GROUP BY vliga"));
    return array('ma_zak' => $zakonczone[0], 'ma_akt' => $aktywowane[0], 'ma_wsz' => $wszystkie[0]);
}


function mini_tabela_ligowa($liga)
{

    $a = wrzuc_graczy_ligowych($liga);
    if (count($a) != 0) {
        //aktualizcja tabeli ligowej
        if (empty($_SESSION['league_aktualizacja_tabeli'])) {
            unset($_SESSION['league_name']);
            unset($_SESSION['league_wygrane']);
            unset($_SESSION['league_przegrane']);
            unset($_SESSION['league_zremisowane']);
            unset($_SESSION['league_rozegrane']);
            unset($_SESSION['league_strzelone']);
            unset($_SESSION['league_stracone']);
            unset($_SESSION['league_stracone']);
            unset($_SESSION['league_stracone']);

            $sortuj = sortowanie_d_d_a();
            sortx($a, $sortuj);
            while (list($key, $value) = each($a)) {
                if ($zlicz <= 9 || $value['name'] == DEFINIOWANE_ID) {
                    $id = $value['name'];
                    $przegrane = przegrane_mecze_liga($id);
                    $wygrane = wygrane_mecze_liga($id);
                    $zremisowane = remisy_liga($id);
                    $rozegrane = rozegrane_mecze_liga($id);
                    $stracone = stracone_bramki_liga($id);

                    $_SESSION['league_name'][] = $value['name'];

                    $_SESSION['league_wygrane'][$id] = $wygrane;
                    $_SESSION['league_przegrane'][$id] = $przegrane;
                    $_SESSION['league_zremisowane'][$id] = $zremisowane;

                    $_SESSION['league_rozegrane'][$id] = $zremisowane + $przegrane + $wygrane;

                    $_SESSION['league_strzelone'][$id] = $value['num1'];
                    $_SESSION['league_stracone'][$id] = $stracone;
                    $_SESSION['league_pm'][$id] = $value['num2'];
                    $_SESSION['league_pkt'][$id] = $value['num3'];
                    $zlicz++;
                }
            }
            $_SESSION['league_aktualizacja_tabeli'] = true;
            $_SESSION['league_'] = $liga;
        }


        echo "
		<table border=\"0\" width=\"100%\"  frame=\"void\" >
		<tr class=\"naglowek\">
			<td></td>
			<td>Nick</td>
			<td>M</td>
			<td>W</td>
			<td>P</td>
			<td>+/-</td>
			<td>Pkt</td>
		</tr>";
        foreach ($_SESSION['league_name'] as $id) {
            echo "<tr" . kolor($p++ . '_mini') . " style=\"height:25px;\">
				<td><div class=\"ranking_g\" " . ($value['name'] == DEFINIOWANE_ID ? "style=\"color:red;\"" : NULL) . ">{$p}</div></td>
				<td>" . linkownik('profil', $id, '') . "</td>
				<td>{$_SESSION['league_rozegrane'][$id]}</td>
				<td>{$_SESSION['league_wygrane'][$id]}</td>
				<td>{$_SESSION['league_przegrane'][$id]}</td>
				<td>{$_SESSION['league_pm'][$id]}</td>
				<td>{$_SESSION['league_pkt'][$id]}</td>
			</tr>";


        }
        echo "
		<tr><td colspan=\"7\">To jest tabela dla ligi: <b>{$liga}</b></td></tr>
		</table><br/>";


    } else {
        note(M483, "blad");
    }
}


// wyswietla tabele elimminacyjna
function pokaz_tabele($liga)
{
    $a = wrzuc_graczy_ligowych($liga);
    if (count($a) != 0) {
        $sortuj = sortowanie_d_d_a();
        $str1 = naglowek_tabeli();
        sortx($a, $sortuj);
        while (list($key, $value) = each($a)) {
            $id = $value['name'];
            $przegrane = przegrane_mecze_liga($id);
            $wygrane = wygrane_mecze_liga($id);
            $zremisowane = remisy_liga($id);
            $rozegrane = rozegrane_mecze_liga($id);
            $stracone = stracone_bramki_liga($id);
            $str2 .= "<tr" . kolor($p++) . ">
			<td><div class=\"ranking_g\">{$p}</div></td>
			<td>" . linkownik('profil', $value['name'], '') . "</td>
			<td>" . linkownik('gg', $value['name'], '') . "</td>
			<td>" . mini_logo_druzyny(sprawdz_moj_klub_w_grze($value['name'], TABELA_LIGA_GRACZE, R_ID_L)) . "</td>
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
} // wyswietla tabele elimminacyjna - END


// wyswietla mecze ligowe
function pokaz_tabele_ligowa($id_gracza, $warunek)
{
    //wyznaczenie id dzialu na forum dla danej ligi / grupy
    $t = array(
        'pes4' => array('A' => '386', 'B' => '387', 'C' => '388', 'D' => '84', 'E' => '85', 1 => '666&t=3202', 2 => '667&t=3203', 3 => '668&t=3204', 4 => '669&t=3205'),
        'pes2010PS3' => array('1' => '124')
    );

    note(M439, "fieldset");
    $a2 = mysql_query("SELECT o.g_1_ocena, o.g_2_ocena,l.*  FROM " . TABELA_LIGA . " l 
	LEFT JOIN " . TABELA_OCENA_LISTA . " o ON l.id = o.id_meczu && o.gdzie='l'
	{$warunek} && (l.n1!='' && l.n2!='') && l.spotkanie='akt' && 
	l.vliga='" . DEFINIOWANA_GRA . "' && l.r_id = '" . R_ID_L . "' 
	;");
    naglowek_meczy_ligowych();
    while ($as2 = mysql_fetch_array($a2)) {
        $link = "http://pesarena.pl/forum/posting.php?mode=reply&f=" . $t[DEFINIOWANA_GRA][$as2['nr_ligi']] . "";
        print "<tr" . kolor($kol++) . " class=\"text_center\">
			<td>{$as2['id']}</td>
			<td>" . linkownik('profil', $as2['n1'], '') . "<u>(" . (isset($as2[0]) ? $as2[0] : "?") . ")</u></td>
			<td>" . linkownik('profil', $as2['n2'], '') . "<u>(" . (isset($as2[1]) ? $as2[1] : "?") . ")</u></td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " vs. " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td>" . ($as2['status'] != 1 ? $as2['w1'] . " : " . $as2['w2'] : "-:-") . "</td>
			<td><div class=\"ranking_g\">{$as2['kolejka']}</div></td>
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
			<td>" . ($as2['n2'] == $id_gracza && $as2['status'] == 2 ? linkownik('zakoncz', $as2['id'], 'liga') . " 
			" . linkownik('odrzuc_wynik', $as2['id'], 'liga') : "") . " " . ($as2['n1'] == $id_gracza && $as2['status'] == 1 ? linkownik('podaj_wynik', $as2['id'], 'liga') : "") . "</td>
			<td>" . ($as2['status'] == 3 ? pokaz_moje_pkt($as2['n1'], $as2['n2'], $as2['k_1'], $as2['k_2'], $id_gracza) : "-:-") . "</td>
			<td>" . ($as2['n1'] == $id_gracza ? linkownik('gg', $as2['n2'], '1') : linkownik('gg', $as2['n1'], '1')) . "</td>
			<td><a href=\"{$link}\"  onclick=\"potwierdzenie('{$link}','" . M440 . " <br/> " . M441 . "<br/> Temat: Spotkanie o ID: {$as2['id']}, kolejka: {$as2['kolejka']}, gracze: " . sprawdz_login_id($as2['n1']) . " vs. " . sprawdz_login_id($as2['n2']) . "'); return false;\">" . M442 . "</a></td>
			</tr>";
        $temp = TRUE;
    }
    end_table();
    if (empty($temp)) {
        note(M383 . " <b>" . DEFINIOWANA_GRA . "</b>", "blad");
    } else {
        wyswietl_legende(1);
    }
} // wyswietla mecze ligowe - END


// pokazuje terminarz ligowy
function pokaz_terminarz($liga)
{
    function max_kolejka($liga)
    {
        $temp = 0;
        $z = mysql_query("SELECT * FROM  " . TABELA_LIGA . " WHERE  nr_ligi='{$liga}' && n1!='' && n2!='' && vliga='" . DEFINIOWANA_GRA . "'  && r_id='" . R_ID_L . "';");
        while ($as2 = mysql_fetch_array($z)) {
            if ($as2[6] > $temp) {
                $temp = $as2[6];
            }
            $max = $temp;
        }
        return $max;
    }

    $max = max_kolejka($liga);
    $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . "  where spotkanie='akt' && nr_ligi='{$liga}' && n1!='' && n2!='' && vliga='" . DEFINIOWANA_GRA . "'  && r_id='" . R_ID_L . "';"));
    if ($ile != '0') {
        for ($kolejka = 1; $kolejka <= $max; $kolejka++) {
            $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where kolejka='{$kolejka}' && nr_ligi='{$liga}' && n1!='' && n2!='' && spotkanie='akt' && vliga='" . DEFINIOWANA_GRA . "'  && r_id='" . R_ID_L . "';"));
            $ile_juz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where kolejka='{$kolejka}' && nr_ligi='{$liga}' && n1!='' && n2!='' && spotkanie='akt' && status='3'	&& vliga='" . DEFINIOWANA_GRA . "'  && r_id='" . R_ID_L . "';"));
            if ($ile != '0') {
                echo "<fieldset><legend>" . M428 . ": <b>{$ile_juz}</b> " . M429 . ": <b>{$ile}</b> (" . (floor($ile_juz / $ile * 100)) . "%)</legend>";
                naglowek_terminarzu_ligowego($liga, $kolejka);
                $z = mysql_query("SELECT * FROM " . TABELA_LIGA . " where kolejka='{$kolejka}' && spotkanie='akt' && nr_ligi='{$liga}' && n1!='' && n2!='' && vliga='" . DEFINIOWANA_GRA . "'  && r_id='" . R_ID_L . "';");
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
								<td>" . mini_logo_druzyny($as2['klub_1']) . " {$w1}:{$w2} " . mini_logo_druzyny($as2['klub_2']) . "</td>
								<td>{$k_1} : {$k_2}</td>
								<td>(id: {$as2['id']})" . $rozegrano . "</td>
							</tr>";
                }
                end_table();
                echo "</fieldset>";
            }
        }
    } elseif (empty($ile) && !empty($liga)) {
        note(M122 . " " . $liga, "blad");
    }
} // pokazuje terminarz ligowy - END


// zwraca tablice z nazwami graczy,pkt ligowymi oraz bramkami
function wrzuc_graczy_ligowych($liga)
{
    $b = 0;
    $wyn = mysql_query("SELECT * FROM " . TABELA_LIGA_GRACZE . " WHERE status='{$liga}' && vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "';");
    while ($rek = mysql_fetch_array($wyn)) {
        $a[$b]['name'] = $rek['id_gracza'];
        $a[$b]['num3'] = pkt_liga($rek['id_gracza']);
        $a[$b]['num1'] = strzelone_bramki_liga($rek['id_gracza']);
        $a[$b]['num2'] = plusminus_bramki_liga($rek['id_gracza']);
        $a[$b]['clear_id'] = $rek['id_gracza'];
        $b++;
    }
    return $a;
} // zwraca tablice z nazwami graczy,pkt ligowymi oraz bramkami - END


function pkt_liga($id_gracza)
{
    $b = mysql_query("SELECT * FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&  ((n2='{$id_gracza}' && w2=w1) || (n1='{$id_gracza}' && w2=w1)) && status='3' && spotkanie='akt'");
    while ($g = mysql_fetch_array($b)) {
        $a = $a + 1;
    }

    $na = 0;
    //$na=mysql_num_rows(mysql_query("SELECT * FROM liga_wo where r_id='".R_ID_L."'  && (n1='{$id_gracza}' || n2='{$id_gracza}') "));
    $sql = "SELECT l.id FROM liga_wo w LEFT JOIN liga l ON w.match = l.id WHERE w.r_id = '" . R_ID_L . "' && l.k_1 = l.k_2 && (l.n2='{$id_gracza}' || l.n1='{$id_gracza}') ";

    $na = mysql_num_rows(mysql_query($sql));

    //echo ($na!=0 ? "<div style='font: 9px verdana; float: left; margin: auto'> ".sprawdz_login_id($id_gracza)."[-{$na}]pkt&nbsp;;&nbsp; </div>" : null);

    $c = mysql_query("SELECT * FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&  ((n1='{$id_gracza}' && w2<w1) || (n2='{$id_gracza}' && w2>w1)) && status='3' && spotkanie='akt'");
    while ($f = mysql_fetch_array($c)) {
        $a = $a + 3;
    }
    if (empty($a)) {
        return 0;
    } else {
        return $a - $na;
    }
}


function rozegrane_mecze_liga($id_gracza)
{
    $ilema = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&  status='3'  && (n1='{$id_gracza}' || n2='{$id_gracza}') && spotkanie='akt'"));
    return $ilema;
}


function wygrane_mecze_liga($id_gracza)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&   status='3' && ((n1='{$id_gracza}' && (w1>w2)) || (n2='{$id_gracza}' && (w1<w2))) && spotkanie='akt'"));
    return $licz;
}


function remisy_liga($id_gracza)
{

    $aa = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&   status='3' && (n1='{$id_gracza}' || n2='{$id_gracza}') && (w1=w2) && spotkanie='akt'"));
    return $aa;
}


function przegrane_mecze_liga($id_gracza)
{


    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " where vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&   status='3' && ((n1='{$id_gracza}' && (w1<w2)) || (n2='{$id_gracza}' && (w1>w2))) && spotkanie='akt'"));


    return $licz;
}


function strzelone_bramki_liga($id_gracza)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  && n2='{$id_gracza}' && status='3' && spotkanie='akt' GROUP BY n2"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&  n1='{$id_gracza}' && status='3' && spotkanie='akt' GROUP BY n1"));
    return $d[0] + $c[0];
}


function stracone_bramki_liga($id_gracza)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(w2) FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "' &&  n1='{$id_gracza}' && status='3' && spotkanie='akt' GROUP BY n1"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(w1) FROM " . TABELA_LIGA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && r_id='" . R_ID_L . "'  &&  n2='{$id_gracza}' && status='3' && spotkanie='akt' GROUP BY n2"));
    return $d[0] + $c[0];
}


function plusminus_bramki_liga($id_gracza)
{
    return strzelone_bramki_liga($id_gracza) - stracone_bramki_liga($id_gracza);
}




