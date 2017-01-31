<?php
return (function(){
    $hostname = 'essql1.walton.uark.edu';
    $database = 'isys4283';
    $username = 'isys4283';
    $password = 'something looooong and C0mpl!c@t3D';
    // https://www.microsoft.com/en-us/download/details.aspx?id=50419
    $driver   = 'ODBC Driver 13 for SQL Server';

    $pdo = new PDO("odbc:Driver=$driver;
        Server=$hostname;
        Database=$database",
        $username,
        $password
    );
    $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

    return $pdo;
})();
