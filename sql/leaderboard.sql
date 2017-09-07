SELECT user_login, SUM(points) AS 'total'
FROM (
    SELECT *, RANK() OVER (PARTITION BY qid ORDER BY updated_at DESC) AS 'points'
    FROM (
        SELECT q.id AS 'qid', question, a.*,
            ROW_NUMBER() OVER (PARTITION BY q.id, a.user_login ORDER BY a.updated_at DESC) AS 'row_num'
        FROM questions q
        JOIN answers a
          ON q.id = a.question_id
        WHERE q.user_login = 'GACL\jpucket'
          AND q.id > 75
          AND a.invalid = 0
    ) subt
    WHERE row_num = 1
) t
GROUP BY user_login
ORDER BY 'total' DESC

-- reverse rank highest number is best
-- the more followers, the greater the score
SELECT qid, question, id AS 'aid', answer, user_login, updated_at,
    RANK() OVER (PARTITION BY qid ORDER BY updated_at DESC) AS 'points'
FROM (
    SELECT q.id AS 'qid', question, a.*,
        ROW_NUMBER() OVER (PARTITION BY q.id, a.user_login ORDER BY a.updated_at DESC) AS 'row_num'
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE q.user_login = 'GACL\jpucket'
      AND q.id > 75
      AND a.invalid = 0
) subt
WHERE row_num = 1
ORDER BY qid DESC, 'points' DESC
