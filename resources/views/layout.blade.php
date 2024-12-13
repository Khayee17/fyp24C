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
        <div style="display: flex; align-items: center;">
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

    <!-- searcj not found modal -->
    <div class="custom-modal" id="noMatchModal" style="display: none;">
        <div class="custom-modal-content">
            <div class="custom-modal-body">
                <h5 class="custom-modal-title">提示</h5>
                <p>No matching product found.</p>
                <button type="button" class="custom-btn" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- cart modal -->
    <div class="cart-modal" id="cartModal">
      <div class="modal-content">
        <div class="cart-header">
          <button class="back-btn">
            <i class="fas fa-chevron-left"></i>
          </button>
          <h2>My Cart</h2>
        </div>
        
        <div class="restaurant-name">The Toast 土司坊</div>
        
        <div class="order-info">
          <span><i class="far fa-clock"></i> Pre-Order</span>
          <span><i class="fas fa-user"></i> 2 pax</span>
          <i class="fas fa-chevron-right"></i>
        </div>

        <div class="cart-items">
          <div class="cart-item">
            <img src="/images/fishnchip.png" alt="Crispy Fish & Chips">
            <div class="item-details">
              <h3>Crispy Fish & Chips</h3>
              <p class="item-remark">Tata sauce</p>
              <div class="item-price">RM 19.80</div>
            </div>
            <div class="quantity-controls">
              <button class="quantity-btn minus">-</button>
              <span class="quantity">1</span>
              <button class="quantity-btn plus">+</button>
            </div>
          </div>
        </div>

        <div class="cart-summary">
          <div class="summary-row">
            <span>Subtotal</span>
            <span>RM 97.40</span>
          </div>
          <div class="summary-row">
            <span>SST (6%)</span>
            <span>RM 5.84</span>
          </div>
          <div class="summary-row">
            <span>Rounding</span>
            <span>RM 0.01</span>
          </div>
          <div class="summary-row total">
            <span>Total <small>(incl. fees and tax)</small></span>
            <span>RM 103.25</span>
          </div>
        </div>

        <button class="confirm-order-btn">Confirm Order</button>
      </div>
    </div>
    <!-- cart modal -->



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
        $('.add-btn').click(function(e) {
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
                            `<input class="form-check-input" type="${inputType}" name="${inputName}" value="${option}" data-price="${optionPrice}" ${variant.is_required ? 'required' : ''}>
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
        }
      });

      //add to cart
      // 获取元素
const foodModal = document.getElementById('foodModal');
const closeModalButton = document.querySelector('.close-modal');
const addCartButton = document.querySelector('.add-cart-btn');
const cartCount = document.querySelector('.cart-count');
const cartTotal = document.querySelector('.cart-total');
const quantityElement = document.getElementById('quantity');
const productPriceElement = document.getElementById('modal-product-price');

// 从 localStorage 初始化购物车数据
let cart = JSON.parse(localStorage.getItem('cart')) || {
  totalQuantity: 0,
  totalPrice: 0
};

// 初始化时更新购物车显示
updateCart();

// 打开modal时的初始化
function openModal(product) {
  // 重置数量为1
  quantityElement.textContent = '1';
  
  // 重置变体选项
  document.querySelectorAll('#modal-variant-options input[type="checkbox"], #modal-variant-options input[type="radio"]')
    .forEach(input => {
      input.checked = false;
    });
    
  // 设置初始价格为产品单价
  const basePrice = product.unit_price;
  productPriceElement.textContent = `RM ${basePrice.toFixed(2)}`;
  addCartButton.textContent = `Add to cart - RM ${basePrice.toFixed(2)}`;
  
  // 显示modal
  foodModal.style.display = 'block';
}

// 关闭modal
function closeModal() {
  foodModal.style.display = 'none';
}

// 更新modal中的价格显示
function updateModalPrice() {
  const basePrice = parseFloat(productPriceElement.textContent.replace('RM ', '').trim());
  let totalPrice = basePrice;
  
  // 计算变体选项的附加费用
  document.querySelectorAll('#modal-variant-options .form-check-input:checked').forEach(option => {
    totalPrice += parseFloat(option.dataset.price);
  });
  
  const quantity = parseInt(quantityElement.textContent);
  totalPrice *= quantity;
  
  // 更新"Add to cart"按钮的价格显示
  addCartButton.textContent = `Add to cart - RM ${totalPrice.toFixed(2)}`;
}

// 更新购物车
function updateCart() {
  cartCount.textContent = cart.totalQuantity;
  cartTotal.textContent = `RM ${cart.totalPrice.toFixed(2)}`;
  localStorage.setItem('cart', JSON.stringify(cart));
}

// 添加到购物车
addCartButton.addEventListener('click', () => {
  const quantity = parseInt(quantityElement.textContent);
  let basePrice = parseFloat(productPriceElement.textContent.replace('RM ', '').trim());
  let totalPrice = basePrice;

  // 计算变体选项的附加费用
  document.querySelectorAll('#modal-variant-options .form-check-input:checked').forEach(option => {
    totalPrice += parseFloat(option.dataset.price);
  });

  totalPrice *= quantity;

  if (isNaN(totalPrice)) {
    alert('Invalid product price');
    return;
  }

  // 更新购物车
  cart.totalQuantity += quantity;
  cart.totalPrice += totalPrice;
  
  // 更新购物车显示
  updateCart();
  
  // 关闭modal
  closeModal();
});

// 在产品卡片上添加点击事件监听器
document.querySelectorAll('.add-btn').forEach(button => {
  button.addEventListener('click', function(e) {
    e.preventDefault();
    const product = JSON.parse(this.dataset.product);
    openModal(product);
  });
});

// 数量控制
document.querySelector('.quantity-btn.minus').addEventListener('click', () => {
  let quantity = parseInt(quantityElement.textContent);
  if (quantity > 1) {
    quantity--;
    quantityElement.textContent = quantity;
    updateModalPrice();
  }
});

document.querySelector('.quantity-btn.plus').addEventListener('click', () => {
  let quantity = parseInt(quantityElement.textContent);
  quantity++;
  quantityElement.textContent = quantity;
  updateModalPrice();
});

// 监听变体选项变化
document.querySelector('#modal-variant-options').addEventListener('change', () => {
  updateModalPrice();
});

// 初始化产品数量追踪对象
let productQuantities = JSON.parse(localStorage.getItem('productQuantities')) || {};

// 更新产品卡片上的数量显示
function updateProductCardQuantity(productId, quantity) {
  const addButton = document.querySelector(`.add-btn[data-product-id="${productId}"]`);
  if (addButton) {
    if (quantity > 0) {
      addButton.innerHTML = `<span>${quantity}</span>`;
      addButton.classList.add('added'); // 添加类以改变样式
    } else {
      addButton.innerHTML = '<span>+</span>';
      addButton.classList.remove('added');
    }
  }
}

// 添加到购物车时更新数量显示
addCartButton.addEventListener('click', () => {
  const quantity = parseInt(quantityElement.textContent);
  const productId = addCartButton.getAttribute('data-product-id');
  
  // 更新产品数量
  if (!productQuantities[productId]) {
    productQuantities[productId] = 0;
  }
  productQuantities[productId] += quantity;
  
  // 保存到 localStorage
  localStorage.setItem('productQuantities', JSON.stringify(productQuantities));
  
  // 更新UI显示
  updateProductCardQuantity(productId, productQuantities[productId]);
  
  // ... 其他购物车更新逻辑 ...
});

// 页面加载时初始化所有产品的数量显示
document.addEventListener('DOMContentLoaded', () => {
  for (const productId in productQuantities) {
    updateProductCardQuantity(productId, productQuantities[productId]);
  }
});

    </script>

  </body>
</html>
