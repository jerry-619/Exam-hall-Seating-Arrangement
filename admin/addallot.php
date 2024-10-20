<?php
include '../db.php';
session_start();

if (isset($_POST['addallotment'])) {
    $room = $_POST['room'];
    $class = $_POST['class'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Get the room capacity
    $room_capacity_query = "SELECT capacity FROM room WHERE rid = ?";
    $room_capacity_stmt = mysqli_prepare($conn, $room_capacity_query);
    mysqli_stmt_bind_param($room_capacity_stmt, 'i', $room);
    mysqli_stmt_execute($room_capacity_stmt);
    $room_capacity_result = mysqli_stmt_get_result($room_capacity_stmt);
    $room_capacity_row = mysqli_fetch_assoc($room_capacity_result);
    $room_capacity = $room_capacity_row ? $room_capacity_row['capacity'] : 0;

    // Get the current number of students already allotted in the room
    $current_students_query = "SELECT COUNT(DISTINCT seat_no) AS current_students FROM batch WHERE room_id = ?";
    $current_students_stmt = mysqli_prepare($conn, $current_students_query);
    mysqli_stmt_bind_param($current_students_stmt, 'i', $room);
    mysqli_stmt_execute($current_students_stmt);
    $current_students_result = mysqli_stmt_get_result($current_students_stmt);
    $current_students_row = mysqli_fetch_assoc($current_students_result);
    $current_students = $current_students_row ? $current_students_row['current_students'] : 0;

    // Calculate the remaining capacity of the room
    $remaining_capacity = $room_capacity - $current_students;

    // Get the total number of students for the given class
    $student_count_query = "SELECT COUNT(*) AS total_students FROM students WHERE class = ?";
    $student_count_stmt = mysqli_prepare($conn, $student_count_query);
    mysqli_stmt_bind_param($student_count_stmt, 's', $class);
    mysqli_stmt_execute($student_count_stmt);
    $student_count_result = mysqli_stmt_get_result($student_count_stmt);
    $student_count_row = mysqli_fetch_assoc($student_count_result);
    $total_students = $student_count_row ? $student_count_row['total_students'] : 0;

    // Generate a list of unique seat numbers for the remaining capacity
    $available_seats = range(1, $room_capacity);
    $current_seats_query = "SELECT DISTINCT seat_no FROM batch WHERE room_id = ?";
    $current_seats_stmt = mysqli_prepare($conn, $current_seats_query);
    mysqli_stmt_bind_param($current_seats_stmt, 'i', $room);
    mysqli_stmt_execute($current_seats_stmt);
    $current_seats_result = mysqli_stmt_get_result($current_seats_stmt);
    while ($row = mysqli_fetch_assoc($current_seats_result)) {
        if (($index = array_search($row['seat_no'], $available_seats)) !== false) {
            unset($available_seats[$index]);
        }
    }
    shuffle($available_seats);

    // Insert each student with their seat number into the batch table
    $i = $start; // Start with the specified start roll number
    foreach ($available_seats as $seat) {
        if ($remaining_capacity <= 0) break;

        // Get the student_id of the student
        $student_query = "SELECT student_id FROM students WHERE class = ? AND rollno = ?";
        $student_stmt = mysqli_prepare($conn, $student_query);
        mysqli_stmt_bind_param($student_stmt, 'si', $class, $i);
        mysqli_stmt_execute($student_stmt);
        $student_result = mysqli_stmt_get_result($student_stmt);
        $student_row = mysqli_fetch_assoc($student_result);
        if ($student_row) {
            $student_id = $student_row['student_id'];

            // Insert student into the batch table with their seat number
            $insert_query = "INSERT INTO batch (class_id, room_id, startno, endno, seat_no, student_id) VALUES (?, ?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, 'iiiiii', $class, $room, $start, $end, $seat, $student_id);
            if (!mysqli_stmt_execute($insert_stmt)) {
                echo "Error: " . mysqli_stmt_error($insert_stmt);
            }
        } else {
            echo "No student found with roll number $i in class $class.";
        }

        $i++; // Move to the next roll number
        $remaining_capacity--; // Reduce the remaining capacity
    }

    $_SESSION['batch'] = "New allotment placed successfully.";
    header("Location: dashboard.php");
    exit();
}
?>