// Direct cart test - minimal debugging
$(document).ready(function() {
    console.log('=== DIRECT CART TEST ===');
    
    // Check LINK constant
    console.log('LINK constant:', typeof LINK, LINK);
    
    // Check if jQuery is available
    console.log('jQuery available:', typeof $ !== 'undefined');
    console.log('SweetAlert available:', typeof Swal !== 'undefined');
    
    // Add debug button
    const debugBtn = $('<div style="position:fixed; top:10px; right:10px; z-index:9999; background:rgba(0,0,0,0.8); color:white; padding:10px; border-radius:5px; font-family:monospace; font-size:12px;">Debug Panel<br><button onclick="testDirectCart()" style="margin:5px; padding:5px; background:#28a745; color:white; border:none;">Test Direct Cart</button><br><button onclick="checkConsole()" style="margin:5px; padding:5px; background:#007bff; color:white; border:none;">Check Console</button></div>');
    $('body').append(debugBtn);
    
    // Make test function global
    window.testDirectCart = function() {
        console.log('=== TESTING DIRECT CART CALL ===');
        
        if (typeof $ === 'undefined') {
            console.error('jQuery not loaded!');
            alert('jQuery not loaded!');
            return;
        }
        
        const testData = {
            plat_id: 1,
            nom: 'Test Plat Direct',
            prix: 1500,
            image: 'test.jpg',
            quantite: 1
        };
        
        console.log('Test data:', testData);
        console.log('URL to call:', LINK + 'cart/add');
        
        $.ajax({
            url: LINK + 'cart/add',
            type: 'POST',
            data: testData,
            success: function(response) {
                console.log('SUCCESS response:', response);
                alert('Cart test success! Check console.');
            },
            error: function(xhr, status, error) {
                console.error('ERROR response:', xhr.status, status, error);
                console.error('Response text:', xhr.responseText);
                alert('Cart test failed! Check console.');
            }
        });
    };
    
    window.checkConsole = function() {
        alert('Check browser console (F12) for detailed logs');
    };
    
    // Test if cart function exists
    setTimeout(function() {
        console.log('addToCart function exists:', typeof addToCart);
        if (typeof addToCart === 'undefined') {
            console.error('addToCart function not found!');
            alert('addToCart function not found! Check if func.js is loaded.');
        }
    }, 1000);
});