<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWgs84distanceFunction extends Migration {

 /**
  * Run the migrations.
  *
  * @return void
  */
  public function up() {

    $this->down();

    $sql = <<<SQL
CREATE FUNCTION WGS84distance( lat1 DOUBLE, lon1 DOUBLE, lat2 DOUBLE, lon2 DOUBLE )
RETURNS DOUBLE
RETURN ACOS(SIN(RADIANS(lat1)) * SIN(RADIANS(lat2)) + COS(RADIANS(lat1)) * COS(RADIANS(lat2)) * COS(RADIANS(lon2-lon1)));
SQL;

    DB::unprepared($sql);

  } /* function up */



 /**
  * Reverse the migrations.
  *
  * @return void
  */
  public function down() {

    // Drop our function
    $sql = 'DROP FUNCTION IF EXISTS WGS84distance';

    DB::unprepared($sql);

  } /* function down */

} /* class AddWgs84distanceFunction */

/* EOF */
