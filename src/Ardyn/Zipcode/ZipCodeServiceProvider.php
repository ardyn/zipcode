<?php

namespace Ardyn\Zipcode;

use SplFileObject;
use Illuminate\Support\ServiceProvider;
use Ardyn\Zipcode\Artisan\DatabaseSeeder;
use Ardyn\Zipcode\Artisan\MigrationBuilder;
use Ardyn\Zipcode\Artisan\MigrationPublisher;
use Ardyn\Zipcode\Artisan\Commands\SeedCommand;
use Ardyn\Zipcode\Artisan\Commands\MigrateCommand;

class ZipCodeServiceProvider extends ServiceProvider {

 /**
  * Indicates if loading of the provider is deferred.
  *
  * @var bool
  */
  protected $defer = true;



 /**
  * Boot the service provider.
  *
  * @access public
  * @param void
  * @return void
  */
  public function boot() {

    $this->app->bind(
      'Ardyn\Zipcode\Repositories\ZipCodeInterface',
      $this->app['config']->get('ardyn/zipcode::repository')
    );

    $this->app->bind(
      'Ardyn\Zipcode\Models\ZipCodeModelInterface',
      $this->app['config']->get('ardyn/zipcode::model')
    );

  } /* function boot */



 /**
  * Register the service provider.
  *
  * @access public
  * @param void
  * @return void
  */
  public function register() {

    $this->package('ardyn/zipcode', 'ardyn/zipcode');

    $this->registerModel();
    $this->registerRepository();
    $this->registerZipcode();

    $this->registerDatabaseSeeder();
    $this->registerMigrationBuilder();
    $this->registerMigrationPublisher();

    $this->registerMigrateCommand();
    $this->registerSeedCommand();

  } /* function register */



  /**
   * Register the database seeder
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function registerDatabaseSeeder() {

    $this->app->bindShared('zipcode.database-seeder', function ($app) {

      return new DatabaseSeeder(
        $app['config']
      );

    });

  } /* function registerDatabaseSeeder */



  /**
   * Register the migration builder
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function registerMigrationBuilder() {

    $this->app->bindShared('zipcode.migration-builder', function ($app) {

      return new MigrationBuilder(
        $app['config']
      );

    });

  } /* function registerMigrationBuilder */



  /**
   * Register the migrate command
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function registerMigrateCommand() {

    $this->app->bindShared('zipcode.migrate', function ($app) {

      return new MigrateCommand(
        $app['config'],
        $app['zipcode.migration-builder'],
        $app['zipcode.migration-publisher']
      );

    });

    $this->commands('zipcode.migrate');

  } /* function registerMigrateCommand */



  /**
   * Register the migration publisher
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function registerMigrationPublisher() {

    $this->app->bindShared('zipcode.migration-publisher', function ($app) {

      return new MigrationPublisher(
        $app['files']
      );

    });

  } /* function registerMigrationPublisher */



  /**
   * Register the Seed command
   *
   * @access protected
   * @param void
   * @return void
   */
  protected function registerSeedCommand() {

    $this->app->bindShared('zipcode.seed', function ($app) {

      return new SeedCommand(
        $app['config'],
        $app['zipcode.database-seeder']
      );

    });

    $this->commands('zipcode.seed');

  } /* function registerSeedCommand */



 /**
  * Register the model service provider.
  *
  * @access protected
  * @param void
  * @return void
  */
  protected function registerModel() {

    $this->app['zipcode.model'] = $this->app->share(function ($app) {

      $model = $app['config']->get('ardyn/zipcode::model');

      return new $model();

    });

  } /* function registerModel */



 /**
  * Register the repository service provider.
  *
  * @access protected
  * @param void
  * @return void
  */
  protected function registerRepository() {

    $this->app['zipcode.repository'] = $this->app->share(function ($app) {

      $repository = $app['config']->get('ardyn/zipcode::repository');

      return new $repository(
        $app['zipcode.model'],
        $app['config']
      );

    });

  } /* function registerRepository */



 /**
  * Register the zipcode service provider.
  *
  * @access protected
  * @param void
  * @return void
  */
  protected function registerZipcode() {

    $this->app['zipcode'] = $this->app->share(function ($app) {

      return new ZipCodeEngine(
        $app['zipcode.repository']
      );

    });

  } /* function registerZipcode */



 /**
  * Get the services provided by the provider.
  *
  * @return array
  */
  public function provides() {

    return[ 'zipcode' ];

  } /* function provides */

} /* class ZipCodeServiceProvider */

/* EOF */
