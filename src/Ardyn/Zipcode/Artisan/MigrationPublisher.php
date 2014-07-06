<?php

namespace Ardyn\Zipcode\Artisan;

use Illuminate\Filesystem\Filesystem as File;
use Ardyn\Zipcode\Artisan\Exceptions\FileExistsException;

class MigrationPublisher {

  /**
   * Migration file name
   *
   * @const string
   */
  const MIGRATION_TABLE_NAME = 'create_ardyn_zip_codes_table';

  /**
   * WGS84distance function migration name
   *
   * @const string
   */
  const MIGRATION_FUNCTION_NAME = 'add_wgs84distance_function';



  /**
   * Filesystem
   *
   * @var \Illuminate\Filesystem\Filesystem
   */
  protected $file;



  /**
   * Constructor
   *
   * @access public
   * @param \Illuminate\Filesystem\Filesystem $file
   * @return void
   */
  public function __construct(
    File $file
  ) {

    $this->file = $file;

  } /* function __construct */



  /**
   * Copy migration files from the migrations directory
   *
   * @access public
   * @param string $targetPath
   * @return void
   */
  public function publishAddFunctionMigration($targetPath) {

    // The wgs84distance migration
    $source = __DIR__.'/../../../'.self::MIGRATION_FUNCTION_NAME.'.php';
    $target = $this->getMigrationFileName(self::MIGRATION_FUNCTION_NAME);

    $this->doNotOverwriteFile($targetPath, self::MIGRATION_FUNCTION_NAME);

    $this->file->copy($source, "{$targetPath}/{$target}");

  } /* function publishAddFunctionMigration */



  /**
   * Save the create table migration file
   *
   * @access public
   * @param string $targetPath
   * @param string $fileContents
   * @return void
   */
  public function publishCreateTableMigration($targetPath, $fileContents) {

    $target = $this->getMigrationFileName(self::MIGRATION_TABLE_NAME);

    $this->doNotOverwriteFile($targetPath, self::MIGRATION_TABLE_NAME);

    $this->file->put("{$targetPath}/{$target}", $fileContents);

  } /* function publishCreateTableMigration */



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



} /* class MigrationPublisher */

/* EOF */
