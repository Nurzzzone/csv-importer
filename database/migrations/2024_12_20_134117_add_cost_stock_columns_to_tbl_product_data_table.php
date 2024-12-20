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
        Schema::table('tblProductData', function (Blueprint $table) {
            $table->decimal('decCost')->after('dtmDiscontinued');
            $table->integer('intStock')->default(0)->after('decCost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tblProductData', function (Blueprint $table) {
            $table->dropColumn('desCost');
            $table->dropColumn('intStock');
        });
    }
};