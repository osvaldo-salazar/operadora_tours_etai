<!DOCTYPE html>
<html lang="es">
<head>

<meta charset="UTF-8">
<title>Verificación</title>

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

.code{
font-size:24px;
text-align:center;
letter-spacing:10px;
}

</style>

</head>

<body>

<div class="box">

<img src="{{ asset('img/etai-color-default.png') }}" class="logo">

<h5 class="mb-3">Código de seguridad</h5>

<p class="text-muted">
Ingresa el código enviado a tu correo
</p>

<p class="text-muted">
El código expira en <span id="timer">05:00</span>
</p>

@if(session('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
{{ session('success') }}
</div>
@endif

<form method="POST" action="/verificar">
@csrf

<input type="text" name="codigo" class="form-control code mb-3" maxlength="6" placeholder="000000" required>

<button class="btn btn-primary w-100 mb-2">
Verificar
</button>

</form>

<form method="POST" action="/reenviar-codigo">
@csrf

<button class="btn btn-link">
Reenviar código
</button>

</form>

<a href="/login">Volver al login</a>

</div>

<script>

let tiempo = 300;

let contador = setInterval(function(){

let minutos = Math.floor(tiempo / 60);
let segundos = tiempo % 60;

if(segundos < 10){
segundos = "0" + segundos;
}

document.getElementById("timer").innerHTML = minutos + ":" + segundos;

tiempo--;

if(tiempo < 0){
clearInterval(contador);

document.getElementById("timer").innerHTML = "Expirado";

}

},1000);

</script>
</body>
</html>