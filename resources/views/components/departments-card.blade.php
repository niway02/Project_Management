<table id="projects_table" data-toggle="table" data-loading-template="loadingTemplate" data-icons-prefix="bx" data-icons="icons" data-show-refresh="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-query-params="queryParamsProjects">
    <thead>
        <tr>
            
            <th>{{ get_label('name', 'Name') }}</th>
            <th>{{ get_label('created_at', 'Created At') }}</th>
            <th>{{ get_label('description', 'Description') }}</th>
            <th>{{ get_label('status', 'Status') }}</th>
            <th>{{ get_label('actions', 'Actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($departments as $department)
        <tr>
            <td>
                <a href="{{ url('/departments/' . $department->id) }}">{{ $department->name }}</a>
            </td>
            <td>{{ $department->created_at->format('M d, Y') }}</td>
            <td>{{ $department->description }}</td>
            <td>{{ $department->name }}</td>
         
            <td>
                <div class="dropdown">
                    <a href="javascript:void(0);" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ url('/departments/' . $department->id . '/edit') }}">{{ get_label('edit', 'Edit') }}</a></li>
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="deleteDepartment({{ $department->id }})">
                                {{ get_label('delete', 'Delete') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
