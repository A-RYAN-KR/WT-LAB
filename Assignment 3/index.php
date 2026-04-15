<?php
$result_str = $result = '';
$units = '';
if (isset($_POST['unit-submit'])) {
    $units = $_POST['units'];
    if (!empty($units) && is_numeric($units) && $units >= 0) {
        $result = calculate_bill($units);
        $result_str = 'Total amount for ' . $units . ' units is Rs. ' . $result;
    } else {
        $result_str = "Please enter a valid unit amount.";
    }
}

/**
 * To calculate electricity bill as per unit cost
 */
function calculate_bill($units) {
    $unit_cost_first_50 = 3.50;
    $unit_cost_next_100 = 4.00;
    $unit_cost_next_100_2 = 5.20;
    $unit_cost_above_250 = 6.50;

    if($units <= 50) {
        $bill = $units * $unit_cost_first_50;
    }
    else if($units > 50 && $units <= 150) {
        $temp = 50 * $unit_cost_first_50;
        $remaining_units = $units - 50;
        $bill = $temp + ($remaining_units * $unit_cost_next_100);
    }
    else if($units > 150 && $units <= 250) {
        $temp = (50 * $unit_cost_first_50) + (100 * $unit_cost_next_100);
        $remaining_units = $units - 150;
        $bill = $temp + ($remaining_units * $unit_cost_next_100_2);
    }
    else {
        $temp = (50 * 3.50) + (100 * 4.00) + (100 * 5.20);
        $remaining_units = $units - 250;
        $bill = $temp + ($remaining_units * $unit_cost_above_250);
    }
    return number_format((float)$bill, 2, '.', '');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PowerCalc | Modern Electricity Bill Calculator</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #0f172a;
            --accent-color: #38bdf8;
            --card-bg: rgba(30, 41, 59, 0.7);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
        }

        .background-blobs {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            background: var(--accent-color);
            filter: blur(80px);
            opacity: 0.15;
            border-radius: 50%;
            animation: move 20s infinite alternate;
        }

        .blob-1 { width: 400px; height: 400px; top: -100px; left: -100px; }
        .blob-2 { width: 300px; height: 300px; bottom: -50px; right: -50px; background: #818cf8; animation-delay: -5s; }

        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(50px, 50px) scale(1.1); }
        }

        .main-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .calc-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.125);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            transition: transform 0.3s ease;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 10px;
            filter: drop-shadow(0 0 10px rgba(56, 189, 248, 0.4));
        }

        h1 {
            font-weight: 700;
            font-size: 1.75rem;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--text-muted);
            font-weight: 300;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 10px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .input-group {
            background: rgba(15, 23, 42, 0.6);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }

        .form-control {
            background: transparent !important;
            border: none;
            color: white !important;
            padding: 14px 18px;
            font-size: 1.1rem;
            font-weight: 400;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .form-control:focus {
            box-shadow: none;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding-left: 18px;
        }

        .btn-calculate {
            background: linear-gradient(90deg, #38bdf8 0%, #818cf8 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            margin-top: 10px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-calculate:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(56, 189, 248, 0.5);
        }

        .result-container {
            margin-top: 30px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px dashed var(--accent-color);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .result-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-color);
            display: block;
            margin-top: 5px;
        }

        .slabs-info {
            margin-top: 40px;
            font-size: 0.85rem;
            color: var(--text-muted);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding-top: 20px;
        }

        .slab-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .slab-label { font-weight: 400; }
        .slab-value { font-weight: 600; color: var(--text-main); }
    </style>
</head>
<body>
    <div class="background-blobs">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="main-container">
        <div class="calc-card">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h1>PowerCalc</h1>
                <p class="subtitle">Smart Electricity Bill Estimator</p>
            </div>

            <form action="" method="post" id="billForm">
                <div class="mb-4">
                    <label for="units" class="form-label">Units Consumed</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-plug"></i></span>
                        <input type="number" step="0.01" name="units" id="units" class="form-control" placeholder="Enter total units" value="<?php echo htmlspecialchars($units); ?>" required>
                    </div>
                </div>

                <button type="submit" name="unit-submit" class="btn-calculate">
                    Calculate Bill
                </button>
            </form>

            <?php if ($result_str): ?>
                <div class="result-container">
                    <p class="mb-0 text-muted small">Estimated Total</p>
                    <span class="result-amount">₹ <?php echo is_numeric($result) ? $result : '0.00'; ?></span>
                    <p class="mt-2 mb-0" style="font-size: 0.9rem;"><?php echo $result_str; ?></p>
                </div>
            <?php endif; ?>

            <div class="slabs-info">
                <p class="text-center mb-3"><strong>Pricing Slabs</strong></p>
                <div class="slab-item">
                    <span class="slab-label">First 50 Units</span>
                    <span class="slab-value">Rs. 3.50 / unit</span>
                </div>
                <div class="slab-item">
                    <span class="slab-label">Next 100 Units</span>
                    <span class="slab-value">Rs. 4.00 / unit</span>
                </div>
                <div class="slab-item">
                    <span class="slab-label">Next 100 Units</span>
                    <span class="slab-value">Rs. 5.20 / unit</span>
                </div>
                <div class="slab-item">
                    <span class="slab-label">Above 250 Units</span>
                    <span class="slab-value">Rs. 6.50 / unit</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Add subtle interaction
            $('.calc-card').on('mousemove', function(e) {
                let xAxis = (window.innerWidth / 2 - e.pageX) / 25;
                let yAxis = (window.innerHeight / 2 - e.pageY) / 25;
                $(this).css('transform', `rotateY(${xAxis}deg) rotateX(${yAxis}deg)`);
            });

            $('.calc-card').on('mouseleave', function() {
                $(this).css('transform', `rotateY(0deg) rotateX(0deg)`);
            });
        });
    </script>
</body>
</html>
