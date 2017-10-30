ALTER PROCEDURE qascore
    @qdate DATE,
    @adate DATE
AS
SELECT REPLACE(u.username, 'GACL\', '') AS username,
    CASE WHEN COUNT(q.id) > 0 THEN 1 ELSE 0 END +
    CASE WHEN COUNT(a.id) > 0 THEN 1 ELSE 0 END AS score
FROM users u
LEFT JOIN (
    SELECT *
    FROM questions
    WHERE CAST(created_at AS DATE) = @qdate
      AND invalid = 0
) q
  ON u.username = q.user_login
LEFT JOIN (
    SELECT a.*
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE CAST(q.created_at AS DATE) = @qdate
      AND CAST(a.created_at AS DATE) <= @adate
      AND q.user_login <> a.user_login
      AND q.invalid = 0
      AND a.invalid = 0
) a
  ON u.username = a.user_login
GROUP BY u.id, u.username, u.first_name, u.last_name
ORDER BY u.username
