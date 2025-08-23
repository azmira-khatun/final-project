<?php
include("config.php");

$msg = "";

// GET থেকে event id নিন
$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Auto-filled variables
$event_name = $venue_name = $date = $venue_id = "";

// Event info fetch
if ($event_id > 0) {
    $sql = "SELECT e.event_name, e.date, e.venue_id, v.name 
            FROM event e
            JOIN venue v ON e.venue_id = v.id
            WHERE e.id = '$event_id' LIMIT 1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $event_name = $row['event_name'];
        $venue_name = $row['name'];
        $venue_id = $row['venue_id'];
        $date = $row['date'];
    }
}

// Booking submit handle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $gmail = mysqli_real_escape_string($conn, $_POST['gmail']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    $sql = "INSERT INTO booking 
            (event_id, event_name, venue_id,name, date, customer_name, gmail, contact_number, address, created_at)
            VALUES
            ('$event_id','$event_name','$venue_id','$venue_name','$date','$customer_name','$gmail','$contact_number','$address', NOW())";

    if ($conn->query($sql) === TRUE) {
        $msg = "<div class='alert alert-success'>Booking confirmed!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

// Fetch all bookings for table
$bookings = $conn->query("SELECT * FROM booking ORDER BY id DESC");
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>Booking Form</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-body">
                <?php echo $msg; ?>

                <form action="" method="post">

                    <div class="form-group">
                        <label>Event Name</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($event_name); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Venue</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($venue_name); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($date); ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="gmail" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Number</label>
                        <input type="text" name="contact_number" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Confirm Booking</button>
                </form>
            </div>
        </div>

        <!-- Booking Table -->
        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h3 class="card-title">All Bookings</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
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
                        if($bookings->num_rows > 0){
                            while($b = $bookings->fetch_assoc()){
                                echo "<tr>
                                    <td>{$b['id']}</td>
                                    <td>{$b['event_name']}</td>
                                    <td>{$b['venue_name']}</td>
                                    <td>{$b['date']}</td>
                                    <td>{$b['customer_name']}</td>
                                    <td>{$b['gmail']}</td>
                                    <td>{$b['contact_number']}</td>
                                    <td>{$b['address']}</td>
                                    <td>
                                        <a href='edit_booking.php?id={$b['id']}' class='btn btn-sm btn-primary'>Edit</a>
                                        <a href='delete_booking.php?id={$b['id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure?')\">Delete</a>
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
