<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bandera animada profesional</title>
<style>
  body {
    margin: 0;
    height: 100vh;
    background: url('fondo.jpg') no-repeat center center/cover;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .bandera-container {
    width: 200px;
    height: 120px;
    position: relative;
    animation: moverHorizontal 8s ease-in-out infinite alternate;
  }

  .bandera {
    width: 100%;
    height: 100%;
    background: url('meta.png') no-repeat center/cover;
    position: relative;
    animation: ondeo 1.5s ease-in-out infinite;
    transform-origin: left center;
    filter: drop-shadow(0 0 8px rgba(0,0,0,0.3));
    mask-image: linear-gradient(to right, rgba(0,0,0,1) 70%, transparent 100%);
    -webkit-mask-image: linear-gradient(to right, rgba(0,0,0,1) 70%, transparent 100%);
  }

  /* Movimiento de lado a lado */
  @keyframes moverHorizontal {
    0% {
      transform: translateX(-40vw);
    }
    100% {
      transform: translateX(40vw);
    }
  }

  /* Ondulación tipo tela (efecto realista) */
  @keyframes ondeo {
    0% {
      transform: perspective(600px) rotateY(0deg);
    }
    25% {
      transform: perspective(600px) rotateY(6deg);
    }
    50% {
      transform: perspective(600px) rotateY(0deg);
    }
    75% {
      transform: perspective(600px) rotateY(-6deg);
    }
    100% {
      transform: perspective(600px) rotateY(0deg);
    }
  }
</style>
</head>
<body>
  <div class="bandera-container">
    <div class="bandera"></div>
  </div>
</body>
</html>
