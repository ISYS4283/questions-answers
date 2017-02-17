DROP TABLE tempq;

CREATE TABLE tempq (
    id INT IDENTITY PRIMARY KEY,
    username VARCHAR(12) NOT NULL FOREIGN KEY REFERENCES students(username),
    total TINYINT NOT NULL,
    created_at DATETIME NOT NULL,
    question_id UNIQUEIDENTIFIER NULL,
    question NVARCHAR(MAX) NULL,
    error NVARCHAR(MAX) NULL
);
