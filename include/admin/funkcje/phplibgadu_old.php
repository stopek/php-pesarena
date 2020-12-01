<?php

/**
 * Klasa do obslugi protokolu gadu-gadu.
 * Obsluguje wersje 8.0 i 7.7
 * Orginalna wersja znajduje sie pod adresem http://lgpl.nastal.pl/rfGG.class.zip
 * @author Rafa� Awier <lgpl@nastal.pl>
 * @copyright NaStaL-IT 2006 - 2009, Rafa� Awier (ravciu)
 * @version 1.0.20090826
 *
 * NIEZAPOZNANIE SIE Z LICENCJA NIE ZWALANIA ZE STOSOWANIA SIE DO JEJ ZASAD.
 * @license
 * Biblioteka oparta jest o licencje Wolnego Oprogramowanie LGPL z NIEWIELKIMI ZASTRZEZENIAMI.
 * 1. Kazda kopia tego pliku musi zawierac powyzsze informacje odnosnie autora: NaStaL-IT, Rafa� Awier (ravciu), copyright
 *        wersji pliku oraz opis tej licencji w formie niezmienionej.
 * 2. Jesli kod zostanie zmodyfikowany, NALEZY DODAC autora modyfikacji nie usuwajac wczesniejszych wpisow
 *        oraz NALEZY DODAC wersje modyfikacji zachowujac numer wersji podstwowej np. @version 1.0.20090826.3
 *        (oznacza 3 modyfikacje pliku 1.0.20090826)
 * 3. Opis ponizej POWINIEN zawierac dodane zmiany w pliku oraz sposob ich uzycia
 * 4. Kategorycznie zabrania sie wykorzystywania kodu do rozsylania SPAMU oraz niechcianych wiadomosci.
 * 5. Odbiorca wiadomosci musi wyrazic wczesniej zgode na ich otrzymywanie.
 * 6. Zabrania sie wykorzystywania kodu do celow niezgodnych z prawem POLSKIM.
 * 7. Na uzasadniona prosbe serwisu Gadu-Gadu nalezy usunac lub wylaczyc dzialanie kodu.
 * 8. Do licencji tej mozna dodac wlasne wpisy, ale nie mozna modyfikowac lub usuwac wpisow juz istniejacych
 *
 * WYKORZYSTANIE KODU JEST JEDNOZNACZNE Z AKCEPTACJA POWYZSZEJ LICENCJI.
 */

/**
 * Jesli polaczenie nie jest nawiazanie wszystkie funkcjie zwracaja null
 * Korzsytajac z funkcji getError() mozna odczytac blad wykonania operacji (w formie tekstowej)
 * Wiadomosci mozna pobrac w dowolnym momencie (rowniez po zamknieciu polaczenia).
 * Pojedyncza najstarsza za pomoca getMessageOne() oraz wszystkie na raz za pomoca getMessages().
 * Pobrane wiadomosci sa usuwane z kolejki.
 *
 * Mozna dodac wlasny handler dla odebranych wiadomosci, za pomoca funckji set_message_handler()
 * Jako parametr podajemy funkcje do wywolania. Dopuszczalna forma:
 * set_message_handler('nazwa_funkcji');
 * set_message_handler('klasa::nazwa_funkcji_statycznej');
 * set_message_handler(array($objekt,'nazwa_metody'));
 *
 * Najlepiej ustawic handler zaraz po stworzeniu obiektu. W inny przypadku niektore wiadomosci moga
 * zostac juz pominiete przez handler. Jesli hendler zwroci dana wiadomosc, wowczas bedzie dodana do kolejki
 * i mozna ja pobrac ponownie pozniej. Jesli zworci false lub null, wiadomosc nie zostanie zakolejkowana.
 *
 * @example:
 * $GG = new rfGG(rfGG::VER_77); // brak paramteru oznacza VER_80
 * $GG->set_message_handler('otrzymalem_wiadomosc');
 * if ($GG->connect($twoj_nr_gg, $haslo)) {
 *        if (!$GG->sendMessage($nr_gg_odbiorcy,"Dowolna tresc w html <b>pogrobienie</b>, <i>kursywa</i>, <u>podkreslenie</u>\r\n lub <br> jako nowa linia")) {
 *            die($GG->getError());
 *        }
 *        if (!$GG->changeStatus(rfGG::STATUS_BUSY_DESCR,'Opis opcjonalnie')) {
 *            die($GG->getError());
 *        }
 *        $GG->ping();
 *        $message = $GG->getMessageOne();
 *        $GG->disconnect('Opcjonalnie opis');
 *        $messages = $GG->getMessages();
 * }
 */
class rfGG
{
    const PONG = 0x0007; //Pong
    const PING = 0x0008; //Ping

    const DCC7_INFO = 0x001f;
    const DCC7_NEW = 0x0020; //Informacje o ch�ci nawi�zania po��czenia DCC
    const DCC7_ACCEPT = 0x0021; //Zaakceptowanie po��czenia DCC
    const DCC7_REJECT = 0x0022; //Odrzucenie po��czenia DCC
    const DCC7_ID_REPLY = 0x0023;
    const DCC7_ID_REQUEST = 0x0023;
    const DCC7_DUNNO1 = 0x0024;
    const DCC7_ABORTED = 0x0025;
    const DCC7_ABORT = 0x0025;

    /**
     * Pakiety wysylane
     */
    const LOGIN = 0x000c; //Logowanie przed GG 6.0
    const LOGIN_EXT = 0x0013; //Logowanie przed GG 6.0
    const LOGIN60 = 0x0015; //Logowanie przed GG 7.7
    const LOGIN70 = 0x0019; //Logowanie przed GG 8.0
    const LOGIN80 = 0x0031; //Logowanie

    const NEW_STATUS = 0x0002; //Zmiana stanu przed GG 8.0
    const NEW_STATUS80BETA = 0x0028; //Zmiana stanu przed Nowym Gadu-Gadu
    const NEW_STATUS80 = 0x0038; //Zmiana stanu

    const SEND_MSG = 0x000b; //Wys�anie wiadomo�ci przed GG 8.0
    const SEND_MSG80 = 0x002d; //Wys�anie wiadomo�ci

    const ADD_NOTIFY = 0x000d; //Dodanie do listy kontakt�w
    const REMOVE_NOTIFY = 0x000e; //Usuni�cie z listy kontakt�w
    const NOTIFY_FIRST = 0x000f; //Pocz�tkowy fragment listy kontakt�w wi�kszej ni� 400 wpis�w
    const NOTIFY_LAST = 0x0010; //Ostatni fragment listy kontakt�w
    const LIST_EMPTY = 0x0012; //Lista kontakt�w jest pusta

    const PUBDIR50_REQUEST = 0x0014; //Zapytanie katalogu publicznego
    const USERLIST_REQUEST = 0x0016; //Zapytanie listy kontakt�w na serwerze przed Nowym Gadu-Gadu
    const USERLIST_REQUEST80 = 0x002f; //Zapytanie listy kontakt�w na serwerze

    /**
     * Pakiety odbierane
     */
    const WELCOME = 0x0001; //Liczba do wyznaczenie hashu has�a
    const LOGIN_OK = 0x0003; //Logowanie powiod�o si� przed Nowym Gadu-Gadu
    const LOGIN_FAILED = 0x0009; //Logowanie nie powiod�o si�
    const LOGIN_OK80 = 0x0035; //Logowanie powiod�o si�
    const LOGIN_HASH_TYPE_INVALID = 0x0016; //Dany rodzaj hashowania has�a jest nieobs�ugiwany przez serwer
    const NEED_EMAIL = 0x0014; //Logowanie powiod�o si�, ale powinni�my uzupe�ni� adres e-mail w katalogu publicznym

    const STATUS = 0x0002; //Zmiana stanu przed GG 6.0
    const STATUS60 = 0x000f; //Zmiana stanu przed GG 7.7
    const STATUS77 = 0x0017; //Zmiana stanu przed GG 8.0
    const STATUS80BETA = 0x002a; //Zmiana stanu przed Nowym Gadu-Gadu
    const STATUS80 = 0x0036; //Zmiana stanu

    const SEND_MSG_ACK = 0x0005; //Potwierdzenie wiadomo�ci

    const RECV_MSG = 0x000a; //Przychodz�ca wiadomo�� przed GG 8.0
    const RECV_MSG80 = 0x002e; //Przychodz�ca wiadomo��
    const XML_EVENT = 0x0027; //Odebrano wiadomo�� systemow�

    const DISCONNECTING = 0x000b; //Zerwanie po��czenia
    const DISCONNECT_ACK = 0x000d; //Zerwanie po��czenia po zmianie stanu na niedost�pny

    const NOTIFY_REPLY = 0x000c; //Stan listy kontakt�w przed GG 6.0
    const NOTIFY_REPLY60 = 0x0011; //Stan listy kontakt�w przed GG 7.7
    const NOTIFY_REPLY77 = 0x0018; //Stan listy kontakt�w przed GG 8.0
    const NOTIFY_REPLY80BETA = 0x002b; //Stan listy kontakt�w przed Nowym Gadu-Gadu
    const NOTIFY_REPLY80 = 0x0037; //Stan listy kontakt�w

    const PUBDIR50_REPLY = 0x000e; //Odpowied� katalogu publicznego
    const USERLIST_REPLY = 0x0010; //Odpowied� listy kontakt�w na serwerze przed nowym Gadu-Gadu
    const USERLIST_REPLY80 = 0x0030; //Odpowied� listy kontakt�w na serwerze
    const XML_ACTION = 0x002c;

    /**
     * Dostepne statusy
     */
    const STATUS_NOT_AVAIL = 0x0001;
    const STATUS_NOT_AVIAL_DESCR = 0x0015;
    const STATUS_AVAILABLE = 0x0002;
    const STATUS_AVAILABLE_DESCR = 0x0004;
    const STATUS_BUSY = 0x0003;
    const STATUS_BUSY_DESCR = 0x0005;
    const STATUS_INVISIBLE = 0x0014;
    const STATUS_INVISIBLE_DESCR = 0x0016;


    const CLASS_MSG = 0x0004; // wiadomosc ma sie pojawic w
    const CLASS_CHAT = 0x0008;

    const ACK_BLOCKED = 0x0001;
    const ACK_DELIVERED = 0x0002;
    const ACK_QUEUED = 0x0003;
    const ACK_MBOXFULL = 0x0004;
    const ACK_NOT_DELIVERED = 0x0004;

    const FONT_BOLD = 0x01;
    const FONT_ITALIC = 0x02;
    const FONT_UNDERLINE = 0x04;
    const FONT_COLOR = 0x08;
    const FONT_IMAGE = 0x80;

    const PROTOCOL_STAN77 = 0x00; // Rodzaj pakietu informuj�cego o zmianie stanu kontakt�w GG_STATUS77, GG_NOTIFY_REPLY77
    const PROTOCOL_STAN80BETA = 0x01; // GG_STATUS80BETA, GG_NOTIFY_REPLY80BETA
    const PROTOCOL_STAN80 = 0x05; // GG_STATUS80, GG_NOTIFY_REPLY80
    const PROTOCOL_RECV80 = 0x02; // Rodzaj pakietu z otrzymaj� wiadomo�ci� wy�: GG_RECV_MSG, w�: GG_RECV_MSG80

    const CONN_TIMEOUT = 5; // Dla findServer
    const READ_TIMEOUT = 4;

    const VER_80 = 0xff;
    const VER_77 = 0x2a; // build 3315
//	const VER_76 	= 0x29; // build 1688
//	const VER_75 	= 0x28; // build 2201
//	const VER_70 	= 0x27; // build 22
//	const VER_61 	= 0x24; // build 155
//	const VER_60 	= 0x22; // build 140
//	const VER_57 	= 0x1e; // build 121

    public static $verTxt = array(
        self::VER_80 => '8.0.0.7669',
        self::VER_77 => '7.7.0.3315',
//		self::VER_76 => '7.6.0.1688', 
//		self::VER_75 => '7.5.0.2201', 
//		self::VER_70 => '7.0.0.22', 
//		self::VER_61 => '6.1.0.155', 
//		self::VER_60 => '6.0.0.140', 
//		self::VER_57 => '5.7.0.121', 
    );
    protected static $ggServer = array('217.17.41.88', '217.17.41.83', '217.17.41.84', '217.17.41.85');

    protected $hSocket = null;
    protected $ver = null;

    protected $error_msg = '';
    protected $error_connecting = null;
    protected $messages = array();
    protected $msg_handler = null;

    public function __construct($ver = null)
    {
        $this->ver = $ver ? $ver : self::VER_80;
    }

    /**
     * Ustawia handler dla odebranych wiadomosc
     * @param $functionName string Istniejaca funkcja
     * @return string Poprzednia funkcja
     */
    public function set_message_handler($functionName)
    {
        $result = $this->msg_handler;
        $this->msg_handler = $functionName;
        return $result;
    }

    /**
     * Zwraca nazwe bledu
     * @return string
     */
    public function getError()
    {
        return $this->error_connecting ? $this->error_connecting : $this->error_msg;
    }

    /**
     * Pobiera jedna wiadomosc (w kolejnosci) i usuwa ja ze stosu
     * Jesli nie ma wiadomosci zwraca null
     * @return array
     */
    public function getMessageOne()
    {
        return array_shift($this->messages);
    }

    /**
     * Wszystkie wiadomosci i czysci liste
     * @return array
     */
    public function getMessages()
    {
        $result = $this->messages;
        $this->messages = array();
        return $result;
    }

    public function connect($uid, $password, $description = null, $status = self::STATUS_AVAILABLE)
    {

        if ($description) {
            $statusDescription = iconv('utf-8', 'cp1250', $statusDescription);
        }

        if (false === ($aServer = $this->_findServer($uid))) {
            $host = $this->ggServer[array_rand($ggServer)];
            $port = 8074;
        } else {
            $host = $aServer[0];
            $port = $aServer[1];
        }

        if ($this->hSocket = fsockopen($host, $port, &$errorNumber, &$errorString, self::CONN_TIMEOUT)) {
            if (!stream_set_timeout($this->hSocket, self::READ_TIMEOUT, 0)) {
                $this->error_msg = "Can't set socket";
                return false;
            }
            if (!$data = $this->_readPacket()) {
                $this->error_msg = "Can't open socket";
                return false;
            }
            if ($data['type'] != self::WELCOME) {
                $this->error_msg = "Not welcome message";
                return false;
            }
            $seed = unpack('Vseed', $data['value']);

            return ($this->ver > self::VER_77)
                ? $this->_connect80($uid, $password, $seed['seed'], $status, $description)
                : $this->_connect77($uid, $password, $seed['seed'], $status, $description);
        }
        return false;
    }

    /**
     * Funkcja znajduje serwer do polaczen
     * @param $uid
     * @return array
     */
    protected function _findServer($uid)
    {
        if ($hSocket = fsockopen('appmsg.gadu-gadu.pl', 80, &$errorNumber, &$errorString, self::CONN_TIMEOUT)) {
            fputs($hSocket,
                "GET /appsvc/appmsg4.asp?fmnumber=" . $uid . "&fmt=2&lastmsg=0&version=" . self::$verTxt[$this->ver] . " HTTP/1.1\r\nHost: appmsg.gadu-gadu.pl\r\n" .
                "User-Agent: Mozilla/4.7 [en] (Win98; I)\r\nPragma: no-cache\r\n\r\n");
            $sData = '';
            while (($tmpData = fgets($hSocket, 128)) !== false) {
                $sData .= $tmpData;
            }
            fclose($hSocket);

            if (strstr('notoperating', $sData)) {
                return false;
            }
            if (!ereg('(([0-9]{1,3}\.){3}[0-9]{1,3})\:([0-9]{1,5})', $sData, $aRegs) || !ip2long($aRegs[1])) {
                return false;
            }
            return array($aRegs[1], $aRegs[3]);
        }
        return false;
    }

    /**
     * Odczytuje pakiet danych
     * @return array
     */
    protected function _readPacket()
    {
        $packet = fread($this->hSocket, 8);
        if (!strlen($packet)) {
            return false;
        }
        $packetData = unpack('Vtype/Vsize', $packet);
        if ($packetData['size'] > 0) {
            $packetData['value'] = fread($this->hSocket, $packetData['size']);
        }
        return $packetData;
    }

    /**
     * Polaczenie dla gg 80
     * @param $uid
     * @param $password
     * @param $seed
     * @param $status
     * @param $description
     * @return boolean
     */
    protected function _connect80($uid, $password, $seed, $status, $description)
    {
        if (!$this->_writePacket(self::LOGIN80, $this->_packLogin80($uid, $password, $seed, $status, $description))
            || !$data = $this->_read(self::LOGIN_OK80, true)) {
            $this->error_msg = "Login failure";
            return false;
        }
        if (!$this->_writePacket(self::LIST_EMPTY) || !$data = $this->_read(self::NOTIFY_REPLY80, true)) {
            $this->error_msg = "List failure";
            return false;
        }
        return true;
    }

    /**
     * Wysyla pakiet danych
     * @param $type integer
     * @param $packetData mixed
     * @return boolean
     */
    protected function _writePacket($type, $data = null)
    {
        $packetData = $data
            ? pack('VV', $type, strlen($data)) . $data
            : pack('VV', $type, 0);
        return (fwrite($this->hSocket, $packetData) == strlen($packetData));
    }

    /**
     * Pakiet logowanie
     * @param $uid
     * @param $password
     * @param $status
     * @param $seed
     * @return mixed
     */
    protected function _packLogin80($uid, $password, $seed, $status, $description = null)
    {
        if ($description) {
            switch ($status) {
                case self::STATUS_AVAILABLE:
                    $status = self::STATUS_AVAILABLE_DESCR;
                    break;
                case self::STATUS_BUSY:
                    $status = self::STATUS_BUSY_DESCR;
                    break;
                case self::STATUS_INVISIBLE:
                    $status = self::STATUS_INVISIBLE_DESCR;
            }
            return pack('Va2Ca64VVVVvVvCCVa33V' . 'a' . strlen($description)
                , $uid, 'pl', 1, $this->_loginHashGG32($password, $seed), $status, 0, self::PROTOCOL_STAN80 | self::PROTOCOL_RECV80, 0, 0, 0, 0, 0, 0x64, 0x21, 'Gadu-Gadu Client build ' . self::$verTxt[self::VER_80], strlen($description), $description
            );
        } else {
            return pack('Va2Ca64VVVVvVvCCVa33V'
                , $uid, 'pl', 1, $this->_loginHashGG32($password, $seed), $status, 0, self::PROTOCOL_STAN80 | self::PROTOCOL_RECV80, 0, 0, 0, 0, 0, 0x64, 0x21, 'Gadu-Gadu Client build ' . self::$verTxt[self::VER_80], 0
            );
        }
    }

    /**
     * Kodowanie hasla metoda GG32
     * @param $password
     * @param $seed
     * @return unknown_type
     */
    protected function _loginHashGG32($password, $seed)
    {
        $y = $seed;
        $x = 0;
        for ($nr = 0; $nr < strlen($password); $nr++) {
            $x = ($x & 0xFFFFFF00) | ord($password[$nr]);
            $y ^= $x;
            $y += $x;
            $x <<= 8;
            $y ^= $x;
            $x <<= 8;
            $y -= $x;
            $x <<= 8;
            $y ^= $x;

            $z = $y & 0x1F;
            $y1 = ($y << $z);
            if ($z < 32) {
                $y2 = $y >> 1;
                $y2 &= 0x7FFFFFFF;
                $y2 = $y2 >> (31 - $z);
            }
            $y = $y1 | $y2;
        }
        return pack('V', $y);
    }

    protected function _read($expect, $exactly = true)
    {
        $result = false;
        while ($data = $this->_readPacket()) {
            $data = $this->_handlePacket($data);
            if ($data['type'] == $expect) {
                return $data;
            }
            if ($data['type'] == self::DISCONNECTING) {
                fclose($this->hSocket);
                $this->hSocket = null;
                $this->error_connecting = 'Disconnecting';
                return null;
            }
        }
        return (!$exactly && $data);
    }

    /**
     * Obsluga odebranych wiadomosci
     * @param $packet array Odebrany pakiet
     * @return array Pakiet po przetworzeniu
     */
    protected function _handlePacket($packet)
    {
        if ($packet['size'] == 0) {
            return $packet;
        }
        $result = array();
        switch ($packet['type']) {
            case self::SEND_MSG_ACK:
                $result = unpack('Vstatus/Vrecipient/Vseq', $packet['value']);
                break;
            case self::RECV_MSG:
                $result = unpack('Vsender/Vseq/Vtime/Vclass/a' . (strlen($packet['value']) - 16) . 'msg_text', $packet['value']);
                $result['msg_attr'] = preg_replace('#^[^\x0]+#', '', $result['msg_text']);
                $result['msg_text'] = preg_replace('#\x0.+$#', '', $result['msg_text']);
                $this->_addMessage($result);
                break;
            case self::RECV_MSG80:
                $result = unpack('Vsender/Vseq/Vtime/Vclass/Voffset_plain/Voffset_attr/a' . (strlen($packet['value']) - 24) . 'tmp', $packet['value']);
                if ($result['offset_plain'] > 24) {
                    $tmp = unpack('a' . ($result['offset_plain'] - 24) . 'html/a' . ($result['offset_attr'] - $result['offset_plain']) . 'text/a' . (strlen($packet['value']) - $result['offset_attr']) . 'attr', $result['tmp']);
                    $result['msg_html'] = $tmp['html'];
                } else {
                    $tmp = unpack('a' . ($result['offset_attr'] - $result['offset_plain']) . 'text/a' . (strlen($packet['value']) - $result['offset_attr'] - 3) . 'attr', $result['tmp']);
                }
                $result['msg_text'] = $tmp['text'];
                $result['msg_attr'] = $tmp['attr'];
                unset($result['tmp']);
                $this->_addMessage($result);
                break;
            case self::NOTIFY_REPLY:
            case self::NOTIFY_REPLY60:
                $result = unpack('Vuin/Cstatus/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']);
                break;
            case self::NOTIFY_REPLY77:
                $result = unpack('Vuin/Cstatus/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']);
                break;
            case self::NOTIFY_REPLY80BETA:
            case self::NOTIFY_REPLY80:
                $result = unpack('Vuin/Vstatus/Vflags/Vremote_ip/vremote_port/Cversion/Cimage_size', $packet['value']);
                break;
            case self::PUBDIR50_REPLY:
                $tmp = explode("\x00", substr($packet['value'], 5));
                $result = array();
                for ($nr = 0, $cnt = sizeOf($tmp) - 5; $nr < $cnt; $nr += 2)
                    $result[$tmp[$nr]] = $tmp[$nr + 1];
                break;
            default:
                $result['value'] = $packet['value'];
        }

        return array_merge(
            array('type' => $packet['type'], 'size' => $packet['size']),
            $result
        );
    }

    /**
     * Dodaje wiadomosc do listy
     * @param $message
     * @return unknown_type
     */
    protected function _addMessage($message)
    {
        if (isset($message['offset_plain'])) unset($message['offset_plain']);
        if (isset($message['offset_attr'])) unset($message['offset_attr']);
        if (isset($message['class'])) unset($message['class']);
        if (isset($message['size'])) unset($message['size']);
        if (isset($message['type'])) unset($message['type']);
        if (isset($message['msg_text'])) {
            $message['msg_text'] = iconv('cp1250', 'utf-8', $message['msg_text']);
        }
        if ($this->msg_handler) {
            $message = call_user_func_array($this->msg_handler, array($message));
            if (!$message) {
                return;
            }
        }
        array_push($this->messages, $message);
    }

    /**
     * Polaczenie dla gg 77 i starszych
     * @param $uid
     * @param $password
     * @param $seed
     * @param $status
     * @param $description
     * @return boolean
     */
    protected function _connect77($uid, $password, $seed, $status, $description)
    {
        if (!$this->_writePacket(self::LOGIN70, $this->_packLogin70($uid, $password, $seed, $status, $description))
            || !$data = $this->_read(self::LOGIN_OK, true)) {
            $this->error_msg = "Login failure";
            return false;
        }
        if (!$this->_writePacket(self::LIST_EMPTY) || !$data = $this->_read(self::NOTIFY_REPLY77, true)) {
            $this->error_msg = "List failure";
            return false;
        }
        return true;
    }

    /**
     * Pakiet logowanie
     * @param $uid
     * @param $password
     * @param $status
     * @param $seed
     * @return mixed
     */
    protected function _packLogin70($uid, $password, $seed, $status, $description = null)
    {
        if ($description) {
            switch ($status) {
                case self::STATUS_AVAILABLE:
                    $status = self::STATUS_AVAILABLE_DESCR;
                    break;
                case self::STATUS_BUSY:
                    $status = self::STATUS_BUSY_DESCR;
                    break;
                case self::STATUS_INVISIBLE:
                    $status = self::STATUS_INVISIBLE_DESCR;
            }
            return pack('VCa64VVCVvVvCC' . 'a' . strlen($description) . 'C'
                , $uid, 1, $this->_loginHashGG32($password, $seed), $status, $this->ver, 0, 0, 0, 0, 0, 0, 0xBE, $description, 0
            );
        } else {
            return pack('VCa64VVCVvVvCC'
                , $uid, 1, $this->_loginHashGG32($password, $seed), $status, $this->ver, 0, 0, 0, 0, 0, 0, 0xBE
            );
        }
    }

    /**
     * Wyslanie wiadmosci
     * @param $recipient
     * @param $message
     * @param $html
     * @return boolean
     */
    public function sendMessage($recipient, $message, $html = true)
    {
        if (!$this->hSocket) {
            $this->error_connecting = 'Not connecting';
            return false;
        }
        $message = preg_replace('#<br\s*/{0,1}>#i', "\r\n", $message);
        $message = strip_tags($message, '<i><b><u><c>');
        //$message = strtr($message, "\xA1\xA6\xAC\xB1\xB6\xBC", "\xA5\x8C\x8F\xB9\x9C\x9F");

        $mSeq = time() + rand(1, 999);
        $result = ($this->ver <= self::VER_77)
            ? $this->_sendMessage77($recipient, $message, $mSeq)
            : $this->_sendMessage80($recipient, $message, $mSeq);
        if (!$result || !$data = $this->_read(self::SEND_MSG_ACK)) {
            $this->error_msg = "Can't send message";
            return false;
        }
        if ($data['recipient'] != $recipient || $data['seq'] != $mSeq) {
            $this->error_msg = "This is not answer (" . $data['seq'] . ") on my message $mSeq";
            return false;
        }
        return true;
    }

    /**
     * Wyslanie wiadmosci
     * @param $recipient integer numer gg odbiorcy
     * @param $message string tresc max 2000 znakow
     * @param $mSeq integer unikalny numer
     * @param $html boolean jesli chcemy wyslac w formacie plaintext (
     * @return boolean
     */
    protected function _sendMessage77($recipient, $message, $mSeq, $html = true)
    {
        $message = iconv('utf-8', 'cp1250', $message);
        if ($html && $fontFormat = $this->_fontFormat($message)) {
            $message = strip_tags($message);
            $msgPacket = pack('VVVa' . strlen($message) . 'CCv', $recipient, $mSeq, self::CLASS_CHAT, $message, 0, 0x02, strlen($fontFormat))
                . $fontFormat;
        } else {
            $msgPacket = pack('VVVa' . strlen($message) . 'C', $recipient, $mSeq, self::CLASS_CHAT, $message, 0);
        }
        return $this->_writePacket(self::SEND_MSG, $msgPacket);
    }

    /**
     * Analiza czcionki
     * @param $string
     * @return string
     */
    protected function _fontFormat($string)
    {

        $string = strtoupper($string);

        $fontFormatData = array(
            'B' => self::FONT_BOLD, // 1
            'I' => self::FONT_ITALIC, // 2
            'U' => self::FONT_UNDERLINE, // 4
            'C' => self::FONT_COLOR // 8
        );

        if (!preg_match_all("'\<(.*?)\>'", $string, $aRegs, PREG_OFFSET_CAPTURE)) {
            return false;
        }
        $fontData = $aRegs[0];
        $fontFormat = array();

        $a1 = 0;
        $a2 = array();
        $a3 = array();
        $b = 0;
        $d = 0;
        $cColor = pack('CCC', 0, 0, 0);
        $lastColor = array();
        $indexColor = 0;

        for ($nr = 0, $len = sizeOf($fontData); $nr < $len; $nr++) {
            $cFontData = $fontData[$nr];
            $currentPos = $cFontData[1] - $d;
            if ($cFontData[0][1] != '/') {
                $lastColor[$indexColor++] = $cColor;
                if (strlen($cFontData[0]) > 3) {
                    $cColor = pack('CCC', hexdec(substr($cFontData[0], 17, 2)), hexdec(substr($cFontData[0], 19, 2)), hexdec(substr($cFontData[0], 21, 2)));
                }
                $a1 |= $fontFormatData[$cFontData[0][1]];
                if (strlen($cColor)) {
                    $a1 |= self::FONT_COLOR;
                    $a3[$currentPos] = $cColor;
                }
                if (!isset($fontFormat[$currentPos])) {
                    $fontFormat[$currentPos] = $a1;
                } else {
                    $fontFormat[$currentPos] = $fontFormat[$currentPos] | $a1;
                }
                $d += strlen($cFontData[0]);
            } else {
                $c = $fontFormatData[$cFontData[0][2]];
                if ($c != self::FONT_COLOR) {
                    $a1 ^= $c;
                }

                $cColor = $lastColor[--$indexColor];
                unset($lastColor[$indexColor]);
                if (strlen($cColor)) {
                    $a3[$currentPos] = $cColor;
                }
                if (!isset($fontFormat[$currentPos])) {
                    $fontFormat[$currentPos] = $a1;
                } else {
                    $fontFormat[$currentPos] = $fontFormat[$currentPos] | $a1;
                }

                $d += strlen($cFontData[0]);
            }
        }

        $b = '';
        foreach ($fontFormat as $k => $v) {
            $b .= pack('vC', $k, $v);
            if (isset($a3[$k])) $b .= $a3[$k];
        }

        return $b;
    }

    /**
     * Wyslanie wiadmosci w wersji 80 i nowszej
     * @param $recipient integer numer gg odbiorcy
     * @param $message string tresc max 2000 znakow
     * @param $mSeq integer unikalny numer
     * @return boolean
     */
    protected function _sendMessage80($recipient, $message, $mSeq)
    {
        $plainText = iconv('utf-8', 'cp1250', $message);
        if (!$fontFormat = $this->_fontFormat($plainText)) {
            $fontFormat = pack('vCCCC', 0, self::FONT_COLOR, 0, 0, 0);
        }
        $plainText = strip_tags($plainText);
        $msgPacket = pack('VVVVVa' . strlen($message) . 'Ca' . strlen($plainText) . 'CCv', $recipient, $mSeq, self::CLASS_CHAT, 21 + strlen($message), 22 + strlen($message) + strlen($plainText), $message, 0, $plainText, 0, 0x02, strlen($fontFormat)) . $fontFormat;
        return $this->_writePacket(self::SEND_MSG80, $msgPacket);
    }

    /**
     * Wysyla ping (poniwaz serwer nie zawsze odpowiada daltego funkcja zawsze zwroci TRUE)
     * Jesli serwer nie odpoie funkcja bedzie czekac przez chwile (5 sek jesli nie zostalo to zmienione)
     * Funkce mozna wykorzystac do pobrania wiadomosci lub sprawdzenia czy nie ma jakies wiadomosci do odbioru
     * @return boolean
     */
    public function ping()
    {
        if (!$this->hSocket) {
            $this->error_connecting = 'Not connecting';
            return false;
        }
        $this->_writePacket(self::PING);
        $this->_read(self::PING);
        return true;
    }

    /**
     * Wysyla pong (poniwaz serwer nie zawsze odpowiada daltego funkcja zawsze zwroci TRUE)
     * Jesli serwer nie odpoie funkcja bedzie czekac przez chwile (5 sek jesli nie zostalo to zmienione)
     * @return boolean
     */
    public function pong()
    {
        if (!$this->hSocket) {
            $this->error_connecting = 'Not connecting';
            return false;
        }
        $this->_writePacket(self::PONG);
        $this->_read(self::PONG);
        return true;
    }

    /**
     * Zamyka polaczenie
     * Przed zamknieciem zmienia status na niedostepny (ewentualnie z opisem)
     * i sprawdza czy nie czekaja jeszcze jakies wiadomosci w sokecie
     * @param $statusDescription string
     */
    function disconnect($statusDescription = null)
    {
        $this->changeStatus(self::STATUS_NOT_AVAIL, $statusDescription);
        $this->_read(self::DISCONNECT_ACK);
        fclose($this->hSocket);
        $this->hSocket = null;
    }

    /**
     * Zmiana statusu
     * @param $status integer
     * @param $statusDescription string
     * @return boolean
     */
    public function changeStatus($status, $statusDescription = null)
    {
        if (!$this->hSocket) {
            $this->error_connecting = 'Not connecting';
            return false;
        }
        if ($statusDescription !== null) {
            switch ($status) {
                case self::STATUS_AVAILABLE:
                    $status = self::STATUS_AVAILABLE_DESCR;
                    break;
                case self::STATUS_BUSY:
                    $status = self::STATUS_BUSY_DESCR;
                    break;
                case self::STATUS_INVISIBLE:
                    $status = self::STATUS_INVISIBLE_DESCR;
                    break;
                case self::STATUS_NOT_AVAIL:
                    $status = self::STATUS_NOT_AVIAL_DESCR;
                    break;
            }
            $statusDescription = iconv('utf-8', 'cp1250', $statusDescription);
        }
        $result = ($this->ver > self::VER_77)
            ? $this->_changeStatus80($status, $statusDescription)
            : $this->_changeStatus77($status, $statusDescription);
        if (!$result) {
            $this->error_msg = "Can't send change status";
        }
        return $result;
    }

    /**
     * Zmiana statusu wersja 80 i nowsza
     * @param $status integer
     * @param $statusDescription string
     * @return boolean
     */
    protected function _changeStatus80($status, $statusDescription = null)
    {
        if ($statusDescription !== null) {
            $packStatus = pack('VVVa' . strlen($statusDescription), $status, 0, strlen($statusDescription), $statusDescription);
        } else {
            $packStatus = pack('VVV', $status, 0, 0);
        }
        return $this->_writePacket(self::NEW_STATUS80, $packStatus);
    }

    /**
     * Zmiana statusu wersja starsza niz 80
     * @param $status integer
     * @param $statusDescription string
     * @return boolean
     */
    protected function _changeStatus77($status, $statusDescription = null)
    {
        if ($statusDescription !== null) {
            $packStatus = pack('Va' . strlen($statusDescription) . 'C', $status, $statusDescription, 0);
        } else {
            $packStatus = pack('VC', $status, 0);
        }
        return $this->_writePacket(self::NEW_STATUS, $packStatus);
    }

}

?>