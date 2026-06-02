const { Item, BhpStock } = require('../models');

// GET all BHP items with their stocks
const getAllBhp = async (req, res) => {
    try {
        const bhpItems = await Item.findAll({
            where: { type: 'bhp' },
            include: [{
                model: BhpStock,
                as: 'bhp_stock'
            }]
        });

        const formatted = bhpItems.map(item => ({
            id: item.id,
            name: item.name,
            description: item.description,
            stock: item.bhp_stock ? item.bhp_stock.total_quantity : 0
        }));

        return res.status(200).json(formatted);
    } catch (error) {
        console.error('Error fetching BHP stock:', error);
        return res.status(500).json({ message: 'Gagal mengambil data stok BHP.' });
    }
};

// PUT update BHP stock directly
const updateBhpStock = async (req, res) => {
    try {
        const { item_id, quantity } = req.body;

        if (item_id === undefined || quantity === undefined) {
            return res.status(400).json({ message: 'Item ID dan kuantitas wajib diisi.' });
        }

        const item = await Item.findOne({ where: { id: item_id, type: 'bhp' } });
        if (!item) {
            return res.status(404).json({ message: 'Barang BHP tidak ditemukan.' });
        }

        let stock = await BhpStock.findOne({ where: { item_id } });
        if (stock) {
            stock.total_quantity = parseInt(quantity, 10);
            await stock.save();
        } else {
            stock = await BhpStock.create({
                item_id,
                total_quantity: parseInt(quantity, 10)
            });
        }

        return res.status(200).json({
            message: 'Stok BHP berhasil diperbarui.',
            data: {
                id: item.id,
                name: item.name,
                stock: stock.total_quantity
            }
        });
    } catch (error) {
        console.error('Error updating BHP stock:', error);
        return res.status(500).json({ message: 'Gagal memperbarui stok BHP.' });
    }
};

module.exports = {
    getAllBhp,
    updateBhpStock
};
