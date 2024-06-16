<div {{ $attributes }}>
    <form method="GET" action="{{ $filterAction }}">
        <div class="flex justify-between space-x-3">
            <div class="grow flex flex-col space-y-2">
                @isset($showKeyword)
                    @if ($showKeyword)
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <x-field.input name="keyword" label="Search Customer" class="grow" value="{{ $keyword }}" />
                            </div>
                        </div>
                    @endif
                @endisset
                <div class="flex gap-4">
                    <div class="flex-1">
                        <x-field.input name="movie" label="Movie Title" class="grow"
                            value="{{ $movie }}" />
                    </div>

                    <div class="flex-1">
                        <x-field.input name="theater" label="Theater" class="grow"
                            value="{{ $theater }}" />
                    </div>
                </div>
            </div>
            </div>
            <div class="grow-0 flex flex-col space-y-3 justify-start">
                <div class="pt-6">
                    <x-button element="submit" type="dark" text="Filter"/>
                </div>
                <div>
                    <x-button element="a" type="light" text="Cancel" :href="$resetUrl"/>
                </div>
            </div>
        </div>
    </form>
</div>
