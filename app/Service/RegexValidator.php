<?php


namespace App\Service;


class RegexValidator
{

    private $regex;
    /**
     * RegexValidator constructor.
     */
    public function __construct($regex)
    {
        $this->regex = $regex;
    }


    public function isMatch($payload){
        return (preg_match(
            $this->regex,
            $payload
        ) === 1);
    }
}
