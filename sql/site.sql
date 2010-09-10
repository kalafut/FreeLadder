DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS ladders;
DROP TABLE IF EXISTS ladder_members;
DROP INDEX IF EXISTS rank_user;

CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "password" TEXT,
    "email" TEXT,
    "create_date" INTEGER,
    "admin" INTEGER DEFAULT 0
    );
    

CREATE TABLE "ladders" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "login_name" TEXT,
    "type" TEXT,
    "status" TEXT
    "admin" INTEGER
    );
    
CREATE TABLE "ladder_members" (
    "user_id" INTEGER,
    "ladder_id" INTEGER
    );