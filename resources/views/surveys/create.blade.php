<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Survey
        </h2>
    </x-slot>

    <div class="p-6">
        <form>
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Survey Title</label>
                <input type="text" id="title" name="title"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <button type="submit"
                    class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Save
            </button>
        </form>
    </div>
</x-app-layout>