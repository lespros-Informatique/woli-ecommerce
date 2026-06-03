// Minimal cart test without external dependencies
$(document).ready(function() {
    console.log('=== MINIMAL CART TEST ===');
    
    // Add minimal debug UI
    const minimalBtn = $('<button style="position:fixed; top:10px; left:10px; z-index:9999; background:#dc3545; color:white; padding:10px; border:none; border-radius:3px; font-size:12px;">Minimal Cart Test</button>');
    minimalBtn.click(function() {
        console.log('=== MINIMAL AJAX TEST ===');
        
        // Basic AJAX test without SweetAlert dependency
        $.ajax({
            url: LINK + 'cart/add',
            type: 'POST',
            data: {
                plat_id: 999,
                nom: 'Minimal Test',
                prix: 1000,
                image: 'test.jpg',
                quantite: 1
            },
            success: function(response) {
                console.log('MINIMAL SUCCESS:', response);
                alert('AJAX Success! Response: ' + JSON.stringify(response));
            },
            error: function(xhr, status, error) {
                console.error('MINIMAL ERROR:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    error: error,
                    url: LINK + 'cart/add'
                });
                alert('AJAX Error! Check console for details.');
            }
        });
    });
    
    $('body').append(minimalBtn);
    
    // Log basic system status
    console.log('System check:');
    console.log('- jQuery:', typeof $);
    console.log('- AJAX available:', typeof $.ajax);
    console.log('- LINK constant:', LINK);
    console.log('- Current URL:', window.location.href);
    console.log('- Origin:', window.location.origin);
});