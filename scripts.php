<script src="lib/jquery/jquery-4.0.0.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/dataTables/dataTables.js"></script>
<script src="lib/dataTables/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, 'desc']],
        columnDefs: [
            { width: "200px", targets: 4 } // email column index
        ],
        autoWidth: false
    });
});
</script>

