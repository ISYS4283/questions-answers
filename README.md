# Questions and Answers

To satisfy your participation points for this course,
you must insert at least one question *and* answer into your database
every week that's on-topic for the respective week's chapter content.

## Setup

[Log into VMWare][7] and start SSMS (Microsoft SQL Server Management Studio).

Connect to `essql1.walton.uark.edu`

Your database is named `isys4283<your UARK username>`

Create the table by executing [this SQL Data Definition Language (DDL) script][3].

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

Create your [answers table][4] if you haven't already.

Browse the questions after they've been pulled by executing this SQL:

```sql
SELECT * FROM [isys4283].[dbo].[questions]
```

*Protip: you can filter the current questions using a [`WHERE` clause][6].*

```sql
SELECT * FROM [isys4283].[dbo].[questions]
WHERE created_at >= '2017-02-08'
```

Insert your answer passing the question id with it, for example:

```sql
INSERT INTO [answers] ([question_id],[answer])
VALUES ('7B236C12-1EF9-41B1-A08F-B813AF46A72D','Entity Relationship Diagram');
```

[![Youtube Thumbnail][2]][1]

For those who missed it in the first class, we created a table for you to
insert questions. [Watch on Youtube][1]. **NOTE** that the repository has changed since this
video was made. [Click here][5] to browse the repository files at that time if you want the context.
But keep in mind that you will want to create the latest version of the tables
in order to be current with the class.

[1]:https://youtu.be/2t2IeNA1bi0?hd=1
[2]:./docs/images/youtube.png
[3]:./sql/create-questions-table.sql
[4]:./sql/create-answers-table.sql
[5]:https://github.com/ISYS4283/questions-answers/tree/5452b90c488d6ae05b9c87e578a8165504bafb7f
[6]:http://www.w3schools.com/sql/sql_where.asp
[7]:https://waltonlab.uark.edu/
[8]:http://www.w3schools.com/sql/sql_insert.asp
