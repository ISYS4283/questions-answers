SELECT user_login, SUM(points) AS 'total'
FROM (
    SELECT question, a.*,
        RANK() OVER (PARTITION BY q.id ORDER BY a.updated_at DESC) AS 'points'
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE q.user_login = 'GACL\jpucket'
      AND q.id > 75
      AND a.invalid = 0
) t
GROUP BY user_login
ORDER BY 'total' DESC

-- reverse rank highest number is best
-- the more followers, the greater the score
SELECT question, a.*,
    RANK() OVER (PARTITION BY q.id ORDER BY a.updated_at DESC) AS 'points'
FROM questions q
JOIN answers a
  ON q.id = a.question_id
WHERE q.user_login = 'GACL\jpucket'
  AND q.id > 75
  AND a.invalid = 0
ORDER BY q.id DESC, 'points' DESC
