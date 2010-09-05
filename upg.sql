BEGIN TRANSACTION;
CREATE TEMPORARY TABLE "users2" ("id" INTEGER PRIMARY KEY AUTOINCREMENT ,"name" TEXT,"login" TEXT,"password" TEXT,"rank" INTEGER,"email" TEXT,"create_date" INTEGER,"max_challenges" INTEGER DEFAULT 999 ,"status" TEXT DEFAULT "active" ,"losses" INTEGER DEFAULT 0 ,"wins" INTEGER DEFAULT 0 ,"admin" INTEGER DEFAULT 0 ,"email_notification" INTEGER DEFAULT 0 );
INSERT INTO users2 SELECT id2, name,login,password,rank,email,create_date,max_challenges,status,losses,wins,admin,email_notification FROM users;
DROP TABLE users;
CREATE TABLE "users" ("id" INTEGER PRIMARY KEY AUTOINCREMENT ,"name" TEXT,"login" TEXT,"password" TEXT,"rank" INTEGER,"email" TEXT,"create_date" INTEGER,"max_challenges" INTEGER DEFAULT 999 ,"status" TEXT DEFAULT "active" ,"losses" INTEGER DEFAULT 0 ,"wins" INTEGER DEFAULT 0 ,"admin" INTEGER DEFAULT 0 ,"email_notification" INTEGER DEFAULT 0 );
INSERT INTO users SELECT id, name,login,password,rank,email,create_date,max_challenges,status,losses,wins,admin,email_notification FROM users2;
DROP TABLE users2;
COMMIT;