#!/bin/bash

if test -z $1; then
	echo "Error: you must specify an output file";
	exit 1
fi

cat data/template.sql | sqlite3 $1;