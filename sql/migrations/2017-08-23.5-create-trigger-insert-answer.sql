CREATE TRIGGER tr_answer_insert
ON answers AFTER INSERT AS
    UPDATE answers
    SET updated_at = GETDATE(), created_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)
