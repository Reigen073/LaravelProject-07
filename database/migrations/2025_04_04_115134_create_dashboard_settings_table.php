<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDashboardSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('dashboard_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('show_ads')->default(true);
            $table->boolean('show_favorites')->default(true);
            $table->boolean('show_intro')->default(true);
            $table->boolean('show_image')->default(true);
            $table->boolean('show_custom_link')->default(true);
            $table->boolean('show_contracts')->default(true);
            $table->string('bg_color', 7)->default('#ffffff');
            $table->string('text_color', 7)->default('#000000');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dashboard_settings');
    }
};

