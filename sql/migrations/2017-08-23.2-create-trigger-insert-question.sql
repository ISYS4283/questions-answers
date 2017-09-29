CREATE TRIGGER tr_question_insert
ON questions AFTER INSERT AS
    -- update timestamps
    UPDATE questions
    SET updated_at = GETDATE(), created_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)
