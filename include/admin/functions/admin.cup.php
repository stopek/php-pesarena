<?

function saveCupMatch($p1, $p2, $spotkanie, $r_id)
{
    $d1 = sprawdz_moj_klub_w_grze($p1, TABELA_PUCHAR_DNIA_GRACZE, $r_id);
    $d2 = sprawdz_moj_klub_w_grze($p2, TABELA_PUCHAR_DNIA_GRACZE, $r_id);

    if (!mysql_query("insert into " . TABELA_PUCHAR_DNIA . " values('','{$p1}','{$p2}','','','1','{$spotkanie}','','','" . WYBRANA_GRA . "','','{$d1}','{$d2}','{$r_id}')")) {
        note("Mecz miedzy " . sprawdz_login_id($p1) . " a " . sprawdz_login_id($p2) . " nie zostal dodany!", "note");
    }

}


function generateCupMatches($r_id, $fazaGenerowania, $no, $nextStatus)
{
    $a = mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " where  vliga='" . WYBRANA_GRA . "' && status='1' && r_id='{$r_id}';");
    while ($as = mysql_fetch_array($a)) {
        $t[] = $as[1];
    }

    //zliczenie do wyswietlenia bledu bez zaleznosci o eliminacje do PD
    //ta zaleznosc ograniczam przy generowaniu w pliku puchar_dnia.php
    $doesDontExists = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_PUCHAR_DNIA . " 
	WHERE r_id='{$r_id}' && spotkanie='{$fazaGenerowania}' && spotkanie!='ele' GROUP BY r_id"));
    if (!empty($doesDontExists[0])) {
        return note("Faza <strong>{$fazaGenerowania}</strong> jest juz wygenerowana", "blad");
    }

    $countMatches = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_PUCHAR_DNIA . " 
	WHERE r_id='{$r_id}' && status!='3' GROUP BY r_id"));
    if (!empty($countMatches) && $fazaGenerowania != 'ele') {
        return note("W bazie sa niezakonczone mecze", "blad");
    }

    $accessGenerateCup = array('1_16' => 32, '1_8' => 16, '1_4' => 8, '1_2' => 4, '1_1' => 2);
    if (count($t) != $accessGenerateCup[$fazaGenerowania] && ($fazaGenerowania != 'ele')) {
        return note("Nie moge wygenerowac podanego etapu pucharu dnia", "blad");
    }


    $num_players = count($t);
    $num_players = ($num_players > 0) ? (int)$num_players : 4;
    $num_players += $num_players % 2;
    $players_done = array();
    shuffle($t);
    if ($no == 1) {
        while ($round % 2 != 0) {
            $round = rand(1, $num_players - 1);
        }
    }
    if ($no == 2) {
        while ($round % 2 == 0) {
            $round = rand(1, $num_players - 1);
        }
    }

    $arr[$b][] = array('cols' => 2, 'value' => 'Kolejka');
    $b++;

    for ($player = 1; $player < $num_players; $player++) {
        if (!in_array($player, $players_done)) {
            $opponent = $round - $player;
            $opponent += ($opponent < 0) ? $num_players : 1;
            if ($opponent != $player) {
                if (($player + $opponent) % 2 == 0 xor $player < $opponent) {
                    $p1 = $t[$player - 1];
                    $p2 = $t[$opponent - 1];
                    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                } else {
                    $p2 = $t[$player - 1];
                    $p1 = $t[$opponent - 1];

                    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                }
                saveCupMatch($p1, $p2, $fazaGenerowania, $r_id);
                $b++;
                $players_done[] = $player;
                $players_done[] = $opponent;
            }
        }
    }

    if ($round % 2 == 0) {
        $opponent = ($round + $num_players) / 2;
        $p1 = $t[$player - 1];
        $p2 = $t[$opponent - 1];
        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
    } else {
        $opponent = ($round + 1) / 2;
        $p2 = $t[$player - 1];
        $p1 = $t[$opponent - 1];
        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
    }
    saveCupMatch($p1, $p2, $fazaGenerowania, $r_id);
    $b++;

    require_once('include/admin/funkcje/function.table.php');
    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('gosp', 'gosc'), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();

    mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_LISTA . " SET status='{$nextStatus}' WHERE id = '{$r_id}' && vliga='" . WYBRANA_GRA . "'");


}


//laczy zpaluzowanych uzytkownikow jestli liczba jest nieprzysta
function polacz_zpauzowanych($r_id)
{
    $p1 = mysql_fetch_array(mysql_query("SELECT n1 FROM  " . TABELA_PUCHAR_DNIA . " where vliga='" . WYBRANA_GRA . "' && n2='' && r_id='{$r_id}'"));
    $p2 = mysql_fetch_array(mysql_query("SELECT n2 FROM  " . TABELA_PUCHAR_DNIA . " where vliga='" . WYBRANA_GRA . "' && n1='' && r_id='{$r_id}'"));

    require_once('include/admin/funkcje/function.table.php');

    $arr[$b][] = array('cols' => 2, 'value' => 'Mecz pauzowany');
    $b++;
    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => sprawdz_login_id($p2['n2']));
    $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => sprawdz_login_id($p1['n1']));

    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('gosp', 'gosc'), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();

    saveCupMatch($p2['n2'], $p1['n1'], 'ele', $r_id);
    mysql_query("DELETE  FROM " . TABELA_PUCHAR_DNIA . " where vliga='" . WYBRANA_GRA . "' &&  (n1='' || n2='') && spotkanie='ele' && r_id='{$r_id}';");
}


// informacja o statusie meczy
function pokaz_status_meczy($ile, $gra)
{
    $ilee = $ile;
    switch ($ilee) {
        case 32:
            $stan = "1_16";
            break;
        case 16:
            $stan = "1_8";
            break;
        case 8:
            $stan = "1_4";
            break;
        case 4:
            $stan = "1_2";
            break;
        case 2:
            $stan = "1_1";
            break;
    }
    $do_p = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where status!='3' &&  spotkanie='{$stan}' &&  vliga='{$gra}' && r_id='" . R_ID . "';"));
    $skonczone = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where status='3' &&  spotkanie='{$stan}' &&  vliga='{$gra}' && r_id='" . R_ID . "';"));
    $ilee = $ile / 2;
    if ($do_p != '0') {
        $tresc = "<span class=\"cConf\">Aktywne <b>{$do_p}</B> do konca</span>
		<a href=\"administrator.php?opcja=16&wybrana_gra={$gra}&r_id=" . R_ID . "\">[:wo:]</a>
		<a href=\"administrator.php?opcja=19&wybrana_gra={$gra}&r_id=" . R_ID . "\">[:edycja:]</a>";
    } else {
        if ($skonczone == $ilee) {
            $tresc = "<span class=\"cErro\">Zakonczone</span>";
        } else {
            $tresc = "<span class=\"cNorm\">Wylaczone</span>";
        }
    }
    return $tresc;
} // informacja o statusie meczy - END
?>
