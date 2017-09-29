CREATE TRIGGER tr_answer_update
ON answers FOR UPDATE AS
    IF NOT EXISTS (SELECT id FROM INSERTED WHERE user_login = SYSTEM_USER)
    AND SYSTEM_USER <> 'GACL\jpucket'
        BEGIN
            ROLLBACK TRANSACTION
        END
    ELSE
        UPDATE answers
        SET updated_at = GETDATE()
        WHERE id = (SELECT id FROM INSERTED)
