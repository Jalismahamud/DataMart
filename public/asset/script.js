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

function selectProduct(element, price) {
    // Remove selected class and reset indicators from all sibling product cards
    const cards = element.parentElement.querySelectorAll('.product-card');
    cards.forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.radio-indicator');
        if(indicator) {
            indicator.classList.add('empty');
            indicator.innerHTML = '';
        }
    });
    
    // Add selected class to clicked
    element.classList.add('selected');
    const indicator = element.querySelector('.radio-indicator');
    if(indicator) {
        indicator.classList.remove('empty');
        indicator.innerHTML = '<i class="fa-solid fa-check"></i>';
    }
    
    // Check the radio input
    const radio = element.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;

    // Update base price
    basePrice = price;
    
    // Update summary product name
    const prodName = element.querySelector('h5');
    const summaryProdName = document.getElementById('summaryProductName');
    if(prodName && summaryProdName) {
        summaryProdName.textContent = prodName.textContent;
    }
    
    updateSummary();
}

function selectSize(element) {
    // Remove active class from all sibling size boxes
    const boxes = element.parentElement.querySelectorAll('.size-box');
    boxes.forEach(box => box.classList.remove('active'));
    
    // Add active class to clicked
    element.classList.add('active');
    
    // Check the radio input
    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }
}

function selectColor(element) {
    // Remove active class from all sibling color boxes
    const boxes = element.parentElement.querySelectorAll('.size-box');
    boxes.forEach(box => box.classList.remove('active'));
    
    // Add active class to clicked
    element.classList.add('active');
    
    // Check the radio input
    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }
}

function selectDelivery(element, price, text) {
    // Remove selected class from all
    const cards = document.querySelectorAll('.delivery-new-card');
    cards.forEach(card => {
        card.classList.remove('selected');
        const indicator = card.querySelector('.radio-indicator');
        if(indicator) {
            indicator.classList.add('empty');
            indicator.innerHTML = '';
        }
    });
    
    // Add selected class to clicked
    element.classList.add('selected');
    const indicator = element.querySelector('.radio-indicator');
    if(indicator) {
        indicator.classList.remove('empty');
        indicator.innerHTML = '<i class="fa-solid fa-check"></i>';
    }
    
    // Check the radio input
    const radio = element.querySelector('input[type="radio"]');
    if (radio) {
        radio.checked = true;
    }

    // Update price and text
    currentDelivery = price;
    const summaryText = document.getElementById('summaryDeliveryText');
    if(summaryText && text) {
        summaryText.textContent = text;
    }
    
    updateSummary();
}

// Handle form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Thank you! Your order has been placed successfully.');
    // Here you would typically send data to server
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
