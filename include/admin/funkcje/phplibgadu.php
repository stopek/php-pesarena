<?php
/*
 * (c) Copyright 2009 Piotr Latosiński <lato_p@o2.pl>
 *
 * Ten plik jest częścią phplibgadu.
 * 
 * phplibgadu jest wolnym oprogramowaniem; możesz go rozprowadzać dalej
 * i/lub modyfikować na warunkach Mniejszej Licencji Publicznej GNU,
 * wydanej przez Fundację Wolnego Oprogramowania - według wersji 2.1 tej
 * Licencji lub (według twojego wyboru) którejś z późniejszych wersji.
 * 
 * Niniejszy program rozpowszechniany jest z nadzieją, iż będzie on
 * użyteczny - jednak BEZ JAKIEJKOLWIEK GWARANCJI, nawet domyślnej
 * gwarancji PRZYDATNOŚCI HANDLOWEJ albo PRZYDATNOŚCI DO OKREŚLONYCH
 * ZASTOSOWAŃ. W celu uzyskania bliższych informacji sięgnij do
 * Mniejszej Licencji Publicznej GNU.
 * 
 * Z pewnością wraz z niniejszym programem otrzymałeś też egzemplarz
 * Mniejszej Licencji Publicznej GNU (GNU Lesser Public License);
 * jeśli nie - napisz do Free Software Foundation, Inc., 59 Temple
 * Place, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * phplibgadu.php
 */

define('LF', "\n");

require_once 'packets.php';

// {{{ functions
if (!function_exists('dec2hex')) {
    function dec2hex($number, $length = 2)
    {
        $number = dechex($number);
        if (strlen($number) < $length) {
            $number = str_repeat('0', $length - strlen($number)) . $number;
        }
        return $number;
    }
}

if (!function_exists('iso2win')) {
    function iso2win($text)
    {
        $iso2win = array();
        $iso2win["\xB1"] = "\xB9"; // ą
        $iso2win["\xB6"] = "\x9C"; // ś
        $iso2win["\xBC"] = "\x9F"; // ź
        $iso2win["\xA1"] = "\xA5"; // Ą
        $iso2win["\xA6"] = "\x8C"; // Ś
        $iso2win["\xAC"] = "\x8F"; // Ź
        return strtr($text, $iso2win);
    }
}

if (!function_exists('win2iso')) {
    function win2iso($text)
    {
        $win2iso = array();
        $win2iso["\xB9"] = "\xB1"; // ą
        $win2iso["\x9C"] = "\xB6"; // ś
        $win2iso["\x9F"] = "\xBC"; // ź
        $win2iso["\xA5"] = "\xA1"; // Ą
        $win2iso["\x8C"] = "\xA6"; // Ś
        $win2iso["\x8F"] = "\xAC"; // Ź
        return strtr($text, $win2iso);
    }
}

if (!function_exists('utf2win')) {
    function utf2win($text)
    {
        return iso2win(mb_convert_encoding($text, 'ISO-8859-2', 'UTF-8'));
    }
}

if (!function_exists('win2utf')) {
    function win2utf($text)
    {
        return mb_convert_encoding(win2iso($text), 'UTF-8', 'ISO-8859-2');
    }
}

// }}}

class GG
{
    // {{{ constants
    const WELCOME = 0x01;
    const STATUS = 0x02;
    const LOGIN_OK = 0x03;
    const SEND_MSG_ACK = 0x05;
    const PONG = 0x07;
    const PING = 0x08;
    const LOGIN_FAILED = 0x09;
    const RECV_MSG = 0x0a;
    const DISCONNECTING = 0x0b;
    const NOTIFY_REPLY = 0x0c;
    const DISCONNECTING_ACK = 0x0d;
    const PUBDIR50_REPLY = 0x0e;
    const STATUS60 = 0x0f;
    const USERLIST_REPLY = 0x10;
    const NOTIFY_REPLY60 = 0x11;
    const NEED_EMAIL = 0x14;
    const LOGIN_HASH_TYPE_INVALID = 0x16;
    const STATUS77 = 0x17;
    const NOTIFY_REPLY77 = 0x18;
    const DCC7_INFO = 0x1f;
    const DCC7_NEW = 0x20;
    const DCC7_ACCEPT = 0x21;
    const DCC7_REJECT = 0x22;
    const DCC7_ID_REPLY = 0x23;
    const DCC7_ABORTED = 0x25;
    const XML_EVENT = 0x27;
    const STATUS80BETA = 0x2a;
    const NOTIFY_REPLY80BETA = 0x2b;
    const XML_ACTION = 0x2c;
    const RECV_MSG80 = 0x2e;
    const USERLIST_REPLY80 = 0x30;
    const LOGIN80_OK = 0x35;
    const STATUS80 = 0x36;
    const NOTIFY_REPLY80 = 0x37;

    const NEW_STATUS = 0x02;
    //const PONG = 0x07;
    //const PING = 0x08;
    const SEND_MSG = 0x0b;
    const LOGIN = 0x0c;
    const ADD_NOTIFY = 0x0d;
    const REMOVE_NOTIFY = 0x0e;
    const NOTIFY_FIRST = 0x0f;
    const NOTIFY_LAST = 0x10;
    const LIST_EMPTY = 0x12;
    const PUBDIR50_REQUEST = 0x14;
    const LOGIN60 = 0x15;
    const USERLIST_REQUEST = 0x16;
    const LOGIN70 = 0x19;
    //const DCC7_INFO = 0x1f;
    //const DCC7_NEW = 0x20;
    //const DCC7_ACCEPT = 0x21;
    //const DCC7_REJECT = 0x22;
    const DCC7_ID_REQUEST = 0x23;
    const DCC7_DUNNO1 = 0x24;
    const DCC7_ABORT = 0x25;
    const NEW_STATUS80BETA = 0x28;
    const LOGIN80BETA = 0x29;
    const SEND_MSG80 = 0x2d;
    const USERLIST_REQUEST80 = 0x2f;
    const LOGIN80 = 0x31;
    const NEW_STATUS80 = 0x38;

    const EVENT_NONE = 0x00;
    const EVENT_MSG = 0x01;
    const EVENT_NOTIFY = 0x02;
    const EVENT_STATUS = 0x03;
    const EVENT_ACK = 0x04;
    const EVENT_PONG = 0x05;
    const EVENT_DISCONNECT = 0x06;
    const EVENT_PUBDIR = 0x07;
    const EVENT_USERLIST = 0x08;
    const EVENT_IMAGE_REQUEST = 0x09;
    const EVENT_IMAGE_REPLY = 0x0a;

    const DEBUG_BINARY = 0x01;
    const DEBUG_HTTP = 0x02;
    const DEBUG_DUMP = 0x04;
    const DEBUG_MISC = 0x08;
    const DEBUG_ALL = 0x10;

    const STATUS_NOT_AVAIL = 0x0001;
    const STATUS_NOT_AVAIL_DESCR = 0x0015;
    const STATUS_FFC = 0x0017;
    const STATUS_FFC_DESCR = 0x0018;
    const STATUS_AVAIL = 0x0002;
    const STATUS_AVAIL_DESCR = 0x0004;
    const STATUS_BUSY = 0x0003;
    const STATUS_BUSY_DESCR = 0x0005;
    const STATUS_DND = 0x0021;
    const STATUS_DND_DESCR = 0x0022;
    const STATUS_INVISIBLE = 0x0014;
    const STATUS_INVISIBLE_DESCR = 0x0016;
    const STATUS_BLOCKED = 0x0006;

    const STATUS80_GRAPHIC_DESCR_MASK = 0x0100;
    const STATUS80_DESCR_MASK = 0x4000;
    const STATUS_FRIENDS_MASK = 0x8000;

    const STATUS_VOICE_MASK = 0x20000;
    /**< czy ma wlaczone audio (7.7) */

    const STATUS_MASK = 0xff;

    const LOGIN_HASH_GG32 = 0x01;
    const LOGIN_HASH_SHA1 = 0x02;

    const FLAG_UNKNOW = 0x01;
    const FLAG_VIDEO = 0x02;
    const FLAG_ALL = 0x03;
    const FLAG_MOBILE = 0x100000;
    const FLAG_WIDGET = 0x400000;

    const FEATURE_STATUS80BETA = 0x01;
    const FEATURE_MSG80 = 0x02;
    const FEATURE_STATUS80 = 0x04;
    const FEATURE_8007669 = 0x07;
    const FEATURE_DND_FFC = 0x10;
    const FEATURE_DESCR80 = 0x20;
    const FEATURE_ALL = 0x37;

    const USER_OFFLINE = 0x01;
    const USER_NORMAL = 0x03;
    const USER_BLOCKED = 0x04;

    const CLASS_QUEUED = 0x01;
    const CLASS_MSG = 0x04;
    const CLASS_CHAT = 0x08;
    const CLASS_CTCP = 0x10;
    const CLASS_ACK = 0x20;
    const CLASS_WIDGET = 0x40;

    const ACK_BLOCKED = 0x01;
    const ACK_DELIVERED = 0x02;
    const ACK_QUEUED = 0x03;
    const ACK_MBOXFULL = 0x04;
    const ACK_NOT_DELIVERED = 0x06;

    const FONT_BOLD = 0x01;
    const FONT_ITALIC = 0x02;
    const FONT_UNDERLINE = 0x04;
    const FONT_COLOR = 0x08;
    const FONT_IMAGE = 0x80;

    const PUBDIR50_WRITE = 0x01;
    const PUBDIR50_READ = 0x02;
    const PUBDIR50_SEARCH = 0x03;
    const PUBDIR50_SEARCH_REPLY = 0x05;
    const PUBDIR50_NEED_REQUEST = 0x06;

    const PUBDIR80_WRITE = 0x01;
    const PUBDIR80_READ = 0x02;
    const PUBDIR80_SEARCH = 0x03;

    const PUBDIR50_UIN = 'FmNumber';
    const PUBDIR50_STATUS = 'FmStatus';
    const PUBDIR50_FIRSTNAME = 'firstname';
    const PUBDIR50_LASTNAME = 'lastname';
    const PUBDIR50_NICKNAME = 'nickname';
    const PUBDIR50_BIRTHYEAR = 'birthyear';
    const PUBDIR50_CITY = 'city';
    const PUBDIR50_GENDER = 'gender';
    const PUBDIR50_GENDER_ANY = '0';
    const PUBDIR50_GENDER_FEMALE = '1';
    const PUBDIR50_GENDER_MALE = '2';
    const PUBDIR50_GENDER_SET_FEMALE = '2';
    const PUBDIR50_GENDER_SET_MALE = '1';
    const PUBDIR50_ACTIVE = 'ActiveOnly';
    const PUBDIR50_ACTIVE_TRUE = '1';
    const PUBDIR50_START = 'fmstart';
    const PUBDIR50_FAMILYNAME = 'familyname';
    const PUBDIR50_FAMILYCITY = 'familycity';

    const PUBDIR80_UIN = 'uin';
    const PUBDIR80_ANY = 'any';
    const PUBDIR80_ALL = 'all';
    const PUBDIR80_NICK = 'nick';
    const PUBDIR80_NAME = 'name';
    const PUBDIR80_SURNAME = 'surname';
    const PUBDIR50_BIRTH = 'birth';
    const PUBDIR80_CITY = 'city';
    const PUBDIR80_WWW_URL = 'wwwUrl';
    const PUBDIR80_EMAIL = 'email';
    const PUBDIR80_HAS_ACTIVE_MGPROFILE = 'hasActiveMGProfile';
    const PUBDIR80_PROVINCE = 'province';
    const PUBDIR80_GENDER = 'gender';
    const PUBDIR80_GENDER_ANY = '0';
    const PUBDIR80_GENDER_FEMALE = '1';
    const PUBDIR80_GENDER_MALE = '2';
    const PUBDIR80_AGE_FROM = 'ageFrom';
    const PUBFIR80_AGE_TO = 'ageTo';
    const PUBDIR80_LIMIT = 'limit';
    const PUBDIR80_OFFSET = 'offset';
    const PUBDIR80_ONLY_AVAILABLE = 'onlyAvailable';
    const PUBDIR80_AVAILABLE = '1';

    const USERLIST_PUT = 0x00;
    const USERLIST_PUT_MORE = 0x01;
    const USERLIST_GET = 0x02;

    const USERLIST_PUT_REPLY = 0x00;
    const USERLIST_PUT_MORE_REPLY = 0x02;
    const USERLIST_GET_MORE_REPLY = 0x04;
    const USERLIST_GET_REPLY = 0x06;

    // }}}
    public $overflood_lock = 1;
    /*
    private $client_version = '4, 6, 0, 4';
    private $protocol_version = 0x11;
    private $protocol_flags = 0x0;
    // */

    /*
    private $client_version = '6, 0, 0, 129';
    private $protocol_version = 0x20;
    private $protocol_flags = 0x0;
    // */

    /*
    private $client_version = '7, 0, 0, 20';
    private $protocol_version = 0x26;
    private $protocol_flags = 0x0;
    // */

    /*
    private $client_version = '7,7,0,3746';
    private $protocol_version = 0x2a;
    private $protocol_flags = 0xc2020000;
    // */

    /*
    private $client_version = '8,0,0,5443';
    private $protocol_version = 0x2d;
    private $protocol_flags = 0x0;
    // */

    /*
    private $client_version = '8.0.0.7669';
    private $protocol_version = 0x2e;
    private $flags = 0x0;
    private $features = self::FEATURE_8007669;
    // */

    //*
    public $debug_level = 255;
    private $protocol_versions = array(
        0x11 => '4, 6, 0, 4',
        /*0x14 => '4.8.3',
        0x15 => '4.8.3',
        0x16 => '4.8.3',
        0x17 => '4.8.3',
        0x18 => '5.0.0',*/
        0x19 => '5, 0, 3, 94',
        /*0x1a => '5.0.0',*/
        0x1b => '5, 0, 5, 108',
        /*0x1c => '5.0.0',
        0x1d => '5.0.0',
        0x1e => '5.0.0',
        0x1f => '5.0.0',*/
        0x20 => '6, 0, 0, 129',
        0x26 => '7, 0, 0, 20',
        /*0x27 => '7.0.0.22',*/
        0x28 => '7, 5, 0, 2158',
        /*0x29 => '7.6.0.1688',*/
        0x2a => '7,7,0,3746',
        /*0x2c => '8.0.0.0',
        0x2d => '8.0.0.5443',*/
        0x2e => '8.0.0.9215',
    );
    private $client_version = '8.0.0.9215';
    private $protocol_version = 0x2e;
    // */
    private $flags = self::FLAG_UNKNOW; // permits ca. 60/min, w/o should be max 100/min
    private $features = self::FEATURE_ALL;
    private $last_message_time = 0;
    private $last_send_time = 0;
    private $awaiting_events = array();
    private $last_sysmsg = 0;
    private $sock;
    private $uin;
    private $password;
    private $status = self::STATUS_AVAIL;
    private $description = '';

    //private $async = false;

    public function __construct($protocol_version = 0x2a)
    {
        //$this->protocol_version = $protocol_version;
    }

    static public function S_F($x)
    {
        return ($x & self::STATUS_FRIENDS_MASK) != 0;
    }

    // {{{ login()

    static public function S_I($x)
    {
        return self::S($x) == self::STATUS_INVISIBLE ||
            self::S($x) == self::STATUS_INVISIBLE_DESCR;
    }
    // }}}
    // {{{ logout()

    static public function S($x)
    {
        return $x & self::STATUS_MASK;
    }
    // }}}
    // {{{ ping()

    static public function S_A($x)
    {
        return self::S_FF($x) || self::S_AV($x);
    }
    // }}}
    // {{{ notify()

    static public function S_FF($x)
    {
        return self::S($x) == self::STATUS_FFC ||
            self::S($x) == self::STATUS_FFC_DESCR;
    }
    // }}}
    // {{{ add_notify()

    static public function S_AV($x)
    {
        return self::S($x) == self::STATUS_AVAIL ||
            self::S($x) == self::STATUS_AVAIL_DESCR;
    }
    // }}}
    // {{{ remove_notify()

    static public function S_B($x)
    {
        return self::S_AW($x) || self::S_DD($x);
    }
    // }}}
    // {{{ pubdir50()

    static public function S_AW($x)
    {
        return self::S($x) == self::STATUS_BUSY ||
            self::S($x) == self::STATUS_BUSY_DESCR;
    }
    // }}}
    // {{{ pubdir80()

    static public function S_DD($x)
    {
        return self::S($x) == self::STATUS_DND ||
            self::S($x) == self::STATUS_DND_DESCR;
    }
    // }}}
    // {{{ userlist_request()

    static public function S_BL($x)
    {
        return self::S($x) == self::STATUS_BLOCKED;
    }
    // }}}
    // {{{ change_status()

    public function __destruct()
    {
        // check state
        if ($this->check_connection())
            $this->disconnect($this->sock);
    }
    // }}}
    // {{{ send_message()

    public function check_connection($sock = null)
    {
        if ($sock === null)
            $sock = $this->sock;
        $res = is_resource($sock);
        if ($res) {
            if (socket_last_error($sock) > 0)
                return false;
        }
        return $res;
    }
    // }}}
    // {{{ want_image()

    private function disconnect(&$sock)
    {
        if (!$sock)
            return;
        socket_shutdown($sock, 2);
        socket_close($sock);
        $sock = null;
    }
    // }}}
    // {{{ send_image()

    public function login($p = array())
    {
        // check state
        if ($this->check_connection()) {
            $this->debug('Already logged');
            return;
        }
        if (!empty($p['uin']))
            $this->uin = $p['uin'];
        if (!empty($p['password']))
            $this->password = $p['password'];
        if (!empty($p['status']))
            $this->status = $p['status'];
        if (!empty($p['description']))
            $this->description = $p['description'];
        if (empty($this->uin)) {
            $this->debug('Uin must be set');
            return;
        }
        if (empty($this->password)) {
            $this->debug('Password must be set');
            return;
        }
        if (self::S_NA($this->status)) {
            $this->debug('Wrong status');
            return;
        }
        //if (!$this->async)
        {
            // find server
            $res = $this->send_http_request('appmsg.gadu-gadu.pl');
            if ($res === false) {
                $this->debug('Couldn\'t get server address');
                return false;
            }
            $res = substr($res, strpos($res, "\r\n\r\n") + 4);
            $pos = strpos($res, "\n");
            $line = $res;
            if ($pos !== false)
                $line = substr($line, 0, $pos);
            if (!preg_match(';(\d+) (\d+) ([a-z0-9.]+):(\d+) ([a-z0-9.]+);i', $line, $m)) {
                //throw new Exception('Server response: '.$line);
                $this->debug('Server response: ' . $line);
                return;
            }
            //print_r($m);
            if ($m[1] != 0)
                $this->last_sysmsg = $m[1];
            $server_addr = $m[3];
            $server_port = $m[4];
            if ($server_addr == 'notoperating') {
                $this->debug('Servers unavailable');
                return;
            }
            // connect to server
            $this->sock = $this->connect($server_addr, $server_port);
            if (!$this->check_connection()) {
                $this->debug('Server refuse connection');
                return;
            }
            // get seed
            $packet = $this->recv_packet();
            if (!$packet) {
                $this->debug('Couldn\'t get seed');
                $this->disconnect($this->sock);
                return;
            }
            if ($packet->packet_type != self::WELCOME) {
                $this->disconnect($this->sock);
                return;
            }
            $seed = $packet->seed;
            // send login packet
            if ($this->client_version < '6') {
                $packet = new GG_Login_Packet();
                $packet->uin = $this->uin;
                $packet->password = $this->login_hash($this->password, $seed);
                $packet->status = $this->status;
                $packet->flags = $this->protocol_flags | $this->protocol_version;
                $packet->local_addr = ip2long('0.0.0.0');
                $packet->local_port = 0;
            } else if ($this->client_version < '7') {
                $packet = new GG_Login60_Packet();
                $packet->uin = $this->uin;
                $packet->password = $this->login_hash($this->password, $seed);
                $packet->status = $this->status;
                $packet->flags = $this->protocol_flags | $this->protocol_version;
                $packet->dunno1 = 0x0;
                $packet->local_addr = ip2long('0.0.0.0');
                $packet->local_port = 0;
                $packet->external_addr = ip2long('0.0.0.0');
                $packet->external_port = 0;
                $packet->image_size = 100;
                $packet->dunno2 = 0xbe;
                $packet->descr = utf2win($this->description);
            } else if ($this->client_version < '8,0,0,5443') {
                $packet = new GG_Login70_Packet();
                $packet->uin = $this->uin;
                $packet->hash_type = self::LOGIN_HASH_SHA1;
                $packet->password = $this->login_hash_sha1($this->password, $seed);
                $packet->status = $this->status;
                $packet->flags = $this->protocol_flags | $this->protocol_version;
                $packet->dunno1 = 0x0;
                $packet->local_addr = ip2long('0.0.0.0');
                $packet->local_port = 0;
                $packet->external_addr = ip2long('0.0.0.0');
                $packet->external_port = 0;
                $packet->image_size = 100;
                $packet->dunno2 = 0xbe;
                $packet->descr = utf2win($this->description);
            } else if ($this->client_version < '8.0.0.7669') {
                $packet = new GG_Login80Beta_Packet();
                $packet->uin = $this->uin;
                $packet->hash_type = self::LOGIN_HASH_SHA1;
                $packet->password = $this->login_hash_sha1($this->password, $seed);
                $packet->status = $this->status;
                $packet->flags = $this->protocol_flags | $this->protocol_version;
                $packet->dunno1 = 0x0;
                $packet->local_addr = ip2long('0.0.0.0');
                $packet->local_port = 0;
                $packet->external_addr = ip2long('0.0.0.0');
                $packet->external_port = 0;
                $packet->image_size = 100;
                $packet->dunno2 = 0x64;
                $packet->descr = $this->description;
            } else {
                $packet = new GG_Login80_Packet();
                $packet->uin = $this->uin;
                $packet->language = 'pl';
                $packet->hash_type = self::LOGIN_HASH_SHA1;
                $packet->password = $this->login_hash_sha1($this->password, $seed);
                $packet->status = $this->status | (self::S_D($this->status) && $this->features & self::FEATURE_DESCR80 ? self::STATUS80_DESCR_MASK : 0);
                $packet->flags = $this->flags;
                $packet->features = $this->features;
                $packet->local_addr = ip2long('0.0.0.0');
                $packet->local_port = 0;
                $packet->external_addr = ip2long('0.0.0.0');
                $packet->external_port = 0;
                $packet->image_size = 255;
                $packet->unknow2 = 0x64;
                $packet->version_size = strlen('Gadu-Gadu Client build ' . $this->client_version);
                $packet->version = 'Gadu-Gadu Client build ' . $this->client_version;
                $packet->description_size = strlen($this->description);
                $packet->description = $this->description;
            }
            $this->send_packet($packet);
            // get response
            while (!($packet = $this->recv_packet())) ;
            switch ($packet->packet_type) {
                case self::LOGIN_FAILED:
                    $this->debug('Password incorrect');
                    $this->disconnect($this->sock);
                    return;
                    break;
                case self::NEED_EMAIL:
                    $this->debug('Need email');
                    break;
                case self::LOGIN_OK:
                case self::LOGIN80_OK:
                    $this->debug('Logged in');
                    break;
            }
        }
        return true;
    }
    // }}}
    // {{{ dcc7_request_id()

    private function debug($txt, $level = self::DEBUG_ALL)
    {
        if ($this->debug_level & $level)
            echo date('[H:i:s]') . preg_replace('/[^\x0a\x0d\x20-\x7e]/', '.', $txt) . LF;
    }
    // }}}
    // {{{ dcc7_new()

    static public function S_NA($x)
    {
        return self::S($x) == self::STATUS_NOT_AVAIL ||
            self::S($x) == self::STATUS_NOT_AVAIL_DESCR;
    }
    // }}}
    // {{{ dcc7_accept()

    private function send_http_request($host)
    {
        // check state
        $client_version = htmlspecialchars($this->client_version);
        if ($client_version < '5') {
            $req = "GET /appsvc/appmsg2.asp?fmnumber=($this->uin}&version=4%2C+6%2C+0%2C+4 HTTP/1.0\r\n" .
                "Host: {$host}\r\n" .
                "User-Agent: Mozilla/4.0 (compatible; MSIE 5.0; Windows NT)\r\n" .
                "Pragma: no-cache\r\n" .
                "\r\n";
        } else if ($client_version < '7') {
            $req = "GET /appsvc/appmsg4.asp?fmnumber={$this->uin}&version={$client_version}&fmt=2&lastmsg={$this->last_sysmsg} HTTP/1.0\r\n" .
                "Host: appmsg.gadu-gadu.pl\r\n" .
                "User-Agent: Mozilla/4.0 (compatible; MSIE 5.0; Windows 98)\r\n" .
                "Pragma: no-cache\r\n" .
                "\r\n";
        } else if ($client_version < '8') {
            $req = "GET /appsvc/appmsg4.asp?fmnumber={$this->uin}&version=" . strtr($this->client_version, array(',' => '%2C', ' ' => '+')) . "&fmt=2&lastmsg={$this->last_sysmsg} HTTP/1.0\r\n" .
                "Host: {$host}\r\n" .
                "Accept: image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*\r\n" .
                "Accept-Language: pl\r\n" .
                "User-Agent: Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)\r\n" .
                "Pragma: no-cache\r\n" .
                "\r\n";
        } else {
            $req = "GET /appsvc/appmsg_ver8.asp?fmnumber={$this->uin}&fmt=2&lastmsg={$this->last_sysmsg}&version={$this->client_version} HTTP/1.1\r\n" .
                "Connection: Keep-Alive\r\n" .
                "Host: {$host}\r\n" .
                "\r\n";
        }
        $sock = $this->connect($host, 80);
        if ($sock) {
            $ret = $this->write($sock, $req);
            if ($ret === false) {
                $this->debug('Couldn\'t send http request');
                $this->disconnect($sock);
                return false;
            }
            $this->debug('write(): ' . htmlspecialchars($req), self::DEBUG_HTTP);
            $res = $this->read($sock);
            if ($res === false) {
                $this->debug('Couldn\'t get http response');
                $this->disconnect($sock);
                return false;
            }
            $this->debug('read(): ' . htmlspecialchars($res), self::DEBUG_HTTP);
            $this->disconnect($sock);
            return $res;
        }
        return false;
    }
    // }}}
    // {{{ dcc7_info()

    private function connect($host, $port, $async = false)
    {
        $this->debug('Connecting to: ' . $host . ':' . $port);
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if (false == $sock) {
            $msg = 'connect() socket_create() failed: ' . socket_strerror(socket_last_error());
            $this->debug($msg, self::DEBUG_MISC);
            //throw new Exception($msg);
            return false;
        }
        if (false == socket_bind($sock, '0.0.0.0', 0)) {
            if (false == socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1) || false == socket_bind($sock, '0.0.0.0', 0)) {
                $msg = 'connect() socket_bind() failed: ' . socket_strerror(socket_last_error());
                $this->debug($msg, self::DEBUG_MISC);
                socket_close($sock);
                //throw new Exception($msg);
                return false;
            }
        }
        if ($async) {
            if (false == socket_set_nonblock($sock)) {
                $msg = 'connect() socket_set_nonblock() failed: ' . socket_strerror(socket_last_error());
                $this->debug($msg, self::DEBUG_MISC);
                socket_close($sock);
                //throw new Exception($msg);
                return false;
            }
        }
        if (false == @socket_connect($sock, $host, $port)) {
            if (!$async) {
                $msg = 'connect() socket_connect() failed: ' . socket_strerror(socket_last_error());
                $this->debug($msg, self::DEBUG_MISC);
                socket_close($sock);
                //throw new Exception($msg);
                return false;
            }
            $this->debug('connect() socket_connect() in progress', self::DEBUG_MISC);
        }
        return $sock;
    }

    // }}}

    private function write($sock, $buf)
    {
        socket_write($sock, $buf);
        if (socket_last_error($sock) > 0)
            return false;
    }

    // {{{ watch()

    private function read($sock, $len = 0)
    {
        if ($len > 0) {
            $ret = @socket_read($sock, $len);
            if (socket_last_error($sock) > 0)
                return false;
            return $ret;
        }
        $res = '';
        while (($out = @socket_read($sock, 1024)) != '')
            $res .= $out;
        if (socket_last_error($sock) > 0)
            return false;
        return $res;
    }
    // }}}

    // {{{ setters

    private function recv_packet()
    {
        // check state
        if (!$this->check_connection())
            return false;
        $num = $this->check_read($this->sock);
        if ($num === false) {
            $this->debug('Connection is broken');
            $this->disconnect($this->sock);
            return false;
        } else if ($num == 0)
            return false;
        $pack = $this->read($this->sock, 8);
        if ($pack === false) {
            $this->debug('Connection reset by peer');
            $this->disconnect($this->sock);
            return false;
        }
        if (!$pack)
            return null;
        $pack2 = unpack('Vtype/Vlength', $pack);
        //echo 'read(): '.bin2hex(pack('VV', $pack2['type'], $pack2['length']))."\n";
        if ($pack2['length'] > 0) {
            $to_read = $pack2['length'];
            while ($to_read) {
                $pack .= $this->read($this->sock, $to_read);
                $to_read = $pack2['length'] + 8 - strlen($pack);
            }
        }
        //$packet = new $this->recv_packet_type[$pack['type']]($pack);
        //$a = unpack('H*', $pack);
        //echo chunk_split($a[1], 4, ' ');
        $packet = get_recv_packet($pack);
        $this->debug('read(): ' . $packet->dump_binary(), self::DEBUG_BINARY);
        $this->debug($packet->dump(), self::DEBUG_DUMP);
        return $packet;
    }

    private function check_read($sock)
    {
        $read = array($sock);
        $write = NULL;
        $except = NULL;
        $num = socket_select($read, $write, $except, 2);
        if (socket_last_error($sock) > 0)
            return false;
        return $num;
    }

    function login_hash($password, $seed)
    {
        $y = $seed;
        $x = 0;

        for ($i = 0, $l = strlen($password); $i < $l; $i++) {
            $x = ($x & 0xFFFFFF00) | ord($password[$i]);
            $y ^= $x;
            $y += $x;
            $x <<= 8;
            $y ^= $x;
            $x <<= 8;
            $y -= $x;
            $x <<= 8;
            $y ^= $x;

            $z = $y & 0x1F;
//            $y = ($y << $z) | ($y >> (32 - $z));
            $y1 = ($y << $z);
            if ($z < 32) {
                $y2 = $y >> 1;
                $y2 &= 0x7FFFFFFF;
                $y2 = $y2 >> (31 - $z);
            }
            $y = $y1 | $y2;
        }
        return $y;
    }

    function login_hash_sha1($password, $seed)
    {
//        $seed = $this->fixuint($seed); // niepotrzebne, bo przy pack() bajty traktowane są tak samo
        $seed = pack('V', $seed); // lepsze niż to, co jest linijkę niżej, bo robi to samo
//        $seed = chr($seed).chr($seed>>8).chr($seed>>16).chr($seed>>24);
        return sha1($password . $seed, true);
    }

    /*
    public function async($async)
    {
        // check state
        $this->async = $async;
    }
     */

    static public function S_D($x)
    {
        return self::S($x) == self::STATUS_NOT_AVAIL_DESCR ||
            self::S($x) == self::STATUS_FFC_DESCR ||
            self::S($x) == self::STATUS_AVAIL_DESCR ||
            self::S($x) == self::STATUS_BUSY_DESCR ||
            self::S($x) == self::STATUS_DND_DESCR ||
            self::S($x) == self::STATUS_INVISIBLE_DESCR;
    }
    // }}}

// {{{ connect()

    private function send_packet($packet)
    {
        if (!$this->check_connection()) {
            $this->disconnect($this->sock);
            return false;
        }
        // check state
        $ret = $this->write($this->sock, $packet->binary());
        if ($ret === false) {
            $this->disconnect($this->sock);
            return false;
        }
        $this->last_send_time = time();
        $this->debug('write(): ' . $packet->dump_binary(), self::DEBUG_BINARY);
        $this->debug($packet->dump(), self::DEBUG_DUMP);
    }
// }}}
// {{{ disconnect()

    public function logout()
    {
        // check state
        if (!$this->check_connection())
            return;
        $this->change_status(self::STATUS_NOT_AVAIL);
    }
// }}}
    // {{{ check_read()

    public function change_status($status, $descr = '')
    {
        if (!$this->check_connection())
            return;
        if ($this->client_version < '8.0.0.5443') {
            $packet = new GG_NewStatus_Packet();
            $this->status = $status;
            $packet->status = $status;
            $this->descr = $descr;
            $packet->description = utf2win($descr);
        } else if ($this->client_version < '8.0.0.9215') {
            $packet = new GG_NewStatus80Beta_Packet();
            $this->status = $status;
            $packet->status = $status;
            $this->descr = $descr;
            $packet->description = $descr;
        } else {
            $packet = new GG_NewStatus80_Packet();
            $this->status = $status;
            $packet->status = $status | (self::S_D($status) && $this->features & self::FEATURE_DESCR80 ? self::STATUS80_DESCR_MASK : 0);
            $packet->flags = $this->flags;
            $packet->description_size = strlen($descr);
            $this->descr = $descr;
            $packet->description = $descr;
        }
        $this->send_packet($packet);
        if (self::S($status) == self::STATUS_NOT_AVAIL) {
            $this->disconnect($this->sock);
        }
    }
    // }}}
    // {{{ check_connection()

    public function notify($notify = array())
    {
        if (!$this->check_connection())
            return;
        if (empty($notify)) {
            $packet = new GG_ListEmpty_Packet();
            $this->send_packet($packet);
        } else {
            $only_numbers = key($notify) == 0;
            $notify = array_chunk($notify, 400, true);
            $last = array_pop($notify);
            foreach ($notify as $n) {
                $packet = new GG_NotifyFirst_Packet();
                $notify2 = array();
                if ($only_numbers) {
                    foreach ($n as $v) {
                        $p = new GG_Notify_Packet_Part();
                        $p->uin = $v;
                        $p->type = self::USER_NORMAL;
                        $notify2[] = $p;
                    }
                } else {
                    foreach ($n as $k => $v) {
                        $p = new GG_Notify_Packet_Part();
                        $p->uin = $k;
                        $p->type = $v;
                        $notify2[] = $p;
                    }
                }
                $packet->notify = $notify2;
                $this->send_packet($packet);
            }
            $packet = new GG_NotifyLast_Packet();
            $notify2 = array();
            if ($only_numbers) {
                foreach ($last as $v) {
                    $p = new GG_Notify_Packet_Part();
                    $p->uin = $v;
                    $p->type = self::USER_NORMAL;
                    $notify2[] = $p;
                }
            } else {
                foreach ($last as $k => $v) {
                    $p = new GG_Notify_Packet_Part();
                    $p->uin = $k;
                    $p->type = $v;
                    $notify2[] = $p;
                }
            }
            $packet->notify = $notify2;
            $this->send_packet($packet);
        }
    }
    // }}}
// {{{ write()

    public function add_notify($uin, $type = 0x03)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_AddNotify_Packet();
        $packet->uin = $uin;
        $packet->type = $type;
        $this->send_packet($packet);
    }
// }}}
// {{{ read()

    public function remove_notify($uin, $type = 0x03)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_RemoveNotify_Packet();
        $packet->uin = $uin;
        $packet->type = $type;
        $this->send_packet($packet);
    }
// }}}
    // {{{ recv_packet()

    public function pubdir50($type, $request = array())
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_Pubdir50Request_Packet();
        $packet->type = $type;
        $packet->seq = time();
        $req = array();
        foreach ($request as $k => $v) {
            $req[] = $k . "\0" . $v;
        }
        $req = implode("\0", $req) . "\0";
        $packet->request = utf2win($req);
        $this->send_packet($packet);
    }
    // }}}
    // {{{ send_packet()

    public function pubdir80($type, $request = array())
    {
        if (!$this->check_connection())
            return;
        if (!extension_loaded('curl')) {
            $this->debug('cURL library not loaded');
            return;
        }
        if (!defined('GADUAPI_REQUEST_URL'))
            define('GADUAPI_REQUEST_URL', 'http://api.gadu-gadu.pl');
        if (!defined('GADU_API_AUTHORIZATION_URL'))
            define('GADUAPI_AUTHORIZATION_URL', 'https://login.gadu-gadu.pl/authorize');

        require_once 'GaduAPI.php';

        $ret = array();
        switch ($type) {
            case self::PUBDIR80_WRITE:
                $api = new GaduAPI($this->uin, $this->password);
                $ret = $api->saveUser($this->uin, array());
                break;
            case self::PUBDIR80_READ:
                $api = new GaduAPI();
                $ret = $api->getUser($this->uin);
                break;
            case self::PUBDIR80_SEARCH:
                $api = new GaduAPI();
                $ret = $api->getUsers(array());
                break;
        }
        return $ret;
    }
    // }}}
    // {{{ send_http_request()

    public function userlist_request($type, $request = false)
    {
        if (!$this->check_connection())
            return;
        if ($type != self::USERLIST_GET && $type != self::USERLIST_PUT) {
            $this->debug('Wrong userlist request');
            return;
        }
        if ($this->client_version < '8') {
            if ($type == self::USERLIST_GET) {
                $packet = new GG_UserlistRequest_Packet();
                $packet->type = self::USERLIST_GET;
                $packet->request = '';
                $this->send_packet($packet);
            } else {
                $first = true;
                if ($request === false) {
                    $this->debug('Request must be set');
                    return;
                }
                $request = utf2win($request);
                while ($first || strlen($request) > 0) {
                    $packet = new GG_UserlistRequest_Packet();
                    $packet->type = $first ? self::USERLIST_PUT : self::USERLIST_PUT_MORE;
                    $packet->request = substr($request, 0, 2047);
                    $this->send_packet($packet);
                    $first = false;
                    $request = substr($request, 2047);
                }
            }
        } else {
            $packet = new GG_UserlistRequest80_Packet();
            $packet->type = $type;
            $packet->request = $request ? gzcompress($request) : '';
            $this->send_packet($packet);
        }
    }
    // }}}
    // {{{ status functions

    public function send_message($recipients, $message, $msgclass = self::CLASS_CHAT)
    {
        if (!$this->check_connection())
            return;
        if (!is_array($recipients)) {
            $recipients = array($recipients);
        }
        foreach ($recipients as $i => $recipient) {
            $appends = array();
            $r = $recipients;
            unset($r[$i]);
            if (!empty($r)) {
                $a = new GG_MsgRecipients_Packet_Part();
                $a->recipients = $r;
                $appends[] = $a;
            }
            if ($this->client_version < '8') {
                $packet = new GG_SendMsg_Packet();
                $packet->recipient = $recipient;
                $packet->seq = 0x208c618;
                $packet->msgclass = $msgclass;
                $message = utf2win($message);
                if (substr($message, 0, 5) != '<span') {
                    $message = '<span style="color:#000000">' . $message . '</span>';
                }
                list($packet->message, $appends[]) = $this->html2richtext($message);
                //$packet->message = strip_tags(preg_replace(';<img(.*?)>;', "\xa0", $message));
                //$appends[] = $this->html2richtext($message);
            } else {
                $packet = new GG_SendMsg80_Packet();
                $packet->recipient = $recipient;
                $packet->seq = time();
                $packet->msgclass = $msgclass;
                if (substr($message, 0, 5) != '<span') {
                    $message = '<span style="color:#000000; font-family:\'MS Shell Dlg 2\'; font-size:9pt; ">' . $message . '</span>';
                }
                list($message_plain, $appends[]) = $this->html2richtext(utf2win($message));
                //$message_plain = strip_tags(preg_replace(';<img(.*?)>;', "\xa0", utf2win($message)));
                $packet->offset_plain = 21 + strlen($message);
                $packet->offset_attributes = 22 + strlen($message) + strlen($message_plain);
                $packet->message_html = $message;
                $packet->message_plain = $message_plain;
                //$appends[] = $this->html2richtext(utf2win($message));
            }
            $packet->appends = $appends;
            if ($this->overflood_lock && $this->last_message_time == time()) {
                sleep(1);
            }
            $this->send_packet($packet);
            $this->last_message_time = time();
        }
    }

    public function html2richtext($html)
    {
        $org = $html;
        preg_match_all(';<(/?)(b|i|u|span|img)(?: [^<>]*?)?' . '>;', $html, $matches, PREG_SET_ORDER);
        $format = array();
        $pos = 0;
        $attr = 0;
        foreach ($matches as $m) {
            $pos = strpos($html, $m[0]);
            if ($m[2] == 'img') {
                $p = new GG_MsgRichtextFormat_Packet_Part();
                $p->position = $pos;
                $p->font = self::FONT_IMAGE;
                $p->image_size = hexdec(substr($html, $pos + 19, 8));
                $p->image_crc32 = hexdec(substr($html, $pos + 11, 8));
                $format[] = $p;
                $html = substr($html, 0, $pos) . "\xa0" . substr($html, $pos + strlen($m[0]));
                continue;
            }
            $oldattr = $attr;
            if ($m[1] == '') {
                switch ($m[2]) {
                    case 'b':
                        $attr |= self::FONT_BOLD;
                        break;
                    case 'i':
                        $attr |= self::FONT_ITALIC;
                        break;
                    case 'u':
                        $attr |= self::FONT_UNDERLINE;
                        break;
                    case 'span':
                        $attr |= self::FONT_COLOR;
                        $t = strpos($html, 'color', $pos) + 7;
                        $r = hexdec(substr($html, $t, 2));
                        $g = hexdec(substr($html, $t + 2, 2));
                        $b = hexdec(substr($html, $t + 4, 2));
                        break;
                }
            } else {
                switch ($m[2]) {
                    case 'b':
                        $attr &= ~self::FONT_BOLD;
                        break;
                    case 'i':
                        $attr &= ~self::FONT_ITALIC;
                        break;
                    case 'u':
                        $attr &= ~self::FONT_UNDERLINE;
                        break;
                    case 'span':
                        $attr &= ~self::FONT_COLOR;
                        break;
                }
            }
            $html = substr($html, 0, $pos) . substr($html, $pos + strlen($m[0]));
            //if ($oldattr != $attr && (!isset($html[$pos]) || $html[$pos] != '<'))
            if ($oldattr != $attr && isset($html[$pos]) && $html[$pos] != '<') {
                $p = new GG_MsgRichtextFormat_Packet_Part();
                $p->position = $pos;
                $p->font = $attr;
                if ($attr & self::FONT_COLOR) {
                    $p->red = $r;
                    $p->green = $g;
                    $p->blue = $b;
                }
                $format[] = $p;
            }
        }
        if (empty($format))
            return false;
        $richtext = new GG_MsgRichtext_Packet_Part();
        $richtext->format = $format;
        echo htmlspecialchars($org . LF . $html . LF . $richtext->dump()) . '<br>----<br>';
        return array($html, $richtext);
        /* {{{
        $maxlen = strlen(strip_tags($str));
        $format = array();
        $pos = 0;
        $attr = 0;
        while ($pos > -1) {
            $pos = strpos($str, '<');
            $pos++;
            if (substr($str, $pos, 3) == 'img')
            {
                $p = new GG_MsgRichtextFormat_Packet_Part();
                $p->position = $pos-1;
                $p->font = self::FONT_IMAGE;
                $p->image_size = hexdec(substr($str, 19, 8));
                $p->image_crc32 = hexdec(substr($str, 11, 8));
                $format[] = $p;
                $str = substr($str, 0, $pos).substr($str, strpos($str, '>')+1);
                $str[$pos-1] = "\xa0";
                continue;
            }
            if (in_array($str[$pos], array('b','i','u', '/', 's'))) {
                $oldattr = $attr;
                if ($str[$pos] == 'b') {
                    $attr |= self::FONT_BOLD;
                } else if ($str[$pos] == 'i') {
                    $attr |= self::FONT_ITALIC;
                } else if ($str[$pos] == 'u') {
                    $attr |= self::FONT_UNDERLINE;
                } else if ($str[$pos] == 's') {
                    $attr |= self::FONT_COLOR;
                    $t = strpos($str, 'color')+7;
                    $r = hexdec(substr($str, $t, 2));
                    $g = hexdec(substr($str, $t+2, 2));
                    $b = hexdec(substr($str, $t+4, 2));
                } else if ($str[$pos] == '/') {
                    $pos++;
                    if (in_array($str[$pos], array('b','i','u', 's'))) {
                        if ($str[$pos] == 'b') {
                            $attr &= ~self::FONT_BOLD;
                        } else if ($str[$pos] == 'i') {
                            $attr &= ~self::FONT_ITALIC;
                        } else if ($str[$pos] == 'u') {
                            $attr &= ~self::FONT_UNDERLINE;
                        } else if ($str[$pos] == 's') {
                            $attr &= ~self::FONT_COLOR;
                        }
                    }
                    $pos--;
                }
                if ($oldattr != $attr && (strpos($str, '>')+1 < strlen($str) && $str[strpos($str, '>')+1] != '<') && $pos-1 < $maxlen) {
                    $p = new GG_MsgRichtextFormat_Packet_Part();
                    $p->position = $pos-1;
                    $p->font = $attr;
                    if ($attr & self::FONT_COLOR) {
                        $p->red = $r;
                        $p->green = $g;
                        $p->blue = $b;
                    }
                    $format[] = $p;
                }
            }
            if ($pos > -1)
            {
                $str = substr($str, 0, $pos-1).substr($str, strpos($str, '>')+1);
            }
        }
        if (empty($format))
            return false;
        $richtext = new GG_MsgRichtext_Packet_Part();
        $richtext->format = $format;
        return $richtext;
     }}} */
    }

    public function want_image($recipient, $id)
    {
        if (!$this->check_connection())
            return;
        $crc32 = hexdec(substr($id, 0, 8));
        $size = hexdec(substr($id, 8));
        $packet = new GG_SendMsg_Packet();
        $packet->recipient = $recipient;
        $packet->seq = 0x6ebdd80;
        $packet->msgclass = self::CLASS_MSG;
        $packet->message = '';
        $p = new GG_MsgImageRequest_Packet_Part();
        $p->size = $size;
        $p->crc32 = $crc32;
        $packet->appends = array($p);
        $this->send_packet($packet);
    }

    public function send_image($recipient, $filename)
    {
        if (!$this->check_connection())
            return;
        $file = file_get_contents($filename);
        $crc32 = crc32($file);
        $size = filesize($filename);
        $basename = basename($filename);
        $packet = new GG_SendMsg_Packet();
        $packet->recipient = $recipient;
        $packet->seq = 0x0;
        $packet->msgclass = self::CLASS_MSG;
        $packet->message = '';
        $p = new GG_MsgImageReply_Packet_Part();
        $p->flag = 0x05;
        $p->size = $size;
        $p->crc32 = $crc32;
        $p->filename = $basename;
        if ($this->client_version < '8') {
            $p->image = substr($file, 0, 1900 - strlen($basename) - 1);
            $file = substr($file, 1900 - strlen($basename) - 1);
        } else {
            $p->image = substr($file, 0, 1922);
            $file = substr($file, 1922);
        }
        $packet->appends = array($p);
        $this->send_packet($packet);
        while (strlen($file) > 0) {
            $packet = new GG_SendMsg_Packet();
            $packet->recipient = $recipient;
            $packet->seq = 0x0;
            $packet->msgclass = self::CLASS_MSG;
            $packet->message = '';
            $p = new GG_MsgImageReply_Packet_Part();
            $p->flag = 0x06;
            $p->size = $size;
            $p->crc32 = $crc32;
            $p->filename = '';
            if ($this->client_version < '8') {
                $p->image = substr($file, 0, 1900);
                $file = substr($file, 1900);
            } else {
                $p->image = substr($file, 0, 1922);
                $file = substr($file, 1922);
            }
            $packet->appends = array($p);
            $this->send_packet($packet);
        }
    }

    public function dcc7_request_id($type)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_DCC7_IdRequest_Packet();
        $packet->type = $type;
        $this->send_packet($packet);
    }

    public function dcc7_new($id, $recipient, $type, $filename, $size)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_DCC7_New_Packet();
        $packet->id = $id;
        $packet->uin_from = $this->uin;
        $packet->uin_to = $recipient;
        $packet->type = $type;
        $packet->filename = $filename;
        $packet->size = $size;
        $this->send_packet($packet);
        //$this->send_packet(self::DCC7_NEW, 'H16VVVa'.self::DCC7_FILENAME_LEN.'VVa'.self::DCC7_HASH_LEN, array($data['id'], $tmp['uin'], $tmp['peer_uin'], $tmp['dcc_type'], $tmp['filename'], $tmp['size'], 0, 0));
    }

    public function dcc7_accept($uin, $id, $offset = 0)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_DCC7_Accept_Packet();
        $packet->uin = $uin;
        $packet->id = $id;
        $this->send_packet($packet);
        /*$this->send_packet(self::DCC7_ACCEPT, 'VH16VV', array($tmp['peer_uin'], $id, $offset, 0));
        $tmp['offset'] = $offset;
        $this->dcc7_listen_and_send_info($id);*/
    }

    public function dcc7_info($recipient, $type, $id, $info, $info2)
    {
        if (!$this->check_connection())
            return;
        $packet = new GG_DCC7_Info_Packet();
        $packet->uin = $recipient;
        $packet->type = $type;
        $packet->id = $id;
        $packet->info = $info;
        $packet->info2 = $info2;
        $this->send_packet($packet);
    }

    public function watch()
    {
        if (time() - $this->last_send_time >= 240)
            $this->ping();
        if (!empty($this->awaiting_events)) {
            return array_unshift($this->awaiting_events);
        }
        $packet = $this->recv_packet();
        if ($packet) {
            $event = array(
                'type' => self::EVENT_NONE,
                'time' => time(),
                'packet' => $packet,
                'event' => array(),
            );
            switch ($packet->packet_type) {
                case self::STATUS:
                    break;
                // {{{ SEND_MSG_ACK
                case self::SEND_MSG_ACK:
                    $event['type'] = self::EVENT_ACK;
                    $event['event']['recipient'] = $packet->recipient;
                    $event['event']['status'] = $packet->status;
                    break;
                // }}}
                case self::PONG:
                    break;
                case self::PING:
                    break;
                // {{{ RECV_MSG
                case self::RECV_MSG:
                    $event['type'] = self::EVENT_MSG;
                    $event['event']['sender'] = $packet->sender;
                    $message = $packet->message;
                    $recipients = array();
                    if ($packet->appends != array()) {
                        foreach ($packet->appends as $i => $p) {
                            switch (get_class($p)) {
                                case 'GG_MsgRecipients_Packet_Part':
                                    $recipients = $p->recipients;
                                    break;
                                case 'GG_MsgRichtext_Packet_Part':
                                    $message = $this->richtext2html($message, $p);
                                    break;
                                case 'GG_MsgImageRequest_Packet_Part':
                                    $event['type'] = self::EVENT_IMAGE_REQUEST;
                                    $event['event']['id'] = dec2hex($p->crc32, 8) . dec2hex($p->size, 8);
                                    $event['event']['size'] = $p->size;
                                    $event['event']['crc32'] = $p->crc32;
                                    break;
                                case 'GG_MsgImageReply_Packet_Part':
                                    $event['type'] = self::EVENT_IMAGE_REPLY;
                                    $event['event']['id'] = dec2hex($p->crc32, 8) . dec2hex($p->size, 8);
                                    $event['event']['size'] = $p->size;
                                    $event['event']['crc32'] = $p->crc32;
                                    $event['event']['filename'] = $p->filename;
                                    $event['event']['image'] = $p->image;
                                    break;
                            }
                        }
                    }
                    if ($event['type'] == self::EVENT_MSG) {
                        $event['event']['msgclass'] = $packet->msgclass;
                        $event['event']['message'] = win2utf($message);
                        $event['event']['recipients'] = $recipients;
                    }
                    break;
                // }}}
                // {{{ DISCONNECTING DISCONNECTING_ACK
                case self::DISCONNECTING:
                case self::DISCONNECTING_ACK:
                    $event['type'] = self::EVENT_DISCONNECT;
                    $this->disconnect($this->sock);
                    // }}}
                    break;
                case self::NOTIFY_REPLY:
                    break;
                // {{{ PUBDIR50_REPLY
                case self::PUBDIR50_REPLY:
                    $event['type'] = self::EVENT_PUBDIR;
                    $event['event']['type'] = $packet->type;
                    $reply = array();
                    if ($packet->reply != '') {
                        foreach (explode("\0\0", win2utf($packet->reply)) as $r) {
                            $data = array_chunk(explode("\0", $r), 2);
                            $person = array();
                            foreach ($data as $row) {
                                $person[$row[0]] = $row[1];
                            }
                            $reply[] = $person;
                        }
                    }
                    $event['event']['reply'] = $reply;
                    break;
                // }}}
                // {{{ STATUS60
                case self::STATUS60:
                    $event['type'] = self::EVENT_STATUS;
                    $event['event']['uin'] = $packet->uin;
                    $event['event']['status'] = $packet->status;
                    $event['event']['description'] = win2utf($packet->description);
                    break;
                // }}}
                // {{{ USERLIST_REPLY
                case self::USERLIST_REPLY:
                    $event['type'] = self::EVENT_USERLIST;
                    $event['event']['type'] = $packet->type;
                    $event['event']['reply'] = $packet->reply;
                    break;
                // }}}
                // {{{ NOTIFY_REPLY60
                case self::NOTIFY_REPLY60:
                    $event['type'] = self::EVENT_NOTIFY;
                    $reply = array();
                    if ($packet->reply != array()) {
                        foreach ($packet->reply as $r) {
                            $reply[$r->uin] = array(
                                'uin' => $r->uin,
                                'status' => $r->status,
                                'description' => win2utf($r->description),
                            );
                        }
                    }
                    $event['event']['reply'] = $reply;
                    break;
                // }}}
                // {{{ STATUS77
                case self::STATUS77:
                    $event['type'] = self::EVENT_STATUS;
                    $event['event']['uin'] = $packet->uin;
                    $event['event']['status'] = $packet->status;
                    $event['event']['description'] = win2utf($packet->description);
                    break;
                // }}}
                // {{{ NOTIFY_REPLY77
                case self::NOTIFY_REPLY77:
                    $event['type'] = self::EVENT_NOTIFY;
                    $reply = array();
                    if ($packet->reply != array()) {
                        foreach ($packet->reply as $r) {
                            $reply[$r->uin] = array(
                                'uin' => $r->uin,
                                'status' => $r->status,
                                'description' => win2utf($r->description),
                            );
                        }
                    }
                    $event['event']['reply'] = $reply;
                    break;
                // }}}
                case self::DCC7_INFO:
                    break;
                case self::DCC7_NEW:
                    break;
                case self::DCC7_ACCEPT:
                    break;
                case self::DCC7_REJECT:
                    break;
                case self::DCC7_ID_REPLY:
                    break;
                case self::DCC7_ABORTED:
                    break;
                case self::XML_EVENT:
                    break;
                // {{{ STATUS80BETA
                case self::STATUS80BETA:
                    $event['type'] = self::EVENT_STATUS;
                    $event['event']['uin'] = $packet->uin;
                    $event['event']['status'] = $packet->status;
                    $event['event']['description'] = $packet->description;
                    break;
                // }}}
                // {{{ NOTIFY_REPLY80BETA
                case self::NOTIFY_REPLY80BETA:
                    $event['type'] = self::EVENT_NOTIFY;
                    $reply = array();
                    if ($packet->reply != array()) {
                        foreach ($packet->reply as $r) {
                            $reply[$r->uin] = array(
                                'uin' => $r->uin,
                                'status' => $r->status,
                                'description' => $r->description,
                            );
                        }
                    }
                    $event['event']['reply'] = $reply;
                    break;
                // }}}
                case self::XML_ACTION:
                    break;
                // {{{ RECV_MSG80
                case self::RECV_MSG80:
                    $event['type'] = self::EVENT_MSG;
                    $event['event']['sender'] = $packet->sender;
                    $message = $packet->message_html;
                    if (empty($message)) {
                        $message = $packet->message_plain;
                        foreach ($packet->appends as $i => $p) {
                            if (get_class($p) == 'GG_MsgRichtext_Packet_Part') {
                                $message = $this->richtext2html($message, $p);
                                break;
                            }
                        }
                        $message = win2utf($message);
                    }
                    $recipients = array();
                    if ($packet->appends != array()) {
                        foreach ($packet->appends as $i => $p) {
                            switch (get_class($p)) {
                                case 'GG_MsgRecipients_Packet_Part':
                                    $recipients = $p->recipients;
                                    break;
                                case 'GG_MsgImageRequest_Packet_Part':
                                    $event['type'] = self::EVENT_IMAGE_REQUEST;
                                    $event['event']['id'] = dec2hex($p->crc32, 8) . dec2hex($p->size, 8);
                                    $event['event']['size'] = $p->size;
                                    $event['event']['crc32'] = $p->crc32;
                                    break;
                                case 'GG_MsgImageReply_Packet_Part':
                                    $event['type'] = self::EVENT_IMAGE_REPLY;
                                    $event['event']['id'] = dec2hex($p->crc32, 8) . dec2hex($p->size, 8);
                                    $event['event']['size'] = $p->size;
                                    $event['event']['crc32'] = $p->crc32;
                                    $event['event']['filename'] = $p->filename;
                                    $event['event']['image'] = $p->image;
                                    break;
                            }
                        }
                    }
                    if ($event['type'] == self::EVENT_MSG) {
                        $event['event']['msgclass'] = $packet->msgclass;
                        $event['event']['message'] = $message;
                        $event['event']['recipients'] = $recipients;
                    }
                    break;
                // }}}
                // {{{ USERLIST_REPLY80
                case self::USERLIST_REPLY80:
                    $event['type'] = self::EVENT_USERLIST;
                    $event['event']['type'] = $packet->type;
                    $event['event']['reply'] = empty($packet->reply) ? '' : gzuncompress($packet->reply);
                    break;
                /*
                if ($packet->type == self::USERLIST_GET_MORE_REPLY)
                {
                    if ($packet->reply)
                        $this->userlist_reply .= gzuncompress($packet->reply);
                }
                else
                {
                    if ($packet->reply)
                        $this->userlist_reply .= gzuncompress($packet->reply);
                    //*
                    if ($packet->type == self::USERLIST_GET_REPLY && $this->userlist_reply == '')
                    {
                        $packet = new GG_UserlistRequest_Packet();
                        $packet->type = self::USERLIST_GET;
                        $packet->request = '';
                        $this->send_packet($packet);
                    }
                     * /
                    $event['type'] = self::EVENT_USERLIST;
                    $event['event']['reply']= $this->userlist_reply;
                    $this->userlist_reply = '';
                }
                break;
                 */
                // }}}
                // {{{ STATUS80
                case self::STATUS80:
                    $event['type'] = self::EVENT_STATUS;
                    $event['event']['uin'] = $packet->uin;
                    $event['event']['status'] = $packet->status;
                    $event['event']['description'] = $packet->description;
                    break;
                // }}}
                // {{{ NOTIFY_REPLY80
                case self::NOTIFY_REPLY80:
                    $event['type'] = self::EVENT_NOTIFY;
                    $reply = array();
                    if ($packet->reply != array()) {
                        foreach ($packet->reply as $r) {
                            $reply[$r->uin] = array(
                                'uin' => $r->uin,
                                'status' => $r->status,
                                'descriprion' => $r->description,
                            );
                        }
                    }
                    $event['event']['reply'] = $reply;
                    break;
                // }}}
            }
            return $event;
        }
        return null;
    }

    public function ping()
    {
        if (!$this->check_connection())
            return;
        $this->send_packet(new GG_Ping_Packet());
    }

    public function richtext2html($str, $richtext)
    {
        $ret = '';
        $pos = 0;
        $color = '000000';
        $offset = 0;
        $f = 0;
        foreach ($richtext->format as $ff) {
            $ret .= substr($str, $pos, $ff->position - $pos);
            $pos = $ff->position;
            if ($f & ~$ff->font & self::FONT_COLOR) {
                if ($color != '000000') {
                    $color = '000000';
                    $ret .= '</span>';
                }
            }
            if ($f & ~$ff->font & self::FONT_UNDERLINE) $ret .= '</u>';
            if ($f & ~$ff->font & self::FONT_ITALIC) $ret .= '</i>';
            if ($f & ~$ff->font & self::FONT_BOLD) $ret .= '</b>';
            if (~$f & $ff->font & self::FONT_BOLD) $ret .= '<b>';
            if (~$f & $ff->font & self::FONT_ITALIC) $ret .= '<i>';
            if (~$f & $ff->font & self::FONT_UNDERLINE) $ret .= '<u>';
            if (~$f & $ff->font & self::FONT_COLOR) {
                $color = dec2hex($ff->red) . dec2hex($ff->green) . dec2hex($ff->blue);
                if ($color == '000000') {
                    $ff->font &= ~self::FONT_COLOR;
                } else {
                    $ret .= '<span style="color:#' . $color . '">';
                }
            }
            if (~$f & $ff->font & self::FONT_IMAGE) {
                $ret .= '<img name="' . dec2hex($ff->image_crc32, 8) . dec2hex($ff->image_size, 8) . '">';
            }
            if ($f & $ff->font & self::FONT_COLOR) {
                $c = dec2hex($ff->red) . dec2hex($ff->green) . dec2hex($ff->blue);
                if ($color != $c) {
                    $color = $c;
                    $ret .= '</span>';
                    if ($color == '000000') {
                        $ff->font &= ~self::FONT_COLOR;
                    } else {
                        $ret .= '<span style="color:#' . $color . '">';
                    }
                }
            }
            $f = (int)$ff->font;
        }
        if ($pos < strlen($str)) $ret .= substr($str, $pos);
        if ($f > 0) {
            if ($f & self::FONT_BOLD) $ret .= '</b>';
            if ($f & self::FONT_ITALIC) $ret .= '</i>';
            if ($f & self::FONT_UNDERLINE) $ret .= '</u>';
            if ($f & self::FONT_COLOR) $ret .= '</span>';
        }
        return $ret;
    }

    public function uin($uin)
    {
        // check state
        $this->uin = $uin;
    }
    // }}}
    // {{{ login_hash48()

    public function password($password)
    {
        // check state
        $this->password = $password;
    }
    // }}}
    // {{{ login_hash()

    public function status($status)
    {
        // check state
        $this->status = $status;
    }
    // }}}
    // {{{ login_hash_sha1()

    public function descr($descr)
    {
        // check state
        $this->descr = $descr;
    }
    // }}}
    // {{{ html2richtext()

    public function last_sysmsg($last_sysmsg = null)
    {
        // check state
        $old = $this->last_sysmsg;
        if ($last_sysmsg !== null)
            $this->last_sysmsg = $last_sysmsg;
        return $old;
    }
    // }}}
    // {{{ richtext2html()

    function login_hash48($password, $seed)
    {
        $hash = 1;
        for ($i = 0; $i < strlen($password); $i++) {
            $hash *= ord($password[$i]) + 1;
            //if ($this->debug_level)
            //    echo 'hash: '.$hash.' - c: '.ord($password[$i]).CRLF;
        }
        $hash *= $seed;
        $h1 = $hash;
        /*
        if ($this->debug_level) {
            echo 'h %  16: '.($hash % 16).CRLF;
            echo 'h /  16: '.($hash / 16).CRLF;
            echo 'h % 256: '.($hash % 256).CRLF;
            echo 'h / 256: '.($hash / 256).CRLF;
            echo 'h % 0x1000: '.($hash % 0x1000).CRLF;
            echo 'h / 0x1000: '.($hash / 0x1000).CRLF;
            echo 'h % 0x10000: '.($hash % 0x10000).CRLF;
            echo 'h / 0x10000: '.($hash / 0x10000).CRLF;
            echo 'h % 0x100000: '.($hash % 0x100000).CRLF;
            echo 'h / 0x100000: '.($hash / 0x100000).CRLF;
            echo 'h % 0x1000000: '.($hash % 0x1000000).CRLF;
            echo 'h / 0x1000000: '.($hash / 0x1000000).CRLF;
            echo 'hash_last: '.$hash.' ('.($h1).') - seed: '.$seed.CRLF;
        }
         */
        return $hash & 0xffffffff;
    }
    // }}}
    // {{{ 2str functions

    public function event2str($event)
    {
        $str = '';
        switch ($event) {
            case self::EVENT_NONE:
                $str = 'EVENT_NONE';
                break;
            case self::EVENT_MSG:
                $str = 'EVENT_MSG';
                break;
            case self::EVENT_NOTIFY:
                $str = 'EVENT_NOTIFY';
                break;
            case self::EVENT_STATUS:
                $str = 'EVENT_STATUS';
                break;
            case self::EVENT_ACK:
                $str = 'EVENT_ACK';
                break;
            case self::EVENT_PONG:
                $str = 'EVENT_PONG';
                break;
            case self::EVENT_DISCONNECT:
                $str = 'EVENT_DISCONNECT';
                break;
            case self::EVENT_PUBDIR:
                $str = 'EVENT_PUBDIR';
                break;
            case self::EVENT_USERLIST:
                $str = 'EVENT_USERLIST';
                break;
            case self::EVENT_IMAGE_REQUEST:
                $str = 'EVENT_IMAGE_REQUEST';
                break;
            case self::EVENT_IMAGE_REPLY:
                $str = 'EVENT_IMAGE_REPLY';
                break;
        }
        return $str . ' = 0x' . dechex($event);
    }

    public function flags2str($flags)
    {
        $str = array();
        if ($flags & self::FLAG_UNKNOW)
            $str[] = 'FLAG_UNKNOW';
        if ($flags & self::FLAG_VIDEO)
            $str[] = 'FLAG_VIDEO';
        if ($flags & self::FLAG_MOBILE)
            $str[] = 'FLAG_MOBILE';
        if ($flags & self::FLAG_WIDGET)
            $str[] = 'FLAG_WIDGET';
        if ($flags & self::STATUS_VOICE_MASK)
            $str[] = 'STATUS_VOICE_MASK';
        if ($flags & 0x02000000)
            $str[] = '0x2000000';
        if ($flags & 0x40000000)
            $str[] = '0x40000000';
        if ($flags & 0x80000000)
            $str[] = '0x80000000';
        return implode(', ', $str) . ' = 0x' . dechex($flags);
    }

    public function features2str($features)
    {
        $str = array();
        if ($features & self::FEATURE_STATUS80BETA)
            $str[] = 'FEATURE_STATUS80BETA';
        if ($features & self::FEATURE_MSG80)
            $str[] = 'FEATURE_MSG80';
        if ($features & self::FEATURE_STATUS80)
            $str[] = 'FEATURE_STATUS80';
        if ($features & self::FEATURE_DND_FFC)
            $str[] = 'FEATURE_DND_FFC';
        if ($features & self::FEATURE_DESCR80)
            $str[] = 'FEATURE_DESCR80';
        return implode(', ', $str) . ' = 0x' . dechex($features);
    }

    public function status2str($status)
    {
        $str = '';
        switch (self::S($status)) {
            case self::STATUS_AVAIL:
                $str = 'STATUS_AVAIL';
                break;
            case self::STATUS_AVAIL_DESCR:
                $str = 'STATUS_AVAIL_DESCR';
                break;
            case self::STATUS_FFC:
                $str = 'STATUS_FFC';
                break;
            case self::STATUS_FFC_DESCR:
                $str = 'STATUS_FFC_DESCR';
                break;
            case self::STATUS_BUSY:
                $str = 'STATUS_BUSY';
                break;
            case self::STATUS_BUSY_DESCR:
                $str = 'STATUS_BUSY_DESCR';
                break;
            case self::STATUS_DND:
                $str = 'STATUS_DND';
                break;
            case self::STATUS_DND_DESCR:
                $str = 'STATUS_DND_DESCR';
                break;
            case self::STATUS_INVISIBLE:
                $str = 'STATUS_INVISIBLE';
                break;
            case self::STATUS_INVISIBLE_DESCR:
                $str = 'STATUS_INVISIBLE_DESCR';
                break;
            case self::STATUS_NOT_AVAIL:
                $str = 'STATUS_NOT_AVAIL';
                break;
            case self::STATUS_NOT_AVAIL_DESCR:
                $str = 'STATUS_NOT_AVAIL_DESCR';
                break;
            case self::STATUS_BLOCKED:
                $str = 'STATUS_BLOCKED';
                break;
        }
        if ($status & self::STATUS_FRIENDS_MASK)
            $str .= ', STATUS_FRIENDS_MASK';
        if ($status & self::STATUS_VOICE_MASK)
            $str .= ', STATUS_VOICE_MASK';
        if ($status & self::STATUS80_DESCR_MASK)
            $str .= ', STATUS80_DESCR_MASK';
        if ($status & self::STATUS80_GRAPHIC_DESCR_MASK)
            $str .= ', STATUS80_GRAPHIC_DESCR_MASK';
        return $str . ' = 0x' . dechex($status);
    }

    public function type2str($type)
    {
        $str = '';
        switch ($type) {
            case self::USER_OFFLINE:
                $str = 'USER_OFFLINE';
                break;
            case self::USER_NORMAL:
                $str = 'USER_NORMAL';
                break;
            case self::USER_BLOCKED:
                $str = 'USER_BLOCKED';
                break;
        }
        return $str . ' = 0x' . dechex($type);
    }

    public function msgclass2str($class)
    {
        $str = array();
        if ($class & self::CLASS_QUEUED)
            $str[] = 'CLASS_QUEUED';
        if ($class & self::CLASS_MSG)
            $str[] = 'CLASS_MSG';
        if ($class & self::CLASS_CHAT)
            $str[] = 'CLASS_CHAT';
        if ($class & self::CLASS_CTCP)
            $str[] = 'CLASS_CTCP';
        if ($class & self::CLASS_ACK)
            $str[] = 'CLASS_ACK';
        if ($class & self::CLASS_WIDGET)
            $str[] = 'CLASS_WIDGET';
        return implode(', ', $str) . ' = 0x' . dechex($class);
    }

    public function ack2str($ack)
    {
        $str = '';
        switch ($ack) {
            case self::ACK_BLOCKED:
                $str = 'ACK_BLOKED';
                break;
            case self::ACK_DELIVERED:
                $str = 'ACK_DELIVERED';
                break;
            case self::ACK_QUEUED:
                $str = 'ACK_QUEUED';
                break;
            case self::ACK_MBOXFULL:
                $str = 'ACK_MBOXFULL';
                break;
            case self::ACK_NOT_DELIVERED:
                $str = 'ACK_NOT_DELIVERED';
                break;
        }
        return $str . ' = 0x' . dechex($ack);
    }
    // }}}
}

class GG_Event
{
}

?>
