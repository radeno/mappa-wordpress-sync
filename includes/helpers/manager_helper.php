<?php

namespace Mappa;

class ManagerHelper
{
    public static function formatDate(string $datetime, $format) : string
    {
        $datetimeObject = new \DateTime($datetime);
        return $datetimeObject->format($format);
    }

    public static function datetimeToWordpress(string $datetime) : string
    {
        return self::formatDate($datetime, 'Y-m-d H:i:s');
    }

    public static function datetimeFromWordpress(string $datetime) : string
    {
        return self::formatDate($datetime, \DateTimeInterface::W3C);
    }
}
