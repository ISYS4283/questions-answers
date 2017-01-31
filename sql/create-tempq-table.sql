DROP TABLE tempq;

CREATE TABLE tempq (
    id INT IDENTITY PRIMARY KEY,
    username VARCHAR(12) NOT NULL FOREIGN KEY REFERENCES students(username),

    -- nullable for errors
    question_id UNIQUEIDENTIFIER NULL,
    created_at DATETIME NULL,

    -- or error
    question NVARCHAR(MAX) NOT NULL
);
