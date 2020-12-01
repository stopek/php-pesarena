<?
// deklaracja zmiennych 
$edycja = (int)$_GET['edycja'];
$usun = (int)$_POST['usun'];
$dodaj = czysta_zmienna_post($_POST['dodaj']);
$popraw = czysta_zmienna_post($_POST['popraw']);
$news_l = czysta_zmienna_post($_POST['news_l']);
$tytul = czysta_zmienna_post($_POST['tytul']);
$tresc = czysta_zmienna_post($_POST['tresc']);
$obrazek = czysta_zmienna_post($_POST['obrazek']);
$youtube = czysta_zmienna_post($_POST['youtube']);
$cmd = (int)$_POST['cmd'];
$coteraz = dodaj;
$file = $_FILES['obraz']['name'];
$pathinfo = pathinfo($file);
$rozszerzenie = $pathinfo['extension'];
$mozliwe = array("jpg", "png", "gif", "bmp");
// deklaracja zmiennych - END
require_once('include/admin/functions/admin.news.php');


if ($podmenu == "show") {
    if (!in_array('news_edytuj', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        // pobranie wartosci w bazy do edycji
        if (!empty($edycja)) {
            $sprawdz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_NEWS . " where id='{$edycja}'"));
            if ($sprawdz == 1) {
                $wynik = mysql_query("SELECT * FROM " . TABELA_NEWS . " where id='{$edycja}';");
                $rekord = mysql_fetch_array($wynik);
                formularz_dodawania_newsa_admin($rekord, "popraw");
            } else {
                note("News o podanym identyfikatorze nie istnieje!", "blad");
            }
        } // pobranie wartosci w bazy do edycji - END


        // zatwierdzenie edycji newsa
        if (!empty($popraw)) {
            funkcja_popraw_newsa($tytul, $tresc, $obrazek, $edycja);
        } // zatwierdzanie edycji newsa - END

    } ###

    if (!empty($usun)) {
        if (!in_array('news_usun', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            funkcja_usun_newsa($usun);
        } ###
    }


    // wyswietlam wszystkie newsy, stronnicuje
    $na_stronie = 15;
    $podmenu = (int)$_GET['strona'];
    if (!$podmenu) {
        $podmenu = 1;
    }
    $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM news"));
    $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
    $start = ($podmenu - 1) * $na_stronie;

    print "<br/><table border=\"1\" width=\"100%\" frame=\"void\">";
    print "<tr class=\"naglowek\">
				<td>tytul</td>
				<td>tresc</td>
				<td  width=\"30\">edytuj</td>
				<td  width=\"30\">usun</td>
				
			</tr>";
    $a2 = mysql_query("SELECT * FROM " . TABELA_NEWS . " order by id  DESC LIMIT {$start},{$na_stronie};");
    while ($as2 = mysql_fetch_array($a2)) {
        print "<tr" . kolor($a++) . ">
						<td>" . skroc_text($as2['tytul'], 10) . " [...]</td>
						<td>" . htmlentities(skroc_text($as2['tresc'], 200)) . " [...]</td>
						<td><a href=\"administrator.php?opcja=20&podmenu=show&edycja={$as2['id']}\"><img src=\"img/edit.png\" alt=\"edytuj\" title=\"edytuj\"/></a></td>
						<td><form method=\"post\" action=\"\"><input type=\"hidden\" name=\"usun\" value=\"{$as2['id']}\"/><input type=\"image\" src=\"img/delete.png\" alt=\"usun\" title=\"usun\" onclick=\"return confirm('Czy jestes pewien ze chcesz usunac news_la?');\"/></form></tD>
						</tr>";
    }
    end_table();
    wyswietl_stronnicowanie($podmenu, $wszystkie_strony, AKT . "&strona=", '');
    // wyswietlam wszystkie newsy, stronnicuje - END
} elseif ($podmenu == "add") {
    if (!in_array('news_dodaj', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        if (!empty($dodaj)) {
            funkcja_dodaj_newsa($tytul, $tresc, $obrazek, $szefuniu);
        } else {
            formularz_dodawania_newsa_admin($rekord, $coteraz);
        }
    } ###
} elseif ($podmenu == "image") {
    if (!in_array('news_image', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        if (!empty($cmd)) {
            funkcja_uploaduj_obrazek($rozszerzenie, $mozliwe, 'grafiki/news');
        }

        // wyswietlanie formularza do uploadowania obrazu

        echo "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\">Dodawanie obrazu do newsa</li>
						<li class=\"glowne_bloki_zawartosc\">
						
						
							<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">
								<input type=\"hidden\" name=\"cmd\" value=\"1\"/>
								<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"512000\"/>
								<input name=\"obraz\" type=\"file\" />
								<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
							</form>
							
						</li>
					</ul>";

        // wyswietlanie formularza do uploadowania obrazu - END

        podkategorie_galeria("grafiki/news");

    } ###
}
?>
