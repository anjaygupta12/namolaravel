<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Namo Traders - Login</title>

<link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

/* Hide everything initially */
body{
visibility:hidden;
background: linear-gradient(135deg,#2d2d2d,#1a1a1a);
color:#fff;
font-family:'Segoe UI',sans-serif;
}

/* Full screen loader */
#loader{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:#fff;
display:flex;
justify-content:center;
align-items:center;
z-index:999999;
visibility:visible;
}

.spinner{
border:4px solid #f3f3f3;
border-top:4px solid #007bff;
border-radius:50%;
width:60px;
height:60px;
animation:spin .8s linear infinite;
}

@keyframes spin{
100%{transform:rotate(360deg);}
}

.login-container{
max-width:400px;
margin:5% auto;
background:#2c2f33;
padding:2rem;
border-radius:15px;
display:none;
}

</style>

</head>

<body>


<!-- Loader -->
<div id="loader">
<div class="spinner"></div>
</div>



<!-- Login Form -->
<div class="login-container text-center" id="loginForm">

<img src="{{ asset('assets/img/logo.png') }}" width="120" class="mb-3">

<h4>Namo Traders</h4>

@if (session('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif

<p class="mb-4">Log in to your account</p>

<form action="{{ route('user.login') }}" method="POST">
@csrf

<div class="form-floating mb-3">
<input type="text" class="form-control" name="login" required>
<label>Username or phone</label>
</div>

<input type="hidden" id="device_id" name="device_id">

<div class="form-floating mb-3">
<input type="password" class="form-control" name="password" required>
<label>Password</label>
</div>

<button type="submit" class="btn btn-primary w-100">
Log In
</button>

</form>

</div>



<script>

function initDeviceCheck(){

const loader=document.getElementById('loader');
const login=document.getElementById('loginForm');

// keep loader visible
loader.style.display="flex";
login.style.display="none";

let deviceId=localStorage.getItem('device_id');

if(!deviceId){

deviceId='xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g,function(c){
const r=Math.random()*16|0;
const v=c==='x'?r:(r&0x3|0x8);
return v.toString(16);
});

localStorage.setItem('device_id',deviceId);

}

document.getElementById('device_id').value=deviceId;


fetch('/set-device-id?device_id='+encodeURIComponent(deviceId))
.then(res=>res.json())
.then(data=>{

if(data.redirect){

window.location.replace(data.redirect);

}else{

loader.style.display="none";
login.style.display="block";
document.body.style.visibility="visible";

}

})
.catch(()=>{

loader.style.display="none";
login.style.display="block";
document.body.style.visibility="visible";

});

}



// First Load
document.addEventListener("DOMContentLoaded",function(){
initDeviceCheck();
});



// Mobile Back Button (Very Important)
window.addEventListener("pageshow",function(event){

if(event.persisted){
initDeviceCheck();
}

});



// Android WebView Fix
window.addEventListener("focus",function(){
initDeviceCheck();
});



// App Resume Fix
document.addEventListener("visibilitychange",function(){

if(!document.hidden){
initDeviceCheck();
}

});

</script>


</body>

</html>