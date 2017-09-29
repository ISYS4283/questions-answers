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
        $select = "SELECT question FROM questions WHERE id = $id";
        $actual = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC)['question'];
        $this->assertSame($expected, $actual);

        // UPDATE
        $expected = $question = 'What is DDL?';
        $update = "UPDATE questions SET question = '$question' WHERE id = $id";
        $this->assertSame(1, Database::student()->exec($update));
        $actual = Database::student()->query($select)->fetch(PDO::FETCH_ASSOC)['question'];
        $this->assertSame($expected, $actual);

        // DELETE
        $delete = "DELETE FROM questions WHERE id = $id";
        $this->assertSame(1, Database::student()->exec($delete));
    }
}
