<?php
session_start();
require 'db_connect.php';

// Check if the user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.html");
    exit();
}

$employer_id = $_SESSION['user_id'];

// Fetch jobs posted by the employer
$jobs_sql = "SELECT * FROM jobs WHERE employer_id = $employer_id";
$jobs_result = $conn->query($jobs_sql);

// Fetch bids (applications) for employer's jobs
$bids_sql = "SELECT a.*, u.name AS freelancer_name, j.title AS job_title
             FROM applications a
             JOIN jobs j ON a.job_id = j.id
             JOIN users u ON a.freelancer_id = u.id
             WHERE j.employer_id = $employer_id";

$bids_result = $conn->query($bids_sql);

// Check if the query executed successfully
if (!$bids_result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Employer Dashboard</h1>
            <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Posted Jobs Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">My Job Posts</h2>
                <div class="space-y-4">
                    <?php while ($job = $jobs_result->fetch_assoc()): ?>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($job['title']) ?></h3>
                        <p class="text-gray-600 mb-2">Budget: $<?= number_format($job['budget'], 2) ?></p>
                        <p class="mb-4"><?= htmlspecialchars($job['description']) ?></p>
                        <p class="text-sm text-gray-500">Status: <?= ucfirst($job['status']) ?></p>

                        <!-- Virtual Workspace Link -->
                        <a href="workspace/workspace.php?job_id=<?= $job['id'] ?>" class="text-blue-500 hover:underline mt-2 inline-block">
                            <i class="fas fa-door-open"></i> Open Workspace
                        </a>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Bids Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Freelancer Bids</h2>
                <div class="space-y-4">
                    <?php while ($bid = $bids_result->fetch_assoc()): ?>
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h3 class="font-semibold"><?= htmlspecialchars($bid['job_title']) ?></h3>
                        <p class="text-gray-600">Freelancer: <?= htmlspecialchars($bid['freelancer_name']) ?></p>
                        <p class="text-gray-600">Bid Amount: $<?= number_format($bid['bid_amount'], 2) ?></p>
                        <p class="text-sm text-gray-500">Proposal: <?= htmlspecialchars($bid['proposal']) ?></p>
                        <p class="text-sm text-gray-500">Status: <?= ucfirst($bid['status']) ?></p>

                        <!-- Accept or Delete Bid Form -->
                        <form action="manage_bid.php" method="POST" class="mt-2">
                            <input type="hidden" name="bid_id" value="<?= $bid['id'] ?>">
                            <button type="submit" name="action" value="accept" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Accept</button>
                            <button type="submit" name="action" value="delete" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                        </form>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
