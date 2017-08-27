CREATE TABLE questions
(
    id INT IDENTITY PRIMARY KEY,
    question NVARCHAR(MAX) NOT NULL,
    user_login VARCHAR(20) DEFAULT SYSTEM_USER CHECK (user_login = SYSTEM_USER),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);
GO

CREATE TRIGGER tr_question_insert
ON questions AFTER INSERT AS
    -- update timestamps
    UPDATE questions
    SET updated_at = GETDATE(), created_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)
GO

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
GO

CREATE TABLE answers
(
    id INT IDENTITY PRIMARY KEY,
    answer NVARCHAR(MAX) NOT NULL,
    user_login VARCHAR(20) DEFAULT SYSTEM_USER CHECK (user_login = SYSTEM_USER),
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    question_id INT NOT NULL FOREIGN KEY REFERENCES questions(id)
);
GO

CREATE TRIGGER tr_answer_insert
ON answers AFTER INSERT AS
    UPDATE answers
    SET updated_at = GETDATE(), created_at = GETDATE()
    WHERE id = (SELECT id FROM INSERTED)
GO

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
GO
