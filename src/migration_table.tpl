<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArdynZipCodesTable extends Migration {

  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {

    Schema::create('#table#', function(Blueprint $table) {

      // Create the zip_codes table
#columns#
      $table->primary('#zip_code#');
      $table->index('#latitude#');
      $table->index('#longitude#');

    });

  } /* function up */



  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {

    // Drop the table
    Schema::drop('#table#');

  } /* function down */

} /* class CreateArdynZipCodesTable */

/* EOF */
