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

Protip: you can filter the current questions using a [`WHERE` clause][6].

```sql
SELECT * FROM [isys4283].[dbo].[questions]
WHERE created_at >= '2017-02-08'
```

Create your [answers table][4] if you haven't already.

Insert your answer passing the question id with it, for example:

```sql
INSERT INTO [answers] ([question_id],[answer])
VALUES ('7B236C12-1EF9-41B1-A08F-B813AF46A72D','Entity Relationship Diagram');
```

[Watch on Youtube][1]. **NOTE** that the repository has changed since this
video was made. [Click here][5] to browse the repository files at that time.
But keep in mind that you will want to create the latest version of the tables
in order to be current with the class.

[![Youtube Thumbnail][2]][1]

[1]:https://youtu.be/2t2IeNA1bi0?hd=1
[2]:./docs/images/youtube.png
[3]:./sql/create-questions-table.sql
[4]:./sql/create-answers-table.sql
[5]:https://github.com/ISYS4283/questions-answers/tree/5452b90c488d6ae05b9c87e578a8165504bafb7f
[6]:http://www.w3schools.com/sql/sql_where.asp
