<?

function historia_wszystkich_meczy($gdzie, $l_u)
{
    $na_stronie = 50;
    $podmenu = (int)$_GET['podopcja'];
    if (!$podmenu) {
        $podmenu = 1;
    }
    $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} where status='3'  && vliga='{$l_u}' " . ($gdzie == LIGA ? " && spotkanie='akt' " : NULL) . ";"));
    $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);

    $start = ($podmenu - 1) * $na_stronie;

    $jakich = array(
        TABELA_LIGA => 'ligowych',
        TABELA_PUCHAR_DNIA => 'pucharowych ',
        TABELA_WYZWANIA => 'towarzystkich',
        TABELA_TURNIEJ => 'turniejowcyh');


    $z = mysql_query("SELECT n1, n2 FROM {$gdzie} WHERE vliga='{$l_u}' && status='3'");
    while ($r = mysql_fetch_array($z)) {
        $g[$r['n1']]++;
        $g[$r['n2']]++;
    }

    function kto_max_byl($ilosc, $gracze)
    {
        foreach ($gracze as $key => $value) {
            if ($value == $ilosc) {
                return $key;
            }
        }
    }

    $max = max(array_values($g));
    $kto = sprawdz_login_id(kto_max_byl($max, $g));


    $miejsce1 = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'ranking', 'desc'));


    $bp = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'b_p', 'desc'));
    $bm = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'b_m', 'desc'));
    $mw = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'm_w', 'desc'));
    $mp = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'm_p', 'desc'));
    $mr = sprawdz_login_id(miejsce_w_rankingu_ptyp(DEFINIOWANA_GRA, 1, 'm_r', 'desc'));


    $ostatnio = strtotime('2009-08-17');
    $teraz = strtotime("NOW");
    $wynik = ($teraz - $ostatnio) / (60 * 60 * 24);

    echo "<fieldset>";
    kartka("Lacznie rozegrano meczy {$jakich[$gdzie]}", $wszystkie_rekordy);
    kartka("Najwiecej meczy: <b>{$max}</b> zagral", $kto);
    kartka("Najwiecej wygral ", $mw);
    kartka("Najwiecej przegral ", $mp);
    kartka("Najwiecej zremisowal ", $mr);
    kartka("Najwiecej strzelil bramek ", $bp);
    kartka("Najwiecej stracil bramek ", $bm);
    kartka("Srednio {$jakich[$gdzie]} na dzien ", round($wszystkie_rekordy / $wynik, 2));
    echo "</fieldset>";


    naglowek_histori_meczy();
    $z = mysql_query("SELECT * FROM {$gdzie} WHERE status='3' && vliga='{$l_u}' ORDER BY id DESC LIMIT {$start},{$na_stronie}");
    while ($as2 = mysql_fetch_array($z)) {

        print "<tr" . kolor($a++) . " class=\"text_center\">
			<td>" . linkownik('profil', $as2['n1'], '') . "</tD>
			<td>" . linkownik('profil', $as2['n2'], '') . "</tD>
			<td>{$as2['w1']} : {$as2['w2']}</tD>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " vs. " . mini_logo_druzyny($as2['klub_2']) . "</tD>
			<td>{$as2['k_1']} : {$as2['k_2']}</tD>
			<td>" . formatuj_date($as2['rozegrano']) . "</tD>
		</tr>";
    }
    end_table();
    if (empty($wszystkie_rekordy)) {
        note(M322, "blad");
    } else {
        wyswietl_stronnicowanie($podmenu, $wszystkie_strony, str_replace('_dnia', '', $gdzie) . '-historia-', '.htm');
    }
}


//wyswietla historie meczy  dla danego usera
function historia_meczy($id_gracza, $gdzie)
{
    require_once('include/admin/funkcje/function.table.php');

    $createID = str_replace(array(TABELA_PUCHAR_DNIA, TABELA_WYZWANIA, TABELA_LIGA, TABELA_TURNIEJ), array("all_cup", "all_match", "all_league", "all_tournament"), $gdzie);

    $z = mysql_query("SELECT * FROM {$gdzie} where (n1='{$id_gracza}' || n2='{$id_gracza}')  && status='3' && vliga='" . DEFINIOWANA_GRA . "' ORDER BY rozegrano DESC LIMIT 0,10;");
    while ($rek = mysql_fetch_array($z)) {
        $arr[$b][] = linkownik('profil', $rek['n1'], '');
        $arr[$b][] = mini_logo_druzyny($rek['klub_1']);
        $arr[$b][] = $rek['w1'];
        $arr[$b][] = ":";
        $arr[$b][] = $rek['w2'];
        $arr[$b][] = mini_logo_druzyny($rek['klub_2']);
        $arr[$b][] = linkownik('profil', $rek['n2'], '');

        $arr[$b][] = pokaz_moje_pkt($rek['n1'], $rek['n2'], $rek['k_1'], $rek['k_2'], $id_gracza);
        $arr[$b][] = formatuj_date($rek['rozegrano']);
        $b++;
    }

    $titleText = "Mecze w " . str_replace('_', ' ', $gdzie) . " dla gracza: <b>" . sprawdz_login_id($id_gracza) . "</b> Wyswietlono ostatnich 10 <a href=\"javascript:rozwin('{$createID}');\">pokaz wszystkie</a>";
    $table = new createTable('adminFullTable center', $titleText, "");
    $table->setTableHead(array('gospodarz', '', '', '', '', '', 'gosp', 'pkt', 'rozegrano'), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();


    $z2 = mysql_query("SELECT * FROM {$gdzie} where (n1='{$id_gracza}' || n2='{$id_gracza}') 	&& status='3' && vliga='" . DEFINIOWANA_GRA . "' ORDER BY rozegrano DESC LIMIT 11,1000000;");
    while ($rek = mysql_fetch_array($z2)) {
        $arr[$b][] = linkownik('profil', $rek['n1'], '');
        $arr[$b][] = mini_logo_druzyny($rek['klub_1']);
        $arr[$b][] = $rek['w1'];
        $arr[$b][] = ":";
        $arr[$b][] = $rek['w2'];
        $arr[$b][] = mini_logo_druzyny($rek['klub_2']);
        $arr[$b][] = linkownik('profil', $rek['n2'], '');

        $arr[$b][] = pokaz_moje_pkt($rek['n1'], $rek['n2'], $rek['k_1'], $rek['k_2'], $id_gracza);
        $arr[$b][] = formatuj_date($rek['rozegrano']);
        $b++;
    }

    $table2 = new createTable('adminFullTable center');
    $table2->setTableHead(array('gospodarz', '', '', '', '', '', 'gosp', 'pkt', 'rozegrano'), 'adminHeadersClass');
    $table2->setTableBody($arr);
    echo "<div id=\"{$createID}\">{$table2->getTable()}</div>";

} //wyswietla historie meczy  dla danego usera - END
?>
