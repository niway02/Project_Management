@extends('layout')

@section('title')
  <?= get_label('dashboard', 'Dashboard') ?>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row mt-4">
      <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div class="avatar flex-shrink-0">
                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md"></i>
              </div>
              <div>
                <h3 class="card-title mb-2">333</h3>
                <span class="fw-semibold d-block mb-1"><?= get_label('total_items', 'Total Items') ?></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <span class="fw-semibold d-block mb-1"><?= get_label('low_in_quantity', 'Low InQuantity') ?></span>
              <h3 class="card-title mb-2 text-warning">20</h3>
            </div>
            <a href="/projects" class="btn btn-outline-light btn-sm mt-3"><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div class="avatar flex-shrink-0">
                <i class="menu-icon tf-icons bx bx-task bx-md"></i>
              </div>
              <div>
                <h3 class="card-title mb-2">{{ $inventorySummary['totalMaterials'] }}</h3>
                <span class="fw-semibold d-block mb-1"><?= get_label('total_mtrials', 'Total Materials') ?></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <span class="fw-semibold d-block mb-1"><?= get_label('low_in_quantity', 'Low InQuantity') ?></span>
              <h3 class="card-title mb-2 text-warning">{{ $inventorySummary['lowInquantityMaterials'] }}</h3>
            </div>
            <a href="/tasks" class="btn btn-outline-light btn-sm mt-3"><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div class="avatar flex-shrink-0">
                <i class="menu-icon tf-icons bx bxs-user-detail bx-md"></i>
              </div>
              <div>
                <h3 class="card-title mb-2">{{$inventorySummary['totalEquipments'] }}</h3>
                <span class="fw-semibold d-block mb-1"><?= get_label('total_equipment', 'Total Equipment') ?></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <span class="fw-semibold d-block mb-1"><?= get_label('low_in_quantity', 'Low InQuantity') ?></span>
              <h3 class="card-title mb-2 text-warning">{{  $inventorySummary['lowInquantityEquipments'] }}</h3>
            </div>
            <a href="/users" class="btn btn-outline-light btn-sm mt-3"><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></a>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
        <div class="card bg-primary text-white">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div class="avatar flex-shrink-0">
                <i class="menu-icon tf-icons bx bxs-user-detail bx-md"></i>
              </div>
              <div>
                <h3 class="card-title mb-2">784</h3>
                <span class="fw-semibold d-block mb-1"><?= get_label('total_labor', 'Total Labor') ?></span>
              </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-3">
              <span class="fw-semibold d-block mb-1"><?= get_label('low_in_quantity', 'Low InQuantity') ?></span>
              <h3 class="card-title mb-2 text-warning">20</h3>
            </div>
            <a href="/clients" class="btn btn-outline-light btn-sm mt-3"><i class="bx bx-right-arrow-alt"></i> <?= get_label('view_more', 'View more') ?></a>
          </div>
        </div>
      </div>
    </div>
    
    <div class="row mt-4">
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Total Items by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="itemsByCategory"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Total Materials by Type</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="materialsByType"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <div class="row mt-4">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Inventory Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Total Quantity</th>
                                        <th>Low Quantity</th>
                                        <th>Category</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($inventoryData as $item)
                                    <tr>
                <td>{{ $item['item'] }}</td>
                <td>{{ $item['totalQuantity'] }}</td>
                <td>{{ $item['lowQuantity'] }}</td>
                <td>{{ $item['category'] }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary btn-sm">View Details</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  </div>

  
  <script>
    // Example chart.js code for the inventory chart
    var ctx = document.getElementById('inventoryChart').getContext('2d');
    var inventoryChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Items', 'Materials', 'Equipment', 'Labor'],
        datasets: [{
          label: 'Quantity',
          data: [333, 444, 555, 456],
          backgroundColor: [
            'rgba(54, 162, 235, 0.5)',
            'rgba(255, 206, 86, 0.5)',
            'rgba(75, 192, 192, 0.5)',
            'rgba(153, 102, 255, 0.5)'
          ],
          borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
@endsection