<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Create New Store</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.stores.store') }}" enctype="multipart/form-data">
          @csrf
          <div class="mb-4">
            <x-ui.input-label for="name" value="Name" />
            <x-ui.input id="name" name="name" :value="old('name')" required class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('name')" class="mt-2" />
          </div>
          <div class="mb-4">
            <x-ui.input-label for="description" value="Description" />
            <x-ui.textarea id="description" name="description" class="mt-1 w-full">{{ old('description') }}</x-ui.textarea>
            <x-ui.input-error :messages="$errors->get('description')" class="mt-2" />
          </div>
          <div class="mb-4">
            <x-ui.input-label for="logo" value="Logo" />
            <x-ui.input id="logo" type="file" name="logo" class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('logo')" class="mt-2" />
          </div>
          <x-ui.button variant="success" type="submit" class="w-full justify-center">Create Store</x-ui.button>
        </form>
      </div>
    </div>
</x-app-layout>
