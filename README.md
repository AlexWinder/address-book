# Address Book

Address Book is a simple PHP-based contact list manager with authentication, logging features and an API to allow integration with other services. Built using the [Bootstrap](https://getbootstrap.com/) framework, [DataTables](https://datatables.net/), [jQuery](https://jquery.com/) and [FontAwesome](https://fontawesome.com/) to maintain a user-friendly functionality.

## Installation

There are 2 methods to installation. Regardless of which method you choose you should first complete some prerequisites.

Create a copy of the [EXAMPLE.settings.local.inc.php](includes/EXAMPLE.settings.local.inc.php) with the name of `settings.local.inc.php`. This file should exist in the [includes](includes/) directory. In this file are a number of different values which can be set based on your environment.

For example, on a Linux system:

```bash
cp includes/EXAMPLE.settings.local.inc.php includes/settings.local.inc.php
```

### 1. Docker (Recommended)

Docker is the recommended method to set up this system due to it's ease of getting things configured quickly and also it is far less likely to be a victim of issues which may occur due to OS or versions of software. The below assumes that you have `docker` and `docker compose` installed on your system.

1. First you should build the environment. This will download any images and set up the custom images which are required to run in the next step.

```bash
docker compose build
```

2. Once the `build` has completed successfully you can then start the environment with the `up` command.

```bash
docker compose up
```

This may do some additional downloading which wasn't done during the `build` stage. This is normal.

3. The first time you run the `up` command the database will be initialised and the `root` user will have a randomly generated password set. You should check through the console logs where there will be a message which indicates what the password has been set to.

```console
mysql-1    | 2025-03-27 08:52:31+00:00 [Note] [Entrypoint]: GENERATED ROOT PASSWORD: iNBqruolQSo6ZEsc8ZXyQ5QpUWke9KF5
```

You should take this randomly generated password and populate it in the `DB_PASS` of your `settings.local.inc.php`.

4. The remaining values in your `settings.local.inc.php` should then also be set to meet the Docker environment which you are using:

- `DB_SERVER` should be set to `mysql`.
- `DB_USER` should be set to `root`.
- `DB_PASS` should be set the password as detailed above.
- `DB_NAME` should be set to `address_book`.
- `SITE_URL` should be set to the address from which the system will be accessible from. Typically http://localhost/ is acceptable.

### 2. Manual Installation

If you wish to set up the system manually then this too can be done.

#### Requirements

- A web server with PHP (8+ recommended).
- A relational database management system (RDBMS), such as MySQL or MariaDB.
- The `mysql` and `pdo` PHP modules should be installed and enabled for your version of PHP. For example if you are using PHP8.4 then you would need to install `php8.4-mysql`.

#### Database Configuration

You should create a database called `address_book` along with a user which has permissions to this newly created database.

You should then import the [sql/sql.sql](sql/sql.sql) file into your database to set the system up to a baseline. For example:

```bash
mysql -u <username> (-p if your user account has a password) address_book < /location/to/sql/sql.sql
```

#### Settings Configuration

You should then set your `settings.local.inc.php` values to match your environment:

- `DB_SERVER` should be set to the IP address or hostname of your database server. If this is on the same server that the codebase is in then typically this would be `127.0.0.1`.
- `DB_USER` should be set to the user which you created for access to the database.
- `DB_PASS` should be set the password for the user which you created.
- `DB_NAME` should be set to `address_book`, if you used the default set up.
- `SITE_URL` should be the FQDN of the address of the server.

#### Web Server Configuration

You should configure your web server so that the document root is set as the [html](html) directory. However, the web server user for your configuration should have access to both the [html](html/) and [includes](includes/) directories.

### Testing

Once you have finished running one of the above methods you can then test if the system is working by visiting the address of the server in a browser.

If the system is working correctly then you should be prompted with a login window. The default credentials for the system are:

- Username: `admin`
- Password: `LetMeIn123`

## Local Settings Configuration Values

There are several configuration values which can be set in the `includes/settings.local.inc.php` file. Below lists settings with their appropriate values:

- `TIMEZONE` should be set to the timezone you require for the system. See the [PHP Manual](https://www.php.net/manual/en/timezones.php) for options. 

## Screenshots

Screenshots of the system can be found in the [screenshots](screenshots/) directory, or by viewing the [SCREENSHOTS.md](SCREENSHOTS.md) file.

## API

The API built in the system is accessed using a HTTP GET request to the [api.php](html/api.php) page. The request requires 3 values:

- `t` for the API token.
- `m` for the API method.
- `q` for the API query string - note that the query must contain no whitespace (including encoded whitespace characters).

For example, `http://localhost/api.php?t=APITOKEN&m=APIMETHOD&q=APIQUERY`.

API tokens are created on the same [api.php](html/api.php) page.

Results of an API call are returned in a JSON array with the following indexes:

- `success` - This is set to `0` by default, unless the API call is successful in which case it will be set to `1`.
- `method` - The method used as part of the API call. This will only return valid methods (see below). This is the `m` value in the HTTP GET request.
- `query` - The query used against the method. This is the `q` value in the HTTP GET request.
- `result` - The result of the API call, if any.
- `result_message` - Used primarily for troubleshooting, such as if a token or method is valid.

```json
{
    "success": 0,
    "method": null,
    "query": null,
    "result": "invalid_token",
    "result_message": "An invalid API token was sent. This means that the token does not exist or you are making an API call from an unauthorised IP address."
}
```

```json
{
    "success": 0,
    "method": null,
    "query": null,"
    result": "invalid_method",
    "result_message": "An invalid API method was requested. Please follow the documentation and check your requested method exists, this includes correct spelling and upper\/lower case characters."
}
```

```json
{
    "success": 0,
    "method": "findNumber",
    "query": "01189998819991197253",
    "result": "no_result",
    "result_message": "A result could not be found."
} 
```

```json
{
    "success": 1,
    "method": "findNumber",
    "query": "156421616",
    "result": "William Shakespeare",
    "result_message": "API call successful."
}
```

### API Notes

If an API token has no authorised IP address associated with it, then this means that the token can be used from any IP address. If this is not intended then specify an IP address when creating the API token.

### API Methods

API methods are used in the `m` value in the HTTP GET request. The following methods are valid.

- `findNumber` - Obtain the first contact found based on a queried phone number (mobile and home). Note that if more than one contact exists with the same phone number then this will only return the first result, based on the last name of the contacts in alphabetical order.
  - Example: a query of `api.php?t=APITOKEN&m=findNumber&q=0987654321` will return the result (if it exists) for the phone number `0987654321`.

## License

This project is licensed under the [MIT License](LICENSE.md).

## Contributions

Pull requests (PRs) to this repository are welcome. If your PR is to address an open issue, please try to keep your changes specific to only that issue. Also please avoid addressing multiple issues within a single PR.
