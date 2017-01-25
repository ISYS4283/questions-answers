# Questions and Answers

For those who missed it in the first class, we created a table for you to
insert questions.

You must insert at least one question into this table every week.

Log into VMWare and start SSMS (Microsoft SQL Server Management Studio).

Connect to `essql1.walton.uark.edu`

Your database is named `isys4283<your UARK username>`

Create the table using [this DDL][3].

The basic insert syntax for a question goes like this for example:

```sql
INSERT INTO questions (question) VALUES ('What is an ERD?');
```

Browse the questions after they've been pulled here:

```sql
SELECT * FROM [isys4283].[dbo].[questions]
```

Create your [answers table][4] if you haven't already.

Insert your answer passing the question id with it, for example:

```sql
INSERT INTO [answers] ([question_id],[answer])
VALUES ('7B236C12-1EF9-41B1-A08F-B813AF46A72D','Entity Relationship Diagram');
```

[Watch on Youtube][1]

[![Youtube Thumbnail][2]][1]

[1]:https://youtu.be/2t2IeNA1bi0?hd=1
[2]:./youtube.png
[3]:./create_questions_table.sql
[4]:./create_answers_table.sql
