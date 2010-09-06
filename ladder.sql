DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS challenges;
DROP TABLE IF EXISTS rank_history;
DROP TABLE IF EXISTS matches;
DROP INDEX IF EXISTS rank_user;

CREATE TABLE "users" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "password" TEXT,
    "rank" INTEGER,
    "email" TEXT,
    "create_date" INTEGER,
    "max_challenges" INTEGER DEFAULT 999,
    "status" TEXT DEFAULT "active",
    "losses" INTEGER DEFAULT 0 ,
    "wins" INTEGER DEFAULT 0,
    "admin" INTEGER DEFAULT 0,
    "email_notification" INTEGER DEFAULT 0 );
    
CREATE TABLE "challenges" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "opponent" INTEGER,
    "challenger" INTEGER,
    "date" INTEGER,
    "challenger_result" INTEGER NOT NULL DEFAULT 0 , 
    "opponent_result" INTEGER NOT NULL DEFAULT 0);
    
CREATE TABLE "matches" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "date" INTEGER,
    "loser" INTEGER,
    "winner" INTEGER, 
    "forfeit" INTEGER NOT NULL DEFAULT 0);

CREATE TABLE "rank_history" (
    "date" INTEGER NOT NULL,
    "user_id" INTEGER NOT NULL,
    "rank" INTEGER NOT NULL 
    );

CREATE INDEX "rank_user" ON "rank_history" ("user_id" ASC);