# Description

A simple PHP-based contact list manager with authentication and logging features. Built using the Bootstrap framework and DataTables to maintain a minimalistic functionality.

# Authors

* Alex Winder 

# Requirements

* Web server with PHP (7+ recommended) and an SQL database back-end, such as MySQL or MariaDB.

# Usage

- Under sql/sql.sql execute the SQL file to import the relevant information to your database.
- Create a PHP file called localsettings.inc.php inside the includes/ directory. Begin the file with some opening PHP tags on the first line (<?php). Upon loading the system in a web browser for the first time you will be presented with a number of errors that DB_* values aren't set. Each DB_* value should be set with your own system settings and on it's own line, and must be closed with a semi-colon (;).
- Once database constants have been set you should now be able to log into the system. The default username is "admin" with a password of "LetMeIn123"

# License

This project is licensed under the MIT License.