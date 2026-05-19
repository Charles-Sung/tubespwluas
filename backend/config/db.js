const { Sequelize } = require('sequelize');

const sequelize = new Sequelize('capstone2', 'root', '', {
    host: '127.0.0.1',
    dialect: 'mysql',
    logging: false, // Set to true to see SQL queries
});

module.exports = sequelize;
