<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Details</title>
</head>
<body>
    <h1>Client Details</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <p><strong>Name:</strong> {{ $client->name }}</p>
    <p><strong>Client code:</strong> {{ $client->client_code }}</p>
</body>
</html>
