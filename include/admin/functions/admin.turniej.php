<?


function makeGenerationButton($changeT)
{

    return "<div class=\"leagueStatus\">Generowanie: 
		<span>
			" . (empty($changeT[0]) && empty($changeT[1]) ? "<a href=\"" . AKT . "&podziel=1\" class=\"i-start-party\"></a>" : "nie ma zgody na generowanie") . "
		</span>
	</div>";
}


function changeTournament($st, $et, $ikosz, $ikolej, $dr, $team, $do_l)
{
    $noErrorEl = $noErrorTe = true;
    if ($st == 1) {
        if ($et == 2) {
            if (is_numeric($ikolej)) {
                $a = $do_l % $ikosz;

                if ($a == 0 && $ikolej < $ikosz) {
                    $noErrorEl = false;
                } elseif ($ikolej < $ikosz && $ikosz % 2 == 0 && $a == 0) {
                    $noErrorEl = false;
                } elseif ($ikolej % 2 == 0 && $ikolej > 1 && ($ikolej < $ikosz)) {
                    $noErrorEl = false;
                }
            }
        } else {
            $noErrorEl = false;
        }
    } else {
        $noErrorEl = false;
    }


    if ($team == 1) {
        if (!empty($dr)) {
            if (count(explode(',', $dr)) >= (floor($do_l / $ikosz))) {
                $noErrorTe = false;
            }
        }

    } else {
        $noErrorTe = false;
    }
    return array($noErrorEl, $noErrorTe);
}


function changeT($config, $do_l)
{


    $noErrorEl = $noErrorTe = true;
    if ($config['start_type'] == 1) {
        if ($do_l > 32) {
            if ($config['elimination_type'] == 2) {
                if (is_numeric($config['ilosc_kolejek'])) {
                    $a = $do_l % $config['ilosc_koszy'];

                    if ($a == 0 && $config['ilosc_kolejek'] < $config['ilosc_koszy']) {
                        $noErrorEl = false;
                    } elseif ($config['ilosc_kolejek'] < $config['ilosc_koszy'] && $config['ilosc_koszy'] % 2 == 0 && $a == 0) {
                        $noErrorEl = false;
                    } elseif ($config['ilosc_kolejek'] % 2 == 0 && $config['ilosc_kolejek'] > 1 && ($config['ilosc_kolejek'] < $config['ilosc_koszy'])) {
                        $noErrorEl = false;
                    }
                }
            } else {
                $noErrorEl = false;
            }
        } else {
            $noErrorEl = false;
        }
    } elseif ($config['start_type'] == 2) {
        if ($do_l == 32) {
            $noErrorEl = false;
        }
    } elseif ($config['start_type']) {
        if ($do_l > 16) {
            if ($config['elimination_type'] == 2) {
                if (is_numeric($config['ilosc_kolejek'])) {
                    $a = $do_l % $config['ilosc_koszy'];

                    if ($a == 0 && $config['ilosc_kolejek'] < $config['ilosc_koszy']) {
                        $noErrorEl = false;
                    } elseif ($config['ilosc_kolejek'] < $config['ilosc_koszy'] && $config['ilosc_koszy'] % 2 == 0 && $a == 0) {
                        $noErrorEl = false;
                    } elseif ($config['ilosc_kolejek'] % 2 == 0 && $config['ilosc_kolejek'] > 1 && ($config['ilosc_kolejek'] < $config['ilosc_koszy'])) {
                        $noErrorEl = false;
                    }
                }
            } else {

                $noErrorEl = false;
            }
        }
    }


    if ($team == 1) {
        if (!empty($dr)) {
            if (count(explode(',', $dr)) >= (floor($do_l / $config['ilosc_koszy']))) {
                $noErrorTe = false;

            }
        }

    } else {
        $noErrorTe = false;
    }


    $noErrorEl = $noErrorTe = false;
    return array($noErrorEl, false);
}


function wrzuc_gracz_do_kosza_turniej($t, $id_gracza, $error, $st)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "'"));
    if ($licz >= conf_minUsersToTournament) {
        if ($st['status'] == 'S' || $st['status'] == 'P') {
            if (!mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='" . ($t + 1) . "' WHERE id_gracza='{$id_gracza}' && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'")) {
                note("Nie mozna bylo zmienic statusu graczowi: " . ($t + 1) . " <b>" . sprawdz_login_id($id_gracza) . "</b>", "blad");

            } else {
                $error['confirm'] = TRUE;
            }

        } else {
            $error['error'] = TRUE;
        }
    } else {
        $error['count'] = TRUE;
    }
    return $error;
}


function zakoncz_eliminacje_turniej($ma_przejsc, $status, $do_konca)
{

    //tablica przechodza[] z formularza
    $zrodlo = $_POST['przechodza'];
    $ile = count($zrodlo);
    if ($ile == $ma_przejsc && empty($do_konca)) {
        //tworze tablice2 z tablicy uzywajac foreach
        foreach ($zrodlo as $gracze) {
            $zrodlo2[] = (int)$gracze;
        }

        $s = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'");
        $u = $uu = 1;
        while ($rek = mysql_fetch_array($s)) {
            //uzywajac funkci wyklucz_gracza wykluczam gracza
            if (!in_array($rek['id_gracza'], $zrodlo2)) {
                wyklucz_gracza(TABELA_TURNIEJ_GRACZE, $rek['id_gracza'], R_ID);
            } else {
                echo ($uu++) . ") <font color=\"green\">Zostawiam gracza: <b>" . linkownik('profil', $rek['id_gracza'], '') . "</b></font><br>";
            }
        }
        if (mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='{$status}' WHERE id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "'")) {
            note('Eliminacje do turnieju zakonczone!', 'info');
        } else {
            note('Wystapil blad podczas zmiany statusu turnieju!', 'blad');
        }

    } else {
        note("Zla liczba zaznaczonych graczy : <b>{$ile}</b>! Zaznacz {$ma_przejsc}. zawodnikow, lub nie wszystkie mecze zostaly zakonczone", "blad");
    }
    przeladuj($_SESSION['stara']);
}


//przypisuje graczom odpowiednie grupy
//aktualnie gracze sa podzieleni na kosze
//zmiana automatyczna - problem wygasajacej sesji							
function turniej_zapisz_status_grupy_gracza_jeden($dr)
{
    //zmienia status turnieju
    //wykonywane po kliknieciu w link
    //faza ostateczna - najperw nalezy WSZYSTKICH graczy
    //przypisac do opowiednich grup
    if (!empty($_GET['zmien_status'])) {
        if (mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='G' WHERE  vliga='" . WYBRANA_GRA . "' && id='" . R_ID . "'")) {
            note("Status zmieniony", "info");
            unset($_SESSION['zmiany_pamietaj']);
        } else {
            note("Status niezmieniony! BLAD", "blad");
        }
    }

    //zapisywanie danego gracza do odpowieniej grupy
    if (!empty($_POST['zapisz'])) {
        $id_gracza = (int)$_POST['id_gracza'];
        $status = (int)$_POST['grupa'];
        $klub = (int)$_POST['klub'];
        if (mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='{$status}',klub='{$klub}' WHERE  vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "' && id_gracza='{$id_gracza}'")) {
            note("Status dla gracza <b>" . sprawdz_login_id($id_gracza) . "</b> zostal zapisany!", "info");
            $_SESSION['zmiany_pamietaj'][] = $id_gracza;

        } else {
            note("Blad przy zapisywaniu statusu gracza!", "blad");
        }
    }
    $s = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'");
    $b = 1;


    note("<a href=\"" . AKT . "&zmien_status=1\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\">Zmien stlatus</a>", "fieldset");

    if (count($_SESSION['zmiany_pamietaj']) == 0) {
        $_SESSION['zmiany_pamietaj'] = array();
    }
    while ($rek = mysql_fetch_array($s)) {
        $createOption = null;
        $druzyny = explode(',', $dr);
        foreach ($druzyny as $id_d) {
            $nazwa = mysql_fetch_array(mysql_query("SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id='{$id_d}'"));
            $createOption .= "<option value=\"{$id_d}\" " . ($rek['klub'] == $id_d ? "selected" : null) . ">" . $nazwa['nazwa'];
        }

        $createForm = "
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"id_gracza\" value=\"{$rek['id_gracza']}\"/>
				<input type=\"text\" size=\"2\" name=\"grupa\" value=\"{$rek['status']}\"/>
				<select name=\"klub\">{$createOption}</select>
				<input type=\"submit\" name=\"zapisz\" value=\"" . admsg_save . "\"/>
			</form>";

        $arr[$b][] = $b++;
        $arr[$b][] = $createForm;
        $arr[$b][] = "<a class=\"" . (in_array($rek['id_gracza'], $_SESSION['zmiany_pamietaj']) ? "i-access-allow" : "i-access-denied") . "\"></a>";
        $arr[$b][] = mini_logo_druzyny($rek['klub']);
        $arr[$b][] = linkownik('profil', $rek['id_gracza'], '');
    }


    require_once('include/admin/funkcje/function.table.php');
    $table = new createTable('adminFullTable', 'Tabela z meczami glownymi turnieju');
    $table->setTableHead(array('', 'grupa druzyna zapisz', admsg_info, admsg_klub, admsg_pseudo), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}


function turniej_zapisz_status_grupy_gracza_wszyscy($graczy_w_koszu, $ile_koszy, $do_l, $dr, $losowanieDruzyn)
{

    echo "<div id=\"moreOptionGenerateTournament\">
		<ul>
			<li class=\"lCG\"><a href=\"" . AKT . "&podziel=grupy\" onclick=\"return confirm ('" . admsg_confirmQuestion . "'); \"></a></li>
		</ul>
	</div>";


    $graczy_w_koszu = floor($do_l / $ile_koszy);

    for ($ia = 1; $ia <= $ile_koszy; $ia++) {
        $b++;
        $arr[$b][] = "<span class=\"centerT\"><img src=\"grafiki/admin_ikons/bin.png\" alt=\"\"/> <br/> <h2>Kosz: {$ia}</h2></span>";
        $arr[$b][] = gracze_kosza($ia, $graczy_w_koszu, $dr, $losowanieDruzyn);
    }


    require_once('include/admin/funkcje/function.table.php');
    $table = new createTable('adminFullTableOF');
    $table->setTableHead(array('Kosz', 'Gracze'), "adminHeadersClass-silver");
    $table->setTableBody($arr);
    echo $table->getTable();


}


//dzieli graczy na grupy - tworzy wpisy w tabeli turniej_temp
//$stat -> status dla kolejnej fazy
//G -> faza utworzenie g
function podziel_grupy_turniej($stat, $losowanieDruzyn)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "'"));
    if ($licz >= 16) {

        $sql = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_TEMP_TABLE . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "' ORDER BY grupa");
        while ($rek = mysql_fetch_array($sql)) {
            $zmien_druzyny_graczom = ($losowanieDruzyn == 1 ? ",klub='{$rek['klub']}' " : null);
            $zm_dr_gr = ($losowanieDruzyn == 1 ? " + <b>zmienilem graczowi druzyne</b> " : " + <b>druzyna</b> pozostaje <b>bez zmian</b> ");
            if (!mysql_query("UPDATE " . TABELA_TURNIEJ_GRACZE . " SET status='{$rek['grupa']}' {$zmien_druzyny_graczom} WHERE id_gracza='{$rek['id_gracza']}'")) {
                note("Wystapil blad podczas dodawania do tymczasowej tabeli", "blad");
                $error['error'] = TRUE;
            } else {
                note("Gracz: <b>" . sprawdz_login_id($rek['id_gracza']) . "</b> zostal poprawnie przypisany do  grupy: <b>{$rek['grupa']}</b> {$zm_dr_gr} ", "info");
            }
            $temp = TRUE;
        }
        if (empty($temp)) {
            note("Brak graczy w tabeli tymczasowej", "blad");
        } else {
            if (!empty($error['error'])) {
                note("Przy zmianie statusu grup graczy wystapil blad! Turniej nie przeszedl do nastepnej fazy", "blad");
            } else {
                if (!mysql_query("UPDATE " . TABELA_TURNIEJ_LISTA . " SET status='{$stat}' WHERE id='" . R_ID . "'")) {
                    note('Blad przy zmianie statusu rozgrywki', 'blad');
                } else {
                    note("Brak bledow - Gracze przypisani do swoich grup, mozesz wygenerowac mecze!", "info");
                }
            }
        }
    } else {
        note("turniej nie wystartuje z <b>{$licz}</b> graczami! - za malo", "blad");
    }
}


//tabela wyswietlajaca statyskiki graczy
//uzywana przy akceptacji wynikow eliminacji turnieju
//zawiera checkboxy z tablica graczy zaznaczonych do przejscia dalej
function turniej_mecze_eliminacji_tabela_admin($grupa, $typ, $selectPlayer)
{
    define(R_ID_T_ADMIN, TRUE);
    require_once('include/functions/function.turniej.php');
    require_once('include/admin/funkcje/function.table.php');


    $a = wrzuc_graczy_turniejowych($grupa, $typ);
    if (count($a) != 0) {
        $srednio_po = floor($selectPlayer / MAX_GRUPA);
        $sortuj = sortowanie_d_d_a();
        sortx($a, $sortuj);
        $b = 1;

        while (list($key, $value) = each($a)) {


            $data = mysql_fetch_array(mysql_query("SELECT *,
					(SELECT count(id) FROM " . TABELA_TURNIEJ . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "' && (n1='{$value['name']}' || n2='{$value['name']}') && spotkanie='{$typ}') as counterAll,
					(SELECT count(id) FROM " . TABELA_TURNIEJ . " WHERE r_id='" . R_ID . "' && status='3' && vliga='" . WYBRANA_GRA . "' && (n1='{$value['name']}' || n2='{$value['name']}') && spotkanie='{$typ}') as counterAllEnded
					
				FROM " . TABELA_TURNIEJ . " WHERE r_id='" . R_ID . "' && vliga='" . WYBRANA_GRA . "'"));


            $arr[$b][] = $b++;
            $p++;
            $arr[$b][] = linkownik('profil', (int)$value['name'], '');
            $arr[$b][] = $value['num1'];
            $arr[$b][] = $value['num2'];
            $arr[$b][] = $value['num3'];
            $arr[$b][] = @round($data['counterAllEnded'] / $data['counterAll'] * 100, 2) . "%";
            $arr[$b][] = "<input type=\"checkbox\" name=\"przechodza[]\" " . ($p <= $srednio_po ? "checked" : null) . " value=\"" . $value['name'] . "\"/>";
        }
    } else {
        note(M483, "blad");
    }

    $table = new createTable('adminFullTable center', 'Tabela z meczami glownymi turnieju');
    $table->setTableHead(array('', admsg_pseudo, admsg_bramki, '+/-', admsg_pkt, 'rozegral %', 'zaznacz'), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();

}


function losuj_liczbe($ilosc, $kosz)
{
    if (empty($_SESSION[$kosz])) $_SESSION[$kosz] = array();

    if (count($_SESSION[$kosz]) == $ilosc) {
        return 1;
    } else {
        while (!$ok) {

            $a = rand(1, $ilosc);
            if (!in_array($a, $_SESSION[$kosz])) {
                $ok = TRUE;

            }
        }
        $_SESSION[$kosz][] = $a;
        return $a;
    }
}

function losuj_druzyne($ilosc, $kosz)
{
    if (empty($_SESSION['d'][$kosz])) $_SESSION['d'][$kosz] = array();
    if (count($_SESSION['d'][$kosz]) == $ilosc) {
        return 1;
    } else {
        while (!$ok) {

            $a = rand(1, $ilosc);
            if (!in_array($a, $_SESSION['d'][$kosz])) {
                $ok = TRUE;

            }
        }
        $_SESSION['d'][$kosz][] = $a;
        return $a;
    }
}

function gracze_kosza($kosz, $na_ile, $dr, $losowanieDruzyn)
{
    $druzyny = explode(',', $dr);

    mysql_query("DELETE FROM " . TABELA_TURNIEJ_TEMP_TABLE . " WHERE kosz='{$kosz}' && r_id='" . R_ID . "'");
    $_SESSION['save'][$kosz] = array();
    $z = mysql_query("SELECT * FROM " . TABELA_TURNIEJ_GRACZE . " where status='{$kosz}' && r_id='" . R_ID . "';");
    while ($as2 = mysql_fetch_array($z)) {
        $temp++;


        //losuje dla swojego kosza  niepowtarzajaca sie grupe
        //jesli aktualne temp jest wieksze od standardowej liczby graczy
        //w koszu to robie jakby kolejna grupe dla ktorej moge losowac kolejne wartosci
        //
        if ($temp > $na_ile)
            $grupa[$kosz] = losuj_liczbe($na_ile, $kosz + 1);
        else
            $grupa[$kosz] = losuj_liczbe($na_ile, $kosz);


        //losuje dla swojego kosza niepowtarzajaca sie druzyne
        if ($losowanieDruzyn == 1) {

            if ($temp > $na_ile)
                $druzyna[$kosz] = losuj_druzyne($na_ile, $kosz + 1);
            else
                $druzyna[$kosz] = losuj_druzyne($na_ile, $kosz);

            $moj = $druzyny[$druzyna[$kosz] - 1];
        } else {
            $druzyna[$kosz] = sprawdz_moj_klub_w_grze($as2['id_gracza'], TABELA_TURNIEJ_GRACZE, R_ID);
            $moj = $druzyna[$kosz];
        }


        //wrzucam tymczasowo do bazy danych dane kto gdzie trafil
        //
        if (!mysql_query("INSERT INTO " . TABELA_TURNIEJ_TEMP_TABLE . " VALUES ('','{$as2['id_gracza']}','{$grupa[$kosz]}','{$kosz}','" . WYBRANA_GRA . "','" . R_ID . "','{$moj}')")) {
            note("Wystapil blad podczas dodawania do tymczasowej tabeli", "blad");
        }


        $nazwa = mysql_fetch_array(mysql_query("SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id='{$moj}'"));
        $podsumowanie .= "Gracz: " . sprawdz_login_id($as2['id_gracza']) . " Trafia do grupy: {$grupa[$kosz]} Dru�yna " . ($losowanieDruzyn == 1 ? "wylosowana" : "jaka mial") . ": {$nazwa['nazwa']}\n";

        $ret .= "<fieldset class=\"groupInTournament\">
				<span>{$temp}). Gracz: <strong>" . linkownik('profil', $as2['id_gracza'], '') . "</strong>
					<span>Trafia do grupy: <b>{$grupa[$kosz]}</b>  
						<span>{$nazwa['nazwa']}" . mini_logo_druzyny($moj) . "</span>
					</span>
				</span>
		</fieldset>";

    }
    $ret .= "<fieldset class=\"groupInTournament\"><textarea>\t\t KOSZ {$kosz} \n{$podsumowanie}</textarea></fieldset>";
    unset($_SESSION[$kosz]);
    unset($_SESSION[$kosz + 1]);
    unset($_SESSION['d'][$kosz]);
    unset($_SESSION['d'][$kosz + 1]);
    return $ret;
}


// generuje mecze dla turnieju


?>
