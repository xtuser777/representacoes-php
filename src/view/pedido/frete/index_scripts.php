<?php
if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
}
?>

<script type="application/javascript" src="/representacoes/static/lib/bootbox/bootbox.min.js"></script>
<script type="application/javascript" src="/representacoes/static/js/pedido/frete/index.js"></script>
