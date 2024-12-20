@extends('layout')

@section('title')
<?= get_label('todo_list', 'Todo list') ?>
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
                    <li class="breadcrumb-item active">
                        <?= get_label('todos', 'Todos') ?>
                    </li>
                </ol>
            </nav>
        </div>
        <div>
            <span data-bs-toggle="modal" data-bs-target="#create_todo_modal"><a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-original-title="<?= get_label('create_todo', 'Create todo') ?>"><i class='bx bx-plus'></i></a></span>
        </div>
    </div>

    @if (is_countable($todos) && count($todos) > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><?= get_label('todo', 'Todo') ?></th>
                            <th><?= get_label('priority', 'Priority') ?></th>
                            <th><?= get_label('description', 'Description') ?></th>
                            <th><?= get_label('updated_at', 'Updated at') ?></th>
                            <th><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($todos as $todo)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <input type="checkbox" id="{{$todo->id}}" onclick='update_status(this)' name="{{$todo->id}}" class="form-check-input mt-0" {{$todo->is_completed ? 'checked' : ''}}>
                                    </div>
                                    <span class="mx-4">
                                        <h4 class="m-0 <?= $todo->is_completed ? 'striked' : '' ?>" id="{{$todo->id}}_title">{{ $todo->title }}</h4>
                                        <h7 class="m-0 text-muted">{{ format_date($todo->created_at,true)}}</h7>

                                    </span>
                                </div>
                            </td>
                            <td>
                                <span class='badge bg-label-{{config("taskhub.priority_labels")[$todo->priority]}} me-1'>{{$todo->priority}}</span>
                            </td>
                            <td>
                                {{$todo->description}}
                            </td>
                            <td>
                                {{format_date($todo->updated_at, true)}}
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="javascript:void(0);" class="edit-todo" data-bs-toggle="modal" data-bs-target="#edit_todo_modal" data-id="{{ $todo->id }}" title="<?= get_label('update', 'Update') ?>" class="card-link"><i class='bx bx-edit mx-1'></i></a>


                                    <a href="javascript:void(0);" type="button" data-id="{{$todo->id}}" data-type="todos" title="<?= get_label('delete', 'Delete') ?>" class="card-link mx-4 delete"><i class='bx bx-trash text-danger mx-1'></i></a>

                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @else
    <?php
    $type = 'Todos'; ?>
    <x-empty-state-card :type="$type" />
    @endif
</div>


@endsection