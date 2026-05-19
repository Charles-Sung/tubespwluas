const mysql = require('mysql2/promise');

async function resetDb() {
    const connection = await mysql.createConnection({ host: '127.0.0.1', user: 'root', password: '' });
    await connection.query('DROP DATABASE IF EXISTS capstone2');
    await connection.query('CREATE DATABASE capstone2');
    console.log("Database reset.");
    process.exit(0);
}
resetDb();
