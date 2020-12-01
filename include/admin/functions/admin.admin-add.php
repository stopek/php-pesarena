<?
//--------------------//
// komunikaty zebrane //
//--------------------//

function deleteAdmin($usun)
{
    $whoim = mysql_fetch_array(mysql_query("SELECT id,count(id) FROM " . TABELA_ADMINI . " WHERE id='{$usun}' GROUP BY id"));
    if ($_SESSION['login_id'] != $whoim[0]) {
        if (!empty($whoim[1])) {
            if (mysql_query("DELETE FROM " . TABELA_ADMINI . " where id='{$usun}'")) {
                note(admsg_deleteAdminDone, "info");
            } else {
                note(admsg_deleteAdminError, "blad");
            }
        } else {
            note(admsg_deleteAdminNoExistsError, "blad");
        }
    } else {
        note(admsg_deleteYourselfError, "blad");
    }
}


function updateAdmin($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie, $edycja)
{
    foreach ($_POST['dostep'] as $value) {
        $dostep_admina .= str_replace(',', '', $value) . ",";
    }
    foreach ($_POST['wersje_gier_dostep'] as $value) {
        $gry_admina .= str_replace(',', '', $value) . ",";
    }

    if (!empty($login) && !empty($nick) &&
        !empty($stanowisko) && !empty($gg) && !empty($mail)) {
        if (mysql_query("UPDATE " . TABELA_ADMINI . " SET login='{$login}'" . (!empty($haslo) ? ",haslo='{$haslo}'" : null) . ",
		druzyna='{$druzyna}',stanowisko='{$stanowisko}',gg='{$gg}',mail='{$mail}',osobie='{$osobie}',nick='{$nick}',access='{$dostep_admina}',vliga='{$gry_admina}' WHERE id='{$edycja}';")) {
            note(admsg_adminEditDone, "info");
        } else {
            note(admsg_adminEditError, "blad");
        }
    } else {
        note(admsg_wFields, "blad");
    }

}

function addAdmin($login, $haslo, $nick, $druzyna, $stanowisko, $gg, $mail, $osobie)
{
    foreach ($_POST['dostep'] as $value) {
        $dostep_admina .= str_replace(',', '', $value) . ",";
    }
    foreach ($_POST['wersje_gier_dostep'] as $value) {
        $gry_admina .= str_replace(',', '', $value) . ",";
    }
    $count = mysql_fetch_array(mysql_query("SELECT count(id)FROM " . TABELA_ADMINI . " WHERE login='{$login}'"));

    if (!empty($login) && !empty($haslo) && !empty($nick) && !empty($stanowisko) && !empty($gg) && !empty($mail)
        && sprawdz_mail($mail) == 1 && !empty($gg) && strlen($haslo) > 5 && empty($count[0])
    ) {
        if (mysql_query("insert into " . TABELA_ADMINI . " values('','{$login}','{$haslo}','{$dostep_admina}','{$nick}','{$druzyna}','{$stanowisko}','{$mail}','{$gg}','{$osobie}','{$gry_admina}','','','','');")) {
            note(admsg_addAdminDone, "info");
        } else {
            note(admsg_addAdminError, "blad");
        }
    } else {
        if (sprawdz_mail($mail) != 1) {
            $errors .= admsg_badMailError . "<br/>";
        }
        if (empty($gg)) {
            $errors .= admsg_badGgError . "<br/>";
        }
        if (empty($druzyna)) {
            $errors .= admsg_unselectedTeam . "<br/>";
        }
        if (empty($nick)) {
            $errors .= admsg_badNoNick . "<br/>";
        }
        if (strlen($haslo) <= 5) {
            $errors .= admsg_badPass . "<br/>";
        }
        if (!empty($count[0])) {
            $errors .= admsg_adminExistsError . "<br/>";
        }
        if (
            empty($login) || empty($haslo) || empty($nick) ||
            empty($stanowisko) || empty($gg) || empty($mail)) {
            $errors .= admsg_wFields . "<br/>";
        }

        note(admsg_showAllErrors . "<br/>{$errors}", "blad");

    }
}


function showAdminList()
{
    $a2 = mysql_query("SELECT a.*,d.id as d_id,d.nazwa as d_nazwa FROM " . TABELA_ADMINI . " a LEFT JOIN " . TABELA_DRUZYNY . " d ON d.id=a.druzyna");
    $b = 1;
    while ($as2 = mysql_fetch_array($a2)) {
        $arr[$b][] = $b++;
        $arr[$b][] = $as2['login'] . " " . linkownik('profil', $as2['nick'], '');
        $arr[$b][] = mini_logo_druzyny($as2['d_id']) . $as2['d_nazwa'];
        $arr[$b][] = $as2['stanowisko'];
        $arr[$b][] = $as2['gg'];
        $arr[$b][] = $as2['mail'];
        $arr[$b][] = "<a href=\"" . AKT . "&edycja={$as2['id']}\" class=\"i-edit\"></a>";
        $arr[$b][] = "<a href=\"" . AKT . "&usun={$as2['id']}\"  class=\"i-delete\"></a>";
        $arr[$b][] = "<input type=\"checkbox\" name=\"send_to[]\" value=\"{$as2['nick']}\"/>";
    }

    $table = new createTable('adminFullTable');
    $table->setTableHead(array(admsg_id, admsg_login, admsg_klub, admsg_stanowisko, admsg_gg, admsg_mail, admsg_edytuj, admsg_usun, admsg_botgg), "adminHeadersClass");
    $table->setTableBody($arr);
    echo $table->getTable();
}


function showForm($rekord, $coteraz, $wybory_)
{


    ?>


    <ul class="glowne_bloki">
        <li class="glowne_bloki_naglowek"><b><?= admsg_sayHello . " " . $_SESSION['szefuniu'] ?></b></li>
        <li class="glowne_bloki_zawartosc">
            <form method="post" action="" name="admin_add">
                <fieldset class="adminAccessList">
                    <input type="hidden" name="cmd" value="1"/>
                    <ul class="adminAccessOption adminFields">
                        <li><input type="text" name="login"
                                   value="<?= $_POST['login'] ?><?= $rekord['login'] ?>"/><span><?= admsg_login ?></span>
                        </li>
                        <li><input type="text" name="haslo"
                                   value="<?= $_POST['haslo'] ?>"/><span><?= admsg_haslo ?></span></li>
                        <li><input type="text" name="mail"
                                   value="<?= $_POST['mail'] ?><?= $rekord['mail'] ?>"/><span><?= admsg_mail ?></span>
                        </li>
                        <li><input type="text" name="gg"
                                   value="<?= $_POST['gg'] ?><?= $rekord['gg'] ?>"/><span><?= admsg_gg ?></span></li>
                        <li><input type="text" name="nick"
                                   value="<?= $_POST['nick'] ?><?= $rekord['nick'] ?>"/><span><?= admsg_nickId ?></span>
                        </li>
                        <li><input type="text" name="stanowisko"
                                   value="<?= $_POST['stanowisko'] ?><?= $rekord['stanowisko'] ?>"/><span><?= admsg_stanowisko ?></span>
                        </li>
                        <li><input type="text" name="osobie"
                                   value="<?= $_POST['osobie'] ?><?= $rekord['osobie'] ?>"/><span><?= admsg_osobie ?></span>
                        </li>
                        <li><?= wybor_klubu_czysto('druzyna', '') ?><span><?= admsg_druyzna ?></span></li>
                    </ul>
                </fieldset>

                <?
                $wystapily = array();
                foreach ($wybory_ as $key => $value) {
                    $var = explode('_', $key);
                    $keys[$var[0]][] = (!empty($var[1]) ? $var[1] : $var[0]) . (!empty($var[2]) ? "_{$var[2]}" : null) . (!empty($var[3]) ? "_{$var[3]}" : null);
                }
                if (count($_POST['wersje_gier_dostep']) == 0) {
                    $_POST['wersje_gier_dostep'] = array();
                }
                if (count($_POST['dostep']) == 0) {
                    $_POST['dostep'] = array();
                }


                foreach ($keys as $key => $value) {
                    echo "
					<fieldset class=\"adminAccessList\">
						<legend><b>{$key}</b></legend><span class=\"downIkon\">
						<a href=\"javascript:rozwin('poziom_{$key}')\"></a></span>
						<div id=\"poziom_{$key}\">
							<ul class=\"adminAccessOption\">
								";
                    foreach ($value as $a) {
                        $kdt = $wybory_[$key . '_' . $a];
                        $k = $key . "_" . $a;
                        echo "<li><input type=\"checkbox\" " . ($coteraz == 'popraw' ?
                                (in_array($k, explode(',', $rekord['access'])) ? " checked " : null) :
                                (in_array($k, $_POST['dostep'])) ? " checked " : null) . " name=\"dostep[]\" value=\"{$key}_{$a}\"/><span>" . str_replace('(R)', '<a class="i-notice"></a>', $kdt) . "</span></li>";
                    }
                    echo "
							</ul>
						</div>
					</fieldset>
					";
                }
                ?>


                <fieldset class="adminAccessList">
                    <legend><?= admsg_changeGameType ?></legend>
                    <div class="game">
                        <ul class="game adminAccessOption">
                            <?
                            $sql = mysql_query("SELECT * FROM " . TABELA_GAME);
                            while ($rek = mysql_fetch_array($sql)) {
                                echo "<li><input type=\"checkbox\" " . ($coteraz == 'popraw' ?
                                        (in_array($rek['skrot'], explode(',', $rekord['vliga'])) ? " checked " : null) :
                                        (in_array($rek['skrot'], $_POST['wersje_gier_dostep'])) ? " checked " : null) . "  name=\"wersje_gier_dostep[]\" value=\"{$rek['skrot']}\"/><span>{$rek['nazwa']}</span></li>";
                            }
                            ?>
                        </ul>
                    </div>
                </fieldset>
                <input type="hidden" name="<?= $coteraz ?>" value="<?= $coteraz ?>"/>
                <input type="image" src="img/_admin_wykonaj_.jpg"/>
            </form>
        </li>
    </ul>


    <?


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek legenda\"><span>" . admsg_legenda . "</span></li>
		<li class=\"glowne_bloki_zawartosc\">
			<ul class=\"legendaUL\">
				<li class=\"c2\"><a href=\"#\" class=\"i-delete\"></a> " . admsg_infoDeleteAdminAcount . "</li>
				<li class=\"c1\"><a href=\"#\" class=\"i-edit\"></a> " . admsg_infoEditAdminAcount . "</li>
				<li class=\"c2\"><a href=\"#\" class=\"i-notice\"></a></a> " . admsg_infoDangerousOption . "</li>
			</ul>
			<div class=\"description\"><span>" . admsg_des_1 . "</span></div>
		</li>
	</ul>";
}