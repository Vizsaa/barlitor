@extends('layouts.app')
@section('title', 'Add New Item - BruTor Shop')

@section('content')
<div class="bg-[#1a1a1a] border-b border-gray-800 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white uppercase tracking-tight">
                <i class="fa-solid fa-plus text-orange-500 mr-2"></i> Add New Item
            </h1>
            <p class="text-gray-400 text-sm mt-1">Create a new product or rental tool in the BruTor catalog.</p>
        </div>
        <a href="{{ route('items.index') }}" class="hidden sm:inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors text-sm font-semibold">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Items
        </a>
    </div>
</div>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-[#1a1a1a] border border-gray-800 rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-gray-200 uppercase tracking-wider">Item Details</h2>
            <span class="text-xs text-gray-500">Fields marked with * are required</span>
        </div>

        <form id="itemCreateForm" method="POST" action="{{ route('admin.items.store') }}" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Item Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required minlength="2" maxlength="255"
                            class="bg-[#111111] border {{ $errors->has('title') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                        @error('title')
                            <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Short Description *</label>
                        <textarea id="description" name="description" rows="3" required maxlength="500"
                            class="bg-[#111111] border {{ $errors->has('description') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5 resize-none"
                            placeholder="Up to 500 characters">{{ old('description') }}</textarea>
                        <p id="descriptionCounter" class="mt-1 text-xs text-gray-500"><span id="descriptionCount">0</span> / 500 characters</p>
                        @error('description')
                            <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="cost_price" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Cost Price *</label>
                            <input type="number" step="0.01" min="0" id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required
                                class="bg-[#111111] border {{ $errors->has('cost_price') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                            @error('cost_price')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="sell_price" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Sell Price *</label>
                            <input type="number" step="0.01" min="0" id="sell_price" name="sell_price" value="{{ old('sell_price') }}" required
                                class="bg-[#111111] border {{ $errors->has('sell_price') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                            <p id="sellPriceWarning" class="mt-1 text-xs text-amber-400 hidden" role="alert"><i class="fa-solid fa-triangle-exclamation"></i> Sell price is lower than cost price</p>
                            @error('sell_price')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="category" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Category *</label>
                            <select id="category" name="category" required
                                class="bg-[#111111] border {{ $errors->has('category') ? 'border-red-500 focus:ring-red-500' : 'border-gray-700 focus:ring-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                                <option value="">Select a category</option>
                                @foreach(['Engine', 'Electrical', 'Bodywork', 'Consumables', 'Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="type" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Type *</label>
                            <select id="type" name="type" required
                                class="bg-[#111111] border {{ $errors->has('type') ? 'border-red-500 focus:ring-red-500' : 'border-gray-700 focus:ring-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                                <option value="product" {{ old('type') === 'product' ? 'selected' : '' }}>Product</option>
                                <option value="tool" {{ old('type') === 'tool' ? 'selected' : '' }}>Tool Rental</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="stock_quantity" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Stock Quantity *</label>
                            <input type="number" min="0" step="1" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required
                                class="bg-[#111111] border {{ $errors->has('stock_quantity') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                            @error('stock_quantity')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="supplier_id" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Supplier (optional)</label>
                            <select id="supplier_id" name="supplier_id"
                                class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5">
                                <option value="">Select a supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id') == $supplier->supplier_id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Images Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Primary Image (Legacy)</label>
                        <input type="file" id="image_path" name="image_path" accept="image/*"
                            class="block w-full text-xs text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700 bg-[#111111] border border-gray-700 rounded-md cursor-pointer mb-2">
                        <p class="text-[11px] text-gray-500">Optional: single image stored in the legacy <code class="text-gray-400">image_path</code> field.</p>
                    </div>

                    <div class="pt-3 border-t border-gray-800">
                        <label class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Gallery Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                            class="block w-full text-sm text-gray-400 {{ $errors->has('images') || $errors->has('images.*') ? 'border-red-500' : 'border-gray-700' }} border bg-[#111111] rounded-md cursor-pointer px-3 py-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700">
                        <p class="text-[11px] text-gray-500 mt-1">You can select multiple files. Max size 5MB per image.</p>
                        @error('images.*')
                            <p class="mt-1 text-xs text-red-400"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                        @error('images')
                            <p class="mt-1 text-xs text-red-400"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">Preview</label>
                        <div id="imagePreview" class="flex flex-wrap gap-3 bg-[#111111] border border-dashed border-gray-700 rounded-lg p-3 min-h-[4rem]">
                            <span class="text-[11px] text-gray-500" id="imagePreviewPlaceholder">No images selected yet.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors text-sm font-semibold">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-all">
                    <i class="fa-solid fa-check mr-2"></i> Save Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('itemCreateForm');
        const input = document.getElementById('images');
        const preview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('imagePreviewPlaceholder');
        const descriptionEl = document.getElementById('description');
        const descriptionCounter = document.getElementById('descriptionCount');
        const descriptionCounterWrap = document.getElementById('descriptionCounter');
        const costPriceEl = document.getElementById('cost_price');
        const sellPriceEl = document.getElementById('sell_price');
        const sellPriceWarning = document.getElementById('sellPriceWarning');

        function showError(input, message) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-700');
            input.classList.add('focus:ring-red-500', 'focus:border-red-500');
            input.classList.remove('focus:ring-orange-500', 'focus:border-orange-500');
            var err = input.parentElement.querySelector('.js-error');
            if (!err) {
                err = document.createElement('p');
                err.className = 'js-error mt-1 text-xs text-red-400 flex items-center gap-1';
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
                input.parentElement.appendChild(err);
            } else {
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
            }
        }

        function clearError(input) {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-700');
            input.classList.remove('focus:ring-red-500', 'focus:border-red-500');
            input.classList.add('focus:ring-orange-500', 'focus:border-orange-500');
            var err = input.parentElement.querySelector('.js-error');
            if (err) err.remove();
        }

        function validateField(input) {
            if (input.hasAttribute('required') && !input.value.trim()) {
                showError(input, 'This field is required.');
                return false;
            }
            if (input.getAttribute('name') === 'title' && input.value.trim().length > 0 && input.value.trim().length < 2) {
                showError(input, 'Item title must be at least 2 characters.');
                return false;
            }
            if (input.getAttribute('name') === 'description' && input.hasAttribute('required') && !input.value.trim()) {
                showError(input, 'Description is required.');
                return false;
            }
            if (input.getAttribute('name') === 'description' && input.value.length > 500) {
                showError(input, 'Description cannot exceed 500 characters.');
                return false;
            }
            if (input.type === 'number' && input.value !== '') {
                var min = input.getAttribute('min');
                if (min !== null && min !== '' && parseFloat(input.value) < parseFloat(min)) {
                    showError(input, 'Value must be at least ' + min + '.');
                    return false;
                }
            }
            if (input.tagName === 'SELECT' && input.hasAttribute('required') && (!input.value || input.value === '')) {
                showError(input, 'Please select a value.');
                return false;
            }
            clearError(input);
            return true;
        }

        if (descriptionEl && descriptionCounter !== null) {
            function updateDescCounter() {
                var len = (descriptionEl.value || '').length;
                descriptionCounter.textContent = len;
                if (descriptionCounterWrap) {
                    descriptionCounterWrap.classList.toggle('text-red-400', len > 500);
                    descriptionCounterWrap.classList.toggle('text-gray-500', len <= 500);
                }
            }
            descriptionEl.addEventListener('input', updateDescCounter);
            updateDescCounter();
        }

        function updateSellPriceWarning() {
            if (!sellPriceWarning || !costPriceEl || !sellPriceEl) return;
            var cost = parseFloat(costPriceEl.value);
            var sell = parseFloat(sellPriceEl.value);
            if (!isNaN(cost) && !isNaN(sell) && sell < cost && sellPriceEl.value !== '') {
                sellPriceWarning.classList.remove('hidden');
            } else {
                sellPriceWarning.classList.add('hidden');
            }
        }
        if (costPriceEl) costPriceEl.addEventListener('input', updateSellPriceWarning);
        if (sellPriceEl) sellPriceEl.addEventListener('input', updateSellPriceWarning);
        updateSellPriceWarning();

        if (form) {
            form.querySelectorAll('input:not([type=file]):not([type=hidden]), select, textarea').forEach(function (field) {
                field.addEventListener('blur', function () { validateField(this); });
                field.addEventListener('input', function () {
                    if (this.parentElement.querySelector('.js-error')) validateField(this);
                });
            });

            form.addEventListener('submit', function (e) {
                var valid = true;
                var firstError = null;
                form.querySelectorAll('input:not([type=file]):not([type=hidden]), select, textarea').forEach(function (input) {
                    if (!validateField(input)) {
                        valid = false;
                        if (!firstError) firstError = input;
                    }
                });
                if (!valid) {
                    e.preventDefault();
                    if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });
        }

        if (input && preview) {
            input.addEventListener('change', function () {
                while (preview.firstChild) {
                    preview.removeChild(preview.firstChild);
                }
                var files = Array.from(this.files || []);
                if (files.length === 0) {
                    if (placeholder) {
                        placeholder.textContent = 'No images selected yet.';
                        preview.appendChild(placeholder);
                    }
                    return;
                }
                files.forEach(function (file) {
                    if (!file.type.startsWith('image/')) return;
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var wrapper = document.createElement('div');
                        wrapper.className = 'w-20 h-20 rounded-md overflow-hidden border border-gray-700 bg-[#111111] flex items-center justify-center';
                        var img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = file.name;
                        img.className = 'w-full h-full object-cover';
                        wrapper.appendChild(img);
                        preview.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });
            });
        }
    });
</script>
@endpush
