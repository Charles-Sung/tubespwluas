const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const { User, Role } = require('../models');

const JWT_SECRET = process.env.JWT_SECRET || 'capstone2_secret_key';

exports.login = async (req, res) => {
    try {
        const { email, password } = req.body;
        const user = await User.findOne({ 
            where: { email },
            include: [{ model: Role }]
        });

        if (!user) {
            return res.status(401).json({ message: 'Email tidak ditemukan.' });
        }

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(401).json({ message: 'Password salah.' });
        }

        const token = jwt.sign(
            { id: user.id, role: user.Role.name, role_id: user.role_id }, 
            JWT_SECRET, 
            { expiresIn: '1d' }
        );

        res.json({
            message: 'Login sukses',
            token,
            user: {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.Role.name
            }
        });

    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
    }
};

exports.getProfile = async (req, res) => {
    try {
        const user = await User.findByPk(req.user.id, {
            attributes: { exclude: ['password'] },
            include: [{ model: Role }]
        });
        res.json(user);
    } catch (error) {
        res.status(500).json({ message: 'Terjadi kesalahan pada server.' });
    }
};
