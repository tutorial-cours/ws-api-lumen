<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_files', function (Blueprint $table) {
            $table->id();
            $table->timestamp('created_at');

            $table->foreignId('post_id')
                ->constrained('posts');

            $table->string('name')
                ->nullable()
                ->comment('Название файла');

            $table->string('key')
                ->comment('Ключ файла');

            $table->string('extension')
                ->comment('Расширение файла');

            $table->string('type')
                ->comment('Тип файла');

            $table->string('size')
                ->comment('Размер файла');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_files');
    }
}
