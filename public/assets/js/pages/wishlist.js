let itemToDelete = null;

function removeFromWishlist(wishlistId) {
    itemToDelete = wishlistId;
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('active'), 10);
    }
}

function closeModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('active');
        setTimeout(() => modal.style.display = 'none', 300);
    }
    itemToDelete = null;
}

function moveToCart(wishlistId) {
    fetch(`/wishlist/move-to-cart/${wishlistId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Product moved to cart!');
                location.reload();
            } else {
                alert(data.message || 'Failed to move to cart');
            }
        })
        .catch(err => console.error(err));
}

document.addEventListener('DOMContentLoaded', function () {
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (!itemToDelete) return;

            fetch(`/wishlist/remove/${itemToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const item = document.getElementById(`wishlist-item-${itemToDelete}`);
                        if (item) {
                            item.style.transition = 'all 0.3s ease';
                            item.style.opacity = '0';
                            item.style.transform = 'scale(0.9)';
                        }

                        closeModal();

                        setTimeout(() => {
                            if (item) item.remove();
                            const grid = document.querySelector('.wishlist-grid');
                            if (!grid || grid.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                })
                .catch(err => {
                    console.error(err);
                    closeModal();
                });
        });
    }
});

// Expose globals for onclick handlers
window.removeFromWishlist = removeFromWishlist;
window.closeModal = closeModal;
window.moveToCart = moveToCart;
