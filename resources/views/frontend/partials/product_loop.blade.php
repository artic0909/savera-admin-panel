@forelse($products as $product)
<div class="{{ $itemClass ?? 'product-item' }}">
    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}" />
    <p><span>₹</span>{{ $product->display_price }}</p>
    @if(isset($showName) && $showName)
    <p>{{ $product->product_name }}</p>
    @endif
</div>
@empty
<p style="width: 100%; text-align: center;">No products found.</p>
@endforelse
