-- set invalid = 0 on insert
ALTER TRIGGER [dbo].[tr_answer_insert]
ON [dbo].[answers] AFTER INSERT AS
    UPDATE answers
    SET updated_at = GETDATE(), created_at = GETDATE(), invalid = 0
    WHERE id = (SELECT id FROM INSERTED)
