<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create New Survey
        </h2>
    </x-slot>

    <div class="p-6 max-w-3xl mx-auto">
        <form action="{{ route('surveys.store') }}" method="POST">
            @csrf

            <!-- Step 1: Survey Type & Title -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Survey Type</label>
                <select name="survey_type" class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="" selected disabled>-- Choose a type --</option>
                    <option value="telkomsel">Telkomsel</option>
                    <option value="indihome">IndiHome</option>
                    <option value="template">Other</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Survey Title</label>
                <input type="text" name="title"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <hr class="my-6">

            <!-- Step 2: Questions -->
            <div>
                <h3 class="font-semibold text-lg mb-4">Questions</h3>
                <div id="questions-container" class="space-y-6"></div>

                <button type="button" id="add-question"
                    class="mt-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    + Add Question
                </button>
            </div>

            <hr class="my-6">

            <button type="submit"
                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                Create Survey
            </button>
        </form>
    </div>

    <script>
        const container = document.getElementById('questions-container');
        const addBtn = document.getElementById('add-question');

        addBtn.addEventListener('click', () => {
            const qIndex = container.children.length;

            const block = document.createElement('div');
            block.className = "p-4 border rounded-lg bg-white shadow-sm space-y-3 relative";

            block.innerHTML = `
                <label class="block text-sm font-medium text-gray-700">
                    Question Text
                </label>
                <input type="text" name="questions[${qIndex}][text]"
                    class="block w-full border-gray-300 rounded-md shadow-sm">

                <label class="block text-sm font-medium text-gray-700">
                    Question Type
                </label>
                <select name="questions[${qIndex}][type]"
                    class="block w-full border-gray-300 rounded-md shadow-sm question-type">
                    <option value="" selected disabled>-- Choose type --</option>
                    <option value="input">Input</option>
                    <option value="multiple">Multiple Option</option>
                    <option value="scale">Scale (1-5)</option>
                </select>

                <div class="options-container hidden">
                    <label class="block text-sm font-medium text-gray-700">
                        Options (comma separated)
                    </label>
                    <input type="text" name="questions[${qIndex}][options]"
                        class="block w-full border-gray-300 rounded-md shadow-sm options-input">
                </div>
            `;

            // Tombol hapus pertanyaan
            const removeBtn = document.createElement('button');
            removeBtn.type = "button";
            removeBtn.className = "absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm";
            removeBtn.innerText = "Remove";
            removeBtn.addEventListener('click', () => block.remove());
            block.appendChild(removeBtn);

            // toggle options
            const typeSelect = block.querySelector('.question-type');
            const optionsContainer = block.querySelector('.options-container');
            const optionsInput = block.querySelector('.options-input');

            typeSelect.addEventListener('change', () => {
                if (typeSelect.value === 'multiple') {
                    optionsContainer.classList.remove('hidden');
                    optionsInput.value = "";
                } else if (typeSelect.value === 'scale') {
                    optionsContainer.classList.add('hidden');
                    optionsInput.value = "1,2,3,4,5"; // otomatis isi scale
                } else {
                    optionsContainer.classList.add('hidden');
                    optionsInput.value = "";
                }
            });

            container.appendChild(block);
        });
    </script>
</x-app-layout>
