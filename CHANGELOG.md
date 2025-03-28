# Address Book Changelog

## Upcoming Changes

- Docker PHP support updated version `8.4` ([#8](https://github.com/AlexWinder/address-book/issues/8)).
- Docker MySQL support updated to version `9`.
- Fixed incorrect redirect to index page after creating a new user.

## 1.0.5 (2025-01-05)

- Added timezone support by [@zaydons](https://github.com/zaydons) in https://github.com/AlexWinder/address-book/pull/6.

## 1.0.4 (2022-08-03)

- Updated README to include support for `docker compose` on top of `docker-compose`.
- Fixed README with a typo on the `DB_PASS` value.
- API calls returned with the correct header of `Content-Type: application/json`.
- API calls returned with the correct HTTP status code rather than all being returned as HTTP 200 OK.

## 1.0.3 (2022-07-17)

- Added Docker build environment.
- Updated README file instructions and markdown formatting.

## 1.0.2 (2020-04-30)

- Updated jQuery to 3.5.0 to address [CVE-2020-11022](https://github.com/advisories/GHSA-gxr4-xjj5-5px2).
- Updated Bootstrap to 3.4.1.

## 1.0.1 (2019-12-26)

- Added missing DataTables sort images.
- Added missing Bootstrap map files.
- Fixed Bootstrap directory name causing issues on some browsers not loading assets.

## 1.0.0 (2018-05-24)

- Initial release of system.
