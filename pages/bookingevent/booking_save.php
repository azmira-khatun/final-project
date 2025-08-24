<?php
include("config.php");

// Delete handle
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM booking WHERE id = $delete_id");
    // Redirect to avoid resubmission
    header("Location: view_booking.php");
    exit();
}

// Booking লিস্ট fetch করা
$bookings = $conn->query("
    SELECT b.id, b.event_id, e.event_name, b.venue_id, v.name AS venue_name, b.date, 
           b.customer_name, b.gmail, b.contact_number, b.address
    FROM booking b
    JOIN event e ON b.event_id = e.id
    JOIN venue v ON b.venue_id = v.id
    ORDER BY b.id DESC
");
?>

<div class="content-wrapper">
    <section class="content-header"><h1>All Bookings</h1></section>
    <section class="content">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h3 class="card-title">Booking List</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event</th>
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
                        <?php if($bookings->num_rows > 0): ?>
                            <?php while($b = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $b['id']; ?></td>
                                    <td><?php echo htmlspecialchars($b['event_name']); ?></td>
                                    <td><?php echo htmlspecialchars($b['venue_name']); ?></td>
                                    <td><?php echo $b['date']; ?></td>
                                    <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                                    <td><?php echo htmlspecialchars($b['gmail']); ?></td>
                                    <td><?php echo htmlspecialchars($b['contact_number']); ?></td>
                                    <td><?php echo htmlspecialchars($b['address']); ?></td>
                                    <td>
                                        <a href="edit_booking.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="view_booking.php?delete_id=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this booking?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">No bookings found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
