<?php namespace isys4283\qa;

use PDO;

class Users
{
    /**
     * Get all data associated with a user, or all users if none specified.
     *
     * @param  string $username Optional username to filter results.
     * @return array User data.
     */
    public static function get(string $username = null) : array
    {
        $pdo = EnhancedContainer::pdo();

        $sql = "
            SELECT *
            FROM [isys4283].[dbo].[students]
        ";

        if ( isset($username) ) {
            $sql .= "WHERE username = :username";

            $statement = $pdo->prepare($sql);

            $statement->execute([
                'username' => $username,
            ]);
        } else {
            $statement = $pdo->query($sql);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
