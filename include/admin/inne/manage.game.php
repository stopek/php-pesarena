<?
require_once('include/admin/funkcje/function.table.php');
if (!in_array('gry_zarzadzanie', explode(',', POZIOM_U_A))) {
    return note(admsg_accessDenied, "blad");
} else { ###

    $skrot = czysta_zmienna_post($_POST['skrot']);
    $nazwa = czysta_zmienna_post($_POST['nazwa']);
    $logo = czysta_zmienna_post($_POST['logo']);
    $edycja = (int)$_GET['edycja'];
    $coteraz = 'dodaj';

    // pobranie wartosci w bazy do edycji
    if (!empty($edycja) && empty($_GET['popraw'])) {
        $rekord = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GAME . " where id='{$edycja}';"));
        $coteraz = 'popraw';
    }
    // pobranie wartosci w bazy do edycji - END


    //manager wersji gry
    if (!empty($_POST['dodaj'])) {
        addVersionGame($nazwa, $skrot, $logo);
    }
    if (!empty($_POST['popraw'])) {
        editVersionGame($nazwa, $edycja);
    }
    if (!empty($_GET['podmenu'])) {
        changeVersionGame((int)$_GET['id'], $_GET['podmenu']);
    }
    if (!empty($_GET['usun'])) {
        deleteVersionGame((int)$_GET['usun']);
    }
}


if (!in_array('gry_podglad', explode(',', POZIOM_U_A))) {
    return note(admsg_accessDenied, "blad");
} else { ###

    $sql = mysql_query("SELECT * FROM " . TABELA_GAME);
    $b = 1;
    while ($as2 = mysql_fetch_array($sql)) {
        $arr[$b][] = $b++;
        $arr[$b][] = $as2['skrot'];
        $arr[$b][] = $as2['nazwa'];
        $arr[$b][] = $as2['logo'];
        $arr[$b][] = ($as2['view'] == 1 ? "<a href=\"" . AKT . "&podmenu=hidden&id={$as2['id']}\" class=\"i-show-admin-rights\">widoczna</a>"
            : "<a href=\"" . AKT . "&podmenu=show&id={$as2['id']}\" class=\"i-access-denied\">ukryta</a>");
        $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
        $arr[$b][] = "<a class=\"i-delete\" href=\"" . AKT . "&usun={$as2['id']}\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"></a>";
    }


    $table = new createTable('adminFullTable');
    $table->setTableHead(array('id', 'skrot', 'pelna nazwa', 'logo gry', 'status', 'edytuj', 'usun'), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek\"><strong>{$coteraz}</strong> wersje gry</li>
		<li class=\"glowne_bloki_zawartosc\">
			<fieldset class=\"adminAccessList\">
				<form enctype=\"multipart/form-data\" action=\"\" method=\"POST\">
				<ul class=\"adminAccessOption adminFields\">
					
						<li><input name=\"logo\" type=\"file\"/><span>logo</span></li>
						<li><input type=\"text\" name=\"skrot\" value=\"{$rekord['skrot']}\"/><span>skrot</span></li>
						<li><input type=\"text\" name=\"nazwa\" value=\"{$rekord['nazwa']}\"/><span>nazwa gry</span></li>
					</ul>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\"/>
					<input type=\"hidden\" name=\"{$coteraz}\" value=\"1\"/>
					<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"512000\"/>
				</form>
				
			</fieldset>
		</li>
	</ul>";

    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek legenda\"><span>LEGENDA</span></li>
		<li class=\"glowne_bloki_zawartosc\">
			<ul class=\"legendaUL\">
				<li class=\"c2\"><a href=\"#\" class=\"i-delete\"></a> usuwa wersje gry</li>
				<li class=\"c1\"><a href=\"#\" class=\"i-edit\"></a> edytuje wersje gry</li>
				<li class=\"c2\"><a href=\"#\" class=\"i-show-admin-rights\"></a></a> pokazuje status wyswietlania na stronie</li>
			</ul>
			<div class=\"description\">
				<span>
					Pamietaj, ze samo usuniecie wersji gry nie usuwa przypisanych do tej wersji spotkan i rozgrywek. Ponadto, gdy usuniesz
					jakas wersje gry nie bedziesz mogl zarzadzac meczami. Jesli zdarzy Ci przypadkowo kliknac, wystarczy ze dodasz 
					wersje gry, o takim samym jak ta usunieta, skrocie.
				</span>
			</div>
		</li>
	</ul>";


} ###

?>