<div class="flex flex-col gap-3 p-4">
    @foreach ($getComponents() as $component)
        <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-2 last:border-none">
            
            <span class="text-sm font-medium text-gray-700 dark:text-white">
                {{ $component->getLabel() }}
            </span>
            
            <div>
                {{ $component }}
            </div>
        </div>
    @endforeach
</div>