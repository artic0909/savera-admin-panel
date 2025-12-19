<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Savera</title>
</head>

<body>
    <img class="desktop" src="{{ asset('assets/commingsoon-desktop.webp') }}" alt="Coming Soon">
    <img class="mobile" src="{{ asset('assets/commingsoon-mobile.webp') }}" alt="Coming Soon">
</body>
<style>
    html,
    body {
        margin: 0;
        padding: 0;
        width: 100%;
    }

    body {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    img {
        width: 100vw;
        height: auto;
        object-fit: cover;
    }

    .mobile {
        display: none;
    }

    @media (max-width: 768px) {

        html,
        body {

            height: 100%;
        }

        img {
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            overflow: hidden;
        }

        .desktop {
            display: none;
        }

        .mobile {
            display: block;
        }
    }
</style>

</html>
