<?php


namespace Source\Models;


use CoffeeCode\DataLayer\DataLayer;

class License extends DataLayer
{
    public function __construct()
    {
        parent::__construct("licencas", [], 'id', false);
    }

    public function getDetail()
    {
        switch ($this->tipo):
            case 1:
                break;
            case 7:
                $market = (new Market())->find("id_licenca = :ilic", "ilic=" . $license->id)->fetch();
                $zone = (new Zone())->find("id = :izon", "izon=" . $market->id_zona)->fetch();
                $box = (new Fixed())->find("id = :ivag", "ivag=" . $market->id_vaga)->fetch();
                break;
            default:
                $textStatus = 'Bloqueado';
                break;
        endswitch;
    }
}
