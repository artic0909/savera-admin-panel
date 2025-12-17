@forelse($products as $product)
    <div class="product-item">
        <a href="{{ route('product.show', $product->id) }}"
            style="text-decoration: none; color: inherit; width: 100%; display: flex; flex-direction: column; align-items: center;">
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}">
            <span>{{ $product->product_name }}</span>
            <p>Rs. {{ $product->display_price }}</p>
        </a>
    </div>
@empty
    <div class="col-12 text-center" style="grid-column: 1 / -1;">
        <p>No products found.</p>
    </div>
@endforelse
