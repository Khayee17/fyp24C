<!DOCTYPE html>
<html lang="en">
<head>
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dine Queue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff7e6;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }
        .logo-container {
            text-align: center;
        }
        .input-group-container {
            background-color: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 280px;
            text-align: center;
        }
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }
        .input-group input {
            width: 100%;
            padding: 15px 45px;
            border-radius: 20px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-size: 16px;
            background-color: #f5f5f5;
        }
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #ccc;
        }
        .input-group input:focus {
            outline: none;
            border-color: #ff8c00; 
            box-shadow: 0 0 5px rgba(255, 140, 0, 0.5); 
        }
        .button {
            background-color: #ffa500;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 20px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
        }
        .background {
            background-color: #ffa500;
            width: 100%;
            height: 50vh;
            position: absolute;
            bottom: 0;
            left: 0;
            z-index: -1;
        }

    </style>
</head>

<body>
    <div class="logo-container">
        <img src="/images/login logo.png" alt="Dine Queue Logo" style="width: 300px;">
    </div>

    <div class="input-group-container">
        <form action="{{ route('storeUserInfo') }}" method="POST">
            @csrf
            <div class="input-group">
                <i class="fas fa-phone"></i>
                <input type="text" name="phone" placeholder="Phone Number" required>
            </div>
            <div class="input-group">
                <i class="fas fa-users"></i>
                <input type="text" name="numberOfCustomers" placeholder="Number of Customers" required>
            </div>
            <button type="submit"class="button">Next</button>
        </form>
    </div>

    <div class="background"></div>

    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        const numberOfCustomers = document.querySelector('input[name="numberOfCustomers"]').value;
        localStorage.setItem('numberOfCustomers', numberOfCustomers);
    });
    </script>
    
</body>
</html>