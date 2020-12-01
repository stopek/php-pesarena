<?
// ogolna funkcja zapisujaca mecze do tabeli turnieju
function zapis_turniej($p1, $p2, $typ, $r_id, $grupa)
{
    $d1 = sprawdz_moj_klub_w_grze($p1, TABELA_TURNIEJ_GRACZE, $r_id);
    $d2 = sprawdz_moj_klub_w_grze($p2, TABELA_TURNIEJ_GRACZE, $r_id);

    $player_1 = sprawdz_login_id($p1);
    $player_2 = sprawdz_login_id($p2);

    if (mysql_query("INSERT INTO " . TABELA_TURNIEJ . " values('','{$p1}','{$p2}','','','1','{$grupa}','{$typ}','','','','" . WYBRANA_GRA . "','','{$d1}','{$d2}','{$r_id}');")) {
        note("Zapisalem mecz: '{$player_1}'-'{$player_2}' tj. grupa: {$grupa}", "info");
    } else {
        note("Nie zapisalem meczu: '{$player_1}'-'{$player_2}' tj. grupa: {$grupa}", "blad");
    }


    if (mysql_query("INSERT INTO " . TABELA_TURNIEJ . " values('','{$p2}','{$p1}','','','1','{$grupa}','{$typ}','','','','" . WYBRANA_GRA . "','','{$d2}','{$d1}','{$r_id}');")) {
        note("Zapisalem mecz: '{$player_1}'-'{$player_2}' tj. grupa: {$grupa}", "info");
    } else {
        note("Nie zapisalem meczu: '{$player_1}'-'{$player_2}' tj. grupa: {$grupa}", "blad");
    }

}


function generateTournamentMatches($grupa, $typ, $roundsLimit)
{
    $t = array();
    $sql = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "' && status='{$grupa}'");
    while ($rek = mysql_fetch_array($sql)) {
        $t[] = $rek['id_gracza'];
    }
    $num_players = count($t);
    $num_players = ($num_players > 0) ? (int)$num_players : 5;
    $num_players += $num_players % 2;

    $comabyc = (empty($roundsLimit) ? $num_players : $roundsLimit);
    for ($round = 1; $round < $num_players; $round++) {
        $arr[$b][] = array('cols' => 4, 'value' => "Kolejka: $round");
        $b++;
        $players_done = array();
        for ($player = 1; $player < $num_players; $player++) {
            if (!in_array($player, $players_done)) {
                $opponent = $round - $player;
                $opponent += ($opponent < 0) ? $num_players : 1;
                if ($opponent != $player) {
                    if (($player + $opponent) % 2 == 0 xor $player < $opponent) {
                        $p1 = $t[$player - 1];
                        $p2 = $t[$opponent - 1];
                        zapis_turniej($p1, $p2, $typ, R_ID, $grupa);
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_TURNIEJ_GRACZE, R_ID));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_TURNIEJ_GRACZE, R_ID));
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                        $b++;
                    } else {
                        $p2 = $t[$player - 1];
                        $p1 = $t[$opponent - 1];
                        zapis_turniej($p1, $p2, $typ, R_ID, $grupa);
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_TURNIEJ_GRACZE, R_ID));
                        $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_TURNIEJ_GRACZE, R_ID));
                        $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
                        $b++;
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
            zapis_turniej($p1, $p2, $typ, R_ID, $grupa);
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_TURNIEJ_GRACZE, R_ID));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_TURNIEJ_GRACZE, R_ID));
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
            $b++;
        } else {
            $opponent = ($round + 1) / 2;
            $p2 = $t[$player - 1];
            $p1 = $t[$opponent - 1];
            zapis_turniej($p1, $p2, $typ, R_ID, $grupa);
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p1, ''));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p1, TABELA_TURNIEJ_GRACZE, R_ID));
            $arr[$b][] = mini_logo_druzyny(sprawdz_moj_klub_w_grze($p2, TABELA_TURNIEJ_GRACZE, R_ID));
            $arr[$b][] = array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil', $p2, ''));
            $b++;
        }
    }

    $s = mysql_query("SELECT n1,n2 FROM " . TABELA_TURNIEJ . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "' && (n1='' || n2='')");
    while ($pause = mysql_fetch_Array($s)) {
        if (empty($pause['n1'])) {
            $retPla[] = $pause['n2'];
        }
        if (empty($pause['n2'])) {
            $retPla[] = $pause['n1'];
        }
    }
    /*
    for ($i=0; $i<count($retPla); $i=$i+2)
    {
        $arr[$b][] =  array('cols' => 4, 'value' => "Mecz pauzowany"); $b++;
        $arr[$b][] =  array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil',$retPla[$i+1],''));
        $arr[$b][] =  mini_logo_druzyny(sprawdz_moj_klub_w_grze($retPla[$i+1],TABELA_TURNIEJ_GRACZE,R_ID));
        $arr[$b][] =  mini_logo_druzyny(sprawdz_moj_klub_w_grze($retPla[$i],TABELA_TURNIEJ_GRACZE,R_ID));
        $arr[$b][] =  array('leagueTdStyle' => 'leagueTdStyle', 'value' => linkownik('profil',$retPla[$i],''));
        $b++;
        zapis_turniej($retPla[$i],$retPla[$i+1],$typ,R_ID,$grupa);
    }
    */
    mysql_query("DELETE  FROM " . TABELA_TURNIEJ . " where  (n1='' || n2='') && r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "' ;");


    require_once('include/admin/funkcje/function.table.php');
    $table = new createTable('adminFullTable center', '');
    $table->setTableHead(array(admsg_gospodarz, "", "", admsg_gosc), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();


}

?>
