<?php
/**
*
* @package mChat Polish language (napus)
* @version $Id: mchat_lang.php
*  @copyright (c) RMcGirr83 ( http://www.rmcgirr83.org/ )
* @copyright (c) djs596 ( http://djs596.com/ ), (c) Stokerpiller ( http://www.phpbb3bbcodes.com/ )
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
**/

/**
* DO NOT CHANGE!
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
// Some characters you may want to copy&paste (Unicode characters):
// ’ » “ ” …
//


$lang = array_merge($lang, array(

  // MCHAT
  'MCHAT_ADD'              => 'Wyślij',
  'MCHAT_ANNOUNCEMENT'	   => 'Ogłoszenie',
  'MCHAT_ARCHIVE'          => 'Archiwum',
  'MCHAT_ARCHIVE_PAGE'	   => 'Archiwum Mini-Chat',
  'MCHAT_BBCODES'          => 'BBCodey',
  'MCHAT_CLEAN'            => '&curren;',
  'MCHAT_CLEANED'          => 'Wszystkie wiadomości zostały usunięte.',
  'MCHAT_CLEAR_INPUT'	   => 'Wyczyść',
  'MCHAT_COPYRIGHT'  => '&copy; <a href="http://www.rmcgirr83.org/">RMcGirr83.org</a>',
  'MCHAT_CUSTOM_BBCODES'   => 'Dodatkowe BBCodey',
  'MCHAT_DELALLMESS'       => 'Usunąć wszystkie wiadomości?',
  'MCHAT_DELCONFIRM'       => 'Potwierdź usunięcie?',
  'MCHAT_DELITE'           => 'Usuń',
  'MCHAT_EDIT'             => 'Edytuj',
  'MCHAT_EDITINFO'         => 'Edytuj wiadomość i naciśnij OK.',
  'MCHAT_ENABLE'           => 'Przepraszamy, mini-chat jest chwilowo niedostępny.',
  'MCHAT_ERROR'            => 'Błąd',
  'MCHAT_FLOOD'            => 'Nie możesz wysłać następnej wiadomości w tak krótkim czasie.',
  'MCHAT_HELP'             => 'Zasady czatowania',
  // uncomment and translate the following line for languages for the rules in the chat area
// <br /> signifies a new line, see above for Unicode characters to use
  'MCHAT_RULES'				=> 'Zabrania się: <br />Używania wulgaryzmów<br /> Reklamowania własnej strony<br /> Wysyłania kilku wiadomości na raz<br /> Pisania bezsesownych wiadomości<br /> Wysyłania wiadomości zawierających tylko uśmieszki',
  'MCHAT_HIDE_LIST'		   => 'Ukryj listę',	
  'MCHAT_HOUR'			   => 'godzina',
  'MCHAT_HOURS'			   => 'godzin',
  'MCHAT_IP'	           => 'IP:',
  'MCHAT_IP_WHOIS_FOR'	   => 'Kto to %s',
  
  'MCHAT_MINUTE'		   => 'minuta ',
  'MCHAT_MINUTES'		   => 'minut ',
  'MCHAT_MESS_LONG'		   => 'Twoja wiadomość jest zbyt długa.\nOgranicz ją do %s znaków',
  'MCHAT_NO_CUSTOM_PAGE'   => 'Strona mChat nie została aktywowana!',
  'MCHAT_NOACCESS'         => 'Brak dostępu!',
  'MCHAT_NOACCESS_ARCHIVE' => 'Nie posiadasz wystarczających uprawnień aby przeglądać archiwum',	
  'MCHAT_NOJAVASCRIPT'     => 'Twoja przeglądarka nie obsługuje JavaScript lub JavaScript jest wyłączone!',
  'MCHAT_NOMESSAGE'        => 'Brak wiadomości!',
  'MCHAT_NOMESSAGEINPUT'   => 'Brak wiadomości!',
  'MCHAT_NOSMILE'          => 'Nie znaleziono Uśmieszków!',
  'MCHAT_NOTINSTALLED_USER'=> 'mChat nie jest zainstalowany.  Powiadom administratora forum.',
  'MCHAT_NOT_INSTALLED'	   => 'Brak wpisów w bazie danych dla mChat.<br />Uruchom %sinstalatora%s aby zaktualizować bazę danych.',
  'MCHAT_OK'               => 'ОК',
  'MCHAT_PAUSE'			   => 'Zatrzymany',
  'MCHAT_LOAD'				=> 'Ładowanie',
  'MCHAT_PERMISSIONS'	   => 'Zmień uprawnienia użytkownika',
  'MCHAT_REFRESHING'	   => 'Odświeżanie...',
  'MCHAT_REFRESH_NO'	   => 'Auto-aktualizacja jest wyłączona',
  'MCHAT_REFRESH_YES'	   => 'Auto-aktualizacja co <strong>%d</strong> sekund',
  'MCHAT_RESPOND'		   => 'Odpowiedz użytkownikowi',
  'MCHAT_RESET_QUESTION'   => 'Usunąć wprowadzony tekst?',
  'MCHAT_SESSION_OUT'	   => 'Sesja Chata wygasła',	
  'MCHAT_SHOW_LIST'		   => 'Pokaż listę',
  'MCHAT_SECOND'		   => 'sekunda ',
  'MCHAT_SECONDS'		   => 'sekund ',
  'MCHAT_SESSION_ENDS'	   => 'Sesja Chata wygaśnie za',
  'MCHAT_SMILES'           => 'Uśmieszki',
  
  'MCHAT_TOTALMESSAGES'    => 'Wszystkich wiadomości: <b>%s</b>',
  'MCHAT_USESOUND'         => 'Włącz/Wyłącz dzwięk?',
 
// uncomment and translate the following line for languages for the static message in the chat are
//	'STATIC_MESSAGE'			=> 'Tutaj możesz wpisać co chcesz', 
  // whois chatting stuff
	
	'MCHAT_ONLINE_USERS_TOTAL'			=> '<strong>%d</strong> użytkowników na czacie :: ',
	'MCHAT_ONLINE_USER_TOTAL'			=> '<strong>%d</strong> użytkownik na czacie :: ',	
	'MCHAT_NO_CHATTERS'					=> 'Nikt nie czatuje',
	'MCHAT_ONLINE_EXPLAIN'				=> '( oparte na użytkownikach z ostatnich %s)',
  
    'WHO_IS_CHATTING'			=> 'Kto jest na czacie',
    'WHO_IS_REFRESH_EXPLAIN'	=> 'Odświeża co <b>%d</b> sekund',
	'MCHAT_NEW_TOPIC'			=> '<strong>Nowy temat</strong>',		
  
  // UCP
	'UCP_PROFILE_MCHAT'	=> 'Ustawienia mChat',
	
	'DISPLAY_MCHAT' 	=> 'Pokaż mChat na stronie głównej',
	'SOUND_MCHAT'		=> 'Włącz dzwięk w mChat',
	'DISPLAY_STATS_INDEX'	=> 'Pokazuj kto czatuje na stronie głównej',
	'DISPLAY_NEW_TOPICS'	=> 'Pokazuj nowe tematy w czacie',
	'DISPLAY_AVATARS'	=> 'Pokazuj avatary w czacie',
	
	// ACP
	'USER_MCHAT_UPDATED'	=> 'Ustawienia użytkowników mChat zostały zaktualizowane',
));
?>