<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>

  <?php echo 'user: ' . $userId;
		echo 'login: ' . $login;?>
  <form action="" method="POST" enctype="application/x-www-form-urlencoded">

      <input name="url" type="hidden" value=""/>
      <div class="box">
          <div class="row">
              <div class="column"><label for="username">Имя пользователя</label></div>
              <div class="column"><input id="username" name="username" type="text"/></div>
          </div>
          <div class="row">
              <div class="column"><label for="password">Пароль</label></div>
              <div class="column"><input id="password" name="password" type="password"/></div>
          </div>
          <div class="row">
              <div class="column"><button type="submit">Вход</button></div>
          </div>
      </div>
  </form>
  </body>
</html>
