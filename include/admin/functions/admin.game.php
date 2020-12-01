<?
//--------------------//
// komunikaty zebrane //
//--------------------//
function addVersionGame($nazwa, $skrot, $logo)
{
    $pathinfo = pathinfo($logo);
    $rozszerzenie = $pathinfo['extension'];
    $mozliwe = array("jpg", "png", "gif");

    echo $skrot . "-" . $nazwa . "-" . $logo;
    if (!empty($skrot) && !empty($nazwa) && !empty($logo)) {
        if (in_array(strtolower($rozszerzenie), $mozliwe)) {
            $src = "grafiki/game/" . basename($logo);
            if (!file_exists($src)) {
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $src)) {
                    note(admsg_ImageAdded, "info");
                    $zlicz = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_GAME . " WHERE skrot='{$skrot}'"));
                    if (empty($zlicz[0])) {
                        if (mysql_query("INSERT INTO " . TABELA_GAME . " values('','{$skrot}','{$nazwa}','grafiki/game/{$logo}','0')")) {
                            note(admsg_addVersionGameDone, 'info');
                        } else {
                            note(admsg_addVersionGameError, 'blad');
                        }
                    } else {
                        note(admsg_addVersionGameExists, "blad");
                    }
                } else {
                    note(admsg_addVersionGameImageError, "blad");
                }
            } else {
                note(admsg_addVersionGameExists, "blad");
            }
        } else {
            note(admsg_ImageBadFileType, "blad");

        }
    } else {
        note(admsg_wFields, "blad");
    }
}


function editVersionGame($nazwa, $id)
{
    if (!empty($nazwa)) {
        if (mysql_query("UPDATE " . TABELA_GAME . " SET nazwa='{$nazwa}' WHERE id='{$id}'")) {
            note(admsg_editVersionGameDone, 'info');
        } else {
            note(admsg_editVersionGameError, 'blad');
        }
    }
}


function deleteVersionGame($id)
{
    if (!empty($id)) {
        if (mysql_query("DELETE FROM " . TABELA_GAME . " WHERE id='{$id}'")) {
            note(admsg_deleteVersionGameDone, 'info');
        } else {
            note(admsg_deleteVersionGameError, 'blad');
        }
    } else {
        note(admsg_wFields, "blad");
    }
}

function changeVersionGame($id, $action)
{
    if ($action == 'hidden' && !empty($id)) {
        if (mysql_query("UPDATE " . TABELA_GAME . " SET view='0' WHERE id='{$id}'")) {
            note(admsg_hideVersionGameDone, 'info');
        } else {
            note(admsg_hideVersionGameError, 'blad');
        }
    } elseif ($action == 'show' && !empty($id)) {
        if (mysql_query("UPDATE " . TABELA_GAME . " SET view='1' WHERE id='{$id}'")) {
            note(admsg_showVersionGameDone, 'info');
        } else {
            note(admsg_showVersionGameError, 'blad');
        }
    } else {
        note(admsg_wFields, 'blad');
    }
}


function endParty($r_id, $lista)
{
    if (mysql_query("UPDATE {$lista} SET status='4' WHERE id='{$r_id}' && vliga='" . WYBRANA_GRA . "'")) {
        note(admsg_partyEndedDone, "info");
    } else {
        note(admsg_partyEndedError, "blad");
    }
}


function zapisz_ustawienie_gra($key, $value, $gdzie)
{
    if (!empty($key) && !empty($value)) {
        $sprawdz_czy_jest = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} WHERE klucz='{$key}' && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'"));
        if (empty($sprawdz_czy_jest)) {
            if (mysql_query("INSERT INTO {$gdzie} VALUES('','{$key}','{$value}','" . WYBRANA_GRA . "','" . R_ID . "')")) {
                note(admsg_addSettingsKeyDone . " <strong>\${$key}={$value}</strong>", "info");
            } else {
                note(admsg_addSettingsKeyError . " <strong>\${$key}={$value}</strong>", "blad");
            }
        } else {
            if (mysql_query("UPDATE {$gdzie} SET wartosc='{$value}' WHERE klucz='{$key}' && vliga='" . WYBRANA_GRA . "' && r_id='" . R_ID . "'")) {
                note(admsg_editSettingsKeyDone . " <strong>\${$key}={$value}</strong>", "info");
            } else {
                note(admsg_editSettingsKeyError . " <strong>\${$key}={$value}</strong>", "blad");
            }
        }
    }
}


// rozpoczyna zapisy do danej rozgrywki
// gdzie(nazwa tabeli z lista rozgrywki)
// czego(nazwa komorki w ustawieniach)
function rozpoczynanie_zapisow($lista, $gracze)
{
    require_once('include/functions/function.admin-druzyny.php');

    // mozliwosc rozpoczynania rozgrywek
    if (!empty($_POST['uruchom'])) {
        mozliwosc_ropoczynania_rozgrywki(czysta_zmienna_post($_POST['nazwa']), czysta_zmienna_post($_POST['gl_opiekun']), $lista);
    }
    // mozliwosc rozpoczynania rozgrywek - END

    //daje mozliwosc wylaczania zapisow rozgrywek
    if (!empty($_POST['edytuj'])) {
        mozliwosc_edycji_rozgrywki(czysta_zmienna_post($_POST['nazwa']), czysta_zmienna_post($_POST['gl_opiekun']), $lista, (int)$_GET['ed_id']);
    }
    //daje mozliwosc wylaczania zapisow rozgrywek - END


    // daje mozliwosc usuwania rozgrywek w przypadku np niewypalu!! zawsze przed rozpoczeciem
    if (!empty($_GET['usun'])) {
        mozliwosc_usunienia_rozgrywki((int)$_GET['usun'], $gracze, $lista);
    }
    // daje mozliwosc usuwania rozgrywek w przypadku np niewypalu!! zawsze przed rozpoczeciem - END

    //daje mozliwosc wylaczania zapisow rozgrywek
    if (!empty($_GET['wylacz'])) {
        mozliwosc_wylaczania_zapisow($lista, (int)$_GET['wylacz']);
    }
    //daje mozliwosc wylaczania zapisow rozgrywek - END

    //daje mozliwosc przywracania zapisow jesli istnieje potrzeba(tylko jesli mecze niezostaly wygenerowane(status=S)
    if (!empty($_GET['przywroc'])) {
        mozliwosc_przywracania_zapisow($lista, (int)$_GET['przywroc']);
    }
    //daje mozliwosc przywracania zapisow jesli istnieje potrzeba(tylko jesli mecze niezostaly wygenerowane(status=S) -  END

    //daje mozliwosc wylaczania zapisow rozgrywek
    if (!empty($_GET['change_team'])) {
        mozliwosc_ustawiania_druzyny_gry((int)$_GET['change_team'], $lista);
    }
    //daje mozliwosc wylaczania zapisow rozgrywek - END

    if (WYBRANA_GRA) {
        if (!in_array(WYBRANA_GRA, explode(',', LIGA_U_A))) {

            $edycja_id = (int)$_GET['ed_id'];
            $metoda = ($edycja_id ? "edytuj" : "uruchom");

            $rekord = mysql_fetch_array(mysql_query("SELECT nazwa,opiekun FROM {$lista} WHERE id='{$edycja_id}'"));


            print "<ul class=\"glowne_bloki\">
				<li class=\"glowne_bloki_naglowek\">" . admsg_newPartyStart . "</li>
				<li class=\"glowne_bloki_zawartosc\">
					<form method=\"post\" action=\"\">
					<input type=\"hidden\" name=\"{$metoda}\" value=\"1\"/>
						<fieldset class=\"adminAccessList\">
							<ul class=\"adminAccessOption adminFields\">
								<li><input type=\"text\" name=\"gl_opiekun\" value=\"{$rekord['opiekun']}\"/><span>" . admsg_homeAdmin . "</span></li>
								<li><input type=\"text\" name=\"nazwa\" value=\"{$rekord['nazwa']}\"/><span>" . admsg_partysname . "</span></li>
							</ul>
						</fieldset>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
					</form>
				</li>
			</ul>";


            wyswietl_listy_rozgrywek($lista, $gracze);


            echo "<ul class=\"glowne_bloki\">
				<li class=\"glowne_bloki_naglowek legenda\"><span>" . admsg_legenda . "</span></li>
				<li class=\"glowne_bloki_zawartosc\">
					<ul class=\"legendaUL\">
						<li class=\"c2\"><a href=\"#\" class=\"i-select-team\"></a>" . admsg_setTeamToPlay . "</li>
						<li class=\"c1\"><a href=\"#\" class=\"i-delete\"></a>" . admsg_usunImprezy . "</li>
						<li class=\"c2\"><a href=\"#\" class=\"i-stop\"></a>" . admsg_turnoffPlay . "</li>
						<li class=\"c1\"><a href=\"#\" class=\"i-play\"></a>" . admsg_turnonPlay . "</li>
						<li class=\"c2\"><a href=\"#\" class=\"i-edit\"></a>" . admsg_edytujImpreze . "</li>
						<li class=\"c1\"><a href=\"#\" class=\"i-go\"></a>" . admsg_goToPlay . "</li>
					</ul>
					<div class=\"description\"><span>" . admsg_des_3 . "</span></div>
				</li>
			</ul>";

        } else {
            note(gameDenied, "blad");
        }
    }
} // rozpoczyna zapisy do danej rozgrywki - END


function wyswietl_listy_rozgrywek($lista, $gracze)
{
    require_once('include/admin/funkcje/function.table.php');
    $menu_z = array('opcja=17', 'opcja=26', 'opcja=32');
    $menu_na = array('opcja=7', 'opcja=21', 'opcja=29');
    $show = array(26, 17, 32);

    $sqlAdd = ($_GET['show'] == 'all' ? "" : " && status!='4'");
    $buttonClicked = ($_GET['show'] == 'all' ? "showOnce" : "showAll");
    $infoFieldset = ($_GET['show'] == 'all' ? "wszystkie" : "tylko aktualne");
    $infoLink = ($_GET['show'] == 'all' ? "actual" : "all");

    $sql = mysql_query("SELECT d.nazwa,l.nazwa,l.opiekun,l.data,l.status,l.id	FROM {$lista} l
	LEFT JOIN " . TABELA_DRUZYNY_KATEGORIE . " d ON l.id_kategori = d.id WHERE l.vliga='" . WYBRANA_GRA . "' {$sqlAdd}  ORDER BY id DESC");
    $b = 1;
    while ($rek = mysql_fetch_array($sql)) {


        $arr[$b][] = $b++;
        $arr[$b][] = $rek[1];
        $arr[$b][] = $rek[2];
        $arr[$b][] = formatuj_date($rek[3]);
        $arr[$b][] = (!empty($rek[0]) || $rek[4] == '4' ? skroc_text($rek[0], 40) : "<strong>" . admsg_selectKategory . "</strong>");
        $arr[$b][] = zamien_status_rozgrywek($rek[4]);
        $arr[$b][] =
            (in_array($_GET['opcja'], $show) ? "
						<a href=\"" . AKT . "&usun={$rek[5]}\" onclick=\"return confirm('" . admsg_confirmQuestion . "')\" class=\"i-delete\"></a>
						<a href=\"" . AKT . "&ed_id={$rek[5]}\" class=\"i-edit\"></a>
						<a href=\"" . AKT . "&change_team={$rek[5]}\" class=\"i-select-team\"></a>
				" . (empty($rek['status']) ? "
					<a href=\"" . AKT . "&wylacz={$rek[5]}\" class=\"i-stop\"></a>" : "
					<a href=\"" . AKT . "&przywroc={$rek[5]}\" class=\"i-play\"></a>"
                ) : null
            ) .
            "<a href=\"" . str_replace($menu_z, $menu_na, AKT) . "&r_id={$rek[5]}\" class=\"i-go\"></a>
			";
    }


    $table = new createTable('adminFullTable', "Aktualnie rozgrywki z tej kategori, wyswietl: <strong>{$infoFieldset}</strong>");
    $table->setTableHead(array('', admsg_partysname, admsg_homeAdmin, admsg_startPartyDate, admsg_teamToPlay, admsg_statusRozgrywki, admsg_opcja), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();

    echo "<div class=\"smallField\">
		<div>
			<fieldset><legend>Tryb wyswietlania</legend>
				<ul>
					<li class=\"{$buttonClicked}\"><a href=\"" . AKT . "&show={$infoLink}\"></a></li>
				</ul>
			</fieldset>
		</div>
	</div>";


}


function mozliwosc_ustawiania_druzyny_gry($r_id, $gdzie)
{
    $p = (int)$_POST['p'];
    if (isset($_POST['ustaw'])) {
        if (!empty($p)) {
            if (mysql_query("UPDATE {$gdzie} SET id_kategori = '{$p}' WHERE id='{$r_id}'")) {
                note(admsg_kategoryAddToParty, "info");
                przeladuj($_SESSION['starsza']);
            } else {
                note(admsg_kategoryAddToPartyError, "blad");
            }
        } else {
            note(admsg_noKategoryId, "blad");
        }
    }


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_selectTeamToPlay . "</li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
			" . M432 . ": " . select_kategorie("onchange=\"submit()\"", "k") . " 
			" . select_podkategorie("", "p", (int)$_POST['k']) . "
			<input type=\"submit\" name=\"ustaw\" value=\"" . admsg_ustaw . "\"/>
			</form>
		</li>
	</ul>";


}


function mozliwosc_przywracania_zapisow($lista, $id)
{
    if (mysql_query("UPDATE {$lista} SET status='0' WHERE vliga='" . WYBRANA_GRA . "' && id='{$id}' && status='S';")) {
        note(admsg_zapisy_przywrocone, "info");
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_zapisy_przywroconeError, "blad");
        przeladuj($_SESSION['stara']);
    }
}


// mozliwosc rozpoczynanai rozgrywek 
function mozliwosc_ropoczynania_rozgrywki($nazwa, $opiekun, $lista)
{
    if (!empty($nazwa) && !empty($opiekun)) {
        if (mysql_query("INSERT INTO {$lista} values('',NOW(),'{$nazwa}','{$opiekun}','','','','','0','" . WYBRANA_GRA . "','')")) {
            note(admsg_newPartyStartedDone, "info");
            przeladuj($_SESSION['stara']);
        } else {
            note(admsg_newPartyStartedError, 'blad');
        }
    } else {
        note(admsg_wFields, "blad");
    }
} // mozliwosc rozpoczynanai rozgrywek - END


// daje mozliwosc usuniecia rozgrywek wraz z zapisanymy do niej graczami 
function mozliwosc_usunienia_rozgrywki($id, $gracze, $lista)
{
    $s = mysql_fetch_row(mysql_query("SELECT * FROM {$lista} WHERE vliga='" . WYBRANA_GRA . "' && id='{$id}';"));
    $status = $s['status'];
    if (empty($status)) {
        mysql_query("DELETE FROM {$gracze} WHERE  vliga='" . WYBRANA_GRA . "' && id_r='{$id}'");
        mysql_query("DELETE FROM {$lista} WHERE  vliga='" . WYBRANA_GRA . "'  && id='{$id}'");
        note(admsg_partyIsDeletedDone, 'info');
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_artyIsDeletedError, "blad");
    }
} // daje mozliwosc usuniecia rozgrywek wraz z zapisanymy do niej graczami  - END

//daje mozliwosc wylaczania zapisow do rozgrywek
function mozliwosc_wylaczania_zapisow($lista, $id)
{
    if (mysql_query("UPDATE {$lista} SET status='S' WHERE vliga='" . WYBRANA_GRA . "' && id='{$id}';")) {
        note(admsg_partyStatusStop, "info");
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_partyStatusStopError, "blad");
        przeladuj($_SESSION['stara']);
    }
} //daje mozliwosc wylaczania zapisow do rozgrywek - END


//daje mozliwosc wylaczania zapisow do rozgrywek
function mozliwosc_edycji_rozgrywki($nazwa, $opiekun, $lista, $id)
{
    if (mysql_query("UPDATE {$lista} SET nazwa='{$nazwa}',opiekun='{$opiekun}' WHERE vliga='" . WYBRANA_GRA . "' && id='{$id}';")) {
        note(admsg_editPartyStatusDone, "info");
        przeladuj($_SESSION['starsza']);
    } else {
        note(admsg_editPartyStatusError, "blad");
        przeladuj($_SESSION['stara']);
    }
} //daje mozliwosc wylaczania zapisow do rozgrywek - END

