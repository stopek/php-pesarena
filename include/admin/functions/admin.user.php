<?
//--------------------//
// komunikaty zebrane //
//--------------------//


//funkcje odnosnie ostrzezen
function updateNote($tresc, $id)
{
    if (!empty($tresc) && !empty($id)) {
        if (mysql_query("UPDATE " . TABELA_OSTRZEZENIA . " SET opis='{$tresc}' WHERE id='{$id}';")) {
            note(admsg_updateUserNoteDone, "info");
            przeladuj($_SESSION['starsza']);
        } else {
            note(admsg_updateUserNoteError, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }
}

function addNote($tresc, $id)
{
    if (!empty($tresc) && !empty($id)) {
        if (mysql_query("insert into " . TABELA_OSTRZEZENIA . " values('','{$id}',NOW(),'{$tresc}');")) {
            note(admsg_addUserNoteDone, "info");
            przeladuj($_SESSION['stara']);
        } else {
            note(admsg_addUserNoteError, 'blad');
            przeladuj($_SESSION['stara']);
        }
    } else {
        note(admsg_wFields, "blad");
    }
}

function deleteNote($id)
{
    $sprawdz = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_OSTRZEZENIA . " where id='{$id}'"));
    if ($sprawdz[0] == 1) {
        if (mysql_query("DELETE FROM " . TABELA_OSTRZEZENIA . " where id='{$id}'")) {
            note(admsg_deleteUserNoteDone, "info");
        } else {
            note(admsg_deleteUserNoteError, "blad");
        }
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_deleteUserNoteNoExists, "blad");
    }
}

function showNoteForm($coteraz, $rekord)
{
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_infoUserNoteWhatYouSet . "</li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"cmd\" value=\"1\"/>
				<textarea cols=\"55\" class=\"textarea_a\" rows=\"3\" name=\"tresc\">{$rekord['opis']}</textarea>
				<input type=\"hidden\" name=\"{$coteraz}\" value=\"{$coteraz}\"/>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
			</form>
		</li>
	</ul>";
}


//funkcje odnosnie vip'a
function addVip($id, $logo)
{
    if (!empty($logo)) {
        if (mysql_query("insert into " . TABELA_VIP . " values('','{$id}','" . DEFINIOWANA_GRA . "','{$logo}');")) {
            note(admsg_addVipDone, "info");
        } else {
            note(admsg_addVipError, 'blad');
        }
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_wFields, "blad");
    }
}

function deleteVip($id)
{
    if (!empty($id)) {
        if (mysql_query("DELETE FROM " . TABELA_VIP . " where id_gracza='{$id}'")) {
            note(admsg_deleteVipDone, "info");
            przeladuj($_SESSION['starsza']);
        } else {
            note(admsg_deleteVipError, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }
}


// aktywuje kato gracza
function activeAcount()
{
    $id = (int)$_GET['id'];
    if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET status='2' WHERE id = '{$id}'") &&
        mysql_query("UPDATE " . TABELA_GRACZE . " SET status='1' WHERE id_gracza='{$id}'") &&
        mysql_query("DELETE FROM " . TABELA_UZYTKOWNICY_BAN . " WHERE id_usera = '{$id}'")
    ) {
        note(admsg_activeAcountDone, "info");
    } else {
        note(admsg_activeAcountError, "blad");
    }
}


//banuje kato gracza
function banAcount()
{

    $id = (int)$_GET['id'];
    $zamien = array(
        1 => '+ 15 Minute',
        2 => '+ 30 Minute',
        3 => '+ 60 Minute',
        4 => '+ 1 Hours',
        5 => '+ 9 Hours',
        6 => '+ 12 Hours',
        7 => '+ 1 Days',
        8 => '+ 2 Years',
        9 => '+ 2 Days',
        10 => '+ 4 Days',
        11 => '+ 5 Days',
        12 => '+ 1 Week',
        13 => '+ 2 Week',
        14 => '+ 1 Months',
        15 => '+ 2 Months',
        16 => '+ 3 Months'
    );


    $na_ile = (int)$_POST['na_ile'];
    $powod = czysta_zmienna_post($_POST['powod']);
    if (isset($_POST['zapisz'])) {
        if (!empty($na_ile) && !empty($powod)) {
            $sprawdz_status = mysql_fetch_array(mysql_query("SELECT status FROM " . TABELA_UZYTKOWNICY . " WHERE id='{$id}'"));
            $sprawdz_czy_jest = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_UZYTKOWNICY_BAN . " WHERE id_usera='{$id}' GROUP BY id"));
            if (empty($sprawdz_czy_jest) && $sprawdz_status['status'] != '3') {
                $termin = strtotime($zamien[$na_ile]);
                if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET status='3' WHERE id='{$id}'") &&
                    mysql_query("INSERT INTO " . TABELA_UZYTKOWNICY_BAN . " VALUES('','{$id}',NOW(),'{$powod}','{$termin}')")
                ) {
                    note(admsg_banAcountDone . ": <b>{$zamien[$na_ile]}</b>", "info");
                } else {
                    note(admsg_banAcountError, 'blad');
                }
            } else {
                note(admsg_banAcountBanExists, "blad");
            }
        } else {
            note(admsg_wFields, "blad");
        }
    }


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">Wystaw bana dla tego gracza</li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
				<input type=\"radio\" name=\"na_ile\" value=\"1\">15min. 
				<input type=\"radio\" name=\"na_ile\" value=\"2\">30min. 
				<input type=\"radio\" name=\"na_ile\" value=\"3\">60min. 
				<input type=\"radio\" name=\"na_ile\" value=\"4\">3Godz. 
				<input type=\"radio\" name=\"na_ile\" value=\"5\">9Godz. 
				<input type=radio name=\"na_ile\" value=\"6\">12Godz.
				<input type=radio name=\"na_ile\" value=\"7\">1Dzien 
				<input type=\"radio\" name=\"na_ile\" value=\"8\"><b>Full(+2Lata)</b>
				<input type=radio name=\"na_ile\" value=\"9\">2Dni
				<input type=radio name=\"na_ile\" value=\"10\">4Dni
				<input type=radio name=\"na_ile\" value=\"11\">5Dni				
				<input type=radio name=\"na_ile\" value=\"12\">1Tydzien
				<input type=\"radio\" name=\"na_ile\" value=\"13\">2Tygodnie 
				<input type=\"radio\" name=\"na_ile\" value=\"14\">1Miesiac 
				<input type=\"radio\" name=\"na_ile\" value=\"15\">2Miesiace 
				<input type=\"radio\" name=\"na_ile\" value=\"16\">3Miesiace
				
				<textarea name=\"powod\" class=\"textarea_a\"></textarea>
				<input type=\"hidden\" name=\"zapisz\" value=\"wystaw\"/>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
			</form>
		</li>
	</ul>";

}


function deleteAcount()
{
    $id = $_GET['id'];
    if (mysql_query("DELETE FROM " . TABELA_UZYTKOWNICY . " where id='{$id}'")) {
        note(admsg_deleteAcountDone, 'info');
    } else {
        note(admsg_deleteAcountError, 'blad');
    }
    if (mysql_query("DELETE FROM " . TABELA_GRACZE . " where id_gracza='{$id}'")) {
        note(admsg_deleteProfileDone, 'info');
    } else {
        note(admsg_deleteProfileError, 'blad');
    }
}


function changePassword()
{
    $id = (int)$_GET['id'];
    $a = rand(0, 1000000000);
    $kod = kodowanie_hasla($a);
    if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET haslo='{$kod}' WHERE id = '{$id}'")) {
        note(admsg_changePasswordDone . " <b>{$a}</b>", "info");
    } else {
        note(admsg_changePasswordError, "blad");
    }
}


//zmienia status mozliwosci hostowania
function checkHost()
{
    $status = sprawdz_zgode_na_host((int)$_GET['id']);
    $id = (int)$_GET['id'];
    $daj = $_GET['daj'];
    if ($daj == 'nie') $a = '-'; else $a = '+';
    if ($status == '?') {
        if (mysql_query("insert into " . TABELA_ZGODA_HOST . " values('','{$id}','{$a}')")) {
            note(admsg_checkHostDone, 'info');
        } else {
            note(admsg_checkHostError, "blad");
        }
    } else {
        if (mysql_query("UPDATE " . TABELA_ZGODA_HOST . " SET status='{$a}' WHERE id_gracza = '{$id}'")) {
            note(admsg_checkHostDone, 'info');
        } else {
            note(admsg_checkHostError, "blad");
        }
    }
}

function status_obrazkowy($status)
{
    switch ($status) {
        case 1:
            $obrazek = "<a href=\"#\" class=\"i-access-denied\"></a>";
            break;
        case 2:
            $obrazek = "<a href=\"#\" class=\"i-access-allow\"></a>";
            break;
        case 3:
            $obrazek = "<a href=\"#\" class=\"i-user-baned\"></a>";
            break;
    }


    return $obrazek;
}


function editAcount()
{
    $id = (int)$_GET['id'];
    $mail = czysta_zmienna_post($_POST['mail']);
    $gg = czysta_zmienna_post($_POST['gg']);
    $pseudo = czysta_zmienna_post($_POST['pseudo']);

    if (!empty($_POST['zapisz'])) {
        if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET mail='{$mail}', gadu='{$gg}', pseudo='{$pseudo}' WHERE id='{$id}'")) {
            note(admsg_editUserDone, 'info');
        } else {
            note(admsg_editUserError, 'blad');
        }
    }


    $data = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE id = '{$id}'"));
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_infoEditUserNow . " <b>" . sprawdz_login_id($_GET['id']) . "</b></li>
		<li class=\"glowne_bloki_zawartosc\">
				<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"zapisz\" value=\"zapisz\"/>
				<fieldset class=\"adminAccessList\">
					<ul class=\"adminAccessOption adminFields\">
						<li><input type=\"text\" name=\"pseudo\" value=\"{$data['pseudo']}\"/><span>" . admsg_pseudo . "</span></li>
						<li><input type=\"text\" name=\"mail\" value=\"{$data['mail']}\"/><span>" . admsg_mail . "</span></li>
						<li><input type=\"text\" name=\"gg\" value=\"{$data['gadu']}\"/><span>" . admsg_gg . "</span></li>
						<li><span></span></li>
					</ul>
				</fieldset>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
				</form>
		</li>
	</ul>";


}

?>