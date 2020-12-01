<?
require_once('include/admin/functions/admin.sonda.php');
if (!in_array('sonda', explode(',', POZIOM_U_A))) {
    return note("Brak dostepu!", "blad");
} else { ###


    //pobranie danych do formularza
    $id = (int)$_GET['id'];
    if ($podmenu == 'edytuj') {
        $wynik = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_SONDA . " where id='{$id}';"));
        ankieta_formularz_edycja($wynik['pyt'], $wynik['odp1'], $wynik['odp2'], $wynik['odp3'], $wynik['odp4'], $wynik['odp5'], 'edytuj');
    }

    //manager funkcji
    if (!empty($_POST['edytuj'])) {
        sonda_manager('edit', $id);
    }
    if ($podmenu == 'glosowali') {
        sonda_manager('show', $id);
    }
    if ($podmenu == 'usun') {
        sonda_manager('delete', $id);
    }
    if (!empty($_POST['dodaj'])) {
        sonda_manager('add', $id);
    }


    //wyswietlenie dostepnych sond
    $wynik = mysql_query("SELECT * FROM " . TABELA_SONDA . " order by id;");
    print "
	<table border=\"1\" width=\"100%\" frame=\"void\">
	<tr class=\"naglowek\">
		<td>Pytanie</td>
		<td>Odpowiedz1</td>
		<td>Odpowiedz2</td>
		<td>Odpowiedz3</td>
		<td>Odpowiedz4</td>
		<td>Odpowiedz5</td>
		<td>Glos</td>
		<td>Edytuj</td>
		<td>Usun</td>
	</tr>";

    while ($r = mysql_fetch_array($wynik)) {
        $count = $r['glos1'] + $r['glos2'] + $r['glos3'] + $r['glos4'] + $r['glos5'];
        print "<tr " . kolor($a++) . ">
			<td>{$r['pyt']}[{$count}]</td>
			<td>{$r['odp1']}[{$r['glos1']}]</td>
			<td>{$r['odp2']}[{$r['glos2']}]</td>
			<td>" . (!empty($r['odp3']) ? "{$r['odp3']}[{$r['glos3']}]" : null) . "</td>
			<td>" . (!empty($r['odp4']) ? "{$r['odp4']}[{$r['glos4']}]" : null) . "</td>
			<td>" . (!empty($r['odp5']) ? "{$r['odp5']}[{$r['glos5']}]" : null) . "</td>
			<td><a href=\"administrator.php?opcja=24&podmenu=glosowali&id={$r['id']}\"><img src=\"grafiki/admin_ikons/sonda.png\" alt=\"\"/></a></td>
			<td><a href=\"administrator.php?opcja=24&podmenu=edytuj&id={$r['id']}\"><img src=\"grafiki/admin_ikons/edit.png\" alt=\"edytuj\"/></a></td>
			<td>
				<a href=\"administrator.php?opcja=24&podmenu=usun&id={$r['id']}\" 
				onclick=\"return confirm('Czy napewno chcesz bezpowrotnie usunac ta ankiete?');\">
				<img src=\"grafiki/admin_ikons/delete.png\" alt=\"usun\"/></a>
			</td>
		</tr>\n";
    }
    print "</table>";

    ankieta_formularz_edycja('', '', '', '', '', '', 'dodaj');
}###

?>
