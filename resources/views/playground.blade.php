<x-layouts.airports title="Playground">

    <h2 class="text-md font-bold italic my-4 pb-2">Range slider component</h2>

    <div class="grid grid-cols-2 gap-4">
        <x-itf.range-slider min="200" max="500" step="1" name="price" id="id2" label="Price ≤" suffix="€"/>
        <x-itf.range-slider value="6" name="Nr_of_items" step="2" hide-min-max class="border p-2 shadow-xl"/>
    </div>

    <h2 class="text-md font-bold italic my-4 pb-2">Table component</h2>

    <x-itf.table cols="w-32, w-auto, w-[200px]">
        <tr>
            <x-itf.table.sortable-header :sorted="true" direction="asc">Header 1</x-itf.table.sortable-header>
            <x-itf.table.sortable-header :sorted="false">Header 2</x-itf.table.sortable-header>
            <th>Header 3</th>
        </tr>
        <tr>
            <td>Row 1, Cell 1</td>
            <td>Row 1, Cell 2</td>
            <td>Row 1, Cell 3</td>
        </tr>
        <tr>
            <td>Row 2, Cell 1</td>
            <td>Row 2, Cell 2</td>
            <td>Row 2, Cell 3</td>
        </tr>
        <tr>
            <td>Row 3, Cell 1</td>
            <td>Row 3, Cell 2</td>
            <td>Row 3, Cell 3</td>
        </tr>
    </x-itf.table>

    <h2 class="text-md font-bold italic my-4 pb-2">Alert component</h2>

    <x-itf.alert class="shadow-lg hover:shadow-xl transition hover:-translate-2 hover:bg-zinc-100">
        Default alert component.
    </x-itf.alert>
    <x-itf.alert variant="success">
        Alert component with <b>success</b> variant.
    </x-itf.alert>
    <x-itf.alert variant="error" icon="exclamation-triangle">
        Alert component with <b>error</b> variant with an icon.
    </x-itf.alert>
    <x-itf.alert variant="warning" icon="question-mark-circle" dismissible>
        Alert content with <b>warning</b> variant, an icon and dismissible.
    </x-itf.alert>
    <x-itf.alert variant="info" icon="shield-exclamation" self-destruct="5000">
        Alert content with <b>info</b> variant, an icon and it will hide itself after 5 seconds.
    </x-itf.alert>
    <x-itf.alert variant="info" title="Alert title" icon="shield-exclamation" dismissible>
        Alert content with <b>info</b> variant, an icon, a title and dismissible.
    </x-itf.alert>

    <x-itf.livewire-log {{-- :users="$users" --}} />

</x-layouts.airports>
