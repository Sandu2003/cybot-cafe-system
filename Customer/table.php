<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Select Table - Cybot Cafe</title>
<style>
/* ===== Body & Typography ===== */
body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #f5f5f5, #ffe0b2);
    text-align: center;
    padding: 50px;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

/* ===== Heading ===== */
h1 {
    color: #3e2723;
    margin-bottom: 40px;
    font-size: 2.2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

/* ===== Table Container ===== */
#tableContainer {
    display: flex;
    justify-content: center;
    gap: 30px;
    flex-wrap: wrap;
}

/* ===== Table Circles ===== */
.table-circle {
    width: 120px;
    height: 120px;
    background: #6d4c41;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
}
.table-circle:hover {
    transform: scale(1.15) rotate(-3deg);
    background: #4e342e;
    box-shadow: 0 6px 12px rgba(0,0,0,0.3);
}
.table-circle.selected {
    border: 4px solid #ff9800;
    background: #8d6e63;
    box-shadow: 0 8px 16px rgba(0,0,0,0.4);
}

/* ===== Continue Button ===== */
#continueBtn {
    margin-top: 40px;
    padding: 15px 35px;
    font-size: 1.1rem;
    background: #ff9800;
    border: none;
    border-radius: 50px;
    color: white;
    cursor: pointer;
    font-weight: bold;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: background 0.3s, transform 0.3s, box-shadow 0.3s;
}
#continueBtn:hover {
    background: #e68900;
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(0,0,0,0.3);
}

/* ===== Responsive ===== */
@media (max-width: 500px) {
    .table-circle {
        width: 90px;
        height: 90px;
        font-size: 1rem;
    }
    #continueBtn {
        padding: 12px 25px;
        font-size: 1rem;
    }
}
</style>
</head>
<body>

<h1>☕ Select Your Table</h1>
<div id="tableContainer"></div>
<button id="continueBtn">Continue to Menu</button>

<script src="table.js"></script>
</body>
</html>
