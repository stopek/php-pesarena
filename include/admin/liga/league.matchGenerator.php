<?
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.liga.php');
require_once('include/admin/functions/admin.match.php');


$wykonaj = $_POST['wykonaj'];
$grupy = (int)$_POST['grupy'];
$start_od = (int)$_POST['start_od'];
$sezon = (int)$_POST['sezon'];
$rewanzTyp = (int)$_POST['rewanzTyp'];
$pauzowane = (int)$_POST['pauzowane'];
$metoda_przyjmowania = (int)$_POST['metoda_przyjmowania'];
$do_l = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_LIGA_GRACZE . " where vliga='{$wybrana_gra}' && r_id='{$r_id}';"));
$st = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_LIGA_LISTA . " where vliga='{$wybrana_gra}' && id='{$r_id}';"));
$meczy_do_konca = mysql_fetch_array(mysql_query("SELECT count(id) FROM " . TABELA_LIGA . " WHERE  vliga='{$wybrana_gra}' && r_id='{$r_id}' GROUP BY `r_id`;"));

if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {
        if (!in_array('liga_generowanie', explode(',', POZIOM_U_A))) {
            return note(admsg_gameDenied, "blad");
        } else { ###
            if (!empty($r_id)) {
                if (!empty($koncz_rozgrywki)) {
                    zakoncz_lige_ms($meczy_do_konca[0], $r_id);
                }


                // menu glowek ligi - END

                echo "<ul class=\"glowne_bloki\">
									<li class=\"glowne_bloki_naglowek\">Witaj w systemie ligowym</li>
									<li class=\"glowne_bloki_zawartosc\">";


                switch ($st['status']) {
                    case '0':
                        echo "<div class=\"leagueStatus\">Aktualny status: <span>Zapisy do Ligi wlaczone. Wylacz aby kontynulowac</span></div>";
                        break;
                    case 'S':
                        echo "<div class=\"leagueStatus\">Aktualny status: <span>Zapisy wstrzymane. Pora generowac</span></div>";
                        $nastepny_status = array('1' => '1', '2' => '2');
                        if (!empty($wykonaj)) {
                            liga_dziel_graczy($grupy, $do_l, $metoda_przyjmowania, $start_od, $sezon, $nastepny_status[$start_od], $pauzowane, $rewanzTyp);
                        }
                        formularz_dziel_ligi();
                        break;
                    case '1':

                    case '2':
                        $tablica = array(
                            '1' => array('typ_meczy' => 'eliminacje', 'wynik_generowania' => 'a'),
                            '2' => array('typ_meczy' => 'liga glowna', 'wynik_generowania' => 'b')
                        );

                        if ($_GET['menu'] == 'baraze') {
                            if (!empty($_POST['ok'])) {
                                generuj_mecze_barazowe($tab);
                            }
                            formularz_baraze();
                        } else {
                            note("Trwaja mecze:  <b>{$tablica[$st['status']]['typ_meczy']}</b>. Pozostalo: <b>{$meczy_do_konca[0]}</b> meczy do konca.", "info");
                        }
                        break;

                    case 'B':
                        echo "<br/><b>Trwaja Mecze Barazowe</b><br/><br/>";
                        break;

                    case '4':
                        echo "<br/><b>Liga Zakonczona!</b><br/><br/>";
                        break;
                    default:

                        break;
                }
                if (empty($meczy_do_konca[0]) && $st['status'] != '0' && $st['status'] != 'S' && $st['status'] != '1') {
                    echo "<div class=\"leagueStatus\">Aktualny status: <span>Mecze zakonczone <a href=\"" . AKT . "&koncz_rozgrywki=1\">Zakoncz</a></span></div>";
                }
                echo "</li>
								</ul>";


            } else {
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            }
        }###
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(21);
?>
