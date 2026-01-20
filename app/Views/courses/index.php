<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>
    <style>
        .layout {
            display: flex;
            gap: 30px;
        }
        .sidebar {
            width: 260px;
            border-right: 1px solid #ddd;
            padding-right: 15px;
        }
        .course {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
        }
        h4 {
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>



<div class="layout">

    <!--  LEFT : Taxonomy Checkboxes -->
<div class="sidebar">

    <?php if (!empty($groupedTerms)): ?>

        <?php foreach ($groupedTerms as $taxonomy => $terms): ?>

            <h4><?= ucwords(str_replace('_', ' ', $taxonomy)) ?></h4>

            <?php foreach ($terms as $term): ?>
                <label>
                    <input type="checkbox"
                           class="term-checkbox"
                           name="terms[]"
                           value="<?= $term['id']; ?>">
                    <?= esc($term['name']); ?>
                </label><br>
            <?php endforeach; ?>

        <?php endforeach; ?>

    <?php else: ?>
        <p>No filters available</p>
    <?php endif; ?>

    <!-- <button type="button" id="reset-filters">Reset</button> -->

</div>


    <!--  RIGHT : Courses Content -->
    <div class="content" id="courses-content">
        <?= view('courses/partials/course_list', ['courses' => $courses]); ?>
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const coursesContainer = document.getElementById('courses-content');
    let currentView = 'grid';
    let currentPage = 1; // track current page

    function loadCourses(page = 1) {
        currentPage = page;

        const searchInput = document.getElementById('search-keyword');
        let cursorPos = searchInput ? searchInput.selectionStart : 0;
        let value = searchInput ? searchInput.value : '';

        // Build params
        let params = new URLSearchParams();
        document.querySelectorAll('.term-checkbox:checked').forEach(cb => {
            params.append('terms[]', cb.value);
        });

        const versionSelect = document.getElementById('filter-version');
        if (versionSelect && versionSelect.value) {
            params.append('filter_version', versionSelect.value);
        }

        if (value.trim() !== '') {
            params.append('keyword', value.trim());
        }

        params.append('page', page); // pagination

        // AJAX call
        fetch("<?= site_url('courses'); ?>?" + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            cache: 'no-store'
        })
        .then(res => res.text())
        .then(html => {
            coursesContainer.innerHTML = html;

            // restore value & cursor
            const input = document.getElementById('search-keyword');
            if(input){
                input.value = value;
                input.setSelectionRange(cursorPos, cursorPos);
            }

            applyView(currentView);
        });
    }

    function applyView(view) {
        const wrapper = document.getElementById('courses-wrapper');
        if (!wrapper) return;

        wrapper.classList.remove('grid-view', 'list-view');
        wrapper.classList.add(view + '-view');
    }

    //  Grid / List toggle
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('view-btn')) return;

        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        e.target.classList.add('active');

        currentView = e.target.dataset.view;
        applyView(currentView);
    });

    //  Checkbox filter
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('term-checkbox')) return;
        loadCourses(1); // reset to first page
    });

    //  Version filter
    document.addEventListener('change', function (e) {
        if (e.target.id !== 'filter-version') return;
        loadCourses(1); // reset to first page
    });

    // 🔍 SEARCH (with debounce)
    let searchTimer;
    document.addEventListener('input', function (e) {
        if (e.target.id !== 'search-keyword') return;

        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            loadCourses(1); // reset to first page
        }, 400);
    });

    //  Reset
    document.addEventListener('click', function (e) {
        if (e.target.id !== 'reset-filters') return;

        document.querySelectorAll('.term-checkbox').forEach(cb => cb.checked = false);

        const searchInput = document.getElementById('search-keyword');
        if (searchInput) searchInput.value = '';

        const versionSelect = document.getElementById('filter-version');
        if (versionSelect) versionSelect.value = '';

        currentView = 'grid';
        document.querySelectorAll('.view-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.view-btn[data-view="grid"]')?.classList.add('active');

        loadCourses(1); // first page
    });

    //  Pagination click
    document.addEventListener('click', function(e){
        if(!e.target.classList.contains('page-btn')) return;
        const page = parseInt(e.target.dataset.page);
        if(!isNaN(page)){
            loadCourses(page);
            window.scrollTo({top:0, behavior:'smooth'}); // scroll to top on page change
        }
    });

});
</script>



</body>
</html>
