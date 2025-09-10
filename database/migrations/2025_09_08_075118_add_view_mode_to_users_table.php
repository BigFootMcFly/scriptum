<?php

use App\Enums\FrontPageViewingMode;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.s
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('viewing_mode',array_keys(FrontPageViewingMode::userSelectable()))
                ->default(FrontPageViewingMode::Private->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('viewing_mode');
        });
    }
};
