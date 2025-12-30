@forelse($products as $product)
    <div class="product-item">
        <a href="{{ route('product.show', $product->slug) }}"
            style="text-decoration: none; color: inherit; width: 100%; display: flex; flex-direction: column; align-items: center;">
            <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->product_name }}">
            <span>{{ substr($product->product_name, 0, 14) }}...</span>
            <p style="padding-bottom: 0px;">₹ {{ number_format((int) str_replace(',', '', $product->display_price)) }}
            </p>
            @if ($product->mrp > 0)
                <p style="font-size: 14px; font-weight: 500;"> MRP :<span
                        style="text-decoration: line-through; color: #999; font-size: 14px; font-weight: 500; padding: 0 5px">
                        ₹{{ number_format((int) str_replace(',', '', $product->mrp)) }}
                    </span></p>
            @endif
        </a>
    </div>
@empty
    <div class="col-12 text-center" style="grid-column: 1 / -1;">
        <p>No products found.</p>
    </div>
@endforelse
