<?

// dodaje komentarz do bazy strony i forum
function akcja_dodaj_komentarz()
{
    $id = (int)$_POST['id'];
    $tr = str_replace(array('<', '>', '\'', '"'), ' - ', $_POST['tresc']);
    $tresc = czysta_zmienna_post($tr);
    $autor = czysta_zmienna_post($_SESSION['zalogowany']);
    if (!empty($id) && !empty($tresc)) {
        if (!empty($_SESSION['zalogowany'])) {
            if (mysql_query("INSERT INTO " . TABELA_KOMENTARZE_NEWS . " values('','{$id}','{$tresc}',NOW(),'{$autor}','1');")) {
                note("Komentarz dodany!", "info");
            } else {
                note(M458, "blad");
            }
        } else {
            note(M459, "blad");
        }
    }
    unset($_POST['id']);
    unset($_POST['tresc']);
} // dodaje komentarz do bazy strony i forum - END


function wyswietl_komentarze($id)
{
    akcja_dodaj_komentarz();

    $sql = mysql_query("SELECT * FROM " . TABELA_KOMENTARZE_NEWS . " WHERE id_newsa = '{$id}' ORDER BY data DESC");
    $tytul = mysql_fetch_array(mysql_query("SELECT * FROM " . TABELA_NEWS . " WHERE id='{$id}'"));
    echo dodaj_komentarz_news($id, $tytul['tytul']) . "<br/>";
    while ($rekord = mysql_fetch_array($sql)) {
        $a++;
        $ret .= "<ul class=\"news_koment\">
			<li class=\"news_no\">#{$a}</li>
			<li class=\"news_logo_name\">
				<div>{$rekord['autor']}</div>
				<img src=\"grafiki/loga/" . sprawdz_klub(sprawdz_id_login($rekord['autor'])) . ".png\" alt=\"logo\"/>
			</li>
			<li class=\"news_text\">
				<div class=\"t\">Dodano: " . formatuj_date($rekord['data']) . "</div>
				<div class=\"t\" style=\"font-size:13px;\">{$rekord['tresc']}</div>
			</li>
		</ul>";
    }
    echo(empty($ret) ? "<fieldset style=\"font-family:verdana;color:#852222;font-size:13px;padding:10px\">Nikt nie skomentowal jeszcze tego newsa</fieldset>" : $ret);
}


function zamien_emoty($zamien)
{
    $zamien = str_replace(':angel:', '<img src="grafiki/smile/angel.gif" alt=""/>', $zamien);
    $zamien = str_replace(':angry:', '<img src="grafiki/smile/angry.gif" alt=""/>', $zamien);
    $zamien = str_replace(':cool:', '<img src="grafiki/smile/cool.gif" alt=""/>', $zamien);
    $zamien = str_replace(':cry:', '<img src="grafiki/smile/cry.gif" alt=""/>', $zamien);
    $zamien = str_replace(':embarassed:', '<img src="grafiki/smile/embarassed.gif" alt=""/>', $zamien);
    $zamien = str_replace(':grin:', '<img src="grafiki/smile/grin.gif" alt=""/>', $zamien);
    $zamien = str_replace(':rolleyes:', '<img src="grafiki/smile/rolleyes.gif" alt=""/>', $zamien);
    $zamien = str_replace(':sad:', '<img src="grafiki/smile/sad.gif" alt=""/>', $zamien);
    $zamien = str_replace(':sealedlips:', '<img src="grafiki/smile/sealedlips.gif" alt=""/>', $zamien);
    $zamien = str_replace(':shocked:', '<img src="grafiki/smile/shocked.gif" alt=""/>', $zamien);
    $zamien = str_replace(':smile:', '<img src="grafiki/smile/smile.gif" alt=""/>', $zamien);
    $zamien = str_replace(':tongue:', '<img src="grafiki/smile/tongue.gif" alt=""/>', $zamien);
    $zamien = str_replace(':undecided:', '<img src="grafiki/smile/undecided.gif" alt=""/>', $zamien);
    $zamien = str_replace(':wink:', '<img src="grafiki/smile/wink.gif" alt=""/>', $zamien);
    return $zamien;
}


// wyswietla formularz dodawania komentarza
function dodaj_komentarz_news($id, $tytul)
{
    return "
	<div class=\"blok_wersja_gry_title\" style=\"font-size:11px;\">" . M460 . ": <b>{$tytul}</b></div>
	<div class=\"blok_wersja_gry\" style=\"text-align:center;\">
		<form method=\"post\" action=\"\">
		<input type=\"hidden\" name=\"id\" value=\"{$id}\"/>
		<textarea cols=\"56\" rows=\"3\" name=\"tresc\"></textarea>
		<input type=\"submit\" value=\"" . M461 . "\"/>
		</form>
	</div>
	<div class=\"blok_wersja_gry_dol\"></div>
	";

} // wyswietla formularz dodawania komentarza  - END


//  wyswietla archiwum newsow
function wyswietl_archiwum($opcja, $podmenu)
{
    if ($opcja == 'news' || empty($opcja)) {
        akcja_dodaj_komentarz();
        echo '<div 
		style="width:610px;height:13px;text-align:right; margin-left:10px; 
		margin-right:5px;background-image:url(img/gradient_new_pokaz_archiwum.gif);">
		<a href="javascript:rozwin(\'show_list_news\');" style="color:#b21607;text-align:right;">
		<img src="img/punktor.png" alt=""/>Pokaz/Ukryj archiwum newsow</a>
	</div>';
        print "<span id=\"show_list_news\" style=\"display:none;\">
			<div id=\"pozostale_wiadomosci_gora\">
			<img src=\"img/pozostale_wiadomosci.jpg\" 
			class=\"naglowek_image\" alt=\"" . M88 . "\"/>
			</div>
			<div id=\"pozostale_wiadomosci_srodek\">";
        $sql = mysql_query("SELECT n.id,n.tytul,n.tresc,n.obrazek,n.data,n.autor,n.dzial,k.id,k.id_newsa,k.tresc,k.autor, count(k.id)
				FROM " . TABELA_NEWS . " n LEFT JOIN " . TABELA_KOMENTARZE_NEWS . " k ON k.id_newsa = n.id GROUP BY n.id ORDER BY n.data DESC LIMIT 4,1000;");
        while ($as2 = mysql_fetch_array($sql)) {
            print "<div class=\"tlo_archiwum\"><a class=\"archiwum\" title=\"Liga Pes, Puchary dnia, Mistrzostwa Swiata, pro evolution soccer, PES\" href=\"news-{$as2[0]}.htm\">[{$as2['data']}] {$as2['tytul']}</a></div>";
        }
        print "</div></span>";
    }
} //  wyswietla archiwum newsow  - END


//  wyswietla newsy 
function wyswietl_newsy($id)
{
    if (!empty($id)) {
        $sql_p = " WHERE n.id='{$id}' ";
    }
    akcja_dodaj_komentarz();
    $sql = mysql_query("SELECT n.id,n.tytul,n.tresc,n.obrazek,n.data,n.autor,n.dzial,count(k.id_newsa)
				FROM " . TABELA_NEWS . " n LEFT JOIN " . TABELA_KOMENTARZE_NEWS . " k ON k.id_newsa = n.id  $sql_p GROUP BY n.id ORDER BY n.data DESC LIMIT 0,4;");
    while ($as2 = mysql_fetch_array($sql)) {
        $a++;
        echo "<fieldset>
			<div class=\"news_new\">
				<ul>
					<li class=\"news_new_top\">{$as2['tytul']}</li>
					<li class=\"news_new_details\">" . M255 . " <b>" . formatuj_date($as2['data']) . "</b>, " . M387 . " <b>{$as2[5]}</b>, Komentarze: <b>{$as2[7]}</b></li>
					<li class=\"srodek\"><img src=\"grafiki/news/{$as2[3]}\" class=\"imgnews\" alt=\"{$as2['tytul']}\" title=\"{$as2['tytul']}\"/>" . (zamien_emoty($as2[2])) . "</li>
					<li id=\"n_komentarz_{$a}\" class=\"display_none\" style=\"clear:both;\">" . dodaj_komentarz_news($as2[0], $as2[1]) . "</li>
					<li class=\"news_new_footer\">
						<a href=\"javascript:rozwin('n_komentarz_{$a}');\" title=\"dodaj komentarz\">Komentuj</a>
						<a href=\"pokaz,komentarze-{$as2[0]}.htm\">" . M463 . "</a>
					</li>
				</ul>
			</div>	
		</fieldset>";
        $temp = TRUE;
    }
    if (empty($temp)) {
        note(M254, "blad");
    }
} //  wyswietla newsy - END


?>
