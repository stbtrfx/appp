@extends('admin.layouts.app')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <!--begin::Container-->
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div class="d-flex align-items-center me-3">
            <!--begin::Title-->
            <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.orders')
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

<div class="card rounded mb-5 mb-xl-8 shadow-lg">
    <!--begin::Header-->
    <div class="card-header rounded border-0 pt-5">
        <h3 class="card-title align-items-start flex-column">
            <span class="card-label fw-bolder fs-3 mb-1">@lang('site.All') @lang('site.orders')</span>
        </h3>

    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-3">

        @include('admin.includes.messages')

        <!--begin::Table container-->
        <div class="table-responsive rounded">
            <!--begin::Table-->
            <table class="table table-hover align-middle gs-0 gy-4">
                <!--begin::Table head-->
                <thead>
                    <tr class="text-center border-3 fw-bolder text-muted bg-light">
                        <th class="min-w-125px">@lang('site.Order No')</th>
                        <th class="min-w-125px">@lang('site.date')</th>
                        <th class="min-w-125px">@lang('site.Customer Name')</th>
                        <th class="min-w-125px">@lang('site.Recommendations')</th>

                        <th class="min-w-125px">@lang('site.Total')</th>

                        <th class="min-w-200px text-start rounded-end">@lang('site.actions')</th>
                    </tr>
                </thead>
                <!--end::Table head-->
                <!--begin::Table body-->
                <tbody>
                @foreach($orders as $index => $c)
                    <tr class="text-center border-3 m-auto">
                         <td class="px-3">


                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->id}}</span>


                        </td>
                         <td class="px-3">
                        <span class="text-muted fw-bold text-muted d-block fs-7">{{$c->created_at}}</span>
                        </td>

                         <td class="px-3">

                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->user->name ?? ''}}</span>

                       </td>


                        <td class="px-3">

                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->services->title_en ?? ''}}</span>

                       </td>



                        <td class="px-3">

                       <span class="text-dark fw-bolder text-hover-primary mb-1 fs-6">{{$c->total}} @if($c->type == 'Coins') @lang('site.Coins') @else @lang('site.LE')@endif</span>

                       </td>




                        <td class="text-start">
                            <form action="{{ route('deleteOrder.recommendations', $c->id) }}" method="post" id='delform' style="display: inline-block">
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
{!! $orders->render() !!}

@endsection
