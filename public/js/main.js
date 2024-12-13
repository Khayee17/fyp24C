$(document).ready(function() {
  // 处理滚动事件
  const stickyHeader = $('.sticky-header');
  const scrollThreshold = 100;

  $(window).scroll(function() {
    const scrollTop = $(window).scrollTop();
    if (scrollTop > scrollThreshold) {
      stickyHeader.addClass('visible');
    } else {
      stickyHeader.removeClass('visible');
    }
  });

  // 打开购物车模态框
  $('.cart-button').click(function() {
    $('#myCartModal').fadeIn(200).addClass('show');
    $('.app-container, .menu-container, .sticky-header').hide(); // 隐藏其他内容
  });

  // 关闭购物车模态框
  $('.my-cart-back-btn').click(function() {
      $('#myCartModal').fadeOut(200).removeClass('show');
      $('.app-container, .menu-container, .sticky-header').show(); // 显示其他内容
  });

  //myorder
  // 打开订单详情页面
  $('.my-cart-confirm-btn').click(function() {
    $('#myCartModal').hide(); // 隐藏购物车模态框
    $('#orderDetails').fadeIn(200); // 显示订单详情
  });

   // 返回到购物车页面
   $('.my-order-back-btn').click(function() {
    $('#orderDetails').hide(); // 隐藏订单详情
    $('#myCartModal').fadeIn(200); // 显示购物车模态框
  });
});