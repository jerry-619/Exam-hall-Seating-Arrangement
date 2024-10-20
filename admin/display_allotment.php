<?php
include "../link.php";
if(isset($_POST['display'])){
	$roomid = $_POST['display'];
	echo "<table class='table text-center table-bordered'>
			<thead>
				<tr>
					<th>Name</th>
					<th>Class</th>
					<th>Roll No.</th>
				</tr>
			</thead>
			<tbody>";

			$display = "SELECT DISTINCT students.name, students.class, students.rollno 
            FROM students 
            JOIN batch ON students.class = batch.class_id
            WHERE batch.room_id = ? AND students.rollno BETWEEN batch.startno AND batch.endno";


	$display_query = mysqli_prepare($conn, $display);
	mysqli_stmt_bind_param($display_query, "i", $roomid);
	mysqli_stmt_execute($display_query);
	$result = mysqli_stmt_get_result($display_query);

	$total_students = mysqli_num_rows($result); // Total number of students

	if($total_students > 0){
		while($row = mysqli_fetch_assoc($result)){
			echo "<tr>
					<td>".$row['name']."</td>
					<td>".$row['class']."</td>
					<td>".$row['rollno']."</td>
				</tr>";
		}
		echo "</tbody>
			<tfoot>
				<tr>
					<td colspan='3'>Total Students: ".$total_students."</td>
				</tr>
			</tfoot>
		</table>";
	} else {
		echo "</tbody>
			<tfoot>
				<tr>
					<td colspan='3'>No students found.</td>
				</tr>
			</tfoot>
		</table>";
	}
}
?>
