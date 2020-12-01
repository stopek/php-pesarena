<?
function profil_moduly($akcja, $id)
{
    $licz = mysql_fetch_array(mysql_query("SELECT count(*) FROM " . BLOKI_MENU_PRZYPISANIE . " WHERE _id_bloku='{$id}' && 
	_id_gracza='" . DEFINIOWANE_ID . "' && vliga='" . DEFINIOWANA_GRA . "'"));
    if ($akcja == 'modul,wylacz') {
        if (!empty($licz[0])) {
            if (mysql_query("DELETE FROM " . BLOKI_MENU_PRZYPISANIE . " WHERE _id_bloku='{$id}' && 
			_id_gracza='" . DEFINIOWANE_ID . "' && vliga='" . DEFINIOWANA_GRA . "'")) {
                note("Modul zostal wylaczony!", "info");
            } else {
                note("Blad podczas wylaczania tego modulu!", "blad");
            }
        } else {
            note("Ten modul masz aktualnie wylaczony!", "blad");
        }
    } elseif ($akcja == 'modul,wlacz') {
        if (empty($licz[0])) {
            if (mysql_query("INSERT INTO " . BLOKI_MENU_PRZYPISANIE . " VALUES('','{$id}','" . DEFINIOWANE_ID . "','0','" . DEFINIOWANA_GRA . "')")) {
                note("Modul zostal wlaczony!", "info");
            } else {
                note("Blad podczas wlaczania tego modulu!", "blad");
            }
        } else {
            note("Ten modul masz aktualnie wlaczony!", "blad");
        }
    }
}


function profil_zapisz_wersje_gry($wersje_gry)
{
    $jakie = array("1", "2", "0");
    $gra = czysta_zmienna_post($_POST['gra']);
    $how = czysta_zmienna_post($_POST['how']);

    if (in_array($gra, $wersje_gry)) {
        if (in_array($how, $jakie)) {
            $czyjest = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . DEFINIOWANE_ID . "' && vliga='{$gra}';"));
            if ($czyjest == 1) {
                $wynik2 = mysql_query("UPDATE " . TABELA_GRACZE . " SET status='{$how}' WHERE id_gracza='" . DEFINIOWANE_ID . "' && vliga='{$gra}';");
            } else {
                $wynik2 = mysql_query("INSERT INTO " . TABELA_GRACZE . " values('','" . DEFINIOWANE_ID . "','','','','','','','','{$gra}','{$how}','0','','');");
            }
            note(M271, "info");

        } else {
            note(M272, "blad");
        }
    } else {
        note(M273, "blad");
    }
}

function profil_dane_personalne($wersje_gry)
{
    $gadu = (int)$_POST['gadu'];
    $versja = czysta_zmienna_post($_POST['versja']);
    $nowyklub = (int)id_druzyny($_POST['nowyklub']);

    if (!empty($gadu) && !empty($nowyklub) && in_array($versja, $wersje_gry)) {
        if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET gadu='{$gadu}',klub='{$nowyklub}',vliga='{$versja}' WHERE id='" . DEFINIOWANE_ID . "';")) {
            note(M279, "info");
        } else {
            note(M280, 'blad');
        }
    } else {
        note(M253, "blad");
    }
}

function profil_zmien_haslo()
{
    $shaslo = czysta_zmienna_post($_POST['shaslo']);
    $shaslo2 = czysta_zmienna_post($_POST['shaslo2']);
    $nhaslo = czysta_zmienna_post($_POST['nhaslo']);
    $nhaslo2 = czysta_zmienna_post($_POST['nhaslo2']);


    if (!empty($shaslo) && !empty($nhaslo) && !empty($shaslo2) && !empty($nhaslo2)) {
        if ($shaslo == $shaslo2) {
            $haslo = kodowanie_hasla($shaslo);
            $czyjest = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE haslo='{$haslo}' && id='" . DEFINIOWANE_ID . "';"));
            if (!empty($czyjest)) {
                if ($nhaslo == $nhaslo2) {
                    $newhaslo = kodowanie_hasla($nhaslo);
                    if (mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET haslo='{$newhaslo}' WHERE id='" . DEFINIOWANE_ID . "';")) {
                        note(M274, "info");
                    } else {
                        note(M275, 'blad');
                    }
                } else {
                    note(M276, "blad");
                }
            } else {
                note(M277, "blad");
            }
        } else {
            note(M278, "blad");
        }
    } else {
        note(M192, "blad");
    }

}



