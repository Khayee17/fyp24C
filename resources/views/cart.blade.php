<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <!-- Add the CSRF token meta tag here -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Include other necessary styles and scripts -->
</head>
<div class="my-cart-modal" id="myCartModal">

    <div class="my-cart-content">

        <div class="my-cart-header">
        <button class="my-cart-back-btn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <h2>My Cart</h2>
        </div>

        <div class="restaurant-name">The Toast 土司坊</div>

        <div class="order-info">
        <span><i class="far fa-clock"></i> Pre-Order</span>
        <span><i class="fas fa-user"></i> <span id="customerCount"> </span> pax</span>
        <i class="fas fa-chevron-right"></i>
        </div>

        <div class="my-cart-items">
            
        </div>

        <button class="my-cart-back-btn" id="add-more-btn" >Add More</button>
        
        <div class="my-cart-summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>RM 0.00</span>
            </div>

            <div class="summary-row">
                <span>SST (6%)</span>
                <span>RM 0.00</span>
            </div>

            <div class="summary-row">
                <span>Rounding</span>
                <span>RM 0.00</span>
            </div>

            <div class="summary-row my-cart-total">
                <span>Total <small>(incl. fees and tax)</small></span>
                <span>RM 0.00</span>
            </div>

            </div>

    </div>

    <div class="custom-modal" id="confirmOrderModal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-body">
                <h5 class="custom-modal-title">确认订单</h5>
                <p>Are you sure you want to confirm the order?</p>
                <button type="button" class="custom-btn" id="confirmYes">Yes</button>
                <button type="button" class="custom-btn" id="confirmNo">No</button>
            </div>
        </div>
    </div>

    <button class="my-cart-confirm-btn" id="confirmOrderBtn">Confirm Order</button>
</div>

<script>
     //add to cart
     $(document).ready(function() {
        const numberOfCustomers = localStorage.getItem('numberOfCustomers');
        if (numberOfCustomers) {
            $('#customerCount').text(numberOfCustomers);
        }

        let cart = JSON.parse(localStorage.getItem('cart')) || {
          items: {},
          totalQuantity: 0,
          totalPrice: 0.00
        };

        // 更新购物车显示
        function updateCartDisplay() { 
          cart.totalPrice = cart.totalPrice || 0;
          $('.cart-count').text(cart.totalQuantity); 
          $('.cart-total').text('RM ' + cart.totalPrice.toFixed(2));

        }

        // 更新产品数量显示，包括变体
        function updateProductQuantityDisplay(productId) {
          const button = $(`.add-btn[data-product-id="${productId}"]`);
          let totalQuantity = 0;

          for (const fullProductId in cart.items) {
            if (fullProductId.startsWith(productId)) {
                totalQuantity += cart.items[fullProductId].quantity;
            }
          }

          button.each(function() { 
              if (totalQuantity > 0) { 
                  $(this).text(totalQuantity).addClass('quantity'); // 显示数量并添加类 
              } else { 
                  $(this).text('+').removeClass('quantity'); // 显示加号并移除类 
              }
          });
        }



        // 计算总价，包括变体
        function calculateTotalPrice(basePrice, quantity) {
            let totalPrice = basePrice;
            $('#modal-variant-options .form-check-input:checked').each(function() {
                totalPrice += parseFloat($(this).data('price'));
            });
            return totalPrice * quantity;
        }

        // 更新购物车模态框内容
        function updateCartModal() {
            const cartItemsContainer = $('.my-cart-items');
            cartItemsContainer.empty(); // 清空旧内容

            for (const productId in cart.items) {
                const item = cart.items[productId];
                const variantText = item.variants && item.variants.length > 0 
                    ? item.variants.join(', ') // 用逗号分隔多个变体
                    : 'No variant selected'; // 如果没有变体，显示 'No variant selected'

                const cartItemHTML = `
                    <div class="cart-item">
                                  <img src="/storage/${item.product_img}" alt="${item.name}">

                        <div class="item-details">
                            <h5>${item.name}</h5>
                            <p class="item-remark">${variantText}</p> 
                            <div class="item-price">RM ${(item.unitPrice + item.variantPrice).toFixed(2)}</div>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-btn minus" data-product-id="${productId}">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-btn plus" data-product-id="${productId}">+</button>
                        </div>
                    </div>
                `;
                cartItemsContainer.append(cartItemHTML);
            }

            updateCartSummary();
        }
        // 更新购物车总计
        function updateCartSummary() {
          const subtotal = cart.totalPrice;
          const sst = subtotal * 0.06; // 6%的SST
          const totalBeforeRounding = subtotal + sst;

          // 根据马来西亚的规则进行四舍五入
          const roundedTotal = Math.round(totalBeforeRounding * 20) / 20; //将金额乘以 20 后四舍五入，再除以 20，以实现到最近 5 分的四舍五入。
          const rounding = roundedTotal - totalBeforeRounding; //计算四舍五入的差值

          $('.my-cart-summary .summary-row:nth-child(1) span:nth-child(2)').text(`RM ${subtotal.toFixed(2)}`);
          $('.my-cart-summary .summary-row:nth-child(2) span:nth-child(2)').text(`RM ${sst.toFixed(2)}`);
          $('.my-cart-summary .summary-row:nth-child(3) span:nth-child(2)').text(`RM ${rounding.toFixed(2)}`);
          $('.my-cart-summary .my-cart-total span:nth-child(2)').text(`RM ${roundedTotal.toFixed(2)}`);
        }

        updateCartDisplay();
        updateCartModal();

        for (const fullProductId in cart.items) {
          const [productId, variantName] = fullProductId.split('-');
          if (variantName) {
              updateProductQuantityDisplay(productId, variantName); // 针对变体更新
          }
        }
        // 添加到购物车事件
        $('.add-cart-btn').click(function() {
            const productImg = $('#modal-product-image').attr('src').replace('/storage/', '');
            const productId = $('#modal-product-title').data('product-id');
            const productName = $('#modal-product-title').text();
            const basePrice = parseFloat($('#modal-product-price').text().replace('RM ', ''));
            const quantity = parseInt($('#quantity').text());
            const remark = $('#product-remark-input').val(); // 获取备注

            let variantPrice = 0;
            let selectedVariants = [];

            // 获取选中的变体（如 hot 或 ice）
            $('#modal-variant-options .form-check-input:checked').each(function() {
                selectedVariants.push($(this).data('name')); 
                variantPrice += parseFloat($(this).data('price')); 
            });

            const totalPrice = (basePrice + variantPrice) * quantity;

            if (selectedVariants.length === 0) {
                selectedVariants.push('No variant selected'); 
            }

            const variantKey = `${productId}-${selectedVariants.join('-')}`;

            // 遍历每个选中的变体，分别作为一个单独的购物车项目
            selectedVariants.forEach(function(variant) {
                const variantName = `${productName} (${variant})`; // 在名称中加上变体名称（例如：Ice Lemon Tea (Ice)）

                if (!cart.items[variantName]) {
                    cart.items[variantName] = { 
                        product_img: productImg, 
                        name: variantName, 
                        quantity: 0, 
                        totalPrice: 0, 
                        unitPrice: basePrice, 
                        variantPrice: 0, 
                        variants: selectedVariants, // 存储变体名称
                        remark: remark 
                    };
                }

                cart.items[variantName].quantity += quantity;
                cart.items[variantName].totalPrice += (basePrice + variantPrice) * quantity;
                cart.items[variantName].variantPrice = variantPrice;

                cart.totalQuantity = Object.values(cart.items).reduce((sum, item) => sum + item.quantity, 0);
                cart.totalPrice = Object.values(cart.items).reduce((sum, item) => sum + item.totalPrice, 0);
            });

            updateCartDisplay(); 
            updateProductQuantityDisplay(productId); // 更新产品数量显示 
            updateCartModal();

            localStorage.setItem('cart', JSON.stringify(cart));

            $('#foodModal').fadeOut(200).removeClass('show');
        });


        // 处理数量增减
        $(document).on('click', '.quantity-btn.minus', function() {
            const productId = $(this).data('product-id');
            if (cart.items[productId]) {
                cart.items[productId].quantity -= 1;
                cart.items[productId].totalPrice -= cart.items[productId].unitPrice;
                if (cart.items[productId].quantity <= 0) {
                    delete cart.items[productId];
                }
                cart.totalQuantity = Object.values(cart.items).reduce((sum, item) => sum + item.quantity, 0);
                cart.totalPrice = Object.values(cart.items).reduce((sum, item) => sum + item.totalPrice, 0);
                updateCartDisplay();
                updateProductQuantityDisplay(productId);
                updateCartModal();

                localStorage.setItem('cart', JSON.stringify(cart));

            }
        });

        $(document).on('click', '.quantity-btn.plus', function() {
            const productId = $(this).data('product-id');
            if (cart.items[productId]) {
                cart.items[productId].quantity += 1;
                cart.items[productId].totalPrice += cart.items[productId].unitPrice + cart.items[productId].variantPrice;
                cart.totalQuantity = Object.values(cart.items).reduce((sum, item) => sum + item.quantity, 0);
                cart.totalPrice = Object.values(cart.items).reduce((sum, item) => sum + item.totalPrice, 0);
                updateCartDisplay();
                updateProductQuantityDisplay(productId);
                updateCartModal();
            }
        });

        // 显示购物车模态框
        $('.view-cart-btn').click(function() {
            updateCartModal();
            $('#myCartModal').fadeIn(200).addClass('show');
        });

        $('.my-cart-back-btn').click(function() {
            $('#myCartModal').fadeOut(200).removeClass('show');
        });

        // 打开模态框时设置产品ID
        $('.add-btn').each(function () {
          const productId = $(this).data('product-id'); // 获取按钮的 product_id
          updateProductQuantityDisplay(productId);
        });

    });

    $('#confirmOrderBtn').click(function() {
        const cart = JSON.parse(localStorage.getItem('cart'));

        if (!cart || !cart.items || Object.keys(cart.items).length === 0) {
            alert('Your cart is empty!');
            return;
        }
            
        // Retrieve user info from localStorage
        const phone = localStorage.getItem('phone');
        const numberOfCustomers = localStorage.getItem('numberOfCustomers');

        // 检查数据是否存在
        if (!phone || !numberOfCustomers || isNaN(numberOfCustomers) || numberOfCustomers <= 0) {
            alert('User information is missing or invalid. Please log in again.');
            // 可以重定向用户到登录页面
            window.location.href = '/userlogin';
            return;
        }

        const subtotal = Object.values(cart.items).reduce((acc, item) => {
            return acc + ((item.unitPrice || 0) + (item.variantPrice || 0)) * item.quantity;
        }, 0);
        const sst = subtotal * 0.06;
        const totalBeforeRounding = subtotal + sst;
        const rounding = Math.round(totalBeforeRounding * 20) / 20 - totalBeforeRounding;
        const total = Math.round(totalBeforeRounding * 20) / 20;

        const orderData = {
            phone: phone,
            numberOfCustomers: numberOfCustomers,
            items: Object.values(cart.items).map(item => ({
                name: item.name,
                quantity: item.quantity,
                price: (item.unitPrice || 0) + (item.variantPrice || 0),
                remark: item.remark || '',
                variant_text: item.variants ? item.variants.join(', ') : null
            })),
            subtotal: parseFloat(subtotal.toFixed(2)),
            sst: parseFloat(sst.toFixed(2)),
            rounding: parseFloat(rounding.toFixed(2)),
            total: parseFloat(total.toFixed(2)),
            status: 'pending'
        };

        $.ajax({
            url: '/submit-order',
            method: 'POST',
            data: JSON.stringify(orderData),
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                alert('Order saved successfully! Order ID: ' + response.order_id);
                localStorage.removeItem('cart');
            },
            error: function(error) {
                alert('Failed to save order. Please try again.');
                console.error('Error:', error.responseJSON);
            }
        });

        // Render the order items and summary
        const orderItemsContainer = $('.my-order-items');
        orderItemsContainer.empty();

        for (const productId in cart.items) {
            const item = cart.items[productId];
            const variantText = item.variants && item.variants.length > 0 
                ? item.variants.join(', ') 
                : 'No variant selected';

            const orderItemHTML = `
                <div class="order-item">
                    <img src="/storage/${item.product_img}" alt="${item.name}" class="product-image">
                    <div class="item-details">
                        <p>${item.name}</p>
                        <p class="item-note">${variantText}</p>
                        <p class="item-price">
                            RM ${(item.unitPrice || 0) + (item.variantPrice || 0)} 
                            <span class="item-quantity">x ${item.quantity}</span>
                        </p>
                    </div>
                </div>
            `;
            orderItemsContainer.append(orderItemHTML);
        }

        // Update order summary details
        $('.order-summary .summary-item:nth-child(1) span:nth-child(2)').text(`RM ${subtotal.toFixed(2)}`);
        $('.order-summary .summary-item:nth-child(2) span:nth-child(2)').text(`RM ${sst.toFixed(2)}`);
        $('.order-summary .summary-item:nth-child(3) span:nth-child(2)').text(`RM ${rounding.toFixed(2)}`);
        $('.order-summary .order-list-total span:nth-child(2)').text(`RM ${total.toFixed(2)}`);

        // Show order details and hide the cart modal
        $('#orderDetails').fadeIn(200).addClass('show');
        $('#myCartModal').fadeOut(200).removeClass('show');
    });



// new code
// new code
// new code
// new code
// new code
// new code
// new code
// new code
// new code







</script>



