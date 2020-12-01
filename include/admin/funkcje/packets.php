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
 * packets.php
 */

if (!defined('LF'))
    define('LF', "\n");

function get_recv_packet($pack)
{
    if (empty($pack))
        return false;
    $recv_packet_type = array(
        0x01 => 'GG_Welcome_Packet',
        0x02 => 'GG_Status_Packet',
        0x03 => 'GG_LoginOK_Packet',
        0x05 => 'GG_SendMsgAck_Packet',
        0x07 => 'GG_Pong_Packet',
        0x08 => 'GG_Ping_Packet',
        0x09 => 'GG_LoginFailed_Packet',
        0x0a => 'GG_RecvMsg_Packet',
        0x0b => 'GG_Disconnecting_Packet',
        0x0c => 'GG_NotifyReply_Packet',
        0x0d => 'GG_DisconnectingAck_Packet',
        0x0e => 'GG_Pubdir50Reply_Packet',
        0x0f => 'GG_Status60_Packet',
        0x10 => 'GG_UserlistReply_Packet',
        0x11 => 'GG_NotifyReply60_Packet',
        0x14 => 'GG_NeedEmail_Packet',
        0x16 => 'GG_LoginHashTypeInvalid_Packet',
        0x17 => 'GG_Status77_Packet',
        0x18 => 'GG_NotifyReply77_Packet',
        0x1f => 'GG_DCC7_Info_Packet',
        0x20 => 'GG_DCC7_New_Packet',
        0x21 => 'GG_DCC7_Accept_Packet',
        0x22 => 'GG_DCC7_Reject_Packet',
        0x23 => 'GG_DCC7_IdReply_Packet',
        0x25 => 'GG_DCC7_Aborted_Packet',
        0x27 => 'GG_XmlEvent_Packet',
        0x2a => 'GG_Status80Beta_Packet',
        0x2b => 'GG_NotifyReply80Beta_Packet',
        0x2c => 'GG_XmlAction_Packet',
        0x2e => 'GG_RecvMsg80_Packet',
        0x30 => 'GG_UserlistReply80_Packet',
        0x35 => 'GG_Login80OK_Packet',
        0x36 => 'GG_Status80_Packet',
        0x37 => 'GG_NotifyReply80_Packet',
    );
    $packet_arr = unpack('Vtype/Vlength', substr($pack, 0, 8));
    $packet = new $recv_packet_type[$packet_arr['type']]($pack);
    return $packet;
}

function get_send_packet($pack)
{
    if (empty($pack))
        return false;
    $send_packet_type = array(
        0x02 => 'GG_NewStatus_Packet',
        0x07 => 'GG_Pong_Packet',
        0x08 => 'GG_Ping_Packet',
        0x0b => 'GG_SendMsg_Packet',
        0x0c => 'GG_Login_Packet',
        0x0d => 'GG_AddNotify_Packet',
        0x0e => 'GG_RemoveNotify_Packet',
        0x0f => 'GG_NotifyFirst_Packet',
        0x10 => 'GG_NotifyLast_Packet',
        0x12 => 'GG_ListEmpty_Packet',
        0x13 => 'GG_LoginExt_Packet',
        0x14 => 'GG_Pubdir50Request_Packet',
        0x15 => 'GG_Login60_Packet',
        0x16 => 'GG_UserlistRequest_Packet',
        0x19 => 'GG_Login70_Packet',
        0x1f => 'GG_DCC7_Info_Packet',
        0x20 => 'GG_DCC7_New_Packet',
        0x21 => 'GG_DCC7_Accept_Packet',
        0x22 => 'GG_DCC7_Reject_Packet',
        0x23 => 'GG_DCC7_IdRequest_Packet',
        0x24 => 'GG_DCC7_Dunno1_Packet',
        0x25 => 'GG_DCC7_Abort_Packet',
        0x28 => 'GG_NewStatus80Beta_Packet',
        0x29 => 'GG_Login80Beta_Packet',
        0x2d => 'GG_SendMsg80_Packet',
        0x2f => 'GG_UserlistRequest80_Packet',
        0x31 => 'GG_Login80_Packet',
        0x38 => 'GG_NewStatus80_Packet',
    );
    $packet_arr = unpack('Vtype/Vlength', substr($pack, 0, 8));
    $packet = new $send_packet_type[$packet_arr['type']]($pack);
    return $packet;
}

// {{{ Packet
abstract class GG_Packet
{
    protected $pack = '';
    protected $packet = array('type' => 0, 'length' => 0, 'data' => array());
    protected $type = 0;
    protected $length = 0;
    protected $struct = array();
    protected $format = '';

    public function __construct($pack = null)
    {
        if (is_string($pack)) {
            $this->pack = $pack;
            $this->packet = unpack('Vtype/Vlength', $pack);
            $this->pack .= str_repeat("\0", $this->packet['length'] + 8 - strlen($this->pack));
            $this->packet['data'] = array();
            if ($this->packet['length'] > 0) {
                if (method_exists($this, 'unpack'))
                    $this->unpack();
                else {
                    $format = array();
                    foreach ($this->struct as $k => $v) {
                        $format[] = $v['pack'] . $k;
                    }
                    $this->format = implode('/', $format);
                    $this->packet['data'] = unpack($this->format, substr($pack, 8));
                }
            }
            //print_r($this->packet);
        } else if (is_array($pack)) {
            foreach ($pack as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    public function dump()
    {
        $txt = get_class($this);
        //if ($this->packet['length'] > 0)
        {
            foreach ($this->struct as $k => $v) {
                $txt .= LF . '  ' . $k . ' => ';
                switch ($v['type']) {
                    case 'hex':
                        if (isset($v['echo']))
                            $txt .= call_user_func($v['echo'], $this->packet['data'][$k]);
                        else
                            $txt .= '0x' . dechex($this->packet['data'][$k]);
                        break;
                    case 'ip':
                        $txt .= long2ip($this->packet['data'][$k]);
                        break;
                    case 'binary':
                        $bin = '';
                        for ($i = 0; $i < strlen($this->packet['data'][$k]); $i++) {
                            $b = ord($this->packet['data'][$k][$i]);
                            $bin .= ($b < 0x10 ? '0' : '') . dechex($b);
                        }
                        $txt .= '(' . strlen($this->packet['data'][$k]) . ') ' . chunk_split($bin, 4, ' ');
                        break;
                    case 'date':
                        $txt .= date('r', $this->packet['data'][$k]);
                        break;
                    case 'string':
                        $txt .= '(' . strlen($this->packet['data'][$k]) . ') ' . htmlspecialchars($this->packet['data'][$k]);
                        break;
                    case 'array':
                        if (!empty($this->packet['data'][$k])) {
                            foreach ($this->packet['data'][$k] as $v) {
                                $txt .= LF . $v->dump();
                            }
                        }
                        break;
                    case 'int':
                    default:
                        $txt .= $this->packet['data'][$k];
                }
            }
        }
        return $txt;
    }

    public function binary()
    {
        if (method_exists($this, 'pack'))
            $this->pack();
        else {
            $pack = '';
            $packet = array();
            foreach ($this->struct as $k => $v) {
                if ($v['type'] == 'array') {
                    foreach ($this->packet['data'][$k] as $p) {
                        $packet[] = $p->binary();
                    }
                } else {
                    $packet[] = pack($v['pack'], $this->packet['data'][$k]);
                }
            }
            $pack = implode('', $packet);
            $this->pack = pack('VV', $this->type, $this->length) . $pack;
        }
        return $this->pack;
    }

    public function dump_binary()
    {
        $v = $this->pack;
        $bin = '';
        for ($i = 0; $i < strlen($v); $i++) {
            $b = ord($v[$i]);
            $bin .= ($b < 0x10 ? '0' : '') . dechex($b);
        }
        return chunk_split($bin, 4, ' ');
    }

    public function __get($k)
    {
        if (isset($this->packet['data'][$k]))
            return $this->packet['data'][$k];
        else if ($k == 'packet_type')
            return $this->packet['type'];
        else if ($k == 'packet_length')
            return $this->packet['length'];
        else
            return false;
    }

    public function __set($k, $v)
    {
        if (isset($this->struct[$k])) {
            if ($this->struct[$k]['type'] == 'array') {
                if (!is_array($v)) {
                    throw new Exception('value should be an array of objects');
                } else {
                    if (is_array(reset($v))) {
                        foreach ($v as $vv) {
                            $p = new $this->struct[$k]['pack']($vv);
                            $this->length += $p->packet_length;
                            $this->packet['data'][$k][] = $p;
                        }
                    } else {
                        foreach ($v as $p) {
                            $this->length += $p->packet_length;
                            $this->packet['data'][$k][] = $p;
                        }
                    }
                }
            } else {
                $old_v = empty($this->packet['data'][$k]) ? '' : $this->packet['data'][$k];
                $this->packet['data'][$k] = $v;
                switch ($this->struct[$k]['pack'][0]) {
                    case 'C':
                        if (empty($old_v))
                            $this->length += 1;
                        break;
                    case 'v':
                        if (empty($old_v))
                            $this->length += 2;
                        break;
                    case 'V':
                    case 'N':
                        if (empty($old_v))
                            $this->length += 4;
                        break;
                    case 'a':
                        $l = substr($this->struct[$k]['pack'], 1);
                        if ($l == '*') {
                            if (empty($old_v))
                                $this->length -= strlen($old_v);
                            $this->length += strlen($v);
                        } else {
                            if (empty($old_v))
                                $this->length += $l;
                        }
                        break;
                }
            }
        }
    }
}

// }}}
// {{{ Packet_Part
abstract class GG_Packet_Part
{
    protected $pack = '';
    protected $struct = array();
    protected $data = array();
    protected $length = 0;

    public function __construct($pack = null)
    {
        if (is_string($pack)) {
            $this->pack = $pack;
            //$this->pack .= str_repeat("\0", $this->packet['length']+8-strlen($this->pack));
            $this->data = array();
            if (method_exists($this, 'unpack'))
                $this->unpack();
            else {
                $format = array();
                foreach ($this->struct as $k => $v) {
                    $format[] = $v['pack'] . $k;
                    switch ($v['pack']) {
                        case 'C':
                            $this->length += 1;
                            break;
                        case 'v':
                            $this->length += 2;
                            break;
                        case 'V':
                        case 'N':
                            $this->length += 4;
                            break;
                    }
                }
                $this->format = implode('/', $format);
                $this->data = unpack($this->format, $pack);
            }
            $this->pack = substr($this->pack, 0, $this->length);
        } else if (is_array($pack)) {
            foreach ($pack as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    public function binary()
    {
        if (method_exists($this, 'pack'))
            $this->pack();
        else {
            $pack = '';
            $packet = array();
            foreach ($this->struct as $k => $v) {
                if ($v['type'] == 'array') {
                    foreach ($this->data[$k] as $p) {
                        $packet[] = $p->binary();
                    }
                } else {
                    $packet[] = pack($v['pack'], $this->data[$k]);
                }
            }
            $pack = implode('', $packet);
            $this->pack = $pack;
        }
        return $this->pack;
    }

    public function dump($indent = '')
    {
        $txt = $indent . '  ' . get_class($this);
        //if ($this->packet['length'] > 0)
        {
            foreach ($this->struct as $k => $v) {
                if (!isset($this->data[$k]))
                    continue;
                $txt .= LF . $indent . '    ' . $k . ' => ';
                switch ($v['type']) {
                    case 'hex':
                        if (isset($v['echo']))
                            $txt .= call_user_func($v['echo'], $this->data[$k]);
                        else
                            $txt .= '0x' . dechex($this->data[$k]);
                        break;
                    case 'ip':
                        $txt .= long2ip($this->data[$k]);
                        break;
                    case 'binary':
                        $bin = '';
                        for ($i = 0; $i < strlen($this->data[$k]); $i++) {
                            $b = ord($this->data[$k][$i]);
                            $bin .= ($b < 0x10 ? '0' : '') . dechex($b);
                        }
                        $txt .= '(' . strlen($this->data[$k]) . ') ' . chunk_split($bin, 4, ' ');
                        break;
                    case 'date':
                        $txt .= date('r', $this->data[$k]);
                        break;
                    case 'string':
                        $txt .= '(' . strlen($this->data[$k]) . ') ' . htmlspecialchars($this->data[$k]);
                        break;
                    case 'array':
                        if (!empty($this->data[$k])) {
                            foreach ($this->data[$k] as $v) {
                                if ($this->struct[$k]['pack'] == 'int') {
                                    $txt .= LF . $indent . '      ' . $v;
                                } else {
                                    $txt .= LF . $v->dump($indent . '  ');
                                }
                            }
                        }
                        break;
                    case 'int':
                    default:
                        $txt .= $this->data[$k];
                }
            }
        }
        return $txt;
    }

    public function __get($k)
    {
        if (isset($this->data[$k]))
            return $this->data[$k];
        else if ($k == 'packet_length')
            return $this->length;
        return null;
    }

    public function __set($k, $v)
    {
        if (isset($this->struct[$k])) {
            if ($this->struct[$k]['type'] == 'array') {
                if (!is_array($v)) {
                    throw new Exception('value (' . $k . ', ' . print_r($v, 1) . ') should be an array of objects');
                } else {
                    if (is_array(reset($v))) {
                        foreach ($v as $vv) {
                            $p = new $this->struct[$k]['pack']($vv);
                            $this->length += $p->packet_length;
                            $this->data[$k][] = $p;
                        }
                    } else {
                        foreach ($v as $p) {
                            if ($this->struct[$k]['pack'] == 'int') {
                                $this->length += 4;
                                $this->data[$k][] = $p;
                            } else {
                                $this->length += $p->packet_length;
                                $this->data[$k][] = $p;
                            }
                        }
                    }
                }
            } else {
                $old_v = empty($this->data[$k]) ? '' : $this->data[$k];
                $this->data[$k] = $v;
                switch ($this->struct[$k]['pack'][0]) {
                    case 'C':
                        if (empty($old_v))
                            $this->length += 1;
                        break;
                    case 'v':
                        if (empty($old_v))
                            $this->length += 2;
                        break;
                    case 'V':
                    case 'N':
                        if (empty($old_v))
                            $this->length += 4;
                        break;
                    case 'a':
                        $len = substr($this->struct[$k]['pack'], 1);
                        if ($len == '*') {
                            if (empty($old_v))
                                $this->length -= strlen($old_v);
                            $this->length += strlen($v);
                        } else {
                            if (empty($old_v))
                                $this->length += $len;
                        }
                        break;
                }
            }
        }
    }
}

// }}}
// {{{ Recv Packets
class GG_Welcome_Packet extends GG_Packet
{
    protected $type = 0x01;
    protected $struct = array(
        'seed' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_Status_Packet extends GG_Packet
{
    protected $type = 0x02;
}

class GG_LoginOK_Packet extends GG_Packet
{
    protected $type = 0x03;
    protected $struct = array(
        'dunno' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
    );
}

class GG_SendMsgAck_Packet extends GG_Packet
{
    protected $type = 0x05;
    protected $struct = array(
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'ack2str'),
        ),
        'recipient' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_Pong_Packet extends GG_Packet
{
    protected $type = 0x07;
}

class GG_Ping_Packet extends GG_Packet
{
    protected $type = 0x08;
}

class GG_LoginFailed_Packet extends GG_Packet
{
    protected $type = 0x09;
}

class GG_MsgRecipients_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'flag' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'count' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'recipients' => array(
            'pack' => 'int',
            'type' => 'array',
        ),
    );

    protected function pack()
    {
        $this->pack = '';
        $count = 0;
        if (!empty($this->data['recipients'])) {
            foreach ($this->data['recipients'] as $p) {
                $this->pack .= pack('V', $p);
                $count++;
            }
        }
        $this->data['flag'] = 0x01;
        $this->data['count'] = $count;
        $this->length = 5 + 4 * $count;
        $this->pack = pack('CV', 0x01, $count) . $this->pack;
    }

    protected function unpack()
    {
        $format = 'Cflag/Vcount';
        $this->data = unpack($format, $this->pack);
        $this->length = 5 + 4 * $this->data['count'];
        $pack = substr($this->pack, 5);
        $this->data['recipients'] = array();
        for ($i = 0; $i < $this->data['count']; $i++) {
            $p = unpack('V', $pack);
            $this->data['recipients'][] = $p[1];
            $pack = substr($pack, 4);
        }
    }
}

class GG_MsgRichtext_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'flag' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'length' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'format' => array(
            'pack' => 'GG_MsgRichtextFormat_Packet_Part',
            'type' => 'array',
        ),
    );

    protected function pack()
    {
        $this->pack = '';
        $length = 0;
        if (!empty($this->data['format'])) {
            foreach ($this->data['format'] as $p) {
                $this->pack .= $p->binary();
                $length += $p->packet_length;
            }
        }
        $this->data['flag'] = 0x02;
        $this->data['length'] = $length;
        $this->pack = pack('Cv', 0x02, $length) . $this->pack;
        $this->length = 3 + $length;
    }

    protected function unpack()
    {
        $format = 'Cflag/vlength';
        $this->data = unpack($format, $this->pack);
        $this->length = 3 + $this->data['length'];
        $pack = substr($this->pack, 3);
        $this->data['format'] = array();
        $len = $this->data['length'];
        while ($len > 0) {
            $p = new GG_MsgRichtextFormat_Packet_Part($pack);
            $this->data['format'][] = $p;
            $pack = substr($pack, $p->packet_length);
            $len -= $p->packet_length;
        }
    }
}

class GG_MsgRichtextFormat_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'position' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'font' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'red' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'green' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'blue' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_unknow1' => array(
            'pack' => 'v',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'image_crc32' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );

    protected function pack()
    {
        $this->pack = pack('vC', $this->data['position'], $this->data['font']);
        $this->length = 3;
        if ($this->data['font'] & 0x08) {
            $this->pack .= pack('CCC', $this->data['red'], $this->data['green'], $this->data['blue']);
            $this->length += 3;
        }
        if ($this->data['font'] & 0x80) {
            $this->pack .= pack('vVV', 0x109, $this->data['image_size'], $this->data['image_crc32']);
            $this->length += 10;
        }
    }

    protected function unpack()
    {
        $format = 'vposition/Cfont';
        $this->data = unpack($format, $this->pack);
        $this->length = 3;
        if ($this->data['font'] & 0x08) {
            $this->data = array_merge($this->data, unpack('Cred/Cgreen/Cblue', substr($this->pack, $this->length)));
            $this->length += 3;
        }
        if ($this->data['font'] & 0x80) {
            $this->data = array_merge($this->data, unpack('vimage_unknow1/Vimage_size/Vimage_crc32', substr($this->pack, $this->length)));
            $this->length += 10;
        }
    }
}

class GG_MsgImageRequest_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'flag' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'crc32' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );

    protected function pack()
    {
        $this->pack = '';
        $this->data['flag'] = 0x04;
        $this->pack = pack('CVV', 0x04, $this->data['size'], $this->data['crc32']);
        $this->length = 9;
    }

    protected function unpack()
    {
        $format = 'Cflag/Vsize/Vcrc32';
        $this->data = unpack($format, $this->pack);
        $this->length = 9;
    }
}

class GG_MsgImageReply_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'flag' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'crc32' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'filename' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'image' => array(
            'pack' => 'a*',
            'type' => 'binary',
        ),
    );

    protected function pack()
    {
        $this->pack = '';
        $this->pack = pack('CVVa*a*', $this->data['flag'], $this->data['size'], $this->data['crc32'], $this->data['filename'] . ($this->data['flag'] == 0x05 ? "\0" : ''), $this->data['image']);
        $this->length = strlen($this->pack);//9+strlen($this->data['filename'])+strlen($this->data['image']);
    }

    protected function unpack()
    {
        $format = 'Cflag/Vsize/Vcrc32';
        $this->data = unpack($format, $this->pack);
        $nullpos = 9;
        $this->data['filename'] = '';
        if ($this->data['flag'] == 0x05) {
            $nullpos = strpos($this->pack, "\0", 9);
            $this->data = array_merge($this->data, unpack('a' . ($nullpos - 9) . 'filename', substr($this->pack, 9)));
            $nullpos++;
        }
        $this->data = array_merge($this->data, unpack('a*image', substr($this->pack, $nullpos)));
        $this->length = $nullpos + strlen($this->data['image']);
    }
}

class GG_RecvMsg_Packet extends GG_Packet
{
    protected $type = 0x0a;
    protected $struct = array(
        'sender' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'time' => array(
            'pack' => 'V',
            'type' => 'date',
        ),
        'msgclass' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'msgclass2str'),
        ),
        'message' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'appends' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $format = 'Vsender/Vseq/Vtime/Vmsgclass';
        $this->packet['data'] = unpack($format, substr($this->pack, 8));
        $nullpos = strpos(substr($this->pack, 24), "\0");
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . $nullpos . 'message', substr($this->pack, 24)));
        $pack = substr($this->pack, 25 + $nullpos);
        while (strlen($pack) > 0) {
            $packet = unpack('Cflag', $pack);
            switch ($packet['flag']) {
                case 0x01:
                    $p = new GG_MsgRecipients_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x02:
                    $p = new GG_MsgRichtext_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x04:
                    $p = new GG_MsgImageRequest_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x05:
                case 0x06:
                    $p = new GG_MsgImageReply_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                default:
                    $pack = '';
            }
        }
    }
}

class GG_Disconnecting_Packet extends GG_Packet
{
    protected $type = 0x0b;
}

class GG_NotifyReply_Packet extends GG_Packet
{
    protected $type = 0x0c;
}

class GG_DisconnectingAck_Packet extends GG_Packet
{
    protected $type = 0x0d;
}

class GG_Pubdir50Reply_Packet extends GG_Packet
{
    protected $type = 0x0e;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'reply' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_Status60_Packet extends GG_Packet
{
    protected $type = 0x0f;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_UserlistReply_Packet extends GG_Packet
{
    protected $type = 0x10;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'reply' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_NotifyReply60_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );

    protected function unpack()
    {
        $format = 'Vuin/Cstatus/Nremote_addr/vremote_port/Cversion/Cimage_size/Cunknow1';
        $this->data = unpack($format, $this->pack);
        $this->length = 14;
        if (in_array($this->data['status'], array(0x15, 0x18, 0x4, 0x5, 0x22, 0x16))) {
            $this->data = array_merge($this->data, unpack('Cdescription_size', substr($this->pack, 14)));
            $this->data = array_merge($this->data, unpack('a' . $this->data['description_size'] . 'description', substr($this->pack, 15)));
            $this->length += 1 + $this->data['description_size'];
        }
    }
}

class GG_NotifyReply60_Packet extends GG_Packet
{
    protected $type = 0x11;
    protected $struct = array(
        'reply' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $pack = substr($this->pack, 8);
        while (strlen($pack) > 0) {
            $p = new GG_NotifyReply60_Packet_Part($pack);
            $this->packet['data']['reply'][] = $p;
            $pack = substr($pack, $p->packet_length);
        }
    }
}

class GG_NeedEmail_Packet extends GG_Packet
{
    protected $type = 0x14;
}

class GG_Status77_Packet extends GG_Packet
{
    protected $type = 0x17;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_NotifyReply77_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );

    protected function unpack()
    {
        $format = 'Vuin/Cstatus/Nremote_addr/vremote_port/Cversion/Cimage_size/Cunknow1/Vflags';
        $this->data = unpack($format, $this->pack);
        $this->length = 18;
        if (in_array($this->data['status'], array(0x15, 0x18, 0x4, 0x5, 0x22, 0x16))) {
            $this->data = array_merge($this->data, unpack('Cdescription_size', substr($this->pack, 18)));
            $this->data = array_merge($this->data, unpack('a' . $this->data['description_size'] . 'description', substr($this->pack, 19)));
            $this->length += 1 + $this->data['description_size'];
        }
    }
}

class GG_NotifyReply77_Packet extends GG_Packet
{
    protected $type = 0x18;
    protected $struct = array(
        'reply' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $pack = substr($this->pack, 8);
        while (strlen($pack) > 0) {
            $p = new GG_NotifyReply77_Packet_Part($pack);
            $this->packet['data']['reply'][] = $p;
            $pack = substr($pack, $p->packet_length);
        }
    }
}

class GG_DCC7_Info_Packet extends GG_Packet
{
    protected $type = 0x1f;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'type' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'info' => array(
            'pack' => 'a32',
            'type' => 'string',
        ),
        'info2' => array(
            'pack' => 'a32',
            'type' => 'string',
        ),
    );
}

class GG_DCC7_New_Packet extends GG_Packet
{
    protected $type = 0x20;
    protected $struct = array(
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'uin_from' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'uin_to' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'type' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'filename' => array(
            'pack' => 'a255',
            'type' => 'string',
        ),
        'size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'size_hi' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'hash' => array(
            'pack' => 'a20',
            'type' => 'binary',
        ),
    );
}

class GG_DCC7_Accept_Packet extends GG_Packet
{
    protected $type = 0x21;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'offset' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'dunno' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_DCC7_Reject_Packet extends GG_Packet
{
    protected $type = 0x22;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'reason' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_DCC7_IdReply_Packet extends GG_Packet
{
    protected $type = 0x23;
    protected $struct = array(
        'type' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
    );
}

class GG_DCC7_Aborted_Packet extends GG_Packet
{
    protected $type = 0x25;
    protected $struct = array(
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
    );
}

class GG_XmlEvent_Packet extends GG_Packet
{
    protected $type = 0x27;
    protected $struct = array(
        'event' => array(
            'pack' => 'a*',
            'type' => 'str  ing',
        ),
    );
}

class GG_Status80Beta_Packet extends GG_Packet
{
    protected $type = 0x2a;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_NotifyReply80Beta_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );

    protected function unpack()
    {
        $format = 'Vuin/Cstatus/Nremote_addr/vremote_port/Cversion/Cimage_size/Cunknow1/Vflags';
        $this->data = unpack($format, $this->pack);
        $this->length = 18;
        if (in_array($this->data['status'], array(0x15, 0x18, 0x4, 0x5, 0x22, 0x16))) {
            $this->data = array_merge($this->data, unpack('Cdescription_size', substr($this->pack, 18)));
            $this->data = array_merge($this->data, unpack('a' . $this->data['description_size'] . 'description', substr($this->pack, 19)));
            $this->length += 1 + $this->data['description_size'];
        }
    }
}

class GG_NotifyReply80Beta_Packet extends GG_Packet
{
    protected $type = 0x2b;
    protected $struct = array(
        'reply' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $pack = substr($this->pack, 8);
        while (strlen($pack) > 0) {
            $p = new GG_NotifyReply80Beta_Packet_Part($pack);
            $this->packet['data']['reply'][] = $p;
            $pack = substr($pack, $p->packet_length);
        }
    }
}

class GG_XmlAction_Packet extends GG_Packet
{
    protected $type = 0x2c;
}

class GG_RecvMsg80_Packet extends GG_Packet
{
    protected $type = 0x2e;
    protected $struct = array(
        'sender' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'time' => array(
            'pack' => 'V',
            'type' => 'date',
        ),
        'msgclass' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'msgclass2str'),
        ),
        'offset_plain' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'offset_attributes' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'message_html' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'message_plain' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'appends' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $format = 'Vsender/Vseq/Vtime/Vmsgclass/Voffset_plain/Voffset_attributes';
        $this->packet['data'] = unpack($format, substr($this->pack, 8));
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . ($this->packet['data']['offset_plain'] - 24) . 'message_html/a' . ($this->packet['data']['offset_attributes'] - 1 - $this->packet['data']['offset_plain']) . 'message_plain', substr($this->pack, 32)));
        $this->packet['data']['appends'] = array();
        $pack = substr($this->pack, 8 + $this->packet['data']['offset_attributes']);
        while (strlen($pack) > 0) {
            $packet = unpack('Cflag', $pack);
            switch ($packet['flag']) {
                case 0x01:
                    $p = new GG_MsgRecipients_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x02:
                    $p = new GG_MsgRichtext_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x04:
                    $p = new GG_MsgImageRequest_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x05:
                case 0x06:
                    $p = new GG_MsgImageReply_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                default:
                    $pack = '';
            }
        }
    }
}

class GG_UserlistReply80_Packet extends GG_Packet
{
    protected $type = 0x30;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'reply' => array(
            'pack' => 'a*',
            'type' => 'binary',
        ),
    );
}

class GG_Login80OK_Packet extends GG_Packet
{
    protected $type = 0x35;
    protected $struct = array(
        'dunno' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_Status80_Packet extends GG_Packet
{
    protected $type = 0x36;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'features' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'features2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_NotifyReply80_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'features' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'features2str'),
        ),
        'remote_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'remote_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );

    protected function unpack()
    {
        $format = 'Vuin/Vstatus/Vfeatures/Nremote_addr/vremote_port/Cimage_size/Cunknow2/Vflags/Vdescription_size';
        $this->data = unpack($format, $this->pack);
        $this->data = array_merge($this->data, unpack('a' . $this->data['description_size'] . 'description', substr($this->pack, 28)));
        $this->length = 28 + $this->data['description_size'];
    }
}

class GG_NotifyReply80_Packet extends GG_Packet
{
    protected $type = 0x37;
    protected $struct = array(
        'reply' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function unpack()
    {
        $pack = substr($this->pack, 8);
        while (strlen($pack) > 0) {
            $p = new GG_NotifyReply80_Packet_Part($pack);
            $this->packet['data']['reply'][] = $p;
            $pack = substr($pack, $p->packet_length);
        }
    }
}

// }}}
// {{{ Send Packets
class GG_NewStatus_Packet extends GG_Packet
{
    protected $type = 0x02;
    protected $struct = array(
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_SendMsg_Packet extends GG_Packet
{
    protected $type = 0x0b;
    protected $struct = array(
        'recipient' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'msgclass' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'msgclass2str'),
        ),
        'message' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'appends' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function pack()
    {
        $format = 'VVVa*C';
        $d = &$this->packet['data'];
        $this->pack = pack($format, $d['recipient'], $d['seq'], $d['msgclass'], $d['message'], "\0");
        $length = 0;
        if (!empty($this->packet['data']['appends'])) {
            foreach ($this->packet['data']['appends'] as $p) {
                $this->pack .= $p->binary();
                $length += $p->packet_length;
            }
        }
        $this->pack = pack('VV', $this->type, 13 + strlen($d['message']) + $length) . $this->pack;
    }

    protected function unpack()
    {
        $format = 'Vrecipient/Vseq/Vmsgclass';
        $this->packet['data'] = unpack($format, substr($this->pack, 8));
        $nullpos = strpos(substr($this->pack, 20), "\0");
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . $nullpos . 'message', substr($this->pack, 20)));
        $pack = substr($this->pack, 21 + $nullpos);
        while (strlen($pack) > 0) {
            $packet = unpack('Cflag', $pack);
            switch ($packet['flag']) {
                case 0x01:
                    $p = new GG_MsgRecipients_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x02:
                    $p = new GG_MsgRichtext_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x04:
                    $p = new GG_MsgImageRequest_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x05:
                case 0x06:
                    $p = new GG_MsgImageReply_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                default:
                    $pack = '';
            }
        }
    }
}

class GG_Login_Packet extends GG_Packet
{
    protected $type = 0x0c;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'password' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'local_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'local_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
    );
}

class GG_AddNotify_Packet extends GG_Packet
{
    protected $type = 0x0d;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'type2str'),
        ),
    );
}

class GG_RemoveNotify_Packet extends GG_Packet
{
    protected $type = 0x0e;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'type2str'),
        ),
    );
}

class GG_Notify_Packet_Part extends GG_Packet_Part
{
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
            'echo' => array('GG', 'type2str'),
        ),
    );
}

class GG_NotifyFirst_Packet extends GG_Packet
{
    protected $type = 0x0f;
    protected $struct = array(
        'notify' => array(
            'pack' => 'GG_Notify_Packet_Part',
            'type' => 'array',
        ),
    );
}

class GG_NotifyLast_Packet extends GG_Packet
{
    protected $type = 0x10;
    protected $struct = array(
        'notify' => array(
            'pack' => 'GG_Notify_Packet_Part',
            'type' => 'array',
        ),
    );
}

class GG_ListEmpty_Packet extends GG_Packet
{
    protected $type = 0x12;
}

class GG_LoginExt_Packet extends GG_Packet
{
    protected $type = 0x13;
}

class GG_Pubdir50Request_Packet extends GG_Packet
{
    protected $type = 0x14;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'request' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_Login60_Packet extends GG_Packet
{
    protected $type = 0x15;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'password' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'dunno1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'local_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'local_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'external_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'external_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'dunno2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'descr' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_UserlistRequest_Packet extends GG_Packet
{
    protected $type = 0x16;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'request' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_Login70_Packet extends GG_Packet
{
    protected $type = 0x19;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'hash_type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'password' => array(
            'pack' => 'a64',
            'type' => 'binary',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'dunno1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'local_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'local_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'external_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'external_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'dunno2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'descr' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_DCC7_IdRequest_Packet extends GG_Packet
{
    protected $type = 0x23;
    protected $struct = array(
        'type' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_DCC7_Dunno1_Packet extends GG_Packet
{
    protected $type = 0x24;
    protected $struct = array(
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'dunno1' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'dunno2' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'dunno3' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
    );
}

class GG_DCC7_Abort_Packet extends GG_Packet
{
    protected $type = 0x25;
    protected $struct = array(
        'id' => array(
            'pack' => 'a8',
            'type' => 'binary',
        ),
        'uin_from' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'uin_to' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
    );
}

class GG_NewStatus80Beta_Packet extends GG_Packet
{
    protected $type = 0x28;
    protected $struct = array(
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_Login80Beta_Packet extends GG_Packet
{
    protected $type = 0x29;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'hash_type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'password' => array(
            'pack' => 'a64',
            'type' => 'binary',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'dunno1' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'local_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'local_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'external_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'external_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'dunno2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'descr' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

class GG_SendMsg80_Packet extends GG_Packet
{
    protected $type = 0x2d;
    protected $struct = array(
        'recipient' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'seq' => array(
            'pack' => 'V',
            'type' => 'hex',
        ),
        'msgclass' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'msgclass2str'),
        ),
        'offset_plain' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'offset_attributes' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'message_html' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'message_plain' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'appends' => array(
            'pack' => '',
            'type' => 'array',
        ),
    );

    protected function pack()
    {
        $format = 'VVVVVa*Ca*C';
        $d = &$this->packet['data'];
        $this->pack = pack($format, $d['recipient'], $d['seq'], $d['msgclass'], $d['offset_plain'], $d['offset_attributes'], $d['message_html'], "\0", $d['message_plain'], "\0");
        $length = 0;
        if (!empty($this->packet['data']['appends'])) {
            foreach ($this->packet['data']['appends'] as $p) {
                $this->pack .= $p->binary();
                $length += $p->packet_length;
            }
        }
        $this->pack = pack('VV', $this->type, 22 + strlen($d['message_html']) + strlen($d['message_plain']) + $length) . $this->pack;
    }

    protected function unpack()
    {
        $format = 'Vrecipient/Vseq/Vmsgclass/Voffset_plain/Voffset_attributes';
        $this->packet['data'] = unpack($format, substr($this->pack, 8));
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . ($this->packet['data']['offset_plain'] - 20) . 'message_html/a' . ($this->packet['data']['offset_attributes'] - 1 - $this->packet['data']['offset_plain']) . 'message_plain', substr($this->pack, 28)));
        $pack = substr($this->pack, $this->packet['data']['offset_attributes'] + 8);
        while (strlen($pack) > 0) {
            $packet = unpack('Cflag', $pack);
            switch ($packet['flag']) {
                case 0x01:
                    $p = new GG_MsgRecipients_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x02:
                    $p = new GG_MsgRichtext_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x04:
                    $p = new GG_MsgImageRequest_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                case 0x05:
                case 0x06:
                    $p = new GG_MsgImageReply_Packet_Part($pack);
                    $this->packet['data']['appends'][] = $p;
                    $pack = substr($pack, $p->packet_length);
                    break;
                default:
                    $pack = '';
            }
        }
    }
}

class GG_UserlistRequest80_Packet extends GG_Packet
{
    protected $type = 0x2f;
    protected $struct = array(
        'type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'request' => array(
            'pack' => 'a*',
            'type' => 'binary',
        ),
    );
}

class GG_Login80_Packet extends GG_Packet
{
    protected $type = 0x31;
    protected $struct = array(
        'uin' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'language' => array(
            'pack' => 'a2',
            'type' => 'string',
        ),
        'hash_type' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'password' => array(
            'pack' => 'a64',
            'type' => 'binary',
        ),
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'features' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'features2str'),
        ),
        'local_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'local_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'external_addr' => array(
            'pack' => 'N',
            'type' => 'ip',
        ),
        'external_port' => array(
            'pack' => 'v',
            'type' => 'int',
        ),
        'image_size' => array(
            'pack' => 'C',
            'type' => 'int',
        ),
        'unknow2' => array(
            'pack' => 'C',
            'type' => 'hex',
        ),
        'version_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'version' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );

    protected function unpack()
    {
        $format = 'Vuin/a2language/Chash_type/a64password/Vstatus/Vflags/Vfeatures/Nlocal_addr/vlocal_port/Nexternal_addr/vexternal_port/Cimage_size/Cunknow2/Vversion_size';
        $this->packet['data'] = unpack($format, substr($this->pack, 8));
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . $this->packet['data']['version_size'] . 'version/Vdescription_size', substr($this->pack, 109)));
        $this->packet['data'] = array_merge($this->packet['data'], unpack('a' . $this->packet['data']['description_size'] . 'description', substr($this->pack, 113 + $this->packet['data']['version_size'])));
    }
}

class GG_NewStatus80_Packet extends GG_Packet
{
    protected $type = 0x38;
    protected $struct = array(
        'status' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'status2str'),
        ),
        'flags' => array(
            'pack' => 'V',
            'type' => 'hex',
            'echo' => array('GG', 'flags2str'),
        ),
        'description_size' => array(
            'pack' => 'V',
            'type' => 'int',
        ),
        'description' => array(
            'pack' => 'a*',
            'type' => 'string',
        ),
    );
}

// }}}
?>
