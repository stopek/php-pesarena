<?
//wyswietla formularz przyjmujacy tablice $rekord z wartosciami pol
//oraz $coteraz z nazwa przycisku i akcja (dodaj,edytuj)
//odpowiada za dodawanie oraz edytowanie newsow
function formularz_dodawania_newsa_admin($rekord, $coteraz)
{
    //wszystkie nazwy plikow ktore sa wykluczone z wyswietlenia w select.
    $wyklucz = array('.', '..');


    echo "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\">" . admsg_searchUser . "</li>
						<li class=\"glowne_bloki_zawartosc\">
								<form action=\"\" method=\"post\">
								<input type=\"hidden\" name=\"{$coteraz}\" value=\"{$coteraz}\"/>
									<ul class=\"adminAccessOption adminFields\">
										<li><input type=\"text\" name=\"tytul\" value=\"{$rekord['tytul']}\"/><span>tytul newsa</span></li>
										<li>
										<select name=\"obrazek\">";
    $dir = opendir('grafiki/news');
    while ($kat = readdir($dir)) {
        echo !in_array($kat, $wyklucz) ? "<option value=\"{$kat}\" " . ($kat == $rekord['obrazek'] ? "selected" : "") . ">{$kat}" : "";
    }
    echo "
										</select><span>ikona newsa</span>
										</li>
										<li><textarea class=\"ckeditor\"  id=\"editor1\" name=\"tresc\" rows=\"10\">{$rekord['tresc']}</textarea></li>
										<li><span></span></li>
									</ul>
								</form>
						</li>
					</ul>";


}


function funkcja_popraw_newsa($tytul, $tresc, $obrazek, $edycja)
{
    if (mysql_query("UPDATE " . TABELA_NEWS . " SET tytul='{$tytul}',tresc='{$tresc}',obrazek='{$obrazek}' WHERE id='{$edycja}';")) {
        note("News zostal poprawiony!", "info");
        przeladuj($_SESSION['starsza']);
    } else {
        note('Blad podczas edycji newsa!', 'blad');
    }
}

function funkcja_usun_newsa($usun)
{
    $sprawdz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_NEWS . " where id='{$usun}'"));
    if ($sprawdz == 1) {
        if (mysql_query("DELETE FROM " . TABELA_NEWS . " where id='{$usun}'")) {
            note("News zostal usuniety!", "info");
        } else {
            note('Wystapil blad podczas usowania newsa!', 'blad');
        }
    } else {
        note("News o podanym id nie istnieje!", "blad");
    }

}

function funkcja_uploaduj_obrazek($rozszerzenie, $mozliwe, $url)
{
    if (in_array(strtolower($rozszerzenie), $mozliwe)) {
        $nazwa1 = basename($_FILES['obraz']['name']);
        $location1 = $url . "/" . basename($_FILES['obraz']['name']);
        if (move_uploaded_file($_FILES['obraz']['tmp_name'], $location1)) {
            note("Obraz dodany!", "info");
        } else {
            note("Blad podczas dodawania obrazu!", "blad");
        }
    } else {
        note("Uploadowany plik musi byc obrazem!", "blad");

    }
}

function funkcja_dodaj_newsa($tytul, $tresc, $obrazek, $szefuniu)
{
    if (!empty($tytul) && !empty($tresc)) {
        /*
            //czesc odpowiedzialna za dodawanie newsow do forum
            con_forum($db_);
            mysql_query("INSERT INTO phpbb_topics	values('','80','0','0','1','0','{$tytul}','68',NOW(),'0','1','0','0','0','0','','www.pesarena.pl','FF0000','','68','www.pesarena.pl','FF0000','{$tresc}',NOW(),NOW(),'0','0','0','','0','0','1','0','0')");
            $bb=mysql_fetch_array(mysql_query("SELECT topic_id FROM phpbb_topics ORDER BY `topic_id` DESC "));
            mysql_query ("INSERT INTO ".TABELA_NEWS." values('','{$tytul}','{$tresc}','{$obrazek}',NOW(),'{$szefuniu}','{$bb[0]}');");
            mysql_query("INSERT INTO phpbb_posts 	values('','{$bb[0]}','80','68','0','127.0.0.1','-','1','0','1','1','1','1','','{$tytul}','{$tresc}','cfe0da5207d442cfb557383a3642a40f','0','','lvkytqej','1','0','','0','0','0')",$db_);
            mysql_close($db_);
            con_lost();
        */
        if (mysql_query("INSERT INTO " . TABELA_NEWS . " values('','{$tytul}','{$tresc}','{$obrazek}',NOW(),'{$szefuniu}','{$bb[0]}','{$youtube}');")) {
            note("News na stronie dodany prawidlowo", "info");
        } else {
            note("Wystapil blad podczas dodawania newsa na stronie!", "blad");
        }
    } else {
        note("Musisz dodac pola obowiazkowe: tytul,dzial i tresc!", "blad");
    }
}

function popraw_nazwe_zdjecia_przy_pokazywaniu($nazwa)
{
    $z = array('-', ' ', '�', '�', '�', '�', '�', '�', '�', '�');
    $na = array('_', '_', 'n', 'c', 'z', 'o', 'l', 'a', 'e', 'z');
    return str_replace($z, $na, $nazwa);
}


function podkategorie_galeria($gdzie)
{
    if (!empty($_GET['usun,zdjecie'])) {
        if (unlink($gdzie . '/' . $_GET['usun,zdjecie'])) {
            note('Zdjecie zostalo usuniete!', 'info');
        } else {
            note('Blad podczas usuwania zdjecia', 'blad');
        }
        przeladuj($_SESSION['stara']);
    }


    $katalog = opendir($gdzie);
    $a = 0;
    while ($plik = readdir($katalog)) {
        if (filetype($gdzie . '/' . $plik) == 'file' && $plik != '.' && $plik != '..' && file_exists($gdzie . '/' . $plik)) {
            $info = TRUE;
            list($width, $height, $type, $attr) = getimagesize($gdzie . '/' . $plik);


            if (!empty($_POST['nowanazwa']) && $_POST['staranazwa'] == $plik) {
                if (rename($gdzie . '/' . $plik, $gdzie . '/' . popraw_nazwe_zdjecia_przy_pokazywaniu($_POST['nowanazwa']) . $_POST['ro'])) {
                    $ta = array('news');
                    foreach ($ta as $tabela) {
                        $u = mysql_query("UPDATE {$tabela} SET obrazek='" . popraw_nazwe_zdjecia_przy_pokazywaniu($_POST['nowanazwa']) . "' where image='{$plik}'");
                    }
                    przeladuj($_SESSION['stara']);
                } else {
                    note('Zle jest ', 'blad');
                }
            }


            $a++;
            echo "<div class=\"news_galery\">
				<ul>
					<li class=\"news_galery_header\">
						<ul>
							<li><a href=\"" . AKT . "&zmien,nazwe=$plik\"><img src=\"img/galeria_news_zmien_nazwe.gif\" alt=\"\"/></a></li>
							<li><a href=\"$gdzie/$plik\" target=\"_blank\" title=\"kliknij aby zobaczyc w pe�nych rozmiarach!\"><img src=\"img/galeria_news_pokaz.gif\" alt=\"\"/></a></li>
							<li><a href=\"" . AKT . "&usun,zdjecie=$plik\"  onclick=\"return confirm('Czy napewno chcesz usunac te zdjecie?')\"><img src=\"img/galeria_news_usun.gif\" alt=\"\"/></a></li>
						</ul>
					</li>
					<li class=\"news_galery_image\"><img src=\"$gdzie/$plik\" alt=\"zdjecie\"/></li>
					<li class=\"news_galery_description\">
						<ul>
							<li>Waga: " . round(filesize($gdzie . '/' . $plik) / 1000, 2) . "KB </li>
							<li>Wysokosc: " . $height . "px  Szerokosc: " . $width . "px </li>
							<li class=\"news_galery_no\">{$a}</li>
							<li>";

            if (isset($_GET['zmien,nazwe']) && $_GET['zmien,nazwe'] == $plik && empty($_POST['nowanazwa'])) {
                list($na, $ro) = split('[.]', $plik);
                echo '
									<form method="post" action="">
										<input type="hidden" name="staranazwa" value="' . $na . '.' . $ro . '"/>
										<input type="text" name="nowanazwa" value="' . $na . '" class="text_add" />
										<input type="hidden" name="ro" value=".' . $ro . '"/>
										<input type="submit" name="" value="ok"/>
									</form>';
            }

            echo "</li>
						</ul>
					</li>
				</ul>
			</div>
			";


        }
    }
    if (empty($info)) {
        note('W tej kategori brak zdj��!', 'blad');
    }
}

?>