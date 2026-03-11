<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Nueva contraseña</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background:#f5f6f8;
height:100vh;
display:flex;
justify-content:center;
align-items:center;
}

.box{
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

/* Toast estilo notificación */

.toast-success{
position:fixed;
bottom:30px;
right:-400px;
background:#28a745;
color:white;
padding:15px 25px;
border-radius:8px;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
font-size:14px;
transition:all .5s ease;
z-index:9999;
}

.toast-success.show{
right:30px;
}

</style>

</head>

<body>

<div class="box">

<img src="{{ asset('img/etai-color-default.png') }}" class="logo">

<h5 class="mb-3">Nueva contraseña</h5>

<form method="POST" action="/reset-password">

@csrf

<input type="hidden" name="token" value="{{ $token }}">

<input 
type="password" 
name="password" 
placeholder="Nueva contraseña" 
class="form-control mb-3" 
required>

<input 
type="password" 
name="password_confirmation" 
placeholder="Confirmar contraseña" 
class="form-control mb-3" 
required>

<button class="btn btn-primary w-100">
Guardar contraseña
</button>

</form>

</div>

@if(session('success'))

<div id="toast" class="toast-success">
{{ session('success') }}
</div>

<script>

let toast = document.getElementById("toast");

setTimeout(()=>{
toast.classList.add("show");
},200);

setTimeout(()=>{
window.location.href="/login";
},2000);

</script>

@endif

</body>
</html>