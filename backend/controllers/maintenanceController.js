const { sequelize, MaintenanceLog, MaintenanceBhp, Inventory, Item, BhpStock, User } = require('../models');

// GET all maintenance logs
const getAllMaintenance = async (req, res) => {
    try {
        const logs = await MaintenanceLog.findAll({
            include: [
                {
                    model: Inventory,
                    as: 'inventory',
                    include: [{ model: Item, as: 'item', attributes: ['id', 'name'] }]
                },
                {
                    model: User,
                    as: 'user',
                    attributes: ['id', 'name', 'email']
                },
                {
                    model: MaintenanceBhp,
                    as: 'maintenance_bhps',
                    include: [{ model: Item, as: 'item', attributes: ['id', 'name'] }]
                }
            ],
            order: [['maintenance_date', 'DESC'], ['id', 'DESC']]
        });
        return res.status(200).json(logs);
    } catch (error) {
        console.error('Error fetching maintenance logs:', error);
        return res.status(500).json({ message: 'Gagal mengambil data log maintenance.' });
    }
};

// POST log new maintenance and update inventory/BHP stock (Transaction)
const createMaintenance = async (req, res) => {
    const t = await sequelize.transaction();
    try {
        const { inventory_id, description, new_condition, maintenance_date, bhps_used } = req.body;
        const user_id = req.user.id; // From JWT authentication middleware

        if (!inventory_id || !description || !new_condition || !maintenance_date) {
            return res.status(400).json({ message: 'Inventory ID, deskripsi, kondisi baru, dan tanggal wajib diisi.' });
        }

        // 1. Check if inventory item exists
        const inventory = await Inventory.findByPk(inventory_id, { transaction: t });
        if (!inventory) {
            await t.rollback();
            return res.status(404).json({ message: 'Barang inventaris tidak ditemukan.' });
        }

        const previous_condition = inventory.condition;

        // 2. Update Inventory Condition
        inventory.condition = new_condition;
        await inventory.save({ transaction: t });

        // 3. Create Maintenance Log entry
        const log = await MaintenanceLog.create({
            inventory_id,
            user_id,
            maintenance_date,
            description,
            previous_condition,
            new_condition
        }, { transaction: t });

        // 4. Process BHP if used
        if (bhps_used && Array.isArray(bhps_used) && bhps_used.length > 0) {
            for (const bhp of bhps_used) {
                const { item_id, quantity } = bhp;
                const qty = parseInt(quantity, 10);

                if (!item_id || isNaN(qty) || qty <= 0) {
                    continue; // Skip invalid items
                }

                // Verify item is a BHP
                const item = await Item.findOne({ where: { id: item_id, type: 'bhp' }, transaction: t });
                if (!item) {
                    await t.rollback();
                    return res.status(400).json({ message: `Barang dengan ID ${item_id} bukan bertipe BHP.` });
                }

                // Check stock
                const stock = await BhpStock.findOne({ where: { item_id }, transaction: t });
                if (!stock || stock.total_quantity < qty) {
                    await t.rollback();
                    return res.status(400).json({ message: `Stok untuk BHP '${item.name}' tidak mencukupi (Tersedia: ${stock ? stock.total_quantity : 0}, Dibutuhkan: ${qty}).` });
                }

                // Decrement stock
                stock.total_quantity -= qty;
                await stock.save({ transaction: t });

                // Create MaintenanceBhp log
                await MaintenanceBhp.create({
                    maintenance_log_id: log.id,
                    item_id,
                    quantity: qty
                }, { transaction: t });
            }
        }

        await t.commit();

        return res.status(201).json({
            message: 'Log maintenance berhasil dicatat dan kondisi barang diperbarui.',
            data: log
        });

    } catch (error) {
        await t.rollback();
        console.error('Error creating maintenance log:', error);
        return res.status(500).json({ message: 'Gagal mencatat log maintenance.' });
    }
};

module.exports = {
    getAllMaintenance,
    createMaintenance
};
