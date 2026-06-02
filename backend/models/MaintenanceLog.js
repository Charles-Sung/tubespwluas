const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const MaintenanceLog = sequelize.define('MaintenanceLog', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    inventory_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'inventories',
            key: 'id'
        }
    },
    user_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'users',
            key: 'id'
        }
    },
    maintenance_date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    description: {
        type: DataTypes.TEXT,
        allowNull: false
    },
    previous_condition: {
        type: DataTypes.ENUM('good', 'maintenance', 'broken'),
        allowNull: false
    },
    new_condition: {
        type: DataTypes.ENUM('good', 'maintenance', 'broken'),
        allowNull: false
    }
}, {
    tableName: 'maintenance_logs',
    underscored: true
});

module.exports = MaintenanceLog;
