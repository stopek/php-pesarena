<?


function potwierdz_spotkanie_turniej($zatwierdz_spotkanie)
{
    $sprawdz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ . " where id='{$zatwierdz_spotkanie}' && status='2' && vliga='" . WYBRANA_GRA . "';"));
    if ($sprawdz == '1') {
        // szczegoly meczu i statusy pkt oraz miejsc
        szczegoly_meczu($zatwierdz_spotkanie, TABELA_TURNIEJ);

        // wyliczenie pkt za mecz
        $s1 = licz_punkty('', '', '', '', $GLOBALS['wynik_1'], $GLOBALS['wynik_2'], 'pkt_t_1');
        $s2 = licz_punkty('', '', '', '', $GLOBALS['wynik_1'], $GLOBALS['wynik_2'], 'pkt_t_2');

        //  aktualizacja tabeli gracze, staystyk
        aktualizacja_gracze($zatwierdz_spotkanie, $GLOBALS['gral1'], $GLOBALS['gral2'], $wynik_1, $wynik_2, $s1, $s2, WYBRANA_GRA, TABELA_TURNIEJ);
        note("Zatwierdziles to spotkanie!", "info");
    } else {
        note("Nie mozesz zatwierdzic tego spotkania", "blad");
        przeladuj($_SESSION['stara']);
    }
}


//wpisuje wynik wo w turnieju przyjmuje za wartosc
//wynik_1 i wynik_2 oraz id meczu
function wpisz_wynik_turniej($wynik_1_p, $wynik_2_p, $ide)
{
    if (isset($wynik_1_p) && isset($wynik_2_p)) {
        // szczegoly meczu i wyliczenie miejsc i pkt graczy
        szczegoly_meczu($ide, TABELA_TURNIEJ);

        // wyliczenie pkt za mecz
        $s1 = licz_punkty('', '', '', '', $wynik_1_p, $wynik_2_p, 'pkt_t_1');
        $s2 = licz_punkty('', '', '', '', $wynik_1_p, $wynik_2_p, 'pkt_t_2');

        //  aktualizacja tabeli gracze, staystyk
        aktualizacja_gracze($ide, $GLOBALS['gral1'], $GLOBALS['gral2'], $wynik_1_p, $wynik_2_p, $s1, $s2, WYBRANA_GRA, TABELA_TURNIEJ);
    } else {
        note("Musisz podac wynik gospodarza i goscia", "blad");
        przeladuj($_SESSION['stara']);
    }
}


/* puchar dnia */
function zatwierdz_spotkanie_puchar_dnia($zatwierdz_spotkanie)
{
    $sprawdz = mysql_fetch_array(mysql_query("SELECT count(id) as counter, n2 FROM " . TABELA_PUCHAR_DNIA . " where id='{$zatwierdz_spotkanie}' && status='2' && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' GROUP BY id;"));
    if ($sprawdz['counter'] == 1) {
        zakoncz_spotkanie($zatwierdz_spotkanie, $sprawdz['n2'], TABELA_PUCHAR_DNIA);
    } else {
        note(admsg_dontAcceptMatchError, "blad");
        przeladuj($_SESSION['stara']);
    }
}

function wpisz_wynik_puchar_dnia($wynik_1_p, $wynik_2_p, $ide)
{
    szczegoly_meczu($ide, TABELA_PUCHAR_DNIA);
    podaj_wynik($wynik_1_p, $wynik_2_p, $ide, $GLOBALS['gral1'], TABELA_PUCHAR_DNIA);
    zakoncz_spotkanie($ide, $GLOBALS['gral2'], TABELA_PUCHAR_DNIA);
}


/* liga */
function liga_zatwierdz_spotkanie($zatwierdz_spotkanie)
{
    $sprawdz = mysql_fetch_array(mysql_query("SELECT count(id) as counter, n2 FROM " . TABELA_LIGA . " where id='{$zatwierdz_spotkanie}' && status='2' && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' GROUP BY id;"));
    if ($sprawdz['counter'] == 1) {
        zakoncz_spotkanie($zatwierdz_spotkanie, $sprawdz['n2'], TABELA_LIGA);
    } else {
        note(admsg_dontAcceptMatchError, "blad");
        przeladuj($_SESSION['stara']);
    }
}

function liga_wpisz_wynik($wynik_1_p, $wynik_2_p, $ide)
{
    szczegoly_meczu($ide, TABELA_LIGA);
    podaj_wynik($wynik_1_p, $wynik_2_p, $ide, $GLOBALS['gral1'], TABELA_LIGA);
    zakoncz_spotkanie($ide, $GLOBALS['gral2'], TABELA_LIGA);
}


function wystaw_wo_grup($gdzie, $zaznaczone, $typ, $tablica)
{
    if (count($zaznaczone) > 0) {
        foreach ($zaznaczone as $identyfikator) {
            admin_wystaw_wo((int)$identyfikator, $gdzie, $tablica, $typ);
        }
    } else {
        note(admsg_musstCheckSomethink, "blad");
    }
}


//formularz edytujacy mecze $gdzie - nazwa tabeli sprawdza szczegoly_meczu
//$id - zmienna do edycji
function formularz_edycja_meczu($gdzie, $id)
{
    szczegoly_meczu($id, $gdzie);


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_editingResultIn . "</li>
		<li class=\"glowne_bloki_zawartosc\">
			<div class=\"text_center\">
				<form method=\"post\" action=\"\" class=\"small\">
				({$GLOBALS['punkty_ma_1']}pkt)
				" . mini_logo_druzyny($GLOBALS['klub_1']) . "
				" . linkownik('profil', $GLOBALS['gral1'], '') . "
					<input type=\"text\" size=\"5\" name=\"n_w_1\" value=\"{$GLOBALS['wynik_1']}\"/> 	
					<input type=\"submit\" name=\"zapisz\" value=\"" . admsg_save . "\"/> 
					<input type=\"text\" size=\"5\" name=\"n_w_2\" value=\"{$GLOBALS['wynik_2']}\"/> 
				" . linkownik('profil', $GLOBALS['gral2'], '') . "
				" . mini_logo_druzyny($GLOBALS['klub_2']) . "
				({$GLOBALS['punkty_ma_2']}pkt)
				</form>
			</div>
		</li>
	</ul>";


}

function showInfoAboutWo($arrayInfo)
{
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek legenda\"><span>" . admsg_legenda . "</span></li>
		<li class=\"glowne_bloki_zawartosc\">
			<ul class=\"legendaUL\">
				<li class=\"c2\"><span>" . admsg_pointsResultWoText . " : 3-0</span><em>" . (!empty($arrayInfo['for1']['pkt'][0]) ? $arrayInfo['for1']['pkt'][0] : admsg_nie_istnieje) . "</em></li>
				<li class=\"c1\"><span>" . admsg_pointsResultWoText . " : 0-3</span><em>" . (!empty($arrayInfo['for2']['pkt'][0]) ? $arrayInfo['for2']['pkt'][0] : admsg_nie_istnieje) . "</em></li>
				<li class=\"c2\"><span>" . admsg_pointsResultWoText . " : 0-0</span><em>" . (!empty($arrayInfo['for3']['pkt'][0]) ? $arrayInfo['for3']['pkt'][0] : admsg_nie_istnieje) . "</em></li>
			</ul>
		</li>
	</ul>";
}


// wystawia wo dla gracza o $ide w tabeli $gdzie z wykrozystaniem pkt wo: $tablica 
function admin_wystaw_wo($ide, $gdzie, $tablica, $zmienna)
{
    szczegoly_meczu($ide, $gdzie);

    $ile = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . $gdzie . " 
	WHERE id='{$ide}' && status='3' && vliga='" . WYBRANA_GRA . "' GROUP BY id;"));
    if (!empty($ile[0])) {
        note(admsg_thisMatchIsEnded, "blad");
        przeladuj($_SESSION['stara']);
    } else {
        //wyliczam punkty wo z tablicy
        $suma1 = round($tablica[$zmienna]['pkt'][0], 0);
        $suma2 = round($tablica[$zmienna]['pkt'][1], 0);
        if ($gdzie == TABELA_LIGA) {
            mysql_query("INSERT INTO liga_wo VALUES('','" . R_ID_L . "','{$GLOBALS['gral1']}','{$GLOBALS['gral2']}','{$ide}')");
        }
        aktualizacja_gracze($ide, $GLOBALS['gral1'], $GLOBALS['gral2'], $tablica[$zmienna]['gole'][0], $tablica[$zmienna]['gole'][1], $tablica[$zmienna]['pkt'][0], $tablica[$zmienna]['pkt'][1], WYBRANA_GRA, $gdzie);
        //wyliczam punkty wo z tablicy

        //dodaje gracza do rankingow dnia
        aktualizacja_ranking_gracz($GLOBALS['gral1'], $tablica[$zmienna]['pkt'][0], 1);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], $tablica[$zmienna]['pkt'][1], 1);

        //dodaje gracza do rankingow tygodnia
        aktualizacja_ranking_gracz($GLOBALS['gral1'], $tablica[$zmienna]['pkt'][0], 2);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], $tablica[$zmienna]['pkt'][1], 2);

        //dodaje gracza do rankingow miesiaca
        aktualizacja_ranking_gracz($GLOBALS['gral1'], $tablica[$zmienna]['pkt'][0], 3);
        aktualizacja_ranking_gracz($GLOBALS['gral2'], $tablica[$zmienna]['pkt'][1], 3);

        aktualizacja_poker_ranking($GLOBALS['gral1'], floor($s1));
        aktualizacja_poker_ranking($GLOBALS['gral2'], floor($s2));

    }
}

