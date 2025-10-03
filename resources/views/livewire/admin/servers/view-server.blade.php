<section class="w-full">
  <x-page-heading>
    <x-slot:title>{{ __('servers.view_server') }}</x-slot:title>
    <x-slot:subtitle>Viewing {{ $server->name }}</x-slot:subtitle>
    <x-slot:buttons>
      @can('update servers')
      <flux:button icon="edit" variant="primary" href="{{ route('admin.servers.edit', $server) }}">
        {{ __('servers.edit_server') }}
      </flux:button>
      @endcan
    </x-slot:buttons>
  </x-page-heading>


</section>