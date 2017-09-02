DECLARE @date AS DATETIME = '2017-08-29'

SELECT q.id, q.user_login, q.question,
       a.id, a.user_login, a.answer
FROM questions q
LEFT JOIN answers a
  ON q.id = a.question_id
WHERE q.created_at >= @date
ORDER BY q.id

SELECT u.username, u.first_name, u.last_name,
    COUNT(q.id) AS 'Questions',
    COUNT(a.id) AS 'Answers'
FROM users u
LEFT JOIN (
    SELECT *
    FROM questions
    WHERE created_at >= @date
) q
  ON u.username = q.user_login
LEFT JOIN (
    SELECT a.*
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE q.created_at >= @date
      AND q.user_login <> a.user_login
) a
  ON u.username = a.user_login
GROUP BY u.id, u.username, u.first_name, u.last_name
ORDER BY u.last_name
