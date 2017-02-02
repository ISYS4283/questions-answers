<?php namespace isys4283\qa;

use PDO;
use PDOException;
use InvalidArgumentException;

class QuestionExtractor
{
    protected static $pdo;

    /**
     * Run the extractor script.
     *
     * @return void
     */
    public static function run()
    {
        static::$pdo = EnhancedContainer::pdo();

        static::clean();

        foreach ( Users::get() as $user ) {
            static::insert(static::extract($user));
        }
    }

    /**
     * Fetch questions or error from a user's questions table.
     *
     * @param  array $user Contains one user's data.
     * @return array $data Contains questions or error info.
     */
    protected static function extract(array $user) : array
    {
        $sql = "
            SELECT id AS question_id,
                question, created_at,
                '$user[username]' as username
            FROM $user[db].dbo.questions
            WHERE created_at < GETDATE()
            AND id NOT IN (
                SELECT id FROM [isys4283].[dbo].[questions]
            )
        ";

        try {
            $data = static::$pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $data[0]['username'] = $user['username'];
            $data[0]['created_at'] = date("Y-m-d H:i:s");
            $data[0]['error'] = $e->getMessage();
        }

        if ( empty($data) ) {
            $data[0]['username'] = $user['username'];
            $data[0]['created_at'] = date("Y-m-d H:i:s");
        }

        return $data;
    }

    /**
     * Inserts questions or error into temporary questions table.
     *
     * @param  array $data Contains one user's questions or error.
     * @return void
     */
    protected static function insert(array $data)
    {
        foreach ( $data as $row ) {
            $sql  = "INSERT INTO [isys4283].[dbo].[tempq] ";
            $sql .= static::buildColumnValueBinders($row);

            static::$pdo->prepare($sql)->execute($row);
        }
    }

    /**
     * Builds a prepared statement with provided columns and values.
     *
     * @param  array $data The columns and values.
     * @return string String with named columns and placeholders.
     */
    protected static function buildColumnValueBinders(array $data)
    {
        if ( empty($data) ) {
            throw new InvalidArgumentException('array cannot be empty');
        }

        $columns = array_keys($data);

        return '('.implode(',',$columns).') VALUES (:'.implode(',:',$columns).')';
    }

    /**
     * Delete all records from the temporary questions table.
     *
     * @return void
     */
    protected static function clean()
    {
        $sql = "DELETE FROM [isys4283].[dbo].[tempq]";
        static::$pdo->exec($sql);
    }
}
