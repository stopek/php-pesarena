<?php

/**
*
* @package - mChat
* @version $Id: info_acp_mchat.php 2010-11-08
* @copyright (c) 2010 RMcGirr83 ( http://www.rmcgirr83.org/ )
* @copyright (c) 2009 phpbb3bbcodes.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters for use
// ’ » “ ” …


$lang = array_merge($lang, array(

	// UMIL stuff
	'ACP_MCHAT_CONFIG'				=> 'Konfiguracja',
	'ACP_CAT_MCHAT'					=> 'mChat',
	'ACP_MCHAT_TITLE'				=> 'Mini-Chat',
	'ACP_MCHAT_TITLE_EXPLAIN'		=> 'Mini chat (aka “shout box”) dla twojego forum',
	'MCHAT_TABLE_DELETED'			=> 'Tabela mChat została pomyślnie usunięta',
	'MCHAT_TABLE_CREATED'			=> 'Tabela mChat została pomyślnie utworzona',
    'MCHAT_TABLE_UPDATED'			=> 'Tabela mChat została pomyślnie zaktualizowana',
	'MCHAT_NOTHING_TO_UPDATE'		=> 'Wszystko jest aktualne....kontynuuję',
	'UCP_CAT_MCHAT'					=> 'Preferencje mChat',
	'UCP_MCHAT_CONFIG'				=> 'Preferencje użytkownika mChat',
	
	// ACP entries
	'ACP_MCHAT_RULES'				=> 'Zasady czatowania',
	'ACP_MCHAT_RULES_EXPLAIN'		=> 'Wpisz zasady czatowania.  Każda zasada w nowej linii.<br />Maksymalnie 255 znaków.<br /><strong>Zasady te mogą być przetłumaczone.</strong> (musisz edytować plik mchat_lang.php i przeczytać instrukcję).',
	'LOG_MCHAT_CONFIG_UPDATE'		=> '<strong>Aktualizowano konfigurację mChat </strong>',
	'MCHAT_CONFIG_SAVED'			=> 'Konfiguracja Mini Chat została zaktualizowana',
	'MCHAT_TITLE'					=> 'Mini-Chat',
	'MCHAT_VERSION'					=> 'Wersja:',
	'MCHAT_ENABLE'					=> 'Włącz mChat MOD',
	'MCHAT_ENABLE_EXPLAIN'			=> 'Włącz lub wyłącz moda.',
	'MCHAT_AVATARS'					=> 'Pokazuj avatary',
	'MCHAT_AVATARS_EXPLAIN'			=> 'Jeśli ustawione na tak, będą pokazywane zmniejszone avatary użytkowników w oknie czatu',	
	'MCHAT_ON_INDEX'				=> 'mChat na stronie głównej',
	'MCHAT_ON_INDEX_EXPLAIN'		=> 'Pozwala na wyświetlanie mChat na stronie głównej forum.',
    'MCHAT_INDEX_HEIGHT'			=> 'Wysokość na stronie głównej',
	'MCHAT_INDEX_HEIGHT_EXPLAIN'	=> 'Wysokość czata na stronie głównej forum w pikselach.<br /><em>Możesz ustawić wartości od 50 do 1000</em>.',
	'MCHAT_LOCATION'				=> 'Lokalizacja mChat na forum',
	'MCHAT_LOCATION_EXPLAIN'		=> 'Wybierz lokalizację mChat dla strony głównej forum.',
	'MCHAT_TOP_OF_FORUM'			=> 'Na górze Forum',
	'MCHAT_BOTTOM_OF_FORUM'			=> 'Na dole Forum',	
	'MCHAT_REFRESH'					=> 'Odśwież',
	'MCHAT_REFRESH_EXPLAIN'			=> 'Ilość sekund po których chat automatycznie odświeża się. <strong>Nie ustawiaj poniżej 10 sekund</strong>.',
	'MCHAT_PRUNE'					=> 'Włącz czyszczenie',
	'MCHAT_PRUNE_EXPLAIN'			=> 'Ustaw na Tak aby włączyć funkcję czyszczenia.<br /><em>Dostępna tylko jeśli użytkownik przegląda stronę mChat lub archiwum</em>.',
	'MCHAT_PRUNE_NUM'				=> 'Ilość wiadmości',
	'MCHAT_PRUNE_NUM_EXPLAIN'		=> 'Ilość wiadomości która pozostanie w mChat.',	
	'MCHAT_MESSAGE_LIMIT'			=> 'Limit wiadomości',
	'MCHAT_MESSAGE_LIMIT_EXPLAIN'	=> 'Maksymalna ilość wiadomości wyświetlanych na stronie głównej forum.<br /><em>Zalecane 10 do 20</em>.',
	'MCHAT_ARCHIVE_LIMIT'			=> 'Limit archiwum',
	'MCHAT_ARCHIVE_LIMIT_EXPLAIN'	=> 'Maksymalna ilość wiadomości wyświetlanych na stronie archiwum.<br /> <em>Zalecane 25 do 50</em>.',
	'MCHAT_FLOOD_TIME'				=> 'Czas opóźnienia',
	'MCHAT_FLOOD_TIME_EXPLAIN'		=> 'Ilość sekund które użytkownik musi odczekać aby wysłać następną wiadomość.<br /><em>Zalecane 5 do 30, ustaw 0 aby wyłączyć</em>.',
	'MCHAT_MAX_MESSAGE_LENGTH'			=> 'Maksymalna długość wiadomości',
	'MCHAT_MAX_MESSAGE_LENGTH_EXPLAIN'	=> 'Maksymalna ilość znaków w jednej wiadomości.<br /><em>Zalecane 100 do 500, ustaw 0 aby wyłączyć</em>.',
	'MCHAT_CUSTOM_PAGE'				=> 'Strona mChat',
	'MCHAT_CUSTOM_PAGE_EXPLAIN'		=> 'Zezwalaj na używanie strony mChat',
	'MCHAT_CUSTOM_HEIGHT'			=> 'Wysokość czata na stronie mchat.php',
	'MCHAT_CUSTOM_HEIGHT_EXPLAIN'	=> 'Wysokość czata na oddzielnej stronie w pikselach.<br /><em>Możesz ustawić wartości od 50 do 1000</em>.',
	'MCHAT_DATE_FORMAT'				=> 'Format daty',
	'MCHAT_DATE_FORMAT_EXPLAIN'		=> 'Format daty jest taki sam, jak w funkcji PHP <a href="http://www.php.net/date">date()</a>.',
	'MCHAT_CUSTOM_DATEFORMAT'		=> 'Domyślny…',
	'MCHAT_WHOIS'					=> 'Kto czatuje',
	'MCHAT_WHOIS_EXPLAIN'			=> 'Pozwala na pokazanie użytkowników przeglądających stronę mChat',
	'MCHAT_WHOIS_REFRESH'			=> 'Odświeżanie Kto to',
	'MCHAT_WHOIS_REFRESH_EXPLAIN'	=> 'Ilość sekund po których status Kto to? zostanie odświeżony.<br /><strong>Nie ustawiaj poniżej 30 sekund</strong>.',
	'MCHAT_BBCODES_DISALLOWED'		=> 'Zabronione BBCodey',
	'MCHAT_BBCODES_DISALLOWED_EXPLAIN'	=> 'Tutaj możesz wpisać BBCodey które <strong>nie</strong> będą mogły być używane w wiadomościach.<br />Oddziel BBCodey pionową kreską, na przykład: b|u|code',
	'MCHAT_STATIC_MESSAGE'			=> 'Stała wiadomość',
	'MCHAT_STATIC_MESSAGE_EXPLAIN'	=> 'Tutaj możesz określić tekst wiadomości pokazywanej użytkownikom czatu.<br />Pozosta niewypełnione aby wyłączyć.  Limit znaków to 255.<br /><strong>Ta wiadomość może zostać przetłumaczona.</strong>  (musisz edytować plik mchat_lang.php i przeczytać instrukcję).',
	'MCHAT_USER_TIMEOUT'			=> 'Limit czasu użytkownika',
	'MCHAT_USER_TIMEOUT_EXPLAIN'	=> 'Ustaw ilość czasu, w sekundach, po których sesja użytkownika w czacie zakończy się. Ustaw 0 aby wyłączyć.<br /><em>Jesteś ograniczony do %sustawień czasu sesji forum%s która aktualnie ustawiona jest na %s sekund</em>',
	'MCHAT_OVERRIDE_SMILIE_LIMIT'	=> 'Zmień limit uśmieszków',
	'MCHAT_OVERRIDE_SMILIE_LIMIT_EXPLAIN'	=> 'Ustaw na tak aby zmienić limit uśmieszków w wiadomościach czatu',
	'MCHAT_OVERRIDE_MIN_POST_CHARS'	=> 'Zmień minimalny limit znaków',
	'MCHAT_OVERRIDE_MIN_POST_CHARS_EXPLAIN'	=> 'Ustaw na Tak aby zmienić ustawienia minimalnej ilości znaków forum dla wiadomości czatu',
	'MCHAT_NEW_POSTS'				=> 'Pokazuj nowe posty',
	'MCHAT_NEW_POSTS_EXPLAIN'		=> 'Ustaw na tak aby wyświetlać powiadomienia o nowych postach z forum w oknie czatu<br /><strong>Musisz posiadać zainstalowany dodatek aby powiadomienia działały</strong> (Dodatek znajdziesz w katalogu contrib paczki instalacyjnej tej modyfikacji).',
	'MCHAT_MAIN'					=> 'Główna konfiguracja',
	'MCHAT_STATS'					=> 'Kto czatuje',
	'MCHAT_STATS_INDEX'				=> 'Statystyki na głównej stronie',
	'MCHAT_STATS_INDEX_EXPLAIN'		=> 'Pokazuje kto czatuje w sekcji statystyk forum',
	'MCHAT_MESSAGES'				=> 'Ustawienia wiadomości',
	'MCHAT_PAUSE_ON_INPUT'			=> 'Przerwa podczas pisania',
	'MCHAT_PAUSE_ON_INPUT_EXPLAIN'	=> 'Jeżeli ustawione na tak, czat nie będzie się odświeżał do czasu wpisania wiadomości przez użytkownika',
	
	// error reporting
	'WARNING'					=> 'Ostrzeżenie',
	'TOO_LONG_DATE'		=> 'Wpisany format daty jest zbyt długi.',
	'TOO_SHORT_DATE'		=> 'Wpisany format daty jest zbyt krótki.',
	'TOO_SMALL_REFRESH'	=> 'Wartość odświeżania jest za mała.',
	'TOO_LARGE_REFRESH'	=> 'Wartość odświeżania jest za duża.',
	'TOO_SMALL_MESSAGE_LIMIT'	=> 'Wartość limitu wiadomości jest za mała.',
	'TOO_LARGE_MESSAGE_LIMIT'	=> 'Wartość limitu wiadomości jest za duża.',
	'TOO_SMALL_ARCHIVE_LIMIT'	=> 'Wartość limitu archiwum jest za mała.',
	'TOO_LARGE_ARCHIVE_LIMIT'	=> 'Wartość limitu archiwum jest za duża.',
	'TOO_SMALL_FLOOD_TIME'	=> 'Wartość czasu opóźnienia jest za mała.',
	'TOO_LARGE_FLOOD_TIME'	=> 'Wartość czasu opóźnienia jest za duża.',
	'TOO_SMALL_MAX_MESSAGE_LNGTH'	=> 'Wartość maksymalnej długości wiadomości jest za mała.',
	'TOO_LARGE_MAX_MESSAGE_LNGTH'	=> 'Wartość maksymalnej długości wiadomości jest za duża.',
	'TOO_SMALL_MAX_WORDS_LNGTH'		=> 'Wartość maksymalnej długości słów jest za mała.',
	'TOO_LARGE_MAX_WORDS_LNGTH'		=> 'Wartość maksymalnej długości słów jest za duża.',	
	'TOO_SMALL_MAX_WORDS_LNGTH'	=> 'Wartość maksymalnej długości słów jest za mała.',
	'TOO_LARGE_MAX_WORDS_LNGTH'	=> 'Wartość maksymalnej długości słów jest za duża.',
	'TOO_SMALL_WHOIS_REFRESH'	=> 'Wartość odświeżania kto czatuje jest za mała.',
	'TOO_LARGE_WHOIS_REFRESH'	=> 'Wartość odświeżania kto czatuje jest za duża.',	
	'TOO_SMALL_INDEX_HEIGHT'	=> 'Wartość wysokości jest za mała.',
	'TOO_LARGE_INDEX_HEIGHT'	=> 'Wartość wysokości jest za duża.',
	'TOO_SMALL_CUSTOM_HEIGHT'	=> 'Wartość wysokości na stronie mchat.php jest za mała.',
	'TOO_LARGE_CUSTOM_HEIGHT'	=> 'Wartość wysokości na stronie mchat.php jest za duża.',
	'TOO_SHORT_STATIC_MESSAGE'	=> 'Wartość wiadomości jest za mała.',
	'TOO_LONG_STATIC_MESSAGE'	=> 'Wartość wiadomości jest za duża.',	
	'TOO_SMALL_TIMEOUT'	=> 'Wartość oświeżania użytkownika jest za mała.',
	'TOO_LARGE_TIMEOUT'	=> 'Wartość oświeżania użytkownika jest za duża.',
	
));

?>