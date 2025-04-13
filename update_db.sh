#!/bin/bash

export DATABASE_PATH=$1

function add_table {
	table=$1;
	
	echo -ne "TABLE $1: ";
	
	if sqlite3 $DATABASE_PATH "create table $table (placeholder_column)" > /dev/null 2> /dev/null; then
		
		echo -e "\033[32mCREATED\033[0m";
	else
		echo -e "\033[33mEXISTS\033[0m";
	fi
}

function add_column {
	table=$1
	column=$2
	echo -ne "\tcolumn .$column: ";
	if sqlite3 $DATABASE_PATH "select * from $table where $column = NULL" > /dev/null 2> /dev/null; then
		echo -e "\033[33mEXISTS\033[0m";
	else
		sqlite3 $DATABASE_PATH "alter table $table add $column $3";
		echo -e "\033[32mCREATED\033[0m";
	fi
}

function finalize_table {
	echo -ne "\n";
	table=$1
	sqlite3 $DATABASE_PATH "alter table $1 drop column placeholder_column" > /dev/null 2> /dev/null;
}

if [ -z $DATABASE_PATH ]; then
	echo "USAGE: update_db <path>";
	exit 1;
fi

add_table users
add_column users userid "TEXT NOT NULL"
add_column users pfpurl "TEXT"
add_column users bio "TEXT"
add_column users createdtime "INTEGER NOT NULL"
add_column users username "TEXT NOT NULL"
add_column users password "TEXT NOT NULL"
add_column users token "TEXT"
add_column users theme "TEXT DEFAULT 'default'"
finalize_table users

add_table comments
add_column comments commentid "TEXT NOT NULL"
add_column comments userid "TEXT NOT NULL"
add_column comments songid "TEXT NOT NULL"
add_column comments content "TEXT NOT NULL"
add_column comments timestamp "INTEGER NOT NULL"
finalize_table comments

add_table interactions
add_column interactions userid "TEXT NOT NULL"
add_column interactions type "TEXT NOT NULL"
add_column interactions songid "TEXT NOT NULL"
add_column interactions timestamp "INTEGER"
finalize_table interactions

add_table songs
add_column songs songid "TEXT NOT NULL"
add_column songs authorid "TEXT NOT NULL"
add_column songs views "INTEGER NOT NULL DEFAULT 0"
add_column songs likes "INTEGER NOT NULL DEFAULT 0"
add_column songs downloads "INTEGER NOT NULL DEFAULT 0"
add_column songs songurl "TEXT NOT NULL"
add_column songs createdtime "INTEGER"
add_column songs tags "TEXT"
add_column songs name "TEXT"
add_column songs summary "TEXT"
add_column songs deleted "INTEGER NOT NULL DEFAULT 0"
add_column songs featured "INTEGER NOT NULL DEFAULT 0"
add_column songs description "TEXT"
finalize_table songs