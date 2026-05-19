<?php

$dir = __DIR__ . '/database/migrations/';

function update_migration($dir, $pattern, $content) {
    $files = glob($dir . '*' . $pattern);
    if (!empty($files)) {
        $file = $files[0];
        $original = file_get_contents($file);
        $new_content = preg_replace('/public function up\(\): void.*?}/s', "public function up(): void\n    {\n        $content\n    }", $original);
        file_put_contents($file, $new_content);
        echo "Updated $file\n";
    }
}

// 1. Roles
update_migration($dir, 'create_roles_table.php', <<<EOT
Schema::create('roles', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->timestamps();
        });
EOT
);

// 2. Users (add role_id)
update_migration($dir, 'create_users_table.php', <<<EOT
Schema::create('users', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamp('email_verified_at')->nullable();
            \$table->string('password');
            \$table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete();
            \$table->rememberToken();
            \$table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint \$table) {
            \$table->string('email')->primary();
            \$table->string('token');
            \$table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint \$table) {
            \$table->string('id')->primary();
            \$table->foreignId('user_id')->nullable()->index();
            \$table->string('ip_address', 45)->nullable();
            \$table->text('user_agent')->nullable();
            \$table->longText('payload');
            \$table->integer('last_activity')->index();
        });
EOT
);

// 3. Rooms
update_migration($dir, 'create_rooms_table.php', <<<EOT
Schema::create('rooms', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
EOT
);

// 4. Items
update_migration($dir, 'create_items_table.php', <<<EOT
Schema::create('items', function (Blueprint \$table) {
            \$table->id();
            \$table->string('name');
            \$table->enum('type', ['inventory', 'bhp']);
            \$table->text('description')->nullable();
            \$table->timestamps();
        });
EOT
);

// 5. Procurement Drafts
update_migration($dir, 'create_procurement_drafts_table.php', <<<EOT
Schema::create('procurement_drafts', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained('users'); // Kepala Lab
            \$table->string('title');
            \$table->year('year');
            \$table->enum('status', ['draft', 'submitted', 'reviewed', 'finalized'])->default('draft');
            \$table->timestamps();
        });
EOT
);

// 6. Procurement Details
update_migration($dir, 'create_procurement_details_table.php', <<<EOT
Schema::create('procurement_details', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('procurement_draft_id')->constrained('procurement_drafts')->cascadeOnDelete();
            \$table->foreignId('item_id')->constrained('items');
            \$table->integer('quantity');
            \$table->decimal('price', 15, 2);
            \$table->string('purchase_link')->nullable();
            \$table->unsignedBigInteger('replaced_inventory_id')->nullable();
            \$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            \$table->timestamps();
        });
EOT
);

// 7. Inventories
update_migration($dir, 'create_inventories_table.php', <<<EOT
Schema::create('inventories', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items');
            \$table->foreignId('room_id')->constrained('rooms');
            \$table->string('label_number')->unique();
            \$table->string('qr_path')->nullable();
            \$table->enum('condition', ['good', 'maintenance', 'broken', 'replaced'])->default('good');
            \$table->timestamps();
        });
EOT
);

// 8. Item Receipts
update_migration($dir, 'create_item_receipts_table.php', <<<EOT
Schema::create('item_receipts', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('procurement_detail_id')->constrained('procurement_details');
            \$table->integer('quantity_received');
            \$table->date('receipt_date');
            \$table->foreignId('user_id')->constrained('users'); // Staf Admin
            \$table->text('notes')->nullable();
            \$table->timestamps();
        });
EOT
);

// 9. BHP Stocks
update_migration($dir, 'create_bhp_stocks_table.php', <<<EOT
Schema::create('bhp_stocks', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items')->cascadeOnDelete();
            \$table->integer('total_quantity')->default(0);
            \$table->timestamps();
        });
EOT
);

// 10. BHP Transactions
update_migration($dir, 'create_bhp_transactions_table.php', <<<EOT
Schema::create('bhp_transactions', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('item_id')->constrained('items');
            \$table->enum('type', ['in', 'out']);
            \$table->integer('quantity');
            \$table->date('date');
            \$table->string('description')->nullable();
            \$table->timestamps();
        });
EOT
);

// 11. Maintenance Logs
update_migration($dir, 'create_maintenance_logs_table.php', <<<EOT
Schema::create('maintenance_logs', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('inventory_id')->constrained('inventories');
            \$table->foreignId('user_id')->constrained('users'); // Staf Lab
            \$table->date('maintenance_date');
            \$table->text('description');
            \$table->timestamps();
        });
EOT
);

// 12. Maintenance BHP Usages
update_migration($dir, 'create_maintenance_bhp_usages_table.php', <<<EOT
Schema::create('maintenance_bhp_usages', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('maintenance_log_id')->constrained('maintenance_logs')->cascadeOnDelete();
            \$table->foreignId('item_id')->constrained('items');
            \$table->integer('quantity');
            \$table->timestamps();
        });
EOT
);
