<?php

namespace Ardyn\Zipcode\Artisan;

use SplFileObject;
use Illuminate\Config\Repository as Config;
use Ardyn\Zipcode\Artisan\Exceptions\HeaderDoesNotExistException;

class MigrationBuilder {

  /**
   * File Object
   *
   * @var \SplFileObject
   */
  protected $file;

  /**
   * Config Repository
   *
   * @var \Illuminate\Config\Repository
   */
  protected $config;



  /**
   * Constructor
   *
   * @access public
   * @param \Illuminate\Config\Repository $config
   * @return void
   */
  public function __construct(
    Config $config
  ) {

    $this->config = $config;

  } /* function __construct */



  /**
   * Generate migration file contents
   *
   * @access public
   * @param string $source Source file
   * @param array $columns Columns to include in migration
   * @return string
   */
  public function generateMigrationContents($source, $columns) {

    $file = new SplFileObject($source);
    $file->setFlags(SplFileObject::SKIP_EMPTY);
    $headers = $file->fgetcsv();
    $columns = $this->generateColumnsFromHeaders($headers, $columns);
    $template = $this->loadMigrationTemplate();

    foreach ( [
      'table',
      'zip_code',
      'latitude',
      'longitude',
    ] as $key ) {

      $template = str_replace("#{$key}#", $this->config->get("ardyn/zipcode::{$key}"), $template);

    }

    $template = str_replace('#columns#', $columns, $template);

    return $template;

  } /* function generateMigrationContents */



  /**
   * Load the migration template
   *
   * @access protected
   * @param void
   * @return string
   */
  protected function loadMigrationTemplate() {

    $templateFile = $this->config->get('ardyn/zipcode::migration_file') ?: __DIR__.'/../../../migration_table.tpl';
    return file_get_contents($templateFile);

  } /* function loadMigrationTemplate */



  /**
   * Create rows for the headers
   *
   * @access protected
   * @param array $headers CSV headers
   * @param array $columns Columns to include in the migration
   * @return string
   */
  protected function generateColumnsFromHeaders($headers, $columns) {

    $replacements = $this->getReplacements();
    $sql = '';

    foreach ( $columns as $row ) {

      if (  ! in_array($row, $headers) )
        throw new HeaderDoesNotExistException($row);

      if ( array_key_exists($row, $replacements) )
        $sql .= $replacements[$row].PHP_EOL;
      else
        $sql .= "      \$table->string('{$row}');".PHP_EOL;

    }

    return $sql;

  } /* function generateColumnsFromHeaders */



  /**
   * Return an array of field replacements
   *
   * @access protected
   * @param void
   * @return array
   */
  protected function getReplacements() {

    return [
      $zip = $this->config->get('ardyn/zipcode::zip_code') => "      \$table->char('{$zip}', 5);",
      $lat = $this->config->get('ardyn/zipcode::latitude') => "      \$table->decimal('{$lat}', 9, 6);",
      $long = $this->config->get('ardyn/zipcode::longitude') => "      \$table->decimal('{$long}', 9, 6);",
    ];

  } /* function getReplacements */

} /* class MigrationBuilder */

/* EOF */
