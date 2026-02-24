@env('local')
    <div class="fixed z-50 bottom-4 right-4">
        <flux:dropdown>
            <flux:button icon-trailing="chevron-up-down" variant="primary" size="xs">
                <span class="sm:hidden">&lt; 640</span>
                <span class="hidden sm:block md:hidden">SM | 640 - 768</span>
                <span class="hidden md:block lg:hidden">MD | 768 - 1024</span>
                <span class="hidden lg:block xl:hidden">LG | 1024 - 1280</span>
                <span class="hidden xl:block 2xl:hidden">XL | 1280 - 1536</span>
                <span class="hidden 2xl:block">2XL |  &gt; 1536</span>
            </flux:button>
            <flux:menu>
                <flux:menu.item icon="envelope-open" href="http://localhost:8025" target="_mail">Mailpit</flux:menu.item>
                <flux:menu.separator />
                <flux:menu.item icon="document-check" variant="danger" href="https://livewire.laravel.com/docs" target="_tall">Livewire</flux:menu.item>
                <flux:menu.item icon="document-check" variant="danger" href="https://fluxui.dev" target="_tall">Flux UI</flux:menu.item>
                <flux:menu.item icon="document-check" variant="danger" href="https://tailwindcss.com/docs" target="_tall">Tailwind</flux:menu.item>
                <flux:menu.item icon="document-check" variant="danger" href="https://laravel.com/docs/12.x/" target="_tall">Laravel</flux:menu.item>
                <flux:menu.item icon="document-check" variant="danger" href="https://tailwindcss.com/docs" target="_tall">Alpine</flux:menu.item>
                <flux:menu.separator />
                <flux:menu.item icon="shield-check" href="https://heroicons.com/" target="_icons">Heroicons</flux:menu.item>
                <flux:menu.item icon="shield-check" href="https://lucide.dev/icons/" target="_icons">Lucide icons</flux:menu.item>
                <flux:menu.separator />
                <flux:subheading class="p-2">Laravel {{ app()->version() }}</flux:subheading>
            </flux:menu>
        </flux:dropdown>
    </div>
@endenv
