<!doctype html>
<html>

<head>
    <title>IOT Component Manager</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>

<body>
    <div id="header">
        <h1>IOT Component Manager</h1>
        <button id="add-component-button">Add Component</button>
        <button id="export-decharge-button">Export Decharge</button>
        <a id="add-component-button" href="logout.php" style="background-color: red;">Logout</a>
    </div>
    <div class="container">
        <form id="search_form" style="display: inline;">
            <label for="search" id="search_label">Search: </label>
            <input type="text" name="search-query" id="search_query">
        </form>
        <form id="filter_status_form">
            <label for="filter_status" id="filter_status_label">Filter by status: </label>
            <select id="filter_status" name="filter_status">
                <option value="all">All</option>
                <option value="available">Available</option>
                <option value="broken">Broken</option>
                <option value="lost">Lost</option>
            </select>
        </form>
        <form id="filter_date_form">
            <label id="filter_date_label">Filter by date: </label>
            <label for="filter_date" id="filter_date_label">From: </label>
            <input type="date" id="filter_date_from" name="filter_date_from">
            <label for="filter_date" id="filter_date_label">To: </label>
            <input type="date" id="filter_date_to" name="filter_date_to">
        </form>
        <button id="reset_filters" onclick="resetFiltersBtn()">Reset Filters</button>
        <button id="downloadBtn">Export Data</button>
    </div>
    <div id="component-list">
        <?php
        // Include config file
        require __DIR__ . '/connection_db.php';
        $conn = connect_to_db();

        // Attempt select query execution
        $sql = "SELECT * FROM components";
        echo '<table id="result_table">';
        echo '<thead>';
        echo '    <tr>';
        echo '      <th onclick="sortTable(0, this)">Name<span class="fa fa-sort"></span></th>';
        echo '      <th onclick="sortTable(1, this)">Purchase Date<span class="fa fa-sort"></span></th>';
        echo '      <th onclick="sortTable(2, this)">Status<span class="fa fa-sort"></span></th>';
        echo '      <th onclick="sortTable(3, this)">Quantity<span class="fa fa-sort"></span></th>';
        echo '      <th>Image<span class="fa fa-sort" style="display:none;"></span></th>';
        echo '      <th>Actions<span class="fa fa-sort" style="display:none;"></span></th>';
        echo '    </tr>';
        echo '</thead>';
        echo '<tbody id="result_tbody">';
        if ($result = $conn->query($sql)) {
            if ($result->rowCount() > 0) {
                while ($row = $result->fetch()) {
                    echo '  <tr>';
                    echo '    <td>' . $row->name . '</td>';
                    echo '    <td>' . $row->purchase_date . '</td>';
                    echo '    <td><span class="status ' . $row->status . '">' . ucfirst($row->status) . '</span></td>';
                    echo '    <td>' . $row->quantity . '</td>';
                    echo '    <td><a href="' . $row->image . '" class="btn btn-primary"><i class="fa fa-image"></i> View Image</a></td>';
                    echo '    <td>';
                    echo '       <a class="edits" data-id="' . $row->id . '"  class="btn btn-secondary"><i class="fa fa-edit"></i> Edit</a>';
                    echo '      <a  class="btn btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                    echo '    </td>';
                    echo '  </tr>';
                }
            } else {
                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
        echo '</tbody>';
        echo '</table>';

        // Close connection
        unset($conn);
        ?>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal" style="display: none;">

        <!-- Modal content -->
        <div class="modal-content">
            <form id="add-component-form" enctype="multipart/form-data">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <h2>Component</h2>
                </div>
                <div class="modal-body">
                    <div id="add-component-form-div">
                        <label for="name">Name:</label><br>
                        <input type="text" required id="name" name="name"><br><br>
                        <input type="text" id="id" name="id" hidden>
                        <input type="text" id="action" name="action" hidden>
                        <label for="purchase-date">Purchase Date:</label><br>
                        <input type="date" required id="purchase-date" name="purchase-date"><br><br>
                        <label for="quantity">Quantity:</label><br>
                        <input type="number" required id="quantity" name="quantity"><br><br>
                        <label for="status">Status:</label><br>
                        <select id="status" required name="status">
                            <option value="available">Available</option>
                            <option value="broken">Broken</option>
                            <option value="lost">Lost</option>
                        </select>
                        <div id="current_image" style="display: none;">
                        </div>
                        <br><br>
                        <label for="image">Image:</label>
                        <input type="file" required id="image" name="image"><br><br><br>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="Submit">
                    <!-- <h3>Modal Footer</h3> -->
                </div>
            </form>
        </div>

    </div>
    <div id="dechargeModal" class="modal" style="display: none;">

        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>Create Decharge</h2>
            </div>
            <div class="modal-body">
                <div id="add-component-form-div">
                    <label for="name">Student Name:</label><br>
                    <input type="text" required id="student_name" name="student_name"><br><br>

                    <?php
                    $conn = connect_to_db();
                    error_reporting(-1);
                    ini_set('display_errors', 'On');
                    

                    // Attempt select query execution
                    $sql = "SELECT * FROM components";

                    if ($result = $conn->query($sql)) {
                        if ($result->rowCount() > 0) {
                            while ($row = $result->fetch()) {
                                echo '  <input class="messageCheckbox" name="device_' . $row->id . '" id="device_' . $row->id . '" type="checkbox" value=' . $row->id . '>';
                                echo '  <label for="device_' . $row->id . '" style="display:inline;"> ' . $row->name . '</label><br>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <input id="wordExportBtn" type="submit" value="Submit">
            </div>
        </div>

    </div>

    <div id="messages">
        <div class="success-msg">
            <i class="fa fa-check"></i>
            This is a success message.
        </div>

        <div class="error-msg">
            <i class="fa fa-times-circle"></i>
            This is a error message.
        </div>
    </div>

    <script src="script.js"></script>
</body>

</html>