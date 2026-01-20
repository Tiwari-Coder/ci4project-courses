<h2>Manage Courses</h2>

<!-- Add / Edit Course Form -->
<h3>Add New Course</h3>
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
        <h4 style="margin-top:20px;"><?= ucfirst(str_replace('_',' ',$taxonomy)) ?></h4>
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

<hr style="margin:30px 0;">

<!-- Courses List with Edit/Delete -->
<h3>All Courses</h3>
<table border="1" cellpadding="6" style="border-collapse: collapse;">
    <tr>
        <th>Title</th>
        <th>Content</th>
        <th>Terms</th>
        <th>Action</th>
    </tr>

    <?php foreach($courses as $course): ?>
        <tr>
            <td><?= esc($course['title']) ?></td>
            <td><?= esc($course['content']) ?></td>
            <td>
                <?php
                $labels = [];
                foreach($course['terms'] as $term){
                    $labels[] = esc($term['name'])." (".esc($term['taxonomy']).")";
                }
                echo implode(', ', $labels);
                ?>
            </td>
            <td>
                <a href="<?= site_url('admin/courses/edit/'.$course['id']) ?>">Edit</a> |
                <a href="<?= site_url('admin/courses/delete/'.$course['id']) ?>"
                   onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
