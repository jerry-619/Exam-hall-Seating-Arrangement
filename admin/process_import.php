<?php
// Include the autoload file from PhpSpreadsheet library
require 'vendor/autoload.php';

// Use the classes from PhpSpreadsheet library
use PhpOffice\PhpSpreadsheet\IOFactory;

include "../db.php";
session_start();

// Check if a file was uploaded
if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
    // Get the uploaded file
    $file = $_FILES['excel_file']['tmp_name'];

    // Load the Excel file
    $spreadsheet = IOFactory::load($file);

    // Get the first worksheet in the Excel file
    $worksheet = $spreadsheet->getActiveSheet();

    // Loop through each row in the worksheet
    foreach ($worksheet->getRowIterator() as $row) {
        // Get cell values from the current row
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        // Insert data into the student database
        $name = $data[0];
        $rollno = $data[1];
        $password = $data[2];
        $class = $data[3]; // Assuming class data is provided in the Excel file

        // Perform SQL insertion query
        $insert = "insert into students(name,password, class, rollno) VALUES ('$name','$password','$class', '$rollno')";

        // Execute insertion query
        $insert_query = mysqli_query($conn, $insert);

        if ($insert_query) {
        	$_SESSION['student'] = "Excel file imported successfully.";
	}
	else{
		$_SESSION['studentnot'] = "Error!! not imported.";
	}
	header("Location: add_student.php");
    }


    // Redirect back to the admin dashboard or display a success message
    // header("Location: admin_dashboard.php");
    // exit();
} else {
    // No file uploaded or an error occurred during upload
    // Handle the error as needed
    echo "Error: File upload failed.";
}
