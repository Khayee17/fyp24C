<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap@5.3.3 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-qD0eqxPqhdGv8F39C/U2yV8m8DzY0aYfMqa1T7rXj5c5I1PhPGAxA3uUb16uj8p8" crossorigin="anonymous">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- jQuery Library (necessary for the script to work) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>

    <!-- Optionally include jQuery Transit if you use it for transitions -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.transit/0.9.12/jquery.transit.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="/css/modal.css"> 

    <!-- 在其他 script 标签后面添加 -->
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order.css') }}">
  </head>

  <body>
    @include('cart')
    @include('order')

    <!-- sticker header -->
    <div class="sticky-header">
      <div class="estimated-time">
        <span class="highlight">Estimated waiting time: 15-20 minutes</span>
        <span class="highlight">3 groups ahead</span>
      </div>
      <div class="restaurant-name">
        <button class="back-btn">
          <i class="fas fa-chevron-left"></i>
        </button>
        The Toast 土司坊
      </div>
      <div class="sticky-search-container">
        <div class="sticky-search-box">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" placeholder="Search name or code" class="search-input" >
        </div>
      </div>
    </div>

    <!-- app container -->
    <div class="app-container">
      <!-- show logo n estimate -->
      <div class="header-content">
        <div class="header">
          <img src="/images/recLogo.png" alt="Dine Queue" class="logo">
          <hr class="divider">
          <div class="estimated-time">
            <span class="highlight">Estimated waiting time: 15-20 minutes</span> <span class="highlight">3 groups ahead</span>
          </div>
        </div>
      </div>

      <!-- banner -->
      <div class="restaurant-banner">
        <img src="/images/thetoast.jpeg" alt="Restaurant Banner" class="banner-img">
        <div class="banner-overlay">
          <div class="restaurant-info">
            <h1>The Toast 土司坊</h1>
            <button class="info-btn"><i class="fa-solid fa-circle-info"></i> More Info</button>
            <p class="opening-hours">Opening Hour · Today: 8AM - 8:30PM</p>
          </div>
        </div>
      </div>

      <!-- 搜索栏 -->
      <div class="search-container">
        <div class="search-box">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" placeholder="Search name or code" class="search-input" id="productSearchInput">
        </div>
      </div>
    </div> 
    <!-- app container -->

    <!-- menu container -->
    <div class="menu-container">
      <!-- 左侧类别导航栏 -->
      <nav class="categories-nav">
        @foreach($categories as $category)
          <div href="" class="category-item" data-category-id="{{ $category->id }}">
            <img src="{{ $category->ctgImg ? asset('storage/' . $category->ctgImg) : asset('assets/images/default.png') }}" alt="{{ $category->name }}">
            <span>{{ $category->name }}</span>
          </div>
        @endforeach
      </nav>

      <!-- 右侧菜品列表 -->
      <div class="menu-content">
        @if(isset($categories) && $categories->isNotEmpty())
          @foreach($categories as $category)
            <div class="category-section" id="category-{{ $category->id }}">
              <h2 class="category-title">{{ $category->name }}</h2>
              <div class="row ">
                @forelse($category->products as $product)
                  @if($product->in_stock)
                    <div class="col-6 col-md-6 col-lg-3 product-item" id="product-{{ $product->product_id }}"> 
                      <div class="food-card">
                        <div class="card-content">

                          <div class="image-container">
                            <img src="{{ $product->product_img ? asset('storage/'.$product->product_img) : '/images/default.png' }}" alt="{{ $product->product_name }}" class="food-image">
                          </div>

                          <div class="food-info">
                            <h3 class="food-name">
                              <span style="color: #ff8c00;font-weight: bold;">{{ $product->product_id }}</span> - {{ $product->product_name }}
                            </h3>
                            <div class="price-action">
                              <div class="price-text">
                                  <span class="currency">RM</span><span class="price">{{ $product->unit_price }}</span>
                              </div>
                              
                              <button class="add-btn" data-product-id="{{ $product->product_id }}" data-product="{{ json_encode($product) }}"><span>+</span></button>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  @endif
                @empty
                  <p>No products available for this category.</p>
                @endforelse
              </div>
            </div>
          @endforeach 
        @else
            <p>No categories found.</p>
        @endif
      </div> <!-- menu-content -->
      
      <div class="cart-button">
        <div id="view-cart-btn" style="display: flex; align-items: center;">
          View Cart
          <div class="cart-count">0</div>
        </div>
        <div class="cart-total">RM 0.00</div>
      </div> <!-- cart-button -->
    

    <!-- pop up modal -->
    <div class="food-modal" id="foodModal" style="display:none;">
      <div class="modal-content">
        <button class="close-modal">x</button>

        <div class="img-side">
          <div class="modal-image">
            <img id="modal-product-image" src="" alt="Food Image">
          </div>
        </div>

        <div class="content-side">
          <div class="food-details">
            <h2 class="food-title" id="modal-product-title"></h2> 
            <div class="food-price" id="modal-product-price"></div> 
            <p class="food-description" id="modal-product-description"></p>
          </div>

          <!-- variant -->
          <div class="variant-options" id="modal-variant-options">
            <!-- insert variant script here -->
          </div>

          <div class="remark-section">
            <h3>Remark (Optional)</h3>
            <textarea placeholder="Enter remark here"></textarea>
          </div>
        </div>

        <div class="quantity-action">
          <div class="quantity-controls">
            <button class="quantity-btn minus">-</button>
              <span class="quantity" id="quantity">1</span> 
            <button class="quantity-btn plus">+</button>
          </div>
          <button class="add-cart-btn">Add to cart - RM <span id="product-price"></span></button> 
        </div>
      </div>
    </div> 
    <!-- pop up modal -->

    <!-- search not found modal -->
    <div class="custom-modal" id="noMatchModal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-body">
                <h5 class="custom-modal-title">提示</h5>
                <p>No matching product found.</p>
                <button type="button" class="custom-btn" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
  </div>

    



    <script>
     var products = @json($products);
    </script>
   
    <script>
      // sticker header
      $(document).ready(function() {
        const stickyHeader = $('.sticky-header');
        const originalHeader = $('.header-content');
        const scrollThreshold = originalHeader.height();

        $(window).scroll(function() {
          if ($(window).scrollTop() > scrollThreshold) {
            stickyHeader.addClass('visible');
          } else {
            stickyHeader.removeClass('visible');
          }
        });
      });

      // search
      function showModal() {
          document.getElementById('noMatchModal').style.display = 'block';
      }

      function closeModal() {
          document.getElementById('noMatchModal').style.display = 'none';
      }

      function setupSearch(inputElement) {
          let debounceTimer;

          inputElement.addEventListener('input', function() {
              clearTimeout(debounceTimer);
              debounceTimer = setTimeout(() => {
                  const query = inputElement.value.toLowerCase().trim();
                  if (query) {
                      const productItems = document.querySelectorAll('.product-item');
                      let found = false;

                      productItems.forEach(item => {
                          const productName = item.querySelector('.food-name').textContent.toLowerCase();
                          const productId = item.id.toLowerCase();

                          if (productName.includes(query) || productId.includes(query)) {
                              item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                              found = true;
                              return; // 找到第一个匹配项后停止
                          }
                      });

                      if (!found) {
                          showModal();
                      }
                  }
              }, 1000); // 1000毫秒 = 1秒
          });
      }

      document.addEventListener('DOMContentLoaded', function() {
          const searchInputs = document.querySelectorAll('.search-input');
          searchInputs.forEach(setupSearch);
      });

      //左navbar去右menu
      document.querySelectorAll('.category-item').forEach(item => {
        item.addEventListener('click', function() {
          const categoryId = this.getAttribute('data-category-id');
          const targetSection = document.getElementById('category-' + categoryId);
          if (targetSection) {
            const offsetTop = targetSection.offsetTop;
            const headerOffset = 150; // 根据需要调整偏移量
            window.scrollTo({
              top: offsetTop - headerOffset,
              behavior: 'smooth'
            });
          }
        });
      });


      // pop up modal
      $(document).ready(function() {
        let quantity = 1;
        let basePrice = 0;

        // 打开模态框
        $('.add-btn, .product-item').click(function(e) {
          e.preventDefault();
            
            // 获取当前点击的菜品数据
            const product = $(this).data('product');
            basePrice = parseFloat(product.unit_price); // 获取菜品的基础价格

            // 填充模态框内容
            $('#foodModal #modal-product-title').text(product.product_name);
            $('#foodModal #modal-product-price').text('RM ' + product.unit_price);
            $('#foodModal #modal-product-description').text(product.product_des || 'No description available.');
            $('#modal-product-image').attr('src', '/storage/' + product.product_img);

            // 设置初始按钮价格
            updateTotalPrice();

            // 清空原来的变体选项
            $('#foodModal #modal-variant-options').empty();

            // 动态生成变体选项
            if (product.variants && product.variants.length > 0) {
              product.variants.forEach(function (variant) {
                  const label = variant.is_required ? '<span class="text-danger">【required】</span>' : '<span class="text-secondary">【optional】</span>';
                  const variantTitle = $(`<h5 class="variant-title"></h5>`).html(`${variant.variantName} ${label}`);
                  const variantContainer = $('<div class="variant-option mb-3"></div>').append(variantTitle);

                  const optionsContainer = $('<div class="variant-options-container"></div>');
                  const optionPrices = variant.variantPrice ? variant.variantPrice.split(',') : [];

                  variant.variantOpt.split(',').forEach(function (option, index) {
                      const optionPrice = parseFloat(optionPrices[index] || '0.00');
                      const inputType = variant.is_required ? 'radio' : 'checkbox';
                      const inputName = `variant-${variant.variantName.replace(/\s+/g, '-')}`;

                      const optionElement = $('<div class="form-check"></div>');
                      optionElement.append(
                          `<input class="form-check-input" type="${inputType}" name="${inputName}" value="${option}" 
                          data-price="${optionPrice}" data-name="${option}" ${variant.is_required ? 'required' : ''}>
                          <label class="form-check-label">${option} + RM ${optionPrice.toFixed(2)}</label>`
                      );

                      optionsContainer.append(optionElement);
                  });

                  variantContainer.append(optionsContainer);
                  $('#modal-variant-options').append(variantContainer);
              });
          }

            // 显示模态框
            $('#foodModal').fadeIn(200).addClass('show');
        });

        // 关闭模态框
        $('.close-modal').click(function() {
            $('#foodModal').fadeOut(200).removeClass('show');
        });

        // 点击外部关闭
        $(window).click(function(e) {
            if ($(e.target).is('.food-modal')) {
                $('#foodModal').fadeOut(200).removeClass('show');
            }
        });

        // 加减qty
        $('.quantity-btn.plus').click(function() {
            quantity++;
            updateTotalPrice();
        });

        $('.quantity-btn.minus').click(function() {
            if (quantity > 1) {
                quantity--;
                updateTotalPrice();
            }
        });

        // 监听变体选项变化
        $('#modal-variant-options').on('change', '.form-check-input', function() {
            updateTotalPrice();
        });

        function updateTotalPrice() {
            let totalPrice = basePrice;
            $('#modal-variant-options .form-check-input:checked').each(function() {
                totalPrice += parseFloat($(this).data('price'));
            });
            totalPrice *= quantity;
            $('.add-cart-btn').text(`Add to cart - RM ${totalPrice.toFixed(2)}`);
            $('#quantity').text(quantity);
        }
      });
      //pop up modal end

      //add to cart
      $(document).ready(function() {
        let cart = JSON.parse(localStorage.getItem('cart')) || {
          items: {},
          totalQuantity: 0,
          totalPrice: 0.00
        };

        // 更新购物车显示
        function updateCartDisplay() { 
          cart.totalPrice = cart.totalPrice || 0;
          $('.cart-count').text(cart.totalQuantity); $('.cart-total').text('RM ' + cart.totalPrice.toFixed(2));

        }

        // 更新产品数量显示，包括变体
        function updateProductQuantityDisplay(productId, variantName = null) {
          const fullProductId = variantName ? `${productId}-${variantName}` : productId;
          const quantity = cart.items[fullProductId] ? cart.items[fullProductId].quantity : 0;
          const button = $(`.add-btn[data-product-id="${fullProductId}"]`);

          button.each(function() { 
            if (quantity > 0) { 
              $(this).text(quantity).addClass('quantity'); // 显示数量并添加类 
              } else { 
                $(this).text('+').removeClass('quantity'); // 显示加号并移除类 }
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
                        <img src="/images/${productId}.png" alt="${item.name}">
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
            const rounding = Math.round((subtotal + sst) * 100) / 100 - (subtotal + sst);
            const total = subtotal + sst + rounding;

            $('.my-cart-summary .summary-row:nth-child(1) span:nth-child(2)').text(`RM ${subtotal.toFixed(2)}`);
            $('.my-cart-summary .summary-row:nth-child(2) span:nth-child(2)').text(`RM ${sst.toFixed(2)}`);
            $('.my-cart-summary .summary-row:nth-child(3) span:nth-child(2)').text(`RM ${rounding.toFixed(2)}`);
            $('.my-cart-summary .my-cart-total span:nth-child(2)').text(`RM ${total.toFixed(2)}`);
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
            const productId = $('#modal-product-title').data('product-id');
            const productName = $('#modal-product-title').text();
            const basePrice = parseFloat($('#modal-product-price').text().replace('RM ', ''));
            const quantity = parseInt($('#quantity').text());
            const remark = $('#product-remark-input').val(); // 获取备注

            let variantPrice = 0;
            let selectedVariants = [];

            // 获取选中的变体（如 hot 或 ice）
            $('#modal-variant-options .form-check-input:checked').each(function() {
                selectedVariants.push($(this).data('name')); // 获取变体的名称
                variantPrice += parseFloat($(this).data('price')); // 获取变体的价格
            });

            const totalPrice = (basePrice + variantPrice) * quantity;

            // 如果没有选中变体，则视为没有变体
            if (selectedVariants.length === 0) {
                selectedVariants.push('No variant selected'); // 标记为无变体
            }

            // 遍历每个选中的变体，分别作为一个单独的购物车项目
            selectedVariants.forEach(function(variant) {
                const variantName = `${productName} (${variant})`; // 在名称中加上变体名称（例如：Ice Lemon Tea (Ice)）

                if (!cart.items[variantName]) {
                    cart.items[variantName] = { 
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
                cart.items[variantName].totalPrice += totalPrice;
                cart.items[variantName].variantPrice = variantPrice;

                cart.totalQuantity = Object.values(cart.items).reduce((sum, item) => sum + item.quantity, 0);
                cart.totalPrice = Object.values(cart.items).reduce((sum, item) => sum + item.totalPrice, 0);
            });

            updateCartDisplay();
            selectedVariants.forEach(function(variant) { updateProductQuantityDisplay(productId, variant); 
            });
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

    


           

    </script>

  </body>
</html>
