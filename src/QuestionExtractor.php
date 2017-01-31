<?php namespace isys4283\qa;

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
