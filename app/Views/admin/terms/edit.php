<h2>Edit Term</h2>

<form method="post" action="<?= site_url('admin/terms/update/'.$term['id']) ?>">
    <?= csrf_field() ?>

    <div>
        <label>Term Name</label><br>
        <input type="text" name="name" value="<?= esc($term['name']) ?>" required>
    </div>

    <div style="margin-top:10px;">
        <label>Term Type</label><br>
        <select name="taxonomy" required>
            <option value="license_type" <?= $term['taxonomy']=='license_type'?'selected':'' ?>>License Type</option>
            <option value="further_training" <?= $term['taxonomy']=='further_training'?'selected':'' ?>>Further Training</option>
            <option value="level" <?= $term['taxonomy']=='level'?'selected':'' ?>>Level</option>
        </select>
    </div>

    <button style="margin-top:15px;">Update</button>
</form>
