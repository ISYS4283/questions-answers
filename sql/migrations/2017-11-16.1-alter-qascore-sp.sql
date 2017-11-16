ALTER PROCEDURE qascore
    @qdate DATE,
    @adate DATE
AS
SELECT REPLACE(u.username, 'GACL\', '') AS username,
    CASE WHEN question_count > 0 THEN 1 ELSE 0 END +
    CASE WHEN answer_count > 0 THEN 1 ELSE 0 END AS score
FROM users u
LEFT JOIN (
    SELECT user_login, COUNT(id) AS 'question_count'
    FROM questions
    WHERE CAST(created_at AS DATE) = @qdate
      AND invalid = 0
    GROUP BY user_login
) q
  ON u.username = q.user_login
LEFT JOIN (
    SELECT a.user_login, COUNT(a.id) AS 'answer_count'
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE CAST(q.created_at AS DATE) = @qdate
      AND CAST(a.created_at AS DATE) <= @adate
      AND q.user_login <> a.user_login
      AND q.invalid = 0
      AND a.invalid = 0
    GROUP BY a.user_login
) a
  ON u.username = a.user_login
WHERE u.active = 1
GROUP BY u.id, u.username, u.first_name, u.last_name, question_count, answer_count
ORDER BY u.username
