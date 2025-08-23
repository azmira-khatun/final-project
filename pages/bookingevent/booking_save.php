<?php
include("config.php");

// Delete Booking handle
$msg = "";
if (isset($_POST["btnDelete"])) {
    $b_id = mysqli_real_escape_string($conn, $_POST["txtId"]);
    $sql_del = "DELETE FROM booking WHERE id='$b_id'";
    if ($conn->query($sql_del) === TRUE) {
        $msg = "<div class='alert alert-success'>Booking deleted successfully.</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error deleting booking: " . $conn->error . "</div>";
    }
}

// Fetch all bookings
$bookings = $conn->query("
    SELECT * 
    FROM booking 
    ORDER BY id DESC
");
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>All Bookings</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <?php echo $msg; ?>

                <table class="table table-bordered table-striped">
                    <thead class="bg-info text-white">
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Date</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($bookings && $bookings->num_rows > 0) {
                            while ($row = $bookings->fetch_assoc()) {
                                $id = $row['id'];
                                echo "<tr>
                                        <td>{$id}</td>
                                        <td>{$row['event_name']}</td>
                                        <td>{$row['venue_name']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['customer_name']}</td>
                                        <td>{$row['gmail']}</td>
                                        <td>{$row['contact_number']}</td>
                                        <td>{$row['address']}</td>
                                        <td>
                                            <div class='d-flex'>
                                                <form method='post' onsubmit='return confirm(\"Are you sure to delete this booking?\");'>
                                                    <input type='hidden' name='txtId' value='{$id}'>
                                                    <button type='submit' name='btnDelete' class='btn btn-danger btn-sm mr-1'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </form>
                                                <a href='booking_edit.php?id={$id}' class='btn btn-primary btn-sm'>
                                                    <i class='fas fa-edit'></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center'>No bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
