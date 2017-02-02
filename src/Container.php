<?php namespace isys4283\qa;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

class Container
{
    protected static $registry = [];
    protected static $required;

    public static function set(string $key, $value)
    {
        static::$registry[$key] = $value;
        return $value;
    }

    public static function get(string $key)
    {
        if ( ! array_key_exists($key, static::$registry) ) {
            if ( empty(static::$registry['dotenv']) ) {
                try {
                    // find .env file in calling script's folder
                    $dotenv = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
                    $dotenv = new Dotenv($dotenv);
                    $dotenv->load();

                    if ( isset(static::$required) ) {
                        $dotenv->required(static::$required);
                    }

                    static::$registry['dotenv'] = $dotenv;
                } catch ( InvalidPathException $e ) {
                    static::$registry['dotenv'] = 'No .env file set.';
                }
            }

            static::$registry[$key] = static::env($key);
        }

        return static::$registry[$key] ?? null;
    }

    protected static function env($key)
    {
        $value = getenv($key);

        if ($value === false) {
            return;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        return $value;
    }

    public static function dump() : array
    {
        return static::$registry;
    }
}
