<html>

<head>
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="common.css">
    <?php include '../link.php' ?>
    <style type="text/css">
    </style>
</head>

<style>
.drop-container {
  position: relative;
  display: flex;
  gap: 10px;
  width: 35rem;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  height: auto;
  padding: 35px;
  border-radius: 10px;
  margin-left: 15rem;
  margin-top: 5rem;
  border: 2px dashed #555;
  color: #444;
  cursor: pointer;
  transition: background .2s ease-in-out, border .2s ease-in-out, box-shadow .2s ease-in-out; /* Added box-shadow to transition */
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Added drop shadow */
}

.drop-container:hover,
.drop-container.drag-active {
  background: #eee;
  border-color: #111;
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2); /* Change drop shadow on hover or drag */
}

.drop-container:hover .drop-title,
.drop-container.drag-active .drop-title {
  color: #222;
}

.drop-title {
  color: #444;
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  transition: color .2s ease-in-out;
}


input[type=file] {
  width: 350px;
  max-width: 100%;
  color: #444;
  padding: 5px;
  background: #fff;
  border-radius: 10px;
  border: 1px solid #555;
}

input[type=file]::file-selector-button {
  margin-right: 20px;
  border: none;
  background: #084cdf;
  padding: 10px 20px;
  border-radius: 10px;
  color: #fff;
  cursor: pointer;
  transition: background .2s ease-in-out;
}

input[type=file]::file-selector-button:hover {
  background: #0d45a5;
}
</style>

<body>

    <div class="wrapper">
        <nav id="sidebar">
            <div class="sidebar-header">
                <h4>DASHBOARD</h4>
            </div>
            <ul class="list-unstyled components">
                <li>
                    <a href="add_class.php"><img
                            src="https://img.icons8.com/ios-filled/26/ffffff/google-classroom.png" /> Classes</a>
                </li>
                <li>
                    <a href="add_student.php"><img
                            src="https://img.icons8.com/ios-filled/25/ffffff/student-registration.png" /> Students</a>
                </li>
                <li>
                    <a class="active_link" href="import_students.php"><img
                            src="https://img.icons8.com/ios-filled/25/ffffff/import.png" />
                        Import Students</a>
                </li>
                <li>
                    <a href="add_room.php"><img src="https://img.icons8.com/metro/25/ffffff/building.png" /> Rooms</a>
                </li>
                <li>
                    <a href="dashboard.php"><img src="https://img.icons8.com/nolan/30/ffffff/summary-list.png" />
                        Allotment</a>
                </li>
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png" />
                    </button><span class="page-name"> Import Students</span>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <img src="https://img.icons8.com/ios-filled/19/ffffff/menu--v3.png" />
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
            <div class="main-content">
                <form action="process_import.php" method="post" enctype="multipart/form-data">
                    <label for="file" class="drop-container" id="dropcontainer">
                        <span class="drop-title">Drop Excel File here</span>
                                              or
                        <input type="file" class="form-control-file" id="excel_file" name="excel_file" accept=".xls,.xlsx" required>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </label>  
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php' ?>
</body>

</html>
<script type="text/javascript">
   const dropContainer = document.getElementById("dropcontainer")
  const fileInput = document.getElementById("excel_file")

  dropContainer.addEventListener("dragover", (e) => {
    // prevent default to allow drop
    e.preventDefault()
  }, false)

  dropContainer.addEventListener("dragenter", () => {
    dropContainer.classList.add("drag-active")
  })

  dropContainer.addEventListener("dragleave", () => {
    dropContainer.classList.remove("drag-active")
  })

  dropContainer.addEventListener("drop", (e) => {
    e.preventDefault()
    dropContainer.classList.remove("drag-active")
    fileInput.files = e.dataTransfer.files
  })
</script>