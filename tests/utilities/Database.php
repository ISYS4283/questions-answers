<?php

namespace isys4283\qa\tests\utilities;

use PDO;
use jpuck\phpdev\Functions as jp;
use isys4283\qa\Migrator;
use InvalidArgumentException;

class Database
{
    protected static $migrations = __DIR__.'/../../sql/migrations';
    protected static $student;
    protected static $admin;

    protected static function getPdo(string $username, string $password)
    {
        $hostname = getenv('HOSTNAME');
        $database = getenv('DATABASE');
        $pdo = new PDO("dblib:host=$hostname;dbname=$database",
            $username,
            $password
        );
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

        return $pdo;
    }

    public static function student() : PDO
    {
        return static::$student ?? static::$student = static::getPdo(getenv('STUDENT_USERNAME'), getenv('STUDENT_PASSWORD'));
    }

    public static function admin() : PDO
    {
        return static::$admin ?? static::$admin = static::getPdo(getenv('ADMIN_USERNAME'), getenv('ADMIN_PASSWORD'));
    }

    public static function fresh()
    {
        jp::CleanMsSQLdb(static::admin());

        $path = realpath(static::$migrations);

        if ($path === false) {
            throw new InvalidArgumentException("invalid path to migrations: '$pathToMigrations'");
        }

        if (!is_dir($path)) {
            throw new InvalidArgumentException("path is not a directory: '$pathToMigrations'");
        }

        foreach (glob("$path/*.sql") as $filename) {
            $sql = file_get_contents($filename);
            static::admin()->query($sql);
        }
    }
}
