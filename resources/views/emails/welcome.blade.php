@component('mail::message')

# SAMS
Hi {{$data['name']}}, we’re glad you’re here!
Following are your account details:
    <br>
<b>
    Name: {{$data['name']}}
<br>
    Email ID: {{$data['email']}}
<br>
    Mobile No.: {{$data['mobile']}}
<br>

Email : {{$data['email']}}
<br>
Password : {{$data['password']}}
<br></b>

Thanks,<br>
{{ config('app.name')}}
@endcomponent
