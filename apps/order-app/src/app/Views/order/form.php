<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  </head>
  <body>

  <form action="" method="POST" enctype="application/x-www-form-urlencoded">
      <input name="order" id="order" type="hidden" value=""/>
      <input name="user" id="user" type="hidden" value="<?=$userId;?>"/>
      <div class="box">
          <div class="row">
              <div class="column"><label for="price">Price</label></div>
              <div class="column"><input id="price" name="price" type="number"/></div>
          </div>
          <div class="row">
              <div class="column"><button type="submit">Create order</button></div>
          </div>
      </div>
  </form>
  </body>
  <script>
      let orderId = 'order';
      const generateRandomString = (length) => {
                                                 let result = '';
                                                 const characters =
                                                 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
                                                 const charactersLength = characters.length;
                                                 for (let i = 0; i < length; i++) {
          result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
      };
      document.getElementById(orderId).value= generateRandomString(30);
  </script>
</html>
