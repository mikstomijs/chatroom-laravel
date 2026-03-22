const express = require('express'); 
const app = express();
const http = require('http');
const server = http.createServer(app);
const { Server } = require("socket.io");
const io = new Server(server, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"]
  }
});
const Database = require('better-sqlite3');

app.use((req, res, next) => {
  res.header("Access-Control-Allow-Origin", "*");
  next();
});

const db = new Database('database/database.sqlite');

const UserCountTotal = db.prepare(`
  SELECT COUNT(*) AS total
  FROM users
  `);

const UserCountCountry = db.prepare(`
  SELECT country, COUNT(*) AS total
  FROM users
  GROUP BY country
  ORDER BY total DESC
`);
app.get('/stats/usercount', (req, res) => {
  const total = UserCountTotal.get();
  res.json({ total_users: total.total }); 
});

app.get('/stats/usercountbycountry', (req, res) => {
  const results = UserCountCountry.all();
  res.json(results);
});




const getHistory = db.prepare(`
  SELECT username, message 
  FROM messages 
  WHERE room = ? 
  ORDER BY created_at ASC
`);

const insertMessage = db.prepare(`
  INSERT INTO messages (room, username, message, created_at) 
  VALUES (?, ?, ?, ?)
`);

const chat1 = io.of('/chat1');
const chat2 = io.of('/chat2');
const chat3 = io.of('/chat3');

function setupChatroom(namespace, room) {
  namespace.on('connection', (socket) => {
    console.log('a user connected');

    const history = getHistory.all(room);
    console.log(room);
    socket.emit('chat history', history);

    socket.on('set nickname', (nickname) => {
      socket.nickname = nickname;
    });

    socket.on('chat message', (msg) => {
      const name = socket.nickname || 'Anonymous';
      console.log(room, name, msg, Date.now());
      insertMessage.run(room, name, msg, Date.now());
      namespace.emit('chat message', name + ": " + msg);
    });

    socket.on('disconnect', () => {
      const name = socket.nickname || 'A user'; 
      namespace.emit('chat message', name + " left the chat");
    });
  });
}

setupChatroom(chat1, "Chatroom 1");
setupChatroom(chat2, "Chatroom 2");
setupChatroom(chat3, "Chatroom 3");

server.listen(3000, () => {
  console.log('listening on *:3000');
});