<h2>Edit Course</h2>

<form method="post" action="<?= site_url('admin/courses/update/'.$course['id']) ?>">
<?= csrf_field() ?>

<p>
    <label>Course Title</label><br>
    <input type="text" name="title" value="<?= esc($course['title']) ?>" required>
</p>

<p>
    <label>Course Content</label><br>
    <textarea name="content" rows="4"><?= esc($course['content']) ?></textarea>
</p>

<hr>

<?php
$grouped = [];
foreach ($terms as $t) {
    $grouped[$t['taxonomy']][] = $t;
}
?>

<?php foreach ($grouped as $taxonomy => $items): ?>
    <strong><?= strtoupper(str_replace('_',' ', $taxonomy)) ?></strong><br>

    <?php foreach ($items as $term): ?>
        <label>
            <input type="checkbox"
                   name="terms[]"
                   value="<?= $term['id'] ?>"
                   <?= in_array($term['id'], $selectedTerms) ? 'checked' : '' ?>>
            <?= esc($term['name']) ?>
        </label><br>
    <?php endforeach; ?>
    <br>
<?php endforeach; ?>

<button type="submit">Update Course</button>
</form>
