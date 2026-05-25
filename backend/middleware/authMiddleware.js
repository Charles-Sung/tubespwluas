const jwt = require('jsonwebtoken');

const authenticate = (req, res, next) => {
    const authHeader = req.headers['authorization'];
    if (!authHeader) {
        return res.status(401).json({ message: 'Akses ditolak: Token tidak ditemukan.' });
    }

    const token = authHeader.split(' ')[1];
    if (!token) {
        return res.status(401).json({ message: 'Akses ditolak: Format token salah.' });
    }

    try {
        const decoded = jwt.verify(token, process.env.JWT_SECRET || 'supersecretjwttokenkey123');
        
        // Ensure user is admin
        if (decoded.role !== 'admin') {
            return res.status(403).json({ message: 'Akses ditolak: Hanya Administrator yang diizinkan.' });
        }

        req.user = decoded;
        next();
    } catch (error) {
        return res.status(403).json({ message: 'Akses ditolak: Token tidak valid atau kedaluwarsa.' });
    }
};

module.exports = {
    authenticate
};
