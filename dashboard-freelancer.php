<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'freelancer') {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Withdraw application (if requested)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdraw_id'])) {
    $withdraw_id = intval($_POST['withdraw_id']);
    $conn->query("DELETE FROM applications WHERE id = $withdraw_id AND freelancer_id = $user_id");
}

// Handle job search
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_condition = $search ? "AND (j.title LIKE '%$search%' OR j.description LIKE '%$search%')" : '';

// Fetch available jobs not applied to by the freelancer
$jobs_sql = "SELECT j.*, u.name as employer_name 
             FROM jobs j 
             JOIN users u ON j.employer_id = u.id 
             WHERE j.status = 'active'
             AND j.id NOT IN (
                 SELECT job_id FROM applications WHERE freelancer_id = $user_id
             )
             $search_condition";
$jobs_result = $conn->query($jobs_sql);

// Fetch user's applications
$applications_sql = "SELECT a.*, j.title as job_title, j.id as job_id 
                     FROM applications a
                     JOIN jobs j ON a.job_id = j.id
                     WHERE a.freelancer_id = $user_id";
$applications_result = $conn->query($applications_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freelancer Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold">Freelancer Dashboard</h1>
            <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
        </div>

        <!-- Search bar -->
        <form method="GET" class="mb-6">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" 
                   class="w-full md:w-1/2 px-4 py-2 border rounded" 
                   placeholder="Search jobs by title or description">
            <button type="submit" 
                    class="bg-blue-500 text-white px-4 py-2 ml-2 rounded hover:bg-blue-600">
                Search
            </button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Available Jobs Section -->
            <div class="md:col-span-2">
                <h2 class="text-xl font-semibold mb-4">Available Jobs</h2>
                <div class="space-y-4">
                    <?php if ($jobs_result->num_rows > 0): ?>
                        <?php while($job = $jobs_result->fetch_assoc()): ?>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold"><?= htmlspecialchars($job['title']) ?></h3>
                            <p class="text-gray-600 mb-2">Posted by: <?= htmlspecialchars($job['employer_name']) ?></p>
                            <p class="text-gray-600 mb-2">Budget: $<?= number_format($job['budget'], 2) ?></p>
                            <p class="mb-4"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                            <form action="apply.php" method="POST">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                <textarea name="proposal" required 
                                          class="w-full p-2 border rounded mb-2" 
                                          placeholder="Write your proposal..."></textarea>
                                <button type="submit" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Apply
                                </button>
                            </form>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-600">No matching jobs found or you’ve applied to all available jobs.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- My Applications Section -->
            <div>
                <h2 class="text-xl font-semibold mb-4">My Applications</h2>
                <div class="space-y-4">
                    <?php if ($applications_result->num_rows > 0): ?>
                        <?php while($app = $applications_result->fetch_assoc()): ?>
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h3 class="font-semibold"><?= htmlspecialchars($app['job_title']) ?></h3>
                            <p class="text-gray-600 mb-2">
                                Status: 
                                <span class="<?=
                                    $app['status'] === 'accepted' ? 'text-green-500' :
                                    ($app['status'] === 'rejected' ? 'text-red-500' : 'text-yellow-500')
                                ?>">
                                    <?= ucfirst($app['status']) ?>
                                </span>
                            </p>
                            <p class="text-sm text-gray-500 mb-2">Proposal: <?= htmlspecialchars($app['proposal']) ?></p>

                            <?php if ($app['status'] === 'accepted'): ?>
                            <a href="workspace/workspace.php?job_id=<?= $app['job_id'] ?>" 
                               class="text-blue-500 hover:underline inline-block mt-2">
                                <i class="fas fa-door-open"></i> Open Workspace
                            </a>
                            <?php endif; ?>

                            <?php if ($app['status'] === 'pending'): ?>
                            <form method="POST" class="mt-2">
                                <input type="hidden" name="withdraw_id" value="<?= $app['id'] ?>">
                                <button type="submit" 
                                        class="text-sm text-red-500 hover:underline">
                                    Withdraw Application
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-gray-600">You haven't applied to any jobs yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
