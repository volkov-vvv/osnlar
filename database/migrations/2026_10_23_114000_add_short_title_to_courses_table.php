<?php

use App\Models\Course;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortTitleToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('short_title')->nullable()->after('title');
        });

        Course::query()
            ->withTrashed()
            ->orderBy('id')
            ->each(function (Course $course) {
                $course->timestamps = false;
                $course->short_title = Course::makeShortTitle((string) $course->title, $course->years);
                $course->saveQuietly();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('short_title');
        });
    }
}
