CREATE TABLE users
(
    id INT IDENTITY PRIMARY KEY,
    username VARCHAR(20) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    active BIT NOT NULL DEFAULT 1
)
