-- if it was invalid, then set invalid = null
ALTER TRIGGER [dbo].[tr_answer_update]
ON [dbo].[answers] FOR UPDATE AS
    IF SYSTEM_USER = 'GACL\jpucket'
    RETURN

    IF NOT EXISTS (SELECT id FROM INSERTED WHERE user_login = SYSTEM_USER)
    BEGIN
        ROLLBACK TRANSACTION
        RETURN
    END

    UPDATE answers
    SET updated_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)

    IF EXISTS (SELECT id FROM INSERTED WHERE invalid = 1)
    UPDATE answers
    SET invalid = NULL
    WHERE id = (SELECT id FROM INSERTED)
