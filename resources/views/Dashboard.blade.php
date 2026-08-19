<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>

    <h2>Dashboard</h2>
    <p>Halo, {{ Auth::user()->name }}</p>

    <form method="POST" action="/logout">
        @csrf
        <button type="submit">Logout</button>
    </form>

    <hr>

    <h3>Informasi Akun</h3>
    <p>Nama: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <p>ID: {{ Auth::user()->id }}</p>
    <p>Bergabung: {{ Auth::user()->created_at->format('d F Y') }}</p>

</body>
</html>