<?
if (!defined('PEBLOCK') || !PEBLOCK) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/');
    exit;
}
$zaloguj = czysta_zmienna_post($_POST['zaloguj']);
$pamietaj = (int)$_POST['pamietaj'];

$id_g = $_SESSION ['id_goscia_odb'];
if (isset($_POST['akt']) && !empty($id_g)) {
    if (mysql_query("UPDATE uzytkownicy SET status='2' WHERE id='{$id_g}' && status='3'") &&
        mysql_query("DELETE FROM uzytkownicy_ban WHERE id_usera='{$id_g}'")
    ) {
        note("Zostales odbanowany <b>" . sprawdz_login_id($id_g) . "</b>! Mozesz sie juz zalogowac!", "info");
    } else {
        note("Wystapil blad podczas odbanowywania", "blad");
    }
    unset($_SESSION ['id_goscia_odb']);
}


if (!empty($zaloguj)) {
    $login = czysta_zmienna_post($_POST['login']);
    $haslo = czysta_zmienna_post($_POST['haslo']);
    if ($login && $haslo) {
        $haslokd = kodowanie_hasla($haslo);
        $loginkd = kodowanie_loginu($login);
        $ile = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " WHERE login='{$loginkd}' && haslo='{$haslokd}' && status='2';"));
        $ipk = $_SERVER['REMOTE_ADDR'];
        if ($ile != '0') {
            $ile_ipkow = sprawdz_ip($ipk);
            if ($ile_ipkow == '1' || $ile_ipkow == '0' || gracze_uprzywilejowani($loginkd, $ipk)) {
                $ipk = $_SERVER['REMOTE_ADDR'];
                echo $zalogowany;
                $zalogowany = sprawdz_login($loginkd);
                $_SESSION['zalogowany'] = $zalogowany;


                // logowanie do cookie
                if (!empty($_POST['pamietaj'])) {
                    $ipk = $_SERVER['REMOTE_ADDR'];
                    $uniq = ($prefix . uniqid(hash("md5", time()), TRUE) . time() . @$_SERVER['REMOTE_ADDR']);
                    $time = strtotime('+1 week');
                    $id_user = sprawdz_id_login($zalogowany);

                    mysql_query("DELETE FROM cookie WHERE id_gracza='{$id_user}'");
                    mysql_query("INSERT INTO cookie VALUES('','{$id_user}','{$uniq}','{$ipk}','{$time}')");

                    $_SESSION['c']['zapisz_cookie'] = TRUE;

                    $_SESSION['c']['save_uniqid'] = $uniq;
                    $_SESSION['c']['save_user_id'] = $id_user;
                    note("Wybrales opcje Pamietaj! Zostales wlasnie zalogowany na okres jednego tygodnia!", "blad");
                }
                // logowanie do cookie


                note(M424 . ": {$zalogowany} : " . M425 . " <a href=\"index.php\">link</a>!", "info");
                //	przeladuj($_SESSION['starsza']);
                przeladuj("http://stopczynski.pl/pesarena/");
                $rob = mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET last_log=NOW(),counter=counter+1,ip='{$ipk}' WHERE login='{$loginkd}' && haslo='{$haslokd}' && status='2';");


            } else {
                $r = mysql_query("UPDATE " . TABELA_UZYTKOWNICY . " SET status='3' where ip='{$ipk}';");
                note("IP: <b>{$ipk}</b> " . M248, "blad");
            }
        } else {
            $ban = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where login='{$loginkd}' && haslo='{$haslokd}' && status='3';"));
            $akt = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_UZYTKOWNICY . " where login='{$loginkd}' && haslo='{$haslokd}' && status='1';"));
            if ($ban == '1') {
                $szczegoly = mysql_fetch_array(mysql_query("SELECT * FROM uzytkownicy_ban WHERE id_usera='" . sprawdz_id_login(sprawdz_login($loginkd)) . "'"));
                note(M249 . " <b>{$ipk}</b> " . M250, "blad");
                if ($szczegoly['id']) {
                    note("<i>Twoje konto jest zbanowane!</i>
					<img src=img/acces_none.png style=float:left;><br/>Wystawiono: <b>" . formatuj_date($szczegoly['data_wystawienia']) . "</b>
					<br/>Powod: <b>{$szczegoly['powod']}</b> 
					<br/>Termin do: <b>" . formatuj_date(date('Y-m-d H:i:s', $szczegoly['termin'])) . "</b>
					", "fieldset");
                }
                if ($szczegoly['termin'] < strtotime("now")) {
                    $_SESSION ['id_goscia_odb'] = $szczegoly['id_usera'];
                    ?>
                    <form method="post" action=""><input type="hidden" name="akt" value="1"/>
                        <iframe width="100%" height="300" src="doc/regulamin_strony.php" frameborder="no"></iframe>
                        <br/>
                        <fieldset>
                            <legend>Aktywuj moje konto!</legend>
                            <font color="#b60009">Jesli zapoznales sie z powyzszym regulaminem, rozumiesz swoj blad i
                                nie bedziesz go powielac - kliknij w przycisk zakoncz!</font>
                            <p align="right"><input type="image" src="img/zakoncz.jpg" title="" alt=""/></p>
                        </fieldset>
                    </form><br/>
                    <?
                }
            } elseif ($akt == '1') {
                note(M251, "blad");
            } else {
                note(M252, "blad");
            }
        }
    } else {

        note(M253, "blad");
    }
}

?>
