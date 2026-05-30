const { Room } = require('../models');

// Helper to map DB Room (name, description) to Frontend Room compatibility (room_name, location, capacity)
const mapRoomCompat = (roomInstance) => {
    if (!roomInstance) return null;
    const roomJson = roomInstance.toJSON();
    
    // Add compatibility properties
    roomJson.room_name = roomJson.name;
    roomJson.location = roomJson.description || 'Gedung Utama';
    roomJson.capacity = 30; // default sample capacity
    
    return roomJson;
};

// GET all rooms
const getAllRooms = async (req, res) => {
    try {
        const rooms = await Room.findAll();
        const mappedRooms = rooms.map(room => mapRoomCompat(room));
        return res.status(200).json(mappedRooms);
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
        return res.status(200).json(mapRoomCompat(room));
    } catch (error) {
        console.error('Error fetching room:', error);
        return res.status(500).json({ message: 'Gagal mengambil data ruangan.' });
    }
};

// POST create room
const createRoom = async (req, res) => {
    try {
        const { room_name, location, capacity } = req.body;

        if (!room_name || !location) {
            return res.status(400).json({ message: 'Semua field wajib diisi.' });
        }

        // Map frontend values to database columns
        const newRoom = await Room.create({
            name: room_name,
            description: location
        });

        return res.status(201).json({
            message: 'Ruangan berhasil ditambahkan.',
            data: mapRoomCompat(newRoom)
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

        if (room_name) room.name = room_name;
        if (location) room.description = location;

        await room.save();

        return res.status(200).json({
            message: 'Ruangan berhasil diperbarui.',
            data: mapRoomCompat(room)
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
