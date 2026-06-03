// Simple, reliable cart functionality
const CART_BASE_URL = 'http://localhost/resto/public/';

function simpleAddToCart(platId, platNom, platPrix, platImage = null) {
    console.log('Adding to cart:', {platId, platNom, platPrix, platImage});
    
    // Disable the clicked element
    const clickedElement = event.target.closest('a');
    if (clickedElement) {
        clickedElement.style.opacity = '0.5';
        clickedElement.style.pointerEvents = 'none';
    }
    
    // Prepare data
    const data = {
        plat_id: platId,
        nom: platNom,
        prix: platPrix,
        image: platImage || 'default.jpg',
        quantite: 1
    };
    
    console.log('Sending AJAX request with data:', data);
    
    // Make AJAX request
    $.ajax({
        url: CART_BASE_URL + 'cart/add',
        type: 'POST',
        data: data,
        success: function(response) {
            console.log('Cart response:', response);
            
            // Parse response if it's a string
            let result = typeof response === 'string' ? JSON.parse(response) : response;
            
            if (result.status == 1) {
                // Success
                alert('✅ ' + platNom + ' ajouté au panier avec succès!');
                
                // Update cart count
                const cartCount = $('.cart-count');
                if (cartCount.length) {
                    const currentCount = parseInt(cartCount.text()) || 0;
                    const newCount = currentCount + 1;
                    cartCount.text(newCount).show();
                    cartCount.addClass('pulse');
                    setTimeout(() => cartCount.removeClass('pulse'), 600);
                }
                
                // Animate cart icon
                $('.cart-icon').addClass('bounce-animation');
                setTimeout(() => $('.cart-icon').removeClass('bounce-animation'), 600);
                
            } else {
                // Error
                alert('❌ Erreur: ' + (result.msg || 'Erreur inconnue'));
            }
            
            // Re-enable the clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        },
        error: function(xhr, status, error) {
            console.error('Cart error:', xhr.status, error, xhr.responseText);
            
            let errorMsg = 'Erreur de connexion';
            if (xhr.status === 404) {
                errorMsg = 'Endpoint non trouvé';
            } else if (xhr.status === 500) {
                errorMsg = 'Erreur serveur';
            }
            
            alert('❌ ' + errorMsg + ': ' + error);
            
            // Re-enable the clicked element
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        }
    });
}

// Initialize on page load
$(document).ready(function() {
    console.log('Simple cart script loaded');
    
    // Add test button
    const testBtn = $('<button style="position:fixed; top:10px; right:10px; z-index:9999; background:#28a745; color:white; padding:10px; border:none; border-radius:5px; font-size:12px;">Test Cart</button>');
    testBtn.click(function() {
        console.log('Testing simple cart...');
        simpleAddToCart(1, 'Plat Test', 1500, 'test.jpg');
    });
    $('body').append(testBtn);
});

// Make function globally available
window.simpleAddToCart = simpleAddToCart;