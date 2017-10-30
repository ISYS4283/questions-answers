-- variable to filter by chapters
-- according to class caledar
-- question must be ON this date
DECLARE @qdate AS DATETIME = '2017-10-24'
-- answer must be ON OR BEFORE this date
DECLARE @adate AS DATETIME = '2017-10-26'

-- all Q&A content
SELECT q.id, q.user_login, q.question,
       a.id, a.user_login, a.answer
FROM questions q
LEFT JOIN answers a
  ON q.id = a.question_id
WHERE CAST(q.created_at AS DATE) = @qdate
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
      -- invalidate answering your own question
      AND q.user_login <> a.user_login
      AND q.invalid = 0
      AND a.invalid = 0
) a
  ON u.username = a.user_login
GROUP BY u.id, u.username, u.first_name, u.last_name
ORDER BY u.last_name
