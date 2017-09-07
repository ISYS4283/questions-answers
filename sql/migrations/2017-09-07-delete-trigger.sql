CREATE TRIGGER tr_question_delete
ON questions FOR DELETE AS
-- authorize deleting your content only
IF EXISTS (SELECT id FROM DELETED WHERE user_login <> SYSTEM_USER)
AND SYSTEM_USER <> 'GACL\jpucket'
BEGIN
    ROLLBACK TRANSACTION
END

CREATE TRIGGER tr_answer_delete
ON answers FOR DELETE AS
-- authorize deleting your content only
IF EXISTS (SELECT id FROM DELETED WHERE user_login <> SYSTEM_USER)
AND SYSTEM_USER <> 'GACL\jpucket'
BEGIN
    ROLLBACK TRANSACTION
END
