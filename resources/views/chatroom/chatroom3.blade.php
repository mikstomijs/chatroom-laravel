<!DOCTYPE html>
<html>
<head>
<title>Socket.IO chat</title>


<style>
body { margin: 0; padding-bottom: 3rem; font-family: Arial; }

#form {
  background: rgba(0,0,0,0.15);
  padding: 0.25rem;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  height: 3rem;
}

#input { flex-grow: 1; }

#messages { list-style-type: none; margin: 0; padding: 0; }

#messages li { padding: 0.5rem 1rem; }
</style>

</head>

<body>

<ul id="messages"></ul>

<form id="form">
  <a href="/dashboard">Back to main page</a>
  <input id="input" autocomplete="off">
  <button id ="button">Send</button>
</form>
<div id="error" style="display:none; color:red; font-size:3rem; font-weight:bold;">
  Message cannot be empty!
</div>
 <script src="http://localhost:3000/socket.io/socket.io.js"></script>
  <script>
    const socket = io('http://localhost:3000/chat3');
    const input = document.getElementById('input').value;


var form = document.getElementById("form");
function handleForm(event) { event.preventDefault(); } 
form.addEventListener('submit', handleForm);

  document.getElementById('button').addEventListener('click', function(e) {

    const input = document.getElementById('input');

    if (input.value.trim() === '') {
      document.getElementById('error').style.display = 'block';
      window.scrollTo(0, document.body.scrollHeight);
      return;
    }

   document.getElementById('error').style.display = 'none';
    socket.emit('chat message', input.value);
    input.value = '';
});

    socket.emit('set nickname', '{{ auth()->user()->name }}');

  socket.on('chat history', (history) => {
    const ul = document.getElementById('messages');
    ul.innerHTML = '';
    history.forEach(row => {
        const li = document.createElement('li');
        li.textContent = `${row.username}: ${row.message}`;
        ul.appendChild(li);
    });
    window.scrollTo(0, document.body.scrollHeight);
});

  socket.on('chat message', (msg) => {
      const ul = document.getElementById('messages');
      const li = document.createElement('li');
      li.textContent = msg;
      ul.appendChild(li);
      window.scrollTo(0, document.body.scrollHeight);
    });

    socket.on('connect', () => {
      console.log('connected');
    });
  </script>

</body>
</html>