<?php
ob_start();
include '../public/connectDB.php';

// ตรวจสอบการเข้าสู่ระบบของ Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Admin Dashboard</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <script src="js/alerts.js"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Ordering Information -->
            <li class="nav-item">
                <a class="nav-link" href="manage_orders.php">
                    <i class="fas fa-fw fa-info-circle"></i>
                    <span>Ordering Information</span></a>
            </li>

            <!-- Nav Item - Edit Product -->
            <li class="nav-item">
                <a class="nav-link" href="manage_products.php">
                    <i class="fas fa-fw fa-edit"></i>
                    <span>Edit Product</span></a>
            </li>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Time display in the center -->
                    <div class="mx-auto" id="current-time"></div>

                    <!-- JavaScript -->
                    <script>
                        function updateTime() {
                            const now = new Date();
                            const hours = now.getHours().toString().padStart(2, '0');
                            const minutes = now.getMinutes().toString().padStart(2, '0');
                            const seconds = now.getSeconds().toString().padStart(2, '0');
                            const currentTime = `${hours}:${minutes}:${seconds}`;
                            document.getElementById('current-time').textContent = currentTime;
                        }

                        // Update every second
                        setInterval(updateTime, 1000);
                        // Initial call to display time immediately
                        updateTime();
                    </script>
                    <!-- CSS -->
                    <style>
                        .navbar .mx-auto {
                            position: absolute;
                            left: 50%;
                            transform: translateX(-50%);
                            font-size: 24px; /* เพิ่มขนาดตัวอักษร */
                            font-weight: bold;
                            color: #4e73df; /* สีที่โดดเด่น */
                        }

                        @media (max-width: 768px) {
                            .navbar .mx-auto {
                                font-size: 18px; /* ขนาดตัวอักษรเล็กลงสำหรับอุปกรณ์ขนาดเล็ก */
                            }
                        }
                    </style>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                         <!-- Nav Item - Alerts -->
                         <li class="nav-item dropdown no-arrow mx-1 show">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter" id="alertCount">0</span>
                            </a>

                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <!-- New Order Alert -->
                                <div id="alertContent">
                                    <!-- Alerts will be dynamically inserted here -->
                                </div>
                                <a class="dropdown-item text-center small text-gray-500" href="orderDetails.php">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Chart.js and jQuery -->
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                        <script>
                        $(document).ready(function() {
                            // ฟังก์ชันดึงข้อมูลออเดอร์ใหม่
                            function updateAlerts() {
                                $.getJSON('get_new_orders.php', function(data) {
                                    var newOrders = data;

                                    // อัพเดตจำนวนแจ้งเตือน
                                    $('#alertCount').text(newOrders.length);

                                    // สร้างเนื้อหาของการแจ้งเตือน
                                    var alertHtml = '';
                                    if (newOrders.length > 0) {
                                        $.each(newOrders, function(index, order) {
                                            alertHtml += 
                                                '<a class="dropdown-item d-flex align-items-center" href="view_order.php?order_id=' + order.order_id + '">' +
                                                '<div class="mr-3">' +
                                                '<div class="icon-circle bg-info">' +
                                                '<i class="fas fa-shopping-cart text-white"></i>' +
                                                '</div>' +
                                                '</div>' +
                                                '<div>' +
                                                '<div class="small text-gray-500">' + new Date(order.order_date).toLocaleDateString() + '</div>' +
                                                '<span class="font-weight-bold">New order received! Order ID: ' + order.order_id + '</span>' +
                                                '</div>' +
                                                '</a>';
                                        });
                                    } else {
                                        alertHtml = '<a class="dropdown-item text-center small text-gray-500" href="#">No new orders</a>';
                                    }

                                    $('#alertContent').html(alertHtml);
                                });
                            }

                            // เรียกใช้ฟังก์ชันเพื่ออัพเดตแจ้งเตือนเมื่อเอกสารโหลดเสร็จ
                            updateAlerts();

                            // รีเฟรชแจ้งเตือนทุกๆ 30 วินาที
                            setInterval(updateAlerts, 30000);
                        });
                        </script>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>

