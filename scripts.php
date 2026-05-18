<script src="lib/jquery/jquery-4.0.0.js"></script>
<script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="lib/dataTables/dataTables.js"></script>
<script src="lib/dataTables/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#myTable').DataTable({
        pageLength: 10,
        responsive: true,
        order: [[0, 'desc']]
    });
});
</script>

