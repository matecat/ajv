# AJV 

**AJV** is a library to validate Airbnb JSON files.

## Usage

AJV uses `Symfony Console` component.

To use it launch the following command on a CLI:

```
php bin/console  ajv:json:check [json-file-path]
```

If no error were found then the validation passes:

```
 php bin/console  ajv:json:ch tests/json/ok.json 

Validation report for file tests/json/ok.json
=============================================

+-----------------+----+
| IS A VALID JSON | OK |
+-----------------+----+
| ERROR(S)s       |    |
+-----------------+----+
                                                                                                                   
 [OK] Validation passed.                                                                                                
                                                                                                                       
```

Otherwise you obtain a report like this:

```
php bin/console  ajv:json:ch tests/json/duplicate-ids.json 

+-----------------+-------------------------------------------------------------------------------------------------------+
| IS A VALID JSON | OK                                                                                                    |
+-----------------+-------------------------------------------------------------------------------------------------------+
| ERROR(S)s       | TYPE: ids                                                                                             |
|                 | NODE: 0                                                                                               |
|                 | ID: 1234                                                                                              |
|                 | SEGMENT ID: abcdefgh                                                                                  |
|                 | KEY: lorem.ipsum.1                                                                                    |
|                 | MESSAGE: id duplicated                                                                                |
|                 |                                                                                                       |
|                 | TYPE: ids                                                                                             |
|                 | NODE: 2                                                                                               |
|                 | ID: 4567                                                                                              |
|                 | SEGMENT ID: gdfgdfgdf                                                                                 |
|                 | KEY: lorem.ipsum.2                                                                                    |
|                 | MESSAGE: id duplicated                                                                                |
|                 |                                                                                                       |
|                 | TYPE: segment_ids                                                                                     |
|                 | NODE: 1                                                                                               |
|                 | ID: 7890                                                                                              |
|                 | SEGMENT ID: gregregreg                                                                                |
|                 | KEY: lorem.ipsum.3                                                                                    |
|                 | MESSAGE: segment_id duplicated                                                                        |
|                 |                                                                                                       |
+-----------------+-------------------------------------------------------------------------------------------------------+

                                                                                                                        
 [ERROR] Validation NOT passed.                                                                                         
                                                                                                                        
```

### Checks on JSON file

The library checks for:

* JSON validity
* Missing keys
* Duplicated `id` keys
* Duplicated `segment_id` keys
* Invalid `target` keys

In case of error, this is the standard payload array:

```
TYPE: <string>
NODE: <int>
ID: <int>
SEGMENT ID: <string>
KEY: <string>
MESSAGE: <string>
```

## Support

If you found an issue or had an idea please refer [to this section](https://github.com/matecat/ajv/issues).

## Authors

* **Mauro Cassani** - [github](https://github.com/mauretto78)

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details