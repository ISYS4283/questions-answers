ALTER TRIGGER tr_question_update
ON questions FOR UPDATE AS
    IF SYSTEM_USER = 'GACL\jpucket'
    RETURN

    IF NOT EXISTS (SELECT id FROM INSERTED WHERE user_login = SYSTEM_USER)
    ROLLBACK TRANSACTION
    RETURN

    UPDATE questions
    SET updated_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)

    -- if it was invalid, then mark as pending
    IF EXISTS (SELECT id FROM INSERTED WHERE invalid = 1)
    UPDATE questions
    SET invalid = NULL
    WHERE id = (SELECT id FROM INSERTED)
