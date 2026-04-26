<?php
// Database connection
$conn = new mysqli("localhost", "root", "db_password", "db_name");

// Logic to handle the AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax'])) {
    $n = $_POST['name'];
    $m = $_POST['m']; 
    $e = $_POST['e']; 
    
    $sql = "INSERT INTO marks (student_name, s1_m, s1_e, s2_m, s2_e, s3_m, s3_e, s4_m, s4_e) 
            VALUES ('$n', {$m[0]}, {$e[0]}, {$m[1]}, {$e[1]}, {$m[2]}, {$e[2]}, {$m[3]}, {$e[3]})";
    
    if ($conn->query($sql)) {
        echo "success";
    } else {
        echo "error";
    }
    exit; // Stop further execution for AJAX calls
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VIT Result Portal</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .card { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        h2 { margin-top: 0; color: #333; text-align: center; }
        input { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; }
        .row { display: flex; gap: 10px; align-items: center; margin-bottom: 5px; }
        .row label { font-size: 14px; font-weight: bold; min-width: 50px; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; margin-top: 15px; }
        button:hover { background: #0056b3; }

        /* Toast Styling */
        #toast {
            visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center;
            border-radius: 8px; padding: 16px; position: fixed; z-index: 1; left: 50%; bottom: 30px;
            transform: translateX(-50%); font-size: 15px;
        }
        #toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
        @keyframes fadein { from {bottom: 0; opacity: 0;} to {bottom: 30px; opacity: 1;} }
        @keyframes fadeout { from {bottom: 30px; opacity: 1;} to {bottom: 0; opacity: 0;} }
    </style>
</head>
<body>

<div class="card">
    <h2>VIT Marks Entry</h2>
    <form id="marksForm">
        <input type="hidden" name="ajax" value="1">
        <input type="text" name="name" placeholder="Student Name" required>
        
        <?php for($i=1; $i<=4; $i++): ?>
            <div class="row">
                <label>Sub <?=$i?></label>
                <input type="number" name="m[]" placeholder="MSE (30)" max="30" required>
                <input type="number" name="e[]" placeholder="ESE (70)" max="70" required>
            </div>
        <?php endfor; ?>
        
        <button type="submit">Submit Marks</button>
    </form>
</div>

<div id="toast">Submitted successfully!</div>

<script>
    const form = document.getElementById('marksForm');
    const toast = document.getElementById('toast');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('index.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showToast("Data stored successfully!");
                form.reset(); // Clear the form
            } else {
                showToast("Error saving data.");
            }
        });
    });

    function showToast(msg) {
        toast.innerText = msg;
        toast.className = "show";
        setTimeout(() => { toast.className = toast.className.replace("show", ""); }, 3000);
    }
</script>

</body>
</html>