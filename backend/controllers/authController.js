const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { User, Role } = require('../models');

const login = async (req, res) => {
    try {
        const { email, password } = req.body;

        if (!email || !password) {
            return res.status(400).json({ message: 'Email dan password wajib diisi.' });
        }

        // Find user by email along with Role details
        const user = await User.findOne({ 
            where: { email },
            include: [{
                model: Role,
                as: 'role',
                attributes: ['id', 'name']
            }]
        });

        if (!user) {
            return res.status(401).json({ message: 'Email atau password salah.' });
        }

        // Check password
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ message: 'Email atau password salah.' });
        }

        // Allow all roles to login (Admin, Kepala Lab, Kaprodi, Staf Admin, Staf Lab)
        if (!user.role) {
            return res.status(403).json({ message: 'Akses ditolak: Role pengguna tidak valid.' });
        }

        // Generate JWT token - include role_id and role name for permission checks
        const token = jwt.sign(
            { 
                id: user.id, 
                name: user.name, 
                email: user.email, 
                role: user.role.name,
                role_id: user.role_id
            },
            process.env.JWT_SECRET || 'supersecretjwttokenkey123',
            { expiresIn: '24h' }
        );

        return res.status(200).json({
            message: 'Login berhasil.',
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role.name,
                role_id: user.role_id
            }
        });
    } catch (error) {
        console.error('Error during login:', error);
        return res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
    }
};

module.exports = {
    login
};
