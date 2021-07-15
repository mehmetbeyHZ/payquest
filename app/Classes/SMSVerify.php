<?php


namespace App\Classes;


use MClient\Request;

class SMSVerify
{
    public function sendSingle($phoneNumber,$code)
    {
        return (new Request('http://api.216bilisim.com/sms/send/single'))
            ->addParam('key','9234458e1a853551925078d5d630a092')
            ->addParam('secret','xX1Agi0yj0K0rfXqo630YRuA8xPYLsU1vr3eFvd8QeGDynpOhCBGVNcKuotScj6V')
            ->setUserAgent('globalhaberlesme_api')
            ->addPost('data',json_encode([
                "numbers"               => $phoneNumber,
                "originator"            => "BDDMEDYACOM",
                "text"                  => "Payquestion için doğrulama kodunuz: {$code}\n",
                "time"                  => "now",
                "turkish_character"     => "0"
            ]))->execute();

    }
}
