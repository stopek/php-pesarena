<?
//--------------------//
// komunikaty zebrane //
//--------------------//

require_once('include/admin/functions/admin.user.php');
require_once('include/admin/functions/admin.boot.php');
require_once("include/admin/funkcje/function.table.php");
$id = (int)$_GET['id'];
$usun = (int)$_GET['usun'];
$edit = (int)$_GET['edit'];

$dodaj = czysta_zmienna_post($_POST['dodaj']);
$edytuj = czysta_zmienna_post($_POST['edytuj']);
$tresc = czysta_zmienna_post($_POST['tresc']);


if (!empty($wybrana_gra)) {
    if (in_array($wybrana_gra, $liga_u_a)) {

        switch ($_GET['wykonaj']) {
            case 'ban':
                if (!in_array('user_banuj', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    banAcount();
                }
                break;
            case 'aktywuj':
                if (!in_array('user_aktywuj', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    activeAcount();
                }
                break;
            case 'edytuj':
                if (!in_array('user_edytuj', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    editAcount();
                }
                break;
            case 'usun':
                if (!in_array('user_usun', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    deleteAcount();
                }
                break;
            case 'haslo':
                if (!in_array('user_haslo', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    changePassword();
                }
                break;
            case 'host':
                if (!in_array('user_host', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    checkHost();
                }
                break;
            case 'ostrzezenia':
                if (!in_array('user_ostrzez', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    if (!empty($edytuj)) updateNote($tresc, $id);
                    if (!empty($dodaj)) addNote($tresc, $id);
                    if (!empty($usun)) deleteNote($usun);


                    if (!empty($edit) && empty($edytuj)) {
                        showNoteForm('edytuj', mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_OSTRZEZENIA . " where id='{$edit}';")));
                    } else {
                        showNoteForm('dodaj', '');
                    }

                    $wyn = mysql_query("SELECT * FROM " . TABELA_OSTRZEZENIA . " WHERE id_gracza='{$id}';");
                    $b = 1;
                    while ($rek = mysql_fetch_array($wyn)) {
                        $arr[$b][] = $b++;
                        $arr[$b][] = $rek['opis'];
                        $arr[$b][] = formatuj_date($rek['data']);
                        $arr[$b][] = "<a href=\"" . AKT . "&edit={$rek['id']}\" class=\"i-edit\"></a>";
                        $arr[$b][] = "<a href=\"" . AKT . "&usun={$rek['id']}\" class=\"i-delete\"></a>";

                    }
                    end_table();
                    $table = new createTable('adminFullTable');
                    $table->setTableHead(array('id', admsg_tresc, admsg_dataDodania, admsg_edytuj, admsg_usun), "adminHeadersClass");
                    $table->setTableBody($arr);
                    echo $table->getTable();


                }###
                break;
            case 'vip':
                if (!in_array('user_vip', explode(',', POZIOM_U_A))) {
                    return note(admsg_accessDenied, "blad");
                } else { ###
                    if (!empty($dodaj)) addVip($id, $tresc);
                    if (!empty($usun)) deleteVip($id);

                    $sprawdz = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_VIP . " where id_gracza='{$id}' && vliga='{$wybrana_gra}'"));
                    if (!empty($sprawdz['id_gracza'])) {
                        echo "
						<ul class=\"glowne_bloki\">
							<li class=\"glowne_bloki_naglowek\">" . admsg_punktyzarzadzanie . " {$co}</li>
							<li class=\"glowne_bloki_zawartosc\">
								<fieldset>" . admsg_heIsVip . " <a href=\"" . AKT . "&usun={$id}\" class=\"i-delete\"></a></fieldset>
							</li>
						</ul>";
                    } else {
                        echo "<ul class=\"glowne_bloki\">
							<li class=\"glowne_bloki_naglowek\">" . admsg_addVip . "</li>
							<li class=\"glowne_bloki_zawartosc\">
								<form method=\"post\" action=\"\">
									<input type=\"hidden\" name=\"dodaj\" value=\"1\"/>
									" . admsg_logoVipa . ": <input type=\"text\" name=\"tresc\" value=\"\"/>
									<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\"/>
								</form>
							</li>
						</ul>";
                    }
                }###
                break;
            default:


                if ($_GET['pod'] == 'szuk') {
                    echo "<ul class=\"glowne_bloki\">
						<li class=\"glowne_bloki_naglowek\">" . admsg_searchUser . "</li>
						<li class=\"glowne_bloki_zawartosc\">
							<form method=\"get\" action=\"\">
								<input type=\"hidden\" name=\"zawodnika\" value=\"szukaj\"/>
								<input type=\"hidden\" name=\"opcja\" value=\"{$_GET['opcja']}\"/>
								<input type=\"hidden\" name=\"pod\" value=\"{$_GET['pod']}\"/>
								<input type=\"hidden\" name=\"wybrana_gra\" value=\"{$_GET['wybrana_gra']}\"/>
								<input type=\"text\"  name=\"nazwa_szukanego\"/>
								<fieldset>
									<legend>" . admsg_searchUserValueField . ":</legend>
										<input type=\"radio\" checked name=\"szuk_w\" value=\"pseudo\"/>" . admsg_pseudo . "
										<input type=\"radio\" name=\"szuk_w\" value=\"login\"/>" . admsg_login . "
										<input type=\"radio\" name=\"szuk_w\" value=\"haslo\"/>" . admsg_haslo . "
										<input type=\"radio\" name=\"szuk_w\" value=\"mail\"/>" . admsg_mail . "
										<input type=\"radio\" name=\"szuk_w\" value=\"gadu\"/>" . admsg_gg . "
										<input type=\"radio\" name=\"szuk_w\" value=\"ip\"/>" . admsg_ip . "
								</fieldset>
								<fieldset>
									<legend>" . admsg_searchMethod . ":</legend>
										<input type=\"radio\" name=\"search_method\" checked value=\"1\"/>" . admsg_dokladnosc1 . "
										<input type=\"radio\" name=\"search_method\" value=\"2\"/>" . admsg_dokladnosc2 . "
										<input type=\"radio\" name=\"search_method\" value=\"3\"/>" . admsg_dokladnosc3 . "
										<input type=\"radio\" name=\"search_method\" value=\"4\"/>" . admsg_dokladnosc4 . "		
								</fieldset>
								<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\"/>
							</form>
						</li>
					</ul>";
                }


                $pod = czysta_zmienna_post($_GET['pod']);
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

                if (in_array($pod, $do_ligi) && empty($r_id) && !empty($pod)) {
                    note(admsg_selectLeagueGameId . " <strong>{$pod}</strong>", "blad");
                    wyswietl_listy_rozgrywek(TABELA_LIGA_LISTA, TABELA_LIGA_GRACZE);
                } elseif (!empty($r_id)) {
                    $if_join_liga = array("LEFT JOIN " . TABELA_LIGA_GRACZE . "  g
					ON  u.id = g.id_gracza && g.status='{$pod}'", "g.r_id='{$r_id}' &&");
                }

                $na_stronie = 20;
                $podmenu = (int)$_GET['strona'];
                if (!$podmenu) {
                    $podmenu = 1;
                }
                $wszystkie_rekordy = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " u WHERE  
				vliga='{$wybrana_gra}' {$for_sql_} {$tablica[$pod]}  ORDER BY id DESC"));
                $wszystkie_strony = floor($wszystkie_rekordy / $na_stronie + 1);
                $start = ($podmenu - 1) * $na_stronie;


                $zapytanie = "SELECT u.id,u.status,u.ip,u.mail,u.host,u.przegladarka,(SELECT count(id) FROM " . TABELA_OSTRZEZENIA . " WHERE id_gracza=u.id GROUP BY id_gracza) as countNotes  FROM " . TABELA_UZYTKOWNICY . " u 
				{$if_join_liga[0]} where {$if_join_liga[1]} u.vliga='{$wybrana_gra}' {$for_sql_}  {$tablica[$pod]}  ORDER BY `pseudo` ASC LIMIT {$start},{$na_stronie};";
                $wyn = mysql_query($zapytanie);


                if ($search_method != 1 && ($sz_w == 'login' || $sz_w == 'haslo')) {
                    note(admsg_searchError_1, 'blad');
                }
                print "<form method=\"post\" action=\"\" name=\"send\">";
                wyswietl_formularz_wysylania_gg();

                $b = 1;
                while ($rek = mysql_fetch_array($wyn)) {
                    $zgoda = sprawdz_zgode_na_host($rek['id']);
                    $countNotes = ($rek['countNotes'] != 0 ? $rek['countNotes'] : 0);
                    $arr[$b][] = $b++;
                    $arr[$b][] = linkownik('profil', $rek['id'], '');
                    $arr[$b][] = linkownik('gg', $rek['id'], '');
                    $arr[$b][] = $rek['mail'];
                    $arr[$b][] = $rek['ip'];
                    $arr[$b][] = $rek['przegladarka'];
                    $arr[$b][] = status_obrazkowy($rek['status']);
                    $arr[$b][] = sprawdz_ranking_id($rek['id'], WYBRANA_GRA);
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=ostrzezenia&id={$rek['id']}\" class=\"i-note\">{$countNotes}</a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=ban&id={$rek['id']}&mial={$rek['status']}\" class=\"i-ban\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=aktywuj&id={$rek['id']}\" class=\"i-active-acount\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=edytuj&id={$rek['id']}\" class=\"i-edit\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=host&id={$rek['id']}&daj=nie\" class=\"i-hostM" . ($zgoda == 'NIE' ? NULL : "-") . "\"></a>
								  <a href=\"" . AKT . "&wykonaj=host&id={$rek['id']}&daj=tak\" class=\"i-hostP" . ($zgoda == 'TAK' ? NULL : "-") . "\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=vip&id={$rek['id']}\" class=\"i-change-vip\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=haslo&id={$rek['id']}\" class=\"i-rename-password\"></a>";
                    $arr[$b][] = "<a href=\"" . AKT . "&wykonaj=usun&id={$rek['id']}\"  class=\"i-delete\" onclick=\"return confirm('" . admsg_confirmQuestion . "');\"></a>";
                    $arr[$b][] = "<input type=\"checkbox\" name=\"send_to[]\" value=\"{$rek['id']}\"/>";
                    $arr[$b][] = "<input type=\"checkbox\" name=\"send_to_m[]\" value=\"{$rek['id']}\"/>";
                }


                $table = new createTable('adminFullTable');
                $table->setTableHead(array('', admsg_pseudo, admsg_gg, admsg_mail, admsg_ip, admsg_przegladarka, admsg_status, 'rank', '', '', '', '', '', '', '', '', 'botgg', 'botmail'), "adminHeadersClass");
                $table->setTableBody($arr);
                echo $table->getTable();


                echo "
					<div class=\"smallField\">
						<div>
							<fieldset><legend>" . admsg_selectAllFieldsBotGG . "</legend>
								<ul>
									<li onclick=\"makeCheck('send_to[]');\" class=\"select\"></li>
									<li onclick=\"makeUncheck('send_to[]');\" class=\"unselect\"></li>
								</ul>
							</fieldset>
						</div>
						<div>
							<fieldset><legend>" . admsg_selectAllFieldsBotMail . "</legend>
								<ul>
									<li onclick=\"makeCheck('send_to_m[]');\" class=\"select\"></li>
									<li onclick=\"makeUncheck('send_to_m[]');\" class=\"unselect\"></li>
								</ul>
							</fieldset>
						</div>
					</div>
				</form>";

                wyswietl_stronnicowanie($podmenu, $wszystkie_strony, AKT . "&strona=", '');


                echo "<ul class=\"glowne_bloki\">
					<li class=\"glowne_bloki_naglowek legenda\"><span>" . admsg_legenda . "</span></li>
					<li class=\"glowne_bloki_zawartosc\">
						<ul class=\"legendaUL\">
							<li class=\"c1\"><a href=\"#\" class=\"i-access-allow\"></a> " . admsg_infoAcountActive . "</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-access-denied\"></a> " . admsg_infoAcountNotActive . "</li>
							<li class=\"c1\"><a href=\"#\" class=\"i-user-baned\"></a> " . admsg_infoAcountBaned . "</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-delete\"></a> " . admsg_infoDeleteAcount . "</li>
							<li class=\"c1\"><a href=\"#\" class=\"i-ban\"></a> " . admsg_infoBanAcount . "</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-active-acount\"></a> " . admsg_infoActivateAcount . "</li>
							<li class=\"c1\"><a href=\"#\" class=\"i-edit\"></a> " . admsg_infoEditAcount . "</li>
							<li class=\"c2\"><a href=\"#\" class=\"i-rename-password\"></a> " . admsg_infoCreatePassword . "</li>
						</ul>
						<div class=\"description\">
							<span>" . admsg_des_2 . "</span>
						</div>
					</li>
				</ul>";
                break;
        }
    } else {
        note(admsg_gameDenied, "blad");
    }
}
wybor_gry_admin(9);
?>
