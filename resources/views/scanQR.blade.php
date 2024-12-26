<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan to Login</title>
</head>
<body>
    <h1>Scan the QR Code to Pre-order first</h1>
    <div>
    {!! QrCode::size(200)->generate('http://192.168.1.16:8000/userlogin') !!}
    </div>
</body>
</html>