<?php

namespace Ardan\Zipcode;

use SplFileObject;
use Illuminate\Support\ServiceProvider;
use Ardan\Zipcode\Artisan\DatabaseSeeder;
use Ardan\Zipcode\Artisan\MigrationBuilder;
use Ardan\Zipcode\Artisan\Commands\SeedCommand;
use Ardan\Zipcode\Artisan\Commands\MigrateCommand;

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
      'Ardan\Zipcode\Repositories\ZipCodeInterface',
      $this->app['config']->get('ardan/zipcode::repository')
    );

    $this->app->bind(
      'Ardan\Zipcode\Models\ZipCodeModelInterface',
      $this->app['config']->get('ardan/zipcode::model')
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

    $this->package('ardan/zipcode', 'ardan/zipcode');

    $this->registerModel();
    $this->registerRepository();
    $this->registerZipcode();

    $this->registerDatabaseSeeder();
    $this->registerMigrationBuilder();

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
        $app['files'],
        $app['zipcode.migration-builder']
      );

    });

    $this->commands('zipcode.migrate');

  } /* function registerMigrateCommand */



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
        $app['files'],
        $app['zipcode.database-seeder']
      );

    });

    $this->commands('zipcode.seed');

  } /* function registerSeedCommand */



 /**
  * Register the model service provider.
  *
  * @access private
  * @param void
  * @return void
  */
  private function registerModel() {

    $this->app['zipcode.model'] = $this->app->share(function ($app) {

      $model = $app['config']->get('ardan/zipcode::model');
      return new $model();

    });

  } /* function registerModel */



 /**
  * Register the repository service provider.
  *
  * @access private
  * @param void
  * @return void
  */
  private function registerRepository() {

    $this->app['zipcode.repository'] = $this->app->share(function ($app) {

      $repository = $app['config']->get('ardan/zipcode::repository');

      return new $repository(
        $app['zipcode.model'],
        $app['config']
      );

    });

  } /* function registerRepository */



 /**
  * Register the zipcode service provider.
  *
  * @access private
  * @param void
  * @return void
  */
  private function registerZipcode() {

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

    return array('zipcode');

  } /* function provides */

} /* class ZipCodeServiceProvider */

/* EOF */
