const { DataTypes } = require('sequelize');
const sequelize = require('../config/database');

const MaintenanceBhp = sequelize.define('MaintenanceBhp', {
    id: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    maintenance_log_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'maintenance_logs',
            key: 'id'
        }
    },
    item_id: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: 'items',
            key: 'id'
        }
    },
    quantity: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 1
    }
}, {
    tableName: 'maintenance_bhps',
    underscored: true
});

module.exports = MaintenanceBhp;
