@extends('admin.layouts.app')


@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="d-flex align-items-center me-3">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.Recommendations')
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
            <form method="post" action="{{ route('recommendation.store') }}" enctype="multipart/form-data">
                @csrf


                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Title') @lang('site.in English')</label>
                    {{-- <input type='text' name="title_en" class="form-control" value="{{ old('title_en') }}" /> --}}
                    <select class="form-control"  name='title_en'>
                        @foreach ( $currency as $c )
                        <option value='{{$c->name}}'>{{$c->name}}</option>

                        @endforeach

                    </select>
                </div>
                {{-- <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Title') @lang('site.in Arabic') </label>
                    <input type='text' name="title_ar" class="form-control" value="{{ old('title_ar') }}" />
                </div> --}}
                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Description') @lang('site.in English') </label>
                    <textarea name="des_en" class="form-control" >{{ old('des_en') }}</textarea>
                </div>
                <!--<div class="form-group mb-10">-->
                <!--    <label for="exampleFormControlInput1" class="form-label">@lang('site.Description') @lang('site.in Arabic') </label>-->
                <!--    <textarea name="des_ar" class="form-control" >{{ old('des_ar') }}</textarea>-->
                <!--</div>-->



                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Price')</label>
                    <input type='number' name="price" class="form-control" value="{{ old('price') ?? '100' }}" />
                </div>
                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label"> @lang('site.Price') @lang('site.Coins')</label>
                    <input type='number' name="price_coins" class="form-control" value="{{ old('price_coins') ?? '100'}}" />
                </div>




                <div class="form-group mb-10">
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" name='show' id="status" />
                        <label class="form-check-label" for="flexSwitchDefault">
                            @lang('site.show')  @lang('site.free')
                        </label>
                    </div>
                </div>




        </div>
<br>
        <div class="card rounded card-form__body card-body shadow-lg">
            <div class='row'>
            <div class="form-group mb-10 col-md-3">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Action') </label>
                <select class="form-control"  name='action'>
                    <option value='SELL'>SELL</option>
                    <option value='BUY'>BUY</option>
        </select>
            </div>
            <div class="form-group mb-10 col-md-3">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Status') </label>
                <select class="form-control"  name='status'>
                            <option value='Active'>Active</option>
                            <option value='Closed'>Closed</option>
                </select>


            </div>
            <div class="form-group mb-10 col-md-4">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Openning Time') </label>
                <input type='datetime-local' name="opening_time" class="form-control" value="{{ old('opening_time') }}" />
            </div>
            <div class="form-group mb-10 col-md-6">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Open Price') </label>
                <input type='text' name="open_price" class="form-control" value="{{ old('open_price') }}" />
            </div>
            <div class="form-group mb-10 col-md-6">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Stop Loss') </label>
                <input type='text' name="stop_loss" class="form-control" value="{{ old('stop_loss') }}" />
            </div>
            <div class="form-group mb-10 col-md-4">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Take Profit') 1 </label>
                <input type='text' name="take_profit1" class="form-control" value="{{ old('take_profit1') }}" />
            </div>
            <div class="form-group mb-10 col-md-4">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Take Profit')2</label>
                <input type='text' name="take_profit2" class="form-control" value="{{ old('take_profit2') }}" />
            </div>
            <div class="form-group mb-10 col-md-4">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Take Profit') 3</label>
                <input type='text' name="take_profit3" class="form-control" value="{{ old('take_profit3') }}" />
            </div>
            <div class="form-group mb-10 col-md-6">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Profit Loss') </label>
                <input type='text' name="profit_loss" class="form-control" value="{{ old('profit_loss') }}" />
            </div>


            <div class="form-group mb-10 col-md-6">
                <label for="exampleFormControlInput1" class="required form-label">@lang('site.Trade Result') </label>
                <input type='text' name="trade_result" class="form-control" value="{{ old('trade_result') }}" />
            </div>



            <div class="form-group mb-10 col-md-6">
                <label for="exampleFormControlInput1" class="form-label">@lang('site.Comment') </label>
                <textarea name="comment" class="form-control" >{{ old('comment') }}</textarea>
            </div>

        </div>{{--  end row --}}
        </div>

    </div>
    <!-- // END drawer-layout__content -->

    <div class="text-right mb-5">
        <input type="submit" name="add" class="btn btn-success" value="@lang('site.add')">
    </div>
</form>

@stop
