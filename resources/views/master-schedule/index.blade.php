@extends('layout') <!-- Adjust according to your layout -->

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Master Schedule</h1>
 <!-- Flash Messages -->

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-schedule" aria-controls="navs-top-schedule" aria-selected="true">
                <i class="menu-icon tf-icons bx bx-wrench text-warning"></i> Schedule
            </button>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-gantt" aria-controls="navs-top-gantt" aria-selected="false">
                <i class="menu-icon tf-icons bx bx-paper-plane text-success"></i> Gantt Chart
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-3">
        <x-master-schedule :statuses="$statuses" :reportData="$reportData" :priorities="$priorities" :users="$users" :projectsData="$projectsData" :projects="$projects" :tasks="$tasks" />
        <x-gantt-chart />
    </div>
</div>

<script>
    // Toggle task visibility
    document.querySelectorAll('.toggle-tasks').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-id');
            const tasksRow = document.querySelector(`.tasks-row[data-project-id="${projectId}"]`);
            tasksRow.style.display = tasksRow.style.display === 'none' ? '' : 'none';

            // Change button text based on visibility
            this.textContent = tasksRow.style.display === 'none' ? '+' : '-';
        });
    });

    function printTable() {
        const printContent = document.querySelector('.table-responsive').innerHTML;
        const newWindow = window.open('', '_blank');
        newWindow.document.write(`
            <html>
                <head>
                    <title>Print Table</title>
                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                </head>
                <body onload="window.print(); window.close();">
                    <div class="container">
                        <h1>Master Schedule</h1>
                        <table class="table table-bordered">
                            ${printContent}
                        </table>
                    </div>
                </body>
            </html>
        `);
        newWindow.document.close();
    }

    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("projectsTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName("td");
            let rowContainsSearchTerm = false;

            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        rowContainsSearchTerm = true;
                        break;
                    }
                }
            }

            tr[i].style.display = rowContainsSearchTerm ? "" : "none";
        }
    }
</script>
@endsection