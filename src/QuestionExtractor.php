<?php namespace isys4283\qa;

use PDO;
use PDOException;

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
            $data = static::extract($user);

            print_r($data);
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
            $data[0]['question'] = $e->getMessage();
        }

        return $data;
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
