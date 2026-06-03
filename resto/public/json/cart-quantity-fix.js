/**
 * Cart Quantity Management Fix
 * Handles proper decrement/increment functionality
 */

$(document).ready(function () {
    console.log('Cart quantity fix loaded');

    // Handle plus/minus button clicks with event delegation
    $(document).on('click', '.quantity-btn', function (e) {
        e.preventDefault();

        const button = $(this);
        const cartItem = button.closest('.cart-item');
        const platId = cartItem.attr('class').match(/cart-item-(\d+)/);

        if (!platId) {
            console.error('Could not extract plat ID from cart item');
            return;
        }

        const quantityInput = cartItem.find('input[type="number"]');
        const currentQuantity = parseInt(quantityInput.val()) || 0;

        // Determine action based on button type
        const isPlus = button.hasClass('plus-btn') || button.text().includes('+');
        const isMinus = button.hasClass('minus-btn') || button.text().includes('−') || button.text().includes('-');

        let newQuantity;

        if (isPlus) {
            // Increment quantity
            newQuantity = currentQuantity + 1;
            console.log('Incrementing quantity from', currentQuantity, 'to', newQuantity);
        } else if (isMinus) {
            // Decrement quantity (don't go below 1)
            if (currentQuantity <= 1) {
                // If quantity is 1, ask to remove the item entirely
                Swal.fire({
                    title: 'Supprimer l\'article ?',
                    text: 'Voulez-vous retirer cet article du panier ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        removeItemFromCart(platId[1]);
                    }
                });
                return;
            } else {
                newQuantity = currentQuantity - 1;
                console.log('Decrementing quantity from', currentQuantity, 'to', newQuantity);
            }
        } else {
            console.error('Unknown button type');
            return;
        }

        // Update quantity
        updateCartQuantity(platId[1], newQuantity);
    });

    // Handle direct input changes
    $(document).on('change', '.cart-item input[type="number"]', function () {
        const input = $(this);
        const cartItem = input.closest('.cart-item');
        const platId = cartItem.attr('class').match(/cart-item-(\d+)/);
        const newQuantity = parseInt(input.val()) || 0;

        if (platId && newQuantity > 0) {
            updateCartQuantity(platId[1], newQuantity);
        } else if (newQuantity === 0) {
            // Remove item if quantity is set to 0
            Swal.fire({
                title: 'Supprimer l\'article ?',
                text: 'Voulez-vous retirer cet article du panier ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeItemFromCart(platId[1]);
                } else {
                    // Restore previous value
                    input.val(1);
                }
            });
        }
    });
});

/**
 * Update cart quantity properly
 */
function updateCartQuantity(platId, newQuantity) {
    console.log('Updating cart quantity:', platId, newQuantity);

    // Prevent negative quantities
    if (newQuantity < 0) {
        newQuantity = 0;
    }

    // Find the cart item element
    const itemElement = $(`.cart-item-${platId}`);
    if (!itemElement.length) {
        console.error('Cart item not found for ID:', platId);
        return;
    }

    // Show loading state
    itemElement.addClass('loading');

    // Make AJAX request
    $.ajax({
        url: LINK + 'cart/update',
        type: 'POST',
        data: {
            plat_id: platId,
            quantite: newQuantity
        },
        success: function (response) {
            console.log('Cart update response:', response);

            let result = typeof response === 'string' ? JSON.parse(response) : response;

            if (result.status == 1) {
                if (newQuantity === 0) {
                    // Remove item completely
                    removeItemFromDOM(platId);
                    showCartMessage('✅ Article supprimé du panier', 'success');
                } else {
                    // Update quantity display
                    updateQuantityDisplay(platId, newQuantity);
                    showCartMessage('✅ Quantité mise à jour', 'success');
                }

                // Update totals
                updateCartTotals(result.data.cart_total, result.data.cart_count);
                updateNavigationCartCount(result.data.cart_count);

            } else {
                showCartMessage('❌ Erreur: ' + result.msg, 'error');
                // Restore previous value on error
                const quantityInput = itemElement.find('input[type="number"]');
                quantityInput.val(quantityInput.data('original-value') || 1);
            }

            // Remove loading state
            itemElement.removeClass('loading');
        },
        error: function (xhr, status, error) {
            console.error('Cart update error:', error);
            showCartMessage('❌ Erreur de mise à jour', 'error');
            itemElement.removeClass('loading');
        }
    });
}

/**
 * Remove item from cart entirely
 */
function removeItemFromCart(platId) {
    console.log('Removing item from cart:', platId);

    const itemElement = $(`.cart-item-${platId}`);
    if (!itemElement.length) {
        console.error('Cart item not found for removal:', platId);
        return;
    }

    itemElement.addClass('loading');

    $.ajax({
        url: LINK + 'cart/remove',
        type: 'POST',
        data: {
            plat_id: platId
        },
        success: function (response) {
            console.log('Remove response:', response);

            let result = typeof response === 'string' ? JSON.parse(response) : response;

            if (result.status == 1) {
                removeItemFromDOM(platId);
                updateCartTotals(result.data.cart_total, result.data.cart_count);
                updateNavigationCartCount(result.data.cart_count);
                showCartMessage('✅ Article supprimé du panier', 'success');
            } else {
                showCartMessage('❌ Erreur: ' + result.msg, 'error');
            }

            itemElement.removeClass('loading');
        },
        error: function (xhr, status, error) {
            console.error('Remove error:', error);
            showCartMessage('❌ Erreur de suppression', 'error');
            itemElement.removeClass('loading');
        }
    });
}

/**
 * Update quantity display in DOM
 */
function updateQuantityDisplay(platId, quantity) {
    const quantityInput = $(`.cart-item-${platId} input[type="number"]`);
    if (quantityInput.length) {
        quantityInput.val(quantity);

        // Disable/enable minus button based on quantity
        const minusBtn = $(`.cart-item-${platId} .minus-btn, .cart-item-${platId} .quantity-btn:eq(0)`);
        if (minusBtn.length) {
            minusBtn.prop('disabled', quantity <= 1);
        }
    }
}

/**
 * Remove item from DOM with animation
 */
function removeItemFromDOM(platId) {
    const itemElement = $(`.cart-item-${platId}`);
    if (itemElement.length) {
        const remainingItems = $('.cart-item').length;

        itemElement.fadeOut(300, function () {
            $(this).remove();

            if (remainingItems <= 1) {
                showEmptyCartMessage();
            }
        });
    }
}

/**
 * Update cart totals
 */
function updateCartTotals(total, count) {
    const subtotalElement = $('#cart-total');
    const finalTotalElement = $('#cart-total-final');

    if (subtotalElement.length) {
        subtotalElement.text(formatCurrency(total));
    }

    if (finalTotalElement.length) {
        finalTotalElement.text(formatCurrency(total));
    }

    // Update checkout button state
    const checkoutBtn = $('button[onclick="checkout()"]');
    if (checkoutBtn.length) {
        checkoutBtn.prop('disabled', count <= 0);
    }
}

/**
 * Update navigation cart count
 */
function updateNavigationCartCount(count) {
    const cartCount = $('.cart-count');
    if (cartCount.length) {
        cartCount.text(count);

        if (count > 0) {
            cartCount.show().addClass('pulse');
            setTimeout(() => cartCount.removeClass('pulse'), 600);
        } else {
            cartCount.hide();
        }
    }
}

/**
 * Show cart message
 */
function showCartMessage(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Succès' : 'Erreur',
            text: message,
            icon: type,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
}

/**
 * Show empty cart message
 */
function showEmptyCartMessage() {
    const cartContainer = $('.cart-items');
    const cartItems = cartContainer.find('.cart-item');

    if (cartContainer.length && cartItems.length === 0) {
        cartContainer.html(`
            <div class="empty-cart text-center py-5">
                <i class="fa fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h4>Votre panier est vide</h4>
                <p class="text-muted mb-4">Ajoutez des articles pour commencer votre commande</p>
                <a href="${LINK}../menu" class="btn btn-primary btn-lg">
                    <i class="fa fa-utensils"></i> Voir le Menu
                </a>
            </div>
        `);

        updateCartTotals(0, 0);
    }
}

// Make functions globally available
window.updateCartQuantity = updateCartQuantity;
window.removeItemFromCart = removeItemFromCart;
window.updateNavigationCartCount = updateNavigationCartCount;