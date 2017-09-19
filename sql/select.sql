-- variable to filter by chapters
-- according to class caledar
DECLARE @date AS DATETIME = '2017-09-12'

-- all Q&A content
SELECT q.id, q.user_login, q.question,
       a.id, a.user_login, a.answer
FROM questions q
LEFT JOIN answers a
  ON q.id = a.question_id
WHERE q.created_at >= @date
ORDER BY q.id

-- report each user's count of both Q&A
SELECT u.username, u.first_name, u.last_name,
    COUNT(q.id) AS 'Questions',
    COUNT(a.id) AS 'Answers'
FROM users u
LEFT JOIN (
    -- derived tables unconditionally include all users
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
      -- invalidate answering your own question
      AND q.user_login <> a.user_login
      AND a.invalid = 0
) a
  ON u.username = a.user_login
GROUP BY u.id, u.username, u.first_name, u.last_name
ORDER BY u.last_name
