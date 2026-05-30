const { User, Room, Item, BhpStock, Inventory } = require('../models');

const getDashboardStats = async (req, res) => {
    try {
        const usersCount = await User.count();
        const roomsCount = await Room.count();
        const itemsCount = await Item.count();
        
        // Sum of BHP stocks total_quantity
        const bhpQty = await BhpStock.sum('total_quantity') || 0;
        // Count of individual Inventory assets
        const inventoryCount = await Inventory.count() || 0;
        
        const totalStock = bhpQty + inventoryCount;

        res.json({
            users_count: usersCount,
            rooms_count: roomsCount,
            items_count: itemsCount,
            total_stock: totalStock
        });
    } catch (error) {
        console.error('Error fetching dashboard stats:', error);
        res.status(500).json({ message: 'Gagal mengambil statistik dashboard.' });
    }
};

module.exports = {
    getDashboardStats
};
