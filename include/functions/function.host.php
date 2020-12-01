<?


// wystawia komentarz dla danego spotkania
function wystaw_komentarz($gdzie, $id, $id_gracza, $gral2, $ocena)
{


    //ustawienie na start
    $stat_a = ($ocena == '+' ? "1" : "0"); // ocena dla gracza dla +
    $stat_b = ($ocena == '-' ? "1" : "0"); // ocena dla gracza dla -

    //licze czy gracz ma swoja sesje oraz czy to spotkanei nie zostalo juz ocenione!
    $licz = mysql_fetch_array(mysql_query("SELECT count(id),id,sesja_plus,sesja_minus FROM 
	" . TABELA_OCENA_SESJA . " WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "' GROUP BY id"));

    $licz2 = mysql_fetch_array(mysql_query("SELECT count(l.id) FROM " . TABELA_OCENA_LISTA . " l WHERE l.id_meczu='{$id}' && l.gdzie='{$gdzie}' GROUP BY l.id"));
    //jesli nie ma tego spotkania i wystawiajacy jest gospodarzem
    if (empty($licz2[0]) && $id_gracza != $gral2) {
        if (!mysql_query("INSERT INTO " . TABELA_OCENA_LISTA . " VALUES ('','{$id}','{$gdzie}','','{$ocena}','{$id_gracza}','{$gral2}')")) {
            note(M445, "blad");
        }

    }

    //jesli jest jedno spotkanie i osoba wystawiajaca jest gosciem
    if (($licz2[0] == 1) && ($id_gracza == $gral2)) {
        mysql_query("UPDATE " . TABELA_OCENA_LISTA . " SET g_1_ocena='{$ocena}' WHERE id_meczu='{$id}' && gdzie='{$gdzie}'");
    }

    //akutalizacja sesji dla $id_gracza
    if (empty($licz[0])) {
        mysql_query("INSERT INTO " . TABELA_OCENA_SESJA . " VALUES ('','{$id_gracza}','" . DEFINIOWANA_GRA . "','{$stat_a}','{$stat_b}')");
    } elseif (sprawdz_sesje(array($GLOBALS['gral1'], $GLOBALS['gral2']), $licz[1]) == FALSE) {
        mysql_query("UPDATE " . TABELA_OCENA_SESJA . " SET sesja_plus=sesja_plus+'{$stat_a}',sesja_minus=sesja_minus+'{$stat_b}' WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "'");
        mysql_query("INSERT INTO " . TABELA_OCENA_GRALI . " VALUES ('','{$licz[1]}','" . DEFINIOWANA_GRA . "','" . $GLOBALS['gral1'] . ";" . $GLOBALS['gral2'] . "')");
    } else {
        note(M446 . " +({$licz[2]}) , -({$licz[3]})", "fieldset");
    }


    //jesli gracz uzupelnil sesje (10) dodatnich lub ujemnych punktow - wystawiane sa punkty
    $spr = mysql_fetch_array(mysql_query("SELECT sesja_plus,sesja_minus FROM " . TABELA_OCENA_SESJA . " WHERE id_gracza='{$id_gracza}' && vliga = '" . DEFINIOWANA_GRA . "'"));
    if ($spr[0] == SESJA_OCENY) {
        aktualizuj_punkty_za_ocene($id_gracza, PKT_ZA_SESJE_OCEN_DODATNIE, '+', 'plus');
        mysql_query("DELETE FROM  " . TABELA_OCENA_GRALI . "  WHERE id_sesji='{$licz[1]}'");
    }
    if ($spr[1] == SESJA_OCENY) {
        aktualizuj_punkty_za_ocene($id_gracza, PKT_ZA_SESJE_OCEN_UJEMNE, '-', 'minus');
        mysql_query("DELETE FROM  " . TABELA_OCENA_GRALI . "  WHERE id_sesji='{$licz[1]}'");
    }

}

function aktualizuj_punkty_za_ocene($id_gracza, $pkt, $znak, $jaka_sesja)
{
    if (mysql_query("INSERT INTO " . TABELA_DODATKOWE_PUNKTY . " values('','{$id_gracza}','" . DEFINIOWANA_GRA . "','{$znak}{$pkt}','Punkty za ocene hostu!',NOW(),'7')") &&
        mysql_query("UPDATE " . TABELA_GRACZE . " SET ranking=ranking{$znak}{$pkt} WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "';") &&
        mysql_query("UPDATE " . TABELA_OCENA_SESJA . " SET sesja_{$jaka_sesja} = '0' WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "';")
    ) {
        note(M447, "info");
    } else {
        note(M448, "blad");
    }
}

function formularz_komentarz()
{
    echo "<fieldset><legend>" . M449 . "</legend>
	<div  class=text_center>
	<form method=\"post\" action=\"\">
	<div id=\"tlo_ocena_host\">
		<input type=\"radio\" title=\"" . M409 . "\" name=\"glos_k_\" value=\"+\"/>" . M409 . "
		<input type=\"radio\" title=\"" . M411 . "\" name=\"glos_k_\" value=\"0\"/>" . M411 . "
		<input type=\"radio\" title=\"" . M410 . "\" name=\"glos_k_\" value=\"-\"/>" . M410 . "
	</div><p align=\"right\"><input type=\"image\" src=\"img/zakoncz.jpg\"/></p></form>
	" . M450 . "</div>
	</fieldset>";
}


// sprawdza sesje dla danego gracza czy w danej sesji
// nie gral juz z tym graczem
// jesli niegral to przyjmuje ocene do sesji
function sprawdz_sesje($tablica, $id_sesji)
{

    $sql = mysql_query("SELECT * FROM ocena_grali WHERE id_sesji='{$id_sesji}'");
    while ($rek = mysql_fetch_array($sql)) {
        $exp_dobrzy = explode(";", $rek['grali']);
        $blad = 0;
        foreach ($tablica as $gracz) {
            if (!in_array($gracz, $exp_dobrzy)) {
                $blad = 1;
            }
        }
        if (empty($blad)) {
            $zgoda = 1;
        }
    }


    return (!empty($zgoda) ? 1 : 0);
}
