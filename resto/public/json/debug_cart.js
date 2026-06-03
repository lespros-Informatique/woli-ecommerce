// Debug script for cart functionality
console.log('Debug cart script loaded');

// Test if addToCart function exists
console.log('addToCart function exists:', typeof addToCart);

// Test AJAX endpoint
function testCartEndpoint() {
    console.log('Testing cart endpoint...');
    $.ajax({
        url: LINK + 'cart/add',
        type: 'POST',
        data: {
            plat_id: 1,
            nom: 'Test Plat',
            prix: 1000,
            image: 'test.png',
            quantite: 1
        },
        success: function(rep) {
            console.log('Cart endpoint success:', rep);
        },
        error: function(xhr, status, error) {
            console.log('Cart endpoint error:', error);
        }
    });
}

// Add click test to all cart icons
$(document).ready(function() {
    // Test link
    if (typeof console !== 'undefined') {
        console.log('LINK constant:', LINK);
        console.log('Functions available:', typeof showAlert, typeof addToCart);
    }
    
    // Test button to verify functionality
    const testButton = $('<button style="position:fixed; top:10px; right:10px; z-index:9999; background:red; color:white; padding:10px;">Test Cart</button>');
    testButton.click(function() {
        testCartEndpoint();
    });
    $('body').append(testButton);
});