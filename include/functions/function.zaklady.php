<?
function zaklady($gra, $r_id, $rozgrywka)
{
    $status = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_PUCHAR_DNIA_LISTA . " where vliga='{$gra}' && id='{$r_id}';"));
    if ($status['status'] == '4') {
        note("Puchar zakonczony! Mozliwosc zakladow wylaczona!", "blad");
    } elseif ($status['status'] == "0") {
        note("Zapisy do pucharu otwarte!", "blad");
    } elseif ($status['status'] == "1") {
        //index.php?opcja=$1&podmenu=$2&podopcja=$3&menu=$4


        $data_generowania = mysql_fetch_array(mysql_query("SELECT wygenerowano,termin FROM " . TABELA_PUCHAR_DNIA_TEMP . " 
			WHERE vliga='{$gra}' && r_id='{$r_id}' && rozgrywka='{$rozgrywka}' && typ='eliminacja';"));
        note("<i>Eliminacje w trakcie!</i>
					<img src=\"img/acces_none.png\" style=\"float:left;\" alt=\"uwaga\"/>
					<br/>Wygenerowano: <b>" . formatuj_date(date('Y-m-d H:i:s', $data_generowania[0])) . "</b>
					<br/>Koniec zapisow: <b>" . formatuj_date(date('Y-m-d H:i:s', $data_generowania[1])) . "</b>
					<br/>Aktualny czas: <b>" . formatuj_date(date('Y-m-d H:i:s', strtotime('NOW'))) . "</b>
					<br/>" . ($data_generowania[1] - strtotime('NOW') > 0 ? "OBSTAW COS!" : "ZAKLADY WYLACZONE") . "
			", "fieldset");


    } elseif ($status['status'] == "2") {
        note("Eliminacje zakonczone! Gracze z poza tabeli wykluczeni!", "blad");
    } elseif ($status['status'] == "3") {
        note("Rozgrywaja sie jakies fazy", "blad");


        // trzeba sprawdzic kiedy zostala wygenerowana dana faza !
        //jesli sa jakies mecze w baze tej fazy przy tym statusie
    }

    echo "<a href=\"{obstaw}\">{obstaw cos}</a>
		<a href=\"{pokaz}\">{pokaz swoje zaklady}</a>
		<a href=\"{pokaz}\">{pokaz wszystkie zaklady}</a>
		";
}

?>