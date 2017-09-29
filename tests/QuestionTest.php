<?php

use PHPUnit\Framework\TestCase;
use isys4283\qa\tests\utilities\Database;

class QuestionTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        Database::fresh();
    }

    public function test_student_can_crud_question()
    {
        // INSERT
        $expected = $question = 'What is an ERD?';
        $insert = "INSERT INTO questions (question) VALUES ('$question')";
        $this->assertSame(1, Database::student()->exec($insert));

        // SELECT
        $id = Database::student()->lastInsertId();
        $select = "SELECT * FROM questions WHERE id = $id";
        $actual = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC)['question'];
        $this->assertSame($expected, $actual);

        // UPDATE
        $question = 'What is DDL?';
        $update = "UPDATE questions SET question = '$question' WHERE id = $id";
        sleep(1);
        $this->assertSame(1, Database::student()->exec($update));
        $result = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC);
        $this->assertSame($question, $result['question']);
        $created_at = new DateTime($result['created_at']);
        $updated_at = new DateTime($result['updated_at']);
        $this->assertGreaterThan($created_at, $updated_at);

        // DELETE
        $delete = "DELETE FROM questions WHERE id = $id";
        $this->assertSame(1, Database::student()->exec($delete));
    }

    public function test_student_crud_answer()
    {
        // seed question
        $question = "What is DML?";
        $insert = "INSERT INTO questions (question) VALUES ('$question')";
        $this->assertSame(1, Database::admin()->exec($insert));
        $qid = Database::admin()->lastInsertId();

        // INSERT
        $expected = $answer = "Data Manipulation Language";
        $insert = "INSERT INTO answers (answer, question_id) VALUES ('$answer', $qid)";
        $this->assertSame(1, Database::student()->exec($insert));
        $aid = Database::student()->lastInsertId();

        // SELECT
        $select = "SELECT * FROM answers WHERE id = $aid";
        $actual = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC)['answer'];
        $this->assertSame($expected, $actual);

        // UPDATE
        $answer = "SQL to manipulate records";
        $update = "UPDATE answers SET answer = '$answer' WHERE id = $aid";
        sleep(1);
        $this->assertSame(1, Database::student()->exec($update));
        $result = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC);
        $this->assertSame($answer, $result['answer']);
        $created_at = new DateTime($result['created_at']);
        $updated_at = new DateTime($result['updated_at']);
        $this->assertGreaterThan($created_at, $updated_at);

        // DELETE
        $delete = "DELETE FROM answers WHERE id = $aid";
        $this->assertSame(1, Database::student()->exec($delete));
    }

    public function test_student_cannot_update_someone_elses_question()
    {
        // seed question
        $question = "What is DCL?";
        $insert = "INSERT INTO questions (question) VALUES ('$question')";
        $this->assertSame(1, Database::admin()->exec($insert));
        $qid = Database::admin()->lastInsertId();

        $invalid = 'This is not a valid question.';
        $update = "UPDATE questions SET question = '$invalid' WHERE id = $qid";
        $this->expectException(PDOException::class);
        Database::student()->exec($update);
    }
}
