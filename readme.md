# Zip Code Database

Use this package to convert a zip code into geographic coordinates, calculate distances between two zip codes,
find the nearest zip code to a latitude and longitude point, and return a list of zip codes within a radius of a zip code.

## Installation

Install via composer, publish configuration files, setup the database using the new artisan commands, register service providers and aliases.

### Composer

Edit your `composer.json` file:

```json
"require": {
  "ardyn/zipcode": "0.5"
}
```
Run `composer update`.

### Publish Configuration Files

If you want to change default configuration settings, run `php artisan config:publish ardyn/zipcode`,
then modify the contents of `app/config/packages/ardyn/zipcode/config.php`.

### Integrate with Laravel

Add the following to the `providers` array in your `app/config.php` file:

```php
'Ardyn\Zipcode\ZipCodeServiceProvider'
```

Add the alias in `aliases` array:

```php
'Zipcode' => 'Ardyn\Zipcode\Facades\ZipCode'
```

### Database Setup

To create the migrations, run `artisan zipcode:migrate source` where `source` is a CSV file of the zip code data with column headers.
This will create the migration using data from the `config.php` file.
You may supply additional columns by including them with the `--columns` option and deliminating each column by a comma [,].
The column names must match the headers in your zip code data source file.
Migrations will be moved to the migrations directory, as set in the `config.php` file.

After the migrations have been published, you may use `artisan migrate` to run the migrations.

To seed the database, call `artisan zipcode:seed source` where source is the same CSV file used for the migration.
By default, only the zip_code, latitude, and longitude columns will be seeded. To include more columns, use the `columns` option.
As with the migration, the `columns` must exist in the headers of the data source file.

Some methods are cached forever. You may want to clear your cache after updating the database.

## Usage

Usage is incredibly simple. Just call `Zipcode::find($zipCode)` to return a ZipCode model.

Examples:

```php
// Finds the zip code and returns the ZipCodeEngine class
$zipCode = Zipcode::find('90210');

// Return a property of the zip code record
// These three methods are agnostic of your database column names
$zipCode->zipCode();
$zipCode->latitude();
$zipCode->longitude();

// Access any other column using its name
$zipCode->my_column;

// Calculate distance between two zip codes
Zipcode::distance($zipCode, '84102', "miles");

// Return all zip codes within $outerRadius and $innerRadius
Zipcode::radiusSearch($zip1, $zip2, $outerRadius, $innerRadius, "miles");

// Find nearest zip code
Zipcode::nearest($latitude, $longitude);
```

### Units

When calling the distance method or the radiusSearch method, you can supply a 'unit' parameter of "miles", "feet", "kilometers", "meters", "radians", or "degrees".

## Extending the Package

You may over-ride the default Model by editing the `config.php` file to use your own model, which must implement `ardyn\Zipcode\Models\ZipCodeModelInterface`.
The repository may also be extended.

To change the model primaryKey, or table, you can just edit the `config.php` file as the provided ZipCodeRepository class will set those fields on the model.

## Zip Code Data Source

The provided zip code data in `/src/sample.csv` is only for demonstration purposes only! To obtain a complete zip code list, visit [http://greatdata.com/free-zip-code-database]
for a free database or purchase from [http://www.zip-codes.com/zip-code-database.asp] for a database with more accurate WGS84 coordiantes.

## TODO
* Refactor this shit!
* Semantic versioning!
* Unit test the ZipCodeRepository
* Unit test the artisan commands
* Better documentation!
* Find should return a StdClass instead of an Eloquent Model
