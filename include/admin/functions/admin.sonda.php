<?
//--------------------//
// komunikaty zebrane //
//--------------------//
function showForm($pyt, $odp1, $odp2, $odp3, $odp4, $odp5, $metoda)
{
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_ankietazarzadzanie . " {$metoda}</li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\">
				<input type=\"hidden\" name=\"{$metoda}\" value=\"{$metoda}\"/>
				<ul class=\"adminAccessOption adminFields\">	
					<li><input type=\"text\" name=\"pyt\" value=\"{$pyt}\"/><span>" . admsg_pytanie . "</span></li>
					<li><input type=\"text\" name=\"odp1\" value=\"{$odp1}\"/><span>" . admsg_odpowiedz . " 1</span></li>
					<li><input type=\"text\" name=\"odp2\" value=\"{$odp2}\"/><span>" . admsg_odpowiedz . " 2</span></li>
					<li><input type=\"text\" name=\"odp3\" value=\"{$odp3}\"/><span>" . admsg_odpowiedz . " 3</span></li>
					<li><input type=\"text\" name=\"odp4\" value=\"{$odp4}\"/><span>" . admsg_odpowiedz . " 4</span></li>
					<li><input type=\"text\" name=\"odp5\" value=\"{$odp5}\"/><span>" . admsg_odpowiedz . " 5</span></li>
				</ul>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
				</fieldset>
			</form>
		</li>
	</ul>";
}

function addSonda()
{
    foreach ($_POST as $key => $value) {
        $r[$key] = czysta_zmienna_get($value);
    }
    if (!empty($r[pyt]) && !empty($r[odp1]) && !empty($r[odp2])) {
        if (mysql_query("INSERT INTO " . TABELA_SONDA . " VALUES('','{$r[pyt]}','{$r[odp1]}','{$r[odp2]}','{$r[odp3]}','{$r[odp4]}','{$r[odp5]}','0','0','0','0','0','');")) {
            note(admsg_addSondaDone, 'info');
        } else {
            note(admsg_addSondaError, 'blad');
        }
    } else {
        note(admsg_wFieldsSonda, 'blad');
    }
}


function updateSonda($id)
{
    foreach ($_POST as $key => $value) {
        $r[$key] = czysta_zmienna_get($value);
    }
    if (!empty($r[pyt]) && !empty($r[odp1]) && !empty($r[odp2])) {
        if (mysql_query("UPDATE " . TABELA_SONDA . " SET 
		pyt='{$r[pyt]}',odp1='{$r[odp1]}',odp2='{$r[odp2]}',
		odp3='{$r[odp3]}',odp4='{$r[odp4]}',odp5='{$r[odp5]}' WHERE id='{$id}';")) {
            note(admsg_updateSondaDone, 'info');
        } else {
            note(admsg_updateSondaError, 'blad');
        }
    }
}

function deleteSonda($id)
{
    $count = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_SONDA . " WHERE id='{$id}'"));
    if (!empty($count[0])) {
        if (mysql_query("DELETE FROM " . TABELA_SONDA . " where id='{$id}';")) {
            note(admsg_deleteSondaDone, 'info');
        } else {
            note(admsg_deleteSondaError, 'blad');
        }
    } else {
        note(admsg_sondaExists, "blad");
    }
}

function showResult($id)
{
    $wynik = mysql_fetch_array(mysql_query("SELECT glosowali FROM " . TABELA_SONDA . " where id='{$id}';"));
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_showUsersSonda . "</li>
		<li class=\"glowne_bloki_zawartosc\">{$wynik['glosowali']}</li>
	</ul>";
}

?>