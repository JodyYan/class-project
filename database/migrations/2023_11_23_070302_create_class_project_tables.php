<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('consultants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('sex', ['male', 'female']);
            $table->string('nationality');
            $table->text('introduction');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('enabled')->default(true)->comment('是否在職 0:否 | 1: 是');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['email', 'name'], 'mix_index_email_name'); //老師的資料常會被學生或者企業本身後台搜尋

        });

        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('sex', ['male', 'female']);
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->index(['email', 'name'], 'mix_index_email_name'); //學生的資料常會被企業本身後台搜尋

        });

        Schema::create('class_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->text('introduction');
            $table->integer('consultant_id')->unsigned();
            $table->integer('class_type_id')->unsigned();
            $table->dateTime('start_date_time')->default(date("Y-m-d H:i:s"))->comment('課程開始時間');
            $table->dateTime('end_date_time')->default(date("Y-m-d H:i:s"))->comment('課程結束時間');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('class_type_id')->references('id')->on('class_types');
            $table->foreign('consultant_id')->references('id')->on('consultants');
            $table->index('class_type_id','classes_class_type_id_foreign'); //課程會隨時間越來越多，建立索引降低搜尋時間
        });

        Schema::create('student_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->unsigned();
            $table->integer('class_id')->unsigned();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('class_id')->references('id')->on('classes');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_classes', function (Blueprint $table) {
            $table->dropForeign('student_classes_class_id_foreign');
            $table->dropForeign('student_classes_student_id_foreign');
        });
        Schema::dropIfExists('student_classes');
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign('classes_class_type_id_foreign');
            $table->dropForeign('classes_consultant_id_foreign');
            $table->dropIndex('classes_class_type_id_foreign');
        });
        Schema::dropIfExists('classes');
        Schema::table('consultants', function (Blueprint $table) {
            $table->dropIndex('mix_index_email_name');
        });
        Schema::dropIfExists('consultants');

        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('mix_index_email_name');
        });
        Schema::dropIfExists('students');
        Schema::dropIfExists('class_types');
        
        
    }
};