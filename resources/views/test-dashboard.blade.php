<!DOCTYPE html>
<html>
<head>
    <title>Free Live Ticket Dashboard</title>

    <style>
        body { font-family: Arial; padding: 20px; }

        .card {
            display:inline-block;
            padding:15px;
            margin:10px;
            background:#eee;
            border-radius:8px;
        }

        .toast {
            position:fixed;
            top:20px;
            right:20px;
            background:#111;
            color:#fff;
            padding:12px;
            border-radius:8px;
            display:none;
        }
    </style>
</head>

<body>

<h2>🔥 FREE Admin Live Dashboard</h2>

<div class="card">Total: <span id="total">10</span></div>
<div class="card">Pending: <span id="pending">5</span></div>
<div class="card">Resolved: <span id="resolved">5</span></div>
<div class="card">Unread: <span id="unread">0</span></div>

<div id="toast" class="toast"></div>

<script src="https://js.pusher.com/8.0/pusher.min.js"></script>

<script>

let unread = 0;

/* TOAST */
function toast(msg) {
    let t = document.getElementById('toast');
    t.innerText = msg;
    t.style.display = 'block';

    setTimeout(() => t.style.display = 'none', 3000);
}

/* FREE REVERB CONNECTION */
const pusher = new Pusher("local", {
    wsHost: window.location.hostname,
    wsPort: 8080,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws']
});

/* CHANNEL */
const channel = pusher.subscribe('admin-dashboard');

/* LIVE EVENT */
channel.bind('ticket.created', function(data) {

    console.log(data);

    // 🔔 TOAST
    toast("🔔 " + data.message);

    // 🔴 UNREAD
    unread++;
    document.getElementById('unread').innerText = unread;
});

</script>

</body>
</html>
