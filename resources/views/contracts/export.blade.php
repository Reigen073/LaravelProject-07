
<!-- resources/views/contracts/export.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contract {{ $contract->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .contract-details {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Contract Details</h1>
    <div class="contract-details">
        <p><strong>Contract ID:</strong> {{ $contract->id }}</p>
        <p><strong>User ID:</strong> {{ $contract->user_id }}</p>
        <p><strong>File Path:</strong> {{ $contract->file_path }}</p>
        <p><strong>Upload Date:</strong> {{ $contract->created_at->format('d-m-Y H:i:s') }}</p>
        <!-- Add other contract details here as needed -->
    </div>
</body>
</html>
