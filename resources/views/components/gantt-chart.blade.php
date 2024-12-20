<!-- resources/views/components/gantt-chart.blade.php -->

<div class="tab-pane fade" id="navs-top-gantt" role="tabpanel">
    <h3>Gantt Chart</h3>
    <div id="ganttChartContainer" style="height: 400px; border: 1px solid #ccc;">
        <div id="gantt_here" style="width: 100%; height: 400px;"></div>
    </div>
</div>

<!-- Include DHTMLX Gantt CSS and JS -->
<link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" type="text/css">
<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js" type="text/javascript"></script>

<script type="text/javascript">
    // Fetch Gantt data from the backend
    fetch("http://localhost:8000/gantt")
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(projects => {
            // Prepare the Gantt data format
            const ganttData = {
                data: projects.map(project => ({
                    id: project.id,
                    text: project.text,
                    start_date: project.start_date,
                    duration: project.duration,
                    parent: project.parent || 0, // Set parent to 0 if none
                    status: project.status,
                })),
                links: [] // Add links if you have them
            };

            // Configure Gantt chart
            gantt.config.date_format = "%Y-%m-%d";
            gantt.config.details_on_dblclick = true;
            gantt.config.open_tree_initially = true; // Open parents by default
            gantt.config.tree_cell = true; // Enable tree cell view

            // Initialize Gantt chart
            gantt.init("gantt_here");
            gantt.parse(ganttData);
        })
        .catch(error => {
            console.error('Error fetching Gantt data:', error);
            document.getElementById('gantt_here').innerText = 'Failed to load Gantt chart data.';
        });
</script>