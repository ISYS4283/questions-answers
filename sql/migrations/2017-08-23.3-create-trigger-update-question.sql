CREATE TRIGGER tr_question_update
ON questions FOR UPDATE AS
    -- authorize updates to your content only
    IF NOT EXISTS (SELECT id FROM INSERTED WHERE user_login = SYSTEM_USER)
    AND SYSTEM_USER <> 'GACL\jpucket'
        BEGIN
            ROLLBACK TRANSACTION
        END
    ELSE
        -- update timestamp
        UPDATE questions
        SET updated_at = GETDATE()
        WHERE id = (SELECT id FROM INSERTED)
