<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen">
    <div class="flex justify-center items-center h-full">
        <embed src="{{ $url }}" type="application/pdf" class="w-full h-full">
    </div>
</body>
</html>
