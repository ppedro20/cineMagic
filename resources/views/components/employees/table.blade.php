<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Name</th>
                <th class="px-2 py-2 text-left hidden lg:table-cell">Email</th>
                @if ($showView)
                    <th></th>
                @endif
                @if ($showEdit)
                    <th></th>
                @endif
                @if ($showDelete)
                    <th></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr class="border-b border-b-gray-400 dark:border-b-gray-500">
                    <td class="px-2 py-2 text-left">{{ $employee->name }}</td>
                    <td class="px-2 py-2 text-left hidden lg:table-cell">{{ $employee->email }}</td>
                    </td>
                    @if ($showView)
                        <td>
                            @can('view', $employee)
                                <x-table.icon-show class="ps-3 px-0.5"
                                    href="{{ route('employees.show', ['employee' => $employee]) }}" />
                            @else
                                <x-table.icon-show class="ps-3 px-0.5" :enabled="false" />
                            @endcan
                        </td>
                    @endif
                    @if ($showEdit)
                        <td>
                            @can('update', $employee)
                                <x-table.icon-edit class="px-0.5"
                                    href="{{ route('employees.edit', ['employee' => $employee]) }}" />
                            @else
                                <x-table.icon-edit class="px-0.5" :enabled="false" />
                            @endcan
                        </td>
                    @endif
                    @if ($showDelete)
                        <td>
                            @can('delete', $employee)
                                <x-table.icon-delete class="px-0.5"
                                    action="{{ route('employees.destroy', ['employee' => $employee]) }}" />
                            @else
                                <x-table.icon-delete class="px-0.5" :enabled="false" />
                            @endcan
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
