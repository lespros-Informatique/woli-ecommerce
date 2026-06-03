// Simple cart test
$(document).ready(function() {
    console.log('Test cart script loaded');
    
    // Test basic function availability
    console.log('LINK:', LINK);
    console.log('showAlert function:', typeof showAlert);
    console.log('addToCart function:', typeof addToCart);
    
    // Add simple test button
    const testBtn = $('<button style="position:fixed; top:10px; right:10px; z-index:9999; background:#007bff; color:white; padding:10px; border:none; border-radius:5px;">Test Cart Direct</button>');
    testBtn.click(function() {
        console.log('Testing cart directly...');
        console.log('Calling addToCart with:', 1, 'Test Plat', 1000, 'test.png');
        
        try {
            addToCart(1, 'Test Plat', 1000, 'test.png', {target: testBtn[0]});
        } catch (error) {
            console.error('Error calling addToCart:', error);
        }
    });
    $('body').append(testBtn);
    
    // Also add individual cart icon click handlers
    setTimeout(function() {
        console.log('Adding click handlers to cart icons...');
        $('a[onclick*="addToCart"]').off('click.cartTest').on('click.cartTest', function(e) {
            console.log('Cart icon clicked, triggering addToCart...');
            console.log('Event target:', e.target);
            console.log('Link href:', $(this).attr('href'));
            // Let the original onclick handle it, just log for now
        });
    }, 1000);
});