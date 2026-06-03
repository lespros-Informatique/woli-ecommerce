// Working cart functionality for the restaurant website
const CART_BASE_URL = 'http://localhost/resto/public/';

/**
 * Add item to cart with visual feedback
 */
function addToCart(platId, platNom, platPrix, platImage = null) {
    console.log('Adding to cart:', {platId, platNom, platPrix, platImage});
    
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
        success: function(response) {
            console.log('Cart response:', response);
            
            let result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.status == 1) {
                // Success - item added to cart
                showCartSuccess(platNom);
                
                // Update cart count in navigation
                updateCartCount(result.data.cart_count);
                
                // Animate cart icon
                animateCartIcon();
                
            } else {
                // Error from server
                showCartError(result.msg || 'Erreur lors de l\'ajout au panier');
            }
            
            // Restore clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        },
        error: function(xhr, status, error) {
            console.error('Cart error:', xhr.status, error, xhr.responseText);
            
            let errorMessage = 'Erreur de connexion';
            if (xhr.status === 404) errorMessage = 'Endpoint non trouvé';
            if (xhr.status === 500) errorMessage = 'Erreur serveur';
            
            showCartError(errorMessage + ': ' + error);
            
            // Restore clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        }
    });
}
/**
 * Show success message for cart addition
 */
function showCartSuccess(platNom) {
  if (typeof Swal !== 'undefined') {
      Swal.fire({
          title: 'Ajouté au panier !',
          text: `${platNom} a été ajouté à votre panier`,
          icon: 'success',
          timer: 2000,
          showConfirmButton: false,
          position: 'top-center',
          toast: true,
          customClass: {
              popup: 'search-modal-success'
          }
      });
  } else {
      alert(`✅ ${platNom} ajouté au panier !`);
  }
}

/**
 * Show error message for cart operations
 */
function showCartError(message) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Erreur !',
            text: message,
            icon: 'error',
            timer: 3000,
            showConfirmButton: true
        });
    } else {
        alert(`❌ Erreur: ${message}`);
    }
}

/**
 * Update cart count in navigation
 */
function updateCartCount(count) {
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
 * Initialize cart count on page load
 */
function initializeCartCount() {
    $.ajax({
        url: CART_BASE_URL + 'cart/getCount',
        type: 'POST',
        success: function(response) {
            let result = typeof response === 'string' ? JSON.parse(response) : response;
            if (result.status == 1) {
                updateCartCount(result.data.cart_count);
            }
        },
        error: function() {
            // If cart endpoint doesn't exist, initialize with 0
            updateCartCount(0);
        }
    });
}

// Initialize cart when document is ready
$(document).ready(function() {
    console.log('Cart system loaded');
    
    // Initialize cart count
    initializeCartCount();
    
    // Add test button for debugging (remove in production)
    if (window.location.hostname === 'localhost') {
        const testBtn = $('<button style="position:fixed; top:10px; right:10px; z-index:9999; background:#28a745; color:white; padding:8px; border:none; border-radius:4px; font-size:11px;">Test Cart</button>');
        testBtn.click(function() {
            addToCart(1, 'Test Plat', 1500, 'test.jpg');
        });
        $('body').append(testBtn);
    }
});

// Make functions globally available
window.addToCart = addToCart;
window.updateCartCount = updateCartCount;
window.animateCartIcon = animateCartIcon;