const sqlite3 = require("sqlite3");
const path = require("path");

const db = new sqlite3.Database(
  path.join(__dirname, "../docker/mtg_database.db"),
  (err) => {
    if (err) {
      console.error("Error connecting to database:", err.message);
    } else {
      console.log("Connected to database.");
    }
  }
);

module.exports = db;
