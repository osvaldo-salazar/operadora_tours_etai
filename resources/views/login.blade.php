<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>

body{
background:#f5f6f8;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.login-box{
width:380px;
background:white;
padding:40px;
border-radius:10px;
box-shadow:0 0 15px rgba(0,0,0,0.08);
text-align:center;
}

.logo{
width:180px;
margin-bottom:25px;
}

.password-box{
position:relative;
}

.toggle-pass{
position:absolute;
right:12px;
top:10px;
font-size:20px;
cursor:pointer;
color:#6c757d;
}

.toggle-pass:hover{
color:#000;
}

/* TOAST VERDE */

.toast-success{
position:fixed;
bottom:30px;
right:30px;
background:#28a745;
color:white;
padding:12px 20px;
border-radius:8px;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
font-size:14px;
z-index:9999;
}

</style>

</head>

<body>

<div class="login-box">

<img src="{{ asset('img/etai-color-default.png') }}" class="logo">

@if(session('error'))
<div style="color:red;margin-bottom:10px;text-align:center;">
{{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('login') }}">
@csrf

<div class="mb-3">
<input 
type="text" 
name="usuario" 
class="form-control" 
placeholder="Usuario o correo electrónico"
required>
</div>

<div class="mb-3 password-box">

<input 
type="password" 
name="password" 
id="password"
class="form-control" 
placeholder="Contraseña"
required>

<i class="bi bi-eye-slash toggle-pass" onclick="togglePassword()" id="icono"></i>

</div>

<button class="btn btn-primary w-100 mb-3">
Acceder
</button>

<a href="{{ route('password.request') }}">
¿Olvidó su contraseña?
</a>

</form>

</div>


@if(session('success'))

<div id="toast" class="toast-success">
{{ session('success') }}
</div>

<script>

setTimeout(function(){
document.getElementById("toast").style.display="none";
},5000);

</script>

@endif


<script>

function togglePassword(){

let pass = document.getElementById("password");
let icon = document.getElementById("icono");

if(pass.type === "password"){

pass.type = "text";
icon.classList.remove("bi-eye-slash");
icon.classList.add("bi-eye");

}else{

pass.type = "password";
icon.classList.remove("bi-eye");
icon.classList.add("bi-eye-slash");

}

}

</script>

</body>
</html>