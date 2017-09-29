-- set invalid = false on insert
ALTER TRIGGER tr_question_insert
ON questions AFTER INSERT AS
    UPDATE questions
    SET updated_at = GETDATE(), created_at = GETDATE(), invalid = 0
    WHERE id = (SELECT id FROM INSERTED)
