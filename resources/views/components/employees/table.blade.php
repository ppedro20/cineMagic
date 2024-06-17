<div {{ $attributes }}>
    <table class="table-auto border-collapse w-full">
        <thead>
            <tr class="border-b-2 border-b-gray-400 dark:border-b-gray-500 bg-gray-100 dark:bg-gray-800">
                <th class="px-2 py-2 text-left">Name</th>
                <th class="px-2 py-2 text-left hidden lg:table-cell">Email</th>
                <th></th>
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
                    @if ($employee->blocked)
                        <td>
                            <x-table.icon-block class="px-0.5"
                                action="{{ route('user.updateblock', ['user' => $employee]) }}" />
                        </td>
                    @else
                        <td>
                        <x-table.icon-unblock class="px-0.5"
                            action="{{ route('user.updateblock', ['user' => $employee]) }}" />
                        </td>
                    @endif

                    @if ($showView)
                        <td>
                            <x-table.icon-show class="ps-3 px-0.5"
                                href="{{ route('employees.show', ['employee' => $employee]) }}" />
                        </td>
                    @endif
                    @if ($showEdit)
                        <td>
                            <x-table.icon-edit class="px-0.5"
                                href="{{ route('employees.edit', ['employee' => $employee]) }}" />
                        </td>
                    @endif
                    @if ($showDelete)
                        <td>
                            <x-table.icon-delete class="px-0.5"
                                action="{{ route('employees.destroy', ['employee' => $employee]) }}" />
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
