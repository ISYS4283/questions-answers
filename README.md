# Questions and Answers

To satisfy your participation points for this course,
every week you must:

* Insert at least one question into a database.
* Insert at least one answer to *someone else's* question.

The question must be on-topic for the respective week's chapter content.

## Setup

[Log into VMWare][7] and start SSMS (Microsoft SQL Server Management Studio).

Connect to `essql1.walton.uark.edu`

![screenshot of connection][10]

Expand the databases folder and create a new query in `isys4283-2017fa`

![screenshot of using database][11]

## Creating Questions

The basic syntax for [an `INSERT` statement][8] is:

```sql
INSERT INTO <table name> (<column name>) VALUES (<value>);
```

For example, insert a question like this :

```sql
INSERT INTO questions (question) VALUES ('What is an ERD?');
```

## Answering Questions

Browse the questions by executing this SQL:

```sql
SELECT * FROM questions;
```

*Protip: you can filter the current questions using a [`WHERE` clause][6].*

```sql
SELECT * FROM questions
WHERE created_at >= '2017-08-22';
```

| id | question             | user_login | created_at | updated_at |
| -- | -------------------- | ---------- | ---------- | ---------- |
| 42 | What is information? | jdoe       | 2017-08-22 | 2017-08-22 |
| 43 | What is an ERD?      | me         | 2017-08-23 | 2017-08-23 |

Insert your answer passing the question id with it, for example:

```sql
INSERT INTO answers (question_id, answer)
VALUES (42, 'Data in context.');
```

## Updating Content

If you make a mistake, then you can use an [`UPDATE` statement][9] to fix it.

```sql
UPDATE questions
SET question = 'What is metadata?'
WHERE id = 43;
```

[6]:http://www.w3schools.com/sql/sql_where.asp
[7]:https://waltonlab.uark.edu/
[8]:http://www.w3schools.com/sql/sql_insert.asp
[9]:https://www.w3schools.com/SQL/sql_update.asp
[10]:./docs/images/connect.png
[11]:./docs/images/use_db.png
