const bcrypt = require('bcryptjs');
const { User, Role } = require('../models');

// Helper to map role_id to string role for Frontend compatibility
const mapUserRole = (userInstance) => {
    if (!userInstance) return null;
    const userJson = userInstance.toJSON();
    // Map role_id 1 to 'admin', and anything else to 'user'
    userJson.role = (userJson.role_id === 1) ? 'admin' : 'user';
    return userJson;
};

// GET all users
const getAllUsers = async (req, res) => {
    try {
        const users = await User.findAll({
            attributes: { exclude: ['password'] },
            include: [{
                model: Role,
                as: 'role',
                attributes: ['name']
            }]
        });
        
        // Map users to compatibility format
        const mappedUsers = users.map(user => mapUserRole(user));
        return res.status(200).json(mappedUsers);
    } catch (error) {
        console.error('Error fetching users:', error);
        return res.status(500).json({ message: 'Gagal mengambil data user.' });
    }
};

// GET user by ID
const getUserById = async (req, res) => {
    try {
        const user = await User.findByPk(req.params.id, {
            attributes: { exclude: ['password'] },
            include: [{
                model: Role,
                as: 'role',
                attributes: ['name']
            }]
        });
        if (!user) {
            return res.status(404).json({ message: 'User tidak ditemukan.' });
        }
        return res.status(200).json(mapUserRole(user));
    } catch (error) {
        console.error('Error fetching user:', error);
        return res.status(500).json({ message: 'Gagal mengambil data user.' });
    }
};

// POST create user
const createUser = async (req, res) => {
    try {
        const { name, email, password, role } = req.body;

        if (!name || !email || !password || !role) {
            return res.status(400).json({ message: 'Semua field wajib diisi.' });
        }

        // Check if email already exists
        const existingUser = await User.findOne({ where: { email } });
        if (existingUser) {
            return res.status(400).json({ message: 'Email sudah terdaftar.' });
        }

        // Map role string to role_id
        const role_id = (role === 'admin') ? 1 : 5;

        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = await User.create({
            name,
            email,
            password: hashedPassword,
            role_id
        });

        // Fetch new user to return with mapped role
        const createdUser = await User.findByPk(newUser.id, {
            attributes: { exclude: ['password'] }
        });

        return res.status(201).json({
            message: 'User berhasil ditambahkan.',
            data: mapUserRole(createdUser)
        });
    } catch (error) {
        console.error('Error creating user:', error);
        return res.status(500).json({ message: 'Gagal menambahkan user.' });
    }
};

// PUT update user
const updateUser = async (req, res) => {
    try {
        const { name, email, password, role } = req.body;
        const user = await User.findByPk(req.params.id);

        if (!user) {
            return res.status(404).json({ message: 'User tidak ditemukan.' });
        }

        // Check if email is being changed and if it conflicts
        if (email && email !== user.email) {
            const emailExists = await User.findOne({ where: { email } });
            if (emailExists) {
                return res.status(400).json({ message: 'Email sudah digunakan oleh user lain.' });
            }
            user.email = email;
        }

        if (name) user.name = name;
        if (role) {
            // Map role string to role_id
            user.role_id = (role === 'admin') ? 1 : 5;
        }

        if (password) {
            user.password = await bcrypt.hash(password, 10);
        }

        await user.save();

        const updatedUser = await User.findByPk(user.id, {
            attributes: { exclude: ['password'] }
        });

        return res.status(200).json({
            message: 'User berhasil diperbarui.',
            data: mapUserRole(updatedUser)
        });
    } catch (error) {
        console.error('Error updating user:', error);
        return res.status(500).json({ message: 'Gagal memperbarui user.' });
    }
};

// DELETE user
const deleteUser = async (req, res) => {
    try {
        const user = await User.findByPk(req.params.id);
        if (!user) {
            return res.status(404).json({ message: 'User tidak ditemukan.' });
        }

        // Don't let logged in admin delete themselves
        if (user.id === req.user.id) {
            return res.status(400).json({ message: 'Anda tidak dapat menghapus akun Anda sendiri.' });
        }

        await user.destroy();
        return res.status(200).json({ message: 'User berhasil dihapus.' });
    } catch (error) {
        console.error('Error deleting user:', error);
        return res.status(500).json({ message: 'Gagal menghapus user.' });
    }
};

module.exports = {
    getAllUsers,
    getUserById,
    createUser,
    updateUser,
    deleteUser
};
