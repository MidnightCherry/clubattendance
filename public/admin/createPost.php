
<?php
include '../dbFunctions.php';
include 'adminHeader.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to create a post.');window.location.href='login.php';</script>";
    exit();
}

// 
// Fetch flagged keywords for automatic flagging
$keywords_query = "SELECT keyword FROM flag_keywords";
$flagged_keywords_result = mysqli_query($link, $keywords_query);
$flagged_keywords = [];
while ($keyword_row = mysqli_fetch_assoc($flagged_keywords_result)) {
    $flagged_keywords[] = $keyword_row['keyword'];
}
Process the post creation form
if (isset($_POST['title']) && isset($_POST['content'])) {
    $title = mysqli_real_escape_string($link, $_POST['title']);
    $content = mysqli_real_escape_string($link, $_POST['content']);
    $user_id = $_SESSION['user_id'];
    
// Check if post content contains flagged keywords
$flagged = 0;  // Not flagged by default
foreach ($flagged_keywords as $keyword) {
    if (strpos($content, $keyword) !== false) {
        $flagged = 1;  // Flag the post if a keyword is found
        break;
    }
}
$query = "INSERT INTO forum_posts (title, content, author_id, flagged) VALUES ('$title', '$content', $user_id, $flagged)";

    mysqli_query($link, $query);
    echo "<script>alert('Post created successfully!');window.location.href='forumLanding.php';</script>";
}
?>
<div class="container mt-4">
    <h2>Create a New Post</h2>
    <form method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Publish Post</button>
    </form>
</div>
<?php
include 'adminFooter.php';
?>
