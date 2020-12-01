<?

// zlicza ile dana druzyna zdobyla pkt i ile razy grala
function zlicz_druzynowo()
{
    $tablica = array(TABELA_LIGA, TABELA_WYZWANIA, TABELA_PUCHAR_DNIA, TABELA_TURNIEJ);
    foreach ($tablica as $gdzie) {
        $m = mysql_query("SELECT * FROM {$gdzie} where status='3';");
        while ($as1 = mysql_fetch_array($m)) {
            $klub[$as1['klub_1']]['zdobytych_pkt'] += $as1['k_1'];
            $klub[$as1['klub_1']]['ilosc'] += 1;
            $klub[$as1['klub_1']]['nazwa'] = $as1['klub_1'];
            $klub[$as1['klub_2']]['zdobytych_pkt'] += $as1['k_2'];
            $klub[$as1['klub_2']]['ilosc'] += 1;
            $klub[$as1['klub_2']]['nazwa'] = $as1['klub_2'];
        }
    }
    return $klub;
} // zlicza ile dana druzyna zdobyla pkt i ile razy grala - END


function druzyny_wyswietl_liste()
{
    $klub = zlicz_druzynowo();

    print "<div class=\"text_center\">
		<fieldset><br/>
			<form method=\"post\">
			" . M432 . ": " . select_kategorie("onchange=\"submit()\"", "k") . " 
			" . select_podkategorie("onchange=\"submit()\"", "p", (int)$_POST['k']) . "
			</form>
		</fieldset>
	</div>";
    $sql = mysql_query("SELECT d.id, d.nazwa, d.punkty, d.view ,p._id_kat, p._id_druzyny, 
	(SELECT count(ug.id) FROM " . TABELA_UZYTKOWNICY . " ug WHERE ug.klub=d.id)
	FROM " . TABELA_DRUZYNY . " d LEFT JOIN 
	" . TABELA_DRUZYNY_POLACZ . " p ON d.id=p._id_druzyny WHERE  p._id_kat='" . (int)$_POST['p'] . "' && d.view='1'  GROUP BY d.id ORDER BY nazwa ASC");
    naglowek_druzyny();
    while ($rek = mysql_fetch_array($sql)) {
        echo "<tr" . kolor($g++) . ">
			<td>{$g}</td>
			<td>" . linkownik('profil_druzyny', $rek[0], $rek[1]) . "</td>
			<td>" . if_zero($klub[$rek[0]]['ilosc']) . "</td>
			<td>{$rek[2]}</td>
			<td>" . if_zero($klub[$rek[0]]['zdobytych_pkt']) . "</td>
			<td>{$rek[6]}</td>
			<td>" . mini_logo_druzyny($rek[0]) . "</td>
			<td><a href=\"druzyny-rozegrane,mecze-{$rek[0]}.htm\"><img src=\"img/pokaz_wszystkie_mecze.png\" alt=\"pokaz mecze\" title=\"pokaz mecze\"/></a></td>
			<tr>";
        $temp = TRUE;
    }
    end_table();
    if (!empty($_POST['p']) && empty($temp)) {
        note(M437, "blad");
    } elseif (empty($temp)) {
        note(M436, "blad");
    }
}

function wyswietl_wszystkie_mecze_druzyny($podopcja)
{

    $sql = "(SELECT n1,n2,w1,w2,rozegrano,klub_1,klub_2,vliga,status,k_1,k_2 FROM wyzwania WHERE vliga='" . DEFINIOWANA_GRA . "'  && status='3' && (klub_1='{$podopcja}' || klub_2='{$podopcja}')) 
	UNION (SELECT n1,n2,w1,w2,rozegrano,klub_1,klub_2,vliga,status,k_1,k_2 FROM puchar_dnia WHERE vliga='" . DEFINIOWANA_GRA . "'  && status='3' && (klub_1='{$podopcja}' || klub_2='{$podopcja}'))
	UNION (SELECT n1,n2,w1,w2,rozegrano,klub_1,klub_2,vliga,status,k_1,k_2 FROM liga WHERE vliga='" . DEFINIOWANA_GRA . "'  && status='3' && (klub_1='{$podopcja}' || klub_2='{$podopcja}'))
	UNION (SELECT n1,n2,w1,w2,rozegrano,klub_1,klub_2,vliga,status,k_1,k_2 FROM turniej WHERE vliga='" . DEFINIOWANA_GRA . "'  && status='3' && (klub_1='{$podopcja}' || klub_2='{$podopcja}'))
	ORDER BY rozegrano DESC ;";

    echo "<fieldset><legend>" . M430 . " <b>" . mini_logo_druzyny($podopcja) . " 
	" . linkownik('profil_druzyny', $podopcja, '') . "</b> " . M431 . "</legend>";
    naglowek_histori_meczy();
    $z = mysql_query($sql);
    while ($as2 = mysql_fetch_array($z)) {
        print "<tr" . kolor($a++) . " class=\"text_center\">
			<td>" . linkownik('profil', $as2['n1'], '') . "</td>
			<td>" . linkownik('profil', $as2['n2'], '') . "</td>
			<td>{$as2['w1']} : {$as2['w2']}</td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " vs. " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td>{$as2['k_1']} : {$as2['k_2']}</td>
			<td>" . formatuj_date($as2['rozegrano']) . "</td>
		</tr>";
    }

    end_table();
    echo "</fieldset>";
}


function druzyny_wyswietl_profil($podopcja)
{
    $klub = zlicz_druzynowo();
    $sql = mysql_query("SELECT * FROM " . TABELA_DRUZYNY . " WHERE id='{$podopcja}'");
    $rekord = mysql_fetch_array($sql);

    print "<div class=\"rejestracja_naglowki\">" . M433 . "</div>
	<div id=\"profil_tlo\">
			<div class=\"regal_prof\">
				<div class=\"tlo_profil_gra\">
					<div class=\"text_center\">
						<img src=\"grafiki/loga/{$podopcja}.png\" alt=\"\" title=\"\"/><br/>
						{$rekord['nazwa']}
					</div>
				</div>
			
				<div class=\"szczegoly_profilu\">" . M244 . "<b>{$rekord['punkty']}</b></div>
				<div class=\"szczegoly_profilu_b_t\">" . M245 . "<b>" . if_zero($klub[$podopcja]['ilosc']) . "</b></div>
				<div class=\"szczegoly_profilu\">" . M246 . "<b>" . if_zero($klub[$podopcja]['zdobytych_pkt']) . "</b></div>
				<div class=\"szczegoly_profilu_b_t\">" . M435;

    $sortuj[$p]['name'] = "zdobytych_pkt";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = TRUE;
    sortx($klub, $sortuj);

    while (list($key, $value) = @each($klub)) {
        $aa++;
        if ($value['nazwa'] == $podopcja) {
            $ile = $aa;
            break;
        } else {
            $ile = 0;
        }
        $aa++;
    }

    print ": <b>{$ile}</b>
				</div>
				<div class=\"szczegoly_profilu_b_t\"><i><a href=\"druzyny-rozegrane,mecze-{$podopcja}.htm\">" . M434 . "</a></i></div>
			</div>
	</div>";
}
