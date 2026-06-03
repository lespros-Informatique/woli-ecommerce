const LINK = window.location.origin + '/resto/public/';
const LIEN = window.location.origin + '/';

function showAlert(title, message, icon = 'success') {
    Swal.fire({
        title: `<span style="font-size: 24px;">${title}</span>`,
        html: `<span style="font-size: 18px;">${message}</span>`,
        icon: icon,
        confirmButtonText: '<span style="font-size: 16px;">OK</span>'
    });
}

function verifForm(fields) {
    fields.forEach(field => {
        const input = document.getElementById(field.id);
        input.addEventListener('input', function () {
            validateField(input, field.regex, field.errorMessage);
        });
    });

    function validateForm() {
        let isValid = true;
        fields.forEach(field => {
            const input = document.getElementById(field.id);
            if (!validateField(input, field.regex, field.errorMessage)) {
                isValid = false;
            }
        });
        return isValid;
    }

    function validateField(input, regex, errorMessage) {
        const errorElement = document.getElementById(input.id + 'Error');
        const nextSibling = input.nextElementSibling;
        const iconElement = nextSibling ? nextSibling.querySelector('i') : null;

        if (!regex.test(input.value)) {
            input.classList.add('invalid');
            input.classList.remove('valid');
            if (errorElement) {
                errorElement.innerHTML = `<i class="fa fa-exclamation-circle mr-2"></i> ${errorMessage}`;
                errorElement.classList.add('text-red-500');
            }
            if (iconElement) {
                iconElement.classList.add('text-red-500');
            }
            return false;
        } else {
            input.classList.add('valid');
            input.classList.remove('invalid');
            if (errorElement) {
                errorElement.innerHTML = '';
                errorElement.classList.remove('text-red-500');
            }
            if (iconElement) {
                iconElement.classList.remove('text-red-500');
                iconElement.classList.add('text-green-500');
            }
            return true;
        }
    }

    return validateForm;
}

function tableField(includeFields) {
    const allFields = {
        nom: { id: 'nom', regex: /.+/, errorMessage: 'Le nom est requis.' },
        tel: { id: 'tel', regex: /^(07|05|01)\d{8}$/, errorMessage: 'Veuillez entrer un numéro de téléphone valide qui commence par 07, 05, ou 01, suivi de 8 chiffres.' },
        email: { id: 'email', regex: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, errorMessage: 'Email invalide.' },
        password: { id: 'password', regex: /.+/, errorMessage: 'Le mot de passe est requis.' },
        adresse: { id: 'adresse', regex: /.+/, errorMessage: 'L\' adresse est requis.' },
    };
    return includeFields.map(field => allFields[field]);
}

function formIsValided(table) {
    const fields = tableField(table);
    const formIsValidTable = verifForm(fields);
    return formIsValidTable();
}

function loading(selector, status, message) {
    $(selector).html(message);
    $(selector).attr('disabled', status);
}
function redirect(url) {
    setInterval(() => {
        window.location.href = url;
    }, 2000)

}
function connexion() {
    $('.formConnexion').on('submit', function (e) {
        e.preventDefault();
        const table = ['email', 'password'];
        if (formIsValided(table)) {

            $.ajax({
                url: LINK + 'clients/login/connexion',
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: function () {
                    loading('.btnConnexion', 'disabled', '<i class="fa fa-spinner fa-spin fa-2x text-light"></i>');
                },
                success: function (rep) {
                    //console.log(rep);return;
                    let response = JSON.parse(rep);
                    loading('.btnConnexion', false, '<button type="submit" class="btn btnConnexion py-0">Se connecter <i class="fa fa-sign-in"></i></button>');
                    if (response.status == 1) {
                        showAlert('Félicitations !', response.msg, 'success');
                        setInterval(() => {
                            window.location.href = LIEN;

                        }, 2000);
                    } else {
                        showAlert('Désolé !', response, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Erreur : ' + error);
                }
            });
        }

    });
}
function formRegister() {
    $('.formRegister').on('submit', function (e) {
        e.preventDefault();
        const table = ['nom', 'email', 'password', "tel", "adresse"];
        if (formIsValided(table)) {
            let formData = $(this).serialize();
            $.ajax({
                url: LINK + 'clients/register/add',
                type: 'POST',
                data: formData,
                beforeSend: function () {
                    loading('.btn_actions', 'disabled', '<i class="fa fa-spinner fa-spin fa-2x text-light"></i>');
                },
                success: function (rep) {
                    let response = JSON.parse(rep);
                    loading('.btn_actions', false, '<button type="submit" class="btn btn-primary py-0 btn_action">S\'inscrire</button>');
                    if (response.status == 1) {
                        showAlert('Félicitations !', response.msg, 'success');
                        setInterval(() => {
                            window.location.href = LIEN;
                        }, 2000);
                    } else {
                        showAlert('Désolé !', response.msg, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    alert('Erreur :' + error);
                }
            });
        }
    });
}

// CART FUNCTIONS

/**
 * Add item to cart
 */
function addToCart(platId, platNom, platPrix, platImage = null, eventObj = null) {
    // Store the clicked element for later use
    const clickedElement = eventObj ? eventObj.target.closest('a') : null;

    $.ajax({
        url: LINK + 'cart/add',
        type: 'POST',
        data: {
            plat_id: platId,
            nom: platNom,
            prix: platPrix,
            image: platImage,
            quantite: 1
        },
        beforeSend: function () {
            // Add loading animation to the clicked cart icon
            if (clickedElement) {
                clickedElement.style.opacity = '0.5';
                clickedElement.style.pointerEvents = 'none';
            }
        },
        success: function (rep) {
            let response = typeof rep === 'string' ? JSON.parse(rep) : rep;
            if (response.status == 1) {
                // Show success message with item name
                showAlert('Ajouté au panier !', `${platNom} a été ajouté à votre panier`, 'success');
                updateCartIcon(response.data.cart_count);

                // Add bounce animation to cart icon
                animateCartIcon();

                // Update cart preview if exists
                updateCartPreview();

                // Restore cart icon
                if (clickedElement) {
                    clickedElement.style.opacity = '1';
                    clickedElement.style.pointerEvents = 'auto';
                }
            } else {
                showAlert('Erreur !', response.msg, 'error');

                // Restore cart icon even on error
                if (clickedElement) {
                    clickedElement.style.opacity = '1';
                    clickedElement.style.pointerEvents = 'auto';
                }
            }
        },
        error: function (xhr, status, error) {
            console.log('Cart add error:', error, xhr.responseText);
            showAlert('Erreur !', 'Une erreur s\'est produite lors de l\'ajout au panier', 'error');

            // Restore cart icon on error
            if (clickedElement) {
                clickedElement.style.opacity = '1';
                clickedElement.style.pointerEvents = 'auto';
            }
        }
    });
}

/**
 * Update item quantity in cart
 */
function updateCartQuantity(platId, quantite) {
    if (quantite < 0) return;

    $.ajax({
        url: LINK + 'cart/update',
        type: 'POST',
        data: {
            plat_id: platId,
            quantite: quantite
        },
        success: function (rep) {
            let response = JSON.parse(rep);
            if (response.status == 1) {
                updateCartIcon(response.cart_count);
                if (response.cart_total !== undefined) {
                    updateCartTotal(response.cart_total);
                }
                // Update cart preview if exists
                updateCartPreview();
            } else {
                showAlert('Erreur !', response.msg, 'error');
            }
        },
        error: function (xhr, status, error) {
            showAlert('Erreur !', 'Une erreur s\'est produite', 'error');
        }
    });
}

/**
 * Remove item from cart
 */
function removeFromCart(platId) {
    $.ajax({
        url: LINK + 'cart/remove',
        type: 'POST',
        data: {
            plat_id: platId
        },
        success: function (rep) {
            let response = JSON.parse(rep);
            if (response.status == 1) {
                // Remove the item from DOM
                $(`.cart-item-${platId}`).fadeOut(300, function () {
                    $(this).remove();
                    updateCartIcon(response.cart_count);
                    if (response.cart_total !== undefined) {
                        updateCartTotal(response.cart_total);
                    }
                    // Check if cart is empty
                    if (response.cart_count == 0) {
                        showEmptyCart();
                    }
                });
            } else {
                showAlert('Erreur !', response.msg, 'error');
            }
        },
        error: function (xhr, status, error) {
            showAlert('Erreur !', 'Une erreur s\'est produite', 'error');
        }
    });
}

/**
 * Update cart icon with item count
 */
function updateCartIcon(count) {
    const cartIcon = $('.cart-icon');
    const cartCount = $('.cart-count');

    if (cartIcon.length && cartCount.length) {
        // Always update the text first
        cartCount.text(count);

        // Add pulse effect to cart count
        cartCount.addClass('pulse');

        if (count > 0) {
            // Show badge when count > 0 - force visibility
            cartCount.css('display', 'inline-block');
            cartCount.show();
            cartIcon.addClass('cart-has-items');
        } else {
            // Hide badge when count = 0
            cartCount.css('display', 'none');
            cartCount.hide();
            cartIcon.removeClass('cart-has-items');
        }

        // Store count in sessionStorage for persistence
        if (typeof sessionStorage !== 'undefined') {
            sessionStorage.setItem('cart_count', count);
        }

        // Remove pulse effect after animation
        setTimeout(() => {
            cartCount.removeClass('pulse');
        }, 600);
    }
}

/**
 * Update cart total display
 */
function updateCartTotal(total) {
    const totalElement = $('.cart-total');
    if (totalElement.length) {
        totalElement.text(new Intl.NumberFormat('fr-FR').format(total) + ' FCFA');
    }
}

/**
 * Animate cart icon when item is added
 */
function animateCartIcon() {
    const cartIcon = $('.cart-icon');
    if (cartIcon.length) {
        cartIcon.addClass('bounce-animation');
        setTimeout(() => {
            cartIcon.removeClass('bounce-animation');
        }, 600);
    }
}

/**
 * Show empty cart message
 */
function showEmptyCart() {
    const cartContainer = $('.cart-items');
    if (cartContainer.length) {
        cartContainer.html(`
            <div class="empty-cart text-center py-5">
                <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h4>Votre panier est vide</h4>
                <p class="text-muted">Ajoutez des articles pour commencer votre commande</p>
                <a href="${LIEN}/menu" class="btn btn-primary">Voir le menu</a>
            </div>
        `);
    }
}

/**
 * Update cart preview (dropdown/mini-cart)
 */
function updateCartPreview() {
    // This function can be extended to show a preview of cart items
    // in a dropdown or sidebar
}

// Make cart functions globally available
window.addToCart = addToCart;
window.updateCartQuantity = updateCartQuantity;
window.removeFromCart = removeFromCart;
window.updateCartIcon = updateCartIcon;
window.updateCartTotal = updateCartTotal;
window.animateCartIcon = animateCartIcon;
