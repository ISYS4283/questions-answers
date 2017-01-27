<?php

$db = require __DIR__.'/../pdo.php';

$sql = "SELECT username, db FROM students";

$databases = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ( $databases as $database ) {
    $sql = "
        INSERT INTO isys4283.dbo.tempa (id, question_id, answer, username)
        SELECT a.id, a.question_id, a.answer, '$database[username]' AS username
        FROM $database[db].dbo.answers a
        JOIN isys4283.dbo.questions q
          ON q.id = a.question_id
        WHERE a.id NOT IN (
            SELECT id FROM isys4283.dbo.answers
        )
    ";

    try {
        $db->exec($sql);
    } catch (PDOException $e) {
        $sql = "
            INSERT INTO isys4283.dbo.qaerrors
            (username, error) VALUES (:username, :error)
        ";

        $db->prepare($sql)->execute([
            'username' => $database['username'],
            'error'    => $e->getMessage(),
        ]);
    }
}
