<?


function pkt_za_moja_druzyne($id)
{
    $my = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_DRUZYNY . " WHERE id='{$id}';"));
    return $my['punkty'];
}


// wysweitla podkategorie w select $other jesli ma byc onSelect=submit, $id z kategori
function wybor_klubu($post_name)
{
    $sql = mysql_query("SELECT id,nazwa,punkty FROM " . TABELA_DRUZYNY . " WHERE view='1' ORDER BY nazwa");
    $ret = "
	<tr class=\"naglowek\">
		<td colspan=\"2\" class=\"text_center\">
			<select name=\"{$post_name}\"  onchange=\"pokaz(this.form);\">
				<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ciag = $rek['id'] . "-" . $rek['punkty'];
        $ret .= "<option value=\"{$ciag}\" " . ($_POST[$post_name] == $ciag ? 'selected=selected' : '') . ">{$rek['nazwa']} - {$rek['punkty']}pkt.</option>";
    }
    $ret .= "</select>
		</td>
	</tr>";
    echo $ret;
}

// wysweitla podkategorie w select $other jesli ma byc onSelect=submit, $id z kategori
function wybor_klubu_czysto($post_name, $other)
{
    $sql = mysql_query("SELECT id,nazwa,punkty FROM druzyny WHERE view='1' ORDER BY nazwa");
    $ret = "
		<select name=\"{$post_name}\" {$other}>
			<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\">{$rek['nazwa']} - {$rek['punkty']}pkt.</option>";
    }
    $ret .= "</select>
	";
    echo $ret;
}

function createSelectWithTeams($post_name, $other)
{
    $sql = mysql_query("SELECT id,nazwa,punkty FROM druzyny WHERE view='1' ORDER BY nazwa");
    $ret = "
		<select name=\"{$post_name}\" {$other}>
			<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\">{$rek['nazwa']} - {$rek['punkty']}pkt.</option>";
    }
    $ret .= "</select>
	";
    return $ret;
}


// wysweitla podkategorie w select $other jesli ma byc onSelect=submit, $id z kategori
function wybor_klubu_wg_kategori($post_name, $other, $id_kat)
{
    $sql = mysql_query("SELECT p._id_kat,p._id_druzyny,   k.id,k.nazwa,   d.id, d.nazwa, d.punkty 	
	FROM " . TABELA_DRUZYNY_POLACZ . " p left join
	" . TABELA_DRUZYNY_KATEGORIE . " k ON p._id_kat = k.id left join
	" . TABELA_DRUZYNY . " d ON p._id_druzyny = d.id  WHERE p._id_kat='{$id_kat}'");
    $ret = "
		<select name=\"{$post_name}\" {$other}>
			<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\">{$rek[5]} - {$rek[6]}pkt.</option>";
    }
    $ret .= "</select>
	";
    echo $ret;
}


function popraw_nazwe_zdjecia($nazwa)
{
    $z = array('-', ' ', '�', '�', '�', '�', '�', '�', '�', '�');
    $na = array('_', '_', 'n', 'c', 'z', 'o', 'l', 'a', 'e', 'z');
    return str_replace($z, $na, $nazwa);
}

function skaluj_logo($src_open, $src_save, $szerokosc_definiowana)
{
    $obrazek = imagecreatefromPng($src_open);
    $szerokosc = imagesx($obrazek);
    $wysokosc = imagesy($obrazek);
    $szerokosc_koncowa = $szerokosc_definiowana;
    $proporcja1 = $szerokosc / $szerokosc_definiowana;
    $wysokosc_koncowa = $wysokosc / $proporcja1;
    $imgOut1 = imagecreatetruecolor($szerokosc_koncowa, $wysokosc_koncowa);
    imagealphablending($imgOut1, false);
    imagesavealpha($imgOut1, true);
    imagerectangle($imgOut1, 0, 0, $szerokosc_koncowa, $wysokosc_koncowa, imagecolorallocate($imgOut1, 0, 0, 0));
    $dx1 = 0;
    $dy1 = 0;
    $dw1 = $szerokosc_koncowa;
    $dh1 = $wysokosc_koncowa;
    if ($szerokosc_koncowa * $wysokosc != $wysokosc_koncowa * $szerokosc) {
        if ($szerokosc > $wysokosc) {
            $dh1 = ($dw1 * $wysokosc) / $szerokosc;
            $dw1 = $szerokosc_koncowa;
            $dy1 = ($wysokosc_koncowa - $dh1) / 2;
        } else {
            $dh1 = $wysokosc_koncowa;
            $dw1 = ($dh1 * $szerokosc) / $wysokosc;
            $dx1 = ($szerokosc_koncowa - $dw1) / 2;
        }
    }
    imagecopyresampled($imgOut1, $obrazek, $dx1, $dy1, 0, 0, $dw1, $dh1, $szerokosc, $wysokosc);
    imagepng($imgOut1, $src_save);
    imagedestroy($obrazek);
    imagedestroy($imgOut1);

}

function funkcja_uploadujaca($mozliwe, $sciezka, $id)
{
    $file = $_FILES['userfile']['name'];
    $pathinfo = pathinfo($file);
    $rozszerzenie = $pathinfo['extension'];

    if (in_array(strtolower($rozszerzenie), $mozliwe)) {
        $nazwa = $id . "." . $rozszerzenie;
        $location = $sciezka . "/loga/" . $nazwa;
        $location_mini = $sciezka . "/loga_mini/" . $nazwa;
        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $location)) {
            skaluj_logo($location, $location, 95);
            skaluj_logo($location, $location_mini, 25);


            note("Plik zostal zauploadowany pomyslnie, miniatura loga utworzona!", "info");
        } else {
            note("Blad podczas uploadu pliku!", "blad");
        }
    } else {
        note("Uploadowany plik nie spelnia kryteriow zwiazanych z rozszezeniem!", "blad");
    }
}

function dodaj_druzyne_admin($nazwa, $punkty, $view)
{
    if (!empty($nazwa) && !empty($punkty) && isset($_FILES['userfile']['name'])) {
        if (mysql_query("INSERT INTO " . TABELA_DRUZYNY . " VALUES('','{$punkty}','{$nazwa}','{$view}')")) {
            note("Druzyna <b>{$nazwa}</b> dodana!", "info");
            funkcja_uploadujaca(array('png'), 'grafiki', mysql_insert_id());
            //przeladuj($_SESSION['stara']);
        } else {
            note("Blad podczas dodawania druzyny", "blad");
        }
    } else {
        note("Musisz wypelnic wszystkie pola", "blad");
    }
}

function dodaj_druzyne()
{

    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">dodaj nowa druzyne</li>
		<li class=\"glowne_bloki_zawartosc\">
			<div class=\"regal_admin\"> 
			<fieldset>
				<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">
				<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"512000\"/>
				Nazwa:<input type=\"text\" name=\"nazwa_nowa_druzyna\" size=\"30\"/><br/>
				Punkty:<input type=\"text\" name=\"punkty_nowa_druzyna\" size=\"2\"/><br/>
				<input type=\"checkbox\" name=\"view\" value=\"1\"/>Wyswietl na stronie<br/>
				Logo: <input type=\"file\" name=\"userfile\"/><br/>
				<input type=\"submit\" name=\"dodaj\" value=\"Dodaj Druzyne\"/>
				</form>
			</fieldset>
			<fieldset>
				<form method=\"get\" action=\"administrator.php?opcja=28&podopcja=1\">
				<input type=\"hidden\" name=\"opcja\" value=\"28\"/><input type=\"hidden\" name=\"podopcja\" value=\"1\"/>
				Przypisz druzyne: " . select_kategorie("onchange=\"submit()\"", "kategoria_przypisz") . " 
				" . select_podkategorie("onchange=\"submit()\"", "podkategoria_przypisz", (int)$_GET['kategoria_przypisz']) . "
				</form>
			</fieldset>
			</div>
		</li>
	</ul>";


}

function przypisz_druzyne($id_druzyny, $sposob, $podkategoria)
{
    if (!in_array('druzyny_przypisz', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        switch ($sposob) {
            case 1:
                //jesli w bazie jest >=2 rekordow o podanych parametrach to usun wszystko
                if (mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_DRUZYNY_POLACZ . " WHERE _id_kat='{$podkategoria}' && _id_druzyny = '{$id_druzyny}'")) >= 2) {
                    mysql_query("DELETE  FROM " . TABELA_DRUZYNY_POLACZ . " WHERE _id_kat='{$podkategoria}' && _id_druzyny = '{$id_druzyny}'");
                    echo "!!!";
                }

                if (mysql_query("INSERT INTO druzyny_polacz VALUES('{$podkategoria}','{$id_druzyny}')")) {

                    note("Druzyna o <b>id: {$id_druzyny}</b> zostala przypisana do kategori o <b>id: {$podkategoria}</b>", "info");
                    //przeladuj($_SESSION['stara']);
                } else {
                    note("Wystapil blad podczas  przypisu druzyny do kategori!", "blad");
                }
                break;
            case 0:
                if (mysql_query("DELETE FROM druzyny_polacz WHERE _id_kat = '{$podkategoria}' && _id_druzyny='{$id_druzyny}'")) {
                    note("Przypisanie druzyny o <b>id: {$id_druzyny}</b> do kategori o <b>id: {$podkategoria}</b> zostalo usuniete!", "info");
                    przeladuj($_SESSION['stara']);
                } else {
                    note("Wystapil blad podczas usowania przypisu druzyny do kategori!", "blad");
                }
                break;
        }
    }###
}

function renameTeamLogo($id)
{
    if (!empty($_FILES['userfile']['name'])) {
        if ((@unlink("grafiki/loga/{$id}.png") && @unlink("grafiki/loga_mini/{$id}.png")) || (!file_exists('grafiki/loga_mini/' . $id . '.png')) || (!file_exists('grafiki/loga/' . $id . '.png'))) {
            note("Logo usuniete", "info");
            funkcja_uploadujaca(array('png'), 'grafiki', $id);
        }
    } else {
        note("Brak loga", "blad");
    }
}

function usun_druzyne($id)
{
    if (mysql_query("DELETE FROM druzyny WHERE id='{$id}'") && mysql_query("DELETE FROM druzyny_polacz WHERE _id_druzyny='{$id}'")) {
        note("Druzyna z bazy usunieta!", "info");
    }
    if (unlink("grafiki/loga/{$id}.png")) {
        note("Logo (duze) usuniete!", "info");
    }
    if (unlink("grafiki/loga_mini/{$id}.png")) {
        note("Logo (male) usuniete!", "info");
    }
}

function wyswietl_druzyny_admin()
{
    if ($_POST['przypisz_dodaj_usun']) {
        przypisz_druzyne((int)$_POST['id'], (int)$_POST['new_przypisz'], (int)$_GET['podkategoria_przypisz']);
    }
    if ($_POST['renameTeamLogo']) {
        renameTeamLogo($_POST['idD']);
    }


    $pp = (int)$_GET['podkategoria_przypisz'];
    $sql = mysql_query("SELECT d.id, d.nazwa, d.punkty, d.view ,p._id_kat, p._id_druzyny, 
	(SELECT count(c._id_kat) FROM " . TABELA_DRUZYNY_POLACZ . " c WHERE c._id_kat='{$pp}' && c._id_druzyny=d.id)  FROM 
	" . TABELA_DRUZYNY . " d LEFT JOIN 
	" . TABELA_DRUZYNY_POLACZ . " p ON d.id=p._id_druzyny  GROUP BY d.id ORDER BY nazwa ASC");
    $b = 1;
    while ($rek = mysql_fetch_array($sql)) {

        $createForm[0] = "
			<form method=\"post\" action=\"\">
			<input type=\"hidden\" name=\"id\" value=\"{$rek[0]}\"/>
			<input type=\"hidden\" name=\"new_view\" value=\"" . ($rek['view'] == 1 ? '0' : '1') . "\"/>";
        $createForm[1] = "<input type=\"text\" name=\"nazwa\" value=\"{$rek[1]}\"/>";
        $createForm[2] = "<input type=\"text\" name=\"punkty\" size=\"2\" value=\"{$rek[2]}\"/>";
        $createForm[4] =
            (!empty($_GET['kategoria_przypisz']) && !empty($_GET['podkategoria_przypisz']) ? "
				<input type=\"hidden\" name=\"new_przypisz\"  value=\"" . ($rek[6] == 1 ? '0' : '1') . "\"/>
				<img src=\"img/" . ($rek[6] == 1 ? 'oki.png' : 'nie.png') . "\" alt=\"\"/>
				<input type=\"submit\" name=\"przypisz_dodaj_usun\" value=\"" . ($rek[6] == 1 ? 'usun' : 'dodaj') . "\"/>
			" : "-");
        $createForm[3] = "<input type=\"submit\" name=\"zapisz\" value=\"zapisz\"/>";
        $createForm[5] = "<input type=\"submit\" name=\"view\" value=\"" . ($rek['view'] == 1 ? 'ukryj' : 'pokaz') . "\"/>";
        $createForm[6] = "</form>";


        $arr[$b][] = $b++;
        $arr[$b][] = $createForm[0] . $createForm[1];
        $arr[$b][] = $createForm[2];
        $arr[$b][] = $createForm[3];
        $arr[$b][] = $createForm[4];
        $arr[$b][] = $createForm[5] . $createForm[6];

        $arr[$b][] = "<img src=\"img/" . ($rek[3] == 1 ? 'oki.png' : 'nie.png') . "\" alt=\"\"/>";
        $arr[$b][] = "<a href=\"" . AKT . "&usun_druzyne={$rek[0]}\" onclick=\"return confirm('" . admsg_confirmQuestion . "')\">usun</a>";
        $arr[$b][] = "<img src=\"grafiki/loga_mini/{$rek['0']}.png\" alt=\"logo\"/>";
        $arr[$b][] = "
		<form enctype=\"multipart/form-data\" action=\"\" method=\"post\">
			<input type=\"hidden\" name=\"idD\" value=\"{$rek[0]}\"/>
			<input type=\"file\" name=\"userfile\"/><input type=\"submit\" name=\"renameTeamLogo\" value=\"ok\"/>
		</form>";
    }


    require_once('include/admin/funkcje/function.table.php');


    $table = new createTable('adminFullTable', "");
    $table->setTableHead(array('', 'nazwa druzyny', 'punkty', 'zapisz zmieny', 'przypisywanie', 'widocznosc', 'status', 'usun', 'logo', 'zmien logo'), 'adminHeadersClass');
    $table->setTableBody($arr);
    echo $table->getTable();


}

function edytuj_druzyny_admin($id, $punkty, $nazwa, $other)
{
    if (!empty($id)) {
        if (!empty($nazwa) && !empty($punkty)) {
            if (eregi("^([a-zA-Z0-9_-])+$", str_replace(' ', '_', $nazwa))) {
                if (mysql_query("UPDATE druzyny SET nazwa='{$nazwa}', punkty='{$punkty}' {$other} WHERE id='{$id}'")) {
                    note("Poprawa druzyny zakonczona!", "info");
                } else {
                    note("Wystapil blad w zapytaniu!", "blad");
                }
            } else {
                note("Nazwa: <b>{$nazwa}</b> nie spelnia wymagan odnosnie nazwy!", "blad");
            }
        } else {
            note("Musisz wypelnic pole nazwa i punkty", "blad");
        }
    }
}

function dodaj_kategorie_glowna($nazwa)
{
    if (!empty($nazwa)) {
        if (mysql_query("INSERT INTO druzyny_kategorie_glowne VALUES('','{$nazwa}')")) {
            note("Kategoria <b>{$nazwa}</b> dodana!", "info");
            przeladuj($_SESSION['stara']);
        } else {
            note("Blad podczas dodawania kategori", "blad");
        }
    } else {
        note("Musisz podac nazwe kategori", "blad");
    }
}

function dodaj_podkategorie($nazwa, $kategoria)
{
    if (!empty($nazwa) && !empty($kategoria)) {
        if (mysql_query("INSERT INTO druzyny_kategorie VALUES('','{$nazwa}','{$kategoria}')")) {
            note("PodKategoria <b>{$nazwa}</b> dodana!", "info");
            przeladuj($_SESSION['stara']);
        } else {
            note("Blad podczas dodawania podkategori", "blad");
        }
    } else {
        note("Musisz podac nazwe podkategori i ID", "blad");
    }
}


// wysweitla kategorie w select $other jesli ma byc onSelect=submit
function select_kategorie($other, $post_name)
{
    $sql = mysql_query("SELECT id,nazwa FROM druzyny_kategorie_glowne");
    $ret = "<select name=\"{$post_name}\" {$other}>";
    $ret .= "<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\" " . ($_POST[$post_name] == $rek['id'] || $_GET[$post_name] == $rek['id'] ? "selected=\"selected\"" : "") . ">{$rek['nazwa']}</option>";
    }
    $ret .= "</select>";
    return $ret;
}

// wysweitla podkategorie w select $other jesli ma byc onSelect=submit, $id z kategori
function select_podkategorie($other, $post_name, $id)
{
    $sql = mysql_query("SELECT id,nazwa FROM druzyny_kategorie WHERE id_kat='{$id}'");
    $ret = "<select name=\"{$post_name}\" {$other}>
	<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\" " . ($_POST[$post_name] == $rek['id'] || $_GET[$post_name] == $rek['id'] ? "selected=\"selected\"" : "") . ">{$rek['nazwa']}</option>";
    }
    $ret .= "</select>";
    return $ret;
}


// wysweitla podkategorie w select $other jesli ma byc onSelect=submit, $id z kategori
function select_druzyny($post_name)
{
    $sql = mysql_query("SELECT id,nazwa FROM druzyny WHERE view='1'");
    $ret = "<select name=\"{$post_name}\">
	<option></option>";
    while ($rek = mysql_fetch_array($sql)) {
        $ret .= "<option value=\"{$rek['id']}\" " . ($_POST[$post_name] == $rek['id'] || $_GET[$post_name] == $rek['id'] ? "selected=\"selected\"" : "") . ">{$rek['nazwa']}</option>";
    }
    $ret .= "</select>";
    return $ret;
}


function usun_kategorie($id_kat)
{
    if (!empty($id_kat)) {
        if (
            mysql_query("DELETE FROM druzyny_kategorie_glowne WHERE id='{$id_kat}'") &&
            mysql_query("DELETE FROM druzyny_kategorie WHERE id_kat='{$id_kat}'")
        ) {
            note("Kategoria, podkategorie, przylaczone do podkategori druzyny - Usuniete!", "info");
            //przeladuj($_SESSION['stara']);
        } else {
            note("Blad podczas usuwania Kategori", "blad");
        }
    } else {
        note("Musisz podac id Kategori", "blad");
    }
}

function usun_podkategorie($id_podkat)
{
    if (!empty($id_podkat)) {
        if (
            mysql_query("DELETE FROM druzyny_kategorie WHERE id='{$id_podkat}'") &&
            mysql_query("DELETE FROM druzyny_polacz WHERE _id_kat='{$id_podkat}'")
        ) {
            note("Podkategoria, przylaczone do podkategori druzyny - Usuniete!", "info");
            przeladuj($_SESSION['stara']);
        } else {
            note("Blad podczas usuwania podkategori", "blad");
        }
    } else {
        note("Musisz podac id podkategori", "blad");
    }
}

function zapisz_podkategoria($nazwa, $id)
{
    if (!empty($nazwa) && !empty($id)) {
        if (!in_array('druzyny_podkategorie_edytuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            if (mysql_query("UPDATE druzyny_kategorie SET nazwa='{$nazwa}' WHERE id='{$id}'")) {
                note("PodKategoria <b>{$nazwa}</b> zostala zedytowana!", "info");
                przeladuj($_SESSION['stara']);
            } else {
                note("Blad podczas edytowania podkategori", "blad");
            }
        } ###
    } else {
        note("Musisz podac nazwe podkategori i ID", "blad");
    }
}

function zapisz_kategoria($nazwa, $id)
{
    if (!empty($nazwa) && !empty($id)) {
        if (!in_array('druzyny_kategorie_edytuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            if (mysql_query("UPDATE druzyny_kategorie_glowne SET nazwa='{$nazwa}' WHERE id='{$id}'")) {
                note("Kategoria <b>{$nazwa}</b> zostala zedytowana!", "info");
                przeladuj($_SESSION['stara']);
            } else {
                note("Blad podczas edytowania kategori", "blad");
            }
        } ###
    } else {
        note("Musisz podac nazwe kategori i ID", "blad");
    }
}

function edytuj_podkategorie($id)
{
    if (!empty($_POST['nowa_nazwa_podkategoria'])) {
        zapisz_podkategoria(czysta_zmienna_post($_POST['nowa_nazwa_podkategoria']), (int)$id);
    }
    $sql = mysql_fetch_array(mysql_query("SELECT id,nazwa FROM druzyny_kategorie WHERE id='{$id}'"));
    return "<input type=\"text\" size=\"15\" name=\"nowa_nazwa_podkategoria\" value=\"{$sql[1]}\"/><a href=\"?opcja=28&podopcja=2\" class=\"i-access-denied\"></a>";
}

function edytuj_kategorie($id)
{
    if (!empty($_POST['nowa_nazwa_kategoria'])) {
        zapisz_kategoria(czysta_zmienna_post($_POST['nowa_nazwa_kategoria']), (int)$id);
    }
    $sql = mysql_fetch_array(mysql_query("SELECT id,nazwa FROM druzyny_kategorie_glowne WHERE id='{$id}'"));
    return "<input type=\"text\" size=\"15\" name=\"nowa_nazwa_kategoria\" value=\"{$sql[1]}\"/><a href=\"?opcja=28&podopcja=2\" class=\"i-access-denied\"></a>";
}

function wyswietl_kategorie_usun()
{


    print "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">edytuj lub usuwaj kategorie i podkategorie</li>
		<li class=\"glowne_bloki_zawartosc\">
		
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\"><legend>usun lub edytuj podkategorie</legend>
					<ul class=\"adminAccessOption adminFields teamSelect\">
						<li>" . select_kategorie("onchange=\"submit()\"", "kategoria_usun") . " <span>zaznacz kategorie</span></li>
						<li>" . select_podkategorie("", "podkategoria_usun", (int)$_POST['kategoria_usun']) . "<span>wybierz podkategorie</span></li>
						<li><input type=\"submit\" name=\"p_edytuj\" value=\"edytuj\"/><span> opcja edytuj</span></li>
						<li>" . (!empty($_POST['p_edytuj']) && !empty($_POST['kategoria_usun']) &&
        !empty($_POST['podkategoria_usun']) ? edytuj_podkategorie((int)$_POST['podkategoria_usun']) : '') . "<span> formularz edycji</span></li>
						<li>
							" . (empty($_POST['p_edytuj']) ? "<input type=\"submit\" name=\"p_usun\" value=\"usun\" 
							onclick=\"return confirm('Czy napewno chcesz usunac podkategorie z tej kategori?');\"/>" : null) . "<span>opcja usun</span></li>
					</ul>
				</fieldset>
			</form>
			
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\"><legend>usun lub edytuj kategorie</legend>
					<ul class=\"adminAccessOption adminFields teamSelect\">
						<li>" . select_kategorie("", "usun_tylko_kategoria") . "<span>zaznacz kategorie</span></li>
						<li>" . (empty($_POST['k_edytuj']) ? "<input type=\"submit\" name=\"k_usun\" value=\"usun\" onclick=\"return confirm('Czy napewno chcesz usunac ta kategorie?');\"/>" : "") . "<span>opcja usun</span></li>
						<li>" . (!empty($_POST['k_edytuj']) && !empty($_POST['usun_tylko_kategoria']) ? edytuj_kategorie((int)$_POST['usun_tylko_kategoria']) : '') . "<span>formularz edycji</span></li>
						<li><input type=\"submit\" name=\"k_edytuj\" value=\"edytuj\"/><span></span></li>
					</ul>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
				</fieldset>
			</form>
		</li>
	</ul>";
}

/*
do strony o podanym id kategori
$sql=mysql_query("
SELECT p._id_kat,p._id_druzyny,   k.id,k.nazwa,   d.id, d.nazwa 	FROM druzyny_polacz p left join
druzyny_kategorie k ON p._id_kat = k.id left join
druzyny d ON p._id_druzyny = d.id  WHERE p.id='$id'
");

$sql=mysql_query("
SELECT p._id_kat,p._id_druzyny,     d.id, d.nazwa, d.punkty,  k.id,k.nazwa  	FROM
druzyny d LEFT JOIN
druzyny_polacz p ON p._id_druzyny = d.id LEFT JOIN
druzyny_kategorie k ON p._id_kategori = k.id
");

*/
function wyswietl_kategorie_admin()
{
    print "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\">Dodaj kategorie i podkategorie</li>
		<li class=\"glowne_bloki_zawartosc\">
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\"><legend>dodaj kategorie</legend>
					<ul class=\"adminAccessOption adminFields \">
						<li><input type=\"text\" name=\"kategoria\" size=\"50\"/><span>nazwa kategori</span></li>
					</ul>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
				</fieldset>
			</form>
			<form method=\"post\" action=\"\">
				<fieldset class=\"adminAccessList\"><legend>dodaj podkategorie</legend>
					<ul class=\"adminAccessOption adminFields  teamSelect\">
						<li><input type=\"text\" name=\"podkategoria\" size=\"50\"/><span>nazwa podkategori</span></li>
						<li>" . select_kategorie('', 'kategorie_glowne') . "<span>nalezy do kat.</span></li>
					</ul>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
				</fieldset>
			</form>
		</li>
	</ul>";
}

?>
