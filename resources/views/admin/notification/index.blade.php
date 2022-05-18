@extends('admin.layouts.app')


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


@section('toolbar')
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div class="d-flex align-items-center me-3">
                <!--begin::Title-->
                <h1 class="d-flex align-items-center text-dark fw-bolder my-1 fs-3">@lang('site.notification')
                <!--begin::Separator-->
                    <!--end::Separator-->
                    <!--begin::Description-->
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
            <span class="card-label fw-bolder fs-3 mb-1">@lang('site.All') @lang('site.notification')</span>
        </h3>

    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body py-3">

        @include('admin.includes.messages')

        @foreach($notifications as $n)
        <div class="border @if($n->read_at == null) bg-light @endif shadow-xs mb-5 p-5 rounded">
            {{$n->data['details']['user']['name']}}: {{$n->data['details']['data']['title']}}<br>
            @if($n->data['details']['data']['type'] != 'general')
                {{$n->data['details']['data']['body']}}
                {{--                                        {{ var_dump($n->data['details']['data']) }}--}}
            @else
                {{$n->data['details']['data']['body']}}
            @endif
            <br>
            <small><strong>{{__('site.created at')}}: </strong> {{date( 'd/m/Y h:i A',  strtotime($n->created_at)) }}</small>
        </div>
        @endforeach
        {!! $notifications->render() !!}

    </div>
    <!--begin::Body-->
</div>
<!--end::Tables Widget 11-->
@endsection
