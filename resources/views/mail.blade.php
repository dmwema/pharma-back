<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Faculté de pharmacie</title>
</head>
<body>
    <h1>Salut, {{ $name }}</h1>
    <p>Veuillez cliquer sur le bouton ci-dessous pour vous connecter</p>  
    <form action="{{ route('login') }}" method="post">
        <input type="hidden" name="email" value="{{ $email }}">    
        <input type="hidden" name="password" value={{ $password }}>
        <button type="submit">Se connecter</button>    
    </form>  
</body>
</html>