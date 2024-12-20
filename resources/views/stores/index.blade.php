@extends('layout')

@section('content')
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" id="materials-tab" data-toggle="tab" href="#materials" onclick="showTab('materials')">Materials</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="equipment-tab" data-toggle="tab" href="#equipment" onclick="showTab('equipment')">Equipment</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="materials" class="tab-pane fade show active">
            <h1>Store Materials</h1>

            <a href="#" class="btn btn-primary">Add Material</a>
            @if ($storeMaterials->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Qty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
               
                    @foreach ($storeMaterials as $material)
                        <tr>
                            <td>Material {{ $material->id }}</td>
                            <td>{{ $material->quantity }}</td>
                            <td>
                                <a href="#" class="btn btn-primary">Edit</a>
                                <form action="#" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No Materials found.</p>
        @endif
           
        </div>

        <div id="equipment" class="tab-pane fade">
            <h1>Store Equipment</h1>
            <a href="#" class="btn btn-primary">Add Equipment</a>
            <table class="table" id="equipment-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Action<th>
                        <!-- Add more columns as needed -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Equipment 1</td>
                        <td>3</td>
                        <td>
                        <a href="#" class="btn btn-primary btn-sm">Edit</a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this material cost?')">Delete</button>
                        </form>
                    </td>
                        <!-- Add more rows as needed -->
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Equipment 2</td>
                        <td>7</td>
                        <td>
                        <a href="#" class="btn btn-primary btn-sm">Edit</a>
                        <form action="#" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this material cost?')">Delete</button>
                        </form>
                    </td>
                        <!-- Add more rows as needed -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showTab(tabId) {
            document.querySelectorAll('.nav-link').forEach(function(tab) {
                tab.classList.remove('active');
            });

            document.querySelectorAll('.tab-pane').forEach(function(tab) {
                tab.classList.remove('show', 'active');
            });

            document.getElementById(tabId + '-tab').classList.add('active');
            document.getElementById(tabId).classList.add('show', 'active');
        }
    </script>
@endsection