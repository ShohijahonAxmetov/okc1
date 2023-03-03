<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="assets/media/okc-shortcut.png" />
    <title>Добро пожаловать в OKC</title>
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500&display=swap");
      @font-face {
        font-family: "Optima Cyr";
        src: url("/optima-cyr.woff") format("woff");
      }
      * {
        padding: 0;
        margin: 0;
        border: 0;
      }
      *,
      :after,
      :before {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      :active,
      :focus {
        outline: none;
      }
      a:active,
      a:focus {
        outline: none;
      }
      aside,
      footer,
      header,
      nav {
        display: block;
      }
      body,
      html {
        height: 100%;
        width: 100%;
        font-size: 100%;
        line-height: 1;
        font-size: 14px;
        -ms-text-size-adjust: 100%;
        -moz-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
      }
      button,
      input,
      textarea {
        font-family: inherit;
      }
      input::-ms-clear {
        display: none;
      }
      button {
        cursor: pointer;
      }
      button::-moz-focus-inner {
        padding: 0;
        border: 0;
      }
      a,
      a:visited {
        text-decoration: none;
      }
      a:hover {
        text-decoration: none;
      }
      ul li {
        list-style: none;
      }
      img {
        vertical-align: top;
      }
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        font-size: inherit;
        font-weight: 400;
      }
      .main {
        min-height: 100vh;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
      }
      .left {
        background: url(assets/media/img.jpg) center no-repeat;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: flex-end;
        position: relative;
      }
      .left:after {
        background: linear-gradient(
            180deg,
            rgba(72, 117, 123, 0) 59.91%,
            rgba(72, 117, 123, 0.88) 100%
          ),
          url(image.png);
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
      }
      .content {
        padding-bottom: 56px;
        position: relative;
        z-index: 9;
      }
      .title {
        font-family: "Optima Cyr";
        font-style: normal;
        font-weight: 400;
        font-size: 40px;
        line-height: 48px;
        text-align: center;
        color: #ffffff;
        margin-bottom: 26px;
      }
      .img {
        text-align: center;
      }
      .right {
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .wrapper {
        border: 1px solid rgba(31, 117, 168, 0.46);
        border-radius: 4px;
        padding: 32px 40px;
      }
      .suptitle {
        font-family: "Inter";
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 32px;
        color: #111111;
        margin-bottom: 40px;
      }
      .label {
        font-family: "Inter";
        font-style: normal;
        font-weight: 400;
        font-size: 18px;
        line-height: 24px;
        color: #111111;
      }
      input {
        border: 1px solid #b7d3f9;
        border-radius: 500px;
        padding: 12px 16px;
        font-family: "Inter";
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        color: #828282;
      }
      .input__wrapper {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 24px;
      }
      .btn {
        font-family: "Inter";
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 24px;
        color: #ffffff;
        background: #f3628d;
        border-radius: 38px;
        display: block;
        width: 100%;
        padding: 13px 0;
      }
      @media screen and (max-width: 992px) {
        .title {
          font-size: 20px;
          line-height: 24px;
          margin-bottom: 1rem;
        }
        .pic {
          width: 120px;
          height: auto;
          object-fit: contain;
        }
        .main {
          grid-template-columns: repeat(1, 1fr);
        }
        .left {
          padding: 32px 1rem;
        }
        .content {
          padding-bottom: 0;
        }
        .wrapper {
          padding: 1rem;
          border: none;
        }
        .suptitle {
          font-size: 20px;
          line-height: 120%;
        }
        .label {
          font-size: 1rem;
          line-height: 120%;
        }
        .left::after {
          background: linear-gradient(
              0deg,
              rgba(72, 117, 123, 0.64),
              rgba(72, 117, 123, 0.64)
            ),
            url(image.png);
        }
      }
    </style>
  </head>
  <body>
    <main class="main">
      <div class="left">
        <div class="content">
          <h1 class="title">Добро пожаловать в панель администратора</h1>
          <div class="img">
            <img src="assets/media/brand.svg" alt="" class="pic" />
          </div>
        </div>
      </div>
      <div class="right">
        <div class="wrapper">
          <h4 class="suptitle">Вход в систему</h4>
          <form action="{{ route('auth.login') }}" method="post">
          	@csrf
            <div class="input__wrapper">
              <label for="username" class="label">Имя пользователя</label>
              <input type="text" name="username" id="username" required />
            </div>
            <div class="input__wrapper">
              <label for="password" class="label">Пароль</label>
              <input type="password" name="password" id="password" required />
            </div>
            <button class="btn">Войти</button>
          </form>
        </div>
      </div>
    </main>

    <script type="text/javascript" src="{{ asset('app/notify.js') }}"></script>
	@if(isset($_GET['success']) && $_GET['success'] == 'true' || session('success') && session('success') == true)
	<script>
		$.notify("Success!", 'success');
	</script>
	@elseif(session()->has('success') && session('success') == false)
	<script>
		$.notify("Error! Username or password incorrect", 'error');
	</script>
	@endif
  </body>
</html>
