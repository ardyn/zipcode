<?php

namespace Ardyn\Zipcode\Artisan;

use DB;
use SplFileObject;
use Illuminate\Config\Repository as Config;
use Ardyn\Zipcode\Artisan\Exceptions\HeaderDoesNotExistException;

class DatabaseSeeder {

  /**
   * Rows per statement
   *
   * @const int
   */
  const ROWS_PER_STATEMENT = 2500;



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
   * Seed the database
   *
   * @access public
   * @param array $columns
   * @param array $table
   * @return void
   */
  public function seed($columns, $table) {

    while ( current($table) !== false ) {
      $sql = $this->createSqlStatement($columns, $table);
      $this->executeStatement($sql);
    }

  } /* function seed */



  /**
   * Prepare data for seeding
   *
   * @access public
   * @param string $sourceFile
   * @param array $columns
   * @return array
   */
  public function prepare($sourceFile, &$columns) {

    $file = new SplFileObject($sourceFile);
    $file->setFlags(SplFileObject::SKIP_EMPTY);

    $headers = $file->fgetcsv();
    $columns = $this->getColumns($columns, $headers);

    $zipCodeKey = key(array_intersect($columns, [ $this->config->get('ardyn/zipcode::zip_code') ]));

    $table = $this->prepareTable($file, $zipCodeKey);
    $file = null;

    return $table;

  } /* function FunctionName */



  /**
   * Get the columns to seed
   *
   * @access protected
   * @param array $headers
   * @param array $columns
   * @return array
   */
  protected function getColumns($columns, $headers) {

    if  ( $invalid = array_diff($columns, $headers) )
      throw new HeaderDoesNotExistException(current($invalid));

    return array_intersect($headers, $columns); // Strip unused columns and preserve keys of $headers

  } /* function getColumns */



  /**
   * Remove duplicates.
   *
   * @access public
   * @param \SplFileObject $sourceFile
   * @param int $zipCodeKey
   * @return array
   */
  protected function prepareTable(SplFileObject $file, $zipCodeKey) {

    $table = array();

    // Read file into an array while overriding duplicate zip codes
    while ( ! $file->eof() ) {
      if ( $row = $file->fgetcsv() ) {
        $table[$row[$zipCodeKey]] = $row;
      }
    }

    return $table;

  } /* function prepare */



  /**
   * Delete table
   *
   * @access public
   * @param void
   * @return void
   */
  public function delete() {

    DB::table($this->config->get('ardyn/zipcode::table'))
      ->delete();

  } /* function delete */



  /**
   * Create the INSERT statement
   *
   * @access protected
   * @param array $columns
   * @param \SplFileObject $file
   * @return string
   */
  protected function createSqlStatement(array $columns, array &$table) {

    $sql = $this->createInsertStatement($columns);

    for ( $x = 0; $x < self::ROWS_PER_STATEMENT; $x++ ) {
      if ( $line = current($table) ) {
        next($table);
        $sql .= $this->createValueStatement($line, $columns);
      }
      else {
        break;
      }
    }

    return rtrim($sql, ','.PHP_EOL).';';

  } /* function createInsertStatement */



  /**
   * Creates the INSERT portion of the statement
   *
   * @access protected
   * @param array $columns
   * @return string
   */
  protected function createInsertStatement(array $columns) {

    $sql  = 'INSERT INTO '.$this->config->get('ardyn/zipcode::table') .'(';

    foreach ( $columns as $column )
      $sql .= "`{$column}`,";

    $sql = rtrim($sql, ',');
    $sql .=') VALUES ';

    return $sql.PHP_EOL;

  } /* function createInsertStatement */



  /**
   * Creates the VALUE portion of the query
   *
   * @access protected
   * @param array $line Row from source file
   * @param array $columns Columns to seed
   * @return string
   */
  protected function createValueStatement(array $line, array $columns) {

    $len = count($line);
    $str = '(';

    for ( $x = 0; $x < $len; $x++ )
      if ( array_key_exists($x, $columns) )
        $str .= "'".str_replace("'", "\\'", $line[$x])."',";

    $str = rtrim($str, ',');
    $str .= '),'.PHP_EOL;

    return $str;

  } /* function createValueStatement */



  /**
   * Execute the query
   *
   * @access protected
   * @param string $sql
   * @return void
   */
  protected function executeStatement($sql) {

    DB::unprepared($sql);

  } /* function executeQuery */

} /* class DatabaseSeeder */

/* EOF */
