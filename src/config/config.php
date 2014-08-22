<?php

return array(

 /**
  * Database table to use
  *
  * @var string
  */
  'table' => 'zip_codes',

 /**
  * Column name of the zip code field
  *
  * @var string
  */
  'zip_code' => 'ZIPCode',

 /**
  * Column name of the latitude field
  *
  * @var string
  */
  'latitude' => 'Latitude',

 /**
  * Column name of the longitude field
  *
  * @var string
  */
  'longitude' => 'Longitude',

 /**
  * Repository class for the Engine
  *
  * @var string
  */
  'repository' => '\Ardyn\Zipcode\Repositories\Eloquent\ZipCodeRepository',

 /**
  * Model class for the Engine
  *
  * @var string
  */
  'model' => '\Ardyn\Zipcode\Models\Eloquent\ZipCode',

 /**
  * Default unit to use
  *
  * @var string
  */
  'default_unit' => Ardyn\Zipcode\Repositories\AbstractZipCodeRepository::MILES,

  /**
   * Path to migrations folder
   *
   * @var string
   */
  'migrations_path' => app_path('database/migrations'),

  /**
   * Template for migration table
   *
   * @var string
   */
  'migration_file' => base_path('vendor/ardyn/zipcode/src/migration_table.tpl'),

  /**
   * Source file to build migrations and seed table
   *
   * @var string
   */
  'source_file' => base_path('vendor/ardyn/zipcode/src/sample.csv'),

);

/* EOF */
