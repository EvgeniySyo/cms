<?PHP
$date_stat = date("j.n.Y");
$date_time = date("H:i:s");
$date_h = date("H") + 1;
$refer_on = getenv("HTTP_REFERER");
$server_ip = $_SERVER['HTTP_HOST'];
$ip = $_SERVER['REMOTE_ADDR'];
$agent = $_SERVER['HTTP_USER_AGENT'];
$mysql["stats"] = Simple_DbApi::select_db("statistics", "date", $date_stat, "", "", "", "");
$insert = false;
if (!empty($mysql["stats"])) {
    foreach ($mysql["stats"] as $v => $se) {
        if ($se['ip'] == $ip) $insert = true;
    }
}

unset($v);
if ($insert == false) {
    $mysql["insert_stat"] = Simple_DbApi::insert_db("statistics", "id,date,ip,came,agent,time", "<><>" . $date_stat . "<><>" . $ip . "<><>" . $refer_on . "<><>" . $agent . "<><>" . $date_time . "");
}
$mysql["stats_h"] = Simple_DbApi::select_db("hits", "date", $date_stat, "", "", "", "");
$date_yes = true;
if (!empty($mysql["stats_h"])) $date_yes = false;
if ($date_yes == true) {
    $h = array();
    $h[$date_h] = 1;
    Simple_DbApi::insert_db("hits", "id,date,hits,h1,h2,h3,h4,h5,h6,h7,h8,h9,h10,h11,h12,h13,h14,h15,h16,h17,h18,h19,h20,h21,h22,h23,h24", "<><>" . $date_stat . "<><>1<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "<><>" . $h[$date_h] . "");
} else {
    $mysql["stats_hh"] = Simple_DbApi::select_db("hits", "date", $date_stat, "", "", "", "");
    $ss = current($mysql["stats_hh"]);
    $all_hists = $ss['hits'] + 1;
    $hgo = "h" . $date_h;
    $htimes = $ss[$hgo] + 1;
    $mylsq["up_hit"] = Simple_DbApi::update_db("hits", "hits,h" . $date_h . "", "" . $all_hists . "<><>" . $htimes . "", "id", $ss['id']);
}
?>