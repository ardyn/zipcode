<?php

namespace Ardan\Zipcode\Artisan\Commands;

use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem as File;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Ardan\Zipcode\Artisan\MigrationBuilder as Builder;
use Ardan\Zipcode\Artisan\Exceptions\FileExistsException;
use Ardan\Zipcode\Artisan\Exceptions\HeaderDoesNotExistException;

/**
 * Creates a migration from the config file
 * Moves to the migrations folder
 *
 * @TODO Split the WGS84distance function from this migration
 * @TODO create the database migration based on the CSV file?
 * @TODO Move more methods into the builder?
 */
class MigrateCommand extends ZipCodeCommand {

  /**
   * Migration file name
   *
   * @const string
   */
  const MIGRATION_TABLE_NAME = 'create_ardan_zip_codes_table';

  /**
   * WGS84distance function migration name
   *
   * @const string
   */
  const MIGRATION_FUNCTION_NAME = 'add_wgs84distance_function';



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
   * @var \Ardan\Zipcode\MigrationBuilder
   */
  protected $builder;



  /**
   * Constructor
   *
   * @access public
   * @param \Illuminate\Config\Repository $config
   * @param \Illuminate\Filesystem\Filesystem $file
   * @param \Ardan\Zipcode\Artisan\MigrationBuilder $builder
   * @return void
   */
  public function __construct(
    Config $config,
    File $file,
    Builder $builder
  ) {

    parent::__construct($config, $file);

    $this->builder = $builder;

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

      $source = $this->getByArgumentOrConfig('source', 'source_file');
      $columns = $this->getColumns($this->option('columns'));
      $fileContents = $this->builder->generateMigrationContents($source, $columns);

      $this->saveMigrationFile($fileContents);
      $this->copyMigrationFiles();

      $this->info('Migration published!');
      $this->call('dump-autoload');

    } catch ( FileExistsException $e ) {
      $this->error('The migration file '.$e->getMessage().' already exists. Remove the migration and try again.');
    } catch ( HeaderDoesNotExistException $e ) {
      $this->error('The header '.$e->getMessage().' does not exist in the source file.');
    }

  } /* function fire */



  /**
   * Copy migration files from the migrations directory
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function copyMigrationFiles() {

    // The wgs84distance migration
    $source = __DIR__.'/../../../../'.self::MIGRATION_FUNCTION_NAME.'.php';
    $targetPath = $this->getByOptionOrConfig('path', 'migrations_path');
    $target = $this->getMigrationFileName(self::MIGRATION_FUNCTION_NAME);

    $this->doNotOverwriteFile($targetPath, self::MIGRATION_FUNCTION_NAME);

    $this->file->copy($source, "{$targetPath}/{$target}");

  } /* function copyMigrationFiles */



  /**
   * Save the migration file
   *
   * @access protected
   * @param string $fileContents
   * @return void
   */
  protected function saveMigrationFile($fileContents) {

    $filePath = $this->getByOptionOrConfig('path', 'migrations_path');
    $fileName = $this->getMigrationFileName(self::MIGRATION_TABLE_NAME);

    $this->doNotOverwriteFile($filePath, self::MIGRATION_TABLE_NAME);

    $this->file->put("{$filePath}/{$fileName}", $fileContents);

  } /* function saveMigrationFile */



  /**
   * Do not overwrite a migration file
   *
   * @access protected

   * @param string $filePath
   * @param string $name
   * @return void
   */
  protected function doNotOverwriteFile($filePath, $name) {

    foreach ( $this->file->files($filePath) as $file )
      if ( strpos($file, $name) !== false )
        throw new FileExistsException($file);

  } /* function doNotOverwriteFile */



  /**
   * Create the filename for the migration
   *
   * @access protected
   * @param string $name
   * @return string
   */
  protected function getMigrationFileName($name) {

    return date('Y_m_d_His_').$name.'.php';

  } /* function getMigrationFileName */



  /**
   * Return the options
   *
   * @access protected
   * @param void
   * @return array
   */
  protected function getOptions() {

    return [
      [ 'path', 'p', InputOption::VALUE_OPTIONAL, 'Target path for migration.' ],
      [ 'columns', 'c', InputOption::VALUE_REQUIRED, 'Comma deliminated list of columns to include in the migration.' ],
    ];

  } /* function getOptions */



  /**
   * Return the arguments
   *
   * @access protected
   * @param void
   * @return array
   */
  protected function getArguments() {

    return [
      [ 'source', InputArgument::OPTIONAL, 'Full path to CSV file to create migration from.' ],
    ];

  } /* function getArguments */

} /* class MigrateCommand */

/* EOF */
