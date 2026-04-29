const BASE_URL = document.querySelector('meta[name="base-url"]')?.content
  ?? window.location.origin + '/Proyecto';

/* ══════════════════════════════════════════════════
   UTILIDADES
══════════════════════════════════════════════════ */
function fmtPrice(n) {
  return 'Bs. ' + parseFloat(n).toFixed(2);
}

function updateCartBadge(count) {
  ['cartCount', 'ocCartCount'].forEach(id => {
    const el = document.getElementById(id);
    if (!el) return;
    el.textContent = count;
    el.style.transform = 'scale(1.4)';
    setTimeout(() => el.style.transform = 'scale(1)', 200);
  });
}

function showToast(msg, type = 'success') {
  let container = document.getElementById('toastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.style.cssText =
      'position:fixed;bottom:1.5rem;right:1.5rem;z-index:9999;display:flex;flex-direction:column;gap:.5rem;';
    document.body.appendChild(container);
  }
  const icon = type === 'success' ? 'check-circle-fill' : 'exclamation-triangle-fill';
  const toast = document.createElement('div');
  toast.className = `alert alert-${type} shadow py-2 px-3 mb-0 d-flex align-items-center gap-2`;
  toast.style.cssText = 'min-width:220px;max-width:320px;animation:slideIn .3s ease;';
  toast.innerHTML = `<i class="bi bi-${icon}"></i> ${msg}`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

/* ══════════════════════════════════════════════════
   CARRITO — OFFCANVAS
══════════════════════════════════════════════════ */
function renderCartOffcanvas(data) {
  const body   = document.getElementById('ocCartBody');
  const footer = document.getElementById('ocCartFooter');
  const items  = data.items ?? [];

  if (!items.length) {
    body.innerHTML = `
      <div class="text-center py-5 text-muted">
        <i class="bi bi-cart-x" style="font-size:4rem;opacity:.3"></i>
        <p class="mt-3 fs-6">Tu carrito está vacío</p>
        <button class="btn btn-primary btn-sm" data-bs-dismiss="offcanvas"
                onclick="window.location='${BASE_URL}/index.php?r=store/index'">
          <i class="bi bi-arrow-left me-1"></i>Ir a la tienda
        </button>
      </div>`;
    footer.classList.add('d-none');
    return;
  }

  const rows = items.map(item => {
    const img = item.imagen
      ? `${BASE_URL}/resources/imagenes/${item.imagen}`
      : `${BASE_URL}/public/img/no-image.svg`;
    return `
    <div class="oc-cart-item d-flex align-items-center gap-3 py-3 border-bottom" id="ocrow-${item.id}">
      <img src="${img}" width="70" height="70"
           style="object-fit:contain;border-radius:8px;background:#f8f9fa;padding:4px;flex-shrink:0"
           onerror="this.src='${BASE_URL}/public/img/no-image.svg'">
      <div class="flex-grow-1 min-w-0">
        <p class="mb-1 fw-semibold small lh-sm" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
          ${item.nombre}
        </p>
        <span class="text-primary fw-bold">${fmtPrice(item.precio)}</span>
      </div>
      <div class="d-flex flex-column align-items-center gap-1 flex-shrink-0">
        <div class="d-flex align-items-center border rounded-2 overflow-hidden">
          <button class="btn btn-sm px-2 py-0 border-0 oc-qty-btn" data-id="${item.id}" data-op="dec"
                  style="background:#f1f3f5;font-size:1rem;line-height:1.8">−</button>
          <span class="px-2 fw-bold small" id="ocqty-${item.id}">${item.cantidad}</span>
          <button class="btn btn-sm px-2 py-0 border-0 oc-qty-btn" data-id="${item.id}" data-op="inc"
                  style="background:#f1f3f5;font-size:1rem;line-height:1.8">+</button>
        </div>
        <button class="btn btn-link btn-sm text-danger p-0 oc-remove-btn" data-id="${item.id}">
          <i class="bi bi-trash3 small"></i> quitar
        </button>
      </div>
    </div>`;
  }).join('');

  body.innerHTML = rows;
  footer.classList.remove('d-none');
  document.getElementById('ocCartTotal').textContent = fmtPrice(data.total ?? 0);
  updateCartBadge(data.count ?? 0);
}

function loadCartOffcanvas() {
  const body = document.getElementById('ocCartBody');
  body.innerHTML = `
    <div class="text-center py-5 text-muted">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-3 small">Cargando tu carrito...</p>
    </div>`;
  document.getElementById('ocCartFooter')?.classList.add('d-none');

  fetch(`${BASE_URL}/api/?endpoint=carrito`, { credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => renderCartOffcanvas(res.data ?? {}))
    .catch(() => {
      body.innerHTML = '<p class="text-danger text-center py-4">Error al cargar el carrito.</p>';
    });
}

document.addEventListener('show.bs.offcanvas', e => {
  if (e.target.id === 'cartOffcanvas') loadCartOffcanvas();
});

/* Cantidad en offcanvas */
document.addEventListener('click', e => {
  const btn = e.target.closest('.oc-qty-btn');
  if (!btn) return;
  const id = btn.dataset.id;
  const op = btn.dataset.op;

  fetch(`${BASE_URL}/index.php?r=cart/update&ajax=1`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&op=${op}`,
    credentials: 'same-origin',
  })
    .then(r => r.json())
    .then(data => {
      updateCartBadge(data.count);
      const row = document.getElementById(`ocrow-${id}`);
      if (!row) return;
      if (op === 'dec') {
        const qtyEl = document.getElementById(`ocqty-${id}`);
        const cur = parseInt(qtyEl?.textContent || '1');
        if (cur <= 1) { loadCartOffcanvas(); return; }
        if (qtyEl) qtyEl.textContent = cur - 1;
      } else {
        const qtyEl = document.getElementById(`ocqty-${id}`);
        if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent) + 1;
      }
      // recalc total visible
      fetch(`${BASE_URL}/api/?endpoint=carrito`, { credentials: 'same-origin' })
        .then(r => r.json())
        .then(res => {
          const total = res.data?.total ?? 0;
          const el = document.getElementById('ocCartTotal');
          if (el) el.textContent = fmtPrice(total);
        });
    });
});

/* Quitar en offcanvas */
document.addEventListener('click', e => {
  const btn = e.target.closest('.oc-remove-btn');
  if (!btn) return;
  const id = btn.dataset.id;

  fetch(`${BASE_URL}/index.php?r=cart/remove&ajax=1`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`,
    credentials: 'same-origin',
  })
    .then(r => r.json())
    .then(data => {
      updateCartBadge(data.count);
      loadCartOffcanvas();
    });
});

/* ══════════════════════════════════════════════════
   AGREGAR AL CARRITO (botón en tarjetas / detalle)
══════════════════════════════════════════════════ */
document.addEventListener('click', e => {
  const btn = e.target.closest('.btn-add-cart');
  if (!btn || btn.disabled) return;

  const id   = btn.dataset.id;
  const name = btn.dataset.name;
  const orig = btn.innerHTML;

  btn.disabled  = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

  fetch(`${BASE_URL}/index.php?r=cart/add&ajax=1`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`,
    credentials: 'same-origin',
  })
    .then(r => r.json())
    .then(data => {
      if (data.ok) {
        updateCartBadge(data.count);
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Agregado';
        btn.classList.replace('btn-primary', 'btn-success');
        showToast(`"${name}" agregado al carrito`);
        // Si el offcanvas está abierto, refrescarlo
        const oc = document.getElementById('cartOffcanvas');
        if (oc?.classList.contains('show')) loadCartOffcanvas();
        setTimeout(() => {
          btn.innerHTML = orig;
          btn.classList.replace('btn-success', 'btn-primary');
          btn.disabled = false;
        }, 1500);
      } else {
        showToast(data.msg || 'Error al agregar', 'danger');
        btn.innerHTML = orig;
        btn.disabled  = false;
      }
    })
    .catch(() => {
      showToast('Error de red', 'danger');
      btn.innerHTML = orig;
      btn.disabled  = false;
    });
});

/* ══════════════════════════════════════════════════
   CARRITO PÁGINA COMPLETA — cantidad / quitar / vaciar
══════════════════════════════════════════════════ */
document.addEventListener('click', e => {
  const btn = e.target.closest('.btn-qty');
  if (!btn) return;

  const id = btn.dataset.id;
  const op = btn.dataset.op;

  fetch(`${BASE_URL}/index.php?r=cart/update&ajax=1`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&op=${op}`,
    credentials: 'same-origin',
  })
    .then(r => r.json())
    .then(data => {
      updateCartBadge(data.count);
      const row   = document.getElementById(`row-${id}`);
      const qtyEl = document.getElementById(`qty-${id}`);
      const subEl = document.getElementById(`sub-${id}`);
      if (!row) return;
      if (op === 'dec' && parseInt(qtyEl?.textContent || '1') <= 1) {
        row.remove(); recalcCartTotal(); return;
      }
      if (qtyEl) qtyEl.textContent = parseInt(qtyEl.textContent) + (op === 'inc' ? 1 : -1);
      if (data.subtotal !== undefined && subEl) subEl.textContent = fmtPrice(data.subtotal);
      recalcCartTotal();
    });
});

document.addEventListener('click', e => {
  const btn = e.target.closest('.btn-remove');
  if (!btn) return;
  const id  = btn.dataset.id;
  const row = document.getElementById(`row-${id}`);

  fetch(`${BASE_URL}/index.php?r=cart/remove&ajax=1`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`,
    credentials: 'same-origin',
  })
    .then(r => r.json())
    .then(data => {
      row?.remove();
      updateCartBadge(data.count);
      recalcCartTotal();
      if (data.count === 0) location.reload();
    });
});

const clearBtn = document.getElementById('btnClearCart');
if (clearBtn) {
  clearBtn.addEventListener('click', () => {
    if (!confirm('¿Vaciar todo el carrito?')) return;
    fetch(`${BASE_URL}/index.php?r=cart/clear&ajax=1`, {
      method: 'POST',
      credentials: 'same-origin',
    }).then(() => location.reload());
  });
}

function recalcCartTotal() {
  let total = 0;
  document.querySelectorAll('#cartTable tbody tr').forEach(row => {
    const sub = row.querySelector('[id^="sub-"]');
    if (sub) total += parseFloat(sub.textContent.replace('Bs. ', '').replace(',', '')) || 0;
  });
  const fmt = fmtPrice(total);
  const el1 = document.getElementById('cartTotal');
  const el2 = document.getElementById('cartTotalFinal');
  if (el1) el1.textContent = fmt;
  if (el2) el2.textContent = fmt;
}

/* ══════════════════════════════════════════════════
   MODAL VISTA RÁPIDA DE PRODUCTO
══════════════════════════════════════════════════ */
document.addEventListener('click', e => {
  const btn = e.target.closest('.btn-quickview');
  if (!btn) return;

  const id         = btn.dataset.id;
  const modalBody  = document.getElementById('productModalBody');
  if (!modalBody) return;

  modalBody.innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-2 text-muted small">Cargando producto...</p>
    </div>`;

  fetch(`${BASE_URL}/api/?endpoint=productos&id=${id}`, { credentials: 'same-origin' })
    .then(r => r.json())
    .then(res => {
      const p = res.data;
      if (!p) { modalBody.innerHTML = '<p class="text-danger text-center py-4">Producto no encontrado.</p>'; return; }
      renderProductModal(p);
    })
    .catch(() => {
      modalBody.innerHTML = '<p class="text-danger text-center py-4">Error al cargar el producto.</p>';
    });
});

function renderProductModal(p) {
  const modalBody = document.getElementById('productModalBody');
  const img       = p.imagen
    ? `${BASE_URL}/resources/imagenes/${p.imagen}`
    : `${BASE_URL}/public/img/no-image.svg`;

  const precio      = parseFloat(p.precio);
  const oferta      = p.precio_oferta ? parseFloat(p.precio_oferta) : null;
  const precioFinal = oferta ?? precio;
  const descuento   = oferta ? Math.round((1 - oferta / precio) * 100) : 0;

  const priceBlock = oferta
    ? `<div class="pm-price-wrap">
         <span class="pm-price-original">${fmtPrice(precio)}</span>
         <span class="pm-price-final">${fmtPrice(oferta)}</span>
         <span class="pm-discount-badge">-${descuento}%</span>
       </div>`
    : `<div class="pm-price-wrap"><span class="pm-price-final">${fmtPrice(precio)}</span></div>`;

  const stockHtml = p.stock > 0
    ? `<div class="pm-stock pm-stock-ok"><i class="bi bi-check-circle-fill me-2"></i>En stock <span class="pm-stock-qty">(${p.stock} unidades)</span></div>`
    : `<div class="pm-stock pm-stock-out"><i class="bi bi-x-circle-fill me-2"></i>Sin stock</div>`;

  const desc = p.descripcion
    ? `<div class="pm-description"><p class="pm-desc-text">${p.descripcion}</p></div>`
    : '';

  const catBadge  = p.categoria_nombre ? `<span class="pm-badge pm-badge-cat"><i class="bi bi-tag me-1"></i>${p.categoria_nombre}</span>` : '';
  const marcaBadge = p.marca_nombre    ? `<span class="pm-badge pm-badge-brand"><i class="bi bi-award me-1"></i>${p.marca_nombre}</span>` : '';

  modalBody.innerHTML = `
    <div class="pm-layout">
      <!-- Panel imagen -->
      <div class="pm-img-panel">
        <div class="pm-img-wrap">
          <img src="${img}" class="pm-img"
               onerror="this.src='${BASE_URL}/public/img/no-image.svg'"
               alt="${p.nombre}">
        </div>
        ${descuento > 0 ? `<div class="pm-img-badge">-${descuento}%</div>` : ''}
      </div>
      <!-- Panel info -->
      <div class="pm-info-panel">
        <div class="pm-badges-row">${catBadge}${marcaBadge}</div>
        <h3 class="pm-product-name">${p.nombre}</h3>
        <div class="pm-divider"></div>
        ${priceBlock}
        ${stockHtml}
        ${desc}
        <div class="pm-actions">
          <button class="btn pm-btn-cart btn-add-cart"
                  data-id="${p.id}"
                  data-name="${p.nombre.replace(/"/g,'&quot;')}"
                  ${p.stock <= 0 ? 'disabled' : ''}>
            <i class="bi bi-cart-plus me-2"></i>
            ${p.stock <= 0 ? 'Sin stock' : 'Agregar al carrito'}
          </button>
          <a href="${BASE_URL}/index.php?r=product/detail&id=${p.id}"
             class="btn pm-btn-detail" title="Ver detalle completo">
            <i class="bi bi-box-arrow-up-right me-1"></i>Ver más
          </a>
        </div>
        <div class="pm-trust-row">
          <span><i class="bi bi-shield-check text-success me-1"></i>Compra segura</span>
          <span><i class="bi bi-truck text-primary me-1"></i>Envío disponible</span>
          <span><i class="bi bi-arrow-return-left text-warning me-1"></i>Devolución fácil</span>
        </div>
      </div>
    </div>`;
}

/* ══════════════════════════════════════════════════
   BÚSQUEDA CON AUTOCOMPLETAR
══════════════════════════════════════════════════ */
const searchInput = document.getElementById('searchInput');
if (searchInput) {
  // Crear lista de sugerencias
  const wrapper = searchInput.closest('.input-group');
  const suggest = document.createElement('ul');
  suggest.id        = 'searchSuggestions';
  suggest.className = 'list-group position-absolute shadow-sm z-3';
  suggest.style.cssText = 'top:100%;left:0;right:0;display:none;max-height:220px;overflow-y:auto;';
  wrapper.style.position = 'relative';
  wrapper.appendChild(suggest);

  let timer;
  searchInput.addEventListener('input', function () {
    clearTimeout(timer);
    const q = this.value.trim();
    if (q.length < 2) { suggest.style.display = 'none'; return; }
    timer = setTimeout(() => {
      fetch(`${BASE_URL}/api/?endpoint=productos&q=${encodeURIComponent(q)}`, { credentials: 'same-origin' })
        .then(r => r.json())
        .then(res => {
          suggest.innerHTML = '';
          const list = res.data ?? [];
          if (!list.length) { suggest.style.display = 'none'; return; }
          list.slice(0, 6).forEach(p => {
            const li = document.createElement('li');
            li.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2 py-2 small';
            li.style.cursor = 'pointer';
            const img = p.imagen
              ? `${BASE_URL}/resources/imagenes/${p.imagen}`
              : `${BASE_URL}/public/img/no-image.svg`;
            li.innerHTML = `
              <img src="${img}" width="36" height="36"
                   style="object-fit:contain;border-radius:4px;background:#f8f9fa"
                   onerror="this.src='${BASE_URL}/public/img/no-image.svg'">
              <span class="flex-grow-1">${p.nombre}</span>
              <strong class="text-primary">${fmtPrice(p.precio_oferta || p.precio)}</strong>`;
            li.addEventListener('click', () => {
              searchInput.value = p.nombre;
              suggest.style.display = 'none';
              searchInput.form.submit();
            });
            suggest.appendChild(li);
          });
          suggest.style.display = 'block';
        });
    }, 280);
  });

  document.addEventListener('click', ev => {
    if (!searchInput.contains(ev.target)) suggest.style.display = 'none';
  });
}
