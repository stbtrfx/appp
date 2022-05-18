@extends('admin.layouts.app')


@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
    <!--begin::Page title-->
        <div class="d-flex align-items-center me-3">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.Academy')
            <!--begin::Separator-->
            <span class="h-20px border-gray-200 border-start ms-3 mx-2"></span>
            <!--end::Separator-->
            <!--begin::Description-->
            <small class="text-muted fs-7 fw-bold my-1 ms-1">@lang('site.Edit')</small>
            <!--end::Description--></h1>
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
                <form method="post" action="{{ route('academy.update', $academy->id) }}" enctype="multipart/form-data">
                    @csrf

                @method('put')
                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Title') @lang('site.in English')</label>
                    <input type='text' name="title_en" class="form-control" value="{{ $academy->title_en }}" />
                </div>
                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Title') @lang('site.in Arabic') </label>
                    <input type='text' name="title_ar" class="form-control" value="{{ $academy->title_ar }}" />
                </div>


                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Description')
                        @lang('site.in English')</label>
                    <input type='text' name="des_en" class="form-control" value="{{ $academy->des_en }}" />
                </div>
                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Description')
                        @lang('site.in Arabic')</label>
                    <input type='text' name="des_ar" class="form-control" value="{{ $academy->des_ar }}" />
                </div>

                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Video') </label>
                    <input type='text' name="link" class="form-control" value="{{ $academy->link }}" />
                </div>



                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Price')</label>
                    <input type='number' name="price" class="form-control" value="{{ $academy->price }}" />
                </div>


                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label"> @lang('site.Price') @lang('site.Coins')</label>
                    <input type='number' name="price_coins" class="form-control" value="{{ $academy->price_coins }}" />
                </div>


                <div class="form-group mb-10">
                    <label for="exampleFormControlInput1" class="required form-label">@lang('site.category')</label>

                        <select class="form-control"  name='category'>


                                    <option value='educational' @if($academy->category == 'educational' ) selected @endif>Educational</option>
                                    <option value='strategy' @if($academy->category == 'strategy' ) selected @endif>Strategy</option>


                        </select>
                    </div>



                <!--<div class="form-group mb-10">-->
                <!--    <label for="exampleFormControlInput1" class="required form-label">@lang('site.Level')</label>-->

                <!--        <select class="form-control"  name='level'>-->
                <!--            @foreach($levels as $r)-->

                <!--                    <option value='{{$r->id}}' @if($r->id == $academy->level) selected @endif> {{$r->title_en ?? $r->title_ar}}</option>-->

                <!--            @endforeach-->
                <!--        </select>-->
                <!--    </div>-->

                    <div class="form-group">
                        <label for="exampleFormControlInput1" class="required form-label">@lang('site.Image') </label>
                        <input class="image_name" type="file" name="image" value="">
                    </div>




                    <div class="text-right mb-5">
                        <input type="submit" name="add" class="btn btn-success" value="@lang('site.Update')">
                    </div>
                </form>
            </div>
        </div>
        <!-- // END drawer-layout__content -->
    </div>
@stop
