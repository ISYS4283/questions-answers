<?php

$db = require __DIR__.'/../pdo.php';

$sql = "SELECT username, db FROM students";

$databases = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ( $databases as $database ) {
    $sql = "
        INSERT INTO isys4283.dbo.tempq
            (  id, question, created_at  )
        SELECT id, question, created_at
        FROM $database[db].dbo.questions
        WHERE CONVERT(DATE,created_at) = '2017-01-25'
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
