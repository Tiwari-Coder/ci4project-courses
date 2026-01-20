<h2>Add Term</h2>

<form method="post" action="<?= site_url('admin/terms/store') ?>">
    <?= csrf_field() ?> 

    <div>
        <label>New Term Type (optional)</label><br>
        <input type="text" id="newTaxonomy" name="new_taxonomy_label" placeholder="e.g. Course">
    </div>

    <div style="margin-top:10px;">
        <label>Term Name</label><br>
        <input type="text" name="name" required>
    </div>

    <div style="margin-top:10px;">
        <label>Term Type</label><br>
        <select name="taxonomy" id="taxonomySelect">
            <option value="">Select</option>
            <option value="license_type">License Type</option>
            <option value="further_training">Further Training</option>
            <option value="level">Level</option>
        </select>
    </div>

    <div style="margin-top:15px;">
        <button type="submit">Save Term</button>
    </div>
</form>

<script>
document.getElementById('newTaxonomy').addEventListener('blur', function () {
    const inputVal = this.value.trim();
    if (!inputVal) return;

    const slug = inputVal.toLowerCase().replace(/\s+/g, '_');
    const select = document.getElementById('taxonomySelect');

    // check if already exists
    let exists = false;
    for (let opt of select.options) {
        if (opt.value === slug) {
            exists = true;
            opt.selected = true;
            break;
        }
    }

    // if not exists, add new option
    if (!exists) {
        const option = document.createElement('option');
        option.value = slug;
        option.text  = inputVal;
        option.selected = true;
        select.appendChild(option);
    }
});
</script>
