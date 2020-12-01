<?

function ustawienia($src, $splitMethod)
{
    $wSplitMethod = array(
        'read' => array(
            1 => "#\\$+conf\[\'(.*?)\'\]=\"(.*?)\";//(.*?)\n#i",
            2 => "#\\$+admsg\[\'(.*?)\'\]=\"([^\"]+.*?)\";\n#i"
        ),
        'addStyle' => array(
            1 => " adminConf ",
            2 => "  "
        )
    );

    if ($_POST['zapisz'] == 'zapisz') {
        if (!in_array('ustawienia_edytuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            $a = $b = $u = 0;
            foreach ($_POST['opis'] as $v) {
                $zapis[$a]['opis'] = $v;
                $a++;
            }
            foreach ($_POST['zmienna'] as $v) {
                $zapis[$u]['zmienna'] = $v;
                $u++;
            }
            foreach ($_POST['parametry'] as $value) {
                $zapis[$b]['wartosc'] = $value;
                $zapis[$b]['opis'];
                $zapis[$b]['zmienna'];
                $b++;
            }


            $plik = fopen($src, 'w+');
            fwrite($plik, "<?\n");
            for ($i = 0; $i < count($zapis); $i++) {
                if (eregi('^([a-zA-Z0-9._-])+', $zapis[$i]['wartosc'])) {
                    if ($splitMethod == 1) fwrite($plik, "\$conf['" . $zapis[$i]['zmienna'] . "']=\"" . $zapis[$i]['wartosc'] . "\";//" . $zapis[$i]['opis'] . "\n");
                    if ($splitMethod == 2) fwrite($plik, "\$admsg['" . $zapis[$i]['zmienna'] . "']=\"" . $zapis[$i]['wartosc'] . "\";\n");
                } else {
                    fwrite($plik, "\$conf['" . $zapis[$i]['zmienna'] . "']=\"blad\";//" . $zapis[$i]['opis'] . "\n");
                    note("Zmienna: <strong>{$zapis[$i]['zmienna']}</strong> zawiera blad! Popraw!", "blad");
                }
            }
            fwrite($plik, '?>');
            fclose($plik);
            note('Nowe ustawienia zosta�y zapisane!', 'info');
            przeladuj($_SESSION['stara']);
        }
    }


    if (!in_array('ustawienia_pokaz', explode(',', POZIOM_U_A))) {
        return note("Brak dostepu!", "blad");
    } else { ###
        echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek legenda\"><span>Ustawienia</span></li>
		<li class=\"glowne_bloki_zawartosc\">
				<form method=\"post\" action=\"\">
					<fieldset class=\"adminAccessList {$wSplitMethod['addStyle'][$splitMethod]}\"><legend></legend>
						<ul class=\"adminAccessOption adminFields\">
						";
        $linijka = file_get_contents($src);
        $moje = preg_replace('' . $wSplitMethod['read'][$splitMethod] . '',
            '
								<li>
									<input type="hidden" name="opis[]" value="$3"/>
									<input type="hidden" name="zmienna[]" value="$1"/>
									<input type="text" name="parametry[]" value="$2" class="text_add"/>
									<em>Opis: <b>$3</b></em>
									<span>$1</span>
								</li>', $linijka);
        echo str_replace(array('?>', '<?'), '', $moje);

        echo "</ul>
					</fieldset>
					<div class=\"description\">
						<span>
							Opcje, w ktorych wartosci odpowiedzi w domysle maja postac '<strong>TAK</strong>' lub '<strong>NIE</strong>' cyfra '<strong>1</strong>'
							odpowiada '<strong>TAK</strong>' natomiast kazda inna (najlepiej <strong>0(zero)</strong>) to '<strong>NIE</strong>'.<br/>
							Pamietaj, ze w pola powyzej mozesz wpisac tylko litery i cyfry. Przy zapisywaniu jesli uzyjesz znakow innych niz 
							dozwolone system w to pole wpisze '<strong>blad</strong>'
						</span>
					</div>
					<input type=\"hidden\" name=\"zapisz\" value=\"zapisz\"/>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
				</form>
		</li>
	</ul>";
    }
}

?>