// Helper to format currency
function formatCurrency(amount) {
    return '₹ ' + parseFloat(amount).toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Helper to format number
function formatNumber(num, decimals = 2) {
    return parseFloat(num).toFixed(decimals);
}

// Select Material
function selectMaterial(materialId, preferredSizeId = null) {
    // Highlight Button
    document.querySelectorAll('.metal-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.dataset.materialId == materialId) btn.classList.add('active');
    });

    // Filter Configs for this Material
    let availableSizes = [];
    let firstConfig = null;

    for (let key in pdConfig.productConfigs) {
        let conf = pdConfig.productConfigs[key];
        if (conf.material_id == materialId) {
            if (!firstConfig) firstConfig = conf;
            if (!availableSizes.includes(conf.size_id)) {
                availableSizes.push(conf.size_id);
            }
        }
    }

    // Update Size Dropdown
    let sizeSelect = document.getElementById('size-selector');
    if (sizeSelect) {
        sizeSelect.innerHTML = '<option value="">Select Size</option>';

        availableSizes.forEach(sId => {
            let sName = pdConfig.sizes[sId] ? (pdConfig.sizes[sId].size_name || pdConfig.sizes[sId].name) : 'Unknown';
            let opt = document.createElement('option');
            opt.value = sId;
            opt.textContent = sName;
            sizeSelect.appendChild(opt);
        });

        // Select Default Size
        if (availableSizes.length > 0) {
            let targetSize = preferredSizeId && availableSizes.includes(preferredSizeId) ? preferredSizeId : availableSizes[0];
            sizeSelect.value = targetSize;
            selectSize(targetSize);
        } else if (firstConfig) {
            updateProductDetails(firstConfig);
        }
    }
}

// Select Size
function selectSize(sizeId) {
    if (!sizeId) return;

    let activeBtn = document.querySelector('.metal-btn.active');
    if (!activeBtn) return;

    let matId = activeBtn.dataset.materialId;

    let targetConfig = null;
    for (let key in pdConfig.productConfigs) {
        let conf = pdConfig.productConfigs[key];
        if (conf.material_id == matId && conf.size_id == sizeId) {
            targetConfig = conf;
            break;
        }
    }

    if (targetConfig) {
        updateProductDetails(targetConfig);
    }
}

// Update Details
function updateProductDetails(config) {
    let mat = pdConfig.materials[config.material_id];
    let matPrice = mat ? parseFloat(mat.price) : 0;

    // Weights
    let netWt = parseFloat(config.net_weight_gold || 0);
    const netWeightEl = document.getElementById('net-weight');
    const purityEl = document.getElementById('purity-display');
    if (netWeightEl) netWeightEl.textContent = netWt;
    if (purityEl) purityEl.textContent = config.purity || '--';

    // Diamond Info
    let diamondTotalWt = 0;
    let totalDiamondCount = 0;
    let diamondInfo = config.diamond_info || {};

    let diamondWrapper = document.getElementById('diamond-details-wrapper');
    let diamondTableBody = document.querySelector('#diamond-details-table tbody');

    if (diamondTableBody) diamondTableBody.innerHTML = '';

    let hasDiamonds = false;
    let dKeys = Object.keys(diamondInfo);
    if (dKeys.length > 0) {
        hasDiamonds = true;
        dKeys.forEach(k => {
            let info = diamondInfo[k];
            let wt = parseFloat(info.total_weight || 0);
            let count = parseInt(info.number_of_diamonds || 0);

            diamondTotalWt += wt;
            totalDiamondCount += count;

            if (diamondTableBody) {
                let row = `<tr>
                    <td>${info.size || 'Diamond'}</td>
                    <td>${info.color || '--'}</td>
                    <td>${info.clarity || '--'}</td>
                    <td>${info.shape || '--'}</td>
                    <td>${info.number_of_diamonds || '--'}</td>
                    <td>${info.total_weight || '--'} ct</td>
                </tr>`;
                diamondTableBody.innerHTML += row;
            }
        });
    }

    const diamondTotalWtEl = document.getElementById('diamond-total-wt');
    const diamondTotalCountEl = document.getElementById('diamond-total-count');
    if (diamondTotalWtEl) diamondTotalWtEl.textContent = formatNumber(diamondTotalWt, 3);
    if (diamondTotalCountEl) diamondTotalCountEl.textContent = totalDiamondCount;
    if (diamondWrapper) diamondWrapper.style.display = hasDiamonds ? 'block' : 'none';

    // Calculate and Update Gross Weight
    let calculatedGrossWt = netWt + diamondTotalWt;
    const grossWeightEl = document.getElementById('gross-weight');
    if (grossWeightEl) grossWeightEl.textContent = formatNumber(calculatedGrossWt, 3);

    // Price Calculations
    let materialCost = netWt * matPrice;
    let diamondCost = parseFloat(config.total_diamond_price || 0);
    let makingCharge = parseFloat(config.making_charge || 0);
    let basePrice = materialCost + diamondCost + makingCharge;
    let gstPercent = parseFloat(config.gst_percentage || 0);
    let gstAmount = (basePrice * gstPercent) / 100;
    let finalPrice = basePrice + gstAmount;

    // Update Price Breakup
    const goldBreakdownText = document.getElementById('gold-breakdown-text');
    const goldCostDisplay = document.getElementById('gold-cost-display');
    if (goldBreakdownText) goldBreakdownText.textContent = `(${formatNumber(netWt, 3)}g x ${matPrice})`;
    if (goldCostDisplay) goldCostDisplay.textContent = formatCurrency(materialCost);

    let diamondRow = document.getElementById('diamond-row');
    if (diamondRow) diamondRow.style.display = hasDiamonds ? '' : 'none';
    if (hasDiamonds) {
        const diamondBreakdownText = document.getElementById('diamond-breakdown-text');
        const diamondCostDisplay = document.getElementById('diamond-cost-display');
        if (diamondBreakdownText) diamondBreakdownText.textContent = `(${formatNumber(diamondTotalWt, 3)}g x ${pdConfig.diamondRate})`;
        if (diamondCostDisplay) diamondCostDisplay.textContent = formatCurrency(diamondCost);
    }

    const makingChargeDisplay = document.getElementById('making-charge-display');
    const gstPercentDisplay = document.getElementById('gst-percent-display');
    const gstAmountDisplay = document.getElementById('gst-amount-display');
    const finalTotalDisplay = document.getElementById('final-total-display');
    const dynamicPriceEl = document.getElementById('dynamic-price');

    if (makingChargeDisplay) makingChargeDisplay.textContent = formatCurrency(makingCharge);
    if (gstPercentDisplay) gstPercentDisplay.textContent = gstPercent;
    if (gstAmountDisplay) gstAmountDisplay.textContent = formatCurrency(gstAmount);
    if (finalTotalDisplay) finalTotalDisplay.textContent = formatCurrency(finalPrice);
    if (dynamicPriceEl) dynamicPriceEl.textContent = formatCurrency(finalPrice);

    // Update MRP and Discount Badge
    let mrpValue = parseFloat(config.mrp || 0);
    let mrpElement = document.getElementById('mrp');
    let discountBadge = document.getElementById('discount-badge');

    if (mrpValue > 0 && Math.abs(mrpValue - finalPrice) > 0.01) {
        if (mrpElement) {
            mrpElement.textContent = formatCurrency(mrpValue);
            mrpElement.style.display = 'inline-block';
        }
        let discountPercent = Math.round(((mrpValue - finalPrice) / mrpValue) * 100);
        if (discountPercent > 0 && discountBadge) {
            discountBadge.textContent = discountPercent + '% OFF';
            discountBadge.style.display = 'inline-block';
        } else if (discountBadge) {
            discountBadge.style.display = 'none';
        }
    } else {
        if (mrpElement) mrpElement.style.display = 'none';
        if (discountBadge) discountBadge.style.display = 'none';
    }

    // Update Coupon Rotator if exists
    if (window.updateCouponDisplay) {
        window.updateCouponDisplay();
    }
}

// Coupon Logic
function updateCouponRotator() {
    const rotatorDiv = document.getElementById('coupon-rotator');
    const offerText = document.getElementById('coupon-offer-text');
    const codeText = document.getElementById('coupon-code-text');
    const hiddenInput = document.getElementById('current-coupon-code');
    const priceElement = document.getElementById('dynamic-price');

    if (pdConfig.coupons && pdConfig.coupons.length > 0) {
        rotatorDiv.style.display = 'block';
        let currentIndex = 0;

        function getProductPrice() {
            if (!priceElement) return 0;
            let priceText = priceElement.innerText || priceElement.textContent;
            let cleaned = priceText.replace(/[^0-9.]/g, '');
            let price = parseFloat(cleaned);
            return isNaN(price) ? 0 : price;
        }

        function updateCoupon() {
            const coupon = pdConfig.coupons[currentIndex];
            let currentPrice = getProductPrice();

            if (currentPrice === 0) return;

            let discount = 0;
            if (coupon.type === 'fixed') {
                discount = parseFloat(coupon.value);
            } else {
                discount = (currentPrice * parseFloat(coupon.value)) / 100;
                if (coupon.max_discount_amount) {
                    discount = Math.min(discount, parseFloat(coupon.max_discount_amount));
                }
            }

            let finalPrice = Math.max(0, currentPrice - discount);
            offerText.textContent = `GET IT FOR ₹${Math.round(finalPrice).toLocaleString('en-IN')}`;
            codeText.innerHTML = `Use <strong style="color: #333;">${coupon.code}</strong>`;
            hiddenInput.value = coupon.code;

            currentIndex = (currentIndex + 1) % pdConfig.coupons.length;
        }

        updateCoupon();
        setInterval(updateCoupon, 5000);
        window.updateCouponDisplay = updateCoupon;
    }
}

function copyCouponCode() {
    const code = document.getElementById('current-coupon-code').value;
    const btn = document.querySelector('#coupon-rotator button');
    if (!code || !btn) return;

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(code).then(() => showCopied(btn)).catch(() => fallbackCopy(code, btn));
    } else {
        fallbackCopy(code, btn);
    }
}

function fallbackCopy(code, btn) {
    const type = document.createElement("input");
    type.value = code;
    document.body.appendChild(type);
    type.select();
    try {
        document.execCommand("copy");
        showCopied(btn);
    } catch (err) {
        console.error('Copy failed', err);
    }
    document.body.removeChild(type);
}

function showCopied(btn) {
    const originalText = btn.textContent;
    btn.textContent = 'COPIED!';
    setTimeout(() => { btn.textContent = originalText; }, 2000);
}

// Share logic
function copyProductLink() {
    const url = window.location.href;
    const btn = document.getElementById('copy-link-btn');
    if (!btn) return;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            const content = btn.innerHTML;
            btn.innerHTML = '<i class="fi fi-rr-check" style="color: #28a745;"></i> <span style="color: #28a745;">Copied!</span>';
            btn.style.borderColor = '#28a745';
            setTimeout(() => {
                btn.innerHTML = content;
                btn.style.borderColor = '#ddd';
            }, 2000);
        }).catch(() => fallbackCopyText(url, btn));
    } else {
        fallbackCopyText(url, btn);
    }
}

function fallbackCopyText(text, btn) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-9999px";
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        const content = btn.innerHTML;
        btn.innerHTML = '<i class="fi fi-rr-check" style="color: #28a745;"></i> <span style="color: #28a745;">Copied!</span>';
        setTimeout(() => { btn.innerHTML = content; }, 2000);
    } catch (err) { console.error(err); }
    document.body.removeChild(textArea);
}

// Cart & Wishlist
function addToCart() {
    let activeBtn = document.querySelector('.metal-btn.active');
    let sizeSelector = document.getElementById('size-selector');
    if (!activeBtn) { alert('Please select a metal type'); return; }
    if (!sizeSelector || !sizeSelector.value) { alert('Please select a size'); return; }

    let materialId = activeBtn.dataset.materialId;
    let sizeId = sizeSelector.value;
    let materialName = activeBtn.textContent;
    let sizeName = sizeSelector.options[sizeSelector.selectedIndex].text;
    let priceText = document.getElementById('dynamic-price').textContent;
    let price = parseFloat(priceText.replace('₹', '').replace(/,/g, '').trim());

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': pdConfig.csrfToken
        },
        body: JSON.stringify({
            product_id: pdConfig.productId,
            quantity: 1,
            metal_configuration: {
                material_id: materialId,
                material_name: materialName,
                size_id: sizeId,
                size_name: sizeName,
                color_id: window.selectedColorId || null,
                color_name: window.selectedColorName || null
            },
            price: price
        })
    })
        .then(response => {
            if (response.status === 401) { window.location.href = pdConfig.loginUrl; return; }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                alert('Product added to cart!');
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = data.cart_count;
            } else if (data) {
                alert(data.message || 'Failed to add to cart.');
            }
        });
}

function toggleWishlist(icon) {
    if (pdConfig.wishlistId) {
        fetch(`/wishlist/remove/${pdConfig.wishlistId}`, {
            method: 'DELETE',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': pdConfig.csrfToken }
        })
            .then(response => {
                if (response.status === 401) { window.location.href = pdConfig.loginUrl; return; }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    icon.classList.replace('fi-sr-heart', 'fi-rr-heart');
                    icon.style.color = 'inherit';
                    pdConfig.wishlistId = null;
                    const wishlistCountEl = document.getElementById('wishlist-count');
                    if (wishlistCountEl && data.wishlist_count !== undefined) wishlistCountEl.textContent = data.wishlist_count;
                }
            });
    } else {
        fetch('/wishlist/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': pdConfig.csrfToken },
            body: JSON.stringify({ product_id: pdConfig.productId })
        })
            .then(response => {
                if (response.status === 401) { window.location.href = pdConfig.loginUrl; return; }
                return response.json();
            })
            .then(data => {
                if (data && data.success) {
                    icon.classList.replace('fi-rr-heart', 'fi-sr-heart');
                    icon.style.color = 'red';
                    pdConfig.wishlistId = data.wishlist_id;
                    const wishlistCountEl = document.getElementById('wishlist-count');
                    if (wishlistCountEl) wishlistCountEl.textContent = data.wishlist_count;
                }
            });
    }
}

function buyNow() {
    let activeBtn = document.querySelector('.metal-btn.active');
    let sizeSelector = document.getElementById('size-selector');
    if (!activeBtn || !sizeSelector || !sizeSelector.value) {
        alert('Please select metal and size'); return;
    }

    let priceText = document.getElementById('dynamic-price').textContent;
    let price = parseFloat(priceText.replace('₹', '').replace(/,/g, '').trim());

    fetch('/checkout/direct', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': pdConfig.csrfToken
        },
        body: JSON.stringify({
            product_id: pdConfig.productId,
            quantity: 1,
            price: price,
            metal_configuration: {
                material_id: activeBtn.dataset.materialId,
                material_name: activeBtn.textContent,
                size_id: sizeSelector.value,
                size_name: sizeSelector.options[sizeSelector.selectedIndex].text,
                color_id: window.selectedColorId || null,
                color_name: window.selectedColorName || null
            }
        })
    })
        .then(async response => {
            if (response.status === 401) { window.location.href = pdConfig.loginUrl; return; }
            const data = await response.json();
            if (data.success) window.location.href = data.redirect_url;
            else alert(data.message || 'Error occurred');
        });
}

// Initializations
document.addEventListener('DOMContentLoaded', function () {
    // Swipers
    if (document.querySelector('.product-thumb-slider')) {
        var thumbSwiper = new Swiper(".product-thumb-slider", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            breakpoints: {
                320: { slidesPerView: 3, spaceBetween: 8 },
                480: { slidesPerView: 4, spaceBetween: 10 }
            }
        });

        new Swiper(".product-main-slider", {
            spaceBetween: 10,
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
            thumbs: { swiper: thumbSwiper },
            on: {
                slideChangeTransitionStart: function () {
                    document.querySelectorAll('.product-main-slider video').forEach(v => {
                        v.pause(); v.currentTime = 0;
                    });
                }
            }
        });
    }

    // Zoom
    const mainSliderSlides = document.querySelectorAll('.product-main-slider .swiper-slide');
    mainSliderSlides.forEach(slide => {
        const img = slide.querySelector('img');
        if (!img) return;
        if (window.innerWidth >= 992) {
            slide.addEventListener('mousemove', function (e) {
                const rect = slide.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                img.style.transformOrigin = `${x}% ${y}%`;
            });
            slide.addEventListener('mouseleave', () => { img.style.transformOrigin = 'center center'; });
        } else {
            slide.addEventListener('click', (e) => {
                const isZoomed = slide.classList.toggle('is-zoomed');
                if (isZoomed) {
                    const rect = slide.getBoundingClientRect();
                    const x = ((e.clientX - rect.left) / rect.width) * 100;
                    const y = ((e.clientY - rect.top) / rect.height) * 100;
                    img.style.transformOrigin = `${x}% ${y}%`;
                } else {
                    img.style.transformOrigin = 'center center';
                }
            });
        }
    });

    // Initial Material/Size
    let keys = Object.keys(pdConfig.productConfigs);
    if (keys.length > 0) {
        let lastKey = keys[keys.length - 1];
        selectMaterial(pdConfig.productConfigs[lastKey].material_id, pdConfig.productConfigs[lastKey].size_id);
    }

    // First Color
    const firstColorBtn = document.querySelector('.color-btn');
    if (firstColorBtn) {
        window.selectedColorId = firstColorBtn.dataset.colorId;
        window.selectedColorName = firstColorBtn.dataset.colorName;
    }

    updateCouponRotator();
});

// Notify Me
function showNotifyModal() { document.getElementById('notifyModal').style.display = 'flex'; }
function closeNotifyModal() { document.getElementById('notifyModal').style.display = 'none'; }

window.onclick = function (event) {
    let modal = document.getElementById('notifyModal');
    if (event.target == modal) closeNotifyModal();
};

const notifyForm = document.getElementById('notifyForm');
if (notifyForm) {
    notifyForm.addEventListener('submit', function (e) {
        e.preventDefault();
        let btn = document.getElementById('notifySubmitBtn');
        let originalTxt = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'SAVING...';

        fetch(pdConfig.notifyUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': pdConfig.csrfToken, 'Accept': 'application/json' },
            body: new FormData(this)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success || data.already_exists) {
                    if (data.success) {
                        document.getElementById('notifyForm').style.display = 'none';
                        document.getElementById('notifySuccess').style.display = 'block';
                    } else {
                        alert(data.message);
                        closeNotifyModal();
                    }
                    let mainBtn = document.getElementById('notify-me-btn');
                    if (mainBtn) {
                        mainBtn.innerHTML = '<i class="fi fi-rr-check" style="margin-right: 8px;"></i> ALREADY REQUESTED';
                        mainBtn.onclick = null; mainBtn.disabled = true;
                        mainBtn.style.cursor = 'default'; mainBtn.style.background = '#607d8b';
                    }
                    if (data.success) setTimeout(closeNotifyModal, 3000);
                } else {
                    alert('Error: ' + data.message);
                    btn.disabled = false; btn.textContent = originalTxt;
                }
            });
    });
}

// Pincode
function checkPincode() {
    const pincodeInput = document.getElementById('pincode-input');
    const messageDiv = document.getElementById('pincode-message');
    if (!pincodeInput || !messageDiv) return;
    const pincode = pincodeInput.value.trim();
    if (!pincode) { messageDiv.innerHTML = '<span style="color: orange;">⚠️ Please enter a pincode</span>'; return; }

    messageDiv.innerHTML = '<span style="color: #666;">⏳ Checking...</span>';
    fetch(pdConfig.pincodeUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': pdConfig.csrfToken },
        body: JSON.stringify({ pincode: pincode })
    })
        .then(res => res.json())
        .then(data => {
            messageDiv.innerHTML = `<span style="color: ${data.available ? 'green' : 'red'};">${data.message}</span>`;
        });
}

// Color Selection
window.selectColor = function (button) {
    document.querySelectorAll('.color-btn').forEach(btn => {
        btn.classList.remove('active');
        const checkmark = btn.querySelector('.checkmark');
        if (checkmark) checkmark.style.display = 'none';
    });
    button.classList.add('active');
    const checkmark = button.querySelector('.checkmark');
    if (checkmark) checkmark.style.display = button.classList.contains('text-btn') ? 'inline' : 'block';
    window.selectedColorId = button.dataset.colorId;
    window.selectedColorName = button.dataset.colorName;
};

// Expose globals for onclick handlers
window.addToCart = addToCart;
window.toggleWishlist = toggleWishlist;
window.buyNow = buyNow;
window.selectMaterial = selectMaterial;
window.selectSize = selectSize;
window.copyProductLink = copyProductLink;
window.checkPincode = checkPincode;
window.showNotifyModal = showNotifyModal;
window.closeNotifyModal = closeNotifyModal;
window.copyCouponCode = copyCouponCode;
