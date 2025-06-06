<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBulkUploadEmailFileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->string('list_id')->nullable()->after('fileName');
            $table->string('tool_name')->nullable()->after('list_id');
            $table->unsignedBigInteger('integeration_id')->nullable()->after('tool_name');
            $table->string('mc_user_id')->nullable()->after('tool_name');
            $table->string('mc_token')->nullable()->after('mc_user_id');
            $table->string('mc_dc')->nullable()->after('mc_token');
            $table->enum('is_tools_integerate_email', [0, 1])->default(0)->after('mc_dc');
            $table->json('unsubscribe_results')->nullable()->after('update_at');
        });

        Schema::table('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->enum('is_tools_integerate_email', [0, 1])->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('uploaded_and_download_file_names', function (Blueprint $table) {
            $table->dropColumn(['list_id', 'tool_name', 'mc_user_id', 'mc_token', 'is_tools_integerate_email']);
        });
 
        Schema::table('bulk_upload_email_file_data', function (Blueprint $table) {
            $table->dropColumn(['is_tools_integerate_email']);
        });
    }
}