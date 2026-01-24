/**
 * Modal Edit Form Templates
 * Provides HTML form templates for different resource types
 */

function getCategoryFormHtml(data) {
    return `
        <form id="edit-form" method="POST" action="/categories/${data.id}">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="PATCH">
            
            <!-- Category Name -->
            <div class="mb-6">
                <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Category Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="edit-name" value="${data.name || ''}"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="e.g., Salary, Groceries, Transportation" required>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="name"></p>
            </div>

            <!-- Category Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" ${data.type === 'income' ? 'checked' : ''}
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Income</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense" ${data.type === 'expense' ? 'checked' : ''}
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-700">Expense</span>
                    </label>
                </div>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="type"></p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-gray-500 text-xs">(Optional)</span>
                </label>
                <textarea name="description" id="edit-description" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Add details about this category">${data.description || ''}</textarea>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="description"></p>
            </div>

            ${data.transactions_count > 0 ? `
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                <p class="text-sm text-yellow-700">
                    <strong>Warning:</strong> This category is used by ${data.transactions_count} transaction(s). 
                    Changing the type may affect existing transactions.
                </p>
            </div>` : ''}
        </form>
    `;
}

function getTransactionFormHtml(data) {
    // Categories should be passed as a global variable or data attribute
    const categories = window.editModalCategories || [];

    return `
        <form id="edit-form" method="POST" action="/transactions/${data.id}">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="PATCH">
            
            <!-- Transaction Type -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Type <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" ${data.type === 'income' ? 'checked' : ''}
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            onchange="filterModalCategories()">
                        <span class="ml-2 text-sm text-gray-700">Income</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense" ${data.type === 'expense' ? 'checked' : ''}
                            class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            onchange="filterModalCategories()">
                        <span class="ml-2 text-sm text-gray-700">Expense</span>
                    </label>
                </div>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="type"></p>
            </div>

            <!-- Category -->
            <div class="mb-6">
                <label for="edit-category_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Category <span class="text-red-500">*</span>
                </label>
                <select name="category_id" id="edit-category_id" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Select a category</option>
                    ${categories.map(cat => `
                        <option value="${cat.id}" data-type="${cat.type}" 
                            ${data.category_id == cat.id ? 'selected' : ''}>
                            ${cat.name} (${cat.type.charAt(0).toUpperCase() + cat.type.slice(1)})
                        </option>
                    `).join('')}
                </select>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="category_id"></p>
                <p class="mt-1 text-xs text-gray-500">Select transaction type first to filter categories</p>
            </div>

            <!-- Amount -->
            <div class="mb-6">
                <label for="edit-amount" class="block text-sm font-medium text-gray-700 mb-2">
                    Amount (Rp) <span class="text-red-500">*</span>
                </label>
                <input type="number" name="amount" id="edit-amount" value="${data.amount || ''}"
                    step="0.01" min="0.01" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="0.00">
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="amount"></p>
            </div>

            <!-- Transaction Date -->
            <div class="mb-6">
                <label for="edit-transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                    Transaction Date <span class="text-red-500">*</span>
                </label>
                <input type="date" name="transaction_date" id="edit-transaction_date" value="${data.transaction_date || ''}" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="transaction_date"></p>
            </div>

            <!-- Description -->
            <div class="mb-6">
                <label for="edit-description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="edit-description" rows="3" required
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter a brief description of the transaction">${data.description || ''}</textarea>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="description"></p>
            </div>

            <!-- Notes (Optional) -->
            <div class="mb-6">
                <label for="edit-notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Notes <span class="text-gray-500 text-xs">(Optional)</span>
                </label>
                <textarea name="notes" id="edit-notes" rows="3"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Add any additional notes or details">${data.notes || ''}</textarea>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="notes"></p>
            </div>
        </form>
    `;
}

function getFamilyFormHtml(data) {
    return `
        <form id="edit-form" method="POST" action="/family/${data.id}">
            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
            <input type="hidden" name="_method" value="PUT">
            
            <!-- Member Avatar & Name Preview -->
            <div class="mb-6 flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                <div class="w-16 h-16 rounded-full ${data.role === 'wife' ? 'bg-gradient-to-br from-purple-500 to-pink-500' : 'bg-gradient-to-br from-blue-500 to-cyan-500'} flex items-center justify-center text-white font-bold text-2xl">
                    ${data.name ? data.name.charAt(0).toUpperCase() : 'U'}
                </div>
                <div>
                    <p class="text-sm text-gray-500">Editing member</p>
                    <p class="font-semibold text-gray-900">${data.name || ''}</p>
                </div>
            </div>

            <!-- Name -->
            <div class="mb-6">
                <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-2">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="edit-name" value="${data.name || ''}" required
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="name"></p>
            </div>

            <!-- Email -->
            <div class="mb-6">
                <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email Address <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="edit-email" value="${data.email || ''}" required
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="email"></p>
            </div>

            <!-- Role -->
            <div class="mb-6">
                <label for="edit-role" class="block text-sm font-medium text-gray-700 mb-2">
                    Family Role <span class="text-red-500">*</span>
                </label>
                <select name="role" id="edit-role" required
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="wife" ${data.role === 'wife' ? 'selected' : ''}>Wife</option>
                    <option value="child" ${data.role === 'child' ? 'selected' : ''}>Child</option>
                </select>
                <p class="error-message hidden mt-1 text-sm text-red-600" data-field="role"></p>
            </div>

            <!-- Password Reset Section -->
            <div class="mb-6 p-4 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                <div class="flex items-start mb-4">
                    <svg class="w-5 h-5 text-indigo-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900">Reset Password (Optional)</h4>
                        <p class="mt-1 text-xs text-gray-600">Leave blank if you don't want to change the password.</p>
                    </div>
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label for="edit-password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password
                    </label>
                    <input type="password" name="password" id="edit-password"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        placeholder="Enter new password (min. 8 characters)">
                    <p class="error-message hidden mt-1 text-sm text-red-600" data-field="password"></p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="edit-password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="edit-password_confirmation"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        placeholder="Re-enter new password">
                </div>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <p class="text-sm text-blue-700">
                    <strong>Tip:</strong> After updating member information, inform them if their login credentials have changed.
                </p>
            </div>
        </form>
    `;
}

function initTransactionForm() {
    // Initialize category filtering
    filterModalCategories();
}

function filterModalCategories() {
    const typeRadios = document.getElementsByName('type');
    const categorySelect = document.getElementById('edit-category_id');

    if (!categorySelect) return;

    let selectedType = null;

    // Get selected type
    typeRadios.forEach(radio => {
        if (radio.checked) {
            selectedType = radio.value;
        }
    });

    // Filter categories
    const options = categorySelect.options;
    for (let i = 0; i < options.length; i++) {
        const option = options[i];
        const optionType = option.getAttribute('data-type');

        if (option.value === '') {
            // Keep the placeholder option
            option.style.display = '';
        } else if (selectedType && optionType !== selectedType) {
            // Hide categories that don't match selected type
            option.style.display = 'none';
            if (option.selected) {
                option.selected = false;
            }
        } else {
            // Show matching categories
            option.style.display = '';
        }
    }
}
