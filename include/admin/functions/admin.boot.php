<?

function send_gg_message($numer, $wiadomosc, $gg, $id)
{

}

function send_mail_message($mail, $message)
{

    $subject = ' www.pesarena.pl - Wazna wiadomosc!';
    $headers = 'From: administrator@pesarena.pl' . "\r\n" .
        'Reply-To: administrator@pesarena.pl' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
    if (mail($mail, $subject, $message, $headers)) {
        note("Wiadomosc na adres: <b>{$mail}</b> zostala wyslana.", "info");
    } else {
        note("Wystapil blad podczas wysylania wiadomosci na adres: <b>{$mail}</b>", "blad");
    }
}

function wyswietl_formularz_wysylania_gg()
{
    ini_set('display_errors', 0);
    if ($_POST['cmd'] == '1') {
        $zrodlo = $_POST['send_to'];
        if (count($zrodlo) > 0 && count($zrodlo) <= 20) {
            require_once 'include/admin/funkcje/phplibgadu.php';

            $number = GG_STRONY;
            $password = GG_HASLO;
            $gg = new GG;

            $gg->debug_level = 255;//& ~GG::DEBUG_HTTP;

            $p = array();
            $p['uin'] = $number;
            $p['password'] = $password;

            if (!$gg->login($p)) {
                return " blad ";
            }

            $gg->notify();

            //$zrodlo = $_POST['send_to'];
            //foreach ($zrodlo as $id)
            //{

            $rec = sprawdz_gadu_id($id);
            $msg = $_POST['tresc'];

            $z = array('{nick}', '{gg}', '{mail}');
            $na = array(sprawdz_login_id($id), sprawdz_gadu_id($id), sprawdz_mail_id($id));
            $msg = str_replace($z, $na, $msg);
            $gg->send_message(array(9855119, 6806271), 'siema eniu');


            note('Wiadomosc do numeru: <b>' . $rec . '</b> wyslana ', 'info');

            //}

            $gg->logout();

        } else {
            if (count($zrodlo) == 0) {
                note('Brak zaznaczonych numerow GG', 'blad');
            } else {
                note("Max jednorazowa liczba graczy to <b>20</b>", "blad");
            }
        }

        $zrodlo_m = $_POST['send_to_m'];
        if (count($zrodlo_m) > 0) {
            foreach ($zrodlo_m as $numer) {
                send_mail_message(sprawdz_mail_id($numer), $_POST['tresc']);
            }
        } else {
            note('Brak zaznaczonych numerow MAIL', 'blad');
        }
    }


    echo "<ul class=\"glowne_bloki\">
		<li class=\"glowne_bloki_naglowek  bot\">Wpisz tresc wiadomosci, ktora wyslesz zaznaczonym zawodnikom</li>
		<li class=\"glowne_bloki_zawartosc\">
			<input type=\"hidden\" name=\"send\" value=\"1\"/>
			<input type=\"hidden\" name=\"cmd\" value=\"1\"/>
			<textarea  name=\"tresc\" class=\"textarea_a_full\"></textarea>
			<input type=\"image\" src=\"img/_admin_wykonaj_.jpg\" alt=\"\" title=\"\" onclick=\"return confirm('Czy napewno chcesz wyslac ta wiadomosc to zaznaczonych graczy?')\"/>
		</li>
	</ul>";
}



