<?
function reklamy_adkontekst()
{
    ?>

    <fieldset>
        <legend>Nasi sponsorzy - Strony Partnerskie</legend>


        <script type="text/javascript" charset="iso-8859-2">if (document.getElementById('adKontekst_0') == null) {
                var adKontekst_pola = new Array;
                document.write('<scr' + 'ipt type="text/javascript" charset="iso-8859-2" ' +
                    'src="http://adsearch.adkontekst.pl/akon/spliter?prid=5714&caid=95938&ns='
                    + (new Date()).getTime() + '"' + '></' + 'scri' + 'pt>');
            }
            var adc_i = adKontekst_pola.length;
            document.write("<div id='adKontekst_" + adc_i + "'>   </" + "div>");
            adKontekst_pola[adc_i] = new Object();
            adKontekst_pola[adc_i].nazwa = "adKontekst_" + adc_i;
            adKontekst_pola[adc_i].typ = 201;
            adKontekst_pola[adc_i].r = 1;
            adKontekst_pola[adc_i].c = 4;
            adKontekst_pola[adc_i].x = 750;
            adKontekst_pola[adc_i].y = 100;
            adKontekst_pola[adc_i].naroznik_lewy_gorny = 3;
            adKontekst_pola[adc_i].naroznik_prawy_gorny = 3;
            adKontekst_pola[adc_i].naroznik_lewy_dolny = 3;
            adKontekst_pola[adc_i].naroznik_prawy_dolny = 3;
            adKontekst_pola[adc_i].spacing = 4;
            adKontekst_pola[adc_i].scalenie = 2;
            adKontekst_pola[adc_i].paleta = new Object();
            adKontekst_pola[adc_i].paleta.kolor_tlo = "#C2C2C2";
            adKontekst_pola[adc_i].paleta.kolor_tytul = "#343434";
            adKontekst_pola[adc_i].paleta.kolor_opis = "#606060";
            adKontekst_pola[adc_i].paleta.kolor_url = "#7E0303";
            adKontekst_pola[adc_i].paleta.kolor_naglowek = "#AFADAE";
            adKontekst_pola[adc_i].paleta.kolor_tlo_naglowka = "#C2C2C2";
            adKontekst_pola[adc_i].paleta.kolor_ramki_naglowka = "#AFADAE";
            adKontekst_pola[adc_i].metka = "text";
            adKontekst_pola[adc_i].czy_url = true;
            adKontekst_pola[adc_i].id_koloru_metki = "5";
            adKontekst_pola[adc_i].nform = 1;
        </script>

    </fieldset>
    <?
}

function send_mail($to, $subject, $message)
{
    $headers = 'From: www.pesarena.pl@example.com' . "\r\n" .
        'Reply-To: www.pesarena.pl@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    mail($to, $subject, $message, $headers);
}


function kartka($opis, $wartosc)
{
    echo "<div class=\"card\">{$opis}<br/><span>{$wartosc}</span></div>";
}


// zamienia w tabeli meczy na status odpowiedni dla $stat
function status_meczu($stat)
{
    $tablica = array('p' => 'Rzucone, czeka na akceptacje', '1' => M555, '2' => M556, '3' => M557);
    return $tablica[$stat];
} // zamienia w tabeli meczy na status odpowiedni dla $stat - END


function sprawdz_miejsce_w_lidze($gracz, $liga)
{
    $sql = mysql_fetch_array(mysql_query("SELECT status FROM " . TABELA_LIGA_GRACZE . " WHERE id_gracza='{$gracz}' && r_id='{$liga}'"));
    return $sql['status'];
}


// sprawdza czy gracz nalezy do grupy z mozliwoscia logowania na tym samym ip
function gracze_uprzywilejowani($login, $ip)
{
    $sql = mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE ip='{$ip}'");
    while ($lista = mysql_fetch_array($sql)) {
        $tablica[] = $lista['pseudo'];
    }
    // gracz dodatkowy - jesli gracz chce sie
    // zarejestrowac drugi raz z tego samego ip
    // sprawdza mu pseudonim i dorzuca do tablicy graczy z tym samym ip
    if (defined('GRACZ_DODATKOWY')) {
        $tablica[] = GRACZ_DODATKOWY;
    }


    $sql = mysql_query("SELECT * FROM uprzywilejowani_ip");
    while ($rek = mysql_fetch_array($sql)) {
        //echo "<fieldset>";
        $exp_dobrzy = explode(";", $rek['gracze_lista']);
        $blad = 0;
        foreach ($tablica as $gracz) {

            if (in_array($gracz, $exp_dobrzy)) {
                //echo  "W ciagu znakow <b>{$rek['gracze_lista']}</b> znalazlem <b>{$gracz}</b><br/>";
            } else {
                //echo "W ciagu znakow <b>{$rek['gracze_lista']}</b> nie znalazlem <b>{$gracz}</b><br/>";
                $blad = 1;
            }
        }
        if (!empty($blad)) {
            //echo "tutaj nie wszystko spelnia wymagania!";
        } else {
            //echo "Tutaj wszystko jest ok";
            $zgoda = 1;
        }
        //echo "</fieldset>";
    }

    if (!empty($zgoda)) {
        //echo "JEST REKORD KTORY SPELNIA WSZYSTKIE WYMAGANIA!!";
    } else {
        //echo "Niestety nie przechodzisz dalej ";
    }


    if (!empty($zgoda)) {
        return 1;
    } else {
        return 0;
    }
} // sprawdza czy gracz nalezy do grupy z mozliwoscia logowania na tym samym ip - END


//zamienia statusy rozgrywek na odpowiednie dla widoku graczy
function zamien_status_rozgrywek($status)
{
    switch ($status) {
        case '0':
            return M464;
            break; // zapisy otwarte
        case 'S':
            return M465;
            break; // zapisy wstrzymane ( zamkniete )
        case 'K':
            return "Kosze utworzone";
            break;
        case 'B':
            return "Mecze Barazowe";
            break;
        case 'G':
            return "Grupy utworzone";
            break;
        case 'P':
            return "";
            break;

        case '1':
            return M466;
            break; // eliminacje w toku
        case '2':
            return M467;
            break; // eliminacje zakonczone - odpowiedni gracze wykluczeni
        case '3':
            return M468;
            break; // spotkania wlasciwe w trakcie
        case '4':
            return M469;
            break; // rozgrywki zakonczone!
        default :
            return "Oczekiwanie...";
            break;
    }
} //zamienia statusy rozgrywek na odpowiednie dla widoku graczy  - END


// zlicza z danej tabeli wszysko o podanym r_id 
function policz_wszystko_r_id($gdzie, $r_id)
{
    return mysql_num_rows(mysql_query("SELECT * FROM {$gdzie} WHERE r_id='{$r_id}' && vliga='" . DEFINIOWANA_GRA . "'"));
} // zlicza z danej tabeli wszysko o podanym r_id  - END


// sprawdza nazwe rozgrywki z tanej tabeli-listy o podanym r_id
function sprawdz_nazwe_rozgrywki($lista, $r_id)
{
    $sql = mysql_fetch_array(mysql_query("SELECT * FROM {$lista} WHERE vliga='" . DEFINIOWANA_GRA . "' && id='{$r_id}'"));
    return isset($sql['nazwa']) ? $sql['nazwa'] : 'nie wybrano';
} // sprawdza nazwe rozgrywki z tanej tabeli-listy o podanym r_id - END


function user_panel_link($gra)
{
    if (isset($_SESSION['zalogowany'])) {

        //<img src=\"grafiki/loga/".sprawdz_klub(DEFINIOWANE_ID).".png\" alt=\"\"/>
        echo "<ul>
			<li><a href=\"wszystkie,wersje,gier.htm\" title=\"" . M212 . "\">" . M63 . ": <span class=\"u\">{$gra}</span></a></li>
			<li><a href=\"\">Aktualna liga: <span class=\"u\">" . sprawdz_nazwe_rozgrywki(TABELA_LIGA_LISTA, R_ID_L) . "</span></a></li>
			<li><a href=\"\">Aktulny puchar: <span class=\"u\">" . sprawdz_nazwe_rozgrywki(TABELA_PUCHAR_DNIA_LISTA, R_ID_P) . "</span></a></li>
			<li><a href=\"\">Aktualny turniej: <span class=\"u\">" . sprawdz_nazwe_rozgrywki(TABELA_TURNIEJ_LISTA, R_ID_T) . "</span></a></li>
			
			<li><a href=\"\">Twoje miejsce w rankingu: <span class=\"u\">" . miejsce_w_u(DEFINIOWANE_ID) . "</span></a></li>
			<li><a href=\"\">Twoje punkty: <span class=\"u\">" . sprawdz_ranking_id(DEFINIOWANE_ID, DEFINIOWANA_GRA) . "pkt</span></a></li>
			
			<li><a href=\"profil,pokaz-" . DEFINIOWANE_ID . ".htm\">Pokaz moj profil</a></li>
			<li><a href=\"wyloguj.htm\" title=\"" . M125 . "\">" . M125 . "({$_SESSION['zalogowany']})</a></li>
		</ul>";


    } else {
        echo "<ul>
			<li class=\"-punktor\">OPCJE:</li>
			<li><a href=\"logowanie.htm\" title=\"" . M6 . "\">" . M6 . "</a></li>
			<li><a href=\"rejestracja.htm\" title=\"" . M7 . "\">" . M7 . "</a></li>
		</ul>";

    }

}


function formatuj_date($data)
{
    $arrLocale = array("pl_PL", "polish_pol");
    setlocale(LC_ALL, $arrLocale);
    return (strftime('%Y', strtotime($data)) > date('Y') ? 'data nieznana' : strftime('%d %B %Yr godz %H:%M', strtotime($data)));
}


function wyswietl_stronnicowanie($podmenu, $wszystkie_strony, $link_strony, $koncowka)
{
    print "<div class=\"pasek_lista\">";
    if (!empty($podmenu) && ($podmenu <= $wszystkie_strony && $podmenu > 1)) $poprzedni = $podmenu - 1; else $poprzedni = 1;
    print "<a href=\"{$link_strony}{$poprzedni}{$koncowka}\"  title=\"" . M91 . "\"><div class=\"lista_podstawowa\">&lt;-</div></a>";
    for ($i = 0; $i < $wszystkie_strony; $i++) {
        $a = $i + 1;
        if (($podmenu - 6 < $a && $a < $podmenu + 6) || ($a > $wszystkie_strony - 7)) {
            echo " <a href=\"{$link_strony}{$a}{$koncowka}\"  title=\"" . M90 . " {$a}\"><div class=\"lista_";
            if ($podmenu == $a) {
                print "aktualna";
            } else {
                print "podstawowa";
            }
            print "\">{$a}</div></a> ";
        }
        if ($podmenu + 2 == $a && $a < $wszystkie_strony - 7) {
            echo "<div class=\"lista_podstawowa\">...</div>";
        }

    }
    if (!empty($podmenu) && ($podmenu < $wszystkie_strony && $podmenu >= 1)) $nastepny = $podmenu + 1; else $nastepny = $wszystkie_strony;
    print "<a href=\"{$link_strony}{$nastepny}{$koncowka}\" title=\"" . M89 . "\"><div class=\"lista_podstawowa\">-&gt;</div></a>
	</div>";
}


function formularz_logowania2()
{


    ?>
    <form method="post" action="">
        <div class="other_login" class="display: table; padding: 40px 0;">
            <div class="buttons_login">
                <input type="hidden" name="zaloguj" value="pe"/>
                <input type="text" name="login"/>
                <input type="password" name="haslo"/>
            </div>
            <div class="submit_login">
                <input type="submit" value=""/>
            </div>
            <input type="checkbox" name="pamietaj" value="1"/>Zapamietaj
        </div>
    </form>


    <?
}


function formularz_logowania()
{
    ?>
    <div id="logowanie">
        <form method="post" action="">
            <input type="hidden" name="zaloguj" value="pe"/>
            <div class="logowanie_regal">
                <div class="logowanie_lewa"><?= M97 ?></div>
                <div class="logowanie_prawa"><input type="text" name="login" class="button_logowania"/></div>
            </div>
            <div class="logowanie_regal">
                <div class="logowanie_lewa"><?= M98 ?></div>
                <div class="logowanie_prawa"><input type="password" class="button_logowania" name="haslo"/></div>
            </div>
            <div class="logowanie_regal">
                <div class="text_center"><input type="image" src="img/zaloguj.jpg" alt="<?= M99 ?>" title="<?= M99 ?>"/>
                    <input type="checkbox" name="pamietaj" value="1"/>Zapamietaj
                </div>
            </div>
        </form>
    </div>
    <?
}


function formularz_logowania3()
{
    ?>

    <div id="n_logowanie">


        <div id="login_panel">
            <h1>Logowanie do panelu administratora</h1>
            <form method="post" action="">
                <input type="hidden" name="zaloguj" value="pe"/>
                <div class="login_submit"><input type="image" src="img/submit_login.gif"/></div>
                <div class="itt"><span><?= M97 ?></span><input type="text" name="login" class="button_text-v1"/></div>
                <div class="itt"><span><?= M98 ?></span><input type="password" name="haslo" class="button_text-v1"/>
                </div>
            </form>
        </div>


    </div>

    <?
}


function licz_komentarze_gracza($id_gracza, $typ)
{
    $licz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_OCENA_LISTA . " 
	WHERE (p1='{$id_gracza}' && g_1_ocena = '{$typ}') || (p2='{$id_gracza}' && g_2_ocena = '{$typ}')"));


    return empty($licz) ? 0 : $licz;
}

function wyswietl_legende($id)
{
    if ($id == 1) {
        note(M482 . ": <br/>
		<u>(+)</u> - " . M477 . "<br/>
		<u>(-)</u> - " . M478 . "<br/>
		<u>(0)</u> - " . M479 . "<br/>
		<u>(?)</u> - " . M480 . "<br/>
		<u>()</u> -  " . M481 . "<br/>
		", "fieldset");
    }
}

//wyswietla obrazek dla gracza 
// awans , bez zmian i spadek 
function pozycja($rek)
{
    $pozy = array('+' => M563, '-' => M564, '0' => M565);
    switch ($rek) {
        case '+':
            $how = 'gora';
            break;
        case '-':
            $how = 'dol';
            break;
        case '0':
            $how = 'none';
            break;
    }
    return "<img src=\"img/wskaznik_{$how}.png\" alt=\"{$pozy[$rek]}\" title=\"{$pozy[$rek]}\"/>";
}

// mini logo po id gracza
function mini_logo($idk)
{

    $id = sprawdz_klub($idk);
    $title = "puchar dnia, liga, wyzwania, mecze towarzyskie, pes, pro evolution soccer 2010";


    $title = "puchar dnia, liga, wyzwania, mecze towarzyskie, pes, pro evolution soccer 2010";
    if (!empty($id)) {
        if (!file_exists("grafiki/loga_mini/{$id}.png")) {
            $img = "<img src=\"img/brak_danych.png\" alt=\"$title\" title=\"$title\"	class=\"mini_logo\"/>";
        } else {
            $img = "<a href=\"druzyny-profil-" . $id . ".htm\"><img src=\"grafiki/loga_mini/" . $id . ".png\" title=\"$title\" alt=\"$title\" class=\"mini_logo\"/></a>";
        }
    } else {
        $img = "<img src=\"img/brak_danych.png\" alt=\"$title\" title=\"$title\"	class=\"mini_logo\"/>";
    }
    return $img;


}


// mini logo po id druzyny
function mini_logo_druzyny($id)
{
    if (!empty($id)) {
        if (!file_exists("grafiki/loga_mini/{$id}.png")) {
            $img = "<img src=\"img/brak_danych.png\" class=\"mini_logo\"/>";
        } else {
            $img = "<a href=\"druzyny-profil-" . $id . ".htm\"><img src=\"grafiki/loga_mini/" . $id . ".png\"  class=\"mini_logo\"/></a>";
        }
    } else {
        $img = "<img src=\"img/brak_danych.png\" class=\"mini_logo\"/>";
    }
    return $img;
}


function dodatkowe_zakladki()
{
    $opcja = czysta_zmienna_get($_GET['opcja']);
    $podmenu = czysta_zmienna_get($_GET['podmenu']);

    if ($opcja == 'ranking') {
        echo "<div class=\"srodek_news_zakladki_kol\">
			<a onclick=\"javascript:activateTab('zakladka_1_rd','rd')\"><img id=\"rd1\" src=\"grafiki/zakladki/rd_1_b.gif\" class=\"najazd\" alt=\"" . M568 . "\" title=\"" . M568 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_2_rd','rd')\"><img id=\"rd2\" src=\"grafiki/zakladki/rd_2_b.gif\" class=\"najazd\" alt=\"" . M569 . "\" title=\"" . M569 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_3_rd','rd')\"><img id=\"rd3\" src=\"grafiki/zakladki/rd_3_b.gif\" class=\"najazd\" alt=\"" . M570 . "\" title=\"" . M570 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_4_rd','rd')\"><img id=\"rd4\" src=\"grafiki/zakladki/rd_4_b.gif\" class=\"najazd\" alt=\"" . M571 . "\" title=\"" . M571 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_5_rd','rd')\"><img id=\"rd5\" src=\"grafiki/zakladki/rd_5_b.gif\" class=\"najazd\" alt=\"" . M572 . "\" title=\"" . M572 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_6_rd','rd')\"><img id=\"rd6\" src=\"grafiki/zakladki/rd_6_b.gif\" class=\"najazd\" alt=\"" . M573 . "\" title=\"" . M573 . "\"/></a>
		</div>";
    } elseif ($opcja == 'wyzwania' && $podmenu == 'wyniki') {
        echo "<div class=\"srodek_news_zakladki_kol\">
			<a onclick=\"javascript:activateTab('zakladka_1_wy','wy')\"><img id=\"wy1\" src=\"grafiki/zakladki/wy_1_b.gif\" class=\"najazd\" alt=\"" . M574 . "\" title=\"" . M574 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_2_wy','wy')\"><img id=\"wy2\" src=\"grafiki/zakladki/wy_2_b.gif\" class=\"najazd\" alt=\"" . M575 . "\" title=\"" . M575 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_3_wy','wy')\"><img id=\"wy3\" src=\"grafiki/zakladki/wy_3_b.gif\" class=\"najazd\" alt=\"" . M576 . "\" title=\"" . M576 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_4_wy','wy')\"><img id=\"wy4\" src=\"grafiki/zakladki/wy_4_b.gif\" class=\"najazd\" alt=\"" . M577 . "\" title=\"" . M577 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_5_wy','wy')\"><img id=\"wy5\" src=\"grafiki/zakladki/wy_5_b.gif\" class=\"najazd\" alt=\"" . M578 . "\" title=\"" . M578 . "\"/></a>
		</div>";
    } elseif ($opcja == 'puchar' && $podmenu == 'wyniki') {
        echo "<div class=\"srodek_news_zakladki_kol\">
			<a onclick=\"javascript:activateTab('zakladka_1_pu','pu')\"><img id=\"pu1\" src=\"grafiki/zakladki/pu_1_b.gif\" class=\"najazd\" alt=\"" . M579 . "\" title=\"" . M579 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_2_pu','pu')\"><img id=\"pu2\" src=\"grafiki/zakladki/pu_2_b.gif\" class=\"najazd\" alt=\"" . M580 . "\" title=\"" . M580 . "\"/></a>
		</div>";
    } elseif ($opcja == 'liga' && $podmenu == 'wyniki') {
        echo "<div class=\"srodek_news_zakladki_kol\">
			<a onclick=\"javascript:activateTab('zakladka_1_lig','lig')\"><img id=\"lig1\" src=\"grafiki/zakladki/lig_1_b.gif\" class=\"najazd\" alt=\"" . M568 . "\" title=\"" . M568 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_2_lig','lig')\"><img id=\"lig2\" src=\"grafiki/zakladki/lig_2_b.gif\" class=\"najazd\" alt=\"" . M568 . "\" title=\"" . M568 . "\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_3_lig','lig')\"><img id=\"lig3\" src=\"grafiki/zakladki/lig_3_b.gif\" class=\"najazd\" alt=\"" . M568 . "\" title=\"" . M568 . "\"/></a>
		</div>";
    } elseif ($opcja == 'profil,edytuj') {
        echo "<div class=\"srodek_news_zakladki_kol\">
			<a onclick=\"javascript:activateTab('zakladka_1_per','per')\"><img id=\"per1\" src=\"grafiki/zakladki/per_1_b.gif\" class=\"najazd\" alt=\"\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_2_per','per')\"><img id=\"per2\" src=\"grafiki/zakladki/per_2_b.gif\" class=\"najazd\" alt=\"\"/></a>
			<a onclick=\"javascript:activateTab('zakladka_3_per','per')\"><img id=\"per3\" src=\"grafiki/zakladki/per_3_b.gif\" class=\"najazd\" alt=\"\"/></a>
		</div>";
    }

}


// metoda standardowego sortowania num3=DESC,num2=DESC,num1=ASC
function sortowanie_d_d_a()
{
    $p = 0;
    $sortuj[$p]['name'] = "num3";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = trUE;
    $sortuj[$p]['name'] = "num2";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = trUE;
    $sortuj[$p]['name'] = "num1";
    $sortuj[$p]['sort'] = "DESC";
    $sortuj[$p++]['case'] = trUE;
    $sortuj[$p]['name'] = "name";
    $sortuj[$p]['sort'] = "ASC";
    $sortuj[$p++]['case'] = FALSE;
    return $sortuj;
} // metoda standardowego sortowania num3=DESC,num2=DESC,num1=ASC - END


// wyswietla uczestnikow gry
function pokaz_uczestnikow_gry($gra, $gdzie, $r_id)
{
    $tab_puchar = array(1 => "<img src=\"img/activ.png\" alt=\"\" style=\"float:left;\"/><div style=\"margin-top:5px;\">Gra</div>", 0 => "<img src=\"img/no-activ.png\" alt=\"\" style=\"float:left;\"/><div style=\"margin-top:5px;\">Odpadl</div>");
    $zapytanie = mysql_query("SELECT 
	wh.id_gracza, wh.klub, uz.id, uz.gadu, wh.status
	FROM {$gdzie} wh 
	LEFT JOIN " . TABELA_UZYTKOWNICY . " uz ON wh.id_gracza=uz.id where wh.vliga='" . DEFINIOWANA_GRA . "' && wh.r_id='{$r_id}' order by uz.pseudo ASC;");
    echo "<fieldset><legend>" . M484 . "</legend>";
    naglowek_uczestnicy_pucharu_dnia();
    while ($rek = mysql_fetch_array($zapytanie)) {
        print "<tr" . kolor($a++) . ">
			<td class=\"text_center\">{$a}</td>
			<td>" . linkownik('profil', $rek[0], '') . "</td>
			<td>" . linkownik('gg', $rek[0], '') . "</td>
			<td style=\"width:" . ($gdzie == TABELA_LIGA_GRACZE ? 220 : 0) . "px;\">";
        if ($gdzie == TABELA_LIGA_GRACZE) {
            $tab = zlicz_mecze_zakonczone($rek[0]);
            $max_width = 200;
            $procent = round($tab['ma_zak'] / $tab['ma_wsz'] * 100, 0);
            $procent_a = round($tab['ma_zak'] / $tab['ma_akt'] * 100, 0);
            $width = round(($procent / 100) * $max_width, 0);
            $width_a = round(($procent_a / 100) * $max_width, 0);


            echo "
				<ul class=\"paski_procent\" title=\"Z posrod {$tab['ma_wsz']} wszystkich(aktywowane + nieaktywowane) meczy ten gracz rozegral ich: {$procent}%\">
					<li class=\"pasek_czarny_l\"></li>
					<li style=\"width:{$width}px;\" class=\"pasek_czarny\">{$procent}%</li>
					<li class=\"pasek_czarny_p\"></li>
				</ul>
				<br/>
				<ul class=\"paski_procent\" title=\"Z posrod {$tab['ma_akt']} aktywowanych meczy ten gracz rozegral ich: {$procent_a}%\">
					<li class=\"pasek_czerwony_l\"></li>
					<li style=\"width:{$width_a}px;\" class=\"pasek_czerwony\">{$procent_a}%</li>
					<li class=\"pasek_czerwony_p\"></li>
				</ul>";
        }
        echo "</td>
			<td>" . mini_logo_druzyny($rek[1]) . "</td>
			<td>" . sprawdz_ranking_id($rek[0], DEFINIOWANA_GRA) . "</td>
			<td>" . ($gdzie == TABELA_LIGA_GRACZE ? "<div class=\"ranking_g\">{$rek[4]}</a>" : $tab_puchar[$rek[4]]) . "</td>
			<td>" . linkownik('graj', $rek[2], '') . "</td>
		</tr>";
    }
    end_table();
    echo "</fieldset>";
} // wyswietla uczestnikow gry - END


?>
