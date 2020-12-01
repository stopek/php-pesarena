<?
function sprawdz_zgode_na_host($id)
{
    if ($a = mysql_query("SELECT * FROM " . TABELA_ZGODA_HOST . " where id_gracza='{$id}'")) {
        $b = mysql_fetch_array($a);
        switch ($b['status']) {
            case '+':
                return M385;
                break;
            case '-':
                return M386;
                break;
            default:
                return "?";
        }
    }
}

function sprawdz_druzyna_id($id)
{
    $b = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_DRUZYNY . " where id='{$id}'"));
    return $b['nazwa'];
}

//sprawdza jaka wersje gry standardowo ma ustawiony gracz o podanym id
function jaka_gra($id)
{
    $b = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where id='{$id}'"));
    return $b['vliga'];
} //sprawdza jaka wersje gry standardowo ma ustawiony gracz o podanym id - END


//zwraca pelna nazwe gry po podaniu skrotu
function sprawdz_opis_gry($skrot)
{
    $b = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GAME . " WHERE skrot='{$skrot}'"));
    return $b['nazwa'];
} //zwraca pelna nazwe gry po podaniu skrotu -END


// zwraca 0 jesli wartosc pusta
function if_zero($a)
{
    if (empty($a)) return 0; else return $a;
}

// zwraca 0 jesli wartosc pusta - END

// formatowanie linkow 
function linkownik($wersja, $id, $menu)
{
    switch ($wersja) {
        case 'profil_druzyny':
            return "<a href=\"druzyny-profil-{$id}.htm\" title=\"" . M355 . "\">{$menu}</a>";
            break;
        case 'zakoncz':
            return "<a href=\"" . str_replace('_dnia', '', $menu) . "-zakoncz,spotkanie-{$id}.htm\" 
			onclick=\"potwierdzenie('" . str_replace('_dnia', '', $menu) . "-zakoncz,spotkanie-{$id}.htm','" . M356 . "');return false;\">
			<img src=\"img/strzalka.png\" alt=\"" . M114 . "\" title=\"" . M114 . "\"/>
			</a>";
            break;
        case 'odrzuc_wynik':
            return "<a href=\"{$menu}-odrzuc,wynik-{$id}.htm\" 
			onclick=\"potwierdzenie('{$menu}-odrzuc,wynik-{$id}.htm','" . M357 . "');return false;\">
			<img src=\"img/zamknij.png\" alt=\"" . M115 . "\" title=\"" . M115 . "\"/>
			</a>";
            break;
        case 'akceptuj':
            return "<a href=\"{$menu}-akceptuj,spotkanie-{$id}.htm\" 
			onclick=\"potwierdzenie('{$menu}-akceptuj,spotkanie-{$id}.htm','" . M358 . "');return false;\">
			<img src=\"img/strzalka.png\" alt=\"" . M116 . "\" title=\"" . M116 . "\">
			</a>";
            break;
        case 'podaj_wynik':
            return "<a href=\"{$menu}-podaj,wynik-{$id}.htm\"
			onclick=\"potwierdzenie('{$menu}-podaj,wynik-{$id}.htm','" . M360 . "');return false;\">
			<img src=\"img/strzalka.png\" alt=\"" . M105 . "\" title=\"" . M105 . "\"/>
			</a>";
            break;
        case 'odrzuc_spotkanie':
            return "<a href=\"wyzwania-odrzuc,spotkanie-{$id}.htm\" 
			onclick=\"potwierdzenie('wyzwania-odrzuc,spotkanie-{$id}.htm','" . M359 . "');return false;\">
			<img src=\"img/zamknij.png\" alt=\"" . M117 . "\" title=\"" . M117 . "\"/>
			</a>";
            break;
        case 'profil':
            return "<a href=\"profil,pokaz-{$id}.htm\" title=\"" . M92 . "\">" . sprawdz_login_id($id) . "</a>";
            break;
        case 'graj':
            return "<a href=\"wyzwania-graj-{$id}.htm\" title=\"" . M119 . "\" 
			onclick=\"potwierdzenie('wyzwania-graj-{$id}.htm','" . M361 . "');return false;\">
			<img src=\"img/zagraj.png\" alt=\"" . M119 . "\"  title=\"" . M119 . "\"/></a>";
            break;
        case 'gg': //<a href=\"gg:".$g."\">".$g."</a>
            $g = sprawdz_gadu_id($id);
            $menu = !empty($menu) ? $menu : '0';
            return "<a href=\"{$g}\"><img src=\"http://status.gadu-gadu.pl/users/status.asp?id={$g}&amp;styl={$menu}\"/>" . $g . "</a>";
            break;
        case 'mail':
            $mail = sprawdz_mail_id($id);
            return "<a href=\"mailto:{$mail}\" title=\"\">{$mail}</a>";
            break;
        case 'tabela_ligowa':
            if ($id == 'ex') {
                $aaa = "extraklasa";
            } else {
                $aaa = "liga{$id}";
            }
            return "<a href=\"liga-tabela-" . ($menu) . ".htm\"\"><img src=\"img/{$aaa}.jpg\" alt=\" {$aaa} \"/></a> ";
            break;
        case 'terminarz_ligowy':
            if ($id == 'ex') {
                $aaa = "extraklasa";
            } else {
                $aaa = "liga{$id}";
            }
            return "<a href=\"liga-terminarz-" . ($menu) . ".htm\"\"><img src=\"img/{$aaa}.jpg\"  alt=\" {$aaa} \"/></a> ";
            break;
    }
} // formatowanie linkow - END


function skroc_text($tresc, $ile)
{
    return substr($tresc, 0, -(strlen($tresc)) + $ile);
}

function cofnij()
{
    print "<script language=\"javascript\" type=\"text/javascript\">      
	window.location.history.back(1);                
	</script> ";
}

function nazwa_fazy($name)
{
    $exit = str_replace('ele', M362, $name);
    $exit = str_replace('_', '/', str_replace('-k', ' ', $exit));
    $exit = str_replace('1/1', M173, str_replace('-k', ' ', $exit));
    $exit = str_replace('1/2', M174, $exit);
    $exit = str_replace('1/4', M175, $exit);
    return $exit;
}

function usun_sekundy($data)
{
    $rozegranoo = explode(':', $data);
    $exit = $rozegranoo[0] . ":" . $rozegranoo[1];
    return $exit;
}


function nazwij_przegladarke($x)
{
    if (substr_count($x, "pera") != 0) {
        $br = "opera";
    } elseif (substr_count($x, "MSIE") != 0) {
        $br = "explorer";
    } elseif (substr_count($x, "etscape6") != 0) {
        $br = "netscape";
    } elseif (substr_count($x, "rv:1.") != 0) {
        $br = "firefox";
    } elseif (substr_count($x, "4.7") != 0) {
        $br = "netscape";
    } elseif (substr_count($x, "Chrome") != 0) {
        $br = "chrome";
    } else {
        $br = "inna";
    }
    return $br;
}


function sprawdz($zmienna)
{
    $zmienna = (int)$zmienna;
    if (is_int($zmienna)) return $zmienna;
    else return ($zmienna = 0);
}


// podajac nazwe klubu wyswietli id druzyny
function id_druzyny($nazwa)
{
    list($id, $czesc2) = split('[.]', $nazwa);
    if ($id) {
        return $id;
    } else {
        return 0;
    }
} // podajac nazwe klubu wyswietli id druzyny - END


// +- dla bramek strzelonych i straconych
function plusminus_bramki($id_gracza, $gra)
{
    return strzelone_bramki($id_gracza, $gra) - stracone_bramki($id_gracza, $gra);
} // +- dla bramek strzelonych i straconych - END


// liczy ilosc straconych bramek 
function stracone_bramki($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['b_m'])) return 0; else return $a['b_m'];
} // liczy ilosc straconych bramek  - END


########### sprawdza liczbe remisow #############################
function remisy($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['m_r'])) return 0; else return $a['m_r'];
}


################## sprawdza liczbe przegranych spotkan #########
function przegrane_mecze($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['m_p'])) return 0; else return $a['m_p'];
}


########## sprawdza ilosc wygranych spotkan #####################
function wygrane_mecze($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['m_w'])) return 0; else return $a['m_w'];
}


######### liczy ilosc strzelonych bramek #######################
function strzelone_bramki($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['b_p'])) return 0; else return $a['b_p'];
}


######### funkcja sprawdzajaca pseudonim uzytkownika wg zaszyfrowanego loginu #####
function sprawdz_login($pseudonim)
{
    $my = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where login='" . $pseudonim . "';"));
    return $my[1];
}


### sprawdza pseudonim wg id ###################################
function sprawdz_mail_id($id)
{
    $e = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE id='" . $id . "';"));
    return strip_tags($e['mail']);
}


/*

### sprawdza pseudonim wg id ###################################
function sprawdz_login_id ($id) 
{
	$e=mysql_fetch_array(mysql_query("SELECT u.pseudo,v.id_gracza FROM ".TABELA_UZYTKOWNICY." u 
	LEFT JOIN vip v ON v.id_gracza=u.id WHERE u.id='".$id."';"));
	return (!empty($e[1]) ? "[V.I.P]" : "")."".strip_tags($e[0]); 
}
*/


### sprawdza pseudonim wg id ###################################
function sprawdz_login_id($id)
{
    $e = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE id='" . $id . "';"));
    return (strip_tags($e['pseudo']) ? strip_tags($e['pseudo']) : "-");
}


### sprawdza pseudonim wg id ###################################
function sprawdz_login_id_clear($id)
{
    $e = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE id='" . $id . "';"));
    return strip_tags($e['pseudo']);
}


### sprawdza gadu wg id ###################################
function sprawdz_gadu_id($id)
{
    $e = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE id='" . $id . "';"));
    return (int)$e['gadu'];
}


### sprawdza gadu wg id ###################################
function sprawdz_ranking_id($id_gracza, $gra)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='" . $id_gracza . "' && vliga='" . $gra . "'"));
    if (empty($a['ranking'])) return 0; else return $a['ranking'];
}


// sprawdza id wg pseudonimu
function sprawdz_id_login($pseudo)
{
    $e = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE pseudo='" . $pseudo . "';"));
    return $e['id'];
} // sprawdza id wg pseudonimu - END


// przekierowanie
function przeladuj($sciezka)
{
    ?>

    <script type="text/javascript">
        function init() {
            setTimeout('document.location="<?=$sciezka?>"',<?echo(defined('ADMIN_IS_') ? 3000 : 1);?>);
        }

        window.onload = init;
    </script>
    <?
} // przekierowanie - END


// funkcja koloru
function kolor($zmienna)
{
    if ($zmienna % 2 == 0) {
        return " class=\"kolorek_jeden " . (defined('PANE') ? " pane" : null) . "\"	id=\"_{$zmienna}\" onmouseover=\"koloron('_{$zmienna}')\" onmouseout=\"kolorof('_{$zmienna}')\"";
    } else {
        return " class=\"kolorek_dwa " . (defined('PANE') ? " pane" : null) . "\" id=\"_{$zmienna}\" onmouseover=\"koloron('_{$zmienna}')\" onmouseout=\"kolorof2('_{$zmienna}')\"";
    }
} // funkcja koloru  - END


// szyfruje zmienna haslo
function kodowanie_hasla($haslo)
{
    $haslo = sha1(md5(sha1($haslo)));
    return $haslo;
} // szyfruje zmienna haslo  - END

### szyfruje zmienna  login####################
function kodowanie_loginu($login)
{
    $login = md5(sha1(md5($login)));
    return $login;
}


####### sprawdza czy sa jakies mecze ###########
function sprawdz_co_mam($id_gracza)
{
    $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_WYZWANIA . " where  (n1='{$id_gracza}' || n2='{$id_gracza}');"));
    if ($ile == '0') {
        print "<div class=\"text_center\">" . M102 . "</div>";
    }
}

// wyswietla kolorowo puknkty
function pokaz_moje_pkt($id_gracza_1, $id_gracza_2, $k_1, $k_2, $id_gracza)
{
    $c = KOLOR_C;
    $d = KOLOR_D;
    if ($id_gracza_1 == $id_gracza) {
        if ($k_1 < 0) {
            $pok .= "<font color=\"{$d}\">";
        } else {
            $pok .= "<font color=\"{$c}\">";
        }
        $pok .= $k_1;
    }
    if ($id_gracza_2 == $id_gracza) {
        if ($k_2 < 0) {
            $pok .= "<font color=\"{$d}\">";
        } else {
            $pok .= "<font color=\"{$c}\">";
        }
        $pok .= $k_2;
    }
    if ($id_gracza != ($id_gracza_1 || $id_gracza_2)) {
        $pok .= $k_1 . ':' . $k_2;
    }
    $pok .= "</font>";
    return $pok;
} // wyswietla kolorowo puknkty - END


// sprawdza czy nie ma 2 takich samych ip jak podany
function sprawdz_ip($ip)
{
    $e = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE  ip='{$ip}' && status='2';"));
    return $e;
} // sprawdza czy nie ma 2 takich samych ip jak podany - END


// liczy ilu jest uzytkownikow danej gry
function licz_userow_ligi($gra)
{
    $suma = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where  vliga='{$gra}';"));
    return $suma;
} // liczy ilu jest uzytkownikow danej gry - END


// czysci zmienna get
function czysta_zmienna_get($zmienna)
{
    $zmienna = mysql_real_escape_string(stripslashes($zmienna));
    return $zmienna;
} // czysci zmienna get - END


// czysli zmienna post
function czysta_zmienna_post($zmienna)
{
    $zmienna = mysql_real_escape_string(stripslashes($zmienna));
    return $zmienna;
} // czysli zmienna post  -END

// czysli zmienne z odczytu
function czysc_odczyt($zmienna)
{
    $zmienna = htmlentities($zmienna);
    return $zmienna;
} // czysli zmienne z odczytu - END


// czysli zmienne do zapisu 
function czysc_zapis($zmienna)
{
    $zmienna = addslashes($zmienna);
    return $zmienna;
} // czysli zmienne do zapisu  - END


// odliczanie czasu
function poczekaj($data, $ile)
{
    $ostatnio = strtotime($data);
    $teraz = strtotime("NOW");
    $roznica = $teraz - $ostatnio;
    $czas = $ile - $roznica;
    return $czas;
} // odliczanie czasu  - END


function sortx(&$tablica, $sort = array())
{
    $function = '';
    while (list($key) = each($sort)) {
        if (isset($sort[$key]['case']) && ($sort[$key]['case'] == trUE)) {
            $function .= 'if (strtolower($a["' . $sort[$key]['name'] . '"])<>strtolower($b["' . $sort[$key]['name'] . '"])) { return (strtolower($a["' . $sort[$key]['name'] . '"]) ';
        } else $function .= 'if ($a["' . $sort[$key]['name'] . '"]<>$b["' . $sort[$key]['name'] . '"]) { return ($a["' . $sort[$key]['name'] . '"] ';

        if (isset($sort[$key]['sort']) && ($sort[$key]['sort'] == "DESC")) $function .= '<';
        else $function .= '>';

        if (isset($sort[$key]['case']) && ($sort[$key]['case'] == trUE)) $function .= ' strtolower($b["' . $sort[$key]['name'] . '"])) ? 1 : -1; } else';
        else $function .= ' $b["' . $sort[$key]['name'] . '"]) ? 1 : -1; } else';
    }
    $function .= ' { return 0; }';
    if (!empty($tablica)) {
        usort($tablica, create_function('$a, $b', $function));
    }
}


// wyswietlanie bledow i potwierdzen
function note($tresc, $typ)
{
    if ($typ == 'blad') {
        print "<div id=\"sekundy\"	class=\"error\"><img src=\"img/error.png\" alt=\"\"/><span>{$tresc}</span></div>";
    } elseif ($typ == 'info') {
        print "<div class=\"confirm\"><img src=\"img/oki.png\" alt=\"\"/><span>{$tresc}</span></div>";
    } elseif ($typ == 'fieldset') {
        print "<fieldset><img src=\"img/oki.png\" alt=\"\"/><span>{$tresc}</span></fieldset>";
    } else {
        print "<div id=\"text_center\">{$tresc}</div><br/>";
    }
} // wyswietlanie bledow i potwierdzen - END


//sprawdzanie poprawnosci maila
function sprawdz_mail($email)
{
    if (eregi("^([a-zA-Z0-9._-])+@([a-zA-Z0-9._-])+\.([a-zA-Z0-9._-])([a-zA-Z0-9._-])+", $email)) {
        return 1;
    } else {
        return 0;
    }
} //sprawdzanie poprawnosci maila - END


// wylicza jaki status maja pierwsze mecze po eliminacjach
function gdzie_po_eliminacji($gra)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA . " where spotkanie='ele' && vliga='{$gra}';"));
    if ($licz > '8' && $licz < '16') {
        $dalej = '1_4';
    }
    if ($licz > '16' && $licz < '32') {
        $dalej = '1_8';
    }
    if ($licz > '32') {
        $dalej = '1_16';
    }
    return $dalej;
} // wylicza jaki status maja pierwsze mecze po eliminacjach - end


##### sprawdza liczbe zozegranych spotkan #######################
function rozegrane_mecze($id_gracza)
{
    $a = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " WHERE id_gracza='{$id_gracza}' && vliga='" . DEFINIOWANA_GRA . "'"));
    return $a['m_w'] + $a['m_p'] + $a['m_r'];
}


function miejsce_w_u($kogo)
{
    $tablica = sortuj_uzytkownikow_z_pkt(DEFINIOWANA_GRA);
    $licz = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where id_gracza='{$kogo}' && vliga='" . DEFINIOWANA_GRA . "';"));
    if ($licz['status'] == 2) {
        return M363;
    }
    while (list($key, $value) = each($tablica)) {
        if ($value['name'] == $kogo) {
            return $i;
        }
        $i++;
    }
}


// sortuje uzytkownikow z pkt
function sortuj_uzytkownikow_z_pkt($gra)
{
    $b = 0;
    $a2 = mysql_query("SELECT * FROM " . TABELA_GRACZE . " where status='1' && vliga='{$gra}';");
    while ($rek = mysql_fetch_array($a2)) {
        $a[$b]['num3'] = $rek['ranking'];
        $a[$b]['name'] = $rek['id_gracza'];
        $b++;
    }
    $p = 0;
    $sortuj[$p]['name'] = "num3";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = TRUE;
    sortx($a, $sortuj);
    return $a;
} // sortuje uzytkownikow z pkt - END


// sortuje uzytkownikow z pkt
function sortuj_uzytkownikow_z_pkt_gdyby($gra)
{
    $b = 0;
    $a2 = mysql_query("SELECT * FROM " . TABELA_GRACZE . " where status='1' && vliga='{$gra}';");
    while ($rek = mysql_fetch_array($a2)) {
        $a[$b]['num3'] = $rek['ranking'];
        $a[$b]['name'] = $rek['id_gracza'];
        $b++;
    }
    $p = 0;
    $sortuj[$p]['name'] = "num3";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = TRUE;
    sortx($a, $sortuj);
    return $a;
} // sortuje uzytkownikow z pkt - END

?>
