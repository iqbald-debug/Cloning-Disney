
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->unsignedBigInteger('tmdb_id')->nullable();
            $table->string('title');
            $table->string('slug')->unique();

            $table->enum('type', ['movie', 'series']);

            $table->text('synopsis')->nullable();
            $table->string('backdrop_url')->nullable();
            $table->string('title_logo_url')->nullable();
            $table->string('trailer_url')->nullable();

            $table->unsignedSmallInteger('release_year')->nullable();
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('total_seasons')->nullable();

            $table->string('age_rating')->nullable();
            $table->string('video_quality')->nullable();

            $table->boolean('is_featured')->default(false);
            $table->string('category_row')->nullable();

            $table->timestamps();

            $table->index(['brand_id', 'type']);
            $table->index(['category_row', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
