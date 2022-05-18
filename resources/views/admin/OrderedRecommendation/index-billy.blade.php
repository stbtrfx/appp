@extends('admin.layouts.master')

  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


@section('toolbar')
<div class="toolbar" id="kt_toolbar">
							<!--begin::Container-->
							<div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
								<!--begin::Page title-->
								<div class="d-flex align-items-center me-3">
									<!--begin::Title-->
									<h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">Product
									<!--begin::Separator-->
									<span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
									<!--end::Separator-->
									<!--begin::Description-->
									<small class="text-muted fs-7 fw-bold my-1 ms-1"></small>
									<!--end::Description--></h1>
									<!--end::Title-->
								</div>
								<!--end::Page title-->
							
							</div>
							<!--end::Container-->
</div>
@endsection

@section('content')

<div class="card mb-5 mb-xl-8">
									<!--begin::Header-->
									<div class="card-header border-0 pt-5">
										<h3 class="card-title align-items-start flex-column">
											<span class="card-label fw-bolder fs-3 mb-1">All orders</span>
										</h3>
										<div class="card-toolbar">
											<a href="{{route('order.create')}}" class="btn btn-sm btn-light-primary">
											<!--begin::Svg Icon | path: icons/stockholm/Communication/Add-user.svg-->
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
													<path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
													<path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
												</svg>
											</span>
											<!--end::Svg Icon-->Add order +</a>
										</div>
									</div>
									<!--end::Header-->
									<!--begin::Body-->
									<div class="card-body py-3">
										<!--begin::Table container-->
										<div class="table-responsive">
											<!--begin::Table-->
											<table class="table align-middle gs-0 gy-4">
												<!--begin::Table head-->
												<thead>
													<tr class="fw-bolder text-muted bg-light">
                                                        <th class="min-w-125px">Order No</th>
														<th class="min-w-125px">Date</th>
														<th class="min-w-125px">Branch</th>
														<th class="min-w-125px">Customer Name</th>
														<th class="min-w-125px">Customer Phone</th>
														<th class="min-w-125px">Type</th>
														<th class="min-w-125px">Address</th>
														<th class="min-w-125px">Total</th>

														<th class="min-w-150px">Status</th>
														<th class="min-w-150px">Assign To</th>
														<th class="min-w-200px text-start rounded-end">Action</th>
													</tr>
												</thead>
												<!--end::Table head-->
												<!--begin::Table body-->
												<tbody>
                                                @foreach($orders as $index => $c)
													<tr>
														<td>
															

                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->id}}</span>
																	
															
														</td>
                                                        <td>
                                                        <span class="text-muted fw-bold text-muted d-block fs-7">{{$c->created_at}}</span>
                                                        </td>
                                                        <td>
                                                       
                                                        <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->branch->name_en}}</span>

                                                        </td>

                                                        <td>
                                                       
                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->user->name ?? ''}}</span>

                                                       </td>

                                                       
                                                       <td>
                                                       
                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->user->phone ?? ''}}</span>

                                                       </td>

                                                       <td>
                                                       
                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->type}}</span>

                                                       </td>
                                                        
                                                       <td>
                                                       
                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->address}}</span>

                                                       </td>

                                                       <td>
                                                       
                                                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->total}} LE</span>

                                                       </td>
                                                        <form action="{{ route('order.update', $c->id) }}" method='post' id='status_form{{$index}}'>
                                                        <input type='hidden' id='order_id' value='{{$c->id}}' name='id'> 
                                                        @csrf 
                                                        @method('put')
                                                        <td>
                                                        <!-- <span class="badge badge-light-primary fs-7 fw-bold">{{$c->status}}</span> -->
                                                        <select class="form-control"  name='status' id='sel_status{{$index}}'>
                                                                <!-- <option>Update Status</option> -->
                                                            <option value='Pending' @if($c->status =='Pending') selected @endif >Pending</option>
                                                            <option value='Confirmed' @if($c->status =='Confirmed') selected @endif >Confirmed</option>
                                                            <option value='On Delivery' @if($c->status =='On Delivery') selected @endif >On Delivery</option>
                                                            <option value='Delivered' @if($c->status =='Delivered') selected @endif >Delivered</option>

                                                           
                                                        </select>
                                                         
                                                        </td>
                                                        </form>


															<script>
																		$(document).ready(function(){
																			$('#sel_status{{$index}}').change(function(){
																				// var id = $('#order_id').val();
																			
																				$("#status_form{{$index}}").submit();
																		});
																	});
															</script>
 														<form action="{{ route('order.assign', $c->id) }}" method='post' id='delivery_form{{$index}}'>
														 <input type='hidden' id='order_id' value='{{$c->id}}' name='id'> 
														 @csrf 
                                                        @method('put')
														<td>
																	
																		@if($c->status == 'Pending')
																
																		<select class="form-control"  name='delivery_id' id='sel_delivery{{$index}}'>
																				<option>Assign Order To Delivery Boy</option>
																				@foreach($delivery as $d)
																					<option value='{{$d->id}}' >{{$d->user->name}}</option>
																				@endforeach

																		
																		
																		
																		</select>
																		@else
																		<span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->delivery->user->name ?? ''}}</span>
																		@endif
                                                         
														<script>
																		$(document).ready(function(){
																			$('#sel_delivery{{$index}}').change(function(){
																				// var id = $('#order_id').val();
																			
																				$("#delivery_form{{$index}}").submit();
																		});
																	});
															</script>

                                                        </td>
														</form>
														<td class="text-start">
														
															<a href="{{ route('order.print', $c->id) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"> 
                                                            <i class="fa fa-print"></i>
															</a>
															<form action="{{ route('order.destroy', $c->id) }}" method="post" id='delform' style="display: inline-block">
                                                                @csrf
                                                                @method('delete')

                                                                
                                                                <button type="submit" class="btn btn-defult btn-xs delete" style='width:20px'><i class="fa fa-trash"></i> </button>
                                                            </form>
														</td>
                                                    </tr>
                                                    @endforeach
													
												</tbody>
												<!--end::Table body-->
											</table>
											<!--end::Table-->
										</div>
										<!--end::Table container-->
									</div>
									<!--begin::Body-->
								</div>
								<!--end::Tables Widget 11-->
    
     

@endsection



	<!--begin::Tables Widget 11-->
    