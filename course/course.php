<?php

require_once("../db_connect_bark_bijou.php");

$sqlAll = $sql = "SELECT * FROM course WHERE valid=1";
$resultAll = $conn->query($sqlAll);
$courseCount = $resultAll->num_rows;

if (isset($_GET["q"])) {
} else if (isset($_GET["p"]) && isset($_GET["order"])) {
    $p = $_GET["p"];
    $order = $_GET["order"];
    $orderClause = "";
    switch ($order) {
        case 1:
            $orderClause = "ORDER BY registration_start ASC";
            break;
        case 2:
            $orderClause = "ORDER BY registration_start DESC";
            break;
        case 3:
            $orderClause = "ORDER BY cost ASC";
            break;
        case 4:
            $orderClause = "ORDER BY cost DESC";
            break;
    }
    $perPage = 5;
    $startItem = ($p - 1) * $perPage;
    $totalPage = ceil($courseCount / $perPage);

    $sql = "SELECT * FROM course WHERE valid=1 $orderClause LIMIT $startItem,$perPage";
} else {
    header("location: course.php?p=1&order=2");
    exit;
}

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

$sqlImg = "SELECT course.*, 
       (SELECT image FROM course_img WHERE course_img.course_id = course.id LIMIT 1) AS image
FROM course
WHERE course.valid = 1;
";
$resultImg = $conn->query($sqlImg);
$rowImg = $resultImg->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bark & Bijou</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php include("../css.php") ?>
    <link href="./style.css" rel="stylesheet">

    <style>
        .box1 {
            height: 100px;
        }

        .imgsize {
            height: 100px;
        }

        .btn-orange:link,
        .btn-orange:visited {
            color: #ffffff;
            background: rgb(255, 115, 0);
        }

        .btn-orange:hover,
        .btn-orange:active {
            color: #ffffff;
            background:rgba(255, 115, 0, 0.9);
        }
    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion primary" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Bark & Bijou</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>會員專區</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>商品列表</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>課程管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>旅館管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>文章管理</span></a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="index.html">
                    <i class="fa-solid fa-user"></i>
                    <span>優惠券管理</span></a>
            </li>
            <hr class="sidebar-divider">
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
                    <!-- Topbar Search -->
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <!-- Nav Item - Alerts -->
                        <!-- Nav Item - Messages -->
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="mx-4">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h1 mb-0 text-gray-800 fw-bold">課程列表</h1>
                        <a class="btn btn-orange" href="add_course.php">新增課程</a>
                    </div>
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        總共<?= $courseCount ?>筆
                        <div class="dropdown">
                            <a class="btn btn-orange dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown">
                                <?php
                                switch ($order) {
                                    case 1:
                                        echo "按上架時間排序 ↓";
                                        break;
                                    case 2:
                                        echo "按上架時間排序 ↑";
                                        break;
                                    case 3:
                                        echo "按價格排序 ↓";
                                        break;
                                    case 4:
                                        echo "按價格排序 ↑";
                                        break;
                                }
                                ?>
                            </a>
                            <ul class="dropdown-menu btn-orange">
                                <li><a class="dropdown-item" href="course.php?p=1&order=1">按上架時間排序 ↓</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=2">按上架時間排序 ↑</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=3">按價格排序 ↓</a></li>
                                <li><a class="dropdown-item" href="course.php?p=1&order=4">按價格排序 ↑</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- courss-list -->
                    <div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th class="col-1">課程名稱</th>
                                    <th class="col-2">課程內容</th>
                                    <th class="col-1">課程縮圖</th>
                                    <th class="col-1">課程金額</th>
                                    <th class="col-1">課程方法</th>
                                    <th class="col-1">上架時間</th>
                                    <th class="col-1">狀態</th>
                                    <th class="col-1">功能鈕</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rowImg as $course): ?>
                                    <tr class="box1 text-center">
                                        <td class="align-middle"><?= $course["name"] ?></td>
                                        <td class="align-middle"><?= $course["content"] ?></td>
                                        <td><img class="imgsize" src="./course_images/<?= $course["image"] ?>"></td>
                                        <td class="align-middle">$<?= number_format($course["cost"]) ?></td>
                                        <td class="align-middle">
                                            <?php
                                            switch ($course["method_id"]) {
                                                case 1:
                                                    echo "線上";
                                                    break;
                                                case 2:
                                                    echo "線下";
                                                    break;
                                            } ?></td>
                                        <td class="align-middle"><?= $course["registration_start"] ?></td>
                                        <td class="align-middle">在架上</td>
                                        <td class="align-middle">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a class="btn btn-primary" href="course_content.php?id=<?= $course["id"] ?>"><i class="fa-solid fa-eye"></i></i></a>
                                                <a class="btn btn-primary ms-2" href="course_edit.php?id=<?= $course["id"] ?>"><i class="fa-solid fa-pen-to-square fa-fw"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (isset($_GET["p"])): ?>
                        <div>
                            <nav aria-label="">
                                <ul class="pagination">
                                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                        <?php
                                        $active = ($i == $_GET["p"]) ? "active" : "";
                                        ?>
                                        <li class="page-item <?= $active ?>">
                                            <a class="page-link" href="course.php?p=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                    <!-- End of Page Wrapper -->
                </div>
                <!-- Scroll to Top Button-->
            </div>
        </div>
    </div>
</body>


<?php include("../js.php") ?>
<script>

</script>

</html>