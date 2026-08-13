<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->string('title_gu')->nullable()->after('title');
            $table->text('description_gu')->nullable()->after('description');
            $table->longText('content_gu')->nullable()->after('content');
            $table->string('title_en')->nullable()->after('title_gu');
            $table->text('description_en')->nullable()->after('description_gu');
            $table->longText('content_en')->nullable()->after('content_gu');
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropColumn(['title_gu', 'description_gu', 'content_gu', 'title_en', 'description_en', 'content_en']);
        });
    }
};
