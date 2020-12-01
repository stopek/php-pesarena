<?


function online($ip, $kogo)
{
    $action = strip_tags(basename($_SERVER['REQUEST_URI']));
    $host = strip_tags($_SERVER['HTTP_HOST']);
    $przegladarka = nazwij_przegladarke($_SERVER['HTTP_USER_AGENT']);
    if (!empty($_SESSION['zalogowany'])) {
        $delete_za_ip = mysql_query("DELETE FROM " . TABELA_ONLINE . " where ip='{$ip}';");
        $ip = $_SESSION['zalogowany'];
    }
    if (mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_ONLINE . " where ip='{$ip}'")) == '0') {
        $insert = mysql_query("INSERT INTO " . TABELA_ONLINE . " values('','{$ip}',NOW(),NOW(),'{$action}','{$przegladarka}','{$host}');");
    } else {
        $update = mysql_query("UPDATE " . TABELA_ONLINE . " SET end=NOW(), action='{$action}', przegladarka='{$przegladarka}', host='{$host}',ip='{$ip}'	where ip='{$ip}';");
    }

    $lacz = mysql_query("SELECT * FROM " . TABELA_ONLINE . " order by id desc;");
    $a = 1;
    $temp = 0;
    while ($rek = mysql_fetch_array($lacz)) {


        if (strtotime('NOW') - strtotime($rek['end']) > ONLINE_TIME) {
            $delete = mysql_query("DELETE FROM " . TABELA_ONLINE . " where id='{$rek['id']}';");
        }

        $czas = strtotime('NOW') - strtotime($rek['start']);

        if ($kogo == 'user' && sprawdz_id_login($rek['ip']) && $temp < ONLINE_GOSP) {

            echo "
						<ul " . kolor($a . "_rank_gosp") . ">
							<li><div class=\"ranking_g\">" . $a++ . "</div></li>
							<li class=\"online_profil\"><a href=\"online-{$rek['id']}.htm\">{$rek['ip']}</a></li>
							<li class=\"online_czas\">" . round($czas / 60, 1) . "min</li>
							<li class=\"online_play\">" . linkownik('graj', sprawdz_id_login($rek['ip']), '') . "</li>
							<li>" . linkownik('gg', sprawdz_id_login($rek['ip']), '') . "</li>
						</ul>
					";
            $temp++;
        }
        if ($kogo == 'gosc' && !sprawdz_id_login($rek['ip']) && $temp < ONLINE_GOSC) {
            echo "
						<ul " . kolor($a . "_rank_gosc") . ">
							<li><div class=\"ranking_g\">" . $a++ . "</div></li>
							<li class=\"online_profil\"><a href=\"online-{$rek['id']}.htm\">ArenaGuest</a></li>
							<li class=\"online_czas\">" . round($czas / 60, 1) . "min</li>
						</ul>
					";
            $temp++;
        }

    }
    if (empty($temp)) {
        note("Nikogo nie goscimy", "blad");
    }
}

function odwiedzin()
{
    $wynik = mysql_fetch_array(mysql_query("SELECT max(id) FROM " . TABELA_ONLINE . " group by id"));
    return $wynik[0];
}


function na_stronie()
{
    $wynik = mysql_num_rows(mysql_query("SELECT * FROM " . TABELA_ONLINE . " GROUP BY id"));
    return $wynik;
}


function szczegoly_online($id)
{
    $sql = mysql_query("SELECT * FROM " . TABELA_ONLINE . " WHERE id='{$id}';");
    if (mysql_num_rows($sql) == '0') {
        return note(M304, 'blad');
    }

    $rekord = mysql_fetch_array($sql);
    $czas = strtotime('NOW') - strtotime($rekord['start']);
    note(M305 . ' <b>' . odwiedzin() . '</b>', 'info');
    echo '
	<div class="profil_ksiazki">
		
		<fieldset>
			<fieldset class="profil_image_fieldset">
				<img src="grafiki/przegladarki/' . $rekord['przegladarka'] . '.png" class="image_opis"/>
			</fieldset>
				<div class="profil_pasek_jasny"><b>' . M138 . '</b>: ' . (!sprawdz_id_login($rekord['ip']) ? 'ArenaGuest nr ' . $id : $rekord['ip']) . '</div>
				<div class="profil_pasek_ciemny"><b>' . M308 . '</b>: ' . $rekord['host'] . '</div>
				<div class="profil_pasek_jasny"><b>' . M309 . '</b>: ' . $rekord['przegladarka'] . '</div>
				<div class="profil_pasek_ciemny"><b>' . M310 . '</b>: ' . $rekord['start'] . '</div>
				<div class="profil_pasek_jasny"><b>' . M311 . '</b>: ' . $rekord['end'] . '</div>
				<div class="profil_pasek_ciemny"><b>' . M312 . '</b>: ' . $rekord['action'] . '</div>
				<div class="profil_pasek_jasny"><b>' . M313 . '</b>: ' . round($czas / 60, 2) . ' min.</div>
				<div class="profil_pasek_ciemny"><b>' . M314 . '</b>: ' . $rekord['id'] . '</div>
				
				
		</fieldset>
		
	</div>';

}

// funkcje online - END


?>
