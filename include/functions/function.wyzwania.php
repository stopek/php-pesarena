<?
function pokaz_mini_tabele($id_gracza, $gdzie)
{
    $replace = array(
        TABELA_PUCHAR_DNIA => 'puchar',
        TABELA_LIGA => 'liga',
        TABELA_WYZWANIA => 'wyzwania',
        TABELA_TURNIEJ => 'turniej'
    );
    echo "<table>";
    $a2 = mysql_query("SELECT * FROM {$gdzie} WHERE vliga='" . DEFINIOWANA_GRA . "' && (n1='{$id_gracza}' || n2='{$id_gracza}') && status!='3' 
	" . ($gdzie == TABELA_LIGA ? " && spotkanie='akt' " : NULL) . "
	ORDER BY rozegrano DESC;");
    while ($as2 = mysql_fetch_array($a2)) {
        echo "<ul " . kolor($b++ . "_mini_tabela_{$gdzie}") . ">
				<li class=\"match_id\">{$as2['id']}</li>
				<li class=\"opponent\">
					" . ($as2['n1'] == $id_gracza ? linkownik('profil', $as2['n2'], '') . " <br/> 
					" . mini_logo_druzyny($as2['klub_2']) : linkownik('profil', $as2['n1'], '') . " <br/> 
					" . mini_logo_druzyny($as2['klub_2'])) . "</li>
				<li class=\"result\">" . ($as2['status'] < 2 ? "-:-" : $as2['w1'] . " - " . $as2['w2']) . "</li>
				
				<li class=\"status_description\">";
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            print M105;
        }
        if ($as2['n2'] == $id_gracza && $as2['status'] == 1) {
            print M106;
        }
        if ($as2['n1'] == $id_gracza && $as2['status'] == 2) {
            print M107;
        }
        if ($as2['n1'] == $id_gracza && $as2['status'] == 'p') {
            print M108;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            print M109;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 'p') {
            print M110;
        }
        print "</li>
				<li class=\"status_button\">";
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            echo linkownik('zakoncz', $as2['id'], $replace[$gdzie]);
            echo linkownik('odrzuc_wynik', $as2['id'], $replace[$gdzie]);
            $a = 1;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == "p") {
            echo linkownik('akceptuj', $as2['id'], $replace[$gdzie]);
            $a = 1;
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            echo linkownik('podaj_wynik', $as2['id'], $replace[$gdzie]);
            $a = 1;
        }
        if ((($as2['n1'] == $id_gracza) || ($as2['n2'] == $id_gracza)) && $as2['status'] == "p") {
            echo linkownik('odrzuc_spotkanie', $as2['id'], $replace[$gdzie]);
            $a = 1;
        }
        echo "</li>
			</ul>";

        $temp = TRUE;
    }
    end_table();
    if (empty($temp)) {
        echo "<span>" . note(M102, "blad") . "</span>";
    }
}


// wyswietlanie meczy towarzyskich
function pokaz_tabele_wyzwan($id_gracza, $zapytanie)
{
    naglowek_meczy();
    $a2 = mysql_query("SELECT o.g_1_ocena, o.g_2_ocena,w.* FROM " . TABELA_WYZWANIA . " w 
	LEFT JOIN " . TABELA_OCENA_LISTA . " o ON w.id = o.id_meczu && o.gdzie='w' 
	{$zapytanie}  && w.vliga='" . DEFINIOWANA_GRA . "' ORDER BY w.rozegrano DESC;");
    while ($as2 = mysql_fetch_array($a2)) {
        print "<tr" . kolor($kol++) . "  class=\"text_center\">
			<td>" . $as2['id'] . "</td>
			<td>" . linkownik('profil', $as2['n1'], '') . "<u>(" . (isset($as2[0]) ? $as2[0] : "?") . ")</u></td>
			<td>" . linkownik('profil', $as2['n2'], '') . "<u>(" . (isset($as2[1]) ? $as2[1] : "?") . ")</u></td>
			<td>" . mini_logo_druzyny($as2['klub_1']) . " vs. " . mini_logo_druzyny($as2['klub_2']) . "</td>
			<td>" . ($as2['status'] < 2 ? "-:-" : $as2['w1'] . " - " . $as2['w2']) . "</td>
			<td>";
        if ($as2['status'] == 3) {
            print M111;
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            print M105;
        }
        if ($as2['n2'] == $id_gracza && $as2['status'] == 1) {
            print M106;
        }
        if ($as2['n1'] == $id_gracza && $as2['status'] == 2) {
            print M107;
        }
        if ($as2['n1'] == $id_gracza && $as2['status'] == 'p') {
            print M108;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            print M109;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 'p') {
            print M110;
        }
        print "</td>
			<td width=\"30\">" . M112 . "</td>
			<td>";
        if (($as2['n2'] == $id_gracza) && $as2['status'] == 2) {
            echo linkownik('zakoncz', $as2['id'], 'wyzwania');
            echo linkownik('odrzuc_wynik', $as2['id'], 'wyzwania');
            $a = 1;
        }
        if (($as2['n2'] == $id_gracza) && $as2['status'] == "p") {
            echo linkownik('akceptuj', $as2['id'], 'wyzwania');
            $a = 1;
        }
        if (($as2['n1'] == $id_gracza) && $as2['status'] == 1) {
            echo linkownik('podaj_wynik', $as2['id'], 'wyzwania');
            $a = 1;
        }
        if ((($as2['n1'] == $id_gracza) || ($as2['n2'] == $id_gracza)) && $as2['status'] == "p") {
            echo linkownik('odrzuc_spotkanie', $as2['id'], 'wyzwania');
            $a = 1;
        }
        if (empty($a)) {
            print "-";
        }
        print "</td>
			<td>";
        if (!empty($as2['k_1']) && !empty($as2['k_2'])) {
            echo pokaz_moje_pkt($as2['n1'], $as2['n2'], $as2['k_1'], $as2['k_2'], $id_gracza);
        } else {
            print "-:-";
        }
        print "</td>
			</tr>";
        $temp = TRUE;
    }
    end_table();

    if (empty($temp)) {
        note(M102, "blad");
    } else {
        wyswietl_legende(1);
    }

} // wyswietlanie meczy towarzyskich - END

?>
