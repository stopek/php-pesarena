<?

require_once('include/admin/funkcje/function.table.php');
$podopcja = (int)$_GET['podopcja'];
$mozliwe_rankingi = array('1', '2', '3', '5');
$wyklucz = $_POST['wyklucz'];
$zapisz = $_POST['zapisz'];
$id = (int)$_POST['id'];
$pkt = (int)$_POST['pkt'];
$id_g = (int)$_POST['id_g'];
if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!empty($podopcja) && in_array($podopcja, $mozliwe_rankingi)) {
            if (!empty($wyklucz)) {
                if (!in_array('ranking_wyklucz_gracza', explode(',', POZIOM_U_A))) {
                    return note("Brak dostepu!", "blad");
                } else { ###
                    if (mysql_query("DELETE FROM " . TABELA_RANKING . " WHERE id='{$id}'")) {
                        note("Gracz <b>" . sprawdz_login_id($id_g) . "</b> zostal usuniety z rankingu!", "info");
                        przeladuj($_SESSION['stara']);
                    } else {
                        note("Wystapil blad podczas wykluczania gracza z rankingu", "blad");
                    }
                }###
            } elseif (!empty($zapisz)) {
                if (!in_array('ranking_zmien_punkty', explode(',', POZIOM_U_A))) {
                    return note("Brak dostepu!", "blad");
                } else { ###
                    if (mysql_query("UPDATE " . TABELA_RANKING . " SET pkt='{$pkt}' WHERE  id='{$id}'")) {
                        note("Zamiana punktow dla gracza <b>" . sprawdz_login_id($id_g) . "</b> zostala wykonana pomyslnie!", "info");
                        przeladuj($_SESSION['stara']);
                    } else {
                        note("Blad podczs edycji punktow dla tego gracza", "blad");
                    }
                }###
            } else {

                $sql = mysql_query("SELECT r.id_gracza,r.pkt,l.aktualizacja, l.id,r.id, 
				(SELECT klub FROM " . TABELA_UZYTKOWNICY . " WHERE id=r.id_gracza) as iden_klub,
				(SELECT nazwa FROM " . TABELA_DRUZYNY . " WHERE id=iden_klub) as nazwa_druzyny_gracza 
				FROM " . TABELA_RANKING . " r," . TABELA_RANKING_LISTA . " l 
				WHERE r.id_rankingu=l.id && l.type='{$podopcja}' && r.vliga='" . WYBRANA_GRA . "' ORDER BY `pkt` DESC");
                $b = 1;

                while ($rek = mysql_fetch_array($sql)) {
                    $createForm = "
					<form method=\"post\" action=\"\">
						<input size=\"4\" type=\"text\" name=\"pkt\" value=\"{$rek['pkt']}\"/><input type=\"submit\" name=\"zapisz\" value=\"" . admsg_save . "\"/>
						<input type=\"hidden\" name=\"id\" value=\"{$rek[4]}\"/>
						<input type=\"hidden\" name=\"id_g\" value=\"{$rek['id_gracza']}\"/>
					";
                    $createForm2 = "
						<input type=\"submit\" name=\"wyklucz\" value=\"" . admsg_wyklucz . "\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"/>
					</form>
					";

                    $arr[$b][] = $b++;
                    $arr[$b][] = linkownik('profil', $rek['id_gracza'], '');
                    $arr[$b][] = mini_logo($rek['id_gracza']) . " {$rek['nazwa_druzyny_gracza']}";
                    $arr[$b][] = linkownik('gg', $rek['id_gracza'], '');
                    $arr[$b][] = $createForm;
                    $arr[$b][] = $createForm2;

                }


                $table = new createTable('adminFullTable');
                $table->setTableHead(array('', admsg_pseudo, admsg_druzyna, admsg_gg, admsg_save, admsg_wyklucz), 'adminHeadersClass');
                $table->setTableBody($arr);
                echo $table->getTable();


            }
        } else {
            note("Taki typ rankingu nie istnieje!", "blad");
        }
    } else {
        note("Brak dostepu", "blad");
    }
}
wybor_gry_admin(34);
?>
