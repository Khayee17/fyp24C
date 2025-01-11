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
        position: relative;
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

    .table-item #view-order-button,
    .table-item .complete-order-button {
        display: none; /* 初始时隐藏按钮 */
        background-color: #007bff; /* 蓝色背景 */
        color: white; /* 白色字体 */
        border: none; /* 去掉默认边框 */
        border-radius: 12px; /* 圆角 */
        padding: 5px 16px; /* 按钮内边距 */
        font-size: 14px; /* 字体大小 */
        cursor: pointer; /* 鼠标指针 */
        transition: background-color 0.3s, transform 0.2s; /* 平滑过渡效果 */
        position: absolute; /* 定位到 .table-item 内部 */
        bottom: 10px; /* 定位到底部 */
        left: 50%; /* 水平居中 */
        transform: translateX(-50%); /* 水平居中 */
        margin: 5px 0; /* 上下间距 */
    }

    .table-item:hover #view-order-button,
    .table-item:hover .complete-order-button {
        display: block; /* 悬停时显示按钮为块级元素 */
    }
    .table-item:hover #view-order-button {
        bottom: 40px; /* 调整 View 按钮的位置 */
    }

    .table-item:hover .complete-order-button {
        bottom: 5px; /* Complete 按钮的位置 */
    }

    .table-item:hover .table-label.occupied,
    .table-item:hover .table-status.occupied {
        opacity: 0; 
    }

    #view-order-button:hover,
    .complete-order-button:hover {
        background-color: #0056b3;
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
                            @if ($waitlist->isEmpty())
                                <p>No waiting orders at the moment.</p> 
                            @else
                                @foreach ($waitlist as $order)
                                <div class="waitlist-item" data-order-id="{{ $order->id }}" data-created-at="{{ strtotime($order->created_at) }}">
                                        <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <p><strong>Waiting:</strong><span class="waiting-time">{{ $order->waiting_time }} mins</span></p>
                                        <div class="buttons">
                                            <button class="notify-button" data-order-id="{{ $order->id }}" data-phone="{{ $order->phone }}">Notify</button>
                                            <button class="seat-button" data-order-id="{{ $order->id }}">Seat</button>
                                            <button class="btn btn-info view-order-details" data-order-id="{{ $order->id }}"> View </button>
                                        </div>
                                        <p><small>{{ $order->created_at->format('M d, Y h:i A') }}</small></p>
                                        
                                    </div>
                                @endforeach
                            @endif
                            </div>
                        </div>


                        <!-- Seated Content -->
                        <div id="seated-content" class="tab-content">
                            <div class="scrollable-list">
                            @if ($seatedOrders->isEmpty())
                                <p>No seated orders at the moment.</p>
                            @else
                                @foreach ($seatedOrders as $order)
                                    <div class="waitlist-item" data-order-id="{{ $order->id }}" data-created-at="{{ strtotime($order->created_at) }}">
                                        <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <p><strong>Table: </strong>
                                            @php
                                                $tableIds = json_decode($order->table_ids, true);
                                                $tableNames = \App\Models\Table::whereIn('id', $tableIds)->pluck('name')->toArray();
                                            @endphp
                                            {{ implode(', ', $tableNames) }}
                                        </p>
                                        <div class="buttons">
                                            <button class="notify-button" data-order-id="{{ $order->id }}">Notify</button>
                                            <button class="btn btn-info view-order-details" data-order-id="{{ $order->id }}"> View </button>
                                            <button class="complete-order-button" 
                                                data-order-id="{{ $order->id }}" 
                                                data-table-id="{{ implode(',', json_decode($order->table_ids, true)) }}">
                                                Complete
                                            </button>
                                        </div>
                                        <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @endforeach
                            @endif
                            </div>
                        </div>

                        <!-- History Content -->
                        <div id="history-content" class="tab-content">
                            <div class="scrollable-list">
                            @if ($historyOrders->isEmpty())
                                <p>No history orders at the moment.</p> 
                            @else
                                @foreach ($historyOrders as $order)
                                    <div class="waitlist-item" data-order-id="{{ $order->id }}" data-created-at="{{ strtotime($order->updated_at) }}">
                                        <h3>Order ID: {{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <div class="buttons">
                                            <button class="btn btn-info view-order-details" data-order-id="{{ $order->id }}">View</button>
                                        </div>
                                        <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @endforeach
                            @endif
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
                                    <div class="table-status">{{ $table->status == 'available' ? 'Available' : 'Occupied' }}</div>

                                    @if ($table->status == 'occupied')
                                        @php
                                            $order = \App\Models\MyOrder::where('table_ids', 'LIKE', '%' . $table->id . '%')->first();
                                        @endphp
                                        @if ($order)
                                            <button class="btn btn-info view-order-details" id="view-order-button" data-order-id="{{ $order->id }}"> View </button>
                                            <!-- <button class="complete-order-button" 
                                                data-order-id="{{ $order->id }}" 
                                                data-table-id="{{ $table->id }}">
                                                Complete
                                            </button> -->
                                        @endif
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

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" role="dialog" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="orderDetailsContent">
                    <!-- AJAX 内容会动态加载到这里 -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>





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

                if (isNaN(createdAt)) {
                    console.error('Invalid timestamp for order ID:', item.getAttribute('data-order-id'));
                    return; 
                }

                // 获取当前时间戳（秒）
                var currentTime = Math.floor(Date.now() / 1000);
                // 计算时间差（秒）
                var waitingTimeInSeconds = currentTime - createdAt;
                // 确保等待时间不小于0
                waitingTimeInSeconds = Math.max(waitingTimeInSeconds, 0);
                // 将秒转换为分钟和秒
                var hours = Math.floor(waitingTimeInSeconds / 3600);
                var minutes = Math.floor((waitingTimeInSeconds % 3600) / 60);
                var seconds = waitingTimeInSeconds % 60;

                // 更新等待时间
                var waitingTimeElement = item.querySelector('.waiting-time');
                if (waitingTimeElement) {
                    waitingTimeElement.textContent = `${hours} hrs ${minutes} mins ${seconds} secs`;
                }

                console.log('Order ID:', item.getAttribute('data-order-id'), 'Waiting time:', `${hours} hrs ${minutes} mins ${seconds} secs`);
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
                        // 动态生成提示信息
                        let tableNames = data.assignedTables.map(table => table.name);
                        let tablesMessage = tableNames.join(' and ');
                        let successMessage = `Order assigned to table ${tablesMessage} successfully!`;

                        alert(successMessage);
                        location.reload(); 

                        // 拼凑桌位信息
                        let assignedTablesHtml = '';
                        data.assignedTables.forEach(table => {
                            assignedTablesHtml += `
                                <div class="assigned-table">
                                    Table ${table.name} (${table.capacity} pax)
                                </div>
                            `;
                        });

                        const orderElement = document.querySelector(`.waitlist-item[data-order-id="${orderId}"]`);
                        const assignedTablesContainer = orderElement.querySelector('.assigned-tables');
                        assignedTablesContainer.innerHTML = assignedTablesHtml;

                        data.assignedTables.forEach(table => {
                            const tableElement = document.querySelector(`.table-item[data-table-id="${table.id}"]`);
                            tableElement.querySelector('.table-status').textContent = 'Occupied';
                        });

                    } else {
                        alert(data.error || 'An error occurred while assigning the tables.');
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });



        // 完成订单按钮的点击事件
        $('.complete-order-button').on('click', function() {
            var orderId = $(this).data('order-id');
            var tableId = $(this).data('table-id');  // 获取tableId

            console.log('orderId:', orderId);  // 打印调试
            console.log('tableId:', tableId);  // 打印调试

            if (!orderId || !tableId) {
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
                        location.reload(); // 刷新页面以更新界面
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
            var seatedOrder = order.find(o => JSON.parse(o.table_ids).includes(tableId));

            return seatedOrder ? seatedOrder.id : null;
        }

        //notify
        document.querySelectorAll('.notify-button').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-order-id');
                let phone = this.closest('.waitlist-item').querySelector('.phone-number').textContent.trim();

                console.log('Phone before format:', phone);

                // 验证并修复电话号码格式
                if (phone.startsWith('0')) {
                    phone = '+60' + phone.substring(1);
                } else if (!phone.startsWith('+')) {
                    alert('电话号码格式无效，请检查！');
                    return;
                }

                console.log('Phone after format:', phone); 

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

    //admin view order details
    document.querySelectorAll('.view-order-details').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');

            // 显示模态框
            const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
            modal.show();

            // 加载订单详情
            fetch(`/order/details/${orderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('orderDetailsContent').innerHTML = html;
                })
                .catch(error => {
                    document.getElementById('orderDetailsContent').innerHTML = '<p>Error loading order details.</p>';
                    console.error('Error:', error);
                });
        });
    });



</script>

@endsection
