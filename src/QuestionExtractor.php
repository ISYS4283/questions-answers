<?php namespace isys4283\qa;

use PDO;
use PDOException;
use InvalidArgumentException;
use Carbon\Carbon;

class QuestionExtractor
{
    protected $pdo;
    protected $date;

    /**
     * Create an instance of a Question Extractor.
     *
     * @param  string|Carbon $date
     * @return QuestionExtractor
     */
    public function __construct($date)
    {
        $this->pdo = EnhancedContainer::pdo();

        $this->setDate($date);
    }

    /**
     * Set the date filter for which questions to extract.
     *
     * @param  string|Carbon $date
     * @return QuestionExtractor
     */
    public function setDate($date) : QuestionExtractor
    {
        if ( empty($date) ) {
            throw new InvalidArgumentException('$date cannot be empty.');
        }

        if ( ! $date instanceof Carbon ) {
            $date = new Carbon($date);
        }

        $this->date = $date->format('Y-m-d');

        return $this;
    }

    /**
     * Run the extractor script.
     *
     * @return void
     */
    public function run()
    {
        $this->clean();

        foreach ( Users::get() as $user ) {
            $this->insert($this->extract($user));
        }
    }

    /**
     * Fetch questions or error from a user's questions table.
     *
     * @param  array $user Contains one user's data.
     * @return array $data Contains questions or error info.
     */
    protected function extract(array $user) : array
    {
        $sql = "
            SELECT id AS question_id,
                question, created_at,
                '$user[username]' as username
            FROM $user[db].dbo.questions
            WHERE CONVERT(DATE, created_at) = '{$this->date}'
            AND id NOT IN (
                SELECT id FROM [isys4283].[dbo].[questions]
            )
        ";

        try {
            $data = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
    protected function insert(array $data)
    {
        foreach ( $data as $row ) {
            $row['total'] = count($data);

            $sql  = "INSERT INTO [isys4283].[dbo].[tempq] ";
            $sql .= $this->buildColumnValueBinders($row);

            $this->pdo->prepare($sql)->execute($row);
        }
    }

    /**
     * Builds a prepared statement with provided columns and values.
     *
     * @param  array $data The columns and values.
     * @return string String with named columns and placeholders.
     */
    protected function buildColumnValueBinders(array $data) : string
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
    protected function clean()
    {
        $sql = "DELETE FROM [isys4283].[dbo].[tempq]";
        $this->pdo->exec($sql);
    }
}
