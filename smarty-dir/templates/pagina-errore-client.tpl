<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Titolo tab del browser -->
    <title>UniChat - Errore</title>

    <!-- Custom fonts -->
    <link href="/UniChat/Template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

    <!-- Custom styles -->
    <link href="/UniChat/Template/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Inizio del Page Wrapper -->
<div id="wrapper" class="mt-5">

    <!-- Inizio del contenuto della pagina -->
    <div class="container-fluid">

        <!-- Inizio messaggio di errore -->
        <div class="text-center">
            <p class="lead text-gray-800 mb-5">{$messaggioErrore}</p>
        </div>
        <!-- Fine messaggio di errore -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; UniChat 2021</span>
                </div>
            </div>
        </footer>
        <!-- Fine del Footer -->

    </div>
    <!-- Fine del contenuto della pagina -->

</div>
<!-- Fine del Page Wrapper -->
<script type="text/javascript">
    if (navigator.cookieEnabled === false) {
        if (window.location.pathname !== "/UniChat/client/cookieDisabilitati") {
            window.location.replace('/UniChat/client/cookieDisabilitati');
        }
    } else {
        window.location.replace('/UniChat');
    }
</script>
</body>

</html>