# CLI utility - Files diff generator

[![Maintainability](https://api.codeclimate.com/v1/badges/3703746e8f3a2cb9f918/maintainability)](https://codeclimate.com/github/Alexey-654/php-project-lvl2/maintainability) [![Test Coverage](https://api.codeclimate.com/v1/badges/3703746e8f3a2cb9f918/test_coverage)](https://codeclimate.com/github/Alexey-654/php-project-lvl2/test_coverage) ![PHP CI](https://github.com/Alexey-654/php-project-lvl2/workflows/PHP%20CI/badge.svg)



## Installation
```bash
$ composer require alexey-654/php-project-lvl2
```

## Usage
This CLI support -
- for input files - JSON or YAML format
- for output three type format  - 'pretty' by default, 'plain' and JSON

In your terminal go to the directory with installed package, then type:

```bash
$ bin/gendiff --format plain pathToFile1 pathToFile2
```

## Example
### input JSON files, output format - 'pretty' 
[![asciicast](https://asciinema.org/a/DNPv3DjZl79cp0U07NWzuMc40.svg)](https://asciinema.org/a/DNPv3DjZl79cp0U07NWzuMc40)

### input YAML files, output format - 'pretty' 
[![asciicast](https://asciinema.org/a/6LRUGHYFKIJOLg9GUgALfdpdy.svg)](https://asciinema.org/a/6LRUGHYFKIJOLg9GUgALfdpdy)

### input JSON files, output format - 'plain' 
[![asciicast](https://asciinema.org/a/mjyeGVjrjqML58OdIeqlv5npB.svg)](https://asciinema.org/a/mjyeGVjrjqML58OdIeqlv5npB)

### input JSON files, output format - 'JSON' 
[![asciicast](https://asciinema.org/a/HfKuyF9mapBK8bS1lFR7FQXcx.svg)](https://asciinema.org/a/HfKuyF9mapBK8bS1lFR7FQXcx)