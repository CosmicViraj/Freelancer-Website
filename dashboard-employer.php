<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    header("Location: login.html");
    exit();
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

$employer_id = $_SESSION['user_id'];

// Fetch jobs posted by the employer
$jobs_sql = "SELECT * FROM jobs WHERE employer_id = $employer_id";
$jobs_result = $conn->query($jobs_sql);

// Fetch bids for employer's jobs
$bids_sql = "SELECT a.*, u.name AS freelancer_name, j.title AS job_title
             FROM applications a
             JOIN jobs j ON a.job_id = j.id
             JOIN users u ON a.freelancer_id = u.id
             WHERE j.employer_id = $employer_id";
$bids_result = $conn->query($bids_sql);

if (!$bids_result) {
    die("Query Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Employer Dashboard</h1>
            <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
        </div>

        <!-- Post a New Job Section -->
        <div class="mb-10">
            <h2 class="text-xl font-semibold mb-4">Post a New Job</h2>
            <form action="post_job.php" method="POST" class="bg-white p-6 rounded-lg shadow space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Job Title</label>
                    <input type="text" name="title" required class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" required class="w-full p-2 border rounded"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Budget ($)</label>
                    <input type="number" name="budget" required class="w-full p-2 border rounded" min="1">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Category</label>
                    <select name="category" required class="w-full p-2 border rounded">
                        <option value="">-- Select Category --</option>
                        <option value="Web Development">Web Development</option>
                        <option value="Design">Design</option>
                        <option value="Writing">Writing</option>
                        <option value="Data Entry">Data Entry</option>
                        <option value="Marketing">Marketing</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Post Job
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Posted Jobs Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">My Job Posts</h2>
                <div class="space-y-4">
                    <?php if ($jobs_result->num_rows > 0): ?>
                        <?php while ($job = $jobs_result->fetch_assoc()): ?>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold"><?= htmlspecialchars($job['title']) ?></h3>
                            <p class="text-gray-600 mb-2">Budget: $<?= number_format($job['budget'], 2) ?></p>
                            <p class="mb-2">Category: <?= htmlspecialchars($job['category']) ?></p>
                            <p class="mb-4"><?= htmlspecialchars($job['description']) ?></p>
                            <p class="text-sm text-gray-500">Status: <?= ucfirst($job['status']) ?></p>
                            <a href="workspace/workspace.php?job_id=<?= $job['id'] ?>" class="text-blue-500 hover:underline inline-block">
                                <i class="fas fa-door-open"></i> Open Workspace
                            </a>
                            <form action="delete_job.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this job?');">
    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
        Delete
    </button>
</form>

                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-600">No jobs posted yet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bids Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">Freelancer Bids</h2>
                <div class="space-y-4">
                    <?php if ($bids_result->num_rows > 0): ?>
                        <?php while ($bid = $bids_result->fetch_assoc()): ?>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold"><?= htmlspecialchars($bid['job_title']) ?></h3>
                            <p class="text-gray-600">Freelancer: <?= htmlspecialchars($bid['freelancer_name']) ?></p>
                            <p class="text-gray-600">Bid Amount: $<?= number_format($bid['bid_amount'], 2) ?></p>
                            <p class="text-sm text-gray-500">Proposal: <?= htmlspecialchars($bid['proposal']) ?></p>
                            <p class="text-sm text-gray-500">Status: <?= ucfirst($bid['status']) ?></p>
                            <form action="manage_bid.php" method="POST" class="mt-2">
                                <input type="hidden" name="bid_id" value="<?= $bid['id'] ?>">
                                <button type="submit" name="action" value="accept" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Accept</button>
                                <button type="submit" name="action" value="delete" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-600">No bids received yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
