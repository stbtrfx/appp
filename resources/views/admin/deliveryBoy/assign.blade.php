@extends('admin.layouts.app')


@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="d-flex align-items-center me-3">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.Delivery Boy')
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">@lang('site.create')</small>
            <!--end::Description--></h1>
            <!--end::Title-->
        </div>
        <!--end::Page title-->

    </div>
    <!--end::Container-->
</div>
@endsection

@section('content')


        <div class="container-fluid page__container p-2">

            <div class="card rounded card-form__body card-body shadow-lg">

                @include('admin.includes.messages')

                <form method="post" action="{{ route('addDelivery.resturant') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group mb-10">
                        <label for="exampleFormControlInput1" class="required form-label">@lang('site.Delivery Boy')  @lang('site.phone')</label>
                        <input  type='text' name="phone" class="form-control" value="" />
                    </div>

                    @if(Auth::user()->hasRole(['admin','moderator']))

                    <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.resturant')</label>

                        <select class="form-control"  name='resturant'  >
                            @foreach($resturants as $r)
                                
                            <option value='{{$r->id}}'> {{$r->name_ar ?? $r->name_en}}</option>
                               
                            @endforeach
                        </select>
                    </div>
                    @endif
                   
                    <div class="text-right mb-5">
                        <input type="submit" name="add" class="btn btn-success" value="@lang('site.add')">
                    </div>

                 

                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
  

@stop
