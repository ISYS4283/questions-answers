DECLARE @date AS DATETIME = '2017-08-29'

SELECT u.first_name, u.last_name,
    q.id, q.question,
    a.id, a.user_login, a.answer
FROM users u
LEFT JOIN questions q
  ON u.username = q.user_login
LEFT JOIN answers a
  ON q.id = a.question_id
WHERE q.created_at >= @date
ORDER BY u.last_name
