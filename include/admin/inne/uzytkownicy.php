<?
require_once('include/admin/functions/admin.user.php');
require_once('include/admin/functions/admin.boot.php');
$id = (int)$_GET['id'];
$usun = (int)$_GET['usun'];
$edit = (int)$_GET['edit'];

$dodaj = czysta_zmienna_post($_POST['dodaj']);
$edytuj = czysta_zmienna_post($_POST['edytuj']);
$tresc = czysta_zmienna_post($_POST['tresc']);


switch ($_GET['wykonaj']) {
    case 'ban':
        if (!in_array('user_banuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else {
            banuj();
        }
        break;
    case 'aktywuj':
        if (!in_array('user_aktywuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else {
            aktywacja_kata_a();
        }
        break;
    case 'edytuj':
        if (!in_array('user_edytuj', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            edycja_kata_a();
        }
        break;
    case 'usun':
        if (!in_array('user_usun', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            usun_kato();
        }
        break;
    case 'haslo':
        if (!in_array('user_haslo', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            z_haslo();
        }
        break;
    case 'host':
        if (!in_array('user_host', explode(',', POZIOM_U_A))) {
            return note("Brak dostepu!", "blad");
        } else { ###
            zmien_host();
        }
        break;
}


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {

        if ($_GET['wykonaj'] == 'ostrzezenia') {
            if (!in_array('user_ostrzez', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                // pobranie wartosci w bazy do edycji
                $coteraz = 'dodaj';
                if (!empty($edit) && empty($edytuj)) {
                    $wynik = mysql_query("SELECT * FROM " . TABELA_OSTRZEZENIA . " where id='{$edit}';");
                    $rekord = mysql_fetch_array($wynik);
                    $coteraz = 'edytuj';
                } // pobranie wartosci w bazy do edycji - END

                //edycja ostrzezenia
                if (!empty($edytuj)) {
                    if (mysql_query("UPDATE " . TABELA_OSTRZEZENIA . " SET opis='{$tresc}' WHERE id='{$edit}';")) {
                        note("Edycja ostrzezenia zakonczona!", "info");
                        przeladuj($_SESSION['starsza']);
                    }
                } //edycja ostrzezenia  - END

                //dodawanie ostrzezenia
                if (!empty($dodaj)) {
                    if (!empty($tresc)) {
                        if (mysql_query("insert into " . TABELA_OSTRZEZENIA . " values('','{$id}',NOW(),'{$tresc}');")) {
                            note("Ostrzezenie  zostalo dodane!", "info");
                            przeladuj($_SESSION['stara']);
                        } else {
                            note('Wystapil blad podczas dodawania ostrzezenia', 'blad');
                            przeladuj($_SESSION['stara']);
                        }
                    } else {
                        note("Musisz wypelnic wszystkie pola", "blad");
                    }
                } //dodawanie ostrzezenia - END

                //usuwanie ostrzezenia
                if (!empty($usun)) {
                    $sprawdz = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_OSTRZEZENIA . " where id='{$usun}'"));
                    if ($sprawdz == 1) {
                        $wynik = mysql_query("DELETE FROM " . TABELA_OSTRZEZENIA . " where id='{$usun}'");
                        note("Ostrzezenie zostalo usuniete", "info");
                        przeladuj($_SESSION['stara']);
                    } else {
                        note("Brak dostepu!", "blad");
                    }
                }


                //formularz dodawania ostrzezenai

                echo "<ul class=\"glowne_bloki\">
				<li class=\"glowne_bloki_naglowek\">opisz krotko za co wystawiasz ostrzezenie</li>
				<li class=\"glowne_bloki_zawartosc\">
					<form method=\"post\" action=\"\">
					<input type=\"hidden\" name=\"cmd\" value=\"1\"/>
					<textarea cols=\"55\" class=\"textarea_a\" rows=\"3\" name=\"tresc\">{$rekord['opis']}</textarea>
					<input type=\"hidden\" name=\"{$coteraz}\" value=\"{$coteraz}\"/>
					<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
					</form>
				</li>
			</ul>";


                //formularz dodawania ostrzezenia - END


                echo "
			<table border=\"1\" width=\"100%\" frame=\"void\">
			<tr class=\"naglowek\">
				<td>Id</td>
				<td>Tresc</td>
				<td>Data</td>
				<td width=30>Edytuj</td>
				<td width=30>Usun</td>
			</tr>		
			";

                $wyn = mysql_query("SELECT * FROM ostrzezenia WHERE id_gracza='{$id}';");
                while ($rek = mysql_fetch_array($wyn)) {
                    echo "
				<tr" . kolor($rek['id']) . ">
					<td>{$rek['id']}</td>
					<td>{$rek['opis']}</td>
					<td>" . formatuj_date($rek['data']) . "</td>
					<td><a href=\"" . AKT . "&edit={$rek['id']}\" class=\"i-edit\"></td>
					<td><a href=\"" . AKT . "&usun={$rek['id']}\" class=\"i-delete\"></td>
				</tr>";

                }
                end_table();
                if (mysql_num_rows($wyn) == 0) {
                    note("Gracz <b>" . sprawdz_login_id($id) . "</b> ma narazie czyste konto.", "blad");
                }
            }
        } elseif ($_GET['wykonaj'] == 'vip') {
            if (!in_array('user_vip', explode(',', POZIOM_U_A))) {
                return note("Brak dostepu!", "blad");
            } else { ###
                //dodawanie vipa
                if (!empty($dodaj)) {
                    if (!empty($tresc)) {
                        if (mysql_query("insert into vip values('','{$id}','{$wybrana_gra}','{$tresc}');")) {
                            note("VIP zostal dodany!", "info");
                            przeladuj($_SESSION['stara']);
                        } else {
                            note('Wystapil blad podczas dodawania vipa', 'blad');
                            przeladuj($_SESSION['stara']);
                        }
                    } else {
                        note("Musisz wypelnic wszystkie pola", "blad");
                    }
                } //dodawanie vipa - END

                //usuwanie vipa
                if (!empty($usun)) {
                    $sprawdz = mysql_fetch_array(mysql_query("SELECT * FROM vip where id_gracza='{$id}' && vliga='{$wybrana_gra}'"));
                    if (!empty($sprawdz['id_gracza'])) {
                        $wynik = mysql_query("DELETE FROM vip where id_gracza='{$usun}'");
                        note("Vip zostal usuniety!", "info");
                        przeladuj($_SESSION['starsza']);
                    } else {
                        note("Brak dostepu!", "blad");
                    }
                }// usuwanie vipa - END

                $sprawdz = mysql_fetch_array(mysql_query("SELECT * FROM vip where id_gracza='{$id}' && vliga='{$wybrana_gra}'"));
                if (!empty($sprawdz['id_gracza'])) {
                    note("Ten gracz jest VIP'em! <a href=\"" . AKT . "&usun={$id}\"><img src=\"img/del.png\" alt=\"\"/> USUN VIP'a</a> ", "info");
                } else {
                    //formularz dodawania vipa

                    echo "<ul class=\"glowne_bloki\">
				<li class=\"glowne_bloki_naglowek\">{$co} dodatkowe punkty</li>
				<li class=\"glowne_bloki_zawartosc\">
					<form method=\"post\" action=\"\">
						<input type=\"hidden\" name=\"cmd\" value=\"1\"/>
						Logo VIP'a: <input type=\"text\" name=\"tresc\" value=\"\" size=\"40\"/>
						<input type=\"submit\" name=\"dodaj\" value=\"Wystaw\"/>
						<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
					</form>
				</li>
			</ul>";


                    //formularz dodawania vipa - END
                }
            }###

        } else {

            if ($_GET['pod'] == 'szuk') {

                echo "<ul class=\"glowne_bloki\">
					<li class=\"glowne_bloki_naglowek\">Znajdz gracza, ktoremu chcesz aktywowac konto</li>
					<li class=\"glowne_bloki_zawartosc\">
						<form method=\"get\" action=\"\">
						<input type=\"hidden\" name=\"zawodnika\" value=\"szukaj\"/>
						<input type=\"hidden\" name=\"opcja\" value=\"{$_GET['opcja']}\"/>
						<input type=\"hidden\" name=\"pod\" value=\"{$_GET['pod']}\"/>
						<input type=\"hidden\" name=\"wybrana_gra\" value=\"{$_GET['wybrana_gra']}\"/>
						<input type=\"text\"  name=\"nazwa_szukanego\"/>
						<fieldset>
							<legend>Podana fraza zawarta jest w:</legend>
								<input type=\"radio\" checked name=\"szuk_w\" value=\"pseudo\"/>Pseudonim
								<input type=\"radio\" name=\"szuk_w\" value=\"login\"/>Login
								<input type=\"radio\" name=\"szuk_w\" value=\"haslo\"/>Has�o
								<input type=\"radio\" name=\"szuk_w\" value=\"mail\"/>Mail
								<input type=\"radio\" name=\"szuk_w\" value=\"gadu\"/>GG
								<input type=\"radio\" name=\"szuk_w\" value=\"ip\"/>IP
						</fieldset>
						<fieldset>
							<legend>Metoda szukania:</legend>
								<input type=\"radio\" name=\"search_method\" checked value=\"1\"/>dokladnie
								<input type=\"radio\" name=\"search_method\" value=\"2\"/>rozpoczyna sie od
								<input type=\"radio\" name=\"search_method\" value=\"3\"/>zawiera w
								<input type=\"radio\" name=\"search_method\" value=\"4\"/>konczy sie na
								
						</fieldset>
						
						<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
						</form>
					</li>
				</ul>";


            }


            $pod = $_GET['pod'];
            $search_method = (int)$_GET['search_method'];
            $nk = czysta_zmienna_post($_GET['nazwa_szukanego']);
            $sz_w = czysta_zmienna_post($_GET['szuk_w']);

            // ustawia - formatuje zmienna $nk w zaleznosci od zaznaczonej opcji radio
            $sposob = array("login" => kodowanie_loginu($nk), "haslo" => kodowanie_hasla($nk), "pseudo" => $nk, "mail" => $nk, "gadu" => $nk, "ip" => $nk);

            $do_ligi = array("a", "b", "c", "d", "e", "1", "2", "3", "4", "5");

            //jesli zaznaczylem opcje inna niz 'dokladna' to dostawiam do WHERE co ograniczam
            $for_sql_ = (!empty($search_method) && array_key_exists($sz_w, $sposob)) ? ($search_method != 1 ? " && " . $sz_w : null) : null;

            $search_value = array(
                1 => " && u.{$sz_w} = '{$sposob[$sz_w]}'",
                2 => "LIKE '{$nk}%'",
                3 => "LIKE '%{$nk}%'",
                4 => "LIKE '%{$nk}'"
            );


            //dla poszczegolnej zmiennej z get ustawia warunek dla zapytania
            $tablica = array("all" => "", 'ban' => " && u.status='3'", "act" => " && u.status='2' ", "nact" => " && u.status='1' ", "szuk" => "{$search_value[$search_method]}");

            if (in_array($pod, $do_ligi) && empty($r_id)) {
                note("Wybierz rozgrywke dla ktorej chcesz pokazac zawodnikow z ligi/grupy <b>{$pod}</b>!", "blad");
                wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
            } elseif (!empty($r_id)) {
                $if_join_liga = array("LEFT JOIN liga_gracze  g
				ON  u.id = g.id_gracza && g.status='{$pod}'", "g.r_id='{$r_id}' &&");
            }

            $na_stronie = 20;
            $podmenu = (int)$_GET['strona'];
            if (!$podmenu) {
                $podmenu = 1;
            }
            $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM uzytkownicy u 	WHERE  vliga='{$wybrana_gra}' {$for_sql_} {$tablica[$pod]}  ORDER BY id DESC"));
            $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
            $start = ($podmenu - 1) * $na_stronie;

            $zapytanie = "SELECT u.id,u.status,u.ip,u.mail,u.host,u.przegladarka FROM uzytkownicy u 
				{$if_join_liga[0]} where {$if_join_liga[1]} u.vliga='{$wybrana_gra}' {$for_sql_}  {$tablica[$pod]}  ORDER BY `pseudo` ASC LIMIT {$start},{$na_stronie};";
            $wyn = mysql_query($zapytanie);

            //echo "$zapytanie";

            if ($search_value != 1 && ($sz_w == 'login' || $sz_w == 'haslo')) {
                note('Nie mozna porownywac fragmentu do loginu lub hasla. Dla pol: login i haslo musisz zaznaczyc opcje: dokladnie', 'blad');
            }
            print "<form method=\"post\" action=\"\" name=\"send\"><br/>";
            wyswietl_formularz_wysylania_gg();
            echo "<table width=\"100%\" border=\"1\" frame=\"void\">
					<tr class=\"naglowek\">
						<td></tD>
						<td>Nick</tD>
						<td>GG</tD>
						<td>Mail</tD>
						<td>Ip</tD>
						<td>przegladarka</tD>
						<td>Stat</tD>
						<td width=\"30\">Ostrz</tD>
						<td>Ban</td>
						<td>Ak</td>
						<td>Edit</td>
						<td>Host</td>
						<td>VIP</td>
						<td>Pass</td>
						<td>Del</td>
						<td width=50>GG</td>
						<td width=50>Mail</td>
					</tr>";
            while ($rek = mysql_fetch_array($wyn)) {
                $ostrzezenie = mysql_num_rows(mysql_query("SELECT * FROM ostrzezenia WHERE id_gracza='{$rek['id']}';"));
                $zgoda = sprawdz_zgode_na_host($rek['id']);
                print "<tr" . kolor($a++) . ">
						<td>{$a}</td>
						<td>" . linkownik('profil', $rek['id'], '') . "</td>
						<td>" . linkownik('gg', $rek['id'], '') . "</td>
						<td>{$rek['mail']}</td>
						<td>{$rek['ip']}</td>
						<td>{$rek['przegladarka']}</td>
						<td>" . status_obrazkowy($rek['status']) . "</td>
						<td><a href=\"" . AKT . "&wykonaj=ostrzezenia&id={$rek['id']}\" class=\"i-note\">{$ostrzezenie}</a></td>
						<td><a href=\"" . AKT . "&wykonaj=ban&id={$rek['id']}&mial={$rek['status']}\" class=\"i-ban\"></a></td>
						<td><a href=\"" . AKT . "&wykonaj=aktywuj&id={$rek['id']}\" class=\"i-active-acount\"></a></td>
						<td><a href=\"" . AKT . "&wykonaj=edytuj&id={$rek['id']}\" class=\"i-edit\"></a></td>
						<td>
							<a href=\"" . AKT . "&wykonaj=host&id={$rek['id']}&daj=nie\" class=\"i-hostM" . ($zgoda == 'NIE' ? NULL : "-") . "\"></a>
							<a href=\"" . AKT . "&wykonaj=host&id={$rek['id']}&daj=tak\" class=\"i-hostP" . ($zgoda == 'TAK' ? NULL : "-") . "\"></a>
						</td>
						<td><a href=\"" . AKT . "&wykonaj=vip&id={$rek['id']}\" class=\"i-change-vip\"></a></td>
						<td><a href=\"" . AKT . "&wykonaj=haslo&id={$rek['id']}\" class=\"i-rename-password\"></a></td>
						<td><a href=\"" . AKT . "&wykonaj=usun&id={$rek['id']}\"  class=\"i-delete\" onclick=\"return confirm('Czy jestes pewien, ze chcesz usunac dane tego gracza?');\"></a></td>
						<td><input type=\"checkbox\" name=\"send_to[]\" value=\"{$rek['id']}\"/></td>
						<td><input type=\"checkbox\" name=\"send_to_m[]\" value=\"{$rek['id']}\"/></td></tr>
						";
                $temp = TRUE;
            }
            echo "<tr>
				<td colspan=\"15\">
				</td>
				<td><input type=\"button\" value=\"Z\"  style=\"width:20px;\"	onclick=\"makeCheck('send_to[]');\">
				<input type=\"button\" value=\"O\"  style=\"width:20px;\"	onclick=\"makeUncheck('send_to[]');\">
				</td>
				<td><input type=\"button\" style=\"width:20px;\"	value=\"Z\" onclick=\"makeCheck('send_to_m[]');\">
				<input type=\"button\" value=\"O\" style=\"width:20px;\"	 onclick=\"makeUncheck('send_to_m[]');\">
				</td></tr>
				</table></form>";
            if (empty($temp)) {
                note("Brak graczy", "blad");
            }
            wyswietl_stronnicowanie($podmenu, $wszystkie_strony, AKT . "&strona=", '');


            echo "<ul class=\"glowne_bloki\">
					<li class=\"glowne_bloki_naglowek legenda\"><span>LEGENDA</span></li>
					<li class=\"glowne_bloki_zawartosc\">
						<ul class=\"legendaUL\">
							<li class=\"c1\"><a href=\"#\" class=\"i-access-allow\"></a> konto aktywne</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-access-denied\"></a> konto nieaktywowane </li>
							<li class=\"c1\"><a href=\"#\" class=\"i-user-baned\"></a> konto zbanowane </li>
							<li class=\"c2\"><a href=\"#\" class=\"i-delete\"></a> usuwa konto</li>
							<li class=\"c1\"><a href=\"#\" class=\"i-ban\"></a> banuje konto</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-active-acount\"></a> aktywuje konto (s�uzy do odbanowywania)</li>
							<li class=\"c1\"><a href=\"#\" class=\"i-edit\"></a> edytuje konto</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-rename-password\"></a> generuje nowe haslo</li>
						</ul>
						<div class=\"description\">
							<span>
							Pamietaj, ze maxymalna liczba zaznaczonych graczy to 20!<br/>
							Przy wysylaniu wiadomosci Botem musisz cierpliwie czekac!<br/>
							Dla ulatwienia wysylania, gracze sa podzieleni po 20stu na strone
							</span>
						</div>
					</li>
				</ul>";

        }
    } else {
        note("Brak dostepu", "blad");
    }
}
wybor_gry_admin(9);
?>
