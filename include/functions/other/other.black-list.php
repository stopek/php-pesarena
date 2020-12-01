<?
function czarna_lista()
{
    $wyn = mysql_query("SELECT 
	(SELECT h.status FROM " . TABELA_ZGODA_HOST . " h WHERE h.id_gracza=u.id),
	(SELECT g.ranking FROM " . TABELA_GRACZE . " g WHERE g.id_gracza=u.id && g.vliga='" . DEFINIOWANA_GRA . "'),u.gadu,u.id, count(u.id)
	FROM " . TABELA_UZYTKOWNICY . " u
	WHERE u.status='3' && u.vliga='" . DEFINIOWANA_GRA . "' GROUP BY id;");
    echo "<fieldset><legend>" . M451 . "</legend>";
    naglowek_uzytkownicy();
    while ($rek = mysql_fetch_array($wyn)) {
        print "
			<tr" . kolor($a++) . ">
				<td>" . linkownik('profil', $rek[3], '') . "</tD>
				<td>{$rek[2]}</tD>
				<td>" . mini_logo($rek['id']) . "</tD>
				<td>{$rek[0]}</tD>
				<td>{$rek[1]}</tD>
			</tr>";
        $temp = TRUE;
    }
    end_table();

    if (empty($temp)) {
        note(M240, "blad");
    }
    echo "</fieldset>";
}

?>
