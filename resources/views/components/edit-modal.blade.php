{{-- Generic Edit Modal Component --}}
{{-- Usage: Include in layouts, triggered by edit buttons --}}

<div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="edit-modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop -->
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity"
            aria-hidden="true" onclick="closeEditModal()"></div>

        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg leading-6 font-semibold text-white" id="edit-modal-title">
                        Edit Item
                    </h3>
                    <button onclick="closeEditModal()" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="bg-white dark:bg-gray-800 px-6 py-4 max-h-[70vh] overflow-y-auto">
                <!-- Loading State -->
                <div id="modal-loading" class="hidden text-center py-8">
                    <svg class="animate-spin h-10 w-10 text-indigo-600 dark:text-indigo-400 mx-auto"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">Loading...</p>
                </div>

                <!-- Form Container (populated dynamically) -->
                <div id="modal-form-container">
                    <!-- Form will be injected here -->
                </div>

                <!-- General Error Display -->
                <div id="modal-general-error"
                    class="hidden mt-4 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-red-700 dark:text-red-300" id="modal-error-message"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="submitEditForm()" id="modal-submit-btn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Changes
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentEditForm = null;
    let currentResourceType = null;
    let currentResourceId = null;

    function openEditModal(resourceType, resourceId, resourceData = null) {
        currentResourceType = resourceType;
        currentResourceId = resourceId;

        const modal = document.getElementById('edit-modal');
        const modalTitle = document.getElementById('edit-modal-title');
        const formContainer = document.getElementById('modal-form-container');
        const loading = document.getElementById('modal-loading');

        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Update title
        modalTitle.textContent = `Edit ${resourceType.charAt(0).toUpperCase() + resourceType.slice(1)}`;

        // Show loading
        loading.classList.remove('hidden');
        formContainer.innerHTML = '';

        // If resourceData is provided, use it directly; otherwise fetch from current page DOM
        if (resourceData) {
            populateEditForm(resourceType, resourceData);
            loading.classList.add('hidden');
        } else {
            // For now, we'll get data from data attributes on the edit button
            setTimeout(() => {
                const data = getResourceDataFromDOM(resourceType, resourceId);
                if (data) {
                    populateEditForm(resourceType, data);
                } else {
                    showModalError('Failed to load resource data');
                }
                loading.classList.add('hidden');
            }, 300);
        }
    }

    function closeEditModal() {
        const modal = document.getElementById('edit-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';

        // Reset modal state
        currentEditForm = null;
        currentResourceType = null;
        currentResourceId = null;
        document.getElementById('modal-form-container').innerHTML = '';
        document.getElementById('modal-general-error').classList.add('hidden');
    }

    function getResourceDataFromDOM(resourceType, resourceId) {
        // Get data from the edit button's data attributes
        const editBtn = document.querySelector(`[data-edit-id="${resourceId}"][data-resource-type="${resourceType}"]`);
        if (!editBtn) return null;

        const dataStr = editBtn.getAttribute('data-resource');
        return dataStr ? JSON.parse(dataStr) : null;
    }

    function populateEditForm(resourceType, data) {
        const formContainer = document.getElementById('modal-form-container');

        // Get the appropriate form template
        let formHtml = '';

        switch (resourceType) {
            case 'category':
                formHtml = getCategoryFormHtml(data);
                break;
            case 'transaction':
                formHtml = getTransactionFormHtml(data);
                break;
            case 'family':
                formHtml = getFamilyFormHtml(data);
                break;
            default:
                showModalError('Unknown resource type');
                return;
        }

        formContainer.innerHTML = formHtml;

        // Initialize any specific form logic
        if (resourceType === 'transaction') {
            initTransactionForm();
        }
    }

    function submitEditForm() {
        const submitBtn = document.getElementById('modal-submit-btn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>Saving...';

        // Get form data
        const form = document.getElementById('edit-form');
        if (!form) {
            showModalError('Form not found');
            submitBtn.disabled = false;
            return;
        }

        // Submit the form normally (will trigger page reload with validation errors if needed)
        form.submit();
    }

    function showModalError(message) {
        const errorDiv = document.getElementById('modal-general-error');
        const errorMessage = document.getElementById('modal-error-message');
        errorMessage.textContent = message;
        errorDiv.classList.remove('hidden');
    }

    // Close modal on Esc key
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeEditModal();
        }
    });

    // Reopen modal if validation errors exist (after page reload)
    document.addEventListener('DOMContentLoaded', function () {
        const modalTrigger = document.querySelector('[data-reopen-modal]');
        if (modalTrigger) {
            const resourceType = modalTrigger.getAttribute('data-resource-type');
            const resourceId = modalTrigger.getAttribute('data-resource-id');
            const resourceData = JSON.parse(modalTrigger.getAttribute('data-resource'));
            openEditModal(resourceType, resourceId, resourceData);
        }
    });
</script>