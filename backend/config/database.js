const { Sequelize } = require('sequelize');
require('dotenv').config();

const sequelize = new Sequelize(
    process.env.DB_NAME || 'capstone2',
    process.env.DB_USER || 'root',
    process.env.DB_PASSWORD || '',
    {
        host: process.env.DB_HOST || '127.0.0.1',
        port: process.env.DB_PORT || 3306,
        dialect: 'mysql',
        logging: false, // Turn off logging or set to console.log to debug queries
        timezone: '+07:00', // Matches WIB timezone
        define: {
            timestamps: true,
            underscored: true
        }
    }
);

module.exports = sequelize;
