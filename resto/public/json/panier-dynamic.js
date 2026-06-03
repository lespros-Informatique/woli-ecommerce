// Enhanced Cart Management System for Panier Page
const CART_BASE_URL = LINK;

$(document).ready(function () {
    console.log('Enhanced cart page initialized');

    // Initialize cart management
    initializeCartButtons();
    initializeCartCount();
    updateCheckoutButtonState();

    // Add visual feedback for all cart buttons
    addCartButtonEffects();
});

/**
 * Initialize all cart button event handlers
 */
function initializeCartButtons() {
    // Handle quantity decrease buttons
    $(document).on('click', '.quantity-btn[data-action="decrease"]', function (e) {
        e.preventDefault();
        const btn = $(this);
        const platId = btn.data('plat-id');
        const currentQty = btn.closest('.cart-item').find('input[type="number"]').val();
        const newQty = Math.max(0, parseInt(currentQty) - 1);

        if (newQty === 0) {
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
                    updateCartQuantity(platId, newQty);
                }
            });
        } else {
            updateCartQuantity(platId, newQty);
        }
    });

    // Handle quantity increase buttons
    $(document).on('click', '.quantity-btn[data-action="increase"]', function (e) {
        e.preventDefault();
        const btn = $(this);
        const platId = btn.data('plat-id');
        const currentQty = btn.closest('.cart-item').find('input[type="number"]').val();
        const newQty = parseInt(currentQty) + 1;

        updateCartQuantity(platId, newQty);
    });

    // Handle delete buttons
    $(document).on('click', '.delete-item-btn', function (e) {
        e.preventDefault();
        const btn = $(this);
        const platId = btn.data('plat-id');
        const platNom = btn.data('plat-nom');

        Swal.fire({
            title: 'Supprimer l\'article ?',
            text: `Voulez-vous retirer "${platNom}" du panier ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                removeFromCart(platId);
            }
        });
    });

    // Handle clear cart button
    $(document).on('click', '.clear-cart-btn', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Vider le panier ?',
            text: 'Êtes-vous sûr de vouloir supprimer tous les articles ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, tout vider',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                clearCart();
            }
        });
    });

    // Handle direct quantity input changes
    $(document).on('change', '.cart-item input[type="number"]', function () {
        const input = $(this);
        const platId = input.closest('.cart-item').attr('class').match(/cart-item-(\d+)/);

        if (platId) {
            let newQty = parseInt(input.val());
            if (isNaN(newQty) || newQty < 0) {
                newQty = 0;
            }

            updateCartQuantity(platId[1], newQty);
        }
    });
}

/**
 * Update cart quantity with enhanced UI feedback
 */
function updateCartQuantity(platId, newQuantity) {
    console.log('Updating cart quantity:', platId, newQuantity);

    // Prevent negative quantities
    if (newQuantity < 0) {
        newQuantity = 0;
    }

    // Show loading state
    const itemElement = $(`.cart-item-${platId}`);
    if (itemElement.length) {
        itemElement.addClass('loading');
        itemElement.find('button, input').prop('disabled', true);
    }

    $.ajax({
        url: CART_BASE_URL + 'cart/update',
        type: 'POST',
        data: {
            plat_id: platId,
            quantite: newQuantity
        },
        success: function (response) {
            console.log('Update response:', response);

            let result = typeof response === 'string' ? JSON.parse(response) : response;

            if (result.status == 1) {
                // Update UI based on response
                if (result.data.cart_count == 0) {
                    showEmptyCartMessage();
                } else if (newQuantity === 0) {
                    removeItemFromDOM(platId);
                } else {
                    updateQuantityDisplay(platId, newQuantity);
                }

                // Update totals and counts
                updateCartTotals(result.data.cart_total, result.data.cart_count);
                updateNavigationCartCount(result.data.cart_count);
                updateCheckoutButtonState();

                // Show success message
                showCartMessage('✅ Quantité mise à jour', 'success');

            } else {
                showCartMessage('❌ Erreur: ' + (result.msg || 'Mise à jour échouée'), 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Update error:', error);
            showCartMessage('❌ Erreur de connexion lors de la mise à jour', 'error');
        },
        complete: function () {
            // Remove loading state
            if (itemElement.length) {
                itemElement.removeClass('loading');
                itemElement.find('button, input').prop('disabled', false);
            }
        }
    });
}

/**
 * Remove item from cart with enhanced feedback
 */
function removeFromCart(platId) {
    console.log('Removing from cart:', platId);

    // Show loading state
    const itemElement = $(`.cart-item-${platId}`);
    if (itemElement.length) {
        itemElement.addClass('loading');
        itemElement.find('button').prop('disabled', true);
    }

    $.ajax({
        url: CART_BASE_URL + 'cart/remove',
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
                updateCheckoutButtonState();

                showCartMessage('✅ Article supprimé du panier', 'success');

            } else {
                showCartMessage('❌ Erreur: ' + (result.msg || 'Suppression échouée'), 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Remove error:', error);
            showCartMessage('❌ Erreur de connexion lors de la suppression', 'error');
        },
        complete: function () {
            if (itemElement.length) {
                itemElement.removeClass('loading');
            }
        }
    });
}

/**
 * Clear entire cart with confirmation
 */
function clearCart() {
    console.log('Clearing entire cart');

    // Show loading state
    $('.cart-items').addClass('loading');
    $('.clear-cart-btn, .cart-item button').prop('disabled', true);

    $.ajax({
        url: CART_BASE_URL + 'cart/clear',
        type: 'POST',
        success: function (response) {
            console.log('Clear response:', response);

            let result = typeof response === 'string' ? JSON.parse(response) : response;

            if (result.status == 1) {
                $('.cart-items').empty();
                updateCartTotals(0, 0);
                updateNavigationCartCount(0);
                updateCheckoutButtonState();
                showEmptyCartMessage();

                showCartMessage('✅ Panier vidé avec succès', 'success');

            } else {
                showCartMessage('❌ Erreur: ' + (result.msg || 'Vidage échoué'), 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Clear error:', error);
            showCartMessage('❌ Erreur de connexion lors du vidage', 'error');
        },
        complete: function () {
            $('.cart-items').removeClass('loading');
            $('.clear-cart-btn, .cart-item button').prop('disabled', false);
        }
    });
}

/**
 * Update quantity display in DOM
 */
function updateQuantityDisplay(platId, quantity) {
    const itemElement = $(`.cart-item-${platId}`);
    const quantityInput = itemElement.find('input[type="number"]');
    const minusBtn = itemElement.find('.quantity-btn[data-action="decrease"]');

    if (quantityInput.length) {
        quantityInput.val(quantity);
    }

    if (minusBtn.length) {
        minusBtn.prop('disabled', quantity <= 1);
    }

    // Update item total price
    const pricePerUnit = itemElement.find('.font-weight-bold').first().text().replace(/[^\d]/g, '');
    const totalElement = itemElement.find('.font-weight-bold').last();
    const newTotal = parseInt(pricePerUnit) * quantity;

    if (totalElement.length) {
        totalElement.text(formatCurrency(newTotal));
    }
}

/**
 * Remove item from DOM with animation
 */
function removeItemFromDOM(platId) {
    const itemElement = $(`.cart-item-${platId}`);

    if (itemElement.length) {
        itemElement.fadeOut(300, function () {
            const remainingItems = $('.cart-item').length;
            $(this).remove();

            // If this was the last item, show empty cart
            if (remainingItems <= 1) {
                setTimeout(() => showEmptyCartMessage(), 300);
            }
        });
    }
}

/**
 * Update cart totals in summary section
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
}

/**
 * Update checkout button state
 */
function updateCheckoutButtonState() {
    const cartCount = $('.cart-item').length;
    const checkoutBtn = $('button[onclick="checkout()"]');

    if (checkoutBtn.length) {
        checkoutBtn.prop('disabled', cartCount === 0);
    }
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
                <a href="${CART_BASE_URL}menu" class="btn btn-primary btn-lg">
                    <i class="fa fa-utensils"></i> Voir le Menu
                </a>
            </div>
        `);

        updateCartTotals(0, 0);
        updateCheckoutButtonState();
    }
}

/**
 * Add visual effects to cart buttons
 */
function addCartButtonEffects() {
    // Add hover and active effects
    $(document).on('mouseenter', '.quantity-btn, .delete-item-btn, .clear-cart-btn', function () {
        $(this).addClass('hover-effect');
    });

    $(document).on('mouseleave', '.quantity-btn, .delete-item-btn, .clear-cart-btn', function () {
        $(this).removeClass('hover-effect');
    });

    $(document).on('click', '.quantity-btn, .delete-item-btn, .clear-cart-btn', function () {
        $(this).addClass('click-effect');
        setTimeout(() => $(this).removeClass('click-effect'), 200);
    });
}

/**
 * Initialize navigation cart count
 */
function initializeCartCount() {
    $.ajax({
        url: LINK + 'cart/getCount',
        type: 'POST',
        success: function (rep) {
            let response = typeof rep === 'string' ? JSON.parse(rep) : rep;
            if (response.status == 1) {
                updateNavigationCartCount(response.data.cart_count);
            }
        },
        error: function () {
            console.error('Failed to get cart count');
        }
    });
}

/**
 * Show cart message with enhanced styling
 */
function showCartMessage(message, type = 'info') {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Succès' : type === 'error' ? 'Erreur' : 'Information',
            text: message,
            icon: type,
            timer: 2500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    } else {
        // Fallback to basic alert with better styling
        const alertClass = type === 'success' ? 'alert-success' :
            type === 'error' ? 'alert-danger' : 'alert-info';

        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
                ${message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        `;

        $('body').append(alertHtml);

        // Auto-remove after 3 seconds
        setTimeout(() => {
            $('.alert').alert('close');
        }, 3000);
    }
}

/**
 * Format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
}

// Enhanced CSS for cart interactions
const cartStyles = `
    <style>
        .cart-item.loading {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }
        .cart-item.loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: cart-loading 1s linear infinite;
        }
        @keyframes cart-loading {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .quantity-btn.hover-effect,
        .delete-item-btn.hover-effect,
        .clear-cart-btn.hover-effect {
            transform: scale(1.05);
            transition: transform 0.2s ease;
        }
        
        .quantity-btn.click-effect,
        .delete-item-btn.click-effect,
        .clear-cart-btn.click-effect {
            transform: scale(0.95);
            transition: transform 0.1s ease;
        }
        
        .cart-items.loading {
            opacity: 0.6;
            pointer-events: none;
        }
        
        .cart-item {
            transition: all 0.3s ease;
        }
        
        .cart-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
`;

$('head').append(cartStyles);

// Make functions globally available
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.clearCart = clearCart;
window.updateNavigationCartCount = updateNavigationCartCount;