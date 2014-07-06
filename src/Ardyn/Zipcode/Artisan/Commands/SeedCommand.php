<?php

namespace Ardyn\Zipcode\Artisan\Commands;

use Ardyn\Zipcode\Artisan\DatabaseSeeder;
use Illuminate\Config\Repository as Config;
use Symfony\Component\Console\Input\InputOption;
use Ardyn\Zipcode\Artisan\Exceptions\HeaderDoesNotExistException;

class SeedCommand extends ZipCodeCommand {

  /**
   * Command name
   *
   * @var string
   */
  protected $name = 'zipcode:seed';

  /**
   * Command description
   *
   * @var string
   */
  protected $description = 'Seed the database with a CSV file.';

  /**
   * Database Seeder
   *
   * @var \Ardyn\Zipcode\Artisan\DatabaseSeeder
   */
  protected $seeder;



  /**
   * Constructor
   *
   * @access public
   * @param \Illuminate\Config\Repository $config
   * @param \Ardyn\Zipcode\Artisan\DatabaseSeeder $seeder
   * @return void
   */
  public function __construct(
    Config $config,
    DatabaseSeeder $seeder
  ) {

    parent::__construct($config);

    $this->seeder = $seeder;

  } /* function __construct */



  /**
   * Execute the command
   *
   * @access public
   * @param void
   * @return void
   */
  public function fire() {

    $sourceFile = $this->getByOptionOrConfig('source', 'source_file');
    $columns = $this->getColumns($this->option('columns'));
    $start = time();

    $response = $this->confirm("This command will delete the `".$this->config('table')."` table and then seed the '".$this->config('connection')."' database.\n  Do you want to continue? [y|n]");

    if ( ! $response )
      return;

    try {

      $this->info('Deleting table...');
      $this->seeder->delete($this->config('table'));

      $this->info('Preparing data...');
      $data = $this->seeder->prepare($sourceFile, $columns);

      $this->info('Seeding database...');
      $this->seeder->seed($columns, $data);

      $time = time() - $start;
      $this->info("Database Seeded in {$time} seconds!");

    } catch ( HeaderDoesNotExistException $e ) {

      $this->error("The column `".$e->getMessage()."` does not exist in {$sourceFile}");

    }

  } /* function fire */



  /**
   * Return the options
   *
   * @access protected
   * @param void
   * @return array
   */
  protected function getOptions() {

    return [
      [ 'columns', 'c', InputOption::VALUE_REQUIRED, 'Comma deliminated list of columns to include in the seed.' ],
      [ 'source', 's', InputOption::VALUE_REQUIRED, 'Data source as CSV file.' ],
    ];

  } /* function getOptions */

} /* class SeedCommand */

/* EOF */
