<?
// deklaracja zmiennych 
$edycja = (int)$_GET['edycja'];
$usun = (int)$_GET['usun'];

require_once('include/admin/functions/admin.news.php');
require_once('include/admin/funkcje/function.table.php');

$dodaj = $_POST['dodaj'];
$popraw = $_POST['popraw'];
$cmd = (int)$_POST['cmd'];
$tytul = czysta_zmienna_post($_POST['tytul']);
$tresc = czysta_zmienna_post($_POST['tresc']);
$obrazek = czysta_zmienna_post($_POST['obrazek']);
$coteraz = "dodaj";

$file = $_FILES['obraz']['name'];
$pathinfo = pathinfo($file);
$rozszerzenie = $pathinfo['extension'];
$mozliwe = array("jpg", "png", "gif", "bmp");

// deklaracja zmiennych - END


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {


        if ($podmenu == "show") {
            if (!in_array('news_edytuj', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
            } else { ###
                // pobranie wartosci w bazy do edycji
                if (!empty($edycja)) {
                    $sprawdz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_NEWS . " where id='{$edycja}'"));
                    if ($sprawdz == 1) {
                        $wynik = mysql_query("SELECT * FROM " . TABELA_NEWS . " where id='{$edycja}';");
                        $rekord = mysql_fetch_array($wynik);
                        formularz_dodawania_newsa_admin($rekord, "popraw");
                    } else {
                        note(admsg_newsNoExists, "blad");
                    }
                } // pobranie wartosci w bazy do edycji - END


                // zatwierdzenie edycji newsa
                if (!empty($popraw)) {
                    funkcja_popraw_newsa($tytul, $tresc, $obrazek, $edycja);
                } // zatwierdzanie edycji newsa - END

            } ###

            if (!empty($usun)) {
                if (!in_array('news_usun', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
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
            $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_NEWS));
            $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
            $start = ($podmenu - 1) * $na_stronie;

            $a2 = mysql_query("SELECT * FROM " . TABELA_NEWS . "  order by id  DESC LIMIT {$start},{$na_stronie};");
            $b = 1;
            while ($as2 = mysql_fetch_array($a2)) {
                $arr[$b][] = $b++;
                $arr[$b][] = $as2['tytul'];
                $arr[$b][] = htmlentities(skroc_text($as2['tresc'], 200));
                $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
                $arr[$b][] = "<a href=\"" . AKT . "&usun={$as2['id']}\" class=\"i-delete\"></a>";
            }


            $table = new createTable('adminFullTable center', admsg_tableWithNews);
            $table->setTableHead(array(admsg_id, admsg_tytul, admsg_tresc, admsg_edytuj, admsg_usun), "adminHeadersClass");
            $table->setTableBody($arr);
            echo $table->getTable();


            wyswietl_stronnicowanie($podmenu, $wszystkie_strony, AKT . "&strona=", '');
            // wyswietlam wszystkie newsy, stronnicuje - END
        } elseif ($podmenu == "add") {
            if (!in_array('news_dodaj', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
            } else { ###
                if (!empty($dodaj)) {
                    funkcja_dodaj_newsa($tytul, $tresc, $obrazek, $szefuniu);
                } else {
                    formularz_dodawania_newsa_admin($rekord, $coteraz);
                }
            } ###
        } elseif ($podmenu == "image") {
            if (!in_array('news_image', explode(',', POZIOM_U_A))) {
                return note(admsg_accessDenied, "blad");
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


    } else {
        note("Najprawodopodobniej tutaj nie masz dostepu!!", "blad");
    }

}
wybor_gry_admin(20);
?>

?>
