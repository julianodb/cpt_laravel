<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
  <!--<link rel="stylesheet" href="{% static 'compras_publicas_transparentes/jquery-ui.min.css' %}">
  <script src="{% static 'compras_publicas_transparentes/jquery.js' %}"></script>
  <script src="{% static 'compras_publicas_transparentes/jquery-ui.min.js' %}"></script>-->

  <title>{{$title}}</title>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class='col-xs-3'>
        ...
          </div>
          <div class='col-xs-9'>
            @yield('main')
          </div>
      </div>
  </div>
</body>
</html>