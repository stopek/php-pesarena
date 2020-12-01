<?

function add_points()
{
    $pkt = (int)$_POST['punkty_dod'];
    $opis = czysta_zmienna_post($_POST['opis']);
    $id_gracza = (int)$_POST['id_gracza'];
    if (mysql_query("INSERT INTO " . TABELA_DODATKOWE_PUNKTY . " VALUES('','{$id_gracza}','" . WYBRANA_GRA . "','{$pkt}','{$opis}',NOW(),'8')") &&
        mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$pkt}' WHERE id_gracza='{$id_gracza}'")) {
        note(admsg_addPointsDone, "info");
        przeladuj($_SESSION['stara']);
    } else {
        note(admsg_addPointsError, "blad");
    }
}

function edit_points($pkt, $opis, $id_gracza, $id)
{
    $suma = $pkt - $info['punkty'];
    if (!empty($opis) && !empty($pkt) && !empty($id) && !empty($id_gracza)) {
        if (
            mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking+'{$suma}' WHERE id_gracza='{$id_gracza}'") &&
            mysql_query("UPDATE " . TABELA_DODATKOWE_PUNKTY . " SET punkty='{$pkt}',opis='{$opis}' WHERE id='{$id}'")
        ) {
            note(admsg_updatePointsDone, "info");
        } else {
            note(admsg_updatePointsError, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }
}

function delete_points($delete)
{
    //pobieram info o graczu ktory otrzymal punkty o id:$delete
    //oraz punkty ktore trzeba mu odjac/dodac do statystyk
    $info = mysql_fetch_array(mysql_query("SELECT id_gracza, punkty FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE id='{$delete}'"));
    $sprawdz = mysql_num_rows(mysql_query("SELECT id FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE id='{$delete}'"));
    if (!empty($sprawdz)) {
        if (mysql_query("DELETE FROM " . TABELA_DODATKOWE_PUNKTY . " WHERE id='{$delete}'") &&
            mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking-'{$info['punkty']}' WHERE id_gracza='{$info['id_gracza']}'")) {
            note(admsg_deletePointsDone, "info");
            przeladuj($_SESSION['stara']);
        } else {
            note(admsg_deletePointsError, "blad");
        }
    } else {
        note(admsg_deletePointsNoExists, "blad");
    }
}


function showForm($rekord, $co)
{
    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">" . admsg_dodatkowe_punkty . " <strong>{$co}</strong></li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\">
				<input type=\"hidden\" name=\"{$co}\" value=\"1\"/>
				<ul class=\"adminAccessOption adminFields\">	
					<li><input type=\"text\" size=\"20\" value=\"{$rekord['punkty']}\" name=\"punkty_dod\"><span>" . admsg_punkty . "</span></li>
					<li><textarea  name=\"opis\">{$rekord['opis']}</textarea><span>" . admsg_opis . "</span></li>
					<li>" . ($co == 'popraw' ? $rekord['id_gracza'] : createSelectWithPlayers('id_gracza')) . "<span>" . admsg_gracz . "</span></li>
				</ul>
				<input type=\"hidden\" name=\"{$co}\" value=\"{$co}\"/>
				<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
				</fieldset>
			</form>
		</li>
	</ul>";
}

?>