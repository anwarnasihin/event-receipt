@forelse($items as $item)

<div class="form-check mb-2">

    <input
        class="form-check-input"
        type="checkbox"
        name="items[]"
        value="{{ $item->id }}"
        id="item{{ $item->id }}">

    <label
        class="form-check-label"
        for="item{{ $item->id }}">

        {{ $item->name }}

    </label>

</div>

@empty

<div class="text-muted">
    Semua souvenir habis.
</div>

@endforelse
