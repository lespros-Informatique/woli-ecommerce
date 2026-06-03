// Complete Cart Management System
const CART_BASE_URL = LINK;

/**
 * Add item to cart from menu
 */
function addToCart(platId, platNom, platPrix, platImage = null) {
    console.log('Adding to cart from menu:', { platId, platNom, platPrix, platImage });

    // Get the clicked element for visual feedback
    const clickedElement = event.target.closest('a');
    if (clickedElement) {
        clickedElement.style.opacity = '0.6';
        clickedElement.style.pointerEvents = 'none';
    }

    // Prepare cart data
    const cartData = {
        plat_id: platId,
        nom: platNom,
        prix: platPrix,
        image: platImage || 'f1.png',
        quantite: 1
    };

    console.log('Sending cart request:', cartData);

    // AJAX request to add item to cart
    $.ajax({
        url: CART_BASE_URL + 'cart/add',
        type: 'POST',
        data: cartData,
        success: function (response) {
            console.log('Cart response:', response);

            let result = typeof response === 'string' ? JSON.parse(response) : response;

            if (result.status == 1) {
                // Success - item added to cart
                showCartMessage(`${platNom} a été ajouté au panier`, 'success');

                // Update navigation cart count
                updateNavigationCartCount(result.data.cart_count);

                // Animate cart icon
                animateCartIcon();

            } else {
                // Error from server
                showCartMessage(result.msg || 'Erreur lors de l\'ajout au panier', 'error');
            }

            // Restore clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        },
        error: function (xhr, status, error) {
            console.error('Cart error:', xhr.status, error, xhr.responseText);

            let errorMessage = 'Erreur de connexion';
            if (xhr.status === 404) errorMessage = 'Endpoint non trouvé';
            if (xhr.status === 500) errorMessage = 'Erreur serveur';

            showCartMessage(errorMessage + ': ' + error, 'error');

            // Restore clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        }
    });
}

/**
 * Animate cart icon when item is added
 */
function animateCartIcon() {
    const cartIcon = $('.cart-icon');
    if (cartIcon.length) {
        cartIcon.addClass('bounce-animation');
        setTimeout(() => cartIcon.removeClass('bounce-animation'), 600);
    }
}

/**
 * Update cart quantity (for + and - buttons)
 */
function updateCartQuantity(platId, newQuantity) {
    console.log('Updating cart quantity:', platId, newQuantity);

    // Prevent negative quantities on frontend
    if (newQuantity < 0) {
        newQuantity = 0;
    }

    // Get current quantity for comparison
    const currentQuantity = $(`.cart-item-${platId} input[type="number"]`).val();

    // If quantity would be 0, show SweetAlert confirmation
    if (newQuantity === 0) {
        Swal.fire({
            title: 'Supprimer l\'article ?',
            text: 'Voulez-vous vraiment retirer cet article du panier ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }
            // Continue with removal...
            proceedWithQuantityUpdate(platId, newQuantity, itemElement);
        });
        return;
    }

    proceedWithQuantityUpdate(platId, newQuantity, itemElement);
}

function proceedWithQuantityUpdate(platId, newQuantity, itemElement) {
    // Show loading state
    const proceedItemElement = $(`.cart-item-${platId}`);
    if (proceedItemElement.length) {
        proceedItemElement.addClass('loading');
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
                // Check server response first
                if (result.data.cart_count == 0) {
                    // Cart is now empty, show empty state
                    showEmptyCartMessage();
                } else if (newQuantity === 0) {
                    // Item was removed (quantity became 0)
                    removeItemFromDOM(platId);
                } else {
                    // Update quantity display for valid quantities
                    updateQuantityDisplay(platId, newQuantity);
                }

                // Update totals and navigation
                updateCartTotals(result.data.cart_total, result.data.cart_count);
                updateNavigationCartCount(result.data.cart_count);

                if (newQuantity === 0) {
                    showCartMessage('✅ Article supprimé du panier', 'success');
                } else {
                    showCartMessage('✅ Quantité mise à jour', 'success');
                }

            } else {
                showCartMessage('❌ Erreur: ' + result.msg, 'error');
            }

            // Remove loading state
            if (itemElement.length) {
                itemElement.removeClass('loading');
            }
        },
        error: function (xhr, status, error) {
            console.error('Update error:', error);
            showCartMessage('❌ Erreur de mise à jour', 'error');

            // Remove loading state
            if (itemElement.length) {
                itemElement.removeClass('loading');
            }
        }
    });
}

/**
 * Remove item from cart completely
 */
function removeFromCart(platId) {
    console.log('Removing from cart:', platId);

    Swal.fire({
        title: 'Supprimer l\'article ?',
        text: 'Voulez-vous vraiment retirer cet article du panier ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (!result.isConfirmed) {
            return;
        }

        // Show loading state
        const removeActionItemElement = $(`.cart-item-${platId}`);
        if (removeActionItemElement.length) {
            removeActionItemElement.addClass('loading');
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
                    // Remove from DOM with animation
                    removeItemFromDOM(platId);

                    // Update totals
                    updateCartTotals(result.data.cart_total, result.data.cart_count);

                    // Update navigation cart count
                    updateNavigationCartCount(result.data.cart_count);

                    // Check if cart is empty
                    if (result.data.cart_count == 0) {
                        setTimeout(() => {
                            showEmptyCartMessage();
                        }, 500);
                    }

                    showCartMessage('✅ Article supprimé du panier', 'success');

                } else {
                    showCartMessage('❌ Erreur: ' + result.msg, 'error');
                }
            },
            error: function (xhr, status, error) {
                console.error('Remove error:', error);
                showCartMessage('❌ Erreur de suppression', 'error');
            }
        });
    });
}

/**
 * Clear entire cart
 */
function clearCart() {
    console.log('Clearing entire cart');

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
        if (!result.isConfirmed) {
            return;
        }

        // Show loading state
        $('.cart-items').addClass('loading');

        $.ajax({
            url: CART_BASE_URL + 'cart/clear',
            type: 'POST',
            success: function (response) {
                console.log('Clear response:', response);

                let result = typeof response === 'string' ? JSON.parse(response) : response;

                if (result.status == 1) {
                    // Clear cart items from DOM
                    $('.cart-items').empty();

                    // Update totals
                    updateCartTotals(0, 0);

                    // Update navigation cart count
                    updateNavigationCartCount(0);

                    // Show empty cart message
                    showEmptyCartMessage();

                    showCartMessage('✅ Panier vidé avec succès', 'success');

                } else {
                    showCartMessage('❌ Erreur: ' + result.msg, 'error');
                }

                // Remove loading state
                $('.cart-items').removeClass('loading');
            },
            error: function (xhr, status, error) {
                console.error('Clear error:', error);
                showCartMessage('❌ Erreur de suppression', 'error');

                // Remove loading state
                $('.cart-items').removeClass('loading');
            }
        });
    });
}

/**
 * Update quantity display in DOM
 */
function updateQuantityDisplay(platId, quantity) {
    const quantityInput = $(`.cart-item-${platId} input[type="number"]`);
    if (quantityInput.length) {
        quantityInput.val(quantity);

        // Disable minus button if quantity is 1
        const minusBtn = $(`.cart-item-${platId} .quantity-btn:first`);
        if (minusBtn.length) {
            minusBtn.prop('disabled', quantity <= 1);
        }
    }
}

/**
 * Remove item from DOM with animation
 */
function removeItemFromDOM(platId) {
    const removeItemElement = $(`.cart-item-${platId}`);
    if (removeItemElement.length) {
        // Get count of remaining items before removal
        const remainingItems = $('.cart-item').length;

        removeItemElement.fadeOut(300, function () {
            $(this).remove();

            // Check if this was the last item
            if (remainingItems <= 1) {
                // This was the last item, show empty cart
                showEmptyCartMessage();
            }
        });
    }
}

/**
 * Update cart totals in the summary section
 */
function updateCartTotals(total, count) {
    // Update subtotal and total
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
        if (count > 0) {
            checkoutBtn.prop('disabled', false);
        } else {
            checkoutBtn.prop('disabled', true);
        }
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
 * Show empty cart message
 */
function showEmptyCartMessage() {
    const cartContainer = $('.cart-items');
    const cartItems = cartContainer.find('.cart-item');

    // Only show empty cart message if there are actually no items
    if (cartContainer.length && cartItems.length === 0) {
        cartContainer.html(`
            <div class="empty-cart text-center py-5">
                <i class="fa fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h4>Votre panier est vide</h4>
                <p class="text-muted mb-4">Ajoutez des articles pour commencer votre commande</p>
                <a href="${CART_BASE_URL}../menu" class="btn btn-primary btn-lg">
                    <i class="fa fa-utensils"></i> Voir le Menu
                </a>
            </div>
        `);

        // Update totals to 0
        updateCartTotals(0, 0);
    }
}

/**
 * Show cart message (success/error)
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
        // Fallback to basic alert
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
 * Checkout function
 */
function checkout() {
    const adresse = document.getElementById('adresse_livraison').value.trim();
    const instructions = document.getElementById('instructions').value.trim();
    const methode = document.getElementById('methode_paiement').value;

    if (!adresse) {
        showCartMessage('Veuillez entrer votre adresse de livraison', 'warning');
        document.getElementById('adresse_livraison').focus();
        return;
    }

    showCartMessage('Création de votre commande...', 'info');

    $.ajax({
        url: LINK + 'commandes/createFromCart',
        type: 'POST',
        data: {
            adresse_livraison: adresse,
            instructions: instructions,
            methode_paiement: methode
        },
        success: function (response) {
            let result = typeof response === 'string' ? JSON.parse(response) : response;
            if (result.status == 1) {
                showCartMessage('Commande créée avec succès !', 'success');
                setTimeout(() => {
                    window.location.href = LIEN + '/commandes';
                }, 2000);
            } else {
                showCartMessage('Erreur: ' + result.msg, 'error');
            }
        },
        error: function (xhr, status, error) {
            console.error('Checkout error:', error);
            showCartMessage('Erreur lors de la création de la commande', 'error');
        }
    });
}

// Initialize cart management when document is ready
$(document).ready(function () {
    console.log('Cart management system loaded');

    // Disable minus buttons for items with quantity 1
    $('.cart-item').each(function () {
        const platId = $(this).attr('class').match(/cart-item-(\d+)/);
        const quantity = $(this).find('input[type="number"]').val();

        if (platId && quantity <= 1) {
            $(this).find('.quantity-btn:first').prop('disabled', true);
        }
    });

    // Add loading animation styles
    const style = `
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
            .cart-items.loading {
                opacity: 0.6;
                pointer-events: none;
            }
        </style>
    `;
    $('head').append(style);
});

// Make functions globally available
window.addToCart = addToCart;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.clearCart = clearCart;
window.checkout = checkout;
window.updateNavigationCartCount = updateNavigationCartCount;

// Create alias for navigation compatibility
window.updateCartIcon = function (count) {
    updateNavigationCartCount(count);
};