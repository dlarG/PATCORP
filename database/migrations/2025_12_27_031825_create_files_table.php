<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path', 500);
            $table->bigInteger('file_size');
            $table->string('file_type', 100)->nullable();
            $table->foreignId('category_id')->nullable()->constrained('file_categories')->onDelete('set null');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('driver_id')->nullable()->constrained('drivers')->onDelete('set null');
            $table->text('description')->nullable();
            $table->text('tags')->nullable();
            $table->integer('download_count')->default(0);
            $table->boolean('is_public')->default(false);
            $table->timestamp('last_accessed')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('files');
    }
};