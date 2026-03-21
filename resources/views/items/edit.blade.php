@extends('layouts.app')
@section('title', 'Edit Item - BruTor Shop')

@section('content')
<div class="bg-[#1a1a1a] border-b border-gray-800 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-white uppercase tracking-tight">
                <i class="fa-solid fa-pen-to-square text-orange-500 mr-2"></i> Edit Item
            </h1>
            <p class="text-gray-400 text-sm mt-1">Update item details and manage its photo gallery.</p>
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
            <span class="text-xs text-gray-500">ID: {{ $item->item_id }}</span>
        </div>

        <form id="itemEditForm" method="POST" action="{{ route('admin.items.update', $item->item_id) }}" enctype="multipart/form-data" class="px-6 py-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label for="title" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Title *</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $item->title) }}" required minlength="2" maxlength="255"
                            class="bg-[#111111] border {{ $errors->has('title') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                        @error('title')
                            <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Description *</label>
                        <textarea name="description" id="description" rows="3" required maxlength="500"
                            class="bg-[#111111] border {{ $errors->has('description') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5 resize-none"
                            placeholder="Up to 500 characters">{{ old('description', $item->description) }}</textarea>
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
                            <input type="number" name="cost_price" id="cost_price" step="0.01" min="0" value="{{ old('cost_price', $item->cost_price) }}" required
                                class="bg-[#111111] border {{ $errors->has('cost_price') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                            @error('cost_price')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="sell_price" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Sell Price *</label>
                            <input type="number" name="sell_price" id="sell_price" step="0.01" min="0" value="{{ old('sell_price', $item->sell_price) }}" required
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
                            <select name="category" id="category" required
                                class="bg-[#111111] border {{ $errors->has('category') ? 'border-red-500 focus:ring-red-500' : 'border-gray-700 focus:ring-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                                <option value="">Select a category</option>
                                @foreach(['Engine', 'Electrical', 'Bodywork', 'Consumables', 'Other'] as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $item->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
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
                            <select name="type" id="type" required
                                class="bg-[#111111] border {{ $errors->has('type') ? 'border-red-500 focus:ring-red-500' : 'border-gray-700 focus:ring-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                                <option value="product" {{ old('type', $item->type) === 'product' ? 'selected' : '' }}>Product</option>
                                <option value="tool" {{ old('type', $item->type) === 'tool' ? 'selected' : '' }}>Tool Rental</option>
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
                            <input type="number" name="stock_quantity" id="stock_quantity" min="0" step="1" value="{{ old('stock_quantity', $item->stock_quantity) }}" required
                                class="bg-[#111111] border {{ $errors->has('stock_quantity') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-gray-700 focus:ring-orange-500 focus:border-orange-500' }} text-white text-sm rounded-md block w-full px-3 py-2.5">
                            @error('stock_quantity')
                                <p class="mt-1 text-xs text-red-400 flex items-center gap-1">
                                    <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>
                        <div>
                            <label for="supplier_id" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Supplier (optional)</label>
                            <select name="supplier_id" id="supplier_id"
                                class="bg-[#111111] border border-gray-700 text-white text-sm rounded-md focus:ring-orange-500 focus:border-orange-500 block w-full px-3 py-2.5">
                                <option value="">-- Select a Supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->supplier_id }}" {{ old('supplier_id', $item->supplier_id) == $supplier->supplier_id ? 'selected' : '' }}>
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
                        <h3 class="text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">Current Images</h3>
                        @php
                            $images = $item->images ?? collect();
                        @endphp

                        @if($images->isNotEmpty())
                            <div class="flex flex-wrap gap-3">
                                @foreach($images as $img)
                                    <div class="relative w-28 h-28 rounded-md overflow-hidden border border-gray-700 bg-[#111111] flex items-center justify-center">
                                        <img src="{{ asset($img->image_path) }}" alt="Item Image" class="w-full h-full object-cover">
                                        @if($img->is_primary)
                                            <span class="absolute top-1 left-1 bg-orange-600 text-[10px] px-1.5 py-0.5 rounded-full text-white font-semibold shadow">
                                                Primary
                                            </span>
                                        @endif
                                        <button type="button" 
                                                onclick="deleteGalleryImage('{{ route('admin.items.deleteImage', $img->image_id) }}')"
                                                class="absolute top-1 right-1 w-6 h-6 rounded-full bg-red-900/70 hover:bg-red-800 text-red-200 flex items-center justify-center text-xs border border-red-700">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div>
                                <p class="text-[11px] text-gray-500 mb-2">No gallery images yet. Showing legacy image if available.</p>
                                @php
                                    $legacyImg = $item->image_path ? asset($item->image_path) : asset('images/default.png');
                                @endphp
                                <div class="w-28 h-28 rounded-md overflow-hidden border border-gray-700 bg-[#111111] flex items-center justify-center">
                                    <img src="{{ $legacyImg }}" alt="Item Image" class="w-full h-full object-cover">
                                </div>
                                <p class="text-[11px] text-gray-500 mt-1">Legacy image is read-only; new uploads will use the gallery system.</p>
                            </div>
                        @endif
                    </div>

                    <div class="pt-3 border-t border-gray-800 space-y-2">
                        <label for="image_path" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Legacy Primary Image (optional)</label>
                        <input type="file" name="image_path" id="image_path" accept="image/*"
                            class="block w-full text-xs text-gray-300 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700 bg-[#111111] border border-gray-700 rounded-md cursor-pointer">
                        <p class="text-[11px] text-gray-500">Optional single image stored in <code class="text-gray-400">image_path</code> for backward compatibility.</p>
                    </div>

                    <div class="pt-3 border-t border-gray-800 space-y-2">
                        <label for="images" class="block text-xs font-medium text-gray-400 mb-1 uppercase tracking-wider">Add Gallery Images</label>
                        <input type="file" id="images" name="images[]" multiple accept="image/*"
                            class="block w-full text-sm text-gray-400 {{ $errors->has('images') || $errors->has('images.*') ? 'border-red-500' : 'border-gray-700' }} border bg-[#111111] rounded-md cursor-pointer px-3 py-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-gray-800 file:text-gray-200 hover:file:bg-gray-700">
                        <p class="text-[11px] text-gray-500">Select one or more images to append to the gallery. Max 5MB per file.</p>
                        @error('images.*')
                            <p class="mt-1 text-xs text-red-400"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                        @error('images')
                            <p class="mt-1 text-xs text-red-400"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-2 uppercase tracking-wider">New Images Preview</label>
                        <div id="imagePreview" class="flex flex-wrap gap-3 bg-[#111111] border border-dashed border-gray-700 rounded-lg p-3 min-h-[4rem]">
                            <span class="text-[11px] text-gray-500" id="imagePreviewPlaceholder">No new images selected.</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-800">
                <a href="{{ route('items.index') }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-700 text-gray-200 hover:bg-gray-800 transition-colors text-sm font-semibold">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-5 py-2.5 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-bold shadow-[0_0_15px_rgba(249,115,22,0.4)] transition-all">
                    <i class="fa-solid fa-check mr-2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<form id="deleteImageForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
    function deleteGalleryImage(url) {
        if (confirm('Remove this image?')) {
            var form = document.getElementById('deleteImageForm');
            form.action = url;
            form.submit();
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('itemEditForm');
        var input = document.getElementById('images');
        var preview = document.getElementById('imagePreview');
        var placeholder = document.getElementById('imagePreviewPlaceholder');
        var descriptionEl = document.getElementById('description');
        var descriptionCounter = document.getElementById('descriptionCount');
        var descriptionCounterWrap = document.getElementById('descriptionCounter');
        var costPriceEl = document.getElementById('cost_price');
        var sellPriceEl = document.getElementById('sell_price');
        var sellPriceWarning = document.getElementById('sellPriceWarning');

        function showError(inp, message) {
            inp.classList.add('border-red-500');
            inp.classList.remove('border-gray-700');
            inp.classList.add('focus:ring-red-500', 'focus:border-red-500');
            inp.classList.remove('focus:ring-orange-500', 'focus:border-orange-500');
            var err = inp.parentElement.querySelector('.js-error');
            if (!err) {
                err = document.createElement('p');
                err.className = 'js-error mt-1 text-xs text-red-400 flex items-center gap-1';
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
                inp.parentElement.appendChild(err);
            } else {
                err.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> ' + message;
            }
        }

        function clearError(inp) {
            inp.classList.remove('border-red-500');
            inp.classList.add('border-gray-700');
            inp.classList.remove('focus:ring-red-500', 'focus:border-red-500');
            inp.classList.add('focus:ring-orange-500', 'focus:border-orange-500');
            var err = inp.parentElement.querySelector('.js-error');
            if (err) err.remove();
        }

        function validateField(inp) {
            if (inp.hasAttribute('required') && !inp.value.trim()) {
                showError(inp, 'This field is required.');
                return false;
            }
            if (inp.getAttribute('name') === 'title' && inp.value.trim().length > 0 && inp.value.trim().length < 2) {
                showError(inp, 'Item title must be at least 2 characters.');
                return false;
            }
            if (inp.getAttribute('name') === 'description' && inp.hasAttribute('required') && !inp.value.trim()) {
                showError(inp, 'Description is required.');
                return false;
            }
            if (inp.getAttribute('name') === 'description' && inp.value.length > 500) {
                showError(inp, 'Description cannot exceed 500 characters.');
                return false;
            }
            if (inp.type === 'number' && inp.value !== '') {
                var min = inp.getAttribute('min');
                if (min !== null && min !== '' && parseFloat(inp.value) < parseFloat(min)) {
                    showError(inp, 'Value must be at least ' + min + '.');
                    return false;
                }
            }
            if (inp.tagName === 'SELECT' && inp.hasAttribute('required') && (!inp.value || inp.value === '')) {
                showError(inp, 'Please select a value.');
                return false;
            }
            clearError(inp);
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
                form.querySelectorAll('input:not([type=file]):not([type=hidden]), select, textarea').forEach(function (inp) {
                    if (!validateField(inp)) {
                        valid = false;
                        if (!firstError) firstError = inp;
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
                        placeholder.textContent = 'No new images selected.';
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
