<?php

$db = require __DIR__.'/../pdo.php';

$fetch = function($sql) use ($db) {
    return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
};

$sql = "SELECT username, db FROM students";

$databases = $fetch($sql);

foreach ( $databases as $database ) {
    $sql = "SELECT question FROM $database[db].dbo.questions";
    try {
        $results = $fetch($sql);
        foreach ( $results as $result ) {
            if ( !empty($result['question']) ) {
                $questions[$database['username']] []= $result['question'];
            }
        }
    } catch (PDOException $e) {
        $errors[$database['username']] = $e->getMessage();
    }
}

print_r($questions ?? []);
print_r($errors ?? []);

foreach ( $questions as $username ) {
    foreach ( $username as $question ) {
        echo "INSERT INTO questions (question) VALUES ('$question');\n";
    }
}
