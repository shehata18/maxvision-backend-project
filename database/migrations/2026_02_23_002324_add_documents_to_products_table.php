<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds file upload columns to the products table:
     *   - specs_pdf       → used for the "Download Specs" button (hero area)
     *   - datasheet_pdf   → used for "Download Full Datasheet (PDF)" button in Tech Specs tab
     *   - cad_drawings    → used for "CAD Drawings" button in Tech Specs tab
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Quick-spec sheet shown in the product hero area
            $table->string('specs_pdf', 500)->nullable()->after('gallery');
            // Full technical datasheet PDF
            $table->string('datasheet_pdf', 500)->nullable()->after('specs_pdf');
            // CAD drawings file (PDF / DWG / ZIP)
            $table->string('cad_drawings', 500)->nullable()->after('datasheet_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['specs_pdf', 'datasheet_pdf', 'cad_drawings']);
        });
    }
};
