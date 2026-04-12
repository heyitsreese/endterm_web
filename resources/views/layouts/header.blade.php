<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sprint PHL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://kit.fontawesome.com/97c3b6d53c.js" crossorigin="anonymous"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon.png') }}">
    <link rel="icon" type="image" href="{{ asset('images/logo.png') }}">
    <style>
        .option-card {
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;
        }

        .service-card {
            padding: 16px;
            border: 1px solid #ddd;
            border-radius: 12px;
            cursor: pointer;
            transition: 0.2s;

            /* ✅ ADD THESE */
            height: 110px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            text-align: center;
        }

        .service-card:hover {
            border-color: #D47497;
            background: #FFF1F6;
        }

        .option-card:hover {
            border-color: #D47497;
            background: #FFF1F6;
        }

        .option-card.active,
        .service-card.active {
            border: 2px solid #D47497;
            background: #FCE7F3;
        }

        #custom-size-input {
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">