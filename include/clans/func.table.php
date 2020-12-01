<?


function pkt_klan($id_gracza)
{
    $b = mysql_query("SELECT m.id FROM `-klany_spotkania` s  JOIN `-klany_mecze` m  WHERE   
	((m.gosc='{$id_gracza}' && m.w2=m.w1) || (m.gosp='{$id_gracza}' && m.w2=m.w1)) && m.status='3' && m.id_spotkania = s.id && s.r_id = '" . KLAN_R_ID . "' ");
    while ($g = mysql_fetch_array($b)) {
        $a = $a + 1;
    }


    $c = mysql_query("SELECT m.id FROM `-klany_spotkania` s  JOIN `-klany_mecze` m WHERE   
	((m.gosp='{$id_gracza}' && m.w2<m.w1) || (m.gosc='{$id_gracza}' && m.w2>m.w1)) && m.status='3' && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "' ");

    while ($f = mysql_fetch_array($c)) {
        $a = $a + 3;
    }
    if (empty($a)) {
        return 0;
    } else {
        return $a;
    }
}


function rozegrane_mecze_klan($id_gracza)
{
    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE 
	m.status='3'  && (m.gosp='{$id_gracza}' || m.gosc='{$id_gracza}')  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'"));
    return $r[0];
}


function wygrane_mecze_klan($id_gracza)
{
    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE  
	m.status='3' && ((m.gosp='{$id_gracza}' && (m.w1>m.w2)) || (m.gosc='{$id_gracza}' && (m.w1<m.w2)))  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'"));
    return $r[0];
}


function remisy_klan($id_gracza)
{

    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE   
	m.status='3' && (m.gosp='{$id_gracza}' || m.gosc='{$id_gracza}') && (m.w1=m.w2)  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'"));
    return $r[0];
}


function przegrane_mecze_klan($id_gracza)
{


    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE   
	m.status='3' && ((m.gosp='{$id_gracza}' && (m.w1<m.w2)) || (m.gosc='{$id_gracza}' && (m.w1>m.w2)))  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'"));


    return $r[0];
}


function rozegraneSpotkaniaKlan($clan_id)
{
    $a = mysql_fetch_array(mysql_query("
		SELECT COUNT(*) AS ukonczonych_spotkan FROM
		( 
		  SELECT m.id_spotkania, COUNT(*) as ukonczonych_meczy FROM `-klany_mecze` m
		  LEFT JOIN 
			`-klany_spotkania` ks 
		  ON ks.id = m.id_spotkania   
		  WHERE  (ks.gosp = {$clan_id} || ks.gosc = {$clan_id}) && ks.r_id = '" . KLAN_R_ID . "'
		  GROUP BY m.id_spotkania
		  HAVING MIN(m.status)=3 
		) s
		WHERE s.ukonczonych_meczy = 4
	"));


    return $a[0];

}

function func($clan_id, $id, $func)
{
    $sql = mysql_query("SELECT * FROM `-klany_gracze` WHERE id_klanu = '{$clan_id}'");
    while ($rec = mysql_fetch_array($sql)) {
        $goals += $func($rec['id_gracza'], $id);
    }
    return $goals;
}

function strzelone_bramki_klan($id_gracza, $party_id = null)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(m.w2)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id &&  s.r_id='" . KLAN_R_ID . "'
	WHERE  m.gosc='{$id_gracza}' && m.status='3'  
	" . ($party_id ? "&& m.id_spotkania = '{$party_id}'" : null) . " GROUP BY m.id_spotkania"));


    $d = mysql_fetch_array(mysql_query("SELECT SUM(m.w1)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id &&  s.r_id='" . KLAN_R_ID . "'
	WHERE  m.gosc='{$id_gracza}' && m.status='3' 
	" . ($party_id ? "&& m.id_spotkania = '{$party_id}'" : null) . " GROUP BY m.id_spotkania"));


    return $d[0] + $c[0];
}


//wygrane mecze w klanie
function winMatchesClan($clanId)
{
    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  ((s.gosp='{$clanId}' && (m.w1>m.w2)) || (s.gosc='{$clanId}' && (m.w1<m.w2))) && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "'"));


    return ($r[0] ? $r[0] : 0);
}

//przegrane mecze w klanie
function drawMatchesClan($clanId)
{

    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  ((s.gosp='{$clanId}' && (m.w1=m.w2)) || (s.gosc='{$clanId}' && (m.w1=m.w2))) && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "'"));


    return ($r[0] ? $r[0] : 0);
}


//zremisowane mecze w klanie
function lostMatchesClan($clanId)
{


    $r = mysql_fetch_array(mysql_query("SELECT count(m.id)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  ((s.gosp='{$clanId}' && (m.w1<m.w2)) || (s.gosc='{$clanId}' && (m.w1>m.w2))) && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "'"));


    return ($r[0] ? $r[0] : 0);
}


//rozegrane spotkania klan
function playedMatchesClan($clanId)
{
    $r = mysql_fetch_array(mysql_query("SELECT COUNT(m.id)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  (s.gosp='{$clanId}' || s.gosc='{$clanId}') && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "'"));

    return ($r[0] ? $r[0] : 0);
}


//strzelone bramki klanu
function scoredGoalsClan($clanId)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(m.w1)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  s.gosp='{$clanId}' && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "' GROUP BY s.gosp"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(m.w2)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  s.gosc='{$clanId}' && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "' GROUP BY s.gosc"));

    return $c[0] + $d[0];
}

//stracone bramki klan
function lostGoalsClan($clanId)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(m.w2)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  s.gosp='{$clanId}' && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "' GROUP BY s.gosc"));
    $d = mysql_fetch_array(mysql_query("SELECT SUM(m.w1)  FROM `-klany_spotkania` s LEFT JOIN `-klany_mecze` m ON m.id_spotkania = s.id 
	WHERE  s.gosc='{$clanId}' && m.status='3'  &&  s.r_id='" . KLAN_R_ID . "' GROUP BY s.gosp"));

    return $c[0] + $d[0];
}

function bonusKlan($clan_id)
{
    $b = mysql_query("SELECT s.* FROM `-klany_spotkania` s WHERE   s.gosc='{$clan_id}' || s.gosp='{$clan_id}' ");
    while ($r = mysql_fetch_Array($b)) {

        $c = mysql_fetch_array(mysql_query("
		SELECT COUNT(*) AS ukonczonych_spotkan FROM
			( 
			  SELECT id_spotkania, COUNT(*) as ukonczonych_meczy
			  FROM `-klany_mecze`
			  WHERE id_spotkania = '{$r['id']}' && w2" . ($r['gosc'] == $clan_id ? ">" : "<") . "w1
			  GROUP BY id_spotkania
			  HAVING MIN(status)=3 
			) s
		WHERE s.ukonczonych_meczy = 4"));


        $count += $c['ukonczonych_spotkan'] * 3;
    }


    return $count;


}


function stracone_bramki_klan($id_gracza, $party_id = null)
{
    $c = mysql_fetch_array(mysql_query("SELECT SUM(m.w2)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE   
	m.gosc='{$id_gracza}' && m.status='3'  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'
	" . ($party_id ? "&& m.id_spotkania = '{$party_id}'" : null) . " GROUP BY m.gosp"));

    $d = mysql_fetch_array(mysql_query("SELECT SUM(m.w1)  FROM `-klany_spotkania` s JOIN `-klany_mecze` m WHERE   
	m.gosc='{$id_gracza}' && m.status='3'  && m.id_spotkania = s.id && s.r_id='" . KLAN_R_ID . "'
	" . ($party_id ? "&& m.id_spotkania = '{$party_id}'" : null) . " GROUP BY m.gosc"));

    return $d[0] + $c[0];
}


function plusminus_bramki_klan($id_gracza, $party_id = null)
{
    return strzelone_bramki_klan($id_gracza, $party_id = null) - stracone_bramki_klan($id_gracza, $party_id = null);
}


?>