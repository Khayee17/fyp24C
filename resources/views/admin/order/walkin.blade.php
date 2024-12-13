@extends('admin.layouts.app')
@section('content')

<!-- Page Wrapper -->
<div class="page-wrapper">
			
	<!-- Page Content -->
	<div class="content container-fluid">
		
		<!-- Page Header -->
		<div class="page-header">
			<div class="row">
				<div class="col-sm-12">
					<h3 class="page-title">Applied Jobs</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
						<li class="breadcrumb-item ">Jobs</li>
						<li class="breadcrumb-item">User Dashboard</li>
						<li class="breadcrumb-item active">Applied Jobs</li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /Page Header -->
		
		<!-- Content Starts -->
		<div class="card">
			<div class="card-body">
				<!-- <h4 class="card-title">Solid justified</h4> -->
				<ul class="nav nav-tabs nav-tabs-solid nav-justified">
					<li class="nav-item"><a class="nav-link" href="user-dashboard.html">Dashboard</a></li>
					<li class="nav-item"><a class="nav-link" href="user-all-jobs.html">All </a></li>
					<li class="nav-item"><a class="nav-link" href="saved-jobs.html">Saved</a></li>
					<li class="nav-item"><a class="nav-link active" href="applied-jobs.html">Applied</a></li>
					<li class="nav-item"><a class="nav-link" href="interviewing.html">Interviewing</a></li>
					<li class="nav-item"><a class="nav-link" href="offered-jobs.html">Offered</a></li>
					<li class="nav-item"><a class="nav-link" href="visited-jobs.html">Visitied </a></li>
					<li class="nav-item"><a class="nav-link" href="archived-jobs.html">Archived </a></li>
				</ul>
			</div>
		</div>	
		
		<!-- Search Filter -->
		<div class="row filter-row">
			<div class="col-sm-6 col-md-3">  
				<div class="form-group form-focus select-focus">
					<select class="select floating"> 
						<option>Select</option>
						<option>Development</option>
						<option>Designing</option>
						<option>Android</option>
					</select>
					<label class="focus-label">Department</label>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">  
				<div class="form-group form-focus select-focus">
					<select class="select floating"> 
						<option>Select</option>
						<option>Full Time</option>
						<option>Part Time</option>
						<option>Internship</option>
					</select>
					<label class="focus-label">Job Type</label>
				</div>
			</div>
			<div class="col-sm-6 col-md-3"> 
				<div class="form-group form-focus select-focus">
					<select class="select floating"> 
						<option>Select Designation</option>
						<option>Web Developer</option>
						<option>Web Designer</option>
						<option>Android Developer</option>
						<option>Ios Developer</option>
					</select>
					<label class="focus-label">Designation</label>
				</div>
			</div>
			<div class="col-sm-6 col-md-3">  
				<a href="#" class="btn btn-success btn-block"> Search </a>  
			</div>
		</div>
		<!-- Search Filter -->

		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table mb-0 datatable">
						<thead>
							<tr>
								<th>#</th>
								<th>Job Title</th>
								<th>Department</th>
								<th>Start Date</th>
								<th>Expire Date</th>
								<th class="text-center">Job Type</th>
								<th class="text-center">Status</th>
								<th class="text-center">Actions</th>
								
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td><a href="job-details.html">Web Developer</a></td>
								<td>Development</td>
								<td>3 Mar 2019</td>
								<td>31 May 2019</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-danger"></i> Full Time
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-danger"></i> Open
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>2</td>
								<td><a href="job-details.html">Web Designer</a></td>
								<td>Designing</td>
								<td>3 Mar 2019</td>
								<td>31 May 2019</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-success"></i> Part Time
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-success"></i> Closed
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>3</td>
								<td><a href="job-details.html">Android Developer</a></td>
								<td>Android</td>
								<td>3 Mar 2019</td>
								<td>31 May 2019</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-danger"></i> Internship
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="action-label">
										<a class="btn btn-white btn-sm btn-rounded" href="#" data-toggle="dropdown" aria-expanded="false">
											<i class="fa fa-dot-circle-o text-danger"></i> Cancelled
										</a>
									</div>
								</td>
								<td class="text-center">
									<div class="dropdown dropdown-action">
										<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
										<div class="dropdown-menu dropdown-menu-right">
											<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	
		<!-- /Content End -->
		
	</div>
	<!-- /Page Content -->
	
</div>
<!-- /Page Wrapper -->
@endsection

