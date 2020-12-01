<?

function zarzadzaj_graczami_ip()
{
    if (!in_array('ip_zarzadzanie', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        $tresc = czysta_zmienna_post($_POST['tresc']);
        if ($_POST['dodaj']) {
            if (mysql_query("INSERT INTO uprzywilejowani_ip values('','{$tresc}')")) {
                note("Gracze dodani", "info");
            } else {
                note("Wystapil blad podczas dodawnia graczy", "blad");
            }
        }
        $usun = (int)$_GET['usun'];
        if ($_GET['usun']) {
            if (mysql_query("DELETE  FROM uprzywilejowani_ip WHERE id='{$usun}'")) {
                note("Usunieto", "info");
                przeladuj($_SESSION['stara']);
            } else {
                note("Blad podczas usuwania", "blad");
                przeladuj($_SESSION['stara']);
            }
        }
        $edytuj = (int)$_GET['edytuj'];
        if ($_POST['popraw']) {
            if (mysql_query("UPDATE uprzywilejowani_ip set gracze_lista='{$tresc}' WHERE id='{$edytuj}'")) {
                note("Poprawiono", "info");
                przeladuj($_SESSION['starsza']);
            } else {
                note("Blad podczas poprawiania", "blad");
                przeladuj($_SESSION['starsza']);
            }
        }

        if (!empty($edytuj)) {
            $co = 'popraw';
            $rekord = mysql_fetch_array(mysql_query("SELECT * FROM uprzywilejowani_ip WHERE id='{$edytuj}'"));
        } else {
            $co = 'dodaj';
        }


        require_once('include/admin/funkcje/function.table.php');


        $z = mysql_query("SELECT * FROM uprzywilejowani_ip");
        while ($rek = mysql_fetch_array($z)) {
            $arr[$b][] = $b++;
            $arr[$b][] = $rek['gracze_lista'];
            $arr[$b][] = "<a href=\"" . AKT . "&edytuj={$rek['id']}\" class=\"i-edit\"></a>";
            $arr[$b][] = "<a href=\"" . AKT . "&usun={$rek['id']}\" class=\"i-delete\" onclick=\"return confirm('Czy chcesz usunac?');\"/></a>";
        }


        $table = new createTable('adminFullTable');
        $table->setTableHead(array('id', 'Gracze Uprzywilejowani', 'edytuj', 'usun'), "adminHeadersClass");
        $table->setTableBody($arr);
        echo $table->getTable();


        echo "<ul class=\"glowne_bloki\">
			<li class=\"glowne_bloki_naglowek\">Wymien graczy, z mozliwoscia logowania na tym samym IP</li>
			<li class=\"glowne_bloki_zawartosc\">
				<form method=\"post\" action=\"\" enctype=\"multipart/form-data\">
					<input type=\"hidden\" name=\"{$co}\" value=\"1\"/>
					<div class=\"regal\">
					<input type=\"text\" size=\"75\" value=\"{$rekord['gracze_lista']}\" name=\"tresc\">
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
				</form>
				<div class=\"description\"><span>Graczy podajemy po ; Wielkosc liter ma znaczenie</span></div>
			</li>
		</ul>";

    }###
}

