<?php namespace isys4283\qa;

use PDO;

class EnhancedContainer extends Container
{
    protected static $required = 'PDO_FILENAME';

    public static function pdo() : PDO
    {
        if ( empty(static::get('pdo')) ) {
            static::set('pdo', require static::get('PDO_FILENAME'));
        }

        return static::get('pdo');
    }
}
