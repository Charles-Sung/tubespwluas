const sequelize = require('../config/database');
const User = require('./User');
const Room = require('./Room');
const Item = require('./Item');

// Define relationships
Room.hasMany(Item, {
    foreignKey: 'room_id',
    as: 'items',
    onDelete: 'CASCADE'
});

Item.belongsTo(Room, {
    foreignKey: 'room_id',
    as: 'room'
});

module.exports = {
    sequelize,
    User,
    Room,
    Item
};
