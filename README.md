# Description

A simple PHP-based contact list manager with authentication and logging features. Built using the [Bootstrap](https://getbootstrap.com/) framework and [DataTables](https://datatables.net/) to maintain a user-friendly functionality.

# Authors

* [Alex Winder](https://www.alexwinder.uk) 

# Requirements

* Web server with PHP (7+ recommended) and an SQL database back-end, such as MySQL or MariaDB.
* As part of PHP modules please ensure that the php*-mysql module is installed which is related to your version of PHP on your system. For example, if you are using PHP7 then you need to install the php7-mysql module.

# Usage

- Under sql/sql.sql execute the SQL file to import the relevant information to your database.
- Create a PHP file called "settings.local.inc.php" inside the includes/ directory. Begin the file with some opening PHP tags on the first line (<?php). Upon loading the system in a web browser for the first time you will be presented with a number of errors that DB_* values aren't set. Each DB_* value should be set with your own system settings and on it's own line, and each line must be closed with a semi-colon (;). There is an example file of the "settings.local.inc.php" file in the includes/ directory called ["EXAMPLE.settings.local.inc.php"](includes/EXAMPLE.settings.local.inc.php).
- Once database constants have been set you should now be able to log into the system. The default username is "admin" with a password of "LetMeIn123".

# License

This project is licensed under the [MIT License](LICENSE.md).