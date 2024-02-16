<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_authors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('author_id');

            $table->timestamps();

            $table->index('course_id','course_author_course_idx');
            $table->index('author_id','course_author_author_idx');

            $table->foreign('course_id', 'course_author_course_fk')->on('courses')->references('id');
            $table->foreign('author_id', 'course_author_author_fk')->on('authors')->references('id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_authors');
    }
}
