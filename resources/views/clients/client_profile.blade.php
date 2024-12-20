@extends('layout')

@section('title')
<?= get_label('client_profile', 'Client profile') ?>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-2 mt-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-style1">
                    <li class="breadcrumb-item">
                        <a href="{{url('/home')}}"><?= get_label('home', 'Home') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{url('/clients')}}"><?= get_label('clients', 'Clients') ?></a>
                    </li>
                    <li class="breadcrumb-item">
                        <?= $client->first_name . ' ' . $client->last_name; ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">

                <!-- Account -->
                <div class="card-body">
                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <img src="{{$client->photo ? asset('storage/' . $client->photo) : asset('/profiles/1.png')}}" alt="client-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        <h4 class="card-header fw-bold">{{ $client->first_name }} {{$client->last_name}}</h4> <?= $client->status == 1 ? '<span class="badge bg-success">' . get_label('active', 'Active') . '</span>' : '<span class="badge bg-danger">' . get_label('deactive', 'Deactive') . '</span>' ?>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label"><?= get_label('country_code_and_phone_number', 'Country code and phone number') ?> <span class="asterisk">*</span></label>
                            <div class="input-group">
                                <!-- Country Code Input -->
                                <input type="text" name="country_code" class="form-control country-code-input" placeholder="+1" value="{{$client->country_code}}" readonly>

                                <!-- Mobile Number Input -->
                                <input type="text" name="phone" class="form-control" placeholder="1234567890" value="{{$client->phone}}" readonly>
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="email"><?= get_label('email', 'E-mail') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="exampleFormControlReadOnlyInput1" placeholder="" value="{{$client->email}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="address"><?= get_label('address', 'Address') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="address" placeholder="" value="{{$client->address}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="city"><?= get_label('city', 'City') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="city" placeholder="" value="{{$client->city}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="state"><?= get_label('state', 'State') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="state" placeholder="" value="{{$client->state}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="country"><?= get_label('country', 'Country') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="country" placeholder="" value="{{$client->country}}" readonly="">
                            </div>
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="zip"><?= get_label('zip_code', 'Zip code') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="zip" placeholder="" value="{{$client->zip}}" readonly="">
                            </div>
                        </div>


                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="dob"><?= get_label('dob', 'Date of birth') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="dob" placeholder="" value="{{ format_date($client->dob)}}" readonly="">
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="doj"><?= get_label('date_of_join', 'Date of joining') ?></label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="text" id="doj" placeholder="" value="{{ format_date($client->doj)}}" readonly="">
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($auth_user->can('manage_projects') || $auth_user->can('manage_tasks'))
    <div class="nav-align-top my-4">
        <ul class="nav nav-tabs" role="tablist">
            @if ($auth_user->can('manage_projects'))
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-projects" aria-controls="navs-top-projects" aria-selected="true">
                    <i class="menu-icon tf-icons bx bx-briefcase-alt-2 text-success"></i><?= get_label('projects', 'Projects') ?>
                </button>
            </li>
            @endif
            @if ($auth_user->can('manage_tasks'))
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-tasks" aria-controls="navs-top-tasks" aria-selected="false">
                    <i class="menu-icon tf-icons bx bx-task text-primary"></i><?= get_label('tasks', 'Tasks') ?>
                </button>
            </li>
            @endif
        </ul>
        <div class="tab-content">
            @if ($auth_user->can('manage_projects'))
            <div class="tab-pane fade active show" id="navs-top-projects" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold mx-4 mt-4">{{$client->first_name}}'s <?= get_label('projects', 'Projects') ?></h4>
                </div>
                @if (is_countable($projects) && count($projects) > 0)
                <?php
                $id = 'client_' . $client->id;
                ?>
                <x-projects-card :projects="$projects" :id="$id" :users="$users" :clients="$clients" />
                @else
                <?php
                $type = 'Projects'; ?>
                <x-empty-state-card :type="$type" />

                @endif
            </div>
            @endif

            @if ($auth_user->can('manage_tasks'))
            <div class="tab-pane fade {{!$auth_user->can('manage_projects')?'active show':''}}" id="navs-top-tasks" role="tabpanel">
                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold mx-4 mt-4">{{$client->first_name}}'s <?= get_label('tasks', 'Tasks') ?></h4>
                </div>
                @if ($tasks > 0)
                <?php
                $id = 'client_' . $client->id;
                ?>
                <x-tasks-card :tasks="$tasks" :id="$id" :users="$users" :clients="$clients" :projects="$projects" />
                @else
                <?php
                $type = 'Tasks'; ?>
                <x-empty-state-card :type="$type" />
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif


</div>


<!-- / projects -->

@endsection