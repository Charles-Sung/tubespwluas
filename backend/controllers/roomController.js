const { Room } = require('../models');

// GET all rooms
const getAllRooms = async (req, res) => {
    try {
        const rooms = await Room.findAll();
        return res.status(200).json(rooms);
    } catch (error) {
        console.error('Error fetching rooms:', error);
        return res.status(500).json({ message: 'Gagal mengambil data ruangan.' });
    }
};

// GET room by ID
const getRoomById = async (req, res) => {
    try {
        const room = await Room.findByPk(req.params.id);
        if (!room) {
            return res.status(404).json({ message: 'Ruangan tidak ditemukan.' });
        }
        return res.status(200).json(room);
    } catch (error) {
        console.error('Error fetching room:', error);
        return res.status(500).json({ message: 'Gagal mengambil data ruangan.' });
    }
};

// POST create room
const createRoom = async (req, res) => {
    try {
        const { room_name, location, capacity } = req.body;

        if (!room_name || !location || capacity === undefined) {
            return res.status(400).json({ message: 'Semua field wajib diisi.' });
        }

        const newRoom = await Room.create({
            room_name,
            location,
            capacity
        });

        return res.status(201).json({
            message: 'Ruangan berhasil ditambahkan.',
            data: newRoom
        });
    } catch (error) {
        console.error('Error creating room:', error);
        return res.status(500).json({ message: 'Gagal menambahkan ruangan.' });
    }
};

// PUT update room
const updateRoom = async (req, res) => {
    try {
        const { room_name, location, capacity } = req.body;
        const room = await Room.findByPk(req.params.id);

        if (!room) {
            return res.status(404).json({ message: 'Ruangan tidak ditemukan.' });
        }

        if (room_name) room.room_name = room_name;
        if (location) room.location = location;
        if (capacity !== undefined) room.capacity = capacity;

        await room.save();

        return res.status(200).json({
            message: 'Ruangan berhasil diperbarui.',
            data: room
        });
    } catch (error) {
        console.error('Error updating room:', error);
        return res.status(500).json({ message: 'Gagal memperbarui ruangan.' });
    }
};

// DELETE room
const deleteRoom = async (req, res) => {
    try {
        const room = await Room.findByPk(req.params.id);
        if (!room) {
            return res.status(404).json({ message: 'Ruangan tidak ditemukan.' });
        }

        await room.destroy();
        return res.status(200).json({ message: 'Ruangan berhasil dihapus.' });
    } catch (error) {
        console.error('Error deleting room:', error);
        return res.status(500).json({ message: 'Gagal menghapus ruangan.' });
    }
};

module.exports = {
    getAllRooms,
    getRoomById,
    createRoom,
    updateRoom,
    deleteRoom
};
