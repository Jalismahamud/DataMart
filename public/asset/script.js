let basePrice = 2550;
let currentQty = 1;
let currentDelivery = 100;

function toBengaliNum(str) {
    const bengaliDigits = {'0':'০','1':'১','2':'২','3':'৩','4':'৪','5':'৫','6':'৬','7':'৭','8':'৮','9':'৯'};
    return str.toString().replace(/\d/g, x => bengaliDigits[x]);
}

function updateSummary() {
    const productTotal = basePrice * currentQty;
    const grandTotal = productTotal + currentDelivery;

    // Update quantity section total
    const qtyTotalElem = document.getElementById('qtyTotal');
    if(qtyTotalElem) {
        qtyTotalElem.textContent = '৳' + toBengaliNum(productTotal.toLocaleString('en-IN'));
    }

    // Update order summary qty text
    const summaryQtyElem = document.getElementById('summaryQty');
    if(summaryQtyElem) {
        summaryQtyElem.textContent = toBengaliNum(currentQty);
    }

    // Update checkout summary
    const prodTotalElem = document.getElementById('productTotal');
    if(prodTotalElem) {
        prodTotalElem.textContent = toBengaliNum(productTotal.toLocaleString('en-IN'));
    }
    const devChargeElem = document.getElementById('deliveryCharge');
    if(devChargeElem) {
        devChargeElem.textContent = toBengaliNum(currentDelivery);
    }
    const grandTotalElem = document.getElementById('grandTotal');
    if(grandTotalElem) {
        grandTotalElem.textContent = toBengaliNum(grandTotal.toLocaleString('en-IN'));
    }
}

function increaseQty() {
    currentQty++;
    document.getElementById('qty').value = toBengaliNum(currentQty);
    updateSummary();
}

function decreaseQty() {
    if (currentQty > 1) {
        currentQty--;
        document.getElementById('qty').value = toBengaliNum(currentQty);
        updateSummary();
    }
}

function syncSelectedValues() {
    const productRadio = document.querySelector('input[name="product"]:checked');
    const productIdInput = document.getElementById('product_id');
    if (productIdInput && productRadio) {
        productIdInput.value = productRadio.value;
    }

    const sizeRadio = document.querySelector('input[name="size"]:checked');
    const sizeInput = document.getElementById('selected_size');
    if (sizeInput && sizeRadio) {
        sizeInput.value = sizeRadio.value;
    }

    const colorRadio = document.querySelector('input[name="color"]:checked');
    const colorInput = document.getElementById('selected_color');
    if (colorInput && colorRadio) {
        colorInput.value = colorRadio.value;
    }

    const qtyInput = document.getElementById('quantity');
    if (qtyInput) {
        qtyInput.value = currentQty;
    }

    const cityInput = document.getElementById('city');
    const cityRadio = document.querySelector('input[name="delivery_area"]:checked');
    if (cityInput && cityRadio) {
        cityInput.value = cityRadio.value;
    }
}

function resolveVariationId() {
    const productInput = document.getElementById('product_id');
    const productId = productInput ? productInput.value : null;
    const size = document.querySelector('input[name="size"]:checked')?.value;
    const color = document.querySelector('input[name="color"]:checked')?.value;

    if (!productId || !size || !color) {
        return Promise.resolve();
    }

    const variationInput = document.getElementById('variation_id');
    if (!variationInput) return Promise.resolve();

    return fetch(`/api/product/${productId}/variation-id?size=${encodeURIComponent(size)}&color=${encodeURIComponent(color)}`)
        .then((response) => response.json())
        .then((data) => {
            if (data.variation_id) {
                variationInput.value = data.variation_id;
            }
            return data;
        })
        .catch(() => {
            variationInput.value = '';
            return null;
        });
}

function selectProduct(element, price) {
    const cards = element.parentElement.querySelectorAll('.product-card');
    cards.forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.radio-indicator');
        if(indicator) {
            indicator.classList.add('empty');
            indicator.innerHTML = '';
        }
    });

    element.classList.add('selected');
    const indicator = element.querySelector('.radio-indicator');
    if(indicator) {
        indicator.classList.remove('empty');
        indicator.innerHTML = '<i class="fa-solid fa-check"></i>';
    }

    const radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    basePrice = price;
    const prodName = element.querySelector('h5');
    const summaryProdName = document.getElementById('summaryProductName');
    if(prodName && summaryProdName) {
        summaryProdName.textContent = prodName.textContent;
    }

    syncSelectedValues();
    resolveVariationId();
    updateSummary();
}

function selectSize(element) {
    const boxes = element.parentElement.querySelectorAll('.size-box');
    boxes.forEach(box => box.classList.remove('active'));
    element.classList.add('active');

    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }

    syncSelectedValues();
    resolveVariationId();
}

function selectColor(element) {
    const boxes = element.parentElement.querySelectorAll('.size-box');
    boxes.forEach(box => box.classList.remove('active'));
    element.classList.add('active');

    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }

    syncSelectedValues();
    resolveVariationId();
}

function selectDelivery(element, price, text, cityValue) {
    const cards = document.querySelectorAll('.delivery-new-card');
    cards.forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.radio-indicator');
        if(indicator) {
            indicator.classList.add('empty');
            indicator.innerHTML = '';
        }
    });

    element.classList.add('selected');
    const indicator = element.querySelector('.radio-indicator');
    if(indicator) {
        indicator.classList.remove('empty');
        indicator.innerHTML = '<i class="fa-solid fa-check"></i>';
    }

    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }

    currentDelivery = price;
    const summaryText = document.getElementById('summaryDeliveryText');
    if(summaryText && text) {
        summaryText.textContent = text;
    }

    const cityInput = document.getElementById('city');
    if (cityInput && cityValue) {
        cityInput.value = cityValue;
    }

    updateSummary();
}

document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    syncSelectedValues();
    const form = e.currentTarget;
    const submitButton = form.querySelector('button[type="submit"]');

    if (submitButton) {
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="me-2">⏳</span> অর্ডার প্রক্রিয়াধীন...';
    }

    try {
        await resolveVariationId();

        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value
            },
            body: formData
        });

        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(data.message || 'Order failed. Please try again.');
        }

        const modal = new bootstrap.Modal(document.getElementById('orderSuccessModal'));
        const invoiceEl = document.getElementById('successInvoiceNumber');
        if (invoiceEl) {
            invoiceEl.textContent = data.invoice_number || '—';
        }
        modal.show();
        form.reset();
        document.getElementById('qty').value = '১';
        currentQty = 1;
        updateSummary();
    } catch (error) {
        alert(error.message || 'Something went wrong while placing your order.');
    } finally {
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa-solid fa-cart-shopping"></i> অর্ডার কনফার্ম করুন';
        }
    }
});
// Enable swipe and mouse drag support for all carousels
document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.carousel');
    carousels.forEach(carousel => {
        // Initialize bootstrap carousel if not already initialized
        let bsCarousel = bootstrap.Carousel.getInstance(carousel);
        if (!bsCarousel) {
            bsCarousel = new bootstrap.Carousel(carousel);
        }
        
        // Prevent default image drag to allow our custom mouse drag
        carousel.querySelectorAll('img').forEach(img => {
            img.addEventListener('dragstart', (e) => e.preventDefault());
        });
        
        let startX = 0;
        let endX = 0;
        let isDragging = false;

        // Touch events (Mobile)
        carousel.addEventListener('touchstart', function(e) {
            startX = e.changedTouches[0].screenX;
        }, {passive: true});

        carousel.addEventListener('touchend', function(e) {
            endX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        // Mouse events (Desktop)
        carousel.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.screenX;
            carousel.style.cursor = 'grabbing';
        });
        
        carousel.addEventListener('mouseup', function(e) {
            if(!isDragging) return;
            isDragging = false;
            endX = e.screenX;
            carousel.style.cursor = 'default';
            handleSwipe();
        });
        
        carousel.addEventListener('mouseleave', function() {
            if(isDragging) {
                isDragging = false;
                carousel.style.cursor = 'default';
            }
        });

        function handleSwipe() {
            const threshold = 50; // minimum distance to be considered a swipe
            if (startX - endX > threshold) {
                bsCarousel.next(); // Swiped left -> Next item
            } else if (endX - startX > threshold) {
                bsCarousel.prev(); // Swiped right -> Previous item
            }
        }
    });
});
