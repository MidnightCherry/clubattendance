
<?php
include '../dbFunctions.php';
include 'adminHeader.php';

// Add flagged keyword
if (isset($_POST['keyword'])) {
    $keyword = mysqli_real_escape_string($link, $_POST['keyword']);
    $query = "INSERT INTO flag_keywords (keyword) VALUES ('$keyword')";
    mysqli_query($link, $query);
}

// Fetch existing flagged keywords
$query = "SELECT * FROM flag_keywords";
$flagged_keywords = mysqli_query($link, $query);
?>
<div class="container mt-4">
    <h2>Manage Flagged Keywords</h2>
    <p>Set keywords that will automatically flag a post for review.</p>

    <!-- Input field for adding flagged keywords -->
    <form method="post">
        <div class="mb-3">
            <label for="keyword" class="form-label">Keyword/Phrase</label>
            <input type="text" class="form-control" id="keyword" name="keyword" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Keyword</button>
    </form>

    <!-- Display table of flagged keywords -->
    <h3 class="mt-4">Current Flagged Keywords</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">Keyword/Phrase</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($keyword = mysqli_fetch_assoc($flagged_keywords)): ?>
            <tr>
                <td><?php echo $keyword['keyword']; ?></td>
                <td>
                    <a href="removeKeyword.php?keyword_id=<?php echo $keyword['keyword_id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php
include 'adminFooter.php';
?>
