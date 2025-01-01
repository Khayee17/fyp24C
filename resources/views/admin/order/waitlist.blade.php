@extends('admin.layouts.app')
@section('content')
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
                                    <div class="waitlist-item">
                                        <h3>{{ $order->id }} - <span class="phone-number">{{ $order->userInfo ? $order->userInfo->phone : 'No phone number' }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->userInfo ? $order->userInfo->numberOfCustomers : 'No customer count' }}</p>
                                        <p><strong>Waiting:</strong> {{ $order->waiting_time }} mins</p>
                                        <div class="buttons">
                                            <button>Notify</button>
                                            <button>Seat</button>
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
                                <!-- List Items -->
                                @foreach ($seatedOrders as $order)
                                    <div class="waitlist-item">
                                        <h3>{{ $order->id }} - <span class="phone-number">{{ $order->phone_number }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->customer_count }}</p>
                                        <p><strong>Seated:</strong> Yes</p>
                                        <div class="buttons">
                                            <button>Notify</button>
                                            <button>View</button>
                                        </div>
                                        <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @endforeach
                                
                            </div>
                        </div>

                        <!-- History Content -->
                        <div id="history-content" class="tab-content">
                            <div class="scrollable-list">
                                <!-- List Items -->
                                @forelse ($historyOrders as $order)
                                    <div class="waitlist-item">
                                        <h3>{{ $order->id }} - <span class="phone-number">{{ $order->phone_number }}</span></h3>
                                        <p><strong>People:</strong> {{ $order->customer_count }}</p>
                                        <p><strong>History:</strong> Completed</p>
                                        <p><small>{{ $order->updated_at->format('M d, Y h:i A') }}</small></p>
                                    </div>
                                @empty
                                    <p>No history available yet.</p>
                                @endforelse

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-8">
                    <div class="floor-plan">
                        <h2 class="table-title">Table Layout</h2>
                        <div class="table-container">
                            @foreach ($tables as $table)
                                <div class="table-item">
                                    <div class="table-label">Table {{ $table->name }}</div>
                                    <div class="table-price">RM {{ number_format($table->price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Page Wrapper -->

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
        font-size: 25px;
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
        padding: 0 8px; 
    }

    .table-item .table-price {
        font-size: 14px; /* 金额字体大小 */
        margin-top: 5px;
    }

</style>

<script>
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

    
    // 获取并更新桌位的总价
    function updateTablePrice() {
        $.ajax({
            url: '{{ route("updateTablePrice") }}', // 后端API路由
            type: 'GET',
            success: function (data) {
                // 更新页面上所有桌位的价格
                if (data.success) {
                    data.tables.forEach(function (table) {
                        // 根据 table id 更新对应的桌位显示内容
                        $('#table-' + table.id + ' .table-price').text('Total Price: RM ' + table.price);
                    });
                } else {
                    alert('Failed to update table prices.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred while updating table prices.');
            }
        });
    }

    // 初始化页面时调用
    updateTablePrice();

    // 可选：根据某些操作，比如在订单完成时再调用
    $('.update-price-button').on('click', function () {
        updateTablePrice(); // 手动触发更新
    });

</script>

@endsection
