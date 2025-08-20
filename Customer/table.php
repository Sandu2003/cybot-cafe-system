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
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    text-align: center;
    padding: 50px;
}
h1 {
    color: #333;
    margin-bottom: 30px;
}
#tableContainer {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}
.table-circle {
    width: 100px;
    height: 100px;
    background: #6d4c41;
    color: white;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    font-weight: bold;
    transition: transform 0.2s, background 0.2s;
}
.table-circle:hover {
    transform: scale(1.1);
    background: #4e342e;
}
.table-circle.selected {
    border: 3px solid #ff9800;
    background: #8d6e63;
}
#continueBtn {
    margin-top: 30px;
    padding: 10px 20px;
    font-size: 16px;
    background: #ff9800;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    transition: background 0.2s;
}
#continueBtn:hover {
    background: #e68900;
}
</style>
</head>
<body>

<h1>Select Your Table</h1>
<div id="tableContainer"></div>
<button id="continueBtn">Continue to Menu</button>

<script src="table.js"></script>
</body>
</html>
