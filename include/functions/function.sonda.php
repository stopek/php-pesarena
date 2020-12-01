<?
$odp_sonda_warianty = array('odp1', 'odp2', 'odp3', 'odp4', 'odp5');

function sonda_wyswietl($odp)
{
    $zalogowany = czysta_zmienna_post($_SESSION['zalogowany']);

    $my = mysql_query("SELECT * FROM " . TABELA_SONDA . " order by id desc limit 1");
    if (mysql_num_rows($my) == 0) {
        note(M207, 'blad');
    }

    if (isset($_SESSION['glosowal'])) {
        sonda_wyniki();
    } else {
        while ($rekord = mysql_fetch_array($my)) {
            echo '<b>' . $rekord['pyt'] . '</b>
			<form method="post" action="">
			<input type="hidden" name="nr_ankiety" value="' . $rekord['id'] . '"/>
			<table class="table_online">';
            foreach ($odp as $key => $versje) {
                $i = $key + 1;
                $k = $i + 1;
                if (!empty($rekord[$versje])) {
                    echo '<tr' . kolor($k . '_sonda') . '>
					<td height="23"><input type="radio"  value="' . $i . '" name="glos"/>' . $rekord[$versje] . '(' . $rekord['glos' . $i] . ')</td>
					</tr>';
                }
            }
            echo '</table>';

            if (!empty($zalogowany) && !eregi($zalogowany, $rekord['glosowali'])) {
                echo "<input type=hidden name=ankiet value=1 />";
                echo '<input type="image"  src="img/glosuj.jpg" class="button_glosuj" alt="' . M338 . '" title="' . M338 . '"/>';
            } else {

                if (empty($zalogowany)) {
                    note(M394, 'blad');
                }
                if (@eregi($zalogowany, $rekord['glosowali'])) {
                    note(M393, 'blad');
                }
            }
            echo '</form>';
        }
    }
}


function sonda_wyniki($odp)
{
    $czytaj = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_SONDA . " order by id desc limit 0,1;"));
    $suma = $czytaj['glos1'] + $czytaj['glos2'] + $czytaj['glos3'] + $czytaj['glos4'] + $czytaj['glos5'];

    if ($czytaj['pyt']) {
        echo '<b>' . $czytaj['pyt'] . '</b>';
        for ($b = 1; $b < 6; $b++) {
            if ($czytaj['odp' . $b]) {
                echo '
				<table class="table_online">
				<tr ' . kolor($b . '_sonda_wyniki') . '>
					<td width="100" height="23">' . $czytaj['odp' . $b] . ' [' . $czytaj['glos' . $b] . '] ';
                if (!empty($suma)) {
                    $procent = round(($czytaj['glos' . $b] / $suma) * 100, 2) . '%';
                    print "</td><td>";
                    if (!empty($procent)) {
                        echo '<table border="0"><tr><td class="naglowek"   width="' . $procent . '%"></td><td>' . $procent . '</td></tr></table>';
                    }
                } else {
                    echo '0%';
                }
                echo '</td>
				</tr>
				</table>';
            }
        }
        echo ' <p align="right">' . M392 . ' <b>' . $suma . '</b></a></p>';

    } else {
        note(M207, 'blad');
    }
}

function sonda_oddaj_glos()
{

    $glosy = array('1', '2', '3', '4', '5');
    $ankiet = $_POST['ankiet'];
    $zalogowany = czysta_zmienna_post($_SESSION['zalogowany']);
    $glos = (int)$_POST['glos'];
    $nr_ankiety = (int)$_POST['nr_ankiety'];

    if (!empty($ankiet)) {
        if (!isset($_SESSION['glosowal'])) {

            if (!empty($glos) && in_array($glos, $glosy)) {
                if (isset($_SESSION['zalogowany'])) {
                    $data = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_SONDA . " WHERE id = '{$nr_ankiety}'"));
                    if (!eregi($zalogowany, $data['glosowali'])) {
                        if (!mysql_query("UPDATE " . TABELA_SONDA . " SET glos{$glos}=glos{$glos}+1,glosowali='{$data['glosowali']} | {$zalogowany}({$glos})'  WHERE id='{$nr_ankiety}'")) {
                            note(M336, 'blad');
                        } else {
                            note(M335, 'info');
                        }
                    }
                } else {
                    note(M394, 'blad');
                }
            } else {
                note(M337, 'blad');
            }
        } else {
            note(M393, 'blad');
        }
    }
}
