<?php
// Function to calculate bill based on slabs
function calculate_bill($units) {
    if (!is_numeric($units) || $units < 0) return 0;

    $unit_cost_first_50 = 3.50;
    $unit_cost_next_100 = 4.00;
    $unit_cost_next_100_2 = 5.20;
    $unit_cost_above_250 = 6.50;

    if($units <= 50) {
        $bill = $units * $unit_cost_first_50;
    }
    else if($units <= 150) {
        $bill = (50 * $unit_cost_first_50) + (($units - 50) * $unit_cost_next_100);
    }
    else if($units <= 250) {
        $bill = (50 * $unit_cost_first_50) + (100 * $unit_cost_next_100) + (($units - 150) * $unit_cost_next_100_2);
    }
    else {
        $bill = (50 * 3.50) + (100 * 4.00) + (100 * 5.20) + (($units - 250) * $unit_cost_above_250);
    }
    return $bill;
}

$months = ["January", "February", "March", "April", "May", "June", 
          "July", "August", "September", "October", "November", "December"];

$results = [];
$total_yearly_units = 0;
$total_yearly_cost = 0;
$submitted = false;

if (isset($_POST['calculate-yearly'])) {
    $submitted = true;
    $input_months = $_POST['month_units']; // This is an array from the form

    foreach ($months as $index => $month_name) {
        $u = isset($input_months[$index]) ? (float)$input_months[$index] : 0;
        $cost = calculate_bill($u);
        
        $results[$month_name] = [
            'units' => $u,
            'cost' => number_format($cost, 2)
        ];
        
        $total_yearly_units += $u;
        $total_yearly_cost += $cost;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Electricity Bill Calculator (Yearly)</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { 
            background-color: #e9ecef; 
            padding-top: 50px;
            font-family: Arial, sans-serif;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            max-width: 900px;
        }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .month-input-row { margin-bottom: 10px; }
        .total-box {
            background-color: #d1ecf1;
            padding: 15px;
            border: 1px solid #bee5eb;
            margin-top: 20px;
        }
        table thead { background-color: #343a40; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h2>Yearly Electricity Calculator</h2>
    <p>Enter the units consumed for each month below to see your yearly breakdown.</p>

    <form method="post" action="">
        <div class="row">
            <?php foreach ($months as $index => $month): ?>
            <div class="col-md-3 mb-3">
                <label><strong><?php echo $month; ?></strong></label>
                <input type="number" step="0.01" name="month_units[]" class="form-control" 
                       value="<?php echo isset($_POST['month_units'][$index]) ? $_POST['month_units'][$index] : '0'; ?>" required>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-3">
            <button type="submit" name="calculate-yearly" class="btn btn-primary btn-lg">Calculate Yearly Bill</button>
            <a href="index.php" class="btn btn-secondary btn-lg">Reset</a>
        </div>
    </form>

    <?php if ($submitted): ?>
    <hr>
    <h3>Results Summary</h3>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Month</th>
                <th>Units Used</th>
                <th>Calculated Cost (Rs.)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $month => $data): ?>
            <tr>
                <td><?php echo $month; ?></td>
                <td><?php echo $data['units']; ?></td>
                <td><?php echo $data['cost']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot style="font-weight: bold; background: #f8f9fa;">
            <tr>
                <td>TOTALS</td>
                <td><?php echo $total_yearly_units; ?> Units</td>
                <td>Rs. <?php echo number_format($total_yearly_cost, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <div class="total-box">
        <h4>Average Monthly Cost: Rs. <?php echo number_format($total_yearly_cost / 12, 2); ?></h4>
    </div>
    <?php endif; ?>

    <div class="mt-5 small text-muted">
        <strong>Price Slabs Reference:</strong><br>
        0-50 Units: 3.50/unit | 51-150: 4.00/unit | 151-250: 5.20/unit | 250+: 6.50/unit
    </div>
</div>

</body>
</html>