PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "interactions" (
	"userid"	TEXT NOT NULL,
	"type"	TEXT NOT NULL,
	"songid"	TEXT NOT NULL,
	"timestamp"	INTEGER,
	PRIMARY KEY("userid","songid")
);

CREATE TABLE IF NOT EXISTS "songs" (
	"songid"	TEXT NOT NULL UNIQUE,
	"authorid"	TEXT NOT NULL,
	"views"	INTEGER NOT NULL DEFAULT 0,
	"likes"	INTEGER NOT NULL DEFAULT 0,
	"downloads"	INTEGER NOT NULL DEFAULT 0,
	"songurl"	TEXT NOT NULL,
	"createdtime"	INTEGER,
	"tags"	TEXT,
	"name"	TEXT,
	"desc"	TEXT,
	"featured"	INTEGER NOT NULL DEFAULT 0,
	PRIMARY KEY("songid")
);

CREATE TABLE IF NOT EXISTS "users" (
	"userid"	TEXT NOT NULL UNIQUE,
	"pfpurl"	TEXT,
	"bio"	TEXT,
	"createdtime"	INTEGER NOT NULL,
	"username"	TEXT NOT NULL,
	"password"	TEXT NOT NULL,
	"token"	TEXT,
	PRIMARY KEY("userid")
);

COMMIT;
