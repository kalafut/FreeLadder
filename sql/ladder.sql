DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS challenge;
DROP TABLE IF EXISTS rank_history;
DROP TABLE IF EXISTS match;
DROP INDEX IF EXISTS rank_user;
DROP TABLE IF EXISTS ladder;
DROP TABLE IF EXISTS ladder_user;

CREATE TABLE "user" (
    "user_id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "name" TEXT,
    "password" TEXT,
    "email" TEXT,
    "create_date" INTEGER,
    "admin" INTEGER DEFAULT 0,
    "email_notification" INTEGER DEFAULT 0
    );
    

CREATE TABLE "ladder" (
    "ladder_id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "ladder_name" TEXT,
    "access_name" TEXT,
    "type" TEXT,
    "status" TEXT
    );
    
CREATE TABLE "ladder_user" (
    "user_id" INTEGER,
    "ladder_id" INTEGER,
    "admin" INTEGER DEFAULT 0,
    "rank" INTEGER,
    "max_challenges" INTEGER DEFAULT 999,
    "status" TEXT DEFAULT "active",
    "losses" INTEGER DEFAULT 0,
    "wins" INTEGER DEFAULT 0
    );

    
CREATE TABLE "challenge" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "ladder_id" INTEGER, 
    "challenger" INTEGER,
    "opponent" INTEGER,
    "date" INTEGER,
    "opponent_result" INTEGER NOT NULL DEFAULT 0,
    "challenger_result" INTEGER NOT NULL DEFAULT 0 
    );
    
CREATE TABLE "match" (
    "id" INTEGER PRIMARY KEY AUTOINCREMENT,
    "ladder_id" INTEGER, 
    "date" INTEGER,
    "winner" INTEGER, 
    "loser" INTEGER,
    "forfeit" INTEGER NOT NULL DEFAULT 0
    );

CREATE TABLE "rank_history" (
    "user_id" INTEGER NOT NULL,
    "ladder_id" INTEGER, 
    "date" INTEGER NOT NULL,
    "rank" INTEGER NOT NULL 
    );

CREATE INDEX "rank_user" ON "rank_history" ("user_id" ASC);

INSERT INTO ladder(ladder_id, ladder_name, access_name) VALUES(0, "Demo", "demo");
