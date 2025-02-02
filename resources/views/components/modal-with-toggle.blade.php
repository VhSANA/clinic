@props([
    'button_title',
    'modal_title',
    'model',
    'path',
])
<!-- Modal toggle -->
<button
    id="open-personnel-modal-btn"
   class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
    {{ $button_title }}
</button>

<div id="choose-personnel-modal" class="hidden bg-gray-500 bg-opacity-40 overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-7xl max-h-full">
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $modal_title }}
                </h3>
                <button id="close-personnel-modal-btn" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <table class="w-full text-sm text-left rtl:text-center text-gray-500 dark:text-gray-400">
                    {{ $slot }}
                </table>

                {{-- pagination --}}
                <x-pagination :model="$model" path="{{ $path }}">
                    {{ $model->links() }}
                </x-pagination>
            </div>
        </div>
    </div>
</div>


<script>
    const urlParams = new URLSearchParams(window.location.search);
    const page = urlParams.get('page');
    const search = urlParams.get('search');
    const personnelModal = document.getElementById('choose-personnel-modal');

    // pop up personnel modal going through pages
    if (page) {
        document.addEventListener('DOMContentLoaded', function () {
            personnelModal.classList.remove('hidden');
            personnelModal.classList.add('flex');
        });
    }

    // pop up modal after searching user
    if (search) {
        document.addEventListener('DOMContentLoaded', function () {
            personnelModal.classList.remove('hidden');
            personnelModal.classList.add('flex');
        });
    }

    // Open Modal
    document.getElementById('open-personnel-modal-btn').addEventListener('click', function() {
        personnelModal.classList.remove('hidden');
        personnelModal.classList.add('flex');
    });

    // Close Modal
    document.getElementById('close-personnel-modal-btn').addEventListener('click', function() {
        personnelModal.classList.add('hidden');
        personnelModal.classList.remove('flex');
    });

    // Close Modal by clicking outside of the modal content area (background)
    personnelModal.addEventListener('click', function(e) {
        if (e.target === this) {
            personnelModal.classList.add('hidden');
            personnelModal.classList.remove('flex');
        }
    });
</script>
