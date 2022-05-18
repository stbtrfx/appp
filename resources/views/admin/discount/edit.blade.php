@extends('admin.layouts.app')


@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="d-flex align-items-center me-3">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.discount')
                    <!--begin::Separator-->
                    <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
                    <!--end::Separator-->
                    <!--begin::Description-->
                    <small class="text-muted fs-7 fw-bold my-1 ms-1">@lang('site.create')</small>
                    <!--end::Description-->
                </h1>
                <!--end::Title-->
            </div>
            <!--end::Page title-->

        </div>
        <!--end::Container-->
    </div>
@endsection

@section('content')

    @include('admin.includes.messages')

    <div class="container-fluid page__container p-2">

        <div class="card rounded card-form__body card-body shadow-lg">
            <form method="post" action="{{ route('discount.update', $discount->id) }}" enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class='row'>
                    <div class="col-lg-4">
                        <label for="exampleFormControlInput1" class="required form-label">@lang('site.category')</label>
                        <input type='text' name='category_id' class="form-control"
                            value='{{ $discount->product->category->name_en }}'>
                    </div>


                    <div class="col-lg-4">
                        <label for="exampleFormControlInput1" class="required form-label">@lang('site.product')</label>
                        <input type='text' name='product_id' class="form-control" value='{{ $discount->product->id }}'>
                    </div>
                </div>

                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.discount')</label>
                    <input type='number' name="discount" value='{{ $discount->discount }}' class="form-control" />
                </div>


                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Discount Type')</label>
                    <select class="form-select" name='discount_type' aria-label="Select example">
                        <option value="Flat" @if ($discount->discount_type == 'Flat') selected @endif>Flat</option>
                        <option value="Percentage" @if ($discount->discount_type == 'Percentage') selected @endif>Percentage</option>
                    </select>
                </div>



                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Flat')</label>
                    <input type='date' name="from" value='{{ $discount->from }}' class="form-control" />
                </div>

                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Flat')</label>
                    <input type='date' name="to" value='{{ $discount->to }}' class="form-control" />
                </div>

                <div class="form-group mb-10">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name='published' id="published" @if ($discount->published == 1) checked @endif />
                        <label class="form-check-label" for="flexSwitchDefault">
                            @lang('site.percentage')
                        </label>
                    </div>
                </div>



                <div class="text-right mb-5">
                    <input type="submit" name="add" class="btn btn-success" value="@lang('site.percentage')">
                </div>
            </form>
        </div>
    </div>
    <!-- // END drawer-layout__content -->
@stop
