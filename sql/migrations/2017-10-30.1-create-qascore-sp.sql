IF OBJECT_ID('dbo.qascore', 'p') IS NULL
    EXEC('CREATE PROCEDURE qascore AS SELECT 1')
