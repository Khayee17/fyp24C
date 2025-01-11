  <!-- 订单详情内容 -->
    <div class="sticky-header">
        <div class="queue-alert">Show your queue ticket when your turn</div>
    </div>

<div class="my-order-modal" id="orderDetails" style="display: none;">
    <div class="my-order-content">
        <div class="my-order-header">
            <button class="my-order-back-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
            <h2>My Order</h2>
        </div>

        <div class="queue-alert">Show your queue ticket when your turn</div>

        <div class="ticket">
            <div class="ticket-left">
                <h2>Wait</h2>
                <div class="wait-number" id="wait-number">5</div>
            </div>
            <div class="ticket-divider"></div>
            <div class="ticket-right">
                <div class="cafe-info">
                    <div class="cafe-details">
                        <p>The Toast 土司坊</p>
                        <p>📍 Tun Aminah</p>
                    </div>
                </div>
                <div class="customer-info">
                    <div class="info">
                        <p>Number of Customers</p>
                        <p><strong><span id="orderCustomerCount"> </span></strong></p>
                    </div>
                    <div class="info">
                        <p>Order Id</p>
                        <p><strong id="queue-number"> </strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-order-details">
            <div class="order-list-header">
                <h2>Order Details</h2>
            </div>
            <div class="order-meta">
                <span id="order-id">Order Id: No. </span>
                <span id="order-created-at"> </span>
            </div>
            
            <div class="my-order-items" id="orderItems">
                
            </div>
            
            <div class="order-summary">
                <div class="summary-item">
                    <span>Subtotal</span>
                    <span id="subtotal">RM 0.00</span>
                </div>
                <div class="summary-item">
                    <span>SST (6%)</span>
                    <span id="sst">RM 0.00</span>
                </div>
                <div class="summary-item">
                    <span>Rounding</span>
                    <span id="rounding">RM 0.00</span>
                </div>
                <div class="total order-list-total">
                    <span>Total <small>(incl. fees and tax)</small></span>
                    <span id="total">RM 0.00</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const numberOfCustomers = localStorage.getItem('numberOfCustomers');
        if (numberOfCustomers) {
            $('#orderCustomerCount').text(numberOfCustomers);
        }

    });
</script>
