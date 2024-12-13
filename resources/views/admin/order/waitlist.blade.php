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
                    <h3 class="page-title">Clients</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                        <li class="breadcrumb-item active">Clients</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_client"><i class="fa fa-plus"></i> Add Client</a>
                    <div class="view-icons">
                        <a href="clients.html" class="grid-view btn btn-link"><i class="fa fa-th"></i></a>
                        <a href="clients-list.html" class="list-view btn btn-link active"><i class="fa fa-bars"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Header -->

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="order-sidebar">
                        <div class="tabs">
                            <div class="tab active">Waitlist</div>
                            <div class="tab">Seated</div>
                            <div class="tab">History</div>
                        </div>
                        <div class="waitlist-item">
                            <h3>P025 - Jason</h3>
                            <p>📞 012-3456789</p>
                            <p>👥 4 Person</p>
                            <p>Waiting - 15 mins</p>
                            <div class="buttons">
                                <button>Notify</button>
                                <button>Seat</button>
                                <button>View</button>
                            </div>
                            <p>Nov 22, 2024 09:25am</p>
                        </div>
                        <div class="waitlist-item">
                            <h3>P026 - Jason</h3>
                            <p>📞 012-3456789</p>
                            <p>👥 4 Person</p>
                            <p>Waiting - 15 mins</p>
                            <div class="buttons">
                                <button>Notify</button>
                                <button>Seat</button>
                                <button>View</button>
                            </div>
                            <p>Nov 22, 2024 09:25am</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="floor-plan">
                        <h2 style="color: orange;">Table</h2>
                        <div class="table">T01</div>
                        <div class="table">T02</div>
                        <div class="table">T03</div>
                        <div class="table">T04</div>
                        <div class="table">T05</div>
                        <div class="table">T06</div>
                        <div class="table">T07</div>
                        <div class="table">T08</div>
                        <div class="table">T09</div>
                        <div class="table">T10</div>
                        <div class="table">T11</div>
                        <div class="table">T12</div>
                        <div class="table">T13</div>
                        <div class="table">T14</div>
                        <div class="table">T15</div>
                        <div class="table">T16</div>
                    </div>
                </div>
            </div>
        </div>
        
</div>
<!-- /Page Wrapper -->

<style>
        
        .order-sidebar {
            width: 300px;
            margin-right: 20px;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
        }
        .tab {
            flex: 1;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .tab.active {
            border-bottom: 2px solid orange;
            font-weight: bold;
        }
        .waitlist-item {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .waitlist-item h3 {
            margin: 0;
            color: orange;
        }
        .buttons {
            margin-top: 10px;
        }
        .buttons button {
            margin-right: 5px;
            background-color: #ffd700;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .floor-plan {
            flex-grow: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .table {
            width: 60px;
            height: 40px;
            border: 2px solid orange;
            margin: 10px;
            display: inline-block;
            text-align: center;
            line-height: 40px;
            border-radius: 5px;
        }
    </style>
 
@endsection
