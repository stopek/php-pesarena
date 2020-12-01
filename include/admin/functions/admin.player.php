<?
//--------------------//
// komunikaty zebrane //
//--------------------//
function mozliwosc_zamiany_gracza($z, $na, $klub, $r_id, $gracze, $gdzie, $whatWithMatch)
{
    if (!empty($z) && !empty($na) && !empty($klub)) {
        if (mysql_query("UPDATE {$gracze} SET id_gracza='{$na}',klub='{$klub}' where id_gracza='{$z}' && r_id='{$r_id}';") &&
            mysql_query("UPDATE {$gdzie} SET  n1='{$na}',klub_1='{$klub}' WHERE n1='{$z}'  " . ($whatWithMatch == 1 ? "&& status!='3'" : null) . ";") &&
            mysql_query("UPDATE {$gdzie} SET  n2='{$na}',klub_2='{$klub}' WHERE n2='{$z}'  " . ($whatWithMatch == 1 ? "&& status!='3'" : null) . ";")) {
            note(admsg_renamePlayerDone . " <b>" . sprawdz_login_id($z) . " <small>-&gt&gt</small> " . sprawdz_login_id($na) . "</b>", "info");
        } else {
            note(admsg_renamePlayerError, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }
}


function wyklucz_gracza($gracze, $wyklucz, $r_id)
{
    $czy_jego = mysql_num_rows(mysql_query("SELECT * FROM {$gracze} WHERE vliga='" . WYBRANA_GRA . "' && id_gracza='{$wyklucz}';"));
    if (!empty($czy_jego)) {
        if (mysql_query("DELETE FROM {$gracze} WHERE  vliga='" . WYBRANA_GRA . "' && id_gracza='{$wyklucz}';")) {
            note(admsg_deletePlayerInPartyDone, "info");
            przeladuj($_SESSION['stara']);
        } else {
            note(admsg_deletePlayerInPartyError, "blad");
            przeladuj($_SESSION['stara']);
        }
    } else {
        note(admsg_deletePlayerInPartyErr, "blad");
    }
}

function changeStatus($status, $r_id, $player)
{
    $czy_jego = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_GRACZE . " WHERE vliga='" . WYBRANA_GRA . "' && id_gracza='{$player}' && r_id='{$r_id}';"));
    if (!empty($czy_jego)) {

        if (mysql_query("UPDATE " . TABELA_PUCHAR_DNIA_GRACZE . " SET status='{$status}'  WHERE vliga='" . WYBRANA_GRA . "' && id_gracza='{$player}' && r_id='{$r_id}'")) {
            note("Status gracza zamieniony", "info");
        } else {
            note("Status gracza niezamieniony - blad", "info");
        }
    } else {
        note("Gracz w tych rozgrywkach nie istnieje", "blad");
    }
}

function createSelectWithPlayers($post_name)
{
    $a1 = mysql_query("SELECT g.id_gracza,l.pseudo,g.ranking,l.gadu,g.vliga 
	FROM " . TABELA_GRACZE . " g LEFT JOIN " . TABELA_UZYTKOWNICY . " l
	ON g.id_gracza=l.id WHERE g.vliga='" . WYBRANA_GRA . "' && 
	g.status=1 && l.status=2  ORDER BY l.pseudo;");
    $createOption .= "<option value=\"0\">";
    while ($as2 = mysql_fetch_array($a1)) {
        $createOption .= "<option value=\"{$as2[0]}\">{$as2[1]} : ({$as2[2]})";
    }

    $createO .= "<select name=\"{$post_name}\">{$createOption}</select>";
    return $createO;

}


function funkcja_podmien_gracza($gdzie, $r_id)
{
    $tablica = array(TABELA_PUCHAR_DNIA_GRACZE => admsg_status, TABELA_LIGA_GRACZE => admsg_liga);

    $a1 = mysql_query("SELECT g.id_gracza, l.pseudo, g.status FROM {$gdzie} g 
	LEFT JOIN " . TABELA_UZYTKOWNICY . " l	ON g.id_gracza=l.id
	WHERE g.vliga='" . WYBRANA_GRA . "' && g.r_id='{$r_id}' ORDER BY l.pseudo;");
    $createOption .= "<option value=\"0\"></option>";
    while ($as2 = mysql_fetch_array($a1)) {
        $createOption .= "<option value=\"{$as2['id_gracza']}\">" . $as2['pseudo'] . " [" . ($tablica[$gdzie] . " : " . $as2['status']) . "]";
    }

    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\"><span>" . admsg_selectFieldToRename . "</span></li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
			<fieldset class=\"adminAccessList\">
				<ul class=\"adminAccessOption adminFields\">
					<li><select name=\"zkogo\">{$createOption}</select><span>" . admsg_deleteWithGame . "</span></li>
					<li>" . createSelectWithPlayers('nakogo') . "<span>" . admsg_addToGame . "</span>				</li>
					<li>" . createSelectWithTeams('klub', '') . " <span>" . admsg_teamNewPlayer . "</span>			</li>
					<li>
						<input type=\"radio\" name=\"whatWithMatch\" value=\"1\" checked=\"checked\"/>" . admsg_whatWithMatchClearAccount . "<br/>
						<input type=\"radio\" name=\"whatWithMatch\" value=\"2\"/>" . admsg_whatWithMatchNoClearAccount . "
						<span>" . admsg_whatWithMatch . "</span>
					</li>
				</ul>
				<input type=\"hidden\" name=\"podmien\" value=\"podmien\" >
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\" alt=\"\"/>
			</fieldset>
			</form>
		</li>
	</ul>";
}

// wyswietla formularz dodajacy graczy wraz z klubami do rozgrywek
function mozliwosc_dodania_graczy()
{
    $a1 = mysql_query("SELECT g.id_gracza,l.pseudo,g.ranking,l.gadu,g.vliga 
	FROM " . TABELA_GRACZE . " g LEFT JOIN " . TABELA_UZYTKOWNICY . " l
	ON g.id_gracza=l.id WHERE g.vliga='" . WYBRANA_GRA . "' && 
	g.status=1 && l.status=2  ORDER BY l.pseudo;");
    $createOption .= "<option value=\"0\">";
    while ($as2 = mysql_fetch_array($a1)) {
        $createOption .= "<option value=\"{$as2[0]}\">{$as2[1]} : ({$as2[2]})\n";
    }

    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_addPlayerToParty . "</li>
		<li class=\"glowne_bloki_zawartosc\">
		<fieldset class=\"adminAccessList\">
			<form method=\"post\" action=\"\">
				<ul class=\"adminAccessOption adminFields\">
					<li><select name=\"nowygracz\">{$createOption}</select><span>" . admsg_addToGame . "</span>				</li>
					<li>" . createSelectWithTeams('klub', '') . " <span>" . admsg_teamNewPlayer . "</span>			</li>
				</ul>
				<input type=\"hidden\" name=\"nowy_gracz_add\" value=\"dodaj\"/>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
			</form>
		</fieldset>
		</li>
	</ul>";

} // wyswietla formularz dodajacy graczy wraz z klubami do rozgrywek - END


// mozliwosc dodawania gracza do rozgrywek 
function dodaj_gracza($gdzie, $nowygracz, $klub, $r_id)
{
    if (!empty($nowygracz) && !empty($klub)) {
        $czy_nie_gra = mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} WHERE  id_gracza='{$nowygracz}' && vliga='" . WYBRANA_GRA . "' && r_id='{$r_id}';"));
        if (empty($czy_nie_gra)) {
            if (mysql_query("INSERT INTO  {$gdzie} values('','{$nowygracz}','{$klub}','" . WYBRANA_GRA . "','1','{$r_id}');")) {
                note(admsg_addPlayerToPartyDone, "info");
            } else {
                note(admsg_addPlayerToPartyError, 'blad');
            }
        } else {
            note(admsg_addPlayerToPartyExists, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }
} // mozliwosc dodawania gracza do rozgrywek - END


// wyswietla liste aktualnych graczy wg id listy rozgrywki 
function wyswietl_graczy_gry($gracze, $id)
{
    //GLOBAL $wykorzystaj;
    //$wykorzystaj ="Nick :: Gadu-Gadu :: Meczy :: Zgoda Na host \n";
    //$wykorzystaj .= "".$rek['pseudo']." :: ".$rek['gadu']." :: ". ($wantToInfo[0] ? $wantToInfo[0] : 'BRAK') ." :: ".(sprawdz_zgode_na_host($rek['id_gracza'])=='?' ? 'Nie przetestowano' : sprawdz_zgode_na_host($rek['id_gracza']))."  ".($wantToInfo[0]<11 ? 'Za mało!' : 'Ok. Zaliczono!')."\n";


    require_once('include/admin/funkcje/function.table.php');


    $wyn = mysql_query("SELECT g.id_gracza,g.r_id, g.vliga, l.pseudo, l.gadu,g.klub,g.status,
	(SELECT nazwa FROM " . TABELA_DRUZYNY . "  WHERE id=g.klub) as teamName
	FROM {$gracze} g LEFT JOIN " . TABELA_UZYTKOWNICY . " l
	ON g.id_gracza=l.id WHERE g.vliga='" . WYBRANA_GRA . "'  && g.r_id='{$id}' ORDER BY pseudo;");


    echo "<div class=\"jq_alert\"></div>";
    define('PANE', TRUE);
    $b = 1;

    $zamien = array(
        TABELA_PUCHAR_DNIA_GRACZE => cupPlayersKey,
        TABELA_LIGA_GRACZE => leaguePlayersKey,
        TABELA_TURNIEJ_GRACZE => torurnamentPlayersKey
    );


    echo "<form method=\"post\" action=\"\" name=\"send\">";


    while ($rek = mysql_fetch_array($wyn)) {
        $cupSelect = "" . ($rek[6] == 0 ? "<a href=\"" . AKT . "&changeStatus=on&changePlayer={$rek['id_gracza']}\" class=\"i-access-denied\">Aktywuj</a>" : "<a href=\"" . AKT . "&changeStatus=off&changePlayer={$rek['id_gracza']}\" class=\"i-access-allow\">Dezaktywuj</a>") . "";
        $arr[$b][] = $b++;
        $arr[$b][] = linkownik('profil', $rek['id_gracza'], '');
        $arr[$b][] = linkownik('gg', $rek['id_gracza'], '');
        $arr[$b][] = linkownik('mail', $rek['id_gracza'], '');
        $arr[$b][] = mini_logo_druzyny($rek[5]) . "{$rek['teamName']}";
        $arr[$b][] = $gracze == TABELA_PUCHAR_DNIA_GRACZE ? $cupSelect : $rek[6];
        $arr[$b][] = "<a class=\"jq\" href=\"#\" title=\"{$zamien[$gracze]}|{$rek['id_gracza']}|{$id}\">" . admsg_wyklucz . "</a>";
        $arr[$b][] = "<input type=\"checkbox\" name=\"send_to[]\" value=\"{$rek['id_gracza']}\"/>";
        $arr[$b][] = "<input type=\"checkbox\" name=\"send_to_m[]\" value=\"{$rek['id_gracza']}\"/>";
    }


    $table = new createTable('adminFullTable center');
    $table->setTableHead(array('', admsg_nick, admsg_gg, admsg_mail, admsg_klub, admsg_status, admsg_opcja, admsg_botgg, admsg_botmail), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();

    echo "<div class=\"smallField\">
			<div>
				<fieldset><legend>" . admsg_selectAllFieldsBotGG . "</legend>
					<ul>
						<li onclick=\"makeCheck('send_to[]');\" class=\"select\"></li>
						<li onclick=\"makeUncheck('send_to[]');\" class=\"unselect\"></li>
					</ul>
				</fieldset>
			</div>
			<div>
				<fieldset><legend>" . admsg_selectAllFieldsBotMail . "</legend>
					<ul>
						<li onclick=\"makeCheck('send_to_m[]');\" class=\"select\"></li>
						<li onclick=\"makeUncheck('send_to_m[]');\" class=\"unselect\"></li>
					</ul>
				</fieldset>
			</div>
	</div>";

    $wantAccess = array(
        TABELA_PUCHAR_DNIA_GRACZE => 'bot_puchar',
        TABELA_LIGA_GRACZE => 'bot_liga',
        TABELA_TURNIEJ_GRACZE => 'bot_turniej'
    );
    if (in_array($wantAccess[$gracze], explode(',', POZIOM_U_A))) {
        wyswietl_formularz_wysylania_gg();
    }

    echo "</form>";

} // wyswietla liste aktualnych graczy wg id listy rozgrywki - END


