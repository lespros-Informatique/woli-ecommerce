<?php require_once '../public/inc/header.php'; ?>


  <!-- Accessibilité: Lien d'évitement -->
  <a href="#main-content" class="skip-link">Passer au contenu principal</a>

  <!-- ================================================================================
     == TOP PROMO BAR ==
     ================================================================================ -->
  <div class="promo-bar js-promo-bar" id="promo-bar">
    <span> Soldes Exceptionnelles 2026 : Jusqu'à -50% sur l'Électronique ! Livraisons à 2 000 FCFA</span>
    <button class="promo-bar__close js-promo-bar-close" aria-label="Fermer la barre de promotion">×</button>
  </div>

  <!-- ================================================================================
     == NAVBAR DESKTOP (Viewport >= 768px) ==
     ================================================================================ -->
  <header class="navbar desktop-only" id="main-header">
    <div class="container navbar__container">
      <!-- Logo -->
      <a href="#" class="navbar__logo-link" aria-label="Woli Accueil">
        <img src="<?= RACINE ?>assets/images/logo/woli.png" alt="Woli Logo" class="navbar__logo">
      </a>

      <!-- Navigation Centrale -->
      <nav aria-label="Navigation principale">
        <ul class="navbar__nav">
          <li class="navbar__item">
            <a href="#" class="navbar__link" onclick="activeFilters={search:'',categories:[],minPrice:0,maxPrice:50000,stock:'all',suppliers:[],badge:''}; renderProducts(); return false;">Accueil</a>
          </li>
          <li class="navbar__item">
            <a href="#" class="navbar__link" onclick="openCategoriesExplorerFullscreen(); return false;">
              Boutique
              <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
            </a>
            <ul class="navbar__dropdown">
              <li class="navbar__dropdown-item"><a href="#" onclick="openCategoryFullscreen('CAT01', 'Électronique', ''); return false;">Électronique</a></li>
              <li class="navbar__dropdown-item"><a href="#" onclick="openCategoryFullscreen('CAT02', 'Vêtements & Mode', ''); return false;">Vêtements & Mode</a></li>
              <li class="navbar__dropdown-item"><a href="#" onclick="openCategoryFullscreen('CAT03', 'Maison & Déco', ''); return false;">Maison & Déco</a></li>
              <li class="navbar__dropdown-item"><a href="#" onclick="openCategoryFullscreen('CAT04', 'Cosmétique & Beauté', ''); return false;">Cosmétique & Beauté</a></li>
            </ul>
          </li>
          <li class="navbar__item">
            <a href="#" class="navbar__link" onclick="openCategoryFullscreen('new', 'Nouveautés', ''); return false;">Nouveautés</a>
          </li>
          <li class="navbar__item">
            <a href="#" class="navbar__link" onclick="openCategoryFullscreen('promo', 'Promotions', ''); return false;">Promotions</a>
          </li>
        </ul>
      </nav>

      <!-- Actions de Droite (Recherche + Panier + User) -->
      <div class="navbar__actions">
        <!-- Barre de recherche avec Autocomplete -->
        <div class="search-container">
          <input type="text" placeholder="Rechercher un article..." class="search-input js-search-input" id="search-input" aria-label="Rechercher des produits">
          <svg class="search-icon-inside" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
          <div class="autocomplete-results js-autocomplete-results" id="autocomplete-results"></div>
        </div>

        <!-- Favoris -->
        <button class="btn-icon" aria-label="Voir les favoris" onclick="openFavoritesFullscreen(); return false;">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        </button>

        <!-- Panier -->
        <button class="btn-icon navbar__action-btn js-cart-trigger" aria-label="Ouvrir le panier" id="desktop-cart-btn">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
          <span class="navbar__badge js-cart-count" id="cart-badge-desktop">0</span>
        </button>

        <!-- Profil -->
        <button class="btn-icon" aria-label="Mon compte" onclick="openAccountFullscreen(); return false;">
          <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </button>
      </div>
    </div>
  </header>

  <!-- ================================================================================
     == NAVBAR MOBILE (Viewport < 768px) ==
     ================================================================================ -->
  <header class="mobile-header mobile-only" id="mobile-header">
    <a href="#" class="mobile-header__logo-link">
      <img src="<?= RACINE ?>assets/images/logo/woli.png" alt="Woli Logo" class="mobile-header__logo">
    </a>

    <div style="display: flex; align-items: center; gap: 8px;">
      <!-- Rechercher Trigger -->
      <button class="btn-icon js-search-trigger" aria-label="Ouvrir la recherche mobile" style="color: #FFFFFF;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      </button>

      <!-- Panier Trigger -->
      <button class="btn-icon js-cart-trigger" aria-label="Ouvrir le panier" style="color: #FFFFFF; position: relative;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <span class="navbar__badge js-cart-count" id="cart-badge-mobile-top">0</span>
      </button>

      <!-- Hamburger Button -->
      <button class="hamburger js-hamburger" aria-label="Ouvrir le menu de navigation" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </header>

  <!-- Menu Mobile Plein Écran Overlay -->
  <nav class="mobile-nav js-mobile-nav mobile-only" aria-label="Navigation mobile">
    <ul class="mobile-nav__list">
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link" onclick="activeFilters={search:'',categories:[],minPrice:0,maxPrice:50000,stock:'all',suppliers:[],badge:''}; renderProducts(); document.querySelector('.js-hamburger').click(); return false;">Accueil</a>
      </li>
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link js-mobile-submenu-trigger">
          Catégories Boutique
          <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
        </a>
        <ul class="mobile-nav__submenu">
          <li class="mobile-nav__submenu-item"><a href="#" onclick="openCategoryFullscreen('CAT01', 'Électronique', ''); document.querySelector('.js-hamburger').click(); return false;">Électronique</a></li>
          <li class="mobile-nav__submenu-item"><a href="#" onclick="openCategoryFullscreen('CAT02', 'Vêtements & Mode', ''); document.querySelector('.js-hamburger').click(); return false;">Vêtements & Mode</a></li>
          <li class="mobile-nav__submenu-item"><a href="#" onclick="openCategoryFullscreen('CAT03', 'Maison & Déco', ''); document.querySelector('.js-hamburger').click(); return false;">Maison & Déco</a></li>
          <li class="mobile-nav__submenu-item"><a href="#" onclick="openCategoryFullscreen('CAT04', 'Cosmétique & Beauté', ''); document.querySelector('.js-hamburger').click(); return false;">Cosmétique & Beauté</a></li>
        </ul>
      </li>
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link" onclick="openCategoryFullscreen('new', 'Nouveautés', ''); document.querySelector('.js-hamburger').click(); return false;">Nouveautés</a>
      </li>
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link" onclick="openCategoryFullscreen('promo', 'Promotions', ''); document.querySelector('.js-hamburger').click(); return false;">Promotions</a>
      </li>
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link" onclick="openFavoritesFullscreen(); document.querySelector('.js-hamburger').click(); return false;">Mes Favoris</a>
      </li>
      <li class="mobile-nav__item">
        <a href="#" class="mobile-nav__link" onclick="openAccountFullscreen(); document.querySelector('.js-hamburger').click(); return false;">Mon Compte</a>
      </li>
    </ul>
  </nav>

  <!-- Mobile Search Drawer (Slide-down below header) -->
  <div class="mobile-search-drawer js-mobile-search-drawer mobile-only">
    <div class="mobile-search-input-wrapper">
      <input type="text" placeholder="Rechercher des articles Woli..." class="mobile-search-input js-mobile-search-input" id="mobile-search-input">
      <svg class="mobile-search-icon-inside" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      <div class="mobile-autocomplete-results js-mobile-autocomplete-results"></div>
    </div>
    <button class="btn btn-primary" style="padding:10px var(--space-md); min-height:42px;" onclick="document.querySelector('.js-mobile-search-drawer').classList.remove('open');">x</button>
  </div>

<!-- ================================================================================
      == MAIN HERO BANNER - Style Alibaba ==
      ================================================================================ -->
  <section class="hero-alibaba" aria-label="Offres et Catégories">
    <div class="container">
      <!-- Announcement Slider -->
      <div class="hero-alibaba__slider">
        <div class="announcement-slider js-announcement-slider">
          <div class="announcement-slide active">
            <span class="badge badge-promo" style="margin-right:8px;">-30%</span>
            <span>Soldes jusqu'à -50%</span>
          </div>
          <div class="announcement-slide">
            <span class="badge badge-new" style="margin-right:8px;">Nouveau</span>
            <span>Livraison gratuite dès 50 000 FCFA</span>
          </div>
        </div>
      </div>
    </div>
  </section>
<!-- s -->
  <!-- ================================================================================
      == CATEGORIES SCROLLABLE HORIZONTAL - Cards ==
      ================================================================================ -->
  <section class="categories-scroll section" aria-label="Catégories">
    <div class="container">
      <div class="categories-scroll__track">
        <div class="category-card-small js-filter-category" data-cat="CAT01">
          <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=200" alt="Électronique" class="category-card-small__image">
          <span class="category-card-small__label">Électronique</span>
        </div>
        <div class="category-card-small js-filter-category" data-cat="CAT02">
          <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=200" alt="Vêtements" class="category-card-small__image">
          <span class="category-card-small__label">Vêtements</span>
        </div>
        <div class="category-card-small js-filter-category" data-cat="CAT03">
          <img src="https://images.unsplash.com/photo-1517256064527-09c53b2d0bc6?w=200" alt="Maison" class="category-card-small__image">
          <span class="category-card-small__label">Maison</span>
        </div>
        <div class="category-card-small js-filter-category" data-cat="CAT04">
          <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?w=200" alt="Beauté" class="category-card-small__image">
          <span class="category-card-small__label">Beauté</span>
        </div>
        <div class="category-card-small js-filter-category" data-cat="new">
          <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=200" alt="Nouveautés" class="category-card-small__image">
          <span class="category-card-small__label">Nouveautés</span>
        </div>
      </div>
    </div>
  </section>

<!-- ================================================================================
      == RECOMMENDED PRODUCTS SECTION ==
      ================================================================================ -->
  <main class="container section " id="main-content">
  <section class="products-section section" id="recommended-section">
    <div class="container">
      <div class="products-section__header">
        <h2 class="section-title">Recommandés</h2>
        <div class="products-header-controls">
          <div class="products-filter-tabs">
            <button class="products-filter-tab active" data-filter="all">Tous</button>
            <button class="products-filter-tab" data-filter="Nouveau">Nouveautés</button>
            <button class="products-filter-tab" data-filter="Best-Seller">Tendances</button>
            <button class="products-filter-tab" data-filter="Promo">Promos</button>
          </div>
          <button class="btn-filter js-filter-trigger" aria-label="Filtrer les produits">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <span>Filtrer</span>
          </button>
        </div>
      </div>
      <div class="product-grid js-product-grid" id="product-grid-container"></div>
    </div>
  </section>

  <!-- Sidebar Backdrop & Sidebar Drawer -->
  <div class="sidebar-backdrop js-sidebar-backdrop index-page-backdrop"></div>
  
  <aside class="sidebar js-sidebar sidebar--drawer" aria-label="Filtres">
    <div class="sidebar__header">
      <h2 class="sidebar__title" style="font-size: 20px; font-weight: 700; margin-bottom: 0;">Filtres</h2>
      <button class="sidebar__close-btn js-close-filter" aria-label="Fermer">×</button>
    </div>
    <div class="sidebar__content">
      <!-- Section Prix -->
      <div class="sidebar__section">
        <h3 class="sidebar__title" style="font-size: 15px; font-weight: 600; margin-bottom: 12px; color: var(--primary-color);">Prix</h3>
        <div class="range-container">
          <div class="range-inputs">
            <input type="number" class="js-min-price-input" value="1000" readonly>
            <span style="color:var(--text-muted);">à</span>
            <input type="number" class="js-max-price-input" value="50000" readonly>
          </div>
          <input type="range" min="1000" max="50000" step="1000" value="50000" class="range-slider js-range-slider">
          <span class="range-label js-range-label">1 000 - 50 000 FCFA</span>
        </div>
      </div>
      
      <!-- Section Catégories -->
      <div class="sidebar__section">
        <h3 class="sidebar__title" style="font-size: 15px; font-weight: 600; margin-bottom: 12px; color: var(--primary-color);">Catégories</h3>
        <ul class="filter-list">
          <li class="filter-item">
            <label><input type="checkbox" class="js-cat-filter" value="CAT01"> Électronique</label>
          </li>
          <li class="filter-item">
            <label><input type="checkbox" class="js-cat-filter" value="CAT02"> Vêtements & Mode</label>
          </li>
          <li class="filter-item">
            <label><input type="checkbox" class="js-cat-filter" value="CAT03"> Maison & Déco</label>
          </li>
          <li class="filter-item">
            <label><input type="checkbox" class="js-cat-filter" value="CAT04"> Cosmétique & Beauté</label>
          </li>
        </ul>
      </div>

      <!-- Section Fournisseurs -->
      <div class="sidebar__section">
        <h3 class="sidebar__title" style="font-size: 15px; font-weight: 600; margin-bottom: 12px; color: var(--primary-color);">Fournisseurs</h3>
        <ul class="filter-list">
          <li class="filter-item">
            <label><input type="checkbox" class="js-sup-filter" value="FOU01"> Afrimarket Sarl</label>
          </li>
          <li class="filter-item">
            <label><input type="checkbox" class="js-sup-filter" value="FOU02"> Sahel Import</label>
          </li>
          <li class="filter-item">
            <label><input type="checkbox" class="js-sup-filter" value="FOU03"> Babi Mode</label>
          </li>
        </ul>
      </div>
      
      <!-- Section Stock -->
      <div class="sidebar__section">
        <h3 class="sidebar__title" style="font-size: 15px; font-weight: 600; margin-bottom: 12px; color: var(--primary-color);">Stock</h3>
        <ul class="filter-list">
          <li class="filter-item"><label><input type="radio" name="filter-stock" value="all" checked> Tous</label></li>
          <li class="filter-item"><label><input type="radio" name="filter-stock" value="disponible"> En stock</label></li>
          <li class="filter-item"><label><input type="radio" name="filter-stock" value="faible"> Dernières pièces</label></li>
        </ul>
      </div>
      
      <div class="sidebar__actions">
        <button class="btn btn-primary js-apply-filters" style="width: 100%; min-height: 44px; padding: 12px;">Appliquer</button>
        <button class="btn btn-secondary js-reset-filters" style="width: 100%; min-height: 44px; padding: 12px; margin-top: 8px;">Réinitialiser</button>
      </div>
    </div>
  </aside>
</main>

  <!-- ================================================================================
     == FOOTER ==
     ================================================================================ -->
  <footer class="footer" id="main-footer">
    <div class="container">
      <div class="footer-grid">
        <!-- Logo and info -->
        <div class="footer-col">
          <img src="<?= RACINE ?>assets/images/logo/woli.png" alt="Woli Logo" class="footer-col__logo">
          <p class="footer-col__text">Woli est la plateforme de référence pour des achats sécurisés, connectant directement les consommateurs aux meilleurs fournisseurs locaux.</p>
          <div class="footer-col__socials">
            <a href="#" class="footer-col__social-link" aria-label="Woli sur Facebook">FB</a>
            <a href="#" class="footer-col__social-link" aria-label="Woli sur Twitter">TW</a>
            <a href="#" class="footer-col__social-link" aria-label="Woli sur Instagram">IG</a>
            <a href="#" class="footer-col__social-link" aria-label="Woli sur LinkedIn">IN</a>
          </div>
        </div>

        <!-- Quick links -->
        <div class="footer-col">
          <h3 class="footer-col__title">Woli Links</h3>
          <ul class="footer-links">
            <li><a href="#">À propos de nous</a></li>
            <li><a href="#">Conditions de vente</a></li>
            <li><a href="#">Devenir Livreur</a></li>
            <li><a href="#">Espace Fournisseur</a></li>
          </ul>
        </div>

        <!-- Categories links -->
        <div class="footer-col">
          <h3 class="footer-col__title">Catégories</h3>
          <ul class="footer-links">
            <li><a href="#" onclick="activeFilters.categories=['CAT01']; renderProducts();">Électronique</a></li>
            <li><a href="#" onclick="activeFilters.categories=['CAT02']; renderProducts();">Vêtements</a></li>
            <li><a href="#" onclick="activeFilters.categories=['CAT03']; renderProducts();">Maison & Déco</a></li>
            <li><a href="#" onclick="activeFilters.categories=['CAT04']; renderProducts();">Cosmétique</a></li>
          </ul>
        </div>

        <!-- Contact & Newsletter -->
        <div class="footer-col">
          <h3 class="footer-col__title">Nous contacter</h3>
          <div class="footer-contact">
            <p>📧 Email: contact@woli.ci</p>
            <p>📞 Tél: +225 07 08 09 10 11</p>
            <p>📍 Adresse: Plateau, Abidjan, Côte d'Ivoire</p>
          </div>
          <div class="footer-newsletter">
            <form class="newsletter-form" onsubmit="event.preventDefault(); showToast('Inscription validée !', 'success'); this.reset();">
              <input type="email" placeholder="Votre adresse email..." aria-label="S'inscrire à la newsletter" required>
              <button type="submit" class="btn btn-primary" style="min-height: 40px; padding: 10px 16px;">S'abonner</button>
            </form>
          </div>
        </div>
      </div>

      <!-- Copyright row -->
      <div class="footer-bottom">
        <div>&copy; 2026 Woli E-commerce. Tous droits réservés.</div>
        <div style="display:flex; gap:16px;">
          <a href="#" style="color:var(--text-muted); text-decoration:none;">Mentions Légales</a>
          <span style="color:rgba(255,255,255,0.2);">|</span>
          <a href="#" style="color:var(--text-muted); text-decoration:none;">Données Personnelles</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- ================================================================================
     == MOBILE BOTTOM APP BAR (Sticky Footer) ==
     ================================================================================ -->
  <nav class="bottom-navbar mobile-only" aria-label="Menu de navigation mobile rapide">
    <a href="#" class="bottom-navbar__item bottom-navbar__item--active" onclick="document.querySelectorAll('.bottom-navbar__item').forEach(el=>el.classList.remove('bottom-navbar__item--active')); this.classList.add('bottom-navbar__item--active'); activeFilters={search:'',categories:[],minPrice:0,maxPrice:50000,stock:'all',suppliers:[]}; renderProducts(); return false;">
      <svg class="bottom-navbar__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
      <span>Accueil</span>
    </a>
    <a href="#" class="bottom-navbar__item" onclick="document.querySelectorAll('.bottom-navbar__item').forEach(el=>el.classList.remove('bottom-navbar__item--active')); this.classList.add('bottom-navbar__item--active'); openCategoriesExplorerFullscreen(); return false;">
      <svg class="bottom-navbar__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V7a2 2 0 00-2-2H7a2 2 0 00-2 2v4"></path></svg>
      <span>Catégories</span>
    </a>
    <a href="#" class="bottom-navbar__item" onclick="document.querySelectorAll('.bottom-navbar__item').forEach(el=>el.classList.remove('bottom-navbar__item--active')); this.classList.add('bottom-navbar__item--active'); document.querySelector('.js-search-trigger').click(); return false;">
      <svg class="bottom-navbar__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
      <span>Rechercher</span>
    </a>
    <a href="#" class="bottom-navbar__item" onclick="document.querySelectorAll('.bottom-navbar__item').forEach(el=>el.classList.remove('bottom-navbar__item--active')); this.classList.add('bottom-navbar__item--active'); openFavoritesFullscreen(); return false;">
      <svg class="bottom-navbar__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
      <span>Favoris</span>
    </a>
    <a href="#" class="bottom-navbar__item js-cart-trigger" id="mobile-cart-btn">
      <svg class="bottom-navbar__icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
      <span>Panier</span>
      <span class="bottom-navbar__badge js-cart-count" id="cart-badge-mobile-footer">0</span>
    </a>
  </nav>

  <!-- ================================================================================
     == CART DRAWER OVERLAY ==
     ================================================================================ -->
  <div class="cart-drawer js-cart-drawer" id="cart-drawer">
    <div class="cart-drawer__header">
      <h2 class="cart-drawer__title">Votre Panier Woli</h2>
      <button class="cart-drawer__close js-close-cart" aria-label="Fermer le panier">×</button>
    </div>
    
    <div class="cart-drawer__content">
      <!-- Empty message -->
      <div class="cart-drawer__empty js-cart-empty">
        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-bottom:12px; color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <p>Votre panier est vide</p>
        <p style="font-size:12px; margin-top:4px;">Ajoutez des articles pour commencer vos achats.</p>
      </div>

      <!-- Items List -->
      <div class="cart-drawer__items js-cart-items">
        <!-- Rendered dynamically -->
      </div>
    </div>

    <!-- Summary & Checkout Footer -->
    <div class="cart-drawer__footer">
      <div class="cart-summary-row">
        <span>Sous-total</span>
        <span class="cart-summary-row__value js-cart-total-value">0 FCFA</span>
      </div>
      <div class="cart-summary-row" style="font-size:13px; color:var(--text-secondary);">
        <span>Livraison estimée</span>
        <span>Calculée à l'étape suivante</span>
      </div>
      <button class="btn btn-primary js-cart-checkout-btn" style="width:100%; margin-top:12px;" onclick="startCheckout()">
        Passer la commande
      </button>
    </div>
  </div>

  <!-- ================================================================================
     == PRODUCT DETAIL MODAL (MODAL OVERLAY) ==
     ================================================================================ -->
  <div class="modal-overlay js-modal-overlay" id="product-modal">
    <div class="modal">
      <button class="modal__close js-modal-close" aria-label="Fermer les détails">×</button>
      <div class="js-modal-body" id="product-modal-body">
        <!-- Loaded dynamically via JS -->
      </div>
    </div>
  </div>

  <!-- ================================================================================
     == CHECKOUT MULTI-STEP MODAL ==
     ================================================================================ -->
  <div class="modal-overlay js-checkout-modal-overlay" id="checkout-modal">
    <div class="modal" style="max-width: 550px;">
      <button class="modal__close" onclick="closeCheckoutModal()" aria-label="Fermer la commande">×</button>
      
      <!-- Steps Header Indicators -->
      <div class="checkout-steps">
        <div class="checkout-step checkout-step--active">
          <div class="checkout-step__circle">1</div>
          <span class="checkout-step__label">Livraison</span>
        </div>
        <div class="checkout-step">
          <div class="checkout-step__circle">2</div>
          <span class="checkout-step__label">Paiement</span>
        </div>
        <div class="checkout-step">
          <div class="checkout-step__circle">3</div>
          <span class="checkout-step__label">Résumé</span>
        </div>
        <div class="checkout-step">
          <div class="checkout-step__circle">4</div>
          <span class="checkout-step__label">Confirmation</span>
        </div>
      </div>

      <!-- Step 1 Form: Client Information (matching clients table) -->
      <div class="checkout-flow-container active" id="checkout-step-1">
        <h2 style="font-size:18px; margin-bottom:16px;">Adresse de livraison</h2>
        <form onsubmit="event.preventDefault(); nextCheckoutStep();">
          <div class="form-group">
            <label for="chk-name">Nom complet *</label>
            <input type="text" id="chk-name" class="form-control" placeholder="Ex: Jean Koffi" required>
          </div>
          <div class="form-group">
            <label for="chk-phone">Numéro Téléphone / WhatsApp *</label>
            <input type="tel" id="chk-phone" class="form-control" placeholder="Ex: +225 07 08 09 10" required>
          </div>
          <div class="form-group">
            <label for="chk-city">Ville / Localité *</label>
            <input type="text" id="chk-city" class="form-control" placeholder="Ex: Abidjan (Cocody)" required>
          </div>
          <div class="form-group">
            <label for="chk-address">Indications géographiques précises *</label>
            <textarea id="chk-address" class="form-control" rows="2" placeholder="Ex: Face à la pharmacie du centre, immeuble bleu" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">Continuer vers le paiement</button>
        </form>
      </div>

      <!-- Step 2 Form: Payment selection (matching paiements table) -->
      <div class="checkout-flow-container" id="checkout-step-2">
        <h2 style="font-size:18px; margin-bottom:16px;">Sélectionner un moyen de paiement</h2>
        <div class="payment-methods">
          <label class="payment-method-option">
            <input type="radio" name="payment-method" value="cash" checked>
            <div>
              <div class="payment-method-option__title">Paiement cash à la livraison</div>
              <div class="payment-method-option__desc">Réglez en espèces directement auprès du livreur à réception de votre colis.</div>
            </div>
          </label>
          <label class="payment-method-option">
            <input type="radio" name="payment-method" value="mobile_money">
            <div>
              <div class="payment-method-option__title">Mobile Money (Wave, MTN, Orange Money)</div>
              <div class="payment-method-option__desc">Réglez de manière instantanée et sécurisée via votre portefeuille mobile.</div>
            </div>
          </label>
          <label class="payment-method-option">
            <input type="radio" name="payment-method" value="carte">
            <div>
              <div class="payment-method-option__title">Carte Bancaire (Visa / Mastercard)</div>
              <div class="payment-method-option__desc">Transaction sécurisée SSL par carte de débit ou de crédit.</div>
            </div>
          </label>
        </div>
        <div style="display:flex; gap:12px; margin-top:24px;">
          <button class="btn btn-secondary" style="flex:1;" onclick="prevCheckoutStep()">Retour</button>
          <button class="btn btn-primary" style="flex:1;" onclick="nextCheckoutStep()">Suivant</button>
        </div>
      </div>

      <!-- Step 3: Order Summary -->
      <div class="checkout-flow-container" id="checkout-step-3">
        <h2 style="font-size:18px; margin-bottom:16px;">Vérifiez votre commande</h2>
        <div style="background-color:var(--bg-secondary); padding:16px; border-radius:8px; margin-bottom:16px;">
          <div class="cart-summary-row">
            <span>Sous-total articles</span>
            <span class="js-checkout-subtotal font-mono">0 FCFA</span>
          </div>
          <div class="cart-summary-row">
            <span>Frais de livraison</span>
            <span class="js-checkout-shipping font-mono">2 000 FCFA</span>
          </div>
          <div class="cart-summary-row cart-summary-row--total" style="margin-bottom:0; padding-bottom:0;">
            <span>Montant Total</span>
            <span class="js-checkout-total font-mono" style="font-size: 20px;">0 FCFA</span>
          </div>
        </div>
        <div style="font-size:13px; color:var(--text-secondary); margin-bottom:24px;">
          ⚠️ En cliquant sur "Confirmer la commande", vous acceptez nos conditions générales de vente. Votre commande sera immédiatement transmise à notre partenaire logistique.
        </div>
        <div style="display:flex; gap:12px;">
          <button class="btn btn-secondary" style="flex:1;" onclick="prevCheckoutStep()">Retour</button>
          <button class="btn btn-primary" style="flex:1;" onclick="confirmCheckoutOrder()" id="confirm-order-btn">Confirmer la commande</button>
        </div>
      </div>

      <!-- Step 4: Success & Confirmation (Receipt aligning with db.sql columns) -->
      <div class="checkout-flow-container" id="checkout-step-4" style="text-align:center;">
        <div style="font-size: 48px; color: var(--color-success); margin-bottom:12px;">🎉</div>
        <h2 style="font-size:20px; color:var(--primary-color); margin-bottom:8px;">Merci pour votre commande !</h2>
        <p style="font-size:14px; color:var(--text-secondary); margin-bottom:20px;">Votre commande a bien été enregistrée et est en attente de traitement logistique.</p>
        
        <div style="background:var(--bg-secondary); border: 2px dashed var(--text-muted); border-radius:8px; padding:16px; text-align:left; margin-bottom:24px;">
          <div style="margin-bottom:8px;"><strong>Référence de commande :</strong> <span class="js-confirm-code" style="font-family:var(--font-mono); font-weight:700; color:var(--secondary-color);">CMD-1234</span></div>
          <div style="margin-bottom:8px;"><strong>Client :</strong> <span class="js-confirm-client">Jean Koffi</span></div>
          <div style="margin-bottom:8px;"><strong>Moyen de paiement :</strong> <span class="js-confirm-payment">Cash à la livraison</span></div>
          <div style="margin-bottom:8px;"><strong>Frais de livraison :</strong> <span style="font-family:var(--font-mono);">2 000 FCFA</span></div>
          <div style="margin-bottom:0;"><strong>Statut :</strong> <span style="color:var(--color-info); font-weight:600;">En cours d'attribution livreur</span></div>
        </div>

        <button class="btn btn-primary" style="width:100%;" onclick="closeCheckoutModal()">Retourner à la boutique</button>
      </div>
    </div>
  </div>

  <!-- ================================================================================
     == CATEGORY FULLSCREEN MODAL (Alibaba-style mobile page) ==
     ================================================================================ -->
  <div class="category-fullscreen js-category-fullscreen" id="category-fullscreen" aria-label="Explorer la catégorie">
    <!-- Sticky Header with back arrow + search -->
    <div class="category-fullscreen__header">
      <button class="category-fullscreen__back js-category-back" aria-label="Retour">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <div class="category-fullscreen__search-wrapper">
        <svg class="category-fullscreen__search-icon" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        <input type="text" class="category-fullscreen__search js-category-search" id="category-search-input" placeholder="Rechercher dans cette catégorie..." aria-label="Rechercher dans la catégorie">
      </div>
    </div>

    <!-- Category Info Banner -->
    <div class="category-fullscreen__banner js-category-banner">
      <div class="category-fullscreen__banner-overlay"></div>
      <img src="" alt="" class="category-fullscreen__banner-img js-category-banner-img">
      <div class="category-fullscreen__banner-content">
        <h2 class="category-fullscreen__banner-title js-category-banner-title"></h2>
        <p class="category-fullscreen__banner-desc js-category-banner-desc"></p>
        <span class="category-fullscreen__banner-count js-category-banner-count"></span>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="category-fullscreen__body">
      <div class="category-fullscreen__grid js-category-grid" id="category-grid"></div>
    </div>
  </div>


  <!-- ================================================================================
     == ACCOUNT FULLSCREEN MODAL (Alibaba-style) ==
     ================================================================================ -->
  <div class="category-fullscreen js-account-fullscreen" id="account-fullscreen" aria-label="Mon Compte">
    <!-- Sticky Header with back arrow -->
    <div class="category-fullscreen__header" style="justify-content: flex-start; gap: 16px;">
      <button class="category-fullscreen__back js-account-back" aria-label="Retour" onclick="closeAccountFullscreen()" style="position: static; margin-right: 0;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <div style="font-family: var(--font-title); font-size: 18px; font-weight: 700; color: #FFFFFF;">Mon Compte</div>
    </div>

    <div class="category-fullscreen__body" style="padding-top: 72px;">
      <!-- Profile section -->
      <div style="background: #FFFFFF; border-radius: 12px; padding: var(--space-lg); box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: var(--space-md); display: flex; align-items: center; gap: var(--space-md);">
        <div style="width: 64px; height: 64px; border-radius: 50%; background-color: var(--secondary-color); color: #FFFFFF; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 700; font-family: var(--font-title);">
          JK
        </div>
        <div>
          <h3 style="font-size: 18px; font-weight: 700; color: var(--primary-color); margin-bottom: 4px;">Jean Koffi</h3>
          <p style="font-size: 14px; color: var(--text-secondary); margin-bottom: 2px;">jean.koffi@email.com</p>
          <p style="font-size: 14px; color: var(--text-secondary);">+225 07 08 09 10 11</p>
        </div>
      </div>

      <!-- Orders Section -->
      <div style="margin-bottom: var(--space-lg);">
        <h3 style="font-family: var(--font-title); font-size: 16px; font-weight: 700; color: var(--primary-color); margin-bottom: var(--space-md);">Mes Dernières Commandes</h3>
        <div style="display: flex; flex-direction: column; gap: var(--space-sm);">
          <!-- Order Card 1 -->
          <div style="background: #FFFFFF; border-radius: 12px; padding: var(--space-md); box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-sm); border-bottom: 1px solid #F3F4F6; padding-bottom: 8px;">
              <span style="font-family: var(--font-mono); font-weight: 700; color: var(--secondary-color);">CMD-4521</span>
              <span style="background-color: rgba(255, 107, 0, 0.1); color: var(--secondary-color); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">En livraison</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--text-secondary);">
              <span>12 mai 2026</span>
              <span style="font-weight: 700; color: var(--primary-color);">45 200 FCFA</span>
            </div>
          </div>
          <!-- Order Card 2 -->
          <div style="background: #FFFFFF; border-radius: 12px; padding: var(--space-md); box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--space-sm); border-bottom: 1px solid #F3F4F6; padding-bottom: 8px;">
              <span style="font-family: var(--font-mono); font-weight: 700; color: var(--secondary-color);">CMD-4489</span>
              <span style="background-color: rgba(16, 185, 129, 0.1); color: var(--color-success); padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600;">Livrée</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 13px; color: var(--text-secondary);">
              <span>28 avril 2026</span>
              <span style="font-weight: 700; color: var(--primary-color);">18 500 FCFA</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Action Links -->
      <div style="background: #FFFFFF; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: var(--space-lg); border: 1px solid #E5E7EB;">
        <div style="padding: 16px; border-bottom: 1px solid #F3F4F6; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="showToast('Carnet d\'adresses (simulation)', 'info')">
          <span style="font-weight: 500; font-size: 15px;">Mes Adresses de livraison</span>
          <span style="color: var(--text-muted);">›</span>
        </div>
        <div style="padding: 16px; border-bottom: 1px solid #F3F4F6; display: flex; justify-content: space-between; align-items: center; cursor: pointer;" onclick="showToast('Moyens de paiement (simulation)', 'info')">
          <span style="font-weight: 500; font-size: 15px;">Moyens de paiement</span>
          <span style="color: var(--text-muted);">›</span>
        </div>
        <a href="auth.html" style="padding: 16px; display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: var(--color-error); font-weight: 600;">
          <span>Déconnexion</span>
          <span>›</span>
        </a>
      </div>
    </div>
  </div>

  <!-- ================================================================================
     == FAVORITES FULLSCREEN MODAL (Alibaba-style) ==
     ================================================================================ -->
  <div class="category-fullscreen js-favorites-fullscreen" id="favorites-fullscreen" aria-label="Mes Favoris">
    <!-- Sticky Header with back arrow -->
    <div class="category-fullscreen__header" style="justify-content: flex-start; gap: 16px;">
      <button class="category-fullscreen__back js-favorites-back" aria-label="Retour" onclick="closeFavoritesFullscreen()" style="position: static; margin-right: 0;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <div style="font-family: var(--font-title); font-size: 18px; font-weight: 700; color: #FFFFFF;">Mes Favoris</div>
    </div>

    <div class="category-fullscreen__body" style="padding-top: 72px;">
      <div class="category-fullscreen__grid js-favorites-grid" id="favorites-grid">
        <!-- Filled dynamically by JS -->
      </div>
    </div>
  </div>

  <!-- ================================================================================
       == CATEGORIES EXPLORER FULLSCREEN MODAL (Alibaba-style) ==
      ================================================================================ -->
  <div class="category-fullscreen js-categories-explorer-fullscreen" id="categories-explorer-fullscreen" aria-label="Explorer les catégories">
    <!-- Sticky Header with back arrow -->
    <div class="category-fullscreen__header" style="justify-content: flex-start; gap: 16px;">
      <button class="category-fullscreen__back js-categories-explorer-back" aria-label="Retour" onclick="closeCategoriesExplorerFullscreen()" style="position: static; margin-right: 0;">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <div style="font-family: var(--font-title); font-size: 18px; font-weight: 700; color: #FFFFFF;">Toutes les Catégories</div>
    </div>

    <div class="category-fullscreen__body" style="padding-top: 72px;">
      <div class="categories-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: var(--space-md); margin-top: var(--space-md);">
        <!-- Electronic Card -->
        <div class="category-card" onclick="closeCategoriesExplorerFullscreen(); openCategoryFullscreen('CAT01', 'Électronique', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500')">
          <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500" alt="Électronique" class="category-card__image">
          <div class="category-card__overlay">
            <h3 class="category-card__title">Électronique</h3>
          </div>
        </div>
        <!-- Vêtements Card -->
        <div class="category-card" onclick="closeCategoriesExplorerFullscreen(); openCategoryFullscreen('CAT02', 'Vêtements & Mode', 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500')">
          <img src="https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500" alt="Vêtements" class="category-card__image">
          <div class="category-card__overlay">
            <h3 class="category-card__title">Vêtements</h3>
          </div>
        </div>
        <!-- Maison Card -->
        <div class="category-card" onclick="closeCategoriesExplorerFullscreen(); openCategoryFullscreen('CAT03', 'Maison & Déco', 'https://images.unsplash.com/photo-1517256064527-09c53b2d0bc6?w=500')">
          <img src="https://images.unsplash.com/photo-1517256064527-09c53b2d0bc6?w=500" alt="Maison" class="category-card__image">
          <div class="category-card__overlay">
            <h3 class="category-card__title">Maison</h3>
          </div>
        </div>
        <!-- Beauté Card -->
        <div class="category-card" onclick="closeCategoriesExplorerFullscreen(); openCategoryFullscreen('CAT04', 'Cosmétique & Beauté', 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=500')">
          <img src="https://images.unsplash.com/photo-1541643600914-78b084683601?w=500" alt="Beauté" class="category-card__image">
          <div class="category-card__overlay">
            <h3 class="category-card__title">Beauté</h3>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ================================================================================
      == PRODUCT FULLSCREEN PAGE (Alibaba-style) ==
      ================================================================================ -->
  <div class="category-fullscreen js-product-fullscreen" id="product-fullscreen" aria-label="Détail produit">
    <div class="category-fullscreen__header">
      <button class="category-fullscreen__back js-product-fullscreen-back" aria-label="Retour" onclick="closeProductFullscreen()">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
      </button>
      <div style="font-family: var(--font-title); font-size: 15px; font-weight: 700; color: #FFFFFF;">Détail produit</div>
    </div>

    <div class="category-fullscreen__body" style="padding-top: 72px;">
      <div class="product-fullscreen-content js-product-fullscreen-content"></div>

      <section class="section" style="background: var(--bg-secondary);">
        <div class="container">
          <h2 class="section-title">Recommandés</h2>
          <div class="product-grid js-product-fullscreen-recommendations"></div>
        </div>
      </section>

      <div style="height: 16px;"></div>
    </div>
  </div>

  <!-- Toast Notification Container -->
  <div class="toast-container js-toast-container" id="toast-container" aria-live="polite"></div>


  <!-- end client section -->
  <?php require_once '../public/inc/footer.php'; ?>