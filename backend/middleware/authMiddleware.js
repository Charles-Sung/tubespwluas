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
        req.user = decoded;
        next();
    } catch (error) {
        return res.status(403).json({ message: 'Akses ditolak: Token tidak valid atau kedaluwarsa.' });
    }
};

const isAdmin = (req, res, next) => {
    const roleId = req.user && req.user.role_id;
    if (roleId !== 1) {
        return res.status(403).json({ message: 'Akses ditolak: Hanya Administrator yang diizinkan.' });
    }
    next();
};

const isKaprodi = (req, res, next) => {
    const roleId = req.user && req.user.role_id;
    // Admin (1) can also access Kaprodi routes
    if (roleId !== 3 && roleId !== 1) {
        return res.status(403).json({ message: 'Akses ditolak: Hanya Ketua Program Studi yang diizinkan.' });
    }
    next();
};

const isKalab = (req, res, next) => {
    const roleId = req.user && req.user.role_id;
    // Admin (1) can also access Kalab routes
    if (roleId !== 2 && roleId !== 1) {
        return res.status(403).json({ message: 'Akses ditolak: Hanya Kepala Laboratorium yang diizinkan.' });
    }
    next();
};

module.exports = {
    authenticate,
    isAdmin,
    isKaprodi,
    isKalab
};
