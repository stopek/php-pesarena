<?

// wyswietla formularz do zapisania 
function zapisy($gracze, $lista, $id_zalogowanego_usera, $r_id)
{
    if (!empty($r_id)) {
        $mozliwe_jak = array("1", "0");
        $zapis = $_POST['zapis'];
        if (isset($zapis)) {
            if ($zapis == '0' && in_array($zapis, $mozliwe_jak)) {
                if (mysql_query("DELETE FROM {$gracze} WHERE id_gracza='{$id_zalogowanego_usera}' && r_id='{$r_id}' && vliga='" . DEFINIOWANA_GRA . "';")) {
                    note(M10, "info");
                } else {
                    note(M303, 'blad');
                }
            } else {
                $klub_do_gry = (int)$_POST['klub_do_gry'];
                if (!empty($klub_do_gry)) {
                    $jest = mysql_num_rows(mysql_query("SELECT * FROM {$gracze} where id_gracza='{$id_zalogowanego_usera}' && vliga='" . DEFINIOWANA_GRA . "' && r_id='{$r_id}';"));
                    if (empty($jest)) {
                        if (mysql_query("INSERT INTO {$gracze} values('','{$id_zalogowanego_usera}','{$klub_do_gry}','" . DEFINIOWANA_GRA . "','1','{$r_id}');")) {
                            note(M11, "info");
                        } else {
                            note(M300, 'blad');
                        }
                    }
                } else {
                    note(M12, "blad");
                }
            }
        }


        print "<div class=\"text_center\">
		<table border=\"0\" width=\"500\">
		<tr>
			<td>";
        $jest = mysql_fetch_array(mysql_query("SELECT count(g.id_gracza), (SELECT d.nazwa FROM druzyny d WHERE d.id=g.klub),g.klub 
			FROM {$gracze} g WHERE g.id_gracza='{$id_zalogowanego_usera}'  
			&& g.vliga='" . DEFINIOWANA_GRA . "' && g.r_id='{$r_id}' GROUP BY g.id;"));

        $kat_druzyny = mysql_fetch_array(mysql_query("SELECT id_kategori FROM {$lista} WHERE id='{$r_id}'"));
        if (!empty($jest[0])) {
            note(M184, 'fieldset');
        }
        print "<form method=\"post\" action=\"\">
				<input type=\"hidden\" name=\"zapis\" value=\"" . (empty($jest[0]) ? "1" : "0") . "\"/>";
        if (empty($jest[0])) {
            if (!empty($kat_druzyny['id_kategori'])) {
                echo $kat_druzyny['id_kategori'];
                wybor_klubu_wg_kategori("klub_do_gry", "onchange=\"document.getElementById('klub_pucharu').src='grafiki/loga/'+this.value+'.png'\"", $kat_druzyny['id_kategori']);
            } else {
                wybor_klubu_czysto("klub_do_gry", "onchange=\"document.getElementById('klub_pucharu').src='grafiki/loga/'+this.value+'.png'\"");
            }
            print "<br/><input type=\"image\" alt=\"" . M8 . "\" title=\"" . M8 . "\" src=\"img/zapisz_mnie.jpg\"/>";
        } elseif (!empty($jest[0])) {
            print "<input type=\"image\" alt=\"" . M9 . "\" title=\"" . M9 . "\" src=\"img/anuluj_zapis.jpg\"/>";
        }
        print "</form>
			</td>
			<td><img src=\"" . (!empty($jest[0]) ? "grafiki/loga/" . $jest[2] . ".png" : "img/brak_druzyny.png") . "\" 
			id=\"klub_pucharu\" alt=\"\"/><br/>{$jest[1]}</td>
		</tr>
		</table>
		</div>";
    } else {
        note(M536, "blad");
    }
} // wyswietla formularz do zapisania - END
?>