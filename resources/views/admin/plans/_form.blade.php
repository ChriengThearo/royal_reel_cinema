{{-- Shared plan form. $plan is null on create. --}}
@php $old = fn($f, $d = '') => old($f, $plan?->$f ?? $d); @endphp

<div class="admin-card p-4 mb-3">
    <div class="row g-3">

        <div class="col-sm-6">
            <label class="form-label-sm">Plan Name <span style="color:#dc3545">*</span></label>
            <input type="text" name="name" value="{{ $old('name') }}"
                   class="form-control form-control-dark @error('name') is-invalid @enderror"
                   placeholder="e.g. Premium" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-3">
            <label class="form-label-sm">Price <span style="color:#dc3545">*</span></label>
            <input type="number" name="price" value="{{ $old('price', '0.00') }}"
                   step="0.01" min="0"
                   class="form-control form-control-dark @error('price') is-invalid @enderror" required>
            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-3">
            <label class="form-label-sm">Currency <span style="color:#dc3545">*</span></label>
            <input type="text" name="currency" value="{{ $old('currency', 'USD') }}"
                   class="form-control form-control-dark @error('currency') is-invalid @enderror"
                   maxlength="10" required>
            @error('currency')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Billing Cycle <span style="color:#dc3545">*</span></label>
            <select name="billing_cycle" class="form-select form-select-dark @error('billing_cycle') is-invalid @enderror" required>
                @foreach(['monthly','yearly'] as $c)
                    <option value="{{ $c }}" {{ $old('billing_cycle','monthly') === $c ? 'selected':'' }}>{{ ucfirst($c) }}</option>
                @endforeach
            </select>
            @error('billing_cycle')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Max Quality <span style="color:#dc3545">*</span></label>
            <select name="max_quality" class="form-select form-select-dark @error('max_quality') is-invalid @enderror" required>
                @foreach(['480p','720p','1080p','4k'] as $q)
                    <option value="{{ $q }}" {{ $old('max_quality','1080p') === $q ? 'selected':'' }}>{{ $q }}</option>
                @endforeach
            </select>
            @error('max_quality')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-4">
            <label class="form-label-sm">Screens Allowed <span style="color:#dc3545">*</span></label>
            <input type="number" name="screens_allowed" value="{{ $old('screens_allowed', 1) }}"
                   min="1" class="form-control form-control-dark @error('screens_allowed') is-invalid @enderror" required>
            @error('screens_allowed')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-sm-6">
            <label class="form-label-sm">Stripe Product ID</label>
            <input type="text" name="stripe_product_id" value="{{ $old('stripe_product_id') }}"
                   class="form-control form-control-dark" placeholder="prod_…">
        </div>

        <div class="col-sm-6">
            <label class="form-label-sm">Stripe Price ID</label>
            <input type="text" name="stripe_price_id" value="{{ $old('stripe_price_id') }}"
                   class="form-control form-control-dark" placeholder="price_…">
        </div>

        <div class="col-12">
            <div class="form-check" style="margin-top:0.25rem;">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                       {{ $old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active" style="font-size:0.85rem; color:rgba(255,255,255,0.7);">
                    Plan is active (visible to users)
                </label>
            </div>
        </div>

    </div>
</div>
