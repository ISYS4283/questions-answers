-- view and grade
SELECT * FROM [isys4283].[dbo].[tempq]
ORDER BY [username]

-- delete undesireables
DELETE FROM [isys4283].[dbo].[tempq]
WHERE question_id IS NULL
   OR id IN (95,98);

-- import gold
INSERT INTO [isys4283].[dbo].[questions]
(id, question, created_at)
SELECT question_id, question, created_at
FROM [isys4283].[dbo].[tempq]
