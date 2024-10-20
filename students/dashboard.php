<?php
session_start();
?>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../admin/common.css">
    <?php include'../link.php' ?>
</head>
<body>
    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <span class="page-name"> DASHBOARD</span>
                <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                   <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png"/>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="nav navbar-nav ml-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="../logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="main-content d-lg-flex justify-content-around">
            <?php
            if(isset($_SESSION['loginid'])){
                $id = $_SESSION['loginid'];
                $select_student = "SELECT * FROM students, class WHERE student_id=? AND class=class_id";
                $stmt = mysqli_prepare($conn, $select_student);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) > 0){
                    $row = mysqli_fetch_assoc($result);
                    $class = $row['class']; 
                    $roll = $row['rollno'];
                    $student_id = $row['student_id'];

                    echo "<div class='mt-4 '>
                            <h2>".$row['name']."</h2>
                            <h6 class='py-2'>".$row['year']." ".$row['dept']." ".$row['division']."</h6>
                            <p>Roll No. ".$row['rollno']."</p>
                        </div>
                        <div>
                            <h5 align=center class='mt-4 mb-3 text-primary'>Exam Seating Allotment</h5>";

                    // Retrieve and display seat allotment with room number and floor number
                    $seat_query = "SELECT b.seat_no, r.room_no, r.floor FROM batch b
                                   JOIN room r ON b.room_id = r.rid
                                   WHERE b.student_id=?";
                    $stmt_seat = mysqli_prepare($conn, $seat_query);
                    mysqli_stmt_bind_param($stmt_seat, 'i', $student_id);
                    mysqli_stmt_execute($stmt_seat);
                    $result_seat = mysqli_stmt_get_result($stmt_seat);

                    if(mysqli_num_rows($result_seat) > 0){
                        echo "<table class='table text-center table-bordered'>
                                <tr>
                                    <th>Seat Number</th>
                                    <th>Room Number</th>
                                    <th>Floor Number</th>
                                </tr>";
                        while($seat_row = mysqli_fetch_assoc($result_seat)) {
                            echo "<tr>
                                    <td>".$seat_row['seat_no']."</td>
                                    <td>".$seat_row['room_no']."</td>
                                    <td>".$seat_row['floor']."</td>
                                </tr>";
                        }
                        echo "</table>";
                    }
                    else{
                        echo "Exam Seat Not Allotted";
                    }
                    echo "</div>";
                }
                else{
                    echo "No student with Id = '$id'";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
