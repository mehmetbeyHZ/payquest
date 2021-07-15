<?php

use Illuminate\Support\Str;
use Pusher\Pusher;

function pusher()
{
    return new Pusher(
        config('broadcasting.connections.pusher.key'),
        config('broadcasting.connections.pusher.secret'),
        config('broadcasting.connections.pusher.app_id'),
        config('broadcasting.connections.pusher.options')
    );
}

function substr_text($string,$length = 30)
{
    return Str::substr((strip_tags($string)),0,$length);
}

function date_init($date)
{
    return \Carbon\Carbon::createFromTimeString($date)->format('Y-m-d');
}
function time_init($time)
{
    return \Carbon\Carbon::createFromTimeString($time)->format('H:i');
}

function admin_ticket_notification()
{
    $total = (new \App\Model\TicketsMessages())->unseen_total_admin();
    if ($total > 0):
        return '<span class="new badge green" data-badge-caption="mesaj">'.$total.'</span>';
    else :
        return null;
    endif;
}

function is_active($routeName)
{
    if (request()->route()):
        return request()->route()->getName() === $routeName ? "active" : null;
    endif;
    return null;
}

/**
 * @return int[]
 *  1 Level 10xp to 2 +30 soru       0.05 0.07
 *  2 level 20xp to 3 +50 soru       0.06 - 0.08
 *  3 level 30xp to 4 +80 soru       7 - 0.09
 *  4 level 40xp to 5 +100 soru      8 10
 *  5 level 50xp to 6 +150 soru      9 11
 *  6 level 60xp to 7 +400 soru      10 12
 *  7 level 70xp to 8 +800 soru      11 13
 *  8 level 80xp to 9 +1500 soru     10 14
 *  9 level 90xp to 10 +2000 soru     10 14
 *  10 level 100xp +3000soru    10 14
 */



function bars()
{

    return [
        0,
        300, // 2. lv
        1300,
        3700, // 4.lv
        11200,
        23200, // 200 soru 60xp
        47200
    ];
}

function levelBars()
{
    return [
        0,
        300, // 2. lv
        1300,
        3700, // 4.lv
        11200,
        23200, // 200 soru 60xp
        47200,
        103200
    ];
}

function levelToMinXP($level)
{
    $xp = levelBars();
    return $xp[$level - 1] ?? 0;
}

function xpToLevel($my_xp)
{
    $xp = levelBars();


    for ($i = 0, $iMax = count($xp); $i < $iMax; $i++) {

        // if we've looped beyond our xp, exit with last assigned (current) values
        if ($my_xp < $xp[$i]) {
            break;
        }

        $my_level = $i;

        // if index + 1 is in range, assign values
        if ($i + 1 < count($xp)) {
            $next_level = $i + 1;
            $xp_needed = $xp[$i + 1] - $my_xp;

            // if index + 1 is out of range, give default values
        } else {
            $next_level = $i;
            $xp_needed = 0;
        }
    }

    return [
        'level' => $my_level + 1,
        'next_level' => $next_level + 1,
        'xp_need' => $xp_needed,
    ];

}

function isValidIBAN($iban)
{
    try {
        $iban = strtolower(str_replace(' ', '', $iban));
        $Countries = array('al' => 28, 'ad' => 24, 'at' => 20, 'az' => 28, 'bh' => 22, 'be' => 16, 'ba' => 20, 'br' => 29, 'bg' => 22, 'cr' => 21, 'hr' => 21, 'cy' => 28, 'cz' => 24, 'dk' => 18, 'do' => 28, 'ee' => 20, 'fo' => 18, 'fi' => 18, 'fr' => 27, 'ge' => 22, 'de' => 22, 'gi' => 23, 'gr' => 27, 'gl' => 18, 'gt' => 28, 'hu' => 28, 'is' => 26, 'ie' => 22, 'il' => 23, 'it' => 27, 'jo' => 30, 'kz' => 20, 'kw' => 30, 'lv' => 21, 'lb' => 28, 'li' => 21, 'lt' => 20, 'lu' => 20, 'mk' => 19, 'mt' => 31, 'mr' => 27, 'mu' => 30, 'mc' => 27, 'md' => 24, 'me' => 22, 'nl' => 18, 'no' => 15, 'pk' => 24, 'ps' => 29, 'pl' => 28, 'pt' => 25, 'qa' => 29, 'ro' => 24, 'sm' => 27, 'sa' => 24, 'rs' => 22, 'sk' => 24, 'si' => 19, 'es' => 24, 'se' => 24, 'ch' => 21, 'tn' => 24, 'tr' => 26, 'ae' => 23, 'gb' => 22, 'vg' => 24);
        $Chars = array('a' => 10, 'b' => 11, 'c' => 12, 'd' => 13, 'e' => 14, 'f' => 15, 'g' => 16, 'h' => 17, 'i' => 18, 'j' => 19, 'k' => 20, 'l' => 21, 'm' => 22, 'n' => 23, 'o' => 24, 'p' => 25, 'q' => 26, 'r' => 27, 's' => 28, 't' => 29, 'u' => 30, 'v' => 31, 'w' => 32, 'x' => 33, 'y' => 34, 'z' => 35);

        if (strlen($iban) == $Countries[substr($iban, 0, 2)]) {

            $MovedChar = substr($iban, 4) . substr($iban, 0, 4);
            $MovedCharArray = str_split($MovedChar);
            $NewString = "";

            foreach ($MovedCharArray as $key => $value) {
                if (!is_numeric($MovedCharArray[$key])) {
                    $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
                }
                $NewString .= $MovedCharArray[$key];
            }

            if (bcmod($NewString, '97') == 1) {
                return true;
            }
        }
        return false;
    } catch (\Exception $e) {
        return false;
    }
}

function cmp($a, $b)
{
    if ($a === $b) {
        return 0;
    }
    return ($a < $b) ? -1 : 1;
}

function mission_status($statusId)
{
    $data = [
        0 => 'Görev Bekliyor',
        1 => 'Görev Tamamlandı',
        2 => 'Görev Başarısız',
        3 => 'Görev Iptal',
        4 => 'Görev Kontrol Ediliyor'
    ];
    return $data[$statusId] ?? "Unknown";
}
