<?php

namespace Ardyn\Zipcode\Artisan\Commands;

use Illuminate\Config\Repository as Config;
use Symfony\Component\Console\Input\InputOption;
use Ardyn\Zipcode\Artisan\MigrationBuilder as Builder;
use Ardyn\Zipcode\Artisan\Exceptions\FileExistsException;
use Ardyn\Zipcode\Artisan\MigrationPublisher as Publisher;
use Ardyn\Zipcode\Artisan\Exceptions\HeaderDoesNotExistException;

/**
 * Creates a migration from the config file
 * Moves to the migrations folder
 *
 * @TODO Move more methods into the builder?
 */
class MigrateCommand extends ZipCodeCommand {

  /**
   * Command name
   *
   * @var string
   */
  protected $name = 'zipcode:migrate';

  /**
   * Command description
   *
   * @var string
   */
  protected $description = 'Create the database migration.';

  /**
   * Migration Builder
   *
   * @var \Ardyn\Zipcode\Artisan\MigrationBuilder
   */
  protected $builder;


  /**
   * Migration Builder
   *
   * @var \Ardyn\Zipcode\MigrationPublisher
   */
  protected $publisher;



  /**
   * Constructor
   *
   * @access public
   * @param \Illuminate\Config\Repository $config
   * @param \Ardyn\Zipcode\Artisan\MigrationBuilder $builder
   * @return void
   */
  public function __construct(
    Config $config,
    Builder $builder,
    Publisher $publisher
  ) {

    parent::__construct($config);

    $this->builder = $builder;
    $this->publisher = $publisher;

  } /* function __construct */



  /**
   * Fire the command
   *
   * @access public
   * @param void
   * @return void
   */
  public function fire() {

    try {

      $source = $this->getByOptionOrConfig('source', 'source_file');
      $columns = $this->getColumns($this->option('columns'));
      $fileContents = $this->builder->generateMigrationContents($source, $columns);
      $targetPath = $this->getByOptionOrConfig('path', 'migrations_path');

      $this->publisher->publishCreateTableMigration($targetPath, $fileContents);
      $this->publisher->publishAddFunctionMigration($targetPath);

      $this->info('Migration published!');
      $this->call('dump-autoload');

    } catch ( FileExistsException $e ) {

      $this->error('The migration file '.$e->getMessage().' already exists. Remove the migration and try again.');

    } catch ( HeaderDoesNotExistException $e ) {

      $this->error('The header '.$e->getMessage().' does not exist in the source file.');

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
      [ 'path', 'p', InputOption::VALUE_REQUIRED, 'Target path for migration.' ],
      [ 'columns', 'c', InputOption::VALUE_REQUIRED, 'Comma deliminated list of columns to include in the migration.' ],
      [ 'source', 's',  InputOption::VALUE_REQUIRED, 'Full path to CSV file to create migration from.' ],
    ];

  } /* function getOptions */

} /* class MigrateCommand */

/* EOF */
