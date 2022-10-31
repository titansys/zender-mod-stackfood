@extends('layouts.admin.app')

@section('title',translate('messages.system_module_setup'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center">
                <h1 class="page-header-title">
                    <span class="page-header-icon">
                        <img src="{{asset('public/assets/admin/img/sms.png')}}" class="w--26" alt="">
                    </span>
                    <span>
                        {{translate('messages.sms')}} {{translate('messages.gateway')}} {{translate('messages.setup')}}
                    </span>
                </h1>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row g-3">
            <div class="col-md-6">
                {{-- 
                    Zender Gateway Settings
                    Please note that this form was not localized
                 --}}
                <div class="card">
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('zender_gateway'))
                        <form class="sms-module-form" action="{{route('admin.business-settings.sms-module-update', ['zender_gateway'])}}"
                            method="post">
                            @csrf
                            <div>
                                <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                    <span>Zender</span>
                                    <div class="pl-2">
                                        <img src="{{asset('public/assets/admin/img/titansys.png')}}" alt="public">
                                    </div>
                                </h5>
                                
                                <span class="badge badge-soft-info mb-3">{{ translate('NB : #OTP# will be replace with otp') }}</span>

                                <div class="d-flex flex-wrap mb-4">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status'] == 1 ? 'checked' : false}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>
                                    </label>
                                    <label class="form-check form--check">
                                        <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status'] == 0 ? 'checked' : false}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">Site URL</label>
                                    <br>
                                    <small>The site url of your Zender. Do not add ending slash.</small>
                                    <input type="text" class="form-control" name="site_url" value=" {{$config['site_url'] ?: ""}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">API Key</label>
                                    <br>
                                    <small>Your API key. Please make sure that everything is correct and required permissions are granted: sms_send, wa_send</small>
                                    <input type="text" class="form-control" name="api_key" value="{{$config['api_key'] ?: ""}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">Sending Service</label>
                                    <br>
                                    <small>Select the sending service. Please make sure that the API key has the following permissions: sms_send, wa_send</small>
                                    <select class="form-control" name="service">
                                        <option value="1" {{$config['service'] < 2 ? "selected" : false}}>SMS</option>
                                        <option value="2" {{$config['service'] > 1 ? "selected" : false}}>WhatsApp</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">WhatsApp Account ID</label><br>
                                    <small>For WhatsApp service only. WhatsApp account ID you want to use for sending.</small>
                                    <input type="text" class="form-control" name="whatsapp" value="{{$config['whatsapp'] ?: ""}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">Device Unique ID</label><br>
                                    <small>For SMS service only. Linked device unique ID. Please only enter this field if you are sending using one of your devices.</small>
                                    <input type="text" class="form-control" name="device" value="{{$config['device'] ?: ""}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">Gateway Unique ID</label><br>
                                    <small>For SMS service only. Partner device unique ID or gateway ID. Please only enter this field if you are sending using a partner device or third party gateway.</small>
                                    <input type="text" class="form-control" name="gateway" value="{{$config['gateway'] ?: ""}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">Sim Slot</label><br>
                                    <small>For SMS service only. Select the sim slot you want to use for sending the messages. Please only enter this field if you are sending using a partner device or third party gateway. This is ignored for partner devices and third party gateways.</small>
                                    <select class="form-control" name="slot">
                                        <option value="1" {{$config['slot'] < 2 ? "selected" : false}}>SIM 1</option>
                                        <option value="2" {{$config['slot'] > 1 ? "selected" : false}}>SIM 2</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">{{translate('messages.otp_template')}}</label>
                                    <input type="text" class="form-control" name="otp_template" value="{{$config['otp_template'] ?: ""}}">
                                </div>
                            </div>

                            <div class="btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form class="sms-module-form" action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['twilio_sms']):'javascript:'}}"
                            method="post">
                            @csrf
                            <div>
                                <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                    <span>{{translate('messages.twilio_sms')}}</span>
                                    <div class="pl-2">
                                        <img src="{{asset('public/assets/admin/img/twilio.png')}}" alt="public">
                                    </div>
                                </h5>
                                <span class="badge badge-soft-info mb-3">{{ translate('NB : #OTP# will be replace with otp') }}</span>
                                <div class="d-flex flex-wrap mb-4">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>
                                    </label>
                                    <label class="form-check form--check">
                                        <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="text-capitalize form-label"
                                        >{{translate('messages.sid')}}</label>
                                    <input type="text" class="form-control" name="sid"
                                        value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}">
                                </div>
                                <div class="form-group">
                                    <label class="text-capitalize form-label"
                                        >{{translate('messages.messaging_service_id')}}</label>
                                    <input type="text" class="form-control" name="messaging_service_id"
                                        value="{{env('APP_MODE')!='demo'?$config['messaging_service_id']??"":''}}">
                                </div>
                                <div class="form-group">
                                    <label class="text-capitalize form-label">{{translate('messages.token')}}</label>
                                    <input type="text" class="form-control" name="token"
                                        value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">{{translate('messages.from')}}</label>
                                    <input type="text" class="form-control" name="from"
                                        value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                                </div>

                                <div class="form-group">
                                    <label class="text-capitalize form-label">{{translate('messages.otp_template')}}</label>
                                    <input type="text" class="form-control" name="otp_template"
                                        value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('nexmo_sms'))
                        <form class="sms-module-form" action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div>
                                <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                    <span>{{translate('messages.nexmo_sms')}}</span>
                                    <div class="pl-2">
                                        <img src="{{asset('public/assets/admin/img/nexmo.png')}}" alt="public">
                                    </div>
                                </h5>
                                <span class="badge badge-soft-info mb-3">{{ translate('messages.NB : #OTP# will be replace with otp') }}</span>
                                <div class="d-flex flex-wrap mb-4">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>

                                    </label>
                                    <label class="form-check form--check">
                                        <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-capitalize"
                                        >{{translate('messages.api_key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                        value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-capitalize">{{translate('messages.api_secret')}}</label>
                                    <input type="text" class="form-control" name="api_secret"
                                        value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label text-capitalize">{{translate('messages.from')}}</label>
                                    <input type="text" class="form-control" name="from"
                                        value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label text-capitalize">{{translate('messages.otp_template')}}</label>
                                    <input type="text" class="form-control" name="otp_template"
                                        value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form class="sms-module-form" action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['2factor_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div>
                                <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                    <span>{{translate('messages.2factor_sms')}}</span>
                                    <div class="pl-2">
                                        <img src="{{asset('public/assets/admin/img/twilio.png')}}" alt="public">
                                    </div>
                                </h5>
                                <div>
                                    <span class="badge badge-soft-info mb-1">{{ translate('EX of SMS provider`s template : your OTP is XXXX here, please check.') }}</span>
                                </div>
                                <div>
                                    <span class="badge badge-soft-info mb-3">{{ translate('messages.NB : #OTP# will be replace with otp') }}</span>
                                </div>

                                <div class="d-flex flex-wrap mb-4">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>

                                    </label>
                                    <label class="form-check form--check">
                                        <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="form-label text-capitalize"
                                        >{{translate('messages.api_key')}}</label>
                                    <input type="text" class="form-control" name="api_key"
                                        value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form class="sms-module-form" action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf
                            <div>
                                <h5 class="d-flex flex-wrap justify-content-between align-items-center text-uppercase">
                                    <span>{{translate('messages.msg91_sms')}}</span>
                                    <div class="pl-2">
                                        <img src="{{asset('public/assets/admin/img/nexmo.png')}}" alt="public">
                                    </div>
                                </h5>
                                <span class="badge badge-soft-info mb-3">{{ translate('NB : Keep an OTP variable in your SMS providers OTP Template.') }}</span>

                                <div class="d-flex flex-wrap mb-4">
                                    <label class="form-check form--check mr-2 mr-md-4">
                                        <input class="form-check-input" type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.active')}}</span>

                                    </label>
                                    <label class="form-check form--check">
                                        <input class="form-check-input" type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                        <span class="form-check-label text--title pl-2">{{translate('messages.inactive')}} </span>
                                    </label>
                                </div>


                                <div class="form-group">
                                    <label class="form-label text-capitalize"
                                        >{{translate('messages.template_id')}}</label>
                                    <input type="text" class="form-control" name="template_id"
                                        value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label text-capitalize"
                                        >{{translate('messages.authkey')}}</label>
                                    <input type="text" class="form-control" name="authkey"
                                        value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}">
                                </div>
                            </div>
                            <div class="btn--container justify-content-end">
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                class="btn btn--primary">{{translate('messages.save')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
