<h2>Add Course</h2>

<form method="post" action="<?= site_url('admin/courses/store') ?>">
    <?= csrf_field() ?>

    <div>
        <label>Course Title</label><br>
        <input type="text" name="title" required>
    </div>

    <div style="margin-top:10px;">
        <label>Course Content</label><br>
        <textarea name="content" rows="4"></textarea>
    </div>

    <?php foreach($groupedTerms as $taxonomy => $terms): ?>
        <h3 style="margin-top:20px;">
            <?= ucfirst(str_replace('_',' ',$taxonomy)) ?>
        </h3>
        <?php foreach($terms as $term): ?>
            <label>
                <input type="checkbox" name="terms[]" value="<?= $term['id'] ?>">
                <?= esc($term['name']) ?>
            </label><br>
        <?php endforeach; ?>
    <?php endforeach; ?>

    <div style="margin-top:20px;">
        <button type="submit">Save Course</button>
    </div>
</form>
