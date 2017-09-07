DROP TABLE IF EXISTS #leaderdata

-- reverse rank highest number is best
-- the more users that answer a question, the greater the respective top score
SELECT qid, question, id AS 'aid', answer, user_login, updated_at,
    RANK() OVER (PARTITION BY qid ORDER BY updated_at DESC) AS 'points'
INTO #leaderdata
FROM (
    SELECT q.id AS 'qid', question, a.*,
        -- only count the most recent answer per question per user
        ROW_NUMBER() OVER (PARTITION BY q.id, a.user_login ORDER BY a.updated_at DESC) AS 'row_num'
    FROM questions q
    JOIN answers a
      ON q.id = a.question_id
    WHERE q.user_login = 'GACL\jpucket'
      AND q.id > 75
      AND a.invalid = 0
) t
WHERE row_num = 1

-- show the leaderboard
SELECT user_login, SUM(points) AS 'total'
FROM #leaderdata
GROUP BY user_login
ORDER BY 'total' DESC

-- show the leaderdata
SELECT *
FROM #leaderdata
ORDER BY qid DESC, points DESC
