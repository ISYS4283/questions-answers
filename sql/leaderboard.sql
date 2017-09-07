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
      AND a.updated_at < '2017-09-08'
) t
WHERE row_num = 1

-- show the leaderboard
SELECT first_name, last_name, SUM(points) AS 'total',
    CASE
        WHEN (11 - ROW_NUMBER() OVER(ORDER BY SUM(points) DESC)) > 0
        THEN (11 - ROW_NUMBER() OVER(ORDER BY SUM(points) DESC))
        ELSE 0
    END AS 'bonus'
FROM users u
LEFT JOIN #leaderdata d
  ON u.username = d.user_login
GROUP BY user_login, first_name, last_name
ORDER BY 'total' DESC

-- show the leaderdata
SELECT *
FROM #leaderdata
ORDER BY qid DESC, points DESC
