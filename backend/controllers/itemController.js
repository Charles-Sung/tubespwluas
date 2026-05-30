const { Item, Room, BhpStock, Inventory } = require('../models');

// Helper to map DB Item to Frontend Item compatibility
const mapItemCompat = async (itemInstance) => {
    if (!itemInstance) return null;
    const itemJson = itemInstance.toJSON();
    
    // Compatibility properties
    itemJson.item_name = itemJson.name;
    itemJson.category = itemJson.description || 'Elektronik';
    
    // Calculate stock and find room_id
    if (itemJson.type === 'bhp') {
        const bhpStock = await BhpStock.findOne({ where: { item_id: itemJson.id } });
        itemJson.stock = bhpStock ? bhpStock.total_quantity : 0;
        itemJson.room_id = 1; // default room for BHP
    } else {
        const inventories = await Inventory.findAll({ where: { item_id: itemJson.id } });
        itemJson.stock = inventories.length;
        itemJson.room_id = inventories.length > 0 ? inventories[0].room_id : 1;
    }
    
    // Fetch Room for includes
    const room = await Room.findByPk(itemJson.room_id);
    itemJson.room = room ? {
        id: room.id,
        room_name: room.name,
        location: room.description || 'Gedung Utama'
    } : null;
    
    return itemJson;
};

// GET all items
const getAllItems = async (req, res) => {
    try {
        const items = await Item.findAll();
        
        const mappedItems = [];
        for (const item of items) {
            mappedItems.push(await mapItemCompat(item));
        }
        
        return res.status(200).json(mappedItems);
    } catch (error) {
        console.error('Error fetching items:', error);
        return res.status(500).json({ message: 'Gagal mengambil data barang.' });
    }
};

// GET item by ID
const getItemById = async (req, res) => {
    try {
        const item = await Item.findByPk(req.params.id);
        if (!item) {
            return res.status(404).json({ message: 'Barang tidak ditemukan.' });
        }
        
        const mappedItem = await mapItemCompat(item);
        return res.status(200).json(mappedItem);
    } catch (error) {
        console.error('Error fetching item:', error);
        return res.status(500).json({ message: 'Gagal mengambil data barang.' });
    }
};

// POST create item
const createItem = async (req, res) => {
    try {
        const { item_name, category, stock, room_id } = req.body;

        if (!item_name || !category || stock === undefined || !room_id) {
            return res.status(400).json({ message: 'Semua field wajib diisi.' });
        }

        // Verify if room exists
        const room = await Room.findByPk(room_id);
        if (!room) {
            return res.status(400).json({ message: 'Ruangan yang dipilih tidak valid.' });
        }

        // Determine item type (bhp or inventory)
        const isBhp = category.toLowerCase().includes('bhp') || category.toLowerCase().includes('bahan');
        const type = isBhp ? 'bhp' : 'inventory';

        // Create Item
        const newItem = await Item.create({
            name: item_name,
            type,
            description: category
        });

        // Initialize Stocks or Inventories based on type
        if (type === 'bhp') {
            await BhpStock.create({
                item_id: newItem.id,
                total_quantity: parseInt(stock, 10)
            });
        } else {
            // Create rows in inventories table for each stock unit
            const count = parseInt(stock, 10);
            const labelPrefix = `INV-${item_name.substring(0, 3).toUpperCase()}`;
            
            for (let i = 1; i <= count; i++) {
                const randSuffix = Math.floor(100 + Math.random() * 900);
                await Inventory.create({
                    item_id: newItem.id,
                    room_id: parseInt(room_id, 10),
                    label_number: `${labelPrefix}-${randSuffix}`,
                    condition: 'good'
                });
            }
        }

        const mappedNewItem = await mapItemCompat(newItem);
        return res.status(201).json({
            message: 'Barang berhasil ditambahkan.',
            data: mappedNewItem
        });
    } catch (error) {
        console.error('Error creating item:', error);
        return res.status(500).json({ message: 'Gagal menambahkan barang.' });
    }
};

// PUT update item
const updateItem = async (req, res) => {
    try {
        const { item_name, category, stock, room_id } = req.body;
        const item = await Item.findByPk(req.params.id);

        if (!item) {
            return res.status(404).json({ message: 'Barang tidak ditemukan.' });
        }

        if (item_name) item.name = item_name;
        if (category) item.description = category;
        await item.save();

        // Update stocks if stock or room_id changed
        if (stock !== undefined || room_id) {
            if (item.type === 'bhp' && stock !== undefined) {
                const bhpStock = await BhpStock.findOne({ where: { item_id: item.id } });
                if (bhpStock) {
                    bhpStock.total_quantity = parseInt(stock, 10);
                    await bhpStock.save();
                } else {
                    await BhpStock.create({
                        item_id: item.id,
                        total_quantity: parseInt(stock, 10)
                    });
                }
            } else if (item.type === 'inventory') {
                // If room_id is changing, update all existing inventory locations
                if (room_id) {
                    await Inventory.update(
                        { room_id: parseInt(room_id, 10) },
                        { where: { item_id: item.id } }
                    );
                }
                
                // Adjust quantity
                if (stock !== undefined) {
                    const currentCount = await Inventory.count({ where: { item_id: item.id } });
                    const targetCount = parseInt(stock, 10);
                    
                    if (targetCount > currentCount) {
                        // Add more inventory items
                        const diff = targetCount - currentCount;
                        const labelPrefix = `INV-${item.name.substring(0, 3).toUpperCase()}`;
                        const finalRoomId = room_id ? parseInt(room_id, 10) : 1;
                        
                        for (let i = 0; i < diff; i++) {
                            const randSuffix = Math.floor(100 + Math.random() * 900);
                            await Inventory.create({
                                item_id: item.id,
                                room_id: finalRoomId,
                                label_number: `${labelPrefix}-${randSuffix}`,
                                condition: 'good'
                            });
                        }
                    } else if (targetCount < currentCount) {
                        // Delete excess inventory items
                        const diff = currentCount - targetCount;
                        const itemsToDelete = await Inventory.findAll({
                            where: { item_id: item.id },
                            limit: diff
                        });
                        for (const it of itemsToDelete) {
                            await it.destroy();
                        }
                    }
                }
            }
        }

        const mappedUpdatedItem = await mapItemCompat(item);
        return res.status(200).json({
            message: 'Barang berhasil diperbarui.',
            data: mappedUpdatedItem
        });
    } catch (error) {
        console.error('Error updating item:', error);
        return res.status(500).json({ message: 'Gagal memperbarui barang.' });
    }
};

// DELETE item
const deleteItem = async (req, res) => {
    try {
        const item = await Item.findByPk(req.params.id);
        if (!item) {
            return res.status(404).json({ message: 'Barang tidak ditemukan.' });
        }

        await item.destroy();
        return res.status(200).json({ message: 'Barang berhasil dihapus.' });
    } catch (error) {
        console.error('Error deleting item:', error);
        return res.status(500).json({ message: 'Gagal menghapus barang.' });
    }
};

module.exports = {
    getAllItems,
    getItemById,
    createItem,
    updateItem,
    deleteItem
};
