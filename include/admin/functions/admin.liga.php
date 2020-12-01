<?


function pokaz_tabele_admin($liga)
{
    //pokaz_tabele_admin('a');

    require_once('include/functions/function.liga.php');
    $a = wrzuc_graczy_ligowych($liga);
    if (count($a) != 0) {
        $sortuj = sortowanie_d_d_a();
        $str1 = "<table><tr class=\"naglowek\"><td>nick</td><td>gole+</td><td>+/-</td><td>pkt</td></tr>";
        sortx($a, $sortuj);
        while (list($key, $value) = each($a)) {
            $str2 .= "<tr" . kolor($p++ . $liga) . ">
			<td>" . linkownik('profil', $value['name'], '') . "</td>
			<td>{$value['num1']}</td>
			<td>{$value['num2']}</td>
			<td>{$value['num3']}</td>
			</tr>";
        }
        return $str1 . $str2 . $str4 . "</table>";
    } else {
        return "<b>" . M483 . "</b>";
    }
} // wyswietla tabele elimminacyjna - END


function liga_staty_gracza($liga, $r_id)
{
    $sql = mysql_query("SELECT * FROM " . TABELA_LIGA_GRACZE . " WHERE status='{$liga}' && r_id='{$r_id}'");
    echo "<fieldset><legend>Lista graczy z iloscia wo oraz  rozegranych meczy</legend>
	<table border=\"1\" width=\"100%\" frame=\"void\">
	<tr class=naglowek>
		<td>lp</td>
		<td>Nick</td>
		<td>WO</td>
		<td>Meczy</td>
	</tr>";
    while ($rek = mysql_fetch_array($sql)) {
        $licz_wo = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " WHERE r_id='{$r_id}' && nr_ligi='{$liga}' && vliga='" . WYBRANA_GRA . "'
		&& 
		((n1='{$rek['id_gracza']}' && w1='0' && w2='3' && k_1='-250' && k_2='200') || 
		(n2='{$rek['id_gracza']}' && w2='0' && w1='3' && k_2='-250' && k_1='200'  ) ||
		((n1='{$rek['id_gracza']}' || n2='{$rek['id_gracza']}') && w2='0' && w1='0' && k_2='-100' && k_1='-100'  )
		)"));
        $rozegral = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA . " WHERE (n1='{$rek['id_gracza']}' || n2='{$rek['id_gracza']}')  && vliga='" . WYBRANA_GRA . "' && status='3' "));
        echo "<tr " . kolor($b++) . "><td>{$b}</td><td>" . linkownik('profil', $rek['id_gracza'], '') . "</td><td>{$licz_wo}</td><td>{$rozegral}</td></tr>";
    }
    echo "</table>
	</fieldset>";
}


function formularz_baraze()
{
    echo "<form method=\"post\" action=\"\">
	<input type=\"text\" name=\"b_a\" size=\"40\"/>Grupa A<br/>
	<input type=\"text\" name=\"b_b\" size=\"40\"/>Grupa B<br/>
	<input type=\"text\" name=\"b_c\" size=\"40\"/>Grupa C<br/>
	<input type=\"text\" name=\"b_d\" size=\"40\"/>Grupa D<br/>
	<input type=\"text\" name=\"b_e\" size=\"40\"/>Grupa E<br/>
	<input type=\"submit\" name=ok value=ok>
	</form>";
    note("Przyklad: <b>3-4,7-8,11-12,15-16</b>", "blad");
}


//funkcja posrednia:: zapisuje mecze eliminacyjne z podzialem graczy na ligi od-do
function zapis_eliminacyjny($tablica, $od, $do, $liga, $r_id, $sezon, $rewanzTyp)
{
    print "<fieldset><legend>Generowanie ligi/grupy: {$liga}</legend>";
    $temp = 0;
    foreach ($tablica as $ci) {
        $temp++;
        if ($temp >= $od && $temp <= $do) {
            $an = mysql_query("UPDATE " . TABELA_LIGA_GRACZE . " SET status='{$liga}'	where vliga='" . WYBRANA_GRA . "' && id_gracza='{$ci}';");
        }
    }
    liga($liga, $od, $do, $tablica, $r_id, $sezon, $rewanzTyp);
    print "</fieldset>";
} //funkcja posrednia:: zapisuje mecze eliminacyjne z podzialem graczy na ligi od-do - END


function liga_dziel_graczy($grupy, $do_l, $metoda_przyjmowania, $start_od, $sezon, $nastepny_status, $pauzowane, $rewanzTyp)
{


    //wykonuyje dzialanie poza petla .. jesli opcja z radio jest 3 to zatrzymuje dzialanie funkcji.
    //nie potrzebuje tutaj uzycia petli
    if ($metoda_przyjmowania == 3) {
        echo "<fieldset><legend>" . admsg_lGeneratorWait . "</legend>";
        //tutaj sprawdzam jakie grupy zosatly utworzone od min. do max
        $rek = mysql_fetch_array(
            mysql_query("SELECT MAX(status) as max,MIN(status) as min 
				FROM " . TABELA_LIGA_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' GROUP BY r_id;")
        );

        $tablica_z_graczami = array();
        for ($group = $rek['min']; $group <= $rek['max']; $group++) {
            $r = mysql_fetch_array(
                mysql_query("SELECT count(id) FROM " . TABELA_LIGA_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && status='{$group}';")
            );
            $zapytanie = mysql_query("SELECT id_gracza FROM " . TABELA_LIGA_GRACZE . "  WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && status='{$group}'");

            while ($tablica = mysql_fetch_array($zapytanie)) {
                $tablica_z_graczami[$group][] = $tablica['id_gracza'];
            }

            //if (!empty($r[0])) { echo "Grupa: {$group} wystepuje: {$r[0]} razy<br>"; }
            zapis_eliminacyjny($tablica_z_graczami[$group], 0, count($tablica_z_graczami[$group]), $group, R_ID, $sezon, $rewanzTyp);
        }
        echo "</fieldset>";


        mysql_query("UPDATE " . TABELA_LIGA_LISTA . " SET status='{$nastepny_status}' WHERE id='" . R_ID . "'");
        if (!empty($pauzowane)) {
            if (mysql_query("DELETE FROM " . TABELA_LIGA . " WHERE (n1='0' || n2='0') && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "';")) {
                $p_tresc = admsg_lGeneratorPausedMatchDone;
            } else {
                $p_tresc = admsg_lGeneratorPausedMatchError;
            }
        }
        note(admsg_lGeneratorDone . ", {$p_tresc}", "info");
        return;
    } else {
        //w tym przypadku (tj wtedy gdy liga jest od eliminacji lub wlasciwej)
        $gra = round($do_l / $grupy, 0);
        echo "GRA: $gra";
        $an = mysql_query("SELECT * FROM " . TABELA_LIGA_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "';");
        $b = 0;
        while ($rek = mysql_fetch_array($an)) {
            switch ($metoda_przyjmowania) {
                case 1:
                    $a[$b]['num3'] = md5($rek['id'] * $rek['klub'] * ($rek['klub'] + $rek['id']) * 2 * sqrt($rek['id_gracza']) + date('d'));
                    break; // losowo
                case 2:
                    $a[$b]['num3'] = sprawdz_ranking_id($rek['id_gracza'], WYBRANA_GRA);
                    break; //wg rankingu
            }
            $a[$b]['name'] = $rek[1];
            $b++;

        }
    }


    $p = 0;
    $sortuj[$p]['name'] = "num3";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = TRUE;
    sortx($a, $sortuj);

    while (list($key, $value) = each($a)) {
        $zapis_eliminacyjny[$bb++] = $value['name'];
    }


    $g = array(
        '1' => array('', 'A', 'B', 'C', 'D', 'E'),
        '2' => array('', '1', '2', '3', '4', '5'),
        '1_nazwa' => 'Grupa',
        '2_nazwa' => 'Liga');
    echo "<div class=\"leagueStatus generatorInfo\">Status generowania: <span>Sposrod: <b>{$do_l}</b> graczy robie <b>{$grupy}</b> grupy po <b>{$gra}</b> graczy w grupie</span></div>";

    if ($gra >= 2) {
        for ($i = 1; $i <= $grupy; $i++) {
            $temp[$i] = $i - 1;
            $gray[$i] = $gra;
            $wartosc[$i] = 1;
            $temp[1] = 1;
            $gray[1] = 1;
            $wartosc[1] = 0;
            $suma = $temp[$i] * $gray[$i] + $wartosc[$i];
            echo $g[$start_od . '_nazwa'] . ": " . $g[$start_od][$i] . "# OD " . $suma . " DO " . $i * $gra . "<br/>";

            if ($suma <= $do_l) {
                zapis_eliminacyjny($zapis_eliminacyjny, $suma, $i * $gra, $g[$start_od][$i], R_ID, $sezon, $rewanzTyp);

            } else {
                note("Brak graczy do tej grupy.", "blad");
            }
        }
        mysql_query("UPDATE " . TABELA_LIGA_LISTA . " SET status='{$nastepny_status}' WHERE id='" . R_ID . "'");
    } else {
        note("W tej grupie jest za malo graczy({$gra})! Utworz podzial na mniej grup", "blad");
    }


    // jesli zaznaczona opcja pazwoane. to usuwa te mecze
    if (!empty($pauzowane)) {
        if (mysql_query("DELETE FROM " . TABELA_LIGA . " WHERE (n1='0' || n2='0') && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "';")) {
            $p_tresc = admsg_lGeneratorPausedMatchDone;
        } else {
            $p_tresc = admsg_lGeneratorPausedMatchError;
        }
    } // jesli zaznaczona opcja pazwoane. to usuwa te mecze - END

    note(admsg_lGeneratorDone . ", {$p_tresc}", "info");
}


function gracze_zapis_reczny()
{
    if (!empty($_POST['zapisz'])) {
        $id_gracza = (int)$_POST['id_gracza'];
        $status = (int)$_POST['grupa'];
        $klub = (int)$_POST['klub'];
        if (mysql_query("UPDATE " . TABELA_LIGA_GRACZE . " SET status='{$status}',klub='{$klub}' WHERE  vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && id_gracza='{$id_gracza}'")) {
            note("Status dla gracza <b>" . sprawdz_login_id($id_gracza) . "</b> zostal zapisany!", "info");
            $_SESSION['zmiany_pamietaj'][] = $id_gracza;

        } else {
            note("Blad przy zapisywaniu statusu gracza!", "blad");
        }

        //
        $_SESSION['start_formularza_ligi']['fieldset'][1] = "checked=\"checked\"";
        $_SESSION['start_formularza_ligi']['select'][1] = null;

        $_SESSION['start_formularza_ligi']['radio'][2] = "checked=\"checked\"";
    }


    $s = mysql_query("SELECT * FROM " . TABELA_LIGA_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'  order by status");
    echo "<table border=1 width=100% frame=void>
	<tr class=\"naglowek\">
		<td>Liga</td>
		<td width=\"20\">Info</td>
		<td width=\"20\">Druzyna</td>
		<td>Gracz</td>
		<td>Zapisz</td>
	</tr>";
    if (count($_SESSION['zmiany_pamietaj']) == 0) $_SESSION['zmiany_pamietaj'] = array();
    while ($rek = mysql_fetch_array($s)) {

        echo "<tr" . kolor($i++) . "><td width=\"100\"><form method=\"post\">
			<input type=\"hidden\" name=\"id_gracza\" value=\"{$rek['id_gracza']}\"/>
			
			<select name=\"grupa\">
				<option value=\"1\" " . ($rek['status'] == '1' ? "selected=\"selected\"" : null) . ">Extraklasa
				<option value=\"2\" " . ($rek['status'] == '2' ? "selected=\"selected\"" : null) . ">1 Liga
				<option value=\"3\" " . ($rek['status'] == '3' ? "selected=\"selected\"" : null) . ">2 Liga
				<option value=\"4\" " . ($rek['status'] == '4' ? "selected=\"selected\"" : null) . ">3 Liga
				<option value=\"5\" " . ($rek['status'] == '5' ? "selected=\"selected\"" : null) . ">4 Liga
			</select></td>
			<td><img src=\"" . (in_array($rek['id_gracza'], $_SESSION['zmiany_pamietaj']) ? "img/oki.png" : "img/nie.png") . "\" alt=\"\"/></td>
			<td>";
        $sql = mysql_query("SELECT id,nazwa,punkty FROM druzyny WHERE view='1' ORDER BY nazwa");
        echo "<select name=\"klub\">
						<option></option>";
        while ($rk = mysql_fetch_array($sql)) {
            echo "<option value=\"{$rk['id']}\" " . ($rk['id'] == $rek['klub'] ? "selected=\"selected\"" : null) . ">{$rk['nazwa']} - {$rk['punkty']}pkt.</option>";
        }
        echo "</select>";
        echo "</td>
			
			<td>" . linkownik('profil', $rek['id_gracza'], '') . "</td><td><input type=\"submit\" name=\"zapisz\" value=\"zapisz\"/></form></td></tr>";
    }
    echo "</table>";


}

function formularz_dziel_ligi()
{
    $_SESSION['start_formularza_ligi']['radio'][1] = (empty($_SESSION['start_formularza_ligi']['radio'][2]) ? "checked=\"checked\"" : null);
    $_SESSION['start_formularza_ligi']['fieldset'][1] = (!empty($_SESSION['start_formularza_ligi']['fieldset'][1]) ? null : "style=\"display:none\"");
    $_SESSION['start_formularza_ligi']['select'][1] = (!empty($_SESSION['start_formularza_ligi']['select'][1]) ? null : "checked=\"checked\"");

    echo "
	<form method=\"post\" action=\"\"> 
	<fieldset class=\"adminAccessList leagueManager\"><legend>Podziel graczy na grupy/ligi</legend>
		Z graczy utworz: 
		<select name=\"grupy\" {$_SESSION['start_formularza_ligi']['select'][1]} id=\"select_group\">
			<option value=\"1\">Bez podzialu
			<option value=\"2\">A-B(wlacznie) - 2 Grupy
			<option value=\"3\">A-C(wlacznie) - 3 Grupy
			<option value=\"4\">A-D(wlacznie) - 4 Grupy
			<option value=\"5\">A-E(wlacznie) - 5 Grup
		</select> 
		<input type=\"checkbox\" name=\"pauzowane\" value=\"1\" checked=\"checked\">Usun mecze pauzowane(jesli wystapia)
	</fieldset>
	<fieldset  class=\"adminAccessList leagueManager\"><legend>Start Ligi Od</legend>

		<input type=\"radio\" name=\"start_od\" checked=checked value=\"1\"/>Eliminacje
		<input type=\"radio\" name=\"start_od\" value=\"2\"/>Liga Wlasciwa<br>
	</fieldset>
	<fieldset class=\"adminAccessList leagueManager\"><legend>Zasada przyjmowania do lig</legend>
		<input  value=\"1\" type=\"radio\" name=\"metoda_przyjmowania\"  {$_SESSION['start_formularza_ligi']['radio'][1]} 
			onclick=\"getElementById('select_group').disabled = false; getElementById('hidden_field').style.display = 'none'\"/>Losowo
		<input  value=\"2\" type=\"radio\" name=\"metoda_przyjmowania\" 
			onclick=\"getElementById('select_group').disabled = false; getElementById('hidden_field').style.display = 'none'\"/>wg. rankingu
		<input value=\"3\"  type=\"radio\" name=\"metoda_przyjmowania\" {$_SESSION['start_formularza_ligi']['radio'][2]} 
			 
			onclick=\"getElementById('select_group').disabled = true; getElementById('hidden_field').style.display = 'block'\" />wlasnie ustawienie
	<fieldset class=\"adminAccessList\" id=\"hidden_field\" " . $_SESSION['start_formularza_ligi']['fieldset'][1] . ">";
    gracze_zapis_reczny();
    echo "</fieldset>
	</fieldset>
	<fieldset class=\"adminAccessList leagueManager\"><legend>Mecze rewanzowe</legend>
		<input type=\"radio\" name=\"sezon\" checked=\"checked\" value=\"1\"/>Generuj
		<input type=\"radio\" name=\"sezon\" value=\"2\"/>Nie generuj
	</fieldset>
	<fieldset class=\"adminAccessList leagueManager\"><legend>Metoda generowania rewanzy(o ile zaznaczone)</legend>
		<input type=\"radio\" name=\"rewanzTyp\" checked=\"checked\" value=\"1\"/>W jednej kolejce mecze (wi�cej meczy w jednej kolejce, mniej kolejek ogolem)<br/>
		<input type=\"radio\" name=\"rewanzTyp\" value=\"2\"/>Po wszystkich kolejkach tworzy nowe kolejki z rewanzami(mniej meczy w jednej kolejce, wiecej kolejek ogolem)
	</fieldset>
	
	<input type=\"submit\" name=\"wykonaj\" value=\"wykonaj\" onclick=\"return confirm('Czy napewno chcesz utworzyc te grupy i wygenerowac terminarz eliminacji?')\"/>
	</form>
	
	
	
		<div class=\"description\">
			<span>
			O czym musisz pamietac? <br/>
			1. Przy wyborze wlasnego ustawienia mozesz zaznaczac ligi od extraklasa do 4 ligi. Jednak pamietaj ze system generuje dla kazdej ligi mecze kazdy z kazdym
			dlatego musisz sam wybrac ilu graczy trafi do ktrej ligi<br/>
			2. Gdy juz zapiszesz wszystkich graczy do odpowiednich lig pozostaw opcje <b>wlasnie ustawienie </b> zaznaczone i kliknij w Wykonaj<br/>
			Jesli zaznaczysz odpowiednio graczy do lig. A przy konczeniu zaznaczysz inna opcje niz wlasne ustawienie statusy ustawione przez Ciebie nie beda mialy znaczenia
			</span>
		</div>";


}

function generuj_mecze_barazowe($tab)
{

    $a = $_POST['b_a'];
    $b = $_POST['b_b'];
    $c = $_POST['b_c'];
    $d = $_POST['b_d'];
    $e = $_POST['b_e'];

    $tab = array(
        "a" => $a,
        "b" => $b,
        "c" => $c,
        "d" => $d,
        "e" => $e
    );


    DEFINE(R_ID_L, R_ID);


    foreach (array_keys($tab) as $co) {
        $a = wrzuc_graczy_ligowych($co);
        $sortuj = sortowanie_d_d_a();
        sortx($a, $sortuj);
        $mie = 0;
        while (list($key, $value) = each($a)) {
            $mie++;
            $var_tab[$co][$mie] = $value['clear_id'];
        }

        echo "<fieldset><legend>Gracze z grupy: {$co}</legend>";
        $dziel_na_czesci[$co] = explode(',', $tab[$co]);
        $ile_czesci = count($dziel_na_czesci[$co]);
        $temp = 0;
        foreach ($dziel_na_czesci[$co] as $wynik) {
            $temp++;
            $dziel_na_graczy = explode('-', $wynik);
            foreach ($dziel_na_graczy as $efekt) {
                $var_tab_group[$temp][] = $var_tab[$co][$efekt];
                echo "Miejsce <b>{$efekt}:</b> " . linkownik('profil', $var_tab[$co][$efekt], '') . "<br>";
            }
            echo "<hr>";
        }

        $aaa[$co] = split(',', $tab[$co]);
        $dz = split('-', $aaa);
        foreach ($aaa[$co] as $qrwa) {
            $dz = explode('-', $qrwa);
            $roz_1 = $dz[0];
            $roz_2 = $dz[1];
            $kto_1 = $var_tab[$co][$roz_1];
            $kto_2 = $var_tab[$co][$roz_2];
            $kto_z_kim[] = "{$kto_1}-{$kto_2}";
        }
        echo "</fieldset>";
    }

    for ($ii = 1; $ii <= $ile_czesci; $ii++) {
        echo "<fieldset>";

        poka_graczy($var_tab_group[$ii], $kto_z_kim);

        echo "</fieldset>";
    }
    mysql_query("UPDATE " . TABELA_LIGA_LISTA . " SET status='B' WHERE id='{$r_id}'");
}


//funckja do generowana meczy
//$tab to tablica z graczami danej wirtualnej grupy
//$wyk to tablica z graczami (wszystkich grup-nie istotne czy z wszystkich) zapisana g1-g2
//w ktorej zawarci sa gracze nie mogacy ze soba grac 
function poka_graczy($tab, $wyk)
{

    $wid = 0;
    $w_parzyste = array();
    $w_nieparzyste = array();
    foreach ($tab as $gracze) {
        $wid++;
        if ($wid % 2 == 0) {

            array_push($w_parzyste, $gracze);
        } else {

            array_push($w_nieparzyste, $gracze);
        }
    }

    $ilosc = count($w_nieparzyste) - 1;
    echo "
		<table border=\"1\" width=\"100%\">
			<tr class=\"naglowek\">
				<tD width=\"150\"><b>Gospodarz</b></TD>
				<tD width=\"50\"><b>Gospodarz Klub</b></TD>
				<tD width=\"50\"><b>Gosc Klub</b></tD>
				<tD width=\"150\"><b>Gosc</b></tD>
			</tr>";
    $dodac = 2;

    foreach ($w_nieparzyste as $key => $pl) {
        $w_parzyste[$ilosc + 1] = $w_parzyste[1];
        $w_parzyste[$ilosc + 2] = $w_parzyste[0];
        //$opponent = l($ilosc,$pl,$w_parzyste,$w_nieparzyste);
        define(BARAZ, 'bar');
        zapis($w_nieparzyste[$key], $w_parzyste[$key + $dodac], '0', '-', R_ID);
        NOTE('Mecze zostaly zapisane', 'info');
        //
        $d1 = sprawdz_moj_klub_w_grze($w_nieparzyste[$key], TABELA_LIGA_GRACZE, R_ID);
        $d2 = sprawdz_moj_klub_w_grze($w_parzyste[$key + $dodac], TABELA_LIGA_GRACZE, R_ID);
        echo "
				<tr " . (in_array($pl . "-" . $w_nieparzyste[$key], $wyk) || in_array($w_parzyste[$key + $dodac] . "-" . $pl, $wyk) ? " style=color:red;" : "-") . " " . kolor($key) . ">
					<td>" . sprawdz_login_id($w_nieparzyste[$key]) . " </td>
					<td>" . mini_logo_druzyny($d1) . "</td>
					<td>" . mini_logo_druzyny($d2) . "</td>
					<td>" . sprawdz_login_id($w_parzyste[$key + $dodac]) . "</td>
				</tr>";


    }
    echo "</table>";
    unset($_SESSION['liga_rand_memory']);

}


function wlacz_kolejki($zrodlo, $liga, $r_id)
{
    if ($_POST['zapisz'] == 'zapisz') {
        if (count($zrodlo) > 0) {
            foreach ($zrodlo as $numer) {
                $wynik2 = mysql_query("UPDATE " . TABELA_LIGA . " SET  spotkanie='akt' WHERE kolejka='{$numer}' && nr_ligi='{$liga}' && r_id='{$r_id}';");
            }
        }
    }
}


#### skrypt generujacy mecze  ######################ca###########################
function liga($liga, $od, $do, $tablica, $r_id, $sezon, $rewanzTyp)
{
    require_once('include/admin/funkcje/function.table.php');
    $temp = 0;
    $a = 0;
    foreach ($tablica as $ci) {
        $temp++;
        if ($temp >= $od && $temp <= $do) {
            $t[$a++] = $ci;
        }
    }


    $num_players = count($t);
    $num_players = ($num_players > 0) ? (int)$num_players : 4;
    $num_players += $num_players % 2;


    for ($round = 1; $round < $num_players; $round++) {
        $players_done = array();
        $arr[$b][] = array('cols' => '4', 'value' => "<strong> <em>Kolejka {$round}</em></strong>");
        $arrNext[$bNext][] = array('cols' => '4', 'value' => "<strong>Mecze rewanzowe</strong>");
        $b++;
        $bNext++;
        for ($player = 1; $player < $num_players; $player++) {
            if (!in_array($player, $players_done)) {
                $opponent = $round - $player;
                $opponent += ($opponent < 0) ? $num_players : 1;
                if ($opponent != $player) {
                    if (($player + $opponent) % 2 == 0 xor $player < $opponent) {
                        $p1 = $t[$player - 1];
                        $p2 = $t[$opponent - 1];
                        zapis($p1, $p2, $round, $liga, $r_id);
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                        $b++;

                        if ($sezon == '1') {
                            if ($rewanzTyp == 1) {
                                $kolejkaAkt = $round;
                            } else {
                                $kolejkaAkt = $round + $num_players - 1;
                            }

                            zapis($p2, $p1, $kolejkaAkt, $liga, $r_id);
                            $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                            $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                            $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                            $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                            $bNext++;
                        }
                    } else {
                        $p2 = $t[$player - 1];
                        $p1 = $t[$opponent - 1];
                        zapis($p1, $p2, $round, $liga, $r_id);
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                        $b++;

                        if ($sezon == '1') {
                            if ($rewanzTyp == 1) {
                                $kolejkaAkt = $round;
                            } else {
                                $kolejkaAkt = $round + $num_players - 1;
                            }

                            zapis($p2, $p1, $kolejkaAkt, $liga, $r_id);
                            $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                            $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                            $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                            $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                            $bNext++;
                        }
                    }
                    $players_done[] = $player;
                    $players_done[] = $opponent;
                }
            }
        }

        if ($round % 2 == 0) {
            $opponent = ($round + $num_players) / 2;
            $p1 = $t[$player - 1];
            $p2 = $t[$opponent - 1];


            zapis($p1, $p2, $round, $liga, $r_id);
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
            $b++;

            if ($sezon == '1') {
                if ($rewanzTyp == 1) $kolejkaAkt = $round; else $kolejkaAkt = $round + $num_players - 1;

                zapis($p2, $p1, $kolejkaAkt, $liga, $r_id);
                $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                $bNext++;
            }
        } else {
            $opponent = ($round + 1) / 2;
            $p2 = $t[$player - 1];
            $p1 = $t[$opponent - 1];

            zapis($p1, $p2, $round, $liga, $r_id);
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
            $b++;

            if ($sezon == '1') {
                if ($rewanzTyp == 1) $kolejkaAkt = $round; else $kolejkaAkt = $round + $num_players - 1;

                zapis($p2, $p1, $kolejkaAkt, $liga, $r_id);
                $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id));
                $arrNext[$bNext][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id));
                $arrNext[$bNext][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                $bNext++;
            }

        }
    }


    $table = new createTable('adminFullTable center', 'Tabela z meczami glownymi');
    $table->setTableHead(array("Gospodarz", "", "", "Gosc"), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();


    $tableNext = new createTable('adminFullTable center', 'Tabela z meczami rewanzowymi');
    $tableNext->setTableHead(array("Gospodarz", "", "", "Gosc"), "adminHeadersClass");
    $tableNext->setTableBody($arrNext);
    echo $tableNext->getTable();


}


function zapis($p1, $p2, $runda, $liga, $r_id)
{
    if (defined('BARAZ')) {
        $typ_meczu = BARAZ;
    } else {
        $typ_meczu = 'off';
    }
    $d1 = sprawdz_moj_klub_w_grze($p1, TABELA_LIGA_GRACZE, $r_id);
    $d2 = sprawdz_moj_klub_w_grze($p2, TABELA_LIGA_GRACZE, $r_id);
    if (!mysql_query("insert into " . TABELA_LIGA . " values('','{$p1}','{$p2}','','','1','{$runda}','{$liga}','{$typ_meczu}','','','','" . WYBRANA_GRA . "','{$d1}','{$d2}','{$r_id}')")) {
        note("Wystapil blad przy zapisywaniu meczu ligowego", "blad");
    }
}


?>
