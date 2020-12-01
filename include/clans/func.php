<?

//tworze klany (nazwa klanu, identyfikator druzyny klanu)
function createClan($name, $team_id)
{
    if (!empty($name) && !empty($team_id)) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_spotkania` WHERE r_id = '" . KLAN_R_ID . "'"));

        if (empty($count[0])) {
            if (mysql_query("INSERT INTO `-klany` VALUES ('','{$name}','" . KLAN_R_ID . "','0','{$team_id}');")) {
                $crid = mysql_insert_id();
                if (!mkdir("grafiki/avatar-clans/{$crid}/")) $err = true;
                if (!mkdir("grafiki/avatar-clans/{$crid}/thumbs/")) $err = true;

                if (empty($err)) note(clans_createClanDone, "info"); else note(clans_createClanErrorDir, "blad");
            } else {
                note(clans_createClanError, "blad");
            }
        } else {
            note(clans_createClanError2, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//zmieniam druzyne klanowi (identyfikator klanu, identyfikator druzyny)
function renameClanTeam($clan_id, $team_id)
{
    if (!empty($clan_id) && !empty($team_id)) {
        if (mysql_query("UPDATE `-klany` SET team = '{$team_id}' WHERE id = '{$clan_id}'")) {
            note(clans_renameClanTeamDone, "info");
        } else {
            note(clans_renameClanTeamError, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }

}

//zwracam nazwe klanu (identyfikator klanu)
function checkClanTeam($clan_id)
{
    $sql = mysql_fetch_array(mysql_query("SELECT team FROM `-klany` WHERE id = '{$clan_id}'"));
    return $sql['team'];
}

//lista dostepnych rozgrywek klanowych
function showListPartys($smarty)
{
    $s = mysql_query("SELECT * FROM `-klany_lista`");
    while ($r = mysql_fetch_array($s)) {
        $arr[$b][] = $r['nazwa'];
        $arr[$b][] = formatuj_date($r['data']);

        $smarty->assign("id", $r['id']);
        $smarty->assign("status", $r['status']);
        $arr[$b][] = $smarty->fetch('clans/changesOptionSelect.tpl');
        $b++;
    }

    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array("nazwa", "data rozpocz�cia", "mo�liwo�� zmian"), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}

//ustawia kapitana (identyfikator klanu, identyfikator gracza)
function setCaptain($clan_id, $player_id)
{
    if (!empty($clan_id) && !empty($player_id)) {
        if (mysql_query("UPDATE `-klany_gracze` SET stanowisko = 0 WHERE id_klanu = '{$clan_id}'") &&
            mysql_query("UPDATE `-klany_gracze` SET stanowisko = 1 WHERE id_klanu = '{$clan_id}' && id_gracza = '{$player_id}'")) {
            note(clans_setCaptainDone, "info");
        } else {
            note(clans_setCaptainError, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//zamienia zawodnika na gracza z zewnatrz (ide starego zawodnika, id nowego zawodnika)
function replecementPlayerNew($old, $new, $clan_id)
{
    if (!empty($old) && !empty($new)) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_gracze` WHERE id_gracza = '{$new}' "));
        if (empty($count[0])) {

            if (!mysql_query("UPDATE `-klany_gracze` SET id_gracza = '{$new}' WHERE id_gracza = '{$old}' && id_klanu = '{$clan_id}'")) {
                $err = true;
            }
            if (!mysql_query("UPDATE `-klany_mecze` SET gosp = '{$new}' WHERE gosp = '{$old}'")) {
                $err = true;
            }
            if (!mysql_query("UPDATE `-klany_mecze` SET gosc = '{$new}' WHERE gosc = '{$old}'")) {
                $err = true;
            }
            if (empty($err)) note(clans_replecementDone, "info"); else note(clans_replecementError, "blad");
        } else {
            note(clans_replecementErrorPlayerExists, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//zamieniam zawodnika wewnatrz klanu (id starego zawodnika, id nowego zawodnika)
function replecementPlayerInside($old, $new, $clan_id)
{
    if (!empty($old) && !empty($new)) {
        if (!mysql_query("UPDATE `-klany_mecze` SET gosp = '{$new}' WHERE gosp = '{$old}' && status != 3")) $err = true;
        if (!mysql_query("UPDATE `-klany_mecze` SET gosc = '{$new}' WHERE gosc = '{$old}' && status != 3")) $err = true;

        if (empty($err)) note(clans_replecementDone, "info"); else note(clans_replecementError, "blad");
    } else {
        note(clans_AFR, "blad");
    }
}

//dodaje graczy do klanow (identyfikator gracza, identyfikator klanu)
function addPlayersToClan($player_id, $clan_id)
{
    if (!empty($player_id) && !empty($clan_id)) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id), (SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$clan_id}') FROM `-klany_gracze` WHERE id_gracza = '{$player_id}' && id_klanu='{$clan_id}' "));
        if (empty($count[0]) && $count[1] < 6) {
            if (mysql_query("INSERT INTO `-klany_gracze` VALUES ('','{$player_id}','{$clan_id}','0','0'); ")) {
                note(clans_addPlayerToClanDone, "info");
            } else {
                note(clans_addPlayerToClanError, "blad");
            }
        } else {
            note(clans_addPlayerToClanError2, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//zmiana pozycji zawodnika (identyfikator gracza, identyfikator klanu, nowa pozycja)
function changePosition($player_id, $clan_id, $position)
{
    if (!empty($player_id) && !empty($clan_id)) {
        $status = mysql_fetch_array(mysql_query("SELECT status FROM `-klany_lista` WHERE id = '" . KLAN_R_ID . "'"));
        if (!empty($status['status'])) {
            $sta = mysql_fetch_array(mysql_query("SELECT status FROM `-klany` WHERE id = '{$clan_id}'"));
            if (empty($sta['status'])) {
                if (
                    mysql_query("UPDATE `-klany_gracze` SET pozycja = '0' WHERE  id_klanu = '{$clan_id}' && pozycja = '{$position}' ") &&
                    mysql_query("UPDATE `-klany_gracze` SET pozycja = '{$position}' WHERE id_gracza = '{$player_id}' && id_klanu = '{$clan_id}' ")) {
                    note(clans_setPositionDone, "info");
                } else {
                    note(clans_setPotitionError, "blad");
                }
            } else {
                note(clans_setPositionErrorClanReady, "blad");
            }
        } else {
            note(clans_BAS, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//zmienia nazwe klanu (nowa nazwa klanu, identyfikator klanu)
function changeClanName($name, $clan_id)
{
    if (!empty($name) && !empty($clan_id)) {
        if (mysql_query("UPDATE `-klany` SET nazwa = '{$name}' WHERE id = '{$clan_id}' ")) {
            note(clans_renameClanDone, "info");
        } else {
            note(clans_renameClanError, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//wyswietla pager dostepnych kolejek (aktualna kolejka z _GET , ilosc graczy )
function selectRound($act, $graczy)
{
    for ($a = 1; $a < $graczy; $a++) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_spotkania` WHERE kolejka = '{$a}'"));
        $opacity = (empty($count[0]) ? "class=\"opacity30\"" : null);
        echo "<a href=\"" . BASE_URL . "timetable/{$a}/\" {$opacity}><button " . ($act == $a ? "class=\"info-button\"" : null) . ">{$a}</button></a>";
    }

}

//zatwierdzam edycje spotkania (id spotkania, wynik dla gospodarza, wynik dla goscia)
function editResult($id, $w1, $w2)
{

    $check = mysql_fetch_array(mysql_query("SELECT count(*) FROM `-klany_mecze` m WHERE  m.id = '{$id}' && m.status = '3' "));

    if (!empty($id)) {
        if (!empty($w1) && !empty($w2) && ACCESS == 1 && !empty($check[0])) {
            if (mysql_query("UPDATE `-klany_mecze` SET data = NOW(), w1 = '{$w1}' , w2 = '{$w2}', status = '3' WHERE id = '{$id}'")) {
                note(clans_editResultDone, "info");
            } else {
                note(clans_editResultError, "blad");
            }
        } else {
            note(clans_editResultAccessDenied, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//wyswietla terminarz (identyfikator klanu)
function showTimetable($clan_id, $smarty)
{
    $round = (int)$_GET[SECOND_HTACCESS_VAR];

    $adSql = ($round ? " && s.kolejka = '{$round}' " : null);

    $sql = mysql_query("SELECT s.* ,
		(SELECT count(m.id) FROM `-klany_mecze` m WHERE s.id = m.id_spotkania && m.status = '3') as ilosc_rozegranych
	FROM `-klany_spotkania` s WHERE s.r_id = '" . KLAN_R_ID . "' {$adSql} ");
    while ($rec = mysql_fetch_array($sql)) {
        $smarty->assign('rec', $rec);
        $smarty->assign('BASE_URL', BASE_URL);
        $smarty->assign('gosp', checkClanName($rec['gosp']));
        $smarty->assign('gosc', checkClanName($rec['gosc']));
        $arr[$b][] = array("cols" => 4, "value" => $smarty->fetch('clans/timetableHeader.tpl'), 'leagueTdStyle' => 'header');
        $b++;


        $s = mysql_query("SELECT * FROM `-klany_mecze` WHERE id_spotkania = '{$rec['id']}' ");
        while ($r = mysql_fetch_array($s)) {
            $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "&nbsp;&nbsp;&nbsp;" . sprawdz_login_id($r['gosp']));
            $arr[$b][] = array('leagueTdStyle' => 'w25', 'value' => sprawdz_login_id($r['gosc']));
            $arr[$b][] = ($r['status'] == 3 ? formatuj_date($r['data']) : "-");
            $arr[$b][] = ($r['status'] == 3 ? " {$r['w1']} - {$r['w2']}" : "-");
            $b++;
        }

    }

    $players = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany` WHERE r_id = '" . KLAN_R_ID . "' "));

    selectRound((int)$_GET[SECOND_HTACCESS_VAR], ($players[0] % 2 == 0 ? $players[0] : $players[0] + 1));
    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array(), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}

//wyswietla tabele (identyfikator klanu, show (albo ranking albo tabela)
function showTable($clan_id, $show)
{
    require_once('include/clans/func.table.php');

    $sql = mysql_query("SELECT k.id, k.nazwa, k.status FROM `-klany` k WHERE r_id = '" . KLAN_R_ID . "'");
    while ($rec = mysql_fetch_array($sql)) {
        //sortowanie od najwazniejszego: pkt, +/- bramek, + bramek
        $a[$c]['name'] = $rec['id'];
        $a[$c]['num1'] = scoredGoalsClan($rec['id']);
        $a[$c]['stracone_bramki'] = lostGoalsClan($rec['id']);
        $a[$c]['num2'] = $a[$c]['num1'] - $a[$c]['stracone_bramki'];
        $a[$c]['rozegrane_spotkania'] = rozegraneSpotkaniaKlan($rec['id']);
        $a[$c]['wygrane_mecze'] = winMatchesClan($rec['id']);
        $a[$c]['przegrane_mecze'] = lostMatchesClan($rec['id']);
        $a[$c]['remisy'] = drawMatchesClan($rec['id']);
        $a[$c]['rozegrane_mecze'] = playedMatchesClan($rec['id']);
        $a[$c]['num3'] = ($show == 'ranking' ? ($a[$c]['wygrane_mecze'] * 300) + ($a[$c]['remisy'] * 50) + ($a[$c]['przegrane_mecze'] * (-100)) : ($a[$c]['wygrane_mecze'] * 3) + ($a[$c]['remisy']));

        $c++;
    }


    if (count($a) != 0) {
        sortx($a, sortowanie_d_d_a());
        while (list($key, $value) = each($a)) {
            $id = $value['name'];
            $img = "http://pesarena.pl/grafiki/avatar-clans/{$id}/thumbs/avatar.jpg";
            $arr[$b][] = array('leagueTdStyle' => 'w25', 'value' => "
				<div style=\"background:  no-repeat center left url('{$img}'); padding-left: 70px; margin-left: 4px;\">

					<a href=\"" . BASE_URL . "profile/{$id}/\"><button>" . checkClanName($id) . "</button></a>
				</div>
				
			");
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['rozegrane_spotkania']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['rozegrane_mecze']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['wygrane_mecze']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['przegrane_mecze']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['remisy']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['stracone_bramki']);
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['num2']); //bramki plus minus
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['num1']); //strzelone bramki
            $arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => "<button class=\"klan_table_points\">" . ($value['num3'] ? $value['num3'] : 0) . "</button>"); //zdobyte punkty
            //$arr[$b][] = array('leagueTdStyle' => 'w8-3', 'value' => $value['test']);
            //$arr[$b][] = $value['bonus']; //zdobyte punkty

            $b++;
        }

    }


    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array(
        array('leagueTdStyle' => 'w25', 'value' => "nazwa klanu"),
        array('leagueTdStyle' => 't2 tips w8-3', 'value' => "RS", 'title' => 'rozegrane spotkania'),
        array('leagueTdStyle' => 't3 tips w8-3', 'value' => "M", 'title' => 'rozegrane mecze'),
        array('leagueTdStyle' => 't4 tips w8-3', 'value' => "WM", 'title' => 'wygrane mecze'),
        array('leagueTdStyle' => 't5 tips w8-3', 'value' => "PM", 'title' => 'przegrane mecze'),
        array('leagueTdStyle' => 't6 tips w8-3', 'value' => "R", 'title' => 'zremisowane mecze'),
        array('leagueTdStyle' => 't7 tips w8-3', 'value' => "B-", 'title' => 'stracone bramki'),
        array('leagueTdStyle' => 't8 tips w8-3', 'value' => "B +/-", 'title' => 'bramki +/-'),
        array('leagueTdStyle' => 't9 tips w8-3', 'value' => "B+", 'title' => 'strzelone bramki'),
        array('leagueTdStyle' => 't10 tips w8-3', 'value' => "", 'title' => 'zdobyte punkty')
    ), "adminHeadersClass");
    $table->setTableBody($arr);


    echo $table->getTable();


}

//wyswietla profil klanu
function showClanProfile($id, $smarty)
{
    require_once('include/clans/func.table.php');
    $data = mysql_fetch_array(mysql_query(
        "
		SELECT k.id, k.nazwa,
			(SELECT id_gracza FROM `-klany_gracze` WHERE stanowisko = '1' && id_klanu = '{$id}') as kapitan
		FROM `-klany` k WHERE k.id = '{$id}'
	
	"));

    $sql = mysql_query("SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$id}'");
    while ($players = mysql_fetch_array($sql)) {
        $arr[$b]['name'] = array('leagueTdStyle' => 'w25', 'value' => "&nbsp;&nbsp;&nbsp;" . sprawdz_login_id($players['id_gracza']));
        $arr[$b]['num1'] = strzelone_bramki_klan($players['id_gracza']);
        $arr[$b][] = stracone_bramki_klan($players['id_gracza']);
        $arr[$b][] = wygrane_mecze_klan($players['id_gracza']);
        $arr[$b][] = przegrane_mecze_klan($players['id_gracza']);
        $arr[$b][] = remisy_klan($players['id_gracza']);
        $arr[$b]['num3'] = pkt_klan($players['id_gracza']) . "&nbsp;";

        $b++;
    }


    sortx($arr, sortowanie_d_d_a());


    $smarty->assign('images', array(
            'team' => "http://pesarena.pl/grafiki/loga/" . checkClanTeam($id) . ".png",
            'avatar' => "http://pesarena.pl/grafiki/avatar-clans/{$id}/avatar.jpg")
    );
    $smarty->assign('data', array(
            'kapitan' => sprawdz_login_id($data['kapitan']),
            'nazwa' => $data['nazwa'])
    );


    $table = new createTable('adminFullTable text-center w15', '');
    $table->setTableHead(array(array('leagueTdStyle' => 'w25', 'value' => '&nbsp;nazwa gracza'), 'b+', 'b-', 'mw', 'mp', 'mr', 'pkt&nbsp;'), '');
    $table->setTableBody($arr);
    $smarty->assign('tableWithPlayers', $table->getTable());


    $re = mysql_query("SELECT id,gosp, gosc FROM `-klany_spotkania` WHERE gosp = '{$id}' || gosc = '{$id}'");
    while ($rec = mysql_Fetch_array($re)) {
        $s = mysql_query("SELECT * FROM `-klany_mecze` WHERE id_spotkania = '{$rec['id']}' && status = '3' ");
        while ($r = mysql_fetch_array($s)) {
            $ar[$b][] = $r['id'];
            $ar[$b][] = array('leagueTdStyle' => 'w25', 'value' => sprawdz_login_id($r['gosp']));
            $ar[$b][] = array('leagueTdStyle' => 'w25', 'value' => sprawdz_login_id($r['gosc']));
            $ar[$b][] = ($r['status'] == 3 ? formatuj_date($r['data']) : "-");
            $ar[$b][] = ($r['status'] == 3 ? " {$r['w1']} - {$r['w2']}" : "-");
            $ar[$b][] = ($id == $rec['gosp'] ?
                ($r['w1'] > $r['w2'] ? "<button class=\"button3pkt\">3pkt</button>" :
                    ($r['w1'] == $r['w2'] ? "<button class=\"button1pkt\">1pkt</button>" :
                        ($r['w1'] < $r['w2'] ? "<button class=\"button0pkt\">0pkt</button>" :
                            null))) : ($r['w1'] < $r['w2'] ? "<button class=\"button3pkt\">3pkt</button>" :
                    ($r['w1'] == $r['w2'] ? "<button class=\"button1pkt\">1pkt</button>" :
                        ($r['w1'] > $r['w2'] ? "<button class=\"button0pkt\">0pkt</button>" :
                            null))));

            $b++;
        }
    }


    $table = new createTable('adminFullTable text-center', '');
    $table->setTableHead(array(), '');
    $table->setTableBody($ar);
    $smarty->assign('tableWithMatches', $table->getTable());


    $re = mysql_query("SELECT s.id, s.gosp, s.gosc, (SELECT count(id) FROM `-klany_mecze` WHERE id_spotkania = s.id && status = 3) as count
	FROM `-klany_spotkania` s WHERE s.gosp = '{$id}' || s.gosc = '{$id}' HAVING count=4 ");
    $arrr[$b] = array('gospodarz', 'gosc', 'bramki strzelone', 'bramki stracone', 'bramki +/-');
    $b++;
    while ($rec = mysql_fetch_array($re)) {
        $arrr[$b][] = checkClanName($rec['gosp']);
        $arrr[$b][] = checkClanName($rec['gosc']);

        $x += func($id, $rec['id'], 'strzelone_bramki_klan');
        $y += func(($id == $rec['gosp'] ? $rec['gosc'] : $rec['gosp']), $rec['id'], 'strzelone_bramki_klan');
        $arrr[$b][] = "<button><strong>{$x}</strong>/{$y}</button>";
        $arrr[$b][] = "<button><strong>{$y}</strong>/{$x}</button>";
        $arrr[$b][] = "<button><strong>" . ($x - $y) . "</strong>/" . ($y - $x) . "</button>";


        $b++;
    }
    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array(), '');
    $table->setTableBody($arrr);
    $smarty->assign('tableWithPartys', $table->getTable());

    $smarty->display('clans/clanProfile.tpl');
}

//akceptuje wynik - konczy spotkanie (identyfikator spotkania)
function acceptResult($id)
{
    $loggedCaptain = (int)$_SESSION['klany_kapitan_id'];


    $check = mysql_fetch_array(mysql_query("
		SELECT count(s.id) FROM `-klany_mecze` m
		LEFT JOIN `-klany_spotkania` s
		ON m.id_spotkania = s.id  && m.id = '{$id}' && m.status = '2' 
		WHERE  s.gosc = '{$loggedCaptain}' 
	"));

    if (!empty($id)) {
        if (!empty($check[0]) || ACCESS == 1) {
            if (mysql_query("UPDATE `-klany_mecze` SET data = NOW(), status = '3' WHERE id = '{$id}'")) {
                note(clans_acceptResultDone, "info");
            } else {
                note(clans_accespResultError, "blad");
            }
        } else {
            note(clans_setResultAccessDenied, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//wprowadza wynik (identyfikator meczu)
function saveResult($id)
{

    $loggedCaptain = (int)$_SESSION['klany_kapitan_id'];
    $w1 = (int)$_POST['w1'];
    $w2 = (int)$_POST['w2'];


    $check = mysql_fetch_array(mysql_query("
		SELECT count(s.id) FROM `-klany_mecze` m
		LEFT JOIN `-klany_spotkania` s
		ON m.id_spotkania = s.id  && m.id = '{$id}' && m.status = '1' 
		WHERE  s.gosp = '{$loggedCaptain}' 
	"));

    if (!empty($id) && isset($w1) && isset($w2)) {
        if (!empty($check[0]) || ACCESS == 1) {
            if (mysql_query("UPDATE `-klany_mecze` SET w1 = '{$w1}' , w2 = '{$w2}' , data = NOW(), status = '2' WHERE id = '{$id}'")) {
                note(clans_setResultDone, "info");
            } else {
                note(clans_setResultError, "blad");
            }
        } else {
            note(clans_setResultAccessDenied, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }

}

//odrzuca wynik spotkania (identyfikator spotkania)
function rejectResult($id)
{
    $loggedCaptain = (int)$_SESSION['klany_kapitan_id'];


    $check = mysql_fetch_array(mysql_query("
		SELECT count(s.id) FROM `-klany_mecze` m
		LEFT JOIN `-klany_spotkania` s
		ON m.id_spotkania = s.id  && m.id = '{$id}' && m.status = '2' 
		WHERE  s.gosc = '{$loggedCaptain}' 
	"));

    if (!empty($id)) {
        if (!empty($check[0]) || ACCESS == 1) {
            if (mysql_query("UPDATE `-klany_mecze` SET data = NOW(), status = '1' WHERE id = '{$id}'")) {
                note(clans_rejectResultDone, "info");
            } else {
                note(clans_rejectResultError, "blad");
            }
        } else {
            note(clans_setResultAccessDenied, "blad");
        }
    } else {
        note(clans_AFR, "blad");
    }
}

//wprowadzanie wyniku klanowego (identyfikator spotkania)
function typeClanResult($id, $smarty)
{

    if (!empty($_POST['goResult'])) saveResult($id);
    $rec = mysql_fetch_array(mysql_query("SELECT * FROM `-klany_mecze` WHERE id = '{$id}'"));
    $smarty->assign('players', array(sprawdz_login_id($rec['gosp']), sprawdz_login_id($rec['gosc'])));
    $smarty->display('clans/typeResults.tpl');

}

//tabela ze spotkaniami danego klanu (smarty)
function clanResults($smarty)
{
    $loggedUser = (int)$_SESSION['klay_id_user'];

    $loggedCaptain = (int)$_SESSION['klany_kapitan_id'];

    if (ACCESS == 2) {
        $additionalSql = " && (s.gosp = '{$loggedCaptain}' || s.gosc = '{$loggedCaptain}')";
    } elseif (ACCESS == 1 && $_GET['addopt'] != 'edit') {
        if (!empty($_POST['goSearch'])) {
            $aSql = " && id = '" . (int)$_POST['searchID'] . "' ";
        }
        $smarty->display('clans/searchMatch.tpl');
    }


    $stats = array(1 => clans_matchStatus1, 2 => clans_matchStatus2, 3 => clans_matchStatus3);


    $check = mysql_query("SELECT s.*,(SELECT count(m.id) FROM `-klany_mecze` m WHERE m.id_spotkania = s.id && m.status != 3) as test
	FROM `-klany_spotkania` s WHERE s.r_id = '" . KLAN_R_ID . "' {$additionalSql} ORDER BY test DESC , s.id");
    while ($r = mysql_fetch_array($check)) {
        $smarty->assign('vars', array('gosp' => array($r['gosp'], checkClanName($r['gosp'])), 'gosc' => array($r['gosc'], checkClanName($r['gosc']))));
        $smarty->assign('BASE_URL', BASE_URL);


        $arr[$b][] = array('leagueTdStyle' => 'header', 'cols' => 6, 'value' => $smarty->fetch('clans/results-header.tpl'));

        $b++;
        $sql = mysql_query("SELECT * FROM `-klany_mecze` WHERE id_spotkania = '{$r['id']}' {$aSql} ");
        while ($rec = mysql_fetch_array($sql)) {
            $arr[$b][] = "&nbsp;&nbsp;&nbsp;" . $rec['id'];
            $arr[$b][] = sprawdz_login_id($rec['gosp']);
            $arr[$b][] = sprawdz_login_id($rec['gosc']);
            $arr[$b][] = ($rec['status'] != 1 ? "{$rec['w1']}:{$rec['w2']}" : "-");
            $arr[$b][] = $stats[$rec['status']];

            $smarty->assign('ident', $rec['id']);
            $smarty->assign('option_1', ((($r['gosp'] == $loggedCaptain || ACCESS == 1) && $rec['status'] == 1) ? true : false));
            $smarty->assign('option_2', ((($r['gosc'] == $loggedCaptain || ACCESS == 1) && $rec['status'] == 2) ? true : false));
            $smarty->assign('option_3', ($rec['status'] == 3 ? true : false));

            $arr[$b][] = $smarty->fetch('clans/results-option.tpl');
            $b++;
        }
    }


    $table = new createTable('adminFullTable w20 text-left', '');
    $table->setTableHead(array(), '');
    $table->setTableBody($arr);
    echo $table->getTable();
}

//logowanie (smarty)
function lForm($smarty)
{
    if (!empty($_POST['goLogin'])) {
        $l = kodowanie_loginu(czysta_zmienna_post($_POST['login']));
        $h = kodowanie_hasla(czysta_zmienna_post($_POST['haslo']));

        $count = mysql_fetch_array(mysql_query("SELECT count(id), id, pseudo FROM `-klany_admini` WHERE login = '{$l}' && haslo = '{$h}'"));
        if (empty($count[0])) {
            $captain = mysql_fetch_Array(mysql_query("
				SELECT u.id, k.id_klanu, u.pseudo FROM `" . TABELA_UZYTKOWNICY . "` u 
				LEFT JOIN `-klany_gracze` k 
				ON u.id = k.id_gracza 
				JOIN `-klany` nk
				
				WHERE k.stanowisko = '1' && u.login = '{$l}' && u.haslo = '{$h}' && k.id_klanu = nk.id && nk.r_id = '" . KLAN_R_ID . "'
			
			"));
            if (empty($captain[0])) {
                $player = mysql_fetch_Array(mysql_query("SELECT u.id, u.pseudo FROM `" . TABELA_UZYTKOWNICY . "` u WHERE  u.login = '{$l}' && u.haslo = '{$h}' "));

                if (empty($player[0])) {
                    note(clans_loginBadData, "blad");
                } else {
                    $_SESSION['klay_id_user'] = $player[0];
                    $_SESSION['klany_access'] = 3;
                    $_SESSION['klan_name_user'] = $player[1];

                    note(clans_loginDoneUser, "info");
                }
            } else {
                $_SESSION['klay_id_user'] = $captain[0];
                $_SESSION['klany_access'] = 2;
                $_SESSION['klany_kapitan_id'] = $captain[1];
                $_SESSION['klan_name_user'] = $captain[2];

                note(clans_loginDoneCaptain, "info");
            }
        } else {
            $_SESSION['klay_id_user'] = $count[1];
            $_SESSION['klany_access'] = 1;
            $_SESSION['klan_name_user'] = $count[2];

            note(clans_loginDoneAdmin, "info");
        }
    }

    $smarty->display('clans/login.tpl');

}

//wylogowywanie
function lOut()
{
    $_SESSION['klay_id_user'] = false;
    $_SESSION['klany_access'] = false;
    $_SESSION['zalogowany'] = false;
    session_destroy();
    note(clans_logoutDone, "info");
}

//glowna tabela z klanami, graczami przyciskami (smarty)
function showCLansWithPlayers($smarty)
{

    $additionalSql = (ACCESS != 1 ? " && k.id = '" . CAPTAINS_CLAN . "' " : null);


    $sql = mysql_query("SELECT k.id, k.nazwa, k.status, (SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = k.id && pozycja='0') as ilosc_graczy 
	FROM `-klany` k WHERE r_id = '" . KLAN_R_ID . "' {$additionalSql} ORDER BY ilosc_graczy DESC");
    while ($rec = mysql_fetch_array($sql)) {
        $smarty->assign('BASE_URL', BASE_URL);
        $smarty->assign('id', $rec['id']);
        $smarty->assign('clanName', checkClanName($rec['id']));
        $smarty->assign('status', $rec['status']);

        $smarty->assign('check', ($_GET[FIRST_HTACCESS_VAR] == 'changeClanName' && $_GET[SECOND_HTACCESS_VAR] == $rec['id'] && empty($_POST['goName']) ? true : false));
        $arr[$b][] = array('leagueTdStyle' => 'header', 'cols' => 3, 'value' =>

            $smarty->fetch('clans/changeClanNameForm.tpl') .
            $smarty->fetch('clans/changeTeam,uploadButtons.tpl') .
            $smarty->fetch('clans/replecementPlayerForm.tpl')
        );
        $arr[$b][] = array('leagueTdStyle' => 'header', 'value' => $smarty->fetch('clans/readyStatusButtons.tpl'));
        $b++;

        $s = mysql_query("SELECT * FROM `-klany_gracze` WHERE id_klanu = '{$rec['id']}' ");
        while ($r = mysql_fetch_array($s)) {
            $arr[$b][] = array('leagueTdStyle' => 'w25', 'value' => "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . sprawdz_login_id($r['id_gracza']));

            $smarty->assign('id_gracza', $r['id_gracza']);
            $smarty->assign('id', $rec['id']);
            $smarty->assign('pozycja', $r['pozycja']);
            $smarty->assign('stanowisko', $r['stanowisko']);
            $arr[$b][] = $smarty->fetch('clans/changePositionForm.tpl');

            $arr[$b][] = $smarty->fetch('clans/typePlayerButtons.tpl');
            $arr[$b][] = "";
            $b++;
        }
    }


    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array(), '');
    $table->setTableBody($arr);
    echo $table->getTable();
}

//zwraca nazwe klanu
function checkClanName($clan_id)
{
    $sql = mysql_fetch_array(mysql_query("SELECT nazwa FROM `-klany` WHERE id = '{$clan_id}'"));
    return $sql['nazwa'];
}

//zapisuje spotkania klanowe (kolejka, gospodarz, gosc , czy aktywna)
function saveMatches($round, $player, $opponent, $activate)
{
    if (ACCESS == 1) {
        if (!empty($activate) && !empty($player) && !empty($opponent)) {
            $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_lista` WHERE id = '" . KLAN_R_ID . "' && status = '0'"));
            if (!empty($count[0])) {
                $d = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_spotkania` WHERE kolejka = '{$round}' && r_id = '" . KLAN_R_ID . "'"));
                if (empty($d[0]) || $_SESSION['NOWADD']) {
                    $_SESSION['NOWADD'] = TRUE;
                    if (mysql_query("INSERT INTO `-klany_spotkania` VALUES('','{$player}','{$opponent}',NOW(),'{$round}','" . KLAN_R_ID . "');")) {
                        $id = mysql_insert_id();
                        $players = mysql_fetch_array(mysql_query("
						SELECT id,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$player}' && pozycja = '1') as k1p1,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$player}' && pozycja = '2') as k1p2,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$player}' && pozycja = '3') as k1p3,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$player}' && pozycja = '4') as k1p4,	
							
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$opponent}' && pozycja = '1') as k2p1,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$opponent}' && pozycja = '2') as k2p2,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$opponent}' && pozycja = '3') as k2p3,
							(SELECT id_gracza FROM `-klany_gracze` WHERE id_klanu = '{$opponent}' && pozycja = '4') as k2p4 
						FROM `-klany_gracze`"));


                        if (mysql_query("INSERT INTO `-klany_mecze` VALUES 
											('','{$id}','{$players['k1p1']}','{$players['k2p1']}','','','1',''),
											('','{$id}','{$players['k1p2']}','{$players['k2p2']}','','','1',''),
											('','{$id}','{$players['k1p3']}','{$players['k2p3']}','','','1',''),
											('','{$id}','{$players['k1p4']}','{$players['k2p4']}','','','1','')				
					")

                        ) {
                            //it's kk
                        } else {
                            note(clans_saveMatchesError, "blad");
                        }
                    } else {
                        note(clans_saveMatchesError2, "blad");
                    }
                }
            } else {
                if (!$_SESSION['one_error']) note(clans_toChangeIsEnabledError, "blad");
                $_SESSION['one_error'] = true;
            }
        }
    }
}


//ustawiam mozliwosc zmian (nowy status 0 lub 1, identyfikator zorgrywek)
function changes($status, $id)
{
    if (!empty($id)) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany` WHERE r_id = '" . KLAN_R_ID . "' && status = '0'"));
        if (empty($count[0]) || $status == 1) // jesli otwieram zapisy
        {
            if ($status == 1) {
                mysql_query("UPDATE `-klany` SET status='0' && r_id = '" . KLAN_R_ID . "'");
            }
            if (mysql_query("UPDATE `-klany_lista` SET status = '{$status}' WHERE id = '{$id}' ")) {
                note("Poprawnie wykonano zmian�!", "info");
            } else {
                note("B��d podczas zmiany!", "blad");
            }
        } else {
            note("Nie wszystkie klany zg�osi�y swoj� gotowo��", "blad");
        }
    } else {
        note("Wszystkie pola s� wymagane", "blad");
    }
}

//tworze nowe rozgrywki klanowe
function startRecords($name)
{
    if (!empty($name)) {
        if (mysql_query("INSERT INTO `-klany_lista` VALUES ('','{$name}',NOW(),'0');")) {
            note("Klan zosta� utworzony", "info");
        } else {
            note("B��d podczas tworzenia klanu", "blad");
        }
    } else {
        note("Wszystkie pola s� wymagane", "blad");
    }
}

function returnArray($table, $where, $v = '*')
{
    //echo "SELECT {$v} FROM {$table} {$where}";
    $s = mysql_query("SELECT {$v} FROM {$table} {$where}");
    while ($r = mysql_fetch_array($s)) $ret[] = $r;

    return $ret;
}

function deletePlayerFromClan($clan_id, $player_id)
{
    if (!empty($clan_id) && !empty($player_id)) {
        if (mysql_query("DELETE FROM `-klany_gracze` WHERE id_klanu = '{$clan_id}' && id_gracza = '{$player_id}'")) {
            note("Gracz zosta� usuni�ty z klanu", "info");
        } else {
            note("B��d podczas usuwania gracza z klanu", "blad");
        }
    } else {
        note("Wszystkie pola s� wymagane", "blad");
    }
}

//usuwam klan wraz z graczami
function deleteClan($clan_id)
{
    if (!empty($clan_id)) {
        $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_spotkania` WHERE gosc = '{$clan_id}' || gosp = '{$clan_id}'"));

        if (empty($count[0])) {
            if (
                mysql_query("DELETE FROM `-klany_gracze` WHERE id_klanu = '{$clan_id}' ") &&
                mysql_query("DELETE FROM `-klany` WHERE id = '{$clan_id}'")

            ) {
                note("Klan wraz z graczami zosta� usuni�ty", "info");
            } else {
                note("B��d podczas usuwania klanu lub/i graczy tego klanu", "blad");
            }
        } else {
            note("Nie mo�na usun�� klanu poniewa� zosta�o wygenerowane jakie� spotkanie w kt�rym klan ten bierze udzia�", "blad");
        }
    } else {
        note("Wszystkie pola s� wymagane", "blad");
    }
}

function deleteRound($round)
{
    if (ACCESS == 1) {
        $block = false;
        $count = mysql_query("SELECT id FROM `-klany_spotkania` WHERE kolejka='{$round}' && r_id = '" . KLAN_R_ID . "'");
        while ($check = mysql_fetch_array($count)) {
            $c = mysql_fetch_array(mysql_query("SELECT count(id) FROM `-klany_mecze` WHERE id_spotkania = '{$check['id']}' && status != '1'"));
            if (!empty($c[0])) $block = true;
        }

        if (empty($block)) {
            $sql = mysql_query("SELECT * FROM `-klany_spotkania` WHERE kolejka = '{$round}'");
            while ($r = mysql_fetch_array($sql)) {
                mysql_query("DELETE FROM `-klany_mecze` WHERE id_spotkania = '{$r['id']}'");
            }
            mysql_query("DELETE FROM `-klany_spotkania` WHERE kolejka = '{$round}'");
        } else {
            note("Nie mo�na usun�� ten kolejki poniewa� jakies spotkanie zostalo ju� rozegrane", "blad");
        }
    }
}

function generateMatches($id)
{

    $status = mysql_fetch_array(mysql_query("SELECT status FROM `-klany_lista` WHERE id = '" . KLAN_R_ID . "'"));
    if (!empty($status['status'])) {
        note("
		Administrator w��czy� mo�liwo�� zmian - generowanie mo�liwe dopiero po zg�oszeniu swojej gotowo�ci przez wszystkie klany i wy��czeniu mo�liwo�ci zmian przez administratora", "blad");

    }


    $sql = mysql_query("SELECT nazwa,id  FROM `-klany` WHERE r_id = '" . KLAN_R_ID . "'");
    while ($rec = mysql_fetch_array($sql)) $t[] = $rec['id'];


    $num_players = count($t);
    $num_players = ($num_players > 0) ? (int)$num_players : 0;
    $num_players += $num_players % 2;

    $activate = (int)$_GET['activate'];

    $a = (!empty($activate) ? $activate : 1);

    $c = (!empty($activate) ? $a + 1 : $num_players);


    for ($round = $a; $round < $c; $round++) {
        $d = mysql_fetch_array(mysql_query("SELECT count(k.id), k.data
			FROM `-klany_spotkania` k WHERE k.kolejka = '{$round}' && r_id = '" . KLAN_R_ID . "'"));


        $arr[$b][] = array('leagueTdStyle' => 'header', 'value' => "
				<span class=\"float-left\"><button class=\"info-button\">kolejka {$round}</button></span>
				<span class=\"float-left\">
					" . (!empty($d[0]) ? "
					
					<button class=\"info-button\" >" . formatuj_date($d['data']) . "</button>
		
					
					" : null) . " 
					
				</span>");
        $arr[$b][] = array('leagueTdStyle' => 'header', 'value' => "
				<span class=\"float-right\">
					<a href=\"" . BASE_URL . "match/?activate={$round}\">
					<button id = \"button-{$round}\" " . (!empty($d[0]) ? " class=\"green-button\" " : null) . ">
					" . (!empty($d[0]) ? " kolejka aktywna " : "aktywuj kolejk�") . "</button></a>
				
					" . (!empty($d[0]) ? " 
						<a href=\"" . BASE_URL . "match/?dr={$round}\"><button class=\"important-button\">x</button></a>
					" : null) . "
				</span>
			");
        $b++;
        $players_done = array();
        $b++;
        for ($player = 1; $player < $num_players; $player++) {
            if (!in_array($player, $players_done)) {
                $opponent = $round - $player;
                $opponent += ($opponent < 0) ? $num_players : 1;
                if ($opponent != $player) {
                    if (($player + $opponent) % 2 == 0 xor $player < $opponent) {
                        $p1 = $t[$player - 1];
                        $p2 = $t[$opponent - 1];

                        $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "&nbsp;&nbsp;<a href=\"" . BASE_URL . "profile/{$p1}/\">" . checkClanName($p1) . "</a>");
                        $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "<a href=\"" . BASE_URL . "profile/{$p2}/\">" . checkClanName($p2) . "</a>");
                        saveMatches($round, $p1, $p2, $activate);
                        $b++;
                    } else {
                        $p2 = $t[$player - 1];
                        $p1 = $t[$opponent - 1];

                        $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "&nbsp;&nbsp;<a href=\"" . BASE_URL . "profile/{$p1}/\">" . checkClanName($p1) . "</a>");
                        $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "<a href=\"" . BASE_URL . "profile/{$p2}/\">" . checkClanName($p2) . "</a>");
                        saveMatches($round, $p1, $p2, $activate);
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

            $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "&nbsp;&nbsp;<a href=\"" . BASE_URL . "profile/{$p1}/\">" . checkClanName($p1) . "</a>");
            $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "<a href=\"" . BASE_URL . "profile/{$p2}/\">" . checkClanName($p2) . "</a>");
            saveMatches($round, $p1, $p2, $activate);
            $b++;
        } else {
            $opponent = ($round + 1) / 2;
            $p2 = $t[$player - 1];
            $p1 = $t[$opponent - 1];

            $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "&nbsp;&nbsp;<a href=\"" . BASE_URL . "profile/{$p1}/\">" . checkClanName($p1) . "</a>");
            $arr[$b][] = array('leagueTdStyle' => 'w50', 'value' => "<a href=\"" . BASE_URL . "profile/{$p2}/\">" . checkClanName($p2) . "</a>");
            saveMatches($round, $p1, $p2, $activate);
            $b++;


        }


        $_SESSION['NOWADD'] = FALSE;
        $_SESSION['one_error'] = FALSE;
    }


    $table = new createTable('adminFullTable', '');
    $table->setTableHead(array(), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}

function setReadyStatus($st, $id)
{

    if (!empty($id)) {
        $check = mysql_Fetch_Array(mysql_query("SELECT count(id),
			(SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$id}') ,
			
			(SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$id}' && pozycja = '1') as p1,
			(SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$id}' && pozycja = '2') as p2 ,
			(SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$id}' && pozycja = '3') as p3 ,
			(SELECT count(id) FROM `-klany_gracze` WHERE id_klanu = '{$id}' && pozycja = '4') as p4 
		FROM `-klany_gracze` WHERE id_klanu = '{$id}' && stanowisko = '1'"));

        //sprawdzam czy jest kapitan
        if (!empty($check[0])) {
            //sprawdzam czy zapisy(mozliwosc zmian) sa otwarte
            $status = mysql_fetch_array(mysql_query("SELECT status FROM `-klany_lista` WHERE id = '" . KLAN_R_ID . "'"));
            if (!empty($status['status'])) {
                //sprawdzam czy ilosc graczy jest ok.
                if ($check[1] >= 4 && $check[1] <= 6) {
                    //sprawdzam czy kazdy zawodnik jest na swojej pozycji
                    //jesli zawodnik chce cofnac gotowosc nie ma znaczenia czy kazdy gracz jest na miejscu
                    if (
                        (!empty($check['p1']) && !empty($check['p2']) && !empty($check['p3']) && !empty($check['p4']) && $st == 1)
                        ||
                        ($st == 0)
                    ) {
                        if (mysql_query("UPDATE `-klany` SET status = '{$st}' WHERE id = '{$id}' ")) {
                            note("Status gotowo�ci zmieniony", "info");
                        } else {
                            note("B��d podczas zmiany gotowo�ci!", "blad");
                        }
                    } else {
                        note("Bledne ustawienie pozycji graczy. 
							1. " . ($check['p1'] ? "jest" : "brak") . "
							2. " . ($check['p2'] ? "jest" : "brak") . "
							3. " . ($check['p3'] ? "jest" : "brak") . "
							4. " . ($check['p4'] ? "jest" : "brak"), "blad");
                    }

                } else {
                    note("Zla liczba [{$check[1]}] graczy", "blad");
                }
            } else {
                note("Nie mo�na zmieni� statusu gotowo�ci poniewa� administrator wy��czy� na chwil� obecn� t� funkcj�", "blad");
            }
        } else {
            note("Klan nie ma kapitana!", "blad");
        }
    } else {
        note("Wszystkie pola s� wymagane", "blad");
    }
}

?>