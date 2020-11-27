<?php
if (!isset($_COOKIE['USER_ID'])) {
    header('Location: /representacoes/login');
}
?>

<script type="application/javascript" src="/representacoes/static/lib/fancybox/jquery.fancybox.min.js"></script>
<script type="application/javascript" src="/representacoes/static/lib/jquery-mask-plugin/dist/jquery.mask.min.js"></script>
<script type="application/javascript" src="/representacoes/static/lib/bootbox/bootbox.min.js"></script>
<script type="application/javascript" src="/representacoes/static/js/pedido/frete/novo.js"></script>
<script type="application/javascript" src="/representacoes/static/js/pedido/frete/novo_add_item.js"></script>