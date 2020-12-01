<?
//index.php?opcja=$1&podmenu=$2&podopcja=$3&menu=$4
function naglowek_title()
{
    $opcja = $_GET['opcja'];
    $podmenu = ($_GET['podmenu'] ? "-" . $_GET['podmenu'] : "");
    $podopcja = ($_GET['podopcja'] ? "-" . $_GET['podopcja'] : "");
    $subject = mysql_fetch_array(mysql_query("SELECT tytul FROM " . TABELA_NEWS . " WHERE id = '" . (int)$_GET['podmenu'] . "'"));

    $tab_przelacz = array('puchar' => TABELA_PUCHAR_DNIA_LISTA, 'liga' => TABELA_LIGA_LISTA, 'turniej' => TABELA_TURNIEJ_LISTA);
    $przelacz = @mysql_fetch_array(mysql_query("SELECT nazwa FROM " . $tab_przelacz[czysta_zmienna_get($_GET['podmenu'])] . " WHERE id = '" . (int)$_GET['podopcja'] . "'"));

    $tablica = array(
        'regulamin,a' => 'Regulamin PES4',
        'regulamin,b' => 'Regulamin PES2010 PS3',
        'rejestracja' => 'Zarejestruj si� na www.pesarena.pl',
        'polecamy' => 'Rekomendowane pozycje',
        'artykuly' => 'Najnowsze Tutoriale ',
        'reklama' => 'Obiekty reklamowane',
        'redakcja' => 'Za�oga serwisu',
        'ranking' => 'Ranking graczy',
        'profil,edytuj' => 'Edytuj sw�j profil',
        'wszystkie,wersje,gier' => 'Wszystkie wersje gier',
        'dodatkowe,punkty' => 'Dodatkowe punkty graczy',
        'logowanie' => 'Zaloguj sie na www.pesarena.pl',
        'gra-' . $_GET['podmenu'] => 'Przelacz na inn� wersje gry',

        'profil,edytuj-popraw,bledy' => 'Wykonaj poprawe bled�w!',
        'przelacz-turniej-' . $_GET['podopcja'] => 'Przelacz na: ' . $przelacz['nazwa'],
        'przelacz-puchar-' . $_GET['podopcja'] => 'Przelacz na: ' . $przelacz['nazwa'],
        'przelacz-liga-' . $_GET['podopcja'] => 'Przelacz na: ' . $przelacz['nazwa'],

        'profil,pokaz-' . (int)$_GET['podmenu'] => 'Profil gracza ' . sprawdz_login_id((int)$_GET['podmenu']),
        'druzyny-profil-' . (int)$_GET['podopcja'] => 'Profil druzyny ' . sprawdz_druzyna_id((int)$_GET['podopcja']),
        'druzyny-rozegrane,mecze-' . (int)$_GET['podopcja'] => 'Mecze rozegrane ' . sprawdz_druzyna_id((int)$_GET['podopcja']),
        'wyzwania-graj-' . (int)$_GET['podopcja'] => 'Zagraj z  ' . sprawdz_login_id((int)$_GET['podopcja']),
        'news-' . (int)$_GET['podmenu'] => $subject['tytul'],
        'liga-terminarz-' . $_GET['podopcja'] => 'Terminarz ligowy dla ligi: ' . $_GET['podopcja'],
        'liga-tabela-' . $_GET['podopcja'] => 'Tabela ligowa dla ligi: ' . $_GET['podopcja'],
        'turniej-terminarz-' . $_GET['podopcja'] => 'Terminarz turniejowy dla grupy: ' . $_GET['podopcja'],
        'turniej-tabela-' . $_GET['podopcja'] => 'Tabela  turniejowa dla grupy: ' . $_GET['podopcja'],

        'liga-uczestnicy' => 'Uczestnicy rozgrywek ligowych',
        'liga-tabela' => 'Tabele rozgrywek ligowych',
        'liga-terminarz' => 'Terminarz rozgrywek ligowych',
        'liga-historia' => 'Historia rozgrywek ligowych',
        'liga-wyniki' => 'Wyniki rozgrywek ligowych',


        'puchar-uczestnicy' => 'Uczestnicy Pucharu PesArena',
        'puchar-eliminacje' => 'Eliminacje Pucharu PesArena',
        'puchar-glowny' => 'Puchar g��wny PesArena',
        'puchar-zwyciezcy' => 'Beneficjenci Pucharu PesArena',
        'puchar-wyniki' => 'Wyniki Pucharu PesArena',
        'puchar-historia' => 'Historia rozgrywek pucharowych',

        'wyzwania-historia-' . $_GET['podopcja'] => 'Historia wyzwa� strona: ' . $_GET['podopcja'],
        'turniej-historia-' . $_GET['podopcja'] => 'Historia Mistrzostw �wiata strona: ' . $_GET['podopcja'],
        'puchar-historia-' . $_GET['podopcja'] => 'Historia pucharu dnia strona: ' . $_GET['podopcja'],
        'liga-historia-' . $_GET['podopcja'] => 'Historia rozgrywek ligowych strona: ' . $_GET['podopcja'],

        'wyszukiwarka' => 'Znajd� swojego rywala',
        'uzytkownicy' => 'Potencjalni rywale',
        'banowani' => 'Czarna lista. Na nich szczeg�lnie uwa�ajmy',
        'wyzwania-historia' => 'Historia Wyzwa�',
        'wyzwania-wyniki' => 'Moje wyniki Wyzwa�',
        'druzyny' => 'Dost�pne dru�yny',
        'index' => 'Puchary dnia , 	Liga Mistrzow ,	Mistrzostwa Europy  , Mistrzostwa �wiata , Liga Angielska , Liga Europejska , Turnieje , Ranking PES , Mecze towarzyskie, Wyzwania, Pro Evolution Soccer!',


        'deklaracje-puchar' => 'Zapisy do Pucharu PesArena',
        'deklaracje-liga' => 'Zapisy do sezonu ligowego',
        'deklaracje-turniej' => 'Zapisy do Mistrzostw �wiata',


        'turniej-uczestnicy' => 'Uczestnicy Mistrzostw �wiata',
        'turniej-tabela' => 'Tabela Mistrzostw �wiata',
        'turniej-terminarz' => 'Terminarz Mistrzostw �wiata',
        'turniej-historia' => 'Historia Mistrzostw �wiata',
        'turniej-wyniki' => 'Wyniki Mistrzostw �wiata',
        'default' => 'Witaj w :: www.pesarena.pl :: 
		Puchary dnia , 	Liga Mistrzow ,	Mistrzostwa Europy  , Mistrzostwa �wiata , Liga Angielska , Liga Europejska , Turnieje , Ranking PES , Mecze towarzyskie, Wyzwania, Pro Evolution Soccer! '
    );


    $a = $tablica[$opcja . "" . $podmenu . "" . $podopcja] . " : " . $_SESSION['DEFINIOWANA_GRA_OPIS'][0];
    if (empty($opcja) && empty($podmenu) && empty ($podopcja)) {
        return (empty($a) ? "nic" : $tablica['default']);
    } else {
        return (empty($a) ? "nic" : $a);
    }
}

?>
