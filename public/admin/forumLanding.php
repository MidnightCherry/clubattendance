
<?php
include '../dbFunctions.php';
// Approve a flagged post
if (isset($_GET['approve_post_id'])) {
    $approve_post_id = $_GET['approve_post_id'];
    $query = "UPDATE forum_posts SET flagged = 0 WHERE post_id = $approve_post_id";  // Remove the flag
    mysqli_query($link, $query);
    echo "<script>alert('Post approved successfully!');window.location.href='forumLanding.php';</script>";
}

// Remove a flagged post
if (isset($_GET['remove_post_id'])) {
    $remove_post_id = $_GET['remove_post_id'];
    $query = "DELETE FROM forum_posts WHERE post_id = $remove_post_id";  // Delete the post
    mysqli_query($link, $query);
    echo "<script>alert('Post removed successfully!');window.location.href='forumLanding.php';</script>";
}

// Suspend a user
if (isset($_GET['suspend_user_id'])) {
    $suspend_user_id = $_GET['suspend_user_id'];
    $query = "UPDATE users SET suspended = 1 WHERE user_id = $suspend_user_id";  // Set the user as suspended
    mysqli_query($link, $query);
    echo "<script>alert('User suspended successfully!');window.location.href='forumLanding.php';</script>";
}

include 'adminHeader.php';

// Fetch recent posts for display
$query = "SELECT * FROM forum_posts ORDER BY timestamp DESC LIMIT 10";
$recent_posts = mysqli_query($link, $query);
?>
<div class="container mt-4">
    <h2>Welcome to the Club Attendance Forum</h2>
    <p>Engage with the community, share your thoughts, and ask questions.</p>
    
    <!-- Display prompt for unregistered users -->
    <?php if (!isset($_SESSION['user_id'])): ?>
    <div class="alert alert-info">
        <strong>Want to participate?</strong> Please <a href="login.php">log in</a> or <a href="signup.php">register</a>.
    </div>
    <?php endif; ?>

    <!-- Display post creation option for registered users -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="mb-3">
        <a href="createPost.php" class="btn btn-primary">Create New Post</a>
    </div>
    <?php endif; ?>

    <!-- Display list of recent posts -->
    <h3>Recent Posts</h3>
    <div class="list-group">
        <?php while ($post = mysqli_fetch_assoc($recent_posts)): ?>
        <a href="viewPost.php?post_id=<?php echo $post['post_id']; ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?php echo $post['title']; ?></h5>
                <small><?php echo $post['timestamp']; ?></small>
            </div>
            <p class="mb-1"><?php echo substr($post['content'], 0, 100) . '...'; ?></p>
        </a>
        <?php endwhile; ?>
    </div>
</div>
<?php


<!-- Fetch flagged posts for review -->
<?php
$flagged_posts_query = "SELECT * FROM forum_posts WHERE flagged = 1";  // Example query, adjust based on actual database structure
$flagged_posts = mysqli_query($link, $flagged_posts_query);

// Fetch users for potential suspension (this can be based on number of flagged posts or other criteria)
$suspend_users_query = "SELECT * FROM users WHERE flagged_count > 5";  // Example query, adjust based on actual database structure
$suspend_users = mysqli_query($link, $suspend_users_query);
?>
<!-- Admin/Moderator Modal for Reviewing Flagged Posts and Suspending Users -->
<?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'moderator')): ?>
<div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="adminModalLabel">Review & Action Center</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
        <!-- Content for reviewing flagged posts and suspending users -->
        <h6>Flagged Posts</h6>
        <!-- Display a list of flagged posts with options to approve or remove -->
        <div class="list-group mb-4">
            <?php while ($post = mysqli_fetch_assoc($flagged_posts)): ?>
            <div class="list-group-item">
                <h5 class="mb-1"><?php echo $post['title']; ?></h5>
                <p class="mb-1"><?php echo substr($post['content'], 0, 100) . '...'; ?></p>
                <!-- Example action buttons, these should be linked to actual actions -->
                <button class="btn btn-success btn-sm">Approve</button>
                <button class="btn btn-danger btn-sm">Remove</button>
            </div>
            <?php endwhile; ?>
        </div>
        <h6>Suspend Users</h6>
        <!-- Display a list of users with options to suspend -->
        <div class="list-group">
            <?php while ($user = mysqli_fetch_assoc($suspend_users)): ?>
            <div class="list-group-item">
                <p class="mb-1"><?php echo $user['username']; ?></p>
                <!-- Example action button, this should be linked to an actual action -->
                <button class="btn btn-warning btn-sm">Suspend</button>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- This can be further expanded based on specific requirements -->
        <h6>Flagged Posts</h6>
        <!-- Display a list of flagged posts with options to approve or remove -->
        <!-- ... -->
        <h6>Suspend Users</h6>
        <!-- Display a list of users with options to suspend -->
        <!-- ... -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Button to open the modal -->
<div class="mt-4 text-end">
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#adminModal">Admin/Moderator Actions</button>
</div>
<?php endif; ?>
include 'adminFooter.php';
?>
