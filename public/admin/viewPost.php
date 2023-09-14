
<?php
include '../dbFunctions.php';
include 'adminHeader.php';

// Fetch the post details based on the post_id from the URL
$post_id = $_GET['post_id'];
$query = "SELECT * FROM forum_posts WHERE post_id = $post_id";
$post = mysqli_fetch_assoc(mysqli_query($link, $query));

// Fetch the comments for the post
$query = "SELECT * FROM forum_comments WHERE post_id = $post_id ORDER BY timestamp DESC";
$comments = mysqli_query($link, $query);
?>
<div class="container mt-4">
    <h2><?php echo $post['title']; ?></h2>
    <p class="text-muted">Posted by <?php echo $post['author_id']; ?> on <?php echo $post['timestamp']; ?></p>
    <p><?php echo $post['content']; ?></p>
    
    <!-- Section to display comments -->
    <h3 class="mt-4">Comments</h3>
    <div class="list-group">
        <?php while ($comment = mysqli_fetch_assoc($comments)): ?>
        <div class="list-group-item">
            <p class="mb-1"><?php echo $comment['content']; ?></p>
            <small class="text-muted">Posted by <?php echo $comment['author_id']; ?> on <?php echo $comment['timestamp']; ?></small>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Section for users to add comments -->
    <?php if (isset($_SESSION['user_id'])): ?>
    <h3 class="mt-4">Add a Comment</h3>
    <form method="post">
        <div class="mb-3">
            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Post Comment</button>
    </form>
    <?php endif; ?>
</div>
<?php
include 'adminFooter.php';
?>
