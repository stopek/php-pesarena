<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
require_once('include/functions/function.edit-profile.php');
require_once('include/functions/calculation/calculation.check-errors.php');
require_once('include/functions/function.admin-druzyny.php');

$personalne = (int)$_POST['personalne'];
$zapisz_gra = (int)$_POST['zapisz_gra'];
$haslo = (int)$_POST['haslo'];

if ($_GET['podmenu'] == 'popraw,bledy') {
    wielka_poprawa_bledow_2(DEFINIOWANE_ID, DEFINIOWANA_GRA);
}

//zapisuje mozliwosc gry dla danego gracza w dana wersje gry
if (!empty($zapisz_gra)) {
    profil_zapisz_wersje_gry($wersje_gry);
}
//zapisuje mozliwosc gry dla danego gracza w dana wersje gry - END

//zapisuje dla danego gracza haslo
if (!empty($haslo)) {
    profil_zmien_haslo();
}
//zapisuje dla danego gracza haslo - END


// zmiana personalnych ustawien
if (!empty($personalne)) {
    profil_dane_personalne($wersje_gry);
}
// zmiana personalnych ustawien - END


if ($podmenu == 'modul,wlacz' || $podmenu == 'modul,wylacz') {
    profil_moduly($podmenu, (int)$podopcja);
}


// pobieranie wartosci do edycji
$wynik = mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where id='" . DEFINIOWANE_ID . "';");
$rek = mysql_fetch_array($wynik);
$gadu = $rek['gadu'];
$mail = $rek['mail'];
$wersje = $rek['vliga'];
// pobranie wartosci w bazy do edycji - END


?>


<div id="zakladka_1_per" class="display_none">
    <div class="rejestracja_naglowki"></div>
    <div id="rejestracja">
        <div class="regal_admin">
            <?
            $zapytanie = mysql_query("SELECT l.recordText,l.recordID,l.opis, count(p.id) as policz FROM " . BLOKI_MENU_LISTA . " l  LEFT JOIN " . BLOKI_MENU_PRZYPISANIE . " p
					ON l.recordID = p._id_bloku && p._id_gracza='" . DEFINIOWANE_ID . "' && p.vliga='" . DEFINIOWANA_GRA . "' GROUP BY l.recordID ORDER BY l.recordText");
            while ($rek = mysql_fetch_array($zapytanie)) {
                $logo = file_exists("grafiki/bloki/{$rek['recordText']}.jpg") ? " grafiki/bloki/{$rek['recordText']}.jpg " : "img/bloki_brak_loga.jpg";
                echo "
						<div class=\"bloki\">
							<img src=\"{$logo}\" alt=\"wizualna prezentacja bloku menu\"/>
							<div class=\"bloki_opis\">
								<span>{$rek['opis']}</span>
							</div>
							<ul>
								" . (empty($rek['policz']) ? "
								<li class=\"wlacz\" onclick=\"potwierdzenie('profil,edytuj-modul,wlacz-{$rek['recordID']}.htm','Czy jestes pewien, ze chcesz wlaczyc ten modul?');\"></li>
								" : "
								<li class=\"wylacz\" onclick=\"potwierdzenie('profil,edytuj-modul,wylacz-{$rek['recordID']}.htm','Czy jestes pewien, ze chcesz wylaczyc ten modul?');\"></li>
								") . "
							</ul>
						</div>
						";
            }
            ?>
        </div>
    </div>
    <div class="rejestracja_naglowki"></div>
</div>


<div id="zakladka_2_per" class="display_none">
    <div class="rejestracja_naglowki">Popraw bledy!</div>
    <div class="rejestracja">
        <h1><a href="profil,edytuj-popraw,bledy.htm">Popraw bledy! </a></h1>
    </div>
    <div class="rejestracja_naglowki"></div>


    <br/>
    <div class="rejestracja_naglowki"><?= M281 ?></div>
    <div class="rejestracja">
        <form method="post" action="">
            <div class="regal">
                <div class="rejestracja_lewa"><?= M282 ?></div>
                <div class="rejestracja_prawa"><input type="password" name="shaslo"/></div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M283 ?></div>
                <div class="rejestracja_prawa"><input type="password" name="shaslo2"/></div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M284 ?></div>
                <div class="rejestracja_prawa"><input type="password" name="nhaslo"/></div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M283 ?></div>
                <div class="rejestracja_prawa"><input type="password" name="nhaslo2"/></div>
            </div>
            <div class="text_center"><input type="image" name="haslo" value="1" src="img/zakoncz.jpg" alt="<?= M285 ?>"
                                            title="<?= M285 ?>"/></div>
        </form>
    </div>
    <div class="rejestracja_naglowki"></div>
    <br/>


    <div class="rejestracja_naglowki"><?= M286 ?></div>
    <div class="rejestracja">
        <form method="post" action="">
            <div class="regal">
                <div class="rejestracja_lewa"><?= M287 ?></div>
                <div class="rejestracja_prawa">
                    <input type="hidden" name="personalne" value="1"/>
                    <select name="versja">
                        <? foreach ($wersje_gry as $value) {
                            print "<option value=\"{$value}\" " . ($wersje == $value ? "selected=\"selected\" style=\"background:#8b8b8b;\"" : null) . "> {$opis_gry[$value][0]}</option>";
                        } ?>
                    </select>
                </div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M81 ?></div>
                <div class="rejestracja_prawa"><input type="text" name="gadu" value="<?= $gadu ?>"/></div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M82 ?></div>
                <div class="rejestracja_prawa"><input type="text" value="<?= $mail ?>" disabled="disabled"/></div>
            </div>
            <div class="regal">
                <div class="rejestracja_lewa"><?= M288 ?></div>
                <div class="rejestracja_prawa">
                    <? wybor_klubu('nowyklub'); ?>
                </div>
            </div>
            <div class="text_center"><input type="image" src="img/zakoncz.jpg" alt="<?= M285 ?>" title="<?= M285 ?>"/>
            </div>
        </form>
    </div>
    <div class="rejestracja_naglowki"></div>


</div>


<div id="zakladka_3_per" class="display_none">
    <?
    foreach ($wersje_gry as $value) {
        $licz = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_GRACZE . " where id_gracza='" . DEFINIOWANE_ID . "' && vliga='{$value}';"));
        ?>
        <div class="blok_wersja_gry_title"><?= M290 ?> <?= $opis_gry[$value][0] ?></div>
        <div class="blok_wersja_gry">
            <div class="blok_wersja_gry_lewa">
                <img src="<?= $logo_gry[$value][0] ?>" alt="<?= $key ?>"/>
                <form method="post" action="">
                    <input type="hidden" name="zapisz_gra" value="1"/>
                    <input type="hidden" name="gra" value="<?= $value ?>"/>
                    <select name="how">
                        <option <?= ($licz['status'] == 1 ? "selected=\"selected\"" : null) ?>
                                value="1"><?= M291 ?></option>
                        <option <?= ($licz['status'] == 0 ? "selected=\"selected\"" : null) ?>
                                value="0"><?= M292 ?></option>
                    </select>
                    <input type="image" src="img/wykonaj.jpg" alt="<?= M285 ?>" title="<?= M285 ?>"/>
                </form>
            </div>
            <div class="blok_wersja_gry_prawa">
                <ul>
                    <li><?= M294 ?> <?= sprawdz_ranking_id($id_zalogowanego_usera, $value) ?></li>
                    <li><?= M295 ?> <?= przegrane_mecze($id_zalogowanego_usera, $value) ?>    </li>
                    <li><?= M296 ?> <?= wygrane_mecze($id_zalogowanego_usera, $value) ?>        </li>
                    <li><?= M297 ?> <?= remisy($id_zalogowanego_usera, $value) ?>            </li>
                    <li><?= M298 ?> <?= przegrane_mecze($id_zalogowanego_usera, $value) ?>    </li>
                    <li><?= M299 ?> <?= przegrane_mecze($id_zalogowanego_usera, $value) ?>    </li>
                </ul>
            </div>
        </div>
        <div class="blok_wersja_gry_dol"></div>
        <hr class="blok_wersja_hr"/>
        <?
    }
    ?>

</div>