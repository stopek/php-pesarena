<?
function admin_login()
{
    $zaloguj = czysta_zmienna_post($_POST['zaloguj']);
    if (!empty($zaloguj)) {
        $login = czysta_zmienna_post($_POST['login']);
        $haslo = czysta_zmienna_post($_POST['haslo']);
        if ($login && $haslo) {
            $data = mysql_fetch_array(mysql_query("SELECT lastlog,ip,host,count(id) as zlicz,login,id FROM " . TABELA_ADMINI . " WHERE login='{$login}' && haslo='{$haslo}' GROUP BY id"));
            if (!empty($data['zlicz'])) {

                $_SESSION['admin_last'][1] = $data['ip'];
                $_SESSION['admin_last'][0] = $data['lastlog'];
                $_SESSION['admin_last'][2] = $data['host'];
                $_SESSION['login'] = true;
                $_SESSION['login_id'] = $data['id'];
                $_SESSION['szefuniu'] = $data['login'];

                $host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                $ip = $_SERVER['REMOTE_ADDR'];
                $sql = mysql_query("UPDATE " . TABELA_ADMINI . " SET lastlog=NOW(), ip = '{$ip}',host = '{$host}',counter=counter+1 WHERE login='{$login}' && haslo='{$haslo}';");
                note("Zostales poprawnie zalogowany do panelu administracyjnego. Za chwile zostaniesz przekierowany.", "info");
                przeladuj("administrator.php");
            } else {
                note("Taki uzytkownik nie istnieje!", "blad");
            }
        } else {
            note("Pola zle wypelnione!", "blad");
        }
    }
    formularz_logowania3();
}

?>