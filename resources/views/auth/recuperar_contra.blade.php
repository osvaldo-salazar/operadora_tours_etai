<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Recuperar contraseña</title>

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

<div class="box">

<img src="{{ asset('img/etai-color-default.png') }}" class="logo">

<h5 class="mb-3">Recuperar contraseña</h5>

@if(session('error'))
<div style="color:red;margin-bottom:10px;text-align:center;">
{{ session('error') }}
</div>
@endif

<form method="POST" action="/recuperar-contra">

@csrf

<input type="email" name="email" class="form-control mb-3" placeholder="Correo registrado" required>

<button class="btn btn-primary w-100">
Enviar enlace
</button>

</form>

<a href="/login" class="d-block mt-3">
Volver al login
</a>

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

</body>

</html>