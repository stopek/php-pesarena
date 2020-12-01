<?php

include('include/admin/msg.php');
foreach ($admsg as $keyToDefine => $valueToDefine) {
    define('admsg_' . $keyToDefine, $valueToDefine);
}


$tab = array(
    'liga', 'puchar', 'turniej',
    'uzytkownicy', 'newsy', 'rankingi', 'wyzwania',
    'druzyny'
);
if (in_array($_GET['wybor'], $tab)) {
    $_SESSION['select'] = $_GET['wybor'];
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/pesarena/administrator.php');
}

if (!empty($_GET['save_game_league'])) {
    if (in_array($_GET['save_game_league'], $wersje_gry)) {
        setcookie("save_game_league", $_GET['save_game_league'], time() + 2592000);
    }
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/pesarena/administrator.php');
}
$cookie_game = $_COOKIE['save_game_league'];


define('leaguePlayersKey', sha1(md5('GH356643jhdgh2SDF96SDFSD8F6SDFH4UFSD')));
define('cupPlayersKey', sha1(md5('d6hv1883233ASDdfERSDSE44432GFHDSDDDD')));
define('tournamentPlayerKey', sha1(md5('J178432RTY9984SDFS4O3IFS0DF43OIOSOID')));


$r_id = (int)$_GET['r_id'];
define('R_ID', $r_id);
define('ADMIN_IS_', TRUE);
define('R_ID_P', $r_id);
define('R_ID_L', $r_id);
define('R_ID_T', $r_id);
$wybrana_gra = $_GET['wybrana_gra'];
define(WYBRANA_GRA, $wybrana_gra);
define(DEFINIOWANA_GRA, $wybrana_gra);
$szefuniu = czysta_zmienna_post($_SESSION['szefuniu']);
include("include/admin/funkcje/funkcje.php");
require_once("include/admin/functions/admin.cup.php");
$poziom_u_a = sprawdz_dostep_admina($szefuniu, poziom);
DEFINE('POZIOM_U_A', sprawdz_dostep_admina($szefuniu, '1'));
$liga_u_a = sprawdz_dostep_admina($szefuniu, liga);
define('PEBLOCK', true);
DEFINE('ADMIN_GAMES', sprawdz_dostep_admina($szefuniu, '2')); // ta zmienna wyswietla m.i.n opcje w zarzadzaniu pktami
define('LIGA_U_A', "$liga_u_a");
require_once('include/functions/function.admin-druzyny.php');
require_once('include/admin/functions/admin.game.php');
require_once('include/admin/functions/admin.statystyki.php');


//odpowiednie podmenu po kliknieciu w menu

$menu = array(
    'liga' =>
        '

	<ul>
		<li><a href="administrator.php?opcja=21&wybrana_gra=' . $cookie_game . '">Generowanie</a></li>
		<li><a href="administrator.php?opcja=38&wybrana_gra=' . $cookie_game . '">Zarzadzaj graczami</a></li>
		<li><a href="administrator.php?opcja=13&wybrana_gra=' . $cookie_game . '">Zamiana Gracza</a></li>
		<li><a href="administrator.php?opcja=12&wybrana_gra=' . $cookie_game . '">Zarzadzanie meczami</a></li>
		<li><a href="administrator.php?opcja=33&wybrana_gra=' . $cookie_game . '">Edycja spotkan</a></li>
		<li><a href="administrator.php?opcja=5&wybrana_gra=' . $cookie_game . '">Wlacz Kolejki</a></li>			
		<li><a href="administrator.php?opcja=26&wybrana_gra=' . $cookie_game . '">Ropoczynanie Ligi</a></li>
		<li><a href="administrator.php?opcja=35&wybrana_gra=' . $cookie_game . '">Zlicz WO graczy</a></li>
		<li><a href="administrator.php?opcja=42&wybrana_gra=' . $cookie_game . '">Pokaz Tabele</a></li>
	</ul>
	
', 'puchar' => '

	<ul>
		<li><a href="administrator.php?opcja=7&wybrana_gra=' . $cookie_game . '">Generowanie</a></li>				
		<li><a href="administrator.php?opcja=39&wybrana_gra=' . $cookie_game . '">Zarzadzaj graczami</a></li>
		<li><a href="administrator.php?opcja=15&wybrana_gra=' . $cookie_game . '">Zamiana Gracza</a></li>			
		<li><a href="administrator.php?opcja=16&wybrana_gra=' . $cookie_game . '">Zarzadzaj meczami</a></li>
		<li><a href="administrator.php?opcja=8&wybrana_gra=' . $cookie_game . '">Wynik eliminacji</a></li>
		<li><a href="administrator.php?opcja=19&wybrana_gra=' . $cookie_game . '">Edycja Spotkan</a></li>
		<li><a href="administrator.php?opcja=17&wybrana_gra=' . $cookie_game . '">Rozpoczynanie pucharu</a></li>
	</ul>
	
',
    'turniej' => '

	<ul>
		<li><a href="administrator.php?opcja=29&wybrana_gra=' . $cookie_game . '">Generowanie</a></li>
		<li><a href="administrator.php?opcja=41&wybrana_gra=' . $cookie_game . '">Zarzadzaj graczami</a></li>
		<li><a href="administrator.php?opcja=36&wybrana_gra=' . $cookie_game . '">WO, wpisywanie wyn. ,akcept. meczy</a></li>
		<li><a href="administrator.php?opcja=37&wybrana_gra=' . $cookie_game . '">Edycja Spotkan</a></li>
		<li><a href="administrator.php?opcja=32&wybrana_gra=' . $cookie_game . '">Ropoczynanie Turnieju</a></li>
	</ul>
	
',
    'uzytkownicy' => '

	<ul>
		<li><a href="administrator.php?opcja=9&pod=all&wybrana_gra=' . $cookie_game . '">Wszyscy</a></li>
		<li><a href="administrator.php?opcja=9&pod=act&wybrana_gra=' . $cookie_game . '">Aktywowani</a></li>
		<li><a href="administrator.php?opcja=9&pod=nact&wybrana_gra=' . $cookie_game . '">Nieaktywowani</a></li>
		<li><a href="administrator.php?opcja=9&pod=ban&wybrana_gra=' . $cookie_game . '">Zbanowani</a></li>

		<li>[<a href="administrator.php?opcja=9&pod=a&wybrana_gra=' . $cookie_game . '">A</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=b&wybrana_gra=' . $cookie_game . '">B</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=c&wybrana_gra=' . $cookie_game . '">C</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=d&wybrana_gra=' . $cookie_game . '">D</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=e&wybrana_gra=' . $cookie_game . '">E</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=1&wybrana_gra=' . $cookie_game . '">1</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=2&wybrana_gra=' . $cookie_game . '">2</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=3&wybrana_gra=' . $cookie_game . '">3</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=4&wybrana_gra=' . $cookie_game . '">4</a>]</li>
		<li>[<a href="administrator.php?opcja=9&pod=5&wybrana_gra=' . $cookie_game . '">5</a>]</li>
		<li><a href="administrator.php?opcja=9&pod=szuk&wybrana_gra=' . $cookie_game . '">Szukaj/Aktywuj</a></li>
	</ul>
	
',
    'newsy' => '

	<ul>
		<li><a href="administrator.php?opcja=20&podmenu=show">Pokaz Newsy</a></li>
		<li><a href="administrator.php?opcja=20&podmenu=add">Dodaj Newsa</a></li>
		<li><a href="administrator.php?opcja=20&podmenu=image">Zazadzanie Ikonami</a></li>
	</ul>

',
    'druzyny' => '

	<ul>
		<li><a href="administrator.php?opcja=28&podopcja=1">Zarzadzaj Druzynami</a></li>
		<li><a href="administrator.php?opcja=28&podopcja=2">Zarzadzaj Kategoriami</a></li>
	</ul>

',
    'rankingi' => '

	<ul>
		<li><a href="administrator.php?opcja=34&podopcja=1&wybrana_gra=' . $cookie_game . '">Ranking dnia</a></li>
		<li><a href="administrator.php?opcja=34&podopcja=2&wybrana_gra=' . $cookie_game . '">Ranking tygodnia</a></li>
		<li><a href="administrator.php?opcja=34&podopcja=3&wybrana_gra=' . $cookie_game . '">Ranking miesiaca</a></li>
		<li><a href="administrator.php?opcja=34&podopcja=5&wybrana_gra=' . $cookie_game . '">PokerRanking</a></li>
	</ul>

',
    'wyzwania' => '

	<ul>
		<li><a href="administrator.php?opcja=22&poka=sz&wybrana_gra=' . $cookie_game . '">Szukaj</a></li>
		<li><a href="administrator.php?opcja=22&wybrana_gra=' . $cookie_game . '">Pokaz wszystkie</a></li>
		<li><a href="administrator.php?opcja=22&poka=lp&wybrana_gra=' . $cookie_game . '">Wg liczby porzadkowej</a></li>
	</ul>
'
);


//opisy do menu po najechaniu
$description = array(
    'liga' => '
	Liga - 
	w tym menu mozesz generowac spotkania ligowe, zarzadzac graczami (dodawac nowych graczy do ligi
	wykluczac ich z rozgrywek, podgladac na jakim etapie znajduja sie gracze wysylac danym graczom
	informacje botem gg lub na email), zamieniac graczy w lidze, zarzadzac meczami(wystawiac wo pojedynczo,
	wpisywac wynik spotkannia, akceptowac spotkania, a takze wystawiac wo grupowo), mozesz takze edytowac 
	zakonczone juz spotkania, wlaczac (w domysle: generowac) nowe kolejki ligowe, rozpoczynac nowe rozgrywki
	ligowe o podanej nazwie i opiekunie) a takze dodatkowe funkcje miedzy innymi wyswietlanie tabeli ligowych graczy 
	oraz zliczanie (prawdopodobne) wystawionych WO
',
    'puchar' => '
	Puchar Dnia - 
	w tym menu mozesz miedzy innymi generowac spotkania pucharowe(poczawszy od fazy glownej lub eliminacji),
	zarzadzac graczami (dodawac nowych graczy do ligi
	wykluczac ich z rozgrywek, podgladac na jakim etapie znajduja sie gracze wysylac danym graczom
	informacje botem gg lub na email),zamieniac graczy w pucharze, zarzadzac meczami(edytowac usuwac), 
	zatwierdzac wynik eliminacji (o ile takowa istnieje) oraz  rozpoczynac nowe rozgrywki
	pucharowe o podanej nazwie i opiekunie)
', 'turniej' => '
	Turniej -  
	w tym menu mozesz miedzy innymi generowac spotkania turniejowe ,
	zarzadzac graczami (dodawac nowych graczy do ligi
	wykluczac ich z rozgrywek, podgladac na jakim etapie znajduja sie gracze wysylac danym graczom
	informacje botem gg lub na email), edytowac spotkania turniejowe(edytowawac, wystawiac wo, wpisywac wynik, akceptowac wynik)
	a takze jak i w pucharze i lidze mozesz uruchamiac zapisy do nowych rozgrywek turniejowych
', 'uzytkownicy' => '
	Uzytkownicy - 
	pod ta opcja znajduje sie lista wszystkich uzytkownikow systemu, mozesz miedzy innymi edytowac, usuwac , wystawiac 
	ostrzezenia, banowac na okreslony czas, aktywowac konta, edytowac dane graczy, przyznawac zgody na host, dawac miano 
	VIPa, generowac nowe haslo(losowo), a takze wysylac wiadomosc botem lub na adres email. Tabela z graczami zawiera miedzy innymi
	taki informacje jak nick gracza, jego gg, mail, ip a takze informacje o uzywanej przez niego przegladarki. Opcja posiada takze
	wygodna wyszukiwarke dzieki ktorej latwiej bedziesz mogl znalezc graczy. Mozesz takze wyswietlic okeslone grupy userow np. tylko 
	aktywowanych, tylko banowanych itp.
', 'newsy' => '
	Newsy - 
	opcja ta pozwala Ci dodawac nowe newsy, usuwac je, edytowac , a takze zarzadzac ikonami (dodawac nowe 
	usuwac, zmieniac nazwe wyswietlac informacje o nich)
', 'rankingi' => '
	Rankingi - 
	tutaj mozesz zarzadzac graczami w poszczegolnych rankingach takich jak: rankging dnia , tygodnia oraz miesiaca oraz PokerRank.
	Mozesz wykluczac graczy, zmieniac ich aktualny stan pkt)
', 'wyzwania' => '
	Mecze towarzystkie - 
	w tym menu bedziesz mogl zobaczyc wszystkie wyzwania znajdujace sie w bazie. Mozesz usuwac oraz edytowac wyzwania. Opcja
	szukaj pozwoli Ci na latwiejsze znajezienie szukanego przez Ciebie spotkania. Mozesz wyswietlic spotkania wg daty albo
	wg liczby porzadkowej.
', 'druzyny' => '
	Druzyny - 
	tutaj mozesz zarzadzac wszystkimi druzynami. Mozesz dodawac nowe kategorie druzyn , do nich mozesz dodawac nowe podkategorie
	nastepnie mozesz przypisywac wybrane przez Ciebie druzyny do odpowienich podkategori. Mozesz edytowac i usuwac kategorie i podkategorie
	Mozesz takze dodawac nowe druzyny oraz zmieniac nazwy druzyn i otrzymanych za nie pkt.
',
    'pkt' => '
	Punkty dodatkowe - 
	Mneu to pozwala dodawac punkty dodatkowe dla graczy. Mozesz takze usuwac dane pkt oraz je edytowac.
',
    'sonda' => '
	Sonda - 
	Standardowa funkcja , mozesz dzieki niej dodawac nowe ankiety. Znajduje sie tam lista utworzonych przez Ciebie sond, a takze zobaczyc
	ilosc glosow oddanych na nia.
',
    'ip' => '
	Uprzywilejowani IP - 
	Opcja pozwala na dodawanie grup graczy, ktorzy moga sie logowac z jednego ip.
',
    'gry' => '
	Wersje Gier - 
	Opcja pozwala na dodawanie usuwanie i edytowanie wersji gry na stronie.
',
    'admini' => '
	Administracja - 
	Opcja pozwala na dodawanie nowych opiekunow ligowych(administratorow). Mozesz okreslic prawa tworzonemu adminowi. 
	Mozesz takze wyslac do obecnych juz administratorow wiadomosc na gg lub emial. Mozesz takze usuwac i edytowac dane opiekunow.
'
);
if (conf_showMenuDescription != 1) $description = array();
// menu w ktorym nie ma podmenu (od razu linki)
$ta = array(
    'pkt' => '40',
    'sonda' => '24',
    'ip' => '27',
    'gry' => '25',
    'admini' => '18'
);


//zeby nie bylo bledow wymagane jest nazwa (grupa_parametr) i nie wiecej niz (grupa_nazwa_parametru_jakas)
$wybory = array(
    'liga_generowanie' => 'Menager Ligowy(generowanie itp)',
    'liga_gracze' => 'Zarzadzanie graczami ligowymi (dodawanie wykluczanie)',
    'liga_zamiana' => 'Zamiana graczy w lidze',
    'liga_wo' => 'Zarzadzanie meczami ligowymi(wo,akceptacja meczy itp)',
    'liga_edycja' => 'Edytowanie meczy ligowych',
    'liga_wlacz' => 'Wlaczanie kolejek ligowych',
    'liga_start' => 'Uruchamianie, edycja, usuwanie rozgrywek ligowych (R)',
    'liga_inne_wo' => 'Wyswietlanie statusu graczy wo,rozegranych spotkan (dodatkowe)',
    'liga_inne_tabele' => 'Wyswietlenie tabel ligowych (dodatkowe)',

    'turniej_generowanie' => 'Manager turnieju(generowanie itp)',
    'turniej_gracze' => 'Zarzadzanie graczami turniejowymi (dodawanie wykluczanie)',
    'turniej_wo' => 'Zarzadzanie meczami turniejowymi(wo,akceptacja meczy itp)',
    'turniej_edycja' => 'Edytowanie meczy turniejowych',
    'turniej_start' => 'Uruchamianie, edycja, usuwanie rozgrywek turniejowych (R)',

    'puchar_generowanie' => 'Menager pucharowy(generowanie itp)',
    'puchar_gracze' => 'Zarzadzanie graczami pucharowymi (dodawanie wykluczanie)',
    'puchar_zamiana' => 'Zamiana graczy w pucharze',
    'puchar_wo' => 'Zarzadzanie meczami pucharowymi(wo,akceptacja meczy itp)',
    'puchar_edycja' => 'Edytowanie meczy pucharowych',
    'puchar_eliminacje' => 'Konczenie eliminacji',
    'puchar_start' => 'Uruchamianie, edycja, usuwanie rozgrywek pucharowych (R)',

    'wyzwania_edytuj' => 'Pozwala na edycje wyzwan',
    'wyzwania_usun' => 'Pozwala adminowi usuwac wyzwania (R)',

    'sonda_zarzadzanie' => 'Zarzadzanie sonda',

    'gry_podglad' => 'Dodawanie usuwanie i edytowanie wersji gier dostepnych na stronie',
    'gry_zarzadzanie' => 'Dodawanie usuwanie i edytowanie wersji gier dostepnych na stronie (R)',

    'opiekun_dodaj' => 'Zezwala na dodawanie opiekunow/administratow (R)(R)',
    'opiekun_edytuj' => 'Zezwala na edycje opiekunow/administratow (R)(R)',
    'opiekun_usun' => 'Zezwala na usuwanie opiekunow/administratow (R)(R)',
    'opiekun_patrz' => 'Moze patrzec na liste opiekunow (a w tym widziec ich adresy e-mail, linki do edycji itp',


    'pkt_dodaj' => 'Zezwala na dodawnie  punktow dodatkowych',
    'pkt_usun' => 'Zezwala na usuwanie graczom punktow dodatkowych',
    'pkt_edytuj' => 'Zezwala na edytowanie graczom punktow dodatkowych',

    'ip_zarzadzanie' => 'zarzadzanie graczami, ktorzy loguja sie z jednego ip',

    'user_usun' => 'Zezwala na usuwanie uzytkownikow (R)',
    'user_aktywuj' => 'Zezwala na aktywacje userow(oraz odbanowanie)',
    'user_banuj' => 'Zezwala na banowanie graczy',
    'user_host' => 'Zezwala na wystawianie zgody na host ',
    'user_edytuj' => 'Zezwala na edycje userow',
    'user_haslo' => 'Zezwala wygenerowanie hasla dla usera',
    'user_ostrzez' => 'Mozliwosc zarzadzania ostrzezeniami userow',
    'user_vip' => 'Mozliwosc przyznawania lub odbierania statusow VIP',

    'druzyny_dodaj' => 'Zezwolenie na dodawanie nowych druzyn do bazy',
    'druzyny_usun' => 'Zezwolenie na dodawanie nowych druzyn do bazy (R)',
    'druzyny_edytuj' => 'Zezwolenie na edycje druzyn',
    'druzyny_przypisz' => 'Daje mozliwosc przypisywania druzyn do kategori',

    'druzyny_kategorie_dodaj' => 'Moze dodawac kategorie glowne',
    'druzyny_podkategorie_dodaj' => 'Moze dodawac podkategorie',
    'druzyny_podkategorie_usun' => 'Moze usuwac podkategorie (R)',
    'druzyny_kategorie_usun' => 'Moze usuwac kategorie (R)',
    'druzyny_podkategorie_edytuj' => 'Moze edytowac podkategorie',
    'druzyny_kategorie_edytuj' => 'Moze edytowac kategorie',

    'ranking_zmien_punkty' => 'Mozliwosc zmiany punktow w rankinach',
    'ranking_wyklucz_gracza' => 'Mozliwosc wykluczenia gracza z rankingu (R)',

    'news_dodaj' => 'Upowaznienie do dodawania newsow',
    'news_edytuj' => 'Upowaznienie do edytowania',
    'news_usun' => 'Upowaznienie do usuwania (R)',
    'news_image' => 'Mozliwosc zarzadzania ikonami do newso (upload, zmiana nazwy, usuwanie)',

    'bot_uzytkownicy' => 'Ma formularz do wysylania w opcji z uzytkownikami',
    'bot_admini' => 'Ma formularz do wysylania w opcji z lista administratorow',
    'bot_liga' => 'Ma formualarz do wysylania wiadomosci botem w opcji z graczami ligowymi',
    'bot_puchar' => 'Ma formualarz do wysylania wiadomosci botem w opcji z graczami pucharowymi',
    'bot_turniej' => 'Ma formualarz do wysylania wiadomosci botem w opcji z graczami turniejowymi',

    'ustawienia_pokaz' => 'Wyswietla liste dostepnych ustawien ',
    'ustawienia_edytuj' => 'Ma mozliwosc edytowania ustawien'


);


?>