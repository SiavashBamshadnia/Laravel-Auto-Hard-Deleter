<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleModelsTestTable extends Migration
{
    public function up()
    {
        Schema::create('sample_models', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('foo');
            $table->string('bar');
            $table->softDeletes('del');
        });
    }
}