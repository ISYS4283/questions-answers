USE [isys4283-2017fa]
GO

ALTER TABLE answers ADD invalid BIT;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- set invalid = 0 on insert
ALTER TRIGGER [dbo].[tr_answer_insert]
ON [dbo].[answers] AFTER INSERT AS
    UPDATE answers
    SET updated_at = GETDATE(), created_at = GETDATE(), invalid = 0
    WHERE id = (SELECT id FROM INSERTED)
GO

-- if it was invalid, then set invalid = null
ALTER TRIGGER [dbo].[tr_answer_update]
ON [dbo].[answers] FOR UPDATE AS
    IF NOT EXISTS (SELECT id FROM INSERTED WHERE user_login = SYSTEM_USER)
    AND SYSTEM_USER <> 'GACL\jpucket'
        BEGIN
            ROLLBACK TRANSACTION
        END
    ELSE
        UPDATE answers
        SET updated_at = GETDATE()
        WHERE id = (SELECT id FROM INSERTED)

    IF EXISTS (SELECT id FROM INSERTED WHERE invalid = 1)
    AND SYSTEM_USER <> 'GACL\jpucket'
        UPDATE answers
        SET invalid = NULL
        WHERE id = (SELECT id FROM INSERTED)
