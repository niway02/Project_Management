<h5>Task Summary <span style="color: green; margin-left: 50px;">Total {{ $totalCompleted + $totalnotstarted + $totalCancelled + $totalPending }}</span></h5>
<div class="row mt-4"> 
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #71dd37;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('completed', 'completed') ?></span>
                        <h3 class="card-title mb-2">{{$totalCompleted}}</h3>
                        <a href="/tasks/information/7"><small class="text-success fw-semibold" style="color: #71dd37;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md " style="color: #696cff;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('in pending', 'Pending') ?></span>
                        <h3 class="card-title mb-2">{{$totalPending}}</h3>
                        <a href="/tasks/information/7"><small style="color: #696cff;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
        <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ffab00;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('Not started', 'Not started') ?></span>
                        <h3 class="card-title mb-2">{{$totalnotstarted}}</h3>
                        <a href="/tasks/information/7"><small style="color: #ffab00;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
         <div class="col-lg-3 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                            <i class="menu-icon tf-icons bx bx-briefcase-alt-2 bx-md text-canceled" style="color: #ff3e1d;"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1"><?= get_label('cancelled', 'Cancelled') ?></span>
                        <h3 class="card-title mb-2">{{$totalCancelled}}</h3>
                        <a href="/tasks/information/7"><small style="color: #ff3e1d;"><i class="bx bx-right-arrow-alt"></i><?= get_label('view_more', 'View more') ?></small></a>
                    </div>
                </div>
            </div>
            <div>
    <div>