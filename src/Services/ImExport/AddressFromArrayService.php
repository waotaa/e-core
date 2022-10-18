<?php

namespace Vng\EvaCore\Services\ImExport;

use Vng\EvaCore\Models\Address;

class AddressFromArrayService
{
    public static function create($data)
    {
        $address = new Address($data);
        $address->saveQuietly();
        return $address;
    }
}