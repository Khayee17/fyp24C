@extends('admin.layouts.app')
@section('content')
<style>
    .order-sidebar {
        max-height: 505px; /* Set a fixed height for the sidebar */
        overflow-y: auto; /* Enable vertical scrolling within the sidebar */
        padding: 15px;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .tabs {
        display: flex;
        background-color: #fff; /* Ensure tabs have a visible background */
        z-index: 10; /* Make sure tabs are above other content */
        top: 0; /* Stick to the top of the order-sidebar */
        position: sticky; /* Sticky positioning for scrolling */
        padding: 10px 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .tab {
        flex: 1;
        text-align: center;
        cursor: pointer;
        font-weight: bold;
        border-bottom: 2px solid transparent;
        color: #555;
        transition: all 0.3s;
    }

    .tab.active {
        border-bottom: 2px solid #ff7f00;
        color: #ff7f00;
    }

    /* Hide inactive content by default */
    .tab-content {
        display: none;
    }

    /* Show active content */
    .tab-content.active {
        display: block;
    }

    /* Scrollable List Styling */
    .scrollable-list {
        max-height: auto; /* Set a fixed height */
        
    }

    /* Scrollbar Styles */
    .order-sidebar::-webkit-scrollbar {
        width: 6px; /* Make scrollbar thinner */
    }

    .order-sidebar::-webkit-scrollbar-track {
        background: #f9f9f9; /* Match sidebar background for a seamless look */
        border-radius: 8px;
    }

    .order-sidebar::-webkit-scrollbar-thumb {
        background-color: #ff7f00; /* Scrollbar thumb color */
        border-radius: 10px; /* Rounded edges for a softer look */
        border: 2px solid #f9f9f9; /* Adds padding-like effect */
    }

    .order-sidebar::-webkit-scrollbar-thumb:hover {
        background-color: #ff7f00; /* Darken on hover for visual feedback */
    }

    .waitlist-item {
        background-color: #fff;
        padding: 10px 10px 0 10px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .waitlist-item h3 {
        color: #ff7f00;
        font-size: 23px;
    }

    .waitlist-item p {
        margin: 5px 0;
        font-size: 16px;
        color: #333;
    }

    .phone-number {
        color: #555;
    }

    .buttons {
        margin-top: 15px;
    }

    .buttons button {
        margin-right: 10px;
        background-color: #ff7f00;
        color: #fff;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .buttons button:hover {
        background-color: #e66a00;
    }

    .floor-plan {
        height: 100%;
        min-height: 480px;
        width: 100%;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        
        
    }

    .table-title {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    .table-container {
        display: flex; /* 使用 Flexbox 布局 */
        flex-wrap: wrap; /* 允许换行 */
        gap: 10px; /* 设置子元素间距 */
    }

    .table-item {
        width: 110px;
        height: 90px;
        margin: 10px;
        padding: 5px;
        text-align: center;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        background-color: #fff; /* 背景颜色 */
        border: 2px solid #ff7f00; /* 边框 */
        border-radius: 15px; /* 圆角 */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* 阴影 */
        color: #000; /* 字体颜色 */
        font-family: Arial, sans-serif; /* 字体样式 */
        font-size: 15px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    
    .table-item .table-label.available {
        background-color: #28a745; /* 绿色 */
        color: white;
    }

    .table-item .table-label.occupied {
        background-color: #dc3545; /* 红色 */
        color: white;
    }

    .table-item:hover {
        transform: scale(1.05); /* 鼠标悬浮时的缩放效果 */
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }

    .table-item .table-label {
        font-size: 15px; /* 桌子标题字体大小 */
        font-weight: bold;
        background-color: #ff7f00; /* 背景颜色改为红色 */
        color: #fff; /* 字体颜色改为白色 */
        border-radius: 18px; /* 圆角 */
        padding: 0 15px; 
    }

    .table-item .table-price {
        font-size: 14px; /* 金额字体大小 */
        margin-top: 5px;
    }

</style>

<!-- Page Wrapper -->
<div class="page-wrapper">

    <!-- Page Content -->
    <div class="content container-fluid">

        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Waitlist</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Waitlist</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="order-sidebar">
                        <div class="tabs">
                            <div class="tab active" id="waitlist-tab">Waitlist</div>
                            <div class="tab" id="seated-tab">Seated</div>
                            <div class="tab" id="history-tab">History</div>
                        </div>

                       <!-- Waitlist Content -->
                        <div id="waitlist-content" class="tab-content active">
                            <div class="scrollable-list">
                                <!-- List Items -->
                                @foreach ($waitlist as $order)
                                    <div class="waitlist-item" data-order-id="{{ $order->id }}" data-created-at="{{ strtotime($order->created_at) }}">
                                        <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <p><strong>Waiting:</strong><span class="waiting-time">{{ $order->waiting_time }} mins</span></p>
                                        <div class="buttons">
                                            <button class="notify-button" data-order-id="{{ $order->id }}" data-phone="{{ $order->phone }}">Notify</button>
                                            <button class="seat-button" data-order-id="{{ $order->id }}">Seat</button>
                                            <button>View</button>
                                        </div>
                                        <p><small>{{ $order->created_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @endforeach
                                <!-- Add more items as needed -->
                            </div>
                        </div>


                        <!-- Seated Content -->
                        <div id="seated-content" class="tab-content">
                            <div class="scrollable-list">
                            @foreach ($seatedOrders as $order)
                                <div class="waitlist-item" data-order-id="{{ $order->id }}" data-created-at="{{ strtotime($order->created_at) }}">
                                    <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                    <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                    <p><strong>Seated: </strong>Table {{ $order->table->name }}</p>
                                    <div class="buttons">
                                        <button class="notify-button" data-order-id="{{ $order->id }}">Notify</button>
                                        <button>View</button>
                                    </div>
                                    <button class="complete-order-button" data-order-id="{{ $order->id }}" data-table-id="{{ $order->table->id }}">Complete</button>
                                    <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                </div>
                            @endforeach
                                
                            </div>
                        </div>

                        <!-- History Content -->
                        <div id="history-content" class="tab-content">
                            <div class="scrollable-list">
                                <!-- List Items -->
                                @foreach ($historyOrders as $order)
                                    <div class="waitlist-item">
                                        <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <!-- <p><strong>History:</strong> Completed</p> -->
                                        <div class="buttons">
                                            <button class="notify-button" data-order-id="{{ $order->id }}">Notify</button>
                                            <button>View</button>
                                        </div>
                                        <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>


                <div class="col-md-8">
                    <div class="floor-plan">
                        <h2 class="table-title">Table Layout</h2>
                        <div class="table-container">
                            @foreach ($tables as $table)
                            <div class="table-item" data-table-id="{{ $table->id }}" data-capacity="{{ $table->capacity }}" data-status="{{ $table->status }}" >
                                <div class="table-label @if($table->status == 'available') available @else occupied @endif">Table {{ $table->name }} </br> ({{ $table->capacity }} pax)</div>
                                <!-- <div class="table-price">RM {{ number_format($table->price, 2) }}</div> -->
                                <div class="table-status">{{ $table->status == 'available' ? 'Available' : 'Occupied' }}</div>
                                @if ($table->status == 'occupied')
                                    <!-- Add Complete Order button -->
                                    <button class="complete-order-button" data-table-id="{{ $table->id }}">Complete Order</button>
                                @endif
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // JavaScript for tab switching
        document.getElementById('waitlist-tab').addEventListener('click', function() {
            setActiveTab('waitlist');
        });

        document.getElementById('seated-tab').addEventListener('click', function() {
            setActiveTab('seated');
        });

        document.getElementById('history-tab').addEventListener('click', function() {
            setActiveTab('history');
        });

        function setActiveTab(tabName) {
            // Remove active class from all tabs and content
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active class to the selected tab and content
            document.getElementById(tabName + '-tab').classList.add('active');
            document.getElementById(tabName + '-content').classList.add('active');
        }

        
       
    
        setInterval(function() {
            // 获取所有待处理的订单项
            document.querySelectorAll('.waitlist-item').forEach(function (item) {
                // 获取订单的创建时间
                var createdAt = parseInt(item.getAttribute('data-created-at'));

                // 确保 createdAt 是一个有效的数字
                if (isNaN(createdAt)) {
                    console.error('Invalid timestamp for order ID:', item.getAttribute('data-order-id'));
                    return; // 如果时间戳无效，跳过此项
                }

                // 获取当前时间戳（秒）
                var currentTime = Math.floor(Date.now() / 1000);

                // 计算时间差（秒）
                var waitingTimeInSeconds = currentTime - createdAt;

                // 确保等待时间不小于0
                waitingTimeInSeconds = Math.max(waitingTimeInSeconds, 0);

                // 将秒转换为分钟和秒
                var minutes = Math.floor(waitingTimeInSeconds / 60);
                var seconds = waitingTimeInSeconds % 60;

                // 更新等待时间
                var waitingTimeElement = item.querySelector('.waiting-time');
                if (waitingTimeElement) {
                    waitingTimeElement.textContent = `${minutes} mins ${seconds} seconds`;
                }

                // 调试信息
                console.log('Order ID:', item.getAttribute('data-order-id'), 'Waiting time:', `${minutes} mins ${seconds} seconds`);
            });
        }, 1000); // 每秒更新一次


        //assign table
        const seatButtons = document.querySelectorAll('.seat-button');

        seatButtons.forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.dataset.orderId;

                fetch(`/assignTable/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ orderId: orderId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Order assigned to a table successfully!');
                        location.reload(); // 刷新页面以更新界面
                    } else {
                        alert(data.error || 'An error occurred while assigning the table.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        // 完成订单按钮的点击事件
        $('.complete-order-button').on('click', function() {
            var tableId = $(this).data('table-id');

            // 获取当前桌位上已经就座的订单
            var orderId = getSeatedOrderIdForTable(tableId);
            if (!orderId) {
                alert('No order seated at this table!');
                return;
            }

            // 发送 AJAX 请求，通知后台完成订单
            $.ajax({
                url: '/admin/order/complete/' + orderId,  // 完成订单的后端 URL
                method: 'POST',
                data: {
                    table_id: tableId
                },
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                success: function(response) {
                    if (response.success) {
                        // 更新桌位状态为 'available'
                        $('div[data-table-id="' + tableId + '"] .table-status').text('Available');
                        $('div[data-table-id="' + tableId + '"]').removeClass('occupied').addClass('available');

                        // 更新订单状态为 'history'，从列表中移除该订单
                        $('div[data-order-id="' + orderId + '"]').remove();

                        alert('Order completed successfully!');
                    } else {
                        alert('Error completing order!');
                    }
                },
                error: function() {
                    alert('Error completing order!');
                }
            });

        });

        // 辅助函数：根据 tableId 获取对应的 seated 订单 ID
        function getSeatedOrderIdForTable(tableId) {
            // 通过 tableId 获取该桌位上已经就座的订单 ID
            var order = @json($seatedOrders); // 获取 seated 订单的列表
            var seatedOrder = order.find(o => o.table_id == tableId);
            return seatedOrder ? seatedOrder.id : null;
        }

        //notify
        document.querySelectorAll('.notify-button').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                let phone = this.closest('.waitlist-item').querySelector('.phone-number').textContent.trim();

                // 验证并修复电话号码格式
                if (phone.startsWith('0')) {
                    phone = '+60' + phone.substring(1);
                } else if (!phone.startsWith('+')) {
                    alert('电话号码格式无效，请检查！');
                    return;
                }

                fetch('/notify', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        phone: phone,
                        message: `Your table is ready! Please proceed to the restaurant. Order ID: ${orderId}`,
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('通知发送成功！');
                        } else {
                            alert('发送失败：' + data.message);
                        }
                    })
                    .catch(error => console.error('错误:', error));
            });
        });


        






    });

</script>

@endsection
