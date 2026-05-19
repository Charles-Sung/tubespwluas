<?php

$dir = __DIR__ . '/database/migrations/';

function overwrite_migration($dir, $pattern, $up_content, $down_table) {
    $files = glob($dir . '*' . $pattern);
    if (!empty($files)) {
        $file = $files[0];
        $content = <<<PHP
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
        $up_content
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('$down_table');
    }
};
PHP;
        file_put_contents($file, $content);
        echo "Updated $file\n";
    }
}

overwrite_migration($dir, 'create_roles_table.php', "Schema::create('roles', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->timestamps();
        });", 'roles');

overwrite_migration($dir, 'create_rooms_table.php', "Schema::create('rooms', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->timestamps();
        });", 'rooms');

overwrite_migration($dir, 'create_items_table.php', "Schema::create('items', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->enum('type', ['inventory', 'bhp']);
            \$table->text('description')->nullable();
            \$table->timestamps();
        });", 'items');

overwrite_migration($dir, 'create_procurement_drafts_table.php', "Schema::create('procurement_drafts', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users'); // Kepala Lab
            \$table->string('title');
            \$table->year('year');
            \$table->enum('status', ['draft', 'submitted', 'reviewed', 'finalized'])->default('draft');
            \$table->timestamps();
        });", 'procurement_drafts');

overwrite_migration($dir, 'create_procurement_details_table.php', "Schema::create('procurement_details', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('procurement_draft_id')->constrained('procurement_drafts')->cascadeOnDelete();
            \$table->foreignId('item_id')->constrained('items');
            \$table->integer('quantity');
            \$table->decimal('price', 15, 2);
            \$table->string('purchase_link')->nullable();
            \$table->unsignedBigInteger('replaced_inventory_id')->nullable();
            \$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            \$table->timestamps();
        });", 'procurement_details');

overwrite_migration($dir, 'create_inventories_table.php', "Schema::create('inventories', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items');
            \$table->foreignId('room_id')->constrained('rooms');
            \$table->string('label_number')->unique();
            \$table->string('qr_path')->nullable();
            \$table->enum('condition', ['good', 'maintenance', 'broken', 'replaced'])->default('good');
            \$table->timestamps();
        });", 'inventories');

overwrite_migration($dir, 'create_item_receipts_table.php', "Schema::create('item_receipts', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('procurement_detail_id')->constrained('procurement_details');
            \$table->integer('quantity_received');
            \$table->date('receipt_date');
            \$table->foreignId('user_id')->constrained('users'); // Staf Admin
            \$table->text('notes')->nullable();
            \$table->timestamps();
        });", 'item_receipts');

overwrite_migration($dir, 'create_bhp_stocks_table.php', "Schema::create('bhp_stocks', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            \$table->integer('total_quantity')->default(0);
            \$table->timestamps();
        });", 'bhp_stocks');

overwrite_migration($dir, 'create_bhp_transactions_table.php', "Schema::create('bhp_transactions', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items');
            \$table->enum('type', ['in', 'out']);
            \$table->integer('quantity');
            \$table->date('date');
            \$table->string('description')->nullable();
            \$table->timestamps();
        });", 'bhp_transactions');

overwrite_migration($dir, 'create_maintenance_logs_table.php', "Schema::create('maintenance_logs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('inventory_id')->constrained('inventories');
            \$table->foreignId('user_id')->constrained('users'); // Staf Lab
            \$table->date('maintenance_date');
            \$table->text('description');
            \$table->timestamps();
        });", 'maintenance_logs');

overwrite_migration($dir, 'create_maintenance_bhp_usages_table.php', "Schema::create('maintenance_bhp_usages', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('maintenance_log_id')->constrained('maintenance_logs')->cascadeOnDelete();
            \$table->foreignId('item_id')->constrained('items');
            \$table->integer('quantity');
            \$table->timestamps();
        });", 'maintenance_bhp_usages');

echo "Setup done.\n";
