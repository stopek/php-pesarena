<?

if (!in_array('gry_zarzadzanie', explode(',', POZIOM_U_A))) {
    return note("Brak dostepu!", "blad");
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
        wersion_game('add', $edycja, $nazwa, $skrot, $logo);
    }
    if (!empty($_POST['popraw'])) {
        wersion_game('edit', $edycja, $nazwa, $skrot, $logo);
    }
    if (!empty($_GET['podmenu'])) {
        wersion_game('status', (int)$_GET['id'], '', '', '');
    }
}


if (!in_array('gry_podglad', explode(',', POZIOM_U_A))) {
    return note("Brak dostepu!", "blad");
} else { ###
    echo "<div class=\"jq_alert\"></div>";
    naglowek_edycja_gier();
    $sql = mysql_query("SELECT * FROM " . TABELA_GAME . "");
    define('PANE', TRUE);
    while ($as2 = mysql_fetch_array($sql)) {
        print "<tr" . kolor($a++) . "> 
			<td>{$a}</td>
			<td>{$as2['skrot']}</td>
			<td>{$as2['nazwa']}</td>
			<td>{$as2['logo']}</td>
			<td>" . ($as2['view'] == 1 ? "<a href=\"" . AKT . "&podmenu=hidden&id={$as2['id']}\" class=\"i-show-admin-rights\">widoczna</a>"
                : "<a href=\"" . AKT . "&podmenu=show&id={$as2['id']}\" class=\"i-access-denied\">ukryta</a>") . "
					
			</td>
			<td>
				<a href=\"administrator.php?opcja=25&edycja={$as2['id']}\" class=\"i-edit\"></a>
			</td>
			<td>
				<a class=\"i-delete jq\" href=\"#\" title=\"" . adminGameList . "|{$as2['id']}|0\"></a>
			</td>	
		</tr>";
    }
    end_table();


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
				<li class=\"c2\"><a href=\"#\" class=\"i-delete\"></a> usuwa konto adminowi</li>
				<li class=\"c1\"><a href=\"#\" class=\"i-edit\"></a> edytuje konto admina</li>
				<li class=\"c2\"><a href=\"#\" class=\"i-notice\"></a></a> oznacza opcje wzglednie niebezpieczna</li>
			</ul>
			<div class=\"description\">
				<span>
						Pamietaj, jesli dasz adminowi mozliwosc edytowania
						lub dodawania opiekunow, dajesz mu po czesci superadmina, pami�taj aby wypelnic wszystkie pola.
				</span>
			</div>
		</li>
	</ul>";


} ###

?>