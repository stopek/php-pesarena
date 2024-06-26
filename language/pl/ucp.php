﻿<?php
/**
*
* ucp [Polski]
*
* @package language
* @copyright (c) 2006 - 2010 phpBB3.PL Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// INFORMACJA
//
// Wszystkie pliki językowe powinny używać kodowania UTF-8 i nie powinny zawierać znaku BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

// Privacy policy and T&C
$lang = array_merge($lang, array(
	'TERMS_OF_USE_CONTENT'	=> 'Rejestrując się na „%1$s” zgadzasz się na poniższe warunki. Jeśli się na nie nie zgadzasz, opuść i nie korzystaj z „%1$s”. „%1$s” ma prawo zmienić te warunki, ale musi Cię o tym poinformować.<br/>
	<br/>
	Forum „%1$s” działa na skrypcie phpBB, wydanym na licencji „<a href="http://opensource.org/licenses/gpl-license.php">General Public License</a>” (zwanej też „GPL”) i możliwym do pobrania ze strony <a href="http://phpbb3.pl/">www.phpBB3.PL</a>. Skrypt phpBB tylko ułatwia dyskusje przez Internet, jego autorzy nie kontrolują tekstów zamieszczanych w Internecie za pomocą tego skryptu. Więcej informacji o skrypcie phpBB można znaleźć na stronie <a href="http://phpbb3.pl/">www.phpBB3.PL</a>.<br/>
	<br/>
	Zgadzasz się nie pisać żadnych wypowiedzi o charakterze obraźliwym, oszczerczym, propagującym treści niezgodne z polskim prawem lub naruszających cudze prawa autorskie. Robienie tego może spowodować, że zostaniesz zbanowany na zawsze, a Twój dostawca internetu powiadomiony o Twoim zachowaniu. Zgadzasz się, że „%1$s” ma prawo w każdej chwili usunąć, edytować, przenieść lub zablokować każdy wątek. Zgadzasz się też na zapisywanie wszystkich informacji, które podajesz, w bazie danych. Dane te nie będą przekazywane nikomu bez Twojej zgody, ale ani „%1$s” ani phpBB nie odpowiada za włamania, które mogą spowodować wykradzenie danych.
	',

	'PRIVACY_POLICY'		=> 'Ten tekst opisuje w jaki sposób „%1$s” i firmy z nim stowarzyszone (zwane dalej „my”, „nas”, „nasz”, „%1$s”, „%2$s”) i phpBB (zwane dalej „oni”, „ich”, „oprogramowanie phpBB”, „www.phpbb.com”, „phpBB Group”, „Zespoły phpBB”) korzystają z informacji zebranych w czasie dowolnej sesji używania naszego forum przez Ciebie (zwane dalej „informacjami o Tobie”).<br/>
	<br/>
	Informacje o Tobie są zbierane na dwa sposoby. Po pierwsze, przeglądanie „%1$s” powoduje, że oprogamowanie phpBB tworzy kilka ciasteczek (małych plików tekstowych pobranych do katalogu plików tymczasowych na Twoim komputerze). Pierwsze dwa ciasteczka zawierają identyfikator użytkownika (zwany dalej „user-id”) i anonimowy identyfikator sesji (zwany dalej „session-id”), automatycznie przyznane Ci przez oprogramowanie phpBB. Trzecie ciasteczko zostanie utworzone, gdy przejrzysz chociaż jeden wątek na „%1$s” i jest używane żeby zapisywać, które wątki zostały przeczytane, ułatwiając Ci w ten sposób nawigację po forum.<br/>
	<br/>
	W czasie przeglądania „%1$s” możemy też utworzyć ciasteczka niezależne od oprogramowania phpBB, ale ich ten dokument nie dotyczy - ma on opisywać tylko strony utworzone przez oprogramowanie phpBB. Drugi sposób, w który zbieramy informacje o Tobie, to wysyłanie ich do nas przez Ciebie. To mogą być m.in: posty napisane jako anonimowy użytkownik, konto użytkownika założone w procesie rejestracji na „%1$s” (zwane dalej „Twoim kontem”) i posty napisane przez Ciebie po rejestracji, w czasie, gdy jesteś zalogowany.<br/>
	<br/>
	Twoje konto będzie zawierać co najmniej unikalną identyfikacyjną nazwę (zwaną dalej „Twoją nazwą użytkownika”), osobiste hasło używane do logowania na Twoje konto (zwane dalej „Twoim hasłem”) i osobisty prawidłowy adres e-mail (zwany dalej „Twoim adresem e-mail”). Informacje podane dla Twojego konta na „%1$s” są chronione przez prawa dotyczące ochrony danych osobowych w państwie, w którym stoi nasz serwer. Mamy prawo wymagać podania dodatkowych informacji przy rejestracji i to my ustalamy, czy podanie ich jest konieczne, czy nie. W każdym przypadku masz możliwość wybrania, które informacje o Twoim koncie są wyświetlane publicznie. Co więcej, w panelu użytkownika masz możliwość włączenia lub wyłaczenia wysyłania do Ciebie automatycznie wygenerowanych przez oprogramowanie phpBB e-maili.<br/>
	<br/>
	Twoje hasło zostało zaszyfrowane jednostronnym szyfrem, więc jest bezpieczne. Mimo to, nie powinieneś używać tego samego hasła na różnych stronach WWW. Twoje hasło umożliwia dostęp do Twojego konta na „%1$s”, więc pilnuj go i nie podawaj <strong>nikomu</strong>. Jeśli je zapomniesz, użyj funkcji „Zapomniałem hasła”, która zapyta Cię o Twoją nazwę użytkownika i adres e-mail, a potem prześle nowe hasło na Twój adres e-mail.<br/>
	',
));

// Common language entries
$lang = array_merge($lang, array(
	'ACCOUNT_ACTIVE'				=> 'Twoje konto zostało aktywowane. Dziękujemy za rejestrację.',
	'ACCOUNT_ACTIVE_ADMIN'			=> 'Konto zostało aktywowane.',
	'ACCOUNT_ACTIVE_PROFILE'		=> 'Twoje konto zostało ponownie aktywowane.',
	'ACCOUNT_ADDED'					=> 'Dziękujemy za rejestrację, Twoje konto zostało utworzone. Możesz zalogować się korzystając z podanych wcześniej nazwy użytkownika i hasła.',
	'ACCOUNT_COPPA'					=> 'Twoje konto zostało utworzone, ale musi zostać zatwierdzone. Więcej informacji znajdziesz w specjalnej wiadomości e-mail wysłanej w momencie zakończenia rejestracji.',
	'ACCOUNT_EMAIL_CHANGED'			=> 'Twój adres e-mail został zmieniony. To forum jednak wymaga ponownej aktywacji kont poprzez podanie klucza aktywującego, który otrzymasz w specjalnej wiadomości e-mail. W niej też znajdziesz dalsze instrukcje postępowania.',
	'ACCOUNT_EMAIL_CHANGED_ADMIN'	=> 'Twój adres e-mail został zmieniony. To forum jednak wymaga ponownej aktywacji kont przez administratora. Został już do niego wysłany e-mail powiadamiający o utworzeniu nowego konta i najprawdopodobniej wkrótce zostanie ono aktywowane.',
	'ACCOUNT_INACTIVE'				=> 'Twoje konto zostało utworzone. To forum jednak wymaga aktywacji kont poprzez podanie klucza aktywującego, który otrzymasz w specjalnej wiadomości e-mail. W niej też znajdziesz dalsze instrukcje postępowania.',
	'ACCOUNT_INACTIVE_ADMIN'		=> 'Twoje konto zostało utworzone. To forum jednak wymaga aktywacji kont przez administratora. Został już do niego wysłany e-mail powiadamiający o utworzeniu nowego konta i wkrótce zostanie ono aktywowane.',
	'ACTIVATION_EMAIL_SENT'			=> 'E-mail aktywacyjny został wysłany na Twój adres e-mail.',
	'ACTIVATION_EMAIL_SENT_ADMIN'	=> 'E-mail aktywacyjny został wysłany na adresy e-mail administratorów.',
	'ADD'							=> 'Dodaj',
	'ADD_BCC'						=> 'Dodaj [BCC]',
	'ADD_FOES'						=> 'Dodaj wroga',
	'ADD_FOES_EXPLAIN'				=> 'Możesz wpisać kilka loginów, każdy w osobnej linii.',
	'ADD_FOLDER'					=> 'Dodaj folder',
	'ADD_FRIENDS'					=> 'Dodaj przyjaciela',
	'ADD_FRIENDS_EXPLAIN'			=> 'Możesz wpisać kilka loginów, każdy w osobnej linii.',
	'ADD_NEW_RULE'					=> 'Dodaj nową regułę',
	'ADD_RULE'						=> 'Dodaj regułę',
	'ADD_TO'						=> 'Dodaj [Do]',
	'ADD_USERS_UCP_EXPLAIN'			=> 'Tutaj możesz dodać nowych użytkowników do grupy. Możesz wybrać czy ta grupa zostanie nową grupą domyślną dla wybranych użytkowników. Umieść każdą nazwę użytkownika w osobnej linii.',
	'ADMIN_EMAIL'					=> 'Administratorzy mogą wysyłać mi e-maile',
	'AGREE'							=> 'Zgadzam się',
	'ALLOW_PM'						=> 'Użytkownicy mogą mi wysyłać prywatne wiadomości',
	'ALLOW_PM_EXPLAIN'				=> 'Zauważ, że administratorzy i moderatorzy zawsze będą mogli wysyłać Ci wiadomości.',
	'ALREADY_ACTIVATED'				=> 'Już aktywowałeś/aś swoje konto.',
	'ATTACHMENTS_EXPLAIN'			=> 'To jest lista załączników, które wysłałeś/aś na forum w swoich postach.',
	'ATTACHMENTS_DELETED'			=> 'Załączniki usunięte.',
	'ATTACHMENT_DELETED'			=> 'Załącznik usunięty.',
	'AVATAR_CATEGORY'				=> 'Kategoria',
	'AVATAR_EXPLAIN'				=> 'Maksymalne rozmiary; szerokość: %1$d pikseli, wysokość: %2$d pikseli, wielkość pliku: %3$.2f KiB.',
	'AVATAR_FEATURES_DISABLED'		=> 'Avatary zostały zablokowane.',
	'AVATAR_GALLERY'				=> 'Galeria lokalna',
	'AVATAR_GENERAL_UPLOAD_ERROR'	=> 'Nie można wysłać avatara do %s.',
	'AVATAR_NOT_ALLOWED'			=> 'Twój avatar nie mógł zostać wyświetlony ponieważ avatary zostały wyłączone.',
	'AVATAR_PAGE'					=> 'Strona',
	'AVATAR_TYPE_NOT_ALLOWED'		=> 'Twój obecny avatar nie mógł zostać wyświetlony ponieważ avatary jego typu zostały wyłączone.',

	'BACK_TO_DRAFTS'			=> 'Powrót do kopii roboczych',
	'BACK_TO_LOGIN'				=> 'Powrót do ekranu logowania',
	'BIRTHDAY'					=> 'Urodziny',
	'BIRTHDAY_EXPLAIN'			=> 'Wybranie roku spowoduje wyświetlenie Twojego wieku w dniu Twoich urodzin.',
	'BOARD_DATE_FORMAT'			=> 'Format daty',
	'BOARD_DATE_FORMAT_EXPLAIN'	=> 'Składnia jest taka sama, jak w funkcji <a href="http://www.php.net/date"><code>date()</code></a> w PHP.',
	'BOARD_DST'					=> 'Czas letni/<abbr title="Daylight Saving Time">DST</abbr>',
	'BOARD_LANGUAGE'			=> 'Język',
	'BOARD_STYLE'				=> 'Styl forum',
	'BOARD_TIMEZONE'			=> 'Strefa czasowa',
	'BOOKMARKS'					=> 'Zakładki',
	'BOOKMARKS_EXPLAIN'			=> 'Możesz dodać wątki do zakładek, żeby później do nich wrócić. Zaznacz pole obok każdej zakładki, którą chcesz wyrzucić, a następnie naciśnij przycisk <em>Usuń wybrane zakładki</em>.',
	'BOOKMARKS_DISABLED'		=> 'Funkcja zakładek została wyłączona przez administratora.',
	'BOOKMARKS_REMOVED'			=> 'Zakładki zostały usunięte.',

	'CANNOT_EDIT_MESSAGE_TIME'	=> 'Nie możesz już edytować lub usunąć tej wiadomości.',
	'CANNOT_MOVE_TO_SAME_FOLDER'=> 'Wiadomości nie mogą zostać przeniesione do folderu, który chcesz usunąć.',
	'CANNOT_MOVE_FROM_SPECIAL'	=> 'Wiadomości nie mogą zostać przeniesione z folderu „Do wysłania”.',
	'CANNOT_RENAME_FOLDER'		=> 'Nazwa tego folderu nie może zostać zmieniona.',
	'CANNOT_REMOVE_FOLDER'		=> 'Ten folder nie może zostać usunięty.',
	'CHANGE_DEFAULT_GROUP'		=> 'Zmień domyślną grupę',
	'CHANGE_PASSWORD'			=> 'Zmień hasło',
	'CLICK_RETURN_FOLDER'		=> '%1$sPowrót do folderu „%3$s”%2$s',
	'CONFIRMATION'				=> 'Potwierdzenie rejestracji',
	'CONFIRM_CHANGES'			=> 'Potwierdź zmiany',
	'CONFIRM_EMAIL'				=> 'Potwierdź adres e-mail',
	'CONFIRM_EMAIL_EXPLAIN'		=> 'Podaj tylko wtedy, gdy zmieniasz swój adres e-mail.',
	'CONFIRM_EXPLAIN'			=> 'Żeby zapobiec automatycznym rejestracjom, forum wymaga wpisania kodu potwierdzającego. Kod jest pokazywany na obrazku poniżej. Jeśli nie możesz go przeczytać, to skontaktuj się z %sadministratorem forum%s.',
	'VC_REFRESH'				=> 'Odśwież kod potwierdzający',
	'VC_REFRESH_EXPLAIN'		=> 'Jeśli nie możesz przeczytać kodu, możesz zażądać nowego klikając na ten przycisk.',
	'CONFIRM_PASSWORD'			=> 'Potwierdź hasło',
	'CONFIRM_PASSWORD_EXPLAIN'	=> 'Potwierdzić hasło musisz tylko wówczas, jeśli zmieniłeś je powyżej.',
	'COPPA_BIRTHDAY'			=> 'Aby kontynuować rejestrację, podaj datę swoich urodzin.',
	'COPPA_COMPLIANCE'			=> 'Zgodność COPPA',
	'COPPA_EXPLAIN'				=> 'Zauważ, że kliknięcie na „Wyślij” spowoduje utworzenie Twojego konta. Nie może jednak zostać ono aktywowane póki rodzic lub prawny opiekun nie potwierdzi Twojej rejestracji. Otzymasz e-mailem formularz z informacją, gdzie należy go wysłać.',
	'CREATE_FOLDER'				=> 'Dodaj folder…',
	'CURRENT_IMAGE'				=> 'Aktualny avatar',
	'CURRENT_PASSWORD'			=> 'Aktualne hasło',
	'CURRENT_PASSWORD_EXPLAIN'	=> 'Musisz wpisać tu swoje aktualne hasło, jeżeli chcesz zmienić je, swój adres e-mail lub login.',
	'CUR_PASSWORD_ERROR'		=> 'Aktualne hasło, które wpisałeś, jest niewłaściwe.',
	'CUSTOM_DATEFORMAT'			=> 'Inny…',

	'DEFAULT_ACTION'			=> 'Domyślna akcja',
	'DEFAULT_ACTION_EXPLAIN'	=> 'Ta akcja będzie wykonana, jeżeli żadnej z powyższych nie da się zastosować.',
	'DEFAULT_ADD_SIG'			=> 'Domyślnie dołączaj mój podpis',
	'DEFAULT_BBCODE'			=> 'Domyślnie odblokuj BBCode',
	'DEFAULT_NOTIFY'			=> 'Domyślnie informuj mnie o odpowiedziach',
	'DEFAULT_SMILIES'			=> 'Domyślnie odblokuj uśmieszki',
	'DEFINED_RULES'				=> 'Zdefiniowane reguły',
	'DELETED_TOPIC'				=> 'Wątek został usunięty.',
	'DELETE_ATTACHMENT'				=> 'Usuń załącznik',
	'DELETE_ATTACHMENTS'			=> 'Usuń załączniki',
	'DELETE_ATTACHMENT_CONFIRM'		=> 'Czy na pewno chcesz usunąć ten załącznik?',
	'DELETE_ATTACHMENTS_CONFIRM'	=> 'Czy na pewno chcesz usunąć te załączniki?',
	'DELETE_AVATAR'					=> 'Usuń avatar',
	'DELETE_COOKIES_CONFIRM'		=> 'Czy na pewno chcesz usunąć wszystkie ciasteczka wysłane z tego forum?',
	'DELETE_MARKED_PM'				=> 'Usuń wybrane wiadomości',
	'DELETE_MARKED_PM_CONFIRM'		=> 'Czy na pewno chcesz usunąć wszystkie wybrane wiadomości?',
	'DELETE_OLDEST_MESSAGES'		=> 'Usuń najstarsze wiadomości',
	'DELETE_MESSAGE'				=> 'Usuń wiadomość',
	'DELETE_MESSAGE_CONFIRM'		=> 'Czy na pewno chcesz usunąć tę prywatną wiadomość?',
	'DELETE_MESSAGES_IN_FOLDER'		=> 'Usuń wszystkie wiadomości będące wewnątrz usuwanego folderu',
	'DELETE_RULE'					=> 'Usuń regułę',
	'DELETE_RULE_CONFIRM'			=> 'Czy na pewno chcesz usunąć tę regułę?',
	'DEMOTE_SELECTED'			=> 'Zdegraduj wybranych',
	'DISABLE_CENSORS'			=> 'Odblokuj cenzurę słów',
	'DISPLAY_GALLERY'			=> 'Pokaż galerię',
	'DOMAIN_NO_MX_RECORD_EMAIL'	=> 'Podana domena e-mail nie ma właściwego rekordu MX.',
	'DOWNLOADS'					=> 'Pobieranie plików',
	'DRAFTS_DELETED'			=> 'Wszystkie wybrane kopie robocze zostały usunięte.',
	'DRAFTS_EXPLAIN'			=> 'Tutaj możesz przeglądać, edytować i usuwać Twoje zapisane kopie robocze.',
	'DRAFT_UPDATED'				=> 'Kopia robocza została zaktualizowana.',

	'EDIT_DRAFT_EXPLAIN'		=> 'Tutaj możesz zmienić swoją kopię roboczą. Kopie nie zawierają załączników i informacji o ankiecie.',
	'EMAIL_BANNED_EMAIL'		=> 'Wpisany adres e-mail jest zablokowany.',
	'EMAIL_INVALID_EMAIL'		=> 'Wpisany adres e-mail jest błędny.',
	'EMAIL_REMIND'				=> 'To musi być adres e-mail związany z Twoim kontem. Jeżeli nie zmieniałeś go w panelu użytkownika, jest to adres podany w czasie rejestracji.',
	'EMAIL_TAKEN_EMAIL'			=> 'Wpisany adres e-mail jest już używany.',
	'EMPTY_DRAFT'				=> 'Musisz wpisać wiadomość, żeby zapisać zmiany.',
	'EMPTY_DRAFT_TITLE'			=> 'Musisz wpisać tytuł kopii roboczej.',
	'EXPORT_AS_XML'				=> 'Eksportuj jako XML',
	'EXPORT_AS_CSV'				=> 'Eksportuj jako CSV',
	'EXPORT_AS_CSV_EXCEL'		=> 'Eksportuj jako CSV (MS Excel)',
	'EXPORT_AS_TXT'				=> 'Eksportuj jako TXT',
	'EXPORT_AS_MSG'				=> 'Eksportuj jako MSG',
	'EXPORT_FOLDER'				=> 'Eksportuj ten widok',

	'FIELD_REQUIRED'					=> 'Pole „%s” musi być wypełnione.',
	'FIELD_TOO_SHORT'					=> 'Pole „%1$s” jest za krótkie, wymagane jest min. %2$d znaków.',
	'FIELD_TOO_LONG'					=> 'Pole „%1$s” jest za długie, wymagane jest maks. %2$d znaków.',
	'FIELD_TOO_SMALL'					=> 'Wartość pola „%1$s” jest za mała, min. wartość to %2$d.',
	'FIELD_TOO_LARGE'					=> 'Wartość pola „%1$s” jest za duża, maks. wartość to %2$d.',
	'FIELD_INVALID_CHARS_NUMBERS_ONLY'	=> 'Pole „%s” zawiera niewłaściwe znaki. Dozwolone są tylko liczby.',
	'FIELD_INVALID_CHARS_ALPHA_ONLY'	=> 'Pole „%s” zawiera niewłaściwe znaki. Dozwolone są tylko litery, liczby i „_” .',
	'FIELD_INVALID_CHARS_SPACERS_ONLY'	=> 'Pole „%s” zawiera niewłaściwe znaki. Dozwolone są tylko litery, liczby, spacje, „_”, „-”, „+”, „[” i „]”.',
	'FIELD_INVALID_DATE'				=> 'Pole „%s” zawiera błędną datę.',

	'FOE_MESSAGE'				=> 'Wiadomość od wroga',
	'FOES_EXPLAIN'				=> 'Wrogowie to użytkownicy domyślne ignorowani. Posty tych użytkowników nie będą wyświetlane, pojawią się tylko informacje, że posty te zostały napisane. Prywatne wiadomości od wrogów są dozwolone. Zauważ, że nie możesz ignorować moderatorów i administratorów.',
	'FOES_UPDATED'				=> 'Lista Twoich wrogów została zaktualizowana.',
	'FOLDER_ADDED'				=> 'Folder dodany.',
	'FOLDER_MESSAGE_STATUS'		=> 'przechowywana jest %1$d z maksymalnej liczby %2$d wiadomości',
	'FOLDER_NAME_EMPTY'			=> 'Musisz podać nazwę dla tego folderu.',
	'FOLDER_NAME_EXIST'			=> 'Folder <strong>%s</strong> już istnieje.',
	'FOLDER_OPTIONS'			=> 'Opcje folderów',
	'FOLDER_RENAMED'			=> 'Nazwa folderu została zmieniona.',
	'FOLDER_REMOVED'			=> 'Folder został usunięty.',
	'FOLDER_STATUS_MSG'			=> 'Folder jest w %1$d%% zapełniony (przechowywana jest %2$d z maksymalnej liczby %3$d wiadomości)',
	'FORWARD_PM'				=> 'Prześlij dalej PW',
	'FORCE_PASSWORD_EXPLAIN'	=> 'Przed kontynuowaniem przeglądania forum musisz zmienić swoje hasło.',
	'FRIEND_MESSAGE'			=> 'Wiadomość od przyjaciela',
	'FRIENDS'					=> 'Przyjaciele',
	'FRIENDS_EXPLAIN'			=> 'Zakładka „Przyjaciele” pozwoli Ci na szybki dostęp do użytkowników, z którymi często się komunikujesz. Jeśli szablon ma taką możliwość, każdy post napisany przez przyjaciela może być podświetlony.',
	'FRIENDS_OFFLINE'			=> 'Offline',
	'FRIENDS_ONLINE'			=> 'Online',
	'FRIENDS_UPDATED'			=> 'Lista twoich przyjaciół została zaktualizowana.',
	'FULL_FOLDER_OPTION_CHANGED'=> 'Akcja do wykonania, gdy folder jest pełny, została zmieniona.',
	'FWD_ORIGINAL_MESSAGE'		=> '-------- Oryginalna wiadomość --------',
	'FWD_SUBJECT'				=> 'Tytuł: %s',
	'FWD_DATE'					=> 'Data: %s',
	'FWD_FROM'					=> 'Od: %s',
	'FWD_TO'					=> 'Do: %s',

	'GLOBAL_ANNOUNCEMENT'		=> 'Ogłoszenie globalne',

	'HIDE_ONLINE'				=> 'Ukrywaj moją obecność na forum',
	'HIDE_ONLINE_EXPLAIN'		=> 'Efekt zmiany tego ustawienia zobaczysz dopiero podczas kolejnej wizyty na forum.',
	'HOLD_NEW_MESSAGES'			=> 'Nie przyjmuj nowych wiadomości (nowe wiadomości będą czekały, póki nie zwolnisz odpowiedniej ilości miejsca)',
	'HOLD_NEW_MESSAGES_SHORT'	=> 'Nowe wiadomości będą czekały',

	'IF_FOLDER_FULL'			=> 'Jeśli folder będzie pełny',
	'IMPORTANT_NEWS'			=> 'Ważne ogłoszenia',
	'INVALID_USER_BIRTHDAY'		=> 'Podana data urodzin nie jest prawidłową datą.',
	'INVALID_CHARS_USERNAME'	=> 'Login zawiera niedozwolone znaki.',
	'INVALID_CHARS_NEW_PASSWORD'=> 'Hasło nie zawiera wymaganych znaków.',
	'ITEMS_REQUIRED'			=> 'Pola oznaczone gwiazdką (*) są wymagane.',

	'JOIN_SELECTED'				=> 'Dołącz do wybranych',

	'LANGUAGE'					=> 'Język',
	'LINK_REMOTE_AVATAR'		=> 'Link zewnętrzny',
	'LINK_REMOTE_AVATAR_EXPLAIN'=> 'Wpisz adres WWW avatara, którego chcesz mieć.',
	'LINK_REMOTE_SIZE'			=> 'Rozmiary avatara',
	'LINK_REMOTE_SIZE_EXPLAIN'	=> 'Określ wysokość i szerokość avatara lub zostaw puste, żeby spróbować automatycznej weryfikacji.',
	'LOGIN_EXPLAIN_UCP'			=> 'Zaloguj się, aby otrzymać dostęp do panelu użytkownika.',
	'LOGIN_REDIRECT'			=> 'Zalogowałeś/aś się.',
	'LOGOUT_FAILED'				=> 'Nie zostałeś/aś wylogowany/a, ponieważ nie znaleziono Twojej sesji. Skontaktuj się z administratorem forum, jeżeli nadal będziesz miał(a) problemy.',
	'LOGOUT_REDIRECT'			=> 'Wylogowałeś/aś się.',

	'MARK_IMPORTANT'				=> 'Zaznacz/odznacz jako ważne',
	'MARKED_MESSAGE'				=> 'Zaznaczona wiadomość',
	'MAX_FOLDER_REACHED'			=> 'Maksymalna liczba folderów użytkownika została przekroczona.',
	'MESSAGE_BY_AUTHOR'				=> 'przez',
	'MESSAGE_COLOURS'				=> 'Kolory wiadomości',
	'MESSAGE_DELETED'				=> 'Wiadomość została usunięta.',
	'MESSAGE_HISTORY'				=> 'Historia wiadomości',
	'MESSAGE_REMOVED_FROM_OUTBOX'	=> 'Wiadomość została usunięta przez nadawcę zanim została dostarczona.',
	'MESSAGE_SENT_ON'				=> '',
	'MESSAGE_STORED'				=> 'Wiadomość została wysłana.',
	'MESSAGE_TO'					=> 'do',
	'MESSAGES_DELETED'				=> 'Wiadomości usunięte.',
	'MOVE_DELETED_MESSAGES_TO'		=> 'Przenieś wszystkie wiadomości z usuwanego folderu do',
	'MOVE_DOWN'						=> 'Przenieś w dół',
	'MOVE_MARKED_TO_FOLDER'			=> 'Przenieś zaznaczone do %s',
	'MOVE_PM_ERROR'					=> 'Nastąpił błąd w czasie przenoszenie wiadomości do innego folderu, tylko %1d z %2d wiadomości zostały przeniesione.',
	'MOVE_TO_FOLDER'				=> 'Przenieś do folderu',
	'MOVE_UP'						=> 'Przenieś w górę',

	'NEW_EMAIL_ERROR'			=> 'Adresy e-mail, które wpisałeś, nie są identyczne.',
	'NEW_FOLDER_NAME'			=> 'Nazwa nowego folderu',
	'NEW_PASSWORD'				=> 'Nowe hasło',
	'NEW_PASSWORD_ERROR'		=> 'Hasła, które wpisałeś, nie są identyczne.',
	'NOTIFY_METHOD'				=> 'Sposób powiadomienia',
	'NOTIFY_METHOD_BOTH'		=> 'Oba sposoby',
	'NOTIFY_METHOD_EMAIL'		=> 'Tylko e-mail',
	'NOTIFY_METHOD_EXPLAIN'		=> 'Metoda wysyłania powiadomień generowanych przez to forum.',
	'NOTIFY_METHOD_IM'			=> 'Tylko Jabber',
	'NOTIFY_ON_PM'				=> 'Powiadamiaj mnie, gdy dostaję PW',
	'NOT_ADDED_FRIENDS_ANONYMOUS'	=> 'Nie możesz dodać anonima do listy przyjaciół.',
	'NOT_ADDED_FRIENDS_BOTS'		=> 'Nie możesz dodać botów do listy przyjaciół.',
	'NOT_ADDED_FRIENDS_FOES'		=> 'Nie możesz dodać użytkowników będących na liście wrogów do listy przyjaciół.',
	'NOT_ADDED_FRIENDS_SELF'		=> 'Nie możesz dodać siebie samego do listy przyjaciół.',
	'NOT_ADDED_FOES_MOD_ADMIN'		=> 'Nie możesz dodać administratorów ani moderatorów do listy wrogów.',
	'NOT_ADDED_FOES_ANONYMOUS'		=> 'Nie możesz dodać anonima do listy wrogów.',
	'NOT_ADDED_FOES_BOTS'			=> 'Nie możesz dodać botów do listy wrogów.',
	'NOT_ADDED_FOES_FRIENDS'		=> 'Nie możesz dodać użytkowników będących na liście przyjaciół do listy wrogów.',
	'NOT_ADDED_FOES_SELF'			=> 'Nie możesz dodać siebie samego do listy wrogów.',
	'NOT_AGREE'					=> 'Nie zgadzam się',
	'NOT_ENOUGH_SPACE_FOLDER'	=> 'Docelowy folder „%s” wygląda na pełny. Wybrana czynność nie została wykonana.',
	'NOT_MOVED_MESSAGE'			=> '1 prywatna wiadomość oczekuje, aż zwolnisz miejsce w folderze.',
	'NOT_MOVED_MESSAGES'		=> '%d prywatne/ych wiadomości oczekują/e, aż zwolnisz miejsce w folderze.',
	'NO_ACTION_MODE'			=> 'Żadna akcja nie została wybrana.',
	'NO_AUTHOR'					=> 'Ta wiadomość nie ma autora.',
	'NO_AVATAR_CATEGORY'		=> 'Brak',

	'NO_AUTH_DELETE_MESSAGE'		=> 'Nie masz uprawnień do usuwania prywatnych wiadomości.',
	'NO_AUTH_EDIT_MESSAGE'			=> 'Nie masz uprawnień do edytowania prywatnych wiadomości.',
	'NO_AUTH_FORWARD_MESSAGE'		=> 'Nie masz uprawnień do przesyłania dalej prywatnych wiadomości.',
	'NO_AUTH_GROUP_MESSAGE'			=> 'Nie masz uprawnień do wysyłania prywatnych wiadomości do grup.',
	'NO_AUTH_PASSWORD_REMINDER'		=> 'Nie masz uprawnień do proszenia o nowe hasło.',
	'NO_AUTH_READ_HOLD_MESSAGE'		=> 'Nie masz uprawnień do czytania oczekujących prywatnych wiadomości.',
	'NO_AUTH_READ_MESSAGE'			=> 'Nie masz uprawnień do czytania prywatnych wiadomości.',
	'NO_AUTH_READ_REMOVED_MESSAGE'	=> 'Nie możesz przeczytać tej wiadomości, ponieważ została usunięta przez nadawcę.',
	'NO_AUTH_SEND_MESSAGE'			=> 'Nie masz uprawnień do wysyłania prywatnych wiadomości.',
	'NO_AUTH_SIGNATURE'				=> 'Nie masz uprawnień do posiadania podpisu.',

	'NO_BCC_RECIPIENT'			=> 'Brak',
	'NO_BOOKMARKS'				=> 'Nie masz zakładek.',
	'NO_BOOKMARKS_SELECTED'		=> 'Nie wybrałeś/aś żadnych zakładek.',
	'NO_EDIT_READ_MESSAGE'		=> 'Ta prywatna wiadomość nie może zostać zmieniona, ponieważ została już przeczytana.',
	'NO_EMAIL_USER'				=> 'Wysłane informacje o e-mailu / nazwie użytkownika nie zostały znalezione.',
	'NO_FOES'					=> 'Nie masz wrogów.',
	'NO_FRIENDS'				=> 'Nie masz przyjaciół.',
	'NO_FRIENDS_OFFLINE'		=> 'Żaden przyjaciel nie jest offline',
	'NO_FRIENDS_ONLINE'			=> 'Żaden przyjaciel nie jest online',
	'NO_GROUP_SELECTED'			=> 'Nie wybrałeś/aś grupy.',
	'NO_IMPORTANT_NEWS'			=> 'Aktualnie nie ma ważnych ogłoszeń.',
	'NO_MESSAGE'				=> 'Prywatne Wiadomości nie zostały znalezione.',
	'NO_NEW_FOLDER_NAME'		=> 'Musisz wpisać nową nazwę folderu.',
	'NO_NEWER_PM'				=> 'Nie ma nowszych wiadomości.',
	'NO_OLDER_PM'				=> 'Nie ma starszych wiadomości.',
	'NO_PASSWORD_SUPPLIED'		=> 'Nie możesz się zalogować bez podania hasła.',
	'NO_RECIPIENT'				=> 'Nie wybrano odbiorcy.',
	'NO_RULES_DEFINED'			=> 'Żadna reguła nie została zdefiniowana',
	'NO_SAVED_DRAFTS'			=> 'Nie masz żadnych kopii roboczych.',
	'NO_TO_RECIPIENT'			=> 'Brak',
	'NO_WATCHED_FORUMS'			=> 'Nie obserwujesz żadnych działów.',
	'NO_WATCHED_SELECTED'		=> 'Nie wybrałeś/aś żadnych obserwowanych wątków ani działów.',
	'NO_WATCHED_TOPICS'			=> 'Nie obserwujesz żadnych wątków.',

	'PASS_TYPE_ALPHA_EXPLAIN'	=> 'Hasło musi mieć min. %1$d, a maks. %2$d znaków i musi składać się z liter różnej wielkości oraz cyfr.',
	'PASS_TYPE_ANY_EXPLAIN'		=> 'Hasło musi mieć min. %1$d, a maks. %2$d znaków.',
	'PASS_TYPE_CASE_EXPLAIN'	=> 'Hasło musi mieć min. %1$d, a maks. %2$d znaków i musi składać się z liter różnej wielkości.',
	'PASS_TYPE_SYMBOL_EXPLAIN'	=> 'Hasło musi mieć min. %1$d, a maks. %2$d znaków i musi składać się z liter różnej wielkości, cyfr oraz symboli.',
	'PASSWORD'					=> 'Hasło',
	'PASSWORD_ACTIVATED'		=> 'Twoje nowe hasło zostało aktywowane.',
	'PASSWORD_UPDATED'			=> 'Nowe hasło zostało wysłane na Twój adres e-mail.',
	'PERMISSIONS_RESTORED'		=> 'Przywrócono pierwotne uprawnienia.',
	'PERMISSIONS_TRANSFERRED'	=> 'Skopiowano uprawnienia od <strong>%s</strong>. Możesz teraz przeglądać forum z jego uprawnieniami.<br/>Zauważ, że uprawnienia admina nie zostały skopiowane. Możesz wrócić do swoich standardowych uprawnień w każdej chwili.',
	'PM_DISABLED'				=> 'Prywatne wiadomości zostały zablokowane.',
	'PM_FROM'					=> 'Od',
	'PM_FROM_REMOVED_AUTHOR'	=> 'Ta wiadomość została wysłana przez użytkownika, którego konto zostało skasowane.',
	'PM_ICON'					=> 'Ikona PW',
	'PM_INBOX'					=> 'Otrzymane',
	'PM_NO_USERS'				=> 'Wybrani użytkownicy nie istnieją.',
	'PM_OUTBOX'					=> 'Do wysłania',
	'PM_SENTBOX'				=> 'Wysłane',
	'PM_SUBJECT'				=> 'Tytuł wiadomości',
	'PM_TO'						=> 'Wyślij do',
	'PM_USERS_REMOVED_NO_PM'	=> 'Część użytkowników nie mogła zostać dodana, ponieważ zablokowali oni otrzymywanie prywatnych wiadomości.',
	'POPUP_ON_PM'				=> 'Po przyjściu nowej PW otwieraj dodatkowe okno',
	'POST_EDIT_PM'				=> 'Edytuj wiadomosć',
	'POST_FORWARD_PM'			=> 'Prześlij dalej wiadomość',
	'POST_NEW_PM'				=> 'Wyślij wiadomość',
	'POST_PM_LOCKED'			=> 'Wysyłanie PW jest zablokowane.',
	'POST_PM_POST'				=> 'Cytuj post',
	'POST_QUOTE_PM'				=> 'Cytuj wiadomość',
	'POST_REPLY_PM'				=> 'Odpowiedz na wiadomość',
	'PRINT_PM'					=> 'Wersja do wydruku',
	'PREFERENCES_UPDATED'		=> 'Twoje ustawienia zostały zmienione.',
	'PROFILE_INFO_NOTICE'		=> 'Zauważ, że ta informacja może być widoczna dla innych użytkowników. Uważaj, gdy wstawiasz informacje prywatne. Wszystkie pola oznaczone * muszą zostać wypełnione.',
	'PROFILE_UPDATED'			=> 'Twój profil został zmieniony.',

	'RECIPIENT'							=> 'Odbiorca',
	'RECIPIENTS'						=> 'Odbiorcy',
	'REGISTRATION'						=> 'Rejestracja',
	'RELEASE_MESSAGES'					=> '%sUwolnij wszystkie czekające wiadomości%s. Zostaną one rozłożone do różnych folderów, jeśli wolna jest wystarczająca ilość miejsca.',
	'REMOVE_ADDRESS'					=> 'Usuń adres',
	'REMOVE_SELECTED_BOOKMARKS'			=> 'Usuń wybrane zakładki',
	'REMOVE_SELECTED_BOOKMARKS_CONFIRM' => 'Czy na pewno chcesz usunąć wszystkie wybrane zakładki?',
	'REMOVE_BOOKMARK_MARKED'			=> 'Usuń wybrane zakładki',
	'REMOVE_FOLDER'						=> 'Usuń folder',
	'REMOVE_FOLDER_CONFIRM'				=> 'Czy na pewno chcesz usunąć ten folder?',
	'RENAME'							=> 'Zmień nazwę',
	'RENAME_FOLDER'						=> 'Zmień nazwę folderu',
	'REPLIED_MESSAGE'					=> 'Odpowiedziano na wiadomość',
	'REPLY_TO_ALL'						=> 'Odpowiedz nadawcy i wszystkim odbiorcom.',
	'REPORT_PM'							=> 'Zgłoś wiadomość',
	'RESIGN_SELECTED'					=> 'Opuść wybrane',
	'RETURN_FOLDER'						=> '%sPowrót do ostatnio widzianego folderu%s',
	'RETURN_UCP'						=> '%sPowrót do panelu użytkownika%s',
	'RULE_ADDED'						=> 'Reguła została dodana.',
	'RULE_ALREADY_DEFINED'				=> 'Roguła została dodana już wcześniej.',
	'RULE_DELETED'						=> 'Reguła została usunięta.',
	'RULE_NOT_DEFINED'					=> 'Reguła nie została dokładnie określona',
	'RULE_REMOVED_MESSAGE'				=> 'Jedna prywatna wiadomość została usunięta przez ustawione filtry wiadomości.',
	'RULE_REMOVED_MESSAGES'				=> '%d prywatne/ych wiadomości zostały/o usunięte/ych przez ustawione filtry wiadomości.',

	'SAME_PASSWORD_ERROR'		=> 'Podane nowe hasło jest takie samo jak aktualne hasło.',
	'SEARCH_YOUR_POSTS'			=> 'Zobacz swoje posty',
	'SEND_PASSWORD'				=> 'Wyślij hasło',
	'SENT_AT'					=> 'Wysłano',
	'SHOW_EMAIL'				=> 'Użytkownicy mogą wysyłać mi e-maile',
	'SIGNATURE_EXPLAIN'			=> 'Ten blok tekstu będzie dołączany do postów napisanych przez Ciebie. Maksymalna liczba znaków to %d.',
	'SIGNATURE_PREVIEW'			=> 'Twój podpis będzie wyglądać tak:',
	'SIGNATURE_TOO_LONG'		=> 'Twój podpis jest zbyt długi.',
	'SORT'						=> 'Sortuj wg',
	'SORT_COMMENT'				=> 'Komentarz',
	'SORT_DOWNLOADS'			=> 'Liczba pobrań',
	'SORT_EXTENSION'			=> 'Rozszerzenie',
	'SORT_FILENAME'				=> 'Nazwa pliku',
	'SORT_POST_TIME'			=> 'Czas wysłania',
	'SORT_SIZE'					=> 'Rozmiar',

	'TIMEZONE'					=> 'Strefa czasowa',
	'TO'						=> 'Do',
	'TOO_MANY_RECIPIENTS'		=> 'Spróbowałeś wysłać wiadomość do zbyt dużej liczby odbiorców.',
	'TOO_MANY_REGISTERS'		=> 'Przekroczyłeś maksymalną liczbę prób rejestracji w tej sesji. Spróbuj ponownie później.',

	'UCP'						=> 'Panel użytkownika',
	'UCP_ACTIVATE'				=> 'Aktywuj konto',
	'UCP_ADMIN_ACTIVATE'		=> 'Zauważ, że musisz wpisać prawidłowy adres e-mail zanim Twoje konto zostanie aktywowane. Administrator przejrzy Twoje konto i jeśli je zatwierdzi, to dostaniesz e-mail na adres, który podałeś.',
	'UCP_AIM'					=> 'AOL Instant Messenger',
	'UCP_ATTACHMENTS'			=> 'Załączniki',
	'UCP_COPPA_BEFORE'			=> 'Przed %s',
	'UCP_COPPA_ON_AFTER'		=> 'Równo lub po %s',
	'UCP_EMAIL_ACTIVATE'		=> 'Zauważ, że musisz wpisać prawidłowy adres e-mail zanim Twoje konto zostanie aktywowane. Otrzymasz e-mail zawierający link aktywacyjny na adres, który podałeś.',
	'UCP_ICQ'					=> 'Numer ICQ',
	'UCP_JABBER'				=> 'Adres Jabber',

	'UCP_MAIN'					=> 'Przegląd',
	'UCP_MAIN_ATTACHMENTS'		=> 'Załączniki',
	'UCP_MAIN_BOOKMARKS'		=> 'Zakładki',
	'UCP_MAIN_DRAFTS'			=> 'Kopie robocze',
	'UCP_MAIN_FRONT'			=> 'Strona główna',
	'UCP_MAIN_SUBSCRIBED'		=> 'Obserwowane',

	'UCP_MSNM'					=> 'WL/MSN Messenger',
	'UCP_NO_ATTACHMENTS'		=> 'Nie wysłałeś żadnych plików.',

	'UCP_PREFS'					=> 'Ustawienia forum',
	'UCP_PREFS_PERSONAL'		=> 'Ustawienia prywatne',
	'UCP_PREFS_POST'			=> 'Wysyłanie wiadomości',
	'UCP_PREFS_VIEW'			=> 'Przeglądanie postów',

	'UCP_PM'					=> 'Prywatne wiadomości',
	'UCP_PM_COMPOSE'			=> 'Napisz wiadomość',
	'UCP_PM_DRAFTS'				=> 'Kopie robocze',
	'UCP_PM_OPTIONS'			=> 'Zasady, foldery i ustawienia',
	'UCP_PM_POPUP'				=> 'Prywatne wiadomości',
	'UCP_PM_POPUP_TITLE'		=> 'Prywatne wiadomości - Popup',
	'UCP_PM_UNREAD'				=> 'Nieprzeczytane wiadomości',
	'UCP_PM_VIEW'				=> 'Wiadomości',

	'UCP_PROFILE'				=> 'Profil',
	'UCP_PROFILE_AVATAR'		=> 'Avatar',
	'UCP_PROFILE_PROFILE_INFO'	=> 'Profil',
	'UCP_PROFILE_REG_DETAILS'	=> 'Szczegóły rejestracji',
	'UCP_PROFILE_SIGNATURE'		=> 'Podpis',

	'UCP_USERGROUPS'			=> 'Grupy użytkowników',
	'UCP_USERGROUPS_MEMBER'		=> 'Członkostwa',
	'UCP_USERGROUPS_MANAGE'		=> 'Zarządzaj grupami',

	'UCP_REGISTER_DISABLE'			=> 'Utworzenie nowego konta jest aktualnie niemożliwe.',
	'UCP_REMIND'					=> 'Wyślij hasło',
	'UCP_RESEND'					=> 'Wyślij e-mail aktywacyjny',
	'UCP_WELCOME'					=> 'Witamy w panelu użytkownika. Możesz stąd monitorować, przeglądać i edytować swój profil, ustawienia, obserwowane działy i wątki. Możesz też wysyłać wiadomości do innych użytkowników (jeśli administracja na to pozwala). Upewnij się, że przeczytałeś ogłoszenia, zanim będziesz kontynuował.',
	'UCP_YIM'						=> 'Yahoo Messenger',
	'UCP_ZEBRA'						=> 'Przyjaciele i wrogowie',
	'UCP_ZEBRA_FOES'				=> 'Wrogowie',
	'UCP_ZEBRA_FRIENDS'				=> 'Przyjaciele',
	'UNDISCLOSED_RECIPIENT'			=> 'Utajniony odbiorca',
	'UNKNOWN_FOLDER'				=> 'Nieznany folder',
	'UNWATCH_MARKED'				=> 'Nie obserwuj wybranych',
	'UPLOAD_AVATAR_FILE'			=> 'Wgraj ze swojego komputera',
	'UPLOAD_AVATAR_URL'				=> 'Wgraj z adresu WWW',
	'UPLOAD_AVATAR_URL_EXPLAIN'		=> 'Wpisz adres WWW pliku z obrazkiem, a avatar zostanie skopiowany na to forum.',
	'USERNAME_ALPHA_ONLY_EXPLAIN'	=> 'Login musi mieć min. %1$d, a maks. %2$d znaków i składać się tylko z liter alfabetu łacińskiego oraz cyfr.',
	'USERNAME_ALPHA_SPACERS_EXPLAIN'=> 'Login musi mieć min. %1$d, a maks. %2$d znaków i składać się tylko z liter alfabetu łacińskiego, cyfr, spacji oraz  „_”, „+”, „-”, „[” i „]”.',
	'USERNAME_ASCII_EXPLAIN'		=> 'Login musi mieć min. %1$d, a maks. %2$d znaków i składać się tylko ze znaków ASCII.',
	'USERNAME_LETTER_NUM_EXPLAIN'	=> 'Login musi mieć min. %1$d, a maks. %2$d znaków i składać się tylko z liter oraz cyfr.',
	'USERNAME_LETTER_NUM_SPACERS_EXPLAIN'=> 'Login musi mieć min. %1$d, a maks. %2$d znaków i składać się tylko z liter, cyfr, spacji oraz  „_”, „+”, „-”, „[” i „]”.',
	'USERNAME_CHARS_ANY_EXPLAIN'	=> 'Login musi mieć min. %1$d, a maks. %2$d znaków.',
	'USERNAME_TAKEN_USERNAME'		=> 'Nazwa użytkownika, którą wpisałeś, jest już zajęta. Wybierz inną.',
	'USERNAME_DISALLOWED_USERNAME'	=> 'Nazwa użytkownika, którą wpisałeś, została zabroniona lub zawiera zabronione słowo. Wybierz inną.',
	'USER_NOT_FOUND_OR_INACTIVE'	=> 'Loginy, które wpisałeś, nie zostały znalezione lub należą do nieaktywnych użytkowników.',

	'VIEW_AVATARS'				=> 'Pokazuj avatary',
	'VIEW_EDIT'					=> 'Pokaż/Edytuj',
	'VIEW_FLASH'				=> 'Pokazuj animacje Flash',
	'VIEW_IMAGES'				=> 'Pokazuj obrazki',
	'VIEW_NEXT_HISTORY'			=> 'Następna PW w historii',
	'VIEW_NEXT_PM'				=> 'Następna PW',
	'VIEW_PM'					=> 'Wiadomość',
	'VIEW_PM_INFO'				=> 'Szczegóły wiadomości',
	'VIEW_PM_MESSAGE'			=> '1 wiadomość',
	'VIEW_PM_MESSAGES'			=> '%d wiadomości',
	'VIEW_PREVIOUS_HISTORY'		=> 'Poprzednia PW w historii',
	'VIEW_PREVIOUS_PM'			=> 'Poprzednia PW',
	'VIEW_SIGS'					=> 'Wyświetlaj podpisy',
	'VIEW_SMILIES'				=> 'Wyświetlaj uśmieszki jako obrazki',
	'VIEW_TOPICS_DAYS'			=> 'Pokaż wątki z poprzednich dni',
	'VIEW_TOPICS_DIR'			=> 'Kierunek',
	'VIEW_TOPICS_KEY'			=> 'Posortuj wątki wg',
	'VIEW_POSTS_DAYS'			=> 'Pokaż posty z poprzednich dni',
	'VIEW_POSTS_DIR'			=> 'Kierunek',
	'VIEW_POSTS_KEY'			=> 'Posortuj posty wg',

	'WATCHED_EXPLAIN'			=> 'Poniżej znajduje się lista działów i wątków, które obserwujesz. Będziesz informowany o każdym nowym poście w nich napisanym. Aby przestać obserwować, zaznacz dział lub wątek i naciśnij przycisk <em>Przestań obserwować wybrane</em>.',
	'WATCHED_FORUMS'			=> 'Obserwowane działy',
	'WATCHED_TOPICS'			=> 'Obserwowane wątki',
	'WRONG_ACTIVATION'			=> 'Klucz aktywacji, który dostarczyłeś/aś, jest nieprawidłowy.',

	'YOUR_DETAILS'				=> 'Twoja aktywność',
	'YOUR_FOES'					=> 'Twoi wrogowie',
	'YOUR_FOES_EXPLAIN'			=> 'Aby usunąć użytkowników z tej listy, wybierz ich i kliknij na „Wyślij”.',
	'YOUR_FRIENDS'				=> 'Twoi przyjaciele',
	'YOUR_FRIENDS_EXPLAIN'		=> 'Aby usunąć użytkowników z tej listy, wybierz ich i kliknij na „Wyślij”.',
	'YOUR_WARNINGS'				=> 'Liczba Twoich ostrzeżeń',

	'PM_ACTION'					=> array(
		'PLACE_INTO_FOLDER'	=> 'Przenieś do folderu',
		'MARK_AS_READ'		=> 'Oznacz jako przeczytane',
		'MARK_AS_IMPORTANT'	=> 'Oznacz wiadomość',
		'DELETE_MESSAGE'	=> 'Usuń wiadomość'
	),
	'PM_CHECK'					=> array(
		'SUBJECT'			=> 'Tytuł',
		'SENDER'			=> 'Nadawca',
		'MESSAGE'			=> 'Wiadomość',
		'STATUS'			=> 'Status wiadomości',
		'TO'				=> 'Wysłano do'
	),
	'PM_RULE'					=> array(
		'IS_LIKE'			=> 'jest jak',
		'IS_NOT_LIKE'		=> 'nie jest jak',
		'IS'				=> 'jest',
		'IS_NOT'			=> 'nie jest',
		'BEGINS_WITH'		=> 'zaczyna się od',
		'ENDS_WITH'			=> 'kończy się na',
		'IS_FRIEND'			=> 'jest przyjacielem',
		'IS_FOE'			=> 'jest wrogiem',
		'IS_USER'			=> 'jest użytkownikiem',
		'IS_GROUP'			=> 'jest w grupie',
		'ANSWERED'			=> 'odpowiedziane',
		'FORWARDED'			=> 'przesłane dalej',
		'TO_GROUP'			=> 'grupy użytkowników',
		'TO_ME'				=> 'mnie'
	),


	'GROUPS_EXPLAIN'			=> 'Grupy użytkowników pozwalają administratorom forum lepiej zarządzać użytkownikami. Domyślnie trafisz do specyficznej grupy, to jest Twoja domyślna grupa. Ta grupa ustala jak będziesz widoczny dla innnych użytkowników, (np. kolor loginu, avatar, ranga itp.) Możliwe, że administrator pozwala Ci zmienić domyślną grupę. Możesz również przyłączyć się lub zostać przypisany do innych grup. Niektóre grupy dadzą Ci specjalne prawa do oglądania zawartości lub powiększą Twoje możliwości w innych miejscach.',
	'GROUP_LEADER'				=> 'Jesteś liderem:',
	'GROUP_MEMBER'				=> 'Należysz do:',
	'GROUP_PENDING'				=> 'Oczekujesz na przyjęcie do:',
	'GROUP_NONMEMBER'			=> 'Nie należysz do:',
	'GROUP_DETAILS'				=> 'Szczegóły grupy',

	'NO_LEADER'					=> 'Nie jesteś liderem żadnej grupy.',
	'NO_MEMBER'					=> 'Nie należysz do żadnej grupy.',
	'NO_PENDING'				=> 'Nie oczekujesz na przyjęcie do żadnej grupy.',
	'NO_NONMEMBER'				=> 'Nie istnieje żadna grupa, do której nie należysz.',
));


// BEGIN mChat Mod
$lang = array_merge($lang, array(
	'UCP_CAT_MCHAT'		=> 'mChat',
	'UCP_MCHAT_CONFIG'	=> 'Ustawienia',
));
// END mChat Mod?>