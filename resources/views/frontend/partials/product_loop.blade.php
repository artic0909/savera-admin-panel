@forelse($products as $product)
    <div class="product-item">
        <a href="{{ route('product.show', $product->slug) }}"
            style="text-decoration: none; color: inherit; width: 100%; display: flex; flex-direction: column; align-items: center;">
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}">
            <span>{{ substr($product->product_name, 0, 14) }}...</span>
            <p>Rs. {{ $product->display_price }}</p>
        </a>
    </div>
@empty
    <div class="col-12 text-center" style="grid-column: 1 / -1;">
        <p>No products found.</p>
    </div>
@endforelse
