const { Item, Room } = require('../models');

// GET all items
const getAllItems = async (req, res) => {
    try {
        const items = await Item.findAll({
            include: [{
                model: Room,
                as: 'room',
                attributes: ['id', 'room_name', 'location']
            }]
        });
        return res.status(200).json(items);
    } catch (error) {
        console.error('Error fetching items:', error);
        return res.status(500).json({ message: 'Gagal mengambil data barang.' });
    }
};

// GET item by ID
const getItemById = async (req, res) => {
    try {
        const item = await Item.findByPk(req.params.id, {
            include: [{
                model: Room,
                as: 'room',
                attributes: ['id', 'room_name', 'location']
            }]
        });
        if (!item) {
            return res.status(404).json({ message: 'Barang tidak ditemukan.' });
        }
        return res.status(200).json(item);
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

        const newItem = await Item.create({
            item_name,
            category,
            stock,
            room_id
        });

        return res.status(201).json({
            message: 'Barang berhasil ditambahkan.',
            data: newItem
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

        if (room_id) {
            // Verify if room exists
            const room = await Room.findByPk(room_id);
            if (!room) {
                return res.status(400).json({ message: 'Ruangan yang dipilih tidak valid.' });
            }
            item.room_id = room_id;
        }

        if (item_name) item.item_name = item_name;
        if (category) item.category = category;
        if (stock !== undefined) item.stock = stock;

        await item.save();

        return res.status(200).json({
            message: 'Barang berhasil diperbarui.',
            data: item
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
