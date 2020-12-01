<?

// wylicza punkty 
function obliczenie_punktow($gral1, $gral2)
{
    znajdz_miejsca_i_pkt($gral1, $gral2);
    global $pkt_w_1, $pkt_w_2, $pkt_r_1, $pkt_r_2, $pamiec_miejsca_1, $pamiec_miejsca_2, $pamiec_punktow_1, $pamiec_punktow_2, $rezultat_1, $rezultat_2, $pkt_l_1, $pt_l_2;

    if (defined('WCOGRA')) {
        $pkt_l_1 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', pkt_l_1));
        $pkt_l_2 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', pkt_l_2));
    } else {
        if ($pamiec_miejsca_1 < $pamiec_miejsca_2) {
            $pkt_w_1 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', dwolp));
            $pkt_w_2 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', dwogp));
        }
        if ($pamiec_miejsca_1 > $pamiec_miejsca_2) {
            $pkt_w_1 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', dwogp));
            $pkt_w_2 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', dwolp));
        }
        if ($pamiec_miejsca_1 < $pamiec_miejsca_2) {
            $pkt_r_1 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', drolp));
            $pkt_r_2 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', drogp));
        }
        if ($pamiec_miejsca_1 > $pamiec_miejsca_2) {
            $pkt_r_1 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', drogp));
            $pkt_r_2 = floor(licz_punkty($pamiec_punktow_1, $pamiec_punktow_2, $pamiec_miejsca_1, $pamiec_miejsca_2, '', '', drolp));
        }
    }
} // wylicza punkty 

?>
