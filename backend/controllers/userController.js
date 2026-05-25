const bcrypt = require('bcryptjs');
const { User } = require('../models');

// GET all users
const getAllUsers = async (req, res) => {
    try {
        const users = await User.findAll({
            attributes: { exclude: ['password'] }
        });
        return res.status(200).json(users);
    } catch (error) {
        console.error('Error fetching users:', error);
        return res.status(500).json({ message: 'Gagal mengambil data user.' });
    }
};

// GET user by ID
const getUserById = async (req, res) => {
    try {
        const user = await User.findByPk(req.params.id, {
            attributes: { exclude: ['password'] }
        });
        if (!user) {
            return res.status(404).json({ message: 'User tidak ditemukan.' });
        }
        return res.status(200).json(user);
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

        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = await User.create({
            name,
            email,
            password: hashedPassword,
            role
        });

        const { password: _, ...userWithoutPassword } = newUser.toJSON();
        return res.status(201).json({
            message: 'User berhasil ditambahkan.',
            data: userWithoutPassword
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
        if (role) user.role = role;

        if (password) {
            user.password = await bcrypt.hash(password, 10);
        }

        await user.save();

        const { password: _, ...userWithoutPassword } = user.toJSON();
        return res.status(200).json({
            message: 'User berhasil diperbarui.',
            data: userWithoutPassword
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
