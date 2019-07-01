@if(session('flash_message'))
    <div id="flash_message" style="padding: 20px 30px; background: {{ session('success') ? 'green' : 'red' }}; z-index: 999999; font-size: 16px; font-weight: 600;">
        <a class="pull-right" onclick="el=document.getElementById('flash_message');el.parentNode.removeChild(el);" href="#" data-toggle="tooltip" data-placement="left" title="Never show me this again!" style="color: rgb(255, 255, 255); font-size: 20px;">×</a>
        <span style="color: rgba(255, 255, 255, 0.9); display: inline-block; margin-right: 10px; text-decoration: none;">
            {{ session()->get('flash_message') }}
        </span>
    </div>
@endif
@if($errors->any())
    <div id="flash_message" style="padding: 20px 30px; background: red; z-index: 999999; font-size: 16px; font-weight: 600;">
        <a class="pull-right" onclick="el=document.getElementById('flash_message');el.parentNode.removeChild(el);" href="#" data-toggle="tooltip" data-placement="left" title="Never show me this again!" style="color: rgb(255, 255, 255); font-size: 20px;">×</a>
        <span style="color: rgba(255, 255, 255, 0.9); display: inline-block; margin-right: 10px; text-decoration: none;">
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        </span>
    </div>
@endif