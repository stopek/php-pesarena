<?


// jesli mija termin danego rankingu - wystawia punkty za rankingi
function wystaw_punkty_za_ranking($id_gracza, $pkt, $opis, $type, $id_rankingu)
{
    $ciag_znakow = date('d') . '-' . date('W') . '-' . date('m') . '-' . date('Y');
    if (!mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$pkt}' WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "'") ||
        !mysql_query("INSERT INTO " . TABELA_DODATKOWE_PUNKTY . " values('','{$id_gracza}','" . DEFINIOWANA_GRA . "','{$pkt}','{$opis}',NOW(),'{$type}')") ||
        !mysql_query("UPDATE " . TABELA_RANKING_LISTA . " SET aktualizacja='{$ciag_znakow}' WHERE vliga='" . DEFINIOWANA_GRA . "' && type='{$type}'") ||
        !mysql_query("DELETE FROM " . TABELA_RANKING . " WHERE id_rankingu='{$id_rankingu}' && vliga='" . DEFINIOWANA_GRA . "'")
    ) {
        note(M443, "blad");
    }
} // jesli mija termin danego rankingu - wystawia punkty za rankingi - END


// aktualizuje poker ranking dla danego gracza o ile jest zapisany
function aktualizacja_poker_ranking($gracz, $pkt)
{
    $czy_jest_w_poker_rankingu = mysql_fetch_array(mysql_query("SELECT r.id_gracza, count(r.id), r.id_rankingu FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
	WHERE r.id_gracza='{$gracz}' && r.vliga='" . DEFINIOWANA_GRA . "' && l.type='5' && l.id=r.id_rankingu GROUP BY r.id_gracza"));
    if ($czy_jest_w_poker_rankingu[1] != 0) {

        if (!mysql_query("UPDATE " . TABELA_RANKING . " SET pkt=pkt+'{$pkt}' WHERE id_gracza='{$gracz}' && id_rankingu='{$czy_jest_w_poker_rankingu['id_rankingu']}'")) {
            note(M444, "blad");
        }
    }
} // aktualizuje poker ranking dla danego gracza o ile jest zapisany - END


function aktualizacja_ranking_gracz($id_gracza, $pkt, $type)
{
    $rek = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_RANKING_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && type='{$type}'"));
    if (empty($rek['id'])) {
        $ciag_znakow = date('d') . '-' . date('W') . '-' . date('m') . '-' . date('Y');
        mysql_query("INSERT INTO " . TABELA_RANKING_LISTA . " values('','{$type}','{$ciag_znakow}','" . DEFINIOWANA_GRA . "')");
        $rek = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_RANKING_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && type='{$type}'"));
    }


    $count = mysql_fetch_array(mysql_query("SELECT r.id_gracza, count(r.id) FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
	WHERE r.id_gracza='{$id_gracza}' && l.type='{$type}' && r.vliga='" . DEFINIOWANA_GRA . "' && l.id=r.id_rankingu GROUP BY r.id_gracza"));
    if (empty($count[1])) {
        mysql_query("INSERT INTO " . TABELA_RANKING . " values('','{$id_gracza}','{$rek['id']}','{$pkt}','" . DEFINIOWANA_GRA . "')");
    } else {
        mysql_query("UPDATE " . TABELA_RANKING . " SET pkt=pkt+'{$pkt}' WHERE id_gracza='{$id_gracza}' && id_rankingu='{$rek['id']}'");
    }
}


//dodaje gracza do PokerRank'ingu  (chec-pole z formularza, mozliwe-jakie moga byc
//dodaje pkt na - do tabeli z pkttami dodatkowymi

function add_to_poker_rank($chec, $mozliwe_checi, $id_gracza)
{
    if (!empty($chec) && in_array($chec, $mozliwe_checi)) {
        aktualizacja_ranking_gracz($id_gracza, '0', 5);
        if (mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-" . POBOR_PKT_ZA_POKER_RANK . " WHERE 
		id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "';") &&
            mysql_query("insert into  " . TABELA_DODATKOWE_PUNKTY . " values('','{$id_gracza}','" . DEFINIOWANA_GRA . "','-" . POBOR_PKT_ZA_POKER_RANK . "','Pkt. pobrano za PokerRank',NOW(),'9')")
        ) {
            note(M452 . " <b>" . POBOR_PKT_ZA_POKER_RANK . "</b> pkt.", "info");
        }
    }
} //dodaje gracza do PokerRank'ingu  - END


//  sprawdza czy gracz nalezy do poker rankingu, wyswietla przycisk umozliwiajacy zapis do rankingu
function check_player_poker_rank($id_gracza)
{
    $czy_jest_w_poker_rankingu = mysql_fetch_array(mysql_query("SELECT r.id_gracza, count(r.id) FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
	WHERE r.id_gracza='{$id_gracza}' && l.type='5' && l.id=r.id_rankingu GROUP BY r.id_gracza"));
    if ($czy_jest_w_poker_rankingu[1] == 0) {
        echo "<div id=\"save_to_poker_rank\">
			<input type=\"checkbox\"  name=\"zapis_do\" value=\"5\"/>" . M454 . "
		</div>";
    } else {
        note(M455, "fieldset");
    }
} //  sprawdza czy gracz nalezy do poker rankingu, wyswietla przycisk umozliwiajacy zapis do rankingu - END


// sortuje graczy w tabeli rankinkow
function sortowanie_rankingowe($by, $type)
{
    $p = 0;
    $sortuj[$p]['name'] = "{$by}";
    $sortuj[$p]['sort'] = "{$type}";
    return $sortuj;
}// sortuje graczy w tabeli rankinkow - END


// jesli dany ranking nie istnieje - tworzy go
function utworz_ranking($type, $rek)
{
    $rek = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_RANKING_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && type='{$type}'"));
    if (empty($rek['id'])) {
        $ciag_znakow = date('d') . '-' . date('W') . '-' . date('m') . '-' . date('Y');
        mysql_query("INSERT INTO " . TABELA_RANKING_LISTA . " values('','{$type}','{$ciag_znakow}','" . DEFINIOWANA_GRA . "')");
        $rek = mysql_fetch_array(mysql_query("SELECT id FROM " . TABELA_RANKING_LISTA . " WHERE vliga='" . DEFINIOWANA_GRA . "' && type='{$type}'"));
    }
} // jesli dany ranking nie istnieje - tworzy go - END


// wyswietla formularz umozliwiajacy posortowanie graczy ( wg czego, w jaki sposob)
function sortowanie_formularz($opcje, $sort)
{
    echo "<fieldset>
		<form method=\"post\" action=\"\">" . M489 . "
			<select name=\"wybor\" onchange=\"submit()\">";
    foreach ($opcje as $key => $value) {
        echo "<option value=\"{$key}\" " . ($_POST['wybor'] == $key ? 'selected=\"selected\"' : '') . ">{$value}</option>";
    }
    echo "
			</select> " . M490 . ": 
			<select name=\"wybor_da\" onchange=\"submit()\">";
    foreach ($sort as $key => $value) {
        echo "<option value=\"{$key}\" " . ($_POST['wybor_da'] == $key ? 'selected=\"selected\"' : '') . ">{$value}</option>";
    }
    echo "
			</select>
			</form>		
		</form>	
	</fieldset>";
} // wyswietla formularz umozliwiajacy posortowanie graczy ( wg czego, w jaki sposob) - END


// wyswietla tabele z wszystkimi rankingami (gra, typ rankingu) (na stronie glownej)
function ranking_glowny($gra, $czego)
{
    $a2 = mysql_query("SELECT g.id_gracza,u.id,g.ranking,u.vliga,g.vliga,g.pozycja,g.b_p,g.b_m,g.m_w,g.status,g.seria_wskaznik,g.seria_ilosc, 
	(SELECT count(v.id) FROM vip v WHERE v.id_gracza = g.id_gracza) as vip_count FROM " . TABELA_GRACZE . " g Join " . TABELA_UZYTKOWNICY . " u 
	ON g.id_gracza=u.id && g.vliga='{$gra}' && g.status='1' && g.ranking!='0' order by g.{$czego} DESC;");
    $wszystkie_rekordy = mysql_num_rows($a2);
    if (empty($wszystkie_rekordy)) {
        note(" <b>" . sprawdz_opis_gry($gra) . "</b> " . M326, "blad");
    }
    $serie = array('+' => 'plus', '-' => 'minus');
    while ($rek = mysql_fetch_array($a2)) {
        $a++;
        if ($a <= LIMIT_RANKING_NA_STRONIE || $rek['id_gracza'] == DEFINIOWANE_ID) {
            echo "
			<ul " . kolor($a . '_' . $czego) . ">
				<li><div " . ($rek['id_gracza'] == DEFINIOWANE_ID ? " class=\"ranking_g_2\"  " : "class=\"ranking_g\" ") . ">{$a}</div></li>
				<li class=\"ranking_profil\">
					" . linkownik('profil', $rek['id_gracza'], '') . "
					" . (empty($rek['vip_count']) ? null : "  <span>VIP</span>") . "
				</li>
				<li  class=\"ranking_seria\">
					<img src=\"img/seria_{$serie[$rek['seria_wskaznik']]}.gif\"
					title=\"Seria {$serie[$rek['seria_wskaznik']]} {$rek['seria_ilosc']}\" 
					alt=\"\"/>" . ($rek['seria_ilosc'] == 0 ? null : $rek['seria_ilosc']) . "
				</li>
				<li class=\"ranking_punkty\">{$rek[$czego]}</li>
				<li>" . pozycja($rek['pozycja']) . "</li>
				<li>" . mini_logo($rek['id_gracza']) . "</li>
			</ul>";
        }
    }
} // wyswietla tabele z wszystkimi rankingami (gra, typ rankingu)  (na stronie glownej)- END


// zwraca miejsce danego gracza w grze
function miejsce_w_rankingu($gra, $kto)
{
    $a2 = mysql_query("SELECT * FROM " . TABELA_GRACZE . " where vliga='{$gra}' && status='1' order by ranking DESC;");
    while ($rek = mysql_fetch_array($a2)) {
        $a++;
        if ($rek['id_gracza'] == $kto) {
            return $a;
        }
    }
} // zwraca miejsce danego gracza w grze  - END

// zwraca miejsce danego gracza w grze 
function miejsce_w_rankingu_ptyp($gra, $miejsce, $typ, $sort)
{
    $a2 = mysql_query("SELECT * FROM " . TABELA_GRACZE . " where vliga='{$gra}' && status='1' order by {$typ} {$sort};");
    while ($rek = mysql_fetch_array($a2)) {
        $a++;
        if ($a == $miejsce) {
            return $rek['id_gracza'];
        }
    }
} // zwraca miejsce danego gracza w grze  - END


// zwwraca tablice z opcjami do sortowania
function ranking_mozliwe_podzialy()
{
    return $mozliwe = array(
        'l_g' => "",
        'r' => M492,
        'b_p' => M493,
        'b_m' => M494,
        'r_b' => M495,
        'w_m' => M496,
        'p_m' => M497,
        'z_m' => M498,
        'p_w_m' => M499,
        'p_p_m' => M500,
        'p_z_m' => M501,
        'r_s' => M502,
        'm_gp' => M503,
        'm_gc' => M504,
        'st' => M505,
        'seria' => 'Serja zwyciestw',
        'vip' => 'VIP'
    );
} // zwwraca tablice z opcjami do sortowania  - END


// wyswietla GLOWNA tabel rankingow, dodaje wlasciwosci do parametrow sortowania, sortuje i wyswietla
function ranking_wszyscy($gra)
{
    $a2 = mysql_query("SELECT g.*, (SELECT count(v.id) FROM vip v WHERE v.id_gracza = g.id_gracza) as vip_count FROM " . TABELA_GRACZE . " g where g.vliga='{$gra}'  && g.status='1' && g.ranking!='0' order by 'g.ranking'	DESC;");
    $serie = array('+' => 'plus', '-' => 'minus');
    while ($rek = mysql_fetch_array($a2)) {
        $suma_1 = mysql_num_rows(mysql_query("(SELECT id FROM " . TABELA_WYZWANIA . " WHERE n1='{$rek['id_gracza']}' && status='3')
		UNION (SELECT id FROM " . TABELA_PUCHAR_DNIA . " WHERE n1='{$rek['id_gracza']}' && status='3')
		UNION (SELECT id FROM " . TABELA_LIGA . " WHERE n1='{$rek['id_gracza']}' && status='3')"));

        $suma_2 = mysql_num_rows(mysql_query("(SELECT id FROM " . TABELA_WYZWANIA . " WHERE n2='{$rek['id_gracza']}' && status='3')
		UNION (SELECT id FROM " . TABELA_PUCHAR_DNIA . " WHERE n2='{$rek['id_gracza']}' && status='3')
		UNION (SELECT id FROM " . TABELA_LIGA . " WHERE n2='{$rek['id_gracza']}' && status='3')"));

        $suma_meczy = $rek['m_w'] + $rek['m_r'] + $rek['m_p'];
        $a[$b]['l_g'] = $rek['id_gracza'];
        $a[$b]['r'] = $rek['ranking'];
        $a[$b]['b_p'] = $rek['b_p'];
        $a[$b]['b_m'] = $rek['b_m'];
        $a[$b]['r_b'] = $rek['b_p'] - $rek['b_m'];
        $a[$b]['w_m'] = $rek['m_w'];
        $a[$b]['p_m'] = $rek['m_p'];
        $a[$b]['z_m'] = $rek['m_r'];
        $a[$b]['p_w_m'] = $suma_meczy != '0' ? round(($rek['m_w'] / $suma_meczy) * 100, 0) : '0';
        $a[$b]['p_p_m'] = $suma_meczy != '0' ? round(($rek['m_p'] / $suma_meczy) * 100, 0) : '0';
        $a[$b]['p_z_m'] = $suma_meczy != '0' ? round(($rek['m_r'] / $suma_meczy) * 100, 0) : '0';
        $a[$b]['r_s'] = $suma_meczy;
        $a[$b]['m_gp'] = $suma_1;
        $a[$b]['m_gc'] = $suma_2;
        $a[$b]['st'] = pozycja($rek['pozycja']);
        $a[$b]['seria'] = "<img src=\"img/seria_{$serie[$rek['seria_wskaznik']]}.gif\" title=\"Seria {$serie[$rek['seria_wskaznik']]} {$rek['seria_ilosc']}\" alt=\"\"/>" . ($rek['seria_ilosc'] == 0 ? null : $rek['seria_ilosc']) . "";
        $a[$b]['vip'] = (empty($rek['vip_count']) ? null : " <b style=\"color:#b51105;\">VIP</b>");

        $b++;
    }

    if (isset($_POST['wybor']) && isset($_POST['wybor_da'])) {
        sortx($a, sortowanie_rankingowe($_POST['wybor'], $_POST['wybor_da']));
    } else {
        sortx($a, sortowanie_rankingowe('r', 'DESC'));
    }


    sortowanie_formularz(ranking_mozliwe_podzialy(), array('DESC' => 'Malejaco', 'ASC' => 'Rosnaco'));
    naglowek_ranking();
    while (list($key, $value) = each($a)) {
        $aa++;
        echo "<tr" . kolor($aa . '_full_rank') . ">
			<td width=\"30\">{$aa}</td>";

        foreach (ranking_mozliwe_podzialy() as $klucz => $wartosc) {
            $wys = $value[$klucz];
            if ($klucz == 'l_g') {
                $wys = linkownik('profil', $value['l_g'], '') . "" . $value['vip'];
            }
            if ($klucz == 'gg') {
                $wys = linkownik('gg', sprawdz_id_login($value['l_g']), '');
            }
            if ($klucz == 'vip') {
                $wys = linkownik('graj', $value['l_g'], '');
            }
            echo "<td " . ($_POST['wybor'] == $klucz ? 'class="naglowek"' : null) . ">{$wys}</td>";
        }
        echo "
		</tr>";
    }
    end_table();
} // wyswietla GLOWNA tabel rankingow, dodaje wlasciwosci do parametrow sortowania, sortuje i wyswietla  - END


// wyswietla date w dopelniaczu po podaniu cyfry
function dopelniacz_data($liczba)
{
    $arr = array(
        '01' => M507,
        '02' => M508,
        '03' => M509,
        '04' => M510,
        '05' => M511,
        '06' => M512,
        '07' => M513,
        '08' => M514,
        '09' => M515,
        '10' => M516,
        '11' => M517,
        '12' => M518
    );
    return $arr[$liczba];
} // wyswietla date w dopelniaczu po podaniu cyfry - END


// po podaniu typu wyswietla czas trwania i pkt danego rankingu 
function sprawdz_typ_rankingu($type)
{
    switch ($type) {
        case 1 :
            note(M519 . "<br/>
			" . M520 . ": " . M521 . ": <b>" . MIEJSCE_1_DZIEN . "pkt</b>. " . M522 . ": <b>" . MIEJSCE_2_DZIEN . "pkt</b>. " . M523 . ": <b>" . MIEJSCE_3_DZIEN . "pkt</b>.", "fieldset");
            break;
        case 2 :
            note(M524 . "<br/>
			" . M520 . ": " . M521 . ": <b>" . MIEJSCE_1_TYDZIEN . "pkt</b>. " . M522 . ": <b>" . MIEJSCE_2_TYDZIEN . "pkt</b>. " . M523 . ": <b>" . MIEJSCE_3_TYDZIEN . "pkt</b>.", "fieldset");
            break;
        case 3 :
            note(M525 . "<br/>
			" . M520 . ": " . M521 . ": <b>" . MIEJSCE_1_MIESIAC . "pkt</b>. " . M522 . ": <b>" . MIEJSCE_2_MIESIAC . "pkt</b>. " . M523 . ": <b>" . MIEJSCE_3_MIESIAC . "pkt</b>.", "fieldset");
            break;
        case 5 :
            note(M526 . "<br/>
			" . M527 . " * " . PULA_PKT_ZA_POKER_RANK . "pkt</i>. " . M521 . ": " . POKER_RANK_PROCENT_1 . "%, " . M522 . ":  " . POKER_RANK_PROCENT_2 . "%, " . M523 . ":  " . POKER_RANK_PROCENT_3 . "%", "fieldset");
            break;
    }
} // po podaniu typu wyswietla czas trwania i pkt danego rankingu  - END

// aktualizuje graczy, wystawia pkt 
function ranking_aktualizacja($zmienna, $type, $gra, $gracze, $id_rankingu)
{
    $z_kiedy = date('j-n-Y', strtotime("-1 day"));
    switch ($type) {
        case '1':
            if ($zmienna != date('d')) {
                if (!empty($gracze[0])) {
                    wystaw_punkty_za_ranking($gracze[0], MIEJSCE_1_DZIEN, 'Miejsce 1 w rankingu dnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[1])) {
                    wystaw_punkty_za_ranking($gracze[1], MIEJSCE_2_DZIEN, 'Miejsce 2 w rankingu dnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[2])) {
                    wystaw_punkty_za_ranking($gracze[2], MIEJSCE_3_DZIEN, 'Miejsce 3 w rankingu dnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                note(M528, "info");
            }
            break;
        case '2':
            if ($zmienna != date('W')) {
                if (!empty($gracze[0])) {
                    wystaw_punkty_za_ranking($gracze[0], MIEJSCE_1_TYDZIEN, 'Miejsce 1 w rankingu tygodnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[1])) {
                    wystaw_punkty_za_ranking($gracze[1], MIEJSCE_2_TYDZIEN, 'Miejsce 2 w rankingu tygodnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[2])) {
                    wystaw_punkty_za_ranking($gracze[2], MIEJSCE_3_TYDZIEN, 'Miejsce 3 w rankingu tygodnia z: ' . $z_kiedy, $type, $id_rankingu);
                }
                note(M529, "info");
            }
            break;
        case '3':
            if ($zmienna != date('m')) {
                if (!empty($gracze[0])) {
                    wystaw_punkty_za_ranking($gracze[0], MIEJSCE_1_MIESIAC, 'Miejsce 1 w rankingu miesiaca z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[1])) {
                    wystaw_punkty_za_ranking($gracze[1], MIEJSCE_2_MIESIAC, 'Miejsce 2 w rankingu miesiaca z: ' . $z_kiedy, $type, $id_rankingu);
                }
                if (!empty($gracze[2])) {
                    wystaw_punkty_za_ranking($gracze[2], MIEJSCE_3_MIESIAC, 'Miejsce 3 w rankingu miesiaca z: ' . $z_kiedy, $type, $id_rankingu);
                }
                note(M530, "info");
            }
            break;

        case '5':
            if ($zmienna != date('m')) {
                $policz = mysql_fetch_array(mysql_query("SELECT count(r.id) FROM " . TABELA_RANKING . " r, " . TABELA_RANKING_LISTA . " l 
				WHERE r.id_rankingu=l.id && l.type='{$type}' && r.vliga='{$gra}' ORDER BY `pkt` DESC"));
                $punktow = $policz[0] * PULA_PKT_ZA_POKER_RANK;
                $p1 = $punktow * (POKER_RANK_PROCENT_1 / 100);
                $p2 = $punktow * (POKER_RANK_PROCENT_2 / 100);
                $p3 = $punktow * (POKER_RANK_PROCENT_3 / 100);
                wystaw_punkty_za_ranking($gracze[0], $p1, 'Miejsce 1 w pokerRankingu z: ' . $z_kiedy, $type, $id_rankingu);
                wystaw_punkty_za_ranking($gracze[1], $p2, 'Miejsce 2 w pokerRankingu z: ' . $z_kiedy, $type, $id_rankingu);
                wystaw_punkty_za_ranking($gracze[2], $p3, 'Miejsce 3 w pokerRankingu z: ' . $z_kiedy, $type, $id_rankingu);
                note(M531, "info");
            }
            break;
    }
} // aktualizuje graczy, wystawia pkt  - END

function split_data($data, $co)
{
    $data = explode('-', $data);
    return $data[$co];
    // 0-dzien, (1-tydzien=dodatkowo jesli tak jest w dacie), 2-miesiac, 3-rok
}

//wyswietla tabele glowna rankingow czasowych (dnia.tygodnia.miesiaca)
function ranking_czasowy($type, $gra)
{
    $gracze_wszyscy = array();
    $sql = mysql_query("SELECT r.id_gracza,r.pkt,l.aktualizacja, l.id FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
	WHERE r.id_rankingu=l.id && l.type='{$type}' && r.vliga='{$gra}' ORDER BY `pkt` DESC");
    naglowek_ranking_czasowy();
    while ($rek = mysql_fetch_array($sql)) {
        $a = id_druzyny(sprawdz_klub($rek['id_gracza']));
        echo "<tr" . kolor($i++) . ">
			<td><div class=\"ranking_g\">{$i}</div></td>
			<td>" . linkownik('profil', $rek['id_gracza'], '') . "</td>
			<td>{$rek['pkt']}</td>
			<td>" . mini_logo($rek['id_gracza']) . " " . linkownik('profil_druzyny', $a, '') . "</td>
			<td>" . linkownik('gg', $rek['id_gracza'], '') . "</td>
			<td>" . linkownik('graj', $rek['id_gracza'], '') . "</td>
		</tr>";
        $data = $rek['aktualizacja'];
        $id_rankingu = $rek['id'];
        array_push($gracze_wszyscy, $rek['id_gracza']);
    }
    end_table();
    $tablica = array('1' => '0', '2' => '1', '3' => '2', '4' => '3', '5' => '2', '6' => '5');
    if (mysql_num_rows($sql) == 0) {
        note(M532, "blad");
    } else {
        // przyjmuje za parametry (poszczegolna czesc z daty aktualizacji, typ aktualizacji,gra,wszystkich graczy posortowanych wg pkt, id_rankingu)
        ranking_aktualizacja(split_data($data, $tablica[$type]), $type, DEFINIOWANA_GRA, $gracze_wszyscy, $id_rankingu);
        sprawdz_typ_rankingu($type);
    }
} //wyswietla tabele glowna rankingow czasowych (dnia.tygodnia.miesiaca)


// zapisuje gracza do danego rankingu
function ranking_zapis($type, $id_gracza)
{
    $count = mysql_fetch_array(mysql_query("SELECT r.id_gracza, count(r.id), l.id FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
	WHERE r.id_gracza='{$id_gracza}' && l.type='5'  && r.vliga='" . DEFINIOWANA_GRA . "' && l.id=r.id_rankingu GROUP BY r.id_gracza"));
    if (empty($count[1])) {
        if (mysql_query("INSERT INTO " . TABELA_RANKING . " values('','{$id_gracza}','{$count[3]}','0','" . DEFINIOWANA_GRA . "')")) {
            note(M533, "blad");
        } else {
            note(M534, "blad");
        }
    } else {
        note(M535, "blad");
    }
} // zapisuje gracza do danego rankingu  - END


/*

// wystawienie pkt za rankingi . funkcja uruchamiana przy starcie strony
function ranking_czasowy_home_akt($type)
{
	$gracze_wszyscy = array();
	//wyswietla wszystkich graczy z tabeli ranking z  identyfikatorem takim samym jak identyfikator z tabeli rankingow z lista z warunkiem ze typ = $type
	//tworzy zmienne z data ostatnniej aktualizacji, 
	$sql=mysql_query("SELECT r.id_gracza,r.pkt,l.aktualizacja,l.type, l.id FROM ".TABELA_RANKING." r,
	".TABELA_RANKING_LISTA." l WHERE r.id_rankingu=l.id && l.type='{$type}' ORDER BY `pkt` DESC");
	while($rek = mysql_fetch_array($sql)) 
	{
		$data = $rek['aktualizacja'];
		$id_rankingu = $rek['id'];
		array_push($gracze_wszyscy,$rek['id_gracza']);
	}
	// podajac za klucz typ rankingu (1-5) patrzy na odpowiednia czesc w ostatniej aktualizacji 
	$tablica = array('1'=>'0','2'=>'1','3'=>'2','4'=>'3','5'=>'2','6'=>'5');
	if (mysql_num_rows($sql)!=0)	
	{
		// przyjmuje za parametry (poszczegolna czesc z daty aktualizacji, typ aktualizacji,gra,wszystkich graczy posortowanych wg pkt, id_rankingu)
		ranking_aktualizacja(split_data($data,$tablica[$type]),$type,DEFINIOWANA_GRA,$gracze_wszyscy,$id_rankingu);
	}	
} // wystawienie pkt za rankingi . funkcja uruchamiana przy starcie strony-  END


*/


//wyswietla rankingi pelny i mini
function wyswietl_ranking($gra)
{

    echo "<div id=\"zakladka_1_rd\" class=\"display_none\">";
    ranking_wszyscy($gra);
    echo "</div>
	<div id=\"zakladka_2_rd\" class=\"display_none\">";
    ranking_czasowy(1, $gra);
    echo "</div>
	<div id=\"zakladka_3_rd\" class=\"display_none\">";
    ranking_czasowy(2, $gra);
    echo "</div>
	<div id=\"zakladka_4_rd\" class=\"display_none\">";
    ranking_czasowy(3, $gra);
    echo "</div>
	<div id=\"zakladka_5_rd\" class=\"display_none\">";
    ranking_czasowy(4, $gra);
    echo "</div>
	<div id=\"zakladka_6_rd\" class=\"display_none\">";
    ranking_czasowy(5, $gra);
    echo "</div>";
} //wyswietla rankingi pelny i mini - END


?>
