// const express = require("express");
// const http = require("http");
// const cors = require("cors");
// const { Server } = require("socket.io");

// const app = express();
// app.use(cors());
// app.use(express.json());

// const server = http.createServer(app);

// // Initialisation Socket.io
// const io = new Server(server, {
//     cors: {
//         origin: "*",
//         methods: ["GET", "POST"]
//     }
// });

// // Quand un admin se connecte
// io.on("connection", (socket) => {
//     console.log("🟢 Admin connecté :", socket.id);

//     socket.on("disconnect", () => {
//         console.log("🔴 Admin déconnecté :", socket.id);
//     });
// });

// // Route appelée par PHP après une commande
// app.post("/resto/commandes/createFromCart", (req, res) => {
//     console.log("📨 Received POST request to /resto/commandes/createFromCart");
//     console.log("📦 Request body:", req.body);
//     console.log("📦 Headers:", req.headers);
    
//     const orderId = req.body.order_id;

//     console.log("🆕 Nouvelle commande :", orderId);

//     // Notification envoyée aux admins connectés
//     io.emit("nouvelle_commande", { order_id: orderId });

//     return res.json({ status: "ok", message: "Notification envoyée" });
// });

// // Port du serveur Node
// const PORT = 8080;
// server.listen(PORT, () => {
//     console.log("🚀 Serveur notifications lancé sur le port", PORT);
// });


const express = require("express");
const http = require("http");
const cors = require("cors");
const { Server } = require("socket.io");

const app = express();
app.use(cors());
app.use(express.json());

const server = http.createServer(app);

// Initialisation Socket.io
const io = new Server(server, {
    cors: {
        origin: "*",
        methods: ["GET", "POST"]
    }
});

io.on("connection", (socket) => {
    console.log("🟢 Admin connecté :", socket.id);

    socket.on("disconnect", () => {
        console.log("🔴 Admin déconnecté :", socket.id);
    });
});

// Route appelée par PHP
app.post("/commandes/createFromCart", (req, res) => {
    const orderId = req.body.order_id;

    console.log("🆕 Nouvelle commande :", orderId);

    io.emit("nouvelle_commande", { order_id: orderId });

    return res.json({ status: "ok", message: "Notification envoyée" });
});

// Render impose d’utiliser process.env.PORT
const PORT = process.env.PORT || 8080;
server.listen(PORT, () => {
    console.log("🚀 Serveur notifications lancé sur le port", PORT);
});
