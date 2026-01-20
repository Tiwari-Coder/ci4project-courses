<h2>Manage Courses</h2>

<a href="<?= site_url('admin/courses/create') ?>">Add New Course</a>

<div id="courses-container" style="margin-top:15px;"></div>

<script>
function loadCourses() {
    fetch("<?= site_url('courses/ajax') ?>")
        .then(res => res.json())
        .then(courses => {

            let html = `
            <table border="1" cellpadding="8" cellspacing="0" style="width:100%;">
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Terms</th>
                    <th>Action</th>
                </tr>
            `;

            if (courses.length === 0) {
                html += `
                    <tr>
                        <td colspan="4" align="center">No courses found</td>
                    </tr>
                `;
            } else {
                courses.forEach(course => {

                    let termsHtml = '—';
                    if (course.terms && course.terms.length > 0) {
                        termsHtml = '';
                        course.terms.forEach(term => {
                            termsHtml += `
                                <strong>${term.taxonomy.replace('_',' ').toUpperCase()}:</strong>
                                ${term.name}<br>
                            `;
                        });
                    }

                    html += `
                        <tr>
                            <td>${course.title}</td>
                            <td>${course.content.substring(0,150)}...</td>
                            <td>${termsHtml}</td>
                            <td>
                                <a href="<?= site_url('admin/courses/edit') ?>/${course.id}">✏️ Edit</a>
                                |
                                <a href="<?= site_url('admin/courses/delete') ?>/${course.id}"
                                   onclick="return confirm('Are you sure?')">🗑 Delete</a>
                            </td>
                        </tr>
                    `;
                });
            }

            html += `</table>`;

            document.getElementById('courses-container').innerHTML = html;
        });
}

// 🚀 Load immediately
document.addEventListener('DOMContentLoaded', loadCourses);

// 🔄 Optional auto-refresh (every 5 sec)
// setInterval(loadCourses, 5000);


</script>
