<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Apple Style</title>
    <!-- Apple-style CSS -->
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f7;
            color: #333;
        }
        .navbar {
            background-color: #fff;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .navbar-brand:hover {
            text-decoration: none;
            color: #0070c9;
        }
        .logout-btn {
            color: #0070c9;
            font-weight: bold;
            float: right;
            text-decoration: none;
        }
        .logout-btn:hover {
            text-decoration: underline;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #f5f5f7;
            padding-top: 80px;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            color: #0070c9;
            text-decoration: none;
            font-weight: 500;
            display: block;
            padding: 10px 20px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #0070c9;
            color: white;
        }
        .content {
            margin-left: 270px;
            padding: 80px 20px;
        }
        .card {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
        }
        .card h5 {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .card p {
            font-size: 28px;
            font-weight: 500;
            color: #0070c9;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f5f5f7;
            text-align: left;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 12px;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: white;
        }
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Thanh điều hướng -->
    <div class="navbar">
        <a class="navbar-brand" href="#">Dashboard</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#">Overview</a>
        <a href="#">Profile</a>
        <a href="#">Settings</a>
        <a href="#">Reports</a>
    </div>

    <!-- Nội dung chính -->
    <div class="content">
        <h1>Welcome to your Dashboard</h1>
        <div class="row">
            <div class="card">
                <h5>Total Users</h5>
                <p>1,245</p>
            </div>
            <div class="card">
                <h5>Revenue</h5>
                <p>$13,500</p>
            </div>
            <div class="card">
                <h5>Active Sessions</h5>
                <p>156</p>
            </div>
        </div>

        <div class="card">
            <h5>Latest Reports</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Report Name</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Monthly Sales Report</td>
                        <td>2024-10-01</td>
                        <td><span class="badge badge-success">Completed</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>User Activity</td>
                        <td>2024-10-10</td>
                        <td><span class="badge badge-warning">In Progress</span></td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Revenue Analysis</td>
                        <td>2024-10-15</td>
                        <td><span class="badge badge-danger">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

