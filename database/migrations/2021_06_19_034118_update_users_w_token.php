<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersWToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'users',
            function ($table) {
                $table->string('api_token', 80)->after('password')
                    ->unique()
                    ->nullable()
                    ->default(null);
                $table->dateTime('token_expiration', 0)->nullable()
                    ->default(null);
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'users',
            function ($table) {
                $table->dropColumn('api_token');
                $table->dropColumn('token_expiration');
            }
        );
    }
}
