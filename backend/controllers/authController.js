const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { User } = require('../models');

const login = async (req, res) => {
    try {
        const { email, password } = req.body;

        if (!email || !password) {
            return res.status(400).json({ message: 'Email dan password wajib diisi.' });
        }

        // Find user by email
        const user = await User.findOne({ where: { email } });
        if (!user) {
            return res.status(401).json({ message: 'Email atau password salah.' });
        }

        // Check password
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ message: 'Email atau password salah.' });
        }

        // Check role
        if (user.role !== 'admin') {
            return res.status(403).json({ message: 'Akses ditolak: Hanya Administrator yang diizinkan masuk.' });
        }

        // Generate JWT token
        const token = jwt.sign(
            { 
                id: user.id, 
                name: user.name, 
                email: user.email, 
                role: user.role 
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
                role: user.role
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
