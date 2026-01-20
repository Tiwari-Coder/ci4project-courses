<style>

.sidebar h4 {
    font-size: 20px; 
    font-weight: 600; 
    margin-bottom: 10px;
}


.sidebar label {
    font-size: 16px; 
    display: block;   
    margin-bottom: 5px;
}

/* Courses wrapper */
#courses-wrapper {
    margin-top: 0; 
}

/* Grid view: slightly lifted up */
#courses-wrapper.grid-view {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: -10px; 
}

/* List view */
#courses-wrapper.list-view {
    display: block;
    margin-top: 0;
}

/* Individual course card */
.course-item {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 10px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

/* Optional: add a little lift effect on hover */
.course-item:hover {
    transform: translateY(-5px);
    transition: transform 0.2s ease;
}

/* COURSES TOPBAR */
.courses-topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap; /* responsive wrap */
}

/* Grid/List toggle buttons */
.view-toggle {
    display: flex;
    gap: 5px;
}

.view-btn {
    padding: 5px 12px;
    border: 1px solid #ddd;
    background: #f9f9f9;
    cursor: pointer;
}

.view-btn.active {
    background: #007bff;
    color: #fff;
}

/* Results count */
.results-count {
    font-weight: 500;
    font-size: 14px;
    white-space: nowrap;
}

/* Filters: search + version + reset */
.filters {
    display: flex;
    gap: 10px;
    align-items: center;
}

.filters input[type="text"],
.filters select {
    padding: 5px 8px;
    border: 1px solid #ddd;
    border-radius: 3px;
}

.filters button {
    padding: 5px 10px;
    border: 1px solid #ddd;
    background: #f1f1f1;
    cursor: pointer;
    border-radius: 3px;
}


    .layout {
    display: flex;
    gap: 30px;
}

.sidebar {
    width: 260px;
    border-right: 1px solid #ddd;
    padding-right: 15px;
}


.content {
    flex: 1;
}


#courses-wrapper.grid-view {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}


#courses-wrapper.list-view {
    display: block;
}

/* COURSE CARD */
.course-item {
    border: 1px solid #ddd;
    padding: 15px;
    margin-bottom: 10px;
}

.pagination{
    margin-top: 20px;
    display:flex;
    gap:5px;
    flex-wrap:wrap;
}

.pagination .page-btn{
    padding:5px 10px;
    border:1px solid #ddd;
    cursor:pointer;
    background:#f9f9f9;
}

.pagination .page-btn.active{
    background:#007bff;
    color:#fff;
}

</style>


<div class="courses-topbar"
     style="display:flex;align-items:center;justify-content:space-between;margin-bottom:15px;gap:15px;">

    <h2>All Courses</h2>

    <!--  Grid / List toggle -->
    <div>
        <button class="view-btn active" data-view="grid">Grid</button>
        <button class="view-btn" data-view="list">List</button>
    </div>

  
    <div>
        Showing <?= count($courses); ?> of <?= esc($totalCourses ?? count($courses)); ?> results
    </div>

    
    <div style="display:flex;gap:10px;">
        <input
            type="text"
            id="search-keyword"
            placeholder="Search"
            style="padding:5px;"
        >

        <select id="filter-version">
            <option value="">Version</option>
            <option value="2023">2023</option>
        </select>

        <button id="reset-filters">Reset</button>
    </div>

</div>

<!-- 🔹 COURSES WRAPPER (VERY IMPORTANT) -->
<div id="courses-wrapper" class="grid-view">

    <?php if (!empty($courses)): ?>
        <?php foreach ($courses as $course): ?>
            <div class="course-item">
                <h3><?= esc($course['title']); ?></h3>
                <p><?= esc($course['content']); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No courses found.</p>
    <?php endif; ?>

    <!-- Pagination -->
<?php 
$totalPages = ceil(($totalCourses ?? count($courses))/$perPage);
if($totalPages > 1): ?>
<div class="pagination">
    <?php if($currentPage > 1): ?>
        <button class="page-btn" data-page="<?= $currentPage-1 ?>">&laquo;</button>
    <?php endif; ?>

    <?php for($i=1; $i<=$totalPages; $i++): ?>
        <button class="page-btn <?= ($i==$currentPage)?'active':'' ?>" data-page="<?= $i ?>"><?= $i ?></button>
    <?php endfor; ?>

    <?php if($currentPage < $totalPages): ?>
        <button class="page-btn" data-page="<?= $currentPage+1 ?>">&raquo;</button>
    <?php endif; ?>
</div>
<?php endif; ?>

</div>
