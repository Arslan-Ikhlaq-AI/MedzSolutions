<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @var array<int, string>
     */
    private array $tables = ['contact_messages', 'consultation_requests', 'job_applications', 'affiliate_applications'];

    /**
     * @var array<int, string>
     */
    private array $followUpTables = ['contact_messages', 'consultation_requests'];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                $table->uuid('visitor_id')->nullable()->index()->after('id');

                if (in_array($name, $this->followUpTables, true)) {
                    $table->timestamp('follow_up_sent_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, function (Blueprint $table) use ($name) {
                $table->dropIndex(['visitor_id']);
                $table->dropColumn('visitor_id');

                if (in_array($name, $this->followUpTables, true)) {
                    $table->dropColumn('follow_up_sent_at');
                }
            });
        }
    }
};
