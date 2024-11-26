const express = require("express");
const cors = require("cors");
const path = require("path");
const db = require("./db");

const app = express();
const PORT = 3000;

// Middleware
app.use(cors({ origin: "http://localhost:3000" }));
app.use(express.json());

// Routes
const cardsRouter = require("./routes/cards");
const decksRouter = require("./routes/decks");

app.use("/api/cards", cardsRouter);
app.use("/api/decks", decksRouter);

// Serve frontend
app.use(express.static(path.join(__dirname, "../frontend/src")));

// Start server
app.listen(PORT, () => {
  console.log(`Server running at http://localhost:${PORT}`);
});
