const { Inventory, Item, Room } = require('../models');

// GET all inventories
const getAllInventories = async (req, res) => {
    try {
        const inventories = await Inventory.findAll({
            include: [
                {
                    model: Item,
                    as: 'item',
                    attributes: ['id', 'name', 'type']
                },
                {
                    model: Room,
                    as: 'room',
                    attributes: ['id', 'name']
                }
            ]
        });
        return res.status(200).json(inventories);
    } catch (error) {
        console.error('Error fetching inventories:', error);
        return res.status(500).json({ message: 'Gagal mengambil data inventaris.' });
    }
};

module.exports = {
    getAllInventories
};
