/**
 * FitWear Custom Theme JavaScript
 * Handles Size Guide Modal, AJAX Cart Drawer operations, upsells, and free shipping progress.
 */
document.addEventListener('DOMContentLoaded', function() {
  initSizeGuideModal();
  initAjaxCart();
});

/**
 * 1. Size Guide Modal Handler
 */
function initSizeGuideModal() {
  const modal = document.getElementById('fitwear-size-modal');
  if (!modal) return;

  const closeBtns = modal.querySelectorAll('[data-close-modal]');
  const externalLink = document.getElementById('modal-size-guide-external');

  // Listen to open triggers from buttons
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-size-guide')) {
      e.preventDefault();
      const sizeUrl = e.target.getAttribute('data-size-url');
      if (sizeUrl) {
        externalLink.setAttribute('href', sizeUrl);
        externalLink.style.display = 'inline-block';
      } else {
        externalLink.style.display = 'none';
      }
      openModal(modal);
    }
  });

  closeBtns.forEach(btn => {
    btn.addEventListener('click', () => closeModal(modal));
  });
}

function openModal(modal) {
  modal.classList.add('active');
  modal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
  modal.classList.remove('active');
  modal.setAttribute('aria-hidden', 'true');
  document.body.style.overflow = '';
}

/**
 * 2. AJAX Cart Drawer Operations
 */
function initAjaxCart() {
  const drawer = document.getElementById('fitwear-cart-drawer');
  if (!drawer) return;

  const closeBtns = drawer.querySelectorAll('[data-close-drawer]');
  const itemsList = document.getElementById('cart-drawer-items-list');
  const cartSubtotal = document.getElementById('cart-drawer-subtotal-val');
  const cartCountVal = document.getElementById('cart-drawer-count');

  // Shipping progress elements
  const shippingRemaining = document.getElementById('free-shipping-remaining');
  const shippingProgressFill = document.getElementById('shipping-progress-fill');
  const freeShippingMsg = document.getElementById('free-shipping-msg');

  // Upsell elements
  const upsellBox = document.getElementById('cart-drawer-upsell-container');
  const upsellAddBtn = document.getElementById('btn-add-upsell-ajax');
  const upsellImgBox = document.getElementById('upsell-product-img-box');

  // Dynamic upsell products list parsed from Liquid JSON script tag (Task 7.2)
  const upsellProductsEl = document.getElementById('fitwear-upsell-products-json');
  let realUpsellProducts = [];
  if (upsellProductsEl) {
    try {
      realUpsellProducts = JSON.parse(upsellProductsEl.textContent);
    } catch (e) {
      console.warn("Dynamic store products list not loaded (running in mock mode).");
    }
  }

  // Close actions
  closeBtns.forEach(btn => {
    btn.addEventListener('click', () => closeDrawer(drawer));
  });

  // Intercept standard add-to-cart clicks
  document.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('btn-add-to-cart-ajax')) {
      e.preventDefault();
      const variantId = e.target.getAttribute('data-variant-id');
      if (variantId) {
        addToCartAjax(variantId);
      }
    }
  });

  // Handle quantity adjustments
  itemsList.addEventListener('click', function(e) {
    if (e.target && e.target.classList.contains('qty-btn')) {
      const variantId = e.target.getAttribute('data-variant-id');
      const currentQty = parseInt(e.target.getAttribute('data-current-qty'));
      const change = parseInt(e.target.getAttribute('data-change'));
      const newQty = currentQty + change;
      if (newQty >= 0) {
        updateCartQty(variantId, newQty);
      }
    } else if (e.target && e.target.classList.contains('cart-item-remove')) {
      const variantId = e.target.getAttribute('data-variant-id');
      updateCartQty(variantId, 0);
    }
  });

  // Handle upsell addition
  if (upsellAddBtn) {
    upsellAddBtn.addEventListener('click', function(e) {
      e.preventDefault();
      const variantId = e.target.getAttribute('data-upsell-variant-id');
      if (variantId) {
        addToCartAjax(variantId);
      }
    });
  }

  // Load cart initially
  refreshCartDrawer();

  // Helper function to open drawer
  function openDrawer(drawer) {
    drawer.classList.add('active');
    drawer.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer(drawer) {
    drawer.classList.remove('active');
    drawer.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  // Fetch cart data from Shopify /cart.js
  function refreshCartDrawer() {
    fetch('/cart.js')
      .then(res => res.json())
      .then(cart => {
        renderCartItems(cart);
        updateShippingProgress(cart);
        checkComplementaryUpsell(cart);
      })
      .catch(err => {
        console.error('Error fetching cart:', err);
        // Load mock cart data for local testing
        loadMockCart();
      });
  }

  // Add Item to cart via /cart/add.js
  function addToCartAjax(variantId) {
    const formData = {
      'items': [{
        'id': variantId,
        'quantity': 1
      }]
    };

    fetch('/cart/add.js', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(item => {
      refreshCartDrawer();
      openDrawer(drawer);
    })
    .catch(err => {
      console.warn('Shopify API cart endpoints unavailable, updating mock cart instead.');
      addMockItem(variantId);
      openDrawer(drawer);
    });
  }

  // Update item quantity via /cart/change.js
  function updateCartQty(variantId, quantity) {
    const formData = {
      'id': variantId,
      'quantity': quantity
    };

    fetch('/cart/change.js', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(cart => {
      refreshCartDrawer();
    })
    .catch(err => {
      console.warn('Shopify API cart endpoints unavailable, updating mock qty.');
      updateMockItemQty(variantId, quantity);
    });
  }

  // Render items markup
  function renderCartItems(cart) {
    cartCountVal.textContent = cart.item_count;
    cartSubtotal.textContent = formatMoney(cart.total_price);

    if (cart.item_count === 0) {
      itemsList.innerHTML = '<p class="cart-empty-text">Your cart is currently empty.</p>';
      return;
    }

    let html = '';
    cart.items.forEach(item => {
      html += `
        <div class="cart-drawer-item" id="cart-item-${item.key}">
          <div class="cart-item-image">
            <img src="${item.image || 'https://via.placeholder.com/70x70'}" alt="${item.product_title}">
          </div>
          <div class="cart-item-info">
            <span class="cart-item-title">${item.product_title}</span>
            <span class="cart-item-price">${formatMoney(item.line_price)}</span>
            <div class="cart-item-actions">
              <div class="cart-item-qty-selector">
                <button type="button" class="qty-btn" data-variant-id="${item.variant_id}" data-current-qty="${item.quantity}" data-change="-1">&minus;</button>
                <span class="qty-val">${item.quantity}</span>
                <button type="button" class="qty-btn" data-variant-id="${item.variant_id}" data-current-qty="${item.quantity}" data-change="1">+</button>
              </div>
              <button type="button" class="cart-item-remove" data-variant-id="${item.variant_id}">Remove</button>
            </div>
          </div>
        </div>
      `;
    });
    itemsList.innerHTML = html;
  }

  // Update Free Shipping Bar
  function updateShippingProgress(cart) {
    const threshold = 10000; // $100.00 represented in cents
    const totalPrice = cart.total_price;
    const progress = Math.min((totalPrice / threshold) * 100, 100);

    shippingProgressFill.style.width = `${progress}%`;

    if (totalPrice >= threshold) {
      freeShippingMsg.innerHTML = '<span style="color:var(--shopify-accent)">Congratulations! You\'ve unlocked Free Shipping!</span>';
    } else {
      const remaining = threshold - totalPrice;
      freeShippingMsg.innerHTML = `Add <span style="color:var(--shopify-accent)">${formatMoney(remaining)}</span> more for Free Shipping!`;
    }
  }

  // Upsell Logic: Suggest T-Shirt if leggings is in cart, or suggest accessories
  function checkComplementaryUpsell(cart) {
    let hasLeggings = false;
    let hasTshirt = false;
    
    // Track what is already in the cart (by product title or product ID)
    const cartProductTitles = cart.items.map(item => item.product_title.toLowerCase());
    
    cart.items.forEach(item => {
      const title = item.product_title.toLowerCase();
      if (title.includes('leggings') || title.includes('bottoms')) {
        hasLeggings = true;
      }
      if (title.includes('t-shirt') || title.includes('tee') || title.includes('top')) {
        hasTshirt = true;
      }
    });

    if (cart.item_count > 0 && (!hasTshirt || !hasLeggings)) {
      let targetTag = '';
      let fallbackTitle = '';
      let fallbackPrice = 0;
      let fallbackVariant = '';
      let fallbackImg = '';

      if (!hasTshirt) {
        targetTag = 'tops';
        fallbackTitle = 'FitWear Active T-Shirt';
        fallbackPrice = 2800;
        fallbackVariant = 'upsell-tshirt-variant';
        fallbackImg = 'https://via.placeholder.com/50x50/1e293b/ffffff?text=T-Shirt';
      } else {
        targetTag = 'bottoms';
        fallbackTitle = 'FitWear Compression Leggings';
        fallbackPrice = 5500;
        fallbackVariant = 'upsell-leggings-variant';
        fallbackImg = 'https://via.placeholder.com/50x50/1e293b/ffffff?text=Leggings';
      }

      // Look for a real product matching the target tag that is NOT already in the cart
      let recommendedProduct = realUpsellProducts.find(prod => {
        const hasTag = prod.tags.some(tag => tag.toLowerCase() === targetTag);
        const inCart = cartProductTitles.some(title => title.includes(prod.title.toLowerCase()) || prod.title.toLowerCase().includes(title));
        return hasTag && !inCart;
      });

      if (recommendedProduct) {
        upsellBox.style.display = 'block';
        document.getElementById('upsell-product-title').textContent = recommendedProduct.title;
        document.getElementById('upsell-product-price').textContent = formatMoney(recommendedProduct.price);
        upsellAddBtn.setAttribute('data-upsell-variant-id', recommendedProduct.variant_id);
        if (recommendedProduct.image) {
          upsellImgBox.innerHTML = `<img src="${recommendedProduct.image}" alt="">`;
        } else {
          upsellImgBox.innerHTML = `<img src="https://via.placeholder.com/50x50/1e293b/ffffff?text=${targetTag}" alt="">`;
        }
      } else if (realUpsellProducts.length === 0) {
        // Show fallback only for local preview/mock testing when JSON list is completely empty
        upsellBox.style.display = 'block';
        document.getElementById('upsell-product-title').textContent = fallbackTitle;
        document.getElementById('upsell-product-price').textContent = formatMoney(fallbackPrice);
        upsellAddBtn.setAttribute('data-upsell-variant-id', fallbackVariant);
        upsellImgBox.innerHTML = `<img src="${fallbackImg}" alt="">`;
      } else {
        // Hide completely on live store if no matching real product exists
        upsellBox.style.display = 'none';
      }
    } else {
      upsellBox.style.display = 'none';
    }
  }

  function formatMoney(cents) {
    return '$' + (cents / 100).toFixed(2);
  }

  /**
   * 3. Fallback Local Mock Store Operations (for preview / environment validation)
   */
  let mockCart = {
    item_count: 0,
    total_price: 0,
    items: []
  };

  const mockDb = {
    'upsell-tshirt-variant': { id: 'upsell-tshirt-variant', variant_id: 'upsell-tshirt-variant', product_title: 'FitWear Active T-Shirt', price: 2800, image: 'https://via.placeholder.com/70x70/1e293b/ffffff?text=T-Shirt' },
    'upsell-leggings-variant': { id: 'upsell-leggings-variant', variant_id: 'upsell-leggings-variant', product_title: 'FitWear Compression Leggings', price: 5500, image: 'https://via.placeholder.com/70x70/1e293b/ffffff?text=Leggings' }
  };

  function loadMockCart() {
    renderCartItems(mockCart);
    updateShippingProgress(mockCart);
    checkComplementaryUpsell(mockCart);
  }

  function addMockItem(variantId) {
    let mockProduct = mockDb[variantId];
    
    // Check if variantId matches a product in the real products list
    if (!mockProduct && realUpsellProducts.length > 0) {
      const realProd = realUpsellProducts.find(p => p.variant_id == variantId || p.variant_id === variantId);
      if (realProd) {
        mockProduct = {
          id: variantId,
          variant_id: variantId,
          product_title: realProd.title,
          price: realProd.price,
          image: realProd.image
        };
      }
    }

    if (!mockProduct) {
      // Create a default mock item
      mockProduct = {
        id: variantId,
        variant_id: variantId,
        product_title: 'FitWear Active Gear (' + variantId + ')',
        price: 2500,
        image: ''
      };
    }

    const existing = mockCart.items.find(item => item.variant_id === variantId);
    if (existing) {
      existing.quantity += 1;
      existing.line_price = existing.quantity * existing.price;
    } else {
      mockCart.items.push({
        key: variantId,
        variant_id: variantId,
        product_title: mockProduct.product_title,
        price: mockProduct.price,
        line_price: mockProduct.price,
        quantity: 1,
        image: mockProduct.image
      });
    }
    recalculateMockCart();
  }

  function updateMockItemQty(variantId, qty) {
    const idx = mockCart.items.findIndex(item => item.variant_id === variantId);
    if (idx !== -1) {
      if (qty === 0) {
        mockCart.items.splice(idx, 1);
      } else {
        mockCart.items[idx].quantity = qty;
        mockCart.items[idx].line_price = qty * mockCart.items[idx].price;
      }
    }
    recalculateMockCart();
  }

  function recalculateMockCart() {
    let count = 0;
    let subtotal = 0;
    mockCart.items.forEach(item => {
      count += item.quantity;
      subtotal += item.line_price;
    });
    mockCart.item_count = count;
    mockCart.total_price = subtotal;
    loadMockCart();
  }
}
