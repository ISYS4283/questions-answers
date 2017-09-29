GRANT SELECT TO student;
GRANT INSERT ON questions TO student;
GRANT INSERT ON answers TO student;
GRANT UPDATE ON questions(question) TO student;
GRANT UPDATE ON answers(answer, question_id) TO student;
