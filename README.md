# Description

A simple PHP-based contact list manager with authentication and logging features. Built using the [Bootstrap](https://getbootstrap.com/) framework, [DataTables](https://datatables.net/), [jQuery](https://jquery.com/) and [FontAwesome](https://fontawesome.com/) to maintain a user-friendly functionality.

# Authors

* [Alex Winder](https://www.alexwinder.uk) 

# Requirements

* Web server with PHP (7+ recommended) and an SQL database back-end, such as MySQL or MariaDB.
* As part of PHP modules please ensure that the php*-mysql module is installed which is related to your version of PHP on your system. For example, if you are using PHP7 then you need to install the php7-mysql module.

# Usage

- Under sql/sql.sql execute the SQL file to import the relevant information to your database. The default database is set to "address_book". To import the SQL file:
> mysql -u <username> (-p if your user account has a password) address_book < /location/to/sql/sql.sql
- Create a PHP file called "settings.local.inc.php" inside the includes/ directory. Begin the file with some opening PHP tags on the first line (<?php). Upon loading the system in a web browser for the first time you will be presented with a number of errors that DB_* values aren't set. Each DB_* value should be set with your own system settings and on it's own line, and each line must be closed with a semi-colon (;). There is an example file of the "settings.local.inc.php" file in the includes/ directory called ["EXAMPLE.settings.local.inc.php"](includes/EXAMPLE.settings.local.inc.php).
- Once database constants have been set you should now be able to log into the system. The default username is "admin" with a password of "LetMeIn123".

The system is separated out into two main directory: html and includes. It is recommended that you configure your web server to only allow public access to the html directory but your web server user locally, such as www-data, should be able to view the files in the includes directory. This will provide adequate security and prevent users for accessing anything in the includes directory.

# Screenshots

Screenshots of the system can be found in the screenshots/ directory, or by viewing the [SCREENSHOTS.md](SCREENSHOTS.md) file.

# API

The API built in the system is accessed using a HTTP GET request to the api.php page. The request requires 3 values:
- **t** for the API token.
- **m** for the API method.
- **q** for the API query string - note that the query must contain no whitespace (including encoded whitespace characters).
For example, https://yourdomain.local/api.php?t=APITOKEN&m=APIMETHOD&q=APIQUERY

Results of an API call are returned in a JSON array with the following indexes:
- success : This is set to 0 by default, unless the API call is successful in which case it will be set to 1.
- method : The method used as part of the API call. This will only return valid methods (see below). This is the **m** value in the HTTP GET request.
- query : The query used against the method. This is the **q** value in the HTTP GET request.
- result : The result of the API call, if any.
- result_message : Used primarily for troubleshooting, such as if a token or method is valid.

``` 
{"success":0,"method":null,"query":null,"result":"invalid_token","result_message":"An invalid API token was sent. This means that the token does not exist or you are making an API call from an unauthorised IP address."}
{"success":0,"method":null,"query":null,"result":"invalid_method","result_message":"An invalid API method was requested. Please follow the documentation and check your requested method exists, this includes correct spelling and upper\/lower case characters."}
{"success":0,"method":"findNumber","query":"01189998819991197253","result":"no_result","result_message":"A result could not be found."} 
{"success":1,"method":"findNumber","query":"156421616","result":"William Shakespeare","result_message":"API call successful."}
```

## API Notes

If an API token has no authorised IP address associated with it, then this means that the token can be used from any IP address. If this is not intended then specify an IP address when creating the API token.

## API Methods

API methods are used in the **m** value in the HTTP GET request. The following methods are valid.

- **findNumber** : Obtain the first contact found based on a queried phone number (mobile and home). Note that if more than one contact exists with the same phone number then this will only return the first result, based on the last name of the contacts in alphabetical order.
  - Example: a query of api.php?t=**APITOKEN**&m=**findNumber**&q=**0987654321** will return the result (if it exists) for the phone number 0987654321.

# License

This project is licensed under the [MIT License](LICENSE.md).