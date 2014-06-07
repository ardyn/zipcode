<?php

class ZipCodeDatabaseSeeder extends Seeder {

 /**
  * Run the seeder
  *
  * @access public
  * @param void
  * @return void
  */
  public function run() {

    // Configuration
    $sql = file_get_contents(__DIR__.'/seeds.sql');

    // Delete any rows
    $this->command->info('Deleting table...');
    DB::table('zip_codes')->delete();

    // Seed the database
    $this->command->info('Seeding table...');
    DB::unprepared($sql);
    $this->command->info('Table seeded.');

  } /* function run */

} /* class ZipCodeDatabaseSeeder */

/* EOF */
