<?php
/** @var \App\User $user */
?>

<html>
<body>
    <script type="text/javascript">
        window.opener.linkedinAuthComplete('{{ $user->getOrCreateApiToken() }}');
        window.close();
    </script>
</body>
</html>