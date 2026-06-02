/* ================================================================================
   == WOLI E-COMMERCE CORE LOGIC ==
   ================================================================================ */

// --- MOCK DATABASE ALIGNED WITH db.sql ---
const CATEGORIES = {
  'CAT01': { libelle: 'Électronique', desc: 'Gadgets et appareils intelligents' },
  'CAT02': { libelle: 'Vêtements & Mode', desc: 'Habits modernes et accessoires' },
  'CAT03': { libelle: 'Maison & Déco', desc: 'Équipements et luminaires d\'intérieur' },
  'CAT04': { libelle: 'Cosmétique & Beauté', desc: 'Soins corporels et parfums de luxe' }
};

const FOURNISSEURS = {
  'FOU01': { nom: 'Afrimarket Sarl', type: 'Dropshipping', local: 'Abidjan, Zone 4' },
  'FOU02': { nom: 'Sahel Import', type: 'Achat direct', local: 'Dakar, Plateau' },
  'FOU03': { nom: 'Babi Mode', type: 'Dépôt vente', local: 'Yopougon, Bel Air' }
};

const PRODUCTS = [
  {
    code_produit: 'PROD-001',
    fournisseur_code: 'FOU01',
    categorie_code: 'CAT01',
    libelle_produit: 'Montre Intelligente Woli Watch 5',
    description_produit: 'Une montre connectée haut de gamme dotée d\'un écran AMOLED, suivi d\'activité physique en temps réel, capteur cardiaque avancé et autonomie de 7 jours.',
    image_produit: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 15000,
    prix_vente_produit: 24900,
    stock_produit: 45,
    statut_stock_produit: 'disponible',
    badge: 'Nouveau'
  },
  {
    code_produit: 'PROD-002',
    fournisseur_code: 'FOU03',
    categorie_code: 'CAT02',
    libelle_produit: 'Sweat-shirt Capuche Minimaliste Woli',
    description_produit: 'Sweat-shirt à capuche fabriqué en coton 100% biologique, avec intérieur brossé pour un confort optimal au quotidien. Coupe unisexe décontractée.',
    image_produit: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 8000,
    prix_vente_produit: 14500,
    stock_produit: 5,
    statut_stock_produit: 'faible',
    badge: 'Promo'
  },
  {
    code_produit: 'PROD-003',
    fournisseur_code: 'FOU02',
    categorie_code: 'CAT04',
    libelle_produit: 'Parfum Signature Elixir Royal',
    description_produit: 'Une fragrance envoûtante alliant des notes de bois de oud, d\'ambre et de vanille de Madagascar. Parfum longue durée unisexe.',
    image_produit: 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 18000,
    prix_vente_produit: 32000,
    stock_produit: 12,
    statut_stock_produit: 'disponible',
    badge: 'Best-Seller'
  },
  {
    code_produit: 'PROD-004',
    fournisseur_code: 'FOU01',
    categorie_code: 'CAT03',
    libelle_produit: 'Machine à Café Woli Brew Express',
    description_produit: 'Machine à espresso italienne moderne avec système de pression 15 bars pour des arômes intenses et mousse de lait onctueuse façon barista.',
    image_produit: 'https://images.unsplash.com/photo-1517256064527-09c53b2d0bc6?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 28000,
    prix_vente_produit: 45000,
    stock_produit: 8,
    statut_stock_produit: 'disponible',
    badge: ''
  },
  {
    code_produit: 'PROD-005',
    fournisseur_code: 'FOU02',
    categorie_code: 'CAT01',
    libelle_produit: 'Casque Audio Woli Sound Wireless',
    description_produit: 'Casque circum-aural sans fil avec réduction active du bruit ambiant (ANC), coussinets à mémoire de forme et haut-parleurs néodyme de 40mm.',
    image_produit: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 12000,
    prix_vente_produit: 21900,
    stock_produit: 0,
    statut_stock_produit: 'rupture',
    badge: ''
  },
  {
    code_produit: 'PROD-006',
    fournisseur_code: 'FOU03',
    categorie_code: 'CAT02',
    libelle_produit: 'Sneakers Woli Urban Run X',
    description_produit: 'Baskets de running citadines dotées d\'une semelle intercalaire en mousse dynamique réactive et d\'une tige en maille respirante.',
    image_produit: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 15000,
    prix_vente_produit: 29500,
    stock_produit: 24,
    statut_stock_produit: 'disponible',
    badge: 'Best-Seller'
  },
  {
    code_produit: 'PROD-007',
    fournisseur_code: 'FOU02',
    categorie_code: 'CAT04',
    libelle_produit: 'Sérum Hydratant Woli Glow 30ml',
    description_produit: 'Formulé à base d\'acide hyaluronique pur et de vitamine C pour raviver l\'éclat du teint et hydrater la peau en profondeur pendant 24h.',
    image_produit: 'https://images.unsplash.com/photo-1608248597481-496100c80836?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 5500,
    prix_vente_produit: 9900,
    stock_produit: 3,
    statut_stock_produit: 'faible',
    badge: 'Nouveau'
  },
  {
    code_produit: 'PROD-008',
    fournisseur_code: 'FOU01',
    categorie_code: 'CAT03',
    libelle_produit: 'Lampe de Table Nordique Minimaliste',
    description_produit: 'Lampe de chevet en chêne et abat-jour blanc mat. Convient parfaitement pour un bureau d\'architecte ou une table de nuit moderne.',
    image_produit: 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500&auto=format&fit=crop&q=80',
    prix_fournisseur_produit: 9500,
    prix_vente_produit: 18000,
    stock_produit: 15,
    statut_stock_produit: 'disponible',
    badge: 'Promo'
  }
];

// --- APP STATE ---
let cart = JSON.parse(localStorage.getItem('woli_cart')) || [];
let activeFilters = {
  search: '',
  categories: [],
  minPrice: 0,
  maxPrice: 50000,
  stock: 'all', // 'all', 'disponible', 'faible'
  suppliers: []
};

// --- INITIALIZE ON DOM LOAD ---
document.addEventListener('DOMContentLoaded', () => {
  initNavbarToggles();
  initSearchAutocomplete();
  initFilters();
  initCart();
  initModals();
  initPromoBar();
  renderProducts();
});

// ================================================================================
// == NAV & OVERLAYS MANAGEMENT ==
// ================================================================================
function initNavbarToggles() {
  const hamburger = document.querySelector('.js-hamburger');
  const mobileNav = document.querySelector('.js-mobile-nav');
  const searchTrigger = document.querySelector('.js-search-trigger');
  const mobileSearchDrawer = document.querySelector('.js-mobile-search-drawer');

  // Hamburger Toggle
  if (hamburger && mobileNav) {
    hamburger.addEventListener('click', () => {
      hamburger.classList.toggle('open');
      mobileNav.classList.toggle('open');
    });
  }

  // Mobile Submenu toggling
  const submenuTriggers = document.querySelectorAll('.js-mobile-submenu-trigger');
  submenuTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const submenu = trigger.nextElementSibling;
      if (submenu) {
        submenu.classList.toggle('open');
      }
    });
  });

  // Mobile search toggle
  if (searchTrigger && mobileSearchDrawer) {
    searchTrigger.addEventListener('click', (e) => {
      e.preventDefault();
      mobileSearchDrawer.classList.toggle('open');
      const input = mobileSearchDrawer.querySelector('input');
      if (mobileSearchDrawer.classList.contains('open') && input) {
        input.focus();
      }
    });
  }
}

function initPromoBar() {
  const promoBar = document.querySelector('.js-promo-bar');
  const closeBtn = document.querySelector('.js-promo-bar-close');
  const navbar = document.querySelector('.navbar');
  const mobileHeader = document.querySelector('.mobile-header');

  if (closeBtn && promoBar) {
    closeBtn.addEventListener('click', () => {
      promoBar.style.display = 'none';
      document.body.style.paddingTop = '72px'; // Default desktop navbar height
      
      if (window.innerWidth < 768) {
        document.body.style.paddingTop = '64px'; // Default mobile header height
      }

      if (navbar) navbar.classList.add('no-promo');
      if (mobileHeader) mobileHeader.classList.add('no-promo');
    });
  }
}

// ================================================================================
// == SEARCH AUTOCOMPLETE ==
// ================================================================================
function initSearchAutocomplete() {
  const desktopSearch = document.querySelector('.js-search-input');
  const desktopDropdown = document.querySelector('.js-autocomplete-results');
  const mobileSearch = document.querySelector('.js-mobile-search-input');
  const mobileDropdown = document.querySelector('.js-mobile-autocomplete-results');

  function setupAutocomplete(input, dropdown) {
    if (!input || !dropdown) return;

    let debounceTimer;
    input.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      const query = input.value.trim().toLowerCase();

      if (query.length < 2) {
        dropdown.style.display = 'none';
        return;
      }

      debounceTimer = setTimeout(() => {
        const matches = PRODUCTS.filter(p => 
          p.libelle_produit.toLowerCase().includes(query) || 
          p.description_produit.toLowerCase().includes(query)
        ).slice(0, 5); // Limit to top 5

        renderAutocompleteResults(matches, dropdown);
      }, 300);
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
      if (!input.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
      }
    });
  }

  setupAutocomplete(desktopSearch, desktopDropdown);
  setupAutocomplete(mobileSearch, mobileDropdown);
}

function renderAutocompleteResults(matches, container) {
  if (matches.length === 0) {
    container.innerHTML = `<div class="autocomplete-item" style="color: var(--text-secondary); font-size:13px;">Aucun produit trouvé</div>`;
    container.style.display = 'block';
    return;
  }

  container.innerHTML = matches.map(prod => `
    <div class="autocomplete-item" data-code="${prod.code_produit}">
      <img src="${prod.image_produit}" alt="${prod.libelle_produit}" class="autocomplete-image">
      <div style="flex-grow:1; min-width: 0;">
        <div class="autocomplete-title">${prod.libelle_produit}</div>
        <div class="price" style="font-size:12px; color: var(--secondary-color);">${formatPrice(prod.prix_vente_produit)}</div>
      </div>
    </div>
  `).join('');

  container.style.display = 'block';

  // Add click events to suggestions
  container.querySelectorAll('.autocomplete-item').forEach(item => {
    item.addEventListener('click', () => {
      const code = item.getAttribute('data-code');
      openProductModal(code);
      container.style.display = 'none';
    });
  });
}

// ================================================================================
// == PRODUCT RENDERING & FILTERING ==
// ================================================================================
function renderProducts() {
  const grid = document.querySelector('.js-product-grid');
  if (!grid) return;

  // Show Skeleton Loaders for UX smoothness
  renderSkeletons(grid, 4);

  setTimeout(() => {
    const filtered = PRODUCTS.filter(prod => {
      // 1. Text Search
      if (activeFilters.search && !prod.libelle_produit.toLowerCase().includes(activeFilters.search)) {
        return false;
      }
      // 2. Categories
      if (activeFilters.categories.length > 0 && !activeFilters.categories.includes(prod.categorie_code)) {
        return false;
      }
      // 3. Price
      if (prod.prix_vente_produit < activeFilters.minPrice || prod.prix_vente_produit > activeFilters.maxPrice) {
        return false;
      }
      // 4. Stock status
      if (activeFilters.stock !== 'all') {
        if (activeFilters.stock === 'disponible' && prod.statut_stock_produit !== 'disponible') {
          return false;
        }
        if (activeFilters.stock === 'faible' && prod.statut_stock_produit !== 'faible') {
          return false;
        }
      }
      // 5. Suppliers
      if (activeFilters.suppliers.length > 0 && !activeFilters.suppliers.includes(prod.fournisseur_code)) {
        return false;
      }
      return true;
    });

    if (filtered.length === 0) {
      grid.innerHTML = `
        <div style="grid-column: 1/-1; text-align: center; padding: 48px; color: var(--text-secondary);">
          <svg style="width: 48px; height: 48px; margin-bottom: 12px; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          <h3>Aucun produit ne correspond à ces critères</h3>
          <p style="margin-top: 8px;">Essayez de réinitialiser vos filtres ou de modifier votre recherche.</p>
        </div>
      `;
      return;
    }

    grid.innerHTML = filtered.map(prod => {
      // Stock class
      let stockLabel = 'En stock';
      let stockClass = 'product-card__stock--disponible';
      if (prod.statut_stock_produit === 'faible') {
        stockLabel = 'Dernières pièces';
        stockClass = 'product-card__stock--faible';
      } else if (prod.statut_stock_produit === 'rupture') {
        stockLabel = 'Rupture de stock';
        stockClass = 'product-card__stock--rupture';
      }

      // Badges
      let badgeHtml = '';
      if (prod.badge) {
        let badgeType = 'badge-promo';
        if (prod.badge === 'Nouveau') badgeType = 'badge-new';
        if (prod.badge === 'Best-Seller') badgeType = 'badge-bestseller';
        badgeHtml = `<span class="badge ${badgeType} product-card__badge">${prod.badge}</span>`;
      }

      // Promo check for original price
      let priceHtml = `<span class="price">${formatPrice(prod.prix_vente_produit)}</span>`;
      if (prod.badge === 'Promo') {
        const oldPrice = Math.round(prod.prix_vente_produit * 1.25);
        priceHtml = `
          <div class="price">
            <span>${formatPrice(prod.prix_vente_produit)}</span>
            <span class="price-old">${formatPrice(oldPrice)}</span>
          </div>
        `;
      }

      // Disabled button check if out of stock
      const isDisabled = prod.statut_stock_produit === 'rupture' ? 'disabled' : '';

      return `
        <article class="product-card" data-code="${prod.code_produit}">
          ${prod.statut_stock_produit === 'rupture' ? '<div class="product-card__stock--rupture-overlay"></div>' : ''}
          <div class="product-card__image-container">
            ${badgeHtml}
            <button class="product-card__favorite js-favorite" aria-label="Ajouter aux favoris">
              <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </button>
            <img src="${prod.image_produit}" alt="${prod.libelle_produit}" class="product-card__image" loading="lazy">
          </div>
          <div class="product-card__info">
            <h3 class="product-card__title js-open-detail">${prod.libelle_produit}</h3>
            <span class="product-card__supplier">Par ${FOURNISSEURS[prod.fournisseur_code].nom}</span>
            <div class="product-card__price-row">
              ${priceHtml}
            </div>
            <div class="product-card__stock ${stockClass}">
              <span class="stock-indicator-dot"></span> ${stockLabel}
            </div>
            <button class="btn btn-primary product-card__btn js-add-to-cart" ${isDisabled}>
              <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
              Ajouter au panier
            </button>
          </div>
        </article>
      `;
    }).join('');

    // Bind card events
    grid.querySelectorAll('.js-open-detail').forEach(el => {
      el.addEventListener('click', (e) => {
        const code = e.target.closest('.product-card').getAttribute('data-code');
        openProductModal(code);
      });
    });

    grid.querySelectorAll('.js-add-to-cart').forEach(btn => {
      btn.addEventListener('click', (e) => {
        const code = e.target.closest('.product-card').getAttribute('data-code');
        addToCart(code);
      });
    });

    grid.querySelectorAll('.js-favorite').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.currentTarget.classList.toggle('active');
        const isActive = e.currentTarget.classList.contains('active');
        showToast(isActive ? 'Ajouté aux favoris' : 'Retiré des favoris', 'info');
      });
    });

  }, 600);
}

function renderSkeletons(grid, count) {
  let skeletonsHtml = '';
  for (let i = 0; i < count; i++) {
    skeletonsHtml += `
      <div class="product-card" style="box-shadow:none; pointer-events:none;">
        <div class="skeleton" style="width:100%; height:200px;"></div>
        <div class="product-card__info">
          <div class="skeleton" style="width:80%; height:20px; margin-top:12px;"></div>
          <div class="skeleton" style="width:50%; height:15px; margin-top:8px;"></div>
          <div class="skeleton" style="width:40%; height:24px; margin-top:12px;"></div>
          <div class="skeleton" style="width:100%; height:40px; margin-top:16px;"></div>
        </div>
      </div>
    `;
  }
  grid.innerHTML = skeletonsHtml;
}

// ================================================================================
// == FILTER HANDLERS ==
// ================================================================================
function initFilters() {
  const sidebar = document.querySelector('.js-sidebar');
  const sidebarBackdrop = document.querySelector('.js-sidebar-backdrop');
  const filterTriggers = document.querySelectorAll('.js-filter-trigger');
  const closeFilterBtn = document.querySelector('.js-close-filter');
  
  const minPriceInput = document.querySelector('.js-min-price-input');
  const maxPriceInput = document.querySelector('.js-max-price-input');
  const rangeSlider = document.querySelector('.js-range-slider');
  const rangeLabel = document.querySelector('.js-range-label');

  const applyBtn = document.querySelector('.js-apply-filters');
  const resetBtn = document.querySelector('.js-reset-filters');

  // Drawers open/close for mobile
  filterTriggers.forEach(trigger => {
    trigger.addEventListener('click', () => {
      if (sidebar && sidebarBackdrop) {
        sidebar.classList.add('open');
        sidebarBackdrop.classList.add('show');
      }
    });
  });

  const closeFilters = () => {
    if (sidebar && sidebarBackdrop) {
      sidebar.classList.remove('open');
      sidebarBackdrop.classList.remove('show');
    }
  };

  if (closeFilterBtn) closeFilterBtn.addEventListener('click', closeFilters);
  if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeFilters);

  // Price range linking
  if (rangeSlider && minPriceInput && maxPriceInput && rangeLabel) {
    rangeSlider.addEventListener('input', () => {
      const val = parseInt(rangeSlider.value);
      maxPriceInput.value = val;
      activeFilters.maxPrice = val;
      rangeLabel.textContent = `1 000 FCFA - ${formatNumber(val)} FCFA`;
    });
  }

  // Filter application
  if (applyBtn) {
    applyBtn.addEventListener('click', () => {
      // Collect Categories
      activeFilters.categories = [];
      document.querySelectorAll('.js-cat-filter:checked').forEach(cb => {
        activeFilters.categories.push(cb.value);
      });

      // Collect Suppliers
      activeFilters.suppliers = [];
      document.querySelectorAll('.js-sup-filter:checked').forEach(cb => {
        activeFilters.suppliers.push(cb.value);
      });

      // Collect Stock
      const stockSelected = document.querySelector('input[name="filter-stock"]:checked');
      if (stockSelected) {
        activeFilters.stock = stockSelected.value;
      }

      // Pricing
      if (minPriceInput) activeFilters.minPrice = parseInt(minPriceInput.value) || 0;
      if (maxPriceInput) activeFilters.maxPrice = parseInt(maxPriceInput.value) || 50000;

      closeFilters();
      renderProducts();
      showToast('Filtres appliqués avec succès', 'success');
    });
  }

  // Filter resetting
  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      // Uncheck categories & suppliers
      document.querySelectorAll('.js-cat-filter, .js-sup-filter').forEach(cb => cb.checked = false);
      
      // Reset stock
      const stockAll = document.querySelector('input[name="filter-stock"][value="all"]');
      if (stockAll) stockAll.checked = true;

      // Reset pricing
      if (minPriceInput) minPriceInput.value = 1000;
      if (maxPriceInput) maxPriceInput.value = 50000;
      if (rangeSlider) {
        rangeSlider.value = 50000;
        rangeLabel.textContent = "1 000 FCFA - 50 000 FCFA";
      }

      activeFilters = {
        search: '',
        categories: [],
        minPrice: 0,
        maxPrice: 50000,
        stock: 'all',
        suppliers: []
      };

      closeFilters();
      renderProducts();
      showToast('Filtres réinitialisés', 'info');
    });
  }

  // Supplier Search filtering
  const supplierSearchInput = document.querySelector('.js-supplier-search');
  if (supplierSearchInput) {
    supplierSearchInput.addEventListener('input', () => {
      const q = supplierSearchInput.value.toLowerCase();
      document.querySelectorAll('.js-supplier-item').forEach(item => {
        const name = item.querySelector('span').textContent.toLowerCase();
        if (name.includes(q)) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    });
  }
}

// ================================================================================
// == PERSISTENT SHOPPING CART SYSTEM ==
// ================================================================================
function initCart() {
  const cartTriggers = document.querySelectorAll('.js-cart-trigger');
  const cartDrawer = document.querySelector('.js-cart-drawer');
  const closeCartBtn = document.querySelector('.js-close-cart');

  // Drawer slider actions
  cartTriggers.forEach(trigger => {
    trigger.addEventListener('click', (e) => {
      e.preventDefault();
      if (cartDrawer) cartDrawer.classList.add('cart-drawer--show');
    });
  });

  if (closeCartBtn) {
    closeCartBtn.addEventListener('click', () => {
      if (cartDrawer) cartDrawer.classList.remove('cart-drawer--show');
    });
  }

  updateCartUI();
}

function addToCart(code) {
  const product = PRODUCTS.find(p => p.code_produit === code);
  if (!product || product.statut_stock_produit === 'rupture') return;

  const existing = cart.find(item => item.code === code);
  if (existing) {
    existing.quantity += 1;
  } else {
    cart.push({ code: code, quantity: 1 });
  }

  saveCart();
  updateCartUI();
  
  // Show drawer on add to make UI responsive
  const cartDrawer = document.querySelector('.js-cart-drawer');
  if (cartDrawer) cartDrawer.classList.add('cart-drawer--show');

  showToast(`${product.libelle_produit} ajouté au panier`, 'success');
}

function removeFromCart(code) {
  cart = cart.filter(item => item.code !== code);
  saveCart();
  updateCartUI();
  showToast('Article retiré du panier', 'info');
}

function updateQuantity(code, delta) {
  const existing = cart.find(item => item.code === code);
  if (!existing) return;

  const product = PRODUCTS.find(p => p.code_produit === code);
  if (!product) return;

  existing.quantity += delta;

  // bounds check
  if (existing.quantity <= 0) {
    removeFromCart(code);
    return;
  }

  if (existing.quantity > product.stock_produit) {
    existing.quantity = product.stock_produit;
    showToast(`Quantité limitée au stock disponible (${product.stock_produit})`, 'warning');
  }

  saveCart();
  updateCartUI();
}

function saveCart() {
  localStorage.setItem('woli_cart', JSON.stringify(cart));
}

function updateCartUI() {
  const badgeCounts = document.querySelectorAll('.js-cart-count');
  const itemsContainer = document.querySelector('.js-cart-items');
  const totalContainer = document.querySelector('.js-cart-total-value');
  const emptyMessage = document.querySelector('.js-cart-empty');
  const checkoutBtn = document.querySelector('.js-cart-checkout-btn');

  // Count items
  const count = cart.reduce((acc, item) => acc + item.quantity, 0);
  badgeCounts.forEach(el => {
    el.textContent = count;
    el.style.display = count > 0 ? 'flex' : 'none';
  });

  if (count === 0) {
    if (itemsContainer) itemsContainer.innerHTML = '';
    if (emptyMessage) emptyMessage.style.display = 'block';
    if (totalContainer) totalContainer.textContent = '0 FCFA';
    if (checkoutBtn) checkoutBtn.disabled = true;
    return;
  }

  if (emptyMessage) emptyMessage.style.display = 'none';
  if (checkoutBtn) checkoutBtn.disabled = false;

  let subtotal = 0;
  let itemsHtml = '';

  cart.forEach(item => {
    const product = PRODUCTS.find(p => p.code_produit === item.code);
    if (!product) return;

    const price = product.prix_vente_produit;
    const totalItemPrice = price * item.quantity;
    subtotal += totalItemPrice;

    itemsHtml += `
      <div class="cart-item" data-code="${product.code_produit}">
        <img src="${product.image_produit}" alt="${product.libelle_produit}" class="cart-item__image">
        <div class="cart-item__details">
          <div class="cart-item__title">${product.libelle_produit}</div>
          <span class="cart-item__supplier">Par ${FOURNISSEURS[product.fournisseur_code].nom}</span>
          <div class="cart-item__controls">
            <div class="quantity-selector">
              <button class="quantity-btn js-qty-dec">-</button>
              <input type="text" class="quantity-input" value="${item.quantity}" readonly>
              <button class="quantity-btn js-qty-inc">+</button>
            </div>
            <button class="cart-item__remove js-qty-remove">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
              Retirer
            </button>
          </div>
        </div>
        <div class="price" style="font-size: 14px; margin-left: 8px;">${formatPrice(totalItemPrice)}</div>
      </div>
    `;
  });

  if (itemsContainer) {
    itemsContainer.innerHTML = itemsHtml;

    // Bind quantity button listeners
    itemsContainer.querySelectorAll('.cart-item').forEach(el => {
      const code = el.getAttribute('data-code');
      el.querySelector('.js-qty-dec').addEventListener('click', () => updateQuantity(code, -1));
      el.querySelector('.js-qty-inc').addEventListener('click', () => updateQuantity(code, 1));
      el.querySelector('.js-qty-remove').addEventListener('click', () => removeFromCart(code));
    });
  }

  if (totalContainer) {
    totalContainer.textContent = formatPrice(subtotal);
  }
}

// ================================================================================
// == PRODUCT DETAIL MODAL ==
// ================================================================================
function initModals() {
  const modalOverlay = document.querySelector('.js-modal-overlay');
  const closeBtn = document.querySelector('.js-modal-close');

  const closeModal = () => {
    if (modalOverlay) modalOverlay.classList.remove('modal-overlay--show');
  };

  if (closeBtn) closeBtn.addEventListener('click', closeModal);
  if (modalOverlay) {
    modalOverlay.addEventListener('click', (e) => {
      if (e.target === modalOverlay) closeModal();
    });
  }
}

function openProductModal(code) {
  const prod = PRODUCTS.find(p => p.code_produit === code);
  if (!prod) return;

  const overlay = document.querySelector('.js-modal-overlay');
  const body = document.querySelector('.js-modal-body');

  if (!overlay || !body) return;

  let stockLabel = 'Disponible en stock';
  let stockClass = 'product-card__stock--disponible';
  if (prod.statut_stock_produit === 'faible') {
    stockLabel = `Stock faible (Dernières pièces: ${prod.stock_produit})`;
    stockClass = 'product-card__stock--faible';
  } else if (prod.statut_stock_produit === 'rupture') {
    stockLabel = 'Rupture temporaire';
    stockClass = 'product-card__stock--rupture';
  }

  const supplier = FOURNISSEURS[prod.fournisseur_code];

  body.innerHTML = `
    <div class="product-detail-modal">
      <div>
        <img src="${prod.image_produit}" alt="${prod.libelle_produit}" class="product-detail-modal__image">
      </div>
      <div class="product-detail-modal__info">
        <div>
          <span class="badge badge-new" style="margin-bottom: 8px;">${CATEGORIES[prod.categorie_code].libelle}</span>
          <h2 class="product-detail-modal__title">${prod.libelle_produit}</h2>
          <div class="price" style="font-size: 24px; color: var(--secondary-color); margin-bottom: 12px;">
            ${formatPrice(prod.prix_vente_produit)}
          </div>
          <div class="product-card__stock ${stockClass}" style="margin-bottom: 16px;">
            <span class="stock-indicator-dot"></span> ${stockLabel}
          </div>
          <p class="product-detail-modal__desc">${prod.description_produit}</p>
        </div>

        <div style="border-top: 1px solid #E5E7EB; padding-top: 16px; margin-top: 16px; font-size:13px; color: var(--text-secondary);">
          <div style="margin-bottom: 6px;"><strong>Fournisseur :</strong> ${supplier.nom} (${supplier.local})</div>
          <div style="margin-bottom: 12px;"><strong>Mode logistique :</strong> ${supplier.type === 'Dropshipping' ? 'Dropshipping Direct' : 'Expédié par Woli'}</div>
          
          <button class="btn btn-primary js-modal-add-to-cart" style="width:100%;" ${prod.statut_stock_produit === 'rupture' ? 'disabled' : ''}>
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            Ajouter au panier
          </button>
        </div>
      </div>
    </div>
  `;

  overlay.classList.add('modal-overlay--show');

  // Bind add button
  body.querySelector('.js-modal-add-to-cart').addEventListener('click', () => {
    addToCart(prod.code_produit);
    overlay.classList.remove('modal-overlay--show');
  });
}

// ================================================================================
// == TOAST SYSTEM ==
// ================================================================================
function showToast(message, type = 'success') {
  const container = document.querySelector('.js-toast-container');
  if (!container) return;

  const toast = document.createElement('div');
  toast.className = `toast toast--${type}`;

  let iconSvg = '';
  if (type === 'success') {
    iconSvg = `<svg class="toast__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
  } else if (type === 'error') {
    iconSvg = `<svg class="toast__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
  } else if (type === 'warning') {
    iconSvg = `<svg class="toast__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;
  } else {
    iconSvg = `<svg class="toast__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
  }

  toast.innerHTML = `
    ${iconSvg}
    <span class="toast__message">${message}</span>
  `;

  container.appendChild(toast);

  // Trigger repaint to enable CSS transition
  setTimeout(() => {
    toast.classList.add('toast--show');
  }, 50);

  // Auto-remove
  setTimeout(() => {
    toast.classList.remove('toast--show');
    setTimeout(() => {
      toast.remove();
    }, 300);
  }, 4000);
}

// ================================================================================
// == CHECKOUT MULTI-STEP FLOW ==
// ================================================================================
let currentCheckoutStep = 1;

function startCheckout() {
  // Hide cart drawer
  const cartDrawer = document.querySelector('.js-cart-drawer');
  if (cartDrawer) cartDrawer.classList.remove('cart-drawer--show');

  // Reset steps
  currentCheckoutStep = 1;
  updateCheckoutModalView();

  const checkoutModal = document.querySelector('.js-checkout-modal-overlay');
  if (checkoutModal) checkoutModal.classList.add('modal-overlay--show');
}

function updateCheckoutModalView() {
  const steps = document.querySelectorAll('.checkout-step');
  const flows = document.querySelectorAll('.checkout-flow-container');

  // Update visual dots
  steps.forEach((step, idx) => {
    const stepNum = idx + 1;
    step.className = 'checkout-step';
    if (stepNum < currentCheckoutStep) {
      step.classList.add('checkout-step--completed');
    } else if (stepNum === currentCheckoutStep) {
      step.classList.add('checkout-step--active');
    }
  });

  // Toggle flow displays
  flows.forEach((flow, idx) => {
    const flowNum = idx + 1;
    if (flowNum === currentCheckoutStep) {
      flow.classList.add('active');
    } else {
      flow.classList.remove('active');
    }
  });

  // Calculate review values in payment summary step
  if (currentCheckoutStep === 3) {
    const orderTotal = cart.reduce((acc, item) => {
      const p = PRODUCTS.find(prod => prod.code_produit === item.code);
      return acc + (p ? p.prix_vente_produit * item.quantity : 0);
    }, 0);
    
    document.querySelectorAll('.js-checkout-subtotal').forEach(el => el.textContent = formatPrice(orderTotal));
    document.querySelectorAll('.js-checkout-shipping').forEach(el => el.textContent = formatPrice(2000)); // fixed shipping fee
    document.querySelectorAll('.js-checkout-total').forEach(el => el.textContent = formatPrice(orderTotal + 2000));
  }
}

function nextCheckoutStep() {
  // Simple validation
  if (currentCheckoutStep === 1) {
    const name = document.getElementById('chk-name').value.trim();
    const tel = document.getElementById('chk-phone').value.trim();
    const city = document.getElementById('chk-city').value.trim();
    const addr = document.getElementById('chk-address').value.trim();

    if (!name || !tel || !city || !addr) {
      showToast('Veuillez remplir tous les champs de livraison', 'error');
      return;
    }
  }

  currentCheckoutStep += 1;
  updateCheckoutModalView();
}

function prevCheckoutStep() {
  currentCheckoutStep -= 1;
  updateCheckoutModalView();
}

function confirmCheckoutOrder() {
  // Generate mock command code aligning with db.sql schema rules
  const randNum = Math.floor(1000 + Math.random() * 9000);
  const commandCode = `CMD-${randNum}`;

  const clientName = document.getElementById('chk-name').value.trim();
  const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;
  let methodLabel = "Cash à la livraison";
  if (paymentMethod === 'mobile_money') methodLabel = "Mobile Money (Wave/MTN/Orange)";
  if (paymentMethod === 'carte') methodLabel = "Carte Bancaire Visa/Mastercard";

  // Render confirmation details
  document.querySelector('.js-confirm-code').textContent = commandCode;
  document.querySelector('.js-confirm-client').textContent = clientName;
  document.querySelector('.js-confirm-payment').textContent = methodLabel;

  currentCheckoutStep = 4; // Move to confirmation step
  updateCheckoutModalView();

  // Clear cart
  cart = [];
  saveCart();
  updateCartUI();

  showToast('Commande validée avec succès !', 'success');
}

function closeCheckoutModal() {
  const checkoutModal = document.querySelector('.js-checkout-modal-overlay');
  if (checkoutModal) checkoutModal.classList.remove('modal-overlay--show');
}

// ================================================================================
// == HELPERS ==
// ================================================================================
function formatPrice(val) {
  return `${formatNumber(val)} FCFA`;
}

function formatNumber(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}
