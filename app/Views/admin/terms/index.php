<h2>All Terms</h2>

<a href="<?= site_url('admin/terms/create') ?>">➕ Add New Term</a>

<table border="1" cellpadding="8" cellspacing="0" style="margin-top:15px; border-collapse: collapse; width:60%;">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Taxonomy</th>
        <th>Action</th>
    </tr>

    <?php if (!empty($terms)): ?>
        <?php foreach ($terms as $term): ?>
            <tr>
                <td><?= esc($term['id']) ?></td>
                <td><?= esc($term['name']) ?></td>
                <td><?= esc(ucwords(str_replace('_',' ', $term['taxonomy']))) ?></td>
                <td>
                    <a href="<?= site_url('admin/terms/edit/' . $term['id']) ?>"> Edit</a>
                    |
                    <a href="<?= site_url('admin/terms/delete/' . $term['id']) ?>"
                       onclick="return confirm('Are you sure you want to delete this term?')">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" align="center">No terms found</td>
        </tr>
    <?php endif; ?>
</table>
