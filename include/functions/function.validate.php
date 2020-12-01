<?

function sprawdz_moj_klub_w_grze($id, $gdzie, $r_id)
{
    $my = mysql_fetch_array(mysql_query("SELECT * FROM {$gdzie} WHERE id_gracza='{$id}' && r_id='{$r_id}' && vliga='" . DEFINIOWANA_GRA . "';"));
    return $my['klub'];
}

function sprawdz_klub($id)
{
    $my = mysql_fetch_array(mysql_query("SELECT klub FROM " . TABELA_UZYTKOWNICY . " WHERE id='{$id}';"));
    return $my['klub'];
}


// formatuje nazwe pliku na ladna nazwe
function ladna_nazwa_klubu($nazwa)
{
    list($czesc1, $czesc2) = split('[.]', $nazwa);
    list($wlasciwa) = split('[-]', $czesc2);
    $czesc3 = str_replace("_", " ", $wlasciwa);
    return $czesc3;
} // formatuje nazwe pliku na ladna nazwe - END


function sprawdz_gadu($wartosc)
{
    if (eregi("^([0-9])+$", $wartosc))
        return 1;
    else
        return 0;
}

function poprawnosc_rejestracja($wartosc)
{
    if (eregi("^([a-zA-Z0-9_-]{3,20})+$", $wartosc))
        return 1;
    else
        return 0;
}

// podajac id zespolu poda cala nazwe pliku
function sprawdz_nazwe_klubu($identyfikator)
{
    $katalog = opendir("grafiki/loga");
    while ($plik = readdir($katalog)) {
        list($id, $reszta) = split('[.]', $plik);
        if ($identyfikator == $id) {
            return $plik;
        }
    }
} // podajac id zespolu poda cala nazwe pliku - END




