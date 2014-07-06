<?php

namespace Ardyn\Zipcode\Artisan\Commands;

use Illuminate\Console\Command;
use Illuminate\Config\Repository as Config;

abstract class ZipCodeCommand extends Command {

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

    parent::__construct();

    $this->config = $config;

  } /* function __construct */



  /**
   * Get a directory path either through a
   * command argument, or from the configuration
   *
   * @param $option
   * @param $configName
   * @return string
   */
  protected function getByArgumentOrConfig($option, $configName) {

    if ( $path = $this->argument($option) )
      return $path;

    return $this->config($configName);

  } /* function getByOptionOrConfig */



  /**
   * Get a directory path either through a
   * command option, or from the configuration
   *
   * @param $option
   * @param $configName
   * @return string
   */
  protected function getByOptionOrConfig($option, $configName) {

    if ( $path = $this->option($option) )
      return $path;

    return $this->config($configName);

  } /* function getByOptionOrConfig */



  /**
   * Returns the required columns for the migration
   *
   * @access public
   * @param string $optional Columns to include
   * @return array
   */
  public function getColumns($optional) {

    $headers = $optional ? explode(',', $optional) : array();

    $defaults = [
      $this->config('zip_code'),
      $this->config('latitude'),
      $this->config('longitude'),
    ];

    return array_merge($defaults, $headers);

  } /* function getColumns */



  /**
   * Return a value from the config file
   *
   * @access protected
   * @param string $key
   * @param strng $defalt
   * @return string
   */
  protected function config($key, $default=null) {

    return $this->config->get("ardyn/zipcode::{$key}", $default);

  } /* function config */



  /**
   * Format the error before throwing it
   *
   * @access public
   * @param string $message
   * @return void
   */
  public function error($message) {

    parent::error("\n  {$message}\n");

  } /* function error */

} /* class ZipCodeCommand */

/* EOF */
