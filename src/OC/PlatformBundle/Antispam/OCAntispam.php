<?php
/**
 * Created by PhpStorm.
 * User: vboineau
 * Date: 04/01/18
 * Time: 10:34
 */

namespace OC\PlatformBundle\Antispam;


class OCAntispam
{
    private $mailer;
    private $locale;
    private $minLength;

    public function __construct(\Swift_Mailer $mailer, $locale, $minLength){
        $this->mailer=$mailer;
        $this->locale=$locale;
        $this->minLength=(int) $minLength;
    }

    public function isSpam($text){
        return strlen($text <$this->minLength);
    }
}
